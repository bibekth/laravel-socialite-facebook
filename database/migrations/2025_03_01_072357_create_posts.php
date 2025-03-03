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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade')->index();
            $table->string('title')->unique();
            $table->string('slug')->unique()->index();
            $table->string('banner_image')->nullable();
            $table->unsignedBigInteger('likes')->default(0);
            $table->unsignedBigInteger('dislikes')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table){
            $table->id();
            $table->foreignId('post_id')->references('id')->on('posts')->onDelete('cascade')->onUpdate('cascade');
            $table->text('comment');
            $table->timestamps();
        });

        Schema::create('votes', function(Blueprint $table){
            $table->id();
            $table->foreignId('post_id')->references('id')->on('posts')->onDelete('cascade')->onUpdate('cascade');
            $table->string('option')->comment('options for voting');
            $table->unsignedBigInteger('counts')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('posts');
    }
};
