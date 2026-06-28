<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseGradeGroup extends Model
{
    use HasFactory;

    public $table = 'grupos_cursos';
    protected $guarded = ['id'];

    /**
     * Returns the next available letter for a new group.
     * After Z, continues with AA, AB, AC...
     */
    public static function nextAvailableLetter(): string
    {
        $last = self::orderBy('id', 'desc')->first();

        if (!$last) {
            return 'A';
        }

        return self::incrementLetter($last->letter);
    }

    /**
     * Increments a letter sequence: A -> B, Z -> AA, AA -> AB, AZ -> BA
     */
    public static function incrementLetter(string $letter): string
    {
        $len = strlen($letter);
        $chars = str_split($letter);

        for ($i = $len - 1; $i >= 0; $i--) {
            if ($chars[$i] < 'Z') {
                $chars[$i] = chr(ord($chars[$i]) + 1);
                return implode('', $chars);
            } else {
                $chars[$i] = 'A';
            }
        }

        // All chars were 'Z', so prepend an 'A'
        return 'A' . implode('', $chars);
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
