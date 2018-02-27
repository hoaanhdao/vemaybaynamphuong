<?php
get_header();
?>
	<div class="row">
	<div class="block">
	<div class="col-md-8 sidebar-separator">
     <div>
	<div id="cat_description">
    	<?php 
	 	if(is_category()) {
   		 $category = get_category(get_query_var('cat'));
    	 $cat_id = $category->cat_ID;
		 //echo $cat_id;exit;
		}
		?>
		<?php if ($cat_id== '6'):?>
    	 	<h1>Vé máy bay quốc tế khuyến mãi giá rẻ</h1>
       
        <?php else:?>
        	<h1><?php single_cat_title(''); ?></h1>
        <?php endif;?>
        
        <p><?php echo category_description(); ?></p>
    	
    </div>
    
     <?php if ($cat_id== '41'):
        
			query_posts('cat=41&post_status=publish&orderby=date&order=DESC&paged='.$paged.'');
	?>
	 <?php else:?>
    
    <?php 
		$current_cat_id  = get_query_var('cat');
		$args = array (
		   'paged'     => $paged,
		   'cat'=>$current_cat_id,
		   'meta_key' => 'fl_mysortby',
		   'orderby' => 'post_date',
    		'order' => 'DESC',
		   //'orderby' => 'meta_value_num',
		   //'order' => 'ASC',
		);
		query_posts($args);
		?>
        
        <?php endif;?>
        
    <?php if(have_posts()): ?>
    <div id="mainDisplay" class="cat">

        <?php while(have_posts()): the_post(); ?>
        
       
		<div class="row post">
					<div class="col-xs-12 col-sm-6 col-md-4">
						 
						<a  href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="hover-img"> 
						 <img  src="<?php echo (_getHinhDaiDien($post->ID) != '' ? _getHinhDaiDien($post->ID) : v5s_catch_that_image()); ?>" class="img-responsive img-hover"  alt="<?php the_title(); ?>"  />
						 
					</div>
                    <div class="col-xs-12 col-sm-6 col-md-8">
                        <h4>
                             <a class="cate_title" href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                        </h4>
                        
                        </p>
                        <p> <?php new_excerpt_more(the_excerpt()); ?></p>
                        <a class="button pull-right" href="<?php the_permalink() ?>">Xem thêm </a>
                    </div>
        		</div><!--End row-->
				 <hr />
        <?php endwhile; ?>
        <?php emm_paginate(); ?>

        <div class="clearfix"></div>
    </div></div> <!--#mainDisplay-->
    <?php else: ?>
        <div id="nonepost " class="col-md-8><div class="travelo-box">
            Trang bạn đang truy cập hiện không có, vui lòng quay lại dau
        </div></div><!--#nonepost-->
    <?php endif; ?>

</div><!--#mainleft-->	
	<div class="col-md-4"><?php get_sidebar(); ?></div><!--#ctright-->
	 
</div></div> <!--end row wrap col_main+sidebar--> 

<?php
get_footer();
?>