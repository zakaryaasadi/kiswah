<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name(),
            'image' =>  'https://source.unsplash.com/random',
            'text' => $this->faker->paragraph(),
            'language' => 'en',
            'variation' => ['news', 'awareness', 'donation', 'gift'][rand(0, 3)]
        ];
    }
}
