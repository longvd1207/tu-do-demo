<?php

namespace App\Repositories\Ticket;

use App\Models\Ticket;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Nette\Utils\Random;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{
    public function getModel()
    {
        return Ticket::class;
    }

    public function createMutiData(array $dataCreate)
    {
        try {
            $data_return = [];
            DB::beginTransaction();
            foreach ($dataCreate as $value) {
                $data = $this->create($value);
                $data_return[] = $data;
                if (!$data) {
                    break;
                    return false;
                }
            }
            DB::commit();
            return $data_return;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function genCode($length = 9)
    {
        // $code = Str::upper(Random::generate(8));
        $characters = '0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        $data = $this->model->where('code', $code)->get();

        if (count($data) > 0) {
            $genCode = $this->genCode();
        } else {
            $genCode = $code;
        }
        return $genCode;
    }

    public function deleteByOrderId($order_id)
    {
        try {
            DB::beginTransaction();
            $data = $this->getAllNoneGet()->where('order_id', $order_id)->get();

            foreach ($data as $value) {
                $this->delete($value->id);
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function getData($filter = [], $limit = 0, $with = [])
    {
        $data = $this->getAllNoneGet();

        if (!empty($with)) {
            $data = $data->with($with);
        }

        if (!empty($filter['order_id'])) {
            $data = $data->where('order_id', $filter['order_id']);
        }

        if (!empty($filter['order_id'])) {
            $data = $data->where('order_id', $filter['order_id']);
        }

        if (!empty($filter['status'])) {
            $data = $data->where('status', $filter['status']);
        }

        if (!empty($filter['paymentStatus'])) {
            $data = $data->whereHas('order', function ($query) use ($filter) {
                $query->where('payment_status', $filter['paymentStatus']);
            });
        }

        if (!empty($filter['start_date'])) {
            $data = $data->where('created_at', '>=', $filter['start_date']);
        }

        if (!empty($filter['end_date'])) {
            $data = $data->where('created_at', '<=', $filter['end_date']);
        }

        if ($limit != 0) {
            return $data->paginate($limit);
        } else {
            return $data->get();
        }
    }
}
