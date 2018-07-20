<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pesanan')->unsigned()->nullable();
            $table->date('tgl_bayar')->nullable();
            $table->integer('total_tagihan')->nullable();
            $table->integer('jumlah_bayar')->nullable();
            $table->integer('sisa_tagihan')->nullable();
            $table->integer('kembalian')->nullable();
            $table->string('keterangan', 200)->nullable();
            $table->unsignedInteger('user_input')->nullable();
            $table->unsignedInteger('user_update')->nullable();
            $table->timestamps();
            $table->foreign('id_pesanan','pembayaran_fk_pesanan')->references('id')->on('pesanan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
}
