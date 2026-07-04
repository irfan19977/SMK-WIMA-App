<?php

namespace App\Helpers;

class AcademicYearHelper
{
    /**
     * Get current academic year based on current date
     * Academic year runs from July to June
     * 
     * @param int|null $month
     * @param int|null $year
     * @return string
     */
    public static function getAcademicYear($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;
        
        // If month is July or later, academic year is current/next year
        if ($month >= 7) {
            return $year . '/' . ($year + 1);
        } else {
            // If month is before July, academic year is previous/current year
            return ($year - 1) . '/' . $year;
        }
    }
    
    /**
     * Get semester from month
     * 
     * @param int $month
     * @return string
     */
    public static function getSemesterFromMonth($month)
    {
        return ($month >= 7) ? 'ganjil' : 'genap';
    }
    
    /**
     * Get current semester
     * 
     * @return string
     */
    public static function getCurrentSemester()
    {
        return self::getSemesterFromMonth(now()->month);
    }
    
    /**
     * Get current academic year
     * 
     * @return string
     */
    public static function getCurrentAcademicYear()
    {
        return self::getAcademicYear();
    }
    
    /**
     * Get academic year info
     * 
     * @return array
     */
    public static function getAcademicYearInfo()
    {
        $academicYear = self::getCurrentAcademicYear();
        $semester = self::getCurrentSemester();
        
        return [
            'academic_year' => $academicYear,
            'semester' => $semester,
            'start_year' => explode('/', $academicYear)[0],
            'end_year' => explode('/', $academicYear)[1],
            'is_ganjil' => $semester === 'ganjil',
            'is_genap' => $semester === 'genap'
        ];
    }
    
    /**
     * Check if current period is class promotion period
     * Usually June-July for promotion to next grade
     * 
     * @return bool
     */
    public static function isClassPromotionPeriod()
    {
        $month = now()->month;
        return $month >= 6 && $month <= 7;
    }
    
    /**
     * Get next academic year
     * 
     * @return string
     */
    public static function getNextAcademicYear()
    {
        $current = self::getCurrentAcademicYear();
        $years = explode('/', $current);
        return ($years[0] + 1) . '/' . ($years[1] + 1);
    }
    
    /**
     * Get previous academic year
     * 
     * @return string
     */
    public static function getPreviousAcademicYear()
    {
        $current = self::getCurrentAcademicYear();
        $years = explode('/', $current);
        return ($years[0] - 1) . '/' . ($years[1] - 1);
    }
    
    /**
     * Generate academic years for dropdown
     * 
     * @param int $yearsBack
     * @param int $yearsForward
     * @return array
     */
    public static function generateAcademicYears($yearsBack = 3, $yearsForward = 1)
    {
        $currentAcademicYear = self::getCurrentAcademicYear();
        $currentStartYear = (int)explode('/', $currentAcademicYear)[0];
        $academicYears = [];
        
        // Generate years back
        for ($i = $yearsBack; $i >= 0; $i--) {
            $startYear = $currentStartYear - $i;
            $academicYears[] = $startYear . '/' . ($startYear + 1);
        }
        
        // Generate years forward
        for ($i = 1; $i <= $yearsForward; $i++) {
            $startYear = $currentStartYear + $i;
            $academicYears[] = $startYear . '/' . ($startYear + 1);
        }
        
        return $academicYears;
    }
}
