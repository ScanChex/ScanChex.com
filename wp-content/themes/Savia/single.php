<?php get_header(); ?>

<div class="content_bgr">
	

<?php	// Shall we display the page heading
		$hide_heading = get_post_meta($post->ID, 'boc_page_heading_set', true);
		if($hide_heading!=='yes') {
?>
	<?php if((is_page() || is_single() || is_archive() || is_search()) && !is_front_page()): ?>
		<div class="full_container_page_title">	
			<div class="container animationStart startNow">		
				<div class="row no_bm">
					<div class="sixteen columns">
						<?php boc_breadcrumbs(); ?>
						<div class="page_heading"><h1><?php the_title(); ?></h1></div>
					</div>		
				</div>
			</div>
		</div>
	<?php endif; ?>
	
<?php 	} ?>	
	

		
		

	<div class="container animationStart startNow">
	  <div class="row blog_list_page">	

		<?php 
			// Check where sidebar should be
			$sidebar_left = false; 
			if(ot_get_option('sidebar_layout','right-sidebar')=='left-sidebar'){
				$sidebar_left=true;
			}
			// Place sidebar if it's left
			($sidebar_left ? get_sidebar() : '');
		?>

	  
		<div class="twelve columns">
			
			<!-- Post -->
			<?php while (have_posts()) : the_post(); ?>
			<div <?php post_class('post-page'); ?> id="post-<?php the_ID(); ?>" >
				
						<!-- Post Begin -->
						<div class="clearfix">
								
						
			<?php // IF Post type is Standard (false) 	
				if(function_exists( 'get_post_format' ) && get_post_format( $post->ID ) != 'gallery' && get_post_format( $post->ID ) != 'video' && has_post_thumbnail()) { 
						$attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'portfolio-full');
			?>
							<div class="pic">
								<a href="<?php echo $attachment_image[0];?>" rel="prettyPhoto" title="<?php echo $post->post_title; ?>">
									<img src="<?php echo $attachment_image[0]; ?>" alt=" "/><div class="img_overlay_zoom"><span class="icon_zoom"></span></div>
								</a>
							</div>
							
							
							<div class="h20"></div>	
			
			<?php } // IF Post type is Standard :: END ?>
			
			<?php // IF Post type is Gallery
			if (( function_exists( 'get_post_format' ) && get_post_format( $post->ID ) == 'gallery' )) {
						$args = array(
								'numberposts' => -1, // Using -1 loads all posts
								'orderby' => 'menu_order', // This ensures images are in the order set in the page media manager
								'order'=> 'ASC',
								'post_mime_type' => 'image', // Make sure it doesn't pull other resources, like videos
								'post_parent' => $post->ID, // Important part - ensures the associated images are loaded
								'post_status' => null,
								'post_type' => 'attachment'
								);

						$images = get_children( $args );
						
						if($images){ ?>
						
						<div class="flexslider">
							<ul class="slides">
							<?php foreach($images as $image){ 
									$attachment = wp_get_attachment_image_src($image->ID, 'portfolio-full');
									$thumb = wp_get_attachment_image_src($image->ID, 'portfolio-full'); 
							?>
									<li class="pic">
										<a href="<?php echo $attachment[0] ?>" rel="prettyPhoto[gallery]" title="<?php echo $image->post_title; ?>" >
											<img src="<?php echo $thumb[0] ?>" alt=" "/><div class="img_overlay_zoom"></div>
										</a>
									</li>
							<?php } ?>						
							</ul>  
						</div>
						<div class="h20"></div>
						
						<?php } // If Images :: END ?> 
							
					
			<?php } // IF Post type is Gallery :: END ?>
			
			<?php	// IF Post type is Video 
					if (( function_exists( 'get_post_format' ) && get_post_format( $post->ID ) == 'video')  ) {					
						if($video_embed_code = get_post_meta($post->ID, 'video_embed_code', true)) {
							echo "<div class='video_max_scale'>";
							echo $video_embed_code;
							echo "</div>";
							echo '<div class="h20"></div>';
						}										
					} // IF Post type is Video :: END 
			?>
		
			
							<p class="post_meta">
								<span class="calendar_date"><?php printf('%1$s', get_the_date()); ?></span>
								<span class="author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID' )); ?>"><?php echo __('By ','Savia');?> <?php the_author_meta('display_name'); ?></a></span>
								<span class="comments <?php if(!get_the_tags()){ echo "no-border-comments";}?>"><?php  comments_popup_link( __('No comments yet','Savia'), __('1 comment','Savia'), __('% comments','Savia'), 'comments-link', __('Comments are Off','Savia'));?></span>
						<?php if(get_the_tags()) { ?>								
								<span class="tags"><?php the_tags('',', '); ?></span>
						<?php } ?>
							</p>
						
							<div class="post_description clearfix">
							<?php the_content(); ?>
							</div>
						</div>
						<!-- Post End -->

			
			</div>
								
			<?php wp_link_pages(); ?>

			<?php endwhile; // Loop End  ?>
			<!-- Post :: End -->
			
			<?php comments_template('', true); ?>
			
		</div>	
			
			
		<?php // Place sidebar if it's right
				  (!$sidebar_left ? get_sidebar() : '');?>
			

	  </div>	
	</div>

</div>	
<?php get_footer(); ?>	