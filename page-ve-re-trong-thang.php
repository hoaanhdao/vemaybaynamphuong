<?php
/*
Template Name: ve-re-trong-thang
*/
get_header();

    if($_POST['dep_code'] != null){
	
		$dep_code = str_replace(" ","+", $_POST['dep_code']);
		$arv_code = str_replace(" ","+", $_POST['arv_code']);
		if($_POST['arv_date'] == null ){ 
			$params = "?dep=".$dep_code."&arv=".$arv_code."&out=".$_POST['dep_date']."&adt=".$_POST['adt']."&chd=".$_POST['chd']."&inf=".$_POST['inf'];} 
		else {
			$params = "?dep=".$dep_code."&in=".$_POST['arv_date']."&arv=".$arv_code."&out=".$_POST['dep_date']."&adt=".$_POST['adt']."&chd=".$_POST['chd']."&inf=".$_POST['inf']; }
		}
		//print_r($params); exit();
	 
?>

<script type="text/javascript">
NProgress.start();
</script>
	
	<?php if($_POST['dep_code'] != null){?>
	<div id="MyDynamicDiv" class="container">
		<div class="container">
				<div class="row">
					<div class="col-md-6">
						<h5><i class="fa fa-refresh fa-spin"></i> Đang phân tích dữ liệu mới nhất, xin vui lòng chờ trong giây lát...</h5>
						<div id='dep-calendar'></div>
					</div>
					<?php if($_POST['arv_date'] != null){?>
					<div class="col-md-6">
						<h5><i class="fa fa-refresh fa-spin"></i> Đang phân tích dữ liệu mới nhất, xin vui lòng chờ trong giây lát...</h4>
						<div id='arv-calendar'></div>
					</div>
					<?php }?>
				</div>	
				 
		</div>
	</div>
	<?php }else{
	header("Location: https://vemaybaynamphuong.com/san-ve-gia-re");
	exit;
	} ?>
	
<script>

$(document).ready(function() {
	
		$('#dep-calendar').fullCalendar({
			contentHeight: 400,
			header: {
				left: '',
				center: '',
				right: ''
			},
			events: [
			]
		});
	
	
});
</script>
<?php if($_POST['arv_date'] != null){?>	
<script>
$(document).ready(function() {
		$('#arv-calendar').fullCalendar({
			contentHeight: 400,
			header: {
				left: '',
				center: '',
				right: ''
			},
			events: [
			]
		});
	 
});
</script>
<?php }?>

<script>
$('#MyDynamicDiv').load("https://vemaybaynamphuong.com/tim-ve-gia-re-core/<?= $params ?>",function(){});
</script>
<?php get_footer(); ?>
