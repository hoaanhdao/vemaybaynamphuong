<?php
// list all places
$arrplace=array(
    "HAN"=>"Hà Nội",
    "HPH"=>"Hải Phòng",
    "DIN"=>"Điện Biên",
    "THD"=>"Thanh Hóa",
    "VII"=>"Vinh",
    "HUI"=>"Huế",
    "VDH"=>"Đồng Hới",
    "DAD"=>"Đà Nẵng",
    "PXU"=>"Pleiku",
    "TBB"=>"Tuy Hòa",
    "SGN"=>"Hồ Chí Minh",
    "NHA"=>"Nha Trang",
    "DLI"=>"Đà Lạt",
    "PQC"=>"Phú Quốc",
    "VCL"=>"Tam Kỳ",
    "UIH"=>"Quy Nhơn",
    "VCA"=>"Cần Thơ",
    "VCS"=>"Côn Đảo",
    "BMV"=>"Ban Mê Thuột",
    "VKG"=>"Rạch Giá",
    "CAH"=>"Cà Mau",
);


add_action('init', 'myStartSession', 1);
function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

define("tpldir", get_bloginfo("template_directory"),true);
define("imgdir", get_bloginfo("template_directory")."/images",true);
define("interimgdir", imgdir."/airline-icons/inter-icon-66x42",true);
define("styledir", get_bloginfo("template_directory")."/styles",true);
add_theme_support( 'post-thumbnails' );

/*===== Clean up the WordPress head =====*/
// remove header links
add_action('init', 'cap_head_cleanup');
function cap_head_cleanup() {
    remove_action( 'wp_head', 'feed_links_extra', 3 );                      // Category Feeds
    remove_action( 'wp_head', 'feed_links', 2 );                            // Post and Comment Feeds
    remove_action( 'wp_head', 'rsd_link' );                                 // EditURI link
    remove_action( 'wp_head', 'wlwmanifest_link' );                         // Windows Live Writer
    remove_action( 'wp_head', 'index_rel_link' );                           // index link
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );              // previous link
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );               // start link
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );   // Links for Adjacent Posts
    remove_action( 'wp_head', 'wp_generator' );                             // WP version
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0);
    if (!is_admin()) {
        wp_deregister_script('jquery'); // De-Register jQuery
        wp_register_script('jquery', '', '', '', true); // Register as 'empty', because we manually insert our script in header.php
    }
    // Remove Emoji from WordPress
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
}

// remove WP version from RSS
add_filter('the_generator', 'cap_rss_version');
function cap_rss_version() { return ''; }
/*===== End clean up the WordPress head =====*/

######FOR COPYRIGHT AND NOTICE################################


    /* REMOVE NOTICE UPDATE WP */
    add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
    add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );
    add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );

    # REMOVE UPDATE PLUGIN
    remove_action( 'load-update-core.php', 'wp_update_plugins' );
    add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );

    # REMOVE Login url and logo
    function jsviet_login_logo_url() {
        return get_bloginfo( 'url' );
    }
    add_filter( 'login_headerurl', 'jsviet_login_logo_url' );

    function jsviet_login_url_title() {
        return get_bloginfo("name");
    }
    add_filter( 'login_headertitle', 'jsviet_login_url_title' );

    function jsviet_login_logo() {
        echo '';
    }
    add_action('login_head', 'jsviet_login_logo');

    function remove_lostpassword_text ( $text ) {
        $wpv='You are using <span class="b">WordPress %s</span>.';
        if ($text == 'Lost your password?' || $text==$wpv){$text = '';}
        return $text;
    }
    add_filter( 'gettext', 'remove_lostpassword_text' );

    function wpse_edit_footer(){
        add_filter( 'admin_footer_text', 'wpse_edit_text', 11 );
    }
    function wpse_edit_text($content) {
        return get_bloginfo("name");
    }
    add_action( 'admin_init', 'wpse_edit_footer' );

    function annointed_admin_bar_remove() {
        global $wp_admin_bar;
        /* Remove their stuff */
        $wp_admin_bar->remove_menu('wp-logo');
    }
    add_action('wp_before_admin_bar_render', 'annointed_admin_bar_remove', 0);

    add_action( 'admin_menu', 'my_remove_menu_pages' );
    function my_remove_menu_pages() {
        remove_submenu_page( 'index.php', 'update-core.php' );
         remove_menu_page('tools.php');
//        remove_menu_page('edit-comments.php');
    }

    //remove_action ('wp_head', 'rsd_link');
    //remove_action( 'wp_head', 'wlwmanifest_link');
    //remove_action( 'wp_head', 'wp_shortlink_wp_head');
    //remove_action('wp_head', 'wp_generator');

    ######END FOR COPYRIGHT AND NOTICE################################

