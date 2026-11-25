<?php

namespace App\Modules\Appointment\Providers;

use App\Modules\Appointment\Models\Appointment;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppointmentRouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $modulePath = dirname(__DIR__);
        $moduleRoutes = $modulePath . '/Routes';

        $this->loadRoutesFrom($moduleRoutes . '/admin.php');
        $this->loadRoutesFrom($moduleRoutes . '/web.php');
        $this->loadViewsFrom($modulePath . '/Resources/views', 'appointment');
        $this->loadMigrationsFrom($modulePath . '/Database/Migrations');
        Relation::morphMap([
            'App\\Models\\Appointment' => Appointment::class,
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Modules\Appointment\Console\Commands\DeleteUnwantedAppointmentCommand::class,
            ]);
        }
    }
}
