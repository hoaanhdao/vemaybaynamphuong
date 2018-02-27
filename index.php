<?php 
  $_SESSION['fl_btn_search'] = gen_random_string(rand(9,18));
  get_header(); 
?>
<!--START MAIN CONTAINER-->
<?php
$pickuptime_tmp=date("d/m/Y",time()+(60*60));
$deptime_tmp=date("d/m/Y",time()+(60*60*(48+24)));
$maxtime_tmp=date("d/m/Y",time()+(60*60*(8760)));
$crrttime_tmp=date("d/m/Y",time());
?>
  <!-- TOP AREA -->
        <div class="top-area show-onload">
            <div class="bg-holder full">
		         <div class="bg-front bg-front-mob-rel">
					  <div class="container">
        <div class="row search-tabs search-tabs-bg search-tabs-abs">
          <div class="col-md-5 col-sm-12 tabbable">
            <ul class="nav nav-tabs" id="myTab">
              <li  class="active"><a href="#flighttab" data-toggle="tab"><i class="fa fa-plane"></i> <span >Book vé</span></a> </li>
              <li><a href="#hoteltab" data-toggle="tab"><i class="fa fa-building-o"></i> <span >Book phòng</span></a> </li>
              <li><a href="#cartab" data-toggle="tab"><i class="fa fa-car"></i> <span >Book xe</span></a> </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane fade  in active" id="flighttab">
                <div class="search-content">
                  <form method="post" action="<?php echo _page('flightresult'); ?>" id="frmFlightSearch" name="frmFlightSearch">
                    <div class="row form-group">
                      <div class="col-md-5 col-ms-6 col-xs-6">
                        <label class="radio">
                          <input type="radio" name="direction" id="rdbFlightTypeOneWay"  checked="checked" value="1" class="rdbFlightType rdbdirection">
                          <span class="outer"><span class="inner"></span></span>Một chiều </label>
                      </div>
					  <div class="col-md-7 col-ms-6  col-xs-6">
                        <label class="radio">
                          <input type="radio" name="direction" id="rdbFlightTypeReturn" value="0" class="rdbFlightType rdbdirection">
                          <span class="outer"><span class="inner"></span></span>Khứ hồi </label>
                      </div>
                    </div>

                    <div class="row location-wrap">
                      <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                          <label>Nơi đi</label>
                          <input name="depinput" type="text" id="depinput" class="form-control depart" placeholder="Nơi đi" value="Hồ Chí Minh (SGN)" readonly>
                        </div>
                      </div>
                      <div id="listDep" class="listcity row">
                        <div class="list-head col-xs-12">Chọn điểm đi <a href="#" id="close-dep" class="close">×</a></div>
                        <div class="col-xs-6 col-sm-4">
                          <h4>MIỀN BẮC</h4>
                          <ul class="selectcity first">
                            <li><a href="#" data-city="HAN">Hà Nội (HAN)</a></li>
                            <li><a href="#" data-city="HPH">Hải Phòng (HPH)</a></li>
                            <li><a href="#" data-city="DIN">Điện Biên (DIN)</a></li>
                          </ul>
                          <h4>MIỀN NAM</h4>
                          <ul class="selectcity">
                            <li><a href="#" data-city="SGN">Hồ Chí Minh (SGN)</a></li>
                            <li><a href="#" data-city="VCA">Cần Thơ (VCA)</a></li>
                            <li><a href="#" data-city="VCS">Côn Đảo (VCS)</a></li>
                            <li><a href="#" data-city="PQC">Phú Quốc (PQC)</a></li>
                            <li><a href="#" data-city="VKG">Rạch Giá (VKG)</a></li>
                            <li><a href="#" data-city="CAH">Cà Mau (CAH)</a></li>
                          </ul>
                        </div>
                        <div class="col-xs-6 col-sm-4">
                          <h4>MIỀN TRUNG</h4>
                          <ul class="selectcity">
                            <li><a href="#" data-city="DAD">Đà Nẵng (DAD)</a></li>
                            <li><a href="#" data-city="THD">Thanh Hóa (THD)</a></li>
                            <li><a href="#" data-city="VII">Vinh (VII)</a></li>
                            <li><a href="#" data-city="HUI">Huế (HUI)</a></li>
                            <li><a href="#" data-city="VDH">Đồng Hới (VDH)</a></li>
                            <li><a href="#" data-city="VCL">Chu Lai (VCL)</a></li>
                            <li><a href="#" data-city="UIH">Quy Nhơn (UIH)</a></li>
                            <li><a href="#" data-city="TBB">Tuy Hòa (TBB)</a></li>
                            <li><a href="#" data-city="CXR">Nha Trang (CXR)</a></li>
                            <li><a href="#" data-city="PXU">Pleiku (PXU)</a></li>
                            <li><a href="#" data-city="BMV">Ban Mê Thuột (BMV)</a></li>
                            <li><a href="#" data-city="DLI">Đà Lạt (DLI)</a></li>
                          </ul>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                          <h4>QUỐC TẾ</h4>
                          <ul class="selectcity">
                            <li>Vui lòng nhập tên thành phố hoặc mã sân bay<br>
                            </li>
                            <li>
                              <input type="text" autocomplete="off" id="inter-city-dep" class="form-control ac-city"  value="">
                            </li>
                          </ul>
                        </div>
                      </div>
                      <!--.listcity-->
                      
                      <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                          <label>Nơi đến</label>
                          <input name="desinput" type="text" id="desinput" class="form-control arrival" placeholder="Nơi đến" value="Hà Nội (HAN)" readonly>
                        </div>
                      </div>
                      <div id="listDes" class="listcity">
                        <div class="list-head col-xs-12">Chọn điểm đến <a href="#" id="close-arv" class="close">×</a> </div>
                        <div class="col-xs-6 col-sm-4">
                          <h4>MIỀN BẮC</h4>
                          <ul class="selectcity first">
                            <li><a href="#" data-city="HAN">Hà Nội (HAN)</a></li>
                            <li><a href="#" data-city="HPH">Hải Phòng (HPH)</a></li>
                            <li><a href="#" data-city="DIN">Điện Biên (DIN)</a></li>
                          </ul>
                          <h4>MIỀN NAM</h4>
                          <ul class="selectcity">
                            <li><a href="#" data-city="SGN">Hồ Chí Minh (SGN)</a></li>
                            <li><a href="#" data-city="VCA">Cần Thơ (VCA)</a></li>
                            <li><a href="#" data-city="VCS">Côn Đảo (VCS)</a></li>
                            <li><a href="#" data-city="PQC">Phú Quốc (PQC)</a></li>
                            <li><a href="#" data-city="VKG">Rạch Giá (VKG)</a></li>
                            <li><a href="#" data-city="CAH">Cà Mau (CAH)</a></li>
                          </ul>
                        </div>
                        <div class="col-xs-6 col-sm-4">
                          <h4>MIỀN TRUNG</h4>
                          <ul class="selectcity">
                            <li><a href="#" data-city="DAD">Đà Nẵng (DAD)</a></li>
                            <li><a href="#" data-city="THD">Thanh Hóa (THD)</a></li>
                            <li><a href="#" data-city="VII">Vinh (VII)</a></li>
                            <li><a href="#" data-city="HUI">Huế (HUI)</a></li>
                            <li><a href="#" data-city="VDH">Đồng Hới (VDH)</a></li>
                            <li><a href="#" data-city="VCL">Chu Lai (VCL)</a></li>
                            <li><a href="#" data-city="UIH">Quy Nhơn (UIH)</a></li>
                            <li><a href="#" data-city="TBB">Tuy Hòa (TBB)</a></li>
                            <li><a href="#" data-city="CXR">Nha Trang (CXR)</a></li>
                            <li><a href="#" data-city="PXU">Pleiku (PXU)</a></li>
                            <li><a href="#" data-city="BMV">Ban Mê Thuột (BMV)</a></li>
                            <li><a href="#" data-city="DLI">Đà Lạt (DLI)</a></li>
                          </ul>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                          <h4>QUỐC TẾ</h4>
                          <ul class="selectcity">
                            <li>Vui lòng nhập tên thành phố hoặc mã sân bay<br>
                            </li>
                            <li>
                              <input type="text" autocomplete="off" id="inter-city-arv" class="form-control ac_city" value="">
                            </li>
                          </ul>
                        </div>
                      </div>
                      <!--.listcity--> 
                      
                    </div>
                    <div class="row">
                      <div class="col-xs-6">
                        <div class="form-group">
                          <label>Ngày đi</label>
                          <div class="datepicker-wrap">
                            <input id="depdate" readonly="readonly"  class="form-control"  data-next="#retdate" after_function="change_start_date" minDate="<?= $crrttime_tmp?>" maxdate="<?=$maxtime_tmp?>"  default_max_date="<?=$maxtime_tmp?>" name="depdate" type="text" default="<?=$deptime_tmp?>" value="<?=$deptime_tmp?>" autocomplete="off">
                          </div>
                        </div>
                        <div class="row-column-lunar-description-left search-start-date-lunar-des hidden-xs"> <span class="text"></span> </div>
                      </div>
                      <div class="col-xs-6">
                        <div class="form-group">
                          <label>Ngày về</label>
                          <div class="datepicker-wrap">
                            <input id="retdate" readonly="readonly"  class="form-control" after_function="change_return_date" minDate="<?= $deptime_tmp?>" maxdate="<?=$maxtime_tmp?>" name="retdate" type="text" default="--/--/----" value="--/--/----"  autocomplete="off">
                          </div>
                        </div>
                        <div class="row-column-lunar-description-right search-return-date-lunar-des hidden-xs"> <span class="text"></span> </div>
                      </div>
                    </div>
                    <div class="row type-passenger">
                      <div class="col-xs-4">
                        <div class="form-group selector">
                          <label>Người lớn</label>
                          <select name="adult" id="adult"  class="full-width">
								<?php for($i=1;$i<=30;$i++): ?>
								<option value="<?=$i?>"><?=$i?></option>
								<?php endfor; ?>
							</select> 
                        </div>
                      </div>
                      <div class="col-xs-4">
                        <div class="form-group selector">
                          <label>Trẻ em</label>
                          <select name="child" id="child"  class="full-width">
                            <option selected="selected" value="0">0</option>
                            <?php for($i=1;$i<=4;$i++): ?>
								<option value="<?=$i?>"><?=$i?></option>
								<?php endfor; ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-4">
                        <div class="form-group  selector">
                          <label>Em bé</label>
                          <select name="infant" id="infant" class="full-width">
                            <option selected="selected" value="0">0</option>
                            <?php for($i=1;$i<=4;$i++): ?>
								<option value="<?=$i?>"><?=$i?></option>
								<?php endfor; ?>
                          </select>
                        </div>
                      </div>
					  <div class="col-xs-6">
					  	<a class="mt20 lunar-link" href="javascript: void(0);" onclick=" javascript:OpenPopup('https://vemaybaynamphuong.com/lunar/xem-am-lich.html','WindowName','510','280','scrollbars=1');">Xem âm lịch</a>
					  </div>
                      <div class="col-xs-6">
                        <input type="submit" name="btnsearch<?php echo $_SESSION['fl_btn_search']; ?>" value="Tìm chuyến bay" id="BtnSearch<?php echo $_SESSION['fl_btn_search']; ?>" class="button pull-right">
                        <input name="dep" type="hidden" id="dep" value="SGN">
                        <input name="des" type="hidden" id="des" value="HAN">
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="hoteltab">
                <form id="hotelForm" method="post" class="location-search" action="<?php bloginfo('stylesheet_directory'); ?>/core/hotel-search.php" />
                
                <div class="row">
                  <div class="location  col-md-12 col-sm-12 col-xs-12" >
                    <div class="form-group">
                      <label>Địa điểm</label>
                      <input class="typeahead form-control"   placeholder="Địa điểm" id="hotel_destination" name="hotel_destination" type="text"  />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="location  col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Nhận phòng</label>
                      <input id="start"  name="start" type="text"  readonly="readonly" data-next="#end" after_function="change_checkin_date" minDate="<?= $crrttime_tmp?>" maxdate="<?=$maxtime_tmp?>"  default_max_date="<?=$maxtime_tmp?>" default="<?=$deptime_tmp?>" value="<?=$deptime_tmp?>" class="form-control dates input">
                    </div>
                    <div class="row-column-lunar-description-left checkin-date-lunar-des hidden-xs"> <span class="text"></span> </div>
                  </div>
                  <div class="location  col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Trả phòng</label>
                      <input id="end" name="end" type="text" readonly="readonly" after_function="change_checkout_date" minDate="<?= $deptime_tmp?>" maxdate="<?=$maxtime_tmp?>"  default="--/--/----" value="--/--/----" class="form-control dates input">
                    </div>
                    <div class="row-column-lunar-description-right checkout-date-lunar-des hidden-xs"> <span class="text"></span> </div>
                  </div>
                </div>
                <div class="row">
                  <ul class="type-passenger form-group">
                    <li class=" col-md-4 col-sm-4 col-xs-6">
                      <label for="room">Phòng</label>
                      <select name="room" id="room" class="form-control">
                        <?php 
						for($i=1; $i<=10; $i++){
							echo '<option value="'.$i.'">'.$i.' Phòng</option>';
						}
					  ?>
                      </select>
                    </li>
                    <li class=" col-md-4 col-sm-4 col-xs-6">
                      <label for="guest">Khách</label>
                      <select name="guest" id="guest" class="form-control">
                        <?php 
						for($i=1; $i<=10; $i++){
							echo '<option value="'.$i.'">'.$i.' Khách</option>';
						}
					  ?>
                      </select>
                    </li>
                    <li class=" col-md-4 col-sm-4 col-xs-12">
                      <button class="button orange mt25  pull-right" type="submit"  name="btnsearch" id="btntim">Tìm khách sạn</button>
                    </li>
                  </ul>
                </div>
				<div class="row">
					<div class="col-xs-12 mt20">
					  	<a class="mt20 lunar-link" href="javascript: void(0);" onclick=" javascript:OpenPopup('https://vemaybaynamphuong.com/lunar/xem-am-lich.html','WindowName','510','280','scrollbars=1');">Xem âm lịch</a>
					  </div>
				</div>
                 
                </form>
              </div>
              <div class="tab-pane fade" id="cartab">
                <form method="post" action="" id="frmBooking" name="frmBooking">
                  <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="location-wrap form-group">
                        <label>Nơi đi</label>
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
                      <div class="location-wrap form-group">
                        <label>Nơi đến</label>
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
                        <div class="datepicker-wrap">
                          <input id="dropoffDate" name="dropoffDate" readonly  class="form-control" after_function="change_dropoff_date" minDate="<?=$pickuptime_tmp?>" maxdate="<?=$maxtime_tmp?>" type="text" default="--/--/----" value="--/--/----"  autocomplete="off">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-5">
                      <div class="form-group">
                        <label>Giờ </label>
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
                    <div class="col-md-3 col-sm-6 col-xs-6">
                      <div class="form-group">
                        <label>Điện thoại</label>
                        <input  id="cusPhone" name="cusPhone" class="form-control" type="text" />
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                      <div class="form-group">
                        <label>Số khách</label>
                        <select id="cusQty" name="cusQty" class="form-control">
                        <?php 
						for($i=1; $i<=16; $i++){
						echo '<option value="'.$i.'">'.$i.'</option>';
						}
						?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="submit" name="sm_rentalCar" value="Đặt thuê xe" id="BtnBookCar" class="button orange mt25 pull-right">
                    </div>
                  </div>
				  <div class="row">
					<div class="col-xs-12 mt20">
					  	<a class="mt20 lunar-link" href="javascript: void(0);" onclick=" javascript:OpenPopup('https://vemaybaynamphuong.com/lunar/xem-am-lich.html','WindowName','510','280','scrollbars=1');">Xem âm lịch</a>
					  </div>
				</div>
                </form>
                <span class="notice-success">Hệ thống đang xử lý .....</span> </div>
              <?php 
			if(isset($_POST['sm_rentalCar']) && trim($_POST['cusPhone']) != ''){
			//******************************************//
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
            </div>
          </div>
        </div>
      </div>
    
				</div>
				<div class="owl-carousel owl-slider owl-carousel-area visible-lg" id="owl-carousel-slider" data-nav="false">
				 
					 <?php
						$theme_slider=get_option("theme_slider");
						if($theme_slider):
						$i = 0;
							foreach($theme_slider as $slider){
								   if($slider["link"]) echo '<a href="" >';
								?>
								 
						 <div class="bg-holder full">
						  <div class="bg-img" style="background-image:url('<?php echo $slider["img"]?>');"></div>
						</div>
								<?php
									if($slider["link"]) echo '</a>';
							$i++;
							}
						endif;
					?>
				
				</div> 
				<div class="bg-img hidden-lg" style="background-image:url(<?php bloginfo('template_directory')?>/images/slide.jpg);"></div>
               
			
            </div>
        </div>
        <!-- END TOP AREA  -->

 




<div class="container company-description">
  <div class="row gap">
    <div class="col-xs-12 col-sm-3">
      <div class="col-sm-6 col-md-12 columns hidden-sm mb20"><span class="company-description-title">App săn khuyến mãi</span><br>
        <span class="company-description-text"><a href="https://play.google.com/store/apps/details?id=com.namphuong.vemaybay" target="_blank"  title="App vé máy bay">Vé máy bay (Playstore)</a></span>
		<br><img src="<?php bloginfo('template_directory')?>/images/icon-app.png"></div>
      <div class="col-sm-12 columns visible-sm">
        <div class="left company-description-title">App săn khuyến mãi</div>
        <div class="left company-description-text"><a href="https://play.google.com/store/apps/details?id=com.namphuong.vemaybay" target="_blank"  title="App vé máy bay">Vé máy bay (Playstore)</a></div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-9 bordered-section">
      <div class="row"> 
	  <span class="left-arrow-container hidden-sm hidden-xs"> 
		  <span class="left-arrow-container left-arrow-outter"></span> 
		  <span class="left-arrow-container left-arrow-inner"></span> </span> 
	  <span class="top-arrow-container visible-sm visible-xs"> 
		  <span class="top-arrow-container top-arrow-outter"></span> 
		  <span class="top-arrow-container top-arrow-inner"></span> </span>
        <div class="col-xs-12 col-sm-4">
          <div class="icon-box style8 animated" data-animation-type="slideInUp" data-animation-delay="0.6"> <i class="icons-sprite icons-badge_muted"></i>
            <h4 class="box-title"><a href="<?php bloginfo('url'); ?>/san-ve-gia-re" title="Vé máy bay giá rẻ">Săn vé giá rẻ</a></h4>
            <p class="description">
				Tìm thông tin chuyến bay, đặt vé máy bay trực tuyến, vé giá rẻ khuyến mãi Vietnam Airlines, Vietjet, Jetstar, vé máy bay tết 2016.
				</p>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="icon-box style8 animated" data-animation-type="slideInUp" data-animation-delay="0.9"> <i class="icons-sprite icons-crest"></i>
            <h4 class="box-title"><a href="<?php bloginfo('url'); ?>/book-xe-du-lich" title="Book xe du lịch">Book xe du lịch</a></h4>
            <p class="description"> 
			 Book xe du lịch ở HCM, cho thuê xe du lịch, dịch vụ đưa đón tận nơi từ sân bay về tới nội thành. Bạn cần một dịch vụ chuyên nghiệp với mức giá hợp lý...
			</p>		
		</div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="icon-box style8 animated" data-animation-type="slideInUp" data-animation-delay="1.2"> <i class="icons-sprite icons-smiley"></i>
            <h4 class="box-title"><a href="<?php bloginfo('url'); ?>/book-khach-san" title="Book khách sạn">Book khách sạn</a></h4>
            <p class="description">
			Hệ thống thông tin khách sạn cập nhật 247. Hướng dẫn book phòng khách sạn giá rẻ, bình dân. Web đặt phòng trực tuyến hàng đầu tại VN.
			</p>		
		  </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container news-col">
    
</div>

<!--END MAIN CONTAINER-->

<?php get_footer(); ?>
