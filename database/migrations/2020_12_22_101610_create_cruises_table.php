<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCruisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cruises', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('parent')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('logo')->nullable();

            $table->text('description')->nullable();
            $table->longText('overview')->nullable();
            $table->text('activities')->nullable();

            $table->text('location')->default('');
            $table->text('services')->nullable();

            $table->text('gallery')->nullable();
            $table->string('country_code')->nullable();


            $table->string('checkin')->nullable();
            $table->string('checkout')->nullable();


            $table->tinyInteger('rate')->nullable();
            $table->boolean('status')->default(true);



            //SEO
            $table->string('seo_title')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('seo_robots')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('facebook_description')->nullable();
            $table->string('facebook_image')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();


            $table->string('banner')->nullable();
            $table->string('alt')->nullable();
            $table->string('thumb')->nullable();
            $table->string('thumb_alt')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('cruises');
    }
}
