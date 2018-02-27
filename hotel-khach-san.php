<?php 
if(!empty($_SESSION['hotel_cart'])){ unset($_SESSION['hotel_cart']);}
get_header();?>
<script type="text/javascript">
NProgress.start();
</script>
<?php
/*
Template Name: khach-san
*/  $post_data = array();
    if ($_GET['dep'] == NULL OR $_GET['ret'] == NULL) {
        $day_dep = date('d');
        $day_ret = date('d')+1;
        $mon_dep = date('m');
        $mon_ret = date('m');
        $year_dep = '20'.date('y');
        $year_ret = '20'.date('y');
    } else {
        $day_dep = substr($_GET['dep'],0,2);
        $day_ret = substr($_GET['ret'],0,2);
        $mon_dep = substr($_GET['dep'],3,2);
        $mon_ret = substr($_GET['ret'],3,2);
        $year_dep = substr($_GET['dep'],6,4);
        $year_ret = substr($_GET['ret'],6,4);
    }
    $post_data['id'] = $_GET["id"];
    $post_data['type'] = 'hotel';
    $post_data['review'] = 1;
    $post_data['featured'] = 1;
    $post_data['score'] = 1;
    $post_data['room'] = 1;
    $post_data['day_dep'] = $day_dep;
    $post_data['day_ret'] = $day_ret;
    $post_data['mon_dep'] = $mon_dep;
    $post_data['mon_ret'] = $mon_ret;
    $post_data['year_dep'] = $year_dep;
    $post_data['year_ret'] = $year_ret;
    $post_data['adult'] = $_GET['ad'];
    $hotel = chi_tiet_khach_san($post_data); ?>   
 
            
            <div class="booking-item-details">
				<?php if ($hotel['rooms'][0]['room_price'] == null ) { ?>
                <div class="row gap">
				<div class="col-md-12 alert alert-warning">
					<button class="close" type="button" data-dismiss="alert"><span aria-hidden="true">&times;</span>
					</button>
					<p class="text-small">Xin lỗi chúng tôi đã hết phòng cho ngày <?= $day_dep."/".$mon_dep."/".$year_dep ?> xem ngày khác.</a></p>
					
				</div>
				  <div class="col-md-3 wgbox col-md-push-4">
					<div class="wg-search mb30">
                    <div id="wghoteltab">
					<div id="booking-item-dates-change" class="booking-item-dates-change">
					<?php $deptime_tmp=date("d/m/Y",time()+(60*60*(48+24)));
						$maxtime_tmp=date("d/m/Y",time()+(60*60*(8760)));
						$crrttime_tmp=date("d/m/Y",time());
					?>
						  <form method="post" action="<?php bloginfo('stylesheet_directory'); ?>/core/hotel_live_price.php" />
                                <input id="current_id" value=<?= $_GET['id'] ?> name="current_id" style="display: none;" /><br>
                                <div class="input-daterange" data-date-format="dd/mm/yyyy">
                                    <div class="form-group form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                        <label>Ngày đến</label>
                                        <input id="start"  name="start" type="text"  readonly="readonly" data-next="#end" after_function="change_checkin_date" minDate="<?= $crrttime_tmp?>" maxdate="<?=$maxtime_tmp?>"  default_max_date="<?=$maxtime_tmp?>" default="<?=$deptime_tmp?>" value="<?=$deptime_tmp?>" class="form-control dates input">
									</div>
                                    <div class="form-group form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-hightlight"></i>
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
                                <button class="btn button wide-fat dark" type="submit">Tìm giá</button>
                            </form>
						</div> 	
	</div>  
					 </div>
                        <div id="cart-item"></div>
                    </div>
				</div>
				 
				<?php }else{?>	
				<header class="booking-item-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="lh1em"><?= $hotel['name'] ?></h2>
                            <p class="lh1em text-small"><i class="fa fa-map-marker"></i> <?= $hotel['add'].", ".$hotel['ext'].", ".$hotel['local'] ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="booking-item-header-price"><small>Chỉ</small>  <span class="text-lg"><?= price_dot($hotel['rooms'][0]['room_price']) ?></span>/ <?=$hotel['rooms'][0]['room_days'] ?> đêm</p>
                             
                        </div>
                    </div>
                </header>
                <div class="row">
                    <div class="col-md-8">
                        <div class="tabbable booking-details-tabbable">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="active"><a href="#tab-1" data-toggle="tab"><i class="fa fa-camera"></i>Hình ảnh</a>
                                </li>
                                <li><a href="#google-map-tab" data-toggle="tab"><i class="fa fa-map-marker"></i>Bản đồ</a>
                                </li>
                                <li><a href="#tab-4" data-toggle="tab"><i class="fa fa-signal"></i>Đánh giá</a>
                                </li>
                                <li><a href="#tab-5" data-toggle="tab"><i class="fa fa-asterisk"></i>Tiện nghi</a>
                                </li>
                                <li><a href="#tab-7" data-toggle="tab"><i class="fa fa-comments"></i>Nhận xét</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab-1">
                                    <div class="fotorama" data-allowfullscreen="true" data-nav="thumbs">
                                        <?php foreach ($hotel['img'] as $img) { ?>
                                        <img src="<?= $img[0] ?>" alt="<?= $hotel['name'] ?>" title="<?= $hotel['name'] ?>" />
                                        <?php } ?>
                                    </div>
                                </div>
                                        <script type="text/javascript">
                                        NProgress.inc();
                                        </script>
                                <div class="tab-pane fade" id="tab-2">
                                    <div class="mt20">
                                        <p><?= $hotel['des'] ?></p>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="google-map-tab">
                                    <div id="map-canvas" style="width:100%; height:400px;"></div>
                                </div>
                                <script>
                                    if ($('#map-canvas').length) {
                                        var map,
                                            service;
                                    
                                        jQuery(function($) {
                                            $(document).ready(function() {
                                                var latlng = new google.maps.LatLng(<?= $hotel['geo']?>);
                                                var myOptions = {
                                                    zoom: 16,
                                                    center: latlng,
                                                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                                                    scrollwheel: false
                                                };
                                    
                                                map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
                                    
                                    
                                                var marker = new google.maps.Marker({
                                                    position: latlng,
                                                    map: map
                                                });
                                                marker.setMap(map);
                                    
                                    
                                                $('a[href="#google-map-tab"]').on('shown.bs.tab', function(e) {
                                                    google.maps.event.trigger(map, 'resize');
                                                    map.setCenter(latlng);
                                                });
                                            });
                                        });
                                    }
                                </script>
                                <div class="tab-pane fade" id="tab-4">
                                    <div class="mt20">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="lhem">Điểm đánh giá</h4>
                                                <ul class="list booking-item-raiting-list">
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Sạch sẽ</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['clean'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['clean'] ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Thoải mái</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['comfort'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['comfort'] ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Tiện nghi</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['facilities'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['facilities'] ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Nhân viên phục vụ</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['staff'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['staff'] ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Địa điểm</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['location'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['location'] ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Đáng giá tiền</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['money'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['money'] ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Wifi</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['wifi'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['wifi'] ?></div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab-5">
                                    <div class="row mt20">
                                        <div class="col-md-4">
                                            <ul class="booking-item-features booking-item-features-expand mb30 clearfix">
                                                <?php if($hotel['featured']['internet'] == 1) { ?>
                                                <li><i class="im im-wi-fi"></i><span class="booking-item-feature-title">Wi-Fi Internet</span></li>
                                                <?php } ?>
                                                <?php if($hotel['featured']['parking'] == 1) { ?>
                                                <li><i class="im im-parking"></i><span class="booking-item-feature-title">Đỗ xe miễn phí</span></li>
                                                <?php } ?>
                                                <?php if($hotel['featured']['airportBus'] == 1) { ?>
                                                <li><i class="im im-plane"></i><span class="booking-item-feature-title">Đưa đón ra sân bay</span></li>
                                                <?php } ?>
                                                <?php if($hotel['featured']['gym'] == 1) { ?>
                                                <li><i class="im im-fitness"></i><span class="booking-item-feature-title">Trung tâm sức khỏe</span></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <ul class="booking-item-features booking-item-features-expand mb30 clearfix">
                                                <?php if($hotel['featured']['pool'] == 1) { ?>
                                                <li><i class="im im-pool"></i><span class="booking-item-feature-title">Bể bơi</span></li>
                                                <?php } ?>
                                                <?php if($hotel['featured']['spa'] == 1) { ?>
                                                <li><i class="im im-spa"></i><span class="booking-item-feature-title">SPA</span></li>
                                                <?php } ?>
                                                <?php if($hotel['featured']['restaurant'] == 1) { ?>
                                                <li><i class="im im-restaurant"></i><span class="booking-item-feature-title">Nhà hàng</span></li>
                                                <?php } ?>
                                                <?php if($hotel['featured']['disabled'] == 1) { ?>
                                                <li><i class="im im-wheel-chair"></i><span class="booking-item-feature-title">Hỗ trợ người khuyết tật</span></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <ul class="booking-item-features booking-item-features-expand mb30 clearfix">
                                                <?php if($hotel['featured']['babysit'] == 1) { ?>
                                                <li><i class="im im-children"></i><span class="booking-item-feature-title">Dịch vụ giữ trẻ</span></li>
                                                <?php } ?>
                                                <?php if($hotel['featured']['vault'] == 1) { ?>
                                                <li><i class="im im-lock"></i><span class="booking-item-feature-title">Khóa an toàn</span></li>
                                                <?php } ?>
                                                <?php if($hotel['featured']['bar'] == 1) { ?>
                                                <li><i class="im im-bar"></i><span class="booking-item-feature-title">Quầy Bar</span></li>
                                                <?php } ?>
                                                 <?php if($hotel['featured']['breakfast'] == 1) { ?>
                                                <li><i class="im im-kitchen"></i><span class="booking-item-feature-title">Bữa sáng</span></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab-7">
                                    <div class="mt20">
                                        <div class="text-right mb10"><a class="btn button orange" href="#">Viết nhận xét</a>
                                        </div>
                                        <?php if ($hotel['reviews'] != "no have reviews"){ ?>
                                        <ul class="booking-item-reviews list">
                                            <?php foreach ($hotel['reviews'] as $review) { ?>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="booking-item-review-person">
                                                            <a class="booking-item-review-person-avatar round" href="#">
                                                                <img src="<?php bloginfo('stylesheet_directory'); ?>/img/70x70.png" alt="Image Alternative text" title="<?= $review['review_author'] ?>" />
                                                            </a>
                                                            <p class="booking-item-review-person-name"><a href="#"><?= $review['review_author'] ?></a>
                                                            </p>
                                                            <p class="booking-item-review-person-loc"><?= $review['review_date'] ?></p><small><a href="#"><?= $review['review_from'] ?></a></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <div class="booking-item-review-content">
                                                        <p>+ <?= $review['review_good'] ?></p>
                                                        <p>- <?= $review['review_bad'] ?></p>
                                                            <p class="booking-item-review-rate">Đánh giá
                                                                <a class="fa fa-thumbs-o-up box-icon-inline round" href="#"></a><b class="text-color"> <?= $review['review_score'] ?></b>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php } ?>
                                        </ul>
                                        <div class="row wrap">
                                            <div class="col-md-5">
                                                <p><small>Có tổng cộng <?= $hotel['score']['total'] ?> đánh giá khách sạn này.</small>
                                                </p>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="booking-item-meta">
                            <h2 class="lh1em mt40"><?= $hotel['score']['score_status'] ?></h2>
                            <h3><?= $hotel['score']['score_point'] ?> <small > dựa theo <?= $hotel['score']['total'] ?> đánh giá khách sạn này.</small></h3>
                            <h4 class="lhem">Điểm đánh giá</h4>
                                                <ul class="list booking-item-raiting-list">
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Sạch sẽ</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['clean'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['clean'] ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Thoải mái</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['comfort'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['comfort'] ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Tiện nghi</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['facilities'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['facilities'] ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Nhân viên phục vụ</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['staff'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['staff'] ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Địa điểm</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['location'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['location'] ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Đáng giá tiền</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['money'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['money'] ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="booking-item-raiting-list-title">Wifi</div>
                                                        <div class="booking-item-raiting-list-bar">
                                                            <div style="width:<?= $hotel['score']['wifi'] * 10 ?>%;"></div>
                                                        </div>
                                                        <div class="booking-item-raiting-list-number"><?= $hotel['score']['wifi'] ?></div>
                                                    </li>
                                                </ul>
                                <blockquote>
                                    <h5><?= $hotel['des'] ?></h5>
                                </blockquote>
                            </div>
                    </div>

                </div>
                <div class="gap gap-small"></div>
                <div class="row">
                    <div class="col-md-9">
                        <ul class="booking-list">
                            <?php foreach ($hotel['rooms'] as $room) { ?>
                            <form class="add-item">
                            <li>
                                <a class="booking-item">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <img src="<?= $room['room_img'] ?>" class="img-responsive" alt="Image Alternative text" title="<?= $room['room_name'] ?>" />
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="booking-item-title"><?= $room['room_name'] ?></h5>
                                            <ul class="booking-item-features booking-item-features-small clearfix">
                                                <li rel="tooltip" data-placement="top" title="Số người tối đa"><i class="fa fa-male"></i><span class="booking-item-feature-sign">x <?= $room['room_max'] ?></span>
                                                </li>
                                                <?php if($hotel['featured']['aircon'] == 1) { ?>
                                                <li rel="tooltip" data-placement="top" title="Điều hòa"><i class="im im-air"></i></li>
                                                <?php }?>
                                                <li rel="tooltip" data-placement="top" title="Tivi"><i class="im im-tv"></i></li>
                                                <?php if($hotel['featured']['bar'] == 1) { ?>
                                                <li rel="tooltip" data-placement="top" title="Mini Bar"><i class="im im-bar"></i></li>
                                                <?php }?>
                                                <?php if($hotel['featured']['bath'] == 1) { ?>
                                                <li rel="tooltip" data-placement="top" title="Bồn tắm/ Hoa sen"><i class="im im-shower"></i></li>
                                                <?php }?>
                                                <?php if($hotel['featured']['laundry'] == 1) { ?>
                                                <li rel="tooltip" data-placement="top" title="Dịch vụ giặt ủi"><i class="im im-washing-machine"></i></li>
                                                <?php }?>
                                                <?php if($hotel['featured']['pool'] == 1) { ?>
                                                <li rel="tooltip" data-placement="top" title="Bể bơi"><i class="im im-pool"></i></li>
                                                <?php }?>
                                                <?php if($hotel['featured']['internet'] == 1) { ?>
                                                <li rel="tooltip" data-placement="top" title="Wifi miễn phí"><i class="im im-wi-fi"></i></li>
                                                <?php }?>
                                            </ul>
                                            <small>Chỉ còn <?= $room['room_available'] ?> phòng sẵn sàng cho ngày <?= $day_dep."/".$mon_dep."/".$year_dep ?> đến <?= $day_ret."/".$mon_ret."/".$year_ret ?></small>
                                        </div>
                                        <?php if ($room['room_price'] != 'Hết phòng') { ?>
                                        <?php
                                            $hotel_rooms_code = $room['room_price'].$room['room_price_source'].$room['hotel_id'].substr($room['room_img'],-8,-4);
                                            $in_session = "0";
                                            if(!empty($_SESSION["hotel_cart"])) {
                                                $session_code_array = array_keys($_SESSION["hotel_cart"]);
                                                if(in_array($hotel_rooms_code,$session_code_array)) {
                                                    $in_session = "1";
                                                } 
                                            }
                                        ?>
                                        <div class="col-md-3"><span class="booking-item-price"><?= price_dot($room['room_price']) ?></span><span>/ <?= $room['room_days'] ?> đêm </span>
                                        <input type="button" id="add_<?php echo $hotel_rooms_code; ?>" value="Chọn phòng" class="btn button orange btnAddAction cart-action" onClick = "cartAction('add','<?= $hotel_rooms_code; ?>')" <?php if($in_session != "0") { ?>style="display:none" <?php } ?> />
                                        <input type="button" id="added_<?php echo $hotel_rooms_code; ?>" value="Đã chọn" class="btn btn-ghost btnAdded" <?php if($in_session != "1") { ?>style="display:none" <?php } ?> />
                                           <div class="form-group form-group-sm mt10">
                                                <label>Số lượng phòng</label>
                                                <select id="room_available_<?= $hotel_rooms_code ?>" class="form-control">
                                               	<?php $count = 1; ?>
                                                <?php foreach ($room['room_available_price'] as $room_available) { $count_room = $count++ ?>
                                                    <option value="<?= $count_room ?>x<?= $room_available ?>"><?= $count_room ?> phòng <?= price_dot($room_available) ?> đồng</option>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="text" value="<?= $hotel['name'] ?>" name="name" id="hotel_name_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $hotel['star'] ?>" name="hotel-star" id="hotel_star_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $hotel['add'].", ".$hotel['ext'].", ".$hotel['local'] ?>" name="hotel-add" id="hotel_add_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $room['room_name'] ?>" name="room-name" id="room_name_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $room['room_days'] ?>" name="room-days" id="room_days_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $room['room_max'] ?>" name="room-max" id="room_max_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $room['room_price'] ?>" name="room-price" id="room_price_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $room['room_img'] ?>" name="room-img" id="room_img_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $day_dep."/".$mon_dep."/".$year_dep ?>" id="check_in_<?= $hotel_rooms_code ?>" name="check-in" style="display: none;" />
                                        <input type="text" value="<?= $day_ret."/".$mon_ret."/".$year_ret ?>" id="check_out_<?= $hotel_rooms_code ?>" name="check-out" style="display: none;" />
                                        <input type="text" value="<?= $room['service_fee'] ?>" name="service-fee" id="service_fee_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $room['delivery_fee'] ?>" name="delivery-fee" id="delivery_fee_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $room['discount_amount'] ?>" name="discount-amount" id="discount_amount_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $room['hotel_id'] ?>" name="hotel-id" id="hotel_id_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $_GET['ad'] ?>" name="total-guest" id="total_guest_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <input type="text" value="<?= $room['room_price_source'] ?>" name="provider" id="provider_<?= $hotel_rooms_code ?>" style="display: none;" />
                                        <?php } else { ?>
                                        <div class="col-md-4"><p>Hết phòng</p>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </a>
                            </li>
                            </form>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="col-md-3 wgbox ">
					<div class="wg-search mb30">
                    <div id="wghoteltab">
					<div id="booking-item-dates-change" class="booking-item-dates-change">
					<?php $deptime_tmp=date("d/m/Y",time()+(60*60*(48+24)));
						$maxtime_tmp=date("d/m/Y",time()+(60*60*(8760)));
						$crrttime_tmp=date("d/m/Y",time());
					?>
						  <form method="post" action="<?php bloginfo('stylesheet_directory'); ?>/core/hotel_live_price.php" />
                                <input id="current_id" value=<?= $_GET['id'] ?> name="current_id" style="display: none;" /><br>
                                <div class="input-daterange" data-date-format="dd/mm/yyyy">
                                    <div class="form-group form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                        <label>Ngày đến</label>
                                        <input id="start"  name="start" type="text"  readonly="readonly" data-next="#end" after_function="change_checkin_date" minDate="<?= $crrttime_tmp?>" maxdate="<?=$maxtime_tmp?>"  default_max_date="<?=$maxtime_tmp?>" default="<?=$deptime_tmp?>" value="<?=$deptime_tmp?>" class="form-control dates input">
									</div>
                                    <div class="form-group form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-hightlight"></i>
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
                                <button class="btn button wide-fat dark" type="submit">Tìm giá</button>
                            </form>
						</div> 	
	</div>  
					 </div>
                        <div id="cart-item"></div>
                    </div>
                </div>
				<?php }?>
            </div>
            <div class="gap gap-small"></div>
         
<script>
$(document).ready(function () {
cartAction('','');
})
</script>
<script type="text/javascript">
NProgress.done();
</script>
<?php get_footer();?>
