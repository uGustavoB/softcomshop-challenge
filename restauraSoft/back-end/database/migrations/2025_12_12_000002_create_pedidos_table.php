<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('mesa_id')->constrained()->onDelete('restrict');
            $table->enum('status', ['pendente', 'em_preparo', 'pronto', 'entregue', 'cancelado', 'finalizado'])->default('pendente');
            $table->enum('tipo_pedido', ['local', 'delivery', 'retirada'])->default('local');
            $table->enum('forma_pagamento', ['dinheiro', 'cartao_credito', 'cartao_debito', 'pix', 'outros'])->nullable();
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->text('observacoes')->nullable();
            $table->timestamp('data_pedido')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
