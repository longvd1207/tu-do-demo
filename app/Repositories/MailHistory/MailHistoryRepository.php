<?php

namespace App\Repositories\MailHistory;

use App\Models\MailHistory;
use App\Repositories\BaseRepository;

class MailHistoryRepository extends BaseRepository implements MailHistoryRepositoryInterface
{
    public function getModel()
    {
        return MailHistory::class;
    }

    public function getData($filter = [], $limit = 0, $with = [])
    {
        $data = $this->getAllNoneGet();

        $data = $data->with($with);

        if (!empty($filter['key_search'])) {
            $data = $data->where('order_code', 'LIKE', "%" . $filter['key_search'] . "%")
                ->orWhereHas('customer', function ($query) use ($filter) {
                    $query->where('name', 'LIKE', "%" . $filter['key_search'] . "%");
                    $query->orWhere('email', 'LIKE', "%" . $filter['key_search'] . "%");
                });
        }

        if (!empty($filter['date'])) {
            // $data = $data->whereBe('created_at', $filter['date']);
            $data = $data->whereBetween('created_at', [$filter['date'] . ' 00:00:000', $filter['date'] . ' 23:59:000']);
        }

        if ($limit > 0) {
            return  $data->paginate($limit);
        } else {
            return $data->get();
        }
    }
}
