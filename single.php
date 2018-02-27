<?php
get_header();
?>
<div class="row">
<div class="block">
 <div class="col-md-8 sidebar-separator">
     <div>
    <?php if(have_posts()): ?>
    <div id="mainDisplay" class="single post">
        <h1 class="posttitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            
        <?php while(have_posts()): the_post(); ?>
         <ul class="post-meta hidden-xs">
			<li><i class="fa fa-calendar"></i><a href="#"><?php the_date("d/m/Y h:m") ?></a></li>
			<li><i class="fa fa-folder-open"></i><a href="#"><?php the_category(', ') ?></a></li>
			<?php if (get_the_tags()) : ?>
			<li><i class="fa fa-tags"></i>
				 <?php the_tags('', ', ', ''); ?>
			</li>
			<?php endif; ?>		
		</ul>
		
        <div class="post-editor">
           <?php the_content(); ?>
           
       
        </div><!--.post-editor-->
            
        <?php endwhile; ?>
		
		
		<div class="post-social">
								<!-- share facebook -->
				<div class="par hidden-xs">
					<a class="facebook"  href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>" target="_blank" rel="external nofollow">
						<i class="fa fa-facebook"></i> Facebook
					</a>
				</div>
								<!-- share twitter -->
				<div class="par hidden-xs">
					<a class="twitter" href="http://twitter.com/share?text=&url='.get_the_permalink() .'"  title="Share on Twitter" target="_blank" rel="external nofollow"> 
						<i class="fa fa-twitter"></i> Twitter
					</a>
				</div>
				

								<!-- share google + -->
				<div class="par hidden-xs">
					<a class="google" href="https://plus.google.com/share?url=<?php the_permalink();?>" target="_blank" rel="external nofollow">
						<i class="fa fa-google-plus"></i> Google
					</a>
				</div>
				
		<!-- Place this tag where you want the +1 button to render. -->
		</div>    
		   
		<div class="row hidden-xs" id="cb-previous-next-links">
			<div class="col-md-6" id="cb-previous-link">
			<?php $prevPost = get_previous_post(true);
			if($prevPost) {?>
				<a  href="<?php the_permalink() ?>" title="<?php the_title(); ?>"  > 
					<h3><i class="fa fa-long-arrow-left"></i></h3>
					<?php previous_post_link('%link',"$prevthumbnail  <p>%title</p>", TRUE); ?>
				</a>
			<?php } ?> 
			
			</div>

			<div class="col-md-6" id="cb-next-link">
			<?php $nextPost = get_next_post(true);
			if($nextPost) { ?>
				<a  href="<?php the_permalink() ?>" title="<?php the_title(); ?>"  > 
					<h3><i class="fa fa-long-arrow-right"></i></h3>
					<?php next_post_link('%link',"$nextthumbnail  <p>%title</p>", TRUE); ?>
				</i>
				</a>
			<?php } ?>
			</div>
		</div>
		 
		 <!---->
	 
		<div class="gap gap-mini"></div>		
		<div class="relate_news">
             <div class="row">
			<h4 class="pull-left">Bài viết liên quan</h4>
            <div class="pull-right hidden-xs">
				<a  class="prev">
					<i class="fa fa-angle-left"></i>
				</a>
				<a  class="next">
					<i class="fa fa-angle-right"></i>
				</a>
			</div>
			</div class="row">	
			
			     <?php
                $cat=wp_get_post_categories($post->ID);
                $args=array(
                    "post_type"=>"post",
                    "post_status"=>"publish",
                    "category__in"=>$cat,
                    "posts_per_page"=>9,
                    "caller_get_posts"=>1
                );
                $incat_post=new WP_Query($args);
                $temp=1;
                ?>

                <div class="row">
					<div class="col-md-12 owl-carousel owl-slider owl-carousel-area owl-theme" id="owl-relate" >
                    <?php while($incat_post->have_posts()): $incat_post->the_post();  ?>
						
						<div class="item ">
						
							<a  href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="hover-img"> 
								<img  src="<?php echo (_getHinhDaiDien() != '' ? _getHinhDaiDien() : v5s_catch_that_image()); ?>" class="img-responsive img-hover"  alt="<?php the_title(); ?>"    />
							</a>
							<a  href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
								<h6 class="hover-title hover-hold"> <?php echo  wp_trim_words(get_the_title(),6); ?></h6>
							</a>
						</div>
                        
						<?php $temp++; endwhile; wp_reset_query(); ?>
					</div>
					
					
					
                </div>

            
        </div>
        
 
		
		<!--Facebook Google+ Comment-->
		<div class="gap gap-mini"></div>	
		<h4>Thảo luận</h4> 
			<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-width="100%" data-numposts="10" data-colorscheme="light"></div>
		<!--End comment box-->    
       
       

    </div></div> <!--#mainDisplay-->
    <?php else: ?>
        <div id="nonepost">
            Trang bạn đang truy cập hiện không có, vui lòng quay lại sau
        </div><!--#nonepost-->
    <?php endif; ?>

</div><!--#mainDisplay-->
 <div class="col-md-4"><?php get_sidebar(); ?></div><!--#ctright-->
</div>
</div> <!--end row wrap col_main+sidebar--> 

<?php
get_footer();
?>