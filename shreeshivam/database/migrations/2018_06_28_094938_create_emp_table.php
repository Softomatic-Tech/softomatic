<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('genesis_id');
            $table->string('biometric_id');
            $table->integer('branch_location_id');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('blood_group')->nullable();
            $table->string('email')->unique();
            $table->date('dob')->nullable();
            $table->string('mobile');
            $table->string('gender')->nullable();
            $table->string('category')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('adhaar_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->text('local_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('photo')->nullable();
            $table->string('emergency_call_number')->nullable();
            $table->string('emergency_call_person')->nullable();
            $table->string('department');
            $table->string('designation');
            $table->date('doj');
            $table->string('status');
            $table->string('esic_number')->nullable();
            $table->string('epf_number')->nullable();
            $table->string('lin_number')->nullable();
            $table->string('uan_number')->nullable();
            $table->string('acc_holder_name');
            $table->string('acc_no');
            $table->string('ifsc_code');
            $table->string('bank_name');
            $table->string('branch');
            $table->integer('salary_id')->nullable();
            $table->string('esic_option')->nullable();
            $table->string('epf_option')->nullable();
            $table->integer('reason_code_0');
            $table->date('last_working_day');
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
        Schema::dropIfExists('emp');
    }
}
