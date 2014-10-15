<?php get_header(); ?>

<div class="content_bgr">

<?php	// Shall we display the page heading
		
		if(is_shop() || is_product_category() || is_product_tag()) {
			$page_post_id = woocommerce_get_page_id('shop');	
		}else {
			$page_post_id = $post->ID;
		}
		
		$hide_heading = get_post_meta($page_post_id, 'boc_page_heading_set', true);
		if($hide_heading!=='yes') {
?>
	
		<div class="full_container_page_title">
			<div class="container startNow animationStart">
				<div class="row no_bm">
					<div class="sixteen columns">
						<?php boc_breadcrumbs(); ?>
						<div class="page_heading"><h1><?php echo (is_archive() ? _e('Shop', 'Savia') : (is_search() ? _e('Search results for:', 'Savia').' '. get_search_query(): (is_home() ? wp_title('') : the_title()) ));?></h1></div>
					</div>
				</div>
			</div>
		</div>
	
	
<?php 	} ?>



<?php 

//change to 3 columns per row when using sidebar
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}


$rel_products = array(4,4);
// Method that outputs related products, takes in an array of 2 numbers, total products + products per row
function boc_woocommerce_output_related_products() {

	global $rel_products;
	$output = null;

	ob_start();
	woocommerce_related_products($rel_products[0], $rel_products[1]);  // Display 4 products in rows of 4
	$content = ob_get_clean();
	if($content) { $output .= $content; }

	echo '<div class="clear"></div>' . $output;	
}

?>

<div class="container">		
	<div class="row">
				
			<?php			
			
			$woocommerce_layout = ot_get_option( 'woocommerce_sidebar_layout', 'no-sidebar' );
			$single_product_layout = ot_get_option( 'woocommerce_single_product_sidebar_layout', 'no-sidebar' );

			//single product layout
			if(is_product()){	
				
				if($single_product_layout == 'right-sidebar' || $single_product_layout == 'left-sidebar'){
					add_filter('loop_shop_columns', 'loop_columns');
				}
				
				switch($single_product_layout) {
					case 'no-sidebar':
						$rel_products = array(4,4);
						add_action( 'woocommerce_after_single_product_summary', 'boc_woocommerce_output_related_products', 20);
						
						echo '<div class="sixteen columns col_16 woo_content">';
							woocommerce_content();
						echo '</div><!--columns::end-->';
						break;
					case 'right-sidebar':
						$rel_products = array(3,3);
						add_action( 'woocommerce_after_single_product_summary', 'boc_woocommerce_output_related_products', 20);
						echo '<div class="twelve columns col_12 woo_content">';
							woocommerce_content();
						echo '</div><!--columns::end-->';
						
						echo '<!-- WooSidebar -->
							  <div id="sidebar" class="four columns sidebar">';
						if ( ! dynamic_sidebar('WooCommerce Product Page Sidebar') ) : ?>
							<h4 class="left_title">WooCommerce Product Page Sidebar</h4>
							<p><a href="<?php echo admin_url('widgets.php'); ?>">Assign a widget to this area now.</a></p>	
				<?php	endif;
						echo '</div><!-- WooSidebar :: End -->';
						break;
					case 'left-sidebar':
						$rel_products = array(3,3);
						add_action( 'woocommerce_after_single_product_summary', 'boc_woocommerce_output_related_products', 20);
						echo '<!-- WooSidebar -->
							  <div id="sidebar" class="four columns sidebar">';
						if ( ! dynamic_sidebar('WooCommerce Product Page Sidebar') ) : ?>
							<h4 class="left_title">WooCommerce Product Page Sidebar</h4>
							<p><a href="<?php echo admin_url('widgets.php'); ?>">Assign a widget to this area now.</a></p>	
				<?php	endif;
						echo '</div><!-- WooSidebar :: End -->';
						
						echo '<div class="twelve columns col_12 woo_content">';
							woocommerce_content();
						echo '</div><!--columns::end-->';
						break;
					default:
						$rel_products = array(4,4);
						add_action( 'woocommerce_after_single_product_summary', 'boc_woocommerce_output_related_products', 20);
						echo '<div class="sixteen columns col_16 woo_content">';
							woocommerce_content();
						echo '</div><!--columns::end-->';
						break;
				}
			}
			
			//Main Shop page layout 
			elseif(is_shop() || is_product_category() || is_product_tag()) {
			
			
				// If "woo_static_top_content" is set in the Theme Options - Show it!
				if($woo_static_top_content = ot_get_option('woo_static_top_content',0)) {
					echo '<div class="sixteen columns" style="margin-bottom: 30px;">';
					echo do_shortcode_boc($woo_static_top_content);
					echo '</div><!--Woo Static Top Content :: end-->';
				}
			
				
				if($woocommerce_layout == 'right-sidebar' || $woocommerce_layout == 'left-sidebar'){ 
					add_filter('loop_shop_columns', 'loop_columns');
				}

				switch($woocommerce_layout) {
					case 'no-sidebar':
						echo '<div class="sixteen columns col_16 woo_content">';
							woocommerce_content();
						echo '</div><!--columns::end-->';
						break;
					case 'right-sidebar':
						echo '<div class="twelve columns col_12 woo_content">';
							woocommerce_content();
						echo '</div><!--columns::end-->';
						
						echo '<!-- WooSidebar -->
							  <div id="sidebar" class="four columns sidebar">';
						if ( ! dynamic_sidebar('WooCommerce Sidebar') ) : ?>
							<h4 class="left_title">WooCommerce Sidebar</h4>
							<p><a href="<?php echo admin_url('widgets.php'); ?>">Assign a widget to this area.</a></p>	
				<?php	endif;
						echo '</div><!-- WooSidebar :: End -->';
						break; 						
					case 'left-sidebar':
						echo '<!-- WooSidebar -->
							  <div id="sidebar" class="four columns sidebar">';
						if ( ! dynamic_sidebar('WooCommerce Sidebar') ) : ?>
							<h4 class="left_title">WooCommerce Sidebar</h4>
							<p><a href="<?php echo admin_url('widgets.php'); ?>">Assign a widget to this area.</a></p>	
				<?php	endif;
						echo '</div><!-- WooSidebar :: End -->';
						
						echo '<div class="twelve columns col_12 woo_content">';
							woocommerce_content();
						echo '</div><!--columns::end-->';
						break; 
					default: 
						echo '<div class="sixteen columns col_16 woo_content">';
							woocommerce_content();
						echo '</div><!--columns::end-->';
						break; 
				}

			}
			
			//regular WooCommerce page layout 
			else {
				echo '<div class="sixteen columns">';
					woocommerce_content();
				echo '</div><!--columns::end-->';
			}
			
			?>

			
		</div><!--row::end-->
	</div><!-- container::end-->

</div>

<?php get_footer(); ?>