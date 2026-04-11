<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Drs\DailyRunSheetItem;
use App\Models\Wdr\WorkforceDailyReport;
use App\Policies\DailyRunSheetItemPolicy;
use App\Policies\WorkforceDailyReportPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        DailyRunSheetItem::class    => DailyRunSheetItemPolicy::class,
        WorkforceDailyReport::class => WorkforceDailyReportPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
