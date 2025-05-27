<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('github_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repository_id')->constrained('github_repositories')->onDelete('cascade');
            $table->string('name');
            $table->string('path');
            $table->string('runner')->default('raw');
            $table->json('env')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('github_branches');
    }
};
