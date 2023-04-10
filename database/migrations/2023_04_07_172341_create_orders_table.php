<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //private $table_name = "orders";
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Order::table,function (Blueprint $table) {
            $table->id();
            $table->integer("lastPhoneNumber");
            $table->timestamps();
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
