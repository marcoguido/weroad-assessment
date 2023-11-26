<?php

namespace App\Providers;

use App\Contracts\Models\Identifiers\IdentifierContract;
use App\Models\Identifiers\TourId;
use App\Models\Identifiers\TravelId;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var class-string[]
     */
    private static array $routeIdentifierClasses = [
        TourId::class,
        TravelId::class,
    ];

    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->setupRateLimiter();
        $this->setupRouteBindings();
        $this->registerRoutes();
    }

    private function setupRateLimiter(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    private function setupRouteBindings(): void
    {
        /** @var IdentifierContract $identifierClass */
        foreach (static::$routeIdentifierClasses as $identifierClass) {
            Route::bind(
                key: Str::of($identifierClass)
                    ->classBasename()
                    ->camel()
                    ->toString(),
                binder: fn (string $value) => $identifierClass::make($value),
            );
        }
    }

    private function registerRoutes(): void
    {
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
