<?php
/*
Template Name: vmbws
*/
if( (isset($_GET['fltype']) && intval($_GET['fltype']) && (intval($_GET['fltype']) == 1 || intval($_GET['fltype']) == 2))
	&& (isset($_GET['tktype']) && intval($_GET['tktype']) && (intval($_GET['tktype']) == 1 || intval($_GET['tktype']) == 2))
	&& (isset($_GET['dep']) && !empty($_GET['dep']))
	&& (isset($_GET['arv']) && !empty($_GET['arv']))
	&& (isset($_GET['depday']) && intval($_GET['depday']) >= 1 && intval($_GET['depday']) <= 31)
	&& (isset($_GET['depmonth']) && intval($_GET['depmonth']) >= 1 && intval($_GET['depmonth']) <= 12)
	&& (isset($_GET['depyear']) && intval($_GET['depyear']) >= date('Y'))  
	&& (isset($_GET['adult']) && intval($_GET['adult']) > 0)
  ){
	  
	// check return date
	if( !strcmp(trim(stripslashes($_GET['dep'])), trim(stripslashes($_GET['arv'])))
		|| (intval($_GET['fltype']) == 2 && isset($_GET['arvday']) && (intval($_GET['arvday']) < 1 || intval($_GET['arvday']) > 31))
		|| (intval($_GET['fltype']) == 2 && isset($_GET['arvmonth']) && (intval($_GET['arvmonth']) < 1 || intval($_GET['arvmonth']) > 12))
		|| (intval($_GET['fltype']) == 2 && isset($_GET['arvyear']) && intval($_GET['arvyear']) < date('Y'))
		|| (intval($_GET['fltype']) == 2 && strtotime($_GET['depyear'].'-'.$_GET['depmonth'].'-'.$_GET['depday']) > strtotime($_GET['arvyear'].'-'.$_GET['arvmonth'].'-'.$_GET['arvday']))
	){
			header('Content-type: text/plain');
			echo 0;
			exit();
	}
	
	$airline_name = array("VN"=>"Vietnam Airlines","VJ"=>"Vietjet Air","BL"=>"Jetstar","P8"=>"Air Mekong");
	$flight_temp=array();
	$flight=array();
	$flight['flight_type'] = intval($_GET['fltype']); // 1 chiều => 1, khứ hồi => 2
	$flight['ticket_type'] = intval($_GET['tktype']); // vé nội địa => 1, vé quốc tế => 2
	$flight['dep'] = trim(stripslashes($_GET['dep']));
	$flight['arv'] = trim(stripslashes($_GET['arv']));
	$flight['depday'] = str_pad(intval($_GET['depday']), 1, '0', STR_PAD_LEFT);
	$flight['depmonth'] = str_pad(intval($_GET['depmonth']), 1, '0', STR_PAD_LEFT);
	$flight['depyear'] = intval($_GET['depyear']);
	if($flight["flight_type"]==2){
		$flight['arvday'] = str_pad(intval($_GET['arvday']), 1, '0', STR_PAD_LEFT);
		$flight['arvmonth'] = str_pad(intval($_GET['arvmonth']), 1, '0', STR_PAD_LEFT);
		$flight['arvyear'] = intval($_GET['arvyear']);
	}
	$flight["deptime"]=$flight["depday"]."/".$flight["depmonth"]."/".$flight["depyear"];
    $flight["arvtime"]=$flight["arvday"]."/".$flight["arvmonth"]."/".$flight["arvyear"];
	$flight['adult'] = intval($_GET['adult']);
	$flight['child'] = intval($_GET['child']);
	$flight['infant'] = intval($_GET['infant']);
	
	 // international
	 if($flight['ticket_type'] == 2 && (!in_array($flight['dep'],$GLOBALS['CODECITY']) || !in_array($flight['arv'],$GLOBALS['CODECITY']))){
		  $cookiefile=dirname(__FILE__);
		  $cookiefile .= '/flight_config/tmpcookie/cookiefile-inter.txt';
		  include(TEMPLATEPATH.'/flight_config/clsinter.php');
		  $clflight=new clsflight();
		  $clflight->setReturn($flight["flight_type"]); #NEU 1 CHIEU SET ONEWAY=1, 2 CHIEU DE LA ONEWAY=0
		  $clflight->setCookie($cookiefile);
		  $clflight->setDepday($flight["depday"]);
		  $clflight->setDepmonth($flight["depmonth"]);
		  $clflight->setDepyear($flight["depyear"]);
		  if($flight["flight_type"]==2){
			  $clflight->setRetday($flight["arvday"] );
			  $clflight->setRetmonth($flight["arvmonth"]);
			  $clflight->setRetyear($flight["arvyear"]);
		  }
		  $clflight->setDep($flight["dep"]);
		  $clflight->setArv($flight["arv"]);
		  $clflight->setAdult($flight["adult"]);
		  $clflight->setChild($flight["child"]);
		  $clflight->setInfant($flight["infant"]);
		  $clflight->getFlight();
  
		  $arr_flight = array();
		  $arr_flight = $clflight->rs;  
		  
		  $total_flight=count($arr_flight);
  
		  for($i=0;$i<$total_flight;$i++){
			  $tcode=mktime(date('Y-m-d H:i:s')).$i;
			  $arr_flight[$i]["id"]=$tcode;
		  }
		  $flag_count=count($arr_flight);
		  if($flag_count==0){
			  header('Content-type: text/plain');
			  echo 0;
			  exit();
		  }
		  
		  for ($i=0;$i<$total_flight;$i++){
			  $item=$arr_flight[$i];
			  if($flight["flight_type"]==1){
				  $int=$item["int"];
				  
				  // chi tiết hành trình
				  $lastdate="";
				  $int_arr = array();
				  for($j=0;$j<count($int);$j++){
					  $int_detail=$int[$j];
					  if($j==0)
						  $lastdate=$flight["deptime"];
  
					  $aircode=explode(" ",$int_detail["airline_code"]);
					  $aircode=$aircode[0];
					  $int_detail["dep_time"]=str_replace(".",":",$int_detail["dep_time"]);
					  $int_detail["arv_time"]=str_replace(".",":",$int_detail["arv_time"]);
					  $int_depdate=$lastdate;
					  $lastdate=getNextDate($int_detail["dep_time"],$int_detail["arv_time"],$int_depdate);
  
					  $int_arvdate=$lastdate;
  
					  if($int_detail["layover_time"]){
						  $tempNtime=$int[$j+1]["dep_time"];
						  $lastdate=getNextDate($int_detail["arv_time"],$tempNtime,$int_arvdate);
					  }
  
					  $dep_day=getDay($int_depdate);
					  $arv_day=getDay($int_arvdate);
					  
					  $int_arr[] = array(
					  	 'airline_code' => $aircode
					  	,'dep_time' => $int_detail["dep_time"]
						,'dep_date' => $int_depdate
						,'dep_airport' => $int_detail["dep_airport"]
						,'dep_country' => getCountryByCity($int_detail["dep"])
						,'total_time' => $int_detail["total_time"]
						,'flight_no' => $int_detail["airline_code"]
						,'airline_name' => $int_detail["airline"]
						,'arv_time' => $int_detail["arv_time"]
						,'arv_date' => $int_arvdate
						,'arv_airport' => $int_detail["arv_airport"]
						,'arv_country' => getCountryByCity($int_detail["arv"])
						,'layover_time' => ($int_detail["layover_time"] == '' ? 'NULLSTRING' : $int_detail["layover_time"])
					  );
				  }// end chi tiết hành trình
				  
				  $flight_temp['oneway'][] = array(
				  	 'id' => $item['id']
				  	,'dep_city_name' => getCityName($flight["dep"])
					,'arv_city_name' => getCityName($flight["arv"])
					,'depart_dep_time' => str_replace(".",":",$item["dep_time"])
					,'depart_arv_time' => str_replace(".",":",$item["arv_time"])
					,'depart_total_time' => $item["total_time"] // thời gian bay
					,'depart_stop' => $item['stop']
					,'depart_int' => $int_arr
					,'price_total' => $item["price_total"]
					,'price_adult' => $item["price_adult"]
					,'price_child' => $item["price_child"]
					,'price_infant' => $item["price_infant"]
					,'price_tax' => $item["price_tax"]
				  );
			  } else { // END ONE WAY
			  	
				  $itemdep=$item["dep"];
				  $itemarv=$item["arv"];
				  $intdep=$itemdep["int"];
				  $intarv=$itemarv["int"];
				  
				  // chi tiết hành trình chiều đi
				  $lastdate="";
				  for($j=0;$j<count($intdep);$j++){
					  $int_detail=$intdep[$j];
					  if($j==0)
						  $lastdate=$flight["deptime"];

					  $aircode=explode(" ",$int_detail["airline_code"]);
					  $aircode=$aircode[0];
					  $int_detail["dep_time"]=str_replace(".",":",$int_detail["dep_time"]);
					  $int_detail["arv_time"]=str_replace(".",":",$int_detail["arv_time"]);
					  $int_depdate=$lastdate;
					  $lastdate=getNextDate($int_detail["dep_time"],$int_detail["arv_time"],$int_depdate);

					  $int_arvdate=$lastdate;

					  if($int_detail["layover_time"]){
						  $tempNtime=$intdep[$j+1]["dep_time"];
						  $lastdate=getNextDate($int_detail["arv_time"],$tempNtime,$int_arvdate);
					  }

					  $dep_day=getDay($int_depdate);
					  $arv_day=getDay($int_arvdate);
					  
					  $depart_int_arr[$item["id"]][] = array(
					  	 'airline_code' => $aircode
						,'dep_time' => $int_detail["dep_time"]
						,'dep_date' => $int_depdate
						,'dep_airport' => $int_detail["dep_airport"]
						,'dep_country' => getCountryByCity($int_detail["dep"])
						,'total_time' => $int_detail["total_time"]
						,'flight_no' => $int_detail["airline_code"]
						,'airline_name' => $int_detail["airline"]
						,'arv_time' => $int_detail["arv_time"]
						,'arv_date' => $int_arvdate
						,'arv_airport' => $int_detail["arv_airport"]
						,'arv_country' => getCountryByCity($int_detail["arv"])
						,'layover_time' => ($int_detail["layover_time"] == '' ? 'NULLSTRING' : $int_detail["layover_time"])
					  );
				  }// end chi tiết hành trình chiều đi
				  
				  $lastdate="";
				  for($j=0;$j<count($intarv);$j++){
					  $int_detail=$intarv[$j];
					  if($j==0)
						  $lastdate=$flight["arvtime"];

					  $aircode=explode(" ",$int_detail["airline_code"]);
					  $aircode=$aircode[0];
					  $int_detail["dep_time"]=str_replace(".",":",$int_detail["dep_time"]);
					  $int_detail["arv_time"]=str_replace(".",":",$int_detail["arv_time"]);
					  $int_depdate=$lastdate;
					  $lastdate=getNextDate($int_detail["dep_time"],$int_detail["arv_time"],$int_depdate);

					  $int_arvdate=$lastdate;

					  if($int_detail["layover_time"]){
						  $tempNtime=$intarv[$j+1]["dep_time"];
						  $lastdate=getNextDate($int_detail["arv_time"],$tempNtime,$int_arvdate);
					  }

					  $dep_day=getDay($int_depdate);
					  $arv_day=getDay($int_arvdate);
					  
					  $return_int_arr[$item["id"]][] = array(
					  	 'airline_code' => $aircode
						,'dep_time' => $int_detail["dep_time"]
						,'dep_date' => $int_depdate
						,'dep_airport' => $int_detail["dep_airport"]
						,'dep_country' => getCountryByCity($int_detail["dep"])
						,'total_time' => $int_detail["total_time"]
						,'flight_no' => $int_detail["airline_code"]
						,'airline_name' => $int_detail["airline"]
						,'arv_time' => $int_detail["arv_time"]
						,'arv_date' => $int_arvdate
						,'arv_airport' => $int_detail["arv_airport"]
						,'arv_country' => getCountryByCity($int_detail["arv"])
						,'layover_time' => ($int_detail["layover_time"] == '' ? 'NULLSTRING' : $int_detail["layover_time"])
					  );
				  } // end chi tiết hành trình chiều về
				  
				  $flight_temp['returnway'][] = array(
				  	  'id' => $item["id"]
				  	 ,'dep_city_name' => getCityName($flight["dep"])
					 ,'arv_city_name' => getCityName($flight["arv"])
					 ,'depart_dep_time' => str_replace(".",":",$itemdep["dep_time"])
					 ,'depart_arv_time' => str_replace(".",":",$itemdep["arv_time"])
					 ,'depart_total_time' => $itemdep["total_time"]
					 ,'depart_stop' => $itemdep["stop"]
					 ,'depart_int' => $depart_int_arr[$item["id"]]
					 ,'return_dep_time' => str_replace(".",":",$itemarv["dep_time"])
					 ,'return_arv_time' => str_replace(".",":",$itemarv["arv_time"])
					 ,'return_total_time' => $itemarv["total_time"]
					 ,'return_stop' => $itemarv["stop"]
					 ,'return_int' => $return_int_arr[$item["id"]]
					 ,'price_total' => $item["price_total"]
					 ,'price_adult' => $item["price_adult"]
					 ,'price_child' => $item["price_child"]
					 ,'price_infant' => $item["price_infant"]
					 ,'price_tax' => $item["price_tax"]
				  );
			  }// END RETURN WAY
		  }// end for 
		  
		  header('Content-type: application/json');
		  echo json_encode($flight_temp);
		  exit();
		  
	 } // END INTERNATIONAL
	
	 $flag_count=0; #xet xem co chuyen bay nao ko;
	 $flag_dep=0; #xet xem co chuyen di nao ko;
	 $flag_arv=0; #xet xem co chuyen ve nao ko;
	 
	 $dirrand=get_stylesheet_directory()."/flight_config/tmpcookie/".time()."_".rand(1,100);
	 $cookievn=$dirrand."_vn.txt";
	 $cookievj=$dirrand."_vj.txt";
	 $cookiejs=$dirrand."_js.txt";
	 @$fhandle=fopen($cookievn,"w");
	 fclose($fhandle);
	 @$fhandle=fopen($cookievj,"w");
	 fclose($fhandle);
	 @$fhandle=fopen($cookiejs,"w");
	 fclose($fhandle);
	 if(file_exists($cookievn))
	 	@chmod($cookievn,0777);
	 if(file_exists($cookievj))
	 	@chmod($cookievj,0777);
	 if(file_exists($cookiejs))
	 	@chmod($cookiejs,0777);
		
	include(TEMPLATEPATH.'/flight_config/clsflight.php');
	$clflight=new clsflight();
	$clflight->setOneway($flight["flight_type"]==1?1:0); #NEU 1 CHIEU SET ONEWAY=1, 2 CHIEU DE LA ONEWAY=0
	$clflight->setCookieVn($cookievn);
	$clflight->setCookiejs($cookiejs);
	$clflight->setCookieVj($cookievj);
	$clflight->setDepday($flight["depday"]);
	$clflight->setDepmonth($flight["depmonth"]);
	$clflight->setDepyear($flight["depyear"]);
	if($flight["flight_type"]==2){
		$clflight->setRetday($flight["arvday"] );
		$clflight->setRetmonth($flight["arvmonth"]);
		$clflight->setRetyear($flight["arvyear"]);
	}
	$clflight->setDep($flight["dep"]);
	$clflight->setArv($flight["arv"]);
	$clflight->getFlight();

	$arr_flight = array();
	$arr_flight = $clflight->rs;

	#XOA FILE COOKIE
	if(file_exists($cookievj))
		unlink($cookievj);
	if(file_exists($cookiejs))
		unlink($cookiejs);
	if(file_exists($cookievn))
		unlink($cookievn);
		
	if($flight["flight_type"]==1){
		if(count($arr_flight["jetstar"])>0){
			/*Loai bo nhung chuyen bay duoi 900k*/
			foreach($arr_flight["jetstar"] as $key=>$value){
				if($value["price"]<900000)
				{
					unset($arr_flight["jetstar"][$key]);
				}
			}
		}
		if(count($arr_flight["vietnamairline"])>0 || count($arr_flight["jetstar"])>0 || count($arr_flight["airmekong"])>0 || count($arr_flight["vietjetair"])>0)
			$flag_count++;
	}

	if($flight["flight_type"]==2){
		$tempj=0;
		$tempi=0;
		if(count($arr_flight["vietnamairline"])>0){
			$depart[$tempj++]["vn"]=$arr_flight["vietnamairline"]["dep"];
			$return[$tempi++]["vn"]=$arr_flight["vietnamairline"]["ret"];

			$flag_dep+=count($arr_flight["vietnamairline"]["dep"]);
			$flag_arv+=count($arr_flight["vietnamairline"]["ret"]);

			$flag_count++;
		}
		if(count($arr_flight["jetstar"])>0){
			/*Loai bo nhung chuyen bay duoi 900k*/
			if(count($arr_flight["jetstar"]["dep"]) > 0){
				foreach($arr_flight["jetstar"]["dep"] as $key=>$value){
					if($value["price"]<900000)
					{
						unset($arr_flight["jetstar"]["dep"][$key]);
					}
				}
			}
			if(count($arr_flight["jetstar"]["ret"]) > 0){
				foreach($arr_flight["jetstar"]["ret"] as $key=>$value){
					if($value["price"]<900000)
					{
						unset($arr_flight["jetstar"]["ret"][$key]);
					}
				}
			}

			$depart[$tempj++]["js"]=$arr_flight["jetstar"]["dep"];
			$return[$tempi++]["js"]=$arr_flight["jetstar"]["ret"];

			$flag_dep+=count($arr_flight["jetstar"]["dep"]);
			$flag_arv+=count($arr_flight["jetstar"]["ret"]);

			$flag_count++;
		}
		if(count($arr_flight["airmekong"])>0){
			$depart[$tempj++]["arm"]=$arr_flight["airmekong"]["dep"];
			$return[$tempi++]["arm"]=$arr_flight["airmekong"]["ret"];

			$flag_dep+=count($arr_flight["airmekong"]["dep"]);
			$flag_arv+=count($arr_flight["airmekong"]["ret"]);

			$flag_count++;
		}
		if(count($arr_flight["vietjetair"])>0){
			$depart[$tempj++]["vj"]=$arr_flight["vietjetair"]["dep"];
			$return[$tempi++]["vj"]=$arr_flight["vietjetair"]["ret"];

			$flag_dep+=count($arr_flight["vietjetair"]["dep"]);
			$flag_arv+=count($arr_flight["vietjetair"]["ret"]);

			$flag_count++;
		}
	}
	
    $flight_code=mktime(date('Y-m-d H:i:s'));
	$temp_code=0;
	
	
	// flight not found
	if(($flight["flight_type"]==1 && $flag_count==0) || ($flight["flight_type"]==2 && ($flag_dep==0 || $flag_arv==0)) ){
		header('Content-type: text/plain');
		echo 0;
		exit();
	}
	
	// output result
	if($flight["flight_type"]==1){
		foreach ($arr_flight as $key=>$items){ 
			foreach($items as $item){
				$airline_code="VN";
				switch($key){
					case "vietnamairline":
						$airline_code="VN";
						break;
					case "jetstar":
						$airline_code="BL";
						break;
					case "airmekong":
						$airline_code="P8";
						break;
					case "vietjetair":
						$airline_code="VJ";
						break;
				}
				if($item['note'])
					$note=$item['note'];
				else
					$note="";
				if($item["price"]==0) continue;
				$flight_temp['depart'][] = array(
					 'id' => $flight_code.$temp_code
					,'airline_code' => $airline_code
					,'airline_name' => $airline_name[$airline_code]
					,'flight_no' => $item["flightno"]
					,'class' => $item["class"]
					,'dep_time' => $item["deptime"]
					,'arv_time' => $item["arvtime"]
					,'price' => $item["price"]
					,'stop' => $item["stop"]
					,'note' => $note
				);
				$temp_code++;
			}
		}
	}// ONE WAY
	
	
	if($flight["flight_type"]==2){
		if($depart){
		  for($i=0;$i<count($depart);$i++){
			foreach($depart[$i] as $key=>$items){
			   if(count($items)>0){ 
				  foreach($items as $item){
					$airline_code="VN";
					switch($key){
						case "vn":
							$airline_code="VN";
							break;
						case "js":
							$airline_code="BL";
							break;
						case "arm":
							$airline_code="P8";
							break;
						case "vj":
							$airline_code="VJ";
							break;
						default:
							$airline_code=$key;
							break;
					}
					if($item['note'])
						$note=$item['note'];
					else
						$note=""; 
					if($item["price"]==0) continue;
					$flight_temp['depart'][] = array(
						 'id' => $flight_code.$temp_code
						,'airline_code' => $airline_code
						,'airline_name' => $airline_name[$airline_code]
						,'flight_no' => $item["flightno"]
						,'class' => $item["class"]
						,'dep_time' => $item["deptime"]
						,'arv_time' => $item["arvtime"]
						,'price' => $item["price"]
						,'stop' => $item["stop"]
						,'note' => $note
					);
					$temp_code++;
				  }
			   }
			}
		  }
		} // depart
		
		if($return){
            for($i=0;$i<count($return);$i++){
			  foreach($return[$i] as $key => $items){
				  if(count($items)>0){ 
					foreach($items as $item){
					  $airline_code="VN";
					  switch($key){
						  case "vn":
							  $airline_code="VN";
							  break;
						  case "js":
							  $airline_code="BL";
							  break;
						  case "arm":
							  $airline_code="P8";
							  break;
						  case "vj":
							  $airline_code="VJ";
							  break;
						  default:
							  $airline_code=$key;
							  break;
					  }
					  if($item['note'])
						  $note=$item['note'];
					  else
						  $note="";
					  if($item["price"]==0) continue;
					  $flight_temp['return'][] = array(
					  	   'id' => $flight_code.$temp_code
						  ,'airline_code' => $airline_code
						  ,'airline_name' => $airline_name[$airline_code]
						  ,'flight_no' => $item["flightno"]
						  ,'class' => $item["class"]
						  ,'dep_time' => $item["deptime"]
						  ,'arv_time' => $item["arvtime"]
						  ,'price' => $item["price"]
						  ,'stop' => $item["stop"]
						  ,'note' => $note
					  );
					  $temp_code++;
					}
				  }
			  }
			}
		} // return
	}// RETURN WAY
	
	header('Content-type: application/json');
	echo json_encode($flight_temp);
	exit();
	
} else {
	
	header('Content-type: text/plain');
	echo 0;
	exit();
	
}
?>