<?php

namespace Starmoozie\MenuPermission\app\Http\Controllers;

use Starmoozie\MenuPermission\app\Http\Requests\MenuRequest;
use Starmoozie\CRUD\app\Http\Controllers\CrudController;
use Starmoozie\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MenuCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Starmoozie\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MenuCrudController extends CrudController
{
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ReorderOperation { reorder as traitReorder; }

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
        CRUD::setModel(\Starmoozie\MenuPermission\app\Models\Menu::class);
        CRUD::setRoute(config('starmoozie.base.route_prefix') . '/menu');
        CRUD::setEntityNameStrings('menu', 'menu');
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
        CRUD::addColumns($this->setupLists());
        $this->numbering();
        $this->created();
        $this->setupFilters();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->permissionCheck();
        CRUD::setValidation(MenuRequest::class);
        CRUD::addFields(array_merge(
            $this->setupLists(),
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
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();
        $this->setupShows();
    }

    /**
     * Define what happens when the Reorder operation is loaded.
     * 
     * @return void
     */
    protected function setupReorderOperation()
    {
        // define which model attribute will be shown on draggable elements 
        $this->crud->set('reorder.label', 'name');
        // for infinite levels, set it to 0
        $this->crud->set('reorder.max_level', 2);
    }

    private function setupLists()
    {
        $operation = $this->crud->getCurrentOperation();
        $wrapper   = $operation == 'show' || $operation == 'list'  ? [] : ['class' => 'form-group col-md-4'];

        return [
            [
                'name'    => 'name',
                'label'   => 'Name',
                'wrapper' => $wrapper,
                'tab'     => 'Details'
            ],
            [
                'name'    => 'url',
                'label'   => 'URL',
                'wrapper' => $wrapper,
                'tab'     => 'Details',
            ],
            [
                'name'    => 'parent_id',
                'label'   => 'Parent',
                'wrapper' => $wrapper,
                'tab'     => 'Details',
                'options' => (fn($q) => $q->isParent()->get())
            ],
        ];
    }

    private function setupFields()
    {
        return [
            [
                'label'     => 'Permission',
                'type'      => 'checklist',
                'name'      => 'permission',
                'entity'    => 'permission',
                'attribute' => 'name',
                'model'     => 'Starmoozie\MenuPermission\app\Models\Permission',
                'pivot'     => true,
                'tab'       => 'Permissions'
            ]
        ];
    }

    private function setupShows()
    {
        $this->crud->addColumn([
            'name'  => 'permission',
            'type'  => 'relationship',
            'label' => 'Permission',
        ])->afterColumn('parent_id');
    }

    private function setupFilters()
    {
        $this->crud->addFilter([
            'name'  => 'status',
            'type'  => 'select2',
            'label' => 'Type'
        ], function () {
            return [
                0 => "Parent",
                1 => 'Child',
            ];
        }, function($value) {
            $value = $value == 1 ? 'isChildren' : 'isParent';
            $this->crud->addClause($value);
        });
    }
}
