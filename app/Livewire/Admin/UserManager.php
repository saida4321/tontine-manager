<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Cabinet;
use App\Types\Etat;
use App\Types\Role;
use App\Helpers\ActivityLogger;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class UserManager extends Component
{
    public $users;
    public $cabinets;
    public $search = '';
    public $filterRole = '';
    public $filterEtat = '';
    public $modalOpen = false;
    public $modalMode = 'create';
    public $showPassword = false; // ✅ AJOUTÉ
    public $form = [
        'id' => null,
        'nom' => '',
        'email' => '',
        'indicatif' => '+228', // ✅ AJOUTÉ
        'numero_local' => '', // ✅ AJOUTÉ
        'telephone' => '',
        'password' => '',
        'password_confirmation' => '',
        'role' => '',
        'etat' => Etat::ACTIF,
        'cabinets' => []
    ];
    public $message = '';
    public $messageType = '';

    protected function rules()
    {
        $rules = [
            'form.nom' => 'required|string|max:100',
            'form.email' => 'required|email|max:150|unique:users,email,' . ($this->form['id'] ?? 'NULL'),
            'form.telephone' => 'nullable|string|max:20|unique:users,telephone,' . ($this->form['id'] ?? 'NULL'),
            'form.role' => 'required|in:2,3,4',
            'form.etat' => 'required|in:' . implode(',', Etat::all()),
            'form.cabinets' => 'required|array|min:1',
            'form.cabinets.*' => 'exists:cabinets,id',
        ];

        if ($this->modalMode === 'create') {
            $rules['form.password'] = 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/|confirmed';
        } elseif (!empty($this->form['password'])) {
            $rules['form.password'] = 'string|min:8|regex:/[A-Z]/|regex:/[0-9]/|confirmed';
        }

        return $rules;
    }

    protected $messages = [
        'form.nom.required' => 'Le nom est obligatoire.',
        'form.email.required' => 'L\'email est obligatoire.',
        'form.email.unique' => 'Cet email existe déjà.',
        'form.telephone.unique' => 'Ce téléphone existe déjà.',
        'form.role.required' => 'Le rôle est obligatoire.',
        'form.role.in' => 'Vous ne pouvez pas créer d\'administrateur.',
        'form.password.required' => 'Le mot de passe est obligatoire.',
        'form.password.min' => 'Minimum 8 caractères.',
        'form.password.regex' => 'Le mot de passe doit contenir au moins 1 majuscule et 1 chiffre.',
        'form.password.confirmed' => 'Les mots de passe ne correspondent pas.',
        'form.cabinets.required' => 'Sélectionnez au moins un cabinet.',
    ];

    public function mount()
    {
        $this->loadUsers();
        $this->loadCabinets();
    }

    public function loadUsers()
    {
        $this->users = User::with('cabinets')
            ->whereIn('etat', [Etat::ACTIF, Etat::INACTIF, Etat::SUSPENDU])
            ->whereIn('role', [Role::Caissier, Role::Collecteur, Role::Comptable])
            ->orderBy('nom')
            ->get();
    }

    public function loadCabinets()
    {
        $this->cabinets = Cabinet::where('etat', Etat::ACTIF)->orderBy('nom')->get();
    }

    public function getFilteredUsersProperty()
    {
        $users = $this->users;

        if (!empty($this->filterRole)) {
            $users = $users->where('role', $this->filterRole);
        }

        if (!empty($this->filterEtat)) {
            $users = $users->where('etat', $this->filterEtat);
        }

        if (!empty($this->search)) {
            $searchTerm = strtolower($this->search);
            $users = $users->filter(function ($user) use ($searchTerm) {
                return str_contains(strtolower($user->nom), $searchTerm) ||
                    str_contains(strtolower($user->email), $searchTerm) ||
                    str_contains(strtolower($user->telephone ?? ''), $searchTerm);
            });
        }

        return $users;
    }

    public function openModal($mode, $userId = null)
    {
        $this->modalMode = $mode;
        $this->resetErrorBag();

        if ($mode === 'edit' && $userId) {
            $user = User::with('cabinets')->find($userId);
            $this->form = [
                'id' => $user->id,
                'nom' => $user->nom,
                'email' => $user->email,
                'indicatif' => '+228', // ✅ AJOUTÉ
                'numero_local' => '', // ✅ AJOUTÉ
                'telephone' => $user->telephone,
                'password' => '',
                'password_confirmation' => '',
                'role' => $user->role,
                'etat' => $user->etat,
                'cabinets' => $user->cabinets->pluck('id')->toArray(),
            ];
        } else {
            $this->resetForm();
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
            'nom' => '',
            'email' => '',
            'indicatif' => '+228', // ✅ AJOUTÉ
            'numero_local' => '', // ✅ AJOUTÉ
            'telephone' => '',
            'password' => '',
            'password_confirmation' => '',
            'role' => '',
            'etat' => Etat::ACTIF,
            'cabinets' => []
        ];
        $this->showPassword = false; // ✅ AJOUTÉ
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->modalMode === 'create') {
                // ✅ Combiner indicatif + numéro local
                //$this->form['telephone'] = $this->form['indicatif'] . $this->form['numero_local'];

                $user = User::create([
                    'nom' => $this->form['nom'],
                    'email' => $this->form['email'],
                    'telephone' => $this->form['telephone'],
                    'password' => Hash::make($this->form['password']),
                    'role' => $this->form['role'],
                    'etat' => $this->form['etat'],
                ]);

                $user->cabinets()->sync($this->form['cabinets']);

                ActivityLogger::log(
                    'user.created',
                    "Utilisateur '{$user->nom}' créé",
                    User::class,
                    $user->id
                );

                $this->showMessage('Utilisateur créé avec succès !', 'success');
            } else {
                $user = User::find($this->form['id']);
                $oldValues = $user->only(['nom', 'email', 'telephone', 'role', 'etat']);

                $dataToUpdate = [
                    'nom' => $this->form['nom'],
                    'email' => $this->form['email'],
                    'telephone' => $this->form['telephone'],
                    'role' => $this->form['role'],
                    'etat' => $this->form['etat'],
                ];

                if (!empty($this->form['password'])) {
                    $dataToUpdate['password'] = Hash::make($this->form['password']);
                }

                $user->update($dataToUpdate);
                $user->cabinets()->sync($this->form['cabinets']);

                ActivityLogger::log(
                    'user.updated',
                    "Utilisateur '{$user->nom}' modifié",
                    User::class,
                    $user->id,
                    $oldValues,
                    $user->only(['nom', 'email', 'telephone', 'role', 'etat'])
                );

                $this->showMessage('Utilisateur modifié avec succès !', 'success');
            }

            $this->loadUsers();
            $this->closeModal();
        } catch (\Exception $e) {
            $this->showMessage('Erreur : ' . $e->getMessage(), 'error');
        }
    }

    public function changeEtat($userId, $newEtat)
    {
        try {
            $user = User::find($userId);
            $oldEtat = $user->etat;
            $user->update(['etat' => $newEtat]);

            ActivityLogger::log(
                'user.etat_changed',
                "État de '{$user->nom}' changé de '{$oldEtat}' à '{$newEtat}'",
                User::class,
                $user->id
            );

            $this->loadUsers();
            $this->showMessage("Utilisateur " . ucfirst($newEtat) . " !", 'success');
        } catch (\Exception $e) {
            $this->showMessage('Erreur.', 'error');
        }
    }

    // ✅ NOUVELLE MÉTHODE: Toggle visibilité mot de passe
    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function showMessage($msg, $type)
    {
        $this->message = $msg;
        $this->messageType = $type;
    }

    public function render()
    {
        return view('livewire.admin.user-manager', [
            'filteredUsers' => $this->getFilteredUsersProperty(),
            'roles' => [
                Role::Caissier => 'Caissier',
                Role::Collecteur => 'Collecteur',
                Role::Comptable => 'Comptable',
            ]
        ]);
    }
}