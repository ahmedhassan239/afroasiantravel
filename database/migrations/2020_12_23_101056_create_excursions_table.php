<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcursionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excursions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('category_id');
//            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->bigInteger('destination_id');
//            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade');

            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('country_code')->nullable();

            $table->string('departure_location')->nullable();
            $table->string('arrive_location')->nullable();


            $table->text('description')->nullable();
            $table->longText('overview')->nullable();

            $table->text('included')->nullable();
            $table->text('excluded')->nullable();

            $table->integer('likes')->nullable()->default(0);

            $table->integer('duration')->nullable();

            $table->integer('travellers')->nullable();
            $table->integer('from')->default(0)->nullable();
            $table->integer('discount')->nullable()->nullable();
            $table->integer('old_price')->default(0)->nullable();
            $table->text('remarks')->nullable();

            $table->boolean('status')->default(true);
            $table->string('label')->nullable();
            $table->boolean('featured')->default(false);

            $table->string('location')->nullable();
            $table->text('location_description')->nullable();
            $table->text('location_url')->nullable();
            $table->text('map_url')->nullable();



            $table->integer('price_1')->default(0);
            $table->integer('price_2_3')->default(0);
            $table->integer('price_4_6')->default(0);
            $table->integer('price_7_10')->default(0);
            $table->integer('price_11')->default(0);
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
            $table->string('gallery')->nullable();

            $table->string('banner')->nullable();
            $table->string('alt')->nullable();
            $table->string('thumb')->nullable();
            $table->string('thumb_alt')->nullable();


            $table->softDeletes();
            $table->timestamps();
            $table->date('expired_at')->default(null)->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('excursions');
    }
}
