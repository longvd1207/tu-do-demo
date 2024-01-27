<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;
    protected $keyType = 'string';
    protected $table = 'services';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'area_id',
        'name',
        'description',
        'status',
        'company_id',
        'is_delete'
    ];

    public function area() :BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
}
