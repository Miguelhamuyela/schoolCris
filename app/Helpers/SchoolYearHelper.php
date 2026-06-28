<?php

namespace App\Helpers;

use App\Models\Schoolyear;

class SchoolYearHelper
{
    const START_MONTH = 9;

    public static function getCurrentSchoolYearName(): string
    {
        $now = now();
        $year = (int) $now->format('Y');
        $month = (int) $now->format('m');

        if ($month >= self::START_MONTH) {
            return $year . '/' . ($year + 1);
        }

        return ($year - 1) . '/' . $year;
    }

    public static function getCurrentSchoolYearId(): int
    {
        $name = self::getCurrentSchoolYearName();

        $schoolyear = Schoolyear::firstOrCreate(
            ['name' => $name],
            [
                'details' => 'Ano lectivo ' . $name,
                'is_current' => true,
            ]
        );

        if (!$schoolyear->is_current) {
            Schoolyear::where('is_current', true)->update(['is_current' => false]);
            $schoolyear->update(['is_current' => true]);
        }

        return $schoolyear->id;
    }

    public static function getSchoolYearByName(string $name): Schoolyear
    {
        return Schoolyear::firstOrCreate(
            ['name' => $name],
            ['details' => 'Ano lectivo ' . $name]
        );
    }

    public static function getNextSchoolYearName(): string
    {
        $current = self::getCurrentSchoolYearName();
        $parts = explode('/', $current);
        $nextStart = (int) $parts[1];
        return $nextStart . '/' . ($nextStart + 1);
    }

    public static function getAllSchoolYears()
    {
        return Schoolyear::orderBy('id', 'Desc')->get();
    }
}
