<?php

namespace App\Console\Commands;

use App\Models\Charity;
use App\Models\Customer;
use App\Models\DonationType;
use App\Models\News;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UpdateImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Images ';

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
        $models = [
            ['name' => 'Charity', 'model' => Charity::get(), 'columns' => ['image'], 'path' => 'uploads'],
            ['name' => 'Donation Type', 'model' => DonationType::get(), 'columns' => ['icon'], 'path' => 'uploads'],
            ['name' => 'Customers', 'model' => Customer::get(), 'columns' => ['avatar'], 'path' => 'avatar'],
            ['name' => 'News', 'model' => News::get(), 'columns' => ['image'], 'path' => 'uploads'],
            ['name' => 'Tasks', 'model' => Task::get(), 'columns' => ['ref_images'], 'path' => 'images'],
        ];
        foreach ($models as $model) {
            $datas = $model['model'];
            $columns = $model['columns'];
            $path = $model['path'];
            $name = $model['name'];
            $is = $model['name'] === 'Tasks';
            $out->writeln("\n======Starting $name========\n");
            foreach ($datas as $data) {
                foreach ($columns as $column) {
                    $columnData = $data->{$column};
                    if ($is) {
                        if ($columnData && count($columnData)) {
                            $images = [];
                            foreach ($columnData as $image) {
                                $word = substr($image, strpos($image, $path));
                                if ($word) {
                                    if (str_contains($word, 'http')) {
                                        $out->writeln("\nContains HTTP");
                                    } else {
                                        $word = asset(Storage::url($word));
                                    }
                                    if ($word === asset(Storage::url(''))) {
                                        $word = '';
                                    }
                                    $images[] = $word;
                                    $out->writeln("Changed $word\n");
                                }
                            }
                            $data->{$column} = $images;
                            $data->save();
                        }
                    } else {
                        $word = substr($columnData, strpos($columnData, $path));
                        if (str_contains($word, 'http')) {
                            $out->writeln("\nContains HTTP");
                        } else {
                            $word = asset(Storage::url($word));
                        }
                        if ($word === asset(Storage::url(''))) {
                            $word = '';
                        }
                        $data->{$column} = $word;
                        $data->save();
                        $out->writeln("Changed $word\n");
                    }
                }
            }
            $out->writeln("========Done with $name========");
        }
        return $out->writeln("\nDone Processing @ " . now()->diffInSeconds($time) . " seconds\n");
    }
}
