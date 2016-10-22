<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('position');
            $table->dropColumn('layout_position');
            $table->dropColumn('template');
            $table->dropColumn('class');
        });
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->string('layout_position')->nullable();
            $table->string('template')->nullable();
            $table->string('class')->nullable();
            $table->integer('position')->unsigned();
        });
        Schema::table('menu_items', function (Blueprint $table) {
            $table->integer('position')->unsigned();
        });
    }
}
