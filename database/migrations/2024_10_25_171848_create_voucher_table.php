<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vocher', function (Blueprint $table) {
           $table->bigIncrements('id');
           $table->integer('discount');
           $table->string('code');
           $table->date('start');
           $table->date('end');
            $table->text('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vocher', function (Blueprint $table) {
            //
        });
    }
};