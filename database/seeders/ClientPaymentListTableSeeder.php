<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientPaymentListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        DB::table('payment_lists')->truncate();

        $items = [

            [
                'id' => 1,
                'payment_type' => 'Registration Payment',
                'slug' => 'registration_payment',
                'amount' => 20000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'payment_type' => 'Zoom Payment',
                'slug' => 'zoom_payment',
                'amount' => 10000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'payment_type' => 'Appointment Payment',
                'slug' => 'appointment_payment',
                'amount' => 10000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],


        ];
        DB::table('payment_lists')->insert($items);
    }

}
