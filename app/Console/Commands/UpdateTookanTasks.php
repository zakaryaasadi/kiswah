<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Services\Tookan;
use Illuminate\Console\Command;

class UpdateTookanTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update tookan users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Task::latest()->whereIn('job_status', [0, 1, 4, 6, 7])->orWhere('job_status', null)->get();
        foreach ($orders as $order) {
            $tookan_orders = (new Tookan())->getTaskDetailsFromOrders([$order->order_id]);
            if ($tookan_orders->status === 200 && count($tookan_orders->data) > 0) {
                $tookan_order = $tookan_orders->data[0];
                $data = [];
                foreach (['tracking_link', 'task_history', 'job_status', 'arrived_datetime', 'started_datetime', 'completed_datetime', 'acknowledged_datetime'] as $colum) {
                    $data[$colum] = $tookan_order[$colum];
                }
                $order->update($data);
            }
        }
        return 0;
    }
}
