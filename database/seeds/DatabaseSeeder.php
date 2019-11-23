<?php

use App\Laravue\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        //Start point of our date range.
        $start = strtotime("1961-09-10 12:10:10");
        //End point of our date range.
        $end = strtotime("1990-06-22 24:00:00");

        $faker = Faker::create('Ru_RU');

        foreach ($userListAddAdmin as $fullName) {
            $timestamp = mt_rand($start, $end);
            $arrayRundomTime = [null, date('Y-m-d h:m:s', $timestamp), date('Y-m-d h:m:s', $timestamp), date('Y-m-d h:m:s', $timestamp), date('Y-m-d h:m:s', $timestamp)];
            $roleName = \App\Laravue\Faker::randomInArray([
                Acl::ROLE_ADMIN,
            ]);
            $fullName = str_replace(' ', '|||', $fullName);
            $firstname = explode ("|||", $fullName);
            $surname = explode ("|||", $fullName);
            $patronymic = explode ("|||", $fullName);
            $fullName = str_replace('|||', ' ', $fullName);
            $user = User::create([
                'name' => $fullName,
                'firstname' => $firstname[0],
                'surname' => $surname[1],
                'patronymic' => $patronymic[2],
                'gender' => 'male',
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
            'gender' => 'male',
            'birthday' => '2019-10-05',
            'phone1' => $faker->phoneNumber,
            'phone2' => $faker->phoneNumber,
            'skype' => $faker->userName,
            'address' => $faker->address,
            'email' => 'admin@laravue.dev',
            'password' => Hash::make('laravuelaravue'),
            'remember_token' => str::random(20),
        ]);

        $moderator = User::create([
            'name' => 'Moderator',
            'firstname' => 'Moderator',
            'surname' => 'Moderatorov',
            'patronymic' => 'Moderatorovisch',
            'gender' => 'male',
            'birthday' => $arrayRundomTime[rand(1, 4)],
            'phone1' => $faker->phoneNumber,
            'phone2' => $faker->phoneNumber,
            'skype' => $faker->userName,
            'address' => $faker->address,
            'email' => 'moderator@laravue.dev',
            'password' => Hash::make('moderator'),
            'remember_token' => str::random(20),
        ]);

        $manager = User::create([
            'name' => 'Manager',
            'firstname' => 'Manager',
            'surname' => 'Managerov',
            'patronymic' => 'Managerovisch',
            'gender' => 'male',
            'birthday' => $arrayRundomTime[rand(1, 4)],
            'phone1' => $faker->phoneNumber,
            'phone2' => $faker->phoneNumber,
            'skype' => $faker->userName,
            'address' => $faker->address,
            'email' => 'manager@laravue.dev',
            'password' => Hash::make('secretsecret'),
            'remember_token' => str::random(20),
        ]);
        $editor = User::create([
            'name' => 'Editor',
            'firstname' => 'Editor',
            'surname' => 'Editorov',
            'patronymic' => 'Editorovisch',
            'gender' => 'male',
            'birthday' => $arrayRundomTime[rand(1, 4)],
            'phone1' => $faker->phoneNumber,
            'phone2' => $faker->phoneNumber,
            'skype' => $faker->userName,
            'address' => $faker->address,
            'email' => 'editor@laravue.dev',
            'password' => Hash::make('secretsecret'),
            'remember_token' => str::random(20),
        ]);
        $user = User::create([
            'name' => 'User',
            'firstname' => 'User',
            'surname' => 'Userov',
            'patronymic' => 'Userovisch',
            'gender' => 'male',
            'birthday' => $arrayRundomTime[rand(1, 4)],
            'phone1' => $faker->phoneNumber,
            'phone2' => $faker->phoneNumber,
            'skype' => $faker->userName,
            'address' => $faker->address,
            'email' => 'user@laravue.dev',
            'password' => Hash::make('secretsecret'),
            'remember_token' => str::random(20),
        ]);
        $visitor = User::create([
            'name' => 'Visitor',
            'firstname' => 'Visitor',
            'surname' => 'Visitorov',
            'patronymic' => 'Visitorovisch',
            'gender' => 'male',
            'birthday' => $arrayRundomTime[rand(1, 4)],
            'phone1' => $faker->phoneNumber,
            'phone2' => $faker->phoneNumber,
            'skype' => $faker->userName,
            'address' => $faker->address,
            'email' => 'visitor@laravue.dev',
            'password' => Hash::make('secretsecret'),
            'remember_token' => str::random(20),
        ]);

        $adminRole = Role::findByName(\App\Laravue\Acl::ROLE_ADMIN);
        $moderatorRole = Role::findByName(\App\Laravue\Acl::ROLE_MODERATOR);
        $managerRole = Role::findByName(\App\Laravue\Acl::ROLE_MANAGER);
        $editorRole = Role::findByName(\App\Laravue\Acl::ROLE_EDITOR);
        $userRole = Role::findByName(\App\Laravue\Acl::ROLE_USER);
        $visitorRole = Role::findByName(\App\Laravue\Acl::ROLE_VISITOR);

        $admin->syncRoles($adminRole);
        $moderator->syncRoles($moderatorRole);
        $manager->syncRoles($managerRole);
        $editor->syncRoles($editorRole);
        $user->syncRoles($userRole);
        $visitor->syncRoles($visitorRole);

        $this->call(UsersTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(ReportsTableSeeder::class);
        $this->call(OrderersTableSeeder::class);

        for($i = 0; $i < 2; $i++) {
            factory(App\User::class, 20)->create();
        }

        //factory(App\Laravue\Models\Order::class)->create();
        //factory(App\Laravue\Models\Report::class)->create();
    }
}
