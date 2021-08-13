<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_logs', function (Blueprint $table) {
            $table->id();
            $table->string('activity');
            $table->unsignedBigInteger('report_id')->nullable();
            $table->unsignedBigInteger('dispatch_id')->nullable();
            $table->timestamps();
            
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('dispatch_id')->references('id')->on('dispatches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_logs');
    }
}
