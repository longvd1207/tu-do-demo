<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Order\ReportOrderExport;
use App\Exports\Ticket\ReportTicketExport;
use App\Http\Controllers\Controller;
use App\Repositories\Area\AreaRepository;
use App\Repositories\Map\MapRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\PaymentStatus\PaymentStatusRepository;
use App\Repositories\Ticket\TicketRepository;
use App\Repositories\TicketType\TicketTypeRepository;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;
use Nette\Utils\Random;

class OrderController extends Controller
{
    public function __construct(
        protected OrderRepository         $orderRepository,
        protected TicketTypeRepository    $ticketTypeRepository,
        protected UserRepository          $userRepository,
        protected PaymentStatusRepository $paymentStatusRepository,
        protected TicketRepository        $ticketRepository,
        protected MapRepository           $mapRepository,
        protected AreaRepository          $areaRepository
    ) {
    }

    public function index(Request $request)
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Danh sách hóa đơn',
                'route' => 'order.index'
            ]
        ];

        $this->resetSessionSearch('admin/order');

        if (isset($request->confirm_search) && $request->confirm_search == "1") {

            session(['search.page' => 1]);

            if (isset($request->num_show)) {
                session(['search.num_show' => trim($request->num_show)]);
            } else {
                session(['search.num_show' => ""]);
            }

            if (isset($request->created_at)) {
                session(['search.created_at' => trim($request->created_at)]);
            } else {
                session(['search.created_at' => ""]);
            }

            if (isset($request->user_id)) {
                session(['search.user_id' => trim($request->user_id)]);
            } else {
                session(['search.user_id' => ""]);
            }

            if (isset($request->code_order)) {
                session(['search.code_order' => trim($request->code_order)]);
            } else {
                session(['search.code_order' => ""]);
            }

            if (isset($request->payment_status)) {
                session(['search.payment_status' => trim($request->payment_status)]);
            } else {
                session(['search.payment_status' => ""]);
            }
        }


        $conditions = [];
        $condition_likes = [];
        $page = $request->page;
        $today = Carbon::now()->toDateString();

        $code_order = session('search.code_order');
        $user_id = session('search.user_id');
        $created_at = session('search.created_at') ?? $today;
        $payment_status = session('search.payment_status');
        $num_show = session('search.num_show');

        $conditions['is_delete'] = 0;

        if ($user_id) {
            $conditions['created_by'] = $user_id;
        }
        if ($payment_status) {
            $conditions['payment_status'] = $payment_status;
        }

        if ($created_at) {
            $startDateTime = $created_at . ' 00:00:00';
            $endDateTime = $created_at . ' 23:59:59.999';

            $conditions[] = ['created_at', '>=', $startDateTime];
            $conditions[] = ['created_at', '<=', $endDateTime];
        }

        if ($code_order) {
            $condition_likes['code_order'] = $code_order;
        }

        if (companyIdByUser()) {
            $conditions['company_id'] = companyIdByUser();
        }

        $sumAllOrderAmount = $this->orderRepository->getAll()->whereIn('payment_status', 2)->sum('amount');
        $sumAllOrderRealAmount = $this->orderRepository->getAll()->whereIn('payment_status', 2)->sum('real_amount');
        $limit = isset($num_show) > 0 ? $num_show : 30;
        $users = $this->userRepository->getAll();
        $columns = ['*'];
        $orders = $this->orderRepository->paginateWhereLikeOrderBy($conditions, $condition_likes, 'updated_at', 'DESC', $page ?: 1, $limit, $columns);

        // nếu là excel thì xuất
        if ($request->is_export == 1) {
            return Excel::download(new ReportOrderExport($orders, session('search')), 'Bao_cao_tong_hop_hoa_don.xlsx');
        }
        return view('admin.order.index', compact('orders','today', 'sumAllOrderAmount', 'sumAllOrderRealAmount', 'users', 'breadcrumb', 'code_order'));
    }

    public function create()
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Bán vé',
                'route' => 'order.create'
            ]
        ];

        // $type_tickets = $this->ticketTypeRepository->getAll()->where('status', 1);
        $type_tickets = $this->ticketTypeRepository->getData(['type_of_ticket' => 'offline']);
        $users = $this->userRepository->getAll();
        // return view('admin.order.create', compact('type_tickets', 'breadcrumb', 'users'));
        return view('admin.order.create_v2', compact('type_tickets', 'breadcrumb', 'users'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'qty_ticket_type_id' => 'required',
            // 'use_date' => 'date|after_or_equal:' . Carbon::now()->format('Y-m-d')
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        DB::beginTransaction();
        try {
            $sum = 0;
            $orderId = getGUID();
            $type = 1;
            foreach ($request->qty_ticket_type_id as $ticket_type_id => $qty) {
                if ($qty != 0 && $qty != null) {
                    $type_ticket = $this->ticketTypeRepository->getById($ticket_type_id);
                    $price_offline = $type_ticket->price_offline;
                    $amount = $price_offline * $qty;
                    $sum += $amount;
                    $type_ticket_name = $type_ticket->name;
                    $type_ticket_id = $type_ticket->id;
                    for ($i = 0; $i < $qty; $i++) {
                        $dataTicket = [
                            'id' => getGUID(),
                            'ticket_type_name' => $type_ticket_name,
                            'ticket_type_id' => $type_ticket_id,
                            'use_date' => $request->use_date,
                            'order_id' => $orderId,
                            'code' => Str::upper(Random::generate(8)),
                            'price' => $price_offline,
                            'qr_code' => '',
                            'status' => (int)$request->status == 2 ? '1' : '2',
                            'company_id' => companyIdByUser() ?? $request->company_id
                        ];
                        $this->ticketRepository->create($dataTicket);
                    }
                }
            }
            $dataOrder = [
                'id' => $orderId,
                'code_order' => Str::upper(Random::generate(10)),
                'created_by' => (string)auth()->id(),
                'type' => $type,
                'note' => $request->note,
                'payment_status' => (int)$request->status,
                'real_amount' => isset($request->price_nhap_tay) ? (int)($request->price_nhap_tay) : $sum,
                'amount' => isset($request->price_nhap_tay) ? (int)($request->price_nhap_tay) : $sum,
                'company_id' => companyIdByUser() ?? $request->company_id
            ];
            $order = $this->orderRepository->create($dataOrder);

            $dataStatus = [
                'id' => getGUID(),
                'status' => (int)$request->status,
                'note' => '',
                'order_id' => $order->id
            ];
            $this->paymentStatusRepository->create($dataStatus);

            DB::commit();
            return redirect()->route('order.index')->withSuccess('Tạo hóa đơn thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }


    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function getDetailTypeTicket(Request $request): \Illuminate\Http\JsonResponse
    {
        $maps = $this->mapRepository->accessByArea($request['ticket_type_id']);

        if ($maps) {
            return response()->json([
                'data' => [
                    'maps' => $maps
                ],
                'message' => 'Successfully get data',
                'status' => 200
            ], 200);
        }

        return response()->json([
            'message' => 'Error',
            'status' => 401
        ], 401);
    }

    public function changeStatus(Request $request)
    {
        $payment_status =  (int)$request['type'];
        try {
            $order = $this->orderRepository->updateT($request['order_id'], ['payment_status' => $payment_status]);

            if ($payment_status == 1 || $payment_status == 3) {
                foreach ($order->tickets as $ticket) {
                    $ticket->status = 0;
                    $ticket->save();
                }
            }
            if ($payment_status == 2) {
                foreach ($order->tickets as $ticket) {
                    $ticket->status = 1;
                    $ticket->save();
                }
            }

            $data_payment_status = [
                'id' => getGUID(),
                'status' => (int)$request['type'],
                'note' => 'Thay đổi trạng thái thanh toán',
                'order_id' => $order->id
            ];
            $this->paymentStatusRepository->create($data_payment_status);
            return response()->json([
                'message' => 'Thay đổi trạng thái thanh toán thành công',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Thất bại',
                'reason' => $e->getMessage(),
                'status' => 401
            ], 401);
        }
    }

    public function printMultiTicket($id)
    {
        $order = $this->orderRepository->getById($id);
        if (!$order) {
            return redirect()->back()->with('alert-error', 'Xuất vé lỗi!');
        }

        return view('admin.order.print_ticket', [
            'order' => $order
        ]);
    }

    public function orderDetail(Request $request)
    {
        $orderId = $request['order_id'];
        $order = $this->orderRepository->getById($orderId);
        $tickets = $order->tickets;
        $customer = $order->customer;
        $amount = $order->amount;
        $qty = count($tickets);
        return response()->json([
            'data' => $tickets,
            'customer' => $customer,
            'qty' => $qty,
            'amount' => $amount,
            'message' => 'Get data successfully',
            'status' => 200,
        ], 200);
    }

    public function getDataForChart(Request $request)
    {
        try {
            $month = $request->month;
            $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endOfMonth = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

            $filterOrder = [
                'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
                'paymentStatus' => 2,
            ];
            $order = $this->orderRepository->getData($filterOrder, 0, ['paymentStatus']);

            $filterTicket = [
                'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
                'status' => 1,
            ];
            // $ticket = $this->ticketRepository->getData($filterTicket);
            // dd($ticket[0]);

            $total = $order->sum('amount');

            $daysInMonth = $endOfMonth->day;
            $online = [];
            $offline = [];



            for ($day = 1; $day <= $daysInMonth; $day++) {
                $online[$day] = 0;
                $offline[$day] = 0;
            }

            $countOnline = 0;
            $countOffline = 0;
            foreach ($order as $key => $item) {
                $date = Carbon::parse($item->created_at)->day;

                if ((int)$item->type == 1) {
                    $offline[$date] += $item->amount;
                    $countOffline++;
                } elseif ((int)$item->type == 2) {
                    $online[$date] += $item->amount;
                    $countOnline++;
                }
            }

            $pie_chart = [
                'count' => [$countOnline, $countOffline],
                'key' => ['Online', 'Offline']
            ];
            return response()->json([
                'pie_chart' => $pie_chart,
                'offline' => $offline,
                'online' => $online,
                'message' => 'Get data successfully',
                'status' => 200,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Có lỗi xảy ra trong quá trình lấy dữ liệu!',
                'status' => 401,
            ], 401);
        }
    }
}
