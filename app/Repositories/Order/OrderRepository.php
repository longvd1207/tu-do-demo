<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\BaseRepository;
use Illuminate\Support\Str;
use Nette\Utils\Random;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function getModel()
    {
        return Order::class;
    }

    public function getData($filter = [], $limit = 0, $with = [])
    {
        $data = $this->getAllNoneGet();

        if (!empty($with)) {
            $data = $data->with($with);
        }

        if (!empty($filter['start_date'])) {
            $data = $data->where('created_at', '>=', $filter['start_date']);
        }

        if (!empty($filter['end_date'])) {
            $data = $data->where('created_at', '<=', $filter['end_date']);
        }

        if (!empty($filter['paymentStatus'])) {
            $data = $data->where('payment_status', $filter['paymentStatus']);
        }

        if ($limit > 0) {
            return $data->paginate($limit);
        }

        return $data->get();
    }

    public function genCode($length = 9)
    {
        $characters = '0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        $data = $this->model->where('code_order', $code)->get();
        if (count($data) > 0) {
            $genCode = $this->genCode();
        } else {
            $genCode = $code;
        }
        return $genCode;
    }
}
