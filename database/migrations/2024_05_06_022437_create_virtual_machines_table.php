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
        Schema::create('virtual_machines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('vcpu');
            $table->integer('vmem');
            // $table->decimal('cost_vcpu', 10, 4);
            // $table->decimal('cost_vmem', 10, 4);
            $table->decimal('add_storage', 10, 4);
            $table->foreignId('location_id')->constrained('locations');
            $table->foreignId('environment_id')->constrained('environments');
            $table->foreignId('tier_id')->constrained('tiers');
            $table->foreignId('operating_system_id')->constrained('operating_systems');
            $table->foreignId('storage_id')->constrained('storages');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_machines');
    }
};
