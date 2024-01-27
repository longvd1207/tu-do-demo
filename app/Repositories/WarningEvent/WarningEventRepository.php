<?php

namespace App\Repositories\WarningEvent;

use App\Models\WarningEvent;
use App\Repositories\BaseRepository;


class WarningEventRepository extends BaseRepository implements WarningEventRepositoryInterface
{
    public function getModel()
    {
        return WarningEvent::class;
    }

    /**
     * @param $keyword : Từ khoá tìm kiếm
     * @param $paging : số bản ghi tren 1 trang
     * @param $page : trang hiện tại
     * @param $search_option : đk search dạng mảng , phần tử riêng biệt , ko phải mảng
     * @return mixed
     */
    public function getWithFilter($keyword, $paging, $page, $search_option = [])
    {
        //dd($customer_id);
        $keyword_val = isset($keyword) ? trim($keyword) : '';


        $data = $this->model::with('ticket','customer')->where(function ($query) use ($keyword_val, $search_option) {

            if ($keyword_val != '') {

                //tìm theo bảng cảnh báo warningEvent : description
                $query->where('description', 'like', '%' . $keyword_val . '%')
                    //tìm theo bảng vé : mã
                    ->orWhereHas('ticket', function ($q1) use ($keyword_val) {
                        $q1->where('code', 'like', '%' . $keyword_val . '%');
                    })
                    //tìm theo khách hàng
                    ->orWhereHas('customer', function ($q1) use ($keyword_val) {
                        $q1->where('name', 'like', '%' . $keyword_val . '%')
                            ->orWhere('email', 'like', '%' . $keyword_val . '%')
                            ->orWhere('phone', 'like', '%' . $keyword_val . '%')
                            ->orWhere('address', 'like', '%' . $keyword_val . '%');
                    });

            }

            if(!empty(session('search.area_id') )){
                $query->where(function ($q1) {
                    //tìm khu vực
                    $q1->where([["type", 1], ["type_id", session('search.area_id')]]);
                        //search dịch vụ
                     //   ->orWhereRaw("exists(select id from services where area_id=? and is_delete=0 and [status]=1)",[session('search.area_id')])
                        //tìm điểm vui chơi
                     //   ->orWhereRaw("exists(select id from fun_spots where area_id=? and is_delete=0 and [status]=1)",[session('search.area_id')]);
                });
            }
            if(!empty(session('search.service_id') )){
                $query->where("type",2)->where("type_id",session('search.service_id'));
            }
            if(!empty(session('search.fun_spot_id') )){
                $query->where("type",3)->where("type_id",session('search.fun_spot_id'));
            }

            $query->where('is_delete', '=', 0);

            // dd($search_option);

            // Log::error($search_option);
            //tìm kiếm trong đk tìm kiếm
            if (isset($search_option) and count($search_option) > 0) {
                foreach ($search_option as $v) {
                    // dd($v);
                    //nó ko phải là mảng của 3 phần tử  ,mà nó là 3 phần tử truyền vào thôi
                    $query->where($v[0], $v[1], $v[2]);
                }
            }


        });


        $data->orderBy('created_at', 'desc');

        if (isset($page) and $page == -1) {
            return $data->get();
        }

        return $data->paginate($paging);

    }

}
