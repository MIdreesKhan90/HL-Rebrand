<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('price_heading', 255)->nullable();
            $table->text('price_per_item')->nullable();
            $table->longText('price_details')->nullable();
            $table->string('service_overview')->nullable();
            $table->string('service_suitable')->nullable();
            $table->string('service_not_include')->nullable();
            $table->string('service_collection')->nullable();
            $table->string('service_delivery')->nullable();
            $table->string('service_question')->nullable();
            $table->string('service_answer')->nullable();
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
        Schema::dropIfExists('prices');
    }
};
