<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';

    protected $table = 'company';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'code',
        'name',
        'address',
        'phone',
        'email',
        'type',
        'is_delete'
    ];
}
