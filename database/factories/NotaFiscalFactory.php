<?php

namespace Database\Factories;

use App\Models\NotaFiscal;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotaFiscalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NotaFiscal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'numero' => $this->faker->unique()->numberBetween(100000, 999999),
            'data_emissao' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'tipo' => $this->faker->randomElement(['entrada', 'saida']),
            'valor_total' => $this->faker->randomFloat(2, 50, 5000),
            'user_id' => \App\Models\User::factory(),
        ];
    }


}