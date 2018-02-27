<?php get_header();?>
<?php
/*
Template Name: tim-khach-san
*/ 
if (isset ($_GET["tim"])) { 
    $url = 'http://api.vemaybay.website/index.php/hotels/Api_v1/searchs';
    $post_data = array();
    $post_data['str'] = $_GET["tim"];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'NP-API-KEY: a81c7ff7e800a451a646136146c29a36557f0061'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $search_data = curl_exec($ch);
    $result = json_decode($search_data,true);
?>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
						<?php get_sidebar(); ?>
			    </div>
                <div class="col-md-8">
                    <h6></h6>
                    <h4 class="mb20">Ý của bạn là:</h4>
                    <div class="row row-wrap">
                        <div class="col-md-12">
                            <ul>
                            <?php if ($result != NULL){ ?>
                            <?php foreach ($result['locals'] as $local){ ?>
                                <li>
                                 <i class="fa fa-university"></i><a href="/danh-sach-khach-san/?id=<?= $local['id'] ?>&t=local&offset=1"> <?= $local['name'] ?></a>
                                </li>
                            <?php } ?>
                            <?php foreach ($result['areas'] as $area){ ?>
                                 <li>    
                                 <i class="fa fa-university"></i><a href="/danh-sach-khach-san/?id=<?= $area['id'] ?>&t=area&offset=1"> Khu vực <?= $area['name'] ?>, <?= $area['local'] ?></a>
                                 </li>
                            <?php }?>
                            <?php foreach ($result['hotels'] as $hotel){ ?>
                                 <li>
                                 <i class="fa fa-building-o"></i><a href="/khach-san/?id=<?= $hotel['id'] ?>"> Khách sạn <?= $hotel['name'] ?>, <?= $hotel['local'] ?></a>
                                 </li>
                            </ul>
                             <?php } ?>
                             <?php } ?>
                        </div>
                    </div>
                    <div class="gap gap-small"></div>
                </div>
            </div>
        </div>
        <?php } ?>
<?php get_footer();?>