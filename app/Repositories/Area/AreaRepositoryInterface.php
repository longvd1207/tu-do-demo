<?php

namespace App\Repositories\Area;

use App\Repositories\BaseRepositoryInterface;

interface AreaRepositoryInterface extends BaseRepositoryInterface
{
    public function getWithFilter($keyword, $paging, $page, $search_option);
}
