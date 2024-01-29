<?php

use App\Models\UserAlertLevel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_alert_levels', function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->string('name');
        });

        $this->seedAlertLevels();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_code')->unique();
            $table->string('email')->index();
            $table->string('telephone')->nullable();
            $table->string('first_name')->index();
            $table->string('last_name')->index();
            $table->string('payment_code')->unique();
            $table->string('hurigana')->nullable();
            $table->string('kyc', 8)->nullable();
            $table->string('user_kind', 8)->nullable();
            $table->unsignedBigInteger('alert_level_id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
            $table->foreign('alert_level_id')->references('id')->on('user_alert_levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_alert_levels');
    }

    private function seedAlertLevels()
    {
        $levels = [
            UserAlertLevel::NORMAL => 'Normal',
            UserAlertLevel::ALERT => 'Alert',
            UserAlertLevel::BLOCK => 'Block',
        ];

        foreach ($levels as $id => $name) {
            DB::table('user_alert_levels')->insert([
                'id' => $id,
                'name' => $name
            ]);
        }
    }
};
