<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id');
//            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('page_title');
            $table->text('description');

            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(true);
            $table->text('gallery')->nullable();

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
        Schema::dropIfExists('pages');
    }
}
