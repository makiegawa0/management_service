<?php

use App\Models\Bank;
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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('bank_code')->index();
        });

        $this->seedBanks();

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name')->index();
            $table->string('branch_name')->index();
            $table->string('branch_number')->index();
            $table->string('account_number')->index();
            $table->boolean('is_active')->index();
            $table->unsignedBigInteger('balance_limit')->default(500000)->index();
            $table->mediumText('login_credentials')->index();

            $table->unsignedBigInteger('bank_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('bank_id')->references('id')->on('banks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('banks');
    }

    private function seedBanks()
    {
        $banks = [
            Bank::MUFG => ['name' => 'MUFG', 'bank_code' => '0005'],
            Bank::SMBC => ['name' => 'SMBC', 'bank_code' => '0009'],
            Bank::RAKUTEN => ['name' => 'Rakuten', 'bank_code' => '0036'],
            Bank::MIZUHO => ['name' => 'Mizuho', 'bank_code' => '0001'],
            Bank::PAYPAY => ['name' => 'PayPay', 'bank_code' => '0033'],
        ];
        
        foreach ($banks as $id => $data) {
            DB::table('banks')->insert([
                'id' => $id,
                'name' => $data['name'],
                'bank_code' => $data['bank_code'],
            ]);
        }        
    }
};
