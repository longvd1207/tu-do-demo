<?php

namespace App\Repositories\PaymentStatus;

use App\Models\PaymentStatus;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class PaymentStatusRepository extends BaseRepository implements PaymentStatusRepositoryInterface
{
    public function getModel()
    {
        return PaymentStatus::class;
    }

    public function getWithFilter()
    {
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
}
