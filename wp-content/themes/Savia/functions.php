<?php
///////////////////////////////////////////
//--------- OT THEME OPTIONS ---------------//
///////////////////////////////////////////

add_filter( 'ot_show_pages', '__return_false' );
add_filter( 'ot_show_new_layout', '__return_false' );
add_filter( 'ot_theme_mode', '__return_true' );
load_template( trailingslashit( get_template_directory() ) . 'option-tree/ot-loader.php' );

// BOC Theme Options
load_template( trailingslashit( get_template_directory() ) . 'includes/theme-options.php' );


///////////////////////////////////////////
//--------- OT THEME OPTIONS :: END --------//
///////////////////////////////////////////


// Savia Customizer Theme Options
include_once( 'includes/customizer_theme-options.php' );
include_once( 'includes/meta_boxes.php' );

// Default RSS feed links
add_theme_support('automatic-feed-links');

// Post Formats
add_theme_support( 'post-formats',  array( 'gallery','video' ));
add_post_type_support( 'post', 'post-formats' );
add_post_type_support( 'portfolio', 'post-formats' );


// Sets up the content width value based on the theme's design and stylesheet (Required by Theme Check)
if ( ! isset( $content_width ) )
	$content_width = 940;


// Enable Background Support
$args = array(
    'default-color' => 'f6f6f6',
    'default-image' => get_template_directory_uri() . '/images/main_bgr.png',
);
add_theme_support( 'custom-background', $args );

// Savia Customizer Theme Options
add_action( 'customize_register', 'aqua_customize_register' );

// Add customize Menu Item
add_action ('admin_menu', 'customizetheme_admin');
function customizetheme_admin() {
    add_theme_page( 'Customize', 'Customize', 'edit_theme_options', 'customize.php' ); 
}


// Savia suggested plugins
require_once 'includes/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'boc_theme_register_required_plugins' );


//Global custom js_params
$js_params = array();
$footer_js_params = array();