#FOR EXERPT
function new_excerpt_more( $more ) {
    return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');
function custom_excerpt_length( $length ) {
	$newlength = 35;
    return $newlength;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


/* BREADCRUMB - custom by JKey*/
function the_breadcrumb() {

    $name = get_bloginfo('name'); //text for the 'Home' link
    $currentBefore = '<li>';
    $currentAfter = '</li>';
    $delimiter="";

    if ( !is_home() && !is_front_page() || is_paged() ) {
        echo '<div id="breadcrumbs">';
        echo '<div class="wrapper">';
        echo '<ul>';
        global $post;
        $home = get_bloginfo('url');
        echo '<li><a href="'.$home.'" title="'.$name.'" class="home">'.get_bloginfo('name').'</a></li>';

        if ( is_category() ) {
            global $wp_query;
            $cat_obj = $wp_query->get_queried_object();
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
            echo $currentBefore;
            single_cat_title();
            echo $currentAfter;

        } elseif ( is_day() ) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $currentBefore . get_the_time('d') . $currentAfter;

        } elseif ( is_month() ) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $currentBefore . get_the_time('F') . $currentAfter;

        } elseif ( is_year() ) {
            echo $currentBefore . get_the_time('Y') . $currentAfter;

        } elseif ( is_single()) {
            $cat = get_the_category(); $cat = $cat[0];
            echo '<li>';
            echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
            echo '</li>';
            echo $currentBefore;
            the_title();
            echo $currentAfter;

        } elseif ( is_page() && !$post->post_parent ) {
            echo $currentBefore;
            the_title();
            echo $currentAfter;


        } elseif ( is_page() && $post->post_parent ) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
            echo $currentBefore;
            the_title();
            echo $currentAfter;


        } elseif ( is_search() ) {
            echo $currentBefore . 'K?t qu? tìm ki?m t? khóa "<strong>' . get_search_query() . '</strong>"' . $currentAfter;

        } elseif ( is_tag() ) {
            echo $currentBefore . 'tagged &#39;';
            single_tag_title();
            echo '&#39;' . $currentAfter;

        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            echo $currentBefore . 'Articles posted by ' . $userdata->display_name . $currentAfter;

        } elseif ( is_404() ) {
            echo $currentBefore . 'Error 404' . $currentAfter;

        }
        echo '</ul>';
        echo '</div>';
        echo '</div>';

    }
}
/* END BREADCRUMB */


#LIMIT POST PER PAGE
function limit_posts_per_page($posts_per_page) {
    return $posts_per_page;
}
add_filter('pre_option_posts_per_page', 'limit_posts_per_page');


#ADD MENU SUPPORT
add_theme_support('nav-menus');
function register_my_menus() {
    register_nav_menus(
        array(
            'main-menu' => __( 'Menu chính' ),
            'top-menu' => __( 'Top Menu' ),
            'foot-menu' => __( 'Footer Menu' )
        )
    );
}
add_action( 'init', 'register_my_menus' );

/* Highlight current menu item*/
function wpse19375_nav_class( $classes, $item ){
    if( !in_array( 'current-menu-item', $classes ))
        return $classes;
    return $classes;
}
add_filter( 'nav_menu_css_class', 'wpse19375_nav_class', 10, 2 );
#ADD STYLE 
 function enqueue_styles() {
	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/css/bootstrap.css', array() );
	wp_enqueue_style( 'stylesheet', get_template_directory_uri() . '/css/font-awesome.min.css', array() );
	wp_enqueue_style( 'icomoon', get_template_directory_uri() . '/css/icomoon.css', array());
	wp_enqueue_style( 'jquery-ui', get_template_directory_uri() . '/css/jquery-ui-1.9.2.custom.min.css', array() );
	//wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css', array() );
	wp_enqueue_style( 'sprites', get_template_directory_uri() . '/css/sprites.css', array() );
	wp_enqueue_style( 'datepicker', get_template_directory_uri() . '/css/datepicker.css', array() );
	wp_enqueue_style( 'mystyle', get_template_directory_uri() . '/style.css');
 }
add_action( 'wp_enqueue_scripts', 'enqueue_styles' );

 
  
#ADD SRCIRPT JS TO FRONT END

