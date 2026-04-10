<?php

namespace App\Providers;

use App\Models\GeneralSettings\Setting;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Workspace;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        //Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        //  if (Schema::hasTable('settings')) {
        //      $settings = Setting::all()->pluck('value', 'key')->toArray();
        //      config(['settings' => $settings]);  // Load the settings dynamically into Laravel's config
        //  }
        
        if (app()->runningInConsole()) {
            return;
        }

        try {
            if (Schema::hasTable('settings')) {

                // Cache for 24 hours (adjust as needed)
                $settings = Cache::remember('app_settings', now()->addHours(24), function () {
                    return Setting::pluck('value', 'key')->toArray();
                });

                config(['settings' => $settings]);
            }
        } catch (\Throwable $e) {
            // Avoid crashing if DB not ready
            // optional: Log::warning('Settings not loaded: '.$e->getMessage());
        }

        Event::listen(SocialiteWasCalled::class, function (SocialiteWasCalled $event) {
            // $event->extendSocialite('google', \SocialiteProviders\Google\Provider::class);
            $event->extendSocialite('microsoft', \SocialiteProviders\Microsoft\Provider::class);
        });

        if ($this->app->environment('azure')) {
            URL::forceScheme('https');
        }

        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols();
        });


        Carbon::setWeekendDays([
            Carbon::FRIDAY,
            Carbon::SATURDAY,
        ]);

        // try {
        //     DB::connection()->getPdo();
        //     // The table exists in the database
        //     $statuses = Status::all();
        //     // $user_workspace = auth()->user()->workspaces;
        //     $workspaces = Workspace::all();
        //     // $user_events = auth()->user()->events;

        //     // dd($workspaces);

        //     $data = [
        //         'statuses' => $statuses,
        //         // 'user_events' => $user_events,
        //         'workspaces' => $workspaces,
        //     ];

        //     view()->share($data);
        // } catch (\Exception $e) {
        // }
    }
}
