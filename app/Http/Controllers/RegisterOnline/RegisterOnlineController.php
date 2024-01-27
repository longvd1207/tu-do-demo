<?php

namespace App\Http\Controllers\RegisterOnline;

use App\Http\Controllers\Controller;
use App\Mail\SendTicketMail;
use App\Repositories\Area\AreaRepository;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\MailHistory\MailHistoryRepositoryInterface;
use App\Repositories\Map\MapRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\PaymentStatus\PaymentStatusRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\TicketType\TicketTypeRepositoryInterface;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use JsonException;
use Spatie\Browsershot\Browsershot;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;


class RegisterOnlineController extends Controller
{
    protected $paymentStatusRepo;
    protected $max_count_ticket;
    protected $ticketTypeRepo;
    protected $customerRepo;
    protected $mailHistory;
    protected $ticketRepo;
    protected $orderRepo;
    protected $mapRepo;

    public function __construct(
        PaymentStatusRepositoryInterface $paymentStatusRepo,
        TicketTypeRepositoryInterface    $ticketTypeRepo,
        CustomerRepositoryInterface      $customerRepo,
        TicketRepositoryInterface        $ticketRepo,
        OrderRepositoryInterface         $orderRepo,
        MailHistoryRepositoryInterface  $mailHistory,
        MapRepositoryInterface           $mapRepo
    ) {
        $this->paymentStatusRepo = $paymentStatusRepo;
        $this->ticketTypeRepo = $ticketTypeRepo;
        $this->customerRepo = $customerRepo;
        $this->mailHistory = $mailHistory;
        $this->ticketRepo = $ticketRepo;
        $this->orderRepo = $orderRepo;
        $this->mapRepo = $mapRepo;
        $this->max_count_ticket = 10000;
    }

    public function ladipage()
    {
        $ticket_type = $this->ticketTypeRepo->getData(['type_of_ticket' => 'online']);

        return view('registerOnline.ladipage', [
            'list_ticket' => $ticket_type
        ]);
    }

    public function getTicketTypeDetail(Request $request)
    {
        $data = [];
        try {
            $ticketType = $this->ticketTypeRepo->getById($request->id);

            $data = $this->mapRepo->accessByArea($request->id);

            $data_return = [
                'ticket_name' => $ticketType->name,
                'data' => $data,
                'status' => 200
            ];
        } catch (\Throwable $th) {
            $data_return = [
                'data' => $data,
                'status' => 200,
                'message' => $th->getMessage()
            ];
        }

        return response()->json($data_return);
    }

