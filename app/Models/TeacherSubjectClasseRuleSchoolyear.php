<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherSubjectClasseRuleSchoolyear extends Model
{
    use HasFactory;
    public $table = "contratos";
    protected $guarded = ['id'];
    protected $fillable = ['fk_teachers_id', 'fk_classes_id', 'fk_rules_id', 'fk_schoolyears_id', 'season'];
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public function teachers()
    {
        return $this->belongsTo(Teacher::class, 'fk_teachers_id');
    }

    public function classes()
    {
        return $this->belongsTo(Classe::class, 'fk_classes_id');
    }

    public function rules()
    {
        return $this->belongsTo(Rule::class, 'fk_rules_id');
    }

    public function schoolyears()
    {
        return $this->belongsTo(Schoolyear::class, 'fk_schoolyears_id');
    }
}
