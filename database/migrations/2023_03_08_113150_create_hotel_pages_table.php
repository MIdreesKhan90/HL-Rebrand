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
        Schema::create('hotel_page', function (Blueprint $table) {
            $table->id();
            $table->string('banner_image');
            $table->longText('banner_heading');
            $table->longText('text_copy');
            $table->string('how_we_work_heading');
            $table->longText('processes');
            $table->string('services_heading');
            $table->longText('services');
            $table->string('services_link_label');
            $table->string('services_link_url');
            $table->string('minimum_order_text');
            $table->string('faq_heading');
            $table->longText('faqs');
            $table->string('faqs_link_label');
            $table->string('faqs_link_url');
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
        Schema::dropIfExists('hotel_pages');
    }
};
