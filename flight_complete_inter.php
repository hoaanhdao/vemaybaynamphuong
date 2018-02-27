<?php
$siteurl = get_bloginfo('siteurl');
if(empty($_SESSION['booking']) || !$_SESSION['search']['isinter'] || empty($_SESSION['contact']) || empty($_SESSION['dep'])){
    header('Location:'.$siteurl);
    exit();
}

require(TEMPLATEPATH . '/flight_config/sugarrest/sugar_rest.php');
$sugar = new Sugar_REST();
$error = $sugar->get_error();

// LAY THONG TIN BOOKING
$booking_id = $_SESSION['booking']['id'];
$options['limit'] = 1;
$options['where'] = 'ec_flight_bookings.id="'.$booking_id .'"';
$select = array('name','contact_name','email','phone','address','flight_type','total_amount','payment_type');
$result = $sugar->get("EC_Flight_Bookings", $select, $options);

$flight = $_SESSION['dep'];
$booking_num = $result[0]['name'];
$flight_type = $GLOBALS['way_flight_list'][$result[0]['flight_type']];
$contact_name = $result[0]['contact_name'];
$contact_email = $result[0]['email'];
$contact_phone = $result[0]['phone'];
$contact_address = $result[0]['address'];
$payment_type = $GLOBALS['payment_type'][$result[0]['payment_type']];
$total_amount = $result[0]['total_amount'];

$source_ia = getCityName($_SESSION['search']['source']);
$destination_ia = getCityName($_SESSION['search']['destination']);
$depdate = $_SESSION['search']['depart'];
if($_SESSION['search']['way_flight'] == 0) $retdate = $_SESSION['search']['return'];
$adult = $_SESSION['search']['adult'];
$children = $_SESSION['search']['children'];
$infant = $_SESSION['search']['infant'];
$paxs = $adult.' người lớn';
if($children > 0) $paxs .= ','.$children.' trẻ em';
if($infant > 0)	$paxs .= ','.$infant.' em bé';

$com_hotline1 = get_option('opt_hotline');
$com_hotline2 = get_option('opt_hotline2');
$com_hotline3 = get_option('opt_hotline3');
$com_phone = get_option('opt_phone');
$com_email1 = get_option('opt_contactemail');
$com_email2 = get_option('opt_contactemail2');

	//Car Rentall
	$pickuptime_tmp=date("d/m/Y",time()+(60*60));
	$maxtime_tmp=date("d/m/Y",time()+(60*60*(8760)));
	$crrttime_tmp=date("d/m/Y",time());
	//End Car Rentall
?>
<?php
get_header();
?>
    <div class="row">
