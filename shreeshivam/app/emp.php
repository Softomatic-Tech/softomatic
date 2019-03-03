<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class emp extends Model
{
	protected $table = 'emp';
    protected $fillable = [
        'genesis_ledger_id','genesis_id', 'biometric_id', 'branch_location_id', 'title', 'first_name', 'middle_name', 'last_name', 'blood_group', 'email', 'dob', 'mobile', 'gender', 'category', 'marital_status' , 'adhaar_number', 'pan_number', 'local_address', 'permanent_address', 'photo', 'emergency_call_number', 'emergency_call_person', 'department', 'designation', 'doj', 'status', 'esic_number', 'epf_number', 'lin_number', 'uan_number', 'acc_holder_name', 'acc_no', 'ifsc_code', 'bank_name', 'branch', 'salary_id', 'esic_option', 'epf_option','reason_for_code','last_working_day'
    ];
	
	protected $hidden = [
         'remember_token',
    ];
}
