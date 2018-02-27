<?php
/*
Template Name: add-cheap-flights
*/
$_SESSION['fl_btn_search'] = gen_random_string(rand(9,18));
if(!empty($_POST['dep']) && !empty($_POST['arv']) && !empty($_POST['adt'])){
	$dep_code = str_replace(" ","+", $_POST['dep']);
	$arv_code = str_replace(" ","+", $_POST['arv']);
	unset($_SESSION['data_vj']);
	unset($_SESSION['data_bl']);
	unset($_SESSION['data_vn']);
	if (!empty($_POST['day_out'])){
	unset($_SESSION['cheap_flights_cart_oneway']);
	
	
	$item_data = array();
	$item_data['type'] = ($_POST['type']=='oneway')?1:0;
	$item_data['day'] = $_POST['day_out'];
	$item_data['dep'] = $_POST['dep'];
	$item_data['arv'] = $_POST['arv'];
	$item_data['adt'] = $_POST['adt'];
	$item_data['chd'] = $_POST['chd'];
	$item_data['inf'] = $_POST['inf'];
	$item_data['out'] =  str_replace("-","/",$_POST['mon_out']);
	$item_data['price'] = $_POST['price'];
	$item_data['flight_code'] = $_POST['flight_code'];
	$_SESSION['cheap_flights_cart_oneway'] = $item_data;
	//print_r($_SESSION['cheap_flights_cart_oneway']);exit();
  	} else {
  		unset($_SESSION['cheap_flights_cart_return']);
		$item_data = array();
		$item_data['type'] =$_POST['type'];
		$item_data['day'] = $_POST['day_in'];
		$item_data['dep'] = $_POST['dep'];
		$item_data['arv'] = $_POST['arv'];
		$item_data['adt'] = $_POST['adt'];
		$item_data['chd'] = $_POST['chd'];
		$item_data['inf'] = $_POST['inf'];
		$item_data['in'] =  str_replace("-","/",$_POST['mon_in']);
		$item_data['price'] = $_POST['price'];
		$item_data['flight_code'] = $_POST['flight_code'];
		$_SESSION['cheap_flights_cart_return'] = $item_data;
		//print_r($_SESSION['cheap_flights_cart_return']);exit();
		//Array ( [type] => return [day] => 31 [dep] => Hồ Chí Minh (SGN) [arv] => Hà Nội (HAN) [adt] => 1 [chd] => 0 [inf] => 0 [in] => 03-2016 [price] => 399000 [flight_code] => VJ )
  	} ?>
  	 
	 
	<div class="row">
  		<?php if(!empty($_SESSION['cheap_flights_cart_oneway'])) { ?>
		<div class="col-md-12 heading-with-icon">
            <div class="heading-icon"><i class="icons-sprite icons-plane_top_encircled"></i></div>
            <h1 class="heading-title">Hành trình của bạn</h1>
        </div>
		
	  	<div class="col-md-3 col-sm-6">
					<div class="composite-heading  clearfix">
					<div class="composite-heading-title">
						<div class="composite-heading-element heading-title">Lượt đi</div>
						<div class="composite-heading-element heading-icon tall-heading-icon"><i class="icons-sprite icons-plane_right_muted"></i></div>
					</div> 
					<div class="composite-heading-element heading-text">Ngày : <?= $_SESSION['cheap_flights_cart_oneway']['day']."/".$_SESSION['cheap_flights_cart_oneway']['out'] ?></div>
				</div> 
					<div class="booking-item-airline-logo">
						<?php if($_SESSION['cheap_flights_cart_oneway']['flight_code'] == 'BL'){?>
						<img src="http://media.vemaybay.website/images/flights/airlines_sqrt/BL.png" alt="Image Alternative text" title="Image Title" />
						<?php } else { ?>
						<img src="http://media.vemaybay.website/images/flights/airlines_sqrt/VJ.png" alt="Image Alternative text" title="Image Title" />
						<?php }?>
					</div>
					<p>Giá : <b><?= price_dot($_SESSION['cheap_flights_cart_oneway']['price']) ?></b><small> đồng</small></p>
					
		</div>
	  	<?php } ?>
	  	<?php if(!empty($_SESSION['cheap_flights_cart_return'])) { ?>
	  	<div class="col-md-3 col-sm-6">
					<div class="composite-heading  clearfix">
						<div class="composite-heading-title">
							<div class="composite-heading-element heading-title">Lượt về</div>
							<div class="composite-heading-element heading-icon tall-heading-icon"><i class="icons-sprite icons-plane_right_muted"></i></div>
						</div>						
						<div class="composite-heading-element heading-text">Ngày : <?= $_SESSION['cheap_flights_cart_return']['day']."/".$_SESSION['cheap_flights_cart_return']['in'] ?></div>
					</div>
					
					<div class="booking-item-airline-logo">
						<?php if($_SESSION['cheap_flights_cart_return']['flight_code'] == 'BL'){?>
						<img src="http://media.vemaybay.website/images/flights/airlines_sqrt/BL.png" alt="Image Alternative text" title="Image Title" />
						<?php } else { ?>
						<img src="http://media.vemaybay.website/images/flights/airlines_sqrt/VJ.png" alt="Image Alternative text" title="Image Title" />
						<?php }?>
					</div>
					<p>Giá : <b><?= price_dot($_SESSION['cheap_flights_cart_return']['price']) ?></b><small> đồng</small></p>
					
		</div>
	  	<?php } ?>
		<div class="col-md-6 col-sm-12">
	  	<?php if((!empty($_SESSION['cheap_flights_cart_return']) && !empty($_SESSION['cheap_flights_cart_oneway']) ) && strtotime(str_replace('/', '-',($_SESSION['cheap_flights_cart_return']['day']."/".$_SESSION['cheap_flights_cart_return']['in']))) < strtotime(str_replace('/', '-',($_SESSION['cheap_flights_cart_oneway']['day']."/".$_SESSION['cheap_flights_cart_oneway']['out'])))) { ?>
	  		<div class="heading-with-icon">
				<div class="heading-icon"><i class="icons-sprite icons-notification_warning"></i></div>
				<h1 class="heading-title">Vui lòng chọn ngày về lớn hơn ngày đi</h1>
			</div>
		
			
		<?php } elseif(!empty($_SESSION['cheap_flights_cart_return']) && !empty($_SESSION['cheap_flights_cart_oneway'])) {
	  	?>
		
	  <form method="post" action="<?php echo _page('flightresult'); ?>" id="frmFlightSearch" name="frmFlightSearch">
			<input name="direction" type="hidden" id="direction" value="0">
			<input name="dep" type="hidden" id="dep" value="<?= $dep_code?>">
			<input name="des" type="hidden" id="des" value="<?= $arv_code?>">
			<input name="depdate" type="hidden" id="depdate" value="<?=$_SESSION['cheap_flights_cart_oneway']['day']."/".$_SESSION['cheap_flights_cart_oneway']['out']?>">
			<input name="retdate" type="hidden" id="retdate" value="<?=$_SESSION['cheap_flights_cart_return']['day']."/".$_SESSION['cheap_flights_cart_return']['in']?>">
			<input name="adult" type="hidden" id="adult" value="<?=$_SESSION['cheap_flights_cart_oneway']['adt']?>">
			<input name="child" type="hidden" id="child" value="<?=$_SESSION['cheap_flights_cart_oneway']['chd']?>">
			<input name="infant" type="hidden" id="infant" value="<?=$_SESSION['cheap_flights_cart_oneway']['inf']?>">
		 <input type="submit" name="btnsearch<?php echo $_SESSION['fl_btn_search']; ?>" value="Tiếp tục" id="BtnSearch" class="button">
		</form>
	  	<?php } elseif ($_SESSION['cheap_flights_cart_oneway']['type'] == 1){
	  		//$params = "?out=".$_SESSION['cheap_flights_cart_oneway']['day']."/".$_SESSION['cheap_flights_cart_oneway']['out']."&dep=".$dep_code."&arv=".$arv_code."&adt=".$_SESSION['cheap_flights_cart_oneway']['adt']."&chd=".$_SESSION['cheap_flights_cart_oneway']['chd']."&inf=".$_SESSION['cheap_flights_cart_oneway']['inf'];
	  		?>
	  	 <form method="post" action="<?php echo _page('flightresult'); ?>" id="frmFlightSearch" name="frmFlightSearch">
			<input name="direction" type="hidden" id="direction" value="1">
			<input name="dep" type="hidden" id="dep" value="<?= $dep_code?>">
			<input name="des" type="hidden" id="des" value="<?= $arv_code?>">
			<input name="depdate" type="hidden" id="depdate" value="<?=$_SESSION['cheap_flights_cart_oneway']['day']."/".$_SESSION['cheap_flights_cart_oneway']['out']?>">
			<input name="adult" type="hidden" id="adult" value="<?=$_SESSION['cheap_flights_cart_oneway']['adt']?>">
			<input name="child" type="hidden" id="child" value="<?=$_SESSION['cheap_flights_cart_oneway']['chd']?>">
			<input name="infant" type="hidden" id="infant" value="<?=$_SESSION['cheap_flights_cart_oneway']['inf']?>">
			<input type="submit" name="btnsearch<?php echo $_SESSION['fl_btn_search']; ?>" value="Tiếp tục" id="BtnSearch" class="button">
		</form>
	 
	  	<?php } else { ?>
			<div class="heading-with-icon">
				<div class="heading-icon"><i class="icons-sprite icons-notification_warning"></i></div>
				<h1 class="heading-title">Vui lòng chọn đủ chiều đi và chiều về</h1>
			</div>
			 
	  	<?php }?>
		</div>	
  	</div>
<?php } ?>