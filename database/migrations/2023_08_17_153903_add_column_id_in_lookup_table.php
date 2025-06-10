<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIdInLookupTable extends Migration
{
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            // Add columns or make changes to the 'departments' table
            $table->integer('company_id')->nullable()->after('id');
        });

        Schema::table('schedules', function (Blueprint $table) {
            // Add columns or make changes to 'schedules'
            $table->integer('company_id')->nullable()->after('id');
        });

        Schema::table('locations', function (Blueprint $table) {
            // Add columns or make changes to the 'departments' table
            $table->integer('company_id')->nullable()->after('id');
        });


        // Add more Schema::table calls for additional tables
    }

    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            // Reverse changes made to 'lookup' table
            $table->dropColumn('company_id')->nullable();
        });

        Schema::table('schedules', function (Blueprint $table) {
            // Reverse changes made to 'another_table'
            $table->dropColumn('company_id')->nullable();
        });

        Schema::table('locations', function (Blueprint $table) {
            // Reverse changes made to 'another_table'
            $table->dropColumn('company_id')->nullable();
        });

        // Add more Schema::table calls to reverse changes on additional tables
    }
}

