<?php

	$now=time(); // DDOS
    $ran=rand(100,999999);
    $enCode=md5(time().$ran."NpVmbNp");
	date_default_timezone_set("Asia/Ho_Chi_Minh");
	$refer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
	$domain = preg_replace('(^https?://)', '', get_bloginfo('url'));
	$expired_time = 120; // in seconds

    if ((isset($_POST['btnsearch'.$_SESSION['fl_btn_search']]) || isset($_POST['btnchdate'.$_SESSION['fl_btn_chdate']]) || isset($_POST['wgbtnsearch'.$_SESSION['fl_wgbtn_search']])) && isset($_POST['dep']) && !isset($_GET["SessionID"]) && $refer == $domain ) {

        $_SESSION["SSID"]=null;
        $_SESSION["search"]=null;
        $_SESSION["card"]=null;
        $_SESSION["result"]=null;
		$_SESSION["result_inter"]=null;
        $_SESSION['booking']=null;
        $_SESSION['dep']=null;
        $_SESSION['ret']=null;
        $_SESSION['contact']=null;
        $_SESSION['int']=null;
        $_SESSION['pax']=null;
        $_SESSION['dep_flight']=null;
        $_SESSION['ret_flight']=null;
		$_SESSION['fl_captcha_ok']=null;
		$_SESSION['fl_token'] = null;
        $_SESSION['fl_req_count'] = null;
        $_SESSION['fl_req_count_allow'] = null;
		$_SESSION['fl_btn_search'] = null;
		$_SESSION['fl_wgbtn_search'] = null;
		$_SESSION['fl_btn_chdate'] = null;

        unset($_SESSION["SSID"]);
        unset($_SESSION["search"]);
        unset($_SESSION["card"]);
        unset($_SESSION["result"]);
		unset($_SESSION["result_inter"]);
        unset($_SESSION['booking']);
        unset($_SESSION['dep']);
        unset($_SESSION['ret']);
        unset($_SESSION['contact']);
        unset($_SESSION['int']);
        unset($_SESSION['pax']);
        unset($_SESSION['dep_flight']);
        unset($_SESSION['ret_flight']);
		unset($_SESSION['fl_captcha_ok']);
		unset($_SESSION['fl_token']);
        unset($_SESSION['fl_req_count']);
        unset($_SESSION['fl_req_count_allow']);
		unset($_SESSION['fl_btn_search']);
		unset($_SESSION['fl_wgbtn_search']);
		unset($_SESSION['fl_btn_chdate']);

        $_SESSION["SSID"]["ID"]=$enCode;
        $_SESSION["SSID"][$enCode]['s']=array();
        $condition = array(
            'way_flight' => $_POST['direction'],
            'source' => $_POST['dep'],
            'destination' => $_POST['des'],
            'depart' => $_POST['depdate'],
            'return' => $_POST['retdate'],
            'adult' => $_POST['adult'],
            'children' => $_POST['child'],
            'infant' => $_POST['infant']
        );

        $isactive=checkactive($_POST['dep'],$_POST['des']);
        if($isactive['vj'] || $isactive['vna'] || $isactive['js']){
            if($isactive['vj']) $condition['active']['vj']=true; else $condition['active']['vj']=false;
            if($isactive['js']) $condition['active']['js']=true; else $condition['active']['js']=false;
            if($isactive['vna']) $condition['active']['vna']=true; else $condition['active']['vna']=false;
        }
		
        if(!$GLOBALS['CODECITY'][$condition['source']] || !$GLOBALS['CODECITY'][$condition['destination']])
            $condition["isinter"]=true;
        else
            $condition["isinter"]=false;
		
		$expsearch = getexpsearch($condition["depart"]) + $expired_time;
        $_SESSION["SSID"][$enCode]['s']=$condition;
		$_SESSION["SSID"][$enCode]['s']['exp']=$expsearch; // DDOS
        $_SESSION["search"]=$condition;

        //cached it
        /*S query file*/
        $file = dirname(__FILE__)."/flight_config/squery.json";
        $json = json_decode(file_get_contents($file),true);
        $exp=$expsearch;
        if($json==NULL || empty($json)){
            $squery=array();
            $squery[$enCode]=array();
            $squery[$enCode]=$condition;
            $squery[$enCode]["exp"]=$exp;
            file_put_contents($file, json_encode($squery));
        }else{
            $json[$enCode]=$condition;
            $json[$enCode]["exp"]=$exp;
            file_put_contents($file, json_encode($json));
        }


        header("Location: "._page("flightresult")."?SessionID=".$enCode);
		exit;

    } elseif (isset($_GET["SessionID"]) && !isset($_POST['dep']) && !isset($_POST['sm_request'])) {

        $crssid=clearvar(trim($_GET["SessionID"]));
        $condition=array();

        if($_SESSION["SSID"][$crssid]){
            $condition = array(
                'way_flight' => $_SESSION['search']['way_flight'],
                'source' => $_SESSION['search']['source'],
                'destination' => $_SESSION['search']['destination'],
                'depart' => $_SESSION['search']['depart'],
                'return' => $_SESSION['search']['return'],
                'adult' => $_SESSION['search']['adult'],
                'children' => $_SESSION['search']['children'],
                'infant' => $_SESSION['search']['infant'],
                'isinter' => $_SESSION['search']['isinter'],
                'active'    => $_SESSION['search']['active']
            );
			
			// DDOS
			$exp=$_SESSION["SSID"][$crssid]["s"]["exp"];
			$diff=$now-$exp;
			if($diff>$expired_time){
				header("Location: ".get_bloginfo("url"));
				exit;
			}
			
        }else{
            $file = dirname(__FILE__)."/flight_config/squery.json";
            $squery = json_decode(file_get_contents($file),true);
            if(empty($squery[$crssid])){
                header("Location: ".get_bloginfo("url"));
				exit;
            }else{
                $condition=$squery[$crssid];
                $_SESSION["SSID"]["ID"]=$crssid;
                $_SESSION["SSID"][$crssid]['s']=$condition;
                $_SESSION["search"]=$condition;
				
				// DDOS
				$exp=$condition["exp"];
				$diff=$now-$exp;
				if($diff>$expired_time){
					header("Location: ".get_bloginfo("url"));
					exit;
				}
				
            }
        }

        $direction=$condition['way_flight'];
        $source=$condition['source'];
        $destination=$condition['destination'];
        $direction_fulltext=($condition['way_flight']==1)?"Một chiều":"Khứ hồi";
        $adults=$condition['adult'];
        $depart_fulltext=$condition['depart'];
        $returndate_fulltext=$condition['return'];
        $child=$condition['children'];
        $infant=$condition['infant'];
        $passfulltext=$adults." người lớn";
        $passfulltext.=($child!=0)?", ".$child." Trẻ em":"";
        $passfulltext.=($infant!=0)?", ".$infant." Trẻ sơ sinh":"";
        $countactive=(($condition['active']['vna'])?1:0)+(($condition['active']['vj'])?1:0)+(($condition['active']['js'])?1:0);
        $arrlinkrs=array();
        if($condition['active']['vna']) $arrlinkrs[]=_page('vnalink');
        if($condition['active']['vj']) $arrlinkrs[]=_page('vjlink');
        if($condition['active']['js']) $arrlinkrs[]=_page('jslink');

		// Gen token
		$_SESSION['fl_token'] = gen_random_string(rand(9,18));
    
		// Reset request count
		$_SESSION['fl_req_count'] = null;
		$_SESSION['fl_req_count_allow'] = null;
		unset($_SESSION['fl_req_count']);
		unset($_SESSION['fl_req_count_allow']);

    }else{
        header("Location: ".get_bloginfo("url"));
		exit;
    }
	
	// BEGIN LOG CLIENT REQUEST
	$ip_address = get_ip_address_from_client();
	$domain = preg_replace('(^https?://)', '', get_bloginfo('url'));
	$req_content = $condition['way_flight'].$condition['source'].$condition['destination'].$condition['depart'].$condition['return'].$condition['adult'].$condition['children'].$condition['infant'];
	$req_content = preg_replace('/[^a-zA-Z0-9]/', '', $req_content);
	log_client_request($domain, $ip_address, $req_content);
	// END LOG CLIENT REQUEST

    get_header();

