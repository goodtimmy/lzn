<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bath_id')->nullable();
            $table->integer('user_id');
            $table->integer('parent_id')->nullable();
            $table->integer('bath_capacity')->nullable();
            $table->boolean('approved')->default(false);
            $table->boolean('paid')->default(false);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
