<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schoolyear extends Model
{
    use HasFactory;
    public $table = "anos_lectivos";
    protected $guarded = ['id'];
    use softDeletes;
    protected $dates = ['deleted_at'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'matriculas');
    }

    public function rules()
    {
        return $this->belongsToMany(Rule::class, 'contratos');
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }
}