// Enqueue Styles
function boc_style() {
    
	global $js_params;
	global $footer_js_params;
	
	wp_enqueue_style( 'boc-style', get_bloginfo( 'stylesheet_url' ) );
	if(ot_get_option('responsive_design','on')=='on'){
		wp_enqueue_style( 'boc-responsive-style', get_template_directory_uri().'/stylesheets/responsive.css' );
	}else {
		wp_enqueue_style( 'boc-responsive-style', get_template_directory_uri().'/stylesheets/non-responsive.css' );
	}	
	
	$protocol = is_ssl() ? 'https' : 'http';
    wp_enqueue_style( 'boc-fonts', "$protocol://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,300,600,700|Droid+Serif:400,700,400italic,700italic" );

  	$fonts_already_loaded = array("Novecento","Open+Sans", "Droid+Serif");
	$fonts_to_load = array();

	// Load Nav Font 
	if(!in_array(($nav_font = ot_get_option('nav_font_family')), $fonts_already_loaded)) {
		if($nav_font){
			$fonts_to_load[] = $nav_font;
		}
	}
	// Load Headings Font 
	if(!in_array(($heading_font = ot_get_option('heading_font_family')), $fonts_already_loaded)) {
		if($heading_font){	
			$fonts_to_load[] = $heading_font;
		}
	}
	
	// Load Buttons Font 
	if(!in_array(($button_font = ot_get_option('button_font_family')), $fonts_already_loaded)) {
		if($button_font){		
			$fonts_to_load[] = $button_font;
		}
	}

	// Loading additional fonts
	foreach($fonts_to_load as $font){
		
		if(!in_array($font, $fonts_already_loaded)){
			$protocol = is_ssl() ? 'https' : 'http';
	    	wp_enqueue_style('boc-custom-font-'.$font, "$protocol://fonts.googleapis.com/css?family=$font",array('boc-fonts'));		
		}	
	}  	
  	

	$inline_css = '';
	
	// Normal/Sticky Header
	if(ot_get_option('sticky_header_off',0)){
		$inline_css .="
			#header { 
				position: relative; 
				-webkit-transition: 0;
				-moz-transition: 0;
				-ms-transition: 0;
				-o-transition: 0;
				transition: 0;
			}\n";	
		$js_params = array( 'sticky_header' => 0 );
	}else {
		$js_params = array( 'sticky_header' => 1 );
	}
	
	// Nav font family
	if($nav_font!="Novecento"){
		$inline_css .="
			#menu {
				font-family: '".str_replace('+',' ',$nav_font)."';
			}\n";		
	}
	// Nav font size
	if(($nav_font_size=ot_get_option('nav_font_size'))!="16px"){
		$inline_css .="
			#menu > ul > li > a {
				font-size: ".$nav_font_size.";
			}
			#menu > ul > li ul > li > a {
				font-size: ".((int)(substr($nav_font_size,0,2)) - 2).'px'.";
			}\n";
	}
	
	// Custom Menu BGR color
	if(($nav_bgr_color=get_theme_mod('nav_bgr_color'))!="#07bee5"){
		$inline_css .="
		
			.light_menu #menu > ul > li > div { border-top: 2px solid ".$nav_bgr_color."; }
			.light_menu #menu > ul > li:hover > a { border-bottom: 2px solid ".$nav_bgr_color."; }
			.light_menu #menu > ul > li:hover > a.no_border { border-bottom: none; }

			.dark_menu #menu > ul > li > div { border-top: 2px solid ".$nav_bgr_color."; }
			.dark_menu #menu > ul > li:hover > a { border-bottom: 2px solid ".$nav_bgr_color."; }
			.dark_menu #menu > ul > li:hover > a.no_border { border-bottom: none; }
				
			.custom_menu #menu > ul > li ul > li > a:hover { background-color: ".$nav_bgr_color.";}
			.custom_menu #menu > ul > li:hover > a { border-bottom: 2px solid ".$nav_bgr_color."; }
			.custom_menu #menu > ul > li:hover > a.no_border { border-bottom: none; }
				
			.custom_menu2 #menu > ul > li > div { border-top: 2px solid ".$nav_bgr_color.";}
			.custom_menu2 #menu > ul > li:hover > a { border-bottom: 2px solid ".$nav_bgr_color."; }
			.custom_menu2 #menu > ul > li:hover > a.no_border { border-bottom: 2px solid transparent; }
				
			.custom_menu3 #menu > ul > li > div { border-top: 2px solid ".$nav_bgr_color.";}
			.custom_menu3 #menu > ul > li:hover > a { border-bottom: 2px solid ".$nav_bgr_color."; }
			.custom_menu3 #menu > ul > li:hover > a.no_border { border-bottom: none; }
				
			.custom_menu4 #menu > ul > li > div { border-top: 2px solid ".$nav_bgr_color.";}
			.custom_menu4 #menu > ul > li:hover > a { border-bottom: 2px solid ".$nav_bgr_color."; }
			.custom_menu4 #menu > ul > li ul > li > a:hover { background-color: ".$nav_bgr_color.";}
			.custom_menu4 #menu > ul > li:hover > a.no_border { border-bottom: none; }

			.custom_menu5 #menu > ul > li:hover > a { background-color: ".$nav_bgr_color.";}
			.custom_menu5 #menu > ul > li ul > li > a:hover { background-color: ".$nav_bgr_color.";}
				
				
			.custom_menu6 #menu > ul > li ul > li > a:hover { background-color: ".$nav_bgr_color.";}
			.custom_menu6 #menu > ul > li:hover > a { border-top: 2px solid ".$nav_bgr_color.";}
				
			.custom_menu7 #menu > ul > li ul > li > a:hover { background-color: ".$nav_bgr_color.";}
			.custom_menu7 #menu > ul > li:hover > a { border-top: 2px solid ".$nav_bgr_color.";}
				
			.custom_menu8 #menu > ul > li ul > li > a:hover { background-color: ".$nav_bgr_color.";}
			.custom_menu8 #menu > ul > li:hover > a { border-top: 2px solid ".$nav_bgr_color.";}
		
        ";
	}
	
	
	// Main Color
	$aqua_main_color=get_theme_mod('aqua_main_color');
	if(($aqua_main_color)&&($aqua_main_color!="#07bee5")){
		$inline_css .='	
	    	a:hover, a:focus { color:'.$aqua_main_color.'; }
			a.colored, a:visited.colored { color:'.$aqua_main_color.'; }
	    	.button:hover,a:hover.button,button:hover,input[type="submit"]:hover,input[type="reset"]:hover,	input[type="button"]:hover, .button_hilite, a.button_hilite { color: #fff; background-color:'.$aqua_main_color.';}
	    	input.button_hilite, a.button_hilite, .button_hilite { color: #fff; background-color:'.$aqua_main_color.';}
	    	.button_hilite:hover, input.button_hilite:hover, a:hover.button_hilite { color: #fff; background-color: #444444;}

	    	.section_big_title h1 strong, h2 strong, h3 strong { color:'.$aqua_main_color.';}
	    	.section_featured_texts h3 a:hover { color:'.$aqua_main_color.';}

	    	.htabs a.selected  { border-top: 2px solid '.$aqua_main_color.';}
			#s:focus {	border: 1px solid '.$aqua_main_color.';}
	    	
			.breadcrumb a:hover{ color: '.$aqua_main_color.';}

	    	.tagcloud a:hover { background-color: '.$aqua_main_color.';}
	    	.month { background-color: '.$aqua_main_color.';}
	    	.small_month  { background-color: '.$aqua_main_color.';}

	    	.post_meta a:hover{ color: '.$aqua_main_color.';}
			
			.horizontal .resp-tabs-list li.resp-tab-active { border-top: 2px solid '.$aqua_main_color.';}
			.resp-vtabs li.resp-tab-active { border-left: 2px solid '.$aqua_main_color.'; }

	    	#portfolio_filter { background-color: '.$aqua_main_color.';}
	    	#portfolio_filter ul li div:hover { background-color: '.$aqua_main_color.';}
	    	.counter-digit { color: '.$aqua_main_color.';}
	    	
			.tp-caption a:hover { color: '.$aqua_main_color.';}
			
			.more-link:before { background: '.$aqua_main_color.';}

			.iconed_featured_text .icon.accent_color i{  color: '.$aqua_main_color.';}

			h2.title strong {  color: '.$aqua_main_color.';}
			
			.acc_control, .active_acc .acc_control { background-color: '.$aqua_main_color.';}
	    	
			.next:hover,.prev:hover{ background-color: '.$aqua_main_color.';}
			.jcarousel-next-horizontal, .jcarousel-prev-horizontal {	background-color:  '.$aqua_main_color.'; }
			
			.jcarousel-next-horizontal:hover, .jcarousel-prev-horizontal:hover { background-color:  #777; }
			.jcarousel-prev-disabled-horizontal,.jcarousel-next-disabled-horizontal,.jcarousel-prev-disabled-horizontal:hover, .jcarousel-prev-disabled-horizontal:focus, .jcarousel-prev-disabled-horizontal:active,.jcarousel-next-disabled-horizontal:hover, .jcarousel-next-disabled-horizontal:focus, .jcarousel-next-disabled-horizontal:active { background-color: #f3f3f3 }
	    	
			.team_block .team_desc { color: '.$aqua_main_color.';}
			
			.pagination .links a:hover{ background-color: '.$aqua_main_color.';}
	    	.hilite{ background: '.$aqua_main_color.';}
			.price_column.price_column_featured ul li.price_column_title{ background: '.$aqua_main_color.';}

	    	blockquote{ border-left: 4px solid '.$aqua_main_color.'; }
			
			ul.dotted li:before { color: '.$aqua_main_color.'; }
			ul.checked li:before { color: '.$aqua_main_color.'; }
			ul.arrowed li:before { color: '.$aqua_main_color.'; }
			
			.woocommerce .product_meta a { color: '.$aqua_main_color.';}
			.woocommerce div.product .woocommerce-tabs ul.tabs li.active, .woocommerce #content div.product .woocommerce-tabs ul.tabs li.active, .woocommerce-page div.product .woocommerce-tabs ul.tabs li.active, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active { border-top: 2px solid '.$aqua_main_color.' !important; }
			
			.woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button { background-color: '.$aqua_main_color.'!important; }
			.header_cart .cart-wrap	{ background-color: '.$aqua_main_color.'; }
			.header_cart .cart-wrap:before { border-color: transparent '.$aqua_main_color.' transparent; }

	    	.info  h2{ background-color: '.$aqua_main_color.';}
	    	#footer a:hover { color: '.$aqua_main_color.';}
	    	#footer .latest_post_sidebar img:hover { border: 3px solid '.$aqua_main_color.';}
	    	#footer.footer_light .latest_post_sidebar img:hover { border: 1px solid '.$aqua_main_color.'; background: '.$aqua_main_color.';}
		';
		
	}	
	
	// Should we overwrite Portfolio Styles with Bright Blue Color
	$force_savia_blue = ot_get_option('force_savia_blue','on');
	if(($force_savia_blue!='on')&&($aqua_main_color!="#07bee5")){
		$inline_css .='
			a .pic_info.type1 .plus_overlay {	border-bottom: 50px solid '.$aqua_main_color.';}
			a:hover .pic_info.type1 .plus_overlay { border-bottom: 600px solid '.$aqua_main_color.'; }
			
			a .pic_info.type2 .plus_overlay { border-bottom: 50px solid '.$aqua_main_color.'; }
			a:hover .pic_info.type2 .plus_overlay {	border-bottom: 860px solid '.$aqua_main_color.';}
			
			a .pic_info.type3  .img_overlay_icon {	background: '.$aqua_main_color.'; }
			a:hover .pic_info.type3 .img_overlay_icon {	background: '.$aqua_main_color.';}
			
			a .pic_info.type4  .img_overlay_icon { border-bottom: 4px solid '.$aqua_main_color.';}
			
			a:hover .pic_info.type5 .info_overlay {	background: '.$aqua_main_color.';}
			
			.pic_info.type6 .info_overlay {	background: '.$aqua_main_color.';}
			a .pic_info.type6 .plus_overlay { border-bottom: 50px solid '.$aqua_main_color.'; }
}
		';
	}

	// Headings font family
	if($heading_font!="Novecento"){
		$inline_css .="
		h1, h2, h3, h4, h5, .title, .section_big_title h1, .heading, #footer h3, .info_overlay h3, .htabs a, .woocommerce-page div.product .woocommerce-tabs ul.tabs li {
			font-family: '".str_replace('+',' ',$heading_font)."';
		}\n";		
	}	
	// Button font family
	if($button_font!="Novecento"){
		$inline_css .="
		.button, a.button, button, input[type='submit'], input[type='reset'], input[type='button'] {
			font-family: '".str_replace('+',' ',$button_font)."';
		}\n";		
	}	
	// Body font family
	$body_font = ot_get_option('body_font_family');
	if($body_font!="Open+Sans"){
		$inline_css .="
		body {
			font-family: '".str_replace('+',' ',$body_font)."';
		}\n";		
	}	

	// Breadcrumbs
	if(ot_get_option('breadcrumbs','on')!='on'){
		$inline_css .="
		.breadcrumb {
			display: none;
		}\n";
	}

	// Footer Position
	if(!$footer_position = ot_get_option('footer_position')){
		$inline_css .="
		#footer {
			position: relative;
		}\n";
		$footer_js_params = array( 'fixed_footer' => 0 );
	} else {
		$footer_js_params = array( 'fixed_footer' => 1 );
	}
	
	// Custom CSS
	if($boc_custom_css = ot_get_option('custom_css')){
		$inline_css .="\n\n".$boc_custom_css."\n";
	}	
		
    wp_add_inline_style( 'boc-style', $inline_css );
}

add_action( 'wp_enqueue_scripts', 'boc_style' );



add_action( 'wp_enqueue_scripts', 'boc_scripts' );	
// Enqueue Scripts
function boc_scripts() {
	global $js_params;
	global $footer_js_params;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery.easing', get_template_directory_uri().'/js/libs.js');
	wp_enqueue_script('savia.common', get_template_directory_uri().'/js/common.js');
	wp_localize_script('savia.common', 'JSParams', $js_params );
	wp_localize_script('savia.common', 'JSFooterParams', $footer_js_params );
	 
	wp_enqueue_script('smoothscroll', get_template_directory_uri().'/js/jquery.smoothscroll.js');
	wp_enqueue_script('stellar'	 , get_template_directory_uri().'/js/jquery.stellar.min.js');
	 
	/* UnComment to fix HTML5 for IE older than 9
	add_action( 'wp_head', function() {
		echo '<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
	});	 
  	*/
	
	// Load Animated Rows
	$animated_containers = ot_get_option('animated_containers','on');
	if($animated_containers == 'on'){
		 wp_enqueue_script('anim-script', get_template_directory_uri().'/js/anims.js',array('jquery','jquery.easing','common'));
		 wp_localize_script( 'anim-script', 'template_dir_uri', get_template_directory_uri() );
	}
	
	// Loading Product ZOOM resources
	$savia_product_zoom = ot_get_option('savia_product_zoom', 'on');
	if ($savia_product_zoom == 'on') { 
		wp_enqueue_style( 'zoom-style', get_template_directory_uri().'/stylesheets/jquery.jqzoom.css' );
		wp_enqueue_script('zooming', 	get_template_directory_uri().'/js/jquery.jqzoom-core.js');	
	}	 
	
}
 
 
add_action( 'comment_form_before', 'xtreme_enqueue_comments_reply' );
// Correctly enqueue the comment-reply script
function xtreme_enqueue_comments_reply() {
	if( get_option( 'thread_comments' ) )  {
		wp_enqueue_script( 'comment-reply' );
	}
}

	 

	 
// Register Navigation
add_theme_support('menus');
register_nav_menu('main_navigation', 'Main Navigation');


// Custom functions + Widgets
require_once( 'includes/boc_custom.php' );
require_once( 'includes/boc_widgets.php' );

add_action('widgets_init', 'boc_load_widgets');


// Make theme available for translation
load_theme_textdomain( 'Savia', get_template_directory() . '/languages' );


// Images
add_theme_support('post-thumbnails');

set_post_thumbnail_size(640, 300, true); //size of thumbs
add_image_size('small-thumb', 60, 60, true);
add_image_size('portfolio-medium', 460, 290, true);
add_image_size('portfolio-slim', 940, 460, true);
add_image_size('portfolio-full', 1200, 780, true);

function wpex_clean_shortcodes($content){   
    $array = array (
        '<p>[' => '[', 
        ']</p>' => ']', 
        ']<br />' => ']'
    );
    $content = strtr($content, $array);
    return $content;
}
add_filter('the_content', 'wpex_clean_shortcodes');


// Register widgetized locations
if(function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Savia Default Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="left_title"><span>',
		'after_title' => '</span></h4>',
	));

	register_sidebar(array(
		'name' => 'Footer Widget 1',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name' => 'Footer Widget 2',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name' => 'Footer Widget 3',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name' => 'Footer Widget 4',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	register_sidebar(array(
		'name' => 'Savia Contact Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="left_title"><span>',
		'after_title' => '</span></h4>',
	));	

	register_sidebar(array(
		'name' => 'WooCommerce Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="left_title"><span>',
		'after_title' => '</span></h4>',
	));	
	register_sidebar(array(
		'name' => 'WooCommerce Product Page Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="left_title"><span>',
		'after_title' => '</span></h4>',
	));		
	
}


