<?php

namespace App\Repositories\Company;

use App\Models\Company;
use App\Repositories\BaseRepository;

class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{
    public function getModel()
    {
        return Company::class;
    }

    public function getWithFilter($keyword, $paging, $page, $search_option = [])
    {
        $keyword_val = isset($keyword) ? trim($keyword) : '';
        $data = $this->model::with(['ticketType', 'ticket', 'order.customer'])->where(function ($query) use ($keyword_val, $search_option) {

            if ($keyword_val != '') {

                $query->where(function ($q2) use ($keyword_val) {

                    //tìm theo mã vé :
                    $q2->whereHas('ticket', function ($q21) use ($keyword_val) {
                        $q21->where('code', 'like', '%' . $keyword_val . '%');
                    })
                        //tìm theo bảng hoá đơn : mã hoá đon
                        ->orWhereHas('order', function ($q22) use ($keyword_val) {
                            $q22->where('code_order', 'like', '%' . $keyword_val . '%');
                        })
                        //tìm theo khách hàng
                        ->orWhereHas('order.customer', function ($q23) use ($keyword_val) {
                            $q23->where('name', 'like', '%' . $keyword_val . '%')
                                ->orWhere('email', 'like', '%' . $keyword_val . '%')
                                ->orWhere('phone', 'like', '%' . $keyword_val . '%')
                                ->orWhere('address', 'like', '%' . $keyword_val . '%');
                        });
                });


            }

            if (!empty(session('search.area_id'))) {
                $query->where(function ($q1) {
                    $q1->where([["type", 1], ["type_id", session('search.area_id')]]);
                });
            }
            if (!empty(session('search.service_id'))) {
                $query->where("type", 2)->where("type_id", session('search.service_id'));
            }
            if (!empty(session('search.fun_spot_id'))) {
                $query->where("type", 3)->where("type_id", session('search.fun_spot_id'));
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


    public function getData($filter = [], $limit = 0, $with = [])
    {
        $data = $this->getAllNoneGet();

        $data = $data->with($with);

        // if (!empty($filter['key_search'])) {
        //     $data = $data->where('order_code', 'LIKE', "%" . $filter['key_search'] . "%")
        //         ->orWhereHas('customer', function ($query) use ($filter) {
        //             $query->where('name', 'LIKE', "%" . $filter['key_search'] . "%");
        //             $query->orWhere('email', 'LIKE', "%" . $filter['key_search'] . "%");
        //         });
        // }

        // if (!empty($filter['date'])) {
        //     // $data = $data->whereBe('created_at', $filter['date']);
        //     $data = $data->whereBetween('created_at', [$filter['date'] . ' 00:00:000', $filter['date'] . ' 23:59:000']);
        // }
        // dd($data->get());
        $data->orderBy('created_at', 'desc');

        if ($limit > 0) {
            return $data->paginate($limit);
        } else {
            return $data->get();
        }
    }


}
