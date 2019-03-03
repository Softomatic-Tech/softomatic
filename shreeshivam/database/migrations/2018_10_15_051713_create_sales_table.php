<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('branch');
            $table->date('bill_date');
            $table->date('bill_no');
            $table->string('division');
            $table->string('department');
            $table->string('section');
            $table->integer('barcode');
            $table->integer('hsn_code');
            $table->integer('items');
            $table->bigInteger('net_amount');
            $table->string('category2');
            $table->string('aging');
            $table->string('sales_person');
            $table->string('sales_person_id');
            $table->bigInteger('mrp');
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
        Schema::dropIfExists('sales');
    }
}
