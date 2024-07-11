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
        Schema::create('app_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vendor');
            $table->string('version');
            $table->boolean('published')->default(true);
            $table->decimal('cost', 10, 4);
            $table->string('cost_type');
            $table->integer('min_disk');
            $table->integer('min_vcpu');
            $table->integer('min_vmem');
            $table->string('platform');
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
        Schema::dropIfExists('app_services');
    }
};
