<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_kategori')->nullable();
            $table->string('nama')->nullable();
            $table->integer('harga')->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->foreign('id_kategori','produk_fk_kategori')->references('id')->on('kategori');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
}
