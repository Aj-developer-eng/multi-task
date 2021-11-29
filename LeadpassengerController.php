<?php

namespace App\Http\Controllers;

use App\Models\Lead_Passenger_location;
use App\Models\travel;
use App\Models\LeadPassenger;
use App\Models\register;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\Scheduling\Schedule;
use Stevebauman\Location\Facades\Location;
use Carbon\Carbon;
use DB;

class LeadpassengerController extends Controller
{

    public function index()
    {
        $get_email = Auth()->guard('lead_passenger')->user()->email;
        $get_bookings = DB::table('b2b_dow_laravel.bookings')->where('lead_passenger_email', $get_email)->get();
        $get_id = DB::table('b2b_dow_laravel.task_details')->where('email', $get_email)->orderBy('id', 'DESC')->first('employee_id');
        $get_ids = $get_id->employee_id;
        $get_detail = DB::table('b2b_dow_laravel.tasks')->get();
        return view('template/frontend/userdashboard/employeepanel/index', compact('get_bookings', 'get_detail'));
    }
   
    public function lead_passenger_location()
    {
        $get_id = auth()->guard('lead_passenger')->user()->id;
        $location1 = \DB::table('b2b_dow_laravel.lead__passenger_locations')->where('uid',$get_id)->get();
        $ip1 = $_SERVER['REMOTE_ADDR'];
        $ip = substr($ip1, strrpos($ip1, ':'));
        $location = \Location::get($ip);
        
        $insert = new Lead_Passenger_location;
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
        $insert_id = $insert->id;
        $get_id = Auth::guard('lead_passenger')->user()->id;
        //code to point stay markers os user
        $get_email = Auth::guard('lead_passenger')->user()->email;
        $invoiveno = DB::table('lead_passengers')
        ->join('Bookings','lead_passengers.email','=','Bookings.lead_passenger_email')
        ->select('Bookings.invoice_no','Bookings.hotel_makkah_checkavailability','Bookings.hotel_madinah_checkavailability','Bookings.transfer_checkavailability')
        ->where('lead_passengers.email','=',$get_email)
        ->get();

        $hotel_makkah_checkavailability = json_decode($invoiveno[0]->hotel_makkah_checkavailability);
        $hmakkahname = '';
        $hmakkahin = '';
        $hmakkahout = '';
        if (!empty($hotel_makkah_checkavailability)) {
        $hmakkahname = $hotel_makkah_checkavailability->response->name;
        $hmakkahin = $hotel_makkah_checkavailability->response->checkInTime;
        $hmakkahout = $hotel_makkah_checkavailability->response->checkOutTime;
        }
        $hotel_madinah_checkavailability = json_decode($invoiveno[0]->hotel_madinah_checkavailability);
        $hmadinahname = '';
        $hmadinahin = '';
        $hmadinahout = '';
        if (!empty($hotel_madinah_checkavailability)) {
            $hmadinahname = $hotel_madinah_checkavailability->response->name;
            $hmadinahin = $hotel_madinah_checkavailability->response->checkInTime;
            $hmadinahout = $hotel_madinah_checkavailability->response->checkOutTime;    
        }
        $detail = array();
        if (!empty($get_id)) {
             // dd($get_id);
            $user_stay_marker = register::find($get_id);

           
            $user_stay_marker=$user_stay_marker->PnE;

             //dd($user_stay_marker);
            if($user_stay_marker!=""){
                $data = json_decode($user_stay_marker);
                //dd($data);
                foreach ($data as $key => $value) {
                    //dd($value);
                    if($value->StayName=='Makkah' && !empty($hotel_makkah_checkavailability)){
                // dd("Makkah");
                       
                        // if ($hmakkahname != null) {
                        //     $data[$key]->HotelName = $hmakkahname;
                        // }else{
                        //     $data[$key]->HotelName = "";
                        // }
                        //  if ($hmakkahin != null) {
                        //     $data[$key]->checkin = $hmakkahin;
                        // }else{
                        //     $data[$key]->HotelName = "";
                        // }
                        //  if ($hmakkahout != null) {
                        //     $data[$key]->checkout = $hmakkahout;
                        // }else{
                        //     $data[$key]->HotelName = "";
                        // }
                        $data[$key]->HotelName = $hmakkahname;
                        $data[$key]->checkin = $hmakkahin;
                        $data[$key]->checkout = $hmakkahout;
                         //print_r($data[$key]);die;

                    } else if($value->StayName=='Madinah' && !empty($hotel_madinah_checkavailability)){
                        

                        // if ($hmadinahname != null) {
                        //     $data[$key]->HotelName = $hmadinahname;
                        // }else{
                        //     $data[$key]->HotelName = "";
                        // }
                        //  if ($hmadinahin != null) {
                        //     $data[$key]->checkin = $hmadinahin;
                        // }else{
                        //     $data[$key]->checkin = "";
                        // }
                        //  if ($hmadinahout != null) {
                        //     $data[$key]->checkout = $hmadinahout;
                        // }else{
                        //     $data[$key]->checkout = "";
                        // }
                        $data[$key]->HotelName = $hmadinahname;
                        $data[$key]->checkin = $hmadinahin;
                        $data[$key]->checkout = $hmadinahout;
                        
                    }
                }
            }
        }
        $detail = $data;
                        
            //dd($detail);
        return view('template/frontend/userdashboard/employeepanel/pages/lead_passenger_location',compact('location','insert_id','get_id','detail'));
    }
    public function lead_login()
    {
        return view('template/frontend/userdashboard/employeepanel/pages/login');
    }
    public function lead_login_submit(Request $request)
    {
            $this->validate($request, [
                'email' => 'required'
            ]);

            $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];

