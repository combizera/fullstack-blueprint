<?php

namespace Database\Factories;

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
    public function definition(): array
    {
        return [
            'firstName' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'lastName' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'email' => $this->faker->safeEmail(),
            'password' => $this->faker->password(),
        ];
    }
}
