<?php

namespace App\Repositories\Area;

use App\Models\Area;
use App\Repositories\BaseRepository;

class AreaRepository extends BaseRepository implements AreaRepositoryInterface
{
    public function getModel()
    {
        return Area::class;
    }

    public function getWithFilter($keyword, $paging, $page, $search_option = [])
    {
        //dd($customer_id);
        $keyword_val = isset($keyword) ? trim($keyword) : '';


        $data = $this->model::where(function ($query) use ($keyword_val, $search_option) {

            if ($keyword_val != '') {

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
