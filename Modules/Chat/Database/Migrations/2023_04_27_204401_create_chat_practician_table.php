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
        Schema::create('chat_practician', function (Blueprint $table) {
            $table->unsignedBigInteger('practician_id');
            $table->unsignedBigInteger('chat_id');
            $table->timestamps();

            $table->foreign('practician_id')->references('id')->on('practicians');
            $table->foreign('chat_id')->references('id')->on('chats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_practician');
    }
};
