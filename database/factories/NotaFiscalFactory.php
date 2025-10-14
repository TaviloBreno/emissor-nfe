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
            'numero' => $this->faker->numberBetween(1, 999999),
            'serie' => $this->faker->numberBetween(1, 999),
            'chave_acesso' => $this->faker->numerify('################'),
            'destinatario_nome' => $this->faker->company,
            'destinatario_cnpj' => $this->faker->numerify('##############'),
            'valor_total' => $this->faker->randomFloat(2, 10, 10000),
            'status' => 'rascunho',
            'protocolo_autorizacao' => null,
            'xml_gerado' => '<xml>exemplo</xml>',
        ];
    }

    /**
     * Indicate that the nota fiscal is autorizada.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function autorizada()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'autorizada',
                'protocolo_autorizacao' => $this->faker->numerify('###############'),
            ];
        });
    }

    /**
     * Indicate that the nota fiscal is assinada.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function assinada()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'assinada',
            ];
        });
    }

    /**
     * Indicate that the nota fiscal is cancelada.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelada()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelada',
            ];
        });
    }
}