<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->text('topic');
            $table->text('slug')->nullable();
            $table->integer('order')->default(1);
            $table->integer('parent_id')->nullable();
            $table->integer('destination_id')->nullable();
            $table->integer('page_id')->nullable();
            $table->integer('package_id')->nullable();
            $table->integer('excursions_id')->nullable();
            $table->integer('cruises_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }
}
