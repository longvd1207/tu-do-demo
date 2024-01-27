<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Relations\HasOne;
//use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Notifications\Notifiable;
//use Laravel\Passport\HasApiTokens;
//use Illuminate\Database\Eloquent\SoftDeletes;
//use Spatie\Permission\Traits\HasPermissions;
//use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class WarningEvent extends Model
{
    use HasFactory;
    protected $table = 'tbl_warning_event';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'event_code',
        'ticket_id',
        'user_id',
        'type',
        'type_id',
        'type_name',
        'description',
        'customer_id',
        'is_delete',
        'deleted_at',
        'updated_at',
        'created_at'
    ];

    protected $casts = [
        'is_delete' => 'integer'
    ];

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

//    public function staff()
//    {
//        return $this->belongsTo(Staff::class, 'staff_id', 'id')->where('is_delete', '=', 0);
//    }
//
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id')->where('is_delete', '=', 0);
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id')->where('is_delete', '=', 0);
    }

    //lấy tên khu vực chứa dịch vụ và điểm vui chơi
    //đầu vào : $this->type,$this->type_id
    public function getNameArea(){

        $result ="";

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
