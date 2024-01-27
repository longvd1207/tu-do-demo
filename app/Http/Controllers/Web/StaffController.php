<?php

namespace App\Http\Controllers\Web;

use App\Exports\staff\StaffExport;
use App\Http\Controllers\Controller;

use App\Http\Requests\StaffRequest;

use App\Imports\StaffImport;

use App\Models\Config;
use App\Models\Staff;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\Position\PositionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Dompdf\Exception;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    protected $userRepo;
    protected $staffRepo;
    protected $companyRepo;
    protected $departmentRepo;
    protected $positionRepo;


    public function __construct(
        StaffRepositoryInterface      $staffRepo,
        UserRepositoryInterface       $userRepo,
        CompanyRepositoryInterface    $companyRepo,
        DepartmentRepositoryInterface $departmentRepo,
        PositionRepositoryInterface   $positionRepo
    )
    {
        $this->userRepo = $userRepo;
        $this->staffRepo = $staffRepo;
        $this->companyRepo = $companyRepo;
        $this->departmentRepo = $departmentRepo;
        $this->positionRepo = $positionRepo;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
       // $currentRoute =Route::current();
       $this->resetSessionSearch('admin/staff');

        $this->authorize('index_staff');

        $auth_company = $this->getListCompanyForUser();
//     dd($auth_company);

        $this->resetSessionSearch('admin/staff');
        $breadcrumb = [
            [
                'title' => 'Danh mục',
                'route' => ''
            ],
            [
                'title' => 'Nhân viên',
                'route' => 'staff.index'
            ]
        ];

        if (!session()->has('search')) {
            session([
                'search' => [
                    'page' => 1,
                    'key_search' => '',
                    'company_id',
                    'company_id',
                    'department_id',
                    'position_id',
                    'type_staff',
                    'card_id',
                    'is_manager',
                    'gender_staff'
                ]
            ]);
        }

        if (isset($request->confirm_search) && $request->confirm_search == "1") {

            session(['search.page' => 1]);

            if (isset($request->key_search)) {
                session(['search.key_search' => trim($request->key_search)]);
            }
            else {
                session(['search.key_search' => ""]);
            }

            if (isset($request->company_id)) {
                session(['search.company_id' => trim($request->company_id)]);
            }
            else {
                session(['search.company_id' => ""]);
            }

            if (isset($request->department_id)) {
                session(['search.department_id' => trim($request->department_id)]);
            }
            else {
                session(['search.department_id' => ""]);
            }

            if (isset($request->position_id)) {
                session(['search.position_id' => trim($request->position_id)]);
            }
            else {
                session(['search.position_id' => ""]);
            }

            if (isset($request->type_staff)) {
                session(['search.type_staff' => trim($request->type_staff)]);
            }
            else {
                session(['search.type_staff' => ""]);
            }

            if (isset($request->is_manager)) {
                session(['search.is_manager' => trim($request->is_manager)]);
            }
            else {
                session(['search.is_manager' => ""]);
            }

            if (isset($request->gender_staff)) {
                session(['search.gender_staff' => trim($request->gender_staff)]);
            }
            else {
                session(['search.gender_staff' => ""]);
            }

            if (isset($request->card_id)) {
                $request->card_id = trim($request->card_id);
                if (strlen($request->card_id) == 10) {
                    $request->card_id = dechex((int)$request->card_id);
                    $request->card_id = strtoupper($request->card_id);
                }

                session(['search.card_id' => $request->card_id]);
            } else {
                session(['search.card_id' => ""]);
            }

            return redirect('admin/staff');
        }

        //kích vào link chuyển trang
        if (isset($request->page) and (int)$request->page > 0) session(['search.page' => (int)$request->page]);


        //  dd($request->key_search,session()->all());

        $search_option = array();

        if (session('search.company_id') != '') {
            $search_option[] = ['company_id', '=', session('search.company_id')];
        }
        if (session('search.department_id') != '') {
            $search_option[] = ['department_id', '=', session('search.department_id')];
        }
        if (session('search.position_id') != '') {
            $search_option[] = ['position_id', '=', session('search.position_id')];
        }
        if (session('search.type_staff') != '') {
            $search_option[] = ['type', '=', (int)session('search.type_staff')];
        }
        if (session('search.is_manager') != '') {
            $search_option[] = ['is_manager', '=', (int)session('search.is_manager')];
        }
        if (session('search.gender_staff') != '') {
            $search_option[] = ['gender', '=', (int)session('search.gender_staff')];
        }
        if (session('search.card_id') != '') {
            $search_option[] = ['card_id', '=', session('search.card_id')];
        }


        //        $limit = $request->input('limit') ?? config('app.paginate.per_page');
        //        $orderBy = $request->input('order_by') ?? 'id';
        //        $orderType = $request->input('order_type') ?? 'desc';
        //        $key_search = session()->has('key_search') ? session('key_search') : '';


        $limit = 30;
        $data = $this->staffRepo->getWithFilter(session('search.key_search'), $limit, session('search.page'), $search_option,$auth_company);
        //dd($data);
        $total = count($this->staffRepo->getWithFilter(session('search.key_search'), 0, -1, $search_option,$auth_company));
       // dd($total);
        $list_company = $this->companyRepo->getWithFilter('', -1, [
            ['is_delete', '=', 0]
        ],$auth_company);

        // $list_department = $this->departmentRepo->getWithFilter('', -1, [
        //     ['is_delete', '=', 0]
        // ]);

        // $list_position = $this->positionRepo->getWithFilter('', -1, [
        //     ['is_delete', '=', 0]
        // ]);

          // dd($data[0]->TotalRegisterEatOfOneStaff($data[0]["id"]));

        //Cường : lấy time config ở đây , để truy vấn db chỉ có 1 lần ---------------------------
        $config = Config::find(1);
        $config = json_decode($config->content, true);
        $time_config = [];

        foreach ($config['config_time'] as $key => $value) {
            if (!empty($value['name'])) {
                $time_config[$key] = $value;
            }
        }
//Cường : lấy time config ở đây , để truy vấn db chỉ có 1 lần ---------------------------

        return view('admin.staff.index', [
            'data' => $data,
            'total' => $total,
            'limit' => $limit,
            'breadcrumb' => $breadcrumb,
            'list_company' => $list_company,
            'time_config' => $time_config
        ]);
    }

    public function showCreateForm()
    {
      // dd(old('is_manager'));
        $this->authorize('create_staff');

        $auth_company = $this->getListCompanyForUser();

        $list_company = $this->companyRepo->getWithFilter('', -1, [
            ['is_delete', '=', 0]
        ], $auth_company);

        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Danh mục',
                'route' => 'staff.index'
            ],
            [
                'title' => 'Thêm nhân viên'
            ],
        ];
        return view(
            'admin.staff.form',
            [
                'breadcrumb' => $breadcrumb,
                'action' => 'staff.create',
                'list_company' => $list_company
            ]
        );
    }

    public function create(StaffRequest $request)
    {

        $this->authorize('create_staff');

        $dataRequest = $request->all();


//        "id" => null
//      "type_old" => null
//      "file_link_image_old" => null
//      "_token" => "WGopNQ2g0SbP1DXaJsl3cNDOmzrU5KJccARGTZsG"
//      "code" => "1"
//      "name" => "2"
//      "gender" => "1"
//      "phone" => "3"
//      "company_id" => "FFEBD061-FFC3-C3E8-170C-7602AD448D59"
//      "is_manager" => "on"
//      "user_name" => "admin"
//      "password" => "123456"

        //kiểm tra username đã có chưa--------
        if (count($user = $this->userRepo->getDataByFilter(['user_name' => $dataRequest['user_name']])) > 0) {

            return redirect()->back()->with('alert-error', 'user_name đã được đăng kí, vui lòng điện user_name khác!');
        }
        $staff_id = getGUID();
        $user_id = getGUID();

        //--------------BẢNG STAFF--------------------------------
        {
            $data = [
                'id' => $staff_id,
                'user_id' => $user_id,
//            'card_id' => isset($dataRequest['card_id']) ? (strtoupper($dataRequest['card_id'])) : null,
                'name' => isset($dataRequest['name']) ? ($dataRequest['name']) : null,
                'code' => isset($dataRequest['code']) ? ($dataRequest['code']) : null,
                'phone' => isset($dataRequest['phone']) ? ($dataRequest['phone']) : null,
                'gender' => isset($dataRequest['gender']) ? ($dataRequest['gender']) : null,
                'is_manager' => isset($dataRequest['is_manager']) ? 1 : 0,
                'company_id' => isset($dataRequest['company_id']) ? ($dataRequest['company_id']) : null,
//            'department_name' => isset($dataRequest['department_name']) ? ($dataRequest['department_name']) : null,
//            'position_name' => isset($dataRequest['position_name']) ? ($dataRequest['position_name']) : null,
                'is_delete' => 0
            ];
            //type
            // $company_val = $this->companyRepo->getById($data["company_id"]);
            $company_val = $this->companyRepo->getById($data["company_id"]);

            if (!isset($company_val)) {
                return redirect()->back()->with('alert-error', "Lỗi : không tồn tại phòng ban có  id=" . $data["company_id"]);
                exit;
            }

            //xử lý ảnh ==========================================
            if ($request->hasFile('file_link_image')) {
                $image = $request->file('file_link_image');

                //lấy loại file
                if (!in_array($image->getClientMimeType(), unserialize(config('app.image_type')))) {
                    $arr_error["file_link_image"] = "Phải chọn file ảnh đuôi : jpeg , png , jpg !";
                    throw ValidationException::withMessages($arr_error);
                    exit;
                    //  var_dump("FILE:" . __FILE__ . ",FUNCTION:" . __FUNCTION__ . ",LINE:" . __LINE__);
                    //   exit;
                    // return redirect('Admin/news/create/' . $danhmuctintuc_id)->with('error', 'Phải chọn file ảnh đuôi : jpeg png gif jpg bmp');


                }
                $name_file = uniqid('kztek_') . "_" . $this->staffRepo->convert_name($image->getClientOriginalName());

                $link_file = public_path() . "\/" . config('app.folder_image');
                //dd($link_file);


                //copy ảnh
                $storedPath = $image->move($link_file, $name_file);

                //resize ảnh vào chính ảnh vừa copy
                $this->resizeImage($link_file . "/" . $name_file, $link_file . "/" . $name_file, 300);
            }
            if ($request->hasFile('file_link_image')) {
                $data["image_link"] = "public/" . config('app.folder_image') . "/" . $name_file;
                // dd($data["image_link"]);
                //crop ảnh về 250x250 từ tâm
                //   $this->resize_crop_image(250, 250, $data["image_path"], $data["image_path"]);
            } else
                $data["image_link"] = "";
            //xử lý ảnh ==========================================
        }
        // dd($staff);
        //--------------BẢNG STAFF--------------------------------

        //--------------BẢNG USER--------------------------------
        {
            $user_data = [
                'id' => $user_id,
                'staff_id' => $staff_id,
                'is_delete' => 0,
                'name' => $data['name'],
//                'email' => isset($dataRequest['email']) ? ($dataRequest['email']) : null,
                'password' => isset($dataRequest['password']) ? bcrypt($dataRequest['password']) : null,
                'id_token' => $this->getToken(),
                'refresh_token' => $this->getToken(),
                'access_token' => $this->getToken(),
                'user_name' => isset($dataRequest['user_name']) ? ($dataRequest['user_name']) : null,
                'phone' => isset($dataRequest['phone']) ? ($dataRequest['phone']) : null,
                'user_avatar' => $data['image_link'],
//                'address' => isset($dataRequest['address']) ? ($dataRequest['address']) : null,
                'type' => 1, //1 là tài khoản dùng ở web,
                'list_company_id' => json_encode([$data["company_id"]]),
            ];
            //nếu là trường phòng
            if ((int)$data["is_manager"] == 1)
                $user_data["type_of_web"] =3 ;
            else
                //nếu là nhân viên
                $user_data["type_of_web"] =4 ;

        }
        // dd($user);
        //--------------BẢNG USER--------------------------------
        DB::beginTransaction();

        try {

            $result = $this->staffRepo->create($data);

            //biến user này sẽ dùng cho gán role ở dưới
            $user = $this->userRepo->create($user_data);

            if (!$result or !$user) {
                throw new Throwable('Thêm mới lỗi ! !');

              //  return redirect()->back()->with('alert-error', 'Thêm mới lỗi !');
            }


            //cập nhật vai trò--------------------------
            //xoá đi tất các role đã gán cho user này trước đó
            $user->syncRoles([]);
            //nếu là vai trò trưởng phòng
            if ((int)$data["is_manager"] == 1) {

                //tìm ro
                $role = Role::where("is_manager", 1)->first();

                if ($role) {
                    //gán role mới
                    $user->assignRole($role);
                } else {
                    throw new Throwable('Lỗi: không tìm thấy vai trò trưởng phòng !');
                   // return redirect()->back()->with('alert-error', 'Lỗi: không tìm thấy vai trò trưởng phòng !');
                }
            } else {
                //nếu là vài trò nhân viên
                //tìm ro
                $role = Role::where("is_manager", 0)->first();
               // dd($role);
                if ($role) {
                    //gán role mới
                    $user->assignRole($role);
                } else {
                    throw new Throwable('Lỗi: không tìm thấy vai trò nhân viên !');

                  //  return redirect()->back()->with('alert-error', 'Lỗi: không tìm thấy vai trò nhân viên !');
                }

            }
            //cập nhật vai trò--------------------------


            DB::commit();
            return redirect()->route('staff.index')->with('alert-success', 'Thêm mới thành công');
        } catch (\Throwable $th) {

            DB::rollBack();
            Log::error($th->getMessage());

            return redirect()->back()->with('alert-error', 'Có lỗi trong quá trình thêm mới!');
        }


        //bảng user--------------
    }


    public function showEditForm($id)
    {

        $this->authorize('update_staff');

        $auth_company = $this->getListCompanyForUser();

        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Danh mục',
                'route' => 'staff.index'
            ],
            [
                'title' => 'Chỉnh sửa thông tin nhân viên'
            ],
        ];
        //mình chỉ dc chỉnh sửa của mình thôi
        if (Auth::user()->type > 1 && $id != Auth::user()->staff_id) {
            return redirect()->back()->with('alert-error', 'Bạn không được chỉnh sửa thông tin khu vực này!');
        }

