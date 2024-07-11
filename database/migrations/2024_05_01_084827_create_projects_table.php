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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('charge_code');
            $table->decimal('budget', 10, 4);
            $table->decimal('cost', 10, 4);
            $table->string('description');
            $table->foreignId('owner')->constrained('users');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->timestamp('timeline')->nullable(); // Adding the timeline column
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
        Schema::dropIfExists('projects');
    }
};
