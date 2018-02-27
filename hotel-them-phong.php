<?php
/*
Template Name: them-phong
*/
	$itemArray = array($_POST["code"]=>array('name'=>$_POST["name"]));

	if(!empty($_SESSION["hotel_cart"])) {
		if(in_array($_POST["code"],$_SESSION["hotel_cart"])) {
			foreach($_SESSION["hotel_cart"] as $k => $v) {
					if($_POST["code"] == $k)
						$_SESSION["hotel_cart"][$k]["name"] = $_POST["name"];
			}
		} else {
			$_SESSION["hotel_cart"] = array_merge($_SESSION["hotel_cart"],$itemArray);
		}
	} else {
		$_SESSION["hotel_cart"] = $itemArray;
	}
