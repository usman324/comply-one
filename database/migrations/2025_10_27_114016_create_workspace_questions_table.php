<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workspace_questions', function (Blueprint $table) {

            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->integer('order')->default(0); // Order of question in workspace
            $table->boolean('is_required')->default(false); // Override question requirement per workspace
            $table->json('workspace_specific_options')->nullable(); // Workspace-specific configurations
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index('workspace_id');
            $table->index('question_id');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_questions');
    }
};
