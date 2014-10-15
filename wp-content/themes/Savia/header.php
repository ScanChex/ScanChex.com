<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	
	<?php global $woocommerce; ?>
	<title><?php	     
	    // Page or Single Post
	    if ( is_page() or is_single() ) {
	        the_title();	 
	    // Category Archive
	    } elseif ( is_category() ) {
	        echo single_cat_title('', false);	 
	    // Tag Archive
	    } elseif ( function_exists('is_tag') and function_exists('single_tag_title') and is_tag() ) {
	        printf( __('Tag: %s','Savia'), single_tag_title('', false) );	 
		// General Archive
	    } elseif ( is_archive() ) {
	        // If WooCommerce Shop
			if($woocommerce && is_shop() || $woocommerce && is_product_category() || $woocommerce && is_product_tag()) {
				printf( __('Shop', 'Savia'));	 
			}else {
				printf( __('Archive: %s','Savia'), wp_title('', false) );	 
			}
	    // Search Results
	    } elseif ( is_search() ) {
	        printf( __('Search: %s','Savia'), get_query_var('s') );
	    }	 
	    // Insert separator for the titles above
	    if ( !is_home() and !is_404() and !is_front_page() ) {
	        echo " - ";
	    }	
		if ( is_home() ) {
	        wp_title('');
	    }	
	    // If no home page set and default posts listing
	    if ( is_home() && is_front_page() ) {
	        echo "";
	    }elseif ( !is_home() && is_front_page() ) {
	        echo " - ";
	    }     
	    // Finally the blog name
	    bloginfo('name');	 
	    ?></title>

	<meta name="description" content="<?php bloginfo('description'); ?>" />

	<!-- Mobile Specific Metas
	================================================== -->
	<?php if(ot_get_option('responsive_design','on')=='on'){ ?>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<?php } ?>
	

	<!-- Favicons
	================================================== -->
	<link rel="icon" type="image/x-icon" href="<?php echo ot_get_option('favicon_uploaded', get_template_directory_uri().'/images/favicon.png')?>">		
		
	<?php wp_head(); ?>	
	
</head>

