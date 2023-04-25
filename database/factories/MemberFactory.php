<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'birthdate' => fake()->date(),
            'reportSubject' => fake()->text(50),
            'country' => fake()->country(),
            'phone' => fake()->e164PhoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'about' => fake()->text(50),
            'company' => fake()->company(),
            'position' => fake()->jobTitle(),
        ];
    }
}