add_action('wp_enqueue_scripts', 'init_js_scripts', 0);
function init_js_scripts(){
		if(is_home()){
			wp_enqueue_script("local_jquery",get_template_directory_uri()."/js/jquery-1.11.1.min.js",false,null,true);
			wp_enqueue_script("jquery-ui",get_template_directory_uri()."/js/jquery-ui.1.10.4.min.js",false,null,true);
		}else{
			wp_enqueue_script("local_jquery",get_template_directory_uri()."/js/jquery-1.11.1.min.js",false,null,false);
			wp_enqueue_script("jquery-ui",get_template_directory_uri()."/js/jquery-ui.1.10.4.min.js",false,null,false);
			wp_enqueue_script("nprogress",get_template_directory_uri()."/js/hotel/nprogress.js",false,null,false);
	
		}
		if(is_page('san-ve-gia-re')){
			wp_enqueue_script("JQuery_seekticket",get_template_directory_uri()."/js/JQuery.seekticket.js",false,null,true);
		}
		if(is_page('ve-re-trong-thang')){
			wp_enqueue_style( 'fullcalendar_css', get_template_directory_uri() . '/css/fullcalendar.min.css', array() );
			wp_enqueue_script("moment_min",get_template_directory_uri()."/js/libs/moment.min.js",false,null,true);		
			wp_enqueue_script("fullcalendar_min",get_template_directory_uri()."/js/libs/fullcalendar.min.js",false,null,true);	
			wp_enqueue_script("fullcalendar-vi",get_template_directory_uri()."/js/libs/fullcalendar-vi.js",false,null,true);	
			//wp_enqueue_script("magnific",get_template_directory_uri()."/js/magnific.js",false,null,true);
		}		
		wp_enqueue_script("jquery-migrate",get_template_directory_uri()."/js/libs/jquery-migrate-1.2.1.min.js",false,null,true);
		wp_enqueue_script("ui-datepicker-lunar-min",get_template_directory_uri()."/js/libs/ui.datepicker.lunar.min.js",false,null,true);
		wp_enqueue_script("date_lunar",get_template_directory_uri()."/js/libs/date_lunar.js",false,null,true);
	
		wp_enqueue_script("bootstrap",get_template_directory_uri()."/js/bootstrap.min.js",false,null,true);
		wp_enqueue_script("owl-carousel_script",get_template_directory_uri()."/js/owl-carousel/owl.carousel.min.js",false,null,true);
		wp_enqueue_script("carouFredSel",get_template_directory_uri()."/js/libs/JQuery.carouFred/jquery.carouFredSel.min.js",false,null,true);
		wp_enqueue_script("jquery-notie",get_template_directory_uri()."/js/libs/notie.js",false,null,true);		
		/*hotel*/
		//wp_enqueue_script("moment_min",get_template_directory_uri()."/js/hotel/moment.min.js",false,null,false);
		//wp_enqueue_script("nprogress",get_template_directory_uri()."/js/hotel/nprogress.js",false,null,false);
		//wp_enqueue_script("bootstrap_notify_min",get_template_directory_uri()."/js/hotel/bootstrap-notify.min.js",false,null,false);
		wp_enqueue_script("fotorama",get_template_directory_uri()."/js/hotel/fotorama.js",false,null,true);

		
		wp_enqueue_script("formValidation_min",get_template_directory_uri()."/js/hotel/formValidation.min.js",false,null,true);
		wp_enqueue_script("bootstrap_min",get_template_directory_uri()."/js/hotel/bootstrap.min.js",false,null,true);
		wp_enqueue_script("dropit",get_template_directory_uri()."/js/hotel/dropit.js",false,null,true);
		
		wp_enqueue_script("typeahead_bundle_min",get_template_directory_uri()."/js/hotel/typeahead.bundle.min.js",false,null,true);
		wp_enqueue_script("icheck",get_template_directory_uri()."/js/hotel/icheck.js",false,null,true);
		wp_enqueue_script("ionrangeslider",get_template_directory_uri()."/js/hotel/ionrangeslider.js",false,null,true);

		
			
		wp_enqueue_script("custom",get_template_directory_uri()."/js/hotel/custom.js",false,null,true);

		
    wp_enqueue_script("my_main_script",get_template_directory_uri()."/js/JQuery.main.js",false,null,true);
    $my_var=array(
        "siteurl"=>get_bloginfo("url"),
        "tempurl"=>get_bloginfo("template_directory"),
        "flightinfo"=>_page("flightinfo"),
        "flightdetail"=>_page("flightdetail"),
        "vna"=>_page("vnalink"),
        "js"=>_page("jslink"),
        "vj"=>_page("vjlink"),
    );
    wp_localize_script( 'my_main_script', 'myvar', $my_var );

    if(is_home() || is_front_page() || is_page_template("tpl-vemaybay.php")){
		wp_enqueue_script("wow",get_template_directory_uri()."/js/wow.min.js",false,null,true);
		wp_enqueue_script("my_home_script",get_template_directory_uri()."/js/JQuery.home.js",false,'1.1',true);
		wp_enqueue_script("jquery_cookie",get_template_directory_uri()."/js/libs/popup/jquery.cookie.js",false,null,true);
		//wp_enqueue_script("tuyetroi_js",get_template_directory_uri()."/js/libs/tuyetroi.js",false,'1.0',true);
		wp_enqueue_script("popup_js",get_template_directory_uri()."/js/libs/popup/popup.js",false,'1.0',true);
		wp_enqueue_style( 'popup_css', get_template_directory_uri() . '/js/libs/popup/popup.css', '', '1.0');

    
    }else{
        wp_enqueue_script("my_sidebar_script",get_template_directory_uri()."/js/JQuery.sidebar.js",false,'1.1',true);
    }

    if(is_singular()){
        wp_enqueue_style("post_css",get_template_directory_uri()."/css/post-editor.css");
    }
	if(is_page('lien-he')  || is_page('khach-san')){ 
		wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false',false,null,true);
	}
    
	if(is_page('lien-he')){
		wp_enqueue_style("post_contact",get_template_directory_uri()."/css/tpl-contact.css");
		wp_enqueue_script("validate_script",get_template_directory_uri()."/js/validation.js",false,null,true);
		wp_enqueue_script("contact_script",get_template_directory_uri()."/js/contact-form.js",false,'1.1',true);
		wp_enqueue_script("magnific",get_template_directory_uri()."/js/magnific.js",false,null,true);
    }
	
    if(is_page('tim-chuyen-bay')){
		wp_enqueue_style("style_flresult",get_template_directory_uri()."/css/flight_result.css");
		//wp_enqueue_script("result_flight_meta",get_template_directory_uri()."/js/libs/jquery.metadata.js",false,null,false);
		wp_enqueue_script("result_flight_sorter",get_template_directory_uri()."/js/libs/jquery.tablesorter.js",false,null,false);
		wp_enqueue_script("tablesorter_pager",get_template_directory_uri()."/js/libs/jquery.tablesorter.pager.js",false,null,false);
		wp_enqueue_script("tablesorter_control",get_template_directory_uri()."/js/libs/pager-custom-controls.js",false,null,false);
		
		wp_enqueue_script("result_flight",get_template_directory_uri()."/js/Flight_result.js",false,'1.0.2',false);
    }

    if(is_page('thong-tin-hanh-khach')){
        wp_enqueue_style("style_paxinfo",get_template_directory_uri()."/css/flight_paxinfo.css");
		wp_enqueue_script("result_paxinfo_currency",get_template_directory_uri()."/js/libs/formatNumber.js",false,null,true);
        wp_enqueue_script("result_paxinfo",get_template_directory_uri()."/js/Flight_paxinfo.js",false,'1.0',true);
    }

    if(is_page('thong-tin-thanh-toan')){
        wp_enqueue_style("style_payment",get_template_directory_uri()."/css/flight_payment.css");
        wp_enqueue_script("result_payment",get_template_directory_uri()."/js/Flight_payment.js",false,null,true);
    }

    if(is_page('hoan-tat-don-hang')){
        wp_enqueue_style("style_confirm",get_template_directory_uri()."/css/flight_confirm.css");
		wp_enqueue_script("order_complete",get_template_directory_uri()."/js/Flight_complete.js",false,null,true);
    }
	
	if(is_page('kiem-tra-tinh-trang-dat-ve')){
        wp_enqueue_script("check-order",get_template_directory_uri()."/js/JQuery.checkbooking.js",false,null,true);
    }

}

