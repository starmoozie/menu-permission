<?php

namespace Starmoozie\MenuPermission\app\Http\Controllers;

use Starmoozie\MenuPermission\app\Http\Requests\PermissionRequest;
use Starmoozie\CRUD\app\Http\Controllers\CrudController;
use Starmoozie\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PermissionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Starmoozie\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PermissionCrudController extends CrudController
{
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ShowOperation;

    // Global Columns
    use \Starmoozie\CRUD\app\Http\Controllers\Traits\GlobalColumns;
    use \Starmoozie\MenuPermission\app\Traits\MenuPermission;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\Starmoozie\MenuPermission\app\Models\Permission::class);
        CRUD::setRoute(config('starmoozie.base.route_prefix') . '/permission');
        CRUD::setEntityNameStrings('permission', 'permission');
        CRUD::addClause('orderByName');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @return void
     */
    protected function setupListOperation()
    {
        $this->permissionCheck();
        CRUD::setFromDb(); // columns
        $this->numbering();
        $this->created();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->permissionCheck();
        CRUD::setValidation(PermissionRequest::class);

        CRUD::setFromDb(); // fields
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /**
     * Define what happens when the Show operation is loaded.
     * 
     * @return void
     */
    protected function setupShowOperation()
    {
        $this->permissionCheck();
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();

        CRUD::addColumn([
            'name'         => 'menu',
            'type'         => 'relationship',
            'label'        => 'Menu',
        ])->afterColumn('name');
    }
}
