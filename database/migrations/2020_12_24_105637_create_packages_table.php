<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('destination_id');
//            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade');
            $table->bigInteger('category_id');
//            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->string('country_code');


            $table->bigInteger('days')->nullable();
            $table->bigInteger('start_price')->nullable();

            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(true);

             $table->longText('overview')->nullable();
            // Package Itinerary

            $table->text('day_data')->nullable();
            $table->text('travel_experiences')->nullable();
            $table->text('prices')->nullable();
            $table->text('included')->nullable();
            $table->text('excluded')->nullable();
            $table->text('highlight')->nullable();
            $table->text('hotels')->nullable();

            $table->integer('duration')->nullable();
            $table->integer('likes')->nullable()->default(0);
            $table->integer('travellers')->nullable();
            $table->integer('from')->default(0);
            $table->integer('discount')->nullable();
            $table->integer('old_price')->default(0);


            $table->text('location_package_map')->nullable();

            $table->text('videos')->nullable();

            $table->text('remarks')->nullable();
            $table->text('location_description')->nullable();
            $table->text('location_url')->nullable();

            $table->date('expired_at')->nullable();


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
        Schema::dropIfExists('packages');
    }
}
