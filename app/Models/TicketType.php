<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketType extends Model
{
    use HasFactory, SoftDeletes;
    protected $keyType = 'string';

    protected $table = 'ticket_types';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'name',
        'type',
        'price_online',
        'price_offline',
        'company_id',
        'status',
        'is_delete'
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function maps()
    {
        return $this->hasMany(Map::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
