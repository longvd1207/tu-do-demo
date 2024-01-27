<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Area\AreaRepository;
use App\Repositories\FunSpot\FunSpotRepository;
use App\Repositories\Map\MapRepository;
use App\Repositories\Service\ServiceRepository;
use App\Repositories\TicketType\TicketTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class TypeTicketController extends Controller
{
    public function __construct(
        protected TicketTypeRepository $ticketTypeTypeRepository,
        protected AreaRepository       $areaRepository,
        protected ServiceRepository    $serviceRepository,
        protected FunSpotRepository    $funSpotRepository,
        protected MapRepository        $mapRepository
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
                'title' => 'Danh sách loại vé',
                'route' => 'type_ticket.index'
            ]
        ];

        $conditions = [];
        $condition_likes = [];
        $page = $request->page;
        $keyword = $request->key_search;
        $status = $request->status;
        $num_show = $request->num_show;

        if ($status) {
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

        $columns = ['*'];
        $type_tickets = $this->ticketTypeTypeRepository->paginateWhereLikeOrderBy($conditions, $condition_likes, 'updated_at', 'DESC', $page ?: 1, $limit, $columns);
        return view('admin.type_ticket.index', compact('type_tickets', 'breadcrumb'));
    }


    public function create()
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Thêm mới kiểu vé',
                'route' => 'type_ticket.create'
            ]
        ];
        return view('admin.type_ticket.create', compact('breadcrumb'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'required',
        ], [
            'name.required' => 'Tên loại vé không được để trống',
            'name.string' => 'Tên loại vé không hợp lệ',
            'type.required' => 'Kiểu thanh toán bắt buộc'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        try {
            DB::beginTransaction();
            $data = [
                'id' => getGUID(),
                'name' => $request->name,
                'type' => $request->type,
                'price_online' => $request->price_online,
                'price_offline' => $request->price_offline,
                'status' => $request->status,
                'company_id' => companyIdByUser() ?? $request->company_id
            ];
            $ticketType = $this->ticketTypeTypeRepository->create($data);

            if ($request->area) {
                $areas = $request->area;
                $areaConvert = array_combine($areas, array_fill(0, count($areas), config('type.area')));
                if ($request->service) {
                    $services = $request->service;
                    $serviceConvert = array_combine($services, array_fill(0, count($services), config('type.service')));
                }
                if ($request->funSpot) {
                    $fun_spots = $request->funSpot;
                    $funSpotConvert = array_combine($fun_spots, array_fill(0, count($fun_spots), config('type.fun_spot')));
                }
                $typeIds = array_merge($areaConvert, $serviceConvert ?? [], $funSpotConvert ?? []);
                foreach ($typeIds as $typeId => $type) {
                    $maps = [
                        'id' => getGUID(),
                        'ticket_type_id' => $ticketType->id,
                        'type_id' => $typeId,
                        'type' => $type
                    ];
                    $this->mapRepository->create($maps);
                }
            }
            DB::commit();
            return redirect()->route('type_ticket.index')->withSuccess('Thêm mới thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
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
                'title' => 'Sửa kiểu vé',
//                'route' => 'type_ticket.edit'
            ]
        ];

        $areaByTicketTypeIds =
            $this->mapRepository
                ->getByField('ticket_type_id', $id)
                ->where('type', 1)
                ->pluck('type_id')
                ->toArray();
        $servicesByTicketTypeIds =
            $this->mapRepository
                ->getByField('ticket_type_id', $id)
                ->where('type', 2)
                ->pluck('type_id')
                ->toArray();
        $funSpotsByTicketTypeIds =
            $this->mapRepository
                ->getByField('ticket_type_id', $id)
                ->where('type', 3)
                ->pluck('type_id')
                ->toArray();

        $funSpots = $this->funSpotRepository->getAllNoneGet()->whereIn('area_id', $areaByTicketTypeIds)->get()->toArray();
        $services = $this->serviceRepository->getAllNoneGet()->whereIn('area_id', $areaByTicketTypeIds)->get()->toArray();

        $areas = $this->areaRepository->getAll();
        $type_ticket = $this->ticketTypeTypeRepository->getById($id);
        return view('admin.type_ticket.edit',
            compact('type_ticket',
                'breadcrumb',
                'areaByTicketTypeIds',
                'servicesByTicketTypeIds', 'funSpotsByTicketTypeIds',
                'areas', 'funSpots', 'services'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }
        $type_ticket = $this->ticketTypeTypeRepository->getById($id);
        try {
            if ($type_ticket) {
                if (true) {
                    $selectedAreas = is_array($request->area) ? $request->area : [];
                    $existingTypeIds = $type_ticket->maps
                        ->where('type', 1)
                        ->pluck('type_id')
                        ->toArray();
                    $idsToDelete = array_diff($existingTypeIds, $selectedAreas);
                    $type_ticket->maps()->where('type', 1)->whereIn('type_id', $idsToDelete)->delete();
                    if ($request->area) {
                        foreach ($request->area as $areaId) {
                            $findRecordExist = $this->mapRepository->getAllNoneGet()
                                ->where('ticket_type_id', $type_ticket->id)
                                ->where('type_id', $areaId)
                                ->where('type', 1)
                                ->get();

                            if (count($findRecordExist) === 0) {
                                $data = [
                                    'id' => getGUID(),
                                    'ticket_type_id' => $type_ticket->id,
                                    'type_id' => $areaId,
                                    'type' => 1
                                ];
                                $this->mapRepository->create($data);
                            }
                        }
                    }
                }

                if (true) {
                    $selectedAreas = is_array($request->service) ? $request->service : [];
                    $existingTypeIds = $type_ticket->maps()->where('type', 2)->pluck('type_id')->toArray();

                    $idsToDelete = array_diff($existingTypeIds, $selectedAreas);

                    $type_ticket->maps()->where('type', 2)->whereIn('type_id', $idsToDelete)->delete();
                    if ($request->service) {
                        foreach ($request->service as $serviceId) {
                            $findRecordExist = $this->mapRepository->getAllNoneGet()
                                ->where('ticket_type_id', $type_ticket->id)
                                ->where('type_id', $serviceId)
                                ->where('type', 2)
                                ->get();
                            if (count($findRecordExist) === 0) {
                                $data = [
                                    'id' => getGUID(),
                                    'ticket_type_id' => $type_ticket->id,
                                    'type_id' => $serviceId,
                                    'type' => 2
                                ];
                                $this->mapRepository->create($data);
                            }
                        }

                    }
                }

                if (true) {
                    $selectedAreas = is_array($request->funSpot) ? $request->funSpot : [];
                    $existingTypeIds = $type_ticket->maps()->where('type', 3)->pluck('type_id')->toArray();

                    $idsToDelete = array_diff($existingTypeIds, $selectedAreas);

                    $type_ticket->maps()->where('type', 3)->whereIn('type_id', $idsToDelete)->delete();
                    if ($request->funSpot) {
                        foreach ($request->funSpot as $fun_spot_id) {
                            $findRecordExist = $this->mapRepository->getAllNoneGet()
                                ->where('ticket_type_id', $type_ticket->id)
                                ->where('type_id', $fun_spot_id)
                                ->where('type', 3)
                                ->get();
                            if (count($findRecordExist) === 0) {
                                $data = [
                                    'id' => getGUID(),
                                    'ticket_type_id' => $type_ticket->id,
                                    'type_id' => $fun_spot_id,
                                    'type' => 3
                                ];
                                $this->mapRepository->create($data);
                            }
                        }
                    }
                }
                $data = [
                    'name' => $request->name,
                    'type' => $request->type,
                    'price_online' => $request->price_online,
                    'price_offline' => $request->price_offline,
                    'status' => $request->status,
                    'company_id' => companyIdByUser() ?? $request->company_id
                ];
                $this->ticketTypeTypeRepository->update($id, $data);
                return redirect()->route('type_ticket.index')->with('success', 'Sửa thành công');
            }
            return back()->withErrors('ID không hợp lệ');

        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $ticket_type = $this->ticketTypeTypeRepository->getById($id);
        try {
            if ($ticket_type) {
                $this->mapRepository->getByField('ticket_type_id', $id)->delete();
                $this->ticketTypeTypeRepository->delete($id);
                return redirect()->route('type_ticket.index')->withSuccess('Xóa loại vé thành công');
            }
            return redirect()->back()->withErrors('Không tìm thấy');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors('Có lỗi xảy ra, vui lòng kiểm tra lại');
        }
    }

    public function changeDataByArea(Request $request)
    {
        $fun_spots = $this->funSpotRepository->getAllNoneGet()->whereIn('area_id',$request['data'])->pluck('name', 'id')->toArray();
        $services = $this->serviceRepository->getAllNoneGet()->whereIn('area_id',$request['data'])->pluck('name', 'id')->toArray();
        return response()->json([
            'data' => [
                'services' => $services,
                'fun_spots' => $fun_spots
            ],
            'status' => 200,
            'message' => 'OK'
        ], 200);
    }
}
