<?php

namespace App\Http\Controllers;
use App\emp;
use App\User;
use App\salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\File;
use App\Exception;
use \Illuminate\Database\QueryException;

class MasterController extends Controller
{
    public function index()
    {
       $branch = DB::table('branch')->get();
       $division = DB::table('division')->get();
       $epf_master = DB::table('epf_master')->join('branch','epf_master.branch_id', '=' , 'branch.id')->
       select('epf_master.epf','epf_master.id','epf_master.esic','epf_master.minimum_wages','branch.branch','epf_master.branch_id')->get();

       $aging_master = DB::table('aging_master')->join('branch','aging_master.branch_id', '=' , 'branch.id')->join('division','division.id','=','aging_master.division_id')->
       select('aging_master.aging_title','aging_master.id','aging_master.aging_per','branch.branch','aging_master.branch_id','aging_master.division_id','division.division')->
       get();
        $professional = DB::table('professional_tax')->join('branch','professional_tax.branch_id', '=' , 'branch.id')->
       select('professional_tax.id','professional_tax.branch_id','professional_tax.calculation_base','professional_tax.amount_from','professional_tax.amount_to','branch.branch','professional_tax.professional_tax','professional_tax.tax_deducted','professional_tax.for_men','professional_tax.for_women','for_11_month','for_last_month')->get();
       return view('master',compact('epf_master','branch','aging_master','division','professional'));
    }

     public function epf_master(Request $request)
    {
        $location= $request->input('location');
        $epf= $request->input('epf');
        $esic=$request->input('esic');
        $minimum_wage=$request->input('minimum_wage');
        $epf_master= DB::table('epf_master')->insert(['branch_id'=>$location,'epf'=>$epf, 'esic'=>$esic,'minimum_wages'=>$minimum_wage,'created_at'=>now()]);
        return redirect('master')->with('alert-success','Generated Sucessfully');
    }
      public function aging_master(Request $request)
    {
        $location= $request->input('location');
        $division= $request->input('division');
        $i=1;
         if($request->input('aging_title')!='' && $request->input('aging_per')!='' ) 
           { 
            foreach ($request->input('aging_title') as $aging_title ) {             
            $aging_master= DB::table('aging_master')->insert(['branch_id'=>$location,'division_id'=>$division, 'aging_title'=>$aging_title , 'aging_per'=>$request->aging_per[$i] ,'created_at'=>now()]);
            $i++;
        }
    } 
         return redirect('master')->with('alert-success','Generated Sucessfully');
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $branch = $request->input('branch');
        $epf=$request->input('epf');
        $esic=$request->input('esic');
        $minimum=$request->input('minimum');
        $exp=DB::table('epf_master')
        ->where('id', $id)
        ->update(['branch_id' => $branch , 'epf' => $epf ,'esic' =>$esic ,'minimum_wages' =>$minimum ,'updated_at' =>Carbon::now()]);
        return redirect('master')->with('alert-success','EPF Master Updated Successfully!');

    }
     public function update_aging(Request $request)
    {
        $id = $request->input('id');
        $branch = $request->input('branch');
        $division=$request->input('division');
        $title=$request->input('title');
        $per=$request->input('per');
        $exp=DB::table('aging_master')
        ->where('id', $id)
        ->update(['branch_id' => $branch , 'division_id' => $division ,'aging_title' =>$title ,'aging_per' =>$per ,'updated_at' =>Carbon::now()]);
        return redirect('master')->with('alert-success','Aging Master Updated Successfully!');


    }
      public function professional_tax(Request $request)
      {
          
          $branch = $request->input('location');
          $calculation_base=$request->input('calculation_base');
          $amt_from=$request->input('amt_from');

          $amt_to=$request->input('amt_to');
          $prof_tax=$request->input('prof_tax');
          $payment_type=$request->input('payment_type');
          $men=$request->input('men');
          $women=$request->input('women');  
          if($payment_type=='yearly')
          {
            $for_11_month = $request->input('for_11_month');
            $for_last_month = $request->input('for_last_month');
          } 
          else
          {
            $for_11_month = 0;
            $for_last_month = 0;
          }
          if($branch=='' || $calculation_base=='' || $amt_from=='' || $amt_to=='' || $prof_tax=='' || $payment_type=='' || ($men=='' && $women==''))
          {
              return redirect()->back()->with('alert-danger','All fields are required to fill')->with($request->all);
          }        
          $professional_tax= DB::table('professional_tax')->insert(['branch_id'=>$branch,'calculation_base'=>$calculation_base, 'amount_from'=>$amt_from,
          'amount_to'=>$amt_to,'professional_tax'=>$prof_tax,'professional_tax'=>$payment_type,'tax_deducted'=>$prof_tax,'for_11_month'=>$for_11_month,'for_last_month'=>$for_last_month,'for_men'=>$men,'for_women'=>$women,'created_at'=>now()]);
          if($professional_tax==true){
              return redirect('master')->with('alert-success','Professional Tax created Successfully!');
          }
          else {
              return redirect('master')->with('alert-danger','Professional Tax Not Created');
          }
      }

      public function professional_tax_update(Request $request)
      {          
          $branch = $request->input('location');
          $calculation_base=$request->input('calculation_base');
          $amt_from=$request->input('amt_from');
          $amt_to=$request->input('amt_to');
          $prof_tax=$request->input('prof_tax');
          $payment_type=$request->input('payment_type');
          $men=$request->input('men');
          $women=$request->input('women'); 
          if($payment_type=='yearly')
          {
            $for_11_month = $request->input('for_11_month');
            $for_last_month = $request->input('for_last_month');
          } 
          else
          {
            $for_11_month = 0;
            $for_last_month = 0;
          }

          if($branch=='' || $calculation_base=='' || $amt_from=='' || $prof_tax=='' || $payment_type=='' || ($men=='' && $women==''))
          {
              return redirect()->back()->with('alert-danger','All fields are required to fill')->with($request->all);
          }        
          $professional_tax= DB::table('professional_tax')->where('id',$request->input('id'))->update(['branch_id'=>$branch,'calculation_base'=>$calculation_base, 'amount_from'=>$amt_from,
          'amount_to'=>$amt_to,'professional_tax'=>$prof_tax,'professional_tax'=>$payment_type,'tax_deducted'=>$prof_tax,'for_11_month'=>$for_11_month,'for_last_month'=>$for_last_month,'for_men'=>$men,'for_women'=>$women,'updated_at'=>now()]);
          if($professional_tax==true){
              return redirect('master')->with('alert-success','Professional Tax Updated Successfully!');
          }
          else {
              return redirect('master')->with('alert-danger','Professional Tax Not Updated');
          }
      }
}
