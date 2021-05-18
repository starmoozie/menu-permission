<?php

namespace Starmoozie\MenuPermission\database\seeders;

use Illuminate\Database\Seeder;

use Starmoozie\MenuPermission\app\Models\Menu;

class BMenu extends Seeder
{
    private $data = [
        [
            'name'      => 'permission', // 1
            'parent_id' => 3,
            'url'       => 'permission',
            'lft'       => 3,
            'rgt'       => 4,
            'depth'     => 2,
        ],
        [
            'name'      => 'menu', // 2
            'parent_id' => 3,
            'url'       => 'menu',
            'lft'       => 5,
            'rgt'       => 6,
            'depth'     => 2,
        ],
        [
            'name'      => 'menu permission', // 3
            'parent_id' => null,
            'url'       => '#',
            'lft'       => 2,
            'rgt'       => 7,
            'depth'     => 1,
        ],
        [
            'name'      => 'role user', // 4
            'parent_id' => null,
            'url'       => '#',
            'lft'       => 8,
            'rgt'       => 13,
            'depth'     => 1,
        ],
        [
            'name'      => 'role', // 5
            'parent_id' => 4,
            'url'       => 'role',
            'lft'       => 9,
            'rgt'       => 10,
            'depth'     => 2,
        ],
        [
            'name'      => 'user', // 6
            'parent_id' => 4,
            'url'       => 'user',
            'lft'       => 11,
            'rgt'       => 12,
            'depth'     => 2,
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
            Menu::updateOrCreate([
                'name' => $value['name'],
                'url'  => $value['url']
            ], $value);
        }
    }
}
