<?php

use App\Models\AdminUserLevel;
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
        Schema::create('admin_user_levels', function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->string('name');
        });

        $this->seedAdminUserLevels();

        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('level_id');
            $table->rememberToken();
            $table->string('locale', 100)->default('en');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('level_id')->references('id')->on('admin_user_levels')->default(1);
        });

        $aRootUser = [
            'name' => 'root user',
            'email' => 'root@root.com',
            'password' => bcrypt('root'),
            'level_id' => AdminUserLevel::ROOT,
            'created_at' => now(),
        ];

        DB::table('admin_users')->insert($aRootUser);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users');
        Schema::dropIfExists('admin_user_levels');
    }

    private function seedAdminUserLevels()
    {
        $statuses = [
            AdminUserLevel::ADMIN => 'Admin',
            AdminUserLevel::VIEWER => 'Viewer',
            AdminUserLevel::OPERATOR => 'Operator',
            AdminUserLevel::CALLBACK_SETTER => 'Setter',
            AdminUserLevel::CALLBACK_MANAGER => 'Manager',
            AdminUserLevel::UPLOADER => 'Uploader',
            AdminUserLevel::ACCOUNTANT => 'Accountant',
            AdminUserLevel::RESTRICT => 'Restrict',
            AdminUserLevel::ROOT => 'Root',
        ];

        foreach ($statuses as $id => $name) {
            DB::table('admin_user_levels')->insert([
                'id' => $id,
                'name' => $name
            ]);
        }
    }
};
