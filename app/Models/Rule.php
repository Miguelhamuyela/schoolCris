<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rule extends Model
{
    use HasFactory;
    public $table = "regras";
    protected $guarded = ['id'];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'docentes_regras');
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'contratos');
    }

    public function schoolyears()
    {
        return $this->belongsToMany(Schoolyear::class, 'contratos');
    }
}
