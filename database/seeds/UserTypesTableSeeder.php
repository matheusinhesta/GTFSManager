<?php

use Illuminate\Database\Seeder;

class UserTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        \App\Models\UserType::create(['description' => 'Administrator']);
        \App\Models\UserType::create(['description' => 'Driver']);

    }
}
