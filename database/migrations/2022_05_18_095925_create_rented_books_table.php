<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentedBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rented_books', function (Blueprint $table) {
            $table->id();
            $table->string('inventory_number');
            $table->string('department');
            $table->dateTime('issue_date')->nullable()->default(null);
            $table->dateTime('return_date');
            $table->boolean('bail_received')->default(false);
            $table->string('note')->nullable()->default(null);
            $table->string('book_name');
            $table->string('author_name');
            $table->foreignId('book_id')
                ->nullable()
                ->default(null)
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('reader_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
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
        Schema::dropIfExists('rented_books');
    }
}
