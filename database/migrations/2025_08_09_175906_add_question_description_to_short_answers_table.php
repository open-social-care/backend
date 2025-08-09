<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuestionDescriptionToShortAnswersTable extends Migration
{
    public function up()
    {
        Schema::table('short_answers', function (Blueprint $table) {
            $table->string('question_description')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('short_answers', function (Blueprint $table) {
            $table->dropColumn('question_description');
        });
    }
}
