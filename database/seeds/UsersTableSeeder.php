<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        \App\Models\User::create([
            'type_id'   => 1, // Adm
            'agency_id' => 1, // Inhesta Transportes
            'name'      => 'Matheus Inhesta',
            'email'     => 'matheusinhesta@live.com',
            'password'  => \Illuminate\Support\Facades\Hash::make('123123'),
            'remember_token' => Str::random(10)
        ]);

        \App\Models\User::create([
            'type_id'   => 2, // Driver
            'agency_id' => 1, // Inhesta Transportes
            'name'      => 'André Moreira',
            'email'     => 'andremoreira@localhost.com',
            'password'  => \Illuminate\Support\Facades\Hash::make('123456'),
            'remember_token' => Str::random(10)
        ]);

    }
}
