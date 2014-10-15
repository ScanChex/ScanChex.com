<?php 

	$footer_style = ot_get_option('footer_style');
	
?>	

	<!-- Footer -->
	<div id="footer" class="<?php echo (!$footer_style ? 'footer_light' : '');?>">
		<div class="container">	
			<div class="row">
			  <div class="four columns">
				<?php if ( ! dynamic_sidebar('Footer Widget 1') ) : ?>			
					<h3 class="widgettitle">Footer Widget Area 1</h3>
					<p><a href="<?php echo admin_url('widgets.php'); ?>">Assign a widget to this area now.</a></p>	
				<?php endif; // end widget area ?>	
			  </div>

			  <div class="four columns">
				<?php if ( ! dynamic_sidebar('Footer Widget 2') ) : ?>
					<h3 class="widgettitle">Footer Widget Area 2</h3>
					<p><a href="<?php echo admin_url('widgets.php'); ?>">Assign a widget to this area now.</a></p>					
				<?php endif; // end widget area ?>	
			  </div>

			  <div class="four columns">
				<?php if ( ! dynamic_sidebar('Footer Widget 3') ) : ?>
					<h3 class="widgettitle">Footer Widget Area 3</h3>
					<p><a href="<?php echo admin_url('widgets.php'); ?>">Assign a widget to this area now.</a></p>					
				<?php endif; // end widget area ?>	
			  </div>

			  <div class="four columns">
				<?php if ( ! dynamic_sidebar('Footer Widget 4') ) : ?>
					<h3 class="widgettitle">Footer Widget Area 4</h3>
					<p><a href="<?php echo admin_url('widgets.php'); ?>">Assign a widget to this area now.</a></p>					
				<?php endif; // end widget area ?>		
			  </div> 
		    </div> 
			<div class="clear"></div>
		</div>
		
		<div class="footer_btm">
			<div class="container">
				<div class="row">
					<div class="sixteen columns">
						<div class="footer_btm_inner">
						
						<?php 	if(is_array($footer_icons = ot_get_option('footer_icons'))){
									$footer_icons = array_reverse($footer_icons);							
									foreach($footer_icons as $footer_icon){
										echo "<a target='_blank' href='". $footer_icon['icons_url_footer']."' class='icon_". $footer_icon['icons_service_footer'] ."' title='". $footer_icon['title'] ."'>". $footer_icon['icons_service_footer'] ."</a>";			
									}
								}
						?>
						
							<div id="powered"><?php echo ot_get_option('copyrights');?></div>
							<div class="clear"></div>
						</div>	  
					</div>
				</div>			
			</div>
		</div>
  </div>
  <!-- Footer::END -->
  
  <?php wp_footer(); ?>
  
  
</body>
</html>