<div class="block">
	<ul id="progressbar" class="hidden-xs">
		<li><span class="pull-left">Chọn hành trình</span>
			<div class="bread-crumb-arrow"></div>
		</li>
		<li><span class="pull-left">2. Thông tin hành khách</span>
			<div class="bread-crumb-arrow"></div>
		</li>
		<li><span class="pull-left">3. Thanh toán</span>
			<div class="bread-crumb-arrow"></div>
		</li>
		<li class="current"><span class="pull-left">4. Hoàn tất</span></li>
	</ul>
	<div class="gap-small"></div>
	
	<div id="colLeftNoBorder" class="col-md-8 sidebar-separator">
    
        
        <div id="mainDisplay">
            <div class="confirmbox">
                <h2> THÔNG TIN GIỮ CHỖ ĐÃ ĐƯỢC GHI NHẬN  !!!</h2>
                <h3 style="margin-bottom: 5px;">Mã đơn hàng (MDH) của bạn là : <strong style="font-size:25px;color:#F20000;font-weight:bold;"><?php echo $booking_num; ?></strong></h3>
                <div>
                    <p style="text-align: justify; margin: 5px 0;">
                    Cám ơn bạn đã đặt vé tại VMB Nam Phương, Booker sẽ liên hệ bạn trong thời gian sớm nhất để xác thực thông tin đăng ký và giá vé của thời điểm xử lý.
                </p>
                    <p style="text-align: justify; margin: 5px 0;">
                   		Sau quá trình xác nhận này quý khách cần chuyển khoản thanh toán để bảo vệ giá. Sau khi chuyển khoản xin vui lòng nhắn tin vào số  
                        <span style="font-weight: bold; color:#FE5815;"><?php echo $com_hotline2; ?></span> kèm theo thông tin mã đơn hàng, tên người liên hệ.
                   		<br /> Hoặc gọi vào đây để báo có : <span style="font-weight: bold; color:#FE5815;font-size:18px;">
                        <strong><?php echo $com_hotline1; ?></strong> 
                        - <strong><?php echo $com_hotline2; ?></strong> 
                        - <strong><?php echo $com_phone; ?></strong></span>.
                	</p>
                   	<p style="text-align: justify;margin: 5px 0;">
                    	Chi tiết thông tin chuyến bay và mã vé sẽ được gửi tới email : <strong><?php echo $contact_email; ?></strong>
                	</p>
                </div>
                <div class="clearfix"></div>
            </div><!--.confirmbox-->
				<div class="row" id="carRental-wrap">
				<div class="col-md-4">
				<img src="<?php bloginfo('template_directory')?>/images/carRental-icon.png" alt="Dịch vụ đưa đón sân bay">
				</div>
				<div class="col-md-8">
						<a href="#carRentalContainer" id="carRental"  class="nav-toggle">Bạn muốn sử dụng dịch vụ <br><b>XE ĐƯA ĐÓN SÂN BAY?</b> 
						<span class="button">ĐĂNG KÝ NGAY</span></a>
				</div>
			</div>
			<div class="carRentalContainer" id="carRentalContainer" style="display: none;">
 
			<form method="post" action="" id="frmBooking" name="frmBooking">
			  <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label>Nơi đi</label>
                  <div class="location-wrap form-group">
                    <select name="pickupLocation" id="pickupLocation" class="form-control">
                    	<option value="Quận 1">Quận 1</option>
						<option value="Quận 2">Quận 2</option>
						<option value="Quận 3">Quận 3</option>
						<option value="Quận 4">Quận 4</option>
						<option value="Quận 5">Quận 5</option>
						<option value="Bình Thạnh">Bình Thạnh</option>
						<option value="Phú Nhuận">Phú Nhuận</option>
						<option value="Sân bay TSN">Sân bay TSN</option>
						<option value="Bình Dương">Bình Dương</option>
						<option value="Bình Phước">Bình Phước</option>
						<option value="Củ Chi">Củ Chi</option>
						<option value="Long AN">Long An</option>
						<option value="Bến Tre">Bến Tre</option>
						<option value="Tiền Giang">Tiền Giang</option>
						<option value="Đồng Tháp">Đồng Tháp</option>
						<option value="Vũng Tàu">Vũng Tàu</option>
						<option value="Đồng Nai">Đồng Nai</option>
						<option value="Địa điểm khác">Địa điểm khác</option>
   
                    </select>
                  </div>
                </div>
                  <div class="col-md-3 col-xs-7">
                  <div class="form-group">
                    <label>Ngày đi</label>
                    <i class="fa fa-calendar input-icon font-blue"></i>
                    <div class="datepicker-wrap">
                      <input id="pickupDate" name="pickupDate" readonly  class="form-control"  after_function="change_pickup_date" minDate="<?= $crrttime_tmp?>" maxdate="<?=$maxtime_tmp?>"  default_max_date="<?=$maxtime_tmp?>"  type="text" default="<?=$pickuptime_tmp?>" value="<?=$pickuptime_tmp?>" autocomplete="off">
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-xs-5">
                  <div class="form-group">
                    <label>Giờ </label>
                 	      <select name="pickupTime" id="pickupTime" class="time-pick form-control">
          <option value="07:00">7:00 AM</option>
          <option value="07:30">7:30 AM</option>
          <option value="08:00">8:00 AM</option>
          <option value="08:30">8:30 AM</option>
          <option value="09:00">9:00 AM</option>
          <option value="09:30">9:30 AM</option>
          <option value="10:00">10:00 AM</option>
          <option value="10:30">10:30 AM</option>
          <option value="11:00">11:00 AM</option>
          <option value="11:30">11:30 AM</option>
          <option value="12:00">12:00 PM</option>
          <option value="12:30">12:30 PM</option>
          <option value="13:00">1:00 PM</option>
          <option value="13:30">1:30 PM</option>
          <option value="14:00">2:00 PM</option>
          <option value="14:30">2:30 PM</option>
          <option value="15:00">3:00 PM</option>
          <option value="15:30">3:30 PM</option>
          <option value="16:00">4:00 PM</option>
          <option value="16:30">4:30 PM</option>
          <option value="17:00">5:00 PM</option>
          <option value="17:30">5:30 PM</option>
          <option value="18:00">6:00 PM</option>
          <option value="18:30">6:30 PM</option>
          <option value="19:00">7:00 PM</option>
          <option value="19:30">7:30 PM</option>
          <option value="20:00">8:00 PM</option>
          <option value="20:30">8:30 PM</option>
          <option value="21:00">9:00 PM</option>
          <option value="21:30">9:30 PM</option>
          <option value="22:00">10:00 PM</option>
          <option value="22:30">10:30 PM</option>
          <option value="23:00">11:00 PM</option>
          <option value="23:30">11:30 PM</option>
          <option value="00:00">12:00 AM</option>
          <option value="00:30">12:30 AM</option>
          <option value="01:00">1:00 AM</option>
          <option value="01:30">1:30 AM</option>
          <option value="02:00">2:00 AM</option>
          <option value="02:30">2:30 AM</option>
          <option value="03:00">3:00 AM</option>
          <option value="03:30">3:30 AM</option>
          <option value="04:00">4:00 AM</option>
          <option value="04:30">4:30 AM</option>
          <option value="05:00">5:00 AM</option>
          <option value="05:30">5:30 AM</option>
          <option value="06:00">6:00 AM</option>
          <option value="06:30">6:30 AM</option>
        </select>
  
                  </div>
                </div>
              
				
              </div>
              <div class="row">
				<div class="col-md-6 col-sm-6 col-xs-12">
                  <label>Nơi đến</label>
                  <div class="location-wrap form-group">
                    <select name="dropoffLocation" id="dropoffLocation" class="form-control">
						<option value="Quận 1">Quận 1</option>
						<option value="Quận 2">Quận 2</option>
						<option value="Quận 3">Quận 3</option>
						<option value="Quận 4">Quận 4</option>
						<option value="Quận 5">Quận 5</option>
						<option value="Bình Thạnh">Bình Thạnh</option>
						<option value="Phú Nhuận">Phú Nhuận</option>
						<option value="Sân bay TSN">Sân bay TSN</option>
						<option value="Bình Dương">Bình Dương</option>
						<option value="Bình Phước">Bình Phước</option>
						<option value="Củ Chi">Củ Chi</option>
						<option value="Long AN">Long An</option>
						<option value="Bến Tre">Bến Tre</option>
						<option value="Tiền Giang">Tiền Giang</option>
						<option value="Đồng Tháp">Đồng Tháp</option>
						<option value="Vũng Tàu">Vũng Tàu</option>
						<option value="Đồng Nai">Đồng Nai</option>
						<option value="Địa điểm khác">Địa điểm khác</option>
	  
                    </select>
                  </div>
                </div>
                
				<div class="col-md-3 col-xs-7">
                  <div class="form-group">
                    <label>Ngày về</label>
                    <i class="fa fa-calendar input-icon font-blue"></i>
                    <div class="datepicker-wrap">
                      <input id="dropoffDate" name="dropoffDate" readonly  class="form-control" after_function="change_dropoff_date" minDate="<?=$pickuptime_tmp?>" maxdate="<?=$maxtime_tmp?>" type="text" default="--/--/----" value="--/--/----"  autocomplete="off">
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-xs-5">
                  <label>Giờ </label>
                  <div class="form-group">
                          <select name="dropoffTime" id="dropoffTime" class="time-pick form-control">
          <option value="07:00">7:00 AM</option>
          <option value="07:30">7:30 AM</option>
          <option value="08:00">8:00 AM</option>
          <option value="08:30">8:30 AM</option>
          <option value="09:00">9:00 AM</option>
          <option value="09:30">9:30 AM</option>
          <option value="10:00">10:00 AM</option>
          <option value="10:30">10:30 AM</option>
          <option value="11:00">11:00 AM</option>
          <option value="11:30">11:30 AM</option>
          <option value="12:00">12:00 PM</option>
          <option value="12:30">12:30 PM</option>
          <option value="13:00">1:00 PM</option>
          <option value="13:30">1:30 PM</option>
          <option value="14:00">2:00 PM</option>
          <option value="14:30">2:30 PM</option>
          <option value="15:00">3:00 PM</option>
          <option value="15:30">3:30 PM</option>
          <option value="16:00">4:00 PM</option>
          <option value="16:30">4:30 PM</option>
          <option value="17:00">5:00 PM</option>
          <option value="17:30">5:30 PM</option>
          <option value="18:00">6:00 PM</option>
          <option value="18:30">6:30 PM</option>
          <option value="19:00">7:00 PM</option>
          <option value="19:30">7:30 PM</option>
          <option value="20:00">8:00 PM</option>
          <option value="20:30">8:30 PM</option>
          <option value="21:00">9:00 PM</option>
          <option value="21:30">9:30 PM</option>
          <option value="22:00">10:00 PM</option>
          <option value="22:30">10:30 PM</option>
          <option value="23:00">11:00 PM</option>
          <option value="23:30">11:30 PM</option>
          <option value="00:05">12:00 AM</option>
          <option value="00:30">12:30 AM</option>
          <option value="01:00">1:00 AM</option>
          <option value="01:30">1:30 AM</option>
          <option value="02:00">2:00 AM</option>
          <option value="02:30">2:30 AM</option>
          <option value="03:00">3:00 AM</option>
          <option value="03:30">3:30 AM</option>
          <option value="04:00">4:00 AM</option>
          <option value="04:30">4:30 AM</option>
          <option value="05:00">5:00 AM</option>
          <option value="05:30">5:30 AM</option>
          <option value="06:00">6:00 AM</option>
          <option value="06:30">6:30 AM</option>
        </select>
  
				  </div>
                </div>
              </div>
              
			  <div class="row">
                 <div class="col-md-3 col-sm-6 col-xs-12">
                  <label>Điện thoại</label>
                  <div class="form-group">
                     <input  id="cusPhone" name="cusPhone" class="form-control" type="text" />
                  </div> 
                </div>
				<div class="col-md-3 col-sm-6 col-xs-12">
                  <label>Số khách</label>
                <div class="form-group">
                <select id="cusQty" name="cusQty" class="form-control">
                   <?php 
					for($i=1; $i<=16; $i++){
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
				  ?>
                </select>
              </div>  
				</div>
				 <div class="col-md-3 col-sm-6 col-xs-12">
				 	<a href="#couponControl" onclick="toggle_visibility('couponContainer');" class="moduleControl">Mã khuyến mãi</a>
					<div class="couponContainer" id="couponContainer" style="display: none;">
					  <input type="text" id="couponEntry" name="coupon" maxlength="25" class="form-control">
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">
					<input type="submit" name="sm_rentalCar" value="ĐĂNG KÝ NGAY" id="BtnSearch" class="button pull-right">
			    </div>
              </div>
					<div class="row" id="moto-pickup">Dịch vụ đưa đón sân bay bằng xe máy, xe 4 chỗ, 7 chỗ, 16 chỗ.
					Quý khách di chuyển 1 mình ít hành lý nên sử dụng dịch vụ xe máy nhanh chóng, an toàn, tiết kiệm thời gian, chi phí.</div>
					
            </form>
			<span class="notice-success">Hệ thống đang xử lý .....</span>
			
		  </div>
		   <?php 
			if(isset($_POST['sm_rentalCar']) && trim($_POST['cusPhone']) != ''){
			$pickupDate = date( 'Y-m-d',strtotime(str_replace('/','-',$_POST['pickupDate'])));
			$dropoffDate = date( 'Y-m-d',strtotime(str_replace('/','-',$_POST['dropoffDate'])));
			$pickupTime = date( 'H:i:s',strtotime(str_replace('','',$_POST['pickupTime'])));
			$dropoffTime = date( 'H:i:s',strtotime(str_replace('','',$_POST['dropoffTime'])));
			$ob_datetime = $pickupDate.' '.$pickupTime;
			$ib_datetime = $dropoffDate.' '.$dropoffTime;
			//print_r($ob_datetime);
			//exit();
			$post_data = array();
			$post_data['departure']=$_POST['pickupLocation'];
			$post_data['arrival']=$_POST['dropoffLocation'];
			$post_data['ob_datetime']= $ob_datetime;
			$post_data['ib_datetime']= $ib_datetime;
			$post_data['contact_mobile']=$_POST['cusPhone'];
			$post_data['total_pax']=$_POST['cusQty'];
			$post_data['address']='';
			$post_data['contact_name']='';
			$post_data['contact_email']='';
			$post_data['description']='';

			$result = dat_xe(http_build_query($post_data, 'flags_'));				   
		   }?>
          <!--End car rental-content-->
           
            <div id="pagecontent">
                <table id="printit" width="98%" style="margin-bottom: 10px;">
                    <tr>
                        <td width="80%"><h2 style="font-size: 18px;">CHI TIẾT ĐƠN HÀNG SỐ : <?php echo $booking_num; ?> </h2></td>
                        <td style="text-align: right;font-size: 12px;">&nbsp;</td>
                    </tr>
                </table>
                <div id="printarea">
                    
                    <!-- THONG TIN DON HANG -->
                    <table class="field-table" width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="20%">Mã đơn hàng:</td>
                            <td width="30%"><strong><?php echo $booking_num; ?></strong></td>
                            <td width="20%">Trạng thái:</td>
                            <td width="30%"><strong>Chưa xác nhận</strong></td>
                        </tr>
                        <tr>
                            <td>Chuyến bay:</td>
                            <td><strong><?php echo $flight_type; ?></strong></td>
                            <td>Số hành khách:</td>
                            <td><strong><?php echo $paxs; ?></strong></td>
                        </tr>
                        <tr>
                            <td>Ngày đi:</td>
                            <td><strong><?php echo $depdate; ?></strong></td>
                            <?php if(isset($retdate) && !empty($retdate)){ ?>
                                <td>Ngày về:</td>
                                <td><strong><?php echo $retdate; ?></strong></td>
                            <?php }else{ ?>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            <?php } ?>
                        </tr>
                    </table>
                    
                    <!-- THONG TIN HANH TRINH -->
                    <table class="field-table" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr style="line-height:1px;">
                            <td width="13%">&nbsp;</td>
                            <td width="26%">&nbsp;</td>
                            <td width="26%">&nbsp;</td>
                            <td width="35%">&nbsp;</td>
                        </tr>
                        <tr class="inter-outbound-head">
                            <td class="outbound-icon">&nbsp;</td>
                            <td colspan="2">Khởi hành từ <strong><?php echo $source_ia; ?></strong></td>
                            <td><a title="Xem chi tiết" class="inter-view-outbound-detail" href="#">Xem chi tiết</a> Thời gian: <strong><?php echo $flight['outbound_duration']; ?></strong></td>
                        </tr>
                        <!-- HANH TRINH CHIEU DI -->
                        <?php
							foreach($flight['outbound_detail'] as $detail){
								if($detail['type'] == 'route'){
						?>
						
						<tr class="inter-outbound">
							<td><img align="absmiddle" src="<?php echo interimgdir; ?>/<?php echo $detail['aircode']; ?>.png" /></td>
							<td><strong><?php echo  $detail['depname'] ?> (<?php echo  $detail['depcode'] ?>)</strong><p style="font-size:11px;"><?php echo $detail['depairport']; ?></p><p style="font-size:11px;"><strong><?php echo  date('H:i',strtotime($detail['deptime'])) ?></strong>, <?php echo  date('d/m/Y',strtotime($detail['deptime'])) ?></p></td>
							<td><strong><?php echo  $detail['arvname'] ?> (<?php echo  $detail['arvcode'] ?>)</strong><p style="font-size:11px;"><?php echo $detail['arvairport']; ?></p><p style="font-size:11px;"><strong><?php echo  date('H:i',strtotime($detail['arvtime'])) ?></strong>, <?php echo  date('d/m/Y',strtotime($detail['arvtime'])) ?></p></td>
							<td>Mã chuyến: <strong><?php echo  $detail['flightno'] ?></strong><br />Thời gian: <strong><?php echo $detail['duration']; ?></strong></td>
						</tr>
						
						<?php }else{ ?>
									
						<tr class="inter-outbound">
							<td colspan="4" style="border-top:1px solid #DC0F16; border-bottom:1px solid #DC0F16; background:#FDDFE0; text-align:center;">Thay đổi máy bay tại <strong><?php echo $detail['depname']; ?></strong> Thời gian giữa các chuyến bay: <strong><?php echo $detail['duration']; ?></strong></td>
						</tr>
									
						<?php
								}
							}
						?>
                        
                        <?php if($_SESSION['search']['way_flight'] == 0){ ?>
                		
                        <!-- HANH TRINH CHIEU VE -->
                        <tr class="inter-inbound-head">
                            <td class="inbound-icon">&nbsp;</td>
                            <td colspan="2">Khởi hành từ <strong><?php echo $destination_ia; ?></strong></td>
                            <td><a title="Xem chi tiết" class="inter-view-inbound-detail" href="#">Xem chi tiết</a> Thời gian: <strong><?php echo $flight['inbound_duration']; ?></strong></td>
                        </tr>
                        
                        <?php
                            foreach($flight['inbound_detail'] as $detail){
                                if($detail['type'] == 'route'){
                        ?>
                        
                        <tr class="inter-inbound">
                            <td><img align="absmiddle" src="<?php echo interimgdir?>/<?php echo $detail['aircode']; ?>.png" /></td>
                            <td><strong><?php echo  $detail['depname'] ?> (<?php echo  $detail['depcode'] ?>)</strong><p style="font-size:11px;"><?php echo $detail['depairport']; ?></p><p style="font-size:11px;"><strong><?php echo  date('H:i',strtotime($detail['deptime'])) ?></strong>, <?php echo  date('d/m/Y',strtotime($detail['deptime'])) ?></p></td>
                            <td><strong><?php echo  $detail['arvname'] ?> (<?php echo  $detail['arvcode'] ?>)</strong><p style="font-size:11px;"><?php echo $detail['arvairport']; ?></p><p style="font-size:11px;"><strong><?php echo  date('H:i',strtotime($detail['arvtime'])) ?></strong>, <?php echo  date('d/m/Y',strtotime($detail['arvtime'])) ?></p></td>
                            <td>Mã chuyến: <strong><?php echo  $detail['flightno'] ?></strong><br />Thời gian: <strong><?php echo $detail['duration']; ?></strong></td>
                        </tr>
                        
                        <?php } else { ?>
                                    
                        <tr class="inter-inbound">
                            <td colspan="4" style="border-top:1px solid #DC0F16; border-bottom:1px solid #DC0F16; background:#FDDFE0; text-align:center;">Thay đổi máy bay tại <strong><?php echo $detail['depname']; ?></strong> Thời gian giữa các chuyến bay: <strong><?php echo $detail['duration']; ?></strong></td>
                        </tr>
                                    
                        <?php
                                }
                            }
                        ?>
            
                    <?php } // end if way_flight ?>
                    </table>
                    
                    <div style="border-top:1px dashed #ccc; padding-top:10px; line-height:30px;">    
                        <label style="display: inline-block;width: 145px;margin-right: 10px;">Tổng cộng: </label>
                        <span style="font-weight:bold;font-size:20px;color:#F20000;"><?php echo format_price($total_amount); ?></span>
                        <br />
                        <label style="display: inline-block;width: 145px;margin-right: 10px;">Hình thức thanh toán:</label> 
                        <span style="font-weight:bold;font-size:16px;color:#59A800"><?php echo $payment_type ?></span>
                        <br />
                        <label style="display: inline-block;width: 145px;margin-right: 10px;">Trạng thái thanh toán:</label> 
                        <span style="font-weight:bold;font-size:16px;color:#59A800">Chưa thanh toán</span>
                    </div>    
                    
                </div><!-- #printarea -->
                
                <div class="confirm_info">
                    <p style="font-size: 13px; font-weight: bold;">Quý khách chuyển khoản thanh toán cho chúng tôi vui lòng gọi vào đây để báo có :<br />
                    <span style="font-weight:bold;color:#FE5815; font-size: 22px; line-height: 200%;"><?php echo $com_hotline1; ?></strong> - <strong><?php echo $com_hotline2; ?></strong> - <strong><?php echo $com_phone; ?></strong></span> <br />
                     Hoặc có thể gởi mail vào <strong><?php echo get_option('opt_contactemail'); ?></strong> bao gồm thông tin mã đơn hàng, tên người liên hệ. <br/>Trong thời gian 30p nếu chưa nhận được phản hồi, xin gọi đến tổng đài để xác nhận.
                    </p>
                    <br/>
                </div><!-- .confirm_info -->
                
            </div>
        </div> <!--#mainDisplay-->
    
		</div>
	 
		<div id="colRight" class="col-md-4">
			<div class="passsenger">
			   <?php get_sidebar(); ?>
			</div><!-- #colRight -->
		 
		</div>
	</div> 
</div> <!--end row wrap col_main+sidebar--> 

<?php
get_footer();
?>