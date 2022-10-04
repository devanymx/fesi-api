<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('code');
            $table->string('description');
            $table->string('measurement');
            $table->float('price');
            $table->float('sale_price');
            $table->float('profit');
            $table->string('unit');
            $table->integer('minimum');
            $table->integer('maximum');
            $table->integer('taxes');
            $table->string('image')->nullable();
            $table->boolean('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('description');
            $table->dropColumn('measurement');
            $table->dropColumn('price');
            $table->dropColumn('sale_price');
            $table->dropColumn('profit');
            $table->dropColumn('unit');
            $table->dropColumn('minimum');
            $table->dropColumn('maximum');
            $table->dropColumn('taxes');
            $table->dropColumn('image');
            $table->dropColumn('status');
        });
    }
};
