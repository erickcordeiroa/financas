<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author')->constrained('users');
            $table->foreignId('category')->constrained('categories');
            $table->string('title');
            $table->string('uri');
            $table->text('subtitle');
            $table->text('content');
            $table->text('cover');
            $table->string('video');
            $table->integer('views');
            $table->string('status')->default('draft');
            $table->timestamp('post_at');
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
        Schema::dropIfExists('posts');
    }
}
