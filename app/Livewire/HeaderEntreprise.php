<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class HeaderEntreprise extends Component
{
    public $nomEntreprise;
    
    public function mount()
    {
        $this->nomEntreprise = param_entreprise('nom');
    }
    
    #[On('entreprise-updated')]
    public function refreshNom()
    {
        $this->nomEntreprise = param_entreprise('nom');
    }
    
    public function render()
    {
        return view('livewire.header-entreprise');
    }
}