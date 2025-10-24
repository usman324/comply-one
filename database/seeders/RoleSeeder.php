<?php

namespace Database\Seeders;

// use App\Models\Role;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'admin',
                'guard_name' => 'web',
                'is_hidden' => 1,
            ],
             [
                'name' => 'workspace',
                'guard_name' => 'web',
                'is_hidden' => 1,
            ],


        ];
        Role::insert($data);
    }
}
