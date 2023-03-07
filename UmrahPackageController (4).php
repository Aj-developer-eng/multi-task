<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\Tour;
use App\Models\Active;
use App\Models\country;
use App\Models\Activities;
use App\Models\alhijaz_Notofication;
use DB;
use Carbon\Carbon;

class UmrahPackageController extends Controller
{
    
    public function mange_currency_api(){
        $currency = country::all();
        $exchange_currency = country::all();
        return response()->json(['currency'=>  $currency,'exchange_currency'=>  $exchange_currency]);
    }
    
    public function mange_currency_submit_api(Request $request){
            $customer_id=$request->customer_id;
            $purchase_currency=$request->purchase_currency;
         $sale_currency=$request->sale_currency;
         $exchange_rate=$request->exchange_rate;
         $conversion_type=$request->conversion_type;
        
       
       $data = DB::table('mange_currencies')->insert([
           
           'customer_id'=>$customer_id,
           'purchase_currency'=>$purchase_currency,
           'sale_currency'=>$sale_currency,
           'exchange_rate'=>$exchange_rate,
           'conversion_type'=>$conversion_type,
           
           ]);
           return response()->json(['Status'=>'Successful']);
           
    }
    
    public function hotel_markup(){
        return view('template/frontend/userdashboard/pages/hotel_markup/all_markup');

    }
    
    public function payment_messages(Request $request){
        $customer_id   = $request->customer_id;
        $data = DB::table('h_payment_messages')->where('customer_id',$customer_id)->get();
        return response()->json(['data'=>  $data,]);
    }
    
    public function submit_payment_messages(Request $request){
        $customer_id   = $request->customer_id;
        $payment_message_refundable   = $request->payment_message_refundable;
        $payment_message_non_refundable   = $request->payment_message_non_refundable;
        $data = DB::table('h_payment_messages')->insert([
            'customer_id'=>$customer_id,
            'payment_message_refundable'=>$payment_message_refundable,
            'payment_message_non_refundable'=>$payment_message_non_refundable,
            ]);
        return response()->json(['data'=>  $data,]);
    }
     
    public function edit_payment_messages(Request $request){
        $id   = $request->id;
        $payment_message_refundable   = $request->payment_message_refundable;
        $payment_message_non_refundable   = $request->payment_message_non_refundable;
        $data = DB::table('h_payment_messages')->where('id',$id)->update([
          
            'payment_message_refundable'=>$payment_message_refundable,
            'payment_message_non_refundable'=>$payment_message_non_refundable,
            ]);
        return response()->json(['data'=>  $data,]);
    }
    
    public function payment_detact(Request $request,$id){
       $amount_paid=$request->amount_paid;
       $total_amount=$request->total_amount;
       $remaining_amount=$request->remaining_amount;
       $recieved_amount=$request->recieved_amount;
        $data = DB::table('hotel_payment_details')->insert([
            
           'hotel_search_id'=>$id,
            'recieved_amount'=>$recieved_amount,
            'total_amount'=>$total_amount,
            'remaining_amount'=>$remaining_amount,
            'amount_paid'=>$amount_paid,
            ]);
             return response()->json(['data'=>  $data,]);
    }
    
    public function view_Tour_PopUp(Request $request){
        $id   = $request->id;
        $data = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$id)->first();
        // $no_of_pax_details = DB::table('cart_details')->where('tour_id',$id)->join('tours_bookings','cart_details.booking_id','tours_bookings.id')->get();
        // $total_adults = DB::table('cart_details')->where('tour_id',$id)->join('tours_bookings','cart_details.booking_id','tours_bookings.id')->sum('adults');
        // $total_childs = DB::table('cart_details')->where('tour_id',$id)->join('tours_bookings','cart_details.booking_id','tours_bookings.id')->sum('childs');
        return response()->json([
            'data'              =>  $data,
            // 'no_of_pax_details' =>  $no_of_pax_details,
            // 'total_adults'      =>  $total_adults,
            // 'total_childs'      =>  $total_childs,
        ]);
    }
    
    public function occupancy_tour(Request $request){
        $id   = $request->id;
        
        $data = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$id)->first();
        
        $no_of_pax_details  =   DB::table('cart_details')
                                ->join('tours_bookings','cart_details.booking_id','tours_bookings.id')
                                ->where('cart_details.tour_id',$id)
                                ->where('cart_details.pakage_type','tour')
                                ->get();
                            
        $total_adults       =   DB::table('cart_details')
                                ->join('tours_bookings','cart_details.booking_id','tours_bookings.id')
                                ->where('cart_details.pakage_type','tour')
                                ->where('cart_details.tour_id',$id)
                                ->sum('adults');
                        
        $total_childs       =   DB::table('cart_details')
                                ->join('tours_bookings','cart_details.booking_id','tours_bookings.id')
                                ->where('cart_details.pakage_type','tour')
                                ->where('cart_details.tour_id',$id)
                                ->sum('childs');
                        
        return response()->json([
            'data'              =>  $data,
            'no_of_pax_details' =>  $no_of_pax_details,
            'total_adults'      =>  $total_adults,
            'total_childs'      =>  $total_childs,
        ]);
    }
    
    public function hotel_voucher(Request $request){
        $id=$request->id;
    $data=DB::table('hotel_booking')->where('search_id',$id)->first();
    return response()->json(['data'=>$data]);
    }
    
    public function admin_hotel_voucher(Request $request){
        $id=$request->id;
    $data=DB::table('hotel_provider_bookings')->where('invoice_no',$id)->first();
    return response()->json(['data'=>$data]);
    }
    
    public function submitForm_Airline_Name(Request $request){
        $other_Airline_Name_add=DB::table('airline_name_tb')->insert(['other_Airline_Name'=>$request->other_Airline_Name,'customer_id'=>$request->customer_id]);
        $other_Airline_Name_get=DB::table('airline_name_tb')->where('customer_id',$request->customer_id)->get();
        return response()->json(['success'=>'Visa Other Type Added SuccessFUl!','other_Airline_Name_get'=>$other_Airline_Name_get]);
    }
    
    public function get_other_Airline_Name(Request $request){
        $customer_id=$request->customer_id;
        $airline_Name=DB::table('airline_name_tb')->where('customer_id',$request->customer_id)->get();
        return response()->json(['airline_Name'=>$airline_Name]);  
    }
    
    public function recevied_booking(Request $request){
        
        
        $ratehawk_hotel_booking=DB::table('hotel_booking')->where('provider','ratehawk')->limit(5)->orderBy('id', 'DESC')->get();
        $tbo_hotel_booking=DB::table('hotel_booking')->where('provider','tbo')->limit(5)->orderBy('id', 'DESC')->get();
        $hotelbeds_hotel_booking=DB::table('hotel_booking')->where('provider','hotelbeds')->limit(5)->orderBy('id', 'DESC')->get();
        $travellanda_hotel_booking=DB::table('hotel_booking')->where('provider','travellanda')->limit(5)->orderBy('id', 'DESC')->get();
        //  print_r($tbo_hotel_booking);die();
          
        // print_r($hotel_booking);die();
        return view('template/frontend/userdashboard/pages/hotel_booking/received_booking',compact('ratehawk_hotel_booking','tbo_hotel_booking','hotelbeds_hotel_booking','travellanda_hotel_booking'));
    }
    
    public function partial_paid_booking($id,$provider){
        
        
        $hotel_booking=DB::table('hotel_booking')->where('search_id',$id)->where('provider',$provider)->update([
            
            'payment_status'=> 1,
            
            ]);
       
                return redirect()->back();
        
    }
    
    public function fully_partial_paid_booking($id,$provider){
        
        
        $hotel_booking=DB::table('hotel_booking')->where('search_id',$id)->where('provider',$provider)->update([
            
            'payment_status'=> 2,
            
            ]);
       
       
                return redirect()->back();
        
    }
    
    public function submit_dropof_location(Request $request)
    {
        $customer_id=$request->customer_id;
        $dropof_location=$request->dropof_location;
        $dropof_location_add=DB::table('dropof_location_tb')->insert(['dropof_location'=>$request->dropof_location,'customer_id'=>$request->customer_id]);
        $dropof_location_get=DB::table('dropof_location_tb')->where('customer_id',$request->customer_id)->get();
        return response()->json(['success'=>'Visa Other Type Added SuccessFUl!','dropof_location_get'=>$dropof_location_get]);
    }
    
    public function submit_pickup_location(Request $request)
    {
        $customer_id=$request->customer_id;
        $pickup_location=$request->pickup_location;
        $pickup_location_add=DB::table('pickup_location_tb')->insert(['pickup_location'=>$request->pickup_location,'customer_id'=>$request->customer_id]);
        $pickup_location_get=DB::table('pickup_location_tb')->where('customer_id',$request->customer_id)->get();
        return response()->json(['success'=>'Visa Other Type Added SuccessFUl!','pickup_location_get'=>$pickup_location_get]);
    }
    
    public function get_pickup_dropof_location(Request $request)
    {
        $customer_id=$request->customer_id;
        $pickup_location_get=DB::table('pickup_location_tb')->where('customer_id',$request->customer_id)->get();
        $dropof_location_get=DB::table('dropof_location_tb')->where('customer_id',$request->customer_id)->get();
        return response()->json(['pickup_location_get'=>$pickup_location_get,'dropof_location_get'=>$dropof_location_get]);  
    }
    
    public function submit_other_Hotel_Name(Request $request)
    {
        $customer_id=$request->customer_id;
        $other_Hotel_Name=$request->other_Hotel_Name;
        $hotel_Name_add=DB::table('hotel_Name')->insert(['other_Hotel_Name'=>$request->other_Hotel_Name,'customer_id'=>$request->customer_id]);
        $hotel_Name_get=DB::table('hotel_Name')->where('customer_id',$request->customer_id)->get();
        return response()->json(['success'=>'Other Hotel Name Added SuccessFUl!','hotel_Name_get'=>$hotel_Name_get]);
    }
    
    public function get_other_Hotel_Name(Request $request)
    {
        $customer_id=$request->customer_id;
        $hotel_Name=DB::table('hotel_Name')->where('customer_id',$request->customer_id)->get();
        $hotel_Type=DB::table('hotel_Type')->get();
        return response()->json(['hotel_Name'=>$hotel_Name,'hotel_Type'=>$hotel_Type]);
    }
    
    public function submit_other_visa_type(Request $request)
    {
        $customer_id=$request->customer_id;
        $other_visa_type=$request->other_visa_type;
        $visa_type=DB::table('visa_types')->insert(['other_visa_type'=>$request->other_visa_type,'customer_id'=>$request->customer_id]);
        $visa_type_get=DB::table('visa_types')->where('customer_id',$request->customer_id)->get();
        return response()->json(['success'=>'Visa Other Type Added SuccessFUl!','visa_type_get'=>$visa_type_get]);
    } 
  
    public function get_other_visa_type(Request $request)
    {
        $customer_id=$request->customer_id;
        $visa_type=DB::table('visa_types')->where('customer_id',$request->customer_id)->get();
        return response()->json(['visa_type'=>$visa_type]);  
    }
    
    public function submit_other_Hotel_Type(Request $request)
    {
        $customer_id=$request->customer_id;
        $other_Hotel_Type=$request->other_Hotel_Type;
        $hotel_Type_add=DB::table('hotel_Type')->insert(['other_Hotel_Type'=>$request->other_Hotel_Type,'customer_id'=>$request->customer_id]);
        $hotel_Type_get=DB::table('hotel_Type')->get();
        return response()->json(['success'=>'Other Hotel Type Added SuccessFUl!','hotel_Name_get'=>$hotel_Type_get]);
    }
    
    public function get_other_Hotel_Type(Request $request)
    {
        $customer_id=$request->customer_id;
        $hotel_Type=DB::table('hotel_Type')->get();
        return response()->json(['hotel_Type'=>$hotel_Type]);  
    }
    
    public function listing_umrah_packages(Request $request)
    {
      $client_token=$request->client_token;
      //$token='5cfmbbjASoJg5jEtJ6RYLVPnWjndMQV53OgZtPRs-dTkJl0cCiKGFY88RQBxlQ87bd1mlEmhzzUYKerIk8Hyia1AzGYN57OCVGdsU-FO56n1wFfSK36SM9KmziX1drK-fQ6ToEQz0OpRw8kPtK6XX5xZLsVRdQRRC1DEviQqLrJSligF0I7kq15DUQ4ueFBD2EOIogyYd18-dUnhnNmATvZvOBRDNbAzonIDipOmwyeIYDgb1Gzifkk8epEtUjlz4lNT0QMYcQ1dTf2zrp2EnnSWmstQDtS6Li0KmaejC7kV2GYu-LAEAfMCNkF6rJRKIDU1BnTtkn110mssDbqIzW04esPjWE22x8x6LrEyqZXF7FUqDItnL4fno4yA-aoo9ihKtEJqsKUQohO19Qmi6N-AQ8HGMBjUgbWVhdWvYw4Ex5wEZNuTTXeXOZ561uGWlkHZ7RC2j-sNRMJMlzDUafRxhdpaJgOjgQMxIR2BFMdxxTXcjk6d6rc7tXhY';
     
     $customer=DB::table('customer_subcriptions')->where('Auth_key',$client_token)->first();
    $customer_id=$customer->id;
     $umrah_package=UmrahPackage::where('customer_id',$customer_id)->get();
     
     return response()->json(['Message'=>'Success','umrah_package'=>$umrah_package]);
      
  } 
  
  
  // Tour Bookings
     // Tour Bookings
        public function view_ternary_bookings(Request $req){
            // $data = DB::table('booking_users')->join('tours_bookings','booking_users.id','tours_bookings.customer_id')->get();
            $data = DB::table('tours_bookings')->where('customer_id',$req->customer_id)->get();
            return view('template/frontend/userdashboard/pages/tour/view_ternary_bookings',compact('data'));
        }

      public function view_ternary_bookings_tourId($tourId){
        $a = view_booking_payment_recieve::where('tourId',$tourId)->get();
        echo $a ;
      }

      public function view_confirmed_bookings(){
        $data = DB::table('booking_users')->join('tours_bookings','booking_users.id','tours_bookings.customer_id')->get();
        return view('template/frontend/userdashboard/pages/tour/view_confirmed_bookings',compact('data'));
      }

      public function view_booking_payment($id,$tourId){
         $data = DB::table('tours_bookings')->find($id);
         $decode_data = json_decode($data->cart_details);
         $cart_details = $decode_data->$tourId;
         $customer_data = DB::table('booking_users')->join('tours_bookings','booking_users.id','tours_bookings.customer_id')->get();
         $decode_customer_data = json_decode($customer_data);
         $recieve_payment = DB::table('view_booking_payment_recieve')->where('tourId',$tourId)->first();
         $amount_paid = DB::table('view_booking_payment_recieve')->where('tourId',$tourId)->sum('amount_paid');
         return view('template/frontend/userdashboard/pages/tour/view_booking_payment',compact(['data','cart_details','decode_customer_data','recieve_payment','amount_paid']));
      }

      public function view_booking_detail($id,$tourId){
        $data = DB::table('tours_bookings')->find($id);
        $decode_data = json_decode($data->cart_details);
        $cart_details = $decode_data->$tourId;
        $customer_data = DB::table('booking_users')->join('tours_bookings','booking_users.id','tours_bookings.customer_id')->get();
        $decode_customer_data = json_decode($customer_data);
        return view('template/frontend/userdashboard/pages/tour/view_booking_detail',compact(['data','cart_details','decode_customer_data']));
      }

      public function view_booking_customer_details($id){
         $data = DB::table('booking_users')->join('tours_bookings','booking_users.id','tours_bookings.customer_id')->first();
        //  $data = DB::table('tours_bookings')->where('id',$id)->first();
         return view('template/frontend/userdashboard/pages/tour/view_booking_customer_details',compact('data'));
      }

      public function confirmed_tour_booking($id){
         $data = DB::table('tours_bookings')->where('id',$id)->update([
            'confirm' => '1',
          ]);
         return redirect()->route('view_confirmed_bookings',compact('data'));
      }

      public function view_booking_payment_recieve(Request $request,$tourId){
        $insert = new view_booking_payment_recieve();
        $insert->tourId           = $tourId;
        $insert->date             = $request->date;
        $insert->customer_id      = $request->customer_id;
        $insert->customer_name    = $request->customer_name;
        $insert->package_title    = $request->package_title;
        $insert->recieved_amount  = $request->recieved_amount;
        $insert->total_amount     = $request->total_amount;
        $insert->remaining_amount = $request->remaining_amount;
        $insert->amount_paid      = $request->amount_paid;

        $insert->save();
        
        return redirect()->route('view_ternary_bookings');

      }
   // END Tour Bookings

    
    
  public function create()
  {
     
      return view('template/frontend/userdashboard/pages/umrah_packages/create_umrah_packages');
  }
  public function store(Request $request)
  {
      $umrah_package=new UmrahPackage();
    $umrah_package->package_name=$request->package_name;
    $umrah_package->check_in=$request->check_in;
    $umrah_package->check_out=$request->check_out;
    $umrah_package->status=$request->status;

    $umrah_package->makkah_hotel_name=$request->makkah_hotel_name;
    $umrah_package->makkah_location=$request->makkah_location;
    $umrah_package->makkah_check_in=$request->makkah_check_in;
    $umrah_package->makkah_check_out=$request->makkah_check_out;
    $umrah_package->makkah_no_of_nights=$request->makkah_no_of_nights;
    $umrah_package->makkah_sharing_1=$request->makkah_sharing_1;
    $umrah_package->makkah_sharing_2=$request->makkah_sharing_2;
    $umrah_package->makkah_sharing_3=$request->makkah_sharing_3;
    $umrah_package->makkah_sharing_4=$request->makkah_sharing_4;
    $umrah_package->makkah_price=$request->makkah_price;
    $umrah_package->makkah_board_basis=$request->makkah_board_basis;
    $umrah_package->makkah_room_views=$request->makkah_room_views;

    if($request->hasFile('makkah_images')){

      $file = $request->file('makkah_images');
      $extension = $file->getClientOriginalExtension();
      $filename = time() . '.' . $extension;
      $file->move('public/uploads/package_imgs/', $filename);
      $makkah_images = $filename;
  }
  else{
      $makkah_images = '';
  }
    $umrah_package->makkah_images=$makkah_images;

    $umrah_package->madina_hotel_name=$request->madina_hotel_name;
    $umrah_package->madina_location=$request->madina_location;
    $umrah_package->madina_check_in=$request->madina_check_in;
    $umrah_package->madina_check_out=$request->madina_check_out;
    $umrah_package->madina_no_of_nights=$request->madina_no_of_nights;
    $umrah_package->madina_sharing_1=$request->madina_sharing_1;
    $umrah_package->madina_sharing_2=$request->madina_sharing_2;
    $umrah_package->madina_sharing_3=$request->madina_sharing_3;
    $umrah_package->madina_sharing_4=$request->madina_sharing_4;
    $umrah_package->madina_price=$request->madina_price;
    $umrah_package->madina_board_basis=$request->madina_board_basis;
    $umrah_package->madina_room_views=$request->madina_room_views;

    if($request->hasFile('madina_images')){

      $file = $request->file('madina_images');
      $extension = $file->getClientOriginalExtension();
      $filename = time() . '.' . $extension;
      $file->move('public/uploads/package_imgs/', $filename);
      $madina_images = $filename;
  }
  else{
      $madina_images = '';
  }
    $umrah_package->madina_images=$madina_images;

    $umrah_package->transfer_pickup_location=$request->transfer_pickup_location;
    $umrah_package->transfer_drop_location=$request->transfer_drop_location;
    $umrah_package->transfer_pickup_date_time=$request->transfer_pickup_date_time;
    $umrah_package->transfer_vehicle=$request->transfer_vehicle;


    if($request->hasFile('transfer_images')){

      $file = $request->file('transfer_images');
      $extension = $file->getClientOriginalExtension();
      $filename = time() . '.' . $extension;
      $file->move('public/uploads/package_imgs/', $filename);
      $transfer_images = $filename;
  }
  else{
      $transfer_images = '';
  }
    $umrah_package->transfer_images=$transfer_images;

    $umrah_package->flights_airline=$request->flights_airline;
    $umrah_package->flights_departure_airport=$request->flights_departure_airport;
    $umrah_package->flights_arrival_airport=$request->flights_arrival_airport;
    $umrah_package->flights_departure__return_airport=$request->flights_departure__return_airport;
    $umrah_package->flights_arrival_return_airport=$request->flights_arrival_return_airport;
    $umrah_package->flights_departure__return_date=$request->flights_departure__return_date;
    $umrah_package->flights_arrival_return_date=$request->flights_arrival_return_date;
    $umrah_package->flights_price=$request->flights_price;

    $umrah_package->visa_fee=$request->visa_fee;
    $umrah_package->visit_visa_fee=$request->visit_visa_fee;
    $umrah_package->details_visa=$request->details_visa;

    $umrah_package->administration_charges=$request->administration_charges;
    $umrah_package->administration_charges=$request->administration_charges;
    $umrah_package->save();
    return redirect()->back()->with('message','Package Created Successful!');
  }

  public function submit_umrah_packages_api(Request $request)
  {
      $umrah_package=new UmrahPackage();
      $umrah_package->customer_id=$request->customer_id;
    $umrah_package->package_name=$request->package_name;
    $umrah_package->check_in=$request->check_in;
    $umrah_package->check_out=$request->check_out;
    $umrah_package->status=$request->status;
    
    $umrah_package->no_of_pax_days=$request->no_of_pax_days;
      $umrah_package->content=$request->content;
      
      $umrah_package->makkah_details=$request->makkah_details;
      $umrah_package->madina_details=$request->madina_details;
      $umrah_package->transfer_details=$request->transfer_details;
      $umrah_package->transfer_details_more=$request->transfer_details_more;
      
      
      

    // $umrah_package->makkah_hotel_name=$request->makkah_hotel_name;
    // $umrah_package->makkah_location=$request->makkah_location;
    // $umrah_package->makkah_check_in=$request->makkah_check_in;
    // $umrah_package->makkah_check_out=$request->makkah_check_out;
    // $umrah_package->makkah_no_of_nights=$request->makkah_no_of_nights;
    // $umrah_package->makkah_sharing_1=$request->makkah_sharing_1;
    // $umrah_package->makkah_sharing_2=$request->makkah_sharing_2;
    // $umrah_package->makkah_sharing_3=$request->makkah_sharing_3;
    // $umrah_package->makkah_sharing_4=$request->makkah_sharing_4;
    // $umrah_package->makkah_price=$request->makkah_price;
    // $umrah_package->makkah_board_basis=$request->makkah_board_basis;
    // $umrah_package->makkah_room_views=$request->makkah_room_views;

//     if($request->hasFile('makkah_images')){

//       $file = $request->file('makkah_images');
//       $extension = $file->getClientOriginalExtension();
//       $filename = time() . '.' . $extension;
//       $file->move('public/uploads/package_imgs/', $filename);
//       $makkah_images = $filename;
//   }
//   else{
//       $makkah_images = '';
//   }
//     $umrah_package->makkah_images=$makkah_images;

//     $umrah_package->madina_hotel_name=$request->madina_hotel_name;
//     $umrah_package->madina_location=$request->madina_location;
//     $umrah_package->madina_check_in=$request->madina_check_in;
//     $umrah_package->madina_check_out=$request->madina_check_out;
//     $umrah_package->madina_no_of_nights=$request->madina_no_of_nights;
//     $umrah_package->madina_sharing_1=$request->madina_sharing_1;
//     $umrah_package->madina_sharing_2=$request->madina_sharing_2;
//     $umrah_package->madina_sharing_3=$request->madina_sharing_3;
//     $umrah_package->madina_sharing_4=$request->madina_sharing_4;
//     $umrah_package->madina_price=$request->madina_price;
//     $umrah_package->madina_board_basis=$request->madina_board_basis;
//     $umrah_package->madina_room_views=$request->madina_room_views;

//     if($request->hasFile('madina_images')){

//       $file = $request->file('madina_images');
//       $extension = $file->getClientOriginalExtension();
//       $filename = time() . '.' . $extension;
//       $file->move('public/uploads/package_imgs/', $filename);
//       $madina_images = $filename;
//   }
//   else{
//       $madina_images = '';
//   }
//     $umrah_package->madina_images=$madina_images;

//     $umrah_package->transfer_pickup_location=$request->transfer_pickup_location;
//     $umrah_package->transfer_drop_location=$request->transfer_drop_location;
//     $umrah_package->transfer_pickup_date_time=$request->transfer_pickup_date_time;
//     $umrah_package->transfer_vehicle=$request->transfer_vehicle;


//     if($request->hasFile('transfer_images')){

//       $file = $request->file('transfer_images');
//       $extension = $file->getClientOriginalExtension();
//       $filename = time() . '.' . $extension;
//       $file->move('public/uploads/package_imgs/', $filename);
//       $transfer_images = $filename;
//   }
//   else{
//       $transfer_images = '';
//   }
//     $umrah_package->transfer_images=$transfer_images;

    $umrah_package->flights_airline=$request->flights_airline;
    $umrah_package->flights_departure_airport=$request->flights_departure_airport;
    $umrah_package->flights_arrival_airport=$request->flights_arrival_airport;
    $umrah_package->flights_departure__return_airport=$request->flights_departure__return_airport;
    $umrah_package->flights_arrival_return_airport=$request->flights_arrival_return_airport;
    $umrah_package->flights_departure__return_date=$request->flights_departure__return_date;
    $umrah_package->flights_arrival_return_date=$request->flights_arrival_return_date;
    $umrah_package->flights_price=$request->flights_price;

    $umrah_package->visa_fee=$request->visa_fee;
    $umrah_package->visit_visa_fee=$request->visit_visa_fee;
    
     $umrah_package->quad_grand_total_amount=$request->quad_grand_total_amount;
    $umrah_package->triple_grand_total_amount=$request->triple_grand_total_amount;
     $umrah_package->double_grand_total_amount=$request->double_grand_total_amount;
    
    // $umrah_package->details_visa=$request->details_visa;

    $umrah_package->administration_charges=$request->administration_charges;
    // $umrah_package->administration_charges=$request->administration_charges;
    $umrah_package->save();
    
    return response()->json(['message'=>'success','umrah_package'=>$umrah_package]);
    
    // return redirect()->back()->with('message','Package Created Successful!');
  }


  public function submit_edit_umrah_packages_api(Request $request)
  {

    $id=$request->id;
    // print_r($id);die();
    $umrah_package=UmrahPackage::find($id);

    if($umrah_package)
    {
      $umrah_package->customer_id=$request->customer_id;
      $umrah_package->package_name=$request->package_name;
      $umrah_package->check_in=$request->check_in;
      $umrah_package->check_out=$request->check_out;
      $umrah_package->status=$request->status;
    
      $umrah_package->makkah_hotel_name=$request->makkah_hotel_name;
      $umrah_package->makkah_location=$request->makkah_location;
      $umrah_package->makkah_check_in=$request->makkah_check_in;
      $umrah_package->makkah_check_out=$request->makkah_check_out;
      $umrah_package->makkah_no_of_nights=$request->makkah_no_of_nights;
      $umrah_package->makkah_sharing_1=$request->makkah_sharing_1;
      $umrah_package->makkah_sharing_2=$request->makkah_sharing_2;
      $umrah_package->makkah_sharing_3=$request->makkah_sharing_3;
      $umrah_package->makkah_sharing_4=$request->makkah_sharing_4;
      $umrah_package->makkah_price=$request->makkah_price;
      $umrah_package->makkah_board_basis=$request->makkah_board_basis;
      $umrah_package->makkah_room_views=$request->makkah_room_views;
    
      if($request->hasFile('makkah_images')){
    
        $file = $request->file('makkah_images');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $makkah_images = $filename;
    }
    else{
        $makkah_images = '';
    }
      $umrah_package->makkah_images=$makkah_images;
    
      $umrah_package->madina_hotel_name=$request->madina_hotel_name;
      $umrah_package->madina_location=$request->madina_location;
      $umrah_package->madina_check_in=$request->madina_check_in;
      $umrah_package->madina_check_out=$request->madina_check_out;
      $umrah_package->madina_no_of_nights=$request->madina_no_of_nights;
      $umrah_package->madina_sharing_1=$request->madina_sharing_1;
      $umrah_package->madina_sharing_2=$request->madina_sharing_2;
      $umrah_package->madina_sharing_3=$request->madina_sharing_3;
      $umrah_package->madina_sharing_4=$request->madina_sharing_4;
      $umrah_package->madina_price=$request->madina_price;
      $umrah_package->madina_board_basis=$request->madina_board_basis;
      $umrah_package->madina_room_views=$request->madina_room_views;
    
      if($request->hasFile('madina_images')){
    
        $file = $request->file('madina_images');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $madina_images = $filename;
    }
    else{
        $madina_images = '';
    }
      $umrah_package->madina_images=$madina_images;
    
      $umrah_package->transfer_pickup_location=$request->transfer_pickup_location;
      $umrah_package->transfer_drop_location=$request->transfer_drop_location;
      $umrah_package->transfer_pickup_date_time=$request->transfer_pickup_date_time;
      $umrah_package->transfer_vehicle=$request->transfer_vehicle;
    
    
      if($request->hasFile('transfer_images')){
    
        $file = $request->file('transfer_images');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $transfer_images = $filename;
    }
    else{
        $transfer_images = '';
    }
      $umrah_package->transfer_images=$transfer_images;
    
      $umrah_package->flights_airline=$request->flights_airline;
      $umrah_package->flights_departure_airport=$request->flights_departure_airport;
      $umrah_package->flights_arrival_airport=$request->flights_arrival_airport;
      $umrah_package->flights_departure__return_airport=$request->flights_departure__return_airport;
      $umrah_package->flights_arrival_return_airport=$request->flights_arrival_return_airport;
      $umrah_package->flights_departure__return_date=$request->flights_departure__return_date;
      $umrah_package->flights_arrival_return_date=$request->flights_arrival_return_date;
      $umrah_package->flights_price=$request->flights_price;
    
      $umrah_package->visa_fee=$request->visa_fee;
      $umrah_package->visit_visa_fee=$request->visit_visa_fee;
      $umrah_package->details_visa=$request->details_visa;
    
      $umrah_package->administration_charges=$request->administration_charges;
      $umrah_package->administration_charges=$request->administration_charges;
      $umrah_package->update();
      return response(['umrah_package'=>$umrah_package]);
    }
    
  }



