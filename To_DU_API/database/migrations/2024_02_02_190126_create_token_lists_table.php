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
        
        Schema::create('token_lists', function (Blueprint $table) {
            $table->id();
            //Токен доступу
            $table->string('token');
            //Користувач до якого звертається
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');


            $table->timestamps();
            // Індекс для поле user_id
            $table->index('user_id');
        });
        if (Schema::hasTable('token_lists')) {
            \DB::table('token_lists')->truncate();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_lists');
    }
};
