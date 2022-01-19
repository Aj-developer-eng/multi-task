<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Task;
use App\Models\TaskDetail;
use App\Models\TBL_Attendance;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use DateTime;
use \DateTimeZone;
use Carbon\Carbon;
use App\Models\Mondoob_Save_Location;
use Stevebauman\Location\Facades\Location;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use App\Mail\acceptmailtolead;
use App\Mail\acceptmailtoagent;

class EmployeeController extends Controller
{
    public function employees()
    {
        $employees = Employee::all();
        $b2c_visa_applied = \DB::table('bookings')->where('visa_applied','=','1')->orderBy('id', 'DESC')->limit('5')->get();
        $b2c_notification = \DB::table('bookings')->orderBy('id', 'DESC')->limit('5')->get();
        $b2b_notification = \DB::table('bookings')->orderBy('id', 'DESC')->limit('5')->get();
        return view('template/frontend/userdashboard/pages/employees/view_employee',compact('employees','b2c_visa_applied','b2c_notification','b2b_notification'));
    }
    public function create()
    {
        $b2c_visa_applied = \DB::table('bookings')->where('visa_applied','=','1')->orderBy('id', 'DESC')->limit('5')->get();
        $b2c_notification = \DB::table('bookings')->orderBy('id', 'DESC')->limit('5')->get();
        $b2b_notification = \DB::table('bookings')->orderBy('id', 'DESC')->limit('5')->get();
        return view('template/frontend/userdashboard/pages/employees/add_employee',compact('b2c_visa_applied','b2c_notification','b2b_notification'));
    }
    public function store(Request $request)
    {
        $employees = new Employee();
        $employees->first_name = $request->first_name;
        $employees->last_name = $request->last_name;
        $employees->email = $request->email;
        $employees->password = bcrypt($request->password);
        $employees->address_latitude = $request->address_latitude;
        $employees->address_longitude = $request->address_longitude;
        $employees->address = $request->address;
        $employees->salary = $request->salary;
        $employees->birth_date = $request->birth_date;
        $employees->contact_info = $request->contact_info;
        $employees->gender = $request->gender;
        $employees->position = $request->position;
        $employees->schedule = $request->schedule;
        $employees->employee_status = $request->employee_status;
        $employees->image = $request->image;
    if($request->hasFile('image'))
    {
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('uploads/img/', $filename);
        $employees->image = $filename;
    }
    else
    {
        return $request;
        $employees->image = '';
    }
        $employees->save();
        $request->session()->flash('success','Successful Add Employee!');
        return redirect('super_admin/employees');
    }
    public function edit($id)
    {
        $employees = Employee::find($id);
        $b2c_visa_applied = \DB::table('bookings')->where('visa_applied','=','1')->orderBy('id', 'DESC')->limit('5')->get();
        $b2c_notification = \DB::table('bookings')->orderBy('id', 'DESC')->limit('5')->get();
        $b2b_notification = \DB::table('bookings')->orderBy('id', 'DESC')->limit('5')->get();
        return view('template/frontend/userdashboard/pages/employees/edit_employee',compact('employees','b2c_visa_applied','b2c_notification','b2b_notification'));
    }
    public function update(Request $request,$id)
    {
        $employees = Employee::find($id);
    if($employees)
    {
        $employees->first_name = $request->first_name;
        $employees->last_name = $request->last_name;
        $employees->email = $request->email;
        $employees->password = bcrypt($request->password);
        $employees->address_latitude = $request->address_latitude;
        $employees->address_longitude = $request->address_longitude;
        $employees->address = $request->address;
        $employees->salary = $request->salary;
        $employees->birth_date = $request->birth_date;
        $employees->contact_info = $request->contact_info;
        $employees->gender = $request->gender;
        $employees->position = $request->position;
        $employees->schedule = $request->schedule;
        $employees->employee_status = $request->employee_status;
        $employees->image = $request->image;


    if($request->hasFile('image'))
    {
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('uploads/img/', $filename);
        $employees->image = $filename;
    }
    else
    {
        return $request;
        $employees->image = '';
    }
        $employees->update();
        $request->session()->flash('success','Successful Update Employee!');
        return redirect('super_admin/employees');
        }
    }
    public function delete(Request $request,$id)
    {
        $employees = Employee::find($id);
        $employees->delete();
        $request->session()->flash('success','Successful Delete Employee!');
        return redirect('super_admin/employees');
    }
    public function employee_login()
    {
        return view('template/frontend/userdashboard/employeepanel/pages/login');
    }
    public function employee_login_submit(Request $request)
    {

        $this->validate($request, [
            'email' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        if($login = Auth::guard('employee')->attempt($credentials)){
        $get_id = DB::table('employees')->where('email', '=', $request->email)->first();
        $get_ids = $get_id->id;
        $clientIP = request()->ip();
        $position = Location::get($clientIP);
     // dd($position);
      //$save_location=new Mondoob_Save_Location();
      //$save_location->employee_id=$get_ids;
      //$save_location->employee_email=$request->email;
      //$save_location->loc_address=$position->cityName;
      //$save_location->loc_latitude=$position->latitude;
      //$save_location->loc_longitude=$position->longitude;
      //$save_location->save();
        $get_emp_1 = DB::table('employees')
                ->where('email', $request->email)
                ->update(['is_active' => '0']);
        //\DB::table('employees')->where('email','=',$request->email)->
            //     update([
            //         'address_latitude' => $position->latitude,
            //         'address_longitude' => $position->longitude,
            //         'address' => $position->cityName,
            //     ]);

        return redirect()->intended('dashboard');
        }

        return back()->with('message','Email or password is not correct!!');
    }
    public function employee_logout()
    {
        // $ip1 = $_SERVER['REMOTE_ADDR'];
        // $ip = substr($ip1, strrpos($ip1, ':'));
        // $location = \Location::get($ip);
///needs to be check the blow code before activating it
       $mak = 'Makkah';
       $madi = 'Madina';
       $J = 'Jeddah';
    
       $get_emp_1=DB::table('employees')
       ->where('is_active', '0')
       ->update(['is_active' => '1',
                'position' => $madi,
                'address_latitude' => $location->latitude,
                'address_longitude' => $location->longitude,
        ]);

        Auth::guard('employee')->logout();
        return redirect('login');
    }
    public function assigned_lead()
    {
        $me = Auth::guard('employee')->user()->id;
        //dd($me);
        $my_assigned_lead = DB::table('task_details')->where('employee_id','=',$me)->get();
        //dd($my_assigned_lead);
        return view('template/frontend/userdashboard/employeepanel/pages/my_lead_passenger',compact('my_assigned_lead'));
    }
    public function changeStatus(Request $request)
    { 
        $user = TaskDetail::find($request->user_id);
        $user->status = $request->status1;
        $user->save();
        $find_lead_id0 = DB::table('task_details')->where('id','=', $request->user_id)->get();
        $find_lead_id1 = $find_lead_id0[0]->employee_id;
        //dd($find_lead_id1);
        $find_mandoob_detail = DB::table('employees')->where('id','=', $find_lead_id1)->get();
        
        $add_emp_id_col_in_passenger_table = DB::table('lead_passengers')->where('id','=', $request->user_id)->update([
    'assigned_mandoob_id' => $find_lead_id1,
]);
       
        $accept = [
        'title' => 'Your Assigned a Madoob And him information is given below! ',
        'info' => [
            'first_name' => $find_mandoob_detail[0]->first_name,
            'last_name' => $find_mandoob_detail[0]->last_name,
            'email' => $find_mandoob_detail[0]->email,
            'contact_info' => $find_mandoob_detail[0]->contact_info,
            'gender' => $find_mandoob_detail[0]->gender,
        ]
        ];
       // \Mail::to()->send(new acceptmailtolead($accept));
//finding agent email
        $find_agent_detail = DB::table('tasks')->where('employee_id','=', $find_lead_id1)->select('agent_id')->get();
        $find_agent_id = $find_agent_detail[0]->agent_id;
        $find_agent_email = DB::table('agents')->where('id','=', $find_agent_id)->select('email')->get();
        
        //getting lead_passenger information
        $lead_name = $find_lead_id0[0]->lead_p_name;
        $lead_email = $find_lead_id0[0]->email;
        $lead_contact = $find_lead_id0[0]->contact;
        $lead_note = $find_lead_id0[0]->note;
        $to_agent = [
        'title' => 'Your Lead Passenger Has been Assigned a Mandoob And him information is given below! ',
        'info' => [
            'name' => $lead_name,
            'email' => $lead_email,
            'contact' => $lead_contact,
            'note' => $lead_note,
            'first_name' => $find_mandoob_detail[0]->first_name,
            'Memail' => $find_mandoob_detail[0]->email,
            'Mcontact_info' => $find_mandoob_detail[0]->contact_info,
            'Mgender' => $find_mandoob_detail[0]->gender,
        ]
        ];
    //    \Mail::to($find_agent_email[0]->email)->send(new acceptmailtoagent($to_agent));
        
    }
}
