<?php

namespace App\Repositories\Order;

use App\Repositories\BaseRepositoryInterface;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function genCode();

}
