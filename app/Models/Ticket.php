<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tickets';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'ticket_type_name',
        'ticket_type_id',
        'code',
        'order_id',
        'qr_code',
        'use_date',
        'price',
        'status',
        'company_id',
        'is_delete'
    ];

    public function ticketType() :BelongsTo
    {
        return $this->belongsTo(TicketType::class , 'ticket_type_id', 'id');
    }

    public function maps() :HasMany
    {
        return $this->hasMany(Map::class,'ticket_type_id', 'ticket_type_id');
    }

    public function order() :BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
