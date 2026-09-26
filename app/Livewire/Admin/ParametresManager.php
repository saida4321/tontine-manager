<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Parametre;

class ParametresManager extends Component
{
    use WithFileUploads;

    public $ongletActif = 'entreprise';

    // Entreprise
    public $nom_entreprise;
    public $logo;
    public $telephone_entreprise;
    public $email_entreprise;
    public $adresse_entreprise;

    // Financier
    public $montant_min_cotisation;
    public $montant_min_retrait;
    public $commission_collecteur;
    public $taux_interet;

    // Profil
    public $nom_user;
    public $email_user;
    public $password_actuel;
    public $nouveau_password;
    public $confirmation_password;

    // Email SMTP
    public $smtp_host;
    public $smtp_port;
    public $smtp_email;
    public $smtp_username;
    public $smtp_password;
    public $smtp_encryption;

    // Notifications
    public $notif_cotisations = true;
    public $notif_retraits = true;
    public $notif_retraits_approuves = true;
    public $notif_clients = false;
    public $notif_rapports = true;
    public $notif_securite = true;

    // Apparence
    public $theme = 'clair';
    public $langue = 'fr';

    // Sécurité
    public $two_factor_enabled = false;
    public $https_enabled = true;

    public $sections = [
        ['id' => 'entreprise', 'title' => 'Entreprise', 'icon' => '🏢', 'color' => 'violet', 'desc' => 'Nom, logo, coordonnées'],
        ['id' => 'financier', 'title' => 'Financier', 'icon' => '💰', 'color' => 'emerald', 'desc' => 'Montants, commissions'],
        ['id' => 'email', 'title' => 'Configuration Email', 'icon' => '📧', 'color' => 'rose', 'desc' => 'Serveur SMTP, envoi'],
        ['id' => 'notifications', 'title' => 'Notifications', 'icon' => '🔔', 'color' => 'amber', 'desc' => 'Alertes, rappels'],
        ['id' => 'apparence', 'title' => 'Apparence', 'icon' => '🎨', 'color' => 'purple', 'desc' => 'Thème, langue, couleurs'],
        ['id' => 'export', 'title' => 'Export/Import', 'icon' => '📊', 'color' => 'cyan', 'desc' => 'Sauvegarder, restaurer'],
        ['id' => 'securite', 'title' => 'Sécurité', 'icon' => '🔒', 'color' => 'red', 'desc' => '2FA, sessions, HTTPS'],
    ];

    public function mount()
    {
        $this->loadParametres();
        $this->loadUserData();
    }

    public function loadParametres()
    {
        $params = Parametre::first();

        if ($params) {
            // Entreprise
            $this->nom_entreprise = $params->nom_entreprise;
            $this->telephone_entreprise = $params->telephone_entreprise;
            $this->email_entreprise = $params->email_entreprise;
            $this->adresse_entreprise = $params->adresse_entreprise;

            // Financier
            $this->montant_min_cotisation = $params->montant_min_cotisation;
            $this->montant_min_retrait = $params->montant_min_retrait;
            $this->commission_collecteur = $params->taux_commission_collecteur;
            $this->taux_interet = $params->taux_interet ?? null;

            // Email SMTP
            $this->smtp_host = $params->smtp_host;
            $this->smtp_port = $params->smtp_port;
            $this->smtp_email = $params->smtp_email;
            $this->smtp_username = $params->smtp_username;
            $this->smtp_password = $params->smtp_password;
            $this->smtp_encryption = $params->smtp_encryption ?? 'tls';
        }
    }

    public function loadUserData()
    {
        $user = Auth::user();
        $this->nom_user = $user->name;
        $this->email_user = $user->email;
    }

    public function setActiveTab($tab)
    {
        $this->ongletActif = $tab;

        // Empêche le rechargement complet du composant
        $this->skipRender();
    }

    public function getSectionIcon()
    {
        return $this->getCurrentSection()['icon'] ?? '🏢';
    }

    public function getSectionTitle()
    {
        return $this->getCurrentSection()['title'] ?? 'Entreprise';
    }

    public function getSectionDesc()
    {
        return $this->getCurrentSection()['desc'] ?? '';
    }

    public function getColorClass($type = 'gradient', $color = null)
    {
        if (!$color) {
            $section = collect($this->sections)->firstWhere('id', $this->ongletActif);
            $color = $section['color'] ?? 'violet';
        }

        $colors = [
            'violet' => ['gradient' => 'from-violet-600 to-violet-700', 'ring' => 'ring-violet-500'],
            'emerald' => ['gradient' => 'from-emerald-600 to-emerald-700', 'ring' => 'ring-emerald-500'],
            'blue' => ['gradient' => 'from-blue-600 to-blue-700', 'ring' => 'ring-blue-500'],
            'rose' => ['gradient' => 'from-rose-600 to-rose-700', 'ring' => 'ring-rose-500'],
            'amber' => ['gradient' => 'from-amber-600 to-amber-700', 'ring' => 'ring-amber-500'],
            'purple' => ['gradient' => 'from-purple-600 to-purple-700', 'ring' => 'ring-purple-500'],
            'cyan' => ['gradient' => 'from-cyan-600 to-cyan-700', 'ring' => 'ring-cyan-500'],
            'red' => ['gradient' => 'from-red-600 to-red-700', 'ring' => 'ring-red-500'],
        ];

        return $colors[$color][$type] ?? $colors['violet'][$type];
    }

