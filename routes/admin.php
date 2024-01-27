<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DeviceConfigController;
use App\Http\Controllers\Admin\FunSpotController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TypeTicketController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {

    Route::post('/logout', [\App\Http\Controllers\Admin\UserController::class, 'logout'])->name('logout');
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('home/getStaffEatChart', [\App\Http\Controllers\HomeController::class, 'getStaffEatChart'])->name('home.getStaffEatChart');
    Route::post('home/getDataColumnGraphChart', [\App\Http\Controllers\HomeController::class, 'getDataColumnGraphChart'])->name('home.getDataColumnGraphChart');

    // quản lý khu vực

    Route::resources([
        'area' => AreaController::class,
        'fun-spot' => FunSpotController::class,
        'service' => ServiceController::class,
        'ticket' => TicketController::class,
        'type_ticket' => TypeTicketController::class,
        'order' => OrderController::class,
        'device' => DeviceConfigController::class,
        'company' => CompanyController::class,
    ]);

    Route::post('ticket/export', [TicketController::class, 'index'])->name('ticket.export');
    Route::post('order/export', [OrderController::class, 'index'])->name('order.export');

    Route::post('type_ticket/change-area', [TypeTicketController::class, 'changeDataByArea'])->name('type-ticket.change-area');

    //Cường : lấy thông tin dịch vụ và điêm vui chơi từ khu vực
    Route::get('area/get_service_fun_spot/{area_id}', [\App\Http\Controllers\Admin\AreaController::class, 'get_service_funspot_by_area'])->name('area.get_service_funspot_by_area');

    //tạo quyền bán vé
    Route::get('buy_ticket', [OrderController::class, 'create'])->name('buy_ticket.create');

    Route::get('ticket/in-ve/{id}', [TicketController::class, 'print'])->name('ticket.print');
    Route::get('order/in-ve/{id}', [OrderController::class, 'printMultiTicket'])->name('order.print');

    Route::post('ticket/detail', [TicketController::class, 'getDetailTicket'])->name('ticket.detail');
    Route::post('ticket/change_status_ticket', [TicketController::class, 'changeStatusTicket'])->name('ticket.change-status-ticket');


    Route::post('order/getDetailTypeTicket', [OrderController::class, 'getDetailTypeTicket'])->name('order.getDetailTypeTicket');
    Route::post('order/changStatus', [OrderController::class, 'changeStatus'])->name('order.change-status');
    Route::post('order/detail', [OrderController::class, 'orderDetail'])->name('order.detail');
    Route::post('order/getDataForChart', [OrderController::class, 'getDataForChart'])->name('order.getDataForChart');


    // quản lý tài khoản
    Route::get('/user', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('user');
    Route::post('/user/search', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('user_search');
    Route::get('/user/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('user_create');
    Route::post('/user_create', [\App\Http\Controllers\Admin\UserController::class, 'store']);
    Route::get('/user/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('user_edit');
    Route::get('/user/edit_on_user/{id}', [\App\Http\Controllers\Admin\UserController::class, 'editOnUser'])->name('user_edit_on_user');
    Route::put('/user_update/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update']);
    Route::any('/user/{id}', [\App\Http\Controllers\Admin\UserController::class, 'delete'])->name('user_delete');
    Route::get('/user/getFormAddcompanyToUser/{id}', [\App\Http\Controllers\Admin\UserController::class, 'getFormAddcompanyToUser'])->name('user_getFormAddcompanyToUser');
    Route::post('/user_AddcompanyToUser/updateFormAddcompanyToUser', [\App\Http\Controllers\Admin\UserController::class, 'updateFormAddcompanyToUser'])->name('user_updateFormAddcompanyToUser');

    // Quản lý nhóm quyền
    Route::any('roles', [\App\Http\Controllers\Web\RoleController::class, 'index'])->name('role.index');
    Route::get('roles/show-create-form', [\App\Http\Controllers\Web\RoleController::class, 'showCreateForm'])->name('role.show_create_form');
    Route::post('roles/create', [\App\Http\Controllers\Web\RoleController::class, 'create'])->name('role.create');
    Route::get('roles/show-edit-form/{id}', [\App\Http\Controllers\Web\RoleController::class, 'showEditForm'])->name('role.show_edit_form');
    Route::post('roles/update', [\App\Http\Controllers\Web\RoleController::class, 'update'])->name('role.update');
    Route::any('roles/delete/{id}', [\App\Http\Controllers\Web\RoleController::class, 'delete'])->name('role.delete');

    //sự kiện vào
    Route::get('eventReport', [\App\Http\Controllers\Web\EventReportController::class, 'index'])->name('eventReport.index');
    Route::post('eventReport/search', [\App\Http\Controllers\Web\EventReportController::class, 'index'])->name('eventReport.search');
    Route::post('eventReport/export-excel', [\App\Http\Controllers\Web\EventReportController::class, 'exportExcel'])->name('eventReport.exportExcel');

    //báo cáo cảnh báo
    Route::get('warningEventReport', [\App\Http\Controllers\Web\WarningEventController::class, 'index'])->name('warningEvent.index');
    Route::post('warningEventReport/search', [\App\Http\Controllers\Web\WarningEventController::class, 'index'])->name('warningEvent.search');
    Route::post('warningEventReport/exportExcel', [\App\Http\Controllers\Web\WarningEventController::class, 'exportExcel'])->name('warningEvent.exportExcel');


    // ================================= Log hệ thống ==================================================================
    Route::get('log', [\App\Http\Controllers\Web\LogController::class, 'list'])->name('log.index');
    Route::post('log/search', [\App\Http\Controllers\Web\LogController::class, 'list']);


    //    có thể tận dụng hoặc xóa phần bên dưới này-----------------------------------------------------------------------------
    Route::any('RevenueReport', [\App\Http\Controllers\Admin\RevenueReportController::class, 'index'])->name('revenueReport.index');

    Route::match(['get', 'post'], 'RevenueReport/report_with_user', [\App\Http\Controllers\Admin\RevenueReportController::class, 'reportWithUser'])->name('revenueReport.reportWithUser');
    Route::post('RevenueReport/report_with_user/export_excel', [\App\Http\Controllers\Admin\RevenueReportController::class, 'report_with_user_export_excel'])->name('revenueReport.reportWithUser.export_excel');


    Route::match(['get', 'post'], 'RevenueReport/report_with_ticket', [\App\Http\Controllers\Admin\RevenueReportController::class, 'reportWithTicket'])->name('revenueReport.reportWithTicket');
    Route::any('RevenueReport/report_with_ticket/export_excel', [\App\Http\Controllers\Admin\RevenueReportController::class, 'report_with_ticket_export_excel'])->name('revenueReport.reportWithTicket.export_excel');


//    //    có thể tận dụng hoặc xóa phần bên dưới này-----------------------------------------------------------------------------
//    Route::any('RevenueReport', [\App\Http\Controllers\Admin\RevenueReportController::class, 'index'])->name('revenueReport.index');
//    Route::get('RevenueReport/report_with_user', [\App\Http\Controllers\Admin\RevenueReportController::class, 'reportWithUser'])->name('revenueReport.reportWithUser');
//    Route::get('RevenueReport/report_with_ticket', [\App\Http\Controllers\Admin\RevenueReportController::class, 'reportWithTicket'])->name('revenueReport.reportWithTicket');

    /* License */
    Route::get('license/show', [\App\Http\Controllers\Web\LicenseController::class, 'show'])->name('license.show');
    Route::get('license/delete', [\App\Http\Controllers\Web\LicenseController::class, 'destroy'])->name('license.delete');


    // lịch sử gửi mail
    Route::any('mail_history', [\App\Http\Controllers\Web\MailHistoryController::class, 'index'])->name('mail_history.index');
});
