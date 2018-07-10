<?php

namespace Modules\Master\Database\Seeders;

use Modules\Master\Models\Kategori;
use Modules\Master\Models\Produk;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;


class KategoriTableSeeder extends Seeder
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

        // $this->call("OthersTableSeeder");
        // Kategori::insert([
        //     ['nama'  => 'Main Course'],
        //     ['nama'  => 'Appetizer'],
        //     ['nama'  => 'Pasta'],
        //     ['nama'  => 'Drink'],
        //     ['nama'  => 'Dessert'],
        //     ['nama'  => 'Locarasa'],
              
        // ]);

        foreach(range(0,25) as $i){
            Kategori::insert([
                'nama' => $faker->unique()->numerify('category ###') // 'Hello 609'
                
            ]);
        }

        $kategori = Kategori::all();

        foreach($kategori as $value){
            Produk::insert([
                'id_kategori'   => $value->id,
                'nama'          => $faker->unique()->numerify('product ###'),
                'harga'         => $faker->numberBetween($min = 3000, $max = 50000), // 8567
                'deskripsi'     => $faker->sentence($nbWords = 10, $variableNbWords = true)
            ]);
        }
    }
}
