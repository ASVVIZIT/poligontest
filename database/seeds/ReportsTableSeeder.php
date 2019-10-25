<?php

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;

class ReportsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
// Available alpha caracters
        // $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
         $characters = '0123456789';
// generate a pin based on 2 * 7 digits + a random character
        $pin1 = mt_rand(100000, 1000000)
            . mt_rand(100000, 1000000)
            . $characters[rand(3, strlen($characters - 3))];
        $pin2 = mt_rand(1000, 10000)
            . mt_rand(1000, 10000)
            . $characters[rand(3, strlen($characters - 3))];
// shuffle the result
        //Start point of our date range.
        $start = strtotime("2015-01-01 01:10:10");
        //End point of our date range.
        $end = strtotime("2019-01-01 12:10:10");

        for ($i = 1; $i <= 250; $i++) {
            $string1 = str_shuffle($pin1);
            $string2 = str_shuffle($pin2);
            //Custom range.
            $timestamp = mt_rand($start, $end);
            $arrayStatus = ['1', '2', '3', '4', '5'];
            $arrayRundomTime = [null, date('Y-m-d h:m:s', $timestamp), date('Y-m-d h:m:s', $timestamp), date('Y-m-d h:m:s', $timestamp), date('Y-m-d h:m:s', $timestamp)];
            $summod = (rand(1, 9) & 0x00FFFFFF * 10);
            $summod_value = $summod * 1000;

            if ($summod_value % 10000 != 0) {
                $summod_value1 = $summod_value;
            }
            $data[] = [
                'user_id' => User::all()->random()->id,
                'order_id' => $string1 . $i,
                'report_id' => $string2 . $i,
                'status' => $arrayStatus[rand(0, 4)],
                'created_at' => $arrayRundomTime[rand(1, 4)],
                'updated_at' => $arrayRundomTime[rand(0, 4)],
                'title' => 'title title title title title title title title ',
                'price' => $summod_value1,
            ];
        }
      DB::table('reports')->insert($data);
    }
}
