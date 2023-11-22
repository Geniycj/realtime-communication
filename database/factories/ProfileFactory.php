<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return factory(App\Models\User::class)->create()->id;
            },
            'name' => $this->faker->firstName,
            'phone' => $this->faker->phoneNumber,
            'surname' => $this->faker->lastName,
            'middle_name' => $this->faker->firstName,
            'notification' => $this->faker->boolean,
        ];
    }
}
