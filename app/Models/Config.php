<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Config extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'configs';

    protected $fillable = [
        'id',
        'max_individual_ticket',
        'max_group_ticket',
        'is_delete'
    ];


}
