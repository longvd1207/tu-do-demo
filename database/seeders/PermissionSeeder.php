<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //tên các quyền trong nhóm quyền này
        $data = [
            'user' => [
                'index_user' => 'Xem thông tin người dùng',
                'create_user' => 'Thêm mới người dùng',
                'update_user' => 'Cập nhật thông tin người dùng',
                'delete_user' => 'Xóa thông tin người dùng'
            ],
            'role' => [
                'index_role' => 'Xem vai trò',
                'create_role' => 'Thêm vai trò mới',
                'update_role' => 'Cập nhật thông tin vai trò',
                'delete_role' => 'Xóa vai trò'
            ],
            'cabinet' => [
                'index_cabinet' => 'Xem thông tin tủ đồ',
                'create_cabinet' => 'Thêm mới tủ đồ',
                'update_cabinet' => 'Cập nhật tủ đồ',
                'delete_cabinet' => 'Xóa thông tin tủ đồ'
            ],
            'fun_spot' => [
                'index_fun_spot' => 'Xem thông tin khu vui chơi',
                'create_fun_spot' => 'Thêm mới khu vui chơi',
                'update_fun_spot' => 'Cập nhật khu vui chơi',
                'delete_fun_spot' => 'Xóa khu vui chơi'
            ],

            'service' => [
                'index_service' => 'Xem thông tin dịch vụ',
                'create_service' => 'Thêm mới dịch vụ',
                'update_service' => 'Cập nhật dịch vụ',
                'delete_service' => 'Xóa dịch vụ'
            ],

            'ticket' => [
                'index_ticket' => 'Xem thông tin vé',
                'update_ticket' => 'Cập nhật vé',
                'delete_ticket' => 'Xóa vé',
            ],

            'device' => [
                'index_device' => 'Xem thông tin cấu hình thiết bị',
                'update_device' => 'Cập nhật cấu hình thiết bị',
                'delete_device' => 'Xóa cấu hình thiết bị '
            ],


            'company' => [
                'index_company' => 'Xem thông tin công ty',
                'create_company' => 'Thêm mới công ty',
                'update_company' => 'Cập nhật thông tin công ty',
                'delete_company' => 'Xóa công ty '
            ],

            'order' => [
                'index_order' => 'Xem thông tin đơn hàng',
                'create_order' => 'Tạo mới đơn hàng (Bán vé)',
            ],

            'log' => [
                'index_log' => 'Hiển thị log',
            ],
            'config' => [
                'index_config' => 'Cho phép cài đặt hệ thống'
            ],


            'reportSynthetic' => [
                'index_event_report' => 'Xem báo cáo sự kiện vào',
                'export_event_report' => 'Export báo cáo sự kiện vào',

                'index_warning_event' => 'Xem báo cáo cảnh báo',
                'export_warning_event' => 'Export báo cáo cảnh báo',
            ],

            'revenueReport' => [
                'index_revenue_report' => 'Xem báo cáo doanh thu',
                'report_with_user_revenue' => 'Chi tiết doanh thu theo người bán',
                'report_with_ticket_revenue' => 'Chi tiết doanh thu theo loại vé'
            ],


            'mail_history' => [
                'index_mail_history' => 'Xem lịch sử gửi mail'
            ],
        ];

        $data_permission = [];
        foreach ($data as $key => $value) {
            foreach ($value as $keyVal => $val) {
                $data_permission[] = $keyVal; //tên các quyền cụ thể : index_company , create_company ,...., để dùng cho việc xoá các quyền ko có trong này

                $data_insert = [
                    'name' => $keyVal,  //tên các quyền : index_company , create_company ,....
                    'description' => $val,
                    'guard_name' => 'web',
                    'module' => $key //tên module : company,registerEat ...
                ];

                //kiểm tra trong bảng permissions đã có tên quyền này chưa ?
                if (count(DB::table('permissions')->where('name', $data_insert['name'])->get()) == 0) {
                    DB::table('permissions')->insert([
                        $data_insert
                    ]);
                }
            }
        }

        DB::table('permissions')->whereNotIn('name', $data_permission)->delete();


        // sau khi hoàn tất thì chạy lệnh để đổ dữ liệu vào database:
        // bước 1 : xoá hết các quyên trước của module đó : chọn 1 module và ẩn các quyền đi , để module đó rỗng. chạy php artisan db:seed --class=PermissionSeeder
        // bước 2 : bật lại các quyền của mobule này và chạy lênh : php artisan db:seed --class=PermissionSeeder
    }
}
