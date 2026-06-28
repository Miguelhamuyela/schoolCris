<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "exames";
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'season',
        'duration',
        'start',
        'end',
        'fk_teachers_id',
        'fk_classes_id',
        'fk_courses_id',
        'fk_grades_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'fk_teachers_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'fk_classes_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'fk_courses_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'fk_grades_id');
    }
}