    /*
     *  chức năng :lưu vào db , gọi API thanh toán momo
     *  dầu vào :
     *  xử lý : lưu đơn hàng vào db  , gọi sang momo tạo đơn hàng thanh toán ,  người dùng chưa thanh toán ở đây nhé
     *  đầu ra :
     *          thành công :
                       $data_return = [
                            'status' => 200,
                            'message' => 'Tạo vé thành công!',

                            'data_payment' => $data_payment,
                            'result_api' => $result["relatedData"]["payUrl"] =>url
                        ];
                thất bại :
                      $data_return = [
                            'status' => 90,
                            'message' => 'Có lỗi xẩy ra trong quá trình tạo vé!'
                        ];

                response()->json($data_return);
     */
    public function payment(Request $request)
    {
        try {
            DB::beginTransaction();
            $create_customer_data = [
                'id' => getGUID(),
                'name' => $request->form_data['form_name'],
                'email' => $request->form_data['form_mail'],
                'phone' => $request->form_data['form_phone'],
                'gender' => $request->form_data['form_gender'],
                'address' => $request->form_data['form_address'],
                'is_delete' => 0
            ];

            $create_order_data = [
                'id' => getGUID(),
                'type' => 2, //2 online
                'real_amount' => '',
                'amount' => '',
                'is_delete' => 0,
                'customer_id' => $create_customer_data['id'],
                'code_order' => $this->orderRepo->genCode(),
            ];

            $create_payment_status = [
                'id' => getGUID(),
                'status' => 1, //1: chưa thanh toán
                'order_id' => $create_order_data['id'],
            ];

            $create_ticket_data = [];
            $arr_code = [];
            $price = 0;
            foreach ($request->ticket_data as $key => $value) {
                $ticket = $this->ticketTypeRepo->getById($key);
                for ($i = 0; $i < $value; $i++) {
                    $code = $this->checkCode($arr_code);
                    $arr_code[] = $code;
                    $create_ticket_data[] = [
                        'id' => getGUID(),
                        'code' => $code,
                        'ticket_type_name' => $ticket->name,
                        'ticket_type_id' => $ticket->id,
                        'order_id' => $create_order_data['id'],
                        'price' => $ticket->price_online,
                        'use_date' => Carbon::createFromFormat('d/m/Y', $request->form_data['form_date'])->format('Y-m-d')
                    ];
                    $price = $price + $ticket->price_online;
                }
            }
            // dd($create_ticket_data);

            // Tạo customer
            $customer = $this->customerRepo->create($create_customer_data);

            // tạo order
            $create_order_data['real_amount'] = $price;
            $create_order_data['amount'] = $price;
            $order = $this->orderRepo->create($create_order_data);
            // dd($order,$create_order_data);
            // tạo tạo vé
            // dd($create_ticket_data);
            $ticket = $this->ticketRepo->createMutiData($create_ticket_data);

            // tạo paymentstatus
            $paymentStatus = $this->paymentStatusRepo->create($create_payment_status);
            if (!$customer || !$order || !$ticket || !$paymentStatus) {
                $data_return = [
                    'status' => 90,
                    'message' => 'Có lỗi xẩy ra trong quá trình tạo vé!'
                ];
            } else {
                $key = $this->generateAndPandemicKey($order->id);
                $url_success = route('register_online.getTicket', $key);
                $url_error = route('register_online.errorPayment', $key);

                DB::update("update [dbo].[orders] set url_payment_web_success=? , url_payment_web_error=? where id=?", [$url_success, $url_error, $order->id]);

                //OBJECT NÀY CỦA LINH - KO DÙNG ĐẾN-------
                $data_payment = [
                    'order' => $order,
                    'ticket' => $ticket,
                    'url_success' => $url_success,
                    'url_error' => $url_error,
                    'price' => $price,
                ];
                //OBJECT NÀY CỦA LINH - KO DÙNG ĐẾN-------

                //  log::error($data_payment);

                DB::commit();

                $data_return = $this->createPaymentOrder($data_payment);

            }
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error($th->getMessage());
            $data_return = [
                'status' => 90,
                'message' => 'Có lỗi xẩy ra trong quá trình tạo vé!'
            ];
        }
        return response()->json($data_return);
    }


    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function createPaymentOrder($data_payment)
    {
        //==================API 1 : LOGIN =================================
        $url = config("api_payment_online.api_payment_login.url");
        $form_params = config("api_payment_online.api_payment_login.param");

        $client = new \GuzzleHttp\Client([
            'headers' => [
                'Content-Type' => "application/x-www-form-urlencoded"
            ]
        ]);

        $res = $client->request('POST', trim($url), [
            'form_params' => $form_params,
        ]);

        $result = json_decode($res->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if (!empty($result["access_token"])) {
            $token = $result["access_token"];
        } else {
            $error = "api login lấy token của momo bị lỗi !";
            Log::error($error);
            $data_return = [
                'status' => 90,
                'message' => $error
            ];
            return $data_return;
        }

        //==================API 1 : LOGIN =================================

        //==================API 2 : TẠO DƠN HÀNG Ở MOMO NHƯNG CHƯA THANH TOÁN =================================
        // url('') là link gốc hiện tại
        $url = str_replace("[url_replace]", url(''), config("api_payment_online.api_payment_momo.url"));

        $paymentObjectDetails = [];
        foreach ($data_payment["ticket"] as $item) {
            $paymentObjectDetails[] = [
                "id" => $item["id"],   //ticket.id
                "name" => $item["ticket_type_name"], //ticket.ticket_type_name
                "description" => "",
                "category" => $item["ticket_type_id"], //ticket.ticket_type_id
                "price" => $item["price"], //ticket.price
                "currency" => "VND",
                "quantity" => 1,
                "unit" => "chiếc",
                "taxAmount" => 0
            ];
        }

        $form_params = [
            //hoán đổi id cho code, vì momo hiểu id là code, để KH dùng code cho nó ngắn
            "id" => $data_payment["order"]["code_order"],  //order.id
            "code" => $data_payment["order"]["id"],  //order.code_order
            "paymentObjectName" => config("api_payment_online.api_payment_momo.param.paymentObjectName"),
            "paymentObjectDetails" => $paymentObjectDetails,
            "description" => config("api_payment_online.api_payment_momo.param.description"),
            "companyName" => config("api_payment_online.api_payment_momo.param.companyName"),
            "companyCode" => config("api_payment_online.api_payment_momo.param.companyCode"),
            "user" => config("api_payment_online.api_payment_momo.param.user"),
            "categoryId" => config("api_payment_online.api_payment_momo.param.categoryId"),

            "amount" => $data_payment["order"]["real_amount"],
            "additionalData" => $data_payment["order"]["note"],

            "bankCode" => config("api_payment_online.api_payment_momo.param.bankCode"),
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => [
                'Authorization' => "Bearer " . $token,
                'Content-Type' => 'application/json'
            ]
        ]);

        $res = $client->request('POST', trim($url), [
            'json' => $form_params,
        ]);

        $result = json_decode($res->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if (isset($result["code"])) {
            //bắt vòng ngoài: thành công
            // result.code = 200
            if ((int)$result["code"] == 200) {
                $data_return = [
                    'status' => 200,
                    'message' => 'Tạo vé thành công!',
                    'result_api' => $result["relatedData"]["payUrl"]
                ];
            } else {
                //bắt vòng ngoài: lỗi => result.code khác 200
                //lỗi: Thông tin đơn hàng không hợp lệ, Bản ghi không tồn tại, Đơn hàng đã tồn tại, Dịch vụ chưa được hỗ trợ...
                $error = $result["message"];
                Log::error($error);
                $data_return = [
                    'status' => 90,
                    'message' => $error
                ];
            }
        } else {
            //không tồn tại result.code
            $error = "Lỗi: API " . $url ." trả về => không tồn tại result.code";
            Log::error($error);
            $data_return = [
                'status' => 90,
                'message' => $error
            ];
        }
        //==================API 2 : TẠO DƠN HÀNG Ở MOMO NHƯNG CHƯA THANH TOÁN =================================

        return $data_return;
    }


    //kết quả trả về từ momo =>thành công hay thất bại , để gọi vào link này
    //m ko cần biết momo trả về gì , mình gọi api check trạng thái đơn hàng là ok

    public function payment_result(Request $request)
    {
        $code_order = $request->orderId;
        //  Log::error($code_order);

        $order = DB::select("select url_payment_web_success,url_payment_web_error from orders where code_order=? ", [$code_order]);
        //  Log::error($order);
        // dd($order);

        //goi api check trang thai
        $url = str_replace("[orderId]", $code_order, config("api_payment_online.api_payment_momo_status.url"));

        $client = new  \GuzzleHttp\Client([
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $res = $client->request('GET', $url);
        $result = json_decode($res->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if (isset($result["relatedData"]["status"]) && (int)$result["relatedData"]["status"] == 1) {
            return Redirect::away($order[0]->url_payment_web_success);
        }

        return Redirect::away($order[0]->url_payment_web_error);
    }

    /*
     * tìm kiếm theo mã đơn hàng
     *  trả vể client :
     *              [
                        'status' => 200,
                        'url_redirect' => $order[0]->url_payment_web_error, //url_payment_web_error
                        'error' => ''
                    ];

                 [
                'status' => 90,
                'url_redirect' => "",
                'error' => 'Lỗi ....'
            ];

     */
    public function payment_search(Request $request)
    {
        if (!empty($request->code_order)) {
            $code_order = $request->code_order;
        }

        //  Log::error($code_order);

        $order = DB::select("select * from orders where code_order=? ", [$code_order]);
        // dd($order[0]->id);
        if (empty($order)) {
            return [
                'status' => 90,
                'url_redirect' => "",
                'error' => 'Không tìm thấy mã hoá đơn : ' . $code_order
            ];
        }


        //goi api check trang thai
        // $url = "http://14.160.26.45:10000/PaymentStatus?orderId=" . $code_order;
        $url = str_replace("[orderId]", $code_order, config("api_payment_online.api_payment_momo_status.url"));
        $client = new  \GuzzleHttp\Client([
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        $res = $client->request('GET', $url);
        $result = json_decode($res->getBody(), true);

        Log::error("----------http://14.160.26.45:10000/PaymentStatus?orderId---------------");
        Log::error($result);
        Log::error("----------http://14.160.26.45:10000/PaymentStatus?orderId---------------");


        //        [2023-12-25 10:14:39] local.ERROR: array (
        //            'isSuccess' => true,
        //            'relatedData' => NULL,
        //            'code' => 404,
        //            'message' => 'Không tồn tại giao dịch tương ứng',
        //         )


        //        [2023-12-25 10:30:21] local.ERROR: array (
        //        'isSuccess' => true,
        //        'relatedData' =>
        //            array (
        //                'status' => 1,
        //                'code' => 'F16F151C-E44C-DCE4-87E7-7102D073F023',
        //                'requestType' => 0,
        //                'paymentMethod' => 0,
        //                'serviceProvider' => 88,
        //                'dateCreated' => '2023-12-25T01:32:17.909+00:00',
        //                'price' => 100000,
        //                'order' => 'Thanh toán tiền thưởng 1 năm',
        //                'user' => '{ "_id" : "E65050B2-48C1-4BF0-8309-5F6E4AB78A0B", "Name" : "Đỗ Quốc Cường", "Code" : "ma_cuong" }',
        //                'processingResult' => '{ "PartnerCode" : "MOMOSWMU20230731", "RequestId" : "787629ae-3474-4cb3-93e7-843262637660", "OrderId" : "8VMZTCAQ5P", "Signature" : null, "ExtraData" : "dGjDtG5nIHRpbiBi4buVIHh1bmcg", "Amount" : NumberLong(100000), "TransId" : NumberLong("3110516447"), "PayType" : "qr", "ResultCode" : 0, "Message" : "Thành công.", "ResponseTime" : NumberLong("1703467955371") }',
        //                'completeDate' => '2023-12-25T01:32:35.382+00:00',
        //                'resultDiscription' => '',
        //            ),
        //        'code' => 200,
        //        'message' => 'Giao dịch đã kết thúc',
        //    )

        if (isset($result["code"])) {

            //bắt vòng ngoài : thành công
            // result.code = 200
            if ((int)$result["code"] == 200) {

                // bắt vòng trong : ở đối tượng relatedData
                //status =1 => thành công
                // route('register_online.getTicket',)
                // dd($order[0]);
                $key = $this->generateAndPandemicKey($order[0]->id);
                if ((int)$result["relatedData"]["status"] == 1) {

                    $url_payment_web_success = route('register_online.getTicket', $key);
                    return [
                        'status' => 200,
                        'url_redirect' => $url_payment_web_success,
                        'error' => 'Kết quả thành toán Momo : đơn hàng đã thanh toán thành công !'
                    ];
                } else {

                    //status: 0 đang xử lý , 2 đã huỷ , 3 thất bại
                    $error = "";
                    if ((int)$result["relatedData"]["status"] == 0)
                        $error = "Kết quả thành toán Momo : đơn hàng đang xử lý,";
                    else if ((int)$result["relatedData"]["status"] == 2)
                        $error = "Kết quả thành toán Momo : đơn hàng đã huỷ.";
                    else if ((int)$result["relatedData"]["status"] == 3)
                        $error = "Kết quả thành toán Momo : đơn hàng thất bại.";
                    else
                        $error = "Lỗi : API chưa định nghĩa relatedData.status";

                    $url_payment_web_error = route('register_online.errorPayment', $key);
                    return [
                        'status' => 200,
                        'url_redirect' => $order[0]->url_payment_web_error, //url_payment_web_error
                        'error' => $error
                    ];
                }
            } else {

                //bắt vòng ngoài : lỗi => result.code khác 200
                //lỗi : Thông tin đơn hàng không hợp lệ ,Bản ghi không tồn tại,Đơn hàng đã tồn tại,Dịch vụ chưa được hỗ trợ...
                $error = $result["message"];
                return [
                    'status' => 90,
                    'url_redirect' => "",
                    'error' => $error
                ];
            }
        } else {
            //không tồn tại result.code
            Log::error("Lỗi : http://14.160.26.45:10000/PaymentStatus?orderId  trả về => không tồn tại result.code");
            return [
                'status' => 90,
                'url_redirect' => "",
                'error' => 'Lỗi server !!! '
            ];
        }
    }

    private function checkCode(array $arr_code)
    {
        $genCode = $this->ticketRepo->genCode();
        if (in_array($genCode, $arr_code)) {
            $code = $this->checkCode($arr_code);
        } else {
            $code = $genCode;
        }
        return $code;
    }

    /*
     * lấy thông tin của vé qua code_order , khi mua hàng thành công !
     *  lấy thông tin : bảng paymentStatus , customer,ticket
     */
    public function getTicket($key)
    {
        $max_count_ticket = $this->max_count_ticket;

        // check quá 10p thì sẽ bị chuyển vể link gốc
        $time_distance = 10;

        $key = $this->generateAndPandemicKey($key, 'pandemic');
        $order = $this->orderRepo->getById($key);
        $this->orderRepo->update($key, ['payment_status' => 2]);

        $create_payment_status = [
            'id' => getGUID(),
            'status' => 2,
            'order_id' => $order->id,
        ];


        $paymentStatus = $this->paymentStatusRepo->create($create_payment_status);

        $customer = $this->customerRepo->getById($order->customer_id);

        $ticket = $this->ticketRepo->getData(['order_id' => $order->id]);
        // dd($order);

        //nếu đơn hàng lớn hơn 6 vé :
        if (count($ticket) > $max_count_ticket) {
            $qr_code = $this->generateAndSaveQRcodeInfle($order->code_order);
            $order['qr_code'] = $qr_code;

            $data_return = [
                'type' => 'order',
                'data' => $order,
                'ticket' => $ticket->groupBy('ticket_type_name'),
                'customer' => $customer
            ];
        } else {
            foreach ($ticket as $key => $value) {
                $qr_code = $this->generateAndSaveQRcodeInfle($value->code);
                $accessByArea = $this->mapRepo->accessByArea($value->ticket_type_id);

                $ticket[$key]['qr_code'] = $qr_code;
                $ticket[$key]['accessByArea'] = $accessByArea;
            }
            $data_return = [
                'type' => 'ticket',
                'order' => $order,
                'data' => $ticket,
                'customer' => $customer
            ];
            // dd($ticket[0]);
        }

        return view('registerOnline.ladipage_get_ticket', [
            'dataAll' => $data_return,
            'message' => 'Thanh toán thành công!'
        ]);
    }

    //nếu gọi đến hàm này : xoá hết các thông tin của đơn hàng này : order, ticket , customer ,paymentStatus
    //goi đến : tìm kiếm đơn hàng, thanh toán online thất baị
    public function errorPayment($key)
    {
        try {
            DB::beginTransaction();
            $key = $this->generateAndPandemicKey($key, 'pandemic');
            $order = $this->orderRepo->getById($key);
            // dd($order);
            // xóa customer
            $customer = $this->customerRepo->delete($order->customer_id);

            // xóa vé
            $ticket = $this->ticketRepo->deleteByOrderId($order->id);

            // xóa paymentStatus
            $paymentStatus = $this->paymentStatusRepo->deleteByOrderId($order->id);

            // xóa order
            $this->orderRepo->delete($key);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        return redirect()->route('register_online.ladipage')->with('error', 'Thanh toán không thành công!');
    }

    /*
     *  chức năng : từ key => mã hoá và giải mã đc
     *  $type :
     *          'generate' mã hoá
     *          'pandemic': giải mã
     */
    private function generateAndPandemicKey(string $key, $type = 'generate')
    {
        //key mã hoá
        $encryptionKey = 'generateAndPandemicKey';
        $key_return = '';
        if ($type == 'generate') {
            $key_return = Crypt::encryptString($key, $encryptionKey);
        }
        if ($type == 'pandemic') {
            $key_return = Crypt::decryptString($key, $encryptionKey);
        }
        return $key_return;
    }

    public function sendEmail($order_id)
    {
        $max_count_ticket = $this->max_count_ticket;
        $order = $this->orderRepo->getById($order_id);
        $customer = $this->customerRepo->getById($order->customer_id);

        $ticket = $this->ticketRepo->getData(['order_id' => $order->id]);
        foreach ($ticket as $key => $value) {
            $qr_code = $this->generateAndSaveQRcodeInfle($value->code);
            $accessByArea = $this->mapRepo->accessByArea($value->ticket_type_id);

            $ticket[$key]['qr_code'] = $qr_code;
            $ticket[$key]['accessByArea'] = $accessByArea;
        }
        $result = Mail::to($customer->email)->send(new SendTicketMail(
            $order,
            $customer,
            $ticket
        ));
        //
        // $pandemicKey = $this->generateAndPandemicKey($order_id);
        // $result = true;

        $dataCreateMailHistory = [
            'id' => getGUID(),
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'order_code' => $order->code_order,
            'is_delete' => 0
        ];
        if ($result) {
            $dataCreateMailHistory['status'] = 1;
            $message = 'Vé đã được gửi tới email của bạn!';
        } else {
            $dataCreateMailHistory['status'] = 0;
            $message = 'Có lỗi xẩy ra trong quá trình gửi mail';
        }
        $this->createMail($dataCreateMailHistory);
        return redirect()->back()->with('message', $message);
    }

    private function createMail($dataCreateMailHistory)
    {
        try {
            DB::beginTransaction();

            $mail = $this->mailHistory->create($dataCreateMailHistory);
            if ($mail) {
                DB::commit();
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }
}