add_action('after_setup_theme', 'after_setup_savia', 2);

function after_setup_savia(){
	boc_register_dynamic_widgets();
	boc_add_buttons();
	add_filter( 'option_posts_per_page', 'my_option_posts_per_page' );
}

// Register Dynamic Widgets (OT)
function boc_register_dynamic_widgets(){
	if (ot_get_option('boc_sidebars')){
		$dynamic_sidebars = ot_get_option('boc_sidebars');
		foreach ($dynamic_sidebars as $dynamic_sidebar) {
			register_sidebar(array(
				'name' => $dynamic_sidebar["title"],
				'id' => $dynamic_sidebar["id"],
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="left_title"><span>',
				'after_title' => '</span></h4>',
				));
		}
	}
}


// Register custom post types
add_action('init', 'boc_custom_types');
function boc_custom_types() {
	register_post_type(
		'portfolio',
		array(
			'labels' => array(
				'name' => 'Portfolio',
				'singular_name' => 'Portfolio'
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'portfolio_item'),
			'supports' => array('title', 'editor', 'thumbnail'),
			'can_export' => true,
			'show_in_nav_menus' => true,
		)
	);

	register_taxonomy('portfolio_category', 'portfolio', array('hierarchical' => true, 'label' => 'Portfolio Categories', 'query_var' => true, 'rewrite' => true));
}



