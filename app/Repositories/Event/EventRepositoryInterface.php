<?php

namespace App\Repositories\Event;

use App\Repositories\BaseRepositoryInterface;

interface EventRepositoryInterface extends BaseRepositoryInterface
{
    public function getData($filter = [], $limit = 0, $with = []);

    public function getWithFilter($keyword, $paging, $page, $search_option = []);
}
