<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nProcess', 20)->nullable();
            $table->string('nBi')->unique();
            $table->string('father');
            $table->string('mother');
            $table->string('contact');
            $table->string('contactAlter');
            $table->string('email');
            $table->date('dateBirth');
            $table->string('schoolyear');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
        });
    }


    public function down()
    {
        Schema::dropIfExists('alunos');
    }
}
