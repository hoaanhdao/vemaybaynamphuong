<?php
/*
Template Name: wssave2crm
*/
/*
	Name: Lưu vào SugarCRM
	By: LuongQC
	Logs:
		- Ngày 27-09-2013: 
			+ bổ sung 3 loại giá người lớn, trẻ em, trẻ sơ sinh trong chuyến bay quốc tế
			+ chỉnh lại cách lưu vào chi tiết vé chiều đi và chiều về cho chuyến bay quốc tế
*/

$json = file_get_contents('php://input');
$obj = json_decode($json);
if($obj){
	include(TEMPLATEPATH.'/flight_config/sugarrest/sugar_rest.php');
	
	$sugar = new Sugar_REST();
	$error = $sugar->get_error();
	if($error !== FALSE) {
		echo $error['name'];
		exit();
	}

    $flight_type = ($obj->flight_type) == 1 ? 1 : 0; // như trong BM 1 chiều là 1, khứ hồi là 0
    $ticket_type = $obj->ticket_type; // loại vé quốc tế là 2, nội địa là 1
	$contact_name = $obj->contact_name;
	$phone = $obj->phone;
	$email = $obj->email;
	$address = $obj->address;
	$city = $obj->city;
	$country = $obj->country;
	$payment_type = $obj->payment_type;
	$adult = intval($obj->adult);
	$child = intval($obj->child);
	$infant = intval($obj->infant);
	$dep_baseprice = $obj->dep_baseprice;
	$ret_baseprice = $obj->ret_baseprice;
	$inter_tax_fee = $obj->inter_tax_fee;
	$total_amt = $obj->total_amt;
	$note = $obj->note;
	
	// bổ sung cho chuyến bay quốc tế
	/*$price_adult = (float)$obj->price_adult;
	$price_child = (float)$obj->price_child;
	$price_infant = (float)$obj->price_infant;*/
	
	// fix number format date: 30-12-2013
	$price_adult = $obj->price_adult;
	$price_child = $obj->price_child;
	$price_infant = $obj->price_infant;
	
	// chuyển đổi airline code để tính giá
	$aircode_arr = array('VN' => 'vietnamairline', 'BL' => 'jetstar', 'VJ' => 'vietjetair', 'VNA' => 'vietnamairline', 'JET' => 'jetstar', 'VJA' => 'vietjetair');
	$aircode_out = $aircode_arr[$obj->aircode_outbound];
	$aircode_in = '';
	
	$aircode_outbound_bm = get_airline_code_bm($obj->aircode_outbound);
	$aircode_inbound_bm = "";
	if($flight_type == 0){
		$aircode_inbound_bm = get_airline_code_bm($obj->aircode_inbound);
		$aircode_in = $aircode_arr[$obj->aircode_inbound];
	}
   
    //--------------- lưu vào booking
    $address=($address=="")?"10C5 Duong D1, P.25, Q. Binh Thanh":$address;
    $country=($country=="")?"VN":$country;
    $city=($city=="")?"HCM":$city;
    $note=($note=="")?"App":$note;

    $args_booking = array(
        'flight_type' => $flight_type,
		'ticket_type' => $ticket_type,
        'airline' => $aircode_outbound_bm,
        'airline_inbound' => $aircode_inbound_bm,
        'contact_name' => $contact_name,
        'email' => $email,
        'country' => $country,
        'phone' => $phone,
        'address' => $address,
        'city' => $city,
        'payment_type' => $payment_type,
        'description' => $note,
		'luggage_fee' => 0,
		'ticket_change_fee' => 0,
		'other_fee' => 0,
		'discount_percent' => 0,
		'discount_amount' => 0,
		'thuephi_quocte' => $inter_tax_fee,
		'total_amount' => $total_amt,
		'ip_address' => get_ip_address_from_client(),
		'user_agent' => $_SERVER['HTTP_USER_AGENT']
    );
    $booking = $sugar->set('EC_Flight_Bookings',$args_booking);

	//--------------- lưu vào hành trình bay chiều đi
	$iti_depart = $obj->iti_depart;
	$iti_depart_cnt = count($iti_depart);
	if($iti_depart_cnt > 0){
		for($i=0; $i<$iti_depart_cnt; $i++){
			
			$departure_date = '';
			if(!empty($iti_depart[$i]->dep_datetime)){
				$dep_date_arr = explode(' ', $iti_depart[$i]->dep_datetime); 
				$depdate = explode('/', $dep_date_arr[0]);
				$departure_date = $depdate[2].'-'.str_pad($depdate[1],2,'0',STR_PAD_LEFT).'-'.str_pad($depdate[0],2,'0',STR_PAD_LEFT).' '.$dep_date_arr[1];
			}
			
			$arrival_date = '';
			if(!empty($iti_depart[$i]->arv_datetime)){
				$arv_date_arr = explode(' ', $iti_depart[$i]->arv_datetime); 
				$arvdate = explode('/', $arv_date_arr[0]);
				$arrival_date = $arvdate[2].'-'.str_pad($arvdate[1],2,'0',STR_PAD_LEFT).'-'.str_pad($arvdate[0],2,'0',STR_PAD_LEFT).' '.$arv_date_arr[1];
			}
			
			$args_itinerary = array(
				'name' => $iti_depart[$i]->flight_no, 
				'airline_code' => ($ticket_type == 1) ? get_airline_code_bm($iti_depart[$i]->airline_code) : $iti_depart[$i]->airline_code,
				'flight_number' => $iti_depart[$i]->flight_no,
				'ticket_class' => $iti_depart[$i]->ticket_class,
				'departure' => $iti_depart[$i]->dep,
				'arrival' => $iti_depart[$i]->arv,
				'departure_date' => $departure_date,
				'arrival_date' => $arrival_date,
				'base_price' => $iti_depart[$i]->base_price,
				'direction' => $iti_depart[$i]->direction,
				'description' => $iti_depart[$i]->note,
				'total_price' => NULL,
				'booking_id' => $booking['id'],
			);
			$sugar->set('EC_Booking_Itineraries',$args_itinerary);
		}// for
	}
	
	//--------------- lưu vào hành trình bay chiều về
	$iti_return = $obj->iti_return;
	$iti_return_cnt = count($iti_return);
	if($iti_return_cnt > 0){
		for($i=0; $i<$iti_return_cnt; $i++){
			
			$departure_date = '';
			if(!empty($iti_return[$i]->dep_datetime)){
				$dep_date_arr = explode(' ', $iti_return[$i]->dep_datetime); 
				$depdate = explode('/', $dep_date_arr[0]);
				$departure_date = $depdate[2].'-'.str_pad($depdate[1],2,'0',STR_PAD_LEFT).'-'.str_pad($depdate[0],2,'0',STR_PAD_LEFT).' '.$dep_date_arr[1];
			}
			
			$arrival_date = '';
			if(!empty($iti_return[$i]->arv_datetime)){
				$arv_date_arr = explode(' ', $iti_return[$i]->arv_datetime); 
				$arvdate = explode('/', $arv_date_arr[0]);
				$arrival_date = $arvdate[2].'-'.str_pad($arvdate[1],2,'0',STR_PAD_LEFT).'-'.str_pad($arvdate[0],2,'0',STR_PAD_LEFT).' '.$arv_date_arr[1];
			}
			
			$args_itinerary = array(
				'name' => $iti_return[$i]->flight_no, 
				'airline_code' => ($ticket_type == 1) ? get_airline_code_bm($iti_return[$i]->airline_code) : $iti_return[$i]->airline_code,
				'flight_number' => $iti_return[$i]->flight_no,
				'ticket_class' => $iti_return[$i]->ticket_class,
				'departure' => $iti_return[$i]->dep,
				'arrival' => $iti_return[$i]->arv,
				'departure_date' => $departure_date,
				'arrival_date' => $arrival_date,
				'base_price' => $iti_return[$i]->base_price,
				'direction' => $iti_return[$i]->direction,
				'description' => $iti_return[$i]->note,
				'total_price' => NULL,
				'booking_id' => $booking['id'],
			);
			$sugar->set('EC_Booking_Itineraries',$args_itinerary);
		}// for
	}
	
	//--------------- lưu thông tin hành khách
	$pax = $obj->pax;
	$pax_cnt = count($pax);
	if($pax_cnt > 0){
		for($i=0; $i<$pax_cnt; $i++){
			
			$birthday = '';
			if(!empty($pax[$i]->birthday)){
				$birthday_arr = explode('/', $pax[$i]->birthday);
				$birthday = $birthday_arr[2].'-'.str_pad($birthday_arr[1],2,'0',STR_PAD_LEFT).'-'.str_pad($birthday_arr[0],2,'0',STR_PAD_LEFT);
			}
			
			$args_passenger = array(
				'type' => $pax[$i]->type,
				'salutation' => $pax[$i]->salutation,
				'name' => strtoupper(utf8convert(strtolower($pax[$i]->name))),
				'birthday' => $birthday,
				'luggage_price' => 0,
				'luggage_price_inbound' => 0,
				'eticket_outbound' => '',
				'eticket_inbound' => '',
				'pnr_outbound' => '',
				'pnr_inbound' => '',
				'description' => '',
				'booking_id' => $booking['id']
			);
			$sugar->set('EC_Booking_Passengers',$args_passenger);
		}// for
	}
  	
	//################################### CHIỀU ĐI ################################################//
	
	//--------------- thông tin chi tiết giá vé người lớn
	if($adult > 0){
		$detail_price = get_detail_price($dep_baseprice, 'adult', $aircode_out);
		$total_price = ($ticket_type == 1) ? $adult * ($detail_price['price'] + $detail_price['tax'] + $detail_price['airport_fee'] + $detail_price['admin_fee'] + $detail_price['fee']) : $price_adult;
		$args_adult = array(
			'direction' => 0,
			'name' => 'Người lớn',
			'passenger_type' => 0,
			'quantity' => $adult,
			'unit_price' => $detail_price['price'],
			'tax_and_fee' => $detail_price['tax'],
			'airport_fee' => $detail_price['airport_fee'],
			'admin_fee' => $detail_price['admin_fee'],
			'service_fee' => $detail_price['fee'],
			'total_price' => $total_price,
			'booking_id' => $booking['id']
		);
		$sugar->set('EC_Booking_Details',$args_adult);
	}

	//--------------- thông tin chi tiết giá vé trẻ em
	if($child > 0){
		$detail_price = get_detail_price($dep_baseprice, 'child', $aircode_out);
		$total_price = ($ticket_type == 1) ? $child * ($detail_price['price'] + $detail_price['tax'] + $detail_price['airport_fee'] + $detail_price['admin_fee'] + $detail_price['fee']) : $price_child;
		$args_child = array(
			'direction' => 0,
			'name' => 'Trẻ em',
			'passenger_type' => 1,
			'quantity' => $child,
			'unit_price' => $detail_price['price'],
			'tax_and_fee' => $detail_price['tax'],
			'airport_fee' => $detail_price['airport_fee'],
			'admin_fee' => $detail_price['admin_fee'],
			'service_fee' => $detail_price['fee'],
			'total_price' => $total_price,
			'booking_id' => $booking['id']
		);
		$sugar->set('EC_Booking_Details',$args_child);
	}

    //--------------- thông tin chi tiết giá vé trẻ sơ sinh
	if($infant > 0){
		$detail_price = get_detail_price($dep_baseprice, 'infant', $aircode_out);
		$total_price = ($ticket_type == 1) ? $infant * ($detail_price['price'] + $detail_price['tax'] + $detail_price['airport_fee'] + $detail_price['admin_fee'] + $detail_price['fee']) : $price_infant;
		$args_infant = array(
			'direction' => 0,
			'name' => 'Trẻ sơ sinh',
			'passenger_type' => 2,
			'quantity' => $infant,
			'unit_price' => $detail_price['price'],
			'tax_and_fee' => $detail_price['tax'],
			'airport_fee' => $detail_price['airport_fee'],
			'admin_fee' => $detail_price['admin_fee'],
			'service_fee' => $detail_price['fee'],
			'total_price' => $total_price,
			'booking_id' => $booking['id']
		);
		$sugar->set('EC_Booking_Details',$args_infant);
	}

    //################################### CHIỀU VỀ (NẾU CÓ) ################################################//
	
    if($flight_type == 0 && $ticket_type != 2){
        //--------------- thông tin chi tiết giá vé người lớn
		if($adult > 0){
			$detail_price = get_detail_price($ret_baseprice, 'adult', $aircode_in);
			$total_price = ($ticket_type == 1) ? $adult * ($detail_price['price'] + $detail_price['tax'] + $detail_price['airport_fee'] + $detail_price['admin_fee'] + $detail_price['fee']) : $price_adult;
			$args_adult = array(
				'direction' => 1,
				'name' => 'Người lớn',
				'passenger_type' => 0,
				'quantity' => $adult,
				'unit_price' => $detail_price['price'],
				'tax_and_fee' => $detail_price['tax'],
				'airport_fee' => $detail_price['airport_fee'],
				'admin_fee' => $detail_price['admin_fee'],
				'service_fee' => $detail_price['fee'],
				'total_price' => $total_price,
				'booking_id' => $booking['id']
			);
			$sugar->set('EC_Booking_Details', $args_adult);
		}

        //--------------- thông tin chi tiết giá vé trẻ em
		if($child > 0){
			$detail_price = get_detail_price($ret_baseprice, 'child', $aircode_in);
			$total_price = ($ticket_type == 1) ? $child * ($detail_price['price'] + $detail_price['tax'] + $detail_price['airport_fee'] + $detail_price['admin_fee'] + $detail_price['fee']) : $price_child;
			$args_child = array(
				'direction' => 1,
				'name' => 'Trẻ em',
				'passenger_type' => 1,
				'quantity' => $child,
				'unit_price' => $detail_price['price'],
				'tax_and_fee' => $detail_price['tax'],
				'airport_fee' => $detail_price['airport_fee'],
				'admin_fee' => $detail_price['admin_fee'],
				'service_fee' => $detail_price['fee'],
				'total_price' => $total_price,
				'booking_id' => $booking['id']
			);
			$sugar->set('EC_Booking_Details', $args_child);
		}

        //--------------- thông tin chi tiết giá vé trẻ sơ sinh
		if($infant > 0){
			$detail_price = get_detail_price($ret_baseprice, 'infant', $aircode_in);
			$total_price = ($ticket_type == 1) ? $infant * ($detail_price['price'] + $detail_price['tax'] + $detail_price['airport_fee'] + $detail_price['admin_fee'] + $detail_price['fee']) : $price_infant;
			$args_infant = array(
				'direction' => 1,
				'name' => 'Trẻ sơ sinh',
				'passenger_type' => 2,
				'quantity' => $infant,
				'unit_price' => $detail_price['price'],
				'tax_and_fee' => $detail_price['tax'],
				'airport_fee' => $detail_price['airport_fee'],
				'admin_fee' => $detail_price['admin_fee'],
				'service_fee' => $detail_price['fee'],
				'total_price' => $total_price,
				'booking_id' => $booking['id']
			);
			$sugar->set('EC_Booking_Details', $args_infant);
		}
		
    }// if
	
	header('Content-type: text/plain');
	$options['where'] = " ec_flight_bookings.id='".$booking['id']."' ";
	$booking_name = $sugar->get('EC_Flight_Bookings', array('id','name'), $options);
	echo $booking_name[0]['name'];
	exit();
	
} else {
	echo 0;
	exit();
}
?>