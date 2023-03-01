<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColorToStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stages', function (Blueprint $table) {
            $table->string('color')->default('#051c4b')->after('name');
        });

        Schema::table('bug_stages', function (Blueprint $table) {
            $table->string('color')->default('#051c4b')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_stages', function (Blueprint $table) {
            //
        });

        Schema::table('bug_stages', function (Blueprint $table) {
            //
        });
    }
}
