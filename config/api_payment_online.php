<?php
//API thanh toan online
return [
    //api login
    "api_payment_login" =>[
        "url"=>"http://14.160.26.45:15000/connect/token",
        "param"=> [
            "Client_id" => "2a391540-c6e8-4863-b9c5-8cbb3804544b",
            "Client_secret" => "muqG(NDs~@kI&9F!072BFN2?+7q>8U",
            "scope" => "payment-api.readwrite",
            "grant_type" => "client_credentials",
        ]
    ] ,
    //api thanh toán
    "api_payment_momo" =>[
        "url"=>"http://14.160.26.45:10000/PaymentRequest/OneTimeQR/NewTransaction?provider=88&redirectUrl=" . '[url_replace]' . "/payment_result",
        "param"=> [
            "paymentObjectName" => "Mua vé Online tại Bảo tàng vũ trụ",
            "description" => "Mô tả đơn hàng",
            "companyName" => "Bảo tàng vũ trụ",
            "companyCode" => "KZTEK., JSC",
            "user" => [
                "id" => "E65050B2-48C1-4BF0-8309-5F6E4AB78A0B",
                "name" => "Đỗ Quốc Cường",
                "code" => "ma_user_cuong",
                "email" => "cuongdqthaithinh@gmail.com",
                "phonenumber" => "0832938450"
            ],
            "categoryId" => "string",
            "bankCode" => ""
        ]
    ] ,
    //api check trạng thái
   "api_payment_momo_status"=>[
       "url"=>"http://14.160.26.45:10000/PaymentStatus?orderId=[orderId]",
   ]

];

