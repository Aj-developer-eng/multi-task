<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead_Passenger_location;
use App\Models\travel;
use App\Models\LeadPassenger;
use App\Models\register;
use DateTime;
use DateTimeZone;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\Scheduling\Schedule;
use Stevebauman\Location\Facades\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class apicontroller extends Controller
{
     public function lead_login_submit(Request $request)
    {
            $this->validate($request, [
                'email' => 'required'
            ]);
            $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];
            if ($login = Auth::guard('lead_passenger')->attempt($credentials))
            {
                $agent = LeadPassenger::where('email',$request->email)->first();
                $token_result = $agent->createToken('dow-App')->plainTextToken;

            return response()->json(['result'=>$token_result,'lead'=>$agent]);
                // return redirect()->intended('dashboard');
            }else{
            return response()->json(['response'=>'Credentials are not right']);
            }
    }
     public function lead_logout(Request $request)
    {   
            $data=$request->user()->currentAccessToken()->delete();
            return response()->json(['response'=>'Logout is done successfully']);           
    }
     public function lead_passenger_location1()
    {
            $get_id = auth()->guard('lead_passengers-api')->user();
            $location1 = DB::table('lead__passenger_locations')->where('uid',$get_id)->get();
            $get_id = Auth::guard('lead_passengers-api')->user()->id;
        //code to point stay markers os user
            $detail = array();
            if (!empty($get_id)){
            $user_stay_marker = register::find($get_id)->pluck('PnE');
                foreach ($user_stay_marker as $key => $value) {
                array_push($detail,json_decode($value));
                }
            }
               return Redirect::to('dashboard/lead_passenger_location');
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
    public function ip_location(Request $req){

                //dd($req);
            $insert = new Lead_Passenger_location;
            $insert->login = $req->login;
            $insert->logout = $req->logout;
            $insert->landing = $req->landing;
            // $insert->ip = $req->ip;
            $insert->countryName = $req->countryName;
            $insert->countryCode = $req->countryCode;
            $insert->regionCode = $req->regionCode;
            $insert->regionName = $req->regionName;
            $insert->cityName = $req->cityName;
            $insert->zipCode = $req->zipCode;
            $insert->postalCode = $req->postalCode;
            $insert->latitude = $req->latitude;
            $insert->longitude = $req->longitude;
            $insert->areaCode = $req->areaCode;
            $insert->uid = $req->uid;
            $insert->save();
            return response()->json(['response'=>$req]);
    }
     public function register_process(Request $req){
        
        // foreach ($req->staydetails['stay_at'] as $key => $value) {
         
        //     dd($value);
        //     $stay_details[$key][] = $value; 
        //     array_push($stay_details[$key],$req->staydetails['stay_date'][$key],$req->staydetails['check_in'][$key],$req->staydetails['check_out'][$key]);
        // }
        //dd($req);
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
            $save->PnE = $req->PnE;
            $save->save();
           return response()->json(['save'=>$save]);

           die();
    }
    public function indexapi()
    {
        $get_email = Auth()->guard('lead_passengers-api')->user()->email;
        // dd($get_email);
        $get_bookings = DB::table('bookings')->where('lead_passenger_email', $get_email)->get();
        $get_id = DB::table('task_details')->where('email', $get_email)->orderBy('id', 'DESC')->first('employee_id');
        // dd($get_id);
        $get_ids = $get_id->employee_id;
        $get_detail = DB::table('tasks')->get();
        $get_user = DB::table('task_details')->where('email', $get_email)->orderBy('id', 'DESC')->get();
        
         return response()->json(['response'=>$get_bookings,$get_detail]);
    }
    public function lead_passenger_location()
    {
        $get_id = auth()->guard('lead_passengers-api')->user()->id;
        $location1 = \DB::table('b2b_dow_laravel.lead__passenger_locations')->where('uid',$get_id)->get();
        $ip1 = $_SERVER['REMOTE_ADDR'];
        $ip = substr($ip1, strrpos($ip1, ':'));
        $location = \Location::get($ip);
        
       
        $get_id = Auth::guard('lead_passengers-api')->user()->id;
        //code to point stay markers os user
        $get_email = Auth::guard('lead_passengers-api')->user()->email;
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
            $user_stay_marker = register::find($get_id)->pluck('PnE')[0];
            //dd($user_stay_marker);
            if($user_stay_marker!=""){
                $data = json_decode($user_stay_marker);
                // dd($data);
                foreach ($data as $key => $value) {
                    //dd($value);
                    if($value->StayName=='Makkah' && !empty($hotel_makkah_checkavailability)){

                        $data[$key]->HotelName = $hmakkahname;
                        $data[$key]->checkin = $hmakkahin;
                        $data[$key]->checkout = $hmakkahout;

                    } else if($value->StayName=='Madinah' && !empty($hotel_madinah_checkavailability)){
    
                        $data[$key]->HotelName = $hmadinahname;
                        $data[$key]->checkin = $hmadinahin;
                        $data[$key]->checkout = $hmadinahout;
                        
                    }
                }
            }
        }
        $detail = $data;
       // return view('template/frontend/userdashboard/employeepanel/pages/lead_passenger_location',compact('location','insert_id','get_id','detail'));
         // return response()->json(['response'=>$location,$get_id,$detail]);


         return response()->json(['location'=>$location,'get_id'=>$get_id,'detail'=>$detail]);
    }

}
