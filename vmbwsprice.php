<?php
/*
Template Name: vmbwsprice
*/
if( (isset($_GET['adult']) && intval($_GET['adult']) > 0)
	&& (isset($_GET['dep_aircode']) && trim(stripslashes($_GET['dep_aircode'])) != '')
	&& (isset($_GET['dep_price']) && is_numeric($_GET['dep_price']) && (float)$_GET['dep_price'] > 0)
){
	
	if((isset($_GET['ret_aircode']) && trim(stripslashes($_GET['ret_aircode'])) == '')
		|| (isset($_GET['ret_price']) && (!is_numeric($_GET['ret_price']) || (float)$_GET['ret_price'] <= 0))){
			header('Content-type: text/plain');
			echo 0;
			exit();
	}
	
	$price_temp = array();
	$adult_amt = 0;
	$child_amt = 0;
	$infant_amt = 0;
	$tax_amt = 0;
	
	$adult = intval($_GET['adult']);
	$child = intval($_GET['child']);
	$infant = intval($_GET['infant']);
	$dep_aircode = strtoupper(trim(stripslashes($_GET['dep_aircode'])));
	$ret_aircode = strtoupper(trim(stripslashes($_GET['ret_aircode'])));
	
	$aircode_arr = array('VN' => 'vietnamairline', 'BL' => 'jetstar', 'VJ' => 'vietjetair', 'VNA' => 'vietnamairline', 'JET' => 'jetstar', 'VJA' => 'vietjetair');
	$dep_aircode = $aircode_arr[$dep_aircode];
	$ret_aircode = $aircode_arr[$ret_aircode];
	
	$dep_baseprice = (float)$_GET['dep_price'];
	$ret_baseprice = (float)$_GET['ret_price'];
	
	// ONE WAY
	if($dep_aircode != ''){
		$depprice['adult'] = get_detail_price($dep_baseprice,'adult',$dep_aircode);
		$adult_amt += $adult * $depprice['adult']['price'];
		$tax_amt += $adult * ($depprice['adult']['tax'] + $depprice['adult']['airport_fee'] + $depprice['adult']['admin_fee'] + $depprice['adult']['fee']);
		if($child > 0){
			$depprice['child'] = get_detail_price($dep_baseprice,'child',$dep_aircode);
			$child_amt += $child * $depprice['child']['price'];
			$tax_amt += $child * ($depprice['child']['tax'] + $depprice['child']['airport_fee'] + $depprice['child']['admin_fee'] + $depprice['child']['fee']);
		}
		if($infant > 0){
			$depprice['infant'] = get_detail_price($dep_baseprice,'infant',$dep_aircode);
			$infant_amt += $infant * $depprice['infant']['price'];
			$tax_amt += $infant * ($depprice['infant']['tax'] + $depprice['infant']['airport_fee'] + $depprice['infant']['admin_fee'] + $depprice['infant']['fee']);
		}
	}
	
	// RETURN WAY
	if($ret_aircode != ''){
		$retprice['adult'] = get_detail_price($ret_baseprice,'adult',$ret_aircode);
		$adult_amt += $adult * $retprice['adult']['price'];
		$tax_amt += $adult * ($retprice['adult']['tax'] + $retprice['adult']['airport_fee'] + $retprice['adult']['admin_fee'] + $retprice['adult']['fee']);
		if($child > 0){
			$retprice['child'] = get_detail_price($ret_baseprice,'child',$ret_aircode);
			$child_amt += $child * $retprice['child']['price'];
			$tax_amt += $child * ($retprice['child']['tax'] + $retprice['child']['airport_fee'] + $retprice['child']['admin_fee'] + $retprice['child']['fee']);
		}
		if($infant > 0){
			$retprice['infant'] = get_detail_price($ret_baseprice,'infant',$ret_aircode);
			$infant_amt += $infant * $retprice['infant']['price'];
			$tax_amt += $infant * ($retprice['infant']['tax'] + $retprice['infant']['airport_fee'] + $retprice['infant']['admin_fee'] + $retprice['infant']['fee']);
		}
	}
	
	$price_temp['calculate_price']['adult_amt'] = $adult_amt;
	$price_temp['calculate_price']['child_amt'] = $child_amt;
	$price_temp['calculate_price']['infant_amt'] = $infant_amt;
	$price_temp['calculate_price']['tax_amt'] = $tax_amt;
	$price_temp['calculate_price']['total_amt'] = ($adult_amt + $child_amt + $infant_amt + $tax_amt);
	
	header('Content-type: application/json');
	echo json_encode($price_temp);
	exit();
	
} else {
	header('Content-type: text/plain');
	echo 0;
	exit();
}
?>