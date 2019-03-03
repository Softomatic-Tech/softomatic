<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('emp_id');            
            $table->bigInteger('advance');
            $table->integer('deduction_per_month');
            $table->date('advance_given_date');
            $table->date('deduction_start_date');
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advance');
    }
}
