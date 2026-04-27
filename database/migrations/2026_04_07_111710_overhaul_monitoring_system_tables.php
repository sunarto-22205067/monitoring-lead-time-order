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
        // Drop unused tables
        Schema::dropIfExists('incident_logs');
        Schema::dropIfExists('incident_attachments');
        Schema::dropIfExists('incident_reports');
        Schema::dropIfExists('document_versions');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('folders');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('teams');

        // Modify orders table (Recreate for clean schema)
        Schema::dropIfExists('orders');
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('project_name');
            $table->string('design_type')->nullable(); // PT2-Expand, PT2, PT3, PT4
            $table->string('status_project')->default('active'); // active, overdue, completed
            
            // Program info
            $table->string('category')->nullable();
            $table->string('program_type')->nullable();
            $table->string('program_year')->nullable();
            
            // Region info
            $table->string('regional')->nullable();
            $table->string('witel')->nullable();
            $table->string('datel')->nullable();
            $table->string('sto')->nullable();
            $table->string('wok')->nullable();
            
            // Executing parties
            $table->string('mitra_name')->nullable();
            $table->string('surveyor')->nullable();
            
            // Technical data
            $table->integer('household_count')->default(0);
            $table->integer('odp_plan')->default(0);
            $table->integer('odp_realization')->default(0);
            $table->integer('port_plan')->default(0);
            $table->integer('port_realization')->default(0);
            $table->text('alpro_data')->nullable();
            
            // Financial data
            $table->decimal('capex_per_port', 15, 2)->default(0);
            $table->decimal('po_value', 15, 2)->default(0);
            $table->decimal('gr_value', 15, 2)->default(0);
            $table->decimal('ir_value', 15, 2)->default(0);
            
            // Timelines
            $table->date('estimated_go_live')->nullable();
            $table->date('actual_go_live')->nullable();
            $table->date('start_date')->nullable();
            $table->date('finish_installation_date')->nullable();
            
            // Durations (in days or specific units)
            $table->integer('duration_automation')->default(0);
            $table->integer('duration_review')->default(0);
            $table->integer('duration_approval')->default(0);
            $table->integer('duration_go_live')->default(0);
            
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Mitra Management
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('region')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Region Management (Datel/STO)
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Witel, Datel, STO
            $table->string('name');
            $table->string('parent_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
        Schema::dropIfExists('mitras');
        Schema::dropIfExists('orders');
    }
};
