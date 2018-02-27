<?php
/*
Template Name: tim-ve-gia-re-core
*/
unset($_SESSION['cheap_flights_cart_oneway']);
unset($_SESSION['cheap_flights_cart_return']);
preg_match("/\((\w+)\)/", $_GET['dep'], $dep_code);
preg_match("/\((\w+)\)/", $_GET['arv'], $arv_code);
$post_data = array();
$post_data['dep_code'] = $dep_code[1];
$post_data['arv_code'] = $arv_code[1];
$post_data['outbound_date'] = re_date_cheap($_GET["out"]);
if($_GET["in"] != null){$post_data['inbound_date'] = re_date_cheap($_GET["in"]);}
if($_GET["chd"] != null){ $post_data['chd_count'] = $_GET["chd"];}
if($_GET["inf"] != null){ $post_data['inf_count'] = $_GET["inf"];}
$post_data['adt_count'] = $_GET["adt"];
//print_r($post_data);
//Array ( [dep_code] => SGN [arv_code] => HAN [outbound_date] => 2016-02-01 [inbound_date] => 2016-03-01 [chd_count] => 0 [inf_count] => 0 [adt_count] => 1 )
$data = tim_ve_gia_re($post_data);
?>
<div id="MyDynamicDiv">
<script type="text/javascript">
NProgress.inc();
</script>
	<?php  if($_GET['dep'] != null){ ?>
    <div class="row">
              
                <div class="col-md-6">
				<div class="composite-heading  clearfix">
					<div class="composite-heading-title">
						<div class="composite-heading-element heading-title">Lượt đi</div>
						<div class="composite-heading-element heading-icon tall-heading-icon"><i class="icons-sprite icons-plane_right_muted"></i></div>
					</div> 
					<div class="composite-heading-element heading-text"><?= $_GET['dep']?></b> đến <b><?= $_GET['arv']?></b> cho tháng <?= substr($_GET["out"],0,2)?>/<?= substr($_GET["out"],3,4)?></div>
				</div>
					  <div id='dep-calendar'></div>
                </div>
				<?php if($_GET["in"] != null){?>
                <div class="col-md-6">
				
					<div class="composite-heading  clearfix">
						<div class="composite-heading-title">
							<div class="composite-heading-element heading-title">Lượt về</div>
							<div class="composite-heading-element heading-icon tall-heading-icon"><i class="icons-sprite icons-plane_right_muted"></i></div>
						</div> 
						<div class="composite-heading-element heading-text"><?php if($_GET["in"] != null) { ?>từ <b><?= $_GET['arv']?></b> đến <b><?= $_GET['dep']?></b> cho tháng <?= substr($_GET["in"],0,2)?>/<?= substr($_GET["in"],3,4); }?></div>
					</div>
					 <div id='arv-calendar'></div>
                </div>
				<?php } ?>
				
				<div class="gap"></div>
			<div>
			<i class="fa fa-question-circle"></i>
			<button class="btn note orange"></button> Vé rẻ nhất trong tháng
			<button class="btn note blue"></button> Vé rẻ nhất trong ngày
            </div>
			
			<div id="cart-item">
				<div class="heading-with-icon">
					<div class="heading-icon"><i class="icons-sprite icons-notification_warning"></i></div>
					<h1 class="heading-title">Vui lòng chọn chuyến bay.</h1>
				</div>
			</div>
		
			
    </div>
	<?php }else{ echo "lỗi";}?>
</div>


