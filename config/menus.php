<?php
return [
    //-----------------------------------DANH MỤC ------------------------------------------------
    'Danh mục' => [
        'route_group' => '',
        'class' => 'ri-community-fill',
        'child_menu' => [
            'Khu vực' => [
                'route_group' => 'area', //sử dụng để active
                'route' => 'area.index', //route để gắn link
                'class' => 'ri-bubble-chart-line"', //class của icon
                'permission' => 'index_area', //quyền của menu
            ],

            'Danh sách tủ đồ' => [
                'route_group' => 'cabinet', //sử dụng để active
                'route' => 'cabinet.index', //route để gắn link
                'class' => 'ri-bard-fill', //class của icon
                'permission' => 'index_area', //quyền của menu
            ],

        ]
    ],
    //-----------------------------------DANH MỤC ------------------------------------------------

    //-----------------------------------VẬN HÀNH ------------------------------------------------
    'Vận hành' => [
        'route_group' => '',
        'class' => 'ri-apps-line',
        'child_menu' => [

            'Bán vé' => [
                'route_group' => 'buy_ticket', //sử dụng để active
                'route' => 'buy_ticket.create', //route để gắn link
                'class' => 'ri-bard-fill', //class của icon
                'permission' => 'create_order', //quyền của menu
            ],

            'DS hóa đơn' => [
                'route_group' => 'order', //sử dụng để active
                'route' => 'order.index', //route để gắn link
                'class' => 'ri-bard-fill', //class của icon
                'permission' => 'index_order', //quyền của menu
            ],

            'DS vé đã bán' => [
                'route_group' => 'ticket', //sử dụng để active
                'route' => 'ticket.index', //route để gắn link
                'class' => 'ri-bard-fill', //class của icon
                'permission' => 'index_ticket', //quyền của menu
            ],




        ]
    ],


    // //-----------------------------------BÁO CÁO ------------------------------------------------
    'Báo cáo' => [
        'route_group' => '',
        'class' => 'ri-pie-chart-2-line',
        'child_menu' => [

            'Báo cáo sự kiện vào' => [
                'route_group' => 'eventReport', //sử dụng để active
                'route' => 'eventReport.index', //route để gắn link
                'class' => 'ri-apps-line', //class của icon
                'permission' => 'index_event_report', //quyền của menu
            ],
            'Báo cáo doanh thu' => [
                'route_group' => 'revenueReport', //sử dụng để active
                'route' => 'revenueReport.index', //route để gắn link
                'class' => 'ri-money-dollar-circle-fill', //class của icon
                'permission' => 'index_revenue_report', //quyền của menu
            ],
            'Báo cáo sự kiện cảnh báo' => [
                'route_group' => 'warningEvent', //sử dụng để active
                'route' => 'warningEvent.index', //route để gắn link
                'class' => 'ri-error-warning-line', //class của icon
                'permision' => 'index_warning_event', //quyền của menu

            ],
            'Lịch sử gửi mail' => [
                'route_group' => 'mail_history', //sử dụng để active
                'route' => 'mail_history.index', //route để gắn link
                'class' => 'ri-mail-send-line', //class của icon
                'permission' => 'index_mail_history', //quyền của menu
            ],
        ]
    ],

    // //-----------------------------------BÁO CÁO ------------------------------------------------


    // //---------------------------------- PHÂN QUYỀN ------------------------------------------------
    'Hệ thống' => [
        'class' => 'ri-lock-line',
        'child_menu' => [
            'Tài khoản' => [
                'route_group' => 'user', //sử dụng để active
                'route' => 'user', //route để gắn link
                'class' => 'ri-user-fill', //class của icon
                'permission' => 'index_user', //quyền của menu
            ],

            'Nhóm quyền' => [
                'route_group' => 'role', //sử dụng để active
                'route' => 'role.index', //route để gắn link
                'class' => 'ri-key-2-line', //class của icon
                'permission' => 'index_role', //quyền của menu
            ],

             'Công ty' => [
                 'route_group' => 'company', //sử dụng để active
                 'route' => 'company.index', //route để gắn link
                 'class' => 'ri-tools-line', //class của icon
                 'permission' => 'index_company', //quyền của menu
             ],
        ]
    ]
    // menu 1 cấp

];
