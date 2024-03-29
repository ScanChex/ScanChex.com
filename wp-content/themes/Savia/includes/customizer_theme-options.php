<?php

function aqua_customize_register($wp_customize) {

	$wp_customize->add_section( 'main_menu_styles_section', array(
	    'title'          => __( 'Main Menu Styles', 'Savia' ),
	    'priority'       => 35,
	));	
	
  $wp_customize->add_setting( 'aqua_main_color', array(
    'default'        => '#07bee5',
    'transport' =>'postMessage',
    'priority'       => 1, 
    ));
  $wp_customize->add_setting( 'main_menu_style', array(
    'default'        => 'light_menu',
    'transport' =>'postMessage',
    'priority'       => 1, 
    ));

  $wp_customize->add_setting( 'nav_bgr_color', array(
    'default'        => '#07bee5',
    'transport' =>'postMessage',
    'priority'       => 3, 
    ));
    
    

  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqua_main_color', array(
    'label'   => 'Main Color (Default: #07bee5)',
    'section' => 'colors',
    'settings'   => 'aqua_main_color',
    )));
    
	
    $wp_customize->add_control( 'main_menu_style', array(
	    'label'   => 'Select Navigation Style Preset:',
	    'section' => 'main_menu_styles_section',
	    'type'    => 'select',
	    'choices'    => array(
    	    'light_menu' => 'Light Menu',
	        'dark_menu' => 'Dark Menu',
	        'custom_menu' => 'Custom Menu1',
	        'custom_menu2' => 'Custom Menu2',
	        'custom_menu3' => 'Custom Menu3',
	        'custom_menu4' => 'Custom Menu4',
	        'custom_menu5' => 'Custom Menu5',
	        'custom_menu6' => 'Custom Menu6',
			'custom_menu7' => 'Custom Menu7',		
			'custom_menu8' => 'Custom Menu8',				
	        ),
	));       
    
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_bgr_color', array(
    'label'   => 'Navigation Accent Color',
    'section' => 'main_menu_styles_section',
    'settings'   => 'nav_bgr_color',
    )));    
    
    
    
  // Get it on in preiview  
  if ( $wp_customize->is_preview() && ! is_admin() )
    add_action( 'wp_footer', 'aqua_customize_preview', 21);
}


