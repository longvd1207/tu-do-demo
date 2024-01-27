<?php

namespace App\Repositories\TicketType;

use App\Repositories\BaseRepositoryInterface;

interface TicketTypeRepositoryInterface extends BaseRepositoryInterface
{
    public function getData($filter = [], $limit = 0, $with = []);
}
