<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\User;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\TicketType\TicketTypeRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use JsonException;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $orderRepo;
    protected $ticketRepo;
    protected $ticketTypeRepo;


    public function __construct(
        OrderRepositoryInterface $orderRepo,
        TicketRepositoryInterface $ticketRepo,
        TicketTypeRepositoryInterface $ticketTypeRepo
    ) {
        $this->orderRepo = $orderRepo;
        $this->ticketRepo = $ticketRepo;
        $this->ticketTypeRepo = $ticketTypeRepo;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        Log::channel('check_api')->info('router home/index');

        $user = Auth::user();

        // Lấy ngày đầu tháng
        $now = Carbon::now();
        $month = $now->format('Y-m');

        $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // số lượng vé
        $ticketType = $this->ticketTypeRepo->getData(['status', 1]);
        $countTicketType = count($ticketType);

        $filterOrder = [
            'start_date' => $startOfMonth,
            'end_date' => $endOfMonth,
            'paymentStatus' => 2,
        ];

        $order = $this->orderRepo->getData($filterOrder, 0, ['paymentStatus']);

        $total = $order->sum('amount');

        $countOrder = count($order);

        $filterTicket = [
            'start_date' => $startOfMonth,
            'end_date' => $endOfMonth,
            'status' => 1
        ];
        $ticket = $this->ticketRepo->getData($filterTicket);
        $countTicket = count($ticket);

        return view('index', [
            'countOrder' => $countOrder,
            'countTicket' => $countTicket,
            'countTicketType' => $countTicketType,
            'total' => $total,
        ]);
    }
}
