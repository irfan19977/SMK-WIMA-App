<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register the highlight_text helper function
        if (!function_exists('highlight_text')) {
            function highlight_text($text, $query) {
                if (empty($query)) {
                    return $text;
                }
                
                $words = preg_split('/\s+/', $query);
                $highlighted = $text;
                
                foreach ($words as $word) {
                    $word = preg_quote($word, '/');
                    $highlighted = preg_replace(
                        "/($word)/i",
                        '<span class="highlight">$1</span>',
                        $highlighted
                    );
                }
                
                return $highlighted;
            }
        }
        
        // Register the helper function as a Blade directive
        Blade::directive('highlight', function ($expression) {
            return "<?php echo highlight_text($expression); ?>";
        });
    }
}
