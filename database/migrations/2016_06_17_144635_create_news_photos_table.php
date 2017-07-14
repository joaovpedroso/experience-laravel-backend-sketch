<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateNewsPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('news_id')->index()->unsigned();
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->string('file');
            $table->string('subtitle')->nullable();
            $table->integer('featured')->default(0);
            $table->integer('order')->default(0);
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
        Schema::drop('news_photos');
    }
}
