<?php 
/**
 * Template Name: Template - Page + SideBar
 *
 * A Full Width custom page template without sidebar.
 * @package WordPress
 */


get_header(); ?>

<div class="content_bgr">

<?php	// Shall we display the page heading
		$hide_heading = get_post_meta($post->ID, 'boc_page_heading_set', true);
		if($hide_heading!=='yes') {
?>
	<?php if((is_page() || is_single()) && !is_front_page()): ?>
		<div class="full_container_page_title">	
			<div class="container startNow animationStart">		
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
	<div class="row page_sidebar">
	
		<!-- Post -->
		<div <?php post_class(''); ?> id="post-<?php the_ID(); ?>" >
		
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
				<?php while (have_posts()) : the_post(); ?>
				<?php the_content() ?>
				<?php endwhile; ?>
			</div>
			
						
		<?php // Place sidebar if it's right
			  (!$sidebar_left ? get_sidebar() : '');?>
			
			
		</div>
		<!-- Post :: END -->

		
	</div>	
</div>

</div>
<?php get_footer(); ?>