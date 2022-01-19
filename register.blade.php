
@extends('template/frontend/userdashboard/employeepanel/layout/default')
@section('content')
<form id="registerform" action="{{URL::to('')}}/register_method" method="post">
	@csrf
  <div style="margin: 0px; color: white; line-height: 140%; word-wrap: break-word; font-weight: normal; font-family: 'Open Sans',sans-serif; font-size: 20px;background-color: #00a59b; width: auto;"> 
    	<img src="https://umrahtech.com/public/assets/frontend/images/visa_from_icons/passenger-details.png" class="object-cover h-10" style="display: inline;">
    	<h4 style="display: inline;">Lead Passenger Details</h4>
  	
  </div>

	<div class="flex flex-wrap -mx-3 mb-6 mt-5">

    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        E-mail:
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="email" id="grid-first-name" type="email" value="{{$lead_passenger_full->email}}" readonly="">
    </div>
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        Mobile#:
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="mobile" id="grid-first-name" type="text" placeholder="Please Enter Mobile#" value="{{$lead_passenger_full->phone_no}}" readonly="" required="">
    </div>
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        Date Of Birth:
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="dateofbirth" id="grid-first-name" type="date" placeholder="Please Select The Date" value="{{$lead_passenger_full->date_of_birth}}" readonly="" required="">
    </div>
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        Passport#:
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="passport" id="grid-first-name" type="text" placeholder="Please Enter Your Passport#" value="{{$lead_passenger_full->passport_no}}"  readonly="" required="">
    </div>
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
     <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        Nationality:
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="nationality" id="grid-first-name" type="text" placeholder="Please Enter Your Passport#"  value="{{$lead_passenger_full->nationality}}" required="" readonly="">
    </div>
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        Gender:
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="gender" id="grid-first-name" type="text" placeholder="Please Enter Your Passport#"  value="{{$lead_passenger_full->gender}}" required="" readonly="">
    </div>
  </div>
  <div style="margin: 0px; color: white; line-height: 140%; word-wrap: break-word; font-weight: normal; font-family: 'Open Sans',sans-serif; font-size: 20px;background-color: #00a59b; width: auto;"> 
		<img src="https://umrahtech.com/public/assets/frontend/images/visa_from_icons/arrival.png" class="object-cover h-10" style="display: inline;">
		<h4 style="display: inline;">ARRIVAL DETAILs</h4>
		
	</div>
	<div class="flex flex-wrap -mx-3 mb-6 mt-5">
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        Arrival Airport:
      </label>
        <select class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="arrivalairport"  id="grid-first-name" required="">
        	<option disabled="" selected="this">Choose Your Arrival Airport</option>
        	<option>King Abdul Aziz International Airport Jeddah</option>
        	<option>Prince Mohammad bin Abdulaziz International Airport Medina</option>
        </select>
    </div>
   <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        Arrival Flight#:
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="arrivalflight" id="grid-first-name" type="text" placeholder="Please Enter Departure Flight#" required="">
    </div>
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 Arrival_date">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="arrival_date">
        Arrival Date:
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"  name="arrivaldate" id="arrival_date" type="date" required="">
    </div>
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        Arrival Time:
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="arrivaltime" id="grid-first-name" type="time" required="">
    </div>
  </div>
  <div style="margin: 0px; color: white; line-height: 140%; word-wrap: break-word; font-weight: normal; font-family: 'Open Sans',sans-serif; font-size: 20px;background-color: #00a59b; width: auto;"> 
		<img src="https://umrahtech.com/public/assets/frontend/images/visa_from_icons/departure.png" class="object-cover h-10" style="display: inline;">
		<h4 style="display: inline;">DEPARTURE DETAILs</h4>
	</div>
  <div class="flex flex-wrap -mx-3 mb-6 mt-5">
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        Departure Airport:
      </label>
        <select class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="departureairport"  id="grid-first-name" id="grid-first-name" required="">
        	<option disabled="" selected="this">Choose Your Departure Airport</option>
        	<option>King Abdul Aziz International Airport Jeddah</option>
        	<option>Prince Mohammad bin Abdulaziz International Airport Medina</option>
        </select>
    </div>
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        Departure Flight#:
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="departureflight" id="grid-first-name" type="text" placeholder="Please Enter Departure Flight#" required="">
    </div>
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 Departure_date">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="departuredate">
        Departure Date:
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="departuredate" id="departuredate" type="date" required="">
    </div>
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        Departure Time:
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="departuretime" id="grid-first-name" type="time" required="">
    </div>
  </div>
  <div style="margin: 0px; color: white; line-height: 140%; word-wrap: break-word; font-weight: normal; font-family: 'Open Sans',sans-serif; font-size: 20px;background-color: #00a59b; width: auto;"> 
    <i class="fa fa-home" aria-hidden="true"></i>
    <h4 style="display: inline;">STAY DETAILs</h4>
    
  </div>
  <div class="flex flex-wrap mb-6 mt-5">
    <button class="object-cover h-10 w-full md:w-6/10 px-6 mb-7 md:mb-0 " style="margin: 0px; color: white; line-height: 140%; word-wrap: break-word; font-weight: normal; font-family: 'Open Sans',sans-serif; font-size: 20px;background-color: #00a59b; width: auto; border-radius: 10px;" type="button" id="add_more_stay_btn">
       Add More</button>
       <div id="AdatenDdate_msg"></div>
  </div>

