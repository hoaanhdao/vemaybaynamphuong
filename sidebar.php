<?php
/**
 * Created by Notepad.
 * User: Lak
 * Date: 11/12/13
 */
?>
<?php
		$_SESSION['fl_wgbtn_search'] = gen_random_string(rand(9,18));
		if(is_single()){
			global $wp_query;
			$postid = $wp_query->post->ID;
			$dep_code = get_post_meta($postid, 'fl_dep_code', true);
			$arv_code = get_post_meta($postid, 'fl_arv_code', true);
			wp_reset_query();
		}
	?>
<div class="panel-group" id="accordion">
<div  id="wgbox"> 
<?php if(is_page("tim-chuyen-bay") && !$_SESSION['search']['isinter']): ?>

<div class="box hidden-xs" id="flightsort">
<div class="heading-with-icon">
	<div class="heading-link">
		<a href="#" data-role="clear-all-filters-link" class="filters-container-clear-all-selected-filters" style="display: none;">
			Clear all
		</a>
	</div>
	<div class="heading-icon"><i class="icons-sprite icons-magic_wand_encircled"></i></div>
	<div class="heading-title">Điều kiện</div>
</div>
     
  <form id="frmsoftflight">
	 <div class="panel">
	<p class="titleSort panel-title"><i class="fa fa-sort-desc yellow-color"></i> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse1">Sắp xếp</a></p>
	<div id="collapse1" class="panel-collapse collapse in">
		 
			<ul>
			  <li><label for="byairline" class="radio radio-inline">
				<input type="radio" name="rdsort" class="rdsort " value="airline" id="byairline" checked="checked" />
				<span class="outer"><span class="inner"></span></span>Hãng hàng không</label>
			  </li>
			  <li>
			   <label for="byprice" class="radio radio-inline">
				<input type="radio" name="rdsort" class="rdsort" value="price" id="byprice" />
			   <span class="outer"><span class="inner"></span></span>Giá từ thấp tới cao</label>
			  </li>
			  <li> <label for="bytime" class="radio radio-inline">
				<input type="radio" name="rdsort" class="rdsort" value="time" id="bytime" />
			   <span class="outer"><span class="inner"></span></span>Thời gian khởi hành</label>
			  </li>
			</ul>
		 
	</div>
  </div><!--.panel.panel-default-->
  </form>
</div>
<!--#flightsort-->

 
<div class="box hidden-xs" id="filterflight">
        
        <form id="frmfilterflight">
			<div class="panel">
					<p class="titleSort panel-title"><i class="fa fa-sort-desc yellow-color"></i> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse2">Lọc theo hãng</a></p>
				<div id="collapse2" class="panel-collapse collapse in">
					 
					   <ul>
							<li class="checked"><label for="filterall"><input type="checkbox" name="ckfilter" class="flightfilter checkbox" value="all" id="filterall" checked="checked" /> Tất cả</label></li>
							<li class="vna al checked"> <label for="filtervna"><input type="checkbox" name="ckfilter" class="flightfilter checkbox" value="vna" id="filtervna" checked="checked" />VietNam Airlines</label></li>
							<li class="vj al checked"><label for="filtervj"><input type="checkbox" name="ckfilter" class="flightfilter checkbox" value="vj" id="filtervj" checked="checked" /> Vietjet Air</label></li>
							<li class="js al checked"><label for="filterjs"><input type="checkbox" name="ckfilter" class="flightfilter checkbox" value="js" id="filterjs" checked="checked" /> Jetstar</label></li>
						</ul>
					 
				</div>
			</div><!--.panel.panel-default-->	
			
        </form>
    </div><!--#filterflight-->
 <?php endif; ?>


<?php if(is_page("tim-chuyen-bay") && $_SESSION['search']['isinter']): ?>
<div class="box hidden-xs">
	<!-- VE QUOC TE -->
