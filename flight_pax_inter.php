<?php
$flight = $_SESSION['dep'];
$need = '<font style="color:#ED0000;font-weight:bold">*</font>';
$dem = 1;

// ĐIỀU KIỆN TÌM KIÊM
$way_flight = (int)$_SESSION['search']['way_flight'];
$source = $_SESSION['search']['source'];
$destination = $_SESSION['search']['destination'];
$source_ia = getCityName($source);
$destination_ia = getCityName($destination);
$depart = $_SESSION['search']['depart'];   // dd/mm/yyyy
$return = $_SESSION['search']['return'] ;
$adults = (int)$_SESSION['search']['adult'];
$children = (int)$_SESSION['search']['children'];
$infants = (int)$_SESSION['search']['infant'];

($children != 0) ? $qty_children = ', '.$children.' Trẻ em' : $qty_children = '';
($infants != 0) ? $qty_infants = ', '.$infants.' Trẻ sơ sinh' : $qty_infants = '';

#DON GIA
$qty_passenger = $adults + $children + $infants;

$exchange_rate = (int)get_option("opt_exchange_rate");
$exchange_rate = (!$exchange_rate || $exchange_rate <= 0) ? 1 : $exchange_rate;
// avg taxes
$taxes = ($flight['taxes'] * $exchange_rate) / ($adults + $children);
// adult
$price_adult = ($flight['price_adult'] / $adults) * $exchange_rate;
$tax_adult = $taxes + (int)get_option("opt_inter_adt_svfee");
$amount_adult = ($price_adult + $tax_adult) * $adults;
// child
$price_child = ($flight['price_child'] / $children) * $exchange_rate;
$tax_child = $taxes + (int)get_option("opt_inter_chd_svfee");
$amount_child = ($price_child + $tax_child) * $children;
// infant
$price_infant = ($flight['price_infant'] / $infants) * $exchange_rate;
$tax_infant = (int)get_option("opt_inter_inf_svfee");
$amount_infant = ($price_infant + $tax_infant) * $infants;
// total amount
$total_amount = $amount_adult + $amount_child + $amount_infant;
$total_tax = ($tax_adult * $adult) + ($tax_child * $child) + ($tax_infant * $infant);
$total_amount_notax = $total_amount - $total_tax;

/*POST PASSENGER*/
if(count($_POST) && isset($_POST['sm_bookingflight']) && !empty($_SESSION['dep']) ){
	
	// contact
    $_SESSION['contact']=array(
        'flight_type' => $way_flight,
        'airline' => '',
        'airline_inbound' => '',
        'contact_name' => ucwords(utf8convert(strtolower($_POST['contact_name']))),
        'email' => $_POST['contact_email'],
        'country' => $_POST['contact_country'],
        'phone' => $_POST['contact_phone'],
        'address' => utf8convert($_POST['contact_address']),
        'city' => $_POST['contact_city'],
        'description' => $_POST['special_request'],
    );

    // Lưu thông tin hành khách
    for($i = 0; $i < $qty_passenger; $i++){

        $_SESSION['pax'][$i]=array(
            'type' => $_POST['passenger_type'][$i],
            'salutation' => $_POST['passenger_title'][$i],
            'name' => strtoupper(utf8convert(strtolower($_POST['passenger_name'][$i]))),
            'birthday' => $_POST['passenger_birthyear'][$i].'-'.$_POST['passenger_birthmonth'][$i].'-'.$_POST['passenger_birthday'][$i],
            'luggage_price' => 0,
            'luggage_price_inbound' => 0,
            'eticket_outbound' => '',
            'eticket_inbound' => '',
            'pnr_outbound' => '',
            'pnr_inbound' => '',
        );

	}
    
    header("Location: "._page('payment'));

}
get_header();
?>
<div class="row">
<div class="block">
	<div id="nav-flightsearch" class="step2 row">
      <ul id="progressbar" class="hidden-xs">
			<li class="pass"><span class="pull-left">1. Chọn hành trình</span>
				<div class="bread-crumb-arrow"></div>
			</li>
			<li class="current"><span class="pull-left">2. Thông tin hành khách</span>
				<div class="bread-crumb-arrow"></div>
			</li>
			<li><span class="pull-left">3. Thanh toán</span></li>
				<div class="bread-crumb-arrow"></div>
			<li><span class="pull-left">4. Hoàn tất</span></li>
		</ul>
 		
    </div>
<div id="colLeftNoBorder" class="col-md-8 sidebar-separator"><div class="passsenger">

    

