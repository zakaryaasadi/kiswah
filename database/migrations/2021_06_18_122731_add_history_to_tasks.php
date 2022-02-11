<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHistoryToTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('job_status')->nullable();
            $table->text('task_history')->nullable();
            $table->string('tracking_link')->nullable();
            $table->string('arrived_datetime')->nullable();
            $table->string('started_datetime')->nullable();
            $table->string('completed_datetime')->nullable();
            $table->string('acknowledged_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['tracking_link',
                'task_history', 'job_status', 'arrived_datetime', 'started_datetime',
                'completed_datetime', 'acknowledged_datetime']);
        });
    }
}
