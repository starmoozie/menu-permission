<?php

namespace Starmoozie\MenuPermission\database\seeders;

use Illuminate\Database\Seeder;

use Starmoozie\MenuPermission\app\Models\MenuPermission;

class CMenuPermission extends Seeder
{
    private $data = [
        [ // 1
            'menu_id'       => 2,
            'permission_id' => 1
        ],
        [ // 2
            'menu_id'       => 2,
            'permission_id' => 2
        ],
        [ // 3
            'menu_id'       => 2,
            'permission_id' => 3
        ],
        [ // 4
            'menu_id'       => 2,
            'permission_id' => 5
        ],
        [ // 5
            'menu_id'       => 1,
            'permission_id' => 1
        ],
        [ // 6
            'menu_id'       => 1,
            'permission_id' => 2
        ],
        [ // 7
            'menu_id'       => 1,
            'permission_id' => 3
        ],
        [ // 8
            'menu_id'       => 1,
            'permission_id' => 5
        ],
        [ // 9
            'menu_id'       => 3,
            'permission_id' => 1
        ],
        [ // 10
            'menu_id'       => 4,
            'permission_id' => 1
        ],
        [ // 11
            'menu_id'       => 5,
            'permission_id' => 1
        ],
        [ // 12
            'menu_id'       => 5,
            'permission_id' => 2
        ],
        [ // 13
            'menu_id'       => 5,
            'permission_id' => 3
        ],
        [ // 14
            'menu_id'       => 5,
            'permission_id' => 5
        ],
        [ // 15
            'menu_id'       => 6,
            'permission_id' => 1
        ],
        [ // 16
            'menu_id'       => 6,
            'permission_id' => 2
        ],
        [ // 17
            'menu_id'       => 6,
            'permission_id' => 3
        ],
        [ // 18
            'menu_id'       => 6,
            'permission_id' => 5
        ],
        [ // 19
            'menu_id'       => 6,
            'permission_id' => 4
        ],
        [ // 20
            'menu_id'       => 6,
            'permission_id' => 6
        ],
        [ // 21
            'menu_id'       => 6,
            'permission_id' => 7
        ],
        [ // 22
            'menu_id'       => 1,
            'permission_id' => 4
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
            MenuPermission::updateOrCreate([
                'menu_id'       => $value['menu_id'],
                'permission_id' => $value['permission_id']
            ], $value);
        }
    }
}