    public function saveEntreprise()
    {
        $this->validate([
            'nom_entreprise' => 'required|string|max:255',
            'telephone_entreprise' => 'required|string|max:20',
            'email_entreprise' => 'required|email',
            'adresse_entreprise' => 'nullable|string',
        ]);

        $params = Parametre::firstOrCreate([]);
        $params->update([
            'nom_entreprise' => $this->nom_entreprise,
            'telephone_entreprise' => $this->telephone_entreprise,
            'email_entreprise' => $this->email_entreprise,
            'adresse_entreprise' => $this->adresse_entreprise,
        ]);

        $this->loadParametres();


        $this->dispatch('entreprise-updated');

        session()->flash('message', 'Informations entreprise enregistrées avec succès !');
    }

    public function saveFinancier()
    {
        $this->validate([
            'montant_min_cotisation' => 'required|numeric|min:0',
            'montant_min_retrait' => 'required|numeric|min:0',
            'commission_collecteur' => 'required|numeric|min:0|max:100',
            'taux_interet' => 'nullable|numeric|min:0|max:100',
        ]);

        $params = Parametre::firstOrCreate([]);
        $params->update([
            'montant_min_cotisation' => $this->montant_min_cotisation,
            'montant_min_retrait' => $this->montant_min_retrait,
            'taux_commission_collecteur' => $this->commission_collecteur,
        ]);

        // Recharger les données
        $this->loadParametres();

        session()->flash('message', 'Paramètres financiers enregistrés avec succès !');
    }

    public function saveEmail()
    {
        $this->validate([
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|numeric|min:1|max:65535',
            'smtp_email' => 'required|email',
            'smtp_username' => 'required|string',
            'smtp_password' => 'required|string',
            'smtp_encryption' => 'required|in:tls,ssl',
        ]);

        $params = Parametre::firstOrCreate([]);
        $params->update([
            'smtp_host' => $this->smtp_host,
            'smtp_port' => $this->smtp_port,
            'smtp_email' => $this->smtp_email,
            'smtp_username' => $this->smtp_username,
            'smtp_password' => $this->smtp_password,
            'smtp_encryption' => $this->smtp_encryption,
        ]);

        $this->loadParametres();

        session()->flash('message', 'Configuration email enregistrée avec succès !');
    }

    public function testEmail()
    {
        try {
            // Validation rapide
            if (empty($this->smtp_email)) {
                session()->flash('error', '❌ L\'adresse email d\'envoi est vide !');
                return;
            }

            // 🔥 Récupère le nom de l'entreprise depuis la base de données
            $nomEntreprise = param_entreprise('nom') ?? 'Tontine Manager';

            // Configuration temporaire complète
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $this->smtp_host,
                'mail.mailers.smtp.port' => $this->smtp_port,
                'mail.mailers.smtp.username' => $this->smtp_username,
                'mail.mailers.smtp.password' => $this->smtp_password,
                'mail.mailers.smtp.encryption' => $this->smtp_encryption,
                'mail.from.address' => $this->smtp_email,
                'mail.from.name' => $nomEntreprise,  // 🔥 Utilise le nom dynamique
            ]);

            // Forcer le rechargement du mailer
            app()->forgetInstance('mail.manager');
            app()->forgetInstance('swift.mailer');

            // Test avec FROM explicite
            Mail::raw("Ceci est un test de connexion SMTP depuis {$nomEntreprise}. Si vous recevez cet email, votre configuration est correcte ! 🎉", function ($message) use ($nomEntreprise) {
                $message->from($this->smtp_email, $nomEntreprise)  // 🔥 Nom dynamique
                    ->to($this->smtp_email)
                    ->subject($nomEntreprise);  // 🔥 Sujet = nom de l'entreprise
            });

            session()->flash('message', '✅ Connexion SMTP réussie ! Un email de test a été envoyé à ' . $this->smtp_email);
        } catch (\Exception $e) {
            session()->flash('error', '❌ Erreur de connexion : ' . $e->getMessage());
        }
    }

    public function saveProfil()
    {
        $this->validate([
            'nom_user' => 'required|string|max:255',
            'email_user' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'name' => $this->nom_user,
            'email' => $this->email_user,
        ]);

        session()->flash('message', 'Profil mis à jour avec succès !');
    }

    public function changePassword()
    {
        $this->validate([
            'password_actuel' => 'required',
            'nouveau_password' => 'required|min:8',
            'confirmation_password' => 'required|same:nouveau_password',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($this->password_actuel, $user->password)) {
            session()->flash('error', 'Le mot de passe actuel est incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->nouveau_password),
        ]);

        $this->reset(['password_actuel', 'nouveau_password', 'confirmation_password']);
        session()->flash('message', 'Mot de passe modifié avec succès !');
    }

    public function toggleTwoFactor()
    {
        $this->two_factor_enabled = !$this->two_factor_enabled;
        session()->flash('message', $this->two_factor_enabled ? '2FA activé' : '2FA désactivé');
    }

    public function toggleHttps()
    {
        $this->https_enabled = !$this->https_enabled;
        session()->flash('message', $this->https_enabled ? 'HTTPS activé' : 'HTTPS désactivé');
    }

    public function render()
    {
        return view('livewire.admin.parametres-manager');
    }
}
