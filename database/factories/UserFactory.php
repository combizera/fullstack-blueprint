<?php

namespace Database\Factories;

use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */

    /**
     * Get a new Faker instance.
     *
     * @return Generator
     */
    public function withFaker()
    {
        return \Faker\Factory::create('pt_BR');
    }
    public function definition(): array
    {
        return [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->faker->safeEmail(),
            'password' => $this->faker->password(),
        ];
    }
}
