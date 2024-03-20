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
        Schema::table('practicians', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->string('emergency')->nullable();
            $table->string('head_quarter')->nullable();
        });

        Schema::table('establishments', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->string('emergency')->nullable();
            $table->string('head_quarter')->nullable();
        });

        Schema::table('specialities', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('practicians', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('emergency');
            $table->dropColumn('head_quarter');
        });

        Schema::table('establishments', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('emergency');
            $table->dropColumn('head_quarter');
        });

        Schema::table('specialities', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
