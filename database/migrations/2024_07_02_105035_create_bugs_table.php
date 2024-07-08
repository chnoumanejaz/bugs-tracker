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
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description')->nullable(true);
            $table->date('deadline');
            $table->string('screenshot')->nullable(true);
            $table->enum('type', ['Feature', 'Bug']);
            $table->enum('status', ['New','Started', 'Completed', 'Resolved']);
            $table->string('project_id');
            $table->string('qa_id');
            $table->string('developer_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bugs');
    }
};
