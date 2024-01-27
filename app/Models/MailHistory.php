<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mail_history';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'customer_id',
        'order_id',
        'order_code',
        'status',
        'is_delete',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function order()
    {
        return $this->hasOne(Order::class,  'id', 'order_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class,  'id', 'customer_id');
    }
}
