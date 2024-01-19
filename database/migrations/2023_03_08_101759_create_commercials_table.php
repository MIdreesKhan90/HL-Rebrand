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
        Schema::create('commercials', function (Blueprint $table) {
            $table->id();
            $table->string('banner_image');
            $table->longText('banner_heading');
            $table->string('rank_heading');
            $table->string('rank_text_copy');
            $table->string('rank_review_link_label');
            $table->string('rank_review_link_url');
            $table->string('services_heading');
            $table->longText('services');
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
        Schema::dropIfExists('commercials');
    }
};
