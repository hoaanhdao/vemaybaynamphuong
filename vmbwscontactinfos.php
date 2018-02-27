<?php
/*
Template Name: vmbwscontactinfos
*/
	$arr['name'] = 'Công ty TNHH VMB Nam Phương';
	$arr['tax_code'] = '0312253052';
	$arr['address'] = '10C5 Đường D1, P.25, Q. Bình Thạnh, TP.HCM';
	$arr['mobile'] = array('0914 600 802', '091 30 30 802', '(08) 6680 6320');
	$arr['email'] = array('vmbsale@gmail.com','vmbhotro@gmail.com');
	$arr['bank_infos'] = array(
		0 => array(
			'bank_name' => 'Ngân hàng thương mại cổ phần Ngoại thương Việt Nam',
			'acc_name' => 'CÔNG TY TNHH VMB NAM PHƯƠNG',
			'acc_num' => '0331000421204',
			'branch' => 'VCB Bến Thành',
		),
	);
	header('Content-type: application/json');
	echo json_encode($arr);
	exit();
?>