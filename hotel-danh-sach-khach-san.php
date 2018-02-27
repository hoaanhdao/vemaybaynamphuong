<?php get_header(); ?>
<?php
/*
Template Name: danh-sach-khach-san
*/
if (isset ($_GET["id"])){
  $url = 'http://api.vemaybay.website/index.php/hotels/Api_v1/show_hotels';
  $url_now =  "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $post_data = array();
  $post_data['id'] = $_GET['id'];
  $post_data['type'] = $_GET['t'];
  $post_data['score'] = 1;
  $post_data['offset'] = $_GET['offset'];
  $post_data['order_price'] = $_GET['op'];
  $star = $_GET['star'];
  if($_GET['star'] !=""){$post_data['star'] = $star;}
  if($_GET['breakfast'] !=""){$post_data['breakfast'] = 1;}
  if($_GET['babysit'] !=""){$post_data['babysit'] = 1;}
  if($_GET['pool'] !=""){$post_data['pool'] = 1;}
  if($_GET['internet'] !=""){$post_data['internet'] = 1;}
  if($_GET['vault'] !=""){$post_data['vault'] = 1;}
  if($_GET['restaurant'] !=""){$post_data['restaurant'] = 1;}
  if($_GET['laundry'] !=""){$post_data['laundry'] = 1;}
  if($_GET['bar'] !=""){$post_data['bar'] = 1;}
  if($_GET['tennis'] !=""){$post_data['golf'] = 1;}
  if($_GET['bath'] !=""){$post_data['bath'] = 1;}
  if($_GET['spa'] !=""){$post_data['spa'] = 1;}
  if($_GET['gym'] !=""){$post_data['gym'] = 1;}
  if($_GET['airportBus'] !=""){$post_data['airportBus'] = 1;}
  if($_GET['aircon'] !=""){$post_data['aircon'] = 1;}
  if($_GET['parking'] !=""){$post_data['parking'] = 1;}
  if($_GET['disabled'] !=""){$post_data['disabled'] = 1;}
  if($_GET['max'] !=""){$post_data['max_price'] = $_GET['max'];$post_data['min_price'] = 1;}
  $get_array = implode($post_data);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'NP-API-KEY: a81c7ff7e800a451a646136146c29a36557f0061'
    ));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
  $search_data = curl_exec($ch);
  $result = json_decode($search_data,true);
  $siteurl = get_bloginfo('siteurl');

  if ($result == NULL){
  
    header("HTTP/1.1 301 Moved Permanently");
    header('Location:'.$siteurl/404);
    exit();
  } ?>
         
             
            <h3 class="booking-title">Chúng tôi tìm thấy <?= $result['total'] ?> khách sạn tại <?php if($_GET['t'] == 'area'){ echo $result['area_name'];} else { echo $result['name'] ;} ?> theo yêu cầu của bạn </h3>
           
		    
                <div class="col-md-4">
					 
					<div class="heading-with-icon">
						<div class="heading-link">
							<a href="#" data-role="clear-all-filters-link" class="filters-container-clear-all-selected-filters" style="display: none;">
								Clear all
							</a>
						</div>
						<div class="heading-icon"><i class="icons-sprite icons-magic_wand_encircled"></i></div>
						<div class="heading-title">Điều kiện lọc</div>
					</div> 
                    <aside class="booking-filters booking-filters-white panel">
                    <form method="post" id="filter" action="<?php bloginfo('stylesheet_directory'); ?>/core/hotel_filter.php">
                    <input id="local_id" value=<?= $result['local_id'] ?> name="local_id" style="display: none;" />
                    <input id="current_type" value=<?= $_GET['t'] ?> name="current_type" style="display: none;" />
                    <input id="current_id" value=<?= $_GET['id'] ?> name="current_id" style="display: none;" />
                    <input value=<?= $_GET['dep'] ?> name="start" style="display: none;" />
                    <input value=<?= $_GET['ret'] ?> name="end" style="display: none;" />
                    <input value=<?= $_GET['ad'] ?> name="ad" style="display: none;" />
						
						<p class="booking-filters-title panel-title"><i class="fa fa-sort-desc yellow-color"></i> 
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_h2">Xếp hạng sao</a></p>
                       <div id="collapse_h2" class="panel-collapse collapse in">
						
                        <ul class="list booking-filters-list">						
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input id="rating-3" name="rating-5" value="5" 
                                    type="checkbox" class="i-check" <?php if($_GET['star'] == "5"){ echo "checked value='1'";} ?> />5 sao</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="rating-3" name="rating-4" value="4" 
                                    type="checkbox" class="i-check" <?php if($_GET['star'] == "4"){ echo "checked value='1'";} ?> />4 sao</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="rating-3" name="rating-3" value="3" 
                                    type="checkbox" class="i-check" <?php if($_GET['star'] == "3"){ echo "checked value='1'";} ?> />3 sao</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="rating-3" name="rating-2" value="2" 
                                    type="checkbox" class="i-check" <?php if($_GET['star'] == "2"){ echo "checked value='1'";} ?> />2 sao</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="rating-3" name="rating-1" value="1" 
                                    type="checkbox" class="i-check" <?php if($_GET['star'] == "1"){ echo "checked value='1'";} ?> />1 sao</label>
                                </div>
                            </li>
                        </ul>
						</div>
						<p class="booking-filters-title panel-title"><i class="fa fa-sort-desc yellow-color"></i> 
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_h3">Tiện nghi</a></p>
                       <div id="collapse_h3" class="panel-collapse collapse">
					    <ul class="list booking-filters-list">						
							<li>
                                <div class="checkbox">
                                    <label>
                                        <input id="internet" name="internet" 
                                    type="checkbox" class="i-check" <?php if($_GET['internet'] !=""){ echo "checked value='1'";} ?> />Wi-Fi</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="parking" name="parking" 
                                    type="checkbox" class="i-check" <?php if($_GET['parking'] !=""){ echo "checked value='1'";} ?> />Đỗ xe miễn phí</label>
                                </div>
                                <div class="checkbox">
                                <label>
                                        <input id="airportBus" name="airportBus" 
                                    type="checkbox" class="i-check" <?php if($_GET['airportBus'] !=""){ echo "checked value='1'";} ?> />Đưa đón ra sân bay</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="gym" name="gym" 
                                    type="checkbox" class="i-check" <?php if($_GET['gym'] !=""){ echo "checked value='1'";} ?> />Trung tâm thể dục</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="spa" name="spa" 
                                    type="checkbox" class="i-check" <?php if($_GET['spa'] !=""){ echo "checked value='1'";} ?> />Spa</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="pool" name="pool" 
                                    type="checkbox" class="i-check" <?php if($_GET['pool'] !=""){ echo "checked value='1'";} ?> />Bể bơi</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="breakfast" name="breakfast" 
                                    type="checkbox" class="i-check" <?php if($_GET['breakfast'] !=""){ echo "checked value='1'";} ?> />Bữa sáng</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="laundry" name="laundry" 
                                    type="checkbox" class="i-check" <?php if($_GET['laundry'] !=""){ echo "checked value='1'";} ?> />Dich vụ giặt ủi</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="bath" name="bath" 
                                    type="checkbox" class="i-check" <?php if($_GET['bath'] !=""){ echo "checked value='1'";} ?> />Bồn tắm</label>
                                </div>
                                
                                <div class="checkbox">
                                    <label>
                                        <input id="aircon" name="aircon" 
                                    type="checkbox" class="i-check" <?php if($_GET['aircon'] !=""){ echo "checked value='1'";} ?> />Điều hòa nhiệt độ</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="bar" name="bar" 
                                    type="checkbox" class="i-check" <?php if($_GET['bar'] !=""){ echo "checked value='1'";} ?> />Quầy bar</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="disabled" name="disabled" 
                                    type="checkbox" class="i-check" <?php if($_GET['disabled'] !=""){ echo "checked value='1'";} ?> />Hỗ trợ người khuyết tật</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="vault" name="vault" 
                                    type="checkbox" class="i-check" <?php if($_GET['vault'] !=""){ echo "checked value='1'";} ?> />Khóa an toàn</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input id="restaurant" name="restaurant" 
                                    type="checkbox" class="i-check" <?php if($_GET['restaurant'] !=""){ echo "checked value='1'";} ?> />Nhà hàng</label>
                                </div>
                                <div class="checkbox">
                                <label>
                                        <input id="babysit" name="babysit" 
                                    type="checkbox" class="i-check" <?php if($_GET['babysit'] !=""){ echo "checked value='1'";} ?> />Dịch vụ trông trẻ</label>
                                </div>
                            </li>
                        </ul>
						</div>
						<p class="booking-filters-title panel-title"><i class="fa fa-sort-desc yellow-color"></i> 
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_h4">Khu vực</a></p>
                       <div id="collapse_h4" class="panel-collapse collapse">
						<ul class="list booking-filters-list">						
							<li>
                                <?php foreach ($result['areas'] as $area) { ?>
                                <?php if ($area['area_id'] != $_GET['id']) { ?>
                                <div class="checkbox">
                                    <label>
                                        <input id="area-<?= $area['area_id'] ?>" name="area_id" value="<?= $area['area_id'] ?>"
                                        type="checkbox" class="i-check" /><?= $area['name'] ?></label>
                                </div>
                                <?php } } ?>
                            </li>
                        </ul>
						</div>
						<p class="booking-filters-title panel-title"><i class="fa fa-sort-desc yellow-color"></i> 
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_h1">Giá tối đa</a></p>
                         <div id="collapse_h1" class="panel-collapse collapse">
						   <ul class="list booking-filters-list">
								<li>
									<input id="range" name="range" />
								</li>
							</ul>
						</div>	
                    </aside>
					<?php get_sidebar(); ?>
                </div>
                <div class="col-md-8 sidebar-separator inversed">
                    <div class="nav-drop booking-sort">
                        <h5 class="booking-sort-title"><a href="#">Xếp theo<i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i></a></h5>
                        <ul class="nav-drop-menu">
                            <li><a href="javascript:{}" onclick="document.getElementById('filter').submit(); return false;">Giá từ thấp đến cao</a>
                            <?php if ($_GET['op'] == "high"){ ?>
                            <input type="hidden" name="oplow" value="low" />
                            <?php } ?>
                            </li>
                            <li><a href="javascript:{}" onclick="document.getElementById('filter').submit(); return false;">Giá từ cao đến thấp</a>
                            <?php if ($_GET['op'] == "low" || $_GET['op'] == null){ ?>
                            <input type="hidden" name="op" value="high" />
                            <?php } ?>
                            </li>
                        </ul>
                    </div>
                </form>
                    <ul class="booking-list">
                     <?php foreach ($result['hotels'] as $hotel) { ?>
                        <li>
                            <a class="booking-item" href="<?= get_bloginfo('url') ?>/khach-san/?id=<?= $hotel['id'] ?>&dep=<?= $_GET['dep']?>&ret=<?=  $_GET['ret'] ?>&ad=<?=  $_GET['ad'] ?>">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="booking-item-img-wrap">
                                            <img src="<?= $hotel['img'][0][0] ?>" class="img-responsive" alt="Image Alternative text" title="hotel 1" />
                                            <div class="booking-item-img-num"><i class="fa fa-picture-o"></i>23</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="booking-item-rating">
                                            <ul class="icon-group booking-item-rating-stars">
                                                <?php for ($i=1; $i <= $hotel['star']; $i++) { ?>
                                                <li><i class="fa fa-star"></i>
                                                </li>
                                                <?php } ?>
                                            </ul><?php if($hotel['score']['score_point'] != null) { ?><span class="booking-item-rating-number"><b ><?= $hotel['score']['score_point'] ?></b></span><small>(dựa vào <?= $hotel['score']['total'] ?> nhận xét)</small><?php }?>
                                        </div>
                                        <h5 class="booking-item-title"><?= $hotel['name'] ?></h5>
                                        <p class="booking-item-address"><i class="fa fa-map-marker"></i> <?= $hotel['add'].",".$hotel['ext'].",".$hotel['local'] ?></p><small class="booking-item-last-booked">Lần đặt phòng cuối: cách <?= rand(1,48) ?> giờ trước.</small>
                                    </div>
                                    <div class="col-md-3"><span class="booking-item-price-from">từ</span><span class="booking-item-price"><?= price_dot($hotel['price']) ?></span><span>/đêm</span><span class="btn button orange">Xem chi tiết</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                       <?php } ?>
                    </ul>
                    <div class="row">
                        <div class="col-md-12">
                            <p><small>Tim thấy <?= $result['total'] ?> khách sạn. &nbsp;&nbsp;Hiển thị từ một trang 15 kết quả.</small>
                            </p>
                            <ul class="pagination">
                                <?php $page = floor($result['total'] / 15); for ($i = 0; $i <= $page; $i++){ $off = $i * 15 + 1; ?>
                                <li <?php if($_GET['offset'] == $off){echo "class='active'"; } ?>>
                                    <a href="<?php $s = preg_replace("/&offset=(\d+)/", "", $url_now); echo $s."&offset=".$off;?>" title=""><?= $i + 1 ?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            
            
        
<?php get_footer(); } ?>