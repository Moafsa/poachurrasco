<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BbqGuide;

class BbqGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guides = [
            [
                'title' => 'Picanha Perfeita - Tradição Gaúcha',
                'description' => 'Aprenda a fazer a picanha perfeita seguindo a tradição gaúcha. Corte, tempero, cocção e ponto ideal.',
                'meat_type' => 'bovina',
                'cut_type' => 'picanha',
                'seasoning' => 'Sal grosso, alho, ervas finas',
                'preparation_method' => 'Tempere com sal grosso 2 horas antes. Grelhe em fogo alto por 3-4 minutos cada lado.',
                'equipment_needed' => 'Churrasqueira, espetos, faca afiada, termômetro',
                'cooking_time' => '15-20 minutos',
                'temperature_guide' => 'Fogo alto inicial, depois médio',
                'calories_per_100g' => 250,
                'tips' => 'Não vire a carne muitas vezes. Deixe descansar 5 minutos antes de cortar.',
                'serving_suggestions' => 'Farofa de mandioca, vinagrete, pão de alho',
                'difficulty_level' => 'medium',
                'servings' => 6,
                'ingredients' => [
                    '1 peça de picanha (1,5kg)',
                    'Sal grosso a gosto',
                    '2 dentes de alho',
                    'Ervas finas',
                    'Azeite'
                ],
                'steps' => [
                    'Tempere a picanha com sal grosso 2 horas antes',
                    'Prepare a churrasqueira com carvão',
                    'Grelhe a picanha em fogo alto por 3-4 minutos cada lado',
                    'Reduza o fogo e continue grelhando por 10-12 minutos',
                    'Deixe descansar 5 minutos antes de cortar'
                ],
                'is_featured' => true,
            ],
            [
                'title' => 'Costela de Boi - Low & Slow',
                'description' => 'Técnica de cocção lenta para costela macia e saborosa. Segredo do tempo e temperatura.',
                'meat_type' => 'bovina',
                'cut_type' => 'costela',
                'seasoning' => 'Sal grosso, pimenta do reino, alho',
                'preparation_method' => 'Cocção lenta em fogo baixo por 4-6 horas. Temperatura controlada.',
                'equipment_needed' => 'Churrasqueira, termômetro, papel alumínio',
                'cooking_time' => '4-6 horas',
                'temperature_guide' => 'Fogo baixo constante (120-140°C)',
                'calories_per_100g' => 280,
                'tips' => 'Use papel alumínio para manter a umidade. Verifique a temperatura constantemente.',
                'serving_suggestions' => 'Batata doce assada, salada verde, molho barbecue',
                'difficulty_level' => 'hard',
                'servings' => 8,
                'ingredients' => [
                    '1 peça de costela (2kg)',
                    'Sal grosso',
                    'Pimenta do reino',
                    'Alho em pó',
                    'Papel alumínio'
                ],
                'steps' => [
                    'Tempere a costela com sal, pimenta e alho',
                    'Prepare fogo baixo na churrasqueira',
                    'Coloque a costela na grelha',
                    'Mantenha temperatura constante por 4-6 horas',
                    'Envolva em papel alumínio nos últimos 30 minutos'
                ],
                'is_featured' => true,
            ],
            [
                'title' => 'Frango Grelhado com Ervas',
                'description' => 'Frango grelhado temperado com ervas frescas. Simples, saboroso e perfeito para iniciantes.',
                'meat_type' => 'frango',
                'cut_type' => 'frango inteiro',
                'seasoning' => 'Sal, pimenta, alecrim, tomilho, alho',
                'preparation_method' => 'Marinada por 2 horas. Grelhado em fogo médio.',
                'equipment_needed' => 'Churrasqueira, espeto, pincel',
                'cooking_time' => '45-60 minutos',
                'temperature_guide' => 'Fogo médio',
                'calories_per_100g' => 165,
                'tips' => 'Marine bem o frango. Use espeto para cocção uniforme.',
                'serving_suggestions' => 'Arroz, salada, batata assada',
                'difficulty_level' => 'easy',
                'servings' => 4,
                'ingredients' => [
                    '1 frango inteiro (1,5kg)',
                    'Sal a gosto',
                    'Pimenta do reino',
                    'Alecrim fresco',
                    'Tomilho',
                    'Alho',
                    'Azeite'
                ],
                'steps' => [
                    'Limpe e tempere o frango',
                    'Prepare a marinada com ervas',
                    'Deixe marinar por 2 horas',
                    'Grelhe em fogo médio por 45-60 minutos',
                    'Vire ocasionalmente para cocção uniforme'
                ],
                'is_featured' => false,
            ],
            [
                'title' => 'Fraldinha com Chimichurri',
                'description' => 'Fraldinha grelhada servida com chimichurri caseiro. Combinação perfeita de sabores.',
                'meat_type' => 'bovina',
                'cut_type' => 'fraldinha',
                'seasoning' => 'Sal grosso, chimichurri',
                'preparation_method' => 'Grelhada rápida em fogo alto. Chimichurri por cima.',
                'equipment_needed' => 'Churrasqueira, processador',
                'cooking_time' => '8-12 minutos',
                'temperature_guide' => 'Fogo alto',
                'calories_per_100g' => 240,
                'tips' => 'Não cozinhe demais. A fraldinha deve ficar mal passada.',
                'serving_suggestions' => 'Chimichurri, batata assada, salada',
                'difficulty_level' => 'medium',
                'servings' => 4,
                'ingredients' => [
                    '800g de fraldinha',
                    'Sal grosso',
                    'Chimichurri',
                    'Salsa',
                    'Cebola',
                    'Vinagre',
                    'Azeite'
                ],
                'steps' => [
                    'Tempere a fraldinha com sal grosso',
                    'Prepare o chimichurri',
                    'Grelhe em fogo alto por 4-6 minutos cada lado',
                    'Deixe descansar 3 minutos',
                    'Sirva com chimichurri por cima'
                ],
                'is_featured' => true,
            ],
            [
                'title' => 'Linguiça Toscana Grelhada',
                'description' => 'Linguiça toscana grelhada com temperos especiais. Prática e saborosa.',
                'meat_type' => 'suina',
                'cut_type' => 'linguiça',
                'seasoning' => 'Sal, pimenta, ervas',
                'preparation_method' => 'Grelhada em fogo médio. Furos para não estourar.',
                'equipment_needed' => 'Churrasqueira, garfo',
                'cooking_time' => '15-20 minutos',
                'temperature_guide' => 'Fogo médio',
                'calories_per_100g' => 320,
                'tips' => 'Faça pequenos furos na linguiça para não estourar.',
                'serving_suggestions' => 'Pão, mostarda, cebola',
                'difficulty_level' => 'easy',
                'servings' => 6,
                'ingredients' => [
                    '1kg de linguiça toscana',
                    'Sal',
                    'Pimenta',
                    'Ervas finas'
                ],
                'steps' => [
                    'Faça pequenos furos na linguiça',
                    'Tempere com sal e pimenta',
                    'Grelhe em fogo médio',
                    'Vire ocasionalmente',
                    'Sirva quente'
                ],
                'is_featured' => false,
            ],
        ];

        foreach ($guides as $guide) {
            BbqGuide::create($guide);
        }
    }
}
