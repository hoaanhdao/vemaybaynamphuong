<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <title><?php wp_title(''); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="SHORTCUT ICON" href="<?php echo get_bloginfo("template_directory")?>/favicon.ico" />
    <meta name="robots" content="index, follow" />
    <meta name="revisit-after" content="1 day" />
	<meta name= "viewport" content="width=device-width, initial-scale=1">
	<meta property="fb:admins" content="1450452807"/>
	<meta property="fb:app_id" content="758489034174096">
    <link href="https://plus.google.com/102877280748909261057" rel="publisher" />
	<script type="text/javascript">
	function OpenPopup(Url,WindowName,width,height,extras,scrollbars) {
	var wide = width;
	var high = height;
	var additional= extras;
	var top = (screen.height-high)/2;
	var leftside = (screen.width-wide)/2; newWindow=window.open(''+ Url + 
	'',''+ WindowName + '','width=' + wide + ',height=' + high + ',top=' + 
	top + ',left=' + leftside + ',features=' + additional + '' + 
	',scrollbars=1');
	newWindow.focus();
	}
	</script>
   
   <script type="text/javascript">siteurl="<?php bloginfo("url"); ?>";</script>
    <?php wp_head(); ?>
</head>


<body>
<!--Start Popup-->
	<div class="row visible-xs" id="boxes">
		<div style="top: 0; left: 0; display: none;" id="popup" class="window">
		<p id="icon-app"><img src="<?php bloginfo('template_directory')?>/images/icon-app.png"></p>
		<div id="lorem"> <p><a href="https://play.google.com/store/apps/details?id=com.namphuong.vemaybay">App vé máy bay. Cần là có - Tìm là thấy. Công cụ săn vé khuyến mãi giá rẻ !!</a></p> </div>
		<a id="noShow" href="javascript:dontShow()" class="close-popup"><img src="<?php bloginfo('template_directory')?>/images/close.png"></a> 
		
		</div>
	</div>
<!--End Popup-->
  

 <!--START CONTAINER WRAP-->
<div id="wrap">
 
<!--HEADER-->
<header class="container">
	<div class="row">
		<div class="brand col-md-9">
			<a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>" ><img src="<?php bloginfo('template_directory')?>/images/logo.png" alt="logo" class="logo">
			</a>
			<div id="double" class="hidden-xs">
			<ul class="top_nav_center">
				<?php
					$args=array(
						'posts_per_page'   => 6,
						'cat'=>1
					);
				$myposts = get_posts( $args );
				foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
					<li class="quotes">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php  echo wp_trim_words(get_the_title(),9); ?></a>
					</li>
					<?php endforeach;
				wp_reset_postdata();?>
			</ul>
            </div>
		</div>
		<div class="brand col-md-3">
			<p class="pull-right">Mở cửa từ 7h30 - 23h<br>
            <div  id="hotline"  class="xbig red-color"><img src="<?bloginfo('template_directory')?>/images/call_blue.png"> 
                <ul>
                     <li><?php echo get_option('opt_hotline'); ?></li>
                    <li><?php echo get_option('opt_hotline2'); ?></li>
                    <li><?php echo get_option('opt_hotline3'); ?></li>
                </ul>
           </div></p>
		</div>
	</div>
</header>
     
<nav class="navbar navbar-inverse" role="banner">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <!-- navbar-brand is hidden on larger screens, but visible when the menu is collapsed --> 
			<a class="navbar-brand"  href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>">
				<img src="<?php bloginfo('template_directory')?>/images/logo.png" alt="logo">
			</a> 
			<div class="xbig white-color pt20"><i class="icons-sprite icons-phone rtl-horizontal-flip"></i>
            <?php echo get_option('opt_phone'); ?></div>
		</div>
      <div class="collapse navbar-collapse">
	  <?php wp_nav_menu(array("menu"=>"Main Menu","container"=>"","menu_class"=>"nav navbar-nav" )) ?>
        
       
	  </div>
	  
    </div>
    <!--/.container--> 
  </nav>
  <!--/nav--> 
 

<!--END HEADER--> 

<!--START MAIN CONTAINER-->
<?php if(! is_home()){?>
<div class=""> 
<div class="page-title-container hidden-xs">
	<div class="container">
		<div class="page-title pull-left">
		 <?php	yoast_breadcrumb('<div id="breadcrumbs"><div class="breadcrumbs">','</div></div>'); ?>
		</div>
	</div>
</div>
 <div id="content" class="container">
<?php }?>
 