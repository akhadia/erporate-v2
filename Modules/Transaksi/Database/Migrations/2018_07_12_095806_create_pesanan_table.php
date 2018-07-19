<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_pesanan', 20)->nullable();
            $table->unsignedInteger('id_meja')->nullable();
            $table->date('tgl_pesanan')->nullable();
            $table->string('keterangan', 150)->nullable();
            $table->string('status', 1)->default('Y')->nullable();
            $table->integer('total')->default(0)->nullable();
            $table->unsignedInteger('user_input')->nullable();
            $table->unsignedInteger('user_update')->nullable();
            $table->timestamps();
            $table->foreign('id_meja','pesanan_fk_meja')->references('id')->on('meja');
            $table->foreign('user_update','pesanan_fk_user_update')->references('id')->on('users');
            $table->foreign('user_input','pesanan_fk_user_input')->references('id')->on('users');
        });

        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_pesanan')->nullable();
            $table->unsignedInteger('id_produk')->nullable();
            $table->integer('qty_pesanan')->nullable();
            $table->integer('harga')->default(0)->nullable();
            $table->integer('subtotal')->default(0)->nullable();
            // $table->unsignedInteger('user_input')->nullable();
            // $table->unsignedInteger('user_update')->nullable();
        });

        Schema::table('detail_pesanan', function (Blueprint $table) {
            // $table->foreign('user_input','det_pesanan_fk_user_input')->references('id')->on('users');
            // $table->foreign('user_update','det_pesanan_fk_user_update')->references('id')->on('users');
            $table->foreign('id_pesanan','det_pesanan_fk_pesanan')->references('id')->on('pesanan');
            $table->foreign('id_produk','det_pesanan_fk_produk')->references('id')->on('produk');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
    }
}
