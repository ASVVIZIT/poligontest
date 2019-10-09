<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Laravue\Acl;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $userListAddAdmin = [
            "Сергей Андрианов Викторович",
        ];

        foreach ($userListAddAdmin as $fullName) {
            $roleName = \App\Laravue\Faker::randomInArray([
                Acl::ROLE_ADMIN,
            ]);
            $fullName = str_replace(' ', '|||', $fullName);
            $firstname = explode ("|||", $fullName);
            $surname = explode ("|||", $fullName);
            $patronymic = explode ("|||", $fullName);
            $fullName = str_replace('|||', ' ', $fullName);
            $user = \App\Laravue\Models\User::create([
                'name' => $fullName,
                'firstname' => $firstname[0],
                'surname' => $surname[1],
                'patronymic' => $patronymic[2],
                'birthday' => '1986-02-17',
                'email' => 'DillerASV@yandex.ru',
                'password' => \Illuminate\Support\Facades\Hash::make('Fgjrfkbgcbc001001'),
            ]);
            $role = Role::findByName($roleName);
            $user->syncRoles($role);
        }

        $admin = User::create([
            'name' => 'Admin',
            'firstname' => 'Admin',
            'surname' => 'Adminov',
            'patronymic' => 'Adminovisch',
            'birthday' => '2019-10-05',
            'email' => 'admin@laravue.dev',
            'password' => Hash::make('laravuelaravue'),
        ]);
        $manager = User::create([
            'name' => 'Manager',
            'firstname' => 'Manager',
            'surname' => 'Managerov',
            'patronymic' => 'Managerovisch',
            'birthday' => '2019-10-05',
            'email' => 'manager@laravue.dev',
            'password' => Hash::make('secretsecret'),
        ]);
        $editor = User::create([
            'name' => 'Editor',
            'firstname' => 'Editor',
            'surname' => 'Editorov',
            'patronymic' => 'Editorovisch',
            'birthday' => '2019-10-05',
            'email' => 'editor@laravue.dev',
            'password' => Hash::make('secretsecret'),
        ]);
        $user = User::create([
            'name' => 'User',
            'firstname' => 'User',
            'surname' => 'Userov',
            'patronymic' => 'Userovisch',
            'birthday' => '2019-10-05',
            'email' => 'user@laravue.dev',
            'password' => Hash::make('secretsecret'),
        ]);
        $visitor = User::create([
            'name' => 'Visitor',
            'firstname' => 'Visitor',
            'surname' => 'Visitorov',
            'patronymic' => 'Visitorovisch',
            'birthday' => '2019-10-05',
            'email' => 'visitor@laravue.dev',
            'password' => Hash::make('secretsecret'),
        ]);

        $adminRole = Role::findByName(\App\Laravue\Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(\App\Laravue\Acl::ROLE_MANAGER);
        $editorRole = Role::findByName(\App\Laravue\Acl::ROLE_EDITOR);
        $userRole = Role::findByName(\App\Laravue\Acl::ROLE_USER);
        $visitorRole = Role::findByName(\App\Laravue\Acl::ROLE_VISITOR);
        $admin->syncRoles($adminRole);
        $manager->syncRoles($managerRole);
        $editor->syncRoles($editorRole);
        $user->syncRoles($userRole);
        $visitor->syncRoles($visitorRole);
        $this->call(UsersTableSeeder::class);
    }
}
