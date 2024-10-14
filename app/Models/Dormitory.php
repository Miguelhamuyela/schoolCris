<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dormitory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "dormitories";
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];


}
