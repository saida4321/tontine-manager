<?php

namespace App\Livewire\Admin;

use App\Models\Cabinet;
use App\Types\Etat;
use Livewire\Component;

class CabinetManager extends Component
{
    public $cabinets;
    public $search = '';
    public $modalOpen = false;
    public $modalMode = 'create';
    public $form = [
        'id' => null,
        'nom' => '',
        'adresse' => '',
        'telephone' => '',
        'email' => ''
    ];
    public $message = '';
    public $messageType = '';

    protected $rules = [
        'form.nom' => 'required|string|max:100',
        'form.adresse' => 'nullable|string',
        'form.telephone' => 'nullable|string|max:20',
        'form.email' => 'nullable|email|max:150',
    ];

    protected $messages = [
        'form.nom.required' => 'Le nom du cabinet est obligatoire.',
        'form.nom.max' => 'Le nom ne peut pas dépasser 100 caractères.',
        'form.email.email' => 'L\'adresse email n\'est pas valide.',
    ];

    public function mount()
    {
        $this->loadCabinets();
    }

    public function loadCabinets()
    {
        $this->cabinets = Cabinet::where('etat', Etat::ACTIF)
            ->orderBy('nom')
            ->get();
    }

    public function getFilteredCabinetsProperty()
    {
        if (empty($this->search)) {
            return $this->cabinets;
        }

        $searchTerm = strtolower($this->search);

        return $this->cabinets->filter(function ($cabinet) use ($searchTerm) {
            return str_contains(strtolower($cabinet->nom), $searchTerm) ||
                str_contains(strtolower($cabinet->adresse ?? ''), $searchTerm) ||
                str_contains(strtolower($cabinet->telephone ?? ''), $searchTerm) ||
                str_contains(strtolower($cabinet->email ?? ''), $searchTerm);
        });
    }

    public function openModal($mode, $cabinetId = null)
    {
        $this->modalMode = $mode;
        $this->resetErrorBag();

        if ($mode === 'edit' && $cabinetId) {
            $cabinet = Cabinet::find($cabinetId);
            $this->form = [
                'id' => $cabinet->id,
                'nom' => $cabinet->nom,
                'adresse' => $cabinet->adresse,
                'telephone' => $cabinet->telephone,
                'email' => $cabinet->email,
            ];
        } else {
            $this->resetForm();
        }

        $this->modalOpen = true;
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'nom' => '',
            'adresse' => '',
            'telephone' => '',
            'email' => ''
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->modalMode === 'create') {
                // Vérifier l'unicité du nom
                $exists = Cabinet::where('nom', $this->form['nom'])
                    ->where('etat', Etat::ACTIF)
                    ->exists();

                if ($exists) {
                    $this->addError('form.nom', 'Ce nom de cabinet existe déjà.');
                    return;
                }

                Cabinet::create([
                    'nom' => $this->form['nom'],
                    'adresse' => $this->form['adresse'],
                    'telephone' => $this->form['telephone'],
                    'email' => $this->form['email'],
                    'etat' => Etat::ACTIF,
                ]);

                $this->showMessage('Cabinet créé avec succès !', 'success');
            } else {
                // Vérifier l'unicité du nom (sauf pour le cabinet en cours)
                $exists = Cabinet::where('nom', $this->form['nom'])
                    ->where('etat', Etat::ACTIF)
                    ->where('id', '!=', $this->form['id'])
                    ->exists();

                if ($exists) {
                    $this->addError('form.nom', 'Ce nom de cabinet existe déjà.');
                    return;
                }

                $cabinet = Cabinet::find($this->form['id']);
                $cabinet->update([
                    'nom' => $this->form['nom'],
                    'adresse' => $this->form['adresse'],
                    'telephone' => $this->form['telephone'],
                    'email' => $this->form['email'],
                ]);

                $this->showMessage('Cabinet modifié avec succès !', 'success');
            }

            $this->loadCabinets();
            $this->closeModal();
        } catch (\Exception $e) {
            $this->showMessage('Une erreur est survenue.', 'error');
        }
    }

    public function delete($cabinetId)
    {
        try {
            $cabinet = Cabinet::find($cabinetId);

            // Vérifier si le cabinet a des clients actifs
            $clientsActifs = $cabinet->clients()->where('etat', Etat::ACTIF)->count();

            if ($clientsActifs > 0) {
                $this->showMessage("Impossible de supprimer ce cabinet. Il a encore {$clientsActifs} client(s) actif(s).", 'error');
                return;
            }

            $cabinet->update(['etat' => Etat::SUPPRIME]);
            $this->loadCabinets();
            $this->showMessage('Cabinet supprimé avec succès !', 'success');
        } catch (\Exception $e) {
            $this->showMessage('Une erreur est survenue.', 'error');
        }
    }

    public function showMessage($msg, $type)
    {
        $this->message = $msg;
        $this->messageType = $type;

        // Le message disparaîtra automatiquement après 5 secondes via JavaScript
    }

    public function render()
    {
        return view('livewire.admin.cabinet-manager', [
            'filteredCabinets' => $this->getFilteredCabinetsProperty()
        ]);
    }
}
