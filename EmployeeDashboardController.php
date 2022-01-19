<?php

namespace App\Http\Controllers\Emp;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Mondoob_Save_Location;
use App\Models\mandoob_travel;
use App\Models\Booking;
use App\Models\Task;
use Illuminate\Http\Request;
use DateTime;
use \DateTimeZone;
use Carbon\Carbon as time;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Hash;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;
use Illuminate\Notifications\Notification;
use App\Notifications\NewLeaveNotification;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $get_id = auth()->guard('employee')->user()->id;
        $notification = DB::table('tasks')->orderBy('id', 'DESC')->where('employee_id',$get_id)->get();
        $notification_count = DB::table('tasks')->where('task_status','pending')->where('employee_id',$get_id)->count();
        $leave_notification_count = DB::table('leave_notifications')->where('leaves_id',$get_id)->count();
        $leave_notification = DB::table('leave_notifications')->where('leaves_id',$get_id)->get();
        $mytime = time::now();
        $date = $mytime->toRfc850String();
        $today = substr($date, 0, strrpos($date, ","));
//      dd($today)
        $get_date = Carbon::now()->format('d-m-Y');
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
        $current_t = $dateTime->format("H:i A");
        return view('template/frontend/userdashboard/employeepanel/index',compact('notification_count','today','get_date','current_t','leave_notification_count','leave_notification'));
    }
    public function markNotification(Request $request)
    {
        auth()->guard('employee')->user()
        ->unreadNotifications
        ->when($request->input('id'), function ($query) use ($request) {
            return $query->where('id', $request->input('id'));
        })
        ->markAsRead();
        return response()->noContent();
    }
    public function attendance()
    {
        $get_id = auth()->guard('employee')->user()->id;
        $notification = DB::table('tasks')->where('employee_id',$get_id)->get();
        $notification_count = DB::table('tasks')->where('task_status','pending')->where('employee_id',$get_id)->count();
        $get_attendance = \DB::table('attendances')->where('employee_id',$get_id)->get();
        return view('template/frontend/userdashboard/employeepanel/pages/attendance',compact('get_attendance','notification','notification_count'));
    }

    public function clock_in(Request $request)
    {
        $get_email = auth()->guard('employee')->user()->email;
        $employer_id = auth()->guard('employee')->user()->employer_id;
        $check_in = \DB::table('employees')->where('email',$get_email)->get();
        if($check_in)
        {
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
        $current_t = $dateTime->format("H:i A");
        $attendance = new Attendance();
        $attendance->employee_id = auth()->guard('employee')->user()->id;
        $attendance->employee_name = auth()->guard('employee')->user()->first_name. '' .auth()->guard('employee')->user()->last_name;
        $attendance->employee_date = auth()->guard('employee')->user()->created_at;
        $attendance->time_in = $current_t;
        $attendance->time_out = 'NULL';
        $attendance->status = '1';
        $attendance->employer_id = $employer_id;
        $attendance->save();
        $request->session()->flash('success','Clock In Successfully');
        return redirect('dashboard');
        }
    }
    public function clock_out(Request $request)
    {
        $get_email = auth()->guard('employee')->user()->email;
        $check_in = \DB::table('employees')->where('email',$get_email)->get();
        if($check_in)
        {
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
        $current_t = $dateTime->format("H:i A");
        Attendance::orderBy('id','desc')->limit('1')
            ->update([
                'time_out' => $current_t,
                'status' => '0'
            ]);
        $request->session()->flash('success','Clock Out Successfully');
        return redirect('dashboard');
        }

    }
    public function update_profile()
    {
        $get_id = auth()->guard('employee')->user()->id;
        $notification = DB::table('tasks')->where('employee_id',$get_id)->get();
        $notification_count = DB::table('tasks')->where('task_status','pending')->where('employee_id',$get_id)->count();
        return view('template/frontend/userdashboard/employeepanel/pages/update_profile',compact('notification','notification_count'));
    }
    public function personal_information(Request $request)
    {
        $get_id = auth()->guard('employee')->user()->id;
        $get_email = Employee::find($get_id);
        if($get_email)
        {
        $get_email->first_name = $request->first_name;
        $get_email->last_name = $request->last_name;
        $get_email->email = $request->email;
        $get_email->contact_info = $request->contact_info;
        $get_email->update();
        return redirect()->back()->with('message','Updated Your Personal Information!!');
        }
    }
    public function change_picture(Request $request)
    {
        $get_id = auth()->guard('employee')->user()->id;
        $get_email = Employee::find($get_id);
        if($get_email)
        {
        $get_email->image = $request->image;
        if($request->hasFile('image'))
        {
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('uploads/img/', $filename);
        $get_email->image = $filename;
        }
        else
        {
            return $request;
            $get_email->image = '';
        }
        $get_email->update();
        return redirect()->back()->with('message','Updated Your image!!');
        }
    }
    public function change_password(Request $request)
    {
        $this->validate($request,
            [
                'password'=>'required',
                'new_password'=>'required',
                'verify_password'=>'required',
            ]);
        if($request->input('new_password')==$request->input('verify_password'))
        {
        if (Hash::check($request->input('password'), auth()->guard('employee')->user()->password) == false)
        {
        return redirect()->back()->with('message2','invalid current password');
        }
            $user = auth()->guard('employee')->user();
            $user->password = Hash::make($request->input('new_password'));
            $user->save();
            return redirect()->back()->with('message1','password updated successfully');
        }
        else
        {
            return redirect()->back()->with('message','confirm password password does not match!!');
        }
    }
    public function accepted_task(Request $request,$id)
    {
        //dd($id);
        $get_id = auth()->guard('employee')->user()->id;
        $get_email = DB::table('tasks')
            ->where('id', $id)
            ->update(['task_status' => 1]);
        $get_emp = DB::table('employees')
            ->where('id', $get_id)
            ->update(['employee_status' => 'busy']);
        $mytime = time::now();
        $date = $mytime->toRfc850String();
        $today = substr($date, 0, strrpos($date, ","));
//                    dd($today)
        $get_at = Carbon::now()->format('Y-d-m');
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
        $current_t =$dateTime->format("H:i A");
        $get_date_time = $get_at .' '. $current_t;
        $get_emp_1 = DB::table('tasks')
            ->where('employee_id',$get_id)
            ->update(['arrival_time' => $get_date_time]);
            return redirect()->back()->with('message','Accepted Task!!');
    }
    public function rejected_task(Request $request,$id)
    {
        $get_id = auth()->guard('employee')->user()->id;
        $get_email = DB::table('tasks')
            ->where('id', $id)
            ->update(['task_status' => 2]);
        $mytime = time::now();
        $date = $mytime->toRfc850String();
        $today = substr($date, 0, strrpos($date, ","));
//                    dd($today)
        $get_at = Carbon::now()->format('Y-d-m');
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
        $current_t = $dateTime->format("H:i A");
        $get_date_time = $get_at .' '. $current_t;
        $get_emp_1 = DB::table('tasks')
            ->where('employee_id', $get_id)
            ->update(['drop_time' => $get_date_time]);
        return redirect()->back()->with('message','Rejected Task!!');

    }
    public function task_completed(Request $request,$id)
    {
        $get_id = auth()->guard('employee')->user()->id;
        $get_email = DB::table('tasks')
            ->where('id', $id)
            ->update(['task_status' => 3]);
        $get_emp = DB::table('employees')
            ->where('id', $get_id)
            ->update(['employee_status' => 'free']);

        $mytime = time::now();
        $date = $mytime->toRfc850String();
        $today = substr($date, 0, strrpos($date, ","));
//                    dd($today)
        $get_at = Carbon::now()->format('Y-d-m');
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
        $current_t = $dateTime->format("H:i A");
        $get_date_time = $get_at .' '. $current_t;
        $get_emp_1 = DB::table('tasks')
            ->where('employee_id', $get_id)
            ->update(['drop_time' => $get_date_time]);
        return redirect()->back()->with('message','Task Completed!!');

    }
    public function emp_task(Request $request)
    {
       $get_id = auth()->guard('employee')->user()->id;
       $get_task = DB::table('tasks')
            ->where('employee_id', $get_id)->get();
            $count = \DB::table('tasks')
            ->where('employee_id', $get_id)->where('task_status', 3)->count();
        $notification = DB::table('tasks')->where('employee_id',$get_id)->get();
        $notification_count = DB::table('tasks')->where('task_status','pending')->where('employee_id',$get_id)->count();
        return view('template/frontend/userdashboard/employeepanel/pages/completed_task',compact('get_task','count','notification','notification_count'));
    }
    public function employee_task($id){
        $data = DB::table('task_details')->where('employee_id',$id)->latest()->first();
    return view('template/frontend/userdashboard/employeepanel/pages/ajax-message',compact('data'));
    }
    public function emp_leaves()
    {
        $get_id = auth()->guard('employee')->user()->id;
        $get_leave = DB::table('leaves')
            ->where('employee_id', $get_id)->get();
        $notification = DB::table('tasks')->where('employee_id',$get_id)->get();
        $notification_count = DB::table('tasks')->where('task_status','pending')->where('employee_id',$get_id)->count();
        $leave_notification_count = DB::table('leave_notifications')->where('leaves_id',$get_id)->count();
        $leave_notification = DB::table('leave_notifications')->where('employees_id',$get_id)->get();
        return view('template/frontend/userdashboard/employeepanel/pages/view_leaves',compact('get_leave','notification','notification_count','leave_notification_count','leave_notification'));
    }
    public function store(Request $request)
    {
        $get_id = auth()->guard('employee')->user()->id;
        $agent_id = auth()->guard('employee')->user()->employer_id;
        $leaves = new Leave();
        $leaves->employee_id = $get_id;
        $leaves->agent_id = $agent_id;
        $leaves->employee_name = $request->employee_name;
        $leaves->email = $request->email;
        $leaves->position = $request->position;
        $leaves->pax_leave = $request->pax_leave;
        $leaves->form_date = $request->form_date;
        $leaves->to_date = $request->to_date;
        $leaves->leave_type_offer ='pending';
        $leaves->leave_reason = $request->leave_reason;
        $leaves->save();
    return redirect()->back()->with('message','Leave ADD NEW Request');
}
    public function save_location(Request $request)
    {
        $get_em = DB::table('employees')->where('is_active','=',0)->get();
        $email = $get_em[0]->email;
        $get_ems = DB::table('employees')->where('email','=',$email)->get();
        $emails = $get_ems[0]->email;
        $ids = $get_ems[0]->id;
        $clientIP = request()->ip();
        $position = Location::get('192.168.10');
        $save_location = new Mondoob_Save_Location();
        $save_location->employee_id = $ids;
        $save_location->employee_email = $emails;
        $save_location->loc_address = $position->cityName;
        $save_location->loc_latitude = $position->latitude;
        $save_location->loc_longitude = $position->longitude;
        $save_location->save();
    }
    public function emp_leaves_paid($id)
    {
        $get_id = auth()->guard('employee')->user()->id;
        $notification = DB::table('tasks')->where('employee_id',$get_id)->get();
        $notification_count = DB::table('tasks')->where('task_status','pending')->where('employee_id',$get_id)->count();
        $leave_notification_count = DB::table('leave_notifications')->where('leaves_id',$get_id)->count();
        $leave_notification = DB::table('leave_notifications')->where('leaves_id',$get_id)->get();
        $leave = DB::table('leave_notifications')->where('id',$id)->first();
        return view('template/frontend/userdashboard/employeepanel/pages/leaves_request',compact('leave','notification','notification_count','leave_notification_count','leave_notification'));
    }
    public function leaves_request_accepted($id)
    {
        $get_id = DB::table('leave_notifications')
        ->where('id', $id)->first();
        $get_leave_id = $get_id->leaves_id;
        print_r($get_id->leaves_id);
        // die();
        $leaves_accecpted = DB::table('leaves')
        ->where('id', $id)
        ->update(['status' => 2,'leave_type_offer' => 'UnPaid']);
        return redirect()->back();        
    }
    public function leaves_request_rejected($id)
    {
        $leaves_rejected = DB::table('leaves')
            ->where('id', $id)
            ->update(['status' => 3,'leave_type_offer' => 'UnPaid']);
            return redirect()->back(); 
    }
    public function mapview(){

        $get_id = auth()->guard('employee')->user()->id;
        $location1 = \DB::table('employees')->where('id',$get_id)->get();
        //dd($location1);
        $ip1 = $_SERVER['REMOTE_ADDR'];
        $ip = substr($ip1, strrpos($ip1, ':'));
        $location = \Location::get($ip);
        //dd($location);
        $insert = new Mondoob_Save_Location;
        $insert->ip = $location->ip;
        $insert->countryName = $location->countryName;
        $insert->countryCode = $location->countryCode;
        $insert->regionCode = $location->regionCode;
        $insert->regionName = $location->regionName;
        $insert->cityName = $location->cityName;
        $insert->zipCode = $location->zipCode;
        $insert->postalCode = $location->postalCode;
        $insert->latitude = $location->latitude;
        $insert->longitude = $location->longitude;
        $insert->areaCode = $location->areaCode;
        $insert->uid = $get_id;
        $insert->landing = 'on page';
        $insert->save();
        //to update ip from frontend
        $insert_id = $insert->id;
        $all_lead = DB::table('bookings')
        ->join('lead_passengers','lead_passengers.email','=','Bookings.lead_passenger_email')

        ->leftjoin('registers','registers.email','=','lead_passengers.email')
        // //dd($all_lead);
        ->select('lead_passengers.email','lead_passengers.status','Bookings.lead_passenger_email','Bookings.lead_passenger_email','Bookings.invoice_no','Bookings.hotel_makkah_checkavailability','Bookings.hotel_madinah_checkavailability','lead_passengers.email','lead_passengers.status','lead_passengers.latitude','lead_passengers.longitude','registers.PnE')
        ->get();  
        // dd($all_lead);          
        //current date
        $get_date = date('Y-m-d');
//dd($get_date);
        foreach ($all_lead as $key => $value) {
                    if (!empty($value->hotel_makkah_checkavailability)) {
                        $hmc = json_decode($value->hotel_makkah_checkavailability);
                        $hmakkahdatecin1 = $hmc->response->checkInDate;
                        $hmakkahdatecin2 = explode("T", $hmakkahdatecin1);
                        $hmakkahdatecout1 = $hmc->response->checkOutDate;
                        $hmakkahdatecout2 = explode("T", $hmakkahdatecout1);
                        //echo $hmakkahdatecout2[0];
                        //dd($hmakkahdatecin2[0]);
                        }
            
          $get_date = date('Y-m-d');
           
            if (!empty($value->hotel_makkah_checkavailability) && ($hmakkahdatecin2[0] >= $get_date|| $hmakkahdatecout2[0] >= $get_date)) {
            //dd($value);
                $makkah_checkavailability = json_decode($value->hotel_makkah_checkavailability);
                //dd($makkah_checkavailability);
                $hmakkahname = $makkah_checkavailability->response->name;
                $hmakkahdatecin = $makkah_checkavailability->response->checkInDate;
                $hmakkahdatecout = $makkah_checkavailability->response->checkOutDate;
                $hmakkahtimecin = $makkah_checkavailability->response->checkInTime;
                $hmakkahtimecout = $makkah_checkavailability->response->checkOutTime;
                $hcity = $makkah_checkavailability->response->city;
                //making data from above deceded makkah_availability
                $all_lead[$key]->ch_hotelmakName = $hmakkahname;
                $all_lead[$key]->ch_hmakCheckdatein =  date('Y-m-d', strtotime($hmakkahdatecin));
                $all_lead[$key]->ch_hmakCheckdateout = date('Y-m-d', strtotime($hmakkahdatecout));
                $all_lead[$key]->ch_makcheckTimein = $hmakkahtimecin;
                $all_lead[$key]->ch_makcheckTimeout = $hmakkahtimecout; 
                $all_lead[$key]->ch_makhotelcity = $hcity;
                 echo "hotel_makkah_checkavailability"; 
                //dd($value);
            }else{}
                        if (!empty($value->hotel_madinah_checkavailability)) {
                        $hmc = json_decode($value->hotel_madinah_checkavailability);
                        $hmadihdatecin1 = $hmc->response->checkInDate;
                        $hmadihdatecin2 = explode("T", $hmadihdatecin1);
                        $hmadihdatecout1 = $hmc->response->checkOutDate;
                        $hmadihdatecout2 = explode("T", $hmadihdatecout1);
                        //echo $hmakkahdatecout2[0];
                        //dd($hmakkahdatecin2[0]);
                        }
            if (!!$madinah_availability = $value->hotel_madinah_checkavailability && ($hmadihdatecout2[0] > $get_date || $hmadihdatecin2[0] >= $get_date)) {
                $madinah_checkavailability = json_decode($value->hotel_madinah_checkavailability);
                // dd($madinah_checkavailability);
                $hmadinahname = $madinah_checkavailability->response->name;
                $hmadinahdatecin = $madinah_checkavailability->response->checkInDate;
                $hmadinahdatecout = $madinah_checkavailability->response->checkOutDate;
                $hmadinahtimecin = $madinah_checkavailability->response->checkInTime;
                $hmadinahtimecout = $madinah_checkavailability->response->checkOutTime;
                $hcity = $madinah_checkavailability->response->city;
                //making data from above deceded makkah_availability
                $all_lead[$key]->ch_hotelmadiName = $hmadinahname;
                $all_lead[$key]->ch_hmadiCheckdatein =  date('Y-m-d', strtotime($hmadinahdatecin));
                $all_lead[$key]->ch_hmadiCheckdateout = date('Y-m-d', strtotime($hmadinahdatecout));
                $all_lead[$key]->ch_madicheckTimein = $hmadinahtimecin;
                $all_lead[$key]->ch_madicheckTimeout = $hmadinahtimecout;
                $all_lead[$key]->ch_madihotelcity = $hcity;
                 echo "hotel_madinah_checkavailability";
                //dd($value); 
            }else{}

       // dd($all_lead);
            if (!!$stays = $value->PnE){
            //dd($stays);
                $js =json_decode($stays);
                //dd($js);
                foreach ($js as $keyz => $value) {
                    // echo json_encode($value);
                    //dd($value);
                        if (!empty($value->StayName == "Madina")) {
                        $madidin = $value->StayDates;
                        // dd($madidin);
                        $madiin = explode("-", $madidin);
                        $madistaydin = $madiin[0];
                        $madistaydout = $madiin[1];
                        $madistaydin1 = str_replace( array('/'), '-', $madistaydin);
                        $madistaydout1 = str_replace( array('/'), '-', $madistaydout);
                        $newmadistaydin = date("Y-m-d", strtotime($madistaydin1));  
                        $newmadistaydout = date("Y-m-d", strtotime($madistaydout1));  

                       
                        //dd($newmadistaydout);
                        }else{}
        
                    if ($value->StayName == "Madina") {
                    $all_lead[$key]->pne_madinastay = $value->StayName;
                    $all_lead[$key]->pne_madistaytimein = $value->TimeIn;
                    $all_lead[$key]->pne_madistaytimeout = $value->TimeOut;
                    $madidin = $value->StayDates;
                    $madiin = explode("-", $madidin);
                    $all_lead[$key]->pne_madistaydin = $madiin[0];
                    $all_lead[$key]->pne_madistaydout = $madiin[1];
                     // echo  json_encode($all_lead[$key]->pne_madinastay);

                    }else{}
                     if (!empty($value->StayName == "Makkah")) {
                        $makdin = $value->StayDates;
                        $makdiin = explode("-", $makdin);
                        $makstaydin = $makdiin[0];
                        $makstaydout = $makdiin[1];
                        $makstaydin1 = str_replace( array('/'), '-', $makstaydin);
                        $makstaydout1 = str_replace( array('/'), '-', $makstaydout);
                        $newmakstaydin = date("Y-m-d", strtotime($makstaydin1));  
                        $newmakstaydout = date("Y-m-d", strtotime($makstaydout1));  
                        
                        }
                    if ($value->StayName == "Makkah") {
                    $all_lead[$key]->pne_makkahstay = $value->StayName;
                    $all_lead[$key]->pne_makstaytimein = $value->TimeIn;
                    $all_lead[$key]->pne_makstaytimeout = $value->TimeOut;
                    $makdin = $value->StayDates;
                    $makin = explode("-", $makdin);
                    $all_lead[$key]->pne_makstaydin = $makin[0];
                    $all_lead[$key]->pne_makstaydout = $makin[1];
                     //echo "Makkah stay";
                    }else{}
                     if (!empty($value->StayName == "Jeddah")) {
                        $jdin = $value->StayDates;
                        $jdiin = explode("-", $jdin);
                        $jstaydin = $jdiin[0];
                        $jstaydout = $jdiin[1];
                        $jstaydin1 = str_replace( array('/'), '-', $jstaydin);
                        $jstaydout1 = str_replace( array('/'), '-', $jstaydout);
                        $newjstaydin = date("Y-m-d", strtotime($jstaydin1));  
                        $newjstaydout = date("Y-m-d", strtotime($jstaydout1));  
                        
                        }
                    if ($value->StayName == "Jeddah") {
                    $all_lead[$key]->pne_jstay = $value->StayName;
                    $all_lead[$key]->pne_jstaytimein = $value->TimeIn;
                    $all_lead[$key]->pne_jstaytimeout = $value->TimeOut;
                    $jdatein = $value->StayDates;
                    $jin = explode("-", $jdatein);
                    $all_lead[$key]->pne_jstaydin = $jin[0];
                    $all_lead[$key]->pne_jstaydout = $jin[1];
                     //echo "Jeddah stay";

                    
                    }else{}
                    //dd($js);
                }
        }
    }
        
        $notification = DB::table('tasks')->where('employee_id',$get_id)->get();
        $notification_count = DB::table('tasks')->where('task_status','pending')->where('employee_id',$get_id)->count();
        $leave_notification_count = DB::table('leave_notifications')->where('leaves_id',$get_id)->count();
        // echo "pre";
        // print_r($all_lead);
        //dd($all_lead);
        $auth = Auth()->guard('employee')->user();
        //dd($auth);
        return view('template/frontend/userdashboard/employeepanel/pages/mlocation',compact('notification_count','leave_notification_count','location','insert_id','get_id','notification','all_lead','auth'));
    }
    public function update_ip1(Request $request,$id){ 
        $uid = $request->uid;
        $insert = ($request->ip);
        $jh = $insert['ip'];
        $flight = Mondoob_Save_Location::find($id);
        $flight->ip = $jh;
        $flight->update();
        print_r($flight);
    }
     public function update_travel(Request $req){
        $distance = $req->time;
        $start = $req->first;
        $end = $req->second;
        $user_id = $req->uid;
        $insert = new mandoob_travel;
        $insert->starting_point = $start;
        $insert->end_point = $end;
        $insert->route = $distance;
        $insert->uid = $user_id;
        $insert->save();
    }
    
}
