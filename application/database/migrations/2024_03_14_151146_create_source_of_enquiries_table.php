<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('source_of_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->boolean('hidden')->default(false);
        });

        Artisan::call('db:seed', ['--class' => 'SourceOfEnquirySeeder']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('source_of_enquiries');
    }
};
