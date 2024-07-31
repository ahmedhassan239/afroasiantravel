<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socials', function (Blueprint $table) {
            $table->id();
            $table->string('facebook')->nullable()->default('#');
            $table->string('twitter')->nullable()->default('#');
            $table->string('instagram')->nullable()->default('#');
            $table->string('youtube')->nullable()->default('#');
            $table->string('flickr')->nullable()->default('#');
            $table->string('linkedin')->nullable()->default('#');
            $table->string('pinterest')->nullable()->default('#');
            $table->string('address1')->nullable()->default('#');
            $table->string('address2')->nullable()->default('#');
            $table->string('phone1')->nullable()->default('#');
            $table->string('phone2')->nullable()->default('#');
            $table->string('email')->nullable();
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
        Schema::dropIfExists('socials');
    }
}
