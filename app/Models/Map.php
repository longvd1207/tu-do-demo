<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Map extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'maps';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'ticket_type_id',
        'type_id',
        'type',
        'is_delete'
    ];

    public function typeTicket(): BelongsTo
    {
        return $this->belongsTo(TicketType::class, 'type_ticket_id', 'id');
    }
    public function getAreas()
    {
        return $this->hasOne(Area::class,  'id','type_id');
    }
    public function getServices()
    {
        return $this->hasOne(Service::class,  'id', 'type_id');
    }
    public function getFunSpots()
    {
        return $this->hasOne(FunSpot::class,  'id', 'type_id');
    }


}
