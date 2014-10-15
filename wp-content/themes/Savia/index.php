<?php 
/**
 * The Default template file.
 * 
 * @package WordPress
 */


get_header(); ?>

<div class="content_bgr">

	<?php if(is_archive() || is_search() || is_home()): ?>
		<div class="full_container_page_title">	
			<div class="container startNow animationStart">		
				<div class="row no_bm">
					<div class="sixteen columns">
						<?php boc_breadcrumbs(); ?>
						<div class="page_heading"><h1>
						<?php 	if ( is_home() && is_front_page() ) {
								//	bloginfo('name');
								}else{
									echo (is_archive() ? single_cat_title() : (is_search() ? _e('Search results for:', 'Savia').' '. get_search_query(): (is_home() ? wp_title('') :'') ));
						} ?>
						</h1></div>
					</div>		
				</div>
			</div>
		</div>
	<?php else: ?>
	<div class="h10"></div>
	<?php endif; ?>


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
					
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						
						<!-- Post Loop Begin -->
						<div class="post_item clearfix">
						
							
			<?php 	// IF Post type is Standard (false) 	
					if(function_exists( 'get_post_format' ) && get_post_format( $post->ID ) != 'gallery' && get_post_format( $post->ID ) != 'video' && has_post_thumbnail()) { 
						$thumbbig = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'portfolio-slim' );
						$attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'portfolio-slim');
						
			?>			
						<div class="pic">
							<a href="<?php the_permalink(); ?>" title="<?php echo $post->post_title; ?>">
								<img src="<?php echo $attachment_image[0]; ?>"/><div class="img_overlay_zoom"><span class="icon_plus"></span></div>
							</a>
						</div>
			<?php 	} // IF Post type is Standard :: END ?>
			
			<?php 	// IF Post type is Gallery
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
									$thumb = wp_get_attachment_image_src($image->ID,'portfolio-slim');
									?>
									<li class="pic">
										<a href="<?php the_permalink(); ?>" title="<?php echo $image->post_title; ?>" >
											<img src="<?php echo $thumb[0] ?>"/><div class="img_overlay_zoom"><span class="icon_plus"></span></div>
										</a>
									</li>
							<?php } ?>						
							</ul>  
						</div>
						
					<?php } // If Images :: END ?> 
						
			<?php 	} // IF Post type is Gallery :: END ?>
			
			
			<?php	// IF Post type is Video 
					if (( function_exists( 'get_post_format' ) && get_post_format( $post->ID ) == 'video')  ) {					
						if($video_embed_code = get_post_meta($post->ID, 'video_embed_code', true)) {
							echo "<div class='video_max_scale'>";
							echo $video_embed_code;
							echo "</div>";
						}										
					} // IF Post type is Video :: END 
			?>
			
			
							<div class="post_list_left">
								<div class="day"><?php echo get_the_date('j');?></div>
								<div class="month"><?php echo get_the_date('M');?></div>
							</div>
							
							<div class="post_list_right">
								
								<?php if(get_the_title()!=''){ ?>
								<h3 class="post_title"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'Savia'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h3>
								<?php } ?>
								
								<p class="post_meta">
									<span class="author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID' )); ?>"><?php echo __('By ','Savia');?> <?php the_author_meta('display_name'); ?></a></span>
									<span class="comments <?php if(!get_the_tags()){ echo "no-border-comments";}?>"><?php  comments_popup_link( __('No comments yet','Savia'), __('1 comment','Savia'), __('% comments','Savia'), 'comments-link', __('Comments are Off','Savia'));?></span>
								<?php if(get_the_tags()) { ?>	
									<span class="tags"><?php the_tags('',', '); ?></span> 
								<?php } ?>
								</p>
								
								<div class="post_description clearfix">
								<?php the_content('Read more'); ?>
								</div>
																
								<?php if(get_the_title()==''){ ?>
								<a href="<?php the_permalink(); ?>" class='more-link'><?php _e('Read more','Savia');?></a>
								<?php } ?>
							
							</div>
						</div>
						<!-- Post Loop End -->
						
					<?php endwhile; ?>
					
					<?php boc_pagination($pages = '', $range = 2); ?>
					
					<?php else: ?>
					<p><?php _e('Sorry, no posts matched your criteria.','Savia'); ?></p>
					<?php endif; // Loop End  ?>
		
				</div>
			
			
			<?php // Place sidebar if it's right
				  (!$sidebar_left ? get_sidebar() : '');?>
			
		</div>	
	</div>
</div>
<?php get_footer(); ?>	