?>
            <div  class="row" id="colLeftNoBorder">
				<div class="block">
					<ul id="progressbar" class="hidden-xs">
						<li class="current">
							<span class="pull-left">1. Chọn hành trình</span>
							<div class="bread-crumb-arrow"></div>
						</li>
						<li><span class="pull-left">2. Thông tin hành khách</span>
							<div class="bread-crumb-arrow"></div>
						</li>
						<li><span class="pull-left">3. Thanh toán</span>
							<div class="bread-crumb-arrow"></div>
						</li>
						<li><span class="pull-left">4. Hoàn tất</span></li>
					</ul>
					<div class="gap-small"></div>
					 
					 
				
				
				
                <?php
                if($condition)
                {
					
					// BEGIN CHECK CLIENT REQUEST
					$req_count_allow = 19;
					$req_time_allow = 1800; // in seconds
					$req_count = (int)check_client_request($domain, $ip_address, $req_time_allow);
					$_SESSION['fl_req_count'] = $req_count;
          			$_SESSION['fl_req_count_allow'] = $req_count_allow;
					
					if(!$ip_address || strtoupper($ip_address) == 'UNKNOWN' || (checkCIDRBlacklist($ip_address) && !$_SESSION['fl_captcha_ok']) || ($req_count > $req_count_allow && !$_SESSION['fl_captcha_ok'])) {
						
						$_SESSION['fl_captcha'] = simple_php_captcha(array('characters' => '0123456789'));
						$_SESSION['fl_captcha_ok'] = false;		
						include_once(TEMPLATEPATH."/tplpart-captchaform.php");
						
					}
					else {
					
						/*Neu tu search form*/
						($condition['children'] != 0) ? $qty_children = ', '.$condition['children'].' Trẻ em' : $qty_children = '';
						($condition['infant'] != 0) ? $qty_infants = ', '.$condition['infant'].' Trẻ sơ sinh' : $qty_infants = '';
	
						if($condition['way_flight'] == 0 && $condition['return'] != '')
							$str_return = 'Ngày về:</td><td><strong>'.$condition['return'].'</strong>';
						else
							$str_return = '</td><td>';
	
						# KIỂM TRA NẾU LÀ CHUYẾN NỘI ĐỊA
						if(!$condition["isinter"]){
							$waiting_notices = '<div class="row"><div class="col-md-8 col-md-offset-2 col-sm-12 waiting_block"><h2>Khởi hành từ <span class="fontplace">'.$GLOBALS['CODECITY'][$condition['source']].'</span> đi <span class="fontplace">'.$GLOBALS['CODECITY'][$condition['destination']].'</span></h2><table><tr><td>Loại vé:</td><td><strong>'.$GLOBALS['way_flight_list'][$condition['way_flight']].'</strong></td><td>Số hành khách:</td><td><strong>'.$condition['adult'].' người lớn'.$qty_children.$qty_infants.'</strong></td></tr><tr><td>Ngày khởi hành:</td><td><strong>'.$condition['depart'].'</strong></td><td>'.$str_return.'</td></tr></table><p class="notice-waiting">Mời bạn vui lòng chờ trong giây lát ...</p></div></div>';
							if(!$condition['active']['vna'] && !$condition['active']['js'] && !$condition['active']['vj']){ /*Neu duong bay ko co tuyen nay*/
								$isempty_flight=true;
							}else{
								?>
								<script type="text/javascript">
									var SessionID='<?php echo $crssid?>';
									var Direction=<?php echo $direction?>;
									var DirectionText='<?php echo $direction_fulltext?>';
									var Source='<?php echo $source?>';
									var Destination='<?php echo $destination?>';
									var SourceCity='<?php echo $GLOBALS['CODECITY'][$source]?>';
									var DesCity='<?php echo $GLOBALS['CODECITY'][$destination]?>';
									var Departdate='<?php echo $condition['depart']?>';
									var Returndate='<?php echo $condition['return']?>';
									var Adult=<?php echo $adults?>;
									var Child=<?php echo $child?>;
									var Infant=<?php echo $infant?>;
									var PassengerText='<?php echo $passfulltext?>';
									var CountActive=<?php echo $countactive?>;
									var Hotline='<?php echo  get_option("fl_phone") ?>';
									var Getrs=new Array(<?php echo "'".implode("','",$arrlinkrs)."'"; ?>);
									var XhrRequest=new Array();
	
									for(var i=0;i<Getrs.length;i++){
	
										XhrRequest[i]=$.ajax({
											url:Getrs[i],
											cache:false,
											traditional: true,
											type: "POST",
											data:"enCode="+SessionID+"&cache=<?php echo  ($_GET["clearcache"])?0:1; ?>&<?php echo $_SESSION['fl_token']; ?>=",
											timeout:45000,
											dataType: "html"
										}).done(function(data){
												$(function(){
													processResult(data);
												})
										}).error(function(){
													CountActive--;
													$(document).ready(function(){
														if(CountActive==0 && ArrayResult['count']==0){
															var emptyhtml=emptyflight();
															$(document).ready(function(){$("#result").html(emptyhtml)});
														}
													})
											})
									}
									$(document).ready(function(){$("#loadresultfirst").html('<?php echo $waiting_notices?>')});
	
								</script>
								<?php
							}
	
							# ELSE LÀ VÉ QUỐC TẾ
						}else{
							$source_ia = getCityName($condition['source']);
							$destination_ia = getCityName($condition['destination']);
	
							$waiting_notices = '<div class="row"><div class="col-md-8 col-md-offset-2 col-sm-12 waiting_block"><h2>Khởi hành từ <span class="fontplace">'.$source_ia.'</span> đi <span class="fontplace">'.$destination_ia.'</span></h2><table><tr><td>Loại vé:</td><td><strong>'.$GLOBALS['way_flight_list'][$condition['way_flight']].'</strong></td><td>Số hành khách:</td><td><strong>'.$condition['adults'].' người lớn'.$qty_children.$qty_infants.'</strong></td></tr><tr><td>Ngày khởi hành:</td><td><strong>'.$condition['depart'].'</strong></td><td>'.$str_return.'</td></tr></table><p class="notice-waiting">Mời bạn vui lòng chờ trong giây lát ...</p></div></div>';
							?>
	
							<script type="text/javascript">
							
								var SessionID='<?=$crssid?>';
								var Direction=<?=$direction?>;
								var DirectionText='<?=$direction_fulltext?>';
								var Source='<?=$source?>';
								var Destination='<?=$destination?>';
								var SourceCity='<?=$source_ia?>';
								var DesCity='<?=$destination_ia?>';
								var Departdate='<?=$condition['depart']?>';
								var Returndate='<?=$condition['return']?>';
								var Adult=<?=$adults?>;
								var Child=<?=$child?>;
								var Infant=<?=$infant?>;
								var PassengerText='<?=$passfulltext?>';
								var Hotline='<?= get_option("fl_phone") ?>';
								
								$(document).ready(function(){
									$("#loadresultfirst").html('<?=$waiting_notices?>');
									$.ajax({
										url:"<?= _page('interlink') ?>",
										cache:false,
										traditional:true,
										type:"POST",
										data:"enCode="+SessionID,
										timeout:45000,
										dataType:"html",
										before:function(){
											$('#result').html('');
											$("#loadresultfirst").show();
										},
										success:function(output){
											$('#result').html('');
											$('#result').html(output);
										},
										complete:function(){
											$('#result').show();
											$("#wgbox").show();
											$(".wg-search").show();
											$("#loadresultfirst").hide();
										}
									});
									
								});
								
							</script>
							<?php
						}
					
					} // END CHECK CLIENT REQUEST
					
                } // END IF
				 
                elseif(isset($_POST['sm_request'])){ /*######If submit from request form#########*/ ?> 
					
				<?php
						 
					   // Everything is ok and you can proceed by executing your login, signup, update etc scripts
						require(TEMPLATEPATH . '/flight_config/sugarrest/sugar_rest.php');
						$sugar = new Sugar_REST();
						$error = $sugar->get_error();
						$arr_req = array(
							'contact_name'   => trim($_POST['fullname']),
							'phone'		  => trim($_POST['phone']),
							'request_detail' => $_POST['content_request'],
							'request_type'   => 3,
							'request_status' => 0,
						);
						$req_id = $sugar->set("EC_Request_Flight",$arr_req);

						if($req_id){
							?>
							<div class="emptyflight_block">
								<h3>Yêu cầu của bạn đã được gửi thành công</h3>
								<p>Hệ thống đã nhận được yêu cầu của bạn! Nhân viên chúng tôi sẽ liên hệ lại với bạn trong vòng 5 phút.</p>
								<p>Cần trợ giúp bạn hãy gọi theo số <strong style="font-size:16px;color:#FE5815;"><?php echo  get_option('opt_phone');?></strong>.</p>
								<p style="color:#03F" ><a href="<?php bloginfo('siteurl');?>" >&laquo; Trở về trang chủ &raquo;</a></p>
							</div>
							<?php
						}else{
							?>
							<div class="emptyflight_block">
								<h3>Gửi thất bại!</h3>
								<p>Bạn hãy liên hệ theo số <strong style="font-size:16px;color:#FE5815;"><?php echo  get_option('opt_phone'); ?></strong>, để được trợ giúp</p>
								<p style="color:#03F" ><a href="<?php bloginfo('siteurl');?>" >&laquo; Trở về trang chủ &raquo;</a></p>
							</div>
							<?php
						}
					
                }
                else{
                    ?>
                    <div class="emptyflight_block">
                        <h3 class="noinfo">Vui lòng chọn Thông tin tìm kiếm chuyến bay</h3>
                    </div>
                    <?php
                }
                ?>
                
				<div id="loadresultfirst"></div>
				<div class="col-md-6 col-md-offset-3 col-sm-12">
					<?php if($isempty_flight) include_once(TEMPLATEPATH."/tplpart-emptyflight.php"); ?>
				</div>
				<div class="col-md-4"><?php get_sidebar(); ?></div><!--#ctright-->
				 <div id="mainDisplay" class="col-md-8 sidebar-separator inversed">
					
						<div id="result">
                        <form action="<?php echo _page("passenger")?>" method="post" id="frmSelectFlight">
                           
                                <!--Thong Tin Chang Di-->
							<div class="sinfo box">
							
								  <div class="col-md-12 col-sm-12 col-xs-12">
								     <div class="col-md-5 col-ms-12 col-xs-12 ">
									 <h3 class="title hidden-xs">Chiều đi : </h3>
									<span class="big"><?php echo  $GLOBALS['CODECITY'][$source] ?></span> <i class="fa fa-fighter-jet yellow-color"></i>  
									<span class="big"><?php echo  $GLOBALS['CODECITY'][$destination] ?></span></div>
								 	<div class="col-md-3 col-ms-6 col-xs-12"><i class="fa fa-ticket yellow-color"></i> <span>Loại vé : </span><strong><?php echo  $direction_fulltext ?></strong></div>
									<div class="col-md-4 col-ms-6 col-xs-12"><i class="fa fa-users yellow-color"></i> <span>Số lượng: </span> <strong><?php echo  $adults ?> người lớn<?php echo  $qty_children.$qty_infants ?></strong>	</div>
									<div class="hidden-xs location contload"></div>
								 </div>
								 
                               
                            </div>


                            <ul class="date-picker hidden-xs">
                                <?php
                                $arr_depDate = date_of_currentdate($depart_fulltext);
                                $classli='class="firstli"';
                                foreach($arr_depDate as $val)
                                {
                                    ?>
                                    <li <?php if($classli!=""){ echo $classli; $classli=""; } ?> <?php if($val==$depart_fulltext) echo 'class="active"';?>>
                                        <a rel="<?php echo  $val ?>" class="changedepartflight">
                                            <span><?php echo  echoDate($val); ?></span>
                                            <span><?php echo  $val ?></span>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>

                            <table class="table flightlist" border="0" id="OutBound">
                                 <thead>
										 <tr>
										<th class="type-string sortairport col-md-3  col-sm-3 col-xs-3">
											<span><i class="fa fa-plane"></i></span>
											<span class="hidden-xs">Chuyến bay </span>
										</th>
										<th  class="type-string sorttime col-md-2  col-sm-2 col-xs-2"  style="text-align:center">
											<span><i class="fa fa-clock-o"></i></span>
											<span class="hidden-xs">Khởi hành </span>
										</th>
										<th  class="type-string sorttime col-md-2  col-sm-2 hidden-xs"  style="text-align:center">
											<span><i class="fa fa-clock-o"></i></span>
											<span class="hidden-xs">Đến </span>
										</th>
										<th  class="type-string sortprice col-md-3  col-sm-3 col-xs-4" style="text-align:left">
											<span><i class="fa fa-ticket"></i></span> 
											<span class="hidden-xs">Giá vé </span>
										</th>
										<th align="center" class="col-md-2 col-sm-2 col-xs-1">
											<span><i class="fa fa-angle-double-down"></i></span>
											<span class="hidden-xs">Xem</span>
										</th>
									 </tr>
                                </thead>
                                <tbody>
                                </tbody>
								<!-- <tfoot>
										<tr>
											<td colspan="6">
											<div class="pager">
											  <nav class="left hidden-xs">
												Kết quả mỗi trang:
												<a href="#">15</a> |
												<a href="#" class="current">20</a> |
												<a href="#">25</a> |
												<a href="#">30</a> |
												<a href="#">35</a>
											</nav>
											  <nav class="right">
												<span class="prev">
												  <img src="<?php echo tpldir?>/images/prev.png" /> Trước&nbsp;
												</span>
												<span class="pagecount"></span>
												&nbsp;<span class="next">Tiếp
												  <img src="<?php echo tpldir?>/images/next.png" />
												</span>
											  </nav>
											</div>
											</td>
										</tr>
									</tfoot> -->
								
                            </table>

                        <?php if(isset($_SESSION['search']['way_flight']) && $_SESSION['search']['way_flight'] == "0"){ ?>
							
                                <!--Thong Tin Chang Di-->
							<div class="sinfo box">
							
							   <div class="col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-5 col-ms-12 col-xs-12 ">
								 <h3 class="title hidden-xs">Chiều về</h3>
									<span class="big"><?php echo  $GLOBALS['CODECITY'][$destination] ?></span> <i class="fa fa-fighter-jet yellow-color"></i>  
									<span class="big"><?php echo  $GLOBALS['CODECITY'][$source] ?></span>
								</div>
								<div class="col-md-3 col-ms-6 col-xs-12"><i class="fa fa-ticket yellow-color"></i> <span>Loại vé : </span><strong><?php echo  $direction_fulltext ?></strong></div>
								<div class="col-md-4 col-ms-6 col-xs-12"><i class="fa fa-users yellow-color"></i> <span>Số lượng: </span> <strong><?php echo  $adults ?> người lớn<?php echo  $qty_children.$qty_infants ?></strong>	</div>
                                <div class="hidden-xs location contload"></div>
								</div>
								 
                               
                            </div>
							
                      

                            <ul class="date-picker  hidden-xs">
                                <?php
                                $arr_retDate = date_of_currentdate($returndate_fulltext);
                                $classli='class="firstli"';
                                foreach($arr_retDate as $val)
                                {
                                    ?>
                                    <li <?php if($classli!=""){ echo $classli; $classli=""; } ?> <?php if($val==$returndate_fulltext) echo 'class="active"';?>>
                                        <a rel="<?php echo  $val ?>" class="changereturnflight">
                                            <span><?php echo  echoDate($val); ?></span>
                                            <span><?php echo  $val ?></span>
                                        </a>

                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>

                            <table class="table flightlist" border="0" id="InBound">
                                <thead>
								 	   <tr>
										<th class="type-string sortairport col-md-3 col-sm-3 col-xs-3">
											<span><i class="fa fa-plane"></i></span>
											<span class="hidden-xs">Chuyến bay </span>
										</th>
										<th  class="type-string sorttime col-md-2  col-sm-2 col-xs-2"  style="text-align:center">
											<span><i class="fa fa-clock-o"></i></span>										
											<span class="hidden-xs">Khởi hành </span>
										</th>
										<th  class="type-string sorttime col-md-2  col-sm-2 hidden-xs"  style="text-align:center">
											<span><i class="fa fa-clock-o"></i></span>
											<span class="hidden-xs">Đến </span>
										</th>
										<th  class="type-string sortprice col-md-3  col-sm-3 col-xs-4" style="text-align:left">
											<span><i class="fa fa-ticket"></i></span> 
											<span class="hidden-xs">Giá vé</span>
										</th>
										<th align="center" class="col-md-2 col-sm-2 col-xs-1">
											<span><i class="fa fa-angle-double-down"></i></span>										
											<span class="hidden-xs">Xem</span>
										</th>
									 </tr>
                                </thead>
                                <tbody>
                                </tbody>
								<!-- <tfoot>
									<tr>
										<td colspan="6">
										<div class="pager">
										  <nav class="left hidden-xs">
											Kết quả mỗi trang:
											<a href="#">15</a> |
											<a href="#" class="current">20</a> |
											<a href="#">25</a> |
											<a href="#">30</a> |
											<a href="#">35</a>
										  </nav>
										  <nav class="right">
											<span class="prev">
											  <img src="<?php echo tpldir?>/images/prev.png" /> Trước&nbsp;
											</span>
											<span class="pagecount"></span>
											&nbsp;<span class="next">Tiếp
											  <img src="<?php echo tpldir?>/images/next.png" />
											</span>
										  </nav>
										</div>
										</td>
									</tr>
								</tfoot> -->
                            </table>

                        <?php } ?>
                             
							<div id="flightselectbt" class=" continue-flight-result scrollDown mt30"> <span class="moreScroll visible-lg">Kéo xuống để xem thêm kết quả</span>
							  <button type="submit" id="sm_fselect" name="sm_fselect" class="button  pull-right"><span> Tiếp tục</span></button>
							  <br>
							  <span class="noneselect"></span> 
							</div>
							<div class="clearfix"></div>  
                        </form>
                        <form name="changedate" method="post" action="<?php echo  _page("flightresult") ?>" style="display: none;" id="frmchangedate">
							<?php $_SESSION['fl_btn_chdate'] = gen_random_string(rand(9,18)); ?>
							<input type="hidden" name="btnchdate<?php echo $_SESSION['fl_btn_chdate']; ?>" />
						</form>
                    </div>
                </div>
         	
			
			</div>
		 </div><!-- #colLeftNoBorder -->

         
           

<?php
    get_footer();
?>