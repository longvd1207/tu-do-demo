<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SwapCardController extends Controller
{
    /*
     *
     *   chức năng :  api quẹt thẻ
     *   đầu vào :
     *              code: của vé
     *              list_ip (mảng) lấy dc từ máy quẹt thẻ
     *   đầu ra :
     *          ok  +  config("api_swap_card.return_result_full") == 1 => trả về hết
     *              +  config("api_swap_card.return_result_full") == 0 => trả về  đúng trạng thái
     *          lỗi
     *              200: ok
                    90: lỗi chung chung
                    -----------
                    91: vé ko tồn tại
                    92: Có vé nhưng sử dụng sai dịch vụ
                    93: sai ngày sử dụng
                            chưa đúng ngày sử dụng (quá hạn hoặc chưa đến)
                            chưa có ngày null=> trả về mã
                    94: vé đã sử dụng rồi

    */
    public function swipe_card(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'code' => ['required', 'string', 'max:100', 'min:1'],
                'list_ip' => ['required', 'array', 'min:1'],
            ],
            [
                'code.required' => 'code phải nhập',
                'code.max' => 'code phải có độ dài tôi đa 100',
                'code.min' => 'code phải có độ dài tối thiểu 1',

                'list_ip.required' => 'list_ip phải nhập',
                'list_ip.array' => 'list_ip phải là mảng',
                'list_ip.filled' => 'list_ip phải nhập giá trị',
            ]
        );

        if ($validator->fails()) {
            $response = [
                "status" => 90,
                "run" => 1,
                "error" => $validator->errors(),
                "result" => [],
            ];
            return $response;
        }

