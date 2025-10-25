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
        Schema::create('questionnaire_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('questionnaire_id')->constrained('questionnaires')->onDelete('cascade');
            $table->string('section')->index();
            $table->string('question_id')->index();
            $table->json('answer')->nullable();
            $table->text('answer_text')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Composite indexes for better query performance
            $table->index(['user_id', 'section']);
            $table->index(['user_id', 'question_id']);
            $table->unique(['user_id', 'questionnaire_id', 'question_id'], 'user_questionnaire_question_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_responses');
    }
};
