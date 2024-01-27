<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditCompanyRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Repositories\Company\CompanyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class CompanyController extends Controller
{

    protected $companyRepository;

    public function __construct(
        CompanyRepositoryInterface $companyRepository
    )
    {
        $this->companyRepository = $companyRepository;
    }


    public function index(Request $request)
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Công ty',
                'route' => 'company.index'
            ]
        ];
        $conditions = [];
        $condition_likes = [];
        $page = $request->page;
        $keyword = $request->key_search;
        $num_show = $request->num_show;

        $conditions['is_delete'] = 0;
        if ($keyword) {
            $condition_likes['name'] = $keyword;
        }
        $limit = isset($num_show) > 0 ? $num_show : 20;

        $columns = ['*'];
        $companies = $this->companyRepository->paginateWhereLikeOrderBy($conditions, $condition_likes, 'updated_at', 'DESC', $page ?: 1, $limit, $columns);
        return view('admin.company.index', compact('companies', 'breadcrumb'));
    }


    public function create()
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Thêm mới',
                'route' => 'company.create'
            ]
        ];

        return view('admin.company.create', compact('breadcrumb'));
    }

    public function store(StoreCompanyRequest $request)
    {
        $request->validated();

        try {
            $data = [
                'id' => getGUID(),
                'name' => $request->name,
                'code' => $request->code,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'is_delete' => 0
            ];
            $this->companyRepository->create($data);
            return redirect(route('company.index'))->with('success', 'Thêm mới thành công');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect(back())->with('error', 'Có lỗi xảy ra trong quá trình thêm mới');
        }
    }


    public function edit($id)
    {
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Cập nhật',
                'route' => ''
            ]
        ];

        $company = $this->companyRepository->getById($id);
        return view('admin.company.edit', compact('company', 'breadcrumb'));
    }

    public function update(EditCompanyRequest $request, $id)
    {
        $request->validated();
        $company = $this->companyRepository->getById($id);
        if ($company) {
            $data = [
                'name' => $request->name,
                'code' => $request->code,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address
            ];
            $this->companyRepository->update($id, $data);
            return redirect(route('company.index'))->with('success', 'Cập nhật thành công');
        }
        return redirect(back())->with('error', 'Có lỗi xảy ra trong quá trình cập nhật');
    }

    public function destroy($id)
    {
        $company = $this->companyRepository->getById($id);
        if ($company) {
            $this->companyRepository->update($id, ['is_delete' => 1]);
            $company->delete($id);
            return redirect(route('company.index'))->with('success', 'Xóa thành công');
        }
        return redirect(back())->with('error', 'Có lỗi xảy ra trong quá trình xóa');
    }
}
