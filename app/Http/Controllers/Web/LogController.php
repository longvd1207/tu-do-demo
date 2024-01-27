<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Repositories\Log\LogRepositoryInterface;

class LogController extends Controller
{
    //
    protected $logRepo;

    public function __construct(LogRepositoryInterface $logRepo)
    {
        $this->logRepo = $logRepo;
    }

    private function check()
    {
        $staff_id = 'CF0C3098-71F9-A3C4-ACE0-364227C2D60B';
        $data = $this->logRepo->getAll();

        $array = [];
        foreach ($data as $key => $value) {
            $data_old = json_decode($value->data_old, true);
            $data_new = json_decode($value->data_new, true);

            if (!empty($data_old)) {
                if ($data_old['staff_id'] == $staff_id) {
                    $array[][$value->id] = $value;
                    // dd($key, 'data_old', $value);
                }
            }

            if (!empty($data_new)) {
                if ($data_new['staff_id'] == $staff_id) {
                    $array[][$value->id] = $value;
                }
            }
        }
    }

    public function list(Request $request)
    {
        $this->check();
        $this->resetSessionSearch('admin/log');

        $this->authorize('index_log');

        $breadcrumb = [
            [
                'title' => 'Phân quyền',
                'route' => ''
            ],
            [
                'title' => 'TQuản lý log',
                'route' => 'log.index'
            ]
        ];

        if (!session()->has('search')) {
            session([
                'search' => [
                    'page' => 1,
                    'fromDate' => '',
                    'toDate' => '',
                ]
            ]);
        }

        //        $sessionSearch = [
        //            'page' => 1,
        //            'fromDate' => '',
        //            'toDate' => '',
        //        ];
        $search = $request->all();
        $queryWhere = [];

        if (isset($request->confirm_search) and $request->confirm_search == "1") {

            session(['search.page' => 1]);

            if (isset($request->start_date))
                session(['search.start_date' => trim($request->start_date)]);
            else
                session(['search.start_date' => ""]);


            if (isset($request->end_date))
                session(['search.end_date' => trim($request->end_date)]);
            else
                session(['search.end_date' => ""]);


            return redirect('admin/log');
        }

        //kích vào link chuyển trang
        if (isset($request->page) and (int)$request->page > 0) session(['search.page' => (int)$request->page]);

        $search_option = array();

        if (session('search.start_date') != '') {
            $startDate = \Illuminate\Support\Carbon::parse(session('search.start_date'))->startOfDay();
            $search_option[] = ['created_at', '>=', $startDate];
        }
        if (session('search.end_date') != '') {
            $endDate = Carbon::parse(session('search.end_date'))->endOfDay();
            $search_option[] = ['created_at', '<=', $endDate];
        }


        //        if (isset($search['fromDate'])) {
        //            $sessionSearch['fromDate'] = $search['fromDate'];
        //            $fromDate = Carbon::createFromFormat('d-m-Y H:i:s', $search['fromDate'] . ' 00:00:00');
        //            $queryWhere[] = ['created_at', '>=', $fromDate];
        //        }
        //        if (isset($search['toDate'])) {
        //            $sessionSearch['toDate'] = $search['toDate'];
        //            $toDate = Carbon::createFromFormat('d-m-Y H:i:s', $request->toDate . ' 23:59:59');
        //            $queryWhere[] = ['created_at', '<=', $toDate];
        //        }
        //  session(['search' => $sessionSearch]);

        $limit = 10;
        $logs = $this->logRepo->getWithFilter(session('search.key_search'), $limit, session('search.page'), $search_option);
        $total = count($this->logRepo->getWithFilter(session('search.key_search'), 0, -1, $search_option));


        //   $logs = $this->logRepo->lists('', $queryWhere, 10);

        return view('admin.log.index', [
            'logs' => $logs,
            'total' => $total,
            'limit' => $limit,
        ]);
    }
}
