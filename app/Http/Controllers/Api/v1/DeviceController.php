<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Device\CheckStatusDeviceRequest;
use App\Http\Requests\Api\Device\UpdateStatusDeviceRequest;
use App\Models\Device;
use App\Repositories\Device\DeviceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeviceController extends Controller
{
    protected $deviceRepo;
    public function __construct(DeviceRepositoryInterface $deviceRepo)
    {
        $this->deviceRepo = $deviceRepo;
    }

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

    public function checkStatus(CheckStatusDeviceRequest $request)
    {
        $user = auth('api')->user();
        // Chỉ đăng nập dc với user có type = 3
        if ($user->type != 3) {
            return response()->forbidden('Tài khoản không phù hợp');
        }

        $device_id = $request->header('device-id');
        $device_name = $request->header('device-name');

        $device = $this->deviceRepo->getByDeviceId($device_id);
        if ($device) {
            // Thiết bị đã có trong danh sách
            $device->online_date_time = Carbon::now();
            Log::info(Carbon::now());
            $device->save();
            return response()->json([
                'update_status' => $device->download_status == 1 ? true : false
            ], 200);
        } else {
            // Thiết bị chưa có trong danh sách
            $key_contents = Storage::get('kztech.txt');
            $interal_key =  $this->hash_kztek(config('kztek_config.interal_key'));

            $active_code = substr($key_contents, 0, 21) . substr($key_contents, 21 + strlen($interal_key));

            //giải mã
            $active_code = json_decode($this->decrypt_kztek($active_code));

            $device_limit = $active_code->addition_info->clients_count;

            $company_id = auth('api')->user()->company_id;
            $company_device_ids = Device::where('deleted_at', null)
                ->pluck('device_id')
                ->toArray();

            if (count($company_device_ids) >= $device_limit) {
                // Vượt quá số thiết bị cho phép, return message lỗi vượt quá thiết bị cho phép
                return response()->json([
                    'message' => 'Vượt quá số thiết bị đã đăng ký!',
                ], 422);
            } else {
                // Thêm thiết bị mới vào danh sách
                $dataInsert = [
                    'name' => $device_name,
                    'device_id' => $device_id,
                    'company_id' => $company_id,
                    'play_status' => 1,
                    'download_status' => 0,
                    'online_date_time' => now(),
                ];
                $this->deviceRepo->create($dataInsert);
                return response()->json([
                    'update_status' => false
                ], 200);
            }
        }
    }
    public function updateStatus(UpdateStatusDeviceRequest $request)
    {
        $user = auth('api')->user();
        // Chỉ đăng nập dc với user có type = 3
        if ($user->type != 3) {
            return response()->forbidden('Tài khoản không phù hợp');
        }

        $device_id = $request->header('device-id');
        $download_status = $request->download_status;
        $result = $this->deviceRepo->updateDownloadStatus($device_id, $download_status);
        if ($result) {
            // Ghi Log
            Log::info('Thiết bị có device_id = ' . $device_id . ' thay đổi trạng thái update thành ' . $download_status . ' tại ' . now());
            return response()->json([
                'message' => 'Update thành công'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Update thất bại'
            ], 422);
        }
    }
}
