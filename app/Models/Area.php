<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'areas';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'name',
        'description',
        'status',
        'is_delete',
        'company_id',
        'deleted_at'
    ];
    public function funSpots(): HasMany
    {
        return $this->hasMany(FunSpot::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }


}
