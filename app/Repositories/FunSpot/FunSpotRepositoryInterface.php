<?php

namespace App\Repositories\FunSpot;

use App\Repositories\BaseRepositoryInterface;

interface FunSpotRepositoryInterface extends BaseRepositoryInterface
{
    public function getWithFilter($keyword, $paging, $page, $search_option);
}
