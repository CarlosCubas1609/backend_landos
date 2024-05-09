<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'documento' => $this->faker->bothify('########'),
            'nombres' => $this->faker->name(),
            'apellido_paterno' => $this->faker->lastName(),
            'apellido_materno' => $this->faker->lastName(),
            'celular' => $this->faker->bothify('#########'),
            'direccion' => $this->faker->address(),
            'genero_id' => rand(1,2),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
