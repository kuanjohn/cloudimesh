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
        Schema::create('vmspecs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('min_vcpu');
            $table->integer('max_vcpu');
            $table->string('inc_vcpu');
            $table->integer('min_vmem');
            $table->integer('max_vmem');
            $table->string('inc_vmem');
            $table->decimal('cost_vcpu', 10, 4);
            $table->decimal('cost_vmem', 10, 4);
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('vmspecs');
    }
};
