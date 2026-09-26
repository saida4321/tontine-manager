<?php

namespace App\Livewire\Admin;

use App\Models\Cotisation;
use App\Models\Retrait;
use App\Models\Client;
use App\Models\Cabinet;
use App\Models\User;
use App\Types\Etat;
use App\Types\Role;
use Livewire\Component;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class RapportManager extends Component
{
    public $periode;
    public $cabinet_id = '';
    public $collecteur_id = '';
    
    public $stats = [];
    public $statsCabinets = [];
    public $statsCollecteurs = [];

    public function mount()
    {
        $this->periode = Carbon::now()->format('Y-m');
        $this->chargerDonnees();
    }

    public function filtrer()
    {
        $this->chargerDonnees();
    }

    public function chargerDonnees()
    {
        $dateDebut = Carbon::parse($this->periode . '-01')->startOfMonth();
        $dateFin = Carbon::parse($this->periode . '-01')->endOfMonth();

        // 📊 STATS GLOBALES
        $queryCotisations = Cotisation::whereBetween('date_cotisation', [$dateDebut, $dateFin])
            ->where('etat', Etat::ACTIF)
            ->where('statut', 'validé');
        
        $queryRetraits = Retrait::whereBetween('date_demande', [$dateDebut, $dateFin])
            ->where('etat', Etat::ACTIF)
            ->where('statut', 'effectué');

        if ($this->cabinet_id) {
            $queryCotisations->where('cabinet_id', $this->cabinet_id);
            $queryRetraits->where('cabinet_id', $this->cabinet_id);
        }

        if ($this->collecteur_id) {
            $queryCotisations->where('collecteur_id', $this->collecteur_id);
        }

        $this->stats = [
            'total_cotisations' => $queryCotisations->sum('montant'),
            'nb_cotisations' => $queryCotisations->count(),
            'total_retraits' => $queryRetraits->sum('montant'),
            'nb_retraits' => $queryRetraits->count(),
            'solde_global' => Client::where('etat', Etat::ACTIF)->sum('solde'),
            'clients_actifs' => Client::where('etat', Etat::ACTIF)->count(),
        ];

        // 🏢 STATS PAR CABINET
        $this->statsCabinets = Cabinet::where('etat', Etat::ACTIF)
            ->when($this->cabinet_id, fn($q) => $q->where('id', $this->cabinet_id))
            ->withCount([
                'cotisations as total_cotisations' => function($q) use ($dateDebut, $dateFin) {
                    $q->whereBetween('date_cotisation', [$dateDebut, $dateFin])
                      ->where('statut', 'validé');
                },
                'retraits as total_retraits' => function($q) use ($dateDebut, $dateFin) {
                    $q->whereBetween('date_demande', [$dateDebut, $dateFin])
                      ->where('statut', 'effectué');
                }
            ])
            ->withSum([
                'cotisations as montant_cotisations' => function($q) use ($dateDebut, $dateFin) {
                    $q->whereBetween('date_cotisation', [$dateDebut, $dateFin])
                      ->where('statut', 'validé');
                }
            ], 'montant')
            ->withSum([
                'retraits as montant_retraits' => function($q) use ($dateDebut, $dateFin) {
                    $q->whereBetween('date_demande', [$dateDebut, $dateFin])
                      ->where('statut', 'effectué');
                }
            ], 'montant')
            ->get();

        // 👤 STATS PAR COLLECTEUR
        $this->statsCollecteurs = User::where('role', Role::Collecteur)
            ->where('etat', Etat::ACTIF)
            ->when($this->collecteur_id, fn($q) => $q->where('id', $this->collecteur_id))
            ->withCount([
                'cotisationsCollectees as nb_cotisations' => function($q) use ($dateDebut, $dateFin) {
                    $q->whereBetween('date_cotisation', [$dateDebut, $dateFin])
                      ->where('statut', 'validé');
                    if ($this->cabinet_id) {
                        $q->where('cabinet_id', $this->cabinet_id);
                    }
                }
            ])
            ->withSum([
                'cotisationsCollectees as montant_collecte' => function($q) use ($dateDebut, $dateFin) {
                    $q->whereBetween('date_cotisation', [$dateDebut, $dateFin])
                      ->where('statut', 'validé');
                    if ($this->cabinet_id) {
                        $q->where('cabinet_id', $this->cabinet_id);
                    }
                }
            ], 'montant')
            ->having('nb_cotisations', '>', 0)
            ->orderByDesc('montant_collecte')
            ->get();
    }

    // ✅ MÉTHODE POUR RÉCUPÉRER LES TRANSACTIONS (appelée dans render et exporterPDF)
    private function getDernieresTransactions()
    {
        $dateDebut = Carbon::parse($this->periode . '-01')->startOfMonth();
        $dateFin = Carbon::parse($this->periode . '-01')->endOfMonth();

        $cotisations = Cotisation::with(['client', 'cabinet', 'collecteur'])
            ->whereBetween('date_cotisation', [$dateDebut, $dateFin])
            ->where('statut', 'validé')
            ->when($this->cabinet_id, fn($q) => $q->where('cabinet_id', $this->cabinet_id))
            ->when($this->collecteur_id, fn($q) => $q->where('collecteur_id', $this->collecteur_id))
            ->get()
            ->map(fn($c) => [
                'type' => 'Cotisation',
                'date' => $c->date_cotisation->format('Y-m-d H:i:s'),
                'client' => ($c->client->prenom ?? '') . ' ' . ($c->client->nom ?? ''),
                'montant' => $c->montant,
                'cabinet' => $c->cabinet->nom ?? '-',
                'agent' => $c->collecteur->nom ?? '-',
            ])
            ->toArray();

        $retraits = Retrait::with(['client', 'cabinet', 'executeur'])
            ->whereBetween('date_demande', [$dateDebut, $dateFin])
            ->where('statut', 'effectué')
            ->when($this->cabinet_id, fn($q) => $q->where('cabinet_id', $this->cabinet_id))
            ->get()
            ->map(fn($r) => [
                'type' => 'Retrait',
                'date' => ($r->date_retrait ?? $r->date_demande)->format('Y-m-d H:i:s'),
                'client' => ($r->client->prenom ?? '') . ' ' . ($r->client->nom ?? ''),
                'montant' => -$r->montant,
                'cabinet' => $r->cabinet->nom ?? '-',
                'agent' => $r->executeur->nom ?? '-',
            ])
            ->toArray();

        // ✅ Fusionner les deux tableaux PHP (pas de collections)
        $transactions = array_merge($cotisations, $retraits);
        
        // ✅ Trier par date décroissante
        usort($transactions, fn($a, $b) => $b['date'] <=> $a['date']);
        
        // ✅ Prendre les 20 premiers
        return array_slice($transactions, 0, 20);
    }

    public function exporterPDF()
    {
        $data = [
            'periode' => Carbon::parse($this->periode)->locale('fr')->isoFormat('MMMM YYYY'),
            'cabinet' => $this->cabinet_id ? Cabinet::find($this->cabinet_id)->nom : 'Tous les cabinets',
            'collecteur' => $this->collecteur_id ? User::find($this->collecteur_id)->nom : 'Tous les collecteurs',
            'stats' => $this->stats,
            'statsCabinets' => $this->statsCabinets,
            'statsCollecteurs' => $this->statsCollecteurs,
            'transactions' => $this->getDernieresTransactions(),
            'dateGeneration' => Carbon::now()->locale('fr')->isoFormat('DD MMMM YYYY à HH:mm'),
            'generePar' => Auth::user()->nom,
        ];

        $pdf = Pdf::loadView('pdf.rapport-mensuel', $data)
            ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'rapport_' . $this->periode . '.pdf'
        );
    }

    public function render()
    {
        $cabinets = Cabinet::where('etat', Etat::ACTIF)->orderBy('nom')->get();
        $collecteurs = User::where('role', Role::Collecteur)
            ->where('etat', Etat::ACTIF)
            ->orderBy('nom')
            ->get();

        return view('livewire.admin.rapport-manager', [
            'cabinets' => $cabinets,
            'collecteurs' => $collecteurs,
            'dernieresTransactions' => $this->getDernieresTransactions(), // ✅ Calculé à la volée
        ]);
    }
}