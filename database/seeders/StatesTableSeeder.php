<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        State::truncate();

        DB::table('states')->insert([
            [
                'id' => 1,
                'name' => 'Acre',
                'abbreviation' => 'AC',
            ], [
                'id' => 2,
                'name' => 'Alagoas',
                'abbreviation' => 'AL',
            ], [
                'id' => 3,
                'name' => 'Amazonas',
                'abbreviation' => 'AM',
            ], [
                'id' => 4,
                'name' => 'Amapá',
                'abbreviation' => 'AP',
            ], [
                'id' => 5,
                'name' => 'Bahia',
                'abbreviation' => 'BA',
            ], [
                'id' => 6,
                'name' => 'Ceará',
                'abbreviation' => 'CE',
            ], [
                'id' => 7,
                'name' => 'Distrito Federal',
                'abbreviation' => 'DF',
            ], [
                'id' => 8,
                'name' => 'Espírito Santo',
                'abbreviation' => 'ES',
            ], [
                'id' => 9,
                'name' => 'Goiás',
                'abbreviation' => 'GO',
            ], [
                'id' => 10,
                'name' => 'Maranhão',
                'abbreviation' => 'MA',
            ], [
                'id' => 11,
                'name' => 'Minas Gerais',
                'abbreviation' => 'MG',
            ], [
                'id' => 12,
                'name' => 'Mato Grosso do Sul',
                'abbreviation' => 'MS',
            ], [
                'id' => 13,
                'name' => 'Mato Grosso',
                'abbreviation' => 'MT',
            ], [
                'id' => 14,
                'name' => 'Pará',
                'abbreviation' => 'PA',
            ], [
                'id' => 15,
                'name' => 'Paraiba',
                'abbreviation' => 'PB',
            ], [
                'id' => 16,
                'name' => 'Pernambuco',
                'abbreviation' => 'PE',
            ], [
                'id' => 17,
                'name' => 'Piauí',
                'abbreviation' => 'PI',
            ], [
                'id' => 18,
                'name' => 'Paraná',
                'abbreviation' => 'PR',
            ], [
                'id' => 19,
                'name' => 'Rio de Janeiro',
                'abbreviation' => 'RJ',
            ], [
                'id' => 20,
                'name' => 'Rio Grande do Norte',
                'abbreviation' => 'RN',
            ], [
                'id' => 21,
                'name' => 'Rondônia',
                'abbreviation' => 'RO',
            ], [
                'id' => 22,
                'name' => 'Roraima',
                'abbreviation' => 'RR',
            ], [
                'id' => 23,
                'name' => 'Rio Grande do Sul',
                'abbreviation' => 'RS',
            ], [
                'id' => 24,
                'name' => 'Santa Catarina',
                'abbreviation' => 'SC',
            ], [
                'id' => 25,
                'name' => 'Sergipe',
                'abbreviation' => 'SE',
            ], [
                'id' => 26,
                'name' => 'São Paulo',
                'abbreviation' => 'SP',
            ], [
                'id' => 27,
                'name' => 'Tocantins',
                'abbreviation' => 'TO',
            ],
        ]);
    }
}
