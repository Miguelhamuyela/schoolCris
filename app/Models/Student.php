<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    public $table = "alunos";
    protected $guarded = ['id'];
    use softDeletes;
    protected $dates = ['deleted_at'];

    public function schoolyears()
    {
        return $this->belongsToMany(Schoolyear::class, 'matriculas', 'fk_students_id', 'fk_schoolyears_id')->withTimestamps();
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'matriculas');
    }
    
}
