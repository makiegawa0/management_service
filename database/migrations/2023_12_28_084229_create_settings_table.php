<?php

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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('reverse_cb')->default(false)->index();
            $table->boolean('user_import')->default(false)->index();
            $table->boolean('user_individual_deletion')->default(false)->index();
            $table->boolean('user_deletion')->default(false)->index();
            $table->boolean('payment_check')->default(false)->index();
            $table->boolean('payment_request_check')->default(false)->index();
            $table->boolean('balance_check')->default(false)->index();
            $table->timestamps();
        });

        DB::table('settings')->insert([
            'created_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
