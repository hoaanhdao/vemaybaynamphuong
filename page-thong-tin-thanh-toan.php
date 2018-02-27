<?php
$siteurl = get_bloginfo('siteurl');
if((empty($_SESSION['dep']) && empty($_SESSION['interfinishflight'])) || empty($_SESSION['search'])){
  header('Location:'.$siteurl);
  exit();
}

if(!empty($_SESSION['booking'])){
  $bkid=$_SESSION['booking']['id'];
  if($_SESSION[$bkid]['saved']==true){
	  header("Location:"._page("complete"));
	  exit();
  }
}

if(isset($_POST['sm_transfer_method']) ||
   isset($_POST['sm_office_method']) ||
   isset($_POST['sm_home_method']) ||
   isset($_POST['sm_home_method']) ||
   isset($_POST['sm_nganluong_method'])
){
  
  $payment_type='';
  if(isset($_POST['sm_transfer_method'])) $payment_type=3;
  elseif(isset($_POST['sm_office_method'])) $payment_type=2;
  elseif(isset($_POST['sm_home_method'])) $payment_type=1;
  elseif(isset($_POST['sm_nganluong_method'])) $payment_type=4;
  
  $way_flight = (int)$_SESSION['search']['way_flight'];
  $source = $_SESSION['search']['source'];
  $destination = $_SESSION['search']['destination'];
  $depart = $_SESSION['search']['depart'];   // dd/mm/yyyy
  $return = $_SESSION['search']['return'] ;
  $adults = (int)$_SESSION['search']['adult'];
  $children = (int)$_SESSION['search']['children'];
  $infants = (int)$_SESSION['search']['infant'];
  
  require(TEMPLATEPATH.'/flight_config/sugarrest/sugar_rest.php');
  $sugar = new Sugar_REST();
  $error = $sugar->get_error();
  $booking_id = array();
	  
  if($_SESSION['search']['isinter'] && !empty($_SESSION['contact']) && !empty($_SESSION['dep']) && !empty($_SESSION['pax'])){
		/**************************
  				VE QUOC TE
   		 **************************/
	  	$args_booking=array();
        /*Ghi Thong Tin Booking va Thong Tin Lien He*/
        $args_booking = $_SESSION['contact'];
        $args_booking['payment_type']=$payment_type;
		$args_booking['ticket_type']='2';
		$args_booking['ip_address']=get_ip_address_from_client();
		$args_booking['user_agent']=$_SERVER['HTTP_USER_AGENT'];
        $booking_id = $sugar->set("EC_Flight_Bookings",$args_booking);

        /*Ghi Thong Tin Hanh khach*/
        foreach($_SESSION['pax'] as $pass){
            $passenger_arr=array();
            $passenger_arr=$pass;
            $passenger_arr['booking_id']= $booking_id['id'];
            $sugar->set("EC_Booking_Passengers",$passenger_arr);
        }

        #GHI HANH TRINH CHUYEN DI
		$flight = $_SESSION['dep'];
        foreach($flight['outbound_detail'] as $route){
			if($route['type']=='route'){
				$args_itinerary=array();
				$args_itinerary['name']='route';
				$args_itinerary['is_layover']=0;
				$args_itinerary['direction']='0';
				$args_itinerary['airline_code']=$route['aircode'];
				$args_itinerary['flight_number']=$route['flightno'];
				$args_itinerary['flight_duration']=$route['duration'];
				$args_itinerary['ticket_class']=$route['flight_class'];
				$args_itinerary['departure']=$route['depcode'];
				$args_itinerary['arrival']=$route['arvcode'];
				$args_itinerary['departure_date']=date('Y-m-d H:i:s',strtotime($route['deptime']));
				$args_itinerary['arrival_date']=date('Y-m-d H:i:s',strtotime($route['arvtime']));
				$args_itinerary['booking_id']=$booking_id['id'];
			} else {
				$args_itinerary=array();
				$args_itinerary['name']='layover';
				$args_itinerary['is_layover']=1;
				$args_itinerary['direction']='0';
				$args_itinerary['airline_code']='';
				$args_itinerary['flight_number']='';
				$args_itinerary['flight_duration']=$route['duration'];
				$args_itinerary['ticket_class']='';
				$args_itinerary['departure']=$route['depcode'];
				$args_itinerary['arrival']='';
				$args_itinerary['departure_date']=date('Y-m-d H:i:s',strtotime($route['deptime']));
				$args_itinerary['arrival_date']=date('Y-m-d H:i:s',strtotime($route['arvtime']));
				$args_itinerary['booking_id']=$booking_id['id'];
			}
            $itinerary_id_dep = $sugar->set("EC_Booking_Itineraries",$args_itinerary);
        }
		## HANH TRINH LUOT VE
		if($way_flight==0){
			foreach($flight['inbound_detail'] as $route){
				if($route['type']=='route'){
					$args_itinerary=array();
					$args_itinerary['name']='route';
					$args_itinerary['is_layover']=0;
					$args_itinerary['direction']='1';
					$args_itinerary['airline_code']=$route['aircode'];
					$args_itinerary['flight_number']=$route['flightno'];
					$args_itinerary['flight_duration']=$route['duration'];
					$args_itinerary['ticket_class']=$route['flight_class'];
					$args_itinerary['departure']=$route['depcode'];
					$args_itinerary['arrival']=$route['arvcode'];
					$args_itinerary['departure_date']=date('Y-m-d H:i:s',strtotime($route['deptime']));
					$args_itinerary['arrival_date']=date('Y-m-d H:i:s',strtotime($route['arvtime']));
					$args_itinerary['booking_id']=$booking_id['id'];
				} else {
					$args_itinerary=array();
					$args_itinerary['name']='layover';
					$args_itinerary['is_layover']=1;
					$args_itinerary['direction']='1';
					$args_itinerary['airline_code']='';
					$args_itinerary['flight_number']='';
					$args_itinerary['flight_duration']=$route['duration'];
					$args_itinerary['ticket_class']='';
					$args_itinerary['departure']=$route['depcode'];
					$args_itinerary['arrival']='';
					$args_itinerary['departure_date']=date('Y-m-d H:i:s',strtotime($route['deptime']));
					$args_itinerary['arrival_date']=date('Y-m-d H:i:s',strtotime($route['arvtime']));
					$args_itinerary['booking_id']=$booking_id['id'];
				}
				$sugar->set("EC_Booking_Itineraries",$args_itinerary);
			}
		}

        # LƯU CHI TIẾT ĐẶT VÉ - NGƯỜI LỚN
		$exchange_rate = (int)get_option("opt_exchange_rate");
		$exchange_rate = (!$exchange_rate || $exchange_rate <= 0) ? 1 : $exchange_rate;
		// avg taxes
		$taxes = ($flight['taxes'] * $exchange_rate) / ($adults + $children); // thuế trung bình
		// adult
		$price_adult = ($flight['price_adult'] / $adults) * $exchange_rate;
		$tax_adult = $taxes;
		$svfee_adult = (int)get_option("opt_inter_adt_svfee");
		$amount_adult = ($price_adult + $tax_adult + $svfee_adult) * $adults;
		// child
		$price_child = ($flight['price_child'] / $children) * $exchange_rate;
		$tax_child = $taxes;
		$svfee_child = (int)get_option("opt_inter_chd_svfee");
		$amount_child = ($price_child + $tax_child + $svfee_child) * $children;
		// infant
		$price_infant = ($flight['price_infant'] / $infants) * $exchange_rate;
		$tax_infant = 0;
		$svfee_infant = (int)get_option("opt_inter_inf_svfee");
		$amount_infant = ($price_infant + $tax_infant + $svfee_infant) * $infants;
		// total
		$delivery_fee = (int)get_option("opt_delivery_fee");
		$total_amount = $amount_adult + $amount_child + $amount_infant;
		
        $args_detail = array();
		$args_detail['name']='Người lớn';
		$args_detail['passenger_type']='0';
		$args_detail['quantity']=$adults;
		$args_detail['unit_price']=$price_adult;
		$args_detail['tax_and_fee']=$tax_adult;
		$args_detail['direction']='0';
		$args_detail['service_fee']=$svfee_adult;
		$args_detail['total_price']=$amount_adult;
		$args_detail['booking_id']=$booking_id['id'];
		$sugar->set("EC_Booking_Details",$args_detail);
		if($children>0){
			$args_detail = array();
			$args_detail['name']='Trẻ em';
			$args_detail['passenger_type']='1';
			$args_detail['quantity']=$children;
			$args_detail['unit_price']=$price_child;
			$args_detail['tax_and_fee']=$tax_child;
			$args_detail['direction']='0';
			$args_detail['service_fee']=$svfee_child;
			$args_detail['total_price']=$amount_child;
			$args_detail['booking_id']=$booking_id['id'];
			$sugar->set("EC_Booking_Details",$args_detail);
		}
		if($infants>0){
			$args_detail = array();
			$args_detail['name']='Em bé';
			$args_detail['passenger_type']='2';
			$args_detail['quantity']=$infants;
			$args_detail['unit_price']=$price_infant;
			$args_detail['tax_and_fee']=$tax_infant;
			$args_detail['direction']='0';
			$args_detail['service_fee']=$svfee_infant;
			$args_detail['total_price']=$amount_infant;
			$args_detail['booking_id']=$booking_id['id'];	
			$sugar->set("EC_Booking_Details",$args_detail);
		}
		
		$args_booking_update=array();
		$args_booking_update['other_fee']=($payment_type == 1 ? $delivery_fee : 0);
		$args_booking_update['subtotal_amount']=$total_amount;
		$args_booking_update['total_amount']=$args_booking_update['subtotal_amount']+$args_booking_update['other_fee'];
        $args_booking_update['id']=$booking_id['id'];
        $sugar->set("EC_Flight_Bookings",$args_booking_update);
		
        $_SESSION['booking'] = $booking_id;
        $_SESSION[$booking_id['id']]['saved']=true;
        header("Location:"._page("complete"));
        exit();
	  
  } elseif(!$_SESSION['search']['isinter'] && !empty($_SESSION['contact']) && !empty($_SESSION['dep_flight']) && !empty($_SESSION['pax'])) {
	  /**************************
  			VE NOI DIA
   	   **************************/
	  $args_booking=array();
	  /*Ghi Thong Tin Booking va Thong TIn Lien He*/
	  $args_booking = $_SESSION['contact'];
	  $args_booking['payment_type']=$payment_type;
	  $args_booking['ip_address']=get_ip_address_from_client();
	  $args_booking['user_agent']=$_SERVER['HTTP_USER_AGENT'];
	  $booking_id = $sugar->set("EC_Flight_Bookings",$args_booking);
	  /*Ghi Thong Tin Hanh Trinh Chuyen Di*/
	  $args_itinerary=array();
	  $args_itinerary = $_SESSION['dep_flight'];
	  $args_itinerary['booking_id']=$booking_id['id'];
	  $itinerary_id_dep = $sugar->set("EC_Booking_Itineraries",$args_itinerary);
	  /*Ghi Hanh Trinh chuyen ve*/
	  if($way_flight == 0){
		  $args_itinerary_ret=array();
		  $args_itinerary_ret=$_SESSION['ret_flight'];
		  $args_itinerary_ret['booking_id']=$booking_id['id'];
		  $itinerary_id_ret = $sugar->set("EC_Booking_Itineraries",$args_itinerary_ret);
	  }
	  // Lưu thông tin hành khách
	  for($i=0;$i<count($_SESSION['pax']);$i++){
		  $passenger_arr=array();
		  $passenger_arr=$_SESSION['pax'][$i];
		  $passenger_arr['booking_id']=$booking_id['id'];
		  $passenger_info[]=$sugar->set("EC_Booking_Passengers",$passenger_arr);
	  }
	  #########LUOT DI############
	  // Lưu chi tiết đặt vé - Nguoi Lớn
	  $arrticket_adult=array();
	  $arrticket_adult=$_SESSION['card']['dep']['adult'];
	  $arrticket_adult['booking_id']=$booking_id['id'];
	  $articket_adult_id =  $sugar->set("EC_Booking_Details",$arrticket_adult);
	  if($children!=0){
		  $arrticket_child=array();
		  $arrticket_child=$_SESSION['card']['dep']['child'];
		  $arrticket_child['booking_id']=$booking_id['id'];
		  $articket_child_id =  $sugar->set("EC_Booking_Details",$arrticket_child);
	  }
	  if($infants != 0){
		  $arrticket_inf=array();
		  $arrticket_inf=$_SESSION['card']['dep']['infant'];
		  $arrticket_inf['booking_id']=$booking_id['id'];
		  $articket_inf_id=$sugar->set("EC_Booking_Details",$arrticket_inf);
	  }
	  if($way_flight == 0){
		  $arrticket_adult_ret=array();
		  $arrticket_adult_ret=$_SESSION['card']['ret']['adult'];
		  $arrticket_adult_ret['booking_id']=$booking_id['id'];
		  $articket_adult_id =  $sugar->set("EC_Booking_Details",$arrticket_adult_ret);
		  if($children!=0){
			  $arrticket_child_ret=array();
			  $arrticket_child_ret=$_SESSION['card']['ret']['child'];
			  $arrticket_child_ret['booking_id']=$booking_id['id'];
			  $articket_child_id =  $sugar->set("EC_Booking_Details",$arrticket_child_ret);
		  }
		  if($infants != 0){
			  $arrticket_inf_ret=array();
			  $arrticket_inf_ret=$_SESSION['card']['ret']['infant'];
			  $arrticket_inf_ret['booking_id']=$booking_id['id'];
			  $articket_inf_id=$sugar->set("EC_Booking_Details",$arrticket_inf_ret);
		  }
	  }
	  $args_booking_update=array();
	  $args_booking_update=$_SESSION['card']['price'];
	  $args_booking_update['id']=$booking_id['id'];
	  $booking_update = $sugar->set("EC_Flight_Bookings",$args_booking_update);
	  $_SESSION['booking'] = $booking_id;
	  $_SESSION[$booking_id['id']]['saved']=true;
	  header("Location:"._page("complete"));
	  exit();
	}
} // end if submit
?>
 <?php
