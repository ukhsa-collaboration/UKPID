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
        Schema::create('code_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->boolean('user_definable')->default(false);
            $table->unsignedBigInteger('default_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_tables');
    }
};
