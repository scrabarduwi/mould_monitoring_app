<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMouldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mould', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mould_image')->nullable();
            $table->string('mould_details')->nullable();
            $table->string('rack_no');
            $table->string('client_id');
            $table->string('ring_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mould');
    }
}
