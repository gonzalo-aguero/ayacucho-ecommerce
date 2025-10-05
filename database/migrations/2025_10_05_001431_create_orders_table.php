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
        Schema::create('orders', function (Blueprint $table) {
            // Primary key optimizada con UUID para mejor distribución
            $table->uuid('id')->primary();

            // Datos del cliente
            $table->string('customer_name', 50);
            $table->string('customer_dni', 12)->index(); // Índice para búsquedas frecuentes
            $table->string('customer_email', 50)->nullable();
            $table->string('customer_phone', 18);
            $table->string('customer_city', 50);
            $table->string('customer_address', 255);

            // Datos del pedido
            $table->string('shipping_zone', 100);
            $table->string('payment_method', 100);
            $table->text('customer_note')->nullable();

            // Productos del carrito (JSON para flexibilidad)
            $table->json('cart_items');

            // Totales (como valores decimales para cálculos precisos)
            $table->decimal('cart_total', 11, 2);
            $table->decimal('shipping_cost', 11, 2);
            $table->decimal('order_total', 11, 2);

            // Datos del sistema
            $table->string('company_phone_used', 20);
            $table->string('whatsapp_url', 2000); // URL generada

            // Timestamps optimizadas
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Índices para consultas optimizadas
            $table->index('created_at'); // Para búsquedas por fecha
            $table->index(['customer_phone', 'created_at']); // Para historial de cliente
            $table->index('payment_method'); // Para reportes por método de pago
            $table->index('shipping_zone'); // Para reportes por zona
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
