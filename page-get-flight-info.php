<?php
/**
 * Created by Notepad.
 * User: Lak
 * Date: 11/11/13
 */
if((isset($_SESSION["search"]) && $_SESSION["securecode"]==$_POST["enCode"]) || (isset($_SESSION["search"]) && $_POST["fromdate"] != "") || (isset($_SESSION["search"]) && $_POST["todate"] != "") ):
    include(TEMPLATEPATH.'/flight_config/clsflight.php');

    /*if Change Date*/
    if($_POST["fromdate"] && $_POST["fromdate"]!=""){
        $_SESSION["search"]["depart"]=$_POST["fromdate"];
    }
    if($_POST["todate"] && $_POST["todate"]!=""){
        $_SESSION["search"]["return"]=$_POST["todate"];
    }



    $_SESSION["result"]=null;
    unset($_SESSION["result"]);

    $curDay = (int)date('d');
    $curMonth = (int)date('m');
    $curYear = (int)date('Y');
    # Template Directory
    $siteurl = get_bloginfo("url");
    $way_flight = $_SESSION["search"]['way_flight'];
    $source = $_SESSION["search"]['source'];
    $destination = $_SESSION["search"]['destination'];
    $depart_fulltext = $_SESSION["search"]['depart'];	// NGAY DI
    $depart = explode('/',$_SESSION["search"]['depart']);
    $depDay = $depart[0];
    $depMonth= $depart[1];
    $depYear = $depart[2];

    $adults = $_SESSION["search"]['adult'];
    $children = $_SESSION["search"]['children'];
    $infants = $_SESSION["search"]['infant'];

    ($children != 0) ? $qty_children = ', '.$children.' Trẻ em' : $qty_children = '';
    ($infants != 0) ? $qty_infants = ', '.$infants.' Trẻ sơ sinh' : $qty_infants = '';

    $flight=new clsflight();
    $flight->setOneway($way_flight); #NEU 1 CHIEU SET ONEWAY=1, 2 CHIEU DE LA ONEWAY=0
    $direction_fulltext=($way_flight==1)?"Một chiều":"Khử hồi";

    #CREATE COOKIE
    $cookiefile=dirname(__FILE__);
    $cookiefile .= '/flight_config/tmpcookie/'.time()."_".rand(1,100);

    $cookievn=$cookiefile."_vn.txt";
    $cookievj=$cookiefile."_vj.txt";
    $cookiejs=$cookiefile."_js.txt";

    @$fhandle=fopen($cookievn,"w");
    fclose($fhandle);
    @$fhandle=fopen($cookievj,"w");
    fclose($fhandle);
    @$fhandle=fopen($cookiejs,"w");
    fclose($fhandle);
    if(file_exists($cookievn))
        @chmod($cookievn,0777);
    if(file_exists($cookievj))
        @chmod($cookievj,0777);
    if(file_exists($cookiejs))
        @chmod($cookiejs,0777);
    $flight->setCookieVn($cookievn);
    $flight->setCookiejs($cookiejs);
    $flight->setCookieVj($cookievj);

    #SET ORTHER VAR
    $flight->setDepday($depDay);
    $flight->setDepmonth($depMonth);
    $flight->setDepyear($depYear);
    #SET ROUND TRIP
    if(isset($_SESSION["search"]['return']) && $_SESSION["search"]['return'] != '')
    {
        $returndate_fulltext = $_SESSION["search"]['return']; // NGAY VE
        $returndate = explode('/',$_SESSION["search"]['return']);
        $retDay = $returndate[0];
        $retMonth = $returndate[1];
        $retYear = $returndate[2];

        $flight->setRetday($retDay );
        $flight->setRetmonth($retMonth);
        $flight->setRetyear($retYear);
    }

    $flight->setDep($source);
    $flight->setArv($destination);

    #GET RESULT ARRAY
    $flight->getFlight();
    $arr_flight = array();
    $arr_flight = $flight->rs;

    #XOA FILE COOKIE
    if(file_exists($cookievj))
        unlink($cookievj);
    if(file_exists($cookiejs))
        unlink($cookiejs);
    if(file_exists($cookievn))
        unlink($cookievn);

    #FOR DEBUG ONLY
    if(current_user_can("activate_plugins")){
        echo '<div style="display: none;">';
        print_r($flight->getInfo());
        echo '</div>';
    }

    /*Khoi tao mang chua ket qua*/
    $arr=array();
    if($way_flight==1){ /*IF ONEWAY*/
        $temp = 0;

        if(!empty($arr_flight['vietnamairline'])){
            foreach($arr_flight['vietnamairline'] as $key => $val){
                $val['price']=trim($val['price'])+36000;
                $suffix = str_pad($temp,4,0,STR_PAD_LEFT);
                $val['deptime']=trim(str_replace("Next Day Indicator","",$val['deptime']));
                $val['arvtime']=trim(str_replace("Next Day Indicator","",$val['arvtime']));

                $arr['dep'][time().$suffix] = array(
                    'airline' => 'vietnamairline',
                    'dep' => $val['dep'],
                    'arv' => $val['arv'],
                    'deptime' => $val['deptime'],
                    'arvtime' => $val['arvtime'],
                    'flightno' => $val['flightno'],
                    'price' => $val['price'],
                    'class' => $val['class'],
                    'stop' => $val['stop']
                );
                $temp++;
            }
        }

        if(!empty($arr_flight['jetstar'])){
            foreach($arr_flight['jetstar'] as $key => $val){
                $val['price']=trim($val['price'])+36000;
                $suffix = str_pad($temp,4,0,STR_PAD_LEFT);
                $arr['dep'][time().$suffix] = array( 'airline' => 'jetstar',
                    'dep' => $val['dep'],
                    'arv' => $val['arv'],
                    'deptime' => $val['deptime'],
                    'arvtime' => $val['arvtime'],
                    'flightno' => $val['flightno'],
                    'price' => $val['price'],
                    'class' => $val['class'],
                    'stop' => $val['stop']
                );
                $temp++;
            }
        }

        if(!empty($arr_flight['vietjetair'])){
            foreach($arr_flight['vietjetair'] as $key => $val){
                $val['price']=trim($val['price'])+36000;
                $suffix = str_pad($temp,4,0,STR_PAD_LEFT);
                $arr['dep'][time().$suffix] = array( 'airline' => 'vietjetair',
                    'dep' => $val['dep'],
                    'arv' => $val['arv'],
                    'deptime' => $val['deptime'],
                    'arvtime' => $val['arvtime'],
                    'flightno' => $val['flightno'],
                    'price' => $val['price'],
                    'class' => $val['class'],
                    'stop' => $val['stop'],
                );
                $temp++;
            }
        }

        if(!empty($arr['dep'])):
            $_SESSION["result"]=$arr['dep'];
            $arr['ret'] = array();
        endif;

    }else{ /*IF ROUNDTRIP*/

        $temp = 0;

        if(!empty($arr_flight['vietnamairline']['dep'])){
            foreach($arr_flight['vietnamairline']['dep'] as $key => $val){
                $val['price']=trim($val['price'])+36000;
                $suffix = str_pad($temp,4,0,STR_PAD_LEFT);
                $val['deptime']=trim(str_replace("Next Day Indicator","",$val['deptime']));
                $val['arvtime']=trim(str_replace("Next Day Indicator","",$val['arvtime']));

                $arr['dep'][time().$suffix] = array(
                    'airline' => 'vietnamairline',
                    'dep' => $val['dep'],
                    'arv' => $val['arv'],
                    'deptime' => $val['deptime'],
                    'arvtime' => $val['arvtime'],
                    'flightno' => $val['flightno'],
                    'price' => $val['price'],
                    'class' => $val['class'],
                    'stop' => $val['stop']
                );
                $temp++;
            }
        }

        if(!empty($arr_flight['jetstar']['dep'])){
            foreach($arr_flight['jetstar']['dep'] as $key => $val){
                $val['price']=trim($val['price'])+36000;
                $suffix = str_pad($temp,4,0,STR_PAD_LEFT);
                $arr['dep'][time().$suffix] = array(   'airline' => 'jetstar',
                    'dep' => $val['dep'],
                    'arv' => $val['arv'],
                    'deptime' => $val['deptime'],
                    'arvtime' => $val['arvtime'],
                    'flightno' => $val['flightno'],
                    'price' => $val['price'],
                    'class' => $val['class'],
                    'stop' => $val['stop']
                );
                $temp++;
            }
        }

        if(!empty($arr_flight['vietjetair']['dep'])){
            foreach($arr_flight['vietjetair']['dep'] as $key => $val){
                $val['price']=trim($val['price'])+36000;
                $suffix = str_pad($temp,4,0,STR_PAD_LEFT);
                $arr['dep'][time().$suffix] = array(
                    'airline' => 'vietjetair',
                    'dep' => $val['dep'],
                    'arv' => $val['arv'],
                    'deptime' => $val['deptime'],
                    'arvtime' => $val['arvtime'],
                    'flightno' => $val['flightno'],
                    'price' => $val['price'],
                    'class' => $val['class'],
                    'stop' => $val['stop']
                );
                $temp++;
            }
        }


        # Chieu ve

        if(!empty($arr_flight['vietnamairline']['ret'])){
            foreach($arr_flight['vietnamairline']['ret'] as $key => $val){
                $val['price']=trim($val['price'])+36000;
                $suffix = str_pad($temp,4,0,STR_PAD_LEFT);

                $val['deptime']=trim(str_replace("Next Day Indicator","",$val['deptime']));
                $val['arvtime']=trim(str_replace("Next Day Indicator","",$val['arvtime']));

                $arr['ret'][time().$suffix] = array(
                    'airline' => 'vietnamairline',
                    'dep' => $val['dep'],
                    'arv' => $val['arv'],
                    'deptime' => $val['deptime'],
                    'arvtime' => $val['arvtime'],
                    'flightno' => $val['flightno'],
                    'price' => $val['price'],
                    'class' => $val['class'],
                    'stop' => $val['stop']
                );
                $temp++;
            }
        }

        if(!empty($arr_flight['jetstar']['ret'])){
            foreach($arr_flight['jetstar']['ret'] as $key => $val){
                $val['price']=trim($val['price'])+36000;
                $suffix = str_pad($temp,4,0,STR_PAD_LEFT);
                $arr['ret'][time().$suffix] = array(   'airline' => 'jetstar',
                    'dep' => $val['dep'],
                    'arv' => $val['arv'],
                    'deptime' => $val['deptime'],
                    'arvtime' => $val['arvtime'],
                    'flightno' => $val['flightno'],
                    'price' => $val['price'],
                    'class' => $val['class'],
                    'stop' => $val['stop']
                );
                $temp++;
            }
        }

        if(!empty($arr_flight['vietjetair']['ret'])){
            foreach($arr_flight['vietjetair']['ret'] as $key => $val){
                $val['price']=trim($val['price'])+36000;
                $suffix = str_pad($temp,4,0,STR_PAD_LEFT);
                $arr['ret'][time().$suffix] = array(
                    'airline' => 'vietjetair',
                    'dep' => $val['dep'],
                    'arv' => $val['arv'],
                    'deptime' => $val['deptime'],
                    'arvtime' => $val['arvtime'],
                    'flightno' => $val['flightno'],
                    'price' => $val['price'],
                    'class' => $val['class'],
                    'stop' => $val['stop']
                );
                $temp++;
            }
        }

    } /*ROUNDTRIP OR ONEWAY*/

    $_SESSION['result']=array();
    if(!empty($arr['dep'])){
        foreach($arr['dep'] as $key=>$val){
            $_SESSION['result'][$key]=$val;
        }
    }
    if(!empty($arr['ret'])){
        foreach($arr['ret'] as $key=>$val){
            $_SESSION['result'][$key]=$val;
        }
    }

    /*UNSET CARD*/
    $_SESSION['dep'] = NULL;
    $_SESSION['ret'] = NULL;
    unset($_SESSION['dep']);
    unset($_SESSION['ret']);



    /* ************ DISPLAY RESULT ****************** */
    if( (!empty($arr['dep']) && $_SESSION['search']['way_flight'] == "1") || (!empty($arr['dep']) && !empty($arr['ret']) && $_SESSION['search']['way_flight'] == "0") ){
        /*if Has flight*/
        ?>
    <h1 class="choisetitle"> &raquo; Lựa chọn chuyến bay &laquo;</h1>

    <form action="<?php echo _page("passenger")?>" method="post" id="frmSelectFlight">
        <div id="sinfo">
            <!--Thong Tin Chang Di-->
            <p class="location">
                <span class="fontplace"><?php echo  $GLOBALS['CODECITY'][$source] ?></span> đi <span class="fontplace"><?php echo  $GLOBALS['CODECITY'][$destination] ?></span>
            </p>
            <table class="info">
                <tr>
                    <td><span>Loại vé : </span><strong><?php echo  $direction_fulltext ?></strong></td>
                    <td><span>Số hành khách: </span> <strong><?php echo  $adults ?> người lớn<?php echo  $qty_children.$qty_infants ?></strong></td>
                </tr>
                <tr>
                    <td><span class="indate">Ngày xuất phát : <strong><?php echo  $depart_fulltext ?></strong></span></td>
                    <td>
                        <?php if($way_flight!=1){ ?>
                        <span class="indate">Ngày về : <strong><?php echo  $returndate_fulltext ?></strong></span>
                        <?php } ?>
                    </td>
                </tr>
            </table>

        </div>

        <!--SEARCH IN DIFFERENCE DAY-->
        <ul class="date-picker clear">
            <?php
            $arr_depDate = date_of_currentdate($depart_fulltext);
            $classli='class="firstli"';
            foreach($arr_depDate as $val)
            {
                ?>
                <li <?php if($classli!=""){ echo $classli; $classli=""; } ?> <?php if($val==$depart_fulltext) echo 'class="active"';?>>
                    <a rel="<?php echo  $val ?>" class="changedepartflight">
                        <span><?php echo  echoDate($val); ?></span>
                        <span style="font-weight:bold;"><?php echo  $val ?></span>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>

        <table class="flightlist" border="0" id="OutBound">
            <thead>
            <tr>
                <th style="padding-left:15px;text-align:left;width:150px;cursor: pointer;" class="type-string sortairport">Chuyến bay</th>
                <th width="130px" class="type-string sorttime" style="text-align:center;;cursor: pointer;">Thời gian</th>
                <th width="140px" style="text-align:right;padding-right:30px;cursor: pointer;" class="type-string sortprice">Giá rẻ nhất</th>
                <th width="80px" align="center">&nbsp;</th>
                <th width="160px" align="center">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
                <?php
                foreach($arr['dep'] as $key => $value ){

                    if($value['price'] != 0) {

                        $trclass="";
                        if($value['airline'] == 'vietnamairline'){
                            $logo_class = 'bg_vnal';
                            $trclass="vna";
                        }
                        elseif($value['airline'] == 'jetstar'){
                            $logo_class = 'bg_js';
                            $trclass="js";
                        }
                        elseif($value['airline'] == 'vietjetair'){
                            $logo_class = 'bg_vj';
                            $trclass="vj";
                        }
                        ?>
                    <tr class="lineresult-main <?php echo $trclass?>" id="<?php echo $key?>">
                        <td class="f_code <?php echo  $logo_class ?>"><?php echo  $value['flightno']?></td>
                        <td class="f_time"><?php echo  $value['deptime'].' - '.$value['arvtime'] ?></td>
                        <td class="f_price"><?php echo  format_number($value['price']).' VND'?></td>
                        <td class="f_detail"><a href="#" class="viewdetail" rel="<?php echo $key?>">Chi tiết</a> </td>
                        <td class="f_select">
                            <div style="position:relative">
                                <input type="radio" name="selectflightdep" class="selectflight" value="<?php echo  $key ?>" id="selectflightdep<?php echo  $key?>" />
                                <label for="selectflightdep<?php echo  $key?>">Chọn</label>
                            </div>
                        </td>
                    </tr>

                        <?php
                    }//End Price #0
                } // END FOREACH
                ?>
            </tbody>
        </table>

        <?php if(isset($_SESSION['search']['way_flight']) && $_SESSION['search']['way_flight'] == "0"){ ?>

        <div id="sinfo">
            <!--Thong Tin Chang Di-->
            <p class="location">
                <span class="fontplace"><?php echo  $GLOBALS['CODECITY'][$destination] ?></span> đi <span class="fontplace"><?php echo  $GLOBALS['CODECITY'][$source] ?></span>
            </p>
        </div>

        <ul class="date-picker">
            <?php
            $arr_retDate = date_of_currentdate($returndate_fulltext);
            $classli='class="firstli"';
            foreach($arr_retDate as $val)
            {
                ?>
                <li <?php if($classli!=""){ echo $classli; $classli=""; } ?> <?php if($val==$returndate_fulltext) echo 'class="active"';?>><a rel="<?php echo  $val ?>" class="changereturnflight">
                    <span><?php echo  echoDate($val); ?></span>
                    <span style="font-weight:bold;"><?php echo  $val ?></span></a></li>
                <?php
            }
            ?>
        </ul>

        <table class="flightlist" border="0" id="InBound">
            <thead>
            <tr>
                <th style="padding-left:15px;text-align:left;width:150px;cursor: pointer;" class="type-string sortairport">Chuyến bay</th>
                <th width="130px" class="type-string sorttime" style="text-align:center;cursor: pointer;">Thời gian</th>
                <th width="140px" style="text-align:right;padding-right:30px;cursor: pointer;" class="type-string sortprice">Giá rẽ nhất</th>
                <th width="80px" align="center">&nbsp;</th>
                <th width="160px" align="center">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
                <?php
                foreach($arr['ret'] as $key => $value ){
                    $trclass="";
                    if($value['airline'] == 'vietnamairline'){
                        $logo_class = 'bg_vnal';
                        $trclass="vna";
                    }
                    elseif($value['airline'] == 'jetstar'){
                        $logo_class = 'bg_js';
                        $trclass="js";
                    }
                    elseif($value['airline'] == 'vietjetair'){
                        $logo_class = 'bg_vj';
                        $trclass="vj";
                    }
                    ?>
                <tr class="lineresult-main <?php echo $trclass?>">
                    <td class="f_code <?php echo  $logo_class ?>"><?php echo  $value['flightno']?></td>
                    <td class="f_time"><?php echo  $value['deptime'].' - '.$value['arvtime'] ?></td>
                    <td class="f_price"><?php echo  format_number($value['price']).' VND' ?></td>
                    <td class="f_detail"><a href="#" class="viewdetail" rel="<?php echo $key?>">Chi tiết</a> </td>
                    <td class="f_select">
                        <div style="position:relative">
                            <input type="radio" name="selectflightret" class="selectflight" value="<?php echo  $key ?>" id="selectflightret<?php echo  $key ?>" />
                            <label for="selectflightret<?php echo  $key ?>">Chọn</label>
                        </div>
                    </td>
                </tr>
                    <?php
                } //End foreach duyet qua vong lap chuyen ve
                ?>
            </tbody>
        </table>

        <?php }//End if way_fight= roundtrip ?>
        <div id="flightselectbt">
            <input type="submit" id="sm_fselect" value="Tiếp tục" class="btn-flat-red btn_continue" />
            <span class="noneselect"></span>
        </div>
    </form>

    <script type="text/javascript">
        $( function() {
            $('tr.lineresult-main').click( function() {
                $(this).parents('table').find('tr').each( function( index, element ) {
                    $(element).removeClass('marked');
                } );
                $(this).addClass('marked');
            } );
            /***
             SORT RESULT FLIGHT
             ***/
            $.tablesorter.addParser({
                id: 'thousands',
                is: function(s) {
                    return false;
                },
                format: function(s) {
                    return s.replace(' VND','').replace(/,/g,'');
                },
                type: 'numeric'
            });
            $("table.flightlist").tablesorter({
                headers:{
                    2:{sorter:'thousands'},
                    3:{sorter:false},
                    4:{sorter:false}
                }
            });
            $("table.flightlist").bind("sortStart",function(){
                $(".flight-detail").remove();
                //$(".viewdetail").removeClass('on');
            })


            /***
             CHANGE DEPART DAY
             ***/
            $(".changedepartflight").click(function(){

                var departchange = $(this).attr('rel');
                var todate = '<?php echo  $_SESSION['search']['return'] ?>';

                if(todate == '' || (todate != '' & compareFromDatewithToDate(departchange, todate)) ){

                    var notice = '<div class="waiting_block">' +
                        '<h2>Khởi hành từ <span class="fontplace"><?php echo  $GLOBALS['CODECITY'][$source] ?> ' +
                        '</span> đi <span class="fontplace"><?php echo  $GLOBALS['CODECITY'][$destination] ?></span> ' +
                        '</h2><table><tr><td>Loại vé:</td><td><strong><?php echo  $direction_fulltext ?></strong></td>' +
                        '<td>Số hành khách:</td><td><strong><?php echo  $adults ?> người lớn<?php echo  $qty_children.$qty_infants ?></strong></td></tr> ' +
                        '<tr><td>Ngày khởi hành:</td><td><strong>'+departchange+'</strong></td>';
                    <?php if($way_flight==0): ?>notice+='<td>Ngày về</td><td><strong>'+todate+'</strong></td>'; <?php else: ?> notice+='<td></td><td></td>'; <?php endif; ?>
                    notice+='</tr></table><p class="notice-waiting">Mời bạn vui lòng chờ trong giây lát ...</p></div>';
                    getflight('',notice,departchange,'');

                }else{
                    alert('Ngày khởi hành không được lớn hơn ngày về');
                    return false;
                }

            });


            /***
             CHANGE RETURN DAY
             ***/
            $(".changereturnflight").click(function(){
                var fromdate = '<?php echo  $_SESSION['search']['depart'] ?>';
                var returnchange = $(this).attr('rel');
                if(compareFromDatewithToDate(fromdate,returnchange)){
                    var notice = '<div class="waiting_block"><h2>Khởi hành từ <span class="fontplace"><?php echo  $GLOBALS['CODECITY'][$source] ?></span> đi <span class="fontplace"><?php echo  $GLOBALS['CODECITY'][$destination] ?></span></h2><table><tr><td>Loại vé:</td><td><strong><?php echo  $direction_fulltext ?></strong></td><td>Số hành khách:</td><td><strong><?php echo  $adults ?> người lớn<?php echo  $qty_children.$qty_infants ?></strong></td></tr><tr><td>Ngày khởi hành:</td><td><strong><?php echo  $depart_fulltext; ?></strong></td><td>Ngày về:</td><td><strong>'+returnchange+'</strong></td></tr></table><p class="notice-waiting">Mời bạn vui lòng chờ trong giây lát ...</p></div>';
                    getflight('',notice,'',returnchange);
                }else{
                    alert('Ngày về không được nhỏ hơn ngày khởi hành');
                    return false;
                }
            });
            /***
             CHON CHUYEN BAY
             ***/

            $(".selectflight").click(function(){
                var direction=$(this).closest(".flightlist").attr("id");
                $("#"+direction+" .dep-active").removeClass("dep-active");
                var key = $(this).val();

                if($("#flightdetail"+key).length){

                }else{
                    $("#"+direction+" .flight-detail").remove();
                    $(this).closest("tr").after('<tr class="flight-detail" id="flightdetail'+key+'"></tr>');
                    showdetail(false,key,direction);
                }
            });

            /***
             XEM CHI TIET
             ***/
            $(".viewdetail").click(function(){
                var direction=$(this).closest(".flightlist").attr("id");
                $("#"+direction+" .dep-active").removeClass("dep-active");

                var keyactive = $(this).attr('rel');

                if($(this).hasClass("on")){
                    /*Xoa cai khac di*/
                    $("#"+direction+" .flight-detail").remove();
                    $(this).removeClass("on");
                }else{
                    $("#"+direction+" .flight-detail").remove();
                    $("#"+direction+" .live").removeClass("on");
                    $(this).addClass("on");
                    $(this).closest("tr").after('<tr class="flight-detail" id="flightdetail'+keyactive+'"></tr>');
                    showdetail(false,keyactive,direction);
                }
                return false;
            });

            function showdetail(isselect,flightid,direction){

                $("#flightdetail"+flightid).show();
                $.ajax({
                    url: myvar.flightdetail,
                    cache:false,
                    traditional: true,
                    type: "POST",
                    dataType: "html",
                    data: 'select='+isselect+'&flightid='+flightid+'&direction='+direction,
                    beforeSend: function () {
                        $("#flightdetail"+flightid).html('<td colspan="5" class="flight-detail-content"><div class="waiting2"></div></td>');
                    },
                    success: function(output){
                        //console.log(output);
                        $("#flightdetail"+flightid).html(output);
                    }
                });

            }

            $("#flightsort").show();
            $("#filterflight").show();


            /***
             CHECK SUBMIT
             <div class="noneselect">Bạn chưa chọn chuyến bay lượt đi hoặc lượt về</div>
             ***/
            $("#frmSelectFlight").submit(function(){
                var way_flight = <?php echo  $way_flight ?>;
                if(way_flight == 1){
                    if(!$('input[name="selectflightdep"]:checked').val())
                    {
                        $(".noneselect").text('Bạn chưa chọn chuyến bay');
                        $(".noneselect").show();
                        $(".noneselect").fadeOut(2000);
                        return false;
                    }
                }
                else{
                    if(!$('input[name="selectflightdep"]:checked').val() || !$('input[name="selectflightret"]:checked').val())
                    {
                        $(".noneselect").text('Bạn chưa chọn chuyến bay lượt đi hoặc lượt về');
                        $(".noneselect").show();
                        $(".noneselect").fadeOut(2000);
                        return false;
                    }
                }

            });
        });
    </script>

    <?php

    }/*End check if has flight*/
    else{/*Neu ko co chuyen bay*/
        ?>
    <div class="empty_flight">
        <h3>Chuyến bay bạn yêu cầu hiện tại đã hết !</h3>
        <p><strong>Thông báo:</strong> chuyến bay khởi hành từ <strong><?php echo getCityVn($source)?></strong> đi <strong><?php echo getCityVn($destination)?></strong> trong ngày <strong><?php echo  $depart_fulltext?></strong> của các hãng hàng không trên hệ thông đặt vé online đã hết.</p>
        <p>Bạn có thể thay đổi <strong>ngày đi</strong>, hoặc <strong>ngày về</strong> để tìm chuyến bay khác.</p>
        <p>Nếu bạn muốn <strong>đặt vé máy bay theo yêu cầu</strong> trên, bạn có thể gửi yêu cầu theo <strong>biểu mẫu bên dưới</strong> hoặc gọi tới số điện thoại <strong style="font-size:16px;color:#E00;"><?php echo  get_option('opt_phone'); ?></strong>. Nhân viên của chúng tôi sẽ <strong>tìm vé máy bay theo yêu cầu</strong> của bạn </p>
        <div class="request_block">
            <form method="post" action="" id="frm_requestflight">
                <table>
                    <caption>Đặt vé theo yêu cầu</caption>
                    <tr>
                        <td><label for="fullname">Họ tên:</label></td><td><input type="text" name="fullname" id="fullname" /></td>
                    </tr>
                    <tr>
                        <td><label for="phone">Điện thoại:</label></td><td><input type="text" name="phone" id="phone" /></td>
                    </tr>
                    <tr>
                        <td><label for="content_request">Nội dung:</label></td>
                        <td><textarea name="content_request" id="content_request" style="height:80px;">Tôi muốn tìm vé cho chuyến bay từ <?php echo getCityVn($source)?> đi <?php echo getCityVn($destination)?> vào ngày <?php echo $depart_fulltext?> cho <?php echo $adults?> người lớn<?php if($children>0): ?>, <span><?php echo $children?> Trẻ em</span> <?php endif; ?><?php if($infants>0): ?>, <span><?php echo $infants?> Em bé</span> <?php endif; ?></textarea></td>
                    </tr>
                    <tr>
                        <td></td><td><input type="submit" name="sm_request" id="sm_request" value="Gửi" class="btn_send btn-flat-red"/></td>
                    </tr>
                </table>
            </form><!--End frm_requestflight-->
        </div><!--End request_block-->
    </div><!--End empty_flight-->
    <script type="text/javascript">
        $(function(){
            $('#frm_requestflight').submit(function(){
                $(':submit', this).click(function() {
                    return false;
                });
            });
            $('#sm_request').click(function(){
                if($('#fullname').val() == ''){
                    $('#fullname').focus();
                    return false;
                }else if($('#phone').val() == ''){
                    $('#phone').focus();
                    return false;
                }else{
                    return true;
                }
            });
        });
    </script>
    <?php
    }

endif; /*isset($_SESSION['search']*/
?>