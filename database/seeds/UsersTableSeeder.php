<?php

use Illuminate\Database\Seeder;
use App\Laravue\Acl;
use App\Laravue\Models\Role;

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

        foreach ($userList as $fullName) {

            //Start point of our date range.
            $start = strtotime("10 09 1971");
            //End point of our date range.
            $end = strtotime("22 06 1990");
            $timestamp = mt_rand($start, $end);

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
            $user = \App\Laravue\Models\User::create([
                'name' => $fullName,
                'firstname' => $firstname,
                'surname' => $surname,
                'patronymic' => $patronymic,
                'birthday' => date('Y-m-d', $timestamp),
                'email' => strtolower($firstname_email . str_replace('.', '_', str_replace(' ', '', $surname))) . '@laravue.dev',
                'password' => \Illuminate\Support\Facades\Hash::make('randomrandom'),
            ]);
            $role = Role::findByName($roleName);
            $user->syncRoles($role);
            $firstname = '';
            $surname = '';
            $patronymic = '';

        }
    }
}