<div id="filter">
		<div class="heading-with-icon">
			<div class="heading-link">
				<a href="#" data-role="clear-all-filters-link" class="filters-container-clear-all-selected-filters" style="display: none;">
					Clear all
				</a>
			</div>
			<div class="heading-icon"><i class="icons-sprite icons-magic_wand_encircled"></i></div>
			<div class="heading-title">Điều kiện lọc</div>
		</div>
   
	<div class="panel">
		<p class="titleSort panel-title"><i class="fa fa-sort-desc yellow-color"></i> 
		<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse3">Chế độ hiển thị</a></p>
		<div id="collapse3" class="panel-collapse collapse in">
 			  <div class="borderSort">
				<table class="table">
				  <tbody>
					<tr>
					  <td class=""><label class="radio" for="priceBase">
						<input type="radio" name="rblDisplayMode" checked="checked" value="base" id="priceBase">
						<span class="outer"><span class="inner"></span></span>Giá cơ bản cho 1 người</label></td>
					</tr>
					<tr>
					  <td class="checked"><label  class="radio" for="priceFull">
						<input type="radio" name="rblDisplayMode" value="full" id="priceFull">
						<span class="outer"><span class="inner"></span></span>Giá cho 1 người đã gồm thuế &amp; phí</label></td>
					</tr>
				  </tbody>
				</table>
			  </div>
  
		</div>
	</div>	
  <!--End Theo theo gia  co ban--> 
	<div class="panel">
		<p class="titleSort panel-title"><i class="fa fa-sort-desc yellow-color"></i> 
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse4">Đơn vị tiền tệ</a></p>
		<div id="collapse4" class="panel-collapse collapse in">
 			<div class="borderSort">
			 <table class="table">
			  <tbody>
				<tr>
				  <td class="checked"><label  class="radio"  for="currencyVnd">
					  <input type="radio" name="rblCurrency" checked="checked" value="VND" id="currencyVnd">
					  <span class="outer"><span class="inner"></span></span> VND</label></td>
				  <td><label  class="radio"  for="currencyUsd">
					  <input type="radio" name="rblCurrency" value="USD" id="currencyUsd">
					  <span class="outer"><span class="inner"></span></span> USD</label></td>
				  <td><label  class="radio" for="currencyEur">
					<input type="radio" name="rblCurrency" value="EUR" id="currencyEur">
					<span class="outer"><span class="inner"></span></span> EUR</label></td>
				</tr>
			  </tbody>
			</table>
			</div>
		</div>
	</div>	
			 
   <!--End Theo don vi tien te-->
  <div class="panel">
		<p class="titleSort panel-title"><i class="fa fa-sort-desc yellow-color"></i> 
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse5">Số trạm chuyển tiếp</a></p>
		<div id="collapse5" class="panel-collapse collapse in">
 			<div class="borderSort">
			<table class="theo-hang-bay table">
			  <tbody>
				<tr>
				  <td class="checked"> <label class="radio" for="TypeFlight_1">
					<input type="radio" value="" checked="checked" name="rblTypeFlight" id="TypeFlight_1">
					<span class="outer"><span class="inner"></span></span>&nbsp;Tất cả</label></td>
				</tr>
				<tr>
				  <td><label class="radio" for="TypeFlight_2">
					<input type="radio" value="direct" name="rblTypeFlight" id="TypeFlight_2">
					<span class="outer"><span class="inner"></span></span>&nbsp;Bay thẳng</label></td>
				</tr>
				<tr>
				  <td><label class="radio" for="TypeFlight_3">
					<input type="radio" value="1 stop" name="rblTypeFlight" id="TypeFlight_3">
					<span class="outer"><span class="inner"></span></span>&nbsp;1 trạm chuyển tiếp</label></td>
				</tr>
				<tr>
				  <td><label class="radio" for="TypeFlight_4">
					<input type="radio" value="2 stop" name="rblTypeFlight" id="TypeFlight_4">
					<span class="outer"><span class="inner"></span></span>&nbsp;2 trạm chuyển tiếp</label></td>
				</tr>
			  </tbody>
			</table>
			</div>
		</div>
	</div>		
    <!--End theo tram chuyen tiep-->
	<div class="panel">
		<p class="titleSort panel-title"><i class="fa fa-sort-desc yellow-color"></i> 
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse6">Hãng hàng không</a></p>
		<div id="collapse6" class="panel-collapse collapse in">
 			<div class="borderSort">
			  <table id="inter-airlines" class="theo-hang-bay table">
				  <tbody>
					<tr>
					  <td class="checked"><label class="radio" style=" text-align:left; float:left; " for="rblAirline_0">
						<input style="float:left;" type="radio" value="" name="rblAirline" checked="checked" id="rblAirline_0">
						<span class="outer"><span class="inner"></span></span> &nbsp;Hiển thị tất cả</label></td>
					  <td></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>		
 
  <!--End theo hang-->
  
   
