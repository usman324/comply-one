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
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workspace_id');
            $table->unsignedBigInteger('parent_folder_id')->nullable();
            $table->unsignedBigInteger('created_by_user_id');

            // Folder Info
            $table->string('name', 255);
            $table->text('description')->nullable();

            // Status
            $table->enum('status', ['active', 'archived', 'deleted'])->default('active');

            // Timestamps
           $table->timestamps();
           $table->softDeletes();

            // Foreign Keys
            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            $table->foreign('parent_folder_id')->references('id')->on('folders')->onDelete('cascade');
            $table->foreign('created_by_user_id')->references('id')->on('users');

            // Indexes
            $table->index(['workspace_id', 'parent_folder_id']);
            // $table->unique(['workspace_id', 'parent_folder_id', 'name'], 'unique_folder_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};
