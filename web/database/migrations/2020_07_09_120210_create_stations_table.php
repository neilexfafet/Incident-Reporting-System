<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('station_name');
            $table->string('station_contactno')->nullable();;
            $table->string('location_name')->nullable();
            $table->double('location_lat',20,10)->nullable();
            $table->double('location_lng',20,10)->nullable();
            $table->string('image')->default('TBD');
            $table->tinyInteger('is_active')->default('1');
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
        Schema::dropIfExists('stations');
    }
}
