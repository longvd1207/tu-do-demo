<?php

namespace App\Http\Controllers\Web;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use ZipArchive;

use Intervention\Image\Facades\Image;

class FileController extends Controller
{
    private $size_file;
    private $extractPath;
    private $staffImageFolder;

    public function __construct()
    {

        //kích thước file tối đa đc upload cho file zip
        $this->size_file = 2097152000; //tương đương 2000MB
        //thư mục để ảnh giải nén tạm: chứa folder con và các ảnh trong folder con
        $this->extractPathZip = public_path('images/staff_client_unzip');
        //thư mục copy ảnh từ thư mục giai nen zip, trước khi copy vào thư mục ảnh nhân viên
        $this->extractPath = public_path('images/staff_client_send');
        //thư mục để ảnh nhân viên
        $this->staffImageFolder = public_path('images/staff');

    }


    public function uploadZip(Request $request)
    {

        $this->authorize('import_image_staff');

        $validator = Validator::make(
            $request->all(),
            [
                'zip_file' => 'required|mimetypes:application/zip|max:' . $this->size_file, // Điều kiện cho file zip
//                'customer_id' => ['required', 'string', 'max:50'],
//                'project_id' => ['required', 'string', 'max:50'],
//                'is_unlimited' => ['required', 'string', 'max:50'],
//                'data_software' => 'required',
                // 'software_quantity'=>['required'],
            ],
            [
                'zip_file.required' => 'file zip phải lựa chọn !',
                'zip_file.mimetypes' => 'file upload chưa phải là file zip',
                'zip_file.max' => 'File upload vượt quá dung lượng tối đa 2000 MB',
                //   'is_unlimited.required' => 'Thời hạn sử dụng không được bỏ trống',
                // 'software_id.required' => 'Phần mềm không được bỏ trống',
                // 'software_quantity.required' => 'Số lượng license không được bỏ trống',
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->errors();

            // Duyệt qua từng lỗi và xử lý
            $array_error[] = [];
            foreach ($errors->all() as $error) {
                //  echo $error . "<br>";
                $array_error[] = ["File zip" => $error];

            }
            return redirect()->route('staff.index')->with('import-file-zip-error', json_encode($array_error));
            exit;
        }

//        $request->validate([
//            'zip_file' => 'required|mimetypes:application/zip|max:2048', // Điều kiện cho file zip
//        ]);

        $array_error =[];
        if ($request->file('zip_file')->isValid()) {

            $zipPath = $request->file('zip_file')->store('temp'); // Lưu file zip tạm thời trong thư mục 'temp'

            // Giải nén file zip
            $zip = new ZipArchive;
            if ($zip->open(storage_path('app/' . $zipPath)) === true) {

                //b1: giải nén file zip vào thư mục public_path('images/staff_client_unzip');------------
                $extractPathZip = $this->extractPathZip; // Thư mục đích để giải nén

                // Tạo thư mục để lưu ảnh giải nén
                if (!File::exists($extractPathZip)) {
                    File::makeDirectory($extractPathZip);
                }

                //xoá hết thư mục temp chứa ảnh:images/staff_client_unzip
                $this->deleteAllImages($extractPathZip);

                //giải nén mới vào thư mục rỗng
                $zip->extractTo($extractPathZip);
                $zip->close();

                // Xóa file zip tạm thời
                Storage::delete($zipPath);
                //b1: giải nén file zip vào thư mục public_path('images/staff_client_unzip');------------

                //B2: Lấy danh sách tên thư mục con trong thư mục giải nén---------------------
                //xoá ảnh trong thư mục :staff_client_send
                $this->deleteAllImages($this->extractPath);

                $subdirectories = File::directories($extractPathZip);
                foreach ($subdirectories as $subdirectory) {
                    // Copy ảnh từ thư mục con sang thư mục đích
                    $destinationPath = $this->extractPath;
                    File::copyDirectory($subdirectory, $destinationPath);
                }

                // Xóa thư mục giải nén images/staff_client_unzip
                File::deleteDirectory($extractPathZip);
                //B2: Lấy danh sách tên thư mục con trong thư mục giải nén---------------------

                //gọi đến function : copy ảnh sang thư mục staff và update vào db
                $array_error = $this->UpdateStaffImageLink();
                //dd($array_error);

                if (count($array_error) > 0)
                    return redirect()->route('staff.index')->with('import-file-zip-error', json_encode($array_error));
                else
                    return redirect()->route('staff.index')->with('alert-success', 'Import Ảnh thành công');
                exit;


                // return "Upload và giải nén thành công!";
            } else {

                //  return "Không thể giải nén file zip!";
                return redirect()->route('staff.index')->with('alert-error', "Không thể giải nén file zip!");
            }
        } else {
            // return "File zip không hợp lệ!";
            return redirect()->route('staff.index')->with('alert-error', "File zip không hợp lệ!");
        }
    }

    // copy ảnh sang thư mục staff và update vào db
    public function UpdateStaffImageLink()
    {

        // Đường dẫn đến thư mục chứa ảnh (cần điều chỉnh đúng đường dẫn)
        // $sourceFolder = public_path('images/staff_client_send');
        $sourceFolder = $this->extractPath;
        //thư mục sẽ copy ảnh đến
        // $destinationFolder = public_path('images/staff'); // Thư mục đích để sao chép ảnh
        $destinationFolder = $this->staffImageFolder; // Thư mục đích để sao chép ảnh

        // Lấy danh sách tên các tệp ảnh trong thư mục $sourceFolder
        $imageFiles = File::files($sourceFolder);

        $array_error = [];

        // Lặp qua danh sách tệp ảnh và in ra tên
        foreach ($imageFiles as $imageFile) {
            $fileInfo = pathinfo($imageFile);
            //tên ảnh
            $fileName = $fileInfo['filename'];
            $staff_code = $fileName;
            //đuôi ảnh
            $fileExtension = strtolower($fileInfo['extension']);
            //full name
            $full_name = $fileName . "." . $fileExtension;


            // Kiểm tra xem đuôi file có phải là ảnh hay không
            $is_check_type =1;
            if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                //  $fileName = $fileInfo['filename'];
                // echo "Tên: " . $fileName . "." . $fileExtension . PHP_EOL;
            } else {
                $array_error[] = [$full_name => "Đuôi file ảnh phải là : jpeg , png , jpg !"];
                $is_check_type =0;
            }

            if ($is_check_type ==1) {
                //kiểm tra có mã nhân viên này ko
                $staff = \App\Models\Staff::where('code', $staff_code)->first();

                if ($staff) {
                    // echo "Tìm thấy nhân viên với mã nhân viên: " . $staff_code;
                    //copy ảnh
                    $sourcePath = $sourceFolder . '/' . $full_name;
                    $destinationPath = $destinationFolder . '/' . $full_name;

                    $this->resizeImage($sourcePath,$destinationPath,$with=300);


                    //nêếu ảnh này hợp lệ và tên ảnh có trong mã nhân viên thì mới copy và update date-------------
                   // File::copy($sourcePath, $destinationPath);

                    // Cập nhật đường dẫn ảnh vào cột image_link của bảng nhân viên
                    $staff->update(['image_link' => "public/" . config('app.folder_image') . "/" . $full_name]);
                    //nêếu ảnh này hợp lệ và tên ảnh có trong mã nhân viên thì mới copy và update date-------------


                } else {

                    // echo "Không tìm thấy nhân viên với mã nhân viên: " . $staff_code;
                    $array_error[] = [$full_name => "Không tìm thấy mã nhân viên : <b>" . $fileName . "</b>"];
                }
            }

        }

        // dd($array_error);
        return $array_error;


    }

    //xoá hết ảnh trong folder ở trong public nhé , vd: public_path('images');
    private function deleteAllImages($imagesFolderPath)
    {
        // $imagesFolderPath = public_path('images');

        // Lấy danh sách tên tệp ảnh trong thư mục
        $imageFiles = File::files($imagesFolderPath);

        // Xoá từng tệp ảnh
        foreach ($imageFiles as $imageFile) {
            File::delete($imageFile);
        }

        // return "Đã xoá hết các ảnh trong thư mục!";
    }



}







