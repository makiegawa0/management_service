<?php

use App\Models\PayinStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private string $payinStatusesTableName = 'payin_statuses';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->payinStatusesTableName, function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->string('name');
        });

        $this->seedPayinStatuses();

        Schema::create('payins', function (Blueprint $table) {
            $table->id();

            $table->string('date');
            $table->string('input')->index();
            $table->unsignedBigInteger('amount')->index();
            $table->string('remainder');
            $table->string('deposit_manage_id')->unique();
            
            $table->unsignedBigInteger('status_id')->index();
            $table->unsignedBigInteger('bank_account_id')->index();
            $table->unsignedBigInteger('payment_request_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
            $table->foreign('status_id')->references('id')->on($this->payinStatusesTableName);
            $table->foreign('payment_request_id')->references('id')->on('payment_requests');
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts');
        });

        Schema::table('payment_requests', function (Blueprint $table) {
            $table->foreign('payin_id')->references('id')->on('payins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payins');
        Schema::dropIfExists($this->payinStatusesTableName);
    }

    private function seedPayinStatuses()
    {
        $statuses = [
            PayinStatus::UNPROCESSED => 'Unprocessed',
            PayinStatus::AUTO => 'Auto',
            PayinStatus::MANUAL => 'Manual',
            PayinStatus::ALERT => 'Alert',
            PayinStatus::BLOCKED => 'Blocked',
            PayinStatus::CLOSED => 'Closed',
        ];

        foreach ($statuses as $id => $name) {
            DB::table($this->payinStatusesTableName)->insert([
                'id' => $id,
                'name' => $name
            ]);
        }
    }
};
