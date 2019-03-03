<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilyDetailTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_detail_temp', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('emp_id');
            $table->text('father');
            $table->text('mother');
            $table->text('spouse');
            $table->text('children');
            $table->string('status');
            $table->text('remark');
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
        Schema::dropIfExists('family_detail_temp');
    }
}
