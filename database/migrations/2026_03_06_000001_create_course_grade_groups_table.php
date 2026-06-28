<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseGradeGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('grupos_cursos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_courses_id');
            $table->unsignedBigInteger('fk_grades_id');
            $table->string('letter', 5);
            $table->timestamps();

            $table->unique(['fk_courses_id', 'fk_grades_id']);
            $table->foreign('fk_courses_id')->references('id')->on('cursos')->onDelete('cascade');
            $table->foreign('fk_grades_id')->references('id')->on('niveis')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grupos_cursos');
    }
}
