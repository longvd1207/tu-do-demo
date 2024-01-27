<?php

namespace App\Repositories\DeviceConfig;

use App\Repositories\BaseRepositoryInterface;

interface DeviceConfigRepositoryInterface extends BaseRepositoryInterface
{
    public function getWithFilter();
}
