<?php

namespace Starmoozie\MenuPermission\app\Http\View\Composers;

use Illuminate\View\View;

use Starmoozie\MenuPermission\app\Models\Menu;

class MenuComposer
{
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('menu_items', Menu::getTree());
    }
}