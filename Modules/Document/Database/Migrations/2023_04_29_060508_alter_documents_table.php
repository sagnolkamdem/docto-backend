<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->boolean('created_by_practician')->default(false);
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->boolean('created_by_practician')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('created_by_practician');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('created_by_practician');
        });
    }
};
