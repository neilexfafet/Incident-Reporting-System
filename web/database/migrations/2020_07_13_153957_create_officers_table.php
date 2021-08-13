<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('officers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->unsignedBigInteger('rank_id');
            $table->string('id_no');
            $table->string('badge_no');
            $table->string('email');
            $table->date('birthday');
            $table->string('gender');
            $table->string('address');
            $table->string('contact_no');
            $table->string('image')->default('TBD');
            $table->tinyInteger('is_active')->default('1');
            $table->string('status')->default('unassigned');
            $table->unsignedBigInteger('station_id')->nullable();
            $table->timestamps();

            $table->foreign('rank_id')->references('id')->on('ranks')->onDelete('cascade');
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('officers');
    }
}
