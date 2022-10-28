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
        Schema::create('credit_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->cascadeOnDelete();
            $table->float('limit')->default(0);
            $table->float('balance')->default(0);
            $table->date('cutoff_date')->nullable();
            $table->date('due_date')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('credit_profiles');
    }
};