#ADD JS SCRIPT TO BACKEND
add_action('admin_enqueue_scripts', 'admin_load_scripts');
function admin_load_scripts() {
    wp_enqueue_script('JQuery_cus', get_bloginfo("template_directory")."/js/admin/JQuery.admin-function.js");

    $my_var=array(
        "siteurl"=>get_bloginfo("url"),
        "tempurl"=>get_bloginfo("template_directory")
    );
    wp_localize_script( 'JQuery_cus', 'myvar', $my_var );

    wp_enqueue_script('jquery-ui-autocomplete');
    $current_admin_page=$_REQUEST['page'];

    if($current_admin_page=='lak-admin-user-config-slider'){
        wp_enqueue_script('slider-js-script',get_bloginfo("template_directory")."/js/admin/JQuery.admin-slider.js");
    }
}

// Get The First Image From a Post
function v5s_catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches[1][0];

  if(empty($first_img)) {
    $first_img = imgdir."/no_image.png";
  }
  return $first_img;
}

function getBookingStatus($str){
	$status = '';
	switch(strip_tags($str)){
		case 'Called' : $status = 'Đã gọi'; 
			break;
		case 'Confirmed' : $status = 'Đã xác nhận'; 
			break;
		case 'Payment Pending' : $status = 'Đang chờ thanh toán'; 
			break;
		case 'Ticket Exported' : $status = 'Đã xuất vé'; 
			break;
		case 'Cancelled' : $status = 'Đã Hủy'; 
			break;
		case 'Completed' : $status = 'Hoàn tất'; 
			break;					
		default : $status = 'Chưa xác nhận'; 
	}
	echo $status;
}

