<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'title' => $this->faker->jobTitle(),
            'email' => $this->faker->email(),
            'about' => $this->faker->realText(),
            'image' => '0',
            'order' => 99,
        ];
    }
}
