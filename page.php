<?php
get_header();
?>
<div class="row">
<div class="block">
 <div class="col-md-8">
    <?php if(have_posts()): ?>
    <div id="mainDisplay" class="single post">
        <h1 class="posttitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            
        <?php while(have_posts()): the_post(); ?>
        
		
        <div class="post-editor">
           <?php the_content(); ?>
           
       
        </div><!--.post-editor-->
            
        <?php endwhile; ?>
		
		
		<div class="post-social">
								<!-- share facebook -->
				<div class="par">
					<a class="facebook"  href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>" target="_blank" rel="external nofollow">
						<i class="fa fa-facebook"></i> Facebook
					</a>
				</div>
								<!-- share twitter -->
				<div class="par">
					<a class="twitter" href="http://twitter.com/share?text=&url='.get_the_permalink() .'"  title="Share on Twitter" target="_blank" rel="external nofollow"> 
						<i class="fa fa-twitter"></i> Twitter
					</a>
				</div>
				

								<!-- share google + -->
				<div class="par">
					<a class="google" href="https://plus.google.com/share?url=<?php the_permalink();?>" target="_blank" rel="external nofollow">
						<i class="fa fa-google-plus"></i> Google
					</a>
				</div>
		</div>    
	 
	 
        
 
		
		<!--Facebook Google+ Comment-->
		<div class="gap gap-mini"></div>	
		<h4>Thảo luận</h4> 
			<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-width="100%" data-numposts="10" data-colorscheme="light"></div>
		<!--End comment box-->    
       
       

    </div> <!--#mainDisplay-->
    <?php else: ?>
        <div id="nonepost">
            Trang bạn đang truy cập hiện không có, vui lòng quay lại sau
        </div><!--#nonepost-->
    <?php endif; ?>

</div><!--#mainDisplay-->
 <div class="col-md-4"> <?php get_sidebar(); ?></div><!--#ctright-->
</div></div> <!--end row wrap col_main+sidebar--> 

<?php
get_footer();
?>