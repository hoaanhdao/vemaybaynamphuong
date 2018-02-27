<?php
	//if(isset($_POST['g-recaptcha-response'])){
		  //$captcha=$_POST['g-recaptcha-response'];
		//}
	//$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LcaSgYTAAAAACQwhrfuogkTTzJJb_3vCi1gW--h&response=" .$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
    
	//if ($response . success == false) {
        //echo 'Spam';
    //} else {
       // Everything is ok and you can proceed by executing your login, signup, update etc scripts
	   	if(isset($_POST["contact-name"])){
			include(TEMPLATEPATH.'/flight_config/sugarrest/sugar_rest.php');
			$err="";
			$sugar = new Sugar_REST();
			$error = $sugar->get_error();
			if($error){
				echo "loi khi ket noi den server";
				exit();
			}
			$name       = trim($_POST["contact-name"]);
			$email      = trim($_POST["contact-email"]);
			$content    = $_POST["contact-content"];
			$phone      = $_POST["contact-phone"];

			$args_request = array(
				'contact_name' => $name,
				'request_status' => 0,
				'phone' => $phone,
				'request_type' => 1,
				'email' => $email,
				'request_detail' => $content,
			);
			$request_id = $sugar->set("EC_Request_Flight",$args_request);

			if($request_id)
				echo 1;
			else
				echo 0;
			exit();
		}
    //}
	
	
	
?>
<?php
get_header();
?>
 <div class="row"> <div class="block">
	    <div class="col-md-12"> 
     
         <div id="map-canvas">
			<img src="<?php echo imgdir; ?>/NamPhuong-Maps3.jpg" alt="Vé máy bay Nam Phương">
		 </div>
           <?php if(have_posts()):the_post(); ?>
           
            <div class="row mt20">
                <div class="col-md-6 col-sm-6 col-xs-12">
                       
                  
						
						
                        <span style="color:#f20000; font-weight:bold; font-size:16px">Công ty TNHH VMB Nam Phương</span>
						<br>647 Đường 3/2, Phường 6, Q.10, TP.HCM
						<br>Tel : (08) 7300 1886 - 1900 63 6060
                    
					
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
						
                        <br>Email : vmbnamphuong@gmail.com
						<br>MST : 0312253052 - 24/7 : 0909588080
                    
                </div>
				<div id="ct_bank" class="col-md-12">
        <h3>Tài khoản ngân hàng :  <span style="color:#f20000;">CÔNG TY TNHH VMB NAM PHƯƠNG</span></h3>
		
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<table class="tbl_bank" style="width:99%;" >



            <tr class="tr-bank">
                <td class="bank-logo">
                    <img src="<?php bloginfo("template_directory") ?>/images/lg-vcb.png" alt="">
                </td>
                <td class="td-bank-detail">
                    <table class="tbl-bank-detail">
                        <tr class="bank-name">
                            <td colspan="2"><h4><strong>Vietcombank</strong> CN Sài Gòn</h4></td>
                        </tr>
                        
                        <tr>
                            <td class="bank-label">
                                Số TK :
                            </td>
                            <td class="bank-number">
                                <strong>0331000421204</strong>
                            </td>
                        </tr>
					    
                    </table>
                </td>

            </tr>

			
            <tr class="tr-bank">
                <td class="bank-logo">
                    &nbsp;
                </td>
                <td class="td-bank-detail">
                    &nbsp;
                </td>

            </tr>

			

            
			  
			
			
            
            

        </table>
				
			</div>
		</div>
		
        

		
		
    </div>


            </div> <!-- end #colLeft -->
       
		</div> 
		
			</div>
            
			
			 <?php else: ?>
            <div id="nonepost">
                Trang bạn đang truy cập hiện không có, vui lòng quay lại sau
            </div><!--#nonepost-->
			<?php endif; ?>
			</div>
            </div>
			 
            </div>

<?php
get_footer();
?>