@if(!empty($makcity_name))
  <div>
  <div class="flex flex-wrap -mx-3 mb-6 mt-5 clone_this">
    <div class="w-full md:w-1/6 px-4 mb-6 md:mb-0">   
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-4" for="grid-first-name">
          Select stay:
      </label>
      <select class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white input" name="staydetails[stay_at][]" id="grid-first-name"  required="" readonly>
        <option>{{ $makcity_name }}</option> 
      </select>
    </div>
    <div class="w-full md:w-1/3 px-12 mb-10 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-3" for="grid-first-name">
        CHOOSE STAY DATEs:
      </label>
    <div>
    <div class="preview">
      <input style="height: 36px; border-radius: 0.375rem; padding-top: 5%; margin-top: 3%; padding-bottom: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; border-radius: 0.375rem; border-style: solid; -webkit-appearance: none; -moz-appearance: none;  box-sizing: border-box; border-color: #e2e8f0; border-width: 0px;" value="{{ $hmakcheckInDate }}-{{$hmakcheckOutDate}}"  name="staydetails[stay_date][]" readonly=""  selected="">
    </div>
    </div>   
    </div>
    <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        CHECK_IN TIME:
      </label>
      <div>
      <div class="preview">
        <input style="height: 36px; border-radius: 0.375rem; padding-top: 5%; margin-top: 15%; padding-bottom: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; border-radius: 0.375rem; border-style: solid; -webkit-appearance: none; -moz-appearance: none;  box-sizing: border-box; border-color: #e2e8f0; border-width: 0px; background-color: white;" type="time" value="{{$hmakcheckInTime}}" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="staydetails[check_in][]" selected="" readonly>
         
        </div>
        </div>            
        </div>
           <div class="w-full md:w-1/6 px-4 mb-8 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        CHECK_OUT TIME:
      </label>
      <div>
        <div class="preview">
          <input style="height: 36px; border-radius: 0.375rem; padding-top: 5%; margin-top: 15%; padding-bottom: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; border-radius: 0.375rem; border-style: solid; -webkit-appearance: none; -moz-appearance: none;  box-sizing: border-box; border-color: #e2e8f0; border-width: 0px; background-color: white;" type="time" value="{{$hmakcheckOutTime}}" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="staydetails[check_out][]" selected="" readonly>
         
        </div>
          </div>            
        </div>
         <div class="w-full md:w-1/6 px-4 mb-8 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
      INVOICE_NO:
      </label>
      <div>
        <div class="preview">
          <input style="height: 36px; border-radius: 0.375rem; padding-top: 5%; margin-top: 15%; padding-bottom: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; border-radius: 0.375rem; border-style: solid; -webkit-appearance: none; -moz-appearance: none;  box-sizing: border-box; border-color: #e2e8f0; border-width: 0px; background-color: white;" type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"value="{{$makkah_booking_invoice_no}}" selected="" readonly >
    
        </div>
          </div>            
        </div>
    </div>
  </div>
  @endif

  @if(!empty($madicity_name))
 <div>
  <div class="flex flex-wrap -mx-3 mb-6 mt-5 clone_this">
    <div class="w-full md:w-1/6 px-4 mb-6 md:mb-0">   
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-4" for="grid-first-name">
          Select stay:
      </label>
      <select class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white input" name="staydetails[stay_at][]" id="grid-first-name"  required="" readonly>  
          <option>{{ $madicity_name }}</option> 
      </select>
    </div>
    <div class="w-full md:w-1/3 px-12 mb-10 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-3" for="grid-first-name">
        CHOOSE STAY DATEs:
      </label>
      <div>
        <div class="preview">
          <input style="height: 36px; border-radius: 0.375rem; padding-top: 5%; margin-top: 3%; padding-bottom: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; border-radius: 0.375rem; border-style: solid; -webkit-appearance: none; -moz-appearance: none;  box-sizing: border-box; border-color: #e2e8f0; border-width: 0px;" value="{{ $hmadicheckInDate }}-{{$hmadicheckOutDate}}"  name="staydetails[stay_date][]" selected="" readonly>
         
        </div>
          </div>   

        </div>
         <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        CHECK_IN TIME:
      </label>
      <div>
        <div class="preview">
          <input style="height: 36px; border-radius: 0.375rem; padding-top: 5%; margin-top: 15%; padding-bottom: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; border-radius: 0.375rem; border-style: solid; -webkit-appearance: none; -moz-appearance: none;  box-sizing: border-box; border-color: #e2e8f0; border-width: 0px; background-color: white;" type="time" value="{{$hmadicheckInTime}}" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="staydetails[check_in][]" selected="" readonly>
         
        </div> 
          </div>            
        </div>
           <div class="w-full md:w-1/6 px-4 mb-8 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        CHECK_OUT TIME:
      </label>
      <div>
        <div class="preview">
          <input style="height: 36px; border-radius: 0.375rem; padding-top: 5%; margin-top: 15%; padding-bottom: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; border-radius: 0.375rem; border-style: solid; -webkit-appearance: none; -moz-appearance: none;  box-sizing: border-box; border-color: #e2e8f0; border-width: 0px; background-color: white;" type="time" value="{{$hmadicheckOutTime}}" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="staydetails[check_out][]" selected="" readonly>
         
        </div> 
          </div>            
        </div>
         <div class="w-full md:w-1/6 px-4 mb-8 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
      INVOICE_NO:
      </label>
      <div>
        <div class="preview">
          <input style="height: 36px; border-radius: 0.375rem; padding-top: 5%; margin-top: 15%; padding-bottom: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; border-radius: 0.375rem; border-style: solid; -webkit-appearance: none; -moz-appearance: none;  box-sizing: border-box; border-color: #e2e8f0; border-width: 0px; background-color: white;" type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"value="{{$nadina_booking_invoice_no}}" selected="" readonly >
         
        </div>
          </div>            
        </div>

    </div>
  </div>
  @endif

   <div class="add_more_stay_section">
  <div class="flex flex-wrap -mx-3 mb-6 mt-5 clone_this">
    <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0">   
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
          Select stay:
      </label>
      <select class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white input " name="staydetails[stay_at][]" id="grid-first-name" required="" >
          <option class="Select_stay" selected="">Choose Your Stay</option>
          <option class="makoption" readonly>Makkah</option>   
          <option class="madioption" readonly>Madinah</option>   
          <option class="joption" readonly>Jeddah</option>   
      </select>
    </div> 
    <div class="w-full md:w-1/3 px-20 mb-10 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-3" for="grid-first-name">
        CHOOSE STAY DATEs:
      </label>
      <div>
        <div class="preview CHOOSE_STAY_DATE">
          <input style="height: 36px; border-radius: 0.375rem; padding-top: 5%; margin-top: 15%; padding-bottom: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; border-radius: 0.375rem; border-style: solid; -webkit-appearance: none; -moz-appearance: none;  box-sizing: border-box; border-color: #e2e8f0; border-width: 0px; background-color: white;" data-daterange="true"  class="datepicker input w-100 border block" name="staydetails[stay_date][]" required="" value="">
         
        </div>
          </div>   
        </div>
         <div class="w-full md:w-1/6 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        CHECK_IN TIME:
      </label>
      <div>
        <div class="preview CHECK_IN_TIME">
          <input style="height: 36px; border-radius: 0.375rem; padding-top: 5%; margin-top: 16%; padding-bottom: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; border-radius: 0.375rem; border-style: solid; -webkit-appearance: none; -moz-appearance: none;  box-sizing: border-box; border-color: #e2e8f0; border-width: 0px; background-color: white;" type="time" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="staydetails[check_in][]" value="">
         
        </div>
          </div>            
        </div>
           <div class="w-full md:w-1/6 px-4 mb-8 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
        CHECK_OUT TIME:
      </label>
      <div>
        <div class="preview CHECK_OUT_TIME">
          <input style="height: 36px; border-radius: 0.375rem; padding-top: 5%; margin-top: 16%; padding-bottom: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; border-radius: 0.375rem; border-style: solid; -webkit-appearance: none; -moz-appearance: none;  box-sizing: border-box; border-color: #e2e8f0; border-width: 0px; background-color: white;" type="time" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" name="staydetails[check_out][]" value="">
         
        </div>
          </div>            
        </div>
           <div class="w-full md:w-1/6 px-4 mb-8 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
      INVOICE_NO:
      </label>
      <div>
        <div class="preview invoice_div">
          <input style="height: 36px; border-radius: 0.375rem; padding-top: 5%; margin-top: 16%; padding-bottom: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; border-radius: 0.375rem; border-style: solid; -webkit-appearance: none; -moz-appearance: none;  box-sizing: border-box; border-color: #e2e8f0; border-width: 0px; background-color: white;" type="text" class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" required="">
         
        </div>   
          </div>            
        </div>
    </div>
  </div>

