<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('task_id');
            $table->timestamp('requested')->nullable();
            $table->timestamp('agent_assigned')->nullable();
            $table->timestamp('on_way')->nullable();
            $table->timestamp('picked')->nullable();
            $table->timestamp('completed')->nullable();
            $table->string('status_readable')->default('Request Sent');
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
        Schema::dropIfExists('task_statuses');
    }
}
