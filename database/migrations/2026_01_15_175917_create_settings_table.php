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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        DB::table('settings')->insert([
            ['key' => 'app_name', 'value' => 'Telkom DMS', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_version', 'value' => '2.0.0', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_address', 'value' => 'Jl. Telekomunikasi No. 1, Bandung', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_phone', 'value' => '+62 22 1234567', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_email', 'value' => 'contact@telkom.co.id', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'developer_name', 'value' => 'Tim IT Telkom', 'created_at' => now(), 'updated_at' => now()],
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