function aqua_customize_preview() {
?>  
    
    <script type="text/javascript">

    function convertHex(hex,opacity){
        hex = hex.replace('#','');
        r = parseInt(hex.substring(0,2), 16);
        g = parseInt(hex.substring(2,4), 16);
        b = parseInt(hex.substring(4,6), 16);

        result = 'rgba('+r+','+g+','+b+','+opacity/100+')';
        return result;
    }

    
    ( function( $ ){
	    wp.customize('aqua_main_color',function( value ) {
	    	value.bind(function(to) {
	    		$('#footer').append('<style type="text/css">'+
	    			'	#wrapper { border-top: 3px solid '+ convertHex(to, 40) +'; }' +
	    			'	a:hover, a:focus { color:'+ to +'; }' +
	    			'	.button:hover,a:hover.button,button:hover,input[type="submit"]:hover,input[type="reset"]:hover,	input[type="button"]:hover, .button_Accent, a.button_Accent { color: #fff; background-color:'+ to +';}'+
	    			'	input.button_Accent, a.button_Accent, .button_Accent { color: #fff; background-color:'+ to +';}'+
	    			'	.button_Accent:hover, a:hover.button_Accent { color: #fff; background-color: #374045;}'+
	    			
	    			'	.section_big_title h1 strong { color:'+ to +';}'+
	    			'	a:hover .pic_info.type2 .info_overlay { border-bottom: 1px solid '+ to +';}'+
	    			'	.section_featured_texts h3 a:hover { color:'+ to +';}'+

	    			'	.htabs a.selected  { border-top: 1px solid '+ to +';}'+
					'	#s:focus {	border: 1px solid '+ to +';}'+

	    				    			
	    		    '   .breadcrumb a:hover{ color: '+ to +';}'+

	    		    '   .tagcloud a:hover { background-color: '+ to +';}'+
	    		    '   .month { background-color: '+ to +';}'+
	    		    '   .small_month  { background-color: '+ to +';}'+


	    		    
	    		    '   .post_meta a:hover{ color: '+ to +';}'+
	    		    '   h2.title strong { color: '+ to +';}'+

	    		    '   #portfolio_filter ul li div:hover{ background-color: '+ to +';}'+
	    		    '   .counter-digit { color: '+ to +';}'+	    		    
	    		    '   .iconed_featured_text .icon.accent_color i{  color: '+ to +';}'+	
	    		   
	    		    '   .next:hover,.prev:hover{ background-color: '+ to +';}'+
	    		    '   .pagination .links a:hover{ background-color: '+ to +';}'+
	    		    '   .Accent{ background: '+ convertHex(to, 30) +';}'+
					'   .price_column.price_column_featured ul li.price_column_title{ background: '+ to +';}'+

	    			'	blockquote{ border-left: 4px solid '+ to +'; }' +
		    		   
	    		    '   .info  h2{ background-color: '+ to +';}'+
	    		    '   #footer a:hover { color: '+ to +';}'+
	    		    '   #footer .boc_latest_post img:hover { border: 3px solid '+ to +';}'+
	    		    '   #footer.footer_light .boc_latest_post img:hover { border: 1px solid '+ to +'; background: '+ to +';}'+

		 	'</style>');
	    	 });
	    });

	    wp.customize('main_menu_style',function( value ) {
	        value.bind(function(to) {
	        	$('#menu').parent().removeClass('custom_menu').removeClass('custom_menu2').removeClass('custom_menu3').removeClass('custom_menu4').removeClass('custom_menu5').removeClass('custom_menu6').removeClass('custom_menu7').removeClass('custom_menu8').removeClass('light_menu').removeClass('dark_menu').addClass(to);
	        });
	    });

	    wp.customize('nav_bgr_color',function( value ) {
	        value.bind(function(to) {
	        	$('#footer').append('<style type="text/css">'+
					'   .light_menu #menu > ul > li > div { border-top: 2px solid '+ to +'; }' +
					'   .light_menu #menu > ul > li:hover > a { border-bottom: 2px solid '+ to +'; }' +
					'   .light_menu #menu > ul > li:hover > a.no_border { border-bottom: none; }' +

					'   .dark_menu #menu > ul > li > div { border-top: 2px solid '+ to +'; }' +        
					'   .dark_menu #menu > ul > li:hover > a { border-bottom: 2px solid '+ to +'; }' +
					'   .dark_menu #menu > ul > li:hover > a.no_border { border-bottom: none; }' +					
					
					'   .custom_menu #menu > ul > li ul > li > a:hover { background-color: '+ to +';}'+
					'   .custom_menu #menu > ul > li:hover > a { border-bottom: 2px solid '+ to +'; }' +
					'   .custom_menu #menu > ul > li:hover > a.no_border { border-bottom: none; }' +			
					
	        		'   .custom_menu2 #menu > ul > li > div { border-top: 2px solid '+ to +';}'+	
					'   .custom_menu2 #menu > ul > li:hover > a { border-bottom: 2px solid '+ to +'; }' +
					'   .custom_menu2 #menu > ul > li:hover > a.no_border { border-bottom: 2px solid transparent; }' +	
	        		
	        		'	.custom_menu3 #menu > ul > li > div { border-top: 2px solid '+ to +';}'+	
					'   .custom_menu3 #menu > ul > li:hover > a { border-bottom: 2px solid '+ to +'; }' +
					'   .custom_menu3 #menu > ul > li:hover > a.no_border { border-bottom: none; }' +	
	        		
	        		'	.custom_menu4 #menu > ul > li > div { border-top: 2px solid '+ to +';}'+	
					'   .custom_menu4 #menu > ul > li:hover > a { border-bottom: 2px solid '+ to +'; }' +
					'   .custom_menu4 #menu > ul > li ul > li > a:hover { background-color: '+ to +';}'+
					'   .custom_menu4 #menu > ul > li:hover > a.no_border { border-bottom: none; }' +	

	        		'   .custom_menu5 #menu > ul > li:hover > a { background-color: '+ to +';}'+
					'   .custom_menu5 #menu > ul > li ul > li > a:hover { background-color: '+ to +';}'+
					
					
					'   .custom_menu6 #menu > ul > li ul > li > a:hover { background-color: '+ to +';}'+
					'   .custom_menu6 #menu > ul > li:hover > a { border-top: 2px solid '+ to +';}'+
					
					'   .custom_menu7 #menu > ul > li ul > li > a:hover { background-color: '+ to +';}'+
					'   .custom_menu7 #menu > ul > li:hover > a { border-top: 2px solid '+ to +';}'+
					
					'   .custom_menu8 #menu > ul > li ul > li > a:hover { background-color: '+ to +';}'+
					'   .custom_menu8 #menu > ul > li:hover > a { border-top: 2px solid '+ to +';}'+
	        		
			 	'</style>');
	        });
	    });     

	} )( jQuery )
    </script>
    <?php 
}