</div><!---End filter---->
</div>
<?php endif; ?>

<div class="panel hidden-xs">
<div class="heading-with-icon">
	<div class="heading-link">
			<a href="#" data-role="clear-all-filters-link" class="filters-container-clear-all-selected-filters" style="display: none;">
				Clear all
			</a>
		</div>
		<div class="heading-icon"><i class="icons-sprite icons-magnify_lens_encircled"></i></div>
		<div class="heading-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseSearch">Tìm kiếm mới</a></div>
	</div>	
	<div  id="collapseSearch"  class="panel-collapse collapse in">
	<div class="wg-search">	
	
<ul class="wgtab">
	<li class="active"><a href="#wgflighttab" data-toggle="tab" class="flighttab"><i class="fa fa-plane"></i> Book vé</a></li>
	<li class=""><a href="#wghoteltab" data-toggle="tab" class="hoteltab"><i class="fa fa-building-o"></i> Book phòng</a></li>
</ul>	
<div class="clearfix"></div>
<div class="tab-content"> 
    <div class="tab-pane fade no-padding active in" id="wgflighttab">
	 <div class="mb10" id="wgsform">
        <form action="<?= _page("flightresult") ?>" method="post" id="frmwgsearch">
				 
				<div class="row">
					<div class="col-md-12">
						<label class="radio">
							<input type="radio" class="wgdirection checkbox-custom" name="direction" id="wgoneway" value="1" checked="checked" />
							<span class="outer"><span class="inner"></span></span>Một chiều </label>
						</label> 
						
						<label class="radio">
							<input type="radio" class="wgdirection checkbox-custom" name="direction" id="wgroundtrip" value="0"   />
							<span class="outer"><span class="inner"></span></span>Khứ hồi </label>
						</label>
				
					</div>
                </div>
				 <div class="row"> 
					 <div class="col-md-12"> 
						<?php
						$crsource=(isset($dep_code) && !empty($dep_code)) ? $dep_code : (isset($_SESSION["search"]["source"]) && !empty($_SESSION["search"]["source"]) ? $_SESSION["search"]["source"] : 'SGN');
						$crdestination=(isset($arv_code) && !empty($arv_code)) ? $arv_code : (isset($_SESSION["search"]["destination"]) && !empty($_SESSION["search"]["destination"]) ? $_SESSION["search"]["destination"] : 'HAN');
						$crdepdate=isset($arv_code) && !empty($arv_code) ? date('d/m/Y', time() + 259200) : (isset($_SESSION["search"]["depart"]) ? $_SESSION["search"]["depart"] : date('d/m/Y', time() + 259200));
						$crretdate=($_SESSION["search"]["return"])?$_SESSION["search"]["return"]:"--/--/----";
						$cradult=($_SESSION["search"]["adult"])?$_SESSION["search"]["adult"]:1;
						$crchild=($_SESSION["search"]["children"])?$_SESSION["search"]["children"]:0;
						$crinfant=($_SESSION["search"]["infant"])?$_SESSION["search"]["infant"]:0;
						
						$deptime_tmp=date("d/m/Y",time()+(60*60*(48+24)));
						$maxtime_tmp=date("d/m/Y",time()+(60*60*(8760)));
						$crrttime_tmp=date("d/m/Y",time());
						?>
						<label class="setwidth">Nơi đi</label>
						
							<select name="dep" id="wgdep" class="form-control">
								  <optgroup label="Miền Bắc">
									<option value="HAN" <?= ($crsource=="HAN")?"selected='selected'":""; ?> >Hà Nội</option>
									<option value="HPH" <?= ($crsource=="HPH")?"selected='selected'":""; ?>>Hải Phòng</option>
									<option value="DIN" <?= ($crsource=="DIN")?"selected='selected'":""; ?>>Điện Biên</option>
								</optgroup>
								<optgroup label="Miền Trung">
									<option value="THD" <?= ($crsource=="THD")?"selected='selected'":""; ?>>Thanh Hóa</option>
									<option value="VII" <?= ($crsource=="VII")?"selected='selected'":""; ?>>Vinh</option>
									<option value="HUI" <?= ($crsource=="HUI")?"selected='selected'":""; ?>>Huế</option>
									<option value="VDH" <?= ($crsource=="VDH")?"selected='selected'":""; ?>>Đồng Hới</option>
									<option value="DAD" <?= ($crsource=="DAD")?"selected='selected'":""; ?>>Đà Nẵng</option>
									<option value="PXU" <?= ($crsource=="PXU")?"selected='selected'":""; ?>>Pleiku</option>
									<option value="TBB" <?= ($crsource=="TBB")?"selected='selected'":""; ?>>Tuy Hòa</option>
								</optgroup>
								<optgroup label="Miền Nam">
									<option value="SGN" <?= ($crsource=="SGN")?"selected='selected'":""; ?>>Hồ Chí Minh</option>
									<option value="NHA" <?= ($crsource=="NHA")?"selected='selected'":""; ?>>Nha Trang</option>
									<option value="DLI" <?= ($crsource=="DLI")?"selected='selected'":""; ?>>Đà Lạt</option>
									<option value="PQC" <?= ($crsource=="PQC")?"selected='selected'":""; ?>>Phú Quốc</option>
									<option value="VCL" <?= ($crsource=="VCL")?"selected='selected'":""; ?>>Chu Lai</option>
									<option value="UIH" <?= ($crsource=="UIH")?"selected='selected'":""; ?>>Quy Nhơn</option>
									<option value="VCA" <?= ($crsource=="VCA")?"selected='selected'":""; ?>>Cần Thơ</option>
									<option value="VCS" <?= ($crsource=="VCS")?"selected='selected'":""; ?>>Côn Đảo</option>
									<option value="BMV" <?= ($crsource=="BMV")?"selected='selected'":""; ?>>Ban Mê Thuột</option>
									<option value="VKG" <?= ($crsource=="VKG")?"selected='selected'":""; ?>>Rạch Giá</option>
									<option value="CAH" <?= ($crsource=="CAH")?"selected='selected'":""; ?>>Cà Mau</option>
								</optgroup>
							</select>
						 
					</div>

					<div class="col-field-right">
						<label class="setwidth">Nơi đến</label>
						 
							<select name="des" id="wgdes" class="form-control">
								<optgroup label="Miền Bắc">
									<option value="HAN" <?= ($crdestination=="HAN")?"selected='selected'":""; ?> >Hà Nội</option>
									<option value="HPH" <?= ($crdestination=="HPH")?"selected='selected'":""; ?>>Hải Phòng</option>
									<option value="DIN" <?= ($crdestination=="DIN")?"selected='selected'":""; ?>>Điện Biên</option>
								</optgroup>
								<optgroup label="Miền Trung">
									<option value="THD" <?= ($crdestination=="THD")?"selected='selected'":""; ?>>Thanh Hóa</option>
									<option value="VII" <?= ($crdestination=="VII")?"selected='selected'":""; ?>>Vinh</option>
									<option value="HUI" <?= ($crdestination=="HUI")?"selected='selected'":""; ?>>Huế</option>
									<option value="VDH" <?= ($crdestination=="VDH")?"selected='selected'":""; ?>>Đồng Hới</option>
									<option value="DAD" <?= ($crdestination=="DAD")?"selected='selected'":""; ?>>Đà Nẵng</option>
									<option value="PXU" <?= ($crdestination=="PXU")?"selected='selected'":""; ?>>Pleiku</option>
									<option value="TBB" <?= ($crdestination=="TBB")?"selected='selected'":""; ?>>Tuy Hòa</option>
								</optgroup>
								<optgroup label="Miền Nam">
									<option value="SGN" <?= ($crdestination=="SGN")?"selected='selected'":""; ?>>Hồ Chí Minh</option>
									<option value="NHA" <?= ($crdestination=="NHA")?"selected='selected'":""; ?>>Nha Trang</option>
									<option value="DLI" <?= ($crdestination=="DLI")?"selected='selected'":""; ?>>Đà Lạt</option>
									<option value="PQC" <?= ($crdestination=="PQC")?"selected='selected'":""; ?>>Phú Quốc</option>
									<option value="VCL" <?= ($crdestination=="VCL")?"selected='selected'":""; ?>>Chu Lai</option>
									<option value="UIH" <?= ($crdestination=="UIH")?"selected='selected'":""; ?>>Quy Nhơn</option>
									<option value="VCA" <?= ($crdestination=="VCA")?"selected='selected'":""; ?>>Cần Thơ</option>
									<option value="VCS" <?= ($crdestination=="VCS")?"selected='selected'":""; ?>>Côn Đảo</option>
									<option value="BMV" <?= ($crdestination=="BMV")?"selected='selected'":""; ?>>Ban Mê Thuột</option>
									<option value="VKG" <?= ($crdestination=="VKG")?"selected='selected'":""; ?>>Rạch Giá</option>
									<option value="CAH" <?= ($crdestination=="CAH")?"selected='selected'":""; ?>>Cà Mau</option>
								</optgroup>
							</select>
						 
					</div>

                </div>
				 <div class="row"> 
					<div class="col-md-12">
						<label class="setwidth">Ngày đi</label>
						<div class="datepicker-wrap">	
							<input type="text" class="dates form-control" data-next="#wgretdate" after_function="change_start_date" minDate="<?= $crrttime_tmp?>" maxdate="<?=$maxtime_tmp?>"  default_max_date="<?=$maxtime_tmp?>" default="<?=$crdepdate?>" value="<?= $crdepdate?>"  class="dates" name="depdate" id="wgdepdate"   autocomplete="off" readonly="readonly">
						</div>	
					</div>

					<div class="col-md-12"> 
						<label class="setwidth">Ngày về</label>
						<div class="datepicker-wrap">
						    <input type="text" class="dates form-control" after_function="change_return_date" minDate="<?= $deptime_tmp?>" maxdate="<?=$maxtime_tmp?>"  default="<?=$crretdate?>" value="<?=$crretdate?>"  class="dates" name="retdate" id="wgretdate"   autocomplete="off" readonly="readonly">
						</div>
					</div>
				 </div>
               <div class="row"> 
                     
                       <div class="col-md-4 col-md-4 col-sm-4 col-xs-4 wgquantity">   
                            <label for="wgadult"><label>Người lớn</label></label>
                            <select id="wgadult" name="adult" class="form-control">
                                <?php for($i=1;$i<=30;$i++): ?>
                                <option value="<?=$i?>" <?=($cradult==$i)?"selected='selected'":""; ?> ><?=$i?></option>
                                <?php endfor; ?>
                            </select>
                        </div>



                        <div class="col-md-4 col-md-4 col-sm-4 col-xs-4 wgquantity">
                            <label for="wgchild"><label>Trẻ em</label></label>
                            <select id="wgchild" name="child" class="form-control">
                                <?php for($i=0;$i<=5;$i++): ?>
                                <option value="<?=$i?>" <?=($crchild==$i)?"selected='selected'":""; ?> ><?=$i?></option>
                                <?php endfor; ?>
                            </select>
                        </div><!--fqtity-->

                        <div class="col-md-4 col-md-4 col-sm-4 col-xs-4 wgquantity">
                            <label for="wginfant"><label>Em bé</label></label>
                            <select id="wginfant" name="infant" class="form-control">
                                <?php for($i=0;$i<=5;$i++): ?>
                                <option value="<?=$i?>" <?=($crinfant==$i)?"selected='selected'":""; ?> ><?=$i?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                       
                     
                </div>
					<div class="row">
						<div class="col-xs-6 mt20">
							<a class="lunar-link" href="javascript: void(0);" onclick=" javascript:OpenPopup('https://vemaybaynamphuong.com/lunar/xem-am-lich.html','WindowName','510','280','scrollbars=1');">Xem âm lịch</a>
						</div>
						<div class="col-xs-6">
							<button class="button  mt20 pull-right" type="submit"  name="wgbtnsearch<?php echo $_SESSION['fl_wgbtn_search']; ?>" id="wgbtnsearch<?php echo $_SESSION['fl_wgbtn_search']; ?>">Tìm chuyến bay</button>
						</div>
					</div>
                        
						
				
              </form><!--#frmwgsearch-->        
            </div>
   
	</div>
	
	<div class="tab-pane fade no-padding" id="wghoteltab">
	   <div id="booking-item-dates-change" class="booking-item-dates-change">
						 <form id="hotelForm" method="post" action="<?php bloginfo('stylesheet_directory'); ?>/core/hotel-search.php" /> 
                                <div class="location form-group ">
									  <label>Địa điểm</label>
									   <input class="typeahead form-control"   placeholder="Tên khu vực hoặc khách sạn" id="hotel_destination" name="hotel_destination" type="text"  />
								</div>
                                <div class="input-daterange" data-date-format="dd/mm/yyyy">
                                    <div class="form-group">
                                        <label>Ngày đến</label>
                                        <input id="start"  name="start" type="text"  readonly="readonly" data-next="#end" after_function="change_checkin_date" minDate="<?= $crrttime_tmp?>" maxdate="<?=$maxtime_tmp?>"  default_max_date="<?=$maxtime_tmp?>" default="<?=$deptime_tmp?>" value="<?=$deptime_tmp?>" class="form-control dates input">
									</div>
                                    <div class="form-group">
                                        <label>Ngày đi</label>
                                        <input id="end" name="end" type="text" readonly="readonly" after_function="change_checkout_date" minDate="<?= $deptime_tmp?>" maxdate="<?=$maxtime_tmp?>"  default="--/--/----" value="--/--/----" class="form-control dates input">
										
                                    </div>
                                </div>
                                <div class="form-group form-group-select-plus">
                                     <label for="adult">Khách</label>
										  
									   <select name="adult" id="adult" class="form-control">
										  <?php 
											for($i=1; $i<=10; $i++){
												echo '<option value="'.$i.'">'.$i.' Khách</option>';
											}
										  ?>
										</select>  
                                   
                                </div>
								<div class="row"> 
									<div class="col-xs-6 mt20">
										<a class="lunar-link" href="javascript: void(0);" onclick=" javascript:OpenPopup('https://vemaybaynamphuong.com/lunar/xem-am-lich.html','WindowName','510','280','scrollbars=1');">Xem âm lịch</a>
									</div>
									<div class="col-xs-6">	
										<button class="button  pull-right" type="submit">Tìm giá</button>
									</div>
								</div>
                            </form>
						</div> 	
	</div>
