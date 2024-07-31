<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('image')->default('default.png');
            $table->integer('transport')->default(0);
            $table->integer('accomodation')->default(0);
            $table->integer('food_beverages')->default(0);
            $table->integer('overall')->default(0);
            $table->text('review');
            $table->morphs('reviewable');
            $table->boolean('status')->default(false);
            $table->boolean('featured')->default(false);
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
        Schema::dropIfExists('reviews');
    }
}
