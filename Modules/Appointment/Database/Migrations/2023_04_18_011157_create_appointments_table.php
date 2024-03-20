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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id');
            $table->bigInteger('practician_id')->nullable();
            $table->bigInteger('establishment_id')->nullable();
            $table->bigInteger('address_id')->nullable();
            $table->bigInteger('canceled_by')->nullable();
            $table->string('motif')->nullable();
            $table->string('mode')->nullable();
            $table->boolean('first_time')->nullable();
            $table->string('status')->default(\Modules\Appointment\Enums\Status::NEW);
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->json('payload')->nullable();
            $table->json('time_slot')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
