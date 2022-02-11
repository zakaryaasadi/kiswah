<?php

namespace App\Console\Commands;

use App\Models\Locations;
use App\Models\Region;
use App\Models\Task;
use App\Services\Tookan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateLocationRegions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update location region_id';

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
        $locations = Locations::latest()->get();
        foreach ($locations as $location) {
            $regions = (new Tookan())->findRegion($location->latitude, $location->longitude);
            $ids = [];
            foreach ($regions->data as $line) {
                $ids[] = $line['region_id'];
            }
            $region = Region::whereIn('tookan_id', $ids)->first();
            if ($region) {
                $location->region_id = $region->id;
                $location->save();
                DB::table('tasks')->where('location_id', $location->id)->update(['region_id' => $region->id]);
            }
        }
        return print 'Updated';
    }
}
