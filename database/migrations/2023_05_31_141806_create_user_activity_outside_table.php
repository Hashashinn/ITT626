<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivityOutsideTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activity_outside', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('att_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('punch_in_time')->nullable();
            $table->string('punch_out_time')->nullable();
            $table->string('attachment', 255)->nullable();
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
        Schema::dropIfExists('user_activity_outside');
    }
}