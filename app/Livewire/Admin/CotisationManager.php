<?php

namespace App\Livewire\Admin;

use App\Models\Cotisation;
use App\Models\Client;
use App\Models\Cabinet;
use App\Models\User;
use App\Models\Parametre;
use App\Types\Etat;
use App\Types\Role;
use App\Helpers\ActivityLogger;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CotisationManager extends Component
{
    public $cotisations;
    public $clients;
    public $cabinets;
    public $search = '';
    public $filterStatut = '';
    public $filterCabinet = '';
    public $modalOpen = false;
    public $modalMode = 'create';
    public $form = [
        'id' => null,
        'client_id' => '',
        'cabinet_id' => '',
        'montant' => '',
        'date_cotisation' => '',
        'type_paiement' => 'especes',
        'telephone_mobile' => '',
        'collecteur_id' => null,
        'statut' => 'en_attente',
    ];
    public $message = '';
    public $messageType = '';

    // ✅ AJOUTÉ: Récupérer le montant min depuis les paramètres
    public function getMontantMinCotisationProperty()
    {
        $params = Parametre::first();
        return $params->montant_min_cotisation ?? 100;
    }

    protected function rules()
    {
        // ✅ MODIFIÉ: Utilise le montant min des paramètres
        $montantMin = $this->montantMinCotisation;

        $rules = [
            'form.client_id' => 'required|exists:clients,id',
            'form.cabinet_id' => 'required|exists:cabinets,id',
            'form.montant' => "required|numeric|min:{$montantMin}", // ✅ DYNAMIQUE
            'form.date_cotisation' => 'required|date',
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
        $montantMin = $this->montantMinCotisation;

        return [
            'form.client_id.required' => 'Le client est obligatoire.',
            'form.cabinet_id.required' => 'Le cabinet est obligatoire.',
            'form.montant.required' => 'Le montant est obligatoire.',
            'form.montant.min' => "Le montant minimum est de {$montantMin} FCFA.", // ✅ DYNAMIQUE
            'form.date_cotisation.required' => 'La date est obligatoire.',
            'form.telephone_mobile.required' => 'Le numéro Mobile Money est obligatoire.',
        ];
    }

    // ... (reste du code inchangé)

    public function mount()
    {
        $this->loadCotisations();
        $this->loadClients();
        $this->loadCabinets();
    }

    public function loadCotisations()
    {
        $this->cotisations = Cotisation::with(['client', 'cabinet', 'collecteur', 'validePar'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function loadClients()
    {
        $this->clients = Client::where('etat', Etat::ACTIF)
            ->orderBy('prenom')
            ->orderBy('nom')
            ->get();
    }

    public function loadCabinets()
    {
        $this->cabinets = Cabinet::where('etat', Etat::ACTIF)->orderBy('nom')->get();
    }

    public function getFilteredCotisationsProperty()
    {
        $cotisations = $this->cotisations;

        if (!empty($this->filterStatut)) {
            $cotisations = $cotisations->where('statut', $this->filterStatut);
        }

        if (!empty($this->filterCabinet)) {
            $cotisations = $cotisations->where('cabinet_id', $this->filterCabinet);
        }

        if (!empty($this->search)) {
            $searchTerm = strtolower($this->search);
            $cotisations = $cotisations->filter(function ($cotisation) use ($searchTerm) {
                return str_contains(strtolower($cotisation->client->nom ?? ''), $searchTerm) ||
                    str_contains(strtolower($cotisation->client->prenom ?? ''), $searchTerm) ||
                    str_contains(strtolower($cotisation->reference_recu ?? ''), $searchTerm);
            });
        }

        return $cotisations;
    }

    public function openModal($mode, $cotisationId = null)
    {
        $this->modalMode = $mode;
        $this->resetErrorBag();

        if ($mode === 'edit' && $cotisationId) {
            $cotisation = Cotisation::find($cotisationId);
            $this->form = [
                'id' => $cotisation->id,
                'client_id' => $cotisation->client_id,
                'cabinet_id' => $cotisation->cabinet_id,
                'montant' => $cotisation->montant,
                'date_cotisation' => $cotisation->date_cotisation,
                'type_paiement' => $cotisation->type_paiement,
                'telephone_mobile' => $cotisation->telephone_mobile ?? '',
                'collecteur_id' => $cotisation->collecteur_id,
                'statut' => $cotisation->statut,
            ];
        } else {
            $this->resetForm();
            $this->form['date_cotisation'] = Carbon::today()->format('Y-m-d');
            $this->form['collecteur_id'] = Auth::id();
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
            'date_cotisation' => Carbon::today()->format('Y-m-d'),
            'type_paiement' => 'especes',
            'telephone_mobile' => '',
            'collecteur_id' => Auth::id(),
            'statut' => 'en_attente',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            $client = Client::find($this->form['client_id']);

            if ($this->modalMode === 'create') {
                $reference = $this->generateReference();

                $dataToCreate = [
                    'client_id' => $this->form['client_id'],
                    'cabinet_id' => $this->form['cabinet_id'],
                    'montant' => $this->form['montant'],
                    'date_cotisation' => $this->form['date_cotisation'],
                    'type_paiement' => $this->form['type_paiement'],
                    'collecteur_id' => $this->form['collecteur_id'],
                    'reference_recu' => $reference,
                    'statut' => 'en_attente',
                ];

                if ($this->form['type_paiement'] === 'mobile_money') {
                    $dataToCreate['telephone_mobile'] = $this->form['telephone_mobile'];
                }

                $cotisation = Cotisation::create($dataToCreate);

                $client = Client::find($this->form['client_id']);
                $client->increment('solde', $this->form['montant']);

                ActivityLogger::log(
                    'cotisation.created',
                    "Cotisation de {$this->form['montant']} FCFA pour {$client->prenom} {$client->nom}",
                    Cotisation::class,
                    $cotisation->id
                );

                $this->showMessage("Cotisation enregistrée ! Référence : {$reference}", 'success');
            }

            $this->loadCotisations();
            $this->closeModal();
        } catch (\Exception $e) {
            $this->showMessage('Erreur : ' . $e->getMessage(), 'error');
        }
    }

    public function valider($cotisationId)
    {
        try {
            $cotisation = Cotisation::find($cotisationId);
            $client = $cotisation->client;

            $cotisation->update([
                'statut' => 'validé',
                'validé_par' => Auth::id(),
                'date_validation' => now(),
            ]);

            $client->increment('solde', $cotisation->montant);

            ActivityLogger::log(
                'cotisation.validated',
                "Cotisation validée : {$cotisation->montant} FCFA pour {$client->prenom} {$client->nom}",
                Cotisation::class,
                $cotisation->id
            );

            // 🔥 ENVOI D'EMAIL AUTOMATIQUE
            $this->envoyerEmailCotisation($cotisation, $client);

            $this->loadCotisations();
            $this->showMessage('Cotisation validée avec succès ! Email envoyé au client.', 'success');
        } catch (\Exception $e) {
            $this->showMessage('Erreur.', 'error');
        }
    }

    // 🔥 NOUVELLE MÉTHODE : Envoi d'email pour cotisation
    private function envoyerEmailCotisation($cotisation, $client)
    {
        try {
            $nomEntreprise = param_entreprise('nom') ?? 'Tontine Manager';
            $emailEntreprise = param_entreprise('email') ?? config('mail.from.address');

            // 🔥 AJOUT DE LOGS POUR DÉBUGGER
            Log::info("=== ENVOI EMAIL COTISATION ===");
            Log::info("Client: {$client->prenom} {$client->nom}");
            Log::info("Email client: " . ($client->email ?? 'AUCUN EMAIL'));

            // Si le client n'a pas d'email, on ne peut pas envoyer
            if (empty($client->email)) {
                Log::warning("❌ Le client n'a pas d'email !");
                return;
            }

            Log::info("✅ Email du client trouvé: {$client->email}");

            $message = "Bonjour {$client->prenom} {$client->nom},\n\n";
            $message .= "Votre cotisation a été validée avec succès !\n\n";
            $message .= "📋 Détails de la transaction :\n";
            $message .= "• Référence : {$cotisation->reference_recu}\n";
            $message .= "• Montant : " . number_format($cotisation->montant, 0, ',', ' ') . " FCFA\n";
            $message .= "• Date : " . Carbon::parse($cotisation->date_cotisation)->format('d/m/Y') . "\n";
            $message .= "• Nouveau solde : " . number_format($client->solde, 0, ',', ' ') . " FCFA\n\n";
            $message .= "Merci pour votre confiance !\n\n";
            $message .= "Cordialement,\n";
            $message .= "L'équipe {$nomEntreprise}";

            Log::info("📧 Envoi de l'email à {$client->email}...");

            Mail::raw($message, function ($mail) use ($client, $nomEntreprise, $emailEntreprise) {
                $mail->from($emailEntreprise, $nomEntreprise)
                    ->to($client->email)
                    ->subject("✅ Cotisation validée - {$nomEntreprise}");
            });

            Log::info("✅ Email envoyé avec succès !");
        } catch (\Exception $e) {
            // Si l'email échoue, on ne bloque pas la validation
            Log::error("❌ Erreur envoi email cotisation : " . $e->getMessage());
            Log::error("Trace: " . $e->getTraceAsString());
        }
    }

    public function rejeter($cotisationId)
    {
        try {
            $cotisation = Cotisation::find($cotisationId);

            $cotisation->update([
                'statut' => 'rejeté',
                'validé_par' => Auth::id(),
                'date_validation' => now(),
            ]);

            ActivityLogger::log(
                'cotisation.rejected',
                "Cotisation rejetée : {$cotisation->reference_recu}",
                Cotisation::class,
                $cotisation->id
            );

            $this->loadCotisations();
            $this->showMessage('Cotisation rejetée.', 'success');
        } catch (\Exception $e) {
            $this->showMessage('Erreur.', 'error');
        }
    }

    private function generateReference(): string
    {
        $date = Carbon::now()->format('Ymd');
        $lastCotisation = Cotisation::whereDate('created_at', Carbon::today())->latest('id')->first();
        $sequence = $lastCotisation ? (intval(substr($lastCotisation->reference_recu, -5)) + 1) : 1;
        return 'COT-' . $date . '-' . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }

    public function showMessage($msg, $type)
    {
        $this->message = $msg;
        $this->messageType = $type;
    }

    public function render()
    {
        return view('livewire.admin.cotisation-manager', [
            'filteredCotisations' => $this->getFilteredCotisationsProperty(),
            'montantMinCotisation' => $this->montantMinCotisation, // ✅ PASSÉ À LA VUE
        ]);
    }
}