</div>
	
</div><!--wg-search--> 
</div>
</div><!--panel-->          
<?php if(is_single() || is_category()) { ?>
    <div class="Recent_posts mt20">
      <ul>
		<?php
		 global $post;
		$post_id = $post->ID; // current post id
		$cat = get_the_category(); 
		$current_cat_id = $cat[0]->cat_ID; // current category Id 
	
		$args = array( 'numberposts' => 5, 'order'=> 'DESC', 'orderby' => 'date','post_status' => 'publish',  "category__in"=>$current_cat_id, );
		$myposts = get_posts( $args );
		foreach( $myposts as $post ) :	setup_postdata($post);
		
		?>
        <li class="post format-"><a href="<?php the_permalink() ?>">
          <div class="photo"> <img src="<?php echo (_getHinhDaiDien($post->ID) != '' ? _getHinhDaiDien($post->ID) : v5s_catch_that_image()); ?>" width="80" height="80" class="scale-with-grid wp-post-image" ></div>
          <div class="desc">
            <h6 class=""><?php echo  wp_trim_words(get_the_title(),7) ?></h6>
            <span class="date"> 
           <?php the_time('d/m/Y');?>
            </span></div>
          </a> </li>
		<?php
		endforeach;
		wp_reset_postdata();
?>      

	</ul>
    </div>
	

    <?php	 
	}
  ?>
  
	<div id="sidebar-support" class="mt20">
	<img class="img-responsive" src="<?php bloginfo('template_directory')?>/images/ads1.jpg" alt=""/><br>
	<img class="img-responsive" src="<?php bloginfo('template_directory')?>/images/ads2.jpg" alt=""/>
	</div>


</div><!--End #wgbox-->
</div> <!--End #accordion--> 