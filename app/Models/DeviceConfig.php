<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class DeviceConfig extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'device';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'type_id',
        'type',
        'status',
        'company_id',
        'is_delete'
    ];

    public function deviceIp()
    {
        return $this->hasMany(DeviceIP::class, 'device_id', 'id');
    }

    public function getArea()
    {
        return $this->hasOne(Area::class,  'id','type_id');
    }
    public function getService()
    {
        return $this->hasOne(Service::class,  'id', 'type_id');
    }
    public function getFunSpot()
    {
        return $this->hasOne(FunSpot::class,  'id', 'type_id');
    }

    //lấy tên khu vực chứa dịch vụ và điểm vui chơi
    public function getNameArea(){

        $result = "";

        if((int)$this->type ==1){
            //dịch vụ
            $area =  DB::select("select name from areas where  id=?",[$this->type_id]);
            if(!empty($area[0]->name))
                $result = $area[0]->name;
        }
        else  if((int)$this->type ==2){
            //dịch vụ
           $area =  DB::select("select name from areas where  id=(select TOP 1 area_id from services where id=?)",[$this->type_id]);
           if(!empty($area[0]->name))
               $result = $area[0]->name;

        } else if((int)$this->type ==3){

            $fun_spots =  DB::select("select name from areas where  id=(select TOP 1 area_id from fun_spots where id=?)",[$this->type_id]);
            if(!empty($fun_spots[0]->name))
                $result = $fun_spots[0]->name;
        }
        return $result;
    }

}
