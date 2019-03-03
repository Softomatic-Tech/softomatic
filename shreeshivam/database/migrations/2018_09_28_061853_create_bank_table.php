<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank', function (Blueprint $table) {
            $table->increments('id')->primary_key();
            $table->string('branch_location_id');
            $table->string('bank_name');
            $table->string('bank_branch');
            $table->string('bank_ifsc_code');
            $table->string('bank_acc_no');
            $table->string('bank_acc_holder_name');
            $table->string('status');
            $table->date('current_date')->nullable();
            $table->date('end_current_date')->nullable();
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
        Schema::dropIfExists('bank');
    }
}
