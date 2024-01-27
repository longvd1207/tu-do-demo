<?php

namespace App\Repositories\Map;

use App\Models\Map;
use App\Repositories\BaseRepository;

class MapRepository extends BaseRepository implements MapRepositoryInterface
{
    public function getModel()
    {
        return Map::class;
    }

    public function accessByArea($ticket_type_id)
    {
        $data = $this->getByField('ticket_type_id', $ticket_type_id);
        $data =  $data->with('getServices.area', 'getFunSpots.area', 'getAreas')->get();

        $data_return = [];
        foreach ($data as $value) {
            if (!empty($value->getAreas)) {
                $data_return[$value->type_id]['area_name'] = $value->getAreas->name;
            }

            if (!empty($value->getServices)) {
                $data_return[$value->getServices->area_id]['getServices'][] = $value->getServices->name;
            }

            if (!empty($value->getFunSpots)) {
                $data_return[$value->getFunSpots->area_id]['getFunSpots'][] = $value->getFunSpots->name;
            }
        }
        return $data_return;
    }
}
