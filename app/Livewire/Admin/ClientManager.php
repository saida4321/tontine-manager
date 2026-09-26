<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use App\Models\Cotisation;
use App\Models\Retrait;
use App\Models\Cabinet;
use App\Types\Etat;
use App\Helpers\ActivityLogger;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class ClientManager extends Component
{
    public $clients;
    public $cabinets;
    public $search = '';
    public $filterCabinet = '';
    public $filterEtat = '';
    public $modalOpen = false;
    public $modalMode = 'create';
    public $form = [
        'id' => null,
        'identifiant_unique' => '',
        'nom' => '',
        'prenom' => '',
        'indicatif' => '+228',
        'numero_local' => '',
        'telephone' => '',
        'email' => '',
        'adresse' => '',
        'cabinet_id' => '',
        'date_inscription' => '',
        'etat' => Etat::ACTIF,
    ];
    public $message = '';
    public $messageType = '';

    public $detailsModalOpen = false;
    public $selectedClient = null;

    /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Cotisation[] */
    public $clientCotisations = [];

    /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Retrait[] */
    public $clientRetraits = [];

    public $clientStats = [
        'total_cotisations' => 0,
        'total_retraits' => 0,
        'nombre_cotisations' => 0,
        'nombre_retraits' => 0,
        'solde_actuel' => 0,
    ];

    protected function rules()
    {
        return [
            'form.nom' => 'required|string|max:100',
            'form.prenom' => 'required|string|max:100',
            'form.telephone' => 'required|string|max:20|unique:clients,telephone,' . ($this->form['id'] ?? 'NULL'),
            'form.email' => 'nullable|email|max:150|unique:clients,email,' . ($this->form['id'] ?? 'NULL'),
            'form.adresse' => 'nullable|string',
            'form.cabinet_id' => 'required|exists:cabinets,id',
            'form.date_inscription' => 'required|date',
        ];
    }

    protected $messages = [
        'form.nom.required' => 'Le nom est obligatoire.',
        'form.prenom.required' => 'Le prénom est obligatoire.',
        'form.telephone.required' => 'Le téléphone est obligatoire.',
        'form.telephone.unique' => 'Ce téléphone existe déjà.',
        'form.email.unique' => 'Cet email existe déjà.',
        'form.cabinet_id.required' => 'Le cabinet est obligatoire.',
        'form.date_inscription.required' => 'La date d\'inscription est obligatoire.',
    ];

    public function mount()
    {
        $this->loadClients();
        $this->loadCabinets();
    }

    public function loadClients()
    {
        $this->clients = Client::with('cabinet')
            ->whereIn('etat', [Etat::ACTIF, Etat::INACTIF, Etat::SUSPENDU])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function loadCabinets()
    {
        $this->cabinets = Cabinet::where('etat', Etat::ACTIF)->orderBy('nom')->get();
    }
    public function getFilteredClientsProperty()
    {
        $clients = $this->clients;

        if (!empty($this->filterCabinet)) {
            $clients = $clients->where('cabinet_id', $this->filterCabinet);
        }

        if (!empty($this->filterEtat)) {
            $clients = $clients->where('etat', $this->filterEtat);
        }

        if (!empty($this->search)) {
            $searchTerm = strtolower($this->search);
            $clients = $clients->filter(function ($client) use ($searchTerm) {
                return str_contains(strtolower($client->nom), $searchTerm) ||
                    str_contains(strtolower($client->prenom), $searchTerm) ||
                    str_contains(strtolower($client->identifiant_unique), $searchTerm) ||
                    str_contains(strtolower($client->telephone), $searchTerm) ||
                    str_contains(strtolower($client->email ?? ''), $searchTerm);
            });
        }

        return $clients;
    }

    public function openModal($mode, $clientId = null)
    {
        $this->modalMode = $mode;
        $this->resetErrorBag();

        if ($mode === 'edit' && $clientId) {
            $client = Client::find($clientId);
            $this->form = [
                'id' => $client->id,
                'identifiant_unique' => $client->identifiant_unique,
                'nom' => $client->nom,
                'prenom' => $client->prenom,
                'telephone' => $client->telephone,
                'email' => $client->email,
                'adresse' => $client->adresse,
                'cabinet_id' => $client->cabinet_id,
                'date_inscription' => $client->date_inscription,
                'etat' => $client->etat,
            ];
        } else {
            $this->resetForm();
            $this->form['date_inscription'] = Carbon::today()->format('Y-m-d');
        }

        $this->modalOpen = true;
        $this->dispatch('modalOpened');
    }

    public function closeModal()
    {
        $this->dispatch('modalClosed');
        $this->modalOpen = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'identifiant_unique' => '',
            'nom' => '',
            'prenom' => '',
            'indicatif' => '+228',
            'numero_local' => '',
            'telephone' => '',
            'email' => '',
            'adresse' => '',
            'cabinet_id' => '',
            'date_inscription' => Carbon::today()->format('Y-m-d'),
            'etat' => Etat::ACTIF,
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            if ($this->modalMode === 'create') {
                $identifiant = $this->generateIdentifiant();
                //$this->form['telephone'] = $this->form['indicatif'] . $this->form['numero_local'];

                // Créer le compte utilisateur
                $password = Str::random(8);
                $user = User::create([
                    'nom' => $this->form['prenom'] . ' ' . $this->form['nom'],
                    'email' => $this->form['email'] ?: $identifiant . '@client.tontine.local',
                    'telephone' => $this->form['telephone'],
                    'password' => Hash::make($password),
                    'role' => 5,
                    'etat' => Etat::ACTIF,
                ]);

                // Créer le client
                $client = Client::create([
                    'user_id' => $user->id,
                    'identifiant_unique' => $identifiant,
                    'nom' => $this->form['nom'],
                    'prenom' => $this->form['prenom'],
                    'telephone' => $this->form['telephone'],
                    'email' => $this->form['email'],
                    'adresse' => $this->form['adresse'],
                    'cabinet_id' => $this->form['cabinet_id'],
                    'date_inscription' => $this->form['date_inscription'],
                    'solde' => 0.00,
                    'etat' => $this->form['etat'],
                ]);

                ActivityLogger::log(
                    'client.created',
                    "Client '{$client->prenom} {$client->nom}' créé avec compte utilisateur ({$identifiant})",
                    Client::class,
                    $client->id
                );

                $this->showMessage("Client créé ! Identifiant: {$identifiant} | Mot de passe: {$password}", 'success');
            } else {
                // ✅ MODIFICATION (était en dehors du try avant)
                $client = Client::find($this->form['id']);
                $oldValues = $client->only(['nom', 'prenom', 'telephone', 'email', 'cabinet_id', 'etat']);

                $client->update([
                    'nom' => $this->form['nom'],
                    'prenom' => $this->form['prenom'],
                    'telephone' => $this->form['telephone'],
                    'email' => $this->form['email'],
                    'adresse' => $this->form['adresse'],
                    'cabinet_id' => $this->form['cabinet_id'],
                    'date_inscription' => $this->form['date_inscription'],
                    'etat' => $this->form['etat'],
                ]);

                // ✅ Mettre à jour aussi le compte utilisateur associé
                if ($client->user_id) {
                    $client->user->update([
                        'nom' => $this->form['prenom'] . ' ' . $this->form['nom'],
                        'email' => $this->form['email'],
                        'telephone' => $this->form['telephone'],
                    ]);
                }

                ActivityLogger::log(
                    'client.updated',
                    "Client '{$client->prenom} {$client->nom}' modifié",
                    Client::class,
                    $client->id,
                    $oldValues,
                    $client->only(['nom', 'prenom', 'telephone', 'email', 'cabinet_id', 'etat'])
                );

                $this->showMessage('Client modifié avec succès !', 'success');
            }

            DB::commit();
            $this->loadClients();
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showMessage('Erreur : ' . $e->getMessage(), 'error');
        }
    }

    public function changeEtat($clientId, $newEtat)
    {
        try {
            $client = Client::find($clientId);
            $oldEtat = $client->etat;
            $client->update(['etat' => $newEtat]);

            ActivityLogger::log(
                'client.etat_changed',
                "État de '{$client->prenom} {$client->nom}' changé de '{$oldEtat}' à '{$newEtat}'",
                Client::class,
                $client->id
            );

            $this->loadClients();

            if ($this->detailsModalOpen && $this->selectedClient && $this->selectedClient->id == $clientId) {
                $this->openDetailsModal($clientId);
            }

            $this->showMessage("Client " . ucfirst($newEtat) . " !", 'success');
        } catch (\Exception $e) {
            $this->showMessage('Erreur.', 'error');
        }
    }

    private function generateIdentifiant(): string
    {
        $date = Carbon::now()->format('Ymd');
        $lastClient = Client::whereDate('created_at', Carbon::today())->latest('id')->first();
        $sequence = $lastClient ? (intval(substr($lastClient->identifiant_unique, -5)) + 1) : 1;
        return 'CLI-' . $date . '-' . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }

    public function showMessage($msg, $type)
    {
        $this->message = $msg;
        $this->messageType = $type;
    }

    /**
     * @return void
     */
    public function openDetailsModal($clientId)
    {
        /** @var Client $selectedClient */
        $this->selectedClient = Client::with(['cabinet', 'cotisations', 'retraits'])->find($clientId);

        if ($this->selectedClient) {
            /** @var \Illuminate\Database\Eloquent\Collection $cotisations */
            $cotisations = $this->selectedClient->cotisations()
                ->where('statut', 'validé')
                ->orderBy('date_cotisation', 'desc')
                ->get();

            /** @var \Illuminate\Database\Eloquent\Collection $retraits */
            $retraits = $this->selectedClient->retraits()
                ->where('statut', 'effectué')
                ->orderBy('date_retrait', 'desc')
                ->get();

            $this->clientCotisations = $cotisations;
            $this->clientRetraits = $retraits;

            $this->clientStats = [
                'total_cotisations' => $cotisations->sum('montant'),
                'total_retraits' => $retraits->sum('montant'),
                'nombre_cotisations' => $cotisations->count(),
                'nombre_retraits' => $retraits->count(),
                'solde_actuel' => $this->selectedClient->solde,
            ];
        }

        $this->detailsModalOpen = true;
    }

    public function closeDetailsModal()
    {
        $this->detailsModalOpen = false;
        $this->selectedClient = null;
        $this->clientCotisations = [];
        $this->clientRetraits = [];
        $this->clientStats = [];
    }

    public function render()
    {
        return view('livewire.admin.client-manager', [
            'filteredClients' => $this->getFilteredClientsProperty()
        ]);
    }
}
