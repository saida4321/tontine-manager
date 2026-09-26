<?php

namespace App\Livewire\Admin;

use App\Models\Retrait;
use App\Models\Client;
use App\Models\Cabinet;
use App\Models\Parametre;
use App\Models\HistoriqueTransaction;
use App\Types\Etat;
use App\Helpers\ActivityLogger;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RetraitManager extends Component
{
    public $retraits;
    public $clients;
    public $cabinets;
    public $search = '';
    public $filterStatut = '';
    public $filterCabinet = '';
    public $searchClientModal = '';
    public $modalOpen = false;
    public $modalMode = 'create';
    public $form = [
        'id' => null,
        'client_id' => '',
        'cabinet_id' => '',
        'montant' => '',
        'motif' => '',
        'date_demande' => '',
        'type_paiement' => 'especes',
        'telephone_mobile' => '',
        'statut' => 'en_attente',
    ];
    public $message = '';
    public $messageType = '';
    public $soldeClient = 0;

    // ✅ AJOUTÉ: Récupérer le montant min retrait depuis les paramètres
    public function getMontantMinRetraitProperty()
    {
        $params = Parametre::first();
        return $params->montant_min_retrait ?? 100;
    }

    protected function rules()
    {
        // ✅ MODIFIÉ: Utilise le montant min des paramètres
        $montantMin = $this->montantMinRetrait;

        $rules = [
            'form.client_id' => 'required|exists:clients,id',
            'form.cabinet_id' => 'required|exists:cabinets,id',
            'form.montant' => [
                'required',
                'numeric',
                "min:{$montantMin}", // ✅ DYNAMIQUE
                function ($attribute, $value, $fail) {
                    if ($this->form['client_id']) {
                        $client = Client::find($this->form['client_id']);
                        if ($value > $client->solde) {
                            $fail("Le montant dépasse le solde disponible (" . number_format($client->solde, 0, ',', ' ') . " FCFA)");
                        }
                    }
                },
            ],
            'form.motif' => 'nullable|string|max:255',
            'form.date_demande' => 'required|date',
            'form.type_paiement' => 'required|in:especes,mobile_money',
        ];

        if ($this->form['type_paiement'] === 'mobile_money') {
            $rules['form.telephone_mobile'] = 'required|string';
        }

        return $rules;
    }

    protected function messages()
    {
        // ✅ MODIFIÉ: Message dynamique
        $montantMin = $this->montantMinRetrait;

        return [
            'form.client_id.required' => 'Le client est obligatoire.',
            'form.cabinet_id.required' => 'Le cabinet est obligatoire.',
            'form.montant.required' => 'Le montant est obligatoire.',
            'form.montant.min' => "Le montant minimum est de {$montantMin} FCFA.", // ✅ DYNAMIQUE
            'form.date_demande.required' => 'La date est obligatoire.',
            'form.telephone_mobile.required' => 'Le numéro Mobile Money est obligatoire.',
        ];
    }

    public function mount()
    {
        $this->loadRetraits();
        $this->loadClients();
        $this->loadCabinets();
    }

    public function loadRetraits()
    {
        $this->retraits = Retrait::with(['client', 'cabinet', 'approbateur', 'executeur'])
            ->where('etat', '!=', Etat::SUPPRIME)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function loadClients()
    {
        $this->clients = Client::where('etat', Etat::ACTIF)
            ->orderBy('prenom')
            ->orderBy('nom')
            ->get();

        Log::info('Clients chargés pour retraits: ' . $this->clients->count());
    }

    public function loadCabinets()
    {
        $this->cabinets = Cabinet::where('etat', Etat::ACTIF)->orderBy('nom')->get();
    }

    public function updatedFormClientId($value)
    {
        if ($value) {
            $client = Client::find($value);
            $this->soldeClient = $client ? $client->solde : 0;
        } else {
            $this->soldeClient = 0;
        }
    }

    public $searchClient = '';

    public function selectClient($clientId)
    {
        $this->form['client_id'] = $clientId;
        $this->updatedFormClientId($clientId);
        $this->searchClient = '';
    }

    public function getFilteredRetraitsProperty()
    {
        $retraits = $this->retraits;

        if (!empty($this->filterStatut)) {
            $retraits = $retraits->where('statut', $this->filterStatut);
        }

        if (!empty($this->filterCabinet)) {
            $retraits = $retraits->where('cabinet_id', $this->filterCabinet);
        }

        if (!empty($this->search)) {
            $searchTerm = strtolower($this->search);
            $retraits = $retraits->filter(function ($retrait) use ($searchTerm) {
                return str_contains(strtolower($retrait->client->nom ?? ''), $searchTerm) ||
                    str_contains(strtolower($retrait->client->prenom ?? ''), $searchTerm) ||
                    str_contains(strtolower($retrait->reference ?? ''), $searchTerm);
            });
        }

        return $retraits;
    }

    public function openModal($mode, $retraitId = null)
    {
        $this->modalMode = $mode;
        $this->resetErrorBag();

        if ($mode === 'edit' && $retraitId) {
            $retrait = Retrait::find($retraitId);
            $this->form = [
                'id' => $retrait->id,
                'client_id' => $retrait->client_id,
                'cabinet_id' => $retrait->cabinet_id,
                'montant' => $retrait->montant,
                'motif' => $retrait->motif,
                'date_demande' => $retrait->date_demande,
                'type_paiement' => $retrait->type_paiement ?? 'especes',
                'telephone_mobile' => $retrait->telephone_mobile ?? '',
                'statut' => $retrait->statut,
            ];
            $this->updatedFormClientId($retrait->client_id);
        } else {
            $this->resetForm();
            $this->form['date_demande'] = Carbon::today()->format('Y-m-d');
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
            'client_id' => '',
            'cabinet_id' => '',
            'montant' => '',
            'motif' => '',
            'date_demande' => Carbon::today()->format('Y-m-d'),
            'type_paiement' => 'especes',
            'telephone_mobile' => '',
            'statut' => 'en_attente',
        ];
        $this->soldeClient = 0;
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $client = Client::find($this->form['client_id']);

            if ($this->modalMode === 'create') {
                $reference = $this->generateReference();

                $dataToCreate = [
                    'client_id' => $this->form['client_id'],
                    'cabinet_id' => $this->form['cabinet_id'],
                    'montant' => $this->form['montant'],
                    'motif' => $this->form['motif'],
                    'date_demande' => $this->form['date_demande'],
                    'type_paiement' => $this->form['type_paiement'] ?? 'especes',
                    'reference' => $reference,
                    'statut' => 'en_attente',
                    'etat' => Etat::ACTIF,
                ];

                if ($this->form['type_paiement'] === 'mobile_money') {
                    $dataToCreate['telephone_mobile'] = $this->form['telephone_mobile'];
                }

                $retrait = Retrait::create($dataToCreate);

                ActivityLogger::log(
                    'retrait.created',
                    "Demande de retrait de {$this->form['montant']} FCFA pour {$client->prenom} {$client->nom}",
                    Retrait::class,
                    $retrait->id
                );

                $this->showMessage("Demande de retrait enregistrée ! Référence : {$reference}", 'success');
            } elseif ($this->modalMode === 'edit') {
                $retrait = Retrait::find($this->form['id']);

                if ($retrait->statut !== 'en_attente') {
                    $this->showMessage('Seuls les retraits en attente peuvent être modifiés.', 'error');
                    DB::rollBack();
                    return;
                }

                $dataToUpdate = [
                    'client_id' => $this->form['client_id'],
                    'cabinet_id' => $this->form['cabinet_id'],
                    'montant' => $this->form['montant'],
                    'motif' => $this->form['motif'],
                    'date_demande' => $this->form['date_demande'],
                    'type_paiement' => $this->form['type_paiement'] ?? 'especes',
                ];

                if ($this->form['type_paiement'] === 'mobile_money') {
                    $dataToUpdate['telephone_mobile'] = $this->form['telephone_mobile'];
                } else {
                    $dataToUpdate['telephone_mobile'] = null;
                }

                $retrait->update($dataToUpdate);

                ActivityLogger::log(
                    'retrait.updated',
                    "Modification du retrait {$retrait->reference}",
                    Retrait::class,
                    $retrait->id
                );

                $this->showMessage('Retrait modifié avec succès !', 'success');
            }

            DB::commit();
            $this->loadRetraits();
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showMessage('Erreur : ' . $e->getMessage(), 'error');
        }
    }

    public function approuver($retraitId)
    {
        try {
            DB::beginTransaction();

            $retrait = Retrait::find($retraitId);
            $client = $retrait->client;

            if ($retrait->montant > $client->solde) {
                $this->showMessage('Solde insuffisant pour approuver ce retrait.', 'error');
                return;
            }

            $retrait->update([
                'statut' => 'approuvé',
                'approuvé_par' => Auth::id(),
                'date_approbation' => now(),
            ]);

            ActivityLogger::log(
                'retrait.approved',
                "Retrait approuvé : {$retrait->montant} FCFA pour {$client->prenom} {$client->nom}",
                Retrait::class,
                $retrait->id
            );

            DB::commit();
            $this->loadRetraits();
            $this->showMessage('Retrait approuvé avec succès !', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showMessage('Erreur.', 'error');
        }
    }

    public function effectuer($retraitId)
    {
        if (!in_array(Auth::user()->role, [1, 2])) {
            $this->showMessage('❌ Vous n\'avez pas la permission d\'effectuer des retraits.', 'error');
            return;
        }

        try {
            DB::beginTransaction();

            $retrait = Retrait::find($retraitId);
            $client = $retrait->client;

            if ($retrait->statut !== 'approuvé') {
                $this->showMessage('Ce retrait doit d\'abord être approuvé.', 'error');
                return;
            }

            if ($retrait->montant > $client->solde) {
                $this->showMessage('Solde insuffisant.', 'error');
                return;
            }

            $retrait->update([
                'statut' => 'effectué',
                'effectué_par' => Auth::id(),
                'date_retrait' => now(),
            ]);

            $client->decrement('solde', $retrait->montant);

            ActivityLogger::log(
                'retrait.completed',
                "Retrait effectué : {$retrait->montant} FCFA pour {$client->prenom} {$client->nom}",
                Retrait::class,
                $retrait->id
            );

            HistoriqueTransaction::create([
                'type' => 'retrait',
                'client_id' => $retrait->client_id,
                'cabinet_id' => $retrait->cabinet_id,
                'montant' => $retrait->montant,
                'date_operation' => now(),
                'effectué_par' => Auth::id(),
                'reference' => $retrait->reference,
                'description' => "Retrait effectué" . ($retrait->motif ? " - {$retrait->motif}" : ""),
            ]);

            // 🔥 ENVOI D'EMAIL AUTOMATIQUE
            $this->envoyerEmailRetrait($retrait, $client);

            DB::commit();
            $this->loadRetraits();
            $this->showMessage('Retrait effectué avec succès ! Solde client mis à jour. Email envoyé.', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showMessage('Erreur.', 'error');
        }
    }

    // 🔥 NOUVELLE MÉTHODE : Envoi d'email pour retrait
    private function envoyerEmailRetrait($retrait, $client)
    {
        try {
            $nomEntreprise = param_entreprise('nom') ?? 'Tontine Manager';
            $emailEntreprise = param_entreprise('email') ?? config('mail.from.address');

            // Si le client n'a pas d'email, on ne peut pas envoyer
            if (empty($client->email)) {
                return;
            }

            $message = "Bonjour {$client->prenom} {$client->nom},\n\n";
            $message .= "Votre retrait a été effectué avec succès !\n\n";
            $message .= "📋 Détails de la transaction :\n";
            $message .= "• Référence : {$retrait->reference}\n";
            $message .= "• Montant retiré : " . number_format($retrait->montant, 0, ',', ' ') . " FCFA\n";
            $message .= "• Date : " . Carbon::parse($retrait->date_retrait)->format('d/m/Y à H:i') . "\n";
            if ($retrait->motif) {
                $message .= "• Motif : {$retrait->motif}\n";
            }
            $message .= "• Nouveau solde : " . number_format($client->solde, 0, ',', ' ') . " FCFA\n\n";
            $message .= "Merci pour votre confiance !\n\n";
            $message .= "Cordialement,\n";
            $message .= "L'équipe {$nomEntreprise}";

            Mail::raw($message, function ($mail) use ($client, $nomEntreprise, $emailEntreprise) {
                $mail->from($emailEntreprise, $nomEntreprise)
                    ->to($client->email)
                    ->subject("💸 Retrait effectué - {$nomEntreprise}");
            });
        } catch (\Exception $e) {
            // Si l'email échoue, on ne bloque pas le retrait
            Log::error("Erreur envoi email retrait : " . $e->getMessage());
        }
    }

    public function rejeter($retraitId)
    {
        try {
            $retrait = Retrait::find($retraitId);

            $retrait->update([
                'statut' => 'rejeté',
                'approuvé_par' => Auth::id(),
                'date_approbation' => now(),
            ]);

            ActivityLogger::log(
                'retrait.rejected',
                "Retrait rejeté : {$retrait->reference}",
                Retrait::class,
                $retrait->id
            );

            $this->loadRetraits();
            $this->showMessage('Retrait rejeté.', 'success');
        } catch (\Exception $e) {
            $this->showMessage('Erreur.', 'error');
        }
    }

    private function generateReference(): string
    {
        $date = Carbon::now()->format('Ymd');
        $lastRetrait = Retrait::whereDate('created_at', Carbon::today())->latest('id')->first();
        $sequence = $lastRetrait ? (intval(substr($lastRetrait->reference, -5)) + 1) : 1;
        return 'RET-' . $date . '-' . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }

    public function showMessage($msg, $type)
    {
        $this->message = $msg;
        $this->messageType = $type;
    }

    public function render()
    {
        return view('livewire.admin.retrait-manager', [
            'filteredRetraits' => $this->getFilteredRetraitsProperty(),
            'montantMinRetrait' => $this->montantMinRetrait, // ✅ PASSÉ À LA VUE
        ]);
    }
}
