<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RevenueReport\RevenueReportTicketExport;
use App\Http\Controllers\Controller;
use App\Repositories\Area\AreaRepositoryInterface;
use App\Repositories\Map\MapRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\PaymentStatus\PaymentStatusRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\TicketType\TicketTypeRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Exports\RevenueReport\RevenueReportUserExport;
use Maatwebsite\Excel\Facades\Excel;


class RevenueReportController extends Controller
{
    protected $orderRepository;

    public function __construct(
        OrderRepositoryInterface                   $orderRepository,
        protected TicketTypeRepositoryInterface    $ticketTypeRepository,
        protected UserRepositoryInterface          $userRepository,
        protected PaymentStatusRepositoryInterface $paymentStatusRepository,
        protected TicketRepositoryInterface        $ticketRepository,
        protected MapRepositoryInterface           $mapRepository,
        protected AreaRepositoryInterface          $areaRepository
    )
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Báo cáo doanh thu',
                'route' => 'order.index'
            ]
        ];
        return view('admin.revenueReport.index', compact('breadcrumb'));
    }


    public function filterOnline($start_date, $end_date)
    {
        return $this->orderRepository
            ->getAllNoneGet()
            ->when($start_date, fn($query) => $query->where('created_at', '>=', date($start_date) . ' 00:00:00'))
            ->when($end_date, fn($query) => $query->where('created_at', '<=', date($end_date) . ' 23:59:59'))
            ->where('payment_status', 2)
            ->where('type', 2)
            ->where('created_by', null);
    }

    /*
     *  BÁO CÁO DANH THU THEO NGƯỜI BÁN
     */
    public function reportWithUser(Request $request)
    {

        $this->resetSessionSearch('admin/RevenueReport/report_with_user');

        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Báo cáo danh thu theo người bán',
                // 'route' => 'service.index'
            ]
        ];


        if (isset($request->confirm_search) && $request->confirm_search == "1") {

            session(['search.page' => 1]);

            if (isset($request->num_show)) {
                session(['search.num_show' => trim($request->num_show)]);
            }
            else {
                session(['search.num_show' => ""]);
            }

            if (isset($request->start_date)) {
                session(['search.start_date' => trim($request->start_date)]);
            }
            else {
                session(['search.start_date' => ""]);
            }


            if (isset($request->end_date)) {
                session(['search.end_date' => trim($request->end_date)]);
            }
            else {
                session(['search.end_date' => ""]);
            }

            return redirect('admin/RevenueReport/report_with_user');
        }

        //kích vào link chuyển trang
        if (isset($request->page) and (int)$request->page > 0) {
            session(['search.page' => (int)$request->page]);
        }

        $start_date = session('search.start_date');
        $end_date = session('search.end_date');

        $conditions = [];
        $condition_likes = [];
        $page = session('search.page');
        $num_show = session('search.num_show');
        $limit = isset($num_show) > 0 ? $num_show : 20;

        $filterOnl = $this->filterOnline($start_date, $end_date);

        $countTicketOnl = $filterOnl->get()->flatMap->tickets->count();
        $sumRealAmountOnl = $filterOnl->sum('real_amount');
        $sumAmountOnl = $filterOnl->sum('amount');

        $columns = ['*'];
        $users = $this->userRepository->paginateWhereLikeOrderBy($conditions, $condition_likes, 'updated_at', 'DESC', $page ?: 1, $limit, $columns);
        return view('admin.revenueReport.revenueByUser',
            compact('users', 'sumAmountOnl', 'sumRealAmountOnl', 'countTicketOnl', 'start_date', 'end_date', 'breadcrumb'));


    }

    /*
    *   XUẤT EXCEL :  BÁO CÁO DANH THU THEO NGƯỜI BÁN
    */
    public function report_with_user_export_excel(Request $request)
    {

        $start_date = session('search.start_date');
        $end_date = session('search.end_date');

        $conditions = [];
        $condition_likes = [];
        $page = session('search.page');
        $num_show = session('search.num_show');
        $limit = isset($num_show) > 0 ? $num_show : 20;

        $filterOnl = $this->filterOnline($start_date, $end_date);

        $countTicketOnl = $filterOnl->get()->flatMap->tickets->count();
        $sumRealAmountOnl = $filterOnl->sum('real_amount');
        $sumAmountOnl = $filterOnl->sum('amount');

        $columns = ['*'];
        $data = $this->userRepository->paginateWhereLikeOrderBy($conditions, $condition_likes, 'updated_at', 'DESC', $page ?: 1, $limit, $columns);

        session(['search.sumRealAmountOnl'=>$sumRealAmountOnl]);
        session(['search.sumAmountOnl'=>$sumAmountOnl]);
        session(['search.countTicketOnl'=>$countTicketOnl]);


        return Excel::download(new RevenueReportUserExport($data, session('search')), 'Bao_cao_doanh_thu_theo_nguoi_ban.xlsx');
    }

    public function reportWithTicket(Request $request)
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Báo cáo doanh thu',
                'route' => 'revenueReport.index'
            ], [
                'title' => 'Chi tiết doanh thu theo loại vé',
                'route' => ''
            ]
        ];

        $now = Carbon::now();
        $month = $now->format('Y-m');


        if (!session()->has('search')) {
            session([
                'search' => [
                    'page' => 1,
                    'num_show' => '',
                    'start_date'=>Carbon::createFromFormat('Y-m', $month)->startOfMonth()->format('Y-m-d'),
                    'end_date'=>Carbon::createFromFormat('Y-m', $month)->endOfMonth()->format('Y-m-d')
                ]
            ]);
        }

        if (isset($request->confirm_search) and $request->confirm_search == "1") {

            session(['search.page' => 1]);

            if (isset($request->start_date)) {
                session(['search.start_date' => trim(date('Y-m-d', strtotime($request->start_date)))]);
            } else {
                session(['search.start_date' => ""]);
            }

            if (isset($request->end_date)) {
                session(['search.end_date' => trim(date('Y-m-d', strtotime($request->end_date)))]);
            } else {
                session(['search.end_date' => ""]);
            }

            return redirect('admin/RevenueReport/report_with_ticket');
        }

        $startOfMonth = null;
        if (!empty(session('search.start_date'))) {
            $startOfMonth = session('search.start_date') . ' 00:00:000';
        }

        $endOfMonth = null;
        if (!empty(session('search.end_date'))) {
            $endOfMonth = session('search.end_date') . ' 23:59:000';
        }


        $filter = [
            'start_date' => $startOfMonth,
            'end_date' => $endOfMonth,
            'paymentStatus' => 2,
            'status' => 1
        ];

        $ticket = $this->ticketRepository->getData($filter, 0, ['order']);
        $ticket = $ticket->groupBy('ticket_type_id');

        $filterOrder = [
            'start_date' => $startOfMonth,
            'end_date' => $endOfMonth,
            'paymentStatus' => 2,
        ];
        $order = $this->orderRepository->getData($filterOrder, 0, ['paymentStatus', 'tickets']);
        $total = $order->sum('amount');

        $ticketType = $this->ticketTypeRepository->getAll();


        return view(
            'admin.revenueReport.reportWithTicket',
            compact(
                'breadcrumb',
                'ticket',
                'ticketType',
                'total'
            )
        );
    }


    public function report_with_ticket_export_excel()
    {


        $startOfMonth = null;
        if (!empty(session('search.start_date'))) {
            $startOfMonth = session('search.start_date') . ' 00:00:000';
        }

        $endOfMonth = null;
        if (!empty(session('search.end_date'))) {
            $endOfMonth = session('search.end_date') . ' 23:59:000';
        }


        $filter = [
            'start_date' => $startOfMonth,
            'end_date' => $endOfMonth,
            'paymentStatus' => 2,
            'status' => 1
        ];

        $ticket = $this->ticketRepository->getData($filter, 0, ['order']);
        $ticket = $ticket->groupBy('ticket_type_id');

        $filterOrder = [
            'start_date' => $startOfMonth,
            'end_date' => $endOfMonth,
            'paymentStatus' => 2,
        ];
        $order = $this->orderRepository->getData($filterOrder, 0, ['paymentStatus', 'tickets']);
        $total = $order->sum('amount');

        $ticketType = $this->ticketTypeRepository->getAll();

        session(['search.ticketType'=>$ticketType]);
        session(['search.total'=>$total]);

        return Excel::download(new RevenueReportTicketExport($ticket, session('search')), 'Bao_cao_doanh_thu_theo_loai_ve.xlsx');

    }
}
