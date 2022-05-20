<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('readers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('sur_name', 50);
            $table->string('patronymic', 50)->nullable()->default(null);
            $table->dateTime('birth_date');
            $table->string('nationality', 100);
            $table->string('work_place', 100)->nullable()->default(null);
            $table->string('work_position', 100)->nullable()->default(null);
            $table->string('home_address');
            $table->string('phone');
            $table->string('passport_id');
            $table->boolean('agreed_with_rules')->default(true);
            $table->foreignId('user_id')
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
        Schema::dropIfExists('readers');
    }
}
