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
            $table->longText('presentation')->nullable();
            $table->json('expertises')->nullable();
            $table->boolean('accepts_new_patients')->default(true);
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

        });
    }
};
