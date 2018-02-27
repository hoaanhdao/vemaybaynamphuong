<?php
/*
Template Name: hoan-tat-dat-phong
*/ get_header(); ?>
<?php if(!empty($_POST['contact_name']) && !empty($_POST['contact_mobile']) && !empty($_POST['contact_email'])) {
$first_room = reset($_SESSION['hotel_cart']);
$total_price = 0;
$count_room = 0;
$room_details = array();
foreach ($_SESSION['hotel_cart'] as $item) {
	list($room_available,$room_available_price) = explode("x",$item['room_available']);
	$total_price = $total_price + $room_available_price;
	$total_room = $count_room + $room_available;
	$room_details[] = array(
		'room_name' => $item['name'],
		'max_guest' => $item['room_max'],
		'quantity' => $room_available,
		'total_price' => $total_price,
		'base_price' => floor($room_available_price / $item['room_days'] / $room_available),
		'provider' => $item['provider'],
		'no_of_night' => $item['room_days'],
		'room_id' => ''
		);
}
$post_data = array();
$post_data['room_details'] = $room_details;
$post_data['total_amount'] = $total_price + $first_room['service_fee'];
$post_data['total_room'] = $total_room;
$post_data['subtotal'] = $total_price;
$post_data['domain'] = "vemaybaynamphuong.com";
$post_data['contact_name'] = $_POST['contact_name'];
$post_data['contact_mobile'] = $_POST['contact_mobile'];
$post_data['contact_address'] = $_POST['contact_address'];
$post_data['contact_email'] = $_POST['contact_email'];
$post_data['payment_type'] = $_POST['payment_type'];
$post_data['description'] = $_POST['description'];
$post_data['hotel_name'] = $first_room['hotel_name'];
$post_data['hotel_id'] = $first_room['hotel_id'];
$post_data['checkin_date'] = re_date($first_room['check_in']);
$post_data['checkout_date'] = re_date($first_room['check_out']);
$post_data['service_fee'] = $first_room['service_fee'];
$post_data['discount_amount'] = $first_room['discount_amount'];
if($first_room['total_guest'] == null){$post_data['total_guest'] = 1;} else {$post_data['total_guest'] = $first_room['total_guest'];}
$result = dat_phong_khach_san(http_build_query($post_data, 'flags_')); ?>
<section id="confirm-page" class="section wide-fat reservation-pages gap">
         <div class="col-xs-12 col-sm-8">
                        <div class="confirm-page">
                            <h1>Mã đặt phòng của Quý khách là: <strong><?= $result['booking_no']?></strong> </h1>
                            <hr>
                            <article>
                                <h2>Thông tin khách</h2>
                                <ul class="tabled-ul">
                                    <li>
                                        <div class="lbl">Họ tên</div>
                                        <div class="value"><?= $_POST['contact_name'] ?></div>
                                    </li>
									<li>
                                        <div class="lbl">Điện thoại</div>
                                        <div class="value"><?= $_POST['contact_mobile'] ?></div>
                                    </li>	
                                    <li>
                                        <div class="lbl">Email</div>
                                        <div class="value"><?= $_POST['contact_email'] ?></div>
                                    </li>
                                    <li>
                                        <div class="lbl">Địa chỉ</div>
                                        <div class="value"><?= $_POST['contact_address'] ?></div>
                                    </li>
                                </ul>
                            </article>
                           

                            <article>
                                <h2>Thanh toán</h2>
                                <p><?php if($_POST['payment_type']===3){
								echo "Chuyển khoản qua ngân hàng";
								}elseif($_POST['payment_type']===2){
								echo "Đến trực tiếp công ty";
								}else{
								echo"Giao vé và thu tiền tận nơi (25.000 đồng)";	
								} ?></p>
                            </article>
							
							<article>
                                <h2>Yêu cầu khác</h2>
                                <p><?= $_POST['description'] ?></p>
                            </article>	
                            
 
                            <article>
                                <h2>We wish you a pleasant stay</h2>
                                <p>
                                    VMB Nam Phương.
                                </p>
                            </article>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4">
                        <div class="sidebar-holder">
                            <article class="entry-content">
                                <h2 class="post-title"><a href="#" title="Your Hotel Title Here"><?= $item['hotel_name']?></a></h2>
							<hr>
                            </article>

                            <div class="spicifications-widget">

                                <ul>
                                    <li>
                                        <div class="lbl">Phòng</div>
                                        <div class="value"><?= $item['name']?></div>
                                    </li>
                                    <li>
                                        <div class="lbl">Ngày nhận phòng</div>
                                        <div class="value"><?= $item['check_in'] ?></div>
                                    </li>
                                    <li>
                                        <div class="lbl">Ngày trả phòng</div>
                                        <div class="value"><?= $item['check_out'] ?></div>
                                    </li>

                                    <li>
                                        <div class="lbl">Phòng</div>
                                        <div class="value"><?= $item['room_days']?> Đêm, <?= $room_available ?> Phòng, Tối đa <?= $item['room_max'] ?></div>
                                    </li>
                                </ul>
                                <hr>
                                <div class="total-price">Tổng cộng: <span> <?= price_dot($total_price + $first_room['service_fee']) ?> đồng</span>
                                </div>
                                <a href="#" class="button wide-fat">Print Booking</a>
                            </div>

                        </div>


                    </div>
</section> 
		
		<?php 
unset($_SESSION['hotel_cart']);
get_footer(); } ?>