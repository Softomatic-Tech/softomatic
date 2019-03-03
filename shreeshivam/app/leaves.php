<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leaves extends Model
{
    protected $table = 'leaves';
	protected $fillable = [
		'emp_id','user_id','reason','from','to','status','rejection_reason'
	];
	protected $hidden = [
	         'remember_token',
	    ];
}
