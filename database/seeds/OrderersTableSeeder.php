<?php

use App\Laravue\Models\Orderer;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class OrderersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orderers')->delete();
        $json = File::get(base_path('database\datafaker\orderers.json'));
        $data = json_decode($json);
        foreach ($data as $obj_orderers) {
            Orderer::create(array(
                'orderer_id' => $obj_orderers->orderer_id,
                'people_id' => User::all()->random()->id,
                'value' => $obj_orderers->value,
                'label' => $obj_orderers->label,
                'photo' => $obj_orderers->photo
            ));
        }
    }
}
