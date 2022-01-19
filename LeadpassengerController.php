<?php

namespace App\Http\Controllers;

use App\Models\Lead_Passenger_location;
use App\Models\travel;
use App\Models\Booking;
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
use Mail;
use App\Mail\updatetomandoob;


class LeadpassengerController extends Controller
{

    public function index()
    {
        $get_email = Auth()->guard('lead_passenger')->user()->email;
        $get_bookings = DB::table('bookings')->where('lead_passenger_email', $get_email)->get();
        //dd($get_bookings);
        $get_id = DB::table('task_details')->where('email', $get_email)->orderBy('id', 'DESC')->first('employee_id');
        $get_ids = $get_id->employee_id;
        $get_detail = DB::table('tasks')->get();
        //dd($get_detail);
        return view('template/frontend/userdashboard/employeepanel/index', compact('get_bookings', 'get_detail'));
    }
    public function lead_passenger_location()
    {
        $get_id = auth()->guard('lead_passenger')->user()->id;
        
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
       
        //code to point stay markers of user
        $get_email = Auth::guard('lead_passenger')->user()->email;
        $invoiveno = DB::table('lead_passengers')
        ->join('Bookings','lead_passengers.email','=','Bookings.lead_passenger_email')
        ->select('Bookings.invoice_no','Bookings.hotel_makkah_checkavailability','Bookings.hotel_madinah_checkavailability','Bookings.transfer_checkavailability','lead_passengers.assigned_mandoob_id')
        ->where('lead_passengers.email','=',$get_email)
        ->get();
        //dd($invoiveno);
         $employee_id = $invoiveno[0]->assigned_mandoob_id;
        // dd($employee_id);
        $related_mandoob = DB::table('employees')
        ->where('id','=',$employee_id)
        ->select('email','position','image','address_latitude','address_longitude','contact_info','gender')
        ->get();
        

        if (!$invoiveno->isEmpty()){
            $hotel_makkah_checkavailability = json_decode($invoiveno[0]->hotel_makkah_checkavailability);
        if (is_null($hotel_makkah_checkavailability) || empty($hotel_makkah_checkavailability)) {
        dd($hotel_makkah_checkavailability);
        
            $hmakkahname = $hotel_makkah_checkavailability->response->name;
            $hmakkahin = $hotel_makkah_checkavailability->response->checkInTime;
            $hmakkahout = $hotel_makkah_checkavailability->response->checkOutTime;
            $hmakkahdatein = $hotel_makkah_checkavailability->response->checkInDate;
            $hmakkahdateout = $hotel_makkah_checkavailability->response->checkOutDate;
        }
            $hotel_madinah_checkavailability = json_decode($invoiveno[0]->hotel_madinah_checkavailability);
            
        if (!empty($hotel_madinah_checkavailability)) {
            $hmadinahname = $hotel_madinah_checkavailability->response->name;
            $hmadinahin = $hotel_madinah_checkavailability->response->checkInTime;
            $hmadinahout = $hotel_madinah_checkavailability->response->checkOutTime;
            $hmadinahdatein = $hotel_madinah_checkavailability->response->checkInDate;
            $hmadinahdateout = $hotel_madinah_checkavailability->response->checkOutDate;   
        }
        
        $detail = array();
        
        if (!empty($get_id)) {
            $user_stay_marker = register::find($get_id);
            $user_stay_marker = $user_stay_marker->PnE;
            if($user_stay_marker != ""){
             //dd($user_stay_marker);
                $data = json_decode($user_stay_marker);
                //dd($data);
                foreach ($data as $key => $value) {
                    

                    if($value->StayName == "Makkah" || !empty($hotel_makkah_checkavailability)){
                        //dd("makkah");
                        if (!empty($hmakkahname))
                        {
                        $data[$key]->ch_makHotelName = $hmakkahname;
                        $data[$key]->ch_makcheckin = $hmakkahin;
                        $data[$key]->ch_makcheckout = $hmakkahout;
                        $data[$key]->ch_hmakindate = date('Y-m-d', strtotime($hmakkahdatein));
                        $data[$key]->ch_hmakoutdate = date('Y-m-d', strtotime($hmakkahdateout));
                        }
                        $data[$key]->mandoob_email = $related_mandoob[0]->email;
                        $data[$key]->mandoob_position = $related_mandoob[0]->position;
                        $data[$key]->mandoob_image = $related_mandoob[0]->image;
                        if (!empty($value->StayName)) 
                        {
                        $data[$key]->pne_makstaytin = $value->TimeIn;
                        $data[$key]->pne_makstaytout = $value->TimeOut;
                        $makdin = $value->StayDates;
                        $makin = explode("-", $makdin);
                        $data[$key]->pne_makstaydin = $makin[0];
                        $data[$key]->pne_makstaydout = $makin[1];
                        }
                    }
                    if($value->StayName == "Madina" || !empty($hotel_madinah_checkavailability)){
                         //dd("madina");
                        if (!empty($hmadinahname)) 
                        {
                        $data[$key]->ch_madiHotelName = $hmadinahname;
                        $data[$key]->ch_madicheckin = $hmadinahin;
                        $data[$key]->ch_madicheckout = $hmadinahout;
                        $data[$key]->ch_hmadiindate = date('Y-m-d', strtotime($hmadinahdatein));
                        $data[$key]->ch_hmadioutdate = date('Y-m-d', strtotime($hmadinahdateout));
                        }
                        $data[$key]->mandoob_email = $related_mandoob[0]->email;
                        $data[$key]->mandoob_position = $related_mandoob[0]->position;
                        $data[$key]->mandoob_image = $related_mandoob[0]->image; 
                        if (!empty($value->StayName)) 
                        {
                        $data[$key]->pne_madistaytin = $value->TimeIn;
                        $data[$key]->pne_madistaytout = $value->TimeOut;
                        $madidin = $value->StayDates;
                        $madiin = explode("-", $madidin);
                        $data[$key]->pne_madistaydin = $madiin[0];
                        $data[$key]->pne_madistaydout = $madiin[1];
                         }  
                    }
                    if($value->StayName == "Jeddah"){
                        // dd($value->StayName);
                       $date = $value->StayDates;
                        $jinout = explode("-", $date);

                       $data[$key]->jStayDatesin = $jinout[0];
                       $data[$key]->jstayDateout = $jinout[1];
                       // $data[$key]->TimeOut2 = $value->TimeOut;
                    }
                }


            }
        }
       
        $detail = $data;
        //dd($detail);   

        return view('template/frontend/userdashboard/employeepanel/pages/lead_passenger_location',compact('location','insert_id','get_id','detail','related_mandoob'));
    }else{
        dd("wrong");
        // return view('template/frontend/userdashboard/employeepanel/pages/lead_passenger_location',compact('location','insert_id','get_id'));
         }
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
            $get_id = \DB::table('lead_passengers')->where('email', '=', $request->email)->first();
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
            'longitude'=> $data->longitude
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
    public function update_travel(Request $req)
            {
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
             $lead_passenger_emailz = Auth()->guard('lead_passenger')->user()->email;
             //dd($lead_passenger_emailz);
             $lead_passenger_booking = DB::table('bookings')->where('lead_passenger_email','=', $lead_passenger_emailz)->get();
          //dd($lead_passenger_booking);
            
// foreach ($lead_passenger_booking as $lead_passenger_booking) 
// {
//     $invoive_no=$lead_passenger_booking->invoice_no;
//     $hotel_makkah_checkavailability=json_decode($lead_passenger_booking->hotel_makkah_checkavailability);
//     $hotel_madinah_checkavailability=json_decode($lead_passenger_booking->hotel_madinah_checkavailability);
//    print_r($invoive_no);
//    print_r($hotel_makkah_checkavailability);
//    print_r($hotel_madinah_checkavailability);

// }
// die();



             if (!empty($lead_passenger_booking)) {
                foreach ($lead_passenger_booking as $key => $value) {
             //      dd($value);
                if (!!$value && !empty($value->hotel_makkah_checkavailability)) {
                
                $makkah_availability = json_decode($value->hotel_makkah_checkavailability);
            
                $hcheckInDate1 = $makkah_availability->response->checkInDate;
                $hmakcheckInDate = date('d/m/Y', strtotime($hcheckInDate1));
                $hcheckOutDate1 = $makkah_availability->response->checkOutDate;
                $hmakcheckOutDate = date('d/m/Y', strtotime($hcheckOutDate1));
                $hmakcheckInTime = $makkah_availability->response->checkInTime;
                $hmakcheckOutTime = $makkah_availability->response->checkOutTime;
                $makcity_name = $makkah_availability->response->city;
                $makkah_booking_invoice_no = $lead_passenger_booking[0]->invoice_no;
                //dd($booking_invoice_no);
                // $booking_details = array();
                // // $booking_details["makkah"] = array();
                // //$basename(path)ooking_details["hmakcheckindate"] = array();
                // $booking_details['makkah'] =array();

                // array_push($booking_details['makkah'],[ 
                //     'hmakcheckindate' => $hmakcheckInDate,
                //     'hmakcheckoutdate' => $hmakcheckOutDate,
                //     'hmakcheckintime' => $hmakcheckInTime,
                //     'hmakcheckouttime' => $hmakcheckOutTime,
                //     'hmakcity_name' => $city_name,
                //     'hmakbooking_invoice_no' => $booking_invoice_no
                // ]);
               // dd($booking_details);
                }

            if (!!$value && !empty($value->hotel_madinah_checkavailability)) {
             $madina_availability = json_decode($value->hotel_madinah_checkavailability);

            $hcheckInDate1 = $madina_availability->response->checkInDate;
            $hmadicheckInDate = date('d/m/Y', strtotime($hcheckInDate1));
            $hcheckOutDate1 = $madina_availability->response->checkOutDate;
            $hmadicheckOutDate = date('d/m/Y', strtotime($hcheckOutDate1));
            $hmadicheckInTime = $madina_availability->response->checkInTime;
            $hmadicheckOutTime = $madina_availability->response->checkOutTime;
            $madicity_name = $madina_availability->response->city;
            $nadina_booking_invoice_no = $lead_passenger_booking[0]->invoice_no;
           // dd($booking_invoice_no);
           // $booking_details = array();

            // $booking_details['madina'] =array();

            //     array_push($booking_details['madina'],[ 
            //         'hmadicheckindate' => $hmadicheckInDate,
            //         'hmadicheckoutdate' => $hmadicheckOutDate,
            //         'hmadicheckintime' => $hmadicheckInTime,
            //         'hmadicheckouttime' => $hmadicheckOutTime,
            //         'hmadicity_name' => $city_name,
            //         'hmadibooking_invoice_no' => $booking_invoice_no
            //     ]);
            // array_push($booking_details, $booking_details["hmadicheckindate"] = $hmadicheckInDate, $booking_details["hmadicheckoutdate"] = $hmadicheckOutDate, $booking_details["hmadicheckintime"] = $hmadicheckInTime, $booking_details["hmadicheckouttime"] = $hmadicheckOutTime, $booking_details["hmadicity_name"] = $city_name, $booking_details["hmadibooking_invoice_no"] = $booking_invoice_no);
                    }
                }
            $lead_passenger_full = json_decode($lead_passenger_booking[0]->lead_passenger_details);

                   
           // dd($booking_details);
            return view('template/frontend/userdashboard/employeepanel/pages/register',compact('hmakcheckInDate','hmakcheckOutDate','hmakcheckInTime','hmakcheckOutTime','makcity_name','hmadicheckInDate','hmadicheckOutDate','hmadicheckInTime','hmadicheckOutTime','madicity_name','makkah_booking_invoice_no','nadina_booking_invoice_no','lead_passenger_full'));
     }else{
            return redirect('dashboard')->with('status', 'We havent Recived Your Booking.So you cannot Access this Page until you made Booking with the help of Umrah Operator !');
     }
      
    }
    public function register_process(Request $req){
        //dd($req);
         $data = [];
        foreach ($req->staydetails['stay_at'] as $key => $value) {
                 $data[] = array(
                'StayName'=> $value,
                'StayDates' => $req->staydetails['stay_date'][$key],
                'TimeIn' => $req->staydetails['check_in'][$key],
                'TimeOut' => $req->staydetails['check_out'][$key]
            );
        }
        $save = new register;
        $save->email = $req->email;
        $save->mobile = $req->mobile;
        $save->dateofbirth = $req->dateofbirth;
        $save->passport = $req->passport;
        $save->nationality = $req->nationality;
        $save->gender = $req->gender;
        $save->arrivalairpot = $req->arrivalairport;
        $save->arrivalflight = $req->arrivalflight;
        $save->arrivaldate = $req->arrivaldate;
        $save->arrivaltime = $req->arrivaltime;
        $save->departureairport = $req->departureairport;
        $save->departureflight = $req->departureflight;
        $save->departuredate = $req->departuredate;
        $save->departuretime = $req->departuretime;
        $save->PnE = json_encode($data);
        $save->save();
        //dd($save);

        $lead_user_email = Auth()->guard('lead_passenger')->user()->email;
        $user = DB::table('task_details')
        ->where('email','=',$lead_user_email)
        ->select('email')
        ->first();
        
if (!empty($user)) {
          
        //dd($info);die(); 
$updates = [
        'title' => [
            'email' => $req->email,
            'mobile' => $req->mobile,
            'passport' => $req->passport,
            'nationality' => $req->nationality,
            'gender' => $req->gender,
            'landing_airport' => $req->arrivalairpot,
            'landing_flight' => $req->arrivalflight,
            'landing_date' => $req->arrivaldate,
            'landing_time' => $req->arrivaltime,
            'dep_airport' => $req->departureairport,
            'dep_flight' => $req->departureflight,
            'dep_date' => $req->departuredate,
            'dep_time' => $req->departuretime,
        ],

        'body' => json_decode($save->PnE)
        
    ];

        //\Mail::to($user)->send(new updatetomandoob($updates));
        
        return redirect('dashboard')->with('status', 'Plan Is Registered Successfully!');
}
        
return redirect('dashboard')->with('status', 'Your not Registered in our System!');
    
        
    

        
    }
    public function invoice_finding(Request $req){

    $invoice = $req->invoice_no;
    $booking = DB::table('Bookings')->where('invoice_no','=', $invoice)->get();

    $invoice_detail = array();
 // dd($booking);
if (!empty($booking[0]->hotel_makkah_checkavailability)) {
    $makjson = json_decode($booking[0]->hotel_makkah_checkavailability);
     
// dd($makjson);
    $mak_city = $makjson->response->city;
    $hmakcheckInDate1 = $makjson->response->checkInDate;
    $hmakcheckInDate = date('d/m/Y', strtotime($hmakcheckInDate1));

    $hmakcheckOutDate1 = $makjson->response->checkOutDate;
    $hmakcheckOutDate = date('d/m/Y', strtotime($hmakcheckOutDate1));

    $hmakcheckInTime = $makjson->response->checkInTime;
    $hmakcheckOutTime = $makjson->response->checkOutTime;
    array_push($invoice_detail, $mak_city, $hmakcheckInDate, $hmakcheckOutDate,  $hmakcheckInTime, $hmakcheckOutTime); 

}
if (!empty($booking[0]->hotel_madinah_checkavailability)) {
    $madijson = json_decode($booking[0]->hotel_madinah_checkavailability);
    $madi_city = $madijson->response->city;
    $hmadicheckInDate1 = $madijson->response->checkInDate;
    $hmadicheckInDate = date('d/m/Y', strtotime($hmadicheckInDate1));

    $hmadicheckOutDate1 = $madijson->response->checkOutDate;
    $hmadicheckOutDate = date('d/m/Y', strtotime($hmadicheckOutDate1));
    
    $hmadicheckInTime = $madijson->response->checkInTime;
    $hmadicheckOutTime = $madijson->response->checkOutTime;
    array_push($invoice_detail, $madi_city, $hmadicheckInDate, $hmadicheckOutDate,  $hmadicheckInTime, $hmadicheckOutTime);

}

return $invoice_detail;

    }
    
}

