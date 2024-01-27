<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentStatus extends Model
{
    use HasFactory, SoftDeletes;
    protected $keyType = 'string';

    protected $table = 'payment_status';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'status',
        'note',
        'order_id',
        'is_delete'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}

