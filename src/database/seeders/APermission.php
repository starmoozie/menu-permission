<?php

namespace Starmoozie\MenuPermission\database\seeders;

use Illuminate\Database\Seeder;

use Starmoozie\MenuPermission\app\Models\Permission;

class APermission extends Seeder
{
    private $data = [
        [
            'name'  => 'list'
        ],
        [
            'name'  => 'create'
        ],
        [
            'name'  => 'update'
        ],
        [
            'name'  => 'delete'
        ],
        [
            'name'  => 'show'
        ],
        [
            'name'  => 'export'
        ],
        [
            'name'  => 'print'
        ],
        [
            'name'  => 'personal'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $value) {
            Permission::updateOrCreate([
                'name' => $value['name']
            ], $value);
        }
    }
}
