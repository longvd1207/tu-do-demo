<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $keyType = 'string';

    protected $table = 'orders';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'code_order',
        'created_by',
        'type',
        'real_amount',
        'note',
        'amount',
        'is_delete',
        'customer_id',
        'code_order',
        'payment_status',
        'company_id'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'order_id', 'id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
    public function paymentStatus(): HasMany
    {
        return $this->hasMany(PaymentStatus::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }


}
