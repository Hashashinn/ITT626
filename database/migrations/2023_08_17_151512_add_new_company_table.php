<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewCompanyTable extends Migration
{
    
    public function up()
    {
        Schema::create('companys', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('company_name')->nullable();
            $table->string('status');
            $table->string('company_code')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companys');
    }
}
