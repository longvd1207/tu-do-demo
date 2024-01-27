<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceIP;
use App\Repositories\Area\AreaRepository;
use App\Repositories\DeviceConfig\DeviceConfigRepository;
use App\Repositories\FunSpot\FunSpotRepository;
use App\Repositories\Service\ServiceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceConfigController extends Controller
{
    public function __construct(
        protected DeviceConfigRepository $deviceConfigRepository,
        protected AreaRepository         $areaRepository,
        protected FunSpotRepository      $funSpotRepository,
        protected ServiceRepository      $serviceRepository,
    )
    {
    }

    public function index(Request $request)
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Danh sách cấu hình',
                'route' => 'device.index'
            ]
        ];

        $conditions = [];
        $condition_likes = [];
        $page = $request->page;
        $keyword = $request->key_search;
        $num_show = $request->num_show;
        $conditions['is_delete'] = 0;
        if ($keyword) {
            $deviceIp = DeviceIP::query()->where('ip', $keyword)->first();
            if ($deviceIp) {
                $keyword = $deviceIp->device_id;
            }
            $condition_likes['id'] = $keyword;
        }

        if (companyIdByUser()) {
            $conditions['company_id'] = companyIdByUser();
        }


        $limit = isset($num_show) > 0 ? $num_show : 20;

        $columns = ["*"];
        $devices = $this->deviceConfigRepository->paginateWhereLikeOrderBy($conditions, $condition_likes, 'updated_at', 'DESC', $page ?: 1, $limit, $columns);
        return view('admin.device.index', compact('devices', 'breadcrumb'));
    }


    public function create()
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Cài đặt cấu hình',
                'route' => 'device.create'
            ]
        ];

        $areas = $this->areaRepository->getAll()->where('status', 1);
        $services = $this->serviceRepository->getAll()->where('status', 1);
        $fun_spots = $this->funSpotRepository->getAll()->where('status', 1);

        return view('admin.device.create', compact('breadcrumb', 'areas', 'services', 'fun_spots'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'ip' => 'required|unique:device_ip'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        try {
            $data = [
                'id' => getGUID(),
                'type' => $request->type,
                'company_id' => companyIdByUser() ?? $request->company_id,
                'is_delete' => 0
            ];
            if ($request->type == 1) {
                $area = [
                    'type_id' => $request->area_id
                ];
                $data = array_merge($data, $area);
            } elseif ($request->type == 2) {
                $service = [
                    'type_id' => $request->service_id
                ];
                $data = array_merge($data, $service);

            } elseif ($request->type == 3) {
                $fun_spot = [
                    'type_id' => $request->fun_spot_id
                ];
                $data = array_merge($data, $fun_spot);

            }
            $device = $this->deviceConfigRepository->create($data);

            foreach ($request->ip as $ip) {
                $dataDeviceIP = [
                    'id' => getGUID(),
                    'device_id' => $device->id,
                    'ip' => $ip,
                    'status' => 1,
                    'is_delete' => 0
                ];
                DeviceIP::query()->create($dataDeviceIP);
            }
            return redirect()->route('device.index')->with('alert-success', 'Add device successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('alert-error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Cập nhật cấu hình',
//                'route' => 'device.edit'
            ]
        ];
        $device = $this->deviceConfigRepository->getById($id);
        $areas = $this->areaRepository->getAll()->where('status', 1);
        $services = $this->serviceRepository->getAll()->where('status', 1);
        $fun_spots = $this->funSpotRepository->getAll()->where('status', 1);

        return view('admin.device.edit', compact('breadcrumb', 'device', 'areas', 'services', 'fun_spots'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'ip' => 'required|unique:device_ip,ip,'.$id.',device_id'
        ], [
            'type.required' => 'Kiểu dịch vụ bắt buộc',
            'ip.required' => 'Ít nhất phải có 1 địa chỉ IP',
            'ip.unique' => 'Địa chỉ IP đã tồn tại'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $device = $this->deviceConfigRepository->getById($id);
        if ($device) {
            try {
                $data = [
                    'id' => $device->id,
                    'type' => $request->type,
                    'company_id' => companyIdByUser() ?? $request->company_id,
                    'is_delete' => 0
                ];
                if ($request->type == 1) {
                    $area = [
                        'type_id' => $request->area_id
                    ];
                    $data = array_merge($data, $area);
                } elseif ($request->type == 2) {
                    $service = [
                        'type_id' => $request->service_id
                    ];
                    $data = array_merge($data, $service);

                } elseif ($request->type == 3) {
                    $fun_spot = [
                        'type_id' => $request->fun_spot_id
                    ];
                    $data = array_merge($data, $fun_spot);

                }
                $device = $this->deviceConfigRepository->updateT($id, $data);

                DeviceIP::query()->where('device_id', '=', $device->id)?->delete();
                foreach ($request->ip as $ip) {
                    $dataDeviceIP = [
                        'id' => getGUID(),
                        'device_id' => $device->id,
                        'ip' => $ip,
                        'status' => 1,
                        'is_delete' => 0
                    ];
                    DeviceIP::query()->create($dataDeviceIP);
                }

                return redirect()->route('device.index')->with('alert-success', 'Update device successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('alert-error', $e->getMessage());
            }
        }
        return redirect()->back()->with('alert-error', 'Không tìm thấy device');
    }


    public function destroy($id)
    {
        try {
            $this->deviceConfigRepository->delete($id);
            $deviceIp = DeviceIP::query()->where('device_id', '=', $id)->firstOrFail();
            if ($deviceIp) {
                $deviceIp->fill(['is_delete' => 1]);
                $deviceIp->save();
                $deviceIp->delete();
            }
            return redirect()->route('device.index')->with('alert-success', 'Deleted device successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('alert-error', $e->getMessage());
        }
    }
}
