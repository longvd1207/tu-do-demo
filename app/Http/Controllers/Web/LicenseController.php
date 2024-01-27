<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LicenseController extends Controller
{
    private  function decrypt_kztek($string)
    {
        $method = 'aes-256-cbc';
        $key = "uwar4ZkuazsQlIRzxw4kEwZtUegDUDZw";

        // IV must be exact 16 chars (128 bit)
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        $decrypted = openssl_decrypt(base64_decode($string), $method, $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

    private function hash_kztek($text)
    {
        $text = mb_convert_encoding($text, 'UTF-16LE');
        $encrypted = hash("sha512", $text, true);
        return base64_encode($encrypted);
    }
    public function show(Request $request)
    {
        $this->authorize('show_license');
        $breadcrumb = [
            [
                'title' => 'home',
                'route' => 'home'
            ],
            [
                'title' => 'Licence',
                'route' => 'license.show'
            ],
        ];

        $key_contents = Storage::get('kztech.txt');
        $interal_key =  $this->hash_kztek(config('kztek_config.interal_key'));

        $active_code = substr($key_contents,0,21).substr($key_contents,21+strlen($interal_key));


        //giải mã
        $active_code =json_decode($this->decrypt_kztek($active_code));

//        dd($active_code);
        $server_count = $active_code->addition_info->server_count;
        $clients_count = $active_code->addition_info->clients_count;
        $is_unlimited = $active_code->is_unlimited;
        $start_date = $active_code->start_date;
        $end_date = $active_code->end_date;

        return view(
            'admin.license.show',
            [
                'server_count' => $server_count,
                'clients_count' => $clients_count,
                'is_unlimited' => $is_unlimited,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'breadcrumb' => $breadcrumb,
                'action' => '#'
            ]
        );
    }

    public function destroy() {
//        dd(123);
        $result = Storage::delete('kztech.txt');
        if($result) {
            return redirect(route('home'));
        }
    }
}
