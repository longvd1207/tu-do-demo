<?php

namespace App\Repositories\DeviceConfig;

use App\Models\DeviceConfig;
use App\Repositories\BaseRepository;

class DeviceConfigRepository extends BaseRepository implements DeviceConfigRepositoryInterface
{
    public function getModel()
    {
        return DeviceConfig::class;
    }

    public function getWithFilter()
    {
    }


}