get_header();
?>
<div class="row">
<div class="block">
	<ul id="progressbar" class="hidden-xs">
		<li><span class="pull-left">1. Chọn hành trình</span>
			<div class="bread-crumb-arrow"></div>
		</li>
		<li><span class="pull-left">2. Thông tin hành khách</span>
			<div class="bread-crumb-arrow"></div>
		</li>
		<li class="current"><span class="pull-left">3. Thanh toán</span>
			<div class="bread-crumb-arrow"></div>
		</li>
		<li><span class="hidden-xs">4. Hoàn tất</span></li>
	</ul>
	<div class="gap-small"></div>
  <div class="col-md-8  sidebar-separator" id="colLeftNoBorder">
    
  
  <div id="mainDisplay">
	  <div id="ctleft" class="payment">
  
		  <p style="padding: 10px;line-height: 18px;font-size: 13px;">
	    Sau khi chọn vui lòng nhấn "<strong style="font-size: 16px; color: #f60;">Đặt vé</strong>". Bạn gọi số tổng đài <strong style="font-size: 16px; color: #f60;">1900 63 6060</strong> để được trợ giúp. Việc xác thực thông tin là cần thiết nhằm tránh sai sót khi ra sân bay. 
		  </p>
		  
		  <form action="<?php echo  _page("payment"); ?>" method="post" id="frm_selectpaymentmethod" >
          
          	  <!-- THANH TOAN CHUYỂN KHOẢN -->
			  <div class="methods">
				  <div class="methods-header transfer checked">
					  
					  <label for="method_transfer"  class="methods-header radio radio-inline checked active"><input type="radio" id="method_transfer" name="radio" />
						  <span>Chuyển khoản qua ngân hàng</span>
                            <p>Đây là hình thức thanh toán tối ưu nhất và không mất phí. Quý khách có thể chọn lựa tài khoản ngân hàng chuyển đến cùng hệ thống với cái đang sử dụng nhằm đảm bảo tiền được chuyển sang nhanh.
                        <br/><strong>Xem danh sách tài khoản ngân hàng <a href="<?php bloginfo('url'); ?>/lien-he" target="_blank"><span style="text-decoration:none; color:#ffc800;"> tại đây</span></a> .</strong>
                        </p>
					  </label>
				  </div>
				  <div class="methods-content" id="content_transfer" style="display:block">
					   <h3>Lưu ý</h3>
                    <p style="padding-left: 10px;">
                    Khi thực hiện chuyển khoản xin vui lòng ghi rõ mã đơn hàng, tên người liên hệ đặt vé. Sau khi hoàn tất việc CK vui lòng nhắn tin báo có đến số này :<strong style="font-size: 16px; color: #f60;"> 091 30 30 802</strong>
					<br /> Vd : JS3213154, Nguyen Tuan Anh, HCM Hanoi
                     <br /> Mã đơn hàng JS3213154, người liên hệ đặt vé là Nguyen Tuan Anh, hành trình Sài Gòn - Hà Nội.
                     <br /><br /> Đơn giản nhất là quý khách gọi điện vào đây : <strong style="font-size: 16px; color: #f60;"><?php echo get_option('opt_hotline'); ?> - <?php echo get_option('opt_hotline2'); ?></strong>
                      <br /> Việc thông báo CK chậm trễ hoặc quên thông báo sẽ dẫn đến hủy đặt chỗ booking làm tăng giá vé. Xin quý khách vui lòng lưu ý việc này. Các tranh cãi về sau sẽ không được hãng giải quyết.<br/> <br/>
                       <span style="font-weight:bold">Vé khuyến mãi không hoàn đổi !!</span>
                    </p>
					  <p class="select">
						  <input type="submit" name="sm_transfer_method" value="Đặt vé" class="selectpaymentmethod button" />
						  <span class="waiting">Hệ thống đang xử lý...</span>
					  </p>
				  </div>
  
			  </div><!--.methods-->
  
			  <!-- THANH TOAN TẠI VĂN PHÒNG -->
			  <div class="methods">
				  <div class="methods-header office">
					 
					  <label for="method_office" class="methods-header radio radio-inline"> <input type="radio" id="method_office" name="radio" />
						  <span>Đến văn phòng thanh toán</span>
                            
						   <p>Quý khách ghé qua địa chỉ Số 27 Đường D1, P.25, Q. Bình Thạnh, TP.HCM (Khu vực cầu vượt Văn Thánh - từ Hàng xanh hướng ra cầu SG trên đường Điện Biên Phủ sẽ gặp đường D1 cắt ngang, quẹo trái khoảng 200m, đối diện và xéo góc tòa nhà đang xây dựng SSG là Vé máy bay Nam Phương</p>
               
					   
					  
					  </label>
				  </div>
				  <div class="methods-content" id="content_office">
					   <p style="padding: 10px 0; font-weight:bold"> Vé khuyến mãi không hoàn đổi !!	</p>
					
					  <div class="clearfix"></div>
  
					  <p class="select">
						  <input type="submit" name="sm_office_method" value="Đặt vé" class="selectpaymentmethod button" />
						  <span class="waiting">Hệ thống đang xử lý...</span>
					   </p>
				  </div>
			  </div><!--.methods-->
  
			  <!-- THANH TOAN TẠI NHÀ -->
			  <div class="methods">
				  <div class="methods-header home">
					  
					  <label for="method_athome" class="methods-header radio radio-inline"><input type="radio" id="method_athome" name="radio" />
						  <span>Thanh toán tại nhà (Phí <?php echo number_format(get_option('opt_delivery_fee',0,'.',',')); ?> VND)</span>
                           
						     <p>Chúng tôi sẽ tiến hành in mặt vé, đi cùng phiếu thu số tiền phải trả của Quý khách. Đội giao vé nhanh sẽ liên hệ quý khách trong thời gian sớm nhất.</p>
                   
					  </label>
				  </div>
				  <div class="methods-content" id="content_athome">
					  <p style="padding: 10px 0;">Thanh toán tại nhà chỉ áp dụng cho các quận huyện trong TP.HCM.<br/>
                         <span style="font-weight:bold">Vé khuyến mãi không hoàn đổi !!</span></p>
                      
					  <p class="select">
                       
						  <input type="submit" name="sm_home_method" value="Đặt vé" class="selectpaymentmethod button" />
						   <span class="waiting">Hệ thống đang xử lý...</span>
					  </p>
				  </div>
			  </div><!--.method-->
			  
		  </form>
  
	  </div><!--#ctleft-->
  </div> <!--#mainDisplay-->
  
  </div><!-- end #colLeftNoBorder -->
  
   <div class="col-md-4"> 
	
    <?php get_sidebar(); ?>
	</div><!-- #colRight -->
  <div class="clearfix"></div>
  </div></div> <!--end row wrap col_main+sidebar--> 
<?php
get_footer();
?>