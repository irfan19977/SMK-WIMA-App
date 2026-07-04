<?php

// Helper functions for SMK-WIMA-App

if (!function_exists('format_rfid_uid')) {
    /**
     * Format RFID UID to consistent format
     */
    function format_rfid_uid($uid)
    {
        return strtoupper(str_replace(':', '', $uid));
    }
}

if (!function_exists('generate_attendance_code')) {
    /**
     * Generate random attendance code
     */
    function generate_attendance_code($length = 6)
    {
        return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length));
    }
}

if (!function_exists('is_school_hour')) {
    /**
     * Check if current time is within school hours
     */
    function is_school_hour()
    {
        $hour = now()->hour;
        return $hour >= 7 && $hour <= 16;
    }
}
