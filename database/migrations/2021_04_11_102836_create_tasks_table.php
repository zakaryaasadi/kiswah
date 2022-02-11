<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//  'has_pickup': '0',
//  'has_delivery': '1',
//  'layout_type': '0',
//  'tracking_link': 1,
//  'timezone': '-330',
//  'fleet_id': '636',
//  'notify': 1,
//  'tags': '',
//  'geofence': 0
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->text('job_description');
            $table->dateTime('job_delivery_datetime')->nullable();
            $table->bigInteger('team_id')->unsigned()->nullable();
            $table->boolean('is_pickup')->default(true);
            $table->boolean('auto_assignment')->default(false);
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->string('tags')->nullable();
            $table->string('fleet_id')->nullable();
            $table->json('meta_data')->nullable();
            $table->json('ref_images')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
