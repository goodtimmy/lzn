<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameBathNeededColumnInGroupOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_orders', function (Blueprint $table) {
            $table->renameColumn('bath_needed', 'person_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_orders', function (Blueprint $table) {
            $table->renameColumn('person_count', 'bath_needed');
        });
    }
}
