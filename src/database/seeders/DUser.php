<?php

namespace Starmoozie\MenuPermission\database\seeders;

use Illuminate\Database\Seeder;

use Starmoozie\MenuPermission\app\Models\User;

class DUser extends Seeder
{
    private $data;

    public function __construct()
    {
        $this->data = [
            [
                'name'      => 'starmoozie',
                'email'     => 'starmoozie@gmail.com',
                'password'  => bcrypt('password'),
                'role_id'   => 1
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $value) {
            $table = \DB::table('users');
            $users = $table->whereEmail($value['email'])->first();

            if (!$users) {
                $table->insert($value);
            }
        }
    }
}
