<?php

namespace App\Providers;

//use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        Route::singularResourceParameters(false);
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        /*Blade::directive('gopal', function($expression) {
            return "<?php if($expression):?>";
        });
        Blade::directive('endgopal', function() {
            return "<?php endif; ?>";
        });*/
    }
}
