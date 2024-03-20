<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_official')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_enabled')->default(false);
            $table->string('cca2')->nullable();
            $table->string('cca3')->nullable();
            $table->string('flag')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('currencies')->nullable();
            $table->json('callingCodes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
};
