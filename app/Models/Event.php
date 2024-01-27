<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    protected $keyType = 'string';
    protected $table = 'events';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'ticket_type_id',
        'ticket_id',
        'order_id',
        'time_id',
        'note',
        'is_delete'
    ];

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class, 'ticket_type_id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    //lấy tên khu vực chứa dịch vụ và điểm vui chơi
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
