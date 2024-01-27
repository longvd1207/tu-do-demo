<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Area\AreaRepositoryInterface;
use Illuminate\Http\Request;
use App\Repositories\Event\EventRepositoryInterface;
use App\Repositories\FunSpot\FunSpotRepositoryInterface;
use App\Repositories\Service\ServiceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\Event\EventExport;



class EventReportController extends Controller
{
    protected $eventRepo;
    protected $areaRepo;
    protected $serviceRepo;
    protected $funSpotRepo;


    public function __construct(
        EventRepositoryInterface $eventRepo,
        AreaRepositoryInterface $areaRepo,
        ServiceRepositoryInterface $serviceRepo,
        FunSpotRepositoryInterface $funSpotRepo
    ) {
        $this->eventRepo = $eventRepo;
        $this->areaRepo = $areaRepo;
        $this->serviceRepo = $serviceRepo;
        $this->funSpotRepo = $funSpotRepo;
    }

     //báo cáo sự kiện vào
    public function index(Request $request)
    {
        $this->resetSessionSearch('admin/eventReport');

        $this->authorize('index_event_report');

        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Báo cáo sự kiện vào',
                'route' => ''
            ]
        ];

        if (!session()->has('search')) {
            session([
                'search' => [
                    'page' => 1,
                    'key_search' => '',
                    'area_id',
                    'service_id',
                    'fun_spot_id',

                    'start_date',
                    'end_date',
                ]
            ]);
        }

        if (isset($request->confirm_search) and $request->confirm_search == "1") {

            session(['search.page' => 1]);

            if (isset($request->key_search))
                session(['search.key_search' => trim($request->key_search)]);
            else
                session(['search.key_search' => ""]);


            if (isset($request->event_code))
                session(['search.event_code' => trim($request->event_code)]);
            else
                session(['search.event_code' => ""]);


            if (isset($request->area_id))
                session(['search.area_id' => trim($request->area_id)]);
            else
                session(['search.area_id' => ""]);

            if (isset($request->service_id))
                session(['search.service_id' => trim($request->service_id)]);
            else
                session(['search.service_id' => ""]);

            if (isset($request->fun_spot_id))
                session(['search.fun_spot_id' => trim($request->fun_spot_id)]);
            else
                session(['search.fun_spot_id' => ""]);

            if (isset($request->start_date))
                session(['search.start_date' => trim($request->start_date)]);
            else
                session(['search.start_date' => ""]);


            if (isset($request->end_date))
                session(['search.end_date' => trim($request->end_date)]);
            else
                session(['search.end_date' => ""]);

            return redirect('admin/eventReport');
        }

        //kích vào link chuyển trang
        if (isset($request->page) and (int)$request->page > 0) session(['search.page' => (int)$request->page]);


        $limit = 20;
        $search_option = [];
        $search_option_service = [];
        $search_option_fun_spot = [];

        if (session('search.start_date') != '') {
            $startDate = \Illuminate\Support\Carbon::parse(session('search.start_date'))->startOfDay();
            $search_option[] = ['time_in', '>=', $startDate];
        }
        if (session('search.end_date') != '') {
            $endDate = Carbon::parse(session('search.end_date'))->endOfDay();
            $search_option[] = ['time_in', '<=', $endDate];
        }


        //    $data = $this->eventRepo->getData([], 0, ['ticketType', 'ticket', 'order.customer']);
        $data = $this->eventRepo->getWithFilter(session('search.key_search'), $limit, session('search.page'), $search_option);
        $total = count($this->eventRepo->getWithFilter(session('search.key_search'), 0, -1, $search_option));


        if (session('search.area_id') != '') {
            $search_option_service[] = ['area_id', '=', session('search.area_id')];
            $search_option_fun_spot[] = ['area_id', '=', session('search.area_id')];

        }

        $list_area = $this->areaRepo->getWithFilter('', 0, -1);

        $list_service = $this->serviceRepo->getWithFilter('', 0, -1, $search_option_service);

        $list_funSpot = $this->funSpotRepo->getWithFilter('', 0, -1, $search_option_fun_spot);


        return view('admin.eventReport.index', [
            'breadcrumb' => $breadcrumb,
            'data' => $data,
            'total' => $total,
            'list_area' => $list_area,
            'list_service' => $list_service,
            'list_funSpot' => $list_funSpot,

            'limit' => $limit,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $this->authorize('export_event_report');


        $search_option = [];
        $search_option_service = [];
        $search_option_fun_spot = [];


        if (session('search.start_date') != '') {
            $startDate = \Illuminate\Support\Carbon::parse(session('search.start_date'))->startOfDay();
            $search_option[] = ['time_in', '>=', $startDate];
        }
        if (session('search.end_date') != '') {
            $endDate = Carbon::parse(session('search.end_date'))->endOfDay();
            $search_option[] = ['time_in', '<=', $endDate];
        }

        //    $data = $this->eventRepo->getData([], 0, ['ticketType', 'ticket', 'order.customer']);
        $data = $this->eventRepo->getWithFilter(session('search.key_search'), 0, -1, $search_option);

        //tên của khu vực
        session(['search.area_name' => ""]);
        $area  = DB::select("select name from areas where is_delete=0 and [status]=1 and id=?",[session('search.area_id')]);
        if(!empty($area[0]->name)){
            session(['search.area_name' => $area[0]->name]);
        }


        session(['search.service_name' => ""]);
        $area  = DB::select("select name from services where is_delete=0 and [status]=1 and id=?",[session('search.service_id')]);
        if(!empty($area[0]->name)){
            session(['search.service_name' => $area[0]->name]);
        }

        session(['search.fun_spot_name' => ""]);
        $area  = DB::select("select name from fun_spots where is_delete=0 and [status]=1 and id=?",[session('search.fun_spot_id')]);
        if(!empty($area[0]->name)){
            session(['search.fun_spot_name' => $area[0]->name]);
        }



        return Excel::download(new EventExport($data), 'Bao_cao_su_kien_vao.xlsx');


    }
}
