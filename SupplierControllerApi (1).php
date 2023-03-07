<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Session;

class SupplierControllerApi extends Controller
{
    public function createsupplier(Request $request){
        return response()->json(['message'=>'success']);
    }
    
    public function addsupplier(Request $request){
        $categories=\DB::table('supplier')->insert([
            'token'                 => $request->token,
            'customer_id'           => $request->customer_id,
            'companyname'           => $request->companyname,
            'Companyaddress'        => $request->Companyaddress,
            'companyemail'          => $request->companyemail,
            'contactpersonname'     => $request->contactpersonname,
            'contactpersonemail'    => $request->contactpersonemail,
            'personcontactno'       => $request->personcontactno,
            'contactpersonemail'    => $request->contactpersonemail,
            'opening_balance'    => $request->opening_balance,
            'balance'    => $request->opening_balance,
            
        ]);
        return response()->json(['message'=>'success']);
    }
    
    public function fetchsupplier(Request $request){
       $customer_id = $request->customer_id;
        
      $suppliers=\DB::table('supplier')->where('customer_id',$customer_id)->get();
      
          return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]);
    }
    
     public function flight_supplier_ledger(Request $request){
        //  print_r($request->all());
        //  die;
       $customer_id = $request->customer_id;
      $suppliers_data = \DB::table('supplier')->where('id',$request->supplier_id)->first();
      $suppliers_ledger = \DB::table('flight_supplier_ledger')->where('supplier_id',$request->supplier_id)->get();
      
          return response()->json(['message'=>'success','supplier_data'=>$suppliers_data,'ledger_data'=>$suppliers_ledger]);
    }
    
    
    
    public function deletesupplier(Request $request){
       $deleted_id = $request->id;
    //   dd("admin delete");
        
      $suppliers=\DB::table('supplier')->where('id',$deleted_id)->delete();
    //   dd($suppliers);
    if($suppliers = 1){
     return response()->json(['message'=>'success']);   
    }else{
        return response()->json(['message'=>'error']);
    }
          
    }
    
    public function editsupplier(Request $request){
       $edited_id = $request->id;
        
      $suppliers=\DB::table('supplier')->where('id',$edited_id)->first();
          return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]);
    }
    public function updatesupplier(Request $request){
        // dd($request);
      
       $update_data= $request->id;
      $suppliers=\DB::table('supplier')->where('id',$update_data)->update([
          'companyname'=>$request->companyname ?? "",
          'Companyaddress'=>$request->Companyaddress ?? "",
          'companyemail'=>$request->companyemail ?? "",
          'contactpersonname'=>$request->contactpersonname ?? "",
          'contactpersonemail'=>$request->contactpersonemail ?? "",
          'personcontactno'=>$request->personcontactno ?? "",
          
          ]);
    if($suppliers = 1){
     return response()->json(['message'=>'success']);   
    }else{
        return response()->json(['message'=>'error']);
    }
         
    }
    
    
    public function fetchsuppliername(Request $request){
        // dd($request);
       $supplier_id = $request->supplier_id;
        
      $suppliers=\DB::table('supplier')->where('id',$supplier_id)->first();
    //   dd($suppliers);
          return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]);
    }
    public function fetchairline (Request $request){
        // dd($request);
    //   $supplier_id = $request->supplier_id;
        
      $airlines=\DB::table('airline_name_tb')->get();
      
          return response()->json(['message'=>'success','fetchedairline'=>$airlines]);
    }
    
    
    public function fetchallsupplierforseats(Request $req){
        $suppliers    = DB::table('supplier')->get();
        $new_arr      = [];
        foreach($suppliers as $key => $suppliersz){
            $type       = [];
            $all_rutes = DB::table('flight_rute')->where('dep_supplier',$suppliersz->id)->get();
            foreach($all_rutes as $all_rutesz){
                $rute_type_of_supplier = [
                   'multi_rute' => $all_rutesz->dep_flight_type,
                ];
                array_push($type,$rute_type_of_supplier);
            }
            $rute_type_of_supplier = [   
                'multi_rute_suplier'    => $suppliersz,
                'multi_rute'            => $type,
            ];
            array_push($new_arr,$rute_type_of_supplier);
        }
        return response()->json(['message'=>'success','fetchedsupplier' => $new_arr]); 
    }
    
    
    public function createseat(Request $request){
        // dd($request);
        
      $flight=\DB::table('Flight_sup_seats')->insert([
    'customer_id'=> $request->customer_id ?? "",
    'token'=>$request->token ?? "",
    'flight_type'=>$request->flight_type ?? "",
    'selected_flight_airline'=>$request->selected_flight_airline ?? "",
    'supplier'=>$request->supplier ?? "",
    'no_of_stays'=>$request->no_of_stays ?? "",
    'departure_airport_code'=>$request->departure_airport_code ?? "",
    'arrival_airport_code'=>$request->arrival_airport_code ?? "",
    'other_Airline_Name2'=>$request->other_Airline_Name2 ?? "",
    'departure_Flight_Type'=>$request->departure_Flight_Type ?? "",
    'departure_flight_number'=>$request->departure_flight_number ?? "",
    'departure_time'=>$request->departure_time ?? "",
    'arrival_time'=>$request->arrival_time ?? "",
    'total_Time'=>$request->total_Time ?? "",
      'more_departure_airport_code' => $request->more_departure_airport_code ?? "",
      'more_arrival_airport_code' => $request->more_arrival_airport_code ?? "",
      'more_other_Airline_Name2' => $request->more_other_Airline_Name2 ?? "",
      'more_departure_Flight_Type' => $request->more_departure_Flight_Type ?? "",
      'more_departure_flight_number' => $request->more_departure_flight_number ?? "",
      'more_departure_time' => $request->more_departure_time ?? "",
      'more_arrival_time' => $request->more_arrival_time ?? "",
      'more_total_Time' => $request->more_total_Time ?? "",
    
    'return_departure_airport_code'=>$request->return_departure_airport_code ?? "",
    'return_arrival_airport_code'=>$request->return_arrival_airport_code ?? "",
    'return_other_Airline_Name2'=>$request->return_other_Airline_Name2 ?? "",
    'return_departure_Flight_Type'=>$request->return_departure_Flight_Type ?? "",
    'return_departure_flight_number'=>$request->return_departure_flight_number ?? "",
    'return_departure_time'=>$request->return_departure_time ?? "",
    'return_arrival_time'=>$request->return_arrival_time ?? "",
    'return_total_Time'=>$request->return_total_Time ?? "",
    
      "return_more_departure_airport_code" => $request->return_more_departure_airport_code ?? "",
      "return_more_arrival_airport_code" => $request->return_more_arrival_airport_code ?? "",
      "return_more_other_Airline_Name2" => $request->return_more_other_Airline_Name2 ?? "",
      "return_more_departure_Flight_Type" => $request->return_more_departure_Flight_Type ?? "",
      "return_more_departure_flight_number" => $request->return_more_departure_flight_number ?? "",
      "return_more_departure_time" => $request->return_more_departure_time ?? "",
      "return_more_arrival_time" => $request->return_more_arrival_time ?? "",
      "return_more_total_Time" => $request->return_more_total_Time ?? "",
    
    'flights_per_person_price'=>$request->flights_per_person_price ?? "",
    'flights_number_of_seat'=>$request->flights_number_of_seat ?? "",
    'flights_per_seat_price'=>$request->flights_per_seat_price ?? "",
    'flights_per_child_price'=>$request->flights_per_child_price ?? "",
    'flights_per_infant_price'=>$request->flights_per_infant_price ?? "",
    'flight_total_price'=>$request->flights_total_price ?? "",
    'connected_flights_duration_details'=>$request->connected_flights_duration_details ?? "",
    'terms_and_conditions'=>$request->terms_and_conditions ?? "",
    
          ]);

 if($flight = 1){
     return response()->json(['message'=>'success']);   
    }else{
        return response()->json(['message'=>'error']);
    }
         
    }
    
    public function createseat1(Request $request){
       
    //   print_r($request->all());
    //   die;
       
        DB::beginTransaction();
                  
                     try {
                            $route_id = DB::table('flight_rute')->insertGetId([
                                'customer_id'               => $request->customer_id ?? "",
                                'token'                     => $request->token ?? "",
                                
                                'dep_supplier'              => $request->dep_supplier ?? "",
                                'dep_flight_type'           => $request->dep_flight_type ?? "",
                                'dep_airline'               => $request->dep_airline ?? "",
                                'dep_no_of_stay'            => $request->dep_no_of_stay ?? "",
                                'dep_object'                => $request->dep_object ?? "",
                                
                                'return_supplier'           => $request->return_supplier ?? "",
                                'return_flight_type'        => $request->return_flight_type ?? "",
                                'return_airline'            => $request->return_airline ?? "",
                                'return_no_of_stay'         => $request->return_no_of_stay ?? "",
                                'return_object'             => $request->return_object ?? "",
                                
                                'flights_per_person_price'  => $request->flights_per_person_price ?? "",
                                'flights_number_of_seat'    => $request->flights_number_of_seat ?? "",
                                'flights_per_seat_price'    => $request->flights_per_seat_price ?? "",
                                'flights_per_child_price'   => $request->flights_per_child_price ?? "",
                                'flights_per_infant_price'  => $request->flights_per_infant_price ?? "",
                                'flight_total_price'        => $request->flights_total_price ?? "",
                                
                                'connected_flights_duration_details'    => $request->connected_flights_duration_details ?? "",
                                'terms_and_conditions'                  => $request->terms_and_conditions ?? "",
                            ]);
                            
                            
                              $supplier_data = DB::table('supplier')->where('id',$request->dep_supplier)->first();
                              
                              $supplier_balance = $supplier_data->balance + $request->flights_total_price;
                              
                              DB::table('flight_supplier_ledger')->insert([
                                    'supplier_id'=>$supplier_data->id,
                                    'payment'=>$request->flights_total_price,
                                    'balance'=>$supplier_balance,
                                    'route_id'=>$route_id,
                                    'date'=>date('Y-m-d'),
                                    'customer_id'=>$request->customer_id,
                                    'total_seats'=>$request->flights_number_of_seat,
                                    'adult_price'=>$request->flights_per_person_price,
                                    'child_price'=>$request->flights_per_child_price,
                                    'infant_price'=>$request->flights_per_infant_price,
                                  ]);
                                  
                              DB::table('supplier')->where('id',$request->dep_supplier)->update(['balance'=>$supplier_balance]);
                              
                            //   print_r($supplier_data);
                              
                            //   die;
                              DB::commit();
                               return response()->json(['message'=>'success']);   
                                // echo $result;
                        } catch (Throwable $e) {

                            DB::rollback();
                            
                            echo $e;
                            return response()->json(['message'=>'error']);
                            // return response()->json(['message'=>'error','booking_id'=> '']);
                        }

    }
    
    public function fetchseat(Request $request){
        $customer_id = $request->customer_id;
        $tours_arr =[];
        $suppliers_flight=\DB::table('flight_rute')->get();
        foreach($suppliers_flight as $flight){
            $total_adults=0;
            $total_childs=0;
            $total_infants=0;
            $tours = DB::table('tours')->where('flight_id',$flight->id)->select('id')->get();
            foreach($tours as $tourf){
                $cart_details = DB::table('cart_details')->where('tour_id',$tourf->id)->get();
                foreach($cart_details as $cart_detailsz){
                    $cart_data = json_decode($cart_detailsz->cart_total_data);
                    $total_adults += $cart_data->double_adults + $cart_data->triple_adults + $cart_data->quad_adults + $cart_data->without_acc_adults;
                    $total_childs +=$cart_data->double_childs + $cart_data->triple_childs + $cart_data->quad_childs + $cart_data->children;
                    $total_infants +=$cart_data->double_infant + $cart_data->triple_infant + $cart_data->quad_infant + $cart_data->infants;
                }
            
            }
            $flightpaxcount = [
                'flight_id'=>$flight->id, 
                'totaladults'=>$total_adults, 
                'totalchilds'=>$total_childs,
                'totalinfants'=>$total_infants
            ];
            array_push($tours_arr,$flightpaxcount);
        }
        return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers_flight,'tours_arr'=>$tours_arr]);
    }
    
    public function deleteseat(Request $request){
       $deleted_id = $request->id;
    //   dd("admin delete");
        
      $suppliers=\DB::table('Flight_sup_seats')->where('id',$deleted_id)->delete();
    //   dd($suppliers);
    if($suppliers = 1){
     return response()->json(['message'=>'success']);   
    }else{
        return response()->json(['message'=>'error']);
    }
          
    }
    
    public function editseat(Request $request){
       $edited_id = $request->id;
        
      $suppliers=\DB::table('Flight_sup_seats')->where('id',$edited_id)->first();
          return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]);
    }
    
    public function supplier_wallet_trans(Request $request){
      
        
      $suppliers_trans=\DB::table('flight_supplier_wallet_trans')->where('supplier_id',$request->supplier_id)->get();
          return response()->json(['message'=>'success','suppliers_trans'=>$suppliers_trans]);
    }

    public function updateseat(Request $req){
        // dd($request);
      
      $update_data= $req->id;
      $suppliers=\DB::table('Flight_sup_seats')->where('id',$update_data)->update([
          
           
    'customer_id'=>$req->customer_id ?? "",
    'token'=>$req->token ?? "",
    'flight_type'=>$req->flight_type ?? "",
    'supplier'=>$req->supplier ?? "",
    'selected_flight_airline'=>$req->selected_flight_airline ?? "",
    'no_of_stays'=>$req->no_of_stays ?? "",
    'departure_airport_code'=>$req->departure_airport_code ?? "",
    'arrival_airport_code'=>$req->arrival_airport_code ?? "",
    'other_Airline_Name2'=>$req->other_Airline_Name2 ?? "",
    'departure_Flight_Type'=>$req->departure_Flight_Type ?? "",
    'departure_flight_number'=>$req->departure_flight_number ?? "",
    'departure_time'=>$req->departure_time ?? "",
    'arrival_time'=>$req->arrival_time ?? "",
    'total_Time'=>$req->total_Time ?? "",
    
     'more_departure_airport_code' => $req->more_departure_airport_code ?? "",
      'more_arrival_airport_code' => $req->more_arrival_airport_code ?? "",
      'more_other_Airline_Name2' => $req->more_other_Airline_Name2 ?? "",
      'more_departure_Flight_Type' => $req->more_departure_Flight_Type ?? "",
      'more_departure_flight_number' => $req->more_departure_flight_number ?? "",
      'more_departure_time' => $req->more_departure_time ?? "",
      'more_arrival_time' => $req->more_arrival_time ?? "",
      'more_total_Time' => $req->more_total_Time ?? "",
    
    'return_departure_airport_code'=>$req->return_departure_airport_code ?? "",
    'return_arrival_airport_code'=>$req->return_arrival_airport_code ?? "",
    'return_other_Airline_Name2'=>$req->return_other_Airline_Name2 ?? "",
    'return_departure_Flight_Type'=>$req->return_departure_Flight_Type ?? "",
    'return_departure_flight_number'=>$req->return_departure_flight_number ?? "",
    'return_departure_time'=>$req->return_departure_time ?? "",
    'return_arrival_time'=>$req->return_arrival_time ?? "",
    'return_total_Time'=>$req->return_total_Time ?? "",
    
     "return_more_departure_airport_code" => $req->return_more_departure_airport_code ?? "",
      "return_more_arrival_airport_code" => $req->return_more_arrival_airport_code ?? "",
      "return_more_other_Airline_Name2" => $req->return_more_other_Airline_Name2 ?? "",
      "return_more_departure_Flight_Type" => $req->return_more_departure_Flight_Type ?? "",
      "return_more_departure_flight_number" => $req->return_more_departure_flight_number ?? "",
      "return_more_departure_time" => $req->return_more_departure_time ?? "",
      "return_more_arrival_time" => $req->return_more_arrival_time ?? "",
      "return_more_total_Time" => $req->return_more_total_Time ?? "",

    'flights_per_person_price'=>$req->flights_per_person_price ?? "",
    'flights_number_of_seat'=>$req->flights_number_of_seat ?? "",
    'flights_per_seat_price'=>$req->flights_per_seat_price ?? "",
    'flights_per_child_price'=>$req->flights_per_child_price ?? "",
    'flights_per_infant_price'=>$req->flights_per_infant_price ?? "",
    'flight_total_price'=>$req->flights_total_price ?? "",
    'connected_flights_duration_details'=>$req->connected_flights_duration_details ?? "",
    'terms_and_conditions'=>$req->terms_and_conditions ?? "",
   
          ]);
    if($suppliers = 1){
     return response()->json(['message'=>'success']);   
    }else{
        return response()->json(['message'=>'error']);
    }
         
    }
    // payment
    public function save_flight_payment_recieved_and_remaining(Request $req){
    // dd("in admin");
    // print_r($req->all());
    // die;
 
  
    DB::beginTransaction();
                        try {
                            
                                  $flightid= $req->flightId;
                                  
                                  $payment_routine_array = [];
                                  
                                  $flight_data = \DB::table('Flight_sup_seats')->where('id',$flightid)->first();
                      
                                $total_amount = $flight_data->flight_total_price;
                                $paid_amount = $flight_data->flight_paid_amount;
                                $over_paid_amount = $flight_data->over_paid_amount;
                                
                                $total_paid_amount = $paid_amount + $req->amount_paid;
                                $total_over_paid = 0;
                                $over_paid_am = 0;
                                if($total_paid_amount > $total_amount){
                                    $over_paid_am = $total_paid_amount - $total_amount;
                                    $total_over_paid = $over_paid_amount + $over_paid_am;
                                    
                                    $total_paid_amount = $total_paid_amount - $over_paid_am;
                                }
                                
                                DB::table('Flight_sup_seats')->where('id',$flightid)->update([
                                    'flight_paid_amount' => $total_paid_amount,
                                    'over_paid_amount' => $total_over_paid,
                                ]);
                                
                                $supplier_data = DB::table('supplier')->where('id',$flight_data->supplier)->select('id','wallet_amount')->first();
                                $supplier_wallet_am = $supplier_data->wallet_amount + $over_paid_am;
                                DB::table('supplier')->where('id',$flight_data->supplier)->update(['wallet_amount'=>$supplier_wallet_am]);
                                
                                
                                if($over_paid_am != 0){
                                       DB::table('flight_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid_am,
                                                                                    'balance'=>$supplier_wallet_am,
                                                                                    'flight_id'=>$req->flightId,
                                                                                    'supplier_id'=>$flight_data->supplier,
                                                                                    'date'=>$req->date,
                                                                                     ]);
                                }
                             
                                
                                if($req->payment_method == 'Wallet'){
                                    $supplier_data = DB::table('supplier')->where('id',$flight_data->supplier)->select('id','wallet_amount')->first();
                                    $supplier_wallet_am = $supplier_data->wallet_amount - $req->amount_paid;
                                    DB::table('supplier')->where('id',$flight_data->supplier)->update(['wallet_amount'=>$supplier_wallet_am]);
                                    
                                    DB::table('flight_supplier_wallet_trans')->insert(['payment_am'=>$req->amount_paid,
                                                                                'balance'=>$supplier_wallet_am,
                                                                                'flight_id'=>$req->flightId,
                                                                                'supplier_id'=>$flight_data->supplier,
                                                                                'date'=>$req->date,
                                                                                 ]);
                                }
                                
                               
                                
                    //   dd($flight_data);
                      if($flight_data != null){
                          if($flight_data->amount_paidandremaining_of_flight){
                              $payment_routine = $flight_data->amount_paidandremaining_of_flight;
                              $payment_data = json_decode($payment_routine);
                            //   dd($payment_data);
                              
                              array_push($payment_data,$req->all());
                              $payment_routine_array = $payment_data;
                          }else{
                              array_push($payment_routine_array,$req->all());
                            //   $payment_routine_array = [$req->all()];
                          }
                      
                      }
                      
                      
                    //   dd($payment_routine_array);
                      
                      
                      
                      
                      $suppliers=\DB::table('Flight_sup_seats')->where('id',$flightid)->update([
                          
                    'amount_paidandremaining_of_flight'=>json_encode($payment_routine_array),
                    
                          ]);
          
                            DB::commit();
                            
                            return response()->json(['status'=>'success','message'=>'Balance is Updated Successfully']);
                        } catch (\Exception $e) {
                            DB::rollback();
                            echo $e;die;
                            return response()->json(['status'=>'error','message'=>'Something Went Wrong try Again']);
                            // something went wrong
                        }
                        
                        
    if($suppliers = 1){
     return response()->json(['message'=>'success']);   
    }else{
        return response()->json(['message'=>'error']);
    }
     
}
    
    public function get_suppliers_flights_detail(Request $req){
        // dd($req->supplierId);
        $suppliers=\DB::table('flight_rute')->where('dep_supplier',$req->supplierId)->get();
        // $suppliers=\DB::table('Flight_sup_seats')->where('supplier',$req->supplierId)->get();
          return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]); 
         
    } 
    public function get_suppliers_flights_rute(Request $req){
        // dd($req->flight_id);
        $suppliers=\DB::table('Flight_sup_seats')->where('id',$req->flight_id)->first();
          return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]); 
         
    }
    
    public function view_seat_occupancy(Request $req){
        // dd($req->flight_id);
        $suppliers=\DB::table('tours')->where('flight_id',$req->flight_id)->get();
          return response()->json(['message'=>'success','fetchedsupplier'=>$suppliers]); 
         
    }
    public function invoice_for_occupancy(Request $req){
        // dd($req->tour_id);
        $invoice = \DB::table('tours_bookings')
        ->join('cart_details','cart_details.booking_id','tours_bookings.id')
        ->where('cart_details.tour_id',$req->tour_id)->get();
          return response()->json(['message'=>'success','fetchedsupplier'=>$invoice]); 
         
    }
    public function fetchflightrate(Request $req){
        // dd($req->tourId);
        $invoice = \DB::table('tours_2')
        ->where('tour_id',$req->tourId)
        ->first();
          return response()->json(['message'=>'success','fetchedsupplier'=>$invoice]); 
         
    }
    public function pax_details(Request $req){
        // dd($req->tourId);
        $invoice = \DB::table('tours_bookings')
        ->where('invoice_no',$req->invoice_no)
        ->first();
          return response()->json(['message'=>'success','fetchedsupplier'=>$invoice]); 
         
    }
    // chart
    public function fetchallhotels(Request $req){
        // dd($req->hotel_id);
        $hotel = \DB::table('hotels')
        ->get();
          return response()->json(['message'=>'success','fetchedsupplier'=>$hotel]); 
         
    }
    public function fetchhotelrecord(Request $req){
        // dd($req);
        // dd($req->startDate);
        // dd($req->includingDate);

     

     
   
    $hotel = $req->hotel_id;
    $from = $req->startDate;
    $upto = $req->includingDate;
     
    $stop_date = $upto;
    $stop_date = date('Y-m-d', strtotime($stop_date . ' +1 day'));

    $all_suppliers = \DB::table('rooms_Invoice_Supplier')->get();
    $all_types = \DB::table('rooms_types')->get();
    
    // $all_rooms = \DB::table('rooms')->where('availible_from', ">" ,$from)->where('availible_to', "<", $stop_date)->where('hotel_id',$hotel)->get();
    $all_rooms = \DB::table('rooms')
    ->where('hotel_id',$hotel)
    ->orWhere(function($query) use($from,$stop_date,$hotel) {
                        $query->where('hotel_id',$hotel);
                        $query->where('availible_from', ">" ,$from)->where('availible_to', "<", $stop_date);
                })
                                    ->get();
    $booked_room = \DB::table('hotel_booking')->where('check_in', ">" ,$from)->where('check_out', "<", $stop_date)->where('provider','hotels')->whereJsonContains('hotel_checkavailability',['id'=>$hotel])->where('hotel_reservation','confirmed')->get();
   
//   dd($all_rooms);
   
   

    function getBetweenDates($startDate, $endDate)
    {
        $rangArray = [];
            
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
             
        for ($currentDate = $startDate; $currentDate <= $endDate; 
                                        $currentDate += (86400)) {
                                                
            $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        }
  
        return $rangArray;
    }
  
    $dates = getBetweenDates($from, $upto);

  
       $all_suppliers_records = [];
     //all rooms
    foreach($all_suppliers as $supkey1 => $supplier){
     

      $supplier_id_found = false;
    
   
    
         
 
    //total room
    $rooms_types_arr = [];
  
  
   

            foreach($all_types as $skey1 => $all_typez){
                 $all_dates_record=[];
                 $room_type_found = false;
                foreach($dates as $key1 => $date){
               $total_rooms_type_count = 0;
   
                $total_website_booked_count = 0;
                $total_admin_booked_count = 0;
                $total_inhouse_count = 0;
                $total_stay_count = 0;
                $total_checkIN_count = 0;
                $total_checkOUT_count = 0;
                $total_package_count = 0;
                $total_package_booked_count = 0;
                foreach($all_rooms as $skey1 => $all_booking_singlez){
                   
                    //  echo "From outer Room Id ".$all_booking_singlez->id;
                    if($all_booking_singlez->room_supplier_name == $supplier->id && $date >= $all_booking_singlez->availible_from && $date <= $all_booking_singlez->availible_to  && $all_booking_singlez->room_type_cat == $all_typez->id){
                        //   dd($all_booking_singlez);
                        $total_rooms_type_count +=$all_booking_singlez->quantity;
                        $booking_details = json_decode($all_booking_singlez->booking_details);
                         if(isset($booking_details)){
                             
                                                     foreach($booking_details as $skey1 => $booking_detailz){
                                                        if($booking_detailz->booking_from == 'website' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                                            //  echo("website");
                                                            
                                                        $total_inhouse_count += $booking_detailz->quantity;
                                                        $total_website_booked_count += $booking_detailz->quantity;
                                                        
                                                            if($date == $booking_detailz->check_in){
                                                                $total_checkIN_count ++;
                                                            } 
                                                            if($date == $booking_detailz->check_out){
                                                                $total_checkOUT_count ++;
                                                            } 
                                                            if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                                $total_stay_count ++;
                                                            }
                                                    
                                                             
                                                        }
                                                        if($booking_detailz->booking_from == 'Invoices' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                                         
                                                            $total_inhouse_count += $booking_detailz->quantity;
                                                            $total_admin_booked_count += $booking_detailz->quantity;
                                                              
                                                            if($date == $booking_detailz->check_in){
                                                                $total_checkIN_count ++;
                                                                    
                                                            } 
                                                            if($date == $booking_detailz->check_out){
                                                                $total_checkOUT_count ++;
                                                                     
                                                            } 
                                                            if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                                $total_stay_count ++;
                                                            }
                                                                 
                                                        }  
                                                         if($booking_detailz->booking_from == 'package' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                                             
                                                                $total_inhouse_count += $booking_detailz->quantity;
                                                                $total_package_booked_count += $booking_detailz->quantity;
                                                                     
                                                                       
                                                            if($date == $booking_detailz->check_in){
                                                                $total_checkIN_count ++;
                                                            } 
                                                            if($date == $booking_detailz->check_out){
                                                                $total_checkOUT_count ++;
                                                            } 
                                                            if($date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                                                $total_stay_count ++;
                                                            }
                                                        } 
                                                     }
                             }
                             
                             $room_type_found = true;
                }
              
        
                   
                    if($all_booking_singlez->more_room_type_details != null && $all_booking_singlez->more_room_type_details !='null' && $all_booking_singlez->more_room_type_details != ''){
                          
                          $more_rooms_data = json_decode($all_booking_singlez->more_room_type_details);
                         
                          
                          foreach($more_rooms_data as $more_room_res){
                              if($more_room_res->more_room_supplier_name == $supplier->id && $date >= $more_room_res->more_room_av_from && $date <= $more_room_res->more_room_av_to  && $more_room_res->more_room_type_id == $all_typez->id){
          
                      
                                    $total_rooms_type_count +=$more_room_res->more_quantity;
                                    $booking_details = json_decode($more_room_res->more_booking_details);
                                             if(isset($booking_details)){
                                                         
                                                     foreach($booking_details as $skey1 => $booking_detailz){
                                                           if($booking_detailz->booking_from == 'website' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                                                //  echo("website");
                                                            $total_inhouse_count += $booking_detailz->quantity;
                                                            $total_website_booked_count += $booking_detailz->quantity;
                                                              
                                                                if($date == $booking_detailz->check_in){
                                                                    $total_checkIN_count ++;
                                                                 
                                                                } 
                                                                if($date == $booking_detailz->check_out){
                                                                    $total_checkOUT_count ++;
                                                                } 
                                                                if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                                    $total_stay_count ++;
                                                                }
                                                                  
                                                            }
                                                            if($booking_detailz->booking_from == 'Invoices' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                                             
                                                                $total_inhouse_count += $booking_detailz->quantity;
                                                                $total_admin_booked_count += $booking_detailz->quantity;
                                                                  
                                                                if($date == $booking_detailz->check_in){
                                                                    $total_checkIN_count ++;
                                                                } 
                                                                if($date == $booking_detailz->check_out){
                                                                    $total_checkOUT_count ++;
                                                                } 
                                                                if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                                    $total_stay_count ++;
                                                                }
                                                                     
                                                            } 
                                                            if($booking_detailz->booking_from == 'package' && $date >= $booking_detailz->check_in && $date <= $booking_detailz->check_out){
                                                             
                                                                $total_inhouse_count += $booking_detailz->quantity;
                                                                $total_package_booked_count += $booking_detailz->quantity;
                                                                          
                                                                if($date == $booking_detailz->check_in){
                                                                    $total_checkIN_count ++;
                                                                } 
                                                                if($date == $booking_detailz->check_out){
                                                                    $total_checkOUT_count ++;
                                                                } 
                                                                if($date > $booking_detailz->check_in && $date < $booking_detailz->check_out){
                                                                    $total_stay_count ++;
                                                                }
                                                                     
                                                            } 
                                                     }
                                                 }
                                    //more days working
                                 
                                    
                    
                               
                                
                             
                                
                                         
                                        
                                         
                                            // $total_rooms_count += $all_booking_singlez->quantity; 
                                            // $room_type_count += $all_booking_singlez->quantity;
                                            $supplier_id_found = true;
                                             $room_type_found = true;
                              
                                 
                                        }
                          }
                          
                    
                      }
                      
         

                }
                
                 $one_type_day_room =[
                                              'date'=>$date,
                                              'supplier_name'=>$supplier->room_supplier_name,
                                              'room_type_name' => $all_typez->room_type,
                                              'room_type_id' => $all_typez->id,
                                              'total_rooms'=> $total_rooms_type_count,
                                              'total_Inhouse'=> $total_stay_count,
                                              'remaining_room'=>$total_rooms_type_count - $total_inhouse_count,
                                              'total_admin_booked'=> $total_admin_booked_count,
                                              'total_website_booked'=> $total_website_booked_count,
                                              'total_package_booked'=> $total_package_booked_count,
                                              'total_checkIN_count'=> $total_checkIN_count,
                                              'total_checkOUT_count'=> $total_checkOUT_count,
                                            //   'total_stay_count'=> $total_stay_count,
                                              ];
                array_push($all_dates_record,$one_type_day_room);

               
            }
    
                 
  
            $one_type_record_data = [
                
                 'type'=>$all_typez->room_type,
                 'rooms_record' => $all_dates_record,
                ];
            
            // echo "supplier id is ".$supplier->id;
            //   echo "<pre>";
            //         print_r($one_day_data);
            //         echo "</pre>";
                    
                    
                    if($room_type_found){
                            array_push($rooms_types_arr,$one_type_record_data);
                              
                    }
        
            
            
   
           
        }
        
       
        
            $single_supplier_arr = [
                'supplier_id'=>$supplier->id,
               
                // 'supplier_name'=>$supplier->room_supplier_name,
                'supplier_record'=>$supplier->room_supplier_name,
                'rooms_data'=>$rooms_types_arr
                ];
                
               
        
        if($supplier_id_found){
                  array_push($all_suppliers_records,$single_supplier_arr);
                  
        }
        
                
              
         
    }
      
      
       
  


 return [
    
     "dates" => $dates,
     "rooms_records" => $all_suppliers_records,
   
    
     ];
   
         
         
    }
    public function supplierdetail(Request $req){
        // dd($req);

   
     $supplierId = $req->supplier_id;
     
     
    
    $supplier = \DB::table('rooms_Invoice_Supplier')->where('id',$supplierId)->first();
        //   dd($all_booking_single);
  
     return response()->json(['message'=>'success','fetchedsupplier'=>$supplier]);
         
    }
    public function getbooking(Request $req){
        // dd($req);
            if($req->type == "website"){
                 $from = $req->startDate;
                //  $currentDate = $req->currentDate;
                 $stop_date = $req->includingDate;
                 $suplierid = $req->suplier;
                 $roomid = $req->roomid;
                 $hotel = $req->hotel;
  
               
               
                 $booked_room = \DB::table('hotel_booking')->where('check_in','>',$from)->where('check_out', "<", $stop_date)->where('provider','hotels')->where('hotel_reservation','confirmed')->whereJsonContains('rooms_checkavailability',['rooms_type_id'=>$roomid,'rooms_supplier'=>$suplierid])->get();
                    //   dd($booked_room);
            
                 return response()->json(['message'=>'success','fetchedsupplier'=>$booked_room,'type'=>$req->type]);
        
            }
            if($req->type == "adminsite"){
                // dd($req);
                 $from = $req->startDate;
                 $roomid = $req->roomid;
                 $suplierid = $req->suplier;
                 $currentDate = $req->currentDate;
                 $stop_date = $req->includingDate;

                //  
                //  $booked_room = \DB::table('add_manage_invoices')->where('start_date','>',$from)->where('end_date', "<", $stop_date)->whereJsonContains('services',[0=>"accomodation_tab"])->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                 $booked_room = \DB::table('add_manage_invoices')->where('start_date','>',$from)->where('end_date', "<", $stop_date)->whereJsonContains('services',[0=>"accomodation_tab"])->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                    //   dd($booked_room);
              
                 return response()->json(['message'=>'success','fetchedsupplier'=>$booked_room,'type'=>$req->type]);
            }
            
            
           
            if($req->type == "pacsite"){
                // dd("pacadmin here");
                 $from = $req->startDate;
                 $currentDate = $req->currentDate;
                 $stop_date = $req->includingDate;
                 $suplier = $req->suplier;
                 $hotel = $req->hotel;
                 $roomid = $req->roomid;
                 $suplierid = $req->suplier;
                //  $booked_room = \DB::table('hotel_booking')->where('check_in','>',$currentDate)->where('provider','hotels')->where('hotel_reservation','confirmed')->get();
               $booked_room = \DB::table('tours')->where('start_date', ">" ,$from)->where('end_date', "<", $stop_date)->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                    //   dd($booked_room);
              
                 return response()->json(['message'=>'success','fetchedsupplier'=>$booked_room,'type'=>$req->type]);
            }
            
            
             if($req->type == "inhouse"){
                // dd("admin here");
                 $main_arr = [];
                 $from = $req->startDate;
                 $roomid = $req->roomid;
                 $suplierid = $req->suplier;
                 $currentDate = $req->currentDate;
                 $stop_date = $req->includingDate;
                 $hotel = $req->hotel;
         
                $web_booked_room = \DB::table('hotel_booking')->where('check_in','>',$from)->where('check_out', "<", $stop_date)->where('provider','hotels')->where('hotel_reservation','confirmed')->whereJsonContains('rooms_checkavailability',['rooms_type_id'=>$roomid,'rooms_supplier'=>$suplierid])->get();
                array_push($main_arr,$web_booked_room);
                // $admin_booked_room = \DB::table('add_manage_invoices')->where('start_date','>',$from)->where('end_date', "<", $stop_date)->whereJsonContains('services',[0=>"accomodation_tab"])->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                $admin_booked_room = \DB::table('add_manage_invoices')->where('start_date','>',$from)->where('end_date', "<", $stop_date)->whereJsonContains('services',[0=>"accomodation_tab"])->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                array_push($main_arr,$admin_booked_room);
                $package_booked_room = \DB::table('tours')->where('start_date', ">" ,$from)->where('end_date', "<", $stop_date)->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                array_push($main_arr,$admin_booked_room);
              
                 return response()->json(['message'=>'success','fetchedsupplier'=>$main_arr,'type'=>$req->type]);
            } 
            if($req->type == "checkIn"){
                // dd($req);
                 $main_checkin_arr = [];
                 $from = $req->startDate;
                 $roomid = $req->roomid;
                 $suplierid = $req->suplier;
                 $currentDate = $req->currentDate;
                 $stop_date = $req->includingDate;
                 $hotel = $req->hotel;
                
                $web_booked_room = \DB::table('hotel_booking')->where('check_in','>=',$from)->where('check_out','<=',$stop_date)->where('hotel_reservation','confirmed')->whereJsonContains('rooms_checkavailability',['rooms_type_id'=>$roomid,'rooms_supplier'=>$suplierid])->get();
                $data_arr=[
                    'type'=>'website',
                    'data'=>$web_booked_room,
                    ];
                array_push($main_checkin_arr,$data_arr);
                $admin_booked_room = \DB::table('add_manage_invoices')->where('start_date','>=',$from)->where('end_date','<=',$stop_date)->whereJsonContains('services',[0=>"accomodation_tab"])->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                $data_arr=[
                    'type'=>'admin',
                    'data'=>$admin_booked_room,
                    ];
                array_push($main_checkin_arr,$data_arr);
                $package_booked_room = \DB::table('tours')->where('start_date', "=" ,$from)->where('end_date', "<=" ,$stop_date)->get();
                 $data_arr=[
                    'type'=>'package',
                    'data'=>$package_booked_room,
                    ];
                array_push($main_checkin_arr,$data_arr);
              
                 return response()->json(['message'=>'success','fetchedsupplier'=>$main_checkin_arr,'type'=>$req->type]);
            }
            if($req->type == "checkOUT"){
                // dd("admin here");
               $main_checkin_arr = [];
                 $from = $req->startDate;
                 $roomid = $req->roomid;
                 $suplierid = $req->suplier;
                 $currentDate = $req->currentDate;
                 $stop_date = $req->includingDate;
                 $hotel = $req->hotel;
                 
                $web_booked_room = \DB::table('hotel_booking')->where('check_in','>=',$from)->where('check_out','<=',$stop_date)->where('hotel_reservation','confirmed')->whereJsonContains('rooms_checkavailability',['rooms_type_id'=>$roomid,'rooms_supplier'=>$suplierid])->get();
                $data_arr=[
                    'type'=>'website',
                    'data'=>$web_booked_room,
                    ];
                array_push($main_checkin_arr,$data_arr);
                $admin_booked_room = \DB::table('add_manage_invoices')->where('start_date','>=',$from)->where('end_date','<=',$stop_date)->whereJsonContains('services',[0=>"accomodation_tab"])->whereJsonContains('accomodation_details',['hotel_type_id'=>$roomid,'hotel_supplier_id'=>$suplierid])->get();
                $data_arr=[
                    'type'=>'admin',
                    'data'=>$admin_booked_room,
                    ];
                array_push($main_checkin_arr,$data_arr);
                $package_booked_room = \DB::table('tours')->where('start_date', "=" ,$from)->where('end_date', "<=" ,$stop_date)->get();
                 $data_arr=[
                    'type'=>'package',
                    'data'=>$package_booked_room,
                    ];
                array_push($main_checkin_arr,$data_arr);
              
                 return response()->json(['message'=>'success','fetchedsupplier'=>$main_checkin_arr,'type'=>$req->type]);
            }
    }

}
