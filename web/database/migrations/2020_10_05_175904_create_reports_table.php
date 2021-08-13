<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id');
            $table->longText('description');
            $table->string('location')->nullable();
            $table->double('location_lat',20,10)->nullable();
            $table->double('location_lng',20,10)->nullable();
            $table->unsignedBigInteger('reporter_id');
            $table->unsignedBigInteger('station_id');
            $table->timestamp('incident_date')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('incident_id')->references('id')->on('incidents')->onDelete('cascade');
            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('reports');
    }
}
