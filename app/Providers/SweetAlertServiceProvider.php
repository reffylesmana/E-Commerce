<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Http\Controllers\SweetAlertController;

class SweetAlertServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sweet-alert', function () {
            return new SweetAlertController();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Register a directive for SweetAlert in Blade templates
        Blade::directive('sweetalert', function ($expression) {
            return "<?php echo app('sweet-alert')->{$expression}; ?>";
        });
    }
}
