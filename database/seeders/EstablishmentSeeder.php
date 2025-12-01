<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Establishment;
use App\Models\User;

class EstablishmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user if it doesn't exist
        $user = User::firstOrCreate(
            ['email' => 'teste@exemplo.com'],
            [
                'name' => 'Usuário Teste',
                'password' => bcrypt('password'),
            ]
        );

        // Create sample establishments
        $establishments = [
            [
                'name' => 'Churrascaria Gaúcha Tradicional',
                'description' => 'A melhor churrascaria de Porto Alegre, com mais de 30 anos de tradição. Carnes selecionadas e temperos especiais.',
                'address' => 'Av. Ipiranga, 1234',
                'city' => 'Porto Alegre',
                'state' => 'RS',
                'zip_code' => '90160-000',
                'phone' => '(51) 3333-4444',
                'email' => 'contato@churrascariagaucha.com',
                'website' => 'https://www.churrascariagaucha.com',
                'latitude' => -30.0346,
                'longitude' => -51.2177,
                'category' => 'churrascaria',
                'status' => 'active',
                'is_featured' => true,
                'is_verified' => true,
                'rating' => 4.8,
                'review_count' => 156,
                'view_count' => 2340,
                'opening_hours' => [
                    'monday' => ['open' => '11:00', 'close' => '23:00'],
                    'tuesday' => ['open' => '11:00', 'close' => '23:00'],
                    'wednesday' => ['open' => '11:00', 'close' => '23:00'],
                    'thursday' => ['open' => '11:00', 'close' => '23:00'],
                    'friday' => ['open' => '11:00', 'close' => '00:00'],
                    'saturday' => ['open' => '11:00', 'close' => '00:00'],
                    'sunday' => ['open' => '11:00', 'close' => '22:00'],
                ],
                'payment_methods' => ['dinheiro', 'cartao_debito', 'cartao_credito', 'pix'],
                'amenities' => ['estacionamento', 'wifi', 'ar_condicionado', 'acessibilidade'],
            ],
            [
                'name' => 'Açougue Premium do Centro',
                'description' => 'Carnes de primeira qualidade, cortes especiais e atendimento personalizado. O melhor açougue da região central.',
                'address' => 'Rua da Praia, 567',
                'city' => 'Porto Alegre',
                'state' => 'RS',
                'zip_code' => '90010-000',
                'phone' => '(51) 3222-3333',
                'email' => 'vendas@acouguedocentro.com',
                'latitude' => -30.0320,
                'longitude' => -51.2300,
                'category' => 'açougue',
                'status' => 'active',
                'is_featured' => false,
                'is_verified' => true,
                'rating' => 4.6,
                'review_count' => 89,
                'view_count' => 1200,
                'opening_hours' => [
                    'monday' => ['open' => '08:00', 'close' => '19:00'],
                    'tuesday' => ['open' => '08:00', 'close' => '19:00'],
                    'wednesday' => ['open' => '08:00', 'close' => '19:00'],
                    'thursday' => ['open' => '08:00', 'close' => '19:00'],
                    'friday' => ['open' => '08:00', 'close' => '20:00'],
                    'saturday' => ['open' => '08:00', 'close' => '18:00'],
                    'sunday' => ['closed' => true],
                ],
                'payment_methods' => ['dinheiro', 'cartao_debito', 'cartao_credito', 'pix'],
                'amenities' => ['estacionamento', 'delivery'],
            ],
            [
                'name' => 'Supermercado Churrasco & Cia',
                'description' => 'Supermercado especializado em produtos para churrasco. Carnes, temperos, acessórios e tudo que você precisa.',
                'address' => 'Av. Bento Gonçalves, 890',
                'city' => 'Porto Alegre',
                'state' => 'RS',
                'zip_code' => '90550-000',
                'phone' => '(51) 3444-5555',
                'email' => 'info@churrascoecia.com',
                'latitude' => -30.0500,
                'longitude' => -51.2000,
                'category' => 'supermercado',
                'status' => 'active',
                'is_featured' => true,
                'is_verified' => true,
                'rating' => 4.4,
                'review_count' => 203,
                'view_count' => 3100,
                'opening_hours' => [
                    'monday' => ['open' => '07:00', 'close' => '22:00'],
                    'tuesday' => ['open' => '07:00', 'close' => '22:00'],
                    'wednesday' => ['open' => '07:00', 'close' => '22:00'],
                    'thursday' => ['open' => '07:00', 'close' => '22:00'],
                    'friday' => ['open' => '07:00', 'close' => '23:00'],
                    'saturday' => ['open' => '07:00', 'close' => '23:00'],
                    'sunday' => ['open' => '08:00', 'close' => '20:00'],
                ],
                'payment_methods' => ['dinheiro', 'cartao_debito', 'cartao_credito', 'pix', 'vale_alimentacao'],
                'amenities' => ['estacionamento', 'wifi', 'ar_condicionado', 'acessibilidade', 'delivery'],
            ],
        ];

        foreach ($establishments as $establishmentData) {
            Establishment::create(array_merge($establishmentData, [
                'user_id' => $user->id,
            ]));
        }
    }
}