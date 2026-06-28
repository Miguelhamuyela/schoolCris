<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory;
    public $table = "niveis";
    protected $guarded = ['id'];
    use softDeletes;
    protected $dates = ['deleted_at'];

    /** Relação das matrículas */
    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'matriculas');
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'matriculas');
    }

    /** Relação do Exame */

    public function course()
    {
        return $this->belongsToMany(Course::class, 'exames');
    }
}
