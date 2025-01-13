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
        Schema::table('arquivos', function (Blueprint $table) {
            // Adiciona a coluna como chave estrangeira
            $table->foreignId('inscricao_municipal_imovel')
                ->constrained('imoveis', 'inscricao_municipal')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arquivos', function (Blueprint $table) {
            // Remove a chave estrangeira e a coluna associada
            $table->dropForeign(['inscricao_municipal_imovel']); 
            $table->dropColumn('inscricao_municipal_imovel');
        });
    }
};
