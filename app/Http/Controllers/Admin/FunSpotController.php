<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Area\AreaRepository;
use App\Repositories\FunSpot\FunSpotRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class FunSpotController extends Controller
{
    protected $funSpotRepository;
    protected $areaRepository;
    public function __construct(
        FunSpotRepository $funSpotRepository,
        AreaRepository $areaRepository
    )
    {
        $this->areaRepository = $areaRepository;
        $this->funSpotRepository = $funSpotRepository;
    }

    public function index(Request $request)
    {
        $conditions = [];
        $condition_likes = [];
        $page = $request->page;
        $keyword = $request->key_search;
        $status = $request->status;
        $num_show = $request->num_show;

        if ($status ) {
            $conditions['status'] = $status == 1 ? 1 : 0;
        }
        $conditions['is_delete'] = 0;
        if ($keyword) {
            $condition_likes['name'] = $keyword;
        }

        if (companyIdByUser()) {
            $conditions['company_id'] = companyIdByUser();
        }

        $limit = isset($num_show) > 0 ? $num_show : 20;

        $columns = ['id', 'area_id', 'name', 'description', 'status', 'is_delete', 'created_at', 'updated_at'];
        $fun_spots = $this->funSpotRepository->paginateWhereLikeOrderBy($conditions, $condition_likes, 'updated_at', 'DESC', $page ?: 1, $limit, $columns);
        return view('admin.fun_spot.index', compact('fun_spots'));
    }

    public function create()
    {
        $areas = $this->areaRepository->getAll()->where('status', 1);
        return view('admin.fun_spot.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        try {
            $data = [
                'id' => getGUID(),
                'name' => $request['name'],
                'area_id' => $request['area_id'],
                'description' => $request['description'],
                'status' => $request['status'],
                'company_id' => companyIdByUser() ?? $request->company_id
            ];
            $this->funSpotRepository->create($data);
            return redirect()->route('fun-spot.index')->withSuccess('Thêm địa điểm vui chơi thành công');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('Có lỗi xảy ra, vui lòng thử lại');
        }
    }


    public function edit($id)
    {
        $areas = $this->areaRepository->getAll()->where('status', 1);
        $funSpot = $this->funSpotRepository->getById($id);
        return view('admin.fun_spot.edit', compact('funSpot', 'areas'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $funSpot = $this->funSpotRepository->getById($id);

        try {
            if ($funSpot) {
                $dataUpdate = [
                    'name' => $request['name'],
                    'area_id' => $request['area_id'],
                    'description' => $request['description'],
                    'status' => $request['status'],
                    'company_id' => companyIdByUser() ?? $request->company_id
                ];
                $this->funSpotRepository->update($id, $dataUpdate);
                return redirect()->route('fun-spot.index')->withSuccess('Sửa điểm vui chơi thành công');
            }
            return redirect()->back()->withErrors('Không tìm thấy điểm vui chơi');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('Có lỗi xảy ra, vui lòng kiểm tra lại');
        }
    }


    public function destroy($id)
    {
        $funSpot = $this->funSpotRepository->getById($id);
        try {
            if ($funSpot) {
                $this->funSpotRepository->delete($id);
                return redirect()->route('fun-spot.index')->withSuccess('Xóa điểm vui chơi thành công');
            }
            return redirect()->back()->withErrors('Không tìm thấy điểm vui chơi');
        } catch (\PHPUnit\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('Có lỗi xảy ra, vui lòng kiểm tra lại');
        }
    }
}
