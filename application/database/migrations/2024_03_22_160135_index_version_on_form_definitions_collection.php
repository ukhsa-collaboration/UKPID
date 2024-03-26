<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::connection('mongodb')->drop('FormDefinitions');

        Schema::connection('mongodb')->table('FormDefinitions', function ($collection) {
            $collection->index('version', null, null, ['unique' => true]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::connection('mongodb')->table('FormDefinitions', function ($collection) {
            $collection->dropIndex('version_1');
        });
    }
};