//        $auth_company = $this->getListCompanyForUser();

        $list_company = $this->companyRepo->getWithFilter('', -1, [
            ['is_delete', '=', 0]
        ], $auth_company);

//        $list_department = $this->departmentRepo->getWithFilter('', -1, [
//            ['is_delete', '=', 0]
//        ]);
//
//        $list_position = $this->positionRepo->getWithFilter('', -1, [
//            ['is_delete', '=', 0]
//        ]);

        try {

            $data = $this->staffRepo->getById($id);

            if ($data) {
                $user = $this->userRepo->getById($data->user_id);
            } else {
                return redirect()->back()->with('alert-error', 'Không tìm thấy nhân viên có id=' . $id);
            }


            return view(
                'admin.staff.form',
                [
                    'data' => $data,
                    'user' => $user,
                    'breadcrumb' => $breadcrumb,
                    'action' => 'staff.update',
                    'list_company' => $list_company,
//                    'list_department' => $list_department,
//                    'list_position' => $list_position
                ]
            );
        } catch (\Throwable $th) {
            return redirect()->back()->with('alert-error', 'Không tìm thấy nhân viên !');
        }
    }

    public function update(StaffRequest $request)
    {

//        "id" => "719C5B86-0C12-5EAD-41DF-EE19075318D5"
//  "type_old" => null
//  "file_link_image_old" => null
//  "_token" => "punBCYV86AFsSDOG5ofO322zukCgm4fes0hezvsb"
//  "code" => "11"
//  "name" => "12"
//  "gender" => "1"
//  "phone" => "0832938450"
//  "company_id" => "5342EAB3-A977-BBBE-E6F1-6D925FB71073"
//]
//
        $this->authorize('update_staff');

        $dataRequest = $request->all();
//        dd($dataRequest);

        //-------------------NHÂN VIÊN -----------------------------------
        {
            $id_staff = $request->id;
            $id_user = $request->user_id;

            $data = [
                'card_id' => isset($dataRequest['card_id']) ? (strtoupper($dataRequest['card_id'])) : null,
                'name' => isset($dataRequest['name']) ? ($dataRequest['name']) : null,
                'code' => isset($dataRequest['code']) ? ($dataRequest['code']) : null,
                'phone' => isset($dataRequest['phone']) ? ($dataRequest['phone']) : null,
                'company_id' => isset($dataRequest['company_id']) ? ($dataRequest['company_id']) : null,
                'gender' => isset($dataRequest['gender']) ? ($dataRequest['gender']) : null,
                'is_manager' => isset($dataRequest['is_manager']) ? 1 : 0,
//            'department_name' => isset($dataRequest['department_name']) ? ($dataRequest['department_name']) : null,
//            'position_name' => isset($dataRequest['position_name']) ? ($dataRequest['position_name']) : null,
                'is_delete' => 0
            ];


            //giá tri type cũ
//        $type_old = isset($request->type_old) ? (int)$request->type_old : null;


            //type
//        $company_val = $this->companyRepo->getById($data["company_id"]);
//        if (isset($company_val->type)) {
//            $data["type"] = (int)$company_val->type;
//        } else {
//            echo "Lỗi : không tồn tại công ty id=" . $data["company_id"];
//        }

            //xử lý ảnh ==========================================
            if ($request->hasFile('file_link_image')) {
                $image = $request->file('file_link_image');

                //lấy loại file
                if (!in_array($image->getClientMimeType(), unserialize(config('app.image_type')))) {

                    $arr_error["file_link_image"] = "Phải chọn file ảnh đuôi : jpeg , png , jpg !";
                    throw ValidationException::withMessages($arr_error);
                    exit;
                }

                //xóa ảnh cũ
                $duongdanfile_cu = str_replace("public/", "", $request->file_link_image_old);
                if (file_exists($duongdanfile_cu)) {
                    unlink($duongdanfile_cu);
                }

                $name_file = uniqid('kztek_') . "_" . $this->staffRepo->convert_name($image->getClientOriginalName());

                $link_file = public_path() . "\/" . config('app.folder_image');

                $storedPath = $image->move($link_file, $name_file);

                //resize ảnh vào chính ảnh vừa copy
                $this->resizeImage($link_file . "/" . $name_file, $link_file . "/" . $name_file, 300);

                $data["image_link"] = "public/" . config('app.folder_image') . "/" . $name_file;

                //crop ảnh về 250x250 từ tâm
                //  $this->resize_crop_image(250, 250, $athlete["image_path"], $athlete["image_path"]);
            }

            //xử lý ảnh ==========================================
        }
        //-------------------NHÂN VIÊN -----------------------------------


        //-------------------TÀI KHOẢN -----------------------------------
        {
            $user = [
                'name' => $data['name'],
//                'email' => isset($dataRequest['email']) ? ($dataRequest['email']) : null,
//                'user_name' => isset($dataRequest['user_name']) ? ($dataRequest['user_name']) : null,
                'phone' => $data['phone'],
//                'address' => isset($dataRequest['address']) ? ($dataRequest['address']) : null,
                'list_company_id' => json_encode([$data["company_id"]]),
            ];

            if (isset($dataRequest['password']) and $dataRequest['password'] != "") {
                $user["password"] = bcrypt($dataRequest['password']);
            }

            if (isset($data["image_link"]) and $data["image_link"] != "") $user['user_avatar'] = $data['image_link'];


            //nếu là trường phòng
            if ((int)$data["is_manager"] == 1)
                $user["type_of_web"] =3 ;
            else
                //nếu là nhân viên
                $user["type_of_web"] =4 ;
        }
        //-------------------TÀI KHOẢN -----------------------------------


        DB::beginTransaction();
        try {

            //0 là dc phép update
//            $check_update = 0;
//
//            if (isset($type_old)) {
//
//                //chuyển từ đối tác sang nhân viên
//                if ($type_old == 0 and $data["type"] == 1) {
//                    //kiểm tra bảng lích sử ăn
//                    $list_staff_eat = DB::select("select count(*) as tong from [dbo].[tbl_staff_eat] where staff_id=? and is_delete=0", [$id_staff]);
//
//                    if (isset($list_staff_eat[0]->tong) and (int)$list_staff_eat[0]->tong > 0) {
//                        $error = "Nhân viên này đã có trong bảng lịch sử ăn !";
//                        $check_update = 1;
//                    }
//
//                    if ($check_update == 0) {
//                        //kiểm tra bảng đăng ký ăn
//                        $list_register_eat = DB::select("select count(*) as tong from [dbo].[tbl_register_eat] where staff_id=? and is_delete=0", [$id_staff]);
//
//                        if (isset($list_register_eat[0]->tong) and (int)$list_register_eat[0]->tong > 0) {
//                            $error = "Nhân viên này đã có trong bảng đăng ký ăn !";
//                            $check_update = 1;
//                        }
//                    }
//
//                    //lỗi
//                    if ($check_update != 0) {
//                        Log::error("ko dc update do type");
//                        throw new Exception($error);
//                    }
//                } else if ($type_old == 1 and $data["type"] == 0) {
//                    //từ lotte sang dối tác
//                    //kiểm tra bảng lích sử ăn
//                    $list_staff_eat = DB::select("select count(*) as tong from [dbo].[tbl_staff_eat] where staff_id=? and is_delete=0", [$id_staff]);
//
//                    if (isset($list_staff_eat[0]->tong) and (int)$list_staff_eat[0]->tong > 0) {
//                        $error = "Nhân viên mã này đã có trong bảng lịch sử ăn !";
//                        $check_update = 1;
//                    }
//
//                    //lỗi
//                    if ($check_update != 0) {
//                        Log::error("ko dc update do type");
//                        throw new Exception($error);
//                    }
//                } else {
//                    //khong thay đổi type=> ko làm gì cả ,update bình thường
//                }

            $this->staffRepo->update($id_staff, $data);

            $this->userRepo->update($id_user, $user);

            //cập nhật vai trò  ------------------------
            $user = $this->userRepo->getById($id_user);

            //xoá đi tất các role đã gán cho user này trước đó
            $user->syncRoles([]);

            //nếu là trưởng phòng : gán
            if ((int)$data["is_manager"] == 1) {
                //tìm ro
                $role = Role::where("is_manager", 1)->first();
                if ($role) {
                    //gán role mới
                    $user->assignRole($role);
                } else {
                    throw new Throwable('Lỗi: không tìm thấy vai trò trưởng phòng !');

                  //  return redirect()->back()->with('alert-error', 'Lỗi: không tìm thấy vai trò trưởng phòng !');
                }
            } else {
                //tìm ro
                $role = Role::where("is_manager", 0)->first();
               //  dd($role);
                if ($role) {
                    //gán role mới
                    $user->assignRole($role);
                } else {
                    throw new Throwable('Lỗi: không tìm thấy vai trò nhân viên !');

                  //  return redirect()->back()->with('alert-error', 'Lỗi: không tìm thấy vai trò nhân viên !');
                }
            }
            //cập nhật vai trò  ------------------------

//            } else {
//                echo "ko bao gio vào tính huống này ";
//                exit;
//            }

            DB::commit();
        } catch (\Throwable $th) {

            DB::rollBack();

            $errorMessage = $th->getMessage();

            Log::error($errorMessage);

            return redirect()->back()->with('alert-error', 'Có lỗi trong quá trình update:' . $errorMessage);
        }

//        return redirect('admin/staff' . (session('search.page') != 1 ? "?page=" . session('search.page') : ""))->with('alert-success', 'Update thành công!');
        return redirect()->back()->with('alert-success', 'Update thành công!');
    }

    //đọc dữ liệu từ file excel
    public function staff_import_exel(Request $request)
    {
        $this->authorize('import_staff');

        // dd($request->file_list_staff);
        if (empty($request->file_list_staff)) {
            # code...
            return redirect()->route('staff.index')->with('alert-error', 'Bạn cần chọn file để import dữ liệu!');
        }

        try {
            //lưu file
            //đọc và insert vào db
            //chú ý : file excel  phải đóng lại trước khi chọn để import
            $import = new StaffImport($this->staffRepo);
            Excel::import($import, $request->file('file_list_staff')->store('files')); //,\Maatwebsite\Excel\Excel::XLSX

            //kết quả trả về
            $rs_return = $import->getResutl();
            //mã lỗi trả về
            $rs_error_string = $import->getErrorString();
            //dòng lỗi
            $rs_error_stt = $import->getErrorStt();

            if ($rs_return === 90) {
                return redirect()->route('staff.index')->with('alert-error', 'Lỗi server, xem log server.');
            } else if ($rs_return === 200) {

                return redirect()->route('staff.index')->with('alert-success', 'Thêm mới thành công');
            } else {
                echo "Lỗi : chưa định nghĩa mã trả về cho import excel nhân viên !";
                exit;
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            //validate sai các cột của excel => nó sẽ rơi vào đây.
            $failures = $e->failures();

            Log::error($failures);

            $string_error = "";
            $array_error = [];

            foreach ($failures as $failure) {
                $array_error[$failure->row()][] = $failure->errors()[0];
            }

            return redirect()->route('staff.index')->with('import-error', json_encode($array_error));
        }
    }

    //xuất dữ liệu từ excel
    public function staff_export_exel()
    {

        // session()->flush();

        // $this->resetSessionSearch('admin/staff');

        //  $this->authorize('index_staff');

        $this->authorize('export_staff');
        $auth_company = $this->getListCompanyForUser();
        $breadcrumb = [
            [
                'title' => 'Danh mục',
                'route' => ''
            ],
            [
                'title' => 'Nhân viên',
                'route' => 'staff.index'
            ]
        ];

        if (!session()->has('search')) {
            session([
                'search' => [
                    'page' => 1,
                    'key_search' => '',
                    'company_id',
                    'department_id',
                    'position_id',
                    'type_staff',
                    'gender_staff'
                ]
            ]);
        }

        if (isset($request->confirm_search) and $request->confirm_search == "1") {

            session(['search.page' => 1]);

            if (isset($request->key_search))
                session(['search.key_search' => trim($request->key_search)]);
            else
                session(['search.key_search' => ""]);


            if (isset($request->company_id))
                session(['search.company_id' => trim($request->company_id)]);
            else
                session(['search.company_id' => ""]);

            if (isset($request->department_id))
                session(['search.department_id' => trim($request->department_id)]);
            else
                session(['search.department_id' => ""]);

            if (isset($request->position_id))
                session(['search.position_id' => trim($request->position_id)]);
            else
                session(['search.position_id' => ""]);

            if (isset($request->type_staff))
                session(['search.type_staff' => trim($request->type_staff)]);
            else
                session(['search.type_staff' => ""]);

            if (isset($request->gender_staff))
                session(['search.gender_staff' => trim($request->gender_staff)]);
            else
                session(['search.gender_staff' => ""]);

            return redirect('admin/staff');
        }

        //kích vào link chuyển trang
        if (isset($request->page) and (int)$request->page > 0) session(['search.page' => (int)$request->page]);


        //  dd($request->key_search,session()->all());

        $search_option = array();

        if (session('search.company_id') != '') {
            $search_option[] = ['company_id', '=', session('search.company_id')];
        }
        if (session('search.department_id') != '') {
            $search_option[] = ['department_id', '=', session('search.department_id')];
        }
        if (session('search.position_id') != '') {
            $search_option[] = ['position_id', '=', session('search.position_id')];
        }
        if (session('search.type_staff') != '') {
            $search_option[] = ['type', '=', (int)session('search.type_staff')];
        }
        if (session('search.gender_staff') != '') {
            $search_option[] = ['gender', '=', (int)session('search.gender_staff')];
        }

        $key_search = "";
        if (session('search.key_search') != "") {
            $key_search = session('search.key_search');
        }


        $data = $this->staffRepo->getWithFilter(session('search.key_search'), 0, -1, $search_option, $auth_company);


        return Excel::download(new StaffExport(
            $data,
            $key_search,
        ), 'bao-cao-nhan-vien-' . (date("Y-m-d-G-i-s")) . '.xlsx');
    }

    public function delete($id)
    {

        $this->authorize('delete_staff');

        DB::beginTransaction();
        try {


            $staff = $this->staffRepo->getById($id);
            //xoá nhân viên
            $this->staffRepo->delete($id);

            //xoá tài khoản
            DB::update("update [user] set  is_delete = 1, deleted_at =? where staff_id=?", [Carbon::now(), $id]);

            //xoá lịch sử ăn
            DB::update("update tbl_staff_eat set  is_delete = 1, deleted_at =? where staff_id=?", [Carbon::now(), $id]);

            //xoá tổng kết
            DB::update("update tbl_staff_eat_summary set  is_delete = 1 , deleted_at =? where staff_id=?", [Carbon::now(), $id]);

            //xoá đăng ký ăn
//            DB::update("update tbl_register_eat set  is_delete = 1 , deleted_at =? where staff_id=?", [Carbon::now(), $id]);
            DB::update("update tbl_register_eat_detail set  is_delete = 1 , deleted_at =? where staff_id=?", [Carbon::now(), $id]);

            DB::commit();

            //xoá ảnh
            $duongdanfile = str_replace("public/", "", $staff->image_link);
            if (file_exists($duongdanfile)) {
                unlink($duongdanfile);
            }


            return response()->json(['success' => 'Xóa thành công!']);

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['error' => 'Xóa không thành công!']);
        }
    }

    /*
 *  crop ảnh ở tâm ra , kích thước mặc định vuông : 250px x 250px
 */
    function resize_crop_image($max_width = 250, $max_height = 250, $source_file, $dst_dir, $quality = 80)
    {
        $imgsize = getimagesize($source_file);
        $width = $imgsize[0];
        $height = $imgsize[1];
        $mime = $imgsize['mime'];

        switch ($mime) {
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
                break;

            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 7;
                break;

            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 80;
                break;

            default:
                return false;
                break;
        }

        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $src_img = $image_create($source_file);

        $width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;
        //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        if ($width_new > $width) {
            //cut point by height
            $h_point = (($height - $height_new) / 2);
            //copy image
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        } else {
            //cut point by width
            $w_point = (($width - $width_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }


        $image($dst_img, $dst_dir, $quality);

        if ($dst_img) imagedestroy($dst_img);
        if ($src_img) imagedestroy($src_img);
    }

    public function getStaffIdByCard($card_id)
    {
        $staff = $this->staffRepo->findStaffWithCard($card_id);
        if ($staff != null) {
            # code...
            return json_encode([
                'status' => true,
                'staff_id' => $staff->id
            ]);
        } else {
            return json_encode([
                'status' => false
            ]);
        }
    }

    public function deleteCard($id)
    {
        try {
            //code...
            $staff = $this->staffRepo->getById($id);
            if (!empty($staff)) {
                # code...

                $this->staffRepo->update($id, ['card_id' => null]);
                return redirect()->back()->with('alert-success', 'Đã xóa thẻ của nhân viên: ' . $staff->name . '!');
            } else {
                return redirect()->back()->with('alert-error', 'Không tìm thấy dữ liệu!');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('alert-error', 'Có lỗi trong quá trình xóa thẻ!');
        }
    }

    protected function getToken()
    {
        return Str::random(500);
    }
}
