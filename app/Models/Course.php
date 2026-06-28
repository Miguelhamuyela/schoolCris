<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    use softDeletes;
    protected $dates = ['deleted_at'];
    public $table = "cursos";

    /** relação da Maticula */
    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'matriculas');
    }

    /** relação do Exame */

    public function classe()
    {
        return $this->belongsToMany(Classe::class, 'exames');
    }
    public function grade()
    {
        return $this->belongsToMany(Grade::class, 'exames');
    }

    
}