public function edit_umrah_packages_api(Request $request)
{

  $id=$request->id;
    $umrah_package=UmrahPackage::find($id);
    return response(['umrah_package'=>$umrah_package]);


 
}





  public function enable_umrah_packages($id)
  {
    $umrah_package=UmrahPackage::find($id);
    if($umrah_package)
    {
      $umrah_package->status='0';
      $umrah_package->update();
      return redirect()->back()->with('message','umrah package Status Updated Successful!');
    }
    else{
      return redirect()->back()->with('message','umrah package Status Not Updated Successful!');
    }
   
  }

  public function enable_umrah_packages_api(Request $request)
  {
    $id=$request->id;
    $umrah_package=UmrahPackage::find($id);
    if($umrah_package)
    {
      $umrah_package->status='0';
      $umrah_package->update();
      return redirect()->back()->with('message','umrah package Status Updated Successful!');
    }
    else{
      return redirect()->back()->with('message','umrah package Status Not Updated Successful!');
    }
   
  }
  public function disable_umrah_packages($id)
  {


    $umrah_package=UmrahPackage::find($id);
    if($umrah_package)
    {
      $umrah_package->status='1';
      $umrah_package->update();
      return redirect()->back()->with('message','umrah package Status Updated Successful!');
    }
    else{
      return redirect()->back()->with('message','umrah package Status Not Updated Successful!');
    }
  }
  public function disable_umrah_package_api(Request $request)
  {

$id=$request->id;
    $umrah_package=UmrahPackage::find($id);
    if($umrah_package)
    {
      $umrah_package->status='1';
      $umrah_package->update();
      return redirect()->back()->with('message','umrah package Status Updated Successful!');
    }
    else{
      return redirect()->back()->with('message','umrah package Status Not Updated Successful!');
    }
  }

  public function delete_umrah_packages($id)
  {
    $tours=UmrahPackage::find($id);
    $tours->delete();
    return redirect()->back()->with('message','UmrahPackage deleted Successful!');
  }

  public function delete_umrah_packages_api(Request $request)
  {
    $id=$request->id;
    $tours=UmrahPackage::find($id);
    $tours->delete();
    return redirect()->back()->with('message','UmrahPackage deleted Successful!');
  }


  public function view()
  {

    $umrah_package=UmrahPackage::all();
    return view('template/frontend/userdashboard/pages/umrah_packages/view_umrah_packages',compact('umrah_package'));  
  }

  public function view_umrah_packages_api(Request $request)
  {
   
$customer_id=$request->customer_id;
// print_r($customer_id);die();
$umrah_package=DB::table('umrah_packages')->where('customer_id',$customer_id)->get();
  
    return response()->json(['umrah_package'=>$umrah_package]);
    
  }


  public function add_categories()
  {

    
    return view('template/frontend/userdashboard/pages/umrah_packages/categories/add_categories');  
  }

  public function submit_categories(Request $request)
  {

    $categories=DB::table('categories')->insert([
'title'=>$request->title,
    ]);
    return redirect()->back()->with('message','Categories Created Successful!');
  }

  public function edit_categories($id)
  {
    $categories=DB::table('categories')->where('id',$id)->first();
    
    return view('template/frontend/userdashboard/pages/umrah_packages/categories/edit_categories',compact('categories'));  
  }
  public function edit_categories_api(Request $request)
  {
    $id=$request->id;
    // print_r($id);die();
    $categories=DB::table('categories')->where('id',$id)->first();
    
    return response(['categories'=>$categories]);
  }
  public function submit_edit_categories(Request $request,$id)
  {
    
    $categories=DB::table('categories')->where('id',$id)->update([
      'title'=>$request->title,
          ]);
          return redirect('super_admin/view_categories')->with('message','Categories updated Successful!');
    
    
  }
  public function submit_edit_categories_api_request(Request $request)
  {

    $id=$request->id;
    
    $categories=DB::table('categories')->where('id',$id)->update([
      'title'=>$request->title,
      'slug'=>$request->slug,
      'image'=>$request->image,
      'description'=>$request->description,
      'gallery_images'=>$request->gallery_images,
          ]);

          return response(['categories'=>$categories]);
         
    
    
  }
  public function delete_categories($id)
  {
    $categories=DB::table('categories')->where('id',$id)->delete();
          return redirect()->back()->with('message','Categories updated Successful!');
    
    
  }
 

  public function view_categories(Request $request)
  {

    $categories=DB::table('categories')->get();
    return view('template/frontend/userdashboard/pages/umrah_packages/categories/view_categories',compact('categories'));  
  }
  public function add_attributes(Request $request)
  {


    return view('template/frontend/userdashboard/pages/umrah_packages/attributes/add_attributes');  
  }
  public function submit_attributes(Request $request)
  {

    $attributes=DB::table('attributes')->insert([
'title'=>$request->title,
    ]);
    return redirect()->back()->with('message','Attributes Created Successful!');
  }

  public function edit_attributes($id)
  {
    $attributes=DB::table('attributes')->where('id',$id)->first();
    
    return view('template/frontend/userdashboard/pages/umrah_packages/attributes/edit_attributes',compact('attributes'));  
  }
  public function edit_attributes_api(Request $request)
  {
$id=$request->id;
    $attributes=DB::table('attributes')->where('id',$id)->first();
    return response(['attributes'=>$attributes]);
    
    
  }
  public function submit_edit_attributes(Request $request,$id)
  {
    $categories=DB::table('attributes')->where('id',$id)->update([
      'title'=>$request->title,
          ]);
          return redirect('super_admin/view_attributes')->with('message','attributes updated Successful!');
    
    
  }
  public function submit_edit_attributes_api(Request $request)
  {
    $id=$request->id;
    $categories=DB::table('attributes')->where('id',$id)->update([
      'title'=>$request->title,
          ]);
          return redirect('super_admin/view_attributes')->with('message','attributes updated Successful!');
    
    
  }
  public function delete_attributes($id)
  {
    $attributes=DB::table('attributes')->where('id',$id)->delete();
          return redirect()->back()->with('message','attributes updated Successful!');
    
    
  }
  public function delete_attributes_api(Request $request)
  {
    $id=$request->id;
    $attributes=DB::table('attributes')->where('id',$id)->delete();
          return response(['attributes'=>$attributes]);
    
    
  }
  public function view_attributes(Request $request)
  {

    $attributes=DB::table('attributes')->get();
    return view('template/frontend/userdashboard/pages/umrah_packages/attributes/view_attributes',compact('attributes'));  
  }



  public function create_excursion(Request $request)
  {

    $categories=DB::table('categories')->get();
    $attributes=DB::table('attributes')->get();
  
    $all_countries = country::all();
    return view('template/frontend/userdashboard/pages/tour/add_tour',compact('categories','attributes','all_countries'));  
  }
    
    // Tour Packages
    public function create_excursion2(Request $request){
        $customer_id=$request->customer_id;
        $categories=DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes=DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer=DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $mange_currencies=DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
        $all_countries = country::all();
        //print_r($all_countries);die();
        $all_countries_currency = country::all();
         
        $payment_gateways=DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes=DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        
        $currency_Symbol = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        
        $transfer_Vehicle = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
        
        $others_providers_list = DB::table('3rd_party_commissions')->where('customer_id',$customer_id)->get();
        
        $supplier_detail = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        
        $tranfer_supplier       = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $destination_details    = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        
        $all_flight_routes      = DB::table('flight_rute')->get();
        $flight_suppliers       = DB::table('supplier')->get();
     
        return response()->json(['message'=>'success','flight_suppliers'=>$flight_suppliers,'all_flight_routes'=>$all_flight_routes,'tranfer_supplier'=>$tranfer_supplier,'destination_details'=>$destination_details,'supplier_detail'=>$supplier_detail,'mange_currencies'=>$mange_currencies,'transfer_Vehicle'=>$transfer_Vehicle,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'others_providers_list'=>$others_providers_list,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes]);
    }
    
    public function view_tour(Request $request){
        $pakage_type    = 'tour';
        $customer_id    = $request->customer_id;
        $tours          = DB::table('tours')
                            ->join('tours_2','tours.id','tours_2.tour_id')
                            ->where('tours.customer_id',$customer_id)
                            ->get();
                            
        $data1          = DB::table('cart_details')->where('pakage_type',$pakage_type)->get();
        $booking_Id     = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->join('cart_details','tours.id','cart_details.tour_id')->where('tours.customer_id',$customer_id)
                            ->select('cart_details.tour_id','cart_details.booking_id')->get();
        return response()->json([
            'message'       => 'success',
            'tours'         => $tours,
            'data1'         => $data1,
            'booking_Id'    => $booking_Id,
        ]);
    }

    public function submit_tour(Request $request){
        
        $fg = json_decode($request->flight_id_array);
        $fgg = json_decode($request->flight_reserve_array);
    
        $accomodation       = json_decode($request->accomodation_details);
        $accomodation_more  = json_decode($request->more_accomodation_details);

        if($fg !='' && $fg != null){
           
           foreach($fg as $key=>$fgz){
                $update_fig = (int)$fgg[$key];
                
                $seat = DB::table('flight_rute')->where('id',$fgz)->select('occupied_seat','flights_number_of_seat')->first();
                
                $total_occupied = $seat->occupied_seat + $update_fig;
                
                DB::table('flight_rute')->where('id',$fgz)->update(['occupied_seat'=>$total_occupied,]);
           } 
        }

        $total_single_seats = 0;
        $total_double_seats = 0;
        $total_triple_seats = 0;
        $total_quad_seats   = 0;
        
        if(isset($accomodation) && $accomodation != null && $accomodation != ''){
            foreach($accomodation as $acc_res){
                if($acc_res->acc_type == 'Single'){
                    $total_single_seats += $acc_res->acc_pax;
                }
                
                if($acc_res->acc_type == 'Double'){
                    $total_double_seats += $acc_res->acc_pax;
                }
                
                if($acc_res->acc_type == 'Triple'){
                    $total_triple_seats += $acc_res->acc_pax;
                }
                
                if($acc_res->acc_type == 'Quad'){
                    $total_quad_seats += $acc_res->acc_pax;
                }
            }
        }
        
        if(isset($accomodation_more) && $accomodation_more != null && $accomodation_more != ''){
            foreach($accomodation_more as $acc_res){
                if($acc_res->more_acc_type == 'Single'){
                    $total_single_seats += $acc_res->more_acc_pax;
                }
                
                if($acc_res->more_acc_type == 'Double'){
                    $total_double_seats += $acc_res->more_acc_pax;
                }
                
                if($acc_res->more_acc_type == 'Triple'){
                    $total_triple_seats += $acc_res->more_acc_pax;
                }
                
                if($acc_res->more_acc_type == 'Quad'){
                    $total_quad_seats += $acc_res->more_acc_pax;
                }
            }
        }
        
        $generate_id=rand(0,9999999);
        $tour = new Tour();
        $tour->flight_id=$request->flight_id;
        $tour->generate_id=$generate_id;
        $tour->customer_id=$request->customer_id;
        $tour->title=$request->title;
        $tour->content=$request->content;
        $tour->categories=$request->tour_categories;
        $tour->tour_attributes=$request->tour_attributes;
        $tour->no_of_pax_days=$request->no_of_pax_days;
        $tour->city_Count=$request->city_Count;
        $tour->destination_details=$request->destination_details;
        $tour->destination_details_more=$request->destination_details_more;
        $tour->accomodation_details=$request->accomodation_details;
        $tour->accomodation_details_more=$request->more_accomodation_details;
        $tour->visa_type=$request->visa_type;
        $tour->visa_rules_regulations=$request->visa_rules_regulations;
        
        $tour->currency_conversion=$request->currency_conversion;
        $tour->conversion_type_Id=$request->conversion_type_Id;
        
        $tour->visa_fee_purchase=$request->visa_fee_purchase;
        $tour->exchange_rate_visa=$request->exchange_rate_visa;
        $tour->conversion_type=$request->conversion_type;
        $tour->visa_fee=$request->visa_fee;
        $tour->visa_image=$request->visa_image;
        $tour->gallery_images=$request->gallery_images;
        $tour->start_date=$request->start_date;
        $tour->end_date=$request->end_date;
        $tour->time_duration=$request->time_duration;
        $tour->tour_location=$request->tour_location;
        $tour->whats_included=$request->whats_included;
        $tour->whats_excluded=$request->whats_excluded;
        $tour->currency_symbol=$request->currency_symbol;
        $tour->tour_publish=$request->tour_publish;
        $tour->tour_author=$request->tour_author;
        $tour->others_providers_show=$request->others_providers_show;
        $tour->tour_feature=$request->tour_feature;
        $tour->defalut_state=$request->defalut_state;
        $tour->payment_gateways=$request->payment_gateways;
        $tour->payment_modes=$request->payment_modes;
        $tour->tour_featured_image=$request->tour_featured_image;
        $tour->tour_banner_image=$request->tour_banner_image;
        $tour->starts_rating=$request->starts_rating;
        $tour->checkout_message     =   $request->checkout_message;
        $tour->cancellation_policy  =   $request->cancellation_policy;
        
        // $tour->arfatData  =   $request->arfatData;
        $tour->arfat_selected  =   $request->arfat_selected;
        // $tour->muzdalfaData  =   $request->muzdalfaData;
        $tour->muzdalfa_selected  =   $request->muzdalfa_selected;
        // $tour->minaData  =   $request->minaData;
        $tour->mina_selected  =   $request->mina_selected;
        
        $tour->mina_pkg_details  =   $request->mina_pkg_details;
        $tour->arfat_pkg_details  =   $request->arfat_pkg_details;
        $tour->muzdalfa_details  =   $request->muzdalfa_details;
           
        $tour->available_seats          =   $request->no_of_pax_days;
        $tour->available_single_seats   =   $total_single_seats;
        $tour->available_double_seats   =   $total_double_seats;
        $tour->available_triple_seats   =   $total_triple_seats;
        $tour->available_quad_seats     =   $total_quad_seats;
        
        $tour->save();
        
        // Cost
        $quad_cost_priceN               = $request->quad_cost_price ?? '0';
        $triple_cost_priceN             = $request->triple_cost_price ?? '0';
        $double_cost_priceN             = $request->double_cost_price ?? '0';
        $without_acc_cost_priceN        = $request->without_acc_cost_price ?? '0';
        $total_cost_price_all_Services  = $double_cost_priceN + $triple_cost_priceN + $quad_cost_priceN + $without_acc_cost_priceN;
        
        // Sale
        $quad_grand_total_amountN       = $request->quad_grand_total_amount ?? '0';
        $triple_grand_total_amountN     = $request->triple_grand_total_amount ?? '0';
        $double_grand_total_amountN     = $request->double_grand_total_amount ?? '0';
        $without_acc_sale_price_singleN = $request->without_acc_sale_price ?? '0';
        $total_sale_price_all_Services  = $double_grand_total_amountN + $triple_grand_total_amountN + $quad_grand_total_amountN + $without_acc_sale_price_singleN;
        
        $notification_insert = new alhijaz_Notofication();
        $notification_insert->type_of_notification_id   = $tour->id ?? '';
        $notification_insert->customer_id               = $tour->customer_id ?? '';
        $notification_insert->type_of_notification      = 'create_Package' ?? '';
        $notification_insert->generate_id               = $tour->generate_id ?? '';
        $notification_insert->notification_creator_name = 'AlhijazTours' ?? '';
        $notification_insert->total_price               = $total_sale_price_all_Services ?? '';
        $notification_insert->amount_paid               = $tour->amount_Paid ?? '';
        $notification_insert->remaining_price           = $total_sale_price_all_Services ?? '';
        $notification_insert->notification_status       = '1' ?? '';
        $notification_insert->save();
        
        $lastTourId = $tour->id;
        
        $flights_details            = json_decode($request->flights_details);
        if(isset($flights_details) && $flights_details != null && $flights_details != ''){
            DB::table('flight_seats_occupied')->insert([
                'token'                         => $request->token,
                'type'                          => 'Package',
                'booking_id'                    => $lastTourId,
                'flight_supplier'               => $request->flight_supplier,
                'flight_route_id'               => $request->flight_route_id_occupied,
                'flights_adult_seats'           => $request->flight_total_seats,
                'flights_child_seats'           => 0,
                'flight_route_seats_occupied'   => $request->flight_total_seats,
            ]);
        }
        
        DB::beginTransaction();
        try {
            $tours_2 = DB::table('tours_2')->insert([
                'tour_id'=>$lastTourId,
                'Itinerary_details'=>$request->Itinerary_details,
                'tour_itinerary_details_1'=>$request->tour_itinerary_details_1,
                'tour_extra_price'=>$request->tour_extra_price,
                'tour_extra_price_1'=>$request->tour_extra_price_1,
                'tour_faq'=>$request->tour_faq,
                'tour_faq_1'=>$request->tour_faq_1,
                'markup_details'=>$request->markup_details,
                'more_markup_details'=>$request->more_markup_details,
                
                'flight_route_type'         => $request->flight_route_type,
                'flight_supplier'           => $request->flight_supplier,
                'flight_route_id_occupied'  => $request->flight_route_id_occupied,
                'flight_total_seats'        => $request->flight_total_seats,
                'flights_per_person_price'  => $request->flights_per_person_price,
                
                'flights_details'           => $request->flights_details,
                'return_flights_details'    => $request->return_flights_details,
                
                'transportation_details'=>$request->transportation_details,
                'transportation_details_more'=>$request->transportation_details_more,
                'quad_grand_total_amount'=>$request->quad_grand_total_amount,
                'triple_grand_total_amount'=>$request->triple_grand_total_amount,
                'double_grand_total_amount'=>$request->double_grand_total_amount,
                'quad_cost_price'=>$request->quad_cost_price,
                'triple_cost_price'=>$request->triple_cost_price,
                'double_cost_price'=>$request->double_cost_price,
                'all_markup_type'=>$request->all_markup_type,
                'all_markup_add'=>$request->all_markup_add,
                'child_flight_cost_price'=>$request->child_flight_cost,
                'child_flight_mark_type'=>$request->child_flight_markup_type,
                'child_flight_mark_value'=>$request->child_flight_markup_value,
                'child_flight_sale_price'=>$request->child_flight_sale,
                'child_visa_cost_price'=>$request->child_visa_cost,
                'child_visa_mark_type'=>$request->child_visa_markup_type,
                'child_visa_mark_value'=>$request->child_visa_markup_value,
                'child_visa_sale_price'=>$request->child_visa_sale,
                'child_transp_cost_price'=>$request->child_Transporationcost,
                'child_transp_mark_type'=>$request->child_Transporation_markup_type,
                'child_transp_mark_value'=>$request->child_Transporation_markup_value,
                'child_transp_sale_price'=>$request->child_Transporation_sale,
                'child_grand_total_cost_price'=>$request->child_total_cost_price,
                'child_grand_total_sale_price'=>$request->child_total_sale_price,
                'without_acc_cost_price'=>$request->without_acc_cost_price,
                'without_acc_sale_price'=>$request->without_acc_sale_price,
                
                'infant_flight_cost'=>$request->infant_flight_cost,
                'infant_transp_cost'=>$request->infant_transp_cost,
                'infant_visa_cost'=>$request->infant_visa_cost,
                'infant_total_cost_price'=>$request->infant_total_cost_price,
                'infant_total_sale_price'=>$request->infant_total_sale_price,
                
                'ch_price_other_c'=>$request->child_other_prices,
                'in_price_other_c'=>$request->infant_other_prices,
                'in_markup_other_c'=>$request->infant_markup_prices,
            ]);
            
            $tour_batchs = DB::table('tour_batchs')->insertGetId([
                'tour_id'=>$lastTourId,
                'generate_id'=>$generate_id,
                'customer_id'=>$request->customer_id,
                'city_Count'=>$request->city_Count,
                'title'=>$request->title,
                'categories'=>$request->tour_categories,
                'starts_rating'=>$request->starts_rating,
                'content'=>$request->content,
                'tour_attributes'=>$request->tour_attributes,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
                'time_duration'=>$request->time_duration,
                'tour_location'=>$request->tour_location,
                'whats_included'=>$request->whats_included,
                'whats_excluded'=>$request->whats_excluded,
                'currency_symbol'=>$request->currency_symbol,
                'tour_publish'=>$request->tour_publish,
                'tour_author'=>$request->tour_author,
                'tour_feature'=>$request->tour_feature,
                'defalut_state'=>$request->defalut_state,
                'tour_featured_image'=>$request->tour_featured_image,
                'tour_banner_image'=>$request->tour_banner_image,
                'external_packages'=>$request->external_packages,
                'no_of_pax_days'=>$request->no_of_pax_days,
                'destination_details'=>$request->destination_details,
                'destination_details_more'=>$request->destination_details_more,
                'accomodation_details'=>$request->accomodation_details,
                'accomodation_details_more'=>$request->more_accomodation_details,
                'visa_fee'=>$request->visa_fee,
                'visa_type'=>$request->visa_type,
                
                 'visa_fee_purchase'=>$request->visa_fee_purchase,
                'exchange_rate_visa'=>$request->exchange_rate_visa,
                
               
                'visa_image'=>$request->visa_image,
                'visa_rules_regulations'=>$request->visa_rules_regulations,
                'gallery_images'=>$request->gallery_images,
                'payment_gateways'=>$request->payment_gateways,
                'payment_modes'=>$request->payment_modes,
                'cancellation_policy'=>$request->cancellation_policy,
                'checkout_message'=>$request->checkout_message,
            ]);
            
            $lastbatchsId = $tour_batchs;
            
            $tour_batchs2=DB::table('tour_batchs_2')->insert([
                'batchs_id'=>$lastbatchsId,
                'generate_id'=>$generate_id,
                'Itinerary_details'=>$request->Itinerary_details,
                'tour_itinerary_details_1'=>$request->tour_itinerary_details_1,
                'tour_extra_price'=>$request->tour_extra_price,
                'tour_extra_price_1'=>$request->tour_extra_price_1,
                'tour_faq'=>$request->tour_faq,
                'tour_faq_1'=>$request->tour_faq_1,
                'markup_details'=>$request->markup_details,
                'more_markup_details'=>$request->more_markup_details,
                 'multi_flight'=>$request->flight_reserve_object_array ?? "",
                'flights_details'=>$request->flights_details,
                'flights_details_more'=>$request->more_flights_details,
                'transportation_details'=>$request->transportation_details,
                'transportation_details_more'=>$request->transportation_details_more,
                'quad_grand_total_amount'=>$request->quad_grand_total_amount,
                'triple_grand_total_amount'=>$request->triple_grand_total_amount,
                'double_grand_total_amount'=>$request->double_grand_total_amount,
                'quad_cost_price'=>$request->quad_cost_price,
                'triple_cost_price'=>$request->triple_cost_price,
                'double_cost_price'=>$request->double_cost_price,
                'all_markup_type'=>$request->all_markup_type,
                'all_markup_add'=>$request->all_markup_add,
                'child_flight_cost_price'=>$request->child_flight_cost,
                'child_flight_mark_type'=>$request->child_flight_markup_type,
                'child_flight_mark_value'=>$request->child_flight_markup_value,
                'child_flight_sale_price'=>$request->child_flight_sale,
                'child_visa_cost_price'=>$request->child_visa_cost,
                'child_visa_mark_type'=>$request->child_visa_markup_type,
                'child_visa_mark_value'=>$request->child_visa_markup_value,
                'child_visa_sale_price'=>$request->child_visa_sale,
                'child_transp_cost_price'=>$request->child_Transporationcost,
                'child_transp_mark_type'=>$request->child_Transporation_markup_type,
                'child_transp_mark_value'=>$request->child_Transporation_markup_value,
                'child_transp_sale_price'=>$request->child_Transporation_sale,
                'child_grand_total_cost_price'=>$request->child_total_cost_price,
                'child_grand_total_sale_price'=>$request->child_total_sale_price,
                'without_acc_cost_price'=>$request->without_acc_cost_price,
                'without_acc_sale_price'=>$request->without_acc_sale_price,
                
                'infant_flight_cost'=>$request->infant_flight_cost,
                'infant_transp_cost'=>$request->infant_transp_cost,
                'infant_visa_cost'=>$request->infant_visa_cost,
                'infant_total_cost_price'=>$request->infant_total_cost_price,
                'infant_total_sale_price'=>$request->infant_total_sale_price,
                
                'ch_price_other_c'=>$request->child_other_prices,
                'in_price_other_c'=>$request->infant_other_prices,
                'in_markup_other_c'=>$request->infant_markup_prices,
                
            ]);
                
            DB::commit();  
            return response()->json(['status'=>'success','message'=>'Tour2 is add','tour_batchs'=>$tour_batchs,'lastbatchsId'=>$lastbatchsId,'tour_batchs2'=>$tour_batchs2]); 
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error']);
        }
       
    }
    
    public function edit_tour(Request $request){
        $id=$request->id;
        $customer_id=$request->customer_id;
        
        $customer_id=$request->customer_id;
        $categories=DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes=DB::table('activities_attributes')->where('customer_id',$customer_id)->get();
        $customer=DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries = country::all();
        $all_countries_currency = country::all();
        $bir_airports=DB::table('bir_airports')->get();
         
        $payment_gateways=DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes=DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

        if($request->type == 'tour'){
            // $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$request->id)->first();
            $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$request->id)->select('tours_2.*','tours.*','tours_2.id as tour2id')->first();
        }
    
        if($request->type == 'activity'){
            $tours=DB::table('actives')->where('id',$request->id)->first();
        }
        
        $other_Hotel_Name=DB::table('hotel_Name')->where('customer_id',$customer_id)->get();
        $airline_Name=DB::table('airline_name_tb')->where('customer_id',$customer_id)->get();
        $visa_type_get=DB::table('visa_types')->where('customer_id',$request->customer_id)->get();
        $mange_currencies=DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
        
        $supplier_detail = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        
        $tranfer_supplier       = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        $destination_details    = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        
        $all_flight_routes      = DB::table('flight_rute')->get();
        $flight_suppliers       = DB::table('supplier')->get();
        
        return response()->json(['message'=>'success','flight_suppliers'=>$flight_suppliers,'all_flight_routes'=>$all_flight_routes,'tranfer_supplier'=>$tranfer_supplier,'destination_details'=>$destination_details,'supplier_detail'=>$supplier_detail,'visa_type_get'=>$visa_type_get,'mange_currencies'=>$mange_currencies,'airline_Name'=>$airline_Name,'other_Hotel_Name' => $other_Hotel_Name,'tours'=>$tours,'currency_Symbol'=>$currency_Symbol,'payment_modes'=>$payment_modes,'payment_gateways'=>$payment_gateways,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports]);
    }
    
    public function submit_edit_tour(Request $request){
        $id             = $request->id;
        $generate_id    = rand(0,9999999);
        $activities     = Tour::find($id);
        if($activities)
        { 
            if($request->package_update_type == 'new'){
                $activities->generate_id=$generate_id;
            }
            
            DB::beginTransaction();
            try {
                $activities->customer_id            = $request->customer_id;
                // Package_Details
                $activities->title                  = $request->title;
                $activities->no_of_pax_days         = $request->no_of_pax_days;
                $activities->city_Count             = $request->city_Count;
                $activities->starts_rating          = $request->starts_rating;
                $activities->currency_symbol        = $request->currency_symbol;
                $activities->content                = $request->content;
                $activities->start_date             = $request->start_date;
                $activities->end_date               = $request->end_date;
                $activities->time_duration          = $request->time_duration;
                $activities->categories             = $request->tour_categories;
                $activities->tour_feature           = $request->tour_feature;
                $activities->defalut_state          = $request->defalut_state;
                $activities->tour_featured_image    = $request->tour_featured_image;
                $activities->tour_banner_image      = $request->tour_banner_image;
                $activities->tour_author            = $request->tour_author;
                $activities->gallery_images         = $request->gallery_images;
                $activities->payment_gateways       = $request->payment_gateways;
                $activities->payment_modes          = $request->payment_modes;
                $activities->tour_location          = $request->tour_location;
                $activities->conversion_type        = $request->conversion_type;
                
                $activities->currency_conversion    = $request->currency_conversion;
                $activities->conversion_type_Id     = $request->conversion_type_Id;
            
                // Destination_details
                $activities->destination_details=$request->destination_details;
                $activities->destination_details_more=$request->destination_details_more;
                // Accomodation_Details
                $activities->accomodation_details=$request->accomodation_details;
                $activities->accomodation_details_more=$request->more_accomodation_details;
                // Visa_Details
                $activities->visa_type=$request->visa_type;
                $activities->visa_rules_regulations=$request->visa_rules_regulations;
                $activities->visa_fee=$request->visa_fee;
                
                $activities->visa_fee_purchase=$request->visa_fee_purchase;
                $activities->exchange_rate_visa=$request->exchange_rate_visa;
                $activities->visa_image=$request->visa_image;
                
                $activities->update();
                
                $flights_details            = json_decode($request->flights_details);
                if(isset($flights_details) && $flights_details != null && $flights_details != ''){
                    DB::table('flight_seats_occupied')->where('booking_id',$id)->update([
                        'token'                         => $request->token,
                        'type'                          => 'Package',
                        'booking_id'                    => $id,
                        'flight_supplier'               => $request->flight_supplier,
                        'flight_route_id'               => $request->flight_route_id_occupied,
                        'flights_adult_seats'           => $request->flight_total_seats,
                        'flights_child_seats'           => 0,
                        'flight_route_seats_occupied'   => $request->flight_total_seats,
                    ]);
                }
            
                $activities2 = DB::table('tours_2')->where('tour_id',$id)->update([
                    'flights_details'=> $request->flights_details,
                    'multi_flight'=> $request->multiple_flight_option_arr,
                    'flights_details_more'=> $request->more_flights_details,
                    'transportation_details'=> $request->transportation_details,
                    'transportation_details_more'=> $request->transportation_details_more,
                    'Itinerary_details'=> $request->Itinerary_details,
                    'tour_itinerary_details_1'=> $request->tour_itinerary_details_1,
                    'tour_extra_price'=> $request->tour_extra_price,
                    'tour_extra_price_1'=> $request->tour_extra_price_1,
                    'tour_faq'=> $request->tour_faq,
                    'tour_faq_1'=> $request->tour_faq_1,
                    'quad_cost_price'=> $request->quad_cost_price,
                    'triple_cost_price'=> $request->triple_cost_price,
                    'double_cost_price'=> $request->double_cost_price,
                    'quad_grand_total_amount'=> $request->quad_grand_total_amount,
                    'triple_grand_total_amount'=> $request->triple_grand_total_amount,
                    'double_grand_total_amount'=> $request->double_grand_total_amount,
                    'all_markup_type'=> $request->all_markup_type,
                    'all_markup_add'=> $request->all_markup_add,
                    'markup_details'=> $request->markup_details,
                    'more_markup_details'=> $request->more_markup_details,
                    
                    'infant_flight_cost'=>$request->infant_flight_cost,
                    'infant_transp_cost'=>$request->infant_transp_cost,
                    'infant_visa_cost'=>$request->infant_visa_cost,
                    'infant_total_cost_price'=>$request->infant_total_cost_price,
                    'infant_total_sale_price'=>$request->infant_total_sale_price,
                    
                    'ch_price_other_c'=>$request->child_other_prices,
                    'in_price_other_c'=>$request->infant_other_prices,
                    'in_markup_other_c'=>$request->infant_markup_prices,
                    
                    'without_acc_sale_price'=>$request->without_acc_sale_price,
                ]);
            
                if($request->package_update_type == 'old'){
                    echo "Update";
                    $tour_bactches=DB::table('tour_batchs')->where('generate_id',$request->prev_generate_code)->update([
                        'tour_id'=>$id,
                        'customer_id'=>$request->customer_id,
                        'city_Count'=>$request->city_Count,
                        'title'=> $request->title,
                        'no_of_pax_days'=> $request->no_of_pax_days,
                        'starts_rating'=> $request->starts_rating,
                        'currency_symbol'=> $request->currency_symbol,
                        'content'=> $request->content,
                        'start_date'=> $request->start_date,
                        'end_date'=> $request->end_date,
                        'time_duration'=> $request->time_duration,
                        'categories'=> $request->tour_categories,
                        'tour_feature'=> $request->tour_feature,
                        'defalut_state'=> $request->defalut_state,
                        'tour_featured_image'=> $request->tour_featured_image,
                        'tour_banner_image'=> $request->tour_banner_image,
                        'tour_author'=> $request->tour_author,
                        'gallery_images'=> $request->gallery_images,
                        'destination_details'=> $request->destination_details,
                        'destination_details_more'=> $request->destination_details_more,
                        'accomodation_details'=> $request->accomodation_details,
                        'accomodation_details_more'=> $request->more_accomodation_details,
                        'visa_type'=> $request->visa_type,
                        'visa_rules_regulations'=> $request->visa_rules_regulations,
                        'visa_fee'=> $request->visa_fee,
                        'visa_fee_purchase'=> $request->visa_fee_purchase,
                        'exchange_rate_visa'=> $request->exchange_rate_visa,
                        'visa_image'=> $request->visa_image,
                        'whats_included'=> $request->whats_included,
                        'whats_excluded'=> $request->whats_excluded,
                        'payment_gateways'=> $request->payment_gateways,
                        'payment_modes'=> $request->payment_modes,
                        'tour_location'=> $request->tour_location,
                    ]);
                    
                    $tour_bactches2=DB::table('tour_batchs_2')->where('generate_id',$request->prev_generate_code)->update([
                        'flights_details'=> $request->flights_details,
                         'multi_flight'=> $request->multiple_flight_option_arr,
                        'flights_details_more'=> $request->more_flights_details,
                        'transportation_details'=> $request->transportation_details,
                        'transportation_details_more'=> $request->transportation_details_more,
                        'Itinerary_details'=> $request->Itinerary_details,
                        'tour_itinerary_details_1'=> $request->tour_itinerary_details_1,
                        'tour_extra_price'=> $request->tour_extra_price,
                        'tour_extra_price_1'=> $request->tour_extra_price_1,
                        'tour_faq'=> $request->tour_faq,
                        'tour_faq_1'=> $request->tour_faq_1,
                        'quad_cost_price'=> $request->quad_cost_price,
                        'triple_cost_price'=> $request->triple_cost_price,
                        'double_cost_price'=> $request->double_cost_price,
                        'quad_grand_total_amount'=> $request->quad_grand_total_amount,
                        'triple_grand_total_amount'=> $request->triple_grand_total_amount,
                        'double_grand_total_amount'=> $request->double_grand_total_amount,
                        'all_markup_type'=> $request->all_markup_type,
                        'all_markup_add'=> $request->all_markup_add,
                        'markup_details'=> $request->markup_details,
                        'more_markup_details'=> $request->more_markup_details,
                        
                        'infant_flight_cost'=>$request->infant_flight_cost,
                        'infant_transp_cost'=>$request->infant_transp_cost,
                        'infant_visa_cost'=>$request->infant_visa_cost,
                        'infant_total_cost_price'=>$request->infant_total_cost_price,
                        'infant_total_sale_price'=>$request->infant_total_sale_price,
                        
                        'ch_price_other_c'=>$request->child_other_prices,
                        'in_price_other_c'=>$request->infant_other_prices,
                        'in_markup_other_c'=>$request->infant_markup_prices,
                    ]);
                    DB::commit();  
                    return response()->json(['status'=>'success','Tour'=>$tour_bactches,'Tour2'=>$tour_bactches2,'Success'=>'Tour Updated Successful!']);
                }else{
                    
                    $tour_bactches=DB::table('tour_batchs')->insertGetId([
                        'tour_id'=>$id,
                        'generate_id'=>$generate_id,
                        'customer_id'=>$request->customer_id,
                        'title'=> $request->title,
                        'city_Count'=>$request->city_Count,
                        'no_of_pax_days'=> $request->no_of_pax_days,
                        'starts_rating'=> $request->starts_rating,
                        'currency_symbol'=> $request->currency_symbol,
                        'content'=> $request->content,
                        'start_date'=> $request->start_date,
                        'end_date'=> $request->end_date,
                        'time_duration'=> $request->time_duration,
                        'categories'=> $request->tour_categories,
                        'tour_feature'=> $request->tour_feature,
                        'defalut_state'=> $request->defalut_state,
                        'tour_featured_image'=> $request->tour_featured_image,
                        'tour_banner_image'=> $request->tour_banner_image,
                        'tour_author'=> $request->tour_author,
                        'gallery_images'=> $request->gallery_images,
                        'destination_details'=> $request->destination_details,
                        'destination_details_more'=> $request->destination_details_more,
                        'accomodation_details'=> $request->accomodation_details,
                        'accomodation_details_more'=> $request->more_accomodation_details,
                        'visa_type'=> $request->visa_type,
                        
                        'visa_fee_purchase'=> $request->visa_fee_purchase,
                        'exchange_rate_visa'=> $request->exchange_rate_visa,
                        
                        'visa_rules_regulations'=> $request->visa_rules_regulations,
                        'visa_fee'=> $request->visa_fee,
                        'visa_image'=> $request->visa_image,
                        'whats_included'=> $request->whats_included,
                        'whats_excluded'=> $request->whats_excluded,
                        'payment_gateways'=> $request->payment_gateways,
                        'payment_modes'=> $request->payment_modes,
                        'tour_location'=> $request->tour_location,
                    ]);
                    
                    $lastbatchsId = $tour_bactches;
                    
                    $tour_bactches2=DB::table('tour_batchs_2')->insert([
                        'batchs_id'=>$lastbatchsId,
                        'generate_id'=>$generate_id,
                        'flights_details'=> $request->flights_details,
                        'flights_details_more'=> $request->more_flights_details,
                        'transportation_details'=> $request->transportation_details,
                        'transportation_details_more'=> $request->transportation_details_more,
                        'Itinerary_details'=> $request->Itinerary_details,
                        'tour_itinerary_details_1'=> $request->tour_itinerary_details_1,
                        'tour_extra_price'=> $request->tour_extra_price,
                        'tour_extra_price_1'=> $request->tour_extra_price_1,
                        'tour_faq'=> $request->tour_faq,
                        'tour_faq_1'=> $request->tour_faq_1,
                        'quad_cost_price'=> $request->quad_cost_price,
                        'triple_cost_price'=> $request->triple_cost_price,
                        'double_cost_price'=> $request->double_cost_price,
                        'quad_grand_total_amount'=> $request->quad_grand_total_amount,
                        'triple_grand_total_amount'=> $request->triple_grand_total_amount,
                        'double_grand_total_amount'=> $request->double_grand_total_amount,
                        'all_markup_type'=> $request->all_markup_type,
                        'all_markup_add'=> $request->all_markup_add,
                        'markup_details'=> $request->markup_details,
                        'more_markup_details'=> $request->more_markup_details,
                    ]);
                    DB::commit();  
                    return response()->json(['status'=>'success','tour_bactches2'=>$tour_bactches2,'lastbatchsId'=>$lastbatchsId,'Success'=>'Tour Updated Successful!']);
                }
            
            } catch (Throwable $e) {
                DB::rollback();
                return response()->json(['message'=>'error']);
            }
        }
        else{
            return response()->json(['status'=>'error','Tour'=>$activities,'Error'=>'Tour Not Updated!']);
        }
    }
    // End Tour Packages
    

  public function submit_tourOld(Request $request)
  {
      $tour = new Tour();
      $tour->title=$request->title;
      $tour->content=$request->content;
      $categories=$request->categories;
      $categories=json_encode($categories);

      $tour->categories=$categories;

      $attributes=$request->tour_attributes;

      // print_r($attributes);die();
      $attributes=json_encode($attributes);

      $tour->tour_attributes=$attributes;
      
      
      
      //usama changes
      $tour->property_country=$request->property_country;
      $tour->property_city=$request->property_city;
      $tour->hotel_name=$request->hotel_name;
      $tour->hotel_rooms_type=$request->hotel_rooms_type;
      $tour->price_per_night=$request->price_per_night;
      $tour->total_price_per_night=$request->total_price_per_night;
      //
      
      


      $tour->start_date=$request->start_date;
      $tour->end_date=$request->end_date;
      $tour->time_duration=$request->time_duration;
      $tour->tour_min_people=$request->tour_min_people;
      $tour->tour_max_people=$request->tour_max_people;
      $tour->tour_location=$request->tour_location;
    //   $tour->tour_real_address=$request->tour_real_address;
    //   $tour->tour_pricing=$request->tour_pricing;
    //   $tour->tour_sale_price=$request->tour_sale_price;
      $tour->tour_publish=$request->tour_publish;
      $tour->tour_author=$request->tour_author;
      $tour->tour_feature=$request->tour_feature;
      $tour->defalut_state=$request->defalut_state;


      if($request->hasFile('tour_featured_image')){

        $file = $request->file('tour_featured_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_featured_image = $filename;
    }
    else{
        $tour_featured_image = '';
    }


      $tour->tour_featured_image=$tour_featured_image;
      if($request->hasFile('tour_banner_image')){

        $file = $request->file('tour_banner_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_banner_image = $filename;
    }
    else{
        $tour_banner_image = '';
    }
      $tour->tour_banner_image=$tour_banner_image;



      $Itinerary_title=$request->Itinerary_title;
      $Itinerary_city=$request->Itinerary_city;
      $Itinerary_content=$request->Itinerary_content;

      if($request->hasFile('Itinerary_image')){

        $file = $request->file('Itinerary_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $Itinerary_image = $filename;
    }
    else{
        $Itinerary_image = '';
    }
      $Itinerary_image=$Itinerary_image;
      $Itinerary_details=array([
                        'Itinerary_title'=>$Itinerary_title,
                          'Itinerary_city'=>$Itinerary_city,
                          'Itinerary_content'=>$Itinerary_content,
                          'Itinerary_image'=>$Itinerary_image,

                          ]);

      $Itinerary_details=json_encode($Itinerary_details);

      $tour->Itinerary_details=$Itinerary_details;






      $more_Itinerary_title=$request->more_Itinerary_title;
      $more_Itinerary_city=$request->more_Itinerary_city;
      $more_Itinerary_content=$request->more_Itinerary_content;
      
  $more_Itinerary_image=array();
    if($files=$request->file('more_Itinerary_image')){
        foreach($files as $file){
            $name=$file->getClientOriginalName();
            $file->move('public/uploads/package_imgs/',$name);
            $more_Itinerary_image[]=$name;
        }
    }

      $more_Itinerary_image=$more_Itinerary_image;
    //   print_r($more_Itinerary_image); 
    //  die(); 
      if(isset($more_Itinerary_title))
      {
          
          $arrLength = count($more_Itinerary_title);
      for($i = 0; $i < $arrLength; $i++) {

$more_itinery_details[]=(object)[
    
    'more_Itinerary_title'=>$more_Itinerary_title[$i],
    'more_Itinerary_city'=>$more_Itinerary_city[$i],
    'more_Itinerary_content'=>$more_Itinerary_content[$i],
   'more_Itinerary_image'=>$more_Itinerary_image[$i],
    ];

}
// print_r($more_itinery_details); 
//      die(); 

 
      $tour_itinerary_details_1=json_encode($more_itinery_details);
       $tour->tour_itinerary_details_1=$tour_itinerary_details_1;
    //      print_r($tour_itinerary_details_1);
    //   die();

// print_r(json_decode($tour_itinerary_details_1));die();
          
      }
      else
{
   $tour_itinerary_details_1= ''; 
}

     










      $extra_price_name=$request->extra_price_name;
      $extra_price_price=$request->extra_price_price;
      $extra_price_type=$request->extra_price_type;
      $extra_price_person=$request->extra_price_person;
      
      $tour_extra_price=array([
        'extra_price_name'=>$extra_price_name,
          'extra_price_price'=>$extra_price_price,
          'extra_price_type'=>$extra_price_type,
          'extra_price_person'=>$extra_price_person,

          ]);

$tour_extra_price=json_encode($tour_extra_price);

$tour->tour_extra_price=$tour_extra_price;

$faq_title=$request->faq_title;
      $faq_content=$request->faq_content;
      
      $tour_faq=array([
        'faq_title'=>$faq_title,
          'faq_content'=>$faq_content,
          

          ]);

$tour_faq=json_encode($tour_faq);
$tour->tour_faq=$tour_faq;






$more_extra_price_title=$request->more_extra_price_title;
      $more_extra_price_price=$request->more_extra_price_price;
      $more_extra_price_type=$request->more_extra_price_type;
      $more_extra_price_person=$request->more_extra_price_person;

 if(isset($more_extra_price_title))
{
        $arrLength1 = count($more_extra_price_title);
      for($i = 0; $i < $arrLength1; $i++) {

$more_extra_price_details[]=(object)[
    
    'more_extra_price_title'=>$more_extra_price_title[$i],
    'more_extra_price_price'=>$more_extra_price_price[$i],
    'more_extra_price_type'=>$more_extra_price_type[$i],
    'more_extra_price_person'=>$more_extra_price_person[$i],
    ];

}
// print_r($more_extra_price_details); 
// die();
$tour_extra_price_1=json_encode($more_extra_price_details);
$tour->tour_extra_price_1=$tour_extra_price_1;
    
}
else
{
   $tour_extra_price_1= ''; 
}



$more_faq_title=$request->more_faq_title;
      $more_faq_content=$request->more_faq_content;
      
     if(isset($more_faq_title))
{
     $arrLength1 = count($more_faq_title);
      for($i = 0; $i < $arrLength1; $i++) {

$more_faq_details[]=(object)[
    
    'more_faq_title'=>$more_faq_title[$i],
    'more_faq_content'=>$more_faq_content[$i],
   
    ];

}
// print_r($more_faq_details); 
// die();




$tour_faq_1=json_encode($more_faq_details);
$tour->tour_faq_1=$tour_faq_1;
    
}
else
{
   $tour_faq_1= ''; 
}










      $tour->save();
        return redirect()->back()->with('message','Tour Created Successful!');
    }


    public function view_tourOLD(Request $request){

        $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->get();
       
        return view('template/frontend/userdashboard/pages/tour/view_tour',compact('tours'));  
    }

    public function edit_tourOld($id){
        $tours=Tour::find($id);
        $categories=DB::table('categories')->get();
        $attributes=DB::table('Attributes')->get();
        return view('template/frontend/userdashboard/pages/tour/edit_tour',compact('tours','categories','attributes'));  
    }
    
    
    public function booking_allocations(Request $request){
        $id                     = $request->id;
        $customer_id            = $request->customer_id;
        $data                   = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$id)->first();
        $booking_Details        = DB::table('cart_details')
                                    ->join('tours_bookings','cart_details.booking_id','tours_bookings.id')
                                    ->where('cart_details.tour_id',$id)
                                    ->where('cart_details.pakage_type','tour')
                                    ->get();
        $reservation_Details    = DB::table('Hotel_Booking_Reservation_Details')->where('Hotel_Booking_Reservation_Details.tour_No',$id)->get();
        $all_countries          = country::all();
        return response()->json(['message'=>'success','data'=>$data,'booking_Details'=>$booking_Details,'reservation_Details'=>$reservation_Details,'all_countries'=>$all_countries]);
    }
    
    public function delete_tour_api(Request $request){
        DB::beginTransaction();
        try {
            if($request->flight_id){
                $seat           = DB::table('Flight_sup_seats')->where('id',$request->flight_id)->select('occupied_seat')->first();
                $pax            = DB::table('tours')->where('id',$request->id)->select('no_of_pax_days')->first();
                $total_occupied = $seat->occupied_seat - $pax->no_of_pax_days;
                DB::table('Flight_sup_seats')->where('id',$request->flight_id)->update([
                    'occupied_seat'=>$total_occupied,
                ]);
            }
            $id             = $request->id;
            $generate_id    = $request->generate_id;
            $tours          = Tour::find($id);
            $tours->delete();
            DB::table('tours_2')->where('tour_id', $id)->delete();
            DB::table('tour_batchs')->where('generate_id', $generate_id)->delete();
            DB::table('tour_batchs_2')->where('generate_id', $generate_id)->delete();
            DB::table('cart_details')->where('tour_id', $id)->where('cart_details.pakage_type', 'tour')->delete();
            $tours_bookings = DB::table('cart_details')
                                ->join('tours_bookings','cart_details.booking_id','tours_bookings.id')
                                ->where('cart_details.tour_id', $id)->where('cart_details.pakage_type', 'tour')
                                ->select('cart_details.booking_id')
                                ->get();
            foreach($tours_bookings as $booking_id){
                foreach($booking_id as $booking_ids){
                    DB::table('tours_bookings')->where('id', $booking_ids)->delete();die();
                }
            }
            DB::commit();  
            return response()->json(['message'=>'Tour and its all details deleted Successful!']);
        } catch (Throwable $e) {

            DB::rollback();
            return response()->json(['message'=>'error']);
        }
    }

    public function submit_edit_tourOld(Request $request,$id){
        $tour=Tour::find($id);
        if($tour){
          $tour->title=$request->title;
          $tour->content=$request->content;
          $categories=$request->categories;
          $categories=json_encode($categories);
    
          $tour->categories=$categories;
    
          $attributes=$request->attributes;
    
          // print_r($attributes);die();
          $attributes=json_encode($attributes);
    
          $tour->attributes=$attributes;
    
    
          $tour->start_date=$request->start_date;
          $tour->end_date=$request->end_date;
          $tour->time_duration=$request->time_duration;
          $tour->tour_min_people=$request->tour_min_people;
          $tour->tour_max_people=$request->tour_max_people;
          $tour->tour_location=$request->tour_location;
        //   $tour->tour_real_address=$request->tour_real_address;
        //   $tour->tour_pricing=$request->tour_pricing;
        //   $tour->tour_sale_price=$request->tour_sale_price;
          $tour->tour_publish=$request->tour_publish;
          $tour->tour_author=$request->tour_author;
          $tour->tour_feature=$request->tour_feature;
          $tour->defalut_state=$request->defalut_state;
    
    
          if($request->hasFile('tour_featured_image')){
    
            $file = $request->file('tour_featured_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/uploads/package_imgs/', $filename);
            $tour_featured_image = $filename;
        }
        else{
            $tour_featured_image = '';
        }
    
    
          $tour->tour_featured_image=$tour_featured_image;
          if($request->hasFile('tour_banner_image')){
    
            $file = $request->file('tour_banner_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/uploads/package_imgs/', $filename);
            $tour_banner_image = $filename;
        }
        else{
            $tour_banner_image = '';
        }
          $tour->tour_banner_image=$tour_banner_image;
    
    
    
          $Itinerary_title=$request->Itinerary_title;
          $Itinerary_city=$request->Itinerary_city;
          $Itinerary_content=$request->Itinerary_content;
    
          if($request->hasFile('Itinerary_image')){
    
            $file = $request->file('Itinerary_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/uploads/package_imgs/', $filename);
            $Itinerary_image = $filename;
        }
        else{
            $Itinerary_image = '';
        }
          $Itinerary_image=$Itinerary_image;
          $Itinerary_details=array([
                            'Itinerary_title'=>$Itinerary_title,
                              'Itinerary_city'=>$Itinerary_city,
                              'Itinerary_content'=>$Itinerary_content,
                              'Itinerary_image'=>$Itinerary_image,
    
                              ]);
    
          $Itinerary_details=json_encode($Itinerary_details);
    
          $tour->Itinerary_details=$Itinerary_details;
    
    
    
    
    
    
          $more_Itinerary_title=$request->more_Itinerary_title;
          $more_Itinerary_city=$request->more_Itinerary_city;
          $more_Itinerary_content=$request->more_Itinerary_content;
    
          if($request->hasFile('more_Itinerary_image')){
    
            $file = $request->file('more_Itinerary_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/uploads/package_imgs/', $filename);
            $more_Itinerary_image = $filename;
        }
        else{
            $more_Itinerary_image = '';
        }
          $more_Itinerary_image=$more_Itinerary_image;
          $tour_itinerary_details_1=array([
                            'more_Itinerary_title'=>$more_Itinerary_title,
                              'more_Itinerary_city'=>$more_Itinerary_city,
                              'more_Itinerary_content'=>$more_Itinerary_content,
                              'more_Itinerary_image'=>$more_Itinerary_image,
    
                              ]);
    
          $tour_itinerary_details_1=json_encode($tour_itinerary_details_1);
    
          $tour->tour_itinerary_details_1=$tour_itinerary_details_1;
    
    
    
    
    
    
    
    
    
    
          $extra_price_name=$request->extra_price_name;
          $extra_price_price=$request->extra_price_price;
          $extra_price_type=$request->extra_price_type;
          $extra_price_person=$request->extra_price_person;
          $tour_extra_price=array([
            'extra_price_name'=>$extra_price_name,
              'extra_price_price'=>$extra_price_price,
              'extra_price_type'=>$extra_price_type,
              'extra_price_person'=>$extra_price_person,
    
              ]);
    
    $tour_extra_price=json_encode($tour_extra_price);
    
    $tour->tour_extra_price=$tour_extra_price;
    
    $faq_title=$request->faq_title;
          $faq_content=$request->faq_content;
          $tour_extra_price=array([
            'faq_title'=>$faq_title,
              'faq_content'=>$faq_content,
              
    
              ]);
    
    $tour_extra_price=json_encode($tour_extra_price);
    $tour->tour_faq=$tour_extra_price;
    
    
    
    
    
    
    $more_extra_price_title=$request->more_extra_price_title;
          $more_extra_price_price=$request->more_extra_price_price;
          $more_extra_price_type=$request->more_extra_price_type;
          $more_extra_price_person=$request->more_extra_price_person;
          $tour_extra_price_1=array([
            'more_extra_price_title'=>$more_extra_price_title,
              'more_extra_price_price'=>$more_extra_price_price,
              'more_extra_price_type'=>$more_extra_price_type,
              'more_extra_price_person'=>$more_extra_price_person,
    
              ]);
    
    $tour_extra_price_1=json_encode($tour_extra_price_1);
    
    $tour->tour_extra_price_1=$tour_extra_price_1;
    
    $faq_title=$request->faq_title;
          $tour_faq=$request->faq_content;
          $tour_extra_price=array([
            'faq_title'=>$faq_title,
              'faq_content'=>$faq_content,
              
    
              ]);
    
    $tour_faq=json_encode($tour_faq);
    $tour->tour_extra_price=$tour_faq;
    
    
    
    
    
    $more_faq_title=$request->more_faq_title;
          $more_faq_content=$request->more_faq_content;
          $tour_faq_1=array([
            'more_faq_content'=>$more_faq_content,
              'more_faq_content'=>$more_faq_content,
              
    
              ]);
    
    $tour_faq_1=json_encode($tour_faq_1);
    $tour->tour_faq_1=$tour_faq_1;
    
    
    
    
    
    
    
          $tour->update();
    
    
          return redirect('super_admin/view_tour')->with('message','Tour Updated Successful!');
        }
        else{
          return redirect('super_admin/view_tour')->with('message','Tour Updated Not Successful!');
        }
    }
  
    public function delete_tour($id){
        // dd($flightid);
       
    
    
        $tours=Tour::find($id);
        $tours->delete();
        return redirect()->back()->with('message','Tour deleted Successful!');
    }

    public function enable_tour($id){
    $tours=Tour::find($id);
    if($tours)
    {
      $tours->tour_publish='0';
      $tours->update();
      return redirect()->back()->with('message','Tour Status Updated Successful!');
    }
    else{
      return redirect()->back()->with('message','Tour Status Not Updated Successful!');
    }
   
  }

    public function enable_tour_api(Request $request){
    $id=$request->id;
    $tours=Tour::find($id);
    if($tours)
    {
      $tours->tour_publish='0';
      $tours->update();
      return redirect()->back()->with('message','Tour Status Updated Successful!');
    }
    else{
      return redirect()->back()->with('message','Tour Status Not Updated Successful!');
    }
   
  }

    public function disable_tour($id){
    
    $tours=Tour::find($id);
    if($tours)
    {
      $tours->tour_publish='1';
      $tours->update();
      return redirect()->back()->with('message','Tour Status Updated Successful!');
    }
    else{
      return redirect()->back()->with('message','Tour Status Not Updated Successful!');
    }
  }
  
    public function disable_tour_api(Request $request){
    $id=$request->id;
    // print_r($id);die();
    $tours=Tour::find($id);
    print_r($tours);
    if($tours)
    {
      $tours->tour_publish='1';
      $tours->update();
      return redirect()->back();
    }
    else{
      return redirect()->back();
    }
  }

    
    // More Tour Details
    public function more_Tour_Details(Request $request){
    // print_r($request->all());die;
    $packges_tour   = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$request->id)->count();
    $no_of_pax_days = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$request->id)->first();
    $booked_tour    = DB::table('cart_details')->where('cart_details.tour_id',$request->id)->where('cart_details.pakage_type','tour')->count();
    $outStandings   = DB::table('cart_details')->where('cart_details.tour_id',$request->id)->sum('price');
    $recieved       = DB::table('view_booking_payment_recieve')->where('tourId',$request->id)->sum('remaining_amount');
    
    $booking_data   = DB::table('cart_details')->join('tours_bookings','cart_details.booking_id','tours_bookings.id')->where('cart_details.tour_id',$request->id)->where('cart_details.pakage_type','tour')->get();
    $booking_count  = DB::table('cart_details')->join('tours_bookings','cart_details.booking_id','tours_bookings.id')->where('cart_details.tour_id',$request->id)->where('cart_details.pakage_type','tour')->count();
    
    return response()->json([
        'packges_tour'      => $packges_tour,
        'no_of_pax_days'    => $no_of_pax_days,
        'booked_tour'       => $booked_tour,
        'outStandings'      => $outStandings,
        'recieved'          => $recieved,
        'booking_data'      => $booking_data,
        'booking_count'     => $booking_count,
    ]);
  }
  
    public function get_tours_booking(Request $request){
    // print_r($request->all());die;

    $booking_data   = DB::table('cart_details')->where('cart_details.tour_id',$request->id)->get();
   
    return response()->json([
        'booking_data'      => $booking_data,
    ]);
  }
  
    public function view_more_bookings(Request $request){
        $booked_tour_payments_details   = DB::table('view_booking_payment_recieve')->where('view_booking_payment_recieve.package_id',$request->id)->get();
        $recieved_amount                = DB::table('view_booking_payment_recieve')->where('view_booking_payment_recieve.package_id',$request->id)->sum('recieved_amount');
        $total_amount                   = DB::table('view_booking_payment_recieve')->where('view_booking_payment_recieve.package_id',$request->id)->select('total_amount')->first();
        $remaining_amount               = DB::table('view_booking_payment_recieve')->where('view_booking_payment_recieve.package_id',$request->id)->sum('remaining_amount');
        $amount_paid                    = DB::table('view_booking_payment_recieve')->where('view_booking_payment_recieve.package_id',$request->id)->sum('amount_paid');
        
        return response()->json([
            'booked_tour_payments_details'      => $booked_tour_payments_details,
            'recieved_amount'                   => $recieved_amount,
            'total_amount'                      => $total_amount,
            'remaining_amount'                  => $remaining_amount,
            'amount_paid'                       => $amount_paid,
        ]);
    }
    
    // Super Admin Index
    public function super_admin_index(Request $request){
        $tours                          = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$request->customer_id)->get();
        $new_activites                  = DB::table('new_activites')->where('new_activites.customer_id',$request->customer_id)->get();
        $latest_packages                = DB::table('tours_bookings')->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')->join('cart_details','tours_bookings.id','cart_details.booking_id')->get();
        $data1                          = DB::table('cart_details')->get();
        $data2                          = DB::table('tours_bookings')->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','tour')->get();
        $data3                          = DB::table('tours_bookings')->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','activity')->get();
        $packages_tour                  = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$request->customer_id)->count();
        $no_of_pax_days                 = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$request->customer_id)->sum('no_of_pax_days');
        $booked_tour                    = DB::table('cart_details')->where('pakage_type','tour')->count();
        $booked_tourA                   = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','tour')->sum('adults');
        $booked_tourC                   = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','tour')->sum('childs');
        $toTal                          = DB::table('cart_details')->where('pakage_type','tour')->sum('price');
        $pre_Week                       = date("Y-m-d", strtotime("-10 days"));
        $pre_Week1                      = date("Y-m-d", strtotime("-4 days"));
        $current_week                   = $this->getCurrentWeek();
        $toTal_week                     = DB::table('cart_details')->where('pakage_type','tour')->whereBetween('created_at',[$current_week['first_day'],$current_week['last_day']])->sum('price');
        $tour_today_earn                = DB::table('cart_details')->where('pakage_type','tour')->whereDate('created_at',date('Y-m-d'))->sum('price');
        $activity_today_earn            = DB::table('cart_details')->where('pakage_type','activity')->whereDate('created_at',date('Y-m-d'))->sum('price');
        $toTal_today_earn               = $tour_today_earn + $activity_today_earn;
        $recieved                       = DB::table('view_booking_payment_recieve')->sum('remaining_amount');
        // $activities_count               = DB::table('actives')->where('customer_id',$request->customer_id)->count();
        // $activities_no_of_pax_days      = DB::table('actives')->where('customer_id',$request->customer_id)->sum('no_of_pax_days');
        $activities_count               = DB::table('new_activites')->where('new_activites.customer_id',$request->customer_id)->count();
        $activities_no_of_pax_days      = DB::table('new_activites')->where('new_activites.customer_id',$request->customer_id)->sum('max_people');
        $booked_activities              = DB::table('cart_details')->where('pakage_type','activity')->count();
        $booked_activitiesA             = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','activity')->sum('adults');
        $booked_activitiesC             = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','activity')->sum('childs');
        $toTal_activities               = DB::table('cart_details')->where('pakage_type','activity')->sum('price');
        $toTal_activities_week          = DB::table('cart_details')->where('pakage_type','activity')->whereBetween('created_at',[$current_week['first_day'],$current_week['last_day']])->sum('price');
        $recieved_activities            = DB::table('view_booking_payment_recieve_Activity')->sum('remaining_amount');
        $currentYear                    = date("Y");
        $currentMonth                   = date("m");
        for($i = 1; $i<=12; $i++){
            $months[]                   = $this->getMonthsName($i);
            $month                      = $i;
            if($i < 10){ $month         = 0 . $i; }
            $total_bookings             = DB::table('cart_details')->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $currentYear)->where('pakage_type','tour')->where('client_id',$request->customer_id)->count();
            $package_month[]            = $total_bookings;
            $total_bookings1            = DB::table('cart_details')->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $currentYear)->where('pakage_type','activity')->where('client_id',$request->customer_id)->count();
            $package_month1[]           = $total_bookings1;
        }
        for($i = 1; $i<=7; $i++){
            $weeks[]                    = $this->getWeeksName($i);
            $week                       = $i;
            if($i < 8){ $week           = 0 . $i; }
            $total_bookings_w           = DB::table('cart_details')->whereDay('created_at', '=', $week)->whereMonth('created_at', '=', $currentMonth)->whereYear('created_at', '=', $currentYear)->where('pakage_type','tour')->where('client_id',$request->customer_id)->count();
            $package_weeks[]            = $total_bookings_w;
            $total_bookings_w1          = DB::table('cart_details')->whereDay('created_at', '=', $week)->whereMonth('created_at', '=', $currentMonth)->whereYear('created_at', '=', $currentYear)->where('pakage_type','activity')->where('client_id',$request->customer_id)->count();
            $package_weeks1[]           = $total_bookings_w1;
        }
        
        // Total Revenue/Sale
        $total_Revenue_Invoice      = DB::table('add_manage_invoices')
                                        ->where('add_manage_invoices.customer_id',$request->customer_id)
                                        ->sum('total_sale_price_all_Services');
        
        $total_Revenue_P            = DB::table('tours_bookings')
                                        ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                        ->where('tours_bookings.customer_id',$request->customer_id)
                                        ->where('cart_details.pakage_type','tour')
                                        ->sum('cart_details.price');
                                                    
        $toTal_Revenue_A             = DB::table('tours_bookings')
                                        ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                        ->where('tours_bookings.customer_id',$request->customer_id)
                                        ->where('cart_details.pakage_type','activity')
                                        ->sum('cart_details.price');
                                        
        $travellanda_HotelBooking   = DB::table('hotel_booking')
                                        ->where('hotel_booking.auth_token',$request->token)
                                        ->where('hotel_reservation','confirmed')
                                        ->where('provider','travellanda')
                                        ->select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(travellandaSelectionRS, '$[*].TotalPrice')) as TotalPrice"))
                                        ->get();
                                        
        $hotelbeds_HotelBooking     = DB::table('hotel_booking')
                                        ->where('hotel_booking.auth_token',$request->token)
                                        ->where('hotel_reservation','confirmed')
                                        ->where('provider','hotelbeds')
                                        ->select(DB::raw("JSON_EXTRACT(hotelbedSelectionRS, '$.hotel.totalNet') as TotalPrice"))
                                        ->get();
                                        
        $tbo_HotelBooking           = DB::table('hotel_booking')
                                        ->where('hotel_booking.auth_token',$request->token)
                                        ->where('hotel_reservation','confirmed')
                                        ->where('provider','tbo')
                                        ->select(DB::raw("JSON_EXTRACT(tboSelectionRS, '$.HotelResult') as HotelResult"))
                                        ->get();
        
        $ratehawk_HotelBooking      = DB::table('hotel_booking')
                                        ->where('hotel_booking.auth_token',$request->token)
                                        ->where('hotel_reservation','confirmed')
                                        ->where('provider','ratehawk')
                                        ->select(DB::raw("JSON_EXTRACT(ratehawk_details_rs1, '$.hotels') as hotels"))
                                        ->get();
                                        
        $hotels_HotelBooking        = DB::table('hotel_booking')
                                        ->where('hotel_booking.auth_token',$request->token)
                                        ->where('hotel_reservation','confirmed')
                                        ->where('provider','hotels')
                                        ->select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(rooms_checkavailability, '$[*].rooms_price')) as rooms_price"))
                                        ->get();
        
        // Total Cost Price
        $total_cost_price_Invoice   = DB::table('add_manage_invoices')
                                        ->where('add_manage_invoices.customer_id',$request->customer_id)
                                        ->sum('total_cost_price_all_Services');
        
        $tour_booking               = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                        ->where('tours_bookings.customer_id',$request->customer_id)
                                        ->get();
        if(count($tour_booking) > 0){
            foreach($tour_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $grand_profit   = 0;
                $total_cost_price_bookings  = 0;
                $total_Revenue_PandABooking = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_adult_total_cost;
                    $total_Revenue_PandABooking += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_adult_total_cost;
                    $total_Revenue_PandABooking += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_adult_total_cost;
                    $total_Revenue_PandABooking += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_adult_total_cost;
                    $total_Revenue_PandABooking += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_child_total_cost;
                    $total_Revenue_PandABooking += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_child_total_cost;
                    $total_Revenue_PandABooking += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_child_total_cost;
                    $total_Revenue_PandABooking += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_child_total_cost;
                    $total_Revenue_PandABooking += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_infant_total_cost;
                    $total_Revenue_PandABooking += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_infant_total_cost;
                    $total_Revenue_PandABooking += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_infant_total_cost;
                    $total_Revenue_PandABooking += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_infant_total_cost;
                    $total_Revenue_PandABooking += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $total_Revenue_PandABooking = 0;
            $total_cost_price_bookings = 0;
        }
        
        // dd($total_cost_price_bookings);
        // dd($total_Revenue_PandABooking);
        
        // $total_Revenue_PandABooking = $total_Revenue_P + $toTal_Revenue_A;
        // End
        
        // Total Expense Outstandings
        $total_expense_Out_Invoice          = DB::table('pay_Invoice_Agent')
                                                ->where('pay_Invoice_Agent.customer_id',$request->customer_id)
                                                ->sum('recieved_Amount');
        
        $total_expense_Out_PandAbookings    = DB::table('view_booking_payment_recieve')
                                                ->where('view_booking_payment_recieve.customer_id',$request->customer_id)
                                                ->sum('recieved_amount');
                                                
        $total_expense_Out_hotelbookings    = DB::table('hotel_payment_details')
                                                ->where('hotel_payment_details.auth_token',$request->token)
                                                ->sum('recieved_amount');
        // End
        
        // Total Income
        $total_Income_Invoice               = DB::table('pay_Invoice_Agent')
                                                ->where('pay_Invoice_Agent.customer_id',$request->customer_id)
                                                ->sum('recieved_Amount');
        
        $total_Income_PandAbookings         = DB::table('view_booking_payment_recieve')
                                                ->where('view_booking_payment_recieve.customer_id',$request->customer_id)
                                                ->sum('recieved_amount');
                                                
        $total_Income_hotelbookings         = DB::table('hotel_payment_details')
                                                ->where('hotel_payment_details.auth_token',$request->token)
                                                ->sum('recieved_amount');
        // End
        
        // Total Income Outstandings
        $total_Income_Out_Invoice          = DB::table('pay_Invoice_Agent')
                                                ->where('pay_Invoice_Agent.customer_id',$request->customer_id)
                                                ->sum('remaining_Amount');
        
        $total_Income_Out_PandAbookings    = DB::table('view_booking_payment_recieve')
                                                ->where('view_booking_payment_recieve.customer_id',$request->customer_id)
                                                ->sum('remaining_amount');
                                                
        $total_Income_Out_hotelbookings    = DB::table('hotel_payment_details')
                                                ->where('hotel_payment_details.auth_token',$request->token)
                                                ->sum('remaining_amount');
        // End
        
        // Separate Revenue Invoice
        $separate_Revenue_accomodation      = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->whereJsonContains('services',['accomodation_tab'])
                                                ->select('accomodation_details','accomodation_details_more')
                                                // ->select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(accomodation_details, '$[*].acc_total_amount*acc_qty')) as acc_total_amount"))
                                                // ->select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(accomodation_details, '$[*].acc_qty')) as acc_qty"))
                                                // ->select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(accomodation_details_more, '$[*].more_acc_total_amount')) as more_acc_total_amount"))
                                                // ->select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(accomodation_details_more, '$[*].more_acc_qty')) as more_acc_qty"))
                                                ->get();
        
        $separate_Revenue_flight            = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->whereJsonContains('services',['flights_tab'])
                                                ->select('no_of_pax_days','flights_details','flights_details_more','markup_details')
                                                ->get();
        
        $separate_Revenue_visa              = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->whereJsonContains('services',['visa_tab'])
                                                ->select('visa_fee','no_of_pax_days','total_visa_markup_value','visa_Pax','more_visa_details','markup_details')
                                                ->get();
        
        $separate_Revenue_transportation    = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->whereJsonContains('services',['transportation_tab'])
                                                ->select('no_of_pax_days','transportation_details','transportation_details_more','markup_details')
                                                ->get();
                                                        
        // End
        
        // Separate Revenue and Cost Package and Activity Bookings
        $separate_package_booking   = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                        ->where('tours_bookings.customer_id',$request->customer_id)
                                        ->where('cart_details.pakage_type','tour')
                                        ->get();
        if(count($separate_package_booking) > 0){
            $separate_package_grand_profit = 0;
            $separate_package_cost_price   = 0;
            $separate_package_Revenue      = 0;
            foreach($separate_package_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $separate_package_grand_profit += $double_profit;
                    $separate_package_cost_price += $double_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $separate_package_grand_profit += $triple_profit;
                    $separate_package_cost_price += $triple_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $separate_package_grand_profit += $quad_profit;
                    $separate_package_cost_price += $quad_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $separate_package_grand_profit += $without_acc_profit;
                    $separate_package_cost_price += $without_acc_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $separate_package_grand_profit += $double_profit;
                    $separate_package_cost_price += $double_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $separate_package_grand_profit += $triple_profit;
                    $separate_package_cost_price += $triple_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $separate_package_grand_profit += $quad_profit;
                    $separate_package_cost_price += $quad_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $separate_package_grand_profit += $without_acc_profit;
                    $separate_package_cost_price += $without_acc_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $separate_package_grand_profit += $double_profit;
                    $separate_package_cost_price += $double_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $separate_package_grand_profit += $triple_profit;
                    $separate_package_cost_price += $triple_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $separate_package_grand_profit += $quad_profit;
                    $separate_package_cost_price += $quad_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $separate_package_grand_profit += $without_acc_profit;
                    $separate_package_cost_price += $without_acc_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $separate_package_grand_profit = 0;
            $separate_package_cost_price   = 0;
            $separate_package_Revenue      = 0;
        }
        
        $separate_activity_booking  = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                        ->where('tours_bookings.customer_id',$request->customer_id)
                                        ->where('cart_details.pakage_type','activity')
                                        ->get();
        if(count($separate_activity_booking) > 0){
            $separate_activity_grand_profit = 0;
            $separate_activity_cost_price   = 0;
            $separate_activity_Revenue      = 0;
            foreach($separate_activity_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price += $double_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $triple_profit;
                    $separate_activity_cost_price += $triple_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $quad_profit;
                    $separate_activity_cost_price += $quad_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $without_acc_profit;
                    $separate_activity_cost_price += $without_acc_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price += $double_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $separate_activity_grand_profit += $triple_profit;
                    $separate_activity_cost_price += $triple_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $separate_activity_grand_profit += $quad_profit;
                    $separate_activity_cost_price += $quad_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $separate_activity_grand_profit += $without_acc_profit;
                    $separate_activity_cost_price += $without_acc_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price += $double_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $triple_profit;
                    $separate_activity_cost_price += $triple_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $quad_profit;
                    $separate_activity_cost_price += $quad_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $without_acc_profit;
                    $separate_activity_cost_price += $without_acc_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $separate_activity_grand_profit = 0;
            $separate_activity_cost_price   = 0;
            $separate_activity_Revenue      = 0;
        }
        // End
        
        // Weekly Revenue
        $toTal_weekly_revenue_Accomodation      = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    ->whereBetween('add_manage_invoices.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                                    ->whereJsonContains('services',['accomodation_tab'])
                                                    // ->orWhereJsonContains('services',['1'])
                                                    ->select('accomodation_details','accomodation_details_more')
                                                    ->get();
                                                    
        $toTal_weekly_revenue_flight            = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    ->whereBetween('add_manage_invoices.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                                    ->whereJsonContains('services',['flights_tab'])
                                                    // ->orWhereJsonContains('services',['1'])
                                                    ->select('no_of_pax_days','flights_details','flights_details_more','markup_details')
                                                    ->get();
        
        $toTal_weekly_revenue_visa              = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    ->whereBetween('add_manage_invoices.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                                    ->whereJsonContains('services',['visa_tab'])
                                                    // ->orWhereJsonContains('services',['1'])
                                                    ->select('visa_fee','no_of_pax_days','total_visa_markup_value','visa_Pax','more_visa_details','markup_details')
                                                    ->get();
        
        $toTal_weekly_revenue_transportation    = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    ->whereBetween('add_manage_invoices.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                                    ->whereJsonContains('services',['transportation_tab'])
                                                    // ->orWhereJsonContains('services',['1'])
                                                    ->select('no_of_pax_days','transportation_details','transportation_details_more','markup_details')
                                                    ->get();

        $toTal_weekly_revenue_Invoice              = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    ->whereBetween('add_manage_invoices.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                                    ->sum('total_sale_price_all_Services');
        
        $tour_booking   = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                            ->where('tours_bookings.customer_id',$request->customer_id)->where('cart_details.pakage_type','tour')
                            ->whereBetween('cart_details.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->get();
        if(count($tour_booking) > 0){
            foreach($tour_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $grand_profit   = 0;
                $total_cost_price_bookings  = 0;
                $toTal_weekly_revenue_Package = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_adult_total_cost;
                    $toTal_weekly_revenue_Package += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_adult_total_cost;
                    $toTal_weekly_revenue_Package += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_adult_total_cost;
                    $toTal_weekly_revenue_Package += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_adult_total_cost;
                    $toTal_weekly_revenue_Package += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_child_total_cost;
                    $toTal_weekly_revenue_Package += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_child_total_cost;
                    $toTal_weekly_revenue_Package += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_child_total_cost;
                    $toTal_weekly_revenue_Package += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_child_total_cost;
                    $toTal_weekly_revenue_Package += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_infant_total_cost;
                    $toTal_weekly_revenue_Package += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_infant_total_cost;
                    $toTal_weekly_revenue_Package += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_infant_total_cost;
                    $toTal_weekly_revenue_Package += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_infant_total_cost;
                    $toTal_weekly_revenue_Package += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $toTal_weekly_revenue_Package = 0;
        }
                                                    
        $tour_booking   = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                            ->where('tours_bookings.customer_id',$request->customer_id)->where('cart_details.pakage_type','activity')
                            ->whereBetween('cart_details.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->get();
        if(count($tour_booking) > 0){
            foreach($tour_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $grand_profit   = 0;
                $total_cost_price_bookings  = 0;
                $toTal_weekly_revenue_Activity = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_adult_total_cost;
                    $toTal_weekly_revenue_Activity += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_adult_total_cost;
                    $toTal_weekly_revenue_Activity += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_adult_total_cost;
                    $toTal_weekly_revenue_Activity += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_adult_total_cost;
                    $toTal_weekly_revenue_Activity += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_child_total_cost;
                    $toTal_weekly_revenue_Activity += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_child_total_cost;
                    $toTal_weekly_revenue_Activity += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_child_total_cost;
                    $toTal_weekly_revenue_Activity += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_child_total_cost;
                    $toTal_weekly_revenue_Activity += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_infant_total_cost;
                    $toTal_weekly_revenue_Activity += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_infant_total_cost;
                    $toTal_weekly_revenue_Activity += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_infant_total_cost;
                    $toTal_weekly_revenue_Activity += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_infant_total_cost;
                    $toTal_weekly_revenue_Activity += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $toTal_weekly_revenue_Activity = 0;
        }
        // End
        
        // Monthly Revenue
        $first_day_of_this_month    = date('Y-m-d',strtotime(date('Y-m-01')));
        $last_day_of_this_month     = date('Y-m-d',strtotime(date('Y-m-t')));
        
        $toTal_monthly_revenue_Accomodation      = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    ->whereBetween('add_manage_invoices.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                    ->whereJsonContains('services',['accomodation_tab'])
                                                    // ->orWhereJsonContains('services',['1'])
                                                    ->select('accomodation_details','accomodation_details_more')
                                                    ->get();
                                                
        $toTal_monthly_revenue_flight            = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    ->whereBetween('add_manage_invoices.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                    ->whereJsonContains('services',['flights_tab'])
                                                    // ->orWhereJsonContains('services',['1'])
                                                    ->select('no_of_pax_days','flights_details','flights_details_more','markup_details')
                                                    ->get();
        
        $toTal_monthly_revenue_visa              = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    ->whereBetween('add_manage_invoices.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                    ->whereJsonContains('services',['visa_tab'])
                                                    // ->orWhereJsonContains('services',['1'])
                                                    ->select('visa_fee','no_of_pax_days','total_visa_markup_value','visa_Pax','more_visa_details','markup_details')
                                                    ->get();
        
        $toTal_monthly_revenue_transportation    = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    ->whereBetween('add_manage_invoices.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                    ->whereJsonContains('services',['transportation_tab'])
                                                    // ->orWhereJsonContains('services',['1'])
                                                    ->select('no_of_pax_days','transportation_details','transportation_details_more','markup_details')
                                                    ->get();
        
        $toTal_monthly_revenue_Invoice          = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    ->whereBetween('add_manage_invoices.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                    ->sum('total_sale_price_all_Services');                                                    
        
        // $toTal_monthly_revenue_Package           = DB::table('tours_bookings')
        //                                             ->join('cart_details','tours_bookings.id','cart_details.booking_id')
        //                                             ->where('tours_bookings.customer_id',$request->customer_id)
        //                                             ->where('cart_details.pakage_type','tour')
        //                                             ->whereBetween('cart_details.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
        //                                             ->sum('cart_details.price');
        
        $tour_booking   =  DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('tours_bookings.customer_id',$request->customer_id)
                            ->where('cart_details.pakage_type','tour')
                            ->whereBetween('cart_details.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();
                            
        if(count($tour_booking) > 0){
            foreach($tour_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $grand_profit   = 0;
                $total_cost_price_bookings  = 0;
                $toTal_monthly_revenue_Package = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_adult_total_cost;
                    $toTal_monthly_revenue_Package += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_adult_total_cost;
                    $toTal_monthly_revenue_Package += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_adult_total_cost;
                    $toTal_monthly_revenue_Package += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_adult_total_cost;
                    $toTal_monthly_revenue_Package += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_child_total_cost;
                    $toTal_monthly_revenue_Package += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_child_total_cost;
                    $toTal_monthly_revenue_Package += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_child_total_cost;
                    $toTal_monthly_revenue_Package += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_child_total_cost;
                    $toTal_monthly_revenue_Package += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_infant_total_cost;
                    $toTal_monthly_revenue_Package += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_infant_total_cost;
                    $toTal_monthly_revenue_Package += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_infant_total_cost;
                    $toTal_monthly_revenue_Package += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_infant_total_cost;
                    $toTal_monthly_revenue_Package += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $toTal_monthly_revenue_Package = 0;
        }                                    
        // $toTal_monthly_revenue_Activity          = DB::table('tours_bookings')
        //                                             ->join('cart_details','tours_bookings.id','cart_details.booking_id')
        //                                             ->where('tours_bookings.customer_id',$request->customer_id)
        //                                             ->where('cart_details.pakage_type','activity')
        //                                             ->whereBetween('cart_details.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
        //                                             ->sum('cart_details.price');
                                                    
        $tour_booking   =  DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('tours_bookings.customer_id',$request->customer_id)
                            ->where('cart_details.pakage_type','activity')
                            ->whereBetween('cart_details.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->get();
                            
        if(count($tour_booking) > 0){
            foreach($tour_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $grand_profit   = 0;
                $total_cost_price_bookings  = 0;
                $toTal_monthly_revenue_Activity = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_adult_total_cost;
                    $toTal_monthly_revenue_Activity += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_adult_total_cost;
                    $toTal_monthly_revenue_Activity += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_adult_total_cost;
                    $toTal_monthly_revenue_Activity += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_adult_total_cost;
                    $toTal_monthly_revenue_Activity += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_child_total_cost;
                    $toTal_monthly_revenue_Activity += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_child_total_cost;
                    $toTal_monthly_revenue_Activity += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_child_total_cost;
                    $toTal_monthly_revenue_Activity += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_child_total_cost;
                    $toTal_monthly_revenue_Activity += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_infant_total_cost;
                    $toTal_monthly_revenue_Activity += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_infant_total_cost;
                    $toTal_monthly_revenue_Activity += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_infant_total_cost;
                    $toTal_monthly_revenue_Activity += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_infant_total_cost;
                    $toTal_monthly_revenue_Activity += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $toTal_monthly_revenue_Activity = 0;
        }
        // End
        
        // Yearly Revenue
        $Syear = date('Y') . '-01-01';
        $Eyear = date('Y') . '-12-31';
        $toTal_yearly_revenue_Accomodation      = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    // ->whereBetween('add_manage_invoices.created_at',[$Syear,$Eyear])
                                                    ->whereYear('add_manage_invoices.created_at', now()->subYear()->year)
                                                    ->whereJsonContains('services',['accomodation_tab'])
                                                    // ->orWhereJsonContains('services',['1'])
                                                    ->select('accomodation_details','accomodation_details_more')
                                                    ->get();         
                                                    
        $toTal_yearly_revenue_flight            = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    // ->whereBetween('add_manage_invoices.created_at',[$Syear,$Eyear])
                                                    ->whereYear('add_manage_invoices.created_at', now()->subYear()->year)
                                                    ->whereJsonContains('services',['flights_tab'])
                                                    // ->orWhereJsonContains('services',['1'])
                                                    ->select('no_of_pax_days','flights_details','flights_details_more','markup_details')
                                                    ->get();
        
        $toTal_yearly_revenue_visa              = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    // ->whereBetween('add_manage_invoices.created_at',[$Syear,$Eyear])
                                                    ->whereYear('add_manage_invoices.created_at', now()->subYear()->year)
                                                    ->whereJsonContains('services',['visa_tab'])
                                                    // ->orWhereJsonContains('services',['1'])
                                                    ->select('visa_fee','no_of_pax_days','total_visa_markup_value','visa_Pax','more_visa_details','markup_details')
                                                    ->get();
        
        $toTal_yearly_revenue_transportation    = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    // ->whereBetween('add_manage_invoices.created_at',[$Syear,$Eyear])
                                                    ->whereYear('add_manage_invoices.created_at', now()->subYear()->year)
                                                    ->whereJsonContains('services',['transportation_tab'])
                                                    // ->orWhereJsonContains('services',['1'])
                                                    ->select('no_of_pax_days','transportation_details','transportation_details_more','markup_details')
                                                    ->get();
        
        $toTal_yearly_revenue_Invoice           = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                    ->whereYear('add_manage_invoices.created_at', now()->subYear()->year)
                                                    ->sum('total_sale_price_all_Services');                                                    
        
        // $toTal_yearly_revenue_Package           = DB::table('tours_bookings')
        //                                             ->join('cart_details','tours_bookings.id','cart_details.booking_id')
        //                                             ->where('tours_bookings.customer_id',$request->customer_id)
        //                                             ->where('cart_details.pakage_type','tour')
        //                                             // ->whereBetween('cart_details.created_at',[$Syear,$Eyear])
        //                                             ->whereYear('cart_details.created_at', now()->subYear()->year)
        //                                             ->sum('cart_details.price');
        
        $tour_booking   =  DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('tours_bookings.customer_id',$request->customer_id)
                            ->where('cart_details.pakage_type','tour')->whereYear('cart_details.created_at', now()->subYear()->year)->get();
                            
        if(count($tour_booking) > 0){
            foreach($tour_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $grand_profit   = 0;
                $total_cost_price_bookings  = 0;
                $toTal_yearly_revenue_Package = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_adult_total_cost;
                    $toTal_yearly_revenue_Package += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_adult_total_cost;
                    $toTal_yearly_revenue_Package += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_adult_total_cost;
                    $toTal_yearly_revenue_Package += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_adult_total_cost;
                    $toTal_yearly_revenue_Package += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_child_total_cost;
                    $toTal_yearly_revenue_Package += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_child_total_cost;
                    $toTal_yearly_revenue_Package += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_child_total_cost;
                    $toTal_yearly_revenue_Package += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_child_total_cost;
                    $toTal_yearly_revenue_Package += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_infant_total_cost;
                    $toTal_yearly_revenue_Package += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_infant_total_cost;
                    $toTal_yearly_revenue_Package += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_infant_total_cost;
                    $toTal_yearly_revenue_Package += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_infant_total_cost;
                    $toTal_yearly_revenue_Package += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $toTal_yearly_revenue_Package = 0;
        }
                                                    
        // $toTal_yearly_revenue_Activity          = DB::table('tours_bookings')
        //                                             ->join('cart_details','tours_bookings.id','cart_details.booking_id')
        //                                             ->where('tours_bookings.customer_id',$request->customer_id)
        //                                             ->where('cart_details.pakage_type','activity')
        //                                             // ->whereBetween('cart_details.created_at',[$Syear,$Eyear])
        //                                             ->whereYear('cart_details.created_at', now()->subYear()->year)
        //                                             ->sum('cart_details.price');
                                                    
        $tour_booking   =  DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('tours_bookings.customer_id',$request->customer_id)
                            ->where('cart_details.pakage_type','activity')->whereYear('cart_details.created_at', now()->subYear()->year)->get();
                            
        if(count($tour_booking) > 0){
            foreach($tour_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $grand_profit   = 0;
                $total_cost_price_bookings  = 0;
                $toTal_yearly_revenue_Activity = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_adult_total_cost;
                    $toTal_yearly_revenue_Activity += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_adult_total_cost;
                    $toTal_yearly_revenue_Activity += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_adult_total_cost;
                    $toTal_yearly_revenue_Activity += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_adult_total_cost;
                    $toTal_yearly_revenue_Activity += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_child_total_cost;
                    $toTal_yearly_revenue_Activity += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_child_total_cost;
                    $toTal_yearly_revenue_Activity += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_child_total_cost;
                    $toTal_yearly_revenue_Activity += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_child_total_cost;
                    $toTal_yearly_revenue_Activity += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $total_cost_price_bookings += $double_infant_total_cost;
                    $toTal_yearly_revenue_Activity += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $total_cost_price_bookings += $triple_infant_total_cost;
                    $toTal_yearly_revenue_Activity += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $total_cost_price_bookings += $quad_infant_total_cost;
                    $toTal_yearly_revenue_Activity += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $total_cost_price_bookings += $without_acc_infant_total_cost;
                    $toTal_yearly_revenue_Activity += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $toTal_yearly_revenue_Activity = 0;
        }
                            
        // dd($toTal_yearly_revenue_Package);
        // End
        
        // Weekly Cost Price
        $weekly_total_cost_price_Invoice    = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                // ->whereBetween('add_manage_invoices.created_at',[$current_week['first_day'],$current_week['last_day']])
                                                ->whereBetween('add_manage_invoices.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                                ->sum('total_cost_price_all_Services');
        
        $tour_booking                       = DB::table('tours_bookings')
                                                ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                                ->where('tours_bookings.customer_id',$request->customer_id)
                                                // ->whereBetween('cart_details.created_at',[$current_week['first_day'],$current_week['last_day']])
                                                ->whereBetween('cart_details.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                                ->get();
        if(count($tour_booking) > 0){
            foreach($tour_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $grand_profit   = 0;
                $weekly_total_cost_price_bookings = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $weekly_total_cost_price_bookings += $double_adult_total_cost;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $weekly_total_cost_price_bookings += $triple_adult_total_cost;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $weekly_total_cost_price_bookings += $quad_adult_total_cost;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $weekly_total_cost_price_bookings += $without_acc_adult_total_cost;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $weekly_total_cost_price_bookings += $double_child_total_cost;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $weekly_total_cost_price_bookings += $triple_child_total_cost;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $weekly_total_cost_price_bookings += $quad_child_total_cost;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $weekly_total_cost_price_bookings += $without_acc_child_total_cost;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $weekly_total_cost_price_bookings += $double_infant_total_cost;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $weekly_total_cost_price_bookings += $triple_infant_total_cost;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $weekly_total_cost_price_bookings += $quad_infant_total_cost;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $weekly_total_cost_price_bookings += $without_acc_infant_total_cost;
                }
            }
        }else{
            $weekly_total_cost_price_bookings = 0;
        }
        // End
        
        // Monthly Cost Price
        $monthly_total_cost_price_Invoice   = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                // ->whereBetween('add_manage_invoices.created_at',[$first_day_of_this_month,$last_day_of_this_month])
                                                ->whereBetween('add_manage_invoices.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->sum('total_cost_price_all_Services');
        
        $tour_booking                       = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                                ->where('tours_bookings.customer_id',$request->customer_id)
                                                // ->whereBetween('cart_details.created_at',[$first_day_of_this_month,$last_day_of_this_month])
                                                ->whereBetween('cart_details.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                                                ->get();
        if(count($tour_booking) > 0){
            foreach($tour_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $grand_profit   = 0;
                $monthly_total_cost_price_bookings  = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $monthly_total_cost_price_bookings += $double_adult_total_cost;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $monthly_total_cost_price_bookings += $triple_adult_total_cost;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $monthly_total_cost_price_bookings += $quad_adult_total_cost;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $monthly_total_cost_price_bookings += $without_acc_adult_total_cost;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $monthly_total_cost_price_bookings += $double_child_total_cost;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $monthly_total_cost_price_bookings += $triple_child_total_cost;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $monthly_total_cost_price_bookings += $quad_child_total_cost;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $monthly_total_cost_price_bookings += $without_acc_child_total_cost;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $monthly_total_cost_price_bookings += $double_infant_total_cost;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $monthly_total_cost_price_bookings += $triple_infant_total_cost;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $monthly_total_cost_price_bookings += $quad_infant_total_cost;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $monthly_total_cost_price_bookings += $without_acc_infant_total_cost;
                }
            }
        }else{
            $monthly_total_cost_price_bookings = 0;
        }
        // End
        
        // Yearly Cost Price
        $yearly_total_cost_price_Invoice   = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                // ->whereBetween('add_manage_invoices.created_at',[$Syear,$Eyear])
                                                ->whereYear('add_manage_invoices.created_at', now()->subYear()->year)
                                                ->sum('total_cost_price_all_Services');
        
        $tour_booking                       = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                                ->where('tours_bookings.customer_id',$request->customer_id)
                                                // ->whereBetween('cart_details.created_at',[$Syear,$Eyear])
                                                ->whereYear('cart_details.created_at', now()->subYear()->year)
                                                ->get();
        if(count($tour_booking) > 0){
            foreach($tour_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                $grand_profit   = 0;
                $yearly_total_cost_price_bookings  = 0;
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $yearly_total_cost_price_bookings += $double_adult_total_cost;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $yearly_total_cost_price_bookings += $triple_adult_total_cost;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $yearly_total_cost_price_bookings += $quad_adult_total_cost;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $yearly_total_cost_price_bookings += $without_acc_adult_total_cost;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $yearly_total_cost_price_bookings += $double_child_total_cost;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $yearly_total_cost_price_bookings += $triple_child_total_cost;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $yearly_total_cost_price_bookings += $quad_child_total_cost;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $yearly_total_cost_price_bookings += $without_acc_child_total_cost;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $grand_profit += $double_profit;
                    $yearly_total_cost_price_bookings += $double_infant_total_cost;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $grand_profit += $triple_profit;
                    $yearly_total_cost_price_bookings += $triple_infant_total_cost;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $grand_profit += $quad_profit;
                    $yearly_total_cost_price_bookings += $quad_infant_total_cost;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $grand_profit += $without_acc_profit;
                    $yearly_total_cost_price_bookings += $without_acc_infant_total_cost;
                }
            }
        }else{
            $yearly_total_cost_price_bookings = 0;
        }
        // End
        
        // Agent & Suppliers
        $total_Agents               = DB::table('Agents_detail')->where('Agents_detail.customer_id',$request->customer_id)->count();
        $hotel_total_Suppliers      = DB::table('rooms_Invoice_Supplier')->where('rooms_Invoice_Supplier.customer_id',$request->customer_id)->count();
        $Flights_total_Suppliers    = DB::table('supplier')->where('supplier.customer_id',$request->customer_id)->count();
        
        $visa_sale_price      = DB::table('add_manage_invoices')
                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                ->whereJsonContains('services',['visa_tab'])
                                ->sum('total_sale_price_all_Services');
                                
        $visa_cost_price      = DB::table('add_manage_invoices')
                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                ->whereJsonContains('services',['visa_tab'])
                                ->sum('total_cost_price_all_Services');
                                
        
        $new_visa_Profit = $visa_sale_price - $visa_cost_price;
        
        // dd($new_visa_Profit);
        
        // Notification
        // $all_Notifications = DB::table('alhijaz_Notofication')->where('alhijaz_Notofication.notification_status','1')->get();
        
        return response()->json([
            // 'all_Notifications'         => $all_Notifications,
            
            'new_visa_Profit'           => $new_visa_Profit,
            
            'total_Agents'              => $total_Agents,
            'hotel_total_Suppliers'     => $hotel_total_Suppliers,
            'Flights_total_Suppliers'   => $Flights_total_Suppliers,
            
            // Total Revenue/Sale
            'total_Revenue_Invoice'         => $total_Revenue_Invoice,
            'total_Revenue_PandABooking'    => $total_Revenue_PandABooking,
            'travellanda_HotelBooking'      => $travellanda_HotelBooking,
            'hotelbeds_HotelBooking'        => $hotelbeds_HotelBooking,
            'tbo_HotelBooking'              => $tbo_HotelBooking,
            'ratehawk_HotelBooking'         => $ratehawk_HotelBooking,
            'hotels_HotelBooking'           => $hotels_HotelBooking,
            
            // Total Cost
            'total_cost_price_Invoice'      => $total_cost_price_Invoice,
            'total_cost_price_bookings'     => $total_cost_price_bookings,
            
            // Total Expense Outstandings
            'total_expense_Out_Invoice'         => $total_expense_Out_Invoice,
            'total_expense_Out_PandAbookings'   => $total_expense_Out_PandAbookings,
            'total_expense_Out_hotelbookings'   => $total_expense_Out_hotelbookings,
            
            // Total Income
            'total_Income_Invoice'         => $total_Income_Invoice,
            'total_Income_PandAbookings'   => $total_Income_PandAbookings,
            'total_Income_hotelbookings'   => $total_Income_hotelbookings,
            
            // Total Income Outstandings
            'total_Income_Out_Invoice'         => $total_Income_Out_Invoice,
            'total_Income_Out_PandAbookings'   => $total_Income_Out_PandAbookings,
            'total_Income_Out_hotelbookings'   => $total_Income_Out_hotelbookings,
            
            // Separate Revenue
            'separate_Revenue_accomodation'     => $separate_Revenue_accomodation,
            'separate_Revenue_flight'           => $separate_Revenue_flight,
            'separate_Revenue_visa'             => $separate_Revenue_visa,
            'separate_Revenue_transportation'   => $separate_Revenue_transportation,
            'separate_package_Revenue'          => $separate_package_Revenue,
            'separate_activity_Revenue'         => $separate_activity_Revenue,
            
            // Separate Cost Price
            'separate_package_cost_price'  => $separate_package_cost_price,
            'separate_activity_cost_price' => $separate_activity_cost_price,
            
            // Weekly Revenue
            'toTal_weekly_revenue_Accomodation'     => $toTal_weekly_revenue_Accomodation,
            'toTal_weekly_revenue_flight'           => $toTal_weekly_revenue_flight,
            'toTal_weekly_revenue_visa'             => $toTal_weekly_revenue_visa,
            'toTal_weekly_revenue_transportation'   => $toTal_weekly_revenue_transportation,
            'toTal_weekly_revenue_Invoice'          => $toTal_weekly_revenue_Invoice,
            'toTal_weekly_revenue_Package'          => $toTal_weekly_revenue_Package,
            'toTal_weekly_revenue_Activity'         => $toTal_weekly_revenue_Activity,
            
            // Monthly Revenue
            'toTal_monthly_revenue_Accomodation'     => $toTal_monthly_revenue_Accomodation,
            'toTal_monthly_revenue_flight'           => $toTal_monthly_revenue_flight,
            'toTal_monthly_revenue_visa'             => $toTal_monthly_revenue_visa,
            'toTal_monthly_revenue_transportation'   => $toTal_monthly_revenue_transportation,
            'toTal_monthly_revenue_Invoice'          => $toTal_monthly_revenue_Invoice,
            'toTal_monthly_revenue_Package'          => $toTal_monthly_revenue_Package,
            'toTal_monthly_revenue_Activity'         => $toTal_monthly_revenue_Activity,
            
            // Yearly Revenue
            'toTal_yearly_revenue_Accomodation'     => $toTal_yearly_revenue_Accomodation,
            'toTal_yearly_revenue_flight'           => $toTal_yearly_revenue_flight,
            'toTal_yearly_revenue_visa'             => $toTal_yearly_revenue_visa,
            'toTal_yearly_revenue_transportation'   => $toTal_yearly_revenue_transportation,
            'toTal_yearly_revenue_Invoice'          => $toTal_yearly_revenue_Invoice,
            'toTal_yearly_revenue_Package'          => $toTal_yearly_revenue_Package,
            'toTal_yearly_revenue_Activity'         => $toTal_yearly_revenue_Activity,
            
            // Weekly Cost
            'weekly_total_cost_price_Invoice'   => $weekly_total_cost_price_Invoice,
            'weekly_total_cost_price_bookings'  => $weekly_total_cost_price_bookings,
            
            // Monthly Cost
            'monthly_total_cost_price_Invoice'  => $monthly_total_cost_price_Invoice,
            'monthly_total_cost_price_bookings' => $monthly_total_cost_price_bookings,
            
            // Yearly Cost
            'yearly_total_cost_price_Invoice'   => $yearly_total_cost_price_Invoice,
            'yearly_total_cost_price_bookings'  => $yearly_total_cost_price_bookings,

            'package_month'             => $package_month,
            'package_month1'            => $package_month1,
            'package_weeks'             => $package_weeks,
            'package_weeks1'            => $package_weeks1,
            'tours'                     => $tours,
            'new_activites'             => $new_activites,
            'latest_packages'           => $latest_packages,
            'data1'                     => $data1,
            'data2'                     => $data2,
            'data3'                     => $data3,
            'packages_tour'             => $packages_tour,
            'no_of_pax_days'            => $no_of_pax_days,
            'booked_tour'               => $booked_tour,
            'booked_tourA'              => $booked_tourA,
            'booked_tourC'              => $booked_tourC,
            'toTal'                     => $toTal,
            'toTal_week'                => $toTal_week,
            'recieved'                  => $recieved,
            'toTal_today_earn'          => $toTal_today_earn,
            'activities_count'          => $activities_count,
            'activities_no_of_pax_days' => $activities_no_of_pax_days,
            'booked_activities'         => $booked_activities,
            'booked_activitiesA'        => $booked_activitiesA,
            'booked_activitiesC'        => $booked_activitiesC,
            'toTal_activities'          => $toTal_activities,
            'toTal_activities_week'     => $toTal_activities_week,
            'recieved_activities'       => $recieved_activities,
        ]);
    }
    
    public function alhijaz_Notification_function(){
        $all_Notifications       = DB::table('alhijaz_Notofication')->where('alhijaz_Notofication.notification_status','1')->orderBy('created_at', 'asc')->get();
        return response()->json([
            'all_Notifications'         => $all_Notifications,
        ]);
    }
  
    public function getCurrentWeek(){
        $monday         = strtotime("last monday");
        $monday         = date('w', $monday)==date('w') ? $monday+(7*86400) : $monday;
        $sunday         = strtotime(date("Y-m-d",$monday)." +6 days");
        $this_week_sd   = date("Y-m-d",$monday);
        $this_week_ed   = date("Y-m-d",$sunday);
        return $data    = ['first_day' => $this_week_sd, 'last_day' => $this_week_ed];
    }
    
    public function getMonthsName($month){
        switch($month){
                    case 1:
                     return 'Jan';
                    break;
                    case 2:
                     return 'Feb';
                    break;
                    case 3:
                     return 'Mar';
                    break;
                    case 4:
                     return 'April';
                    break;
                    case 5:
                     return 'May';
                    break;
                    case 6:
                     return 'Jun';
                    break;
                    case 7:
                 return  'July';
                    break;
                    case 8:
                 return  'Aug';
                    break;
                     case 9:
                 return  'Sep';
                    break;
                     case 10:
                 return  'Oct';
                    break;
                     case 11:
                     return 'Nov';
                    break;
                     case 12:
                 return  'Dec';
                    break;
                    
                }
    } 
    
    public function getWeeksName($week){
        switch($week){
                    case 1:
                     return 'Mon';
                    break;
                    case 2:
                     return 'Tue';
                    break;
                    case 3:
                     return 'Wed';
                    break;
                    case 4:
                     return 'Thu';
                    break;
                    case 5:
                     return 'Fri';
                    break;
                    case 6:
                     return 'Sat';
                    break;
                    case 7:
                 return  'Sun';
                    break;
                    
                    
                }
    } 

    public function submit_categories_api(Request $request)
    {
        $categories=DB::table('categories')->insert([
            'title'=>$request->title,
            'slug'=>$request->slug,
            'image'=>$request->image,
            'description'=>$request->description,
            'placement'=>$request->placement,
            'customer_id'=>$request->customer_id,
            'gallery_images'=>$request->gallery_images,
         ]);
          return response()->json(['message'=>'success']);    
    }
    
    public function view_categories_api(Request $request)
    {
      $customer_id=$request->customer_id;
      // print_r($customer_id);die();
    $categories=DB::table('categories')->where('customer_id',$customer_id)->get();

    return response()->json(['message'=>'success','categories'=>$categories]);
  }
  
    public function view_attributes_activites_api(Request $request)
    {
      $customer_id=$request->customer_id;
      // print_r($customer_id);die();
    $facilities=DB::table('activities_attributes')->where('customer_id',$customer_id)->get();

    return response()->json(['message'=>'success','facilities'=>$facilities]);
  }
  
    public function delete_categories_api(Request $request)
    {
    $id=$request->id;
   
    $categories=DB::table('categories')->where('id',$id)->delete();
          return redirect()->back()->with('message','Categories updated Successful!');
    
    
  }

    public function submit_attributes_api(Request $request)
    {

        $attributes=DB::table('attributes')->insert([
            'title'=>$request->title,
            'customer_id'=>$request->customer_id,
        ]);
        return response()->json(['message'=>'success']);
    }

    public function view_attributes_api(Request $request)
    {
      $customer_id=$request->customer_id;
      // print_r($customer_id);die();
    $attributes=DB::table('attributes')->where('customer_id',$customer_id)->get();

    return response()->json(['message'=>'success','attributes'=>$attributes]);
  }
    
    
    public function save_Package(Request $request){
        $generate_id=rand(0,9999999);
        $tour = new Tour();
        $tour->generate_id=$generate_id;
        $tour->customer_id=$request->customer_id;
        $tour->title=$request->title;
        $tour->no_of_pax_days=$request->no_of_pax_days;
        $tour->starts_rating=$request->starts_rating;
        $tour->currency_symbol=$request->currency_symbol;
        $tour->content=$request->content;
        $tour->start_date=$request->start_date;
        $tour->end_date=$request->end_date;
        $tour->time_duration=$request->time_duration;
        $tour->categories=$request->tour_categories;
        $tour->tour_feature=$request->tour_feature;
        $tour->defalut_state=$request->defalut_state;
        $tour->tour_featured_image=$request->tour_featured_image;
        $tour->tour_banner_image=$request->tour_banner_image;
        $tour->tour_publish=$request->tour_publish;
        $tour->tour_author=$request->tour_author;
        $tour->save();
        $lastTourId = $tour->id;
        $tour_batchs=DB::table('tour_batchs')->insert([
            'generate_id'=>$generate_id,
            'customer_id'=>$request->customer_id,
            'title'=>$request->title,
            'content'=>$request->content,
            'categories'=>$request->tour_categories,
            'starts_rating'=>$request->starts_rating,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'time_duration'=>$request->time_duration,
            'currency_symbol'=>$request->currency_symbol,
            'tour_publish'=>$request->tour_publish,
            'tour_author'=>$request->tour_author,
            'tour_feature'=>$request->tour_feature,
            'defalut_state'=>$request->defalut_state,
            'tour_featured_image'=>$request->tour_featured_image,
            'tour_banner_image'=>$request->tour_banner_image,
            'gallery_images'=>$request->gallery_images,
            'external_packages'=>$request->external_packages,
            'tour_id'=>$lastTourId,
        ]);
        return response()->json(['message'=>'success','tour'=>$tour,'id'=>$lastTourId]);
    }
    
    public function save_Accomodation(Request $request,$id){
        // print_r($request);die();
        $id=$request->id;
        $tour=Tour::find($id);
        if($tour)
        {
            $tour->accomodation_details=$request->accomodation_details;
            $tour->accomodation_details_more=$request->more_accomodation_details;
            $tour->update();
            return response()->Json(['message'=>'Tour Accomodation Saved Successful!']);
        }
        else
        {
            return response()->Json(['message'=>'Error!']);
        }
    }
  
    // submit_Activities_New
    public function submit_Activities_New(Request $request)
    {
        $generate_id=rand(0,9999999);
        $activities = new Active();
        $activities->generate_id=$generate_id;
        $activities->customer_id=$request->customer_id;
        $activities->title=$request->title;
        $activities->external_packages=$request->external_packages;
        $activities->content=$request->content;
        $activities->categories=$request->tour_categories;
        $activities->tour_attributes=$request->tour_attributes;
        $activities->no_of_pax_days=$request->no_of_pax_days;
        $activities->destination_details=$request->destination_details;
        $activities->destination_details_more=$request->destination_details_more;
        $activities->flights_details=$request->flights_details;
        $activities->flights_details_more=$request->more_flights_details;
        $activities->accomodation_details=$request->accomodation_details;
        $activities->transportation_details=$request->transportation_details;
        $activities->transportation_details_more=$request->transportation_details_more;
        $activities->visa_type=$request->visa_type;
        $activities->visa_rules_regulations=$request->visa_rules_regulations;
        $activities->visa_fee=$request->visa_fee;
        $activities->visa_image=$request->visa_image;
        $activities->quad_grand_total_amount=$request->quad_grand_total_amount;
        $activities->triple_grand_total_amount=$request->triple_grand_total_amount;
        $activities->double_grand_total_amount=$request->double_grand_total_amount;
        $activities->quad_cost_price=$request->quad_cost_price;
        $activities->triple_cost_price=$request->triple_cost_price;
        $activities->double_cost_price=$request->double_cost_price;
        $activities->all_markup_type=$request->all_markup_type;
        $activities->all_markup_add=$request->all_markup_add;
        $activities->gallery_images=$request->gallery_images;
        $activities->start_date=$request->start_date;
        $activities->end_date=$request->end_date;
        $activities->time_duration=$request->time_duration;
        $activities->tour_location=$request->tour_location;
        $activities->whats_included=$request->whats_included;
        $activities->whats_excluded=$request->whats_excluded;
        $activities->currency_symbol=$request->currency_symbol;
        $activities->tour_publish=$request->tour_publish;
        $activities->tour_author=$request->tour_author;
        $activities->tour_feature=$request->tour_feature;
        $activities->defalut_state=$request->defalut_state;
        $activities->payment_gateways=$request->payment_gateways;
        $activities->payment_modes=$request->payment_modes;
        $activities->markup_details=$request->markup_details;
        $activities->more_markup_details=$request->more_markup_details;
        $activities->tour_featured_image=$request->tour_featured_image;
        $activities->tour_banner_image=$request->tour_banner_image;
        $activities->Itinerary_details=$request->Itinerary_details;
        $activities->tour_itinerary_details_1=$request->tour_itinerary_details_1;
        $activities->tour_extra_price=$request->tour_extra_price;
        $activities->tour_extra_price_1=$request->tour_extra_price_1;
        $activities->tour_faq=$request->tour_faq;
        $activities->tour_faq_1=$request->tour_faq_1;
        $activities->save();
        $lastTourId = $activities->id;
        $active_batchs=DB::table('active_batchs')->insert([
            'generate_id'=>$generate_id,
            'customer_id'=>$request->customer_id,
            'title'=>$request->title,
            'content'=>$request->content,
            'categories'=>$request->tour_categories,
            'tour_attributes'=>$request->tour_attributes,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'time_duration'=>$request->time_duration,
            'tour_location'=>$request->tour_location,
            'whats_included'=>$request->whats_included,
            'whats_excluded'=>$request->whats_excluded,
            'currency_symbol'=>$request->currency_symbol,
            'tour_publish'=>$request->tour_publish,
            'tour_author'=>$request->tour_author,
            'tour_feature'=>$request->tour_feature,
            'defalut_state'=>$request->defalut_state,
            'tour_featured_image'=>$request->tour_featured_image,
            'tour_banner_image'=>$request->tour_banner_image,
            'Itinerary_details'=>$request->Itinerary_details,
            'tour_itinerary_details_1'=>$request->tour_itinerary_details_1,
            'tour_extra_price'=>$request->tour_extra_price,
            'tour_extra_price_1'=>$request->tour_extra_price_1,
            'tour_faq'=>$request->tour_faq,
            'tour_faq_1'=>$request->tour_faq_1,
            'external_packages'=>$request->external_packages,
            'markup_details'=>$request->markup_details,
            'more_markup_details'=>$request->more_markup_details,
            'no_of_pax_days'=>$request->no_of_pax_days,
            'destination_details'=>$request->destination_details,
            'destination_details_more'=>$request->destination_details_more,
            'flights_details'=>$request->flights_details,
            'flights_details_more'=>$request->more_flights_details,
            'accomodation_details'=>$request->accomodation_details,
            'transportation_details'=>$request->transportation_details,
            'transportation_details_more'=>$request->transportation_details_more,
            'visa_fee'=>$request->visa_fee,
            'visa_type'=>$request->visa_type,
            'visa_image'=>$request->visa_image,
            'visa_rules_regulations'=>$request->visa_rules_regulations,
            'quad_grand_total_amount'=>$request->quad_grand_total_amount,
            'triple_grand_total_amount'=>$request->triple_grand_total_amount,
            'double_grand_total_amount'=>$request->double_grand_total_amount,
            'quad_cost_price'=>$request->quad_cost_price,
            'triple_cost_price'=>$request->triple_cost_price,
            'double_cost_price'=>$request->double_cost_price,
            'all_markup_type'=>$request->all_markup_type,
            'all_markup_add'=>$request->all_markup_add,
            'gallery_images'=>$request->gallery_images,
            'payment_gateways'=>$request->payment_gateways,
            'payment_modes'=>$request->payment_modes,
            'tour_id'=>$lastTourId,
        ]);
        return response()->json(['message'=>'success','activities'=>$activities]);
    }
  
    public function edit_activities(Request $request)
    {   
        $id=$request->id;
        $customer_id=$request->customer_id;
        $tours=Active::find($id); 
        // return response()->json(['tours'=>$tours,'message'=>'Here are tours']);
        
        $customer_id=$request->customer_id;
        $categories=DB::table('activities_categories')->where('customer_id',$customer_id)->get();
        $attributes=DB::table('activities_attributes')->where('customer_id',$customer_id)->get();
        $customer=DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries = country::all();
        $all_countries_currency = country::all();
        $bir_airports=DB::table('bir_airports')->get();
         
        $payment_gateways=DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes=DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        
        $currency_Symbol = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        return response()->json(['message'=>'success','tours'=>$tours,'currency_Symbol'=>$currency_Symbol,'payment_modes'=>$payment_modes,'payment_gateways'=>$payment_gateways,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports]);
    }
  
    public function submit_edit_activities(Request $request)
    {   
        $id=$request->id;
        $generate_id=rand(0,9999999);
        $activities = Active::find($id);
        // dd($activities);
        if($activities)
        {
            $activities->generate_id=$generate_id;
            $activities->customer_id=$request->customer_id;
            $activities->title=$request->title;
            $activities->external_packages=$request->external_packages;
            $activities->content=$request->content;
            $activities->categories=$request->tour_categories;
            $activities->tour_attributes=$request->tour_attributes;
            $activities->no_of_pax_days=$request->no_of_pax_days;
            $activities->destination_details=$request->destination_details;
            $activities->destination_details_more=$request->destination_details_more;
            $activities->flights_details=$request->flights_details;
            $activities->flights_details_more=$request->more_flights_details;
            $activities->accomodation_details=$request->accomodation_details;
            $activities->transportation_details=$request->transportation_details;
            $activities->transportation_details_more=$request->transportation_details_more;
            $activities->visa_type=$request->visa_type;
            $activities->visa_rules_regulations=$request->visa_rules_regulations;
            $activities->visa_fee=$request->visa_fee;
            $activities->visa_image=$request->visa_image;
            $activities->quad_grand_total_amount=$request->quad_grand_total_amount;
            $activities->triple_grand_total_amount=$request->triple_grand_total_amount;
            $activities->double_grand_total_amount=$request->double_grand_total_amount;
            $activities->quad_cost_price=$request->quad_cost_price;
            $activities->triple_cost_price=$request->triple_cost_price;
            $activities->double_cost_price=$request->double_cost_price;
            $activities->all_markup_type=$request->all_markup_type;
            $activities->all_markup_add=$request->all_markup_add;
            $activities->gallery_images=$request->gallery_images;
            $activities->start_date=$request->start_date;
            $activities->end_date=$request->end_date;
            $activities->time_duration=$request->time_duration;
            $activities->tour_location=$request->tour_location;
            $activities->whats_included=$request->whats_included;
            $activities->whats_excluded=$request->whats_excluded;
            $activities->currency_symbol=$request->currency_symbol;
            $activities->tour_publish=$request->tour_publish;
            $activities->tour_author=$request->tour_author;
            $activities->tour_feature=$request->tour_feature;
            $activities->defalut_state=$request->defalut_state;
            $activities->payment_gateways=$request->payment_gateways;
            $activities->payment_modes=$request->payment_modes;
            $activities->markup_details=$request->markup_details;
            $activities->more_markup_details=$request->more_markup_details;
            $activities->tour_featured_image=$request->tour_featured_image;
            $activities->tour_banner_image=$request->tour_banner_image;
            $activities->Itinerary_details=$request->Itinerary_details;
            $activities->tour_itinerary_details_1=$request->tour_itinerary_details_1;
            $activities->tour_extra_price=$request->tour_extra_price;
            $activities->tour_extra_price_1=$request->tour_extra_price_1;
            $activities->tour_faq=$request->tour_faq;
            $activities->tour_faq_1=$request->tour_faq_1;
            $activities->update();
            return response()->json(['Tour'=>$activities,'Success'=>'Tour Updated Successful!']);
        }
        else{
            return response()->json(['Tour'=>$activities,'Error'=>'Tour Not Updated!']);
        }
    }
  
    public function delete_activities(Request $request)
    {
        $id=$request->id;
        $tours=Active::find($id);
        $tours->delete();
        return redirect()->back()->with('message','Activities deleted Successful!');
    }

    public function get_tour_list_api_with_token(Request $request){
   //return $request;
//   print_r($request->currentURL);
//   die();
        if(isset($request->type) && $request->type == 'activity'){ 
            $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
            $customer_id = $userData->id;
            $transfer_detail = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
            $activities=DB::table('new_activites')->where('customer_id',$customer_id)
                        ->select('id','title','currency_symbol','duration','location','activity_content','tour_attributes','min_people','max_people','Availibilty','sale_price','child_sale_price',
                        'featured_image','banner_image','activity_date','starts_rating','payment_getway')->orderBy('created_at', 'desc')->limit(6)->get();
            return response()->json(['message'=>'success','activity'=>$activities,'transfer_detail'=>$transfer_detail]);
        }
        // $userData = CustomerSubcription::where('Auth_key',$request->token)->where('webiste_Address',$request->currentURL)
        // ->orwhere('website_address_ar',$request->currentURL)
        // ->first();
         $userData = CustomerSubcription::where('Auth_key',$request->token)
        ->first();
 //print_r($userData);die();
        $today_date = date('Y-m-d');
        $customer_id = $userData->id;
        // dd($customer_id);
        $transfer_detail = DB::table('tranfer_destination')->get();
        $tours  =   DB::table('tours')
                        ->join('tours_2','tours.id','tours_2.tour_id')
                        ->where('tours.customer_id',$customer_id)
                        ->where('tours.start_date','>=',$today_date)
                        ->select('tours.id','tours.title','tours_2.quad_grand_total_amount','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image',
                            'tours.tour_banner_image','tours.time_duration','tours.content','tours.no_of_pax_days','tours.currency_symbol','tours.tour_location','tours.starts_rating',
                            'tours.whats_included','tours.start_date','tours.end_date','tours.gallery_images')
                        ->orderBy('tours.created_at', 'desc')->limit(6)->get();
                        // dd($tours);
                        $countries=DB::table('countries')->get();
        return response()->json(['message'=>'success','tours'=>$tours,'transfer_detail'=>$transfer_detail,'countries'=>$countries]);
    }
  
  public function get_tour_for_carsole(Request $request){
      $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

        $today_date = date('Y-m-d');
        $customer_id = $userData->id;
        $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
                ->where('tours.customer_id',$customer_id)
                ->where('tours.start_date','>=',$today_date)
                ->select('tours.id','tours.title','tours_2.quad_grand_total_amount','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration',
                'tours..content','tours..no_of_pax_days','tours..currency_symbol','tours.tour_location','tours.starts_rating','tours.whats_included','tours.start_date','tours.end_date')
                ->orderBy('tours.created_at', 'desc')->limit(6)->get();
    // print_r($customer_id);
    //  $travellanda_get_cities=DB::table('travellanda_get_cities')->get();
    return response()->json(['message'=>'success','tours'=>$tours]);
  }
  
   public function get_category_wi_id(Request $request)
    {
    $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

    $customer_id = $userData->id;
    $category=DB::table($request->table)->where('id',$request->id)->first();
    // print_r($customer_id);die();
    return response()->json(['message'=>'success','category'=>$category]);
  }
  
   public function get_facilities_names(Request $request)
    {
        // print_r($request->all());
    $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

    $customer_id = $userData->id;
    $facilities_names = [];
    $facilities_ids = json_decode($request->faclities);
    
    foreach($facilities_ids as $facility_res){
         $facility_data=DB::table($request->table)->where('id',$facility_res)->select('title')->first();
         
         array_push($facilities_names,$facility_data->title);
    }
//   print_r($facilities_names);
    // print_r($customer_id);die();
    return response()->json(['message'=>'success','facilities_names'=>$facilities_names]);
  }

    // Usama 6-14-2022
    public function get_tour_iternery(Request $request)
    {
      // print_r($request->all());
      $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
  
      $customer_id = $userData->id;
      $tours=DB::table('tour_batchs')->where('generate_id',$request->generate_id)->select('id','tour_id','generate_id','title','Itinerary_details','tour_itinerary_details_1','currency_symbol','start_date','end_date','time_duration','tour_location','starts_rating')->get();
      // print_r($customer_id);die();
      return response()->json(['message'=>'success','tours'=>$tours]);
    }

    // Usama 6-20-2022
    public function get_all_tour_list_api_with_token(Request $request)
    {
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
    
        $customer_id = $userData->id;
        if($request->cat_id == null){
            $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('customer_id',$customer_id)
            ->select('tours.id','tours.title','tours_2.quad_grand_total_amount','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image',
            'tours.tour_banner_image','tours.time_duration','tours.content','tours.no_of_pax_days','tours.currency_symbol','tours.categories','tours.starts_rating','tours.start_date',
            'tours.end_date','tours.destination_details','tours.destination_details_more','tours.tour_location')
            ->orderBy('tours_2.quad_grand_total_amount', 'asc')->paginate();
        }else{
            $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('customer_id',$customer_id)->where('categories',$request->cat_id)
            ->select('tours.id','tours.title','tours_2.quad_grand_total_amount','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image',
            'tours.tour_banner_image','tours.time_duration','tours.content','tours.no_of_pax_days','tours.currency_symbol','tours.categories','tours.starts_rating','tours.start_date',
            'tours.end_date','tours.destination_details','tours.destination_details_more','tours.tour_location')
            ->orderBy('tours_2.quad_grand_total_amount', 'asc')->paginate();
        }
        
        
        $all_active_providers = DB::table('3rd_party_commissions')
                                ->where('third_party_id',$customer_id)
                                ->where('status','approve')
                                ->get();
                                
        $providers_tours = [];
        foreach($all_active_providers as $active_pro_res){
             $other_providers_tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
             ->where('customer_id',$active_pro_res->customer_id)
             ->where('others_providers_show', 'true')
            ->select('tours.id','tours.title','tours_2.quad_grand_total_amount','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours.tour_featured_image',
            'tours.tour_banner_image','tours.time_duration','tours.content','tours.no_of_pax_days','tours.currency_symbol','tours.categories','tours.starts_rating','tours.start_date',
            'tours.end_date','tours.destination_details','tours.destination_details_more','tours.tour_location')
            ->orderBy('tours.created_at', 'asc')->paginate();
            
            
            $provider_data = CustomerSubcription::where('id',$active_pro_res->customer_id)->select('id','name','lname','webiste_Address')->first();
            $provider_arr = [$other_providers_tours,$provider_data];
            array_push($providers_tours,$provider_arr);
        }
        
        // print_r($providers_tours);die();
        return response()->json(['message'=>'success','tours'=>$tours,'providers_tours'=>$providers_tours,'customer_id'=>$customer_id]);
      }
  
   public function get_all_activities(Request $request)
    {
    $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

    $customer_id = $userData->id;
     $today_date = date('Y-m-d');
    $activities=DB::table('new_activites')
            //   ->where('end_date','>=',$today_date)
              ->where('customer_id','=',$customer_id)
    ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','tour_attributes')->orderBy('created_at', 'asc')->paginate(5);
    
    
         
 
    return response()->json(['message'=>'success','activities'=>$activities]);
  }

    public function search_pakages(Request $request)
    {
    $start_date = date("Y-m-d", strtotime($request->start_date));
    // $end_date = date("Y-m-d", strtotime($request->end_date));
    $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

    $customer_id = $userData->id;
    // echo "id is $customer_id";
    $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
              ->where('categories','=',$request->category)
              ->where('start_date','>=',$start_date)
              ->where('customer_id','=',$customer_id)
    ->select('tours.id','tours.title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tours.tour_featured_image','tours.tour_banner_image','tours.time_duration',
    'tours.content','tours.tour_location','tours.start_date','tours.end_date','tours.no_of_pax_days','tours.currency_symbol','tours.starts_rating','tours_2.flights_details','tours_2.flights_details_more','tours.accomodation_details',
    'tours.accomodation_details_more','tours.destination_details','tours.destination_details_more')->orderBy('tours.created_at', 'desc')->get();
    // print_r($customer_id);die();
    
    // print_r($tours);
    // if(count($tours > 0)){
    //     $tours = $tours;
    // }else{
    //     $tours=DB::table('tours')
    //           ->where('categories','=',$request->category)
    //           ->where('customer_id','=',$customer_id)
    // ->select('id','title','quad_grand_total_amount','triple_grand_total_amount','double_grand_total_amount','tour_featured_image','tour_banner_image','time_duration','content','tour_location','start_date','end_date','no_of_pax_days','currency_symbol','starts_rating','flights_details','flights_details_more')->orderBy('created_at', 'desc')->get();
    // // print_r($customer_id);die();
    // }
    return response()->json(['message'=>'success','tours'=>$tours]);
  }
  
   public function map_data(Request $request)
    {

    // print_r($request->all());
    
    $cities_data = [];
    $request_cites = json_decode($request->request_cites);
    
    foreach($request_cites as $city_res){
        // echo "City is $city_res";
          $single_city_data=DB::table('cities')
              ->where('id',$city_res)
             ->select('latitude','longitude','name','id')->get();
        
         array_push($cities_data,$single_city_data);
    }
  
//   print_r($cities_data);
    return response()->json(['message'=>'success','city_data'=>$cities_data]);
  }
  
    public function cites_suggestions(Request $request)
  {
    $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

    $customer_id = $userData->id;
    // echo "id is $customer_id";
    $tours=DB::table('new_activites')
              ->where('location','LIKE','%'.''.$request->location.'%')
              ->where('customer_id','=',$customer_id)
    ->select('location')->limit(20)->get();
    // print_r($customer_id);die();
    return response()->json(['message'=>'success','tours'=>$tours]);
  }
  
  
  
  public function search_activities(Request $request)
  {
    $start_date = date("Y-m-d", strtotime($request->start_date));
    // $end_date = date("Y-m-d", strtotime($request->end_date));
    $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

    $customer_id = $userData->id;
    // echo "id is $customer_id";
    
    $tours=DB::table('new_activites')
              ->where('location','LIKE','%'.''.$request->location.'%')
            //   ->where('start_date','<=',$start_date)
              ->where('end_date','>=',$start_date)
              ->where('customer_id','=',$customer_id)
    ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','tour_attributes','start_date')->orderBy('created_at', 'desc')->get();
    
     $tomorrowDate = date('Y-m-d', strtotime('-1 days'));
         
    
    $relatedtours =DB::table('new_activites')
              ->where('location','LIKE','%'.''.$request->location.'%')
              ->where('start_date','>=',$tomorrowDate)
              ->where('customer_id','=',$customer_id)
    ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','start_date')->orderBy('created_at', 'desc')->get();
    // print_r($customer_id);die();
    return response()->json(['message'=>'success','tours'=>$tours,'relatedTours'=>$relatedtours]);
  }
  
   public function get_activity_for_carsole(Request $request){
      $today_date = date("Y-m-d");
    $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

    $customer_id = $userData->id;
    // echo "id is $customer_id";
    
    $activities=DB::table('new_activites')
              ->where('end_date','>=',$today_date)
              ->where('customer_id','=',$customer_id)
    ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','tour_attributes')->orderBy('created_at', 'desc')->limit(10)->get();
    
    
         
 
    return response()->json(['message'=>'success','activities'=>$activities]);
  }
  
  
  public function get_country_activities(Request $request)
  {
    $today_date = date("Y-m-d");
    $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();

    $customer_id = $userData->id;
    // echo "id is $customer_id";
    
    $activities=DB::table('new_activites')
              ->where('end_date','>=',$today_date)
              ->where('country',$request->country)
              ->where('customer_id','=',$customer_id)
    ->select('id','title','sale_price','child_sale_price','duration','featured_image','banner_image','location','activity_content','activity_date','currency_symbol','min_people','max_people','Availibilty','activity_content','starts_rating','tour_attributes')->orderBy('created_at', 'desc')->get();
    
    
         
 
    return response()->json(['message'=>'success','activities'=>$activities]);
  }

  public function get_tour_details(Request $request)
  {
    //   dd($request);
    // print_r($request->all());
    $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();


    if($request->type == 'tour'){
        // echo "the type is $request->type in tour";
        $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$request->id)->select('tours_2.*','tours.*','tours_2.id as tour2id')->first();
        // dd($tours);
        // echo $request->customer_id;
        $Agents_detail  = DB::table('Agents_detail')->where('customer_id',$userData->id)->get();
        $customer_detail  = DB::table('booking_customers')->where('customer_id',$userData->id)->get();
        $mange_currencies  = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
        // print_r($Agents_detail);
        // die;
        $all_visa_types  = DB::table('visa_types')->where('customer_id',$userData->id)->get();
    }
    
     if($request->type == 'activity'){
        //  echo "the type is $request->type in activity";
        $tours=DB::table('new_activites')->where('id',$request->id)->first();
        $Agents_detail = "";
        $all_visa_types = "";
        $mange_currencies = "";
    }
    
    // print_r($customer_id);die();
    return response()->json(['message'=>'success','tours'=>$tours,'agents'=>$Agents_detail,'visa_types'=>$all_visa_types,'mange_currencies'=>$mange_currencies,'customer_details'=>$customer_detail]);
  }
  // 6-15-2022
  
  
    public function fetch_payment_data1(Request $request)
    {
        $view_booking_payment_recieve = DB::table('view_booking_payment_recieve')->where('view_booking_payment_recieve.tourId',$request->id)->get();
        return response()->json(['message'=>'success','view_booking_payment_recieve'=>$view_booking_payment_recieve]);
    }

  public function get_tour_for_cart(Request $request)
  {
    //   echo "this is ";
    //   print_r($request->all());
    $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
    if($userData){
      if($userData->status == 1){
          if($request->pakage_type == 'tour'){
               $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')
                    ->where('tours.id',$request->id)
                    ->select('tours.id','tours.generate_id','tours.title','tours.flight_id','tours.tour_banner_image','tours_2.tour_extra_price','tours_2.tour_extra_price_1',
                 'tours_2.quad_grand_total_amount','tours_2.triple_grand_total_amount','tours_2.double_grand_total_amount','tours_2.without_acc_sale_price','tours.no_of_pax_days','tours.currency_symbol','tours.tour_location','tours.start_date',
                 'tours.end_date','tours.time_duration','tours.starts_rating','tours.cancellation_policy','tours.checkout_message','tours_2.quad_cost_price','tours_2.triple_cost_price',
                 'tours_2.double_cost_price')->first();
                 
                //  print_r($tours);
                //  die;
            }
          
           if($request->pakage_type == 'activity'){
                 $tours=DB::table('new_activites')->where('id',$request->id)->select('id','generate_id','title','featured_image','addtional_service_arr',
                 'sale_price','child_sale_price','currency_symbol','location','activity_date','duration','starts_rating','cancellation_policy','checkout_message')->first();
            }
    

       
        // print_r($customer_id);die();
        return response()->json(['message'=>'success','tours'=>$tours]);
      }else{
        return response()->json(['message'=>'failed']);

      }
    }else{
      return response()->json(['message'=>'failed']);

    }
    
  }
  
    public function get_tour_payment_mode(Request $request)
  {
    //  print_r($request->all());
    $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
    if($userData){
      if($userData->status == 1){
           if($request->type == 'tour'){
             $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.id',$request->tour_id)
             ->select('tours.id','tours.payment_gateways','tours.payment_modes','tours.payment_messag')->first();
          }else{
              $tours=DB::table('new_activites')->where('id',$request->tour_id)->select('id','payment_getway','payment_modes')->first();
          }
        
        // print_r($customer_id);die();
        return response()->json(['message'=>'success','tours'=>$tours]);
      }else{
        return response()->json(['message'=>'failed']);

      }
    }else{
      return response()->json(['message'=>'failed']);

    }
    
  }
  
  
  
  
 public function view_booking_with_token(Request $request){
     $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
          if($userData->status == 1){
            $tours=DB::table('tours_bookings')->where('parent_token',$request->token)->select('id','passenger_name','email','passenger_detail')->get();
            // print_r($customer_id);die();
            return response()->json(['message'=>'success','bookings'=>$tours]);
          }else{
            return response()->json(['message'=>'failed']);
    
          }
        }else{
          return response()->json(['message'=>'failed']);
    
        }
   }












//activities 

public function add_activities_api(Request $request)
  {



    $tour = new Active();
    // print_r($request->tour_location);die();
    $tour->customer_id=$request->customer_id;
      $tour->title=$request->title;
       $tour->external_packages=$request->external_packages;
      $tour->content=$request->content;
      $tour->categories=$request->tour_categories;
      $tour->tour_attributes=$request->tour_attributes;
      
       $tour->no_of_pax_days=$request->no_of_pax_days;
      $tour->destination_details=$request->destination_details;
      $tour->destination_details_more=$request->destination_details_more;
      $tour->flights_details=$request->flights_details;
      $tour->flights_details_more=$request->more_flights_details;
      $tour->accomodation_details=$request->accomodation_details;
      $tour->transportation_details=$request->transportation_details;
      $tour->transportation_details_more=$request->transportation_details_more;
      $tour->visa_type=$request->visa_type;
      $tour->visa_rules_regulations=$request->visa_rules_regulations;
      $tour->visa_fee=$request->visa_fee;
       $tour->visa_image=$request->visa_image;
      
      
      $tour->quad_grand_total_amount=$request->quad_grand_total_amount;
    $tour->triple_grand_total_amount=$request->triple_grand_total_amount;
    $tour->double_grand_total_amount=$request->double_grand_total_amount;
      
      
      
       $tour->quad_cost_price=$request->quad_cost_price;
    $tour->triple_cost_price=$request->triple_cost_price;
   $tour->double_cost_price=$request->double_cost_price;
     $tour->all_markup_type=$request->all_markup_type;
    $tour->all_markup_add=$request->all_markup_add;
    // $tour->all_quad_markup=$request->all_quad_markup;
    // $tour->all_triple_markup=$request->all_triple_markup;
    //  $tour->all_double_markup=$request->all_double_markup;
      
      
      
      
       //usama changes
    //   $tour->property_country=$request->property_country;
    //   $tour->property_city=$request->property_city;
    //   $tour->hotel_name=$request->hotel_name;
    //   $tour->hotel_rooms_type=$request->hotel_rooms_type;
    //   $tour->price_per_night=$request->price_per_night;
    //   $tour->total_price_per_night=$request->total_price_per_night;
      //
      
      
      

      $tour->start_date=$request->start_date;
      $tour->end_date=$request->end_date;
      $tour->time_duration=$request->time_duration;
    //   $tour->tour_min_people=$request->tour_min_people;
    //   $tour->tour_max_people=$request->tour_max_people;
      $tour->tour_location=$request->tour_location;
      $tour->whats_included=$request->whats_included;
      $tour->whats_excluded=$request->whats_excluded;
        $tour->currency_symbol=$request->currency_symbol;
    //   $tour->tour_pricing=$request->tour_pricing;
    //   $tour->tour_sale_price=$request->tour_sale_price;
      $tour->tour_publish=$request->tour_publish;
      $tour->tour_author=$request->tour_author;
      $tour->tour_feature=$request->tour_feature;
      $tour->defalut_state=$request->defalut_state;


  $tour->markup_details=$request->markup_details;
      $tour->more_markup_details=$request->more_markup_details;


      $tour->tour_featured_image=$request->tour_featured_image;
   
      $tour->tour_banner_image=$request->tour_banner_image;
      $tour->Itinerary_details=$request->Itinerary_details;
      $tour->tour_itinerary_details_1=$request->tour_itinerary_details_1;
$tour->tour_extra_price=$request->tour_extra_price;
$tour->tour_extra_price_1=$request->tour_extra_price_1;
$tour->tour_faq=$request->tour_faq;

$tour->tour_faq_1=$request->tour_faq_1;
 $tour->save();
 
//  print_r($tour);
      
return response()->json(['message'=>'success','tour'=>$tour]);
    
  }
  

    public function create_activities_api (Request $request)
    {
        $customer_id=$request->customer_id;
        $categories=DB::table('activities_categories')->where('customer_id',$customer_id)->get();
        $attributes=DB::table('activities_attributes')->where('customer_id',$customer_id)->get();
        $customer=DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $activities_cities=DB::table('activities_cities')->where('customer_id',$customer_id)->get();
        $all_countries = country::select('id','name')->get();
        $all_countries_currency = country::all();
       
         
        $payment_gateways=DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes=DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        
        $currency_Symbol = DB::table('customer_subcriptions')->where('id',$customer_id)->select('currency_symbol')->get();
        //  print_r($customer_id);die();
       return response()->json(['message'=>'success','currency_Symbol'=>$currency_Symbol,'payment_modes'=>$payment_modes,'payment_gateways'=>$payment_gateways,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'activities_cities'=>$activities_cities]);
       
    }
    
public function submit_activities_api(Request $request,$id)
  {
    $tour=Active::find($id);
    if($tour)
    {
      $tour->title=$request->title;
      $tour->content=$request->content;
      $categories=$request->categories;
      $categories=json_encode($categories);

      $tour->categories=$categories;

      $attributes=$request->attributes;

      // print_r($attributes);die();
      $attributes=json_encode($attributes);

      $tour->attributes=$attributes;


      $tour->start_date=$request->start_date;
      $tour->end_date=$request->end_date;
      $tour->time_duration=$request->time_duration;
      $tour->tour_min_people=$request->tour_min_people;
      $tour->tour_max_people=$request->tour_max_people;
      $tour->tour_location=$request->tour_location;
    //   $tour->tour_real_address=$request->tour_real_address;
    //   $tour->tour_pricing=$request->tour_pricing;
    //   $tour->tour_sale_price=$request->tour_sale_price;
      $tour->tour_publish=$request->tour_publish;
      $tour->tour_author=$request->tour_author;
      $tour->tour_feature=$request->tour_feature;
      $tour->defalut_state=$request->defalut_state;


      if($request->hasFile('tour_featured_image')){

        $file = $request->file('tour_featured_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_featured_image = $filename;
    }
    else{
        $tour_featured_image = '';
    }


      $tour->tour_featured_image=$tour_featured_image;
      if($request->hasFile('tour_banner_image')){

        $file = $request->file('tour_banner_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $tour_banner_image = $filename;
    }
    else{
        $tour_banner_image = '';
    }
      $tour->tour_banner_image=$tour_banner_image;



      $Itinerary_title=$request->Itinerary_title;
      $Itinerary_city=$request->Itinerary_city;
      $Itinerary_content=$request->Itinerary_content;

      if($request->hasFile('Itinerary_image')){

        $file = $request->file('Itinerary_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $Itinerary_image = $filename;
    }
    else{
        $Itinerary_image = '';
    }
      $Itinerary_image=$Itinerary_image;
      $Itinerary_details=array([
                        'Itinerary_title'=>$Itinerary_title,
                          'Itinerary_city'=>$Itinerary_city,
                          'Itinerary_content'=>$Itinerary_content,
                          'Itinerary_image'=>$Itinerary_image,

                          ]);

      $Itinerary_details=json_encode($Itinerary_details);

      $tour->Itinerary_details=$Itinerary_details;






      $more_Itinerary_title=$request->more_Itinerary_title;
      $more_Itinerary_city=$request->more_Itinerary_city;
      $more_Itinerary_content=$request->more_Itinerary_content;

      if($request->hasFile('more_Itinerary_image')){

        $file = $request->file('more_Itinerary_image');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/uploads/package_imgs/', $filename);
        $more_Itinerary_image = $filename;
    }
    else{
        $more_Itinerary_image = '';
    }
      $more_Itinerary_image=$more_Itinerary_image;
      $tour_itinerary_details_1=array([
                        'more_Itinerary_title'=>$more_Itinerary_title,
                          'more_Itinerary_city'=>$more_Itinerary_city,
                          'more_Itinerary_content'=>$more_Itinerary_content,
                          'more_Itinerary_image'=>$more_Itinerary_image,

                          ]);

      $tour_itinerary_details_1=json_encode($tour_itinerary_details_1);

      $tour->tour_itinerary_details_1=$tour_itinerary_details_1;










      $extra_price_name=$request->extra_price_name;
      $extra_price_price=$request->extra_price_price;
      $extra_price_type=$request->extra_price_type;
      $extra_price_person=$request->extra_price_person;
      $tour_extra_price=array([
        'extra_price_name'=>$extra_price_name,
          'extra_price_price'=>$extra_price_price,
          'extra_price_type'=>$extra_price_type,
          'extra_price_person'=>$extra_price_person,

          ]);

$tour_extra_price=json_encode($tour_extra_price);

$tour->tour_extra_price=$tour_extra_price;

$faq_title=$request->faq_title;
      $faq_content=$request->faq_content;
      $tour_extra_price=array([
        'faq_title'=>$faq_title,
          'faq_content'=>$faq_content,
          

          ]);

$tour_extra_price=json_encode($tour_extra_price);
$tour->tour_faq=$tour_extra_price;






$more_extra_price_title=$request->more_extra_price_title;
      $more_extra_price_price=$request->more_extra_price_price;
      $more_extra_price_type=$request->more_extra_price_type;
      $more_extra_price_person=$request->more_extra_price_person;
      $tour_extra_price_1=array([
        'more_extra_price_title'=>$more_extra_price_title,
          'more_extra_price_price'=>$more_extra_price_price,
          'more_extra_price_type'=>$more_extra_price_type,
          'more_extra_price_person'=>$more_extra_price_person,

          ]);

$tour_extra_price_1=json_encode($tour_extra_price_1);

$tour->tour_extra_price_1=$tour_extra_price_1;

$faq_title=$request->faq_title;
      $tour_faq=$request->faq_content;
      $tour_extra_price=array([
        'faq_title'=>$faq_title,
          'faq_content'=>$faq_content,
          

          ]);

$tour_faq=json_encode($tour_faq);
$tour->tour_extra_price=$tour_faq;





$more_faq_title=$request->more_faq_title;
      $more_faq_content=$request->more_faq_content;
      $tour_faq_1=array([
        'more_faq_content'=>$more_faq_content,
          'more_faq_content'=>$more_faq_content,
          

          ]);

$tour_faq_1=json_encode($tour_faq_1);
$tour->tour_faq_1=$tour_faq_1;







      $tour->update();


      return redirect('super_admin/view_tour')->with('message','Tour Updated Successful!');
    }
    else{
      return redirect('super_admin/view_tour')->with('message','Tour Updated Not Successful!');
    }
  }
public function edit_activities_api(Request $request)
  {
    $id=$request->id;
    $tours=Active::find($id);
    return response(['tours'=>$tours]);
    // $categories=DB::table('categories')->get();
    // $attributes=DB::table('Attributes')->get();
    // return view('template/frontend/userdashboard/pages/tour/edit_tour',compact('tours','categories','attributes'));  
  }
public function delete_activities_api(Request $request)
  {
    $id=$request->id;
    $tours=Active::find($id);
    $tours->delete();
    return redirect()->back()->with('message','Tour deleted Successful!');
  }

public function enable_activities_api(Request $request)
  {
    $id=$request->id;
    $tours=Active::find($id);
    if($tours)
    {
      $tours->tour_publish='0';
      $tours->update();
      return redirect()->back()->with('message','Tour Status Updated Successful!');
    }
    else{
      return redirect()->back()->with('message','Tour Status Not Updated Successful!');
    }
   
  }

public function disable_activities_api(Request $request)
  {
    $id=$request->id;
    // print_r($id);die();
    $tours=Active::find($id);
    print_r($tours);
    if($tours)
    {
      $tours->tour_publish='1';
      $tours->update();
      return redirect()->back();
    }
    else{
      return redirect()->back();
    }
  }

  public function get_activities_list_api(Request $request)
  {
    $customer_id=$request->customer_id;
    $activities=DB::table('actives')->where('customer_id',$customer_id)->get();
    $data1 = DB::table('cart_details')->get();
      return response()->json([
        'message'       => 'success',
        'activities'    => $activities,
        'data1'         => $data1,
      ]);
  }

  public function submit_categories_activites_api (Request $request)
  {
    $categories=DB::table('activities_categories')->insert([
      'title'=>$request->title,
      'slug'=>$request->slug,
      'image'=>$request->image,
      'placement'=>$request->placement,
      'description'=>$request->description,
      'customer_id'=>$request->customer_id,
          ]);

          return response()->json(['message'=>'success']);
          
        
  }
  public function view_categories_activites_api(Request $request)
  {
      $customer_id=$request->customer_id;
      // print_r($customer_id);die();
    $categories=DB::table('activities_categories')->where('customer_id',$customer_id)->get();

    return response()->json(['message'=>'success','categories'=>$categories]);
  }
  
    
  
  public function edit_categories_activites_api(Request $request)
  {
    $id=$request->id;
    // print_r($id);die();
    $categories=DB::table('activities_categories')->where('id',$id)->first();
    
    return response(['categories'=>$categories]);
  }
  
  public function submit_edit_categories_activites_api_request(Request $request)
  {

    $id=$request->id;
    
    $categories=DB::table('activities_categories')->where('id',$id)->update([
      'title'=>$request->title,
      'slug'=>$request->slug,
      'image'=>$request->image,
      'description'=>$request->description,
      
          ]);

          return response(['categories'=>$categories]);
         
    
    
  }
   public function delete_categories_activites_api(Request $request)
  {
    $id=$request->id;
   
    $categories=DB::table('activities_categories')->where('id',$id)->delete();
          return redirect()->back()->with('message','Categories updated Successful!');
    
    
  }
  
    //   Hotel Names
        public function view_Hotel_Names(Request $request)
        {
            $customer_id=$request->customer_id;
            $other_Hotel_Name=DB::table('hotel_Name')->where('customer_id',$customer_id)->get();
            return response()->json(['message'=>'success','other_Hotel_Name'=>$other_Hotel_Name]);
        }
        
        public function edit_Hotel_Names(Request $request)
        {
            $id=$request->id;
            $edit_other_Hotel_Name=DB::table('hotel_Name')->where('id',$id)->first();
            return response()->json([
                'edit_other_Hotel_Name'=>$edit_other_Hotel_Name,
            ]);
        }
  
        public function submit_edit_Hotel_Names(Request $request)
        {
            $id=$request->id;
            $update_other_Hotel_Name=DB::table('hotel_Name')->where('id',$id)->update([
              'other_Hotel_Name'=>$request->other_Hotel_Name,
            ]);
            return response(['update_other_Hotel_Name'=>$update_other_Hotel_Name]);
        }
        
        public function delete_Hotel_Names(Request $request)
        {
            $id=$request->id;
            $hotel_Name=DB::table('hotel_Name')->where('id',$id)->delete();
            return response()->json([
                    'message' => 'Hotel Name Deleted Successful!',
                ]);
            // return redirect()->back()->with('message','Hotel Name Deleted Successful!');
        }
    //  End Hotel Names
    
    // Locations
        // Pickup Locations
            public function view_pickup_locations(Request $request)
            {
                $customer_id=$request->customer_id;
                $other_pickup_location=DB::table('pickup_location_tb')->where('customer_id',$customer_id)->get();
                return response()->json(['message'=>'success','other_pickup_location'=>$other_pickup_location]);
            }
            
            public function edit_pickup_locations(Request $request)
            {
                $id=$request->id;
                $edit_other_pickup_location=DB::table('pickup_location_tb')->where('id',$id)->first();
                return response()->json([
                    'edit_other_pickup_location'=>$edit_other_pickup_location,
                ]);
            }
  
            public function submit_edit_pickup_locations(Request $request)
            {
                $id=$request->id;
                $update_other_pickup_location=DB::table('pickup_location_tb')->where('id',$id)->update([
                  'pickup_location'=>$request->pickup_location,
                ]);
                return response(['update_other_pickup_location'=>$update_other_pickup_location]);
            }
        
            public function delete_pickup_locations(Request $request)
            {
                $id=$request->id;
                $other_pickup_location=DB::table('pickup_location_tb')->where('id',$id)->delete();
                return response()->json([
                        'message' => 'Pickup Location Deleted Successful!',
                    ]);
            }
        // End Pickup Locations
        
        // Dropof Locations
            public function view_dropof_locations(Request $request)
            {
                $customer_id=$request->customer_id;
                $other_dropof_location=DB::table('dropof_location_tb')->where('customer_id',$customer_id)->get();
                return response()->json(['message'=>'success','other_dropof_location'=>$other_dropof_location]);
            }
            
            public function edit_dropof_locations(Request $request)
            {
                $id=$request->id;
                $edit_other_dropof_location=DB::table('dropof_location_tb')->where('id',$id)->first();
                return response()->json([
                    'edit_other_dropof_location'=>$edit_other_dropof_location,
                ]);
            }
  
            public function submit_edit_dropof_locations(Request $request)
            {
                $id=$request->id;
                $update_other_dropof_location=DB::table('dropof_location_tb')->where('id',$id)->update([
                  'dropof_location'=>$request->dropof_location,
                ]);
                return response(['$update_other_dropof_location'=>$update_other_dropof_location]);
            }
        
            public function delete_dropof_locations(Request $request)
            {
                $id=$request->id;
                $other_dropof_location=DB::table('dropof_location_tb')->where('id',$id)->delete();
                return response()->json([
                        'message' => 'Pickup Location Deleted Successful!',
                    ]);
            }
        // End Dropof Locations
    // End Locations

  public function submit_attributes_activities_api(Request $request)
  {

    $attributes=DB::table('activities_attributes')->insert([
'title'=>$request->title,
'customer_id'=>$request->customer_id,
    ]);
    return response()->json(['message'=>'success']);
  }
  public function view_attributes_activities_api(Request $request)
  {
      $customer_id=$request->customer_id;
      // print_r($customer_id);die();
    $attributes=DB::table('activities_attributes')->where('customer_id',$customer_id)->get();

    return response()->json(['message'=>'success','attributes'=>$attributes]);
  }
  public function delete_attributes_activities_api(Request $request)
  {
    $id=$request->id;
    $attributes=DB::table('activities_attributes')->where('id',$id)->delete();
          return response(['attributes'=>$attributes]);
    
    
  }


 public function edit_attributes_activities_api(Request $request)
  {
$id=$request->id;
    $attributes=DB::table('activities_attributes')->where('id',$id)->first();
    return response(['attributes'=>$attributes]);
    
    
  }
  
  public function submit_edit_attributes_activities_api(Request $request)
  {
    $id=$request->id;
    $attributes=DB::table('activities_attributes')->where('id',$id)->update([
      'title'=>$request->title,
          ]);
           return response(['attributes'=>$attributes]);
    
    
  }


  


//activities


  public function package_bookings(Request $request)
  {

    $token=$request->token;
    // $latest_packages = Tour::latest()->take(5)->where('customer_id',$request->customer_id)->get();
    $packages = DB::table('tours_bookings')
            ->join('customer_subcriptions','tours_bookings.parent_token','customer_subcriptions.Auth_key')
            ->join('cart_details','tours_bookings.id','cart_details.booking_id')
            ->where('tours_bookings.parent_token',$token)->get();
             return response()->json(['packages'=>$packages]);
  }
  public function activities_bookings(Request $request)
  {

    $token=$request->token;
    // $latest_packages = Tour::latest()->take(5)->where('customer_id',$request->customer_id)->get();
    $packages = DB::table('tours_bookings')
            ->join('customer_subcriptions','tours_bookings.parent_token','customer_subcriptions.Auth_key')
            ->join('cart_details','tours_bookings.id','cart_details.booking_id')
            ->where('tours_bookings.parent_token',$token)->get();
             return response()->json(['packages'=>$packages]);
  }
  public function package_departure_list(Request $request)
  {

// Given a date in string format 
$current_date = date('Y-m-d');
// print_r($current_date);
// Last date of current month.
$lastdatethismonth = date("Y-m-t", strtotime($current_date));

// print_r($lastdatethismonth);



  $token=$request->token;
    // $latest_packages = Tour::latest()->take(5)->where('customer_id',$request->customer_id)->get();
    $departure_list = DB::table('tours_bookings')
            ->join('customer_subcriptions','tours_bookings.parent_token','customer_subcriptions.Auth_key')
            ->join('cart_details','tours_bookings.id','cart_details.booking_id')
            ->join('tour_batchs','cart_details.generate_id','tour_batchs.generate_id')
            ->where('tours_bookings.parent_token',$token)->get();
          
             return response()->json(['departure_list'=>$departure_list]);
  }
  
  public function package_return_list(Request $request)
  {

// Given a date in string format 
$current_date = date('Y-m-d');
// print_r($current_date);
// Last date of current month.
$lastdatethismonth = date("Y-m-t", strtotime($current_date));

// print_r($lastdatethismonth);



  $token=$request->token;
    // $latest_packages = Tour::latest()->take(5)->where('customer_id',$request->customer_id)->get();
    $departure_list = DB::table('tours_bookings')
            ->join('customer_subcriptions','tours_bookings.parent_token','customer_subcriptions.Auth_key')
            ->join('cart_details','tours_bookings.id','cart_details.booking_id')
            ->join('tour_batchs','cart_details.generate_id','tour_batchs.generate_id')
            ->where('tours_bookings.parent_token',$token)->get();
          
             return response()->json(['departure_list'=>$departure_list]);
  }
  
  
  public function activities_departure_list(Request $request)
  {

// Given a date in string format 
$current_date = date('Y-m-d');
// print_r($current_date);
// Last date of current month.
$lastdatethismonth = date("Y-m-t", strtotime($current_date));

// print_r($lastdatethismonth);



  $token=$request->token;
    // $latest_packages = Tour::latest()->take(5)->where('customer_id',$request->customer_id)->get();
    $departure_list = DB::table('tours_bookings')
            ->join('customer_subcriptions','tours_bookings.parent_token','customer_subcriptions.Auth_key')
            ->join('cart_details','tours_bookings.id','cart_details.booking_id')
            ->join('tour_batchs','cart_details.generate_id','tour_batchs.generate_id')
            ->where('tours_bookings.parent_token',$token)->get();
          
             return response()->json(['departure_list'=>$departure_list]);
  }
 
  public function activities_return_list(Request $request)
  {

// Given a date in string format 
$current_date = date('Y-m-d');
// print_r($current_date);
// Last date of current month.
$lastdatethismonth = date("Y-m-t", strtotime($current_date));

// print_r($lastdatethismonth);


  $token=$request->token;
    // $latest_packages = Tour::latest()->take(5)->where('customer_id',$request->customer_id)->get();
    $departure_list = DB::table('tours_bookings')
            ->join('customer_subcriptions','tours_bookings.parent_token','customer_subcriptions.Auth_key')
            ->join('cart_details','tours_bookings.id','cart_details.booking_id')
            ->join('tour_batchs','cart_details.generate_id','tour_batchs.generate_id')
            ->where('tours_bookings.parent_token',$token)->get();
          
             return response()->json(['departure_list'=>$departure_list]);
  }






}
