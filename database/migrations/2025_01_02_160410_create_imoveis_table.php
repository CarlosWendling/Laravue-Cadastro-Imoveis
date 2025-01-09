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
        Schema::create('imoveis', function (Blueprint $table) {
            $table->id('inscricao_municipal');
            $table->string('tipo', 100);
            $table->double('area_terreno')->nullable();
            $table->double('area_edificacao')->nullable();
            $table->string('logadouro', 100);
            $table->integer('numero');
            $table->string('bairro', 100);
            $table->string('complemento', 100)->nullable();
            $table->boolean('situacao', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imoveis');
    }
};
