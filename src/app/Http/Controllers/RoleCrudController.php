<?php

namespace Starmoozie\MenuPermission\app\Http\Controllers;

use Starmoozie\MenuPermission\app\Http\Requests\RoleRequest;
use Starmoozie\CRUD\app\Http\Controllers\CrudController;
use Starmoozie\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RoleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Starmoozie\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RoleCrudController extends CrudController
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
        CRUD::setModel(\Starmoozie\MenuPermission\app\Models\Role::class);
        CRUD::setRoute(config('starmoozie.base.route_prefix') . '/role');
        CRUD::setEntityNameStrings('role', 'role');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumns($this->setupList());
        $this->numbering();
        $this->created();
        $this->permissionCheck();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->permissionCheck();
        CRUD::setValidation(RoleRequest::class);
        CRUD::addFields(array_merge(
            $this->setupList(),
            $this->setupFields()
        ));
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
        // $this->crud->set('show.setFromDb', false);

        /**
         * Columns can be defined using the fluent syntax or array/s syntax:
         * User CRUD::addColumn/s or $this->crud->addColumn/s
         * - CRUD::column('created_at')->type('datetime')->label('Created');
         * - CRUD::addColumn(['name' => 'created_at', 'type' => 'datetime']); 
         * - CRUD::addColumns([['name' => 'created_at', 'type' => 'datetime']]);
         * 
         * To remove column
         * - $this->crud->removeColumn('price');
         */
    }

    private function setupList()
    {
        return [
            [
                'name'  => 'name',
                'label' => 'Name',
            ],
        ];
    }

    private function setupFields()
    {
        return [
            [
                'name'      => 'menuPermission',
                'label'     => 'Menu',
                'type'      => 'menu_permission',
                'model'     => 'Starmoozie\MenuPermission\app\Models\MenuPermission',
                'entity'    => 'menuPermission',
                'attribute' => 'id',
                'pivot'     => true,
                'view_namespace' => 'dynamic_view::fields'
            ]
        ];
    }
}
