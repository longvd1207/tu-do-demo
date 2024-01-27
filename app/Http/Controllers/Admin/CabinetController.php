<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Area\AreaRepository;

//use App\Repositories\Service\ServiceRepositoryInterface;
//use App\Repositories\FunSpot\FunSpotRepositoryInterface;

//use App\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Exception;

class CabinetController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Danh sách tủ đồ',
                'route' => 'cabinet.index'
            ]
        ];

        return view('admin.cabinet.index', compact( 'breadcrumb'));

    }

    public function create()
    {

        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Thêm mới tủ đồ',
                'route' => 'cabinet.create'
            ]
        ];

        return view('admin.cabinet.create', compact('breadcrumb'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:areas',
        ], [
            'name.required' => 'Tên khu vực không được để trống',
            'name.min' => 'Tên khu vực tối thiểu 3 ký tự',
            'name.unique' => 'Tên khu vực đã tồn tại'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        try {
            $data = [
                'id' => getGUID(),
                'name' => $request['name'],
                'description' => $request['description'],
                'status' => $request['status'],
                'company_id' => companyIdByUser() ?? $request->company_id
            ];
            $this->areaRepository->create($data);
            return redirect()->route('area.index')->withSuccess('Thêm khu vực thành công');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('Có lỗi xảy ra, vui lòng kiểm tra lại');
        }
    }

    public function edit($id)
    {
        $area = $this->areaRepository->getById($id);
        return view('admin.area.edit', compact('area'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:areas,name,'.$id.',id'
        ], [
            'name.required' => 'Tên khu vực không được để trống',
            'name.min' => 'Tên khu vực ít nhất 3 ký tự',
            'name.unique' => 'Tên khu vực đã tồn tại'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $area = $this->areaRepository->getById($id);

        try {
            if ($area) {
                $dataUpdate = [
                    'name' => $request['name'],
                    'description' => $request['description'],
                    'status' => $request['status'],
                    'company_id' => companyIdByUser() ?? $request->company_id
                ];
                $this->areaRepository->update($id, $dataUpdate);
                return redirect()->route('area.index')->withSuccess('Sửa khu vực thành công');
            }
            return redirect()->back()->withErrors('Không tìm thấy khu vực');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('Có lỗi xảy ra, vui lòng kiểm tra lại');
        }
    }

    public function destroy($id)
    {
        $area = $this->areaRepository->getById($id);
        try {
            if ($area) {
                $this->areaRepository->delete($id);
                return redirect()->route('area.index')->withSuccess('Xóa khu vực thành công');
            }
            return redirect()->back()->withErrors('Không tìm thấy khu vực');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('Có lỗi xảy ra, vui lòng kiểm tra lại');
        }
    }

    public function get_service_funspot_by_area($area_id)
    {

        $result = [
            "status" => 200,
            "list_service" => [],
            "list_fun_spot" => [],
        ];


        if (!empty($area_id)) {

            $list_service = DB::select("select id, name from services where is_delete=0 and area_id=? and [status]=1", [$area_id]);
            if (!empty($list_service)) {
                $result["list_service"] = $list_service;
            }

            $list_fun_spot = DB::select("select id, name from fun_spots where is_delete=0 and area_id=? and [status]=1", [$area_id]);
            if (!empty($list_fun_spot)) {
                log::error(111);
                $result["list_fun_spot"] = $list_fun_spot;
            }
        }


        return $result;

    }
}
