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
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('section_id')->index();
            // $table->string('section')->index();
            $table->string('question_id')->nullable()->unique();
            $table->string('title');
            $table->string('status');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->text('message')->nullable();
            $table->enum('type', ['select', 'textarea', 'text', 'checkbox', 'radio'])->default('select');
            $table->json('options')->nullable();
            $table->boolean('required')->default(false);
            $table->boolean('allow_anonymous')->default(false);
            $table->boolean('allow_multiple_responses')->default(false);
            $table->boolean('show_progress')->default(false);
            $table->boolean('randomize_questions')->default(false);
            $table->string('placeholder')->nullable();
            $table->json('depends_on')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaires');
    }
};
