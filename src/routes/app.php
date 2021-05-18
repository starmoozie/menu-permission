<?php

Route::group([
    'prefix'     => config('starmoozie.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('starmoozie.base.web_middleware', 'web'),
        (array) config('starmoozie.base.middleware_key', 'admin')
    ),
    'namespace'  => 'Starmoozie\MenuPermission\app\Http\Controllers',
], function () {
    Route::crud('permission', 'PermissionCrudController');
    Route::crud('menu', 'MenuCrudController');
    Route::crud('role', 'RoleCrudController');
    Route::crud('user', 'UserCrudController');
});