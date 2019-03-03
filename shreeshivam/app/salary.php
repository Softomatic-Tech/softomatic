<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class salary extends Model
{
    protected $table = 'salary';
	protected $fillable = [
		'emp_id','salary'
	 ];
	protected $hidden = [
	         'remember_token',
	    ];
}
