<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceIP extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'device_ip';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'device_id',
        'ip',
        'status',
        'is_delete'
    ];
}
