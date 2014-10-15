/* Savia Common JS functionalities */

is_mobile_device = isMobile();
sticky_header = (JSParams.sticky_header=='0')?false:true;
fixed_footer = (JSFooterParams.fixed_footer=='0')?false:true;

jQuery(document).ready(function($){
	
	var header_h = jQuery('#header').height();
	win_width = $(window).width();
	
	if(is_mobile_device){
		$('#header').addClass('mobile_non_responsive_option');
		$('#footer').addClass('mobile_non_responsive_option');
	}
	if(sticky_header && !is_mobile_device){
		if(win_width >= 960){	
			jQuery('.content_bgr').css('marginTop', header_h + 'px');
		}
	}
	if(jQuery('#wrapper.full_wrapper').doesExist()) {
		jQuery('body').css('background-image','none');
	}
	
	// Remove left indenting used for animating in sections on mobile
	if(is_mobile_device){
		jQuery('.info_block.animationStart .info_item').css('left',0);		
		jQuery('#portfolio_items.animationStart .portfolio_animator_class').css('left',0);		
		jQuery('.counter_desc').css('left',0);		
		jQuery('.circ_numbers_holder.animationStart .counter').css('left',0);		
		jQuery('.team_block_content.animationStart > .pic').css('left',0);		
	}


	// MENU LOGIC - Customize to keep HTML logic the same
//	$('#menu .sub-menu').not('#menu .sub-menu .sub-menu').before('<span class="nav_arrow"></span>');
	
	$('#menu .sub-menu').prev('a').addClass('no_border');
	$('#menu .sub-menu').wrap('<div/>');

	$('#menu .sub-menu li a').wrapInner('<span/>');
	$('#menu .sub-menu > li:last-child > a').addClass('last_submenu_item');
	
	$('#menu .sub-menu > li > div').addClass('subsub_menu');

	$('#menu .children').prev().append('<span></span>');
	$('#menu .children').wrap('<div/>');
	$('#menu .children li a').wrapInner('<span/>');
	$('#menu .children > li:last-child > a').addClass('last_submenu_item');
	
	$('#menu .children > li > div').addClass('subsub_menu');
	
	
	$('#menu .subsub_menu > ul > li > div').removeClass('subsub_menu').addClass('subsubsub_menu');
	$('#menu .subsubsub_menu > ul > li > div').addClass('subsubsub_menu');
	
	
	$('#menu .subsub_menu').prev('a').addClass('sub_menu_parent');
		
	// Mobile Menu
	$('#mobile_menu_toggler').click(function(){		
		$('#mobile_menu').stop(true,true).slideToggle(500);
	});
	
	$('#mobile_menu ul li a > span').addClass('icon').addClass('chevron-down');		
	
	$('#mobile_menu ul li a > span').click(function(e){	
		e.preventDefault();
		$(this).parent('a').next('ul').stop(true,true).slideToggle(500);
		if($(this).hasClass('chevron-down')) {
			$(this).removeClass('chevron-down').addClass('chevron-up');	
		}else {
			$(this).removeClass('chevron-up').addClass('chevron-down');	
		}
	});
	
	$(window).resize(function() {
			win_width = $(window).width();
			if(win_width >= 960){
				$('#mobile_menu').css('display', 'none');
			}
	});
	// MENU LOGIC :: END
	
	
	// Comment button restyle
	$('#respond #submit').addClass('button tiny_button regular_text');
	
	
	
	// Sidebar Menu 
	$('#sidebar .widget_categories > ul, #sidebar .widget_nav_menu  > div > ul, #sidebar .widget_pages ul:first, #sidebar .widget_meta ul, #sidebar .widget_recent_entries ul, #sidebar ul.product-categories').addClass('side_bar_menu');
	$('#sidebar ul.side_bar_menu a').wrapInner('<span class="link_span"/>').prepend('<span class="hover_span"></span>');
	
	// Sidebar Menu effects
	$('.side_bar_menu a').not(".active").hover(
			function() {
				$(this).children('.hover_span').stop().animate({width:'100%'},500,'easeOutExpo');
			},
			function() {
				$(this).children('.hover_span').stop().animate({width:'0'},200,'easeOutExpo');
			}
	);	
	
	// Sidebar Nav effects	
	$('.side_bar_nav a').not(".active").hover(
		function() {
			$(this).children('.hover_span').stop().animate({width:'100%'},500,'easeOutExpo');
		},
		function() {
			$(this).children('.hover_span').stop().animate({width:'0'},200,'easeOutExpo');
		}
	);	
	
	$('#sidebar .left_title').addClass('title_bgr');
	$('.testimonials').siblings('.left_title').removeClass('title_bgr');
	$('.testimonials').parents('.textwidget').css('paddingBottom',0);
	
	$('.more-link').before('<p></p>');
	$('.more-link').wrap('<p/>');

	
	// Top Comment class
	$('.single_comment:first').addClass('first_comment');
	
	// Footer Navigation pushoff
	$('#footer .menu').addClass('margined_left');
	$('#footer .menu').parent().prev('h3').addClass('margined_left');
	
	
	// Menu Animation
    $('#menu ul li').hover(
        function() {
            $(this).addClass("active");
            $(this).find('div').not('.subsub_menu, .subsubsub_menu').stop(false, true).fadeIn({
            	duration:700,
            	easing:"easeOutExpo"});
        },
        function() {
            $(this).removeClass("active");
            $(this).find('div').not('.subsub_menu, .subsubsub_menu').stop(false, true).fadeOut({
            	duration:200,
            	easing:"easeOutExpo"});
        }
    );

    
	// Sub Menu Animation
    $('#menu ul li li').hover(
        function() {
            $(this).find('.subsub_menu').stop(false, true).fadeIn({
            	duration:300,
            	easing:"easeOutExpo"});
        },
        function() {        
            $(this).find('.subsub_menu').stop(false, true).hide();
        }
    );	
    
    // Subsub Menu Animation
    $('#menu ul li li li').hover(
    		function() {
    			$(this).find('.subsubsub_menu').stop(false, true).fadeIn({
    				duration:300,
    				easing:"easeOutExpo"});
    		},
    		function() {        
    			$(this).find('.subsubsub_menu').stop(false, true).hide();
    		}
    );	
	
	// Gallery items add PrettyPhoto
	$('.gallery-item .gallery-icon a').attr('data-rel','prettyPhoto');
	
	// Sidebar Nav effects	
	$('.side_bar_nav a').not(".active").hover(
		function() {
			$(this).children('.hover_span').stop().animate({width:'100%'},500,'easeOutExpo');
		},
		function() {
			$(this).children('.hover_span').stop().animate({width:'0'},200,'easeOutExpo');
		}
	);

	/* Custom Slides */
	cust_slides_arr = new Array();
	$('.custom_slides').each(function(){
		var slides_h = $(this).data('height');
		$(this).children('.custom_slides_holder').animate({height: slides_h+"px"}, 200);
		$(this).children('.custom_slides_holder').children('.custom_slide.hidden').css('top',slides_h+"px");

		var this_slider_unique_id = $(this).data('unique_id');
		
		var auto_scroll = $(this).data('auto');
		var auto_time = $(this).data('time');

		if(auto_scroll){
			var this_slider = $(this);
			cust_slides_arr[this_slider_unique_id] = window.setInterval(function(){
				if(this_slider.children('.custom_slides_controls_holder').children('.custom_slides_controls').children('.shown').next().doesExist()){
					next_element = this_slider.children('.custom_slides_controls_holder').children('.custom_slides_controls').children('.shown').next();
				}else {
					next_element = this_slider.children('.custom_slides_controls_holder').children('.custom_slides_controls').children('li:first');
				}
				next_element.click();
			},auto_time+'000');
		}
	});
	
	$('.custom_slides .custom_slides_controls li').click(function(e) {
		e.preventDefault();
		$(this).parents('.custom_slides').find('.custom_slide').each(function(){
			$(this).removeClass('shown');
		});
		$(this).siblings().removeClass('shown');
		$(this).addClass('shown');
		$($(this).data('for')).addClass('shown');
		var title = $(this).data('title');
		$(this).parents('.custom_slides').find('.custom_slides_title').fadeOut(200, function(){
			$(this).text(title).fadeIn(300);
		});

		var this_slider_unique_id = $(this).parents('.custom_slides').data('unique_id');

		// Auto stuff
		if(e.hasOwnProperty('originalEvent')){
        	window.clearInterval(cust_slides_arr[this_slider_unique_id]);
		}
	});

	$('.custom_slides_holder').children('.custom_slide:first-child').addClass('shown');
	$('.custom_slides_controls').children('li:first-child').addClass('shown');

	$('.custom_slides_title').each(function(){
		var title = $(this).next().find('ul li:first-child').data('title');
		$(this).parents('.custom_slides').find('.custom_slides_title').fadeOut(200, function(){
			$(this).text(title).fadeIn(300);
		});
	});
	
	/* Custom Slides :: END */
	
	
	
	/* Accordions */
	$(".accordion").click(function(){
		
		$(this).parent('.acc_item').siblings().children(".accordion_content").not($(this).siblings(".accordion_content")).slideUp(600,'easeInOutExpo');
		$(this).parent('.acc_item').siblings().children(".accordion").not($(this)).removeClass("active_acc");

		$(this).next(".accordion_content").slideToggle(600,'easeInOutExpo');
		
		if($(this).hasClass('active_acc')){
			$(this).removeClass("active_acc");
		}else{
			$(this).addClass("active_acc");
		}
	});
	
	// Open First item if accordion whenever set so
	setTimeout(function(){$(".acc_is_open").delay(1500).click();},600);
	/* Accordions::END */
	
	
	/* Testimonials */
    $(".testimonials_carousel").each(function(){
    	var is_auto_scroll = $(this).hasClass('auto_scroll');
	    $(this).jcarousel({
	    	auto: (is_auto_scroll ? 6 : 0),
	    	wrap: (is_auto_scroll ? "last" : ""),
	    	scroll: 1,
	    	easing: "easeInOutExpo",
	    	animation: 600
	    });
    });   
    /* Testimonials::END */
	
	
	/* Info Messages */
	$(".closable").each(function(){
		$(this).prepend('<a class="close_img"></a>');		
	});
	
	$(".close_img").click(function(){
		$(this).parent().fadeOut(600);
	});
	
	// If responsive mode is enabled set Variable
	if(jQuery('#wrapper').hasClass('responsive')) {		
		responsive_mode = true;
	}else {
		responsive_mode = false;
	}
	
	
	
	// TABS
	// First we build the tabs
	$('.resp-tabs-container .single_tab_div').each(function(){
		var title = $(this).attr('rel-title');
		var icon = $(this).attr('rel-icon');
		var icon_html = (icon!='') ? "<i class='icon "+ icon +"'></i> " : '';
		$(this).parent('.resp-tabs-container').prev('ul.resp-tabs-list').append('<li>'+ icon_html + title +'</li>');
	});
	
	$('.newtabs').each(function(){
		var type = $(this).hasClass("vertical")? "vertical" : "horizontal";
		$(this).easyResponsiveTabs({
			type: type,
			width: 'auto',
			fit: true
		});
	});
	
	
	
	// Tooltips
	$('.tooltipsy').tipsy({fade: true, gravity: 's'});
	
	
	// Slider
	$(window).load(function(){
	    $('.flexslider').each(function(){
			$(this).flexslider({
		      animation: (($(this).attr('rel')=="slide")? "slide" : "fade"),
			  slideshowSpeed: $(this).attr('rel-speed'),
		      controlNav: false,
		      start: function(slider){
		    	  $('body').removeClass('loading');
		      }
			});
		});
	 });
	 
    // PrettyPhoto
    $("a[rel^='prettyPhoto']").prettyPhoto({
		animation_speed:'normal',
		overlay_gallery: false,
		social_tools: false
	});
    
    
	// PrettyPhoto for Woo - takes in data-rel
    $("a[data-rel^='prettyPhoto']").prettyPhoto({
		animation_speed:'normal',
		overlay_gallery: false,
		social_tools: false
	});
    
    
    
    // Animate logos fading
    var fading_logos = true;
	jQuery('.client_info img').hover(
		function() {
			if(typeof(fading_logos) != "undefined" && fading_logos){
				jQuery(this).parents('.client_info:first').siblings('.client_info').each(function (i, e) {
					jQuery(['-webkit-', '-moz-', '-o-', '-ms-', '']).each(function (i, p) {
						jQuery(e).css(p + 'transition-delay' , 0 + 'ms');
					});
				});
				jQuery(this).parents('.client_info:first').siblings('.client_info').stop().fadeTo(150, 0.4);
			}
		},
		function() {
			if(typeof(fading_logos) != "undefined" && fading_logos){
				jQuery(this).parents('.client_info:first').siblings('.client_info').stop().fadeTo(150, 1);
			}
		}	
	);
    
	
	jQuery('.woocommerce ul.products li.product').hover(		
		function() {
			jQuery(this).children('a.button').addClass('activated');
			jQuery(this).siblings('.woocommerce ul.products li.product').each(function () {			
				jQuery(this).css('border' , '1px solid #ffffff');
			});
		},
		function() {
			jQuery(this).children('a.button').removeClass('activated');
			jQuery(this).siblings('.woocommerce ul.products li.product').each(function () {			
				jQuery(this).css('border' , '1px solid #f1f1f1');
			});
		}	
	);

    
    // Animate icons fading
	jQuery('.subheader_inside a').hover(
		function() {
			jQuery(this).siblings('a').stop().fadeTo(100, 0.3);
			jQuery(this).stop().fadeTo(100, 1);
		},
		function() {
			jQuery(this).siblings('a').stop().fadeTo(100, 0.8);
			jQuery(this).stop().fadeTo(100, 0.8);
		}	
	);
     
    // Animate icons fading
	jQuery('.footer_btm_inner a').hover(
		function() {
			jQuery(this).siblings('a').stop().fadeTo(100, 0.35);
		},
		function() {
			jQuery(this).siblings('a').stop().fadeTo(100, 1);
		}	
	);   
    
    // Resize Videos
    jQuery(function() {
        
        var $allVideos = jQuery("iframe[src^='http://player.vimeo.com'], iframe[src^='//player.vimeo.com'], iframe[src^='http://www.youtube.com'], iframe[src^='//www.youtube.com'], object, embed").not('.rev_slider iframe'),
        $fluidEl = jQuery(".video_max_scale");
    	    	
    	$allVideos.each(function() {
    	
    		jQuery(this)
    	    // jQuery .data does not work on object/embed elements
    	    .attr('data-aspectRatio', this.height / this.width)
    	    .removeAttr('height')
    	    .removeAttr('width');
    	
    	});
    	
    	jQuery(window).resize(function() {
    		
    	  $allVideos.each(function() {
  			
    		    var nWidth = $(this).parent().width();
    	    	var $el = jQuery(this);
    	    	$el
    	    	    .width(nWidth)
    	    	    .height(nWidth * $el.attr('data-aspectRatio'));
    	    	  
    	   });
    	
    	}).resize();

    });
    

	 // Reload carousels on window resize
	 if (jQuery(".testimonials_carousel").length){
	 	jQuery(window).resize(function() {
	 		 var el = jQuery(".testimonials_carousel"), carousel = el.data('jcarousel'), win_width = jQuery(window).width();
	 		   carousel.options.visible = 1;
	 		   carousel.options.scroll = 1;
	 		   carousel.reload();
	 	});
	 }

	 
	     
    // Bar graph
    jQuery(".bar_graph li").each( function(){
    	var b_width = jQuery(this).find("span").attr("data-width");
    	jQuery(this).appear();
    	jQuery(this).on('appear', function(event, $all_appeared_elements) {
			if(jQuery(this).hasClass("animationBegin")){	
				jQuery(this).removeClass("animationBegin");
	    		jQuery(this).animate({opacity: 1},800,"easeOutCubic");
	        	jQuery(this).find("span").animate({width: b_width+"%"},1300,"easeOutCubic");
	        	jQuery(this).find("span strong").animate({opacity: 1},1300,"easeOutCubic");
			}
    	});
    });
	 
	 
	 // Header resizing
	if(sticky_header && !is_mobile_device){
		jQuery(window).scroll(function () {
			win_width = $(window).width();
			if(win_width >= 960){
				var window_y = $(window).scrollTop();	
				if(window_y >= header_h){
					jQuery('#header').addClass('scrolled');
				}else {
					jQuery('#header').removeClass('scrolled');
				}
			}
		});
	}
	 
	// Header and footer distance
	if(!is_mobile_device){
		jQuery(window).resize(function() {	 
			win_width = $(window).width();
			if(win_width >= 960){
				if(sticky_header){
					var header_h = jQuery('#header').height();
					jQuery('.content_bgr').css('marginTop', header_h + 'px');	  
				}
				var footer_h = jQuery('#footer').height();
				footer_h += 35;
				if(fixed_footer){
					jQuery('.content_bgr').css('marginBottom', footer_h + 'px');
				}
			}else {
				jQuery('.content_bgr').css('marginTop', 0);	
				jQuery('.content_bgr').css('marginBottom', 0);
			}
		});
	}
	
	/* ----------- WooCommerce Cart ------------ */

	var timeout;
	var productData;
	
	// Woo image wrappers for product page
	$('.woocommerce div.product div.images img').wrap('<div class="product_img_wrapper"></div>');

	// Add top padding to product page if there's no pageheading
	if(!jQuery('.full_container_page_title').doesExist()) {
		jQuery('.boc_single_product').animate({paddingTop: 40},500);
	}
	
	
	$('.woocommerce .product .add_to_cart_button').click(function(){
		productData = $(this).parents('li').find('h3').text();
		$('.header_cart .cart-notification span.item-name').html(productData);
	});

	// Notification
	$('.header_cart .cart-notification').hover(function(){
		$(this).fadeOut(400);
		$('.header_cart .widget_shopping_cart').stop(true,true).fadeIn(400);
		$('.header_cart .cart_list').stop(true,true).fadeIn(400);
		clearTimeout(timeout);
	});

	// Header cart
	$('.header_cart').hover(function(){
		win_width = $(window).width();
		if(win_width >= 960){
			$('.header_cart .widget_shopping_cart').stop(true,true).fadeIn(400);
			$('.header_cart .cart_list').stop(true,true).fadeIn(400);
			clearTimeout(timeout);
			$('.header_cart .cart-notification').fadeOut(300);
		}
	},function(e){
		$('.header_cart .widget_shopping_cart').stop(true,true).fadeOut(300);
		$('.header_cart .cart_list').stop(true,true).fadeOut(300);
	});


	$('body').bind('added_to_cart', shopping_cart_dropdown_show);
	$('body').bind('added_to_cart', shopping_cart_dropdown);
	
	function positionCart() {
		if(!jQuery('.boxed_wrapper .header_cart').doesExist()) {
			win_width = $(window).width();
			if(win_width< 1354){
				if(win_width >= 960){
					// Push menu away
					// Boxed Layout else - Full Width Layout
					if(jQuery('.boxed_wrapper .pushed_menu_by_cart').doesExist()) {
					//	var cart_width = $('#header .header_cart').width() + 21;		
					//	$('#menu').attr('style', 'right: '+cart_width+'px!important;');
						
					}else {
						$('#menu').css('right', 75);
						cart_right_pos = (win_width - $('#header .container:first').width())/2;
						$('#header .header_cart').css('right',cart_right_pos).addClass('cart_menu_attached');
					}
				}else if((win_width >= 940) && (win_width < 960)) {
					$('#menu').css('right', 75);
					cart_right_pos = (win_width - $('#header .container:first').width())/2;
					$('#header .header_cart').css('right', 0).addClass('cart_menu_attached');
				}else {
					$('#header .header_cart').addClass('zero_transitions');
					cart_right_pos = (win_width - $('#header .container:first').width())/2;					
					$('#header .header_cart').css('right',cart_right_pos + 50).addClass('cart_menu_attached');
				}
			}else {
				$('#menu').css('right', 0);
				$('#header .header_cart').css('right','0px').removeClass('cart_menu_attached');
			}
		}
	}

	var sticky_cart = 0;
	function startStickyCart(){
		sticky_cart = 1;
		
		positionCart();
		jQuery(window).resize(function() {
			positionCart();
		});
	}
	
	function shopping_cart_dropdown() {
											
		if(!$('.header_cart .widget_shopping_cart .widget_shopping_cart_content .cart_list .empty').length && $('.header_cart .widget_shopping_cart .widget_shopping_cart_content .cart_list').length > 0 ) {
			
			$('.header_cart').removeClass('is_empty').addClass('is_not_empty');
			if(!sticky_cart) {
				startStickyCart();
			}
		}else {
			$('.header_cart').addClass('is_empty').removeClass('is_not_empty');
		}
	}

	function shopping_cart_dropdown_show(e) {
	
			clearTimeout(timeout);
			
			if(!$('.widget_shopping_cart .widget_shopping_cart_content .cart_list .empty').length && $('.widget_shopping_cart .widget_shopping_cart_content .cart_list').length > 0 && typeof e.type != 'undefined' ) {
				
				if(!$('.header_cart .cart-menu-wrap').hasClass('is_not_empty')) {
					setTimeout(function(){ $('.header_cart .cart-notification').fadeIn(400); },400);
				}
				else if(!$('.header_cart .cart-notification').is(':visible')) {
					$('.header_cart .cart-notification').fadeIn(400);
				} else {
					$('.header_cart .cart-notification').show();
				}
				timeout = setTimeout(hideCart,2900);
			}
	}

	function hideCart() {
		$('.header_cart .cart-notification').stop(true,true).fadeOut();
	}

	setTimeout(shopping_cart_dropdown,1000);
	
	/* ----------- WooCommerce Cart :: END ------------ */
	
    	
		
		
	
	// Call Stellar scrolling (a bit delayed
	if(!is_mobile_device){
		jQuery(function(){	
			setTimeout(function() {
				jQuery.stellar({
						horizontalScrolling: false,
						verticalOffset: 40
				});
			},300);
			
		});
	}else {
		jQuery('.parallax_bgr').addClass('on_mobile_device');
	}	
			
		
});


