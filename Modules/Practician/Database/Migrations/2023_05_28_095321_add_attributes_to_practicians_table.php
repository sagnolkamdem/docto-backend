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
            $table->timestamp('phone_number_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
//            $table->unique('phone_number');
            $table->string('otp_code')->nullable();
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
            $table->dropColumn('phone_number_verified_at');
            $table->dropColumn('otp_code');
            $table->dropColumn('email_verified_at');
        });
    }
};
