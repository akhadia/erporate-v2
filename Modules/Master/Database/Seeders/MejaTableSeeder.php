<?php

namespace Modules\Master\Database\Seeders;

use Modules\Master\Models\Meja;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class MejaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $faker = Faker::create();

        for($i = 1; $i <= 50; $i++) {
            Meja::insert([
                'no_meja' => $i.$faker->randomLetter,
            ]);
        }
    }
}
