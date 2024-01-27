<?php

namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;


use App\Repositories\WarningEvent\WarningEventRepositoryInterface;
use App\Repositories\Service\ServiceRepositoryInterface;
use App\Repositories\FunSpot\FunSpotRepositoryInterface;
use App\Repositories\Area\AreaRepositoryInterface;
use App\Exports\WarningEvent\WarningEventExport;


use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;


class WarningEventController extends Controller
{
    protected $areaRepo;
    protected $serviceRepo;
    protected $funSpotRepo;
    protected $warningEventRepo;
    protected $staffRepo;
    protected $eatRepo;
    protected $locationEatRepo;
    protected $companyRepo;

    public function __construct(
        AreaRepositoryInterface         $areaRepo,
        ServiceRepositoryInterface      $serviceRepo,
        FunSpotRepositoryInterface      $funSpotRepo,
        WarningEventRepositoryInterface $warningEventRepo,


    )
    {
        $this->areaRepo = $areaRepo;
        $this->warningEventRepo = $warningEventRepo;
        $this->serviceRepo = $serviceRepo;
        $this->funSpotRepo = $funSpotRepo;

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {

        //  dd($request->all());

        $this->resetSessionSearch('admin/warningEventReport');

        $this->authorize('index_warning_event');

        $breadcrumb = [
            [
                'title' => 'Báo cáo',
                'route' => ''
            ],
            [
                'title' => 'Thông tin cảnh báo',
                'route' => ''
            ]
        ];

        if (!session()->has('search')) {
            session([
                'search' => [
                    'page' => 1,
                    'key_search' => '',
                    'event_code',

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

            return redirect('admin/warningEventReport');
        }

        //kích vào link chuyển trang
        if (isset($request->page) and (int)$request->page > 0) session(['search.page' => (int)$request->page]);

        $limit = 20;
        $search_option = [];
        $search_option_service = [];
        $search_option_fun_spot = [];

        if (session('search.event_code') != '') {
            $search_option[] = ['event_code', '=', session('search.event_code')];
        }
        if (session('search.start_date') != '') {
            $startDate = Carbon::parse(session('search.start_date'))->startOfDay();
            $search_option[] = ['created_at', '>=', $startDate];
        }
        if (session('search.end_date') != '') {
            $endDate = Carbon::parse(session('search.end_date'))->endOfDay();
            $search_option[] = ['created_at', '<=', $endDate];
        }

        $data = $this->warningEventRepo->getWithFilter(session('search.key_search'), $limit, session('search.page'), $search_option);

        $total = count($this->warningEventRepo->getWithFilter(session('search.key_search'), 0, -1, $search_option));

        if (session('search.area_id') != '') {
            $search_option_service[] = ['area_id', '=', session('search.area_id')];
            $search_option_fun_spot[] = ['area_id', '=', session('search.area_id')];

        }

        $list_area = $this->areaRepo->getWithFilter('', 0, -1);

        $list_service = $this->serviceRepo->getWithFilter('', 0, -1, $search_option_service);

        $list_funSpot = $this->funSpotRepo->getWithFilter('', 0, -1, $search_option_fun_spot);


        return view('admin.warning_event.index', [
            'list_area' => $list_area,
            'list_service' => $list_service,
            'list_funSpot' => $list_funSpot,
            'breadcrumb' => $breadcrumb,
            'data' => $data,
            'total' => $total,
            'limit' => $limit,
        ]);
    }


    public function exportExcel(Request $request)
    {
        $this->authorize('export_warning_event');

        $search_option = [];


        if (session('search.event_code') != '') {
            $search_option[] = ['event_code', '=', session('search.event_code')];
        }
        if (session('search.start_date') != '') {
            $startDate = Carbon::parse(session('search.start_date'))->startOfDay();
            $search_option[] = ['created_at', '>=', $startDate];
        }
        if (session('search.end_date') != '') {
            $endDate = Carbon::parse(session('search.end_date'))->endOfDay();
            $search_option[] = ['created_at', '<=', $endDate];
        }

        $data = $this->warningEventRepo->getWithFilter(session('search.key_search'), 0, -1, $search_option);

        //tên của khu vực
        session(['search.area_name' => ""]);
        $area = DB::select("select name from areas where is_delete=0 and [status]=1 and id=?", [session('search.area_id')]);
        if (!empty($area[0]->name)) {
            session(['search.area_name' => $area[0]->name]);
        }


        session(['search.service_name' => ""]);
        $area = DB::select("select name from services where is_delete=0 and [status]=1 and id=?", [session('search.service_id')]);
        if (!empty($area[0]->name)) {
            session(['search.service_name' => $area[0]->name]);
        }

        session(['search.fun_spot_name' => ""]);
        $area = DB::select("select name from fun_spots where is_delete=0 and [status]=1 and id=?", [session('search.fun_spot_id')]);
        if (!empty($area[0]->name)) {
            session(['search.fun_spot_name' => $area[0]->name]);
        }

        return Excel::download(new WarningEventExport($data, session('search')), 'Báo cáo sự kiện cảnh báo.xlsx');

    }


    public function index_cu(Request $request)
    {

        //  dd($request->all());

        $this->resetSessionSearch('admin/warningEventReport');

        $this->authorize('index_warning_event');

        $breadcrumb = [
            [
                'title' => 'Danh mục',
                'route' => ''
            ],
            [
                'title' => 'Thông tin cảnh báo',
                'route' => ''
            ]
        ];

        if (!session()->has('search')) {
            session([
                'search' => [
                    'page' => 1,
                    'key_search' => '',
                    'event_code',
                    'card_id',
                    'staff_id',
                    'company_id',
                    'eat_id',
                    'location_eat_id',
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

            if (isset($request->card_id)) {

                $card_id = trim($request->card_id);
                if (strlen($card_id) == 10) {
                    $card_id = dechex((int)$card_id);
                    $card_id = strtoupper($card_id);
                }
                session(['search.card_id' => $card_id]);
            } else
                session(['search.card_id' => ""]);

            if (isset($request->staff_id))
                session(['search.staff_id' => trim($request->staff_id)]);
            else
                session(['search.staff_id' => ""]);

            if (isset($request->eat_id))
                session(['search.eat_id' => trim($request->eat_id)]);
            else
                session(['search.eat_id' => ""]);

            if (isset($request->company_id))
                session(['search.company_id' => trim($request->company_id)]);
            else
                session(['search.company_id' => ""]);

            if (isset($request->location_eat_id))
                session(['search.location_eat_id' => trim($request->location_eat_id)]);
            else
                session(['search.location_eat_id' => ""]);

            if (isset($request->start_date))
                session(['search.start_date' => trim($request->start_date)]);
            else
                session(['search.start_date' => ""]);


            if (isset($request->end_date))
                session(['search.end_date' => trim($request->end_date)]);
            else
                session(['search.end_date' => ""]);

            return redirect('admin/warningEventReport');
        }

        //kích vào link chuyển trang
        if (isset($request->page) and (int)$request->page > 0) session(['search.page' => (int)$request->page]);


        //  dd($request->key_search,session()->all());

        $search_option = array();

        if (session('search.event_code') != '') {
            $search_option[] = ['event_code', '=', session('search.event_code')];
        }
        if (session('search.card_id') != '') {
            $search_option[] = ['card_id', '=', session('search.card_id')];
        }

        if (session('search.staff_id') != '') {
            $search_option[] = ['staff_id', '=', session('search.staff_id')];
        }
        if (session('search.eat_id') != '') {
            $search_option[] = ['eat_id', '=', session('search.eat_id')];
        }
        if (session('search.company_id') != '') {
            $search_option[] = ['company_id', '=', session('search.company_id')];
        }

        if (session('search.location_eat_id') != '') {
            $search_option[] = ['location_eat_id', '=', session('search.location_eat_id')];
        }

        if (session('search.start_date') != '') {
            $startDate = Carbon::parse(session('search.start_date'))->startOfDay();
            $search_option[] = ['created_at', '>=', $startDate];
        }
        if (session('search.end_date') != '') {
            $endDate = Carbon::parse(session('search.end_date'))->endOfDay();
            $search_option[] = ['created_at', '<=', $endDate];
        }


        $limit = 20;
        $data = $this->warningEventRepo->getWithFilter(session('search.key_search'), $limit, session('search.page'), $search_option);

//        if($data->count()>0){
//            foreach ($data as $key=>$item){
//
//                $data[$key]["company_name"] ="";
//                $company_id = DB::select("select company_id from tbl_staff where id = (select staff_id from tbl_warning_event where id=?)",[$item["id"]]);
//                if(isset($company_id[0]->company_id)) {
//                    $val_company = DB::select("select name from tbl_company where id = ?",[$company_id[0]->company_id]);
//                    if(isset($val_company[0]->name)) {
//                        $data[$key]["company_name"] =$val_company[0]->name;
//                    }
//                }
//
//            }
//        }

//        dd($data);

        $auth_company = $this->getListCompanyForUser();

        $total = count($this->warningEventRepo->getWithFilter(session('search.key_search'), 0, -1, $search_option));

        $list_staff = $this->staffRepo->getWithFilter('', 0, -1, [], $auth_company);


        $list_eat = $this->eatRepo->getWithFilter('', -1, []);

        $list_company = $this->companyRepo->getWithFilter('', -1, [], $auth_company);

        $list_location_eat = $this->locationEatRepo->getWithFilter('', -1, []);

//        dd($data);
        return view('admin.warning_event.index', [
            'data' => $data,
            'total' => $total,
            'limit' => $limit,
            'breadcrumb' => $breadcrumb,
            'list_staff' => $list_staff,
            'list_eat' => $list_eat,
            'list_location_eat' => $list_location_eat,
            'list_company' => $list_company,

        ]);
    }


}
