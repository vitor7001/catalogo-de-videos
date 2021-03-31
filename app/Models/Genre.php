<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Genre extends Model
{
    use SoftDeletes, \App\Models\Traits\Uuid;

    protected $fillable = ['name', 'is_active'];
    protected $dates = ['deleted_at'];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts =[
        'is_active' => 'bool'
    ];
}
