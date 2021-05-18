<?php

namespace Starmoozie\MenuPermission\app\Traits;

/**
 * 
 */
trait MenuPermission
{
    private $default_access = [
        'list', 
        'create', 
        'delete', 
        'update', 
        'show'
    ];

    public function permissionCheck()
    {
        $menu_permission = $this->getMenuPermission();

        if (empty(array_intersect(['show', 'delete', 'update'], $menu_permission))) {
            $this->crud->removeAllButtonsFromStack('line');
        }

        if (in_array('export', $menu_permission)) {
            $this->crud->enableExportButtons();
        }

        $this->crud->allowAccess($menu_permission);
    }

    private function getMenuPermission()
    {
        $this->crud->denyAccess($this->default_access);

        $route      = explode('/', $this->crud->getRoute());
        $permission = starmoozie_user()->role->menuPermission;
        $permission = $permission->load(['menu:id,url', 'permission:id,name']);
        $permission = $permission->map(function($q) {

            $data[strtolower($q->menu->url)] = strtolower($q->permission->name);

            return $data;
        });

        return array_column($permission->toArray(), end($route));
    }
}
