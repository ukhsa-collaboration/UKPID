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
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('code_table_id');
            $table->string('name', 255);
            $table->text('additional_data')->nullable();
            $table->string('code', 255)->nullable();

            $table->foreign('code_table_id')
                ->references('id')
                ->on('code_tables')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('code_tables', function (Blueprint $table) {
            $table->foreign('default_id')
                ->references('id')
                ->on('codes')
                ->onDelete('set null')
                ->onUpdate('set null');
        });

        Artisan::call('db:seed', ['--class' => 'CodeSeeder']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('code_tables', function (Blueprint $table) {
            $table->dropForeign('code_tables_default_id_foreign');
        });

        Schema::table('codes', function (Blueprint $table) {
            $table->dropForeign('codes_code_table_id_foreign');
        });

        Schema::dropIfExists('codes');
    }
};
