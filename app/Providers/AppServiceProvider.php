<?php

namespace App\Providers;

use App\Models\Pillar;
use App\Models\User;
use App\Support\Roles;
use App\Support\Rupiah;
use App\Support\VisitorAddress;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->limitDonations();

        Paginator::useTailwind();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Blade::directive('rupiah', function (string $expression): string {
            return '<?php echo e(\\'.Rupiah::class."::format($expression)); ?>";
        });

        View::composer('layouts.public', function ($view): void {
            $view->with(
                'navPillars',
                Pillar::query()->orderBy('sort_order')->orderBy('name')->get(),
            );
        });

        Gate::before(function (User $user, string $ability): ?bool {
            return $user->hasRole(Roles::SUPER_ADMIN) ? true : null;
        });
    }

    private function limitDonations(): void
    {
        $limit = function (int $max): \Closure {
            return function (Request $request) use ($max): Limit {
                return Limit::perMinute($max)->by(VisitorAddress::forRateLimit($request));
            };
        };

        RateLimiter::for('donation-store', $limit(20));
        RateLimiter::for('donation-lookup', $limit(30));
        RateLimiter::for('donation-show', $limit(60));
        RateLimiter::for('donation-proof', $limit(10));
    }
}
