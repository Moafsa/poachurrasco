<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bbq_guides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('meat_type'); // beef, pork, chicken, lamb, etc.
            $table->string('cut_type'); // picanha, costela, fraldinha, etc.
            $table->text('seasoning'); // temperos utilizados
            $table->text('preparation_method'); // método de preparo
            $table->text('equipment_needed'); // equipamentos necessários
            $table->text('cooking_time'); // tempo de cocção
            $table->text('temperature_guide'); // guia de temperaturas
            $table->integer('calories_per_100g')->nullable();
            $table->text('tips'); // dicas especiais
            $table->text('serving_suggestions'); // sugestões de acompanhamentos
            $table->string('difficulty_level'); // easy, medium, hard
            $table->integer('servings')->nullable();
            $table->json('ingredients')->nullable(); // ingredientes em JSON
            $table->json('steps')->nullable(); // passos em JSON
            $table->string('image_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bbq_guides');
    }
};
