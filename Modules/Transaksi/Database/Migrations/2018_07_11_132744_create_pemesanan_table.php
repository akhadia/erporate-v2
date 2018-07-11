<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePemesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_pemesanan', 20)->nullable();
            $table->unsignedInteger('id_meja')->nullable();
            $table->date('tgl_pemesanan')->nullable();
            $table->string('keterangan', 150)->nullable();
            $table->string('status', 1)->default('N')->nullable();
            $table->integer('total')->default(0)->nullable();
            $table->unsignedInteger('user_input')->nullable();
            $table->unsignedInteger('user_update')->nullable();
            $table->timestamps();
            $table->foreign('id_meja','pemesanan_fk_meja')->references('id')->on('meja');
            $table->foreign('user_update','pemesanan_fk_user_update')->references('id')->on('users');
            $table->foreign('user_input','pemesanan_fk_user_input')->references('id')->on('users');
        });

        Schema::create('detail_pemesanan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_pemesanan')->nullable();
            $table->unsignedInteger('id_produk')->nullable();
            $table->integer('qty_pemesanan')->nullable();
            $table->integer('harga')->default(0)->nullable();
            $table->integer('subtotal')->default(0)->nullable();
            // $table->unsignedInteger('user_input')->nullable();
            // $table->unsignedInteger('user_update')->nullable();
        });

        Schema::table('detail_pemesanan', function (Blueprint $table) {
            // $table->foreign('user_input','det_pemesanan_fk_user_input')->references('id')->on('users');
            // $table->foreign('user_update','det_pemesanan_fk_user_update')->references('id')->on('users');
            $table->foreign('id_pemesanan','det_pemesanan_fk_pemesanan')->references('id')->on('pemesanan');
            $table->foreign('id_produk','det_pemesanan_fk_produk')->references('id')->on('produk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pemesanan');
        Schema::dropIfExists('pemesanan');
    }
}
