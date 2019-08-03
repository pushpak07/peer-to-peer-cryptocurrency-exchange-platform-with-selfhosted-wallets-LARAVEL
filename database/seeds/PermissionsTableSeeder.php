<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed();
    }

    private function seed()
    {
        if($permissions = config('permission.list')){

            collect($permissions)
                ->each(function ($value) {
                    $name = $value['name'];

                    $record = collect($value)->only([
                        'name', 'priority'
                    ])->toArray();

                    Permission::firstOrCreate([
                        'name' => $name,
                    ], $record);

                });

        }

        $this->seedRoles();
    }

    private function seedRoles()
    {
        if($roles = config('permission.roles')){

            collect($roles)
                ->each(function ($value) {
                    $name = $value['name'];

                    $role = Role::firstOrCreate([
                        'name' => $name,
                    ]);

                    $role->syncPermissions(
                        $value['permissions']
                    );
                });

        }
    }
}
