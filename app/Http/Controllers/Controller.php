<?php

namespace App\Http\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function getResponse($status, $errors, $result)
    {
        return [
            'status' => $status,
            'errors' => $errors,
            'result' => $result,
        ];
    }

    public function uploadFile($image, $image_file)
    {
        return $image->move($image_file, $image->hashName());
    }

    public function deleteFile($file = null)
    {
        if ($file != null) {
            Storage::delete($file);
        }
    }

    /*
 * xoá session tìm kiếm:  nêu chuyển controller thì xoá session tìm kiếm
 * */
    public function resetSessionSearch($name_session)
    {
        //        $currentRouteName = \Request::route();
        ////        dd($currentRouteName);
        //
        //        //-------------CỦA CŨ -----------------------------
        //        $url = explode("?", url()->previous())[0];
        //        dd($url, $currentRouteName);
        //
        //        if (route($currentRouteName) != $url) {
        //            // dd(12313);
        //            session()->forget('key_search');
        //            session()->forget('company_id');
        //        }
        //-------------CỦA CŨ -----------------------------

        session(['url_action_prevour' => session('url_action')]);
        session(['url_action' => $name_session]);

        if (session('url_action') != session('url_action_prevour')) {
            // dd(session()->all());
            session()->forget('search');
            session()->forget('key_search');
            session()->forget('filter');
            session()->forget('search.page');
            session()->forget('page');
            session()->forget('company_id');
            session()->forget('department_id');
            session()->forget('tu_ngay');
            session()->forget('den_ngay');
            session()->forget('position_id');
        }
    }

    public function check_permission($type_name)
    {
        if (in_array("administrator", $type_name)) {

            if ((int)session('type') ==  config('kztek_config.type_admin')) {
                return true;
            }
        }

        if (in_array("user", $type_name)) {
            if ((int)session('type') == config('kztek_config.type_user')) {
                return true;
            }
        }

        if (in_array("customer", $type_name)) {
            if ((int)session('type') == config('kztek_config.type_customer')) {
                return true;
            }
        }

        //xóa mọi session
        Session::flush();
        return false;
    }

    public function convertCardId($card_id)
    {
        $length = strlen($card_id);
        if ($length != 8 && $length != 10) {
            # code...
            return [
                'error' => false,
                'message' => 'Đổ dài mã nhân viên phải là 8 hoặc 10 ký tự!'
            ];
        }

        if ($length == 10) {
            # code...
            $hexadecimalNumber = dechex($card_id);
        } else {
            $hexadecimalNumber = $card_id;
        }
        return [
            'error' => true,
            'message' => $hexadecimalNumber
        ];
    }

    /*
   *  resize ảnh
   *  đầu vào:
   *          $imagePath : public_path('images/your-image.jpg');
   *          $destinationPath = public_path('images/resized/your-image.jpg');
   */
    public function resizeImage($imagePath, $destinationPath, $with = 300)
    {
        // Đường dẫn tới ảnh gốc
        //  $imagePath = public_path('images/your-image.jpg');

        // Đường dẫn để lưu ảnh sau khi resize
        //  $destinationPath = public_path('images/resized/');

        // Resize ảnh với chiều rộng mới là 300, chiều cao tự động theo tỷ lệ
        Image::make($imagePath)
            ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($destinationPath);
    }

    //lấy tất cả phòng ban của tài khoản này
    public function getListCompanyForUser()
    {
        $user = Auth::user();

        if (!empty($user->list_company_id)) {
            # code...
            if (!empty(json_decode($user->list_company_id))) {
                # code...
                $list_company = json_decode($user->list_company_id);
            } else {
                $list_company = [];
            }
        } else {
            $list_company = [];
        }
        return $list_company;
    }

    public function generateAndSaveQrCode($size, $content)
    {
        return \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)->generate($content);
    }

    public function generateAndSaveQRcodeInfle($content)
    {
        $qrCode = new QrCode($content);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $part = 'images/qrcodes/' . $content . '.png';
        file_put_contents($part, $result->getString());
        return $part;
    }




}
