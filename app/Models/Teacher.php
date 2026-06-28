<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory;
    public $table = "docentes";
    protected $guarded = ['id'];
    use softDeletes;
    protected $dates = ['deleted_at'];

    public function rules()
    {
        return $this->belongsToMany(Rule::class, 'docentes_regras','fk_teachers_id','id');
        
    }

    public function classe()
    {
        return $this->belongsToMany(Classe::class, 'exames', 'fk_teachers_id','id')->withTimestamps();
    }
    
    // Novas relações para associação múltipla
    public function teacherCourses()
    {
        return $this->hasMany(TeacherCourse::class, 'fk_teachers_id');
    }
    
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'docentes_cursos', 'fk_teachers_id', 'fk_courses_id')->withTimestamps();
    }
    
    public function teacherClasses()
    {
        return $this->hasMany(TeacherClasse::class, 'fk_teachers_id');
    }
    
    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'docentes_turmas', 'fk_teachers_id', 'fk_classes_id')->withTimestamps();
    }
    
    public function teacherGrades()
    {
        return $this->hasMany(TeacherGrade::class, 'fk_teachers_id');
    }
    
    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'docentes_niveis', 'fk_teachers_id', 'fk_grades_id')->withTimestamps();
    }
    
}
