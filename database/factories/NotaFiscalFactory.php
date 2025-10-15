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
        $quantidade = $this->faker->numberBetween(1, 100);
        $valorUnitario = $this->faker->randomFloat(2, 10, 500);
        
        return [
            'numero' => $this->faker->unique()->numberBetween(1, 999999),
            'serie' => '001',
            'data_emissao' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'tipo' => 'saida',
            'natureza_operacao' => $this->faker->randomElement([
                'Venda de mercadorias',
                'Prestação de serviços',
                'Revenda de mercadorias',
                'Transferência de mercadorias'
            ]),
            'cliente_nome' => $this->faker->company(),
            'cliente_cnpj' => $this->faker->numerify('##.###.###/####-##'),
            'cliente_email' => $this->faker->companyEmail(),
            'cliente_endereco' => $this->faker->streetAddress(),
            'cliente_cidade' => $this->faker->city(),
            'cliente_uf' => $this->faker->stateAbbr(),
            'cliente_cep' => $this->faker->numerify('#####-###'),
            'produto_descricao' => $this->faker->randomElement([
                'Notebook Dell Inspiron',
                'Mouse Wireless Logitech',
                'Teclado Mecânico',
                'Monitor LED 24"',
                'Impressora Multifuncional',
                'Smartphone Samsung',
                'Tablet Apple iPad',
                'Fones de Ouvido Bluetooth'
            ]),
            'produto_codigo' => $this->faker->numerify('PROD###'),
            'quantidade' => $quantidade,
            'valor_unitario' => $valorUnitario,
            'valor_total' => $quantidade * $valorUnitario,
            'status' => $this->faker->randomElement(['pendente', 'aprovada', 'cancelada', 'rejeitada']),
            'chave_acesso' => $this->faker->numerify('####################################'),
            'numero_protocolo' => $this->faker->numerify('###############'),
            'observacoes' => $this->faker->optional()->sentence(),
            'user_id' => \App\Models\User::factory(),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }


}