// Register the Custom Templates for the Custom Post Type Portfolio
function my_cpt_post_types( $post_types ) {
	$post_types = array();
	$post_types[] = 'portfolio';
	return $post_types;
}
add_filter( 'cpt_post_types', 'my_cpt_post_types' );


/**
 * add a default-gravatar to options
 */
if ( !function_exists('fb_addgravatar') ) {
	function fb_addgravatar( $avatar_defaults ) {
		$myavatar = get_template_directory_uri() . '/images/comment_avatar.png';
		$avatar_defaults[$myavatar] = 'people';
		return $avatar_defaults;
	}
	add_filter( 'avatar_defaults', 'fb_addgravatar' );
}


// BOC Shortcodes
include_once( 'includes/shortcodes.php' );

// Use shortcodes in Widgets
add_filter('widget_text', 'do_shortcode');

// Customize Tag Cloud
function my_tag_cloud_args($in){
    return 'smallest=13&largest=13&number=25&orderby=name&unit=px';
}
add_filter( 'widget_tag_cloud_args', 'my_tag_cloud_args');


// Customize Items per page for Portfolio Taxonomy
$option_posts_per_page = get_option( 'posts_per_page' );

function my_option_posts_per_page( $value ) {
    global $option_posts_per_page;
    if ( is_tax( 'portfolio_category') ) {
        return (ot_get_option('portfolio_items_per_page',9) ? ot_get_option('portfolio_items_per_page',9) : 9);
    } else {
        return $option_posts_per_page;
    }
}



