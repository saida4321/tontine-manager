<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use App\Models\Cotisation;
use App\Models\Retrait;
use App\Models\Cabinet;
use App\Models\User;
use App\Types\Etat;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $stats = [];
    public $cotisationsParMois = [];
    public $retraitsParMois = [];
    public $cotisationsParCabinet = [];
    public $topClients = [];
    public $retraitsEnAttente = [];
    public $dernieresActivites = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadGraphData();
        $this->loadTopClients();
        $this->loadRetraitsEnAttente();
    }

    public function loadStats()
    {
        // Total cotisations validées
        $totalCotisations = Cotisation::where('statut', 'validé')->sum('montant');
        
        // Total retraits effectués
        $totalRetraits = Retrait::where('statut', 'effectué')->sum('montant');
        
        // Clients actifs
        $clientsActifs = Client::where('etat', Etat::ACTIF)->count();
        
        // Solde global
        $soldeGlobal = Client::where('etat', Etat::ACTIF)->sum('solde');
        
        // Cotisations du mois en cours
        $cotisationsMoisActuel = Cotisation::where('statut', 'validé')
            ->whereMonth('date_cotisation', Carbon::now()->month)
            ->whereYear('date_cotisation', Carbon::now()->year)
            ->sum('montant');
        
        // Cotisations du mois précédent
        $cotisationsMoisPrecedent = Cotisation::where('statut', 'validé')
            ->whereMonth('date_cotisation', Carbon::now()->subMonth()->month)
            ->whereYear('date_cotisation', Carbon::now()->subMonth()->year)
            ->sum('montant');
        
        // Calcul du pourcentage d'évolution
        $evolutionCotisations = 0;
        if ($cotisationsMoisPrecedent > 0) {
            $evolutionCotisations = (($cotisationsMoisActuel - $cotisationsMoisPrecedent) / $cotisationsMoisPrecedent) * 100;
        }

        $this->stats = [
            'total_cotisations' => $totalCotisations,
            'total_retraits' => $totalRetraits,
            'clients_actifs' => $clientsActifs,
            'solde_global' => $soldeGlobal,
            'evolution_cotisations' => round($evolutionCotisations, 1),
        ];
    }

    public function loadGraphData()
    {
        // Cotisations des 6 derniers mois
        $this->cotisationsParMois = Cotisation::where('statut', 'validé')
            ->where('date_cotisation', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('MONTH(date_cotisation) as mois'),
                DB::raw('YEAR(date_cotisation) as annee'),
                DB::raw('SUM(montant) as total')
            )
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'asc')
            ->orderBy('mois', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'label' => Carbon::create($item->annee, $item->mois)->format('M Y'),
                    'value' => $item->total
                ];
            });

        // Retraits des 6 derniers mois
        $this->retraitsParMois = Retrait::where('statut', 'effectué')
            ->where('date_retrait', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('MONTH(date_retrait) as mois'),
                DB::raw('YEAR(date_retrait) as annee'),
                DB::raw('SUM(montant) as total')
            )
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'asc')
            ->orderBy('mois', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'label' => Carbon::create($item->annee, $item->mois)->format('M Y'),
                    'value' => $item->total
                ];
            });

        // Cotisations par cabinet ce mois
        $this->cotisationsParCabinet = Cotisation::where('statut', 'validé')
            ->whereMonth('date_cotisation', Carbon::now()->month)
            ->whereYear('date_cotisation', Carbon::now()->year)
            ->join('clients', 'cotisations.client_id', '=', 'clients.id')
            ->join('cabinets', 'clients.cabinet_id', '=', 'cabinets.id')
            ->select('cabinets.nom', DB::raw('SUM(cotisations.montant) as total'))
            ->groupBy('cabinets.id', 'cabinets.nom')
            ->get();
    }

    public function loadTopClients()
    {
        $this->topClients = Client::where('etat', Etat::ACTIF)
            ->where('solde', '>', 0)
            ->orderBy('solde', 'desc')
            ->limit(5)
            ->get();
    }

    public function loadRetraitsEnAttente()
    {
        $this->retraitsEnAttente = Retrait::with(['client', 'cabinet'])
            ->where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}