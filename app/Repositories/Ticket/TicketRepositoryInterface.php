<?php

namespace App\Repositories\Ticket;

use App\Repositories\BaseRepositoryInterface;

interface TicketRepositoryInterface extends BaseRepositoryInterface
{
    public function getData($filter = [], $limit = 0, $with = []);
    public function createMutiData(array $dataCreate);
    public function deleteByOrderId($order_id);
    public function genCode();
}
