<?php get_header(); ?>

<div class="content_bgr">
	

		<div class="full_container_page_title">	
			<div class="container animationStart">		
				<div class="row no_bm">
					<div class="sixteen columns">
						<?php boc_breadcrumbs(); ?>
						<div class="page_heading"><h1><?php _e('404 - Page Not Found', 'Savia');?></h1></div>
					</div>		
				</div>
			</div>
		</div>
	


  <div class="container animationStart">	
	<div class="row padded_block">
		<div class="sixteen columns">	
			<div class="warning_msg closable"><?php _e('The page you are trying to access does not exist!', 'Savia');?></div>
		</div>
	</div>
  </div>
  
</div>	

<?php get_footer(); ?>