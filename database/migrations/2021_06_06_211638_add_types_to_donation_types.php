<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypesToDonationTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donation_types', function (Blueprint $table) {
            $table->string('icon')->nullable()->change();
            $table->boolean('is_available')->default(false)->change();
            $table->boolean('is_acceptable')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donation_types', function (Blueprint $table) {
            $table->dropColumn('acceptable');
        });
    }
}
