<?php
/**
 * Template Name: Contact Page
 *
 * A Fully Operational Contact Page
 * @package WordPress
 */

get_header(); ?>

<div class="content_bgr">

<?php /*	// Shall we display the page heading
		$hide_heading = get_post_meta($post->ID, 'boc_page_heading_set', true);
		if($hide_heading!=='yes') {
?>
		<div class="full_container_page_title">	
			<div class="container animationStart">		
				<div class="row no_bm">
					<div class="sixteen columns">
						<?php boc_breadcrumbs(); ?>
						<div class="page_heading"><h1><?php the_title(); ?></h1></div>
					</div>		
				</div>
			</div>
		</div>
<?php 	} */ ?>


<?php

if(isset($_POST['submit'])) {

	if(trim($_POST['comment_name']) == '') {
		$hasError = true;
	} else {
		$name = trim($_POST['comment_name']);
	}

	if(trim($_POST['subject']) == '') {
		$hasError = true;
	} else {
		$subject = trim($_POST['subject']);
	}

	if(trim($_POST['email']) == '')  {
		$hasError = true;
	} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
		$hasError = true;
	} else {
		$email = trim($_POST['email']);
	}

	if(trim($_POST['comment']) == '') {
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$comments = stripslashes(trim($_POST['comment']));
		} else {
			$comments = trim($_POST['comment']);
		}
	}
	
	
	// Send mail if no Errors
	if(!isset($hasError)) {		$emailTo = ot_get_option('contact_page_email');		if (!isset($emailTo) || ($emailTo == '') ){			$emailTo = get_option('admin_email');		}

		$body = "Name: $name \n\nEmail: $email \n\nSubject: $subject \n\nComments:\n $comments";
		$headers = 'From: '.get_bloginfo('name').' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

		$emailSent = wp_mail($emailTo, $subject, $body, $headers);
	}
}
?>
	<?php if(ot_get_option('gmaps_address')): ?>

	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script> 
	
	<div>

			
				<div id="map_canvas" style="width:100%; height: 460px;"></div>
				
				<script type="text/javascript">
					  var geocoder;
					  var map;
					  var address = '<?php echo ot_get_option('gmaps_address'); ?>';
					  function initialize() {
						geocoder = new google.maps.Geocoder();					
						var myOptions = {
							zoom: <?php echo ot_get_option('gmaps_zoom',14); ?>,
							scrollwheel: false,
							styles:  [
    {
        "featureType": "water",
        "stylers": [
            {
                "color": "#41d1f0"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
    "featureType": "landscape.man_made",
    "elementType": "geometry.fill",
    "stylers": [
      { "visibility": "on" },
      { "hue": "#00c3ff" },
      { "saturation": -100 },
      { "lightness": 40 }
    ]
  }
],
							mapTypeControl: true,
							mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
							navigationControl: true,
							mapTypeId: google.maps.MapTypeId.ROADMAP
						};
						map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
						if (geocoder) {
						  geocoder.geocode( { 'address': address}, function(results, status) {
							if (status == google.maps.GeocoderStatus.OK) {
							  if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
							  map.setCenter(results[0].geometry.location);

								var infowindow = new google.maps.InfoWindow(
									{ content: '<b>'+address+'</b>',
									  size: new google.maps.Size(150,50)
									});
						
								var marker = new google.maps.Marker({
									position: results[0].geometry.location,
									map: map, 
									title:address,
									icon: '<?php echo get_template_directory_uri(); ?>/images/custom_marker1.png',
									animation: google.maps.Animation.DROP
									
								}); 
								google.maps.event.addListener(marker, 'click', function() {
									infowindow.open(map,marker);
								});

							  } else {
								alert("No results found");
							  }
							} else {
							  alert("Geocode was not successful for the following reason: " + status);
							}
						  });
						}
					  }

					jQuery( document ).ready(function() {
							initialize();
					});

				</script>				
	</div>	

	<?php endif; ?>


	<div class="container animationStart startNow">	
	  <div class="row padded_block">
		<div class="two-thirds column">
		
		<?php while(have_posts()): the_post(); ?>
			<div id="post-<?php the_ID(); ?>">

				<h2 class="left_title"><span><?php _e('Contact Form', 'Savia'); ?></span></h2>
				
				<?php the_content(); ?>
				
				<?php if(isset($hasError)) { //If errors are found ?>
					<div class="warning_msg closable"><?php _e("Please make sure all fields are correctly filled in!", 'Savia'); ?></div>
				<?php } ?>

				<?php if(isset($emailSent) && $emailSent == true) { //If email is sent ?>
					<div class="success closable"><?php _e('Your email was successfully sent! Thank you for contacting us!', 'Savia'); ?></div>
				<?php } ?>
				
				<div class="h10"></div>
	
				<form action="" method="post" class="boc_form">
					<p>

						<input id="comment_name" class="aqua_input" name="comment_name" type="text" value="" placeholder="<?php _e('Name', 'Savia'); ?>">
					</p>
					<p>	

						<input id="email" class="aqua_input" name="email" type="text" value=""  placeholder="<?php _e('Email', 'Savia'); ?>">
					</p>
					<p>	
		
						<input id="subject" class="aqua_input" name="subject" type="text" value=""  placeholder="<?php _e('Subject', 'Savia'); ?>">
					</p>
					<p>		
						
						<textarea id="comment" rows="8" class="aqua_input" name="comment"  placeholder="<?php _e('Message', 'Savia'); ?>"></textarea>
					</p>
					<p class="form-submit">
						<input name="submit" type="submit" id="submit" value="<?php _e('Send', 'Savia'); ?>" class="button_hilite button regular_text tiny_button">
					</p>						
				</form>			
				<p>&nbsp;</p>
				<p>&nbsp;</p>
			
			
		</div>
		<?php endwhile; ?>
	  </div>

		<div class="one-third column">
			<?php if ( ! dynamic_sidebar('Savia Contact Sidebar') ) : ?>
			<?php endif; // end sidebar widget area ?>
		</div>
 
     </div> 
  </div>
</div>
<?php get_footer(); ?>