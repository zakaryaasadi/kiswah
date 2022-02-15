<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitleArToNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->text('text_ar')->after('text')->nullable();
            $table->string('title_ar')->after('text')->nullable();
        });
        Schema::table('donation_types', function (Blueprint $table) {
            $table->integer('is_available')->default(1)->change();
            $table->integer('is_acceptable')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['url', 'text_ar', 'title_ar']);
        });
        Schema::table('donation_types', function (Blueprint $table) {
            $table->boolean('is_acceptable')->default(true)->change();
            $table->boolean('is_available')->default(true)->change();
        });
    }
}
