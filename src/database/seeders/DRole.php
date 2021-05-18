<?php

namespace Starmoozie\MenuPermission\database\seeders;

use Illuminate\Database\Seeder;

use Starmoozie\MenuPermission\app\Models\Role;

class DRole extends Seeder
{
    private $data = [
        [
            'name'   => 'super user',
            'access' => [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22]
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $value) {
            Role::updateOrCreate([
                'name' => $value['name']
            ], $value);
        }
    }
}
