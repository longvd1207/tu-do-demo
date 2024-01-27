<?php

namespace App\Http\Livewire;

use App\Repositories\Area\AreaRepository;
use App\Repositories\FunSpot\FunSpotRepository;
use App\Repositories\Map\MapRepository;
use App\Repositories\Service\ServiceRepository;
use Livewire\Component;
use Livewire\Livewire;

class  AreaCheckbox extends Component
{
    public $areas = [];
    public $maps = [];
    public $selectedAreas = [];
    protected $serviceRepository;
    protected $funSpotRepository;
    protected $mapRepository;


    protected $rules = [
        'areas' => 'array',
    ];

    public function boot(
        ServiceRepository $serviceRepository,
        FunSpotRepository $funSpotRepository,
        MapRepository     $mapRepository
    )
    {
        $this->mapRepository = $mapRepository;
        $this->funSpotRepository = $funSpotRepository;
        $this->serviceRepository = $serviceRepository;
    }

    public function mount(
        AreaRepository $areaRepository,
    )
    {
        $this->areas = $areaRepository->getAll();
    }

    public function render()
    {
        return view('livewire.area-checkbox');
    }

    public function getAreaByCheckbox()
    {
        $this->skipRender();
        $serviceArr = [];
        $funSpotArr = [];
        if ($this->selectedAreas != []) {
            foreach ($this->selectedAreas as $idArea) {
                $services = $this->serviceRepository->getByField('area_id', $idArea)->where('status', 1)->get();
                $funSpots = $this->funSpotRepository->getByField('area_id', $idArea)->where('status', 1)->get();

                if ($services) {
                    foreach ($services as $service) {
                        $serviceArr[] = $service;
                    }
                }

                if ($funSpots) {
                    foreach ($funSpots as $funSpot) {
                        $funSpotArr[] = $funSpot;
                    }
                }
            }
        }
        $this->emit('servicesSelected', $serviceArr);
        $this->emit('funSpotsSelected', $funSpotArr);
    }



    public function getAreasProperty()
    {
        return $this->areas;
    }
}
