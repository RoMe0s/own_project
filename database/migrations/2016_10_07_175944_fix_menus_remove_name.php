<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixMenusRemoveName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_translations', function (Blueprint $table) {
            $table->dropColumn('name');
        });
        Schema::table('menus', function (Blueprint $table){
           $table->string('name')->required()->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_translations', function (Blueprint $table) {
            $table->string('name')->required();
        });
        Schema::table('menus', function (Blueprint $table){
           $table->dropColumn('name');
        });
    }
}