<body <?php body_class(); ?>>

  <?php $page_heading_style = ot_get_option('page_heading_style') ? ot_get_option('page_heading_style') : '';?>	
  <?php $subheader = ot_get_option('subheader','on') == 'on'; ?>	
  <?php if(ot_get_option('responsive_design','on')=='on'){
		$responsive_option = true;
	}else {
		$responsive_option = false;
	}
	$wrapper_style = ot_get_option('wrapper_style','full_width');
	?>
  
  <div id="wrapper" class="<?php echo ($wrapper_style=='full_width'? 'full_wrapper' : 'boxed_wrapper');?> <?php echo $page_heading_style;?> <?php echo $responsive_option?'responsive':'non-responsive';?>">
  
	<header id="header" <?php echo $subheader?' class="has_subheader"' : ' class="no_subheader"';?>>
		
		
		<?php if($subheader){ ?>
		<!-- Container -->
		<div class="full_header">
			<div class="container">	
				<div id="subheader" class="row">
					<div class="sixteen columns">
						<div class="subheader_inside">
							
						<?php if(ot_get_option('show_search','on') == 'on'){?>	
							<div class="header_search">
								<form class="search" action="<?php echo home_url(); ?>/" method="get">
									<button class="button_search"></button>
									<input name="s" id="s" type="text" placeholder="<?php echo ($s ? $s : __('Search', 'Savia').'...'); ?>" value="" />
								</form>
							</div>
						<?php }  ?>
							
						<?php if(is_array($header_icons = ot_get_option('header_icons'))){
									$header_icons = array_reverse($header_icons);							
									foreach($header_icons as $header_icon){
										echo "<a target='_blank' href='". ( $header_icon['icons_service']!='rss' ? $header_icon['icons_url'] : get_bloginfo('rss2_url') )."' class='header_soc_". $header_icon['icons_service'] ."' title='". $header_icon['title'] ."'>". $header_icon['icons_service'] ."</a>";			
									}
								}
						?>
						
						<?php $header_contacts_position = ot_get_option('header_contacts_position'); ?>	
							<div class="header_contacts" <?php echo (!$header_contacts_position? " style='float: right;'": '');?>>
								<?php if($header_email = ot_get_option('header_email')){?>
									<div class="header_mail"><?php echo $header_email;?></div>
								<?php }  ?>	
								<?php if($header_phone = ot_get_option('header_phone')){?>
									<div class="header_phone"><?php echo $header_phone;?></div>
								<?php }  ?>
							</div>
						</div>					
						
					</div>
				</div>	
			</div>	
		</div>
		<?php }  ?>		
		
		
	<div class="header_container_holder">	
		<!-- Container -->
		<div class="container">
		
			<?php $nav_top_block_style = ot_get_option('nav_top_block_style',0);?>	
					
			<div class="<?php echo ($nav_top_block_style ? 'block_header' : '');?> sixteen columns">

			
					<?php  $logo = ot_get_option('logo_upload');
						   $top_margin = ot_get_option('logo_top_margin');
						   $left_margin = ot_get_option('logo_left_margin');
						   if(isset($top_margin) && is_array($top_margin)){
								$logo_extra_style = ($top_margin[0] || $left_margin[0]) ? 1 : 0;
						   }else{
								$logo_extra_style ='';
						   }
					?>
					
				<div id="logo">
					<?php						   
					if($logo) { ?>
					<div class='logo_img'>
					<a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
						<img src="<?php echo $logo; ?>" <?php echo $logo_extra_style ? "style='". ($top_margin[0] ? 'margin-top: '.$top_margin[0].$top_margin[1].';' : '') . ($left_margin[0] ? 'margin-left: '.$left_margin[0].$left_margin[1].';' : '')."'" : ""; ?> alt="<?php bloginfo('name'); ?>"/>
					</a>
					</div>
					<?php } else { ?>
					<h1<?php echo $logo_extra_style ? "style='". ($top_margin[0] ? 'margin-top: '.$top_margin[0].$top_margin[1].';' : '') . ($left_margin[0] ? 'margin-left: '.$left_margin[0].$left_margin[1].';' : '')."'" : ""; ?>>
						<a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a>
						<div class="tagline"><?php echo get_bloginfo ( 'description' ); ?></div>
					</h1>
					<?php } ?>
				</div>

				<div id="mobile_menu_toggler"></div>
				 
				<?php	
				// If Boxed wrapper show cart before menu
				if($wrapper_style!='full_width' && boc_cart_in_header()) {
					boc_render_cart_in_header();
				}
				?>
				 
				 
				<!-- Main Navigation -->			
				<?php	$nav_top_margin = ot_get_option('nav_top_margin');
						if(isset($nav_top_margin) && is_array($nav_top_margin) && !empty($nav_top_margin[0])){
							$nav_extra_style = " style='margin-top: ".$nav_top_margin[0].$nav_top_margin[1].";'";
						}else{
							$nav_extra_style ='';
						}
				?>		   

				<div <?php echo $nav_extra_style; ?>  class="<?php echo get_theme_mod('main_menu_style'); ?>">	
				<?php wp_nav_menu( array(
						'theme_location'=> 'main_navigation',
						'container_id' 	=> 'menu', 
						'container_class'=> boc_cart_in_header() ? "pushed_menu_by_cart" : '',
						'menu_class' 	=> '', 
						'walker' 		=> new boc_Menu_Walker,
						'fallback_cb'   => 'menuFallBack',
						'items_wrap' => '<ul>%3$s</ul>',
				));?>
				</div>
				
				
						
			</div>			
			
		</div>
		
		
		
<?php 	
	// If Full width wrapper show cart here
	if($wrapper_style=='full_width' && boc_cart_in_header()) {
			boc_render_cart_in_header();
 	} ?>

			
			
		
	</div>			
	
			
		<div id="mobile_menu">	
		<?php wp_nav_menu( array(
				'theme_location'=> 'main_navigation',
				'container' 	=> '',
				'menu_class' 	=> '', 
				'walker' 		=> new boc_Menu_Walker,
				'fallback_cb'   => 'menuFallBack',
				'items_wrap' => '<ul>%3$s</ul>',
		));?>
		</div>
	
	</header>