<?php if($noflight===true): ?>
<!--Redirect ve trang tim chuyen bay neu chuyen bay duoc chon ko ton tai-->
<script type="text/javascript" language="javascript">
    $(document).ready(function(){
        $("#mainDisplay").html("<p style='color: red;padding: 20px 10px;'>Chuyến bay bạn chọn không tồn tại, trang web sẽ tự động quay lại trang tìm chuyến bay.</p>")
        setTimeout(function () {
            window.location.href = "<?php echo _page("flightresult")?>";
        }, 2000);
    })

</script>
    <?php endif; /*End no flight*/ ?>

    <div id="mainDisplay">
    
    <form action="" method="post" id="frmBookingFlight">
    <div id="ctleft" class="passsenger">
    <fieldset>
        <div class="heading-with-icon-and-ruler">
            <div class="heading-icon"><i class="icons-sprite icons-plane_3d_encircled"></i></div>
            <div class="heading-title">Thông tin chuyến bay</div>
            <hr>
        </div> 
		 <div class="field-table" width="100%">
			<div class="row">
				 <div class="col-md-6 col-sm-6 col-xs-12"><label>Chuyến bay :  
				<strong><?= $GLOBALS['way_flight_list'][$_SESSION['search']['way_flight']] ?></strong></label></div>
				<div class="col-md-6 col-sm-6 col-xs-12"><label>Số lượng :  
				 <strong><?= $adults ?> người lớn<?= $qty_children.$qty_infants?></strong></label></div>
			</div>
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-12"><label>Ngày đi : <strong><?= $depart ?></strong></label></div>
				<? if($way_flight == 0)
				echo '<div class="col-md-6 col-sm-6 col-xs-12"><label>Ngày về : <strong>'.$return.'</strong></label></div>';
				?>

			</div>
		</div>
       
        
        <table class="field-table" width="100%">
            
            <tr style="line-height:1px;">
            	<td width="11%">&nbsp;</td>
                <td width="28%">&nbsp;</td>
                <td width="28%">&nbsp;</td>
                <td width="33%">&nbsp;</td>
            </tr>
            
            <tr class="inter-outbound-head">
            	<td class="outbound-icon">&nbsp;</td>
                <td colspan="2">Khởi hành từ <strong><?php echo $source_ia; ?></strong></td>
                <td><a title="Xem chi tiết" class="inter-view-outbound-detail" href="#"><span class="hidden-xs">Chi tiết</span></a> Thời gian: <strong><?php echo $flight['outbound_duration']; ?></strong></td>
            </tr>
            
            <?php
                foreach($flight['outbound_detail'] as $detail){
                    if($detail['type'] == 'route'){
            ?>
            
            <tr class="inter-outbound">
                <td><img align="absmiddle" src="<?=interimgdir?>/<?php echo $detail['aircode']; ?>.png" /></td>
                <td><b><?= $detail['depname'] ?> (<?= $detail['depcode'] ?>)</b><p style="font-size:11px;"><?php echo $detail['depairport']; ?></p><p style="font-size:11px;"><b><?= date('H:i',strtotime($detail['deptime'])) ?></b>, <?= date('d/m/Y',strtotime($detail['deptime'])) ?></p></td>
                <td><b><?= $detail['arvname'] ?> (<?= $detail['arvcode'] ?>)</b><p style="font-size:11px;"><?php echo $detail['arvairport']; ?></p><p style="font-size:11px;"><b><?= date('H:i',strtotime($detail['arvtime'])) ?></b>, <?= date('d/m/Y',strtotime($detail['arvtime'])) ?></p></td>
                <td style="padding-left:30px;">Mã chuyến: <b><?= $detail['flightno'] ?></b><br />Thời gian: <b><?php echo $detail['duration']; ?></b></td>
            </tr>
            
            <?php 
                    } else {
                        ?>
                        
            <tr class="inter-outbound">
                <td colspan="4" style="border-top:1px solid #DC0F16; border-bottom:1px solid #DC0F16; background:#FDDFE0; text-align:center;">Thay đổi máy bay tại <strong><?php echo $detail['depname']; ?></strong> Thời gian giữa các chuyến bay: <strong><?php echo $detail['duration']; ?></strong></td>
            </tr>
                        
                        <?php
                    }
                } // end foreach
            ?>
            
            <?php if($way_flight == 0){ ?>
                
                <tr class="inter-inbound-head">
                	<td class="inbound-icon">&nbsp;</td>
                    <td colspan="2">Khởi hành từ <strong><?php echo $destination_ia; ?></strong></td>
                    <td><a title="Xem chi tiết" class="inter-view-inbound-detail" href="#"><span class="hidden-xs">Chi tiết</span></span></a> Thời gian: <strong><?php echo $flight['inbound_duration']; ?></strong></td>
                </tr>
                
                <?php
                    foreach($flight['inbound_detail'] as $detail){
                        if($detail['type']=='route'){
                ?>
                
                <tr class="inter-inbound">
                    <td><img align="absmiddle" src="<?=interimgdir?>/<?php echo $detail['aircode']; ?>.png" /></td>
                    <td><b><?= $detail['depname'] ?> (<?= $detail['depcode'] ?>)</b><p style="font-size:11px;"><?php echo $detail['depairport']; ?></p><p style="font-size:11px;"><b><?= date('H:i',strtotime($detail['deptime'])) ?></b>, <?= date('d/m/Y',strtotime($detail['deptime'])) ?></p></td>
                    <td><b><?= $detail['arvname'] ?> (<?= $detail['arvcode'] ?>)</b><p style="font-size:11px;"><?php echo $detail['arvairport']; ?></p><p style="font-size:11px;"><b><?= date('H:i',strtotime($detail['arvtime'])) ?></b>, <?= date('d/m/Y',strtotime($detail['arvtime'])) ?></p></td>
                    <td style="padding-left:30px;">Mã chuyến: <b><?= $detail['flightno'] ?></b><br />Thời gian: <b><?php echo $detail['duration']; ?></b></td>
                </tr>
                
                <?php
                        } else {
                            ?>
                            
                <tr class="inter-inbound">
                    <td colspan="4" style="border-top:1px solid #DC0F16; border-bottom:1px solid #DC0F16; background:#FDDFE0; text-align:center;">Thay đổi máy bay tại <strong><?php echo $detail['depname']; ?></strong> Thời gian giữa các chuyến bay: <strong><?php echo $detail['duration']; ?></strong></td>
                </tr>
                            
                            <?php
                        }
                    } // end foreach
                ?>
    
            <?php } // end if way_flight ?>
        </table>
    </fieldset>
		<div class="heading-with-icon-and-ruler">
            <div class="heading-icon"><i class="icons-sprite icons-users_encircled"></i></div>
            <div class="heading-title"> Thông tin hành khách</div>
            <hr>
        </div>
		<div  style="font-size:11px;color:#777"><span style="color: red"> Lưu ý </span>: Tên hành khách vui lòng viết hoa, không dấu</div> 
        
    <?php
    // THÔNG TIN HÀNH KHÁCH : ADULT
    for($k=1; $k <= $adults; $k++){
        ?>
		<p><strong><?= $dem ?>. Người lớn</strong></p>
         <div class="field-table">
         
        <div class="row">
        
            <div class="col-md-2 col-sm-4 col-xs-4">
            	<div class="form-group"> 
              <label>Giớ tính</label>
                <select name="passenger_title[]" class="form-control">
                    <option value="0">Ông</option>
                    <option value="1">Bà</option>
                </select>
                <input type="hidden" name="passenger_type[]" value="0" />
                </div>
            </div>
            
            <div class="col-md-4 col-sm-8 col-xs-8">
            	<div class="form-group"> 
                <label>Họ và tên</label> 
                <input type="text" name="passenger_name[]" class="passenger_name form-control" />
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12" style="display:none;">
           <label  style="font-weight:bold">Ngày sinh <?= $need ?></label>
            	<div class="form-group row">
                    <div class="col-md-4 col-sm-4 col-xs-4">
           	         <select name="passenger_birthday[]" class="birthday">
                        <option value="0">Ngày</option>
                        <?php
                        for($i = 1; $i <= 31; $i++)
                        {
                            ?>
                            <option value="<?php echo  $i ?>" <?php if($birthday[2] == $i) echo "selected"; ?> ><?php echo  $i ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    </div>
                     <div class="col-md-4 col-sm-4 col-xs-4">
                    <select name="passenger_birthmonth[]" class="birthmonth">
                        <option value="0">Tháng</option>
                        <?php
                        for($i = 1; $i <= 12; $i++)
                        {
                            ?>
                            <option value="<?php echo  $i ?>" <?php if($birthday[1] == $i) echo "selected"; ?> ><?php echo  $i ?></option>
                            <?php
                        }
                        ?>
                    </select>
                   	 </div>
                     <div class="col-md-4 col-sm-4 col-xs-4">
                   
                    <select name="passenger_birthyear[]" class="birthyear">
                        <option value="0">Năm</option>
                        <?php
                        (int)$youngest = (int)date('Y') - 12;
                        (int)$oldest = (int)date('Y') - 85;
                        for($i = $youngest; $i >= $oldest; $i--)
                        {
                            ?>
                            <option value="<?php echo  $i ?>" <?php if($birthday[0] == $i) echo "selected"; ?> ><?php echo  $i ?></option>
                            <?php
                            
                        }
                        ?>
                    </select>
              		</div>
                    </div>
           </div>
            
        </div>
    </div>
   
		
	 
    
        <?php
        $dem++;
    } // END FOR ADULTS
    //ADULT
    
    // THÔNG TIN HÀNH KHÁCH: CHILDREN
    for($k = 1; $k <= $children; $k++){
        ?>
		<p><strong><?= $dem ?>. Trẻ em</strong><span class="pax-age-desc">(Từ 2 đến 11 tuổi)</span></p>
          <div class="field-table">
         <div class="row">
        
            <div class="col-md-2 col-sm-4 col-xs-4">
                <div class="form-group">
                   <label>Giới tính</label>
                    <select name="passenger_title[]" class="form-control">
                        <option value="0">Nam</option>
                        <option value="1">Nữ</option>
                    </select>
                    <input type="hidden" name="passenger_type[]" value="1" />
                </div>
            </div>
            <div class="col-md-4 col-sm-8 col-xs-8">
                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" name="passenger_name[]" class="passenger_name form-control" />
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
            	<label>Ngày sinh</label>
            	<div class="form-group row">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                    <select name="passenger_birthday[]" class="birthday form-control">
                        <option value="0">Ngày</option>
                        <?
                        for($i = 1; $i <= 31; $i++)
                        {
                            ?>
                            <option value="<?= $i ?>" <? if($birthday[2] == $i) echo "selected"; ?> ><?= $i ?></option>
                            <?
                        }
                        ?>
                    </select>
                    </div>
                     <div class="col-md-4 col-sm-4 col-xs-4">
                    <select name="passenger_birthmonth[]" class="birthmonth form-control">
                        <option value="0">Tháng</option>
                        <?
                        for($i = 1; $i <= 12; $i++)
                        {
                            ?>
                            <option value="<?= $i ?>" <? if($birthday[1] == $i) echo "selected"; ?> ><?= $i ?></option>
                            <?
                        }
                        ?>
                    </select>
                    </div>
                     <div class="col-md-4 col-sm-4 col-xs-4">
                        <select name="passenger_birthyear[]" class="birthyear form-control">
                            <option value="0">Năm</option>
                            <?
                            (int)$youngest = (int)date('Y') - 3;
                            (int)$oldest = (int)date('Y') - 18;
                            for($i = $youngest; $i >= $oldest; $i--)
                            {
                                ?>
                                <option value="<?= $i ?>" <? if($birthday[0] == $i) echo "selected"; ?> ><?= $i ?></option>
                                <?
                                if($i % 10 == 0)
                                    echo '<option value="0">------</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
            	</div>
            </div>
        </div>
    </div>
   
    
        <?php
        $dem++;
    }// END FOR CHILDREN
    //CHILDREN
    
    //	THÔNG TIN HÀNH KHÁCH: INFANT
    for($k = 1; $k <= $infants; $k++){
        ?>
      <p><strong><?= $dem ?>. Em bé</strong> <span class="pax-age-desc">(Dưới 2 tuổi)</span></p>
		<div class="field-table">
         <div class="row">
        	 
            <div class="col-md-2 col-sm-4 col-xs-4">
                <div class="form-group">
                	<label>Giới tính</label>
                    <select name="passenger_title[]" class="form-control">
                        <option value="0">Em bé trai</option>
                        <option value="1">Em bé gái</option>
                    </select>
                    <input type="hidden" name="passenger_type[]" value="2" />
                </div>
            </div>
            
            <div class="col-md-4 col-sm-8 col-xs-8">
                <div class="form-group">
                <label>Họ và tên</label>
                	<input type="text" name="passenger_name[]" class="passenger_name form-control" />
            	</div>
            </div>
            
            <div class="col-md-6 col-sm-12 col-xs-12">
            	<label>Ngày sinh<br /><font style="font-size:11px;color:#777">ví dụ: 10/07/2012</font></label>
               <div class="form-group row">
                	 <div class="col-md-4 col-sm-4 col-xs-4">
                    <select name="passenger_birthday[]" class="birthday form-control">
                        <option value="0">Ngày</option>
                        <?
                        for($i = 1; $i <= 31; $i++)
                        {
                            ?>
                            <option value="<?= $i ?>" <? if($birthday[2] == $i) echo "selected"; ?> ><?= $i ?></option>
                            <?
                        }
                        ?>
                    </select>
                   </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                    <select name="passenger_birthmonth[]" class="birthmonth form-control">
                        <option value="0">Tháng</option>
                        <?
                        for($i = 1; $i <= 12; $i++)
                        {
                            ?>
                            <option value="<?= $i ?>" <? if($birthday[1] == $i) echo "selected"; ?> ><?= $i ?></option>
                            <?
                        }
                        ?>
                    </select>
                  </div>
                   <div class="col-md-4 col-sm-4 col-xs-4">
                    <select name="passenger_birthyear[]" class="birthyear form-control">
                        <option value="0">Năm</option>
                        <?
                        (int)$youngest = (int)date('Y');
                        (int)$oldest = (int)date('Y')-2;
                        for($i = $youngest; $i >= $oldest; $i--)
                        {
                            ?>
                            <option value="<?= $i ?>" <? if($birthday[0] == $i) echo "selected"; ?> ><?= $i ?></option>
                            <?
                            if($i % 10 == 0)
                                echo '<option value="0">------</option>';
                        }
                        ?>
                    </select>
                    </div>
                </div>
        	</div>
    	</div>
    </div>

    
        <?php
        $dem++;
    }
    // END FOR INFANT
    ?>
    
   <div class="heading-with-icon-and-ruler">
            <div class="heading-icon"><i class="icons-sprite icons-phone_encircled"></i></div>
            <div class="heading-title"> Thông tin liên hệ</div>
            <hr>
        </div>
 
    
    <p style="margin:0 0 5px 0px;">(<?= $need ?>) Vui lòng cung cấp đầy đủ thông tin chi tiết liên hệ của bạn bên dưới để chúng có thể nhanh chóng liên hệ với bạn khi cần thiết.</p>
    <div class="field-table">
         
                        
        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-4">
            	<div class="form-group">  
                <label for="contact_title" style="font-weight:bold">Quý danh <?= $need ?></label>   
                <select name="contact_title" id="contact_title" class="form-control">
                    <option value="0">Ông</option>
                    <option value="1">Bà</option>
                </select>
            	</div>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-8"> 
                <div class="form-group">
                <label for="contact_name" style="font-weight:bold">Họ và tên <?= $need ?></label>
                <input type="text" name="contact_name" id="contact_name" class="form-control"  /> 
            	</div>
              </div>  
            <div class="col-md-5 col-sm-5 col-xs-12">
            <div class="form-group">
                <label for="contact_phone" style="font-weight:bold">Điện thoại di động <?= $need ?></label> 
                <input type="text" name="contact_phone" id="contact_phone"   class="form-control" />
                 
			</div>
            </div>
        </div>
        
        <div class="row">
            <div  class="col-md-7 col-sm-7 col-xs-12" >
            	<div class="form-group">
                <label for="contact_email" style="font-weight:bold">Email <?= $need ?></label> 
                <input type="text" name="contact_email" id="contact_email" class="form-control" />
                </div>
             </div>
            <div  class="col-md-5 col-sm-5 col-xs-12">
                <div class="form-group">
                <label for="contact_city" style="font-weight:bold">Tỉnh/Thành phố</label> 
                <select name="contact_city" id="contact_city"  class="form-control">
                    <?=get_city()?>
                </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7 col-sm-7 col-xs-12">
            	 <div class="form-group">
            	<label for="contact_address" style="font-weight:bold">Địa chỉ</label> 
                <input type="text" name="contact_address" id="contact_address" class="form-control" />
            	</div>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-12">
            	 <div class="form-group">
                    <label for="contact_country" style="font-weight:bold">Quốc gia</label> 
                    <select name="contact_country" id="contact_country" class="form-control">
                        <?php listCountry();?>
                    </select>
             	</div>
             </div>
        </div>
    </div>
    <p id="err_info" class="line_error"></p>
	<h3 class="title">Yêu cầu đặc biệt</strong></h3>
    <p style="margin:0 0 10px 0px;">Khi bạn có thêm yêu cầu, hãy viết vào ô bên dưới.</p>

	<div class="field-table">
            <div><textarea name="special_request"  class="form-control" rows="5"></textarea></div>
              <input type="submit" value="Tiếp tục" title="Tiếp tục" name="sm_bookingflight" class="button pull-right mt20" id="sm_bookingflight" />
			
    </div>
     <div class="clearfix"></div>
	 
 
    </div> 
    </form>
    </div> <!--#mainDisplay-->

</div></div><!-- #colLeftNoBorder -->

<div id="colRight"  class="col-md-4">
     
    <div class="box" id="reviewprice">
        <div class="heading-with-icon">
            <div class="heading-icon skip-horizontal-flip"><i class="currency_tags-sprite currency_tags-EUR_tag_large"></i></div>
            <div class="heading-title">Chi tiết giá</div>
        </div>
        <div class="widgetblock-content">
            <fieldset>
                <table class="field-table">
                    <tr>
                        <td colspan="5">
                            <input type="hidden" id="wayflight" value="<?= $way_flight; ?>"  />
                            <strong>Giá cơ bản</strong>
                        </td>
                    </tr>
                    <tr class="calcuprice">
                        <td>Người lớn </td>
                        <td align="right"><b><?= $adults; ?></b> x </td>
                        <td style="text-align: right;"><?= format_price($price_adult)?></td>
                        <td style="padding-right:2px;padding-left:2px;text-align:center">=</td>
                        <td style="text-align: right;"><b><?= format_price($price_adult * $adults)?></b></td>
                    </tr>
                    <?php if($children != 0) { ?>
                    <tr class="calcuprice">
                        <td>Trẻ em </td>
                        <td align="right"><b><?= $children; ?></b> x</td>
                        <td style="text-align: right;"><?= format_price($price_child) ?></td>
                        <td style="padding-right:2px;padding-left:2px;text-align:center">=</td>
                        <td style="text-align: right;"><b><?= format_price($price_child * $children)?></b></td>
                    </tr>
                    <?php } ?>
                    <?php if($infants != 0) {?>
                    <tr class="calcuprice">
                        <td>Trẻ sơ sinh </td>
                        <td align="right"><b><?= $infants ?></b> x</td>
                        <td style="text-align: right;"><?= format_price($price_infant) ?></td>
                        <td style="padding-right:2px;padding-left:2px;text-align:center">=</td>
                        <td style="text-align: right;"><b><?= format_price($price_infant * $infants) ?></b></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="5"><strong>Thuế và phí</strong></td>
                    </tr>
                    <tr class="calcuprice">
                        <td>Người lớn </td>
                        <td align="right"><b><?= $adults; ?></b> x </td>
                        <td style="text-align: right;"><?= format_price($tax_adult) ?></td>
                        <td style="padding-right:2px;padding-left:2px;text-align:center">=</td>
                        <td style="text-align: right;"><b><?= format_price($tax_adult * $adults) ?></b></td>
                    </tr>
                    <?php if($children != 0) { ?>
                    <tr class="calcuprice">
                        <td>Trẻ em </td>
                        <td align="right"><b><?= $children; ?></b> x</td>
                        <td style="text-align: right;"><?= format_price($tax_child); ?></td>
                        <td style="padding-right:2px;padding-left:2px;text-align:center">=</td>
                        <td style="text-align: right;"><b><?= format_price($tax_child * $children) ?></b></td>
                    </tr>
                    <?php } ?>
                    <?php if($infants != 0) {?>
                    <tr class="calcuprice">
                        <td>Trẻ sơ sinh </td>
                        <td align="right"><b><?= $infants; ?></b> x</td>
                        <td style="text-align: right;"><?= format_price($tax_infant) ?></td>
                        <td style="padding-right:2px;padding-left:2px;text-align:center">=</td>
                        <td style="text-align: right;"><b><?= format_price($tax_infant * $infant) ?></b></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="5" style="border-top:1px solid #DDD"></td>
                    </tr>
                </table>
            </fieldset>
            <div class="total">
                <div class="cont">Tổng cộng</div><p><span id="amounttotal"><? echo format_price_nocrc($total_amount);?></span> VND</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div><!--#reviewprice-->

    <?php get_sidebar(); ?>
     
   
</div><!--#colRight-->

<div class="clearfix"></div>
</div></div> <!--end row wrap col_main+sidebar--> 
<?php
get_footer();
?>