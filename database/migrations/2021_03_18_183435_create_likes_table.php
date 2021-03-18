<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('post_id')->nullable();
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->integer('like_count')->default(0);
            $table->timestamps();

            // внешний ключ, ссылается на поле id таблицы users
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            // внешний ключ, ссылается на поле id таблицы posts
            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->nullOnDelete();
            // внешний ключ, ссылается на поле id таблицы comments
            $table->foreign('comment_id')
                ->references('id')
                ->on('comments')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
