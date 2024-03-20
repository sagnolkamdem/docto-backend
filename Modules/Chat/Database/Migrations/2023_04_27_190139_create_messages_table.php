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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('chat_id');
            $table->string('body');
            $table->integer('parent_id')->nullable();
            $table->boolean('deleted')->default(false);
            $table->string('deleted_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->enum('status', ['sent', 'received', 'read'])->default('sent');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('practicians')->onDelete('cascade');
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
