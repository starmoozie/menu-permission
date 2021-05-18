<?php

namespace Starmoozie\MenuPermission;

use Illuminate\Support\ServiceProvider;
use Starmoozie\MenuPermission\app\Http\Middleware\ClearCacheResponse;
use Spatie\ResponseCache\Middlewares\CacheResponse;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;

class MenuPermissionServiceProvider extends ServiceProvider
{
    protected $seeds_path = '/database/seeders';

    public function boot()
    {
        $this->registerProvider();
        $this->registerMiddleware();
        $this->loadReources();

        // Seeders
        if ($this->app->runningInConsole()) {
            if ($this->isConsoleCommandContains([ 'db:seed', '--seed' ], [ '--class', 'help', '-h' ])) {
                $this->addSeedsAfterConsoleCommandFinished();
            }
        }
    }

    public function register()
    {
        $this->app->make('Starmoozie\MenuPermission\app\Http\Controllers\MenuCrudController');
    }

    private function loadReources()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/app.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'dynamic_view');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang/', 'dynamic_trans');
    }

    /**
     * Register custom middleware to kernel.
     *
     *
     * @return Void
     */
    private function registerMiddleware()
    {
        // To $middlewareGroups
        $this->app->router->pushMiddlewareToGroup('web', ClearCacheResponse::class);
        $this->app->router->pushMiddlewareToGroup('web', CacheResponse::class);

        // To $routeMiddleware
        $this->app->router->aliasMiddleware('doNotCacheResponse', \Spatie\ResponseCache\Middlewares\DoNotCacheResponse::class);
    }

    private function registerProvider()
    {
        $this->app->register(app\Providers\ViewServiceProvider::class);
    }

    /**
     * Get a value that indicates whether the current command in console
     * contains a string in the specified $fields.
     *
     * @param string|array $contain_options
     * @param string|array $exclude_options
     *
     * @return bool
     */
    protected function isConsoleCommandContains($contain_options, $exclude_options = null) : bool
    {
        $args = Request::server('argv', null);
        if (is_array($args)) {
            $command = implode(' ', $args);
            if (
                Str::contains($command, $contain_options) &&
                ($exclude_options == null || !Str::contains($command, $exclude_options))
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add seeds from the $seed_path after the current command in console finished.
     */
    protected function addSeedsAfterConsoleCommandFinished()
    {
        Event::listen(CommandFinished::class, function(CommandFinished $event) {
            // Accept command in console only
            if ($event->output instanceof ConsoleOutput) {
                $this->addSeedsFrom(__DIR__ . $this->seeds_path);
            }
        });
    }

    /**
     * Register seeds.
     *
     * @param string  $seeds_path
     * @return void
     */
    protected function addSeedsFrom($seeds_path)
    {
        $file_names = glob( $seeds_path . '/*.php');
        foreach ($file_names as $filename)
        {
            $classes = $this->getClassesFromFile($filename);
            foreach ($classes as $class) {
                // dd($classes);
                echo "\033[1;33mSeeding:\033[0m {$class}\n";
                $startTime = microtime(true);
                Artisan::call('db:seed', [ '--class' => $class, '--force' => '' ]);
                $runTime = round(microtime(true) - $startTime, 2);
                echo "\033[0;32mSeeded:\033[0m {$class} ({$runTime} seconds)\n";
            }
        }
    }

    /**
     * Get full class names declared in the specified file.
     *
     * @param string $filename
     * @return array an array of class names.
     */
    private function getClassesFromFile(string $filename) : array
    {
        // Get namespace of class
        $namespace = "";
        $lines = file($filename);
        $namespaceLines = preg_grep('/^namespace /', $lines);
        if (is_array($namespaceLines)) {
            $namespaceLine = array_shift($namespaceLines);
            $match = array();
            preg_match('/^namespace (.*)$/', $namespaceLine, $match);
            $namespace = str_replace(["\r", ";"], '', array_pop($match));
        }

        // Get array name of all class has in the file.
        $classes  = array();
        $php_code = file_get_contents($filename);
        $tokens   = token_get_all($php_code);
        $count    = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if ($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {
                $class_name = $tokens[$i][1];
                if ($namespace !== "") {
                    $classes[] = $namespace . "\\$class_name";
                } else {
                    $classes[] = $class_name;
                }
            }
        }

        return $classes;
    }
}