///////////////////////////////////////////
// ----------   WooCommerce  ----------- //
///////////////////////////////////////////

add_theme_support( 'woocommerce' );

function savia_close_div() {
    echo '</div>';
}

add_filter('add_to_cart_fragments', 'add_to_cart_fragment');
function add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	$fragments['a.cart-parent'] = ob_get_clean();
	return $fragments;
}


//wrap single product image in an extra div
add_action( 'woocommerce_before_single_product_summary', 'images_div', 2);
add_action( 'woocommerce_before_single_product_summary',  'savia_close_div', 20);
function images_div()
{
	echo "<div class='five columns alpha single_product_left'>";
}

//wrap product description
add_action( 'woocommerce_before_single_product_summary', 'summary_div', 35);
add_action( 'woocommerce_after_single_product_summary',  'savia_close_div', 4);
function summary_div() {
	echo "<div class='seven columns single_product_right omega'>";
}

//change tab position to be inside summary
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
add_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 1);	




// Show upsells and related products
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products',20);
remove_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products',10);



remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display',10);
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_upsells', 21);

function woocommerce_output_upsells() {

	$output = null;

	ob_start();
	woocommerce_upsell_display(4,4); 
	$content = ob_get_clean(); 
	if($content) { $output .= $content; }

	echo $output;
}



add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	
	ob_start(); ?>
	<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>">
		<div class="cart-icon-wrap">
			<span class="savia_icon_cart"></span>
			<div class="cart-wrap"><span><?php echo $woocommerce->cart->cart_contents_count; ?> </span></div>
		</div>
	</a>
	<?php
	
	$fragments['a.cart-contents'] = ob_get_clean();
	
	return $fragments;
}


add_action( 'woocommerce_before_single_product', 'wrap_single_product_image', 8);
add_action( 'woocommerce_after_single_product', 'savia_close_div', 9);

function wrap_single_product_image() {

	echo "<div class='boc_single_product'>";
}



// Display products per page.	
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );


//add link to item titles
add_action('woocommerce_before_shop_loop_item_title','product_item_title_link_open');
add_action('woocommerce_after_shop_loop_item_title','product_item_title_link_close');
function product_item_title_link_open(){
	echo '<a href="'.get_permalink().'">';
}
function product_item_title_link_close(){
	echo '</a>';
}


