<?php

use App\Laravue\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Laravue\Acl;
use App\Laravue\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userList = [
            "Adriana",
            "Max Max",
            "Adriana C. Ocampo Uria",
            "Albert Einstein",
            "Anna K. Behrensmeyer",
            "Blaise Pascal",
            "Caroline Herschel",
            "Cecilia Payne-Gaposchkin",
            "Chien-Shiung Wu",
            "Dorothy Hodgkin",
            "Edmond Halley",
            "Edwin Powell Hubble",
            "Elizabeth Blackburn",
            "Enrico Fermi",
            "Erwin Schroedinger",
            "Flossie Wong-Staal",
            "Frieda Robscheit-Robbins",
            "Geraldine Seydoux",
            "Gertrude B. Elion",
            "Ingrid Daubechies",
            "Jacqueline K. Barton",
            "Jane Goodall",
            "Jocelyn Bell Burnell",
            "Johannes Kepler",
            "Lene Vestergaard Hau",
            "Lise Meitner",
            "Lord Kelvin",
            "Maria Mitchell",
            "Marie Curie",
            "Max Born",
            "Max Planck",
            "Melissa Franklin",
            "Michael Faraday",
            "Mildred S. Dresselhaus",
            "Nicolaus Copernicus",
            "Niels Bohr",
            "Patricia S. Goldman-Rakic",
            "Patty Jo Watson",
            "Polly Matzinger",
            "Richard Phillips Feynman",
            "Rita Levi-Montalcini",
            "Rosalind Franklin",
            "Ruzena Bajcsy",
            "Sarah Boysen",
            "Shannon W. Lucid",
            "Shirley Ann Jackson",
            "Sir Ernest Rutherford",
            "Sir Isaac Newton",
            "Stephen Hawking",
            "Werner Karl Heisenberg",
            "Wilhelm Conrad Roentgen",
            "Wolfgang Ernst Pauli",
        ];
        //Start point of our date range.
        $start = strtotime("1961-09-10 12:10:10");
        //End point of our date range.
        $end = strtotime("1990-06-22 24:00:00");
        $faker = Faker::create('Ru_RU');
        foreach ($userList as $fullName) {
            // Returns always random genders according to the name, inclusive mixed !!
            $gender = ['male', 'female'];
            $timestamp = mt_rand($start, $end);
            $arrayRundomTime = [null, date('Y-m-d h:m:s', $timestamp), date('Y-m-d h:m:s', $timestamp), date('Y-m-d h:m:s', $timestamp), date('Y-m-d h:m:s', $timestamp)];
            $name = str_replace(' ', '.', $fullName);
            $roleName = \App\Laravue\Faker::randomInArray([
                Acl::ROLE_MANAGER,
                Acl::ROLE_EDITOR,
                Acl::ROLE_USER,
                Acl::ROLE_VISITOR,
            ]);

            $fullName = str_replace('. ', '._', $fullName);
            $fullNamArray = str_replace(' ', '||', $fullName);
            $fullNamArray = str_replace('._', '. ', $fullNamArray);
            $fullNameBuffer = explode("||", $fullNamArray);

                if (count($fullNameBuffer) === 3) {
                    $firstname = $fullNameBuffer[0];
                    $firstname_email = $firstname . '_';
                    $surname = $fullNameBuffer[1];
                    $patronymic = $fullNameBuffer[2];
                } elseif (count($fullNameBuffer) === 2) {
                    $firstname = $fullNameBuffer[0];
                    $firstname_email = $firstname . '_';
                    $surname = $fullNameBuffer[1];
                    $patronymic = '';
                } elseif (count($fullNameBuffer) === 1) {
                    $firstname = $fullNameBuffer[0];
                    $firstname_email = $firstname;
                    $surname = '';
                    $patronymic = '';
                }

            $fullName = str_replace('._', '. ', $fullName);
            $user = User::create([
                'name' => $fullName,
                'firstname' => $firstname,
                'surname' => $surname,
                'patronymic' => $patronymic,
                'gender' => $gender[rand(0, 1)],
                'birthday' => $arrayRundomTime[rand(1, 4)],
                'email' => strtolower($firstname_email . str_replace('.', '_', str_replace(' ', '', $surname))) . '@laravue.dev',
                'phone1' => $faker->phoneNumber,
                'phone2' => $faker->phoneNumber,
                'skype' => $faker->userName,
                'address' => $faker->address,
                'password' => Hash::make('randomrandom'),
                'remember_token' => str::random(20),
            ]);
            $role = Role::findByName($roleName);
            $user->syncRoles($role);
            $firstname = '';
            $surname = '';
            $patronymic = '';

        }
    }
}
