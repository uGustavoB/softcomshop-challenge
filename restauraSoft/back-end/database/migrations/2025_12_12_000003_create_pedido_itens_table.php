<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->onDelete('cascade');
            $table->foreignId('prato_id')->constrained()->onDelete('restrict');
            $table->integer('quantidade');
            $table->decimal('preco_unitario', 10, 2);
            $table->text('observacoes')->nullable();
            $table->enum('status_item', ['pendente', 'em_preparo', 'pronto', 'entregue', 'cancelado'])->default('pendente');
            $table->timestamps();
        });

        Schema::table('pedido_itens', function (Blueprint $table) {
            $table->index('pedido_id');
            $table->index('prato_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_itens');
    }
}