<span class="invoice_error" style="text-align: center;"></span>

    <div class="w-full md:w-5/10 px-48 mb-6 md:mb-0 ">
      <button class="object-cover h-10 w-full md:w-5/10 px-3 mb-6 md:mb-0 px-48 md:mb-0" style="margin: 0px; color: white; line-height: 140%; word-wrap: break-word; font-weight: normal; font-family: 'Open Sans',sans-serif; font-size: 20px;background-color: #00a59b; width: auto; border-radius: 10px; display: flex;" type="submit" >
       REGISTER</button>
    </div> 

</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
var Adate;
var Ddate;
var Departureyear;
var arrivalyear;
var Departuremonth;
$(document).ready(function(){

  $('#add_more_stay_btn').on('click',function(){    
      var clone = $('.clone_this').first().clone().appendTo(".add_more_stay_section").val();
      initialCalendar();
  });

  $('.Arrival_date').on('change',function(){    
      Adate = $(this).find("input").val();
      arrivalyear = new Date(Adate).getFullYear();
      getFullDate = new Date(Adate);
  });

  $('.Departure_date').on('change',function(){    
      Ddate = $(this).find("input").val();
      getFullDate = new Date(Ddate);
      Departureyear = new Date(Ddate).getFullYear();
      Departuremonth = new Date(Ddate).getMonth()+1;
      initCalendar();      
  });
  function initialCalendar(maxDate,minDate) {

  $('.datepicker').each(function () {

  var options = {
    singleDatePicker: true,
    showDropdowns: true,
    minDate:getFullDateA,
    maxDate:getFullDateD,
  };

  if ($(this).data('daterange')) {
    options.singleDatePicker = false;
  }

  $(this).daterangepicker(options);
  });
  }
  function initCalendar(maxDate,minDate) {
  Adate = $('.Arrival_date').find("input").val();
  Ddate = $('.Departure_date').find("input").val();
  getFullDateA = new Date(Adate);
  getFullDateD = new Date(Ddate);
  var maxYear = Departureyear;
  var minYear = arrivalyear;
  var changeMonth = Departuremonth;
  $('.datepicker').each(function () {
  var options = {
  changeMonth: changeMonth,
  singleDatePicker: true,
  showDropdowns: true,
  startDate: getFullDateA,
  minDate:getFullDateA,
  endDate: getFullDateD,
  maxDate:getFullDateD,
  minYear: minYear,
  maxYear: maxYear
  };

  if ($(this).data('daterange')) {
  options.singleDatePicker = false;
  }

  $(this).daterangepicker(options);
  });
  }
  $('.invoice_div').on('keyup',function(){    
  invoive_no = $(this).find("input").val();
  console.log(invoive_no);
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  var find_invoice = invoive_no;
  $.ajax({
  url :  '{{URL::to('find_invoice')}}',
  type: 'POST',
  data: {_token: CSRF_TOKEN,
  "invoice_no": find_invoice
  },
  dataType: 'json',
  success: function(response){
  
  var invoice_data = response;
  if (!!response[0]) {
  $( ".CHECK_OUT_TIME" ).html(response[4]);
  $( ".CHECK_IN_TIME" ).html(response[3]);
  $( ".CHOOSE_STAY_DATE" ).html(response[1]+"-"+response[2]);
  $( ".Select_stay" ).html(response[0]);
  $( ".makoption" ).val('');
  $( ".madioption" ).val('');
  $( ".joption" ).val('');
  $( ".invoice_error" ).remove();
  }else
  {
  $( ".invoice_error" ).html('<p style="color: #00a59b;">Please enter the correct invoice no</p>');
  }

          
       }
    });
  });
});


</script>

@stop