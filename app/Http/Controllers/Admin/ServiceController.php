<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Area\AreaRepository;
use App\Repositories\Service\ServiceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class ServiceController extends Controller
{
    protected $areaRepository;
    protected $serviceRepository;

    public function __construct(
        AreaRepository    $areaRepository,
        ServiceRepository $serviceRepository
    )
    {
        $this->areaRepository = $areaRepository;
        $this->serviceRepository = $serviceRepository;
    }


    public function index(Request $request)
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Dịch vụ',
                'route' => 'service.index'
            ]
        ];
        $conditions = [];
        $condition_likes = [];
        $page = $request->page;
        $keyword = $request->key_search;
        $status = $request->status;
        $num_show = $request->num_show;

        if ($status && $status != 'all') {
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
        $services = $this->serviceRepository->paginateWhereLikeOrderBy($conditions, $condition_likes, 'updated_at', 'DESC', $page ?: 1, $limit, $columns);
        return view('admin.service.index', compact('services', 'breadcrumb'));
    }

    public function create()
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Thêm dịch vụ',
                'route' => 'service.create'
            ]
        ];
        $areas = $this->areaRepository->getAll()->where('status', 1);
        return view('admin.service.create', compact('areas', 'breadcrumb'));
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
            $this->serviceRepository->create($data);
            return redirect()->route('service.index')->withSuccess('Thêm dịch vụ thành công');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('Có lỗi xảy ra, vui lòng thử lại');
        }
    }

    public function edit($id)
    {
        $areas = $this->areaRepository->getAll()->where('status', 1);
        $service = $this->serviceRepository->getById($id);
        return view('admin.service.edit', compact('service', 'areas'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $service = $this->serviceRepository->getById($id);

        try {
            if ($service) {
                $dataUpdate = [
                    'name' => $request['name'],
                    'area_id' => $request['area_id'],
                    'description' => $request['description'],
                    'status' => $request['status'],
                    'company_id' => companyIdByUser() ?? $request->company_id
                ];
                $this->serviceRepository->update($id, $dataUpdate);
                return redirect()->route('service.index')->withSuccess('Sửa dịch vụ thành công');
            }
            return redirect()->back()->withErrors('Không tìm thấy dịch vụ');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('Có lỗi xảy ra, vui lòng kiểm tra lại');
        }
    }

    public function destroy($id)
    {
        $service = $this->serviceRepository->getById($id);
        try {
            if ($service) {
                $this->serviceRepository->delete($id);
                return redirect()->route('service.index')->withSuccess('Xóa dịch vụ thành công');
            }
            return redirect()->back()->withErrors('Không tìm thấy dịch vụ');
        } catch (\PHPUnit\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('Có lỗi xảy ra, vui lòng kiểm tra lại');
        }
    }
}
