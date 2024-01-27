<?php

namespace App\Repositories\TicketType;

use App\Models\TicketType;
use App\Repositories\BaseRepository;

class TicketTypeRepository extends BaseRepository implements TicketTypeRepositoryInterface
{
    public function getModel()
    {
        return TicketType::class;
    }

    public function getWithFilter()
    {
    }

    public function getData($filter = [], $limit = 0, $with = [])
    {
        $data = $this->model->where('is_delete', 0);

        if (!empty($with)) {
            $data = $data->with($with);
        }

        if (!empty($filter['status'])) {
            $data = $data->where('status', $filter['status']);
        }

        if (!empty($filter['type_of_ticket'])) {
            if ($filter['type_of_ticket'] == 'online') {
                $data = $data->where('type', '!=', 1);
            } else {
                $data = $data->where('type', '!=', 2);
            }
        }

        if ($limit != 0) {
            return $data->paginate($limit);
        } else {
            return $data->get();
        }
    }
}
