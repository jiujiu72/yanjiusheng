<?php

namespace App\Providers;

use App\Models\RestReminderSetting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('layouts.app', function ($view) {
            $settings = RestReminderSetting::first();
            $view->with('restReminderEnabled', $settings->enabled ?? true);
            $view->with('restReminderFirst', $settings->first_reminder_minutes ?? 45);
            $view->with('restReminderSecond', $settings->second_reminder_minutes ?? 90);
            $view->with('restReminderSnooze', $settings->snooze_minutes ?? 10);
        });

        Blade::directive('submitToken', function () {
            return '<input type="hidden" name="_submit_token" value="<?php echo uniqid(\'st_\', true); ?>">';
        });
    }
}
