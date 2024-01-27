<?php

namespace App\Repositories\Map;

use App\Repositories\BaseRepositoryInterface;

interface MapRepositoryInterface extends BaseRepositoryInterface
{
    public function accessByArea($ticket_type_id);
}
