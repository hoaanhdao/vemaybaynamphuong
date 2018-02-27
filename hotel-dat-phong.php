<?php get_header(); ?>
<?php
/*
Template Name: dat-phong
*/?>
<?php if(isset($_SESSION["hotel_cart"])){?>
                <div class="col-md-7">
                    <div class="heading-with-icon-and-ruler">
						<div class="heading-icon"><i class="icons-sprite icons-users_encircled"></i></div>
						<div class="heading-title"> Thông tin người đặt phòng</div>
						<hr>
					</div>
                    <form id="hotelForm" method="post" action="<?= _page("hotelcomplete"); ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Họ tên</label>
                                    <input name="contact_name" class="form-control" type="text" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Điện thoại</label>
                                    <input name="contact_mobile" class="form-control" type="text" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>E-mail</label>
                                    <input name="contact_email" class="form-control" type="text" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Địa chỉ</label>
                                    <input name="contact_address" class="form-control" type="text" />
                                </div>
                            </div>
                             <div class="col-md-12 clearfix">
                                <div class="form-group">
								<label>Phương thức thanh toán</label><br>
                                <div class="radio radio-inline radio-small">
                                    <label>
                                    <input name="payment_type" value="3" class="i-radio" type="radio" />Chuyển khoản qua ngân hàng</label>
                                </div>
                                <div class="radio radio-inline radio-small">
                                    <label>
                                    <input name="payment_type" value="2" class="i-radio" type="radio" />Đến trực tiếp công ty</label>
                                </div>
								<div class="radio radio-inline radio-small">
                                    <label>
                                    <input name="payment_type" value="1" class="i-radio" type="radio" />Giao vé và thu tiền tận nơi (25.000 đồng)</label>
                                </div>
                                
                            </div>
                            </div>
							
                            <div class="col-md-12">
                                <div class="form-group">
                                      <label for="description">Khi quý khách có thêm yêu cầu, hãy viết vào ô bên dưới.</label>
                                      <textarea class="form-control" rows="3" name="description" id="description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input class="i-check" type="checkbox"/> Tôi đồng ý với các điều khoản và quy định của VMB Nam Phương
                            </label>
                        </div>
                        <button class="btn button orange pull-right  mb30" type="submit">Đặt phòng</button>
						<div class="clearfix"></div>
                    </form>
                </div>
                <div class="col-md-5">
                    <div class="booking-item-payment">
                    <?php $total_price = 0; ?>
                    <?php foreach ($_SESSION["hotel_cart"] as $item){ 
                        list($room_available,$room_available_price) = explode("x",$item['room_available']);
                        $total_price = $total_price + $room_available_price;
                        ?>
                        <header class="clearfix">
                            <a class="booking-item-payment-img" href="#">
                                <img src="<?= $item['room_img'] ?>" class="img-responsive" alt="<?= $item['hotel_name'] ?>" title="<?= $item['hotel_name'] ?>" />
                            </a>
                            <h5 class="booking-item-payment-title"><a href="#"><?= $item['hotel_name'] ?></a></h5>
                            <small><?= $item['hotel_add'] ?></small>
                        </header>
                        <ul class="booking-item-payment-details">
                            <li>
                                <h5>Đặt phòng cho <?= $item['room_days'] ?> đêm</h5>
                                <div class="booking-item-payment-date">
                                    <p class="booking-item-payment-date-day"><b><?= $item['check_in'] ?></b></p>
                                </div><i class="fa fa-arrow-right booking-item-payment-date-separator"></i>
                                <div class="booking-item-payment-date">
                                    <p class="booking-item-payment-date-day"><b><?= $item['check_out'] ?></b></p>
                                </div>
                            </li>
                            <li>
                                <p class="booking-item-payment-item-title"><?= $item['name'] ?> <small>(tối đa <?= $item['room_max']?> người/phòng)</small></p>
                                <ul class="booking-item-payment-price">
                                    <li>
                                        <p class="booking-item-payment-price-title"><?= $room_available ?> phòng x <?= $item['room_days'] ?> đêm</p>
                                        <p class="booking-item-payment-price-amount"><?= price_dot($room_available_price)?> đồng
                                        </p>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <?php } ?>
                        <p class="booking-item-payment-total">Tổng cộng: <span><?= price_dot($total_price + $item['service_fee']); ?></span> đồng</p>
                        <small class="booking-item-payment-total">* Thuế phí: <?= price_dot($item['service_fee']) ?> đồng</small>
                    </div>
                </div>
            
            <div class="gap"></div>
         
<script>
$(document).ready(function() {
    $('#hotelForm').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            contact_name: {
                validators: {
                    notEmpty: {
                        message: 'Xin nhập họ và tên'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: 'Họ và tên không ít hơn 6 kí tự'
                    }
                }
            },
            contact_mobile: {
                validators: {
                    notEmpty: {
                        message: 'Xin nhập số điện thoại'
                    }
                }
            },
            payment_type: {
                validators: {
                    notEmpty: {
                        message: 'Xin chọn hình thức thanh toán'
                    }
                }
            },
            contact_email: {
                validators: {
                    notEmpty: {
                        message: 'Xin nhập email'
                    },
                    emailAddress: {
                        message: 'Xin nhập đúng email'
                    }
                }
            },
        }
    })
    .find('input[name="payment_type"]')
            .on('ifChanged', function(e) {
                // Get the field name
                var field = $(this).attr('name');
                $('#hotelForm').formValidation('revalidateField', field);
    })
    .end();
});
</script>
<?php } get_footer(); ?>