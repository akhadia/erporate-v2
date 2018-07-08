<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Models\Kategori;


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

        // $this->call("OthersTableSeeder");
        Kategori::insert([
            ['nama'  => 'Main Course'],
            ['nama'  => 'Appetizer'],
            ['nama'  => 'Pasta'],
            ['nama'  => 'Drink'],
            ['nama'  => 'Dessert'],
            ['nama'  => 'Locarasa'],
              
        ]);
    }
}
