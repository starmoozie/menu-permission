<?php

namespace Starmoozie\MenuPermission\app\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use Starmoozie\MenuPermission\app\Http\View\Composers\MenuComposer;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        View::composer(
            starmoozie_view('inc.sidebar_content'),
            MenuComposer::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