function getImageThumb($postid=""){
    if(is_home() && $postid==""){
        return get_bloginfo("template_directory")."/images/logo.png";
    }else{
        if($postid=="")
            $postid=get_queried_object_id();
        $img=wp_get_attachment_image_src(get_post_thumbnail_id($postid),'thumbnail');
        return $img[0];
    }

}

$GLOBALS['payment_type'] = array('1' => 'Tại nhà',
								 '2' => 'Tại văn phòng',
								 '3' => 'Chuyển khoản',
								 '4' => 'Qua ngân lượng');

// customize breadcrumb output
/*
Optimal Image 
*/
add_filter( 'jpeg_quality', create_function( '', 'return 80;' ) );

// prevent pingback DDOS
add_filter( 'xmlrpc_methods', 'remove_xmlrpc_pingback_ping' );
function remove_xmlrpc_pingback_ping( $methods ) {
   unset( $methods['pingback.ping'] );
   return $methods;
};

/*=== Create HOTLINE Shortcode ===*/
function create_hotline_shortcode() { 
	$str = stripcslashes(get_option('opt_phone'));
	return $str;
} 
add_shortcode('HOTLINE', 'create_hotline_shortcode');

/*********************************
POST SIGNAL
*********************************/
function post_sign_function() { 
$i = '<div class="signal alignright">'.stripcslashes(get_option('opt_post_signal')).'</div>';
return $i;
} 
add_shortcode('NPSIGNAL', 'post_sign_function');

/*********************************
ADDRESS SHORTCODE
*********************************/
function address_shortcode_function() { 
$i = '<div class="address_shortcode">'.get_option('opt_address_shortcode').'</div>';
return $i;
} 
add_shortcode('NPADDRESS', 'address_shortcode_function');


/*Hotel*/
define( 'THEME_URL', get_stylesheet_directory() );

define( 'CORE', THEME_URL . '/core' );
add_action( 'init', 'hotel_cart_cookie', 1 );
function hotel_cart_cookie($cookie_value) { 
  setcookie("hotel_cart_cookie", $cookie_value, time() + 86400, "/" );
}

// Stop browser prefetching home page
if(!function_exists('wpseo_disable_rel_next_home')){
    function wpseo_disable_rel_next_home( $link ) {
        if (is_home() || is_front_page()) {
            return false;
        }
    }
    add_filter( 'wpseo_next_rel_link', 'wpseo_disable_rel_next_home' );
}

require(TEMPLATEPATH . '/core/hotel-functions.php');
require(TEMPLATEPATH . '/inc/custom_paginate.php');
require(TEMPLATEPATH . '/inc/meta_box.php');
require(TEMPLATEPATH . '/inc/cusTax.php');
require(TEMPLATEPATH . '/inc/cusFields.php');
require(TEMPLATEPATH . '/inc/cusAdminOption.php');
require(TEMPLATEPATH . '/inc/cusAdminUserOption.php');
require(TEMPLATEPATH . '/inc/cusAdminSlider.php');
require(TEMPLATEPATH . '/flight_config/fl_function.php');
require(TEMPLATEPATH . '/inc/cusFunction.php');
//require(TEMPLATEPATH . '/inc/custom_single.php');
require(TEMPLATEPATH . '/inc/php-captcha/simple-php-captcha.php');
require(TEMPLATEPATH . '/core/flights-functions.php');
?>