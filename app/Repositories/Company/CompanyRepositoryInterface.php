<?php

namespace App\Repositories\Company;

use App\Repositories\BaseRepositoryInterface;

interface CompanyRepositoryInterface extends BaseRepositoryInterface
{
    public function getData($filter = [], $limit = 0, $with = []);

    public function getWithFilter($keyword, $paging, $page, $search_option = []);
}