<script>
$(document).ready(function() {
	$('#dep-calendar').fullCalendar({
	eventClick: function(event) {
        if (event.url) {
            $.ajax({
            type: "POST",
            url: "https://vemaybaynamphuong.com/add-cheap-flights/",
            async: true,
            data: { price: (event.price), flight_code: (event.flight_code), type: (event.type), day_out: (event.day_out), mon_out: (event.mon_out), dep: (event.dep_code), arv: (event.arv_code), adt: (event.adt), chd: (event.chd), inf: (event.inf) },
            success:function(data){
                        $("#cart-item").html(data);
                    }
            });
            return false;
        }
    	},
	    contentHeight: 400,
		header: {
			left: '',
			center: '',
			right: ''
		},
		defaultDate: '<?= re_date_cheap($_GET["out"]) ?>',
		events: [
		
			<?php $arrout = array();
			foreach ($data['data']['outbound'] as $out) { ?>
		    <?php if ($out['cheapest'] != null) { ?>
				<?php
				foreach($out as $k=>$v) {
				$arrout[$k][] = $v;
				} ?>
    		 
			<?php }  } 
			$min_arrout  = array_map('min', $arrout);  ?>
			<?php global $cheapestout;
				$cheapestout = $min_arrout['cheapest'] ?>
				
			 
			 
		    <?php 
			foreach ($data['data']['outbound'] as $out) { ?>
		    <?php if ($out['cheapest'] != null) { ?>
				 
    			{
    				title: '<?= price_dot($out['cheapest'])?>',
					<?php if ($out['cheapest'] <= $cheapestout) { echo "color: '#ff9d00'"; } 
							else { ?> color: '#3A87AD' <?php } ?>,
    				
					start: '<?= substr($_GET["out"],3,4)?>-<?= substr($_GET["out"],0,2)?>-<?= sprintf('%02d',$out['day']) ?>',
    				type: '<?php if (empty($_GET['in'])) { echo 'oneway';} else { echo 'return';} ?>',
                    flight_code: '<?php if($out['cheapest'] == $out['vj_price']){ echo 'VJ';} else { echo 'BL'; }?>',
                    price: '<?= $out['cheapest'] ?>',
                    dep_code: '<?= $post_data['dep_code'] ?>',
                    arv_code: '<?= $post_data['arv_code'] ?>',
					day_out: '<?= sprintf('%02d',$out['day']) ?>',
                    mon_out: '<?= $_GET['out'] ?>',
                    adt: '<?= $_GET['adt'] ?>',
                    chd: '<?= $_GET['chd'] ?>',
                    inf: '<?= $_GET['inf'] ?>',
                    url: "<?= "https://vemaybaynamphuong.com/tim-chuyen-bay" ?>" 
					//url: https://vemaybaynamphuong.com/tim-chuyen-bay
				},
			<?php } else { ?>
			    {
    		    start: '<?= substr($_GET["out"],3,4)?>-<?= substr($_GET["out"],0,2)?>-<?= sprintf('%02d',$out['day']) ?>',
				rendering: 'background',
				color: '#bdc3c7'
			    },
			<?php } } ?>
		]
	});
	
});
</script>
<?php if($_GET["in"] != null){?>
<script>
$(document).ready(function() {
	$('#arv-calendar').fullCalendar({
		eventClick: function(event) {
            if (event.url) {
            $.ajax({
                type: "POST",
                url: "https://vemaybaynamphuong.com/add-cheap-flights/",
                async: true,
                data: { price: (event.price), flight_code: (event.flight_code), type: (event.type), day_in: (event.day_in), mon_in: (event.mon_in), dep: (event.dep_code), arv: (event.arv_code), adt: (event.adt), chd: (event.chd), inf: (event.inf) },
                    success:function(data){
                        $("#cart-item").html(data);
                    }
                });
                return false;
            }
    	},
	    contentHeight: 400,
		header: {
			left: '',
			center: '',
			right: ''
		},
		defaultDate: '<?= re_date_cheap($_GET["in"]) ?>',
		events: [
		<?php if($_GET["in"] != null) { ?>
		
		<?php $arrin = array();
			foreach ($data['data']['inbound'] as $in) { ?>
		    <?php if ($in['cheapest'] != null) { ?>
				<?php
				foreach($in as $k=>$v) {
				$arrin[$k][] = $v;
				} ?>
    		 
			<?php }  } 
			$min_arrin  = array_map('min', $arrin);  ?>
			<?php global $cheapestin;
				$cheapestin = $min_arrin['cheapest'] ?>
				
		
		<?php foreach ($data['data']['inbound'] as $in) { ?>
		    <?php if ($in['cheapest'] != null) { ?>
    			{
    				title: '<?= price_dot($in['cheapest'])?>',
    				start: '<?= substr($_GET["in"],3,4)?>-<?= substr($_GET["in"],0,2) ?>-<?= sprintf('%02d',$in['day']) ?>',
    				
					<?php if ($in['cheapest'] <= $cheapestin) { echo "color: '#ff9d00'"; } 
					else { ?> color: '#3A87AD' <?php } ?>,
                   
					type: '<?php if (empty($_GET['in'])) { echo 'oneway';} else { echo 'return';} ?>',
                    flight_code: '<?php if($in['cheapest'] == $in['vj_price']){ echo 'VJ';} else { echo 'BL'; }?>',
                    price: '<?= $in['cheapest'] ?>',
                    dep_code: '<?= $post_data['dep_code'] ?>',
                    arv_code: '<?= $post_data['arv_code'] ?>',
					day_in: '<?= sprintf('%02d',$in['day']) ?>',
                    mon_in: '<?= $_GET['in'] ?>',
                    adt: '<?= $_GET['adt'] ?>',
                    chd: '<?= $_GET['chd'] ?>',
                    inf: '<?= $_GET['inf'] ?>',
    				//url: "<?= "https://vemaybaynamphuong.com/tim-chuyen-bay/?dep=".$_GET['arv']."&arv=".$_GET['dep']."&out=".sprintf('%02d',$in['day'])."-".substr($_GET["in"],0,2)."-".substr($_GET["in"],3,4)."&adt=".$_GET['adt']."&chd=".$_GET['chd']."&inf=".$_GET['inf'] ?>" 
					url: "<?= "https://vemaybaynamphuong.com/tim-chuyen-bay" ?>" 
				},
			<?php } else { ?>
			    {
    		    start: '<?= substr($_GET["in"],3,4)?>-<?= substr($_GET["in"],0,2) ?>-<?= sprintf('%02d',$in['day']) ?>',
				rendering: 'background',
				color: '#bdc3c7'
			    },
			<?php } } } ?>
		]
	});
	
});
</script>
<?php } ?>
<script type="text/javascript">
NProgress.done();
</script>