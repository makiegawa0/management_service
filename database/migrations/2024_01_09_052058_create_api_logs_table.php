<?php

use App\Models\ApiLogStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private string $apiLogStatusesTableName = 'api_log_statuses';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->apiLogStatusesTableName, function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->string('name');
        });

        $this->seedApiLogStatuses();

        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->text('request')->index();
            // $table->text('send_jwt_json');
            // $table->text('send_result')->index();
            // $table->string('exp_id')->nullable();
            
            $table->text('response')->index();

            $table->unsignedBigInteger('status_id')->index();
            $table->unsignedBigInteger('payment_request_id')->index();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
            $table->foreign('status_id')->references('id')->on($this->apiLogStatusesTableName);
            $table->foreign('payment_request_id')->references('id')->on('payment_requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
        Schema::dropIfExists($this->apiLogStatusesTableName);
    }

    private function seedApiLogStatuses()
    {
        $statuses = [
            ApiLogStatus::PENDING => 'Pending',
            ApiLogStatus::CONFIRMED => 'Confirmed',
        ];

        foreach ($statuses as $id => $name) {
            DB::table($this->apiLogStatusesTableName)->insert([
                'id' => $id,
                'name' => $name
            ]);
        }
    }
};
