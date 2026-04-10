<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class ShowLogViewerRoutes extends Command
{
    protected $signature = 'routes:logviewer';
    protected $description = 'List all LogViewer routes with their middleware';

    public function handle()
    {
        $routes = collect(Route::getRoutes())
             ->filter(fn ($route) => str_contains($route->getName(), 'log-viewer'))
            ->map(function ($route) {
                return [
                    'method'     => implode('|', $route->methods()),
                    'uri'        => $route->uri(),
                    'name'       => $route->getName(),
                    'action'     => $route->getActionName(),
                    'middleware' => implode(',', $route->gatherMiddleware()),
                ];
            });

        $this->table(['Method', 'URI', 'Name', 'Action', 'Middleware'], $routes);
    }
}
