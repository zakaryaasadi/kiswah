<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFloorToLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('building_no', 10)->nullable()->after('longitude');
            $table->string('floor', 10)->nullable()->after('building_no');
            $table->string('apartment_no', 10)->nullable()->after('floor');
            $table->string('description')->nullable()->after('apartment_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['building_no', 'floor', 'apartment_no', 'description']);
        });
    }
}
