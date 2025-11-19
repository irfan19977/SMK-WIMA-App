<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'session_id',
        'url',
        'referer',
        'country',
        'city',
        'device',
        'browser',
        'os',
        'is_unique',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'is_unique' => 'boolean',
    ];

    /**
     * Get total visitors count
     */
    public static function getTotalVisitors()
    {
        return static::count();
    }

    /**
     * Get unique visitors count
     */
    public static function getUniqueVisitors()
    {
        return static::where('is_unique', true)->count();
    }

    /**
     * Get visitors count for current month
     */
    public static function getMonthlyVisitors()
    {
        return static::where('visited_at', '>=', Carbon::now()->startOfMonth())->count();
    }

    /**
     * Get visitors count for today
     */
    public static function getTodayVisitors()
    {
        return static::whereDate('visited_at', Carbon::today())->count();
    }

    /**
     * Get visitors count for current week
     */
    public static function getWeeklyVisitors()
    {
        return static::where('visited_at', '>=', Carbon::now()->subDays(6)->startOfDay())->count();
    }

    /**
     * Get visitors count for yesterday (for comparison)
     */
    public static function getYesterdayVisitors()
    {
        return static::whereDate('visited_at', Carbon::yesterday())->count();
    }

    /**
     * Get visitor statistics
     */
    public static function getVisitorStats()
    {
        return [
            'total' => static::getTotalVisitors(),
            'unique' => static::getUniqueVisitors(),
            'monthly' => static::getMonthlyVisitors(),
            'today' => static::getTodayVisitors(),
            'weekly' => static::getWeeklyVisitors(),
            'yesterday' => static::getYesterdayVisitors(),
        ];
    }

    /**
     * Track visitor data
     */
    public static function track($data = [])
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();
        $sessionId = session()->getId();
        $url = request()->fullUrl();
        $referer = request()->header('referer');

        // Check if this visitor (IP + User Agent) has already been recorded today
        $existingVisitor = static::where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->whereDate('visited_at', Carbon::today())
            ->first();

        // If already recorded today, do not create another record
        if ($existingVisitor) {
            return $existingVisitor;
        }

        $isUnique = true;

        // Get browser and OS info from user agent
        $browser = static::getBrowser($userAgent);
        $os = static::getOS($userAgent);
        $device = static::getDevice($userAgent);

        $visitorData = array_merge([
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'session_id' => $sessionId,
            'url' => $url,
            'referer' => $referer,
            'browser' => $browser,
            'os' => $os,
            'device' => $device,
            'is_unique' => $isUnique,
            'visited_at' => now(),
        ], $data);

        return static::create($visitorData);
    }

    /**
     * Get browser from user agent
     */
    private static function getBrowser($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Edge';
        if (strpos($userAgent, 'Opera') !== false) return 'Opera';
        return 'Unknown';
    }

    /**
     * Get OS from user agent
     */
    private static function getOS($userAgent)
    {
        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Mac') !== false) return 'macOS';
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        if (strpos($userAgent, 'Android') !== false) return 'Android';
        if (strpos($userAgent, 'iOS') !== false) return 'iOS';
        return 'Unknown';
    }

    /**
     * Get device type from user agent
     */
    private static function getDevice($userAgent)
    {
        if (strpos($userAgent, 'Mobile') !== false) return 'Mobile';
        if (strpos($userAgent, 'Tablet') !== false) return 'Tablet';
        return 'Desktop';
    }
}
