<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRoleAndPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_1 = Role::create(['name' => 'admin']);

        $role_2 = Role::create(['name' => 'customer']);

        $role_3 = Role::create(['name' => 'debt-collector']);

        $user = new User();

        $user->name = 'admin';

        $user->full_name = 'Admin';

        $user->email = 'toribiotejadaimport@gmail.com';

        $user->password = bcrypt('zRxQb7KFB4UgCUyv');

        $user->identification = '000-0000000-0';

        $user->status = true;

        $user->save();

        $user->assignRole($role_1);

        $user->assignRole($role_2);

        $user->assignRole($role_3);

    }
}
