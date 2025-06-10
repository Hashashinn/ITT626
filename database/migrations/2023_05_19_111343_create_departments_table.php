<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });
        DB::table('departments')->insert([
            ['name' => 'Web Department'],
            ['name' => 'IT Management'],
            ['name' => 'Marketing'],
        ]);
    
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
