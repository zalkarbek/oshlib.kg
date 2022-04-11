<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookSelectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_selections', function (Blueprint $table) {
            $table->id();
            $table->string('comment', 100)->nullable();
            $table->bigInteger('selection_id')->unsigned();
            $table->bigInteger('book_id')->unsigned();
            $table->foreign('selection_id')
                ->references('id')
                ->on('selections')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->softDeletes();
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
        Schema::dropIfExists('book_selections');
    }
}
