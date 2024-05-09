<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VehiculoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'placa' => $this->faker->bothify('??-####'),
            'color' => $this->faker->colorName(),
            'modelo' => $this->faker->name(),
            'url_foto_placa' => "",
            'cliente_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
