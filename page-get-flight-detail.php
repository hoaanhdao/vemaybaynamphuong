<?php
/**
 * Created by Notepad.
 * User: Lak
 * Date: 11/11/13
 */
if($_POST["flightid"] && $_POST["flightid"] !="" && $_SESSION["result"][$_POST["flightid"]]):
    $crflight=$_SESSION["result"][$_POST["flightid"]];
    $direc=$_POST["direction"];

    $airline = $crflight['airline'];
    $dep = $crflight['dep'];
    $arv = $crflight['arv'];
    $deptime = $crflight['deptime'];
    $arvtime = $crflight['arvtime'];
    $flightno = $crflight['flightno'];
    $price = $crflight['price'];
    $class = $crflight['class'];
    $stop = $crflight['stop'];

    $note = ($crflight['note'])?$crflight['note']:"";
    if($direc=="InBound"){
        $depart = $_SESSION["search"]['return'];
    }else{
        $depart = $_SESSION["search"]['depart'];
    }
    $adults =  $_SESSION["search"]['adult'];
    $children =  $_SESSION["search"]['children'];
    $infants =  $_SESSION["search"]['infant'];

    $lblairline="";
    switch($airline){
        case "vietnamairline":
            $lblairline="Vietnam Airlines";
            break;
        case "vietjetair":
            $lblairline="Vietjet Air";
            break;
        case "jetstar":
            $lblairline="Jetstar";
            break;
        default :
            $lblairline=$airline;
            break;
    }
    $total=0;
    $total_child=0;
    $total_infants=0;
    ?>
<td colspan="5" class="flight-detail-content">
    <table class="flight_info">
        <tr style="font-weight:bold" class="thead">
            <td width="40%" style="font-weight:bold;">Khởi hành</td>
            <td width="40%" style="">Điểm đến</td>
            <td width="20%" style="">Chuyến bay</td>
        </tr>
        <tr>
            <td>từ <strong style="color: #e8382a;"><?php echo  $GLOBALS['CODECITY'][$dep] ?></strong>, <?php echo  $GLOBALS['NATION'][$dep] ?></td>
            <td>đến <strong style="color: #e8382a;"><?php echo  $GLOBALS['CODECITY'][$arv] ?></strong>, <?php echo  $GLOBALS['NATION'][$arv] ?></td>
            <td><strong style="color: #e8382a;"><?php echo $lblairline?></strong></td>

        </tr>
        <tr>
            <td>Sân bay: <strong><?php echo  $GLOBALS['AIRPORT'][$dep] ?></strong></td>
            <td>Sân bay: <strong><?php echo  $GLOBALS['AIRPORT'][$arv] ?></strong></td>
            <td><strong>(<?php echo  $flightno ?>)</strong></td>

        </tr>
        <tr>
            <td>Thời gian: <strong style="color: #e8382a;"><?php echo  $deptime ?></strong>, <?php echo  $depart ?></td>
            <td>Thời gian: <strong style="color: #e8382a;"><?php echo  $arvtime ?></strong>, <?php echo  $depart ?></td>
            <td>Loại vé: <strong><?php echo  $class ?></strong></td>
        </tr>
    </table>
    <?php if($stop != 0 ) echo '<div class="note">'.$note.'</div>';?>

    <table class="flight_info tblprice" style="text-align:center">
        <tr style="font-weight:bold;color:#747474;font-size: 12px;">
            <td>Hành khách</td>
            <td>Số lượng vé</td>
            <td>Giá mỗi vé</td>
            <td>Thuế & Phí</td>
            <td>Tổng giá</td>
        </tr>
        <tr>
            <td>Người lớn</td>
            <td><b><?php echo  $adults ?></b></td>
            <td><b><?php echo  format_price($price);?></b></td>
            <td><b><?php $thuephi = getTaxFee_adult($price,$airline); echo format_price($thuephi); ?></b></td>
            <td><b><?php $total = ($price+$thuephi)*$adults; echo format_price($total); ?></b></td>
        </tr>
        <?php if($children != 0){ ?>
        <tr>
            <td>Trẻ em</td>
            <td><b><?php echo  $children ?></b></td>
            <td><b><?php $price_child = get_price_child($price,$airline); echo format_price($price_child);?></b></td>
            <td><b><?php $tax_child = getTaxFee_child($price,$airline); echo format_price($tax_child); ?></b></td>
            <td><b><?php $total_child = ($price_child + $tax_child) * $children; echo format_price($total_child); ?></b></td>
        </tr>
        <?php } ?>
        <?php if($infants != 0){ ?>
        <tr>
            <td>Trẻ sơ sinh</td>
            <td><b><?php echo  $infants ?></b></td>
            <td><b><?php $price_infants = get_price_infant($price,$airline); echo format_price($price_infants);?></b></td>
            <td><b><?php $tax_infants = getTaxFee_infant($price,$airline); echo format_price($tax_infants); ?></b></td>
            <td><b><?php $total_infants = ($price_infants + $tax_infants) * $infants; echo format_price($total_infants); ?></b></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="4" style="border-top: 1px dashed #ccc;padding-top: 10px;"><b>Tổng cộng<b/></td>
            <td style="text-align: left;font-weight: bold;border-top: 1px dashed #ccc;padding-top: 10px;"><?php echo  format_price($total+$total_child+$total_infants) ?></td>
        </tr>
    </table>

</td>

<?php
else:
    ?>
<td colspan="5" class="flight-detail-content">
    Phiên làm việc đã hết, xin quý khách vui lòng tìm kiếm lại
</td>

<?php

endif;
?>