var win_w = jQuery(window).width();
is_mobile_device = isMobile();

//IF Big Screen
if(win_w > 767 && !is_mobile_device){
	
		if (Modernizr.csstransitions) {
			
			document.write('<link rel="stylesheet" href="'+ template_dir_uri +'/stylesheets/animations.css\" />');
		
			jQuery(function(){

				preloadImages(jQuery('.container.animationStart img'), function () {
					jQuery('.container.animationStart, .full_container_slider.animationStart').appear({
						percentage: 30,
						once: true,
						forEachVisible: function (i, e) {
							jQuery(e).data('delay', i);
						},
						appear: function () {
							jQuery(this).removeClass('animationStart');
						}
					});
				});
			
			
				preloadImages(jQuery('.section_featured_texts.animationStart img'), function () {
					jQuery('.section_featured_texts.animationStart').appear({
						once: true,
						percentage: 50,
						forEachVisible: function (i, e) {
							jQuery(e).data('delay', i);
						},
						appear: function () {
							var delay = 350,
								stagger = 450,
								sequential_delay = stagger * parseInt(jQuery(this).data('delay')) || 0,
								img_delay = (delay * jQuery(this).data('delay') + 200);
							
							jQuery(this).children().each(function (i, e) {
								if(i==0){
									jQuery(e).trans(img_delay + 'ms', '-delay');
								}else{
									jQuery(e).trans(i * delay + sequential_delay + 'ms', '-delay');
								}
							});
							jQuery(this).removeClass('animationStart');
						}
					});
				});			
				
				
				preloadImages(jQuery('.info_block.animationStart img'), function () {
					jQuery('.info_block.animationStart').appear({
						once: true,
						forEachVisible: function (i, e) {
							jQuery(e).data('delay', i);
						},
						appear: function () {
							var delay = 400,
								stagger = 1000,
								sequential_delay = stagger * parseInt(jQuery(this).data('delay')) || 0;
		
							jQuery(this).find('.info_item').each(function (i, e) {
								jQuery(e).trans(i * delay + sequential_delay + 'ms', '-delay');
							});
							jQuery(this).removeClass('animationStart');
						}
					});
				});
				
				preloadImages(jQuery('.client_info_holder.animationStart img'), function () {
					jQuery('.client_info_holder.animationStart').appear({
						once: true,
						forEachVisible: function (i, e) {
							jQuery(e).data('delay', i);
						},
						appear: function () {
							var delay = 300,
								stagger = 1000,
								sequential_delay = stagger * parseInt(jQuery(this).data('delay')) || 0;
		
							jQuery(this).children().each(function (i, e) {
								jQuery(e).trans(i * delay + sequential_delay + 'ms', '-delay');
							});
							jQuery(this).removeClass('animationStart');
						}
					});
				});			


				preloadImages(jQuery('#portfolio_items.animationStart img'), function () {
					jQuery('#portfolio_items.animationStart').appear({
						percentage: 0,
						once: true,
						forEachVisible: function (i, e) {
							jQuery(e).data('delay', i);
						},
						appear: function () {
							var delay = 200,
								stagger = 400,
								sequential_delay = stagger * parseInt(jQuery(this).data('delay')) || 0;
		
							jQuery(this).find('.portfolio_animator_class').each(function (i, e) {
								jQuery(e).trans(i * delay + sequential_delay + 'ms', '-delay');
							});
							jQuery(this).removeClass('animationStart');
						}
					});
				});		

				preloadImages(jQuery('.portfolio_page .columns .pic img'), function () {
					jQuery('.portfolio_page .columns .pic.animationStart').appear({
						percentage: 25,
						once: true,
						forEachVisible: function (i, e) {
							jQuery(e).data('delay', i);
						},
						appear: function () {
							var delay = 400,
								stagger = 1000,
								sequential_delay = stagger * parseInt(jQuery(this).data('delay')) || 0;

							jQuery(this).removeClass('animationStart');
						}
					});
				});


				preloadImages(jQuery('.team_block_content.animationStart img'), function () {
					jQuery('.team_block_content.animationStart').appear({
						once: true,
						forEachVisible: function (i, e) {
							jQuery(e).data('delay', i);
						},
						appear: function () {
							var delay = 200,
								stagger = 400,
								sequential_delay = stagger * parseInt(jQuery(this).data('delay')) || 0;
		
							jQuery(this).children('.pic').each(function (i, e) {
								jQuery(e).trans(i * delay + sequential_delay + 'ms', '-delay');
							});
							jQuery(this).removeClass('animationStart');
						}
					});
				});														
				
			});
		}
}//IF Big Screen :: End

// Immediate start
jQuery(document).ready(function() {
	setTimeout(function(){jQuery('.container.startNow').removeClass('animationStart')},400);
	jQuery('.rev_slider_wrapper').parents('.animationStart:first').addClass('immediateShow');
});


// Disable the transitions that mess up Live resizing (carousels + info Items etc)
jQuery(window).load(function(){
	jQuery(window).resize(function() {  
		 jQuery('.info_block .info_item').each( function (i, e) {
			jQuery(['-webkit-', '-moz-', '-o-', '-ms-', '']).each(function (i, p) {
				jQuery(e).css(p + 'transition','0ms');
			});
		 }); 
	});
});

function isMobile() {
  try{ document.createEvent("TouchEvent"); return true; }
  catch(e){ return false; }
}