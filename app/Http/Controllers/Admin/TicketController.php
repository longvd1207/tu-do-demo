<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RevenueReport\RevenueReportUserExport;
use App\Exports\Ticket\ReportTicketExport;
use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Ticket\TicketRepository;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\TicketType\TicketTypeRepository;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;

class TicketController extends Controller
{
    public function __construct(
        protected TicketRepository     $ticketRepository,
        protected OrderRepository      $orderRepository,
        protected TicketTypeRepository $ticketTypeTypeRepository
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
                'title' => 'Danh sách vé',
                'route' => 'ticket.index'
            ]
        ];

        $this->resetSessionSearch('admin/ticket');

        if (isset($request->confirm_search) && $request->confirm_search == "1") {

            session(['search.page' => 1]);

            if (isset($request->num_show)) {
                session(['search.num_show' => trim($request->num_show)]);
            } else {
                session(['search.num_show' => ""]);
            }

            if (isset($request->use_date)) {
                session(['search.use_date' => trim($request->use_date)]);
            } else {
                session(['search.use_date' => ""]);
            }

            if (isset($request->code)) {
                session(['search.code' => trim($request->code)]);
            } else {
                session(['search.code' => ""]);
            }

            if (isset($request->order_code)) {
                session(['search.order_code' => trim($request->order_code)]);
            } else {
                session(['search.order_code' => ""]);
            }

            if (isset($request->status)) {
                session(['search.status' => trim($request->status)]);
            } else {
                session(['search.status' => ""]);
            }
        }
        $conditions = [];
        $condition_likes = [];
        $page = $request->page;
        $today = Carbon::now()->toDateString();

        $code = session('search.code');
        $order_code = session('search.order_code');
        $use_date = session('search.use_date') ?? $today;
        $status = session('search.status');
        $num_show = session('search.num_show');

        $conditions['is_delete'] = 0;
        if (isset($status)) {
            $conditions['status'] = $status == 1 ? 1 : 2;
        }

        if ($code) {
            $conditions['code'] = $code;
        }

        if ($use_date) {
            $conditions['use_date'] = $use_date;
        }

        if ($order_code) {
            $conditions['order_id'] = $this->orderRepository->getByField('code_order', $order_code)->first()->id ?? 'noFindCode';
        }

        if (companyIdByUser()) {
            $conditions['company_id'] = companyIdByUser();
        }

        $sumAllPrice = $this->ticketRepository->getAll()->sum('price');

        $limit = isset($num_show) > 0 ? $num_show : 30;

        $columns = ['*'];
        $tickets = $this->ticketRepository->paginateWhereLikeOrderBy($conditions, $condition_likes, 'updated_at', 'DESC', $page ?: 1, $limit, $columns);

        if ($request->is_export == 1) {
            return Excel::download(new ReportTicketExport($tickets), 'Bao_cao_tong_hop_ve.xlsx');
        }

        return view('admin.ticket.index', compact('tickets', 'today', 'sumAllPrice', 'breadcrumb', 'code'));
    }

    public function edit($id)
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Xem chi tiết vé',
                'route' => 'ticket.index'
            ]
        ];

        $ticket = $this->ticketRepository->getById($id);
        return view('admin.ticket.edit', compact('ticket', 'breadcrumb'));
    }


    public function changeStatusTicket(Request $request)
    {
        $ticket = $this->ticketRepository->getById($request['ticket_id']);
        try {
            if ($ticket) {
                $ticket->status = (int)$request->status == 1 ? 0 : 1;
                $ticket->save();
                return response()->json([
                    'message' => 'Update status ticket success',
                    'status' => 200
                ], 200);
            }
            return response()->json([
                'message' => 'Không tìm thấy mã vé hợp lệ',
                'status' => 401
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 401
            ], 401);
        }
    }


    public function getDetailTicket(Request $request)
    {
        $id = $request['ticket_id'];
        $ticket = $this->ticketRepository->getById($id);
        if ($ticket) {
            if ($ticket->maps) {
                $areas = [];
                $services = [];
                $fun_spots = [];
                foreach ($ticket->maps as $map) {
                    if ($map->type == 1) {
                        $areas[] = $map->getAreas->name;
                    }
                    if ($map->type == 2) {
                        $services[] = $map->getServices->name;
                    }
                    if ($map->type == 3) {
                        $fun_spots[] = $map->getFunSpots->name;
                    }
                }
            }
            return response()->json([
                'data' => [
                    'area' => $areas,
                    'service' => $services,
                    'fun_spots' => $fun_spots
                ],
                'message' => 'Tra ve ok',
                'status' => 200
            ], 200);
        }
        return response()->json([
            'message' => 'Mã vé không hợp lệ',
            'status' => 401
        ], 401);

    }

    public function destroy($id)
    {
        $ticket = $this->ticketRepository->getById($id);
        try {
            if ($ticket) {
                $ticket->delete();
                return redirect()->route('ticket.index')->withSuccess('Deleted');
            }
            return redirect()->route('ticket.index')->withErrors('Delete failed');
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 401
            ], 401);
        }
    }

    public function print($id)
    {
        $result = $this->ticketRepository->getById($id);
        $maps = $result->maps;

        if ($maps) {
            $areas = [];
            $services = [];
            $fun_spots = [];
            foreach ($maps as $map) {
                if ($map->type == 1) {
                    $areas[] = $map->getAreas->name ?? '';
                }
                if ($map->type == 2) {
                    $services[] = $map->getServices->name ?? '';
                }
                if ($map->type == 3) {
                    $fun_spots[] = $map->getFunSpots->name ?? '';
                }
            }
        }

        $qrCode = $this->generateAndSaveQrCode(100, $result->code);

        if (!$result) {
            return redirect()->back()->with('alert-error', 'Xuất vé lỗi!');
        }

        return view('admin.ticket.print_ticket', [
            'data' => $result,
            'qrCode' => $qrCode,
            'access' => [
                'area' => $areas,
                'service' => $services,
                'fun_spots' => $fun_spots
            ],
        ]);
        // return redirect()->back()->with('alert-success', 'Xuất vé thành công');
    }

}
