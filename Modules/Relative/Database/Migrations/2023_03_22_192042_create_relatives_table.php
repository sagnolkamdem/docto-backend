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
        Schema::create('relatives', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id');
            $table->string('type');
            $table->string('gender')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('address')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->boolean('is_patient')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relatives');
    }
};
