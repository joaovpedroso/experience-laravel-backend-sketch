<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->index()->unique();
            $table->dateTime('date');
            $table->string('title');
            $table->text('text');
            $table->string('source')->nullable();
            //$table->integer('own')->default(0);
            $table->string('video')->nullable();
            $table->string('audio')->nullable();
            $table->dateTime('happened')->nullable();
            $table->string('comment_photo')->nullable();
            $table->string('comment_title')->nullable();
            $table->text('comment_text')->nullable();
            $table->string('comment_description')->nullable();
            $table->integer('featured')->default(0);
            $table->integer('editorial_id')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('news');
    }
}
