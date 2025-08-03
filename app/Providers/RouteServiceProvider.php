<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api/user/')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::group([
                'namespace' => $this->namespace,
                'prefix' => 'api/provider',
            ], function ($router) {
                require base_path('routes/providerapi.php');
            });

            Route::group([
                'middleware' => ['web', 'admin', 'auth:admin'],
                'prefix' => 'admin',
                'as' => 'admin.',
                'namespace' => $this->namespace,
            ], function ($router) {
                require base_path('routes/admin.php');
            });

            Route::group([
                'middleware' => ['web', 'dispatcher', 'auth:dispatcher'],
                'prefix' => 'dispatcher',
                'as' => 'dispatcher.',
                'namespace' => $this->namespace,
            ], function ($router) {
                require base_path('routes/dispatcher.php');
            });

        Route::group([
            'middleware' => ['web', 'corporate', 'auth:corporate'],
            'prefix' => 'corporate',
            'as' => 'corporate.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/corporate.php');
        });

        Route::group([
            'middleware' => ['web', 'customercare', 'auth:customercare'],
            'prefix' => 'customercare',
            'as' => 'customercare.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/customercare.php');
        });

        Route::group([
            'middleware' => ['web', 'partner', 'auth:partner'],
            'prefix' => 'partner',
            'as' => 'partner.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/partner.php');
        });

        Route::group([
            'middleware' => ['web', 'hotel', 'auth:hotel'],
            'prefix' => 'hotel',
            'as' => 'hotel.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/hotel.php');
        });

        Route::group([
            'middleware' => ['web', 'account', 'auth:account'],
            'prefix' => 'account',
            'as' => 'account.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/account.php');
        });


        Route::group([
            'middleware' => ['web', 'provider', 'auth:provider'],
            'prefix' => 'provider',
            'as' => 'provider.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/provider.php');
        });

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));


        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */

    // public function map()
    // {
    //     $this->mapApiRoutes();

    //     $this->mapWebRoutes();

    //     $this->mapHotelRoutes();

    //     $this->mapPartnerRoutes();

    //     $this->mapDispatcherRoutes();

    //     $this->mapProviderRoutes();

    //     $this->mapAccountRoutes();

    //     $this->mapCorporateRoutes();

    //     $this->mapAdminRoutes();

    //     $this->mapProviderApiRoutes();

    //     //
    // }

    // protected function mapAdminRoutes()
    // {
    //     Route::group([
    //         'middleware' => ['web', 'admin', 'auth:admin'],
    //         'prefix' => 'admin',
    //         'as' => 'admin.',
    //         'namespace' => $this->namespace,
    //     ], function ($router) {
    //         require base_path('routes/admin.php');
    //     });
    // }

    // protected function mapCorporateRoutes()
    // {
    //     Route::group([
    //         'middleware' => ['web', 'corporate', 'auth:corporate'],
    //         'prefix' => 'corporate',
    //         'as' => 'corporate.',
    //         'namespace' => $this->namespace,
    //     ], function ($router) {
    //         require base_path('routes/corporate.php');
    //     });
    // }

    // protected function mapAccountRoutes()
    // {
    //     Route::group([
    //         'middleware' => ['web', 'account', 'auth:account'],
    //         'prefix' => 'account',
    //         'as' => 'account.',
    //         'namespace' => $this->namespace,
    //     ], function ($router) {
    //         require base_path('routes/account.php');
    //     });
    // }

    // protected function mapProviderRoutes()
    // {
    //     Route::group([
    //         'middleware' => ['web', 'provider', 'auth:provider'],
    //         'prefix' => 'provider',
    //         'as' => 'provider.',
    //         'namespace' => $this->namespace,
    //     ], function ($router) {
    //         require base_path('routes/provider.php');
    //     });
    // }

    // protected function mapDispatcherRoutes()
    // {
    //     Route::group([
    //         'middleware' => ['web', 'dispatcher', 'auth:dispatcher'],
    //         'prefix' => 'dispatcher',
    //         'as' => 'dispatcher.',
    //         'namespace' => $this->namespace,
    //     ], function ($router) {
    //         require base_path('routes/dispatcher.php');
    //     });
    // }

    // protected function mapPartnerRoutes()
    // {
    //     Route::group([
    //         'middleware' => ['web', 'partner', 'auth:partner'],
    //         'prefix' => 'partner',
    //         'as' => 'partner.',
    //         'namespace' => $this->namespace,
    //     ], function ($router) {
    //         require base_path('routes/partner.php');
    //     });
    // }

    // protected function mapHotelRoutes()
    // {
    //     Route::group([
    //         'middleware' => ['web', 'hotel', 'auth:hotel'],
    //         'prefix' => 'hotel',
    //         'as' => 'hotel.',
    //         'namespace' => $this->namespace,
    //     ], function ($router) {
    //         require base_path('routes/hotel.php');
    //     });
    // }

    // protected function mapWebRoutes()
    // {
    //     Route::middleware('web')
    //          ->namespace($this->namespace)
    //          ->group(base_path('routes/web.php'));
    // }

    // protected function mapApiRoutes()
    // {
    //     Route::group([
    //         'middleware' => 'api',
    //         'namespace' => $this->namespace,
    //         'prefix' => 'api/user',
    //     ], function ($router) {
    //         require base_path('routes/api.php');
    //     });
    // }

    // protected function mapProviderApiRoutes()
    // {
    //     Route::group([
    //         'namespace' => $this->namespace,
    //         'prefix' => 'api/provider',
    //     ], function ($router) {
    //         require base_path('routes/providerapi.php');
    //     });
    // }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api/user', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
