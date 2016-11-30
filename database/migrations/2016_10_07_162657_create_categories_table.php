<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->required();
            $table->boolean('status')->default(true);
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('position')->unsigned()->required();
            $table->string('image')->nullable();
        });
        Schema::create('category_translations', function (Blueprint $table){
           $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name')->nullable();
            $table->text('short_content')->nullable();
            $table->text('content')->nullable();

            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['category_id', 'locale']);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('category_translations');
        Schema::drop('categories');
    }
}
