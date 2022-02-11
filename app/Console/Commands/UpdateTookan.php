<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Locations;
use App\Services\Tookan;
use Illuminate\Console\Command;
use phpDocumentor\Reflection\Location;

class UpdateTookan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:tookan';

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
        $time = now();
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $customers = Customer::where('tookan_id', null)->get();
        foreach ($customers as $customer) {
            $location = Locations::where('customer_id', $customer->id)->where('is_default', 1)->first();
            $tookan = (new Tookan())->addCustomer([
                'name' =>  $customer->name,
                'phone' =>  $customer->phone,
                'email' =>  $customer->email,
                'address' => $location->address ?? '',
                'latitude' => $location->latitude ?? null,
                'longitude' =>  $location->longitude ?? null
            ]);
            if ($tookan->status === 200) {
                $customer->tookan_id = $tookan->data['customer_id'];
                $customer->save();
            }
            $out->writeln("Updated. " . $customer->name);
        }
        return $out->writeln( "\nDone Processing @ " . now()->diffInSeconds($time) ." seconds\n");

        return 0;
    }
}
