<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('github_commands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('github_branches')->onDelete('cascade');
            $table->string('key'); // e.g. pull, install, build
            $table->text('command');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('github_commands');
    }
};
