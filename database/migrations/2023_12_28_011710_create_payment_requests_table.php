<?php

use App\Models\PaymentRequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private string $paymentRequestStatusesTableName = 'payment_request_statuses';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->paymentRequestStatusesTableName, function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->string('name');
        });

        $this->seedPaymentRequestStatuses();

        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('payment_request_unique_code')->unique();
            $table->unsignedBigInteger('amount')->index();
            $table->timestamp('callback_sent_at')->nullable()->index();
            $table->unsignedBigInteger('status_id')->index();
            $table->unsignedBigInteger('payin_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
            $table->foreign('status_id')->references('id')->on($this->paymentRequestStatusesTableName);
            // $table->foreign('payin_id')->references('id')->on('payins');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
        Schema::dropIfExists($this->paymentRequestStatusesTableName);
    }

    private function seedPaymentRequestStatuses()
    {
        $statuses = [
            PaymentRequestStatus::UNPROCESSED => 'Unprocessed',
            PaymentRequestStatus::AUTO => 'Auto',
            PaymentRequestStatus::MANUAL => 'Manual',
            PaymentRequestStatus::FAILED => 'Failed',
        ];

        foreach ($statuses as $id => $name) {
            DB::table($this->paymentRequestStatusesTableName)->insert([
                'id' => $id,
                'name' => $name
            ]);
        }
    }
};
