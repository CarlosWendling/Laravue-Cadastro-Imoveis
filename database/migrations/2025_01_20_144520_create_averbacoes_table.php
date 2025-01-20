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
        Schema::create('averbacoes', function (Blueprint $table) {
            $table->id();
            $table->string('evento');
            $table->double('medida')->nullable();
            $table->longText('descricao');
            $table->date('data_averbacao');
            $table->foreignId('inscricao_municipal_imovel')
                ->constrained('imoveis', 'inscricao_municipal')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('averbacoes');
    }
};
