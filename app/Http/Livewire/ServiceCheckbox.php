<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ServiceCheckbox extends Component
{
    public $selectedServices = [];

    protected $listeners = ['servicesSelected'];

    public function servicesSelected($serviceArr)
    {
        $this->selectedServices = $serviceArr;
    }

    public function render()
    {
        return view('livewire.service-checkbox');
    }
}
