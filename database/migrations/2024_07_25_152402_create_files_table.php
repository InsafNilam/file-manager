<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('folder');
            $table->text('path');
            $table->string('type')->default('undefined');
            $table->string('version')->default('V0');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    //2024_07_25_152218
    // 2024_07_25_152402

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
