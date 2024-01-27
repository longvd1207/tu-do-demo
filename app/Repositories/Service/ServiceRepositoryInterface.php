<?php

namespace App\Repositories\Service;

use App\Repositories\BaseRepositoryInterface;

interface ServiceRepositoryInterface extends BaseRepositoryInterface
{
    public function getWithFilter($keyword, $paging, $page, $search_option);
}
