<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\MailHistory\MailHistoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MailHistoryController extends Controller
{
    protected $mailHistoryRepo;

    public function __construct(MailHistoryRepositoryInterface $mailHistoryRepo)
    {
        $this->mailHistoryRepo = $mailHistoryRepo;
    }

    public function index(Request $request)
    {
        $breadcrumb = [
            [
                'title' => 'Báo cáo',
                'route' => ''
            ],
            [
                'title' => 'Lịch sử gửi mail',
                'route' => ''
            ]
        ];

        if (!session()->has('search')) {
            session([
                'search' => [
                    'page' => 1,
                    'key_search' => '',
                ]
            ]);
        }

        if (isset($request->confirm_search) and $request->confirm_search == "1") {
            session(['search.page' => 1]);

            if (isset($request->key_search)) {
                session(['search.key_search' => trim($request->key_search)]);
            } else {
                session(['search.key_search' => ""]);
            }

            if (isset($request->date)) {
                session(['search.date' => trim(date('Y-m-d', strtotime($request->date)))]);
            } else {
                session(['search.date' => ""]);
            }

            return redirect('admin/mail_history');
        }
        
        $filter = [];
        if (session('search.key_search') != '') {
            $filter['key_search'] = session('search.key_search');
        }

        if (session('search.date') != '') {
            $filter['date'] = session('search.date');
        }

        $data = $this->mailHistoryRepo->getData($filter, 0, ['customer', 'order']);

        return view('admin.mail_history.index', [
            'breadcrumb' => $breadcrumb,
            'data' => $data,
            // 'limit' => $limit,
        ]);
    }
}
