<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Professor extends Model
{
    use HasFactory;
    public $table = "professores";
    protected $guarded = ['id'];
    use softDeletes;
    protected $dates = ['deleted_at'];

    public function schoolyears()
    {
        return $this->belongsToMany(Schoolyear::class, 'registrations', 'fk_students_id', 'fk_schoolyears_id')->withTimestamps();
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'registrations');
    }

}