            if ($login = Auth::guard('lead_passenger')->attempt($credentials)) {
                $get_id = \DB::table('b2b_dow_laravel.lead_passengers')->where('email', '=', $request->email)->first();
                $user_ids = $get_id->id;
                $ip1 = $_SERVER['REMOTE_ADDR'];
                //dd($ip1);
                $ip = substr($ip1, strrpos($ip1, ':'));
                $data = \Location::get($ip);
                //dd($data);
                $insert = new Lead_Passenger_location;
                $insert->login = 'login';
                $insert->ip = $data->ip;
                $insert->countryName = $data->countryName;
                $insert->countryCode = $data->countryCode;
                $insert->regionCode = $data->regionCode;
                $insert->regionName = $data->regionName;
                $insert->cityName = $data->cityName;
                $insert->zipCode = $data->zipCode;
                $insert->postalCode = $data->postalCode;
                $insert->latitude = $data->latitude;
                $insert->longitude = $data->longitude;
                $insert->areaCode = $data->areaCode;
                $insert->uid = $user_ids;
                $insert->save();

                $status = DB::table('lead_passengers')->where('id', $user_ids)->update([
                'status'=> 1
                ]);
                return redirect()->intended('dashboard');
            }
    }
                public function lead_logout()
                {   
                $ip1 = $_SERVER['REMOTE_ADDR'];
                //dd($ip1);
                $ip = substr($ip1, strrpos($ip1, ':'));
                $data = \Location::get($ip);
                //dd($data);
                $user_ids = auth()->guard('lead_passenger')->user()->id;
                $insert = new Lead_Passenger_location;
                $insert->logout = 'logout';
                $insert->ip = $data->ip;
                $insert->countryName = $data->countryName;
                $insert->countryCode = $data->countryCode;
                $insert->regionCode = $data->regionCode;
                $insert->regionName = $data->regionName;
                $insert->cityName = $data->cityName;
                $insert->zipCode = $data->zipCode;
                $insert->postalCode = $data->postalCode;
                $insert->latitude = $data->latitude;
                $insert->longitude = $data->longitude;
                $insert->areaCode = $data->areaCode;
                $insert->uid = $user_ids;
                $insert->save();

                $status = DB::table('lead_passengers')->where('id', $user_ids)->update([
                'status'=> 0,
                'latitude'=> $data->latitude,
                'logitude'=> $data->longitude
                ]);
                Auth::guard('lead_passenger')->logout();
                return redirect('login');
    }
    public function update_ip1(Request $request,$id)
    { 

        $uid = $request->uid;
        $insert = ($request->ip);
        $jh = $insert['ip'];
        $flight = Lead_Passenger_location::find($id);
        $flight->ip = $jh;
        $flight->update();
        print_r($flight);
    }
    public function update_travel(Request $req){
        
        $distance = $req->time;
        $start = $req->first;
        $end = $req->second;
        $user_id = $req->uid;
        $insert = new travel;
        $insert->starting_point = $start;
        $insert->end_point = $end;
        $insert->route = $distance;
        $insert->uid = $user_id;
        $insert->save();
    }
    public function register_view(){
        return view('template/frontend/userdashboard/employeepanel/pages/register');
    }
    public function register_process(Request $req){
        //dd($req);
         // echo '<pre>';
         // print_r($_POST);
         $data = [];
        // $stay_details = array();
        // dd($req->staydetails['stay_at']);
        foreach ($req->staydetails['stay_at'] as $key => $value) {
            // $stay_details[$key][] = $value; 
            $data[] = array(
                'StayName'=> $value,
                'StayDates' => $req->staydetails['stay_date'][$key],
                'TimeIn' => $req->staydetails['check_in'][$key],
                'TimeOut' => $req->staydetails['check_out'][$key]
            );
            // array_push($stay_details[$key],$req->staydetails['stay_date'][$key],$req->staydetails['check_in'][$key],$req->staydetails['check_out'][$key]);
        }
       
        $save = new register;
        $save->email = $req->email;
        $save->mobile = $req->mobile;
        $save->dateofbirth = $req->dateofbirth;
        $save->passport = $req->passport;
        $save->nationality = $req->nationality;
        $save->gender = $req->gender;
        $save->arrivalairpot = $req->arrivalairpot;
        $save->arrivalflight = $req->arrivalflight;
        $save->arrivaldate = $req->arrivaldate;
        $save->arrivaltime = $req->arrivaltime;
        $save->departureairport = $req->departureairport;
        $save->departureflight = $req->departureflight;
        $save->departuredate = $req->departuredate;
        $save->departuretime = $req->departuretime;
        // $save->PnE = json_encode($stay_details);
        $save->PnE = json_encode($data);
        $save->save();
        return redirect('dashboard')->with('status', 'Plan Is Registered Successfully!');
    }
    
}
