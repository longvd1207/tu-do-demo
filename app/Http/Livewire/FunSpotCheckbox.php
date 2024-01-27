<?php

namespace App\Http\Livewire;

use Livewire\Component;

class FunSpotCheckbox extends Component
{
    public $selectedFunSpots = [];

    protected $listeners = ['funSpotsSelected'];

    public function funSpotsSelected($funSpotArr)
    {
        $this->selectedFunSpots = $funSpotArr;
    }

    public function render()
    {
        return view('livewire.fun-spot-checkbox');
    }
}
