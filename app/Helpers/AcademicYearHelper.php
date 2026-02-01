<?php

namespace App\Helpers;

use Carbon\Carbon;

class AcademicYearHelper
{
    /**
     * Menentukan tahun akademik berdasarkan bulan dan tahun
     * 
     * @param int $month
     * @param int $year
     * @return string
     */
    public static function getAcademicYear($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;
        
        if ($month >= 7) {
            // Juli-Desember: tahun akademik dimulai dari tahun berikutnya
            return ($year + 1) . '/' . ($year + 2);
        } elseif ($month >= 6) {
            // Juni: tahun akademik dimulai dari tahun depannya (skip 1 tahun)
            return ($year + 2) . '/' . ($year + 3);
        } else {
            // Januari-Mei: tahun akademik dimulai dari tahun ini
            return $year . '/' . ($year + 1);
        }
    }
    
    /**
     * Mendapatkan tahun akademik saat ini
     * 
     * @return string
     */
    public static function getCurrentAcademicYear()
    {
        return self::getAcademicYear();
    }
    
    /**
     * Generate daftar tahun akademik untuk dropdown
     * 
     * @param int $yearsBefore Jumlah tahun sebelumnya
     * @param int $yearsAfter Jumlah tahun setelahnya
     * @return array
     */
    public static function generateAcademicYears($yearsBefore = 2, $yearsAfter = 2)
    {
        $currentAcademicYear = self::getCurrentAcademicYear();
        $startYear = (int) explode('/', $currentAcademicYear)[0];
        
        $academicYears = [];
        
        // Tahun-tahun sebelumnya
        for ($i = $yearsBefore; $i >= 1; $i--) {
            $year = $startYear - $i;
            $academicYears[] = $year . '/' . ($year + 1);
        }
        
        // Tahun saat ini
        $academicYears[] = $currentAcademicYear;
        
        // Tahun-tahun ke depan
        for ($i = 1; $i <= $yearsAfter; $i++) {
            $year = $startYear + $i;
            $academicYears[] = $year . '/' . ($year + 1);
        }
        
        return $academicYears;
    }
    
    /**
     * Cek apakah tanggal tertentu masuk dalam tahun akademik tertentu
     * 
     * @param string $date
     * @param string $academicYear
     * @return bool
     */
    public static function isDateInAcademicYear($date, $academicYear)
    {
        $carbon = Carbon::parse($date);
        $dateAcademicYear = self::getAcademicYear($carbon->month, $carbon->year);
        
        return $dateAcademicYear === $academicYear;
    }
    
    /**
     * Mendapatkan rentang tanggal untuk tahun akademik tertentu
     * 
     * @param string $academicYear
     * @return array
     */
    public static function getAcademicYearDateRange($academicYear)
    {
        $years = explode('/', $academicYear);
        $startYear = (int) $years[0];
        $endYear = (int) $years[1];
        
        return [
            'start' => Carbon::create($startYear, 7, 1)->startOfDay(),
            'end' => Carbon::create($endYear, 6, 30)->endOfDay()
        ];
    }
    
    /**
     * Mendapatkan semester berdasarkan bulan dalam tahun akademik
     * 
     * @param int $month
     * @return int
     */
    public static function getSemesterFromMonth($month)
    {
        // Semester 1: Juli - Desember
        // Semester 2: Januari - Mei
        // Juni: Bulan transisi ke tahun ajaran baru (masih semester 2)
        return ($month >= 7) ? 1 : 2;
    }
    
    /**
     * Mendapatkan semester saat ini
     * 
     * @return int
     */
    public static function getCurrentSemester()
    {
        return self::getSemesterFromMonth(now()->month);
    }
    
    /**
     * Format tampilan tahun akademik
     * 
     * @param string $academicYear
     * @return string
     */
    public static function formatAcademicYear($academicYear)
    {
        return "Tahun Akademik " . $academicYear;
    }
    
    /**
     * Mendapatkan tahun akademik dari bulan dan tahun dalam bahasa Indonesia
     * 
     * @param int $month
     * @param int $year
     * @return array
     */
    public static function getAcademicYearInfo($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;
        
        $academicYear = self::getAcademicYear($month, $year);
        $semester = self::getSemesterFromMonth($month);
        
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return [
            'academic_year' => $academicYear,
            'semester' => $semester,
            'semester_text' => $semester == 1 ? 'ganjil' : 'genap',
            'month_name' => $months[$month],
            'year' => $year,
            'formatted' => self::formatAcademicYear($academicYear) . " - Semester $semester"
        ];
    }
    
    /**
     * Cek apakah saat ini adalah waktu kenaikan kelas (semester genap)
     * 
     * @return bool
     */
    public static function isClassPromotionPeriod()
    {
        $currentInfo = self::getAcademicYearInfo();
        return $currentInfo['semester'] == 2; // Semester genap
    }
    
    /**
     * Mendapatkan tingkat kelas berikutnya
     * 
     * @param string $currentGrade
     * @return string|null
     */
    public static function getNextGrade($currentGrade)
    {
        $gradeMapping = [
            '10' => '11',
            '11' => '12',
            '12' => 'lulus'
        ];
        
        return $gradeMapping[$currentGrade] ?? null;
    }
    
    /**
     * Mendapatkan tahun akademik berikutnya untuk kenaikan kelas
     * 
     * @param string $currentAcademicYear
     * @return string
     */
    public static function getNextAcademicYear($currentAcademicYear)
    {
        $years = explode('/', $currentAcademicYear);
        $startYear = (int) $years[0];
        $endYear = (int) $years[1];
        
        return ($startYear + 1) . '/' . ($endYear + 1);
    }
    
    /**
     * Proses kenaikan kelas untuk semester genap
     * 
     * @param string $currentAcademicYear
     * @return array
     */
    public static function processClassPromotion($currentAcademicYear = null)
    {
        if (!self::isClassPromotionPeriod()) {
            return [
                'success' => false,
                'message' => 'Bukan periode kenaikan kelas (semester genap)'
            ];
        }
        
        $currentAcademicYear = $currentAcademicYear ?? self::getCurrentAcademicYear();
        $nextAcademicYear = self::getNextAcademicYear($currentAcademicYear);
        
        return [
            'success' => true,
            'current_academic_year' => $currentAcademicYear,
            'next_academic_year' => $nextAcademicYear,
            'current_semester' => 'genap',
            'next_semester' => 'ganjil'
        ];
    }
}