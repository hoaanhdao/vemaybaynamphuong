<?php
/*
Template Name: Săn vé giá rẻ
*/
get_header();
?>

 
        
<div class="row">
	<div id="seekticket" class="col-md-6 search-content wg-search">
				  
			  <form method="post" action="<?= _page("cheapflightsearch")?>" id="cheapflightsForm" name="cheapflightsForm">
                 <div class="row">
                      <div class="col-md-5 col-ms-6 col-xs-6">
                        <label class="radio">
                          <input type="radio" name="direction" id="rdbFlightTypeReturn" value="0" class="rdbFlightType rdbdirection">
                          <span class="outer"><span class="inner"></span></span>Khứ hồi </label>
                      </div>
                      <div class="col-md-7 col-ms-6  col-xs-6">
                        <label class="radio">
                          <input type="radio" name="direction" id="rdbFlightTypeOneWay"  checked="checked" value="1" class="rdbFlightType rdbdirection">
                          <span class="outer"><span class="inner"></span></span>Một chiều </label>
                      </div>
                    </div>
                    <div class="row location-wrap">
                      <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                          <label>Nơi đi</label>
                          <input name="dep_code" type="text" id="dep_code" class="form-control depart" placeholder="Nơi đi" value="Hồ Chí Minh (SGN)" readonly>
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
                         
                      </div>
                      <!--.listcity-->
                      
                      <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                          <label>Nơi đến</label>
                          <input name="arv_code" type="text" id="arv_code" class="form-control arrival" placeholder="Nơi đến" value="Hà Nội (HAN)" readonly>
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
                        
                      </div>
                      <!--.listcity--> 
                      
                    </div>
                    <div class="row">
                      <div class="col-xs-6">
                        <div class="form-group selector">
                          <label>Tháng đi</label>
                           <select name="dep_date" id="dep_date" class="form-control full-width">
							<?php
							for($j=0;$j<=12;$j++)
							{
								$time = date('m-Y',strtotime( date( 'Y-m-01' )." +$j months"));
								print '<option value="'. $time .'">Tháng '.$time.'</option>';
							}
							?>
							  </select>
                        </div>
                      </div>
                      <div class="col-xs-6">
                        <div class="form-group selector">
                          <label>Tháng về</label>
                            <select name="arv_date" id="arv_date" class="form-control full-width">
							<?php
							for($j=0;$j<=12;$j++)
							{
								$time = date('m-Y',strtotime( date( 'Y-m-01' )." +$j months"));
								print '<option value="'. $time .'">Tháng '.$time.'</option>';
							}
							?>
							</select>
                        </div>
                      </div>
                    </div>
                    <div class="row ">
                      <div class="col-xs-4">
                        <div class="form-group selector">
                          <label>Người lớn</label>
                          <select name="adt" id="adt"  class="full-width">
								<?php for($i=1;$i<=30;$i++): ?>
								<option value="<?=$i?>"><?=$i?></option>
								<?php endfor; ?>
							</select> 
                        </div>
                      </div>
                      <div class="col-xs-4">
                        <div class="form-group selector">
                          <label>Trẻ em</label>
                          <select name="chd" id="chd"  class="full-width">
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
                          <select name="inf" id="inf" class="full-width">
                            <option selected="selected" value="0">0</option>
                            <?php for($i=1;$i<=4;$i++): ?>
								<option value="<?=$i?>"><?=$i?></option>
								<?php endfor; ?>
                          </select>
                        </div>
                      </div>
                    </div>
				<div class="row">
					<div class="col-md-4"  id="imgparent">
					    <div id="imgdiv">
							<img id="img" src="<?php bloginfo('template_directory')?>/captcha.php" />
							<img id="reload" src="<?php bloginfo('template_directory')?>/images/reload.png" class="pull-right"/>
						</div>
					</div>
					<div class="col-md-4">
						<input id="captcha1" name="captcha" type="text" class="form-control"> 
                    </div>
					<div class="col-md-4">
						<input name="dep" type="hidden" id="dep" value="SGN">
						<input name="des" type="hidden" id="des" value="HAN">       
						<input type="submit" name="BtnSearch" value="Tìm chuyến bay" id="BtnSearch" class="button full-width pull-right">
					</div>
				</div>
              </form>
            
			
	 
	</div>
	 <div class="col-md-6">
		<img src="<?php bloginfo('template_directory')?>/images/hd.png" alt="Hướng dẫn" class="img_responsive">
	</div>
</div> <!--end row wrap col_main+sidebar--> 
<script>
$(document).ready(function() {

});
</script>
<?php
get_footer();
?>