jQuery.fn.doesExist = function(){
	return jQuery(this).length > 0;
}




//On Page load calculate header spacing for admin bar
jQuery(window).load(function(){
	pushHeaderAdminBar();
});

jQuery(window).resize(function() {
	pushHeaderAdminBar();
});

function pushHeaderAdminBar() {
	if(sticky_header && !is_mobile_device){
		win_width = jQuery(window).width();
		if(win_width >= 960){
			if(jQuery('#wpadminbar').doesExist()) {
				var wp_adm_h = jQuery('#wpadminbar').height();
				jQuery('#header').css('marginTop', wp_adm_h + 'px');
			}
		}else {
			jQuery('#header').css('marginTop', 0);
		}
	}
}

//On Page load calculate footer spacing
if(!is_mobile_device){
	jQuery(window).load(function(){
		win_width = jQuery(window).width();
		if(win_width >= 960){
			var footer_h = jQuery('#footer').height();
			footer_h += 35;
			if(fixed_footer){
				jQuery('.content_bgr').css('marginBottom', footer_h + 'px');
			}
		}else {
			jQuery('.content_bgr').css('marginBottom', 0);
		}
	});
}

// For appear transitions
jQuery.fn.trans = function () {
	var t = arguments[0],
		d = arguments[1] || '';
	if (t) {
		jQuery.each(this, function (i, e) {
			jQuery(['-webkit-', '-moz-', '-o-', '-ms-', '']).each(function (i, p) {
				jQuery(e).css(p + 'transition' + d, t);
			});
		});
	}
};

function preloadImages(imgs, callback) {
	var cache = [],
		imgsTotal = imgs.length,
		imgsLoaded = 0;

	if(jQuery(imgs).length){
		jQuery(imgs).each(function (i, img) {
			var cacheImage = document.createElement('img');
			cacheImage.onload = function () {
				if (++imgsLoaded == imgsTotal) callback();
			};
			cacheImage.src = jQuery(img).attr('src');
			cache.push(cacheImage);
		});
	}else {
		callback();
	}
}

function isMobile() {
  try{ document.createEvent("TouchEvent"); return true; }
  catch(e){ return false; }
}