//        $code = "639X5MHZ";
//        $list_ip = ["IP1","IP2","IP3","IP4"];

        if (config("api_swap_card.view_log") == 1) {

            Log::error("");
            Log::error("=========================================BẮT ĐÂU=========================================");

            Log::error("-------------------------request=--------------");
            log::error($request->all());
            Log::error("-------------------------request=--------------");

            Log::error("");
        }

        $code = $request->code;
        $list_ip = $request->list_ip;

        //mẫu mảng để insert vo hàm cảnh báo, sử dụng chung cho toàn bộ API
        $data_warning = [
            "",  //0: id
            "", //1: event_code
            "",  //2: ticket_id
            '', //3: user_id
            "", //4: type
            "", //5: type_id
            "", //6: description
            0, // 7: is_delete
            Carbon::now(), // 8: created_at
            "" , //9:  type_name,
            "" //10 customer_id
        ];


        DB::beginTransaction();

        try {


            //so sánh 1 : lấy các type va type_id từ vé --------------------------
            {
                $ticket = DB::select("select * from tickets where code=? and is_delete=0 and [status]=1 ", [$code]);

                //kiểm tra ngoại lệ : có vé này không
                if (count($ticket) == 0) {

                    //---------------------
                    DB::rollBack();
                    $event_code = 91;
                    $description = "Lỗi : vé không tồn tại !";
                    //---------------------

                    $data_warning = [
                        getGUID(),  //0: id
                        $event_code, //1: event_code
                        "",  //2: ticket_id
                        '', //3: user_id
                        null, //4: type
                        "", //5: type_id
                        $description, //6: description
                        0, // 7: is_delete
                        Carbon::now(), // 8: created_at
                        "",  //9:  type_name,
                        "" //10 customer_id
                    ];

                    //ghi tbl_warning_event
                    $rs = $this->write_to_tbl_warning_event($data_warning, 0, $list_ip);
                    if ($rs !== true) {
                        return $this->return_error(90, $rs);
                    }

                    //trả vè lỗi
                    return $this->return_error($event_code, $description);

                }


                $ticket_id = $ticket[0]->id;
                $ticket_type_id = $ticket[0]->ticket_type_id;
                $order_id = $ticket[0]->order_id;

                if (config("api_swap_card.view_log") == 1) {
                    Log::error("-------------------------ticket_type_id=--------------");
                    Log::error($ticket_type_id);
                    Log::error("-------------------------ticket_type_id=--------------");
                    Log::error("");
                    // dd($ticket_type_id);
                }


                //kiểm tra ngoại lệ : vé này hết hạn chưa -------------------------
                {

                    if (empty($ticket[0]->use_date)) {

                        //---------------------
                        DB::rollBack();
                        $event_code = 93;
                        $description = "Lỗi : vé chưa có ngày sử dụng !";
                        //---------------------

                        $data_warning = [
                            getGUID(),  //id
                            $event_code, //event_code
                            $ticket_id,  // ticket_id
                            '', // user_id
                            null, // type
                            "", // type_id
                            $description, // description
                            0, // is_delete
                            Carbon::now(), // 8: created_at
                            "",  //9:  type_name,
                            "" //10 customer_id
                        ];

                        //ghi tbl_warning_event
                        $rs = $this->write_to_tbl_warning_event($data_warning, 0, $list_ip);
                        if ($rs !== true) {
                            return $this->return_error(90, $rs);
                        }
                        //trả vè lỗi
                        return $this->return_error($event_code, $description);
                    }

                    // Chuyển chuỗi thành một đối tượng Carbon
                    $dateToCheck = Carbon::createFromFormat('Y-m-d H:i:s.u', $ticket[0]->use_date);

                    // Lấy ngày hiện tại
                    $today = Carbon::now();

                    // So sánh ngày cần kiểm tra với ngày hiện tại
                    if (!$dateToCheck->isSameDay($today)) {

                        //---------------------
                        DB::rollBack();
                        $event_code = 93;
                        $description = "Lỗi : vé sử dụng chưa đúng ngày (quá hạn hoặc chưa đến ngày)";
                        //---------------------

                        //ghi tbl_warning_event
                        $data_warning = [
                            getGUID(),  //id
                            $event_code, //event_code
                            $ticket_id,  // ticket_id
                            '', // user_id
                            null, // type
                            "", // type_id
                            $description, // description
                            0, // is_delete
                            Carbon::now(), // 8: created_at
                            "",  //9:  type_name,
                            "" //10 customer_id
                        ];

                        //ghi tbl_warning_event
                        $rs = $this->write_to_tbl_warning_event($data_warning, 0, $list_ip);
                        if ($rs !== true) {
                            return $this->return_error(90, $rs);
                        }

                        //trả vè lỗi
                        return $this->return_error($event_code, $description);

                    }
                }
                //kiểm tra ngoại lệ : vé này hết hạn chưa -------------------------

                //lấy map => ra dc nhiều kết quả : là loại hình vé này có thể gắn với nhiều loại hình chơi dịch vụ...
                $map = DB::select("select type_id, [type] from [maps] where ticket_type_id=? and is_delete=0 ", [$ticket_type_id]);

                if (empty($map) || count($map) == 0) {

                    //---------------------
                    DB::rollBack();
                    $event_code = 90;
                    $description = "Lỗi : vé này chưa được gán với một hình thức dịch vụ nào !";
                    //---------------------

                    $data_warning = [
                        getGUID(),  //id
                        $event_code, //event_code
                        $ticket_id,  // ticket_id
                        '', // user_id
                        null, // type
                        "", // type_id
                        $description, // description
                        0, // is_delete
                        Carbon::now(), // 8: created_at
                        "",  //9:  type_name,
                        "" //10 customer_id
                    ];

                    //ghi tbl_warning_event
                    $rs = $this->write_to_tbl_warning_event($data_warning, 0, $list_ip);
                    if ($rs !== true) {
                        return $this->return_error(90, $rs);
                    }

                    //trả vè lỗi
                    return $this->return_error($event_code, $description);
                }

                if (config("api_swap_card.view_log") == 1) {
                    Log::error("--------------------map=--------------------");
                    Log::error($map);
                    Log::error("--------------------map=--------------------");
                }
            }
            //so sánh 1 : lấy các type va type_id từ vé --------------------------


            Log::error("");

            //so sánh 2 : lấy các type va type_id  tu IP --------------------------
            {
                //lấy ip_device_config => ra dc 1  hàng thôi
                $string = "";
                foreach ($list_ip as $value) {

                    if ($value != "") {
                        if ($string == "")
                            $string = "'" . $value . "'";
                        else
                            $string .= "," . "'" . $value . "'";
                    }

                }

                if ($string == "") {

                    //---------------------
                    DB::rollBack();
                    $event_code = 90;
                    $description = [
                        "list_ip" => ["IP truyền lên rỗng "]
                    ];
                    //---------------------

                    //trả vè lỗi
                    return $this->return_error($event_code, $description);
                }

                $device_ip = DB::select("select TOP 1 device_id from device_ip where ip in (" . $string . ")  and is_delete=0");

                if (empty($device_ip)) {

                    //---------------------
                    DB::rollBack();
                    $event_code = 90;
                    $description = "IP chưa khai báo trong hệ thống ";
                    //---------------------

                    $data_warning = [
                        getGUID(),  //id
                        $event_code, //event_code
                        $ticket_id,  // ticket_id
                        '', // user_id
                        null, // type
                        "", // type_id
                        $description, // description
                        0, // is_delete
                        Carbon::now(), // created_at
                        "",  //9:  type_name,
                        "" //10 customer_id
                    ];

                    //ghi tbl_warning_event: IP ko tìm thấy => nên không tìm tên dịch vụ qua IP
                    $rs = $this->write_to_tbl_warning_event($data_warning);
                    if ($rs !== true) {
                        return $this->return_error(90, $rs);
                    }

                    //trả vè lỗi
                    return $this->return_error($event_code, $description);
                }

                if (config("api_swap_card.view_log") == 1) {
                    Log::error("--------------------device_ip=--------------------");
                    Log::error($device_ip);
                    Log::error("--------------------device_ip=--------------------");
                    Log::error("");
                }



                //select bảng device
                $list_device = [];

                //tuy là for , nhưng chỉ xét device_ip đầu tiên thôi , bỏ qua các device_ip còn lại. chỉ xét cái đầu tiền thôi
                foreach ($device_ip as $key => $value1) {

                    $device = DB::select("select type_id, [type] from device where id =?  and is_delete=0 ", [$value1->device_id]);

                    if (empty($device[0]->type) || empty($device[0]->type_id)) {

                        //---------------------
                        DB::rollBack();
                        $event_code = 90;
                        $description = "IP chưa gắn với dịch vụ ";
                        //---------------------

                        $data_warning = [
                            getGUID(),  //id
                            $event_code, //event_code
                            $ticket_id,  // ticket_id
                            '', // user_id
                            null, // type
                            "", // type_id
                            $description, // description
                            0, // is_delete
                            Carbon::now(), // 8: created_at
                            "",  //9:  type_name,
                            "" //10 customer_id
                        ];

                        //ghi tbl_warning_event: IP chưa gắn với dịch vụ => nên không tìm tên dịch vụ qua IP
                        $rs = $this->write_to_tbl_warning_event($data_warning);
                        if ($rs !== true) {
                            return $this->return_error(90, $rs);
                        }

                        //trả vè lỗi
                        return $this->return_error($event_code, $description);


                    } else if (count($device) > 2) {

                        echo "Không bao gio xẩy ra, vì  device.id là khoá chính ! ";
                        exit;

                    }

                    $list_device[] = $device[0];
                }

                if (config("api_swap_card.view_log") == 1) {
                    Log::error("--------------------device=--------------------");
                    Log::error($list_device);
                    Log::error("--------------------device=--------------------");
                }


                // dd($list_device);
            }
            //so sánh 2 : lấy các type va type_id  tu IP --------------------------


            //check dịch vụ để sử dụng chưa -------------------------
            {
                $events = DB::select("select * from events where [ticket_id]=? and [type]=? and [type_id]=? and is_delete=0", [
                    $ticket_id, $list_device[0]->type, $list_device[0]->type_id

                ]);

                //===tạm ẩn =======================
                if (count($events) > 0) {

                    //---------------------
                    DB::rollBack();
                    $event_code = 94;
                    $description = "Lỗi : vé đã được sử dụng";
                    //---------------------

                    $data_warning = [
                        getGUID(),  //id
                        $event_code, //event_code
                        $ticket_id,  // ticket_id
                        '', // user_id
                        $list_device[0]->type,
                        $list_device[0]->type_id,
                        $description, // description
                        0, // is_delete
                        Carbon::now(), // created_at
                        "" , //9:  type_name,
                        "" //10 customer_id
                    ];

                    //ghi tbl_warning_event
                    $rs = $this->write_to_tbl_warning_event($data_warning, 0, $list_ip);
                    if ($rs !== true) {
                        return $this->return_error(90, $rs);
                    }

                    //trả vè lỗi
                    return $this->return_error($event_code, $description);
                }
                //===tạm ẩn =======================

            }
            //check dịch vụ để sử dụng chưa -------------------------


            //so sanh 1 và 2 nếu giống nhau là đc ----------------
            $check = 0;
            foreach ($map as $map_value) {
                if ($map_value->type_id == $list_device[0]->type_id and $map_value->type == $list_device[0]->type) {
                    // dd($map_value->type_id,$map_value->type,$list_device[0]->type_id,$list_device[0]->type);
                    $check = 1;
                    break;
                }
            }


            if ($check == 1) {

                //tìm dịch vụ này---------------------------------
                $service = [];

                if ($list_device[0]->type == "1") {
                    //1: khu vụ
                    $service = DB::select("select name from areas where id=? and is_delete=0 and [status] =1", [$list_device[0]->type_id]);

                }
                if ($list_device[0]->type == "2") {

                    //2: dịch vụ ăn uống
                    $service = DB::select("select name from services where id=? and is_delete=0 and [status] =1", [$list_device[0]->type_id]);

                }
                if ($list_device[0]->type == "3") {

                    //3: khu vui chơi
                    $service = DB::select("select name from fun_spots where id=? and is_delete=0 and [status] =1", [$list_device[0]->type_id]);
                };
                //tìm dịch vụ này---------------------------------


                if (count($service) == 0) {
                    //---------------------
                    DB::rollBack();
                    $event_code = 90;
                    $description = "Lỗi: Không tìm thấy dịch vụ ";
                    //---------------------

                    $data_warning = [
                        getGUID(),  //id
                        $event_code, //event_code
                        $ticket_id,  // ticket_id
                        '', // user_id
                        $list_device[0]->type,
                        $list_device[0]->type_id,
                        $description, // description
                        0, // is_delete
                        Carbon::now(),// created_at
                        "",  //9:  type_name,
                        "" //10 customer_id
                    ];

                    //ghi tbl_warning_event
                    $rs = $this->write_to_tbl_warning_event($data_warning);
                    if ($rs !== true) {
                        return $this->return_error(90, $rs);
                    }

                    //trả vè lỗi
                    return $this->return_error($event_code, $description);

                } else if (count($service) > 1) {

                    echo "không bao giờ xẩy ra vì tìm theo ID";
                    exit;
                }


                //lưu vào bảng events -----------------------------------------
                $note = "note...";
                $event = [
                    getGUID(),
                    $ticket_id,
                    $ticket_type_id,
                    $order_id,
                    Carbon::now(),
                    $note,
                    0,
                    Carbon::now(),
                    $list_device[0]->type,
                    $list_device[0]->type_id,
                    $service[0]->name,
                ];
                $sql = "INSERT INTO [dbo].[events] ([id] ,[ticket_id] ,[ticket_type_id] ,[order_id] ,[time_in] ,[note] ,[is_delete] ,[created_at],[type],[type_id],type_name) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

                DB::insert($sql, $event);
                //lưu vào bảng events -----------------------------------------

                //tìm order ---------------------------------
                $orders = DB::select("select * from orders where id=? and is_delete=0", [$order_id]);

                if (count($orders) == 0) {

                    //---------------------
                    DB::rollBack();
                    $event_code = 90;
                    $description = "Lỗi: Không tìm thấy đơn hàng của vé này ";
                    //---------------------

                    $data_warning = [
                        getGUID(),  //id
                        $event_code, //event_code
                        $ticket_id,  // ticket_id
                        '', // user_id
                        $list_device[0]->type,
                        $list_device[0]->type_id,
                        $description, // description
                        0, // is_delete
                        Carbon::now(),// created_at
                        $service[0]->name,
                        "" //10 customer_id
                    ];

                    //ghi tbl_warning_event
                    $rs = $this->write_to_tbl_warning_event($data_warning);
                    if ($rs !== true) {
                        return $this->return_error(90, $rs);
                    }

                    //trả vè lỗi
                    return $this->return_error($event_code, $description);
                }


                DB::commit();

                //trả về hết hay chỉ trả theo trạng thái
                $result_api = (object)[];

                if (config("api_swap_card.return_result_full") == 1) {
                    $result_api = [
                        'ticket' => $ticket[0],
                        'service' => $service,
                        'orders' => $orders,
                    ];
                }

                return $this->return_success($result_api);

            } else {

                //---------------------
                DB::rollBack();
                $event_code = 92;
                $description = 'Lỗi : vé sử dụng sai dịch vụ';
                //---------------------

                $data_warning = [
                    getGUID(),  //id
                    $event_code, //event_code
                    $ticket_id,  // ticket_id
                    '', // user_id
                    null, // type
                    "", // type_id
                    $description, // description
                    0, // is_delete
                    Carbon::now(),// created_at
                    "",  //9:  type_name,
                    "" //10 customer_id
                ];

                //ghi tbl_warning_event
                $rs = $this->write_to_tbl_warning_event($data_warning, 0, $list_ip);
                if ($rs !== true) {
                    return $this->return_error(90, $rs);
                }

                //trả vè lỗi
                return $this->return_error($event_code, $description);
            }

            //so sanh 1 và 2 nếu giống nhau là đc ----------------

        } catch (\Throwable $exception) {

            DB::rollBack();

            $description = $exception->getMessage();
            Log::error($description);

            return [
                'status' => 90,
                "run" => 0,
                'error' => $description,
                "result" => [],
            ];
        }


    }





    //ghi vào bảng cảnh báo , sử dụng trong api quẹt thẻ
    /*
     *  dau vào :
     *          $data_warning : mảng để insert vào bảng tbl_warning_event
     *                          $data_warning = [
                                            getGUID(),  //0: id
                                            $event_code, //1: event_code
                                            "",  //2: ticket_id
                                            '', //3: user_id
                                            null, //4: type
                                            "", //5: type_id
                                            $description, //6: description
                                            0, // 7: is_delete
                                            Carbon::now(), // 8: created_at
                                            ""  //9:  type_name,
                                            "" //10 customer_id
                                        ];
     *          $is_have_type_name : 0 -  chưa có sẵn giá trị type , type_id , type_name dựa vào IP => phải tính
     *                               1-   có sẵn giá trị type , type_id , type_name dựa vào IP =>không phải  tính
     *          $list_ip : mảng IP từ client gửi lên ban đầu
     */
    public function write_to_tbl_warning_event($data_warning, $is_have_type_name = 1, $list_ip = [])
    {

        //vì để không phải sửa nhiều , thi bổ xung ở đây thôi --------------------------------
        //lấy customer_id từ bảng order ở đây => để còn lưu vào log cảnh báo-------------
        $customer_id = null;
        $order = DB::select("select customer_id from orders where id = ( select TOP 1 order_id from tickets where id=? and is_delete=0 and [status]=1 ) and is_delete=0",[$data_warning["2"]]);
        if(isset($order) and count($order)==1)
            $customer_id = $order[0]->customer_id;
        //vì để không phải sửa nhiều , thi bổ xung ở đây thôi --------------------------------


        if ($is_have_type_name == 0) {

            if (count($list_ip) > 0) {
                //gọi hàm tính giá trị type , type_id , type_name dựa vào IP
                $result = $this->get_name_of_service($list_ip);

                $data_warning["4"] = $result["type"];
                $data_warning["5"] = $result["type_id"];
                $data_warning["9"] = $result["type_name"];
                $data_warning["10"] = $customer_id;
            }
        }

        //   dd($data_warning);

        DB::beginTransaction();
        try {
            //ghi tbl_warning_event
            DB::insert("INSERT INTO [dbo].[tbl_warning_event] ([id] ,[event_code] ,[ticket_id] ,[user_id] ,[type] ,[type_id] ,[description] ,[is_delete] ,[created_at],type_name,customer_id) VALUES (?,?,?,?,?,?,?,?,?,?,?)", $data_warning);

            DB::commit();

            return true;

        } catch (\Throwable $ex) {
            DB::rollBack();
            return $ex->getMessage();

        }

    }

    //trả vè lỗi , sử dụng trong api quẹt thẻ
    public function return_error($event_code, $description)
    {
        return [
            'status' => $event_code,
            "run" => 0,
            'error' => $description,
            "result" => (object)[],
        ];


    }

    //trả vè kết quả khi thành công  , sử dụng trong api quẹt thẻ
    public function return_success($result_api)
    {
        return  [
            'status' => 200,
            "run" => 0,
            'error' => '',
            "result" => $result_api
        ];

    }

    /*
     *  lý do viét hàm này :
     *          do luồng bắt lỗi ở API quẹt thẻ: có những lúc bắt lỗi và trả về ngay , thì chưa đến đoạn lấy dc các thông tin type_id ,type_name,type dựa vào ip  để ghi vào log cảnh báo , nên phải viết riêng để lấy cho veè
     *
     *  đầu vào : đầu vào mảng IP
     *
     *  chức năng : trả về type_id ,type_name,type dựa vào ip đang quẹt thẻ , để ghi vào log cảnh báo tbl_warning_event
     *              đây là tìm dựa vào ip đang quẹt thôi , chứ không dựa vào id  của vé , vì dựa vào vé thì 1 vé có nhiều dịch vụ
     *              nên có thể nó sẽ khác nhau , lý do : KH quẹt nhầm thẻ ở dịch vụ nao đó
     *
     *  đầu ra :  trả về mảng [type, type_id, type_name ]
     */
    public function get_name_of_service($list_ip = [])
    {

        $result = [
            "type" => null,
            "type_id" => '',
            "type_name" => '',
        ];

        $string = "";
        foreach ($list_ip as $value) {

            if ($value != "") {
                if ($string == "")
                    $string = "'" . $value . "'";
                else
                    $string .= "," . "'" . $value . "'";
            }

        }
        if ($string == "") {
            return $result;
        }

        //tìm 1 device_id nào ứng với 1 IP truyền lên đầu tiên
        $device_ip = DB::select("select TOP 1 device_id from device_ip where ip in (" . $string . ")  and is_delete=0");

        if (empty($device_ip)) {
            return $result;
        }


        //tìm device_id =>  tìm type, và type_id cụ thê đang sử dụng để quẹt thẻ --------------
        $list_device = [];
        foreach ($device_ip as $key => $value1) {

            $device = DB::select("select type_id, [type] from device where id =?  and is_delete=0 ", [$value1->device_id]);

            if (empty($device[0]->type) || empty($device[0]->type_id)) {
                return $result;

            } else if (count($device) > 2) {

                echo "Không bao gio xẩy ra, vì  device.id là khoá chính ! ";
                exit;

            }
            $list_device[] = $device[0];

            //gán result
            $result["type"] = $list_device[0]->type;
            $result["type_id"] = $list_device[0]->type_id;

        }
        //tìm device_id =>  tìm type, và type_id cụ thê đang sử dụng để quẹt thẻ --------------


        //từ type, type_id => tìm type_name   ----------------
        $service = [];
        if ($list_device[0]->type == "1") {
            //1: khu vụ
            $service = DB::select("select * from areas where id=? and is_delete=0 and [status] =1", [$list_device[0]->type_id]);

        }
        if ($list_device[0]->type == "2") {

            //2: dịch vụ ăn uống
            $service = DB::select("select * from services where id=? and is_delete=0 and [status] =1", [$list_device[0]->type_id]);

        }
        if ($list_device[0]->type == "3") {

            //3: khu vui chơi
            $service = DB::select("select * from fun_spots where id=? and is_delete=0 and [status] =1", [$list_device[0]->type_id]);
        };
        if (count($service) == 0) {
            return $result;
        } else if (count($service) > 1) {

            echo "không bao giờ xẩy ra vì tìm theo ID";
            exit;

        } else {

            $result["type_name"] = $service[0]->name;
        }
        //từ type, type_id => tìm type_name   ----------------


        return $result;

    }
}
