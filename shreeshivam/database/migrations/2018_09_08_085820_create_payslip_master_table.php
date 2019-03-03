<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayslipMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payslip_master', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('month');
            $table->integer('emp_id');
            $table->integer('designation_id');
            $table->string('actual_attendance');
            $table->string('attendance');
            $table->string('over_time');
            $table->float('salary',11,2);
            $table->float('basic',11,2);
            $table->float('attendance_based_sal',11,2);
            $table->float('hra',11,2);
            $table->float('other',11,2);
            $table->float('incentive',11,2);
            $table->float('incentive_target',11,2);
            $table->float('gross_salary',11,2);
            $table->float('epf',11,2);
            $table->float('esic',11,2);
            $table->float('tds',11,2);
            $table->float('advance',11,2);
            $table->float('professional_tax',11,2);
            $table->float('net_salary',11,2);
            $table->float('other_deduction',11,2);
            $table->float('other_addition',11,2);
            $table->text('remark');
            $table->float('net_payable',11,2);
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
        Schema::dropIfExists('payslip_master');
    }
}
