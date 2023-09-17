<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public static array $roles = [
        [
            'is_guest' => true,
            'name' => 'Гость',
            'code' => 'guest',
        ],
        [
            'is_default' => true,
            'name' => 'Пользователь',
            'code' => 'user',
        ],
        [
            'is_admin' => true,
            'name' => 'Администратор',
            'code' => 'admin',
        ],
        [
            'is_partner' => true,
            'name' => 'Партнер',
            'code' => 'partner',
        ],
        [
            'is_employee' => true,
            'name' => 'Сотрудник',
            'code' => 'employee',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        foreach (self::$roles as $key => $role) {
            try {
                $roleData = [
                    'id' => $key,
                    'name' => $role['name'],
                    'code' => $role['code'],
                ];

                $roleData['is_guest'] = $role['is_guest'] ?? false;
                $roleData['is_default'] = $role['is_default'] ?? false;
                $roleData['is_admin'] = $role['is_admin'] ?? false;
                $roleData['is_partner'] = $role['is_partner'] ?? false;
                $roleData['is_employee'] = $role['is_employee'] ?? false;

                DB::table('roles')->insert($roleData);
            } catch (Exception $e) {
                // empty catch =(
            }
        }

    }
}
