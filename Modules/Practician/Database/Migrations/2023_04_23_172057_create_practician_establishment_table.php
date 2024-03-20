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
        Schema::create('establishment_practician', function (Blueprint $table) {
            $table->unsignedBigInteger('practician_id');
            $table->unsignedBigInteger('establishment_id');
            $table->timestamps();

            $table->foreign('establishment_id')
                ->references('id')
                ->on('establishments');
            $table->foreign('practician_id')
                ->references('id')
                ->on('practicians');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('establishment_practician');
    }
};
