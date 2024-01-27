<?php

namespace App\Repositories\PaymentStatus;

use App\Repositories\BaseRepositoryInterface;

interface PaymentStatusRepositoryInterface extends BaseRepositoryInterface
{
    public function getWithFilter();
    public function deleteByOrderId($order_id);
    
}
