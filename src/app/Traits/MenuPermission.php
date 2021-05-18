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

        // If user doesn't have permission show, delete, update in button line
        // Then remove action column in the table list
        if (empty(array_intersect(['show', 'delete', 'update'], $menu_permission))) {
            $this->crud->removeAllButtonsFromStack('line');
        }

        // If user has export permission, then show export button in list view
        if (in_array('export', $menu_permission)) {
            $this->crud->enableExportButtons();
        }

        // if user has permission permission & in model fillable has user_id, then add query where userid = current userid
        if (in_array('personal', $menu_permission) && in_array('user_id', $this->crud->model->getFillable())) {
            $this->crud->addClause('whereUserId', starmoozie_user()->id);
        }

        // allowed default access in current route
        $this->crud->allowAccess($menu_permission);
    }

    private function getMenuPermission()
    {
        $this->crud->denyAccess($this->default_access); // Deny access all permission in current route

        $route      = explode('/', $this->crud->getRoute()); // Get current url as array
        $permission = starmoozie_user()->role->menuPermission; // Get user menu_permission
        $permission = $permission->load(['menu:id,url', 'permission:id,name']); // Then load menu, permission from related entry
        $permission = $permission->map(function($q) { // Mapping menu url and permission name

            $data[strtolower($q->menu->url)] = strtolower($q->permission->name);

            return $data;
        });

        // Get user permission in current route
        return array_column($permission->toArray(), end($route));
    }
}
