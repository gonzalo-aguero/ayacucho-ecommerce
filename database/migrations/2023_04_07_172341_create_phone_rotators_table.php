<?php

use App\Models\PhoneRotator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(PhoneRotator::table,function (Blueprint $table) {
            $table->id();
            $table->integer("lastPhoneNumber");
            $table->timestamps();
        });

        // This is used to keep the last phone number used to send the customer's order to the seller (it alternates between two seller's phones)
        DB::table(PhoneRotator::table)->insertOrIgnore([
            'id' => 1,
            'lastPhoneNumber' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(PhoneRotator::table);
    }
};
