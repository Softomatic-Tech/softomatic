<?php


Route::group(['middlewareGroups' => 'web','middleware' => 'revalidate'], function () {

Route::auth();

Route::get('/','LoginController@showlogin');
Route::post('/','LoginController@login');


Route::get('dashboard','dashboardController@index');


Route::get('mobile-dashboard', function () {
	return view('mobile_dashboard');
});

Route::get('mobile_attendance', function () {
	return view('mobile_attendance');
});
Route::post('mobile_attendance/submit','AttendanceController@add_mobile_attendance');

Route::get('mobile-leave','LeaveController@show');

Route::post('get_branch_emp','TeamController@get_branch_emp');
Route::get('mobile-target','TargetController@show');
Route::post('predict-incentive','TargetController@predict');
Route::post('predict-regular','TargetController@predict_regular');

Route::get('employee-login','LoginController@show_emp_login');

Route::post('employee-login','LoginController@emp_login');

Route::get('department','DepartmentController@get_department');

Route::post('department/submit','DepartmentController@create_department');

Route::post('department','DepartmentController@update_department');

Route::post('department/delete','DepartmentController@delete_department');

Route::post('get-details','EmpController@getDetails');

Route::get('designation','DesignationController@get_designation');

Route::post('designation/submit','DesignationController@create_designation');

Route::post('designation','DesignationController@update_designation');

Route::post('designation/delete','DesignationController@delete_designation');


Route::get('add-employee','EmpController@getform');

Route::get('add-employee/get_desig','EmpController@getdesig');

Route::get('add-employee/get_section','EmpController@getsection');

Route::post('add-employee/submit','EmpController@create');

Route::get('employee-list','EmpController@get_emp_list');

Route::get('edit-employee-{id}','EmpController@edit');

Route::post('edit-employee/submit','EmpController@update');

Route::post('employee/delete','EmpController@delete');

Route::get('emp-search','EmpController@get_emp_search');
Route::post('get-emp-details','EmpController@get_emp_details');

Route::get('emp_autocomplete','EmpController@autocomplete');

Route::get('upload-emp','EmpController@get_emp_upload');


Route::get('upload-tds','UploadController@tds_index'); 


Route::post('upload_emp_csv','UploadController@upload_emp_csv');
Route::post('upload_tds_csv','UploadController@upload_tds_csv');
Route::get('advance','AdvanceController@index');
Route::post('advance/submit','AdvanceController@create_advance');

Route::get('attendance-report','AttendanceController@show');
Route::post('getweek','AttendanceController@getweek');
Route::post('getdate','AttendanceController@getdate');
Route::post('getattendance','AttendanceController@getattendance');

Route::post('uploadcsv','UploadController@upload_attendance_csv');

Route::get('attendance-search', function () {
	if(!empty(session('username')))
    	return view('attendance-search');
	else
		return redirect('/')->with('status',"Please login First");
});
Route::get('edit_attendance','AttendanceController@get_edit_attendance');
Route::post('get_emp','EmpController@get_emp');
Route::post('get_date_attendance','AttendanceController@get_date_attendance');
Route::post('edit_attendance/submit','AttendanceController@update_attendance');

Route::get('monthly_attendance_report','AttendanceController@monthly_report');
Route::post('get_monthly_attendance','AttendanceController@get_monthly_attendance');


Route::get('leave','LeaveController@show');
Route::get('leavetable','LeaveController@showtable');
Route::get('leavetable_emp','LeaveController@showtable_emp');
Route::post('leave/submit','LeaveController@submit');
Route::post('leave/action','LeaveController@action');



Route::get('add-attendance', function () {
	if(!empty(session('username')))
    	return view('add-attendance');
	else
		return redirect('/')->with('status',"Please login First");
});


Route::get('create-payslip', function (){
	if(!empty(session('username')))
    	return view('create-payslip');
	else
		return redirect('/')->with('status',"Please login First");
});

Route::get('payslip-list', function (){
	if(!empty(session('username')))
    	return view('payslip-list');
	else
		return redirect('/')->with('status',"Please login First");
});



Route::get('notice-board', function () {
	if(!empty(session('username')))
    	return view('notice-board');
	else
		return redirect('/')->with('status',"Please login First");
});


Route::get('/logout',['as'=>'logout','uses'=>'LoginController@logout']);


Route::post('holidays/submit','HolidayController@create_holiday');
Route::get('holidays','HolidayController@get_holiday');
Route::post('holidays','HolidayController@update_holiday');
Route::post('holidays/delete','HolidayController@delete_holiday');

Route::post('notice-board/submit','NoticeController@create_notice');
Route::get('notice-board','NoticeController@get_notice');
Route::post('notice-board','NoticeController@update_notice');
Route::post('notice-board/delete','NoticeController@delete_notice');


Route::post('expense/submit','ExpenseController@create_expense');
Route::get('expense','ExpenseController@get_expense');
Route::post('expense/update','ExpenseController@update_expense');
Route::post('expense/delete','ExpenseController@delete_expense');


Route::get('mobile-account','AccountController@index');

Route::get('account','AccountController@index');
Route::post('mobile-account/submit','AccountController@reset_pass');
Route::post('account/submit','AccountController@reset_pass');



Route::get('branch','BranchController@Index');
Route::post('branch/submit','BranchController@create_branch');
Route::post('branch','BranchController@update_branch');
Route::post('branch/delete','BranchController@delete_branch');


Route::get('division','DivisionController@get_division');
Route::post('division/submit','DivisionController@save_division');
Route::post('division/update','DivisionController@update_division');

Route::get('section','DivisionController@get_section');
Route::post('section/submit','DivisionController@save_section');
Route::post('section/update','DivisionController@update_section');

Route::get('sub-section','DivisionController@get_sub_section');
Route::post('subsection/submit','DivisionController@save_sub_section');
Route::post('subsection/update','DivisionController@update_sub_section');


Route::get('create-team','TeamController@index');
Route::post('create-team/submit','TeamController@team_create');
Route::post('team/update','TeamController@team_update');
Route::post('get_select','TeamController@getSelect');

Route::post('getsection','DivisionController@getsection');
Route::post('getsubsection','DivisionController@getsubsection');


Route::get('target','TargetController@get_target_form');
Route::post('get_team','TargetController@get_team');
Route::post('get_team2','TargetController@get_team2');
Route::post('get_team_list','TargetController@get_team_list');
Route::post('target/submit','TargetController@submit_target');

Route::post('target/update','TargetController@update_target');


Route::get('file','UploadController@get_file_form');
Route::post('file_deactivate','UploadController@file_deactivate');


Route::post('upload_file','UploadController@upload_file');
Route::get('upload-sales','UploadController@get_sales_form');
Route::post('upload_sales_csv','UploadController@upload_sales_csv');



Route::get('payslip-list', function (){
	if(!empty(session('username')))
    	return view('payslip-list');
	else
		return redirect('/')->with('status',"Please login First");
});

Route::get('create-payslip','PayslipController@index');
Route::get('master','MasterController@index');
Route::post('epf_master/submit','MasterController@epf_master');
Route::post('aging_master/submit','MasterController@aging_master');
Route::post('epf_update/submit','MasterController@update');
Route::post('aging_update/submit','MasterController@update_aging');

Route::post('payslip-withdata','PayslipController@salary');
Route::post('payslip/submit','PayslipController@generate_payslip');
Route::get('payslip-list','PayslipController@get_payslip_list');
Route::post('payslip-list-data','PayslipController@get_final_salary');


Route::get('incentive', function () {
	return view('incentive');
});
Route::get('incentive','IncentiveController@index');


Route::post('professional/submit','MasterController@professional_tax');

Route::post('professional_tax_update/submit','MasterController@professional_tax_update');

Route::get('history_table','NotificationController@show_history_table');
Route::get('history','NotificationController@show_history');

Route::get('get_notification','NotificationController@get_notification');
Route::get('notification_table','NotificationController@showtable');
Route::get('notification','NotificationController@show');
Route::post('notification/action','NotificationController@dismiss');


Route::get('report','ReportsController@index');
Route::post('provision/submit','ReportsController@provision');
Route::post('current_bank/submit','ReportsController@current_bank');
Route::post('other_bank/submit','ReportsController@other_bank');
Route::post('epf/submit','ReportsController@epf');
Route::post('esic/submit','ReportsController@esic');

 Route::get('various-reports', function () {
 	return view('various-reports');
 });
Route::post('professional/submit','MasterController@professional_tax');


Route::get('mobile_upload_doc','EmpController@get_doc_type');
Route::get('company-doc','UploadController@get_company_docs');
Route::get('doc_viewer','EmpController@get_my_doc');
Route::post('doc/submit','EmpController@upload_file_emp');
Route::get('doc_table','EmpController@show_doc_table');
Route::post('emp_doc/verify','EmpController@update_verify');
Route::post('emp_doc/delete','EmpController@delete_emp_doc');

Route::get('emp-doc','EmpController@get_file_form');
Route::post('getemp','EmpController@getemp');
Route::post('upload_emp_doc','EmpController@upload_file');


Route::post('doc_type/add','EmpController@add_doc_type');
Route::get('mobile_add_doc', function () {
	if(!empty(session('username')))
		return view('mobile_add_doc');
	else
		return redirect('/')->with('status',"Please login First");
});
Route::post('mobile_doc_type/add','EmpController@mobile_add_doc_type');


Route::get('feedback','FeedbackController@get_feedback');
Route::get('mobile_feedback','FeedbackController@get_mobile_feedback');
Route::post('save_feedback','FeedbackController@save_feedback');

Route::get('bank','BankController@get_form');
Route::post('bank/submit','BankController@save');
Route::post('bank/update','BankController@update');

Route::get('daily_report_sample','UploadController@daily_report_sample');
Route::get('sales_sample','UploadController@sales_sample');
Route::get('employee_sample','UploadController@employee_sample');
Route::get('tds_sample','UploadController@tds_sample');

Route::post('employee_download','UploadController@employee_download');


Route::get('upload-attendance','UploadController@index');
Route::post('get_sample','UploadController@get_monthly_sample');
Route::post('upload_att','UploadController@upload_monthly_attendance');


Route::get('analysis','AnalysisController@index');


Route::get('deletepayslip','PayslipController@get_delete_payslip');
Route::POST('payslip','PayslipController@deletepayslip');


Route::get('mobile_personal_detail','EmpController@find_per_detail');
Route::post('personal-detail/submit','EmpController@emp_personal_detail');
Route::post('personal-detail/update','EmpController@update_emp_personal_detail');
Route::get('personal_detail_list','EmpController@personal_detail_list');
Route::post('get_per_details','EmpController@get_per_details');
Route::post('update_per_details','EmpController@update_per_details');
Route::post('reject_per_details','EmpController@reject_per_details');
//////end personal detail
Route::post('get_tds_sample','UploadController@tds_sample');

Route::get('working_hour','BranchController@working_hour_form');

Route::post('working_hour/submit','BranchController@save_working_hour');

Route::post('working_hour/update','BranchController@update_working_hour');


Route::get('upload-incentive','UploadController@incentive_index'); 
Route::post('upload_incentive_csv','UploadController@upload_incentive_csv');
Route::post('get_incentive_sample','UploadController@incentive_sample');

Route::get('upload-bonus','UploadController@bonus_index'); 
Route::post('upload_bonus_csv','UploadController@upload_bonus_csv');
Route::post('bonus/sample','UploadController@bonus_sample');

Route::get('upload-ex_gratia','UploadController@ex_gratia_index'); 
Route::post('upload_ex_gratia_csv','UploadController@upload_ex_gratia_csv');
Route::post('get_ex_gratia_sample','UploadController@ex_gratia_sample');

Route::get('upload-arrear','UploadController@arrear_index'); 
Route::post('upload_arrear_csv','UploadController@upload_arrear_csv');
Route::post('get_arrear_sample','UploadController@arrear_sample');

Route::post('zone/barchart','AnalysisController@barchart');
Route::get('zoneanalysis','AnalysisController@get_zone');
Route::get('employeeanalysis','AnalysisController@get_employee');
Route::post('employee/barchart','AnalysisController@employee_comp');
Route::get('brandanalysis','AnalysisController@get_brand');
Route::post('brand/piechart','AnalysisController@piechart');

Route::get('transfer_master','EmpController@get_emp_transfer');
Route::post('get_branch','EmpController@get_branch');
Route::post('transfer/submit','EmpController@create_transfer');
/////////////////////Warehouse Login//////////////
Route::post('warehouse','WarehouseController@login');

Route::get('ware-account','WarehouseController@get_acc_detail');
Route::post('ware_acc/submit','AccountController@reset_pass');

Route::get('warehouse',function(){
    return redirect('http://test.5el.in/exam/student/login/loginPage');
});
/*Route::get('warehouse', function () {
	return view('warehouse');
});*/

Route::get('gate_staff_dashboard', function () {
	return view('gate_staff_dashboard');
});
Route::get('gate_staff_dashboard','WarehouseController@index');
// Route::get('warehouse_staff_dashboard', function () {
// 	return view('warehouse_staff_dashboard');
// });
Route::get('security_staff_dashboard', function () {
	return view('secur_staff_dashboard');
});
Route::get('purchase_admin_dashboard', function () {
	return view('purchase_admin_dashboard');
});
Route::get('vendor-master', function () {
	return view('vendor-master');
});
Route::get('purchase-order','WarehouseController@purchase_order_form');
Route::get('demo', function () {
	return view('demo');
});
Route::post('vendor/submit','WarehouseController@vendor_insert');
Route::get('gate_task_list','WarehouseController@gate_task_list');
Route::get('warehouse/logout','WarehouseController@logout');
Route::get('warehouse_staff_dashboard','WarehouseController@wareindex');
Route::post('getcity','WarehouseController@getcity');
Route::post('get_form/submit','WarehouseController@get_form');


Route::get('purchase_admin_dashboard','WarehouseController@adminindex');
Route::post('purchase/submit','WarehouseController@purchase_insert');
/////////////////////End Warehouse Login//////////////

Auth::routes();
});

