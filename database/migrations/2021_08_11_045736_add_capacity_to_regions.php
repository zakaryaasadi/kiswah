<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCapacityToRegions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->bigInteger('capacity')->default(20);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->bigInteger('region_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->dropColumn(['capacity']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['region_id']);
        });
    }
}
