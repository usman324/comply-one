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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workspace_id');
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->unsignedBigInteger('uploaded_by_user_id');

            // File Info
            $table->string('original_name', 255);
            $table->string('display_name', 255);
            $table->string('file_path', 255);
            $table->bigInteger('file_size'); // in bytes
            $table->string('mime_type', 100)->nullable();
            $table->string('extension', 20)->nullable();

            // Metadata
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_starred')->default(false);

            // Status
            $table->enum('status', ['active', 'archived', 'deleted'])->default('active');

            // Timestamps
             $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            

            // Indexes
            $table->index(['workspace_id', 'folder_id']);
            $table->fullText(['original_name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
