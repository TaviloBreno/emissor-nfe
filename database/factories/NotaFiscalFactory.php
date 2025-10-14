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
            'numero' => $this->faker->unique()->numberBetween(1, 999999),
            'data_emissao' => $this->faker->date(),
            'tipo' => 'saida',
            'valor_total' => $this->faker->randomFloat(2, 10, 10000),
            'user_id' => \App\Models\User::factory(),
        ];
    }


}