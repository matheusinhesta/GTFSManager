<?php

use Illuminate\Database\Seeder;

class AgencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

        \App\Models\Agency::create([
            'name'     => 'Inhesta Transportes',
            'url'      => 'https://www.google.com',
            'timezone' => 'BRT',
            'lang'     => 'pt-BR',
            'email'    => 'matheusinhesta@live.com'
        ]);

//        \App\Models\Agency::create([
//            'name'     => 'Moreira Transportes',
//            'url'      => 'https://www.google.com',
//            'timezone' => 'BRT',
//            'lang'     => 'pt-BR',
//            'email'    => 'matheus.inhesta@sga.pucminas.br'
//        ]);
    }
}
