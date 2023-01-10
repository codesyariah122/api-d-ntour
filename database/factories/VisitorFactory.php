<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Visitor;
use Illuminate\Support\Str;

class VisitorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $visitor = Visitor::class;

    public function definition()
    {
        $randIP = mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);

        return [
            'ip_address' => $randIP,
            'country' => $this->faker->country(),
            'country_flag' => $this->faker->country() . ' flag',
            'city' => $this->faker->city(),
            'district' => $this->faker->citySuffix(),
            'latitude' => Str::random(8),
            'longitude' => Str::random(8)
        ];
    }
}
