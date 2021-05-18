<?php

namespace Starmoozie\MenuPermission\app\Http\Controllers;

use Starmoozie\MenuPermission\app\Http\Requests\UserRequest;
use Starmoozie\CRUD\app\Http\Controllers\CrudController;
use Starmoozie\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Starmoozie\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
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
        CRUD::setModel(\Starmoozie\MenuPermission\app\Models\User::class);
        CRUD::setRoute(config('starmoozie.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'user');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumns($this->setupLists());
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
        CRUD::setValidation(UserRequest::class);
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

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        CRUD::setRequest(CRUD::validateRequest());
        CRUD::setRequest($this->handlePasswordInput(CRUD::getRequest()));
        CRUD::unsetValidation();

        return $this->traitStore();
    }

    /**
     * Update the specified resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        CRUD::setRequest(CRUD::validateRequest());
        CRUD::setRequest($this->handlePasswordInput(CRUD::getRequest()));
        CRUD::unsetValidation();

        return $this->traitUpdate();
    }

    /**
     * Handle password input fields.
     */
    protected function handlePasswordInput($request)
    {
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');
        $request->request->remove('roles_show');
        $request->request->remove('permissions_show');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', \Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        return $request;
    }

    private function setupLists()
    {
        $operation = $this->crud->getCurrentOperation();
        $wrapper   = $operation == 'show' || $operation == 'list'  ? [] : ['class' => 'form-group col-md-4'];

        return [
            [
                'name'    => 'name',
                'label'   => 'Name',
                'wrapper' => $wrapper
            ],
            [
                'name'    => 'email',
                'label'   => 'Email',
                'type'    => 'email',
                'wrapper' => $wrapper
            ],
            [
                'name'    => 'role_id',
                'label'   => 'Role',
                'wrapper' => $wrapper,
            ]
        ];
    }

    private function setupFields()
    {
        $wrapper = ['class' => 'form-group col-md-6'];

        return [
            [
                'name'    => 'password',
                'label'   => 'Password',
                'type'    => 'password',
                'wrapper' => $wrapper
            ],
            [
                'name'    => 'password_confirmation',
                'label'   => 'Password Confirmation',
                'type'    => 'password',
                'wrapper' => $wrapper
            ]
        ];
    }
}
