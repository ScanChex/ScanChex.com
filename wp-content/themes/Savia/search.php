<?php 

get_header(); ?>

<div class="content_bgr">


<?php if(is_archive() || is_search() || is_home()): ?>
	<div class="full_container_page_title">	
		<div class="container animationStart">		
			<div class="row no_bm">
				<div class="sixteen columns">
				    <?php boc_breadcrumbs(); ?>
					<div class="page_heading"><h1><?php echo (is_archive() ? single_cat_title() : (is_search() ? _e('Search results for:', 'Savia').' '. get_search_query(): (is_home() ? wp_title('') :'') ));?></h1></div>
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
			
				<?php /*
				<form class="search_form_tpl_form" action="<?php echo home_url(); ?>/" method="get">
					<button class="button_search"></button>
					<input name="s" id="s" type="text" placeholder="<?php echo ($s ? $s : __('Search', 'Savia').'...'); ?>" value="" />
				</form>
				<div class="clear"></div>
				*/?>
			
				<ol class="search_res">
				<?php $posts=query_posts($query_string . '&posts_per_page=-1'); ?>
				<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
					<li>
					<!-- Post Loop Begin -->
					<div class="post_item">
					
						
							<h3><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'Savia'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a> <span class="post_type_in_search"> (<?php echo get_post_type( $post ) ?>) </span> </h3>
							
							<?php echo str_replace("&nbsp;","",get_the_excerpt_max_charlength(120)); ?>
							
							<a href="<?php the_permalink(); ?>" class="more-link">Read more</a>
							<div class="divider_bgr"></div>
						
					</div>
					<!-- Post Loop End -->
					</li>
				<?php endwhile; ?>
				
				<?php boc_pagination($pages = '', $range = 2); ?>
				
				</ol>
				
				<?php else: ?>
				<p><?php _e('Sorry, no posts matched your criteria.','Savia'); ?></p>
				<?php endif; // Loop End  ?>

			</div>
		
		
		<?php // Place sidebar if it's right
			  (!$sidebar_left ? get_sidebar() : '');?>
		
			
			<div class="h40 clear"></div>
		</div>	
	</div>
</div>
<?php get_footer(); ?>	