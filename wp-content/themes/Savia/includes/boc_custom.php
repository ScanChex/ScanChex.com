<?php
/* boc_breadcrumbs function: Building the BreadCrumbs.
 * @author Kal 
 * @link http://blueowlcreative.com
 */
 
function boc_breadcrumbs() {
        global $post;
        echo '<div class="breadcrumb">';
        
        if ( !is_front_page() ) {
	        echo '<a class="first_bc" href="';
	        echo home_url('/');
	        echo '"><span>'.__('Home','Savia');
	        echo "</span></a>";
        }
        
        if (is_category() && !is_singular('portfolio')) {
        	$current_cat = get_category(get_query_var('cat'),false);
            $parents_links = get_category_parents($current_cat->cat_ID, TRUE, '', FALSE );

            //Attach <span> to links      
            $parents_links = preg_replace("/(<a\s*href[^>]+>)/", "$1".'<span>', $parents_links);
            $parents_links = str_lreplace("<a href","<a class='last_bc' href", $parents_links);
            $parents_links = str_replace("</a>", "</span></a>", $parents_links);
            
         	echo $parents_links;
        }        
        
		if(function_exists('is_woocommerce') && is_woocommerce()) {
			// For categories and home shop etc
			if(function_exists('is_shop') && is_shop()) {
				echo "<a class='last_bc' href='". get_permalink( woocommerce_get_page_id( 'shop' ) )."'><span>".__('Shop', 'Savia')."</span></a>";
			}else{
				echo "<a href='". get_permalink( woocommerce_get_page_id( 'shop' ) )."'><span>".__('Shop', 'Savia')."</span></a>";
			}	
		}		
		
        if (is_tax()) {
        	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			echo '<a class="last_bc" href="' . get_term_link($term) . '" title="' . $term->name . '"><span>' . $term->name . '</span></a>';
        }        
        
		if(is_singular('portfolio')) {
	
			$taxonomy = 'portfolio_category';
			$terms = get_the_terms( $post->ID , $taxonomy );

			if (! empty( $terms ) ) :
				foreach ( $terms as $term ) {
					
					$link = get_term_link( $term, $taxonomy );
					if ( !is_wp_error( $link ) )
						echo '<a href="' . $link . '"><span>' . $term->name . '</span></a>';
				}
			endif;
        }


        if(is_home()) {
        	echo '<a class="last_bc" href="#" title="' . wp_title('',false) . '"><span>' . wp_title('',false) . '</span></a>';
        }
		

        if(is_page() && !is_front_page()) {
            $parents = array();
            $parent_id = $post->post_parent;
            while ( $parent_id ) :
                $page = get_page( $parent_id );
                $parents[]  = '<a href="' . get_permalink( $page->ID ) . '" title="' . get_the_title( $page->ID ) . '"><span>' . get_the_title( $page->ID ) . '</span></a>';
                $parent_id  = $page->post_parent;
            endwhile;
            $parents = array_reverse( $parents );
            echo join( ' ', $parents );
            echo '<a class="last_bc" href="' . get_permalink() . '" title="' . get_the_title() . '"><span>' . get_the_title(). '</span></a>';
        }
        
        if(is_single()) {
	        $args=array('orderby' => 'none');
			$terms = wp_get_post_terms( $post->ID , 'category', $args);
			foreach($terms as $term) {
			  echo '<a href="' . esc_attr(get_term_link($term, 'category')) . '" title="' . get_the_title() . '" ' . '><span>' . $term->name.'</span></a> ';
			}

            echo '<a class="last_bc" href="' . get_permalink() . '" title="' . get_the_title() . '"><span>' . get_the_title(). '</span></a>';
        }
        
        if(is_tag()){ echo '<a class="last_bc" href="#"><span>'."Tag: ".single_tag_title('',FALSE).'</span></a>'; }
        if(is_404()){ echo '<a class="last_bc" href="#"><span>'.__("404 - Page not Found", 'Savia').'</span></a>'; }
        if(is_search()){ echo '<a class="last_bc" href="#"><span>'.__("Search", 'Savia').'</span></a>'; }
        if(is_year()){ echo '<a class="last_bc" href="#"><span>'.get_the_time('Y').'</span></a>'; }

        echo "</div>";
}


// Replace last occurence
function str_lreplace($search, $replace, $subject)
{
    return preg_replace('~(.*)' . preg_quote($search, '~') . '(.*?)~', '$1' . $replace . '$2', $subject, 1);
}




/* Walker Class for Adding: Home Icon, <span> tag to Menu items with children.
 * @author BOC 
 * @link http://blueowlcreative.com
 */

class boc_Menu_Walker extends Walker_Nav_Menu
{
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output )
    {
        $id_field = $this->db_fields['id'];
        if ( is_object( $args[0] ) ) {
            $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        }
        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

    function start_el(&$output, $object, $depth = 0, $args = array(), $current_object_id = 0) {
        if ( $args->has_children ) {
        	$args->link_after = '<span></span>';
        }else {
        	$args->link_after = NULL;
        }
        parent::start_el($output, $object, $depth, $args, $current_object_id); 
    }
}

// Walker for Select Menu
class boc_Menu_Select_Walker extends Walker_Nav_Menu {
	
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output )
    {
        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
    
    function start_el(&$output, $object, $depth = 0, $args = array(), $current_object_id = 0) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "&nbsp;&nbsp;&nbsp;&nbsp;", $depth ) : '';
		$output .= '<option value="'.(!empty( $item->url ) ? esc_attr( $item->url ) : '').'">'. $indent . apply_filters( 'the_title', $item->title, $item->ID )."</option>\n";
    }
}


// Walker for Fallback of Select Menu
class boc_Menu_Select_Fallback_Walker extends Walker_Page {
	
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output )
    {
        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

    function start_el(&$output, $object, $depth = 0, $args = array(), $current_object_id = 0)  {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "&nbsp;&nbsp;&nbsp;&nbsp;", $depth ) : '';
		$output .= '<option value="'.get_permalink($page->ID).'">'. $indent . apply_filters( 'the_title', $page->post_title, $page->ID )."</option>\n";
    }
}



/* menuCallBack function: Fall Back to the main navigation "wp_nav_menu" (when Menu not selected in Admin).
 * @author Kal 
 * @link http://blueowlcreative.com
 */
function menuFallBack(){
	
	echo '<div id="menu"><ul>';
	wp_list_pages(
      array(
        'title_li'  => '',
      	'sort_column'=> 'menu_order',
      )
    );
    echo '</ul></div>';

}

/* menuSelectFallBack function: Fall Back to the main navigation "wp_nav_menu" (when Menu not selected in Admin).
 * @author Kal 
 * @link http://blueowlcreative.com
 */
function menuSelectFallBack(){

	echo '<select id="select_menu" onchange="location = this.value">';
	echo '<option value="">Select Page</option>';
	wp_list_pages(
      array(
        'title_li'  => '',
      	'sort_column'=> 'menu_order',
      	'walker'     => new boc_Menu_Select_Fallback_Walker,
      )
    );
    echo '</select>'; 		
}


// Remove span from title
function removeSpanFromTitle($title){
		
	$title = str_replace('<span>','',$title);
	$title = str_replace('</span>','',$title);
		
	return $title;
	
}



// Comments
function boc_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<?php $add_below = ''; ?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
	
		<div class="single_comment">
			<div class="comment_avatar">
				<div class="avatar">
					<?php echo get_avatar($comment, 50); ?>
				</div>
				<?php edit_comment_link(__('Edit','Savia'),'  ','') ?>
			</div>
			<div class="comment_content">
			
				<div class="comment-author meta">
					<div class="comment_name"><?php echo get_comment_author_link() ?><span>-</span><?php comment_reply_link(array_merge( $args, array('reply_text' => 'Reply', 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
					<div class="comment_desc"><?php printf(__('%1$s at %2$s', 'Savia'), get_comment_date(),  get_comment_time()) ?></div>
					
				</div>
			
				<div class="comment_text">
					<?php if ($comment->comment_approved == '0') : ?>
					<em><?php _e('Your comment is awaiting moderation.', 'Savia') ?></em>
					<br />
					<?php endif; ?>
					<?php comment_text() ?>
				</div>
			
			</div>
			
		</div>

<?php } 


      

function limitString($str, $maxLen, $minLen = 0){
  
        if (strlen($str) <= $maxLen){//no need of trimming
            return $str;
        }
        
        $suffix = "";
        $suffixLen = strlen($suffix);

        // there's at least one space in the first $len chars
        if (strrpos(substr($str, 0, $maxLen), " ") !== false){
            $retString = substr($str, 0, strrpos(substr($str, 0, $maxLen)," ")) . $suffix;

            // If retstring's length is greater than $minLen or $minLen is to be ignored
            if (strlen($retString) > $minLen || $minLen == 0){
                return $retString;
                
            } else {//if the space is faaaar from the maxLen character
                return substr($str, 0, $maxLen - $suffixLen) . $suffix;
            }
        } else {
            return substr($str, 0, $maxLen - $suffixLen) . $suffix;
        }
	}
/*
function pr($o) {
	echo "<pre>";
    print_r($o);
	echo "</pre>";
}        
*/

function boc_pagination($pages = '', $range = 2)
{  
     $showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<div class='pagination clearfix'>";
         echo '<div class="links">';
         if($paged > 1){
         	echo "<a class='pagination-prev' href='".get_pagenum_link($paged - 1)."'><span class='page-prev'></span>".__('Previous', 'Savia')."</a>";
         }
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? " <b>".$i."</b>":" <a href='".get_pagenum_link($i)."'>".$i."</a>";
             }
         }

         if ($paged < $pages) echo " <a class='pagination-next' href='".get_pagenum_link($paged + 1)."'>".__('Next', 'Savia')."<span class='page-next'></span></a>";  
         echo "</div></div>\n";
     }
}



function get_related_portfolio_items($post_id) {
    $query = new WP_Query();
    
    $args = '';

    $item_cats = get_the_terms($post_id, 'portfolio_category');
    if($item_cats):
    foreach($item_cats as $item_cat) {
        $item_array[] = $item_cat->term_id;
    }
    endif;

    $args = wp_parse_args($args, array(
   //     'showposts' => -1,
        'post__not_in' => array($post_id),
        'ignore_sticky_posts' => 0,
        'post_type' => 'portfolio',
        'tax_query' => array(
            array(
                'taxonomy' => 'portfolio_category',
                'field' => 'id',
                'terms' => $item_array
            )
        )
    ));
    
    $query = new WP_Query($args);
    
    return $query;
}

function get_portfolio_items($limit=0, $order="rand", $category='') {
    
	
	if(!(int)$limit){
		$limit=10;
	}
	if(!$limit){
		$limit=10;
	}

	$query = new WP_Query();
    
    $args = '';

    $args = wp_parse_args($args, array(
        'ignore_sticky_posts' => 0,
        'post_type' => 'portfolio',
    	'posts_per_page' => $limit,
    	'orderby'=> $order,
    	'portfolio_category' => $category, 
    ));
    
    $query = new WP_Query($args);
    
    return $query;
}

function HexToRGB($hex, $transparency) {
		$hex = ereg_replace("#", "", $hex);
		$color = array();

		if(strlen($hex) == 3) {
			$color['r'] = hexdec(substr($hex, 0, 1) . $r);
			$color['g'] = hexdec(substr($hex, 1, 1) . $g);
			$color['b'] = hexdec(substr($hex, 2, 1) . $b);
		}
		else if(strlen($hex) == 6) {
			$color['r'] = hexdec(substr($hex, 0, 2));
			$color['g'] = hexdec(substr($hex, 2, 2));
			$color['b'] = hexdec(substr($hex, 4, 2));
		}

		return 'rgba('.$color['r'].','.$color['g'].', '.$color['b'].', 0.'.$transparency.')';
}

function getPortfolioItemIcon($postID) {

	// If Regular type - photo
	if(function_exists( 'get_post_format' ) && get_post_format($postID) != 'gallery' && get_post_format($postID) != 'video' && has_post_thumbnail()) {
		return 'camera';
	}elseif ( function_exists( 'get_post_format' ) && get_post_format( $postID ) == 'gallery' ) {
		return 'gallery';
	}elseif ( function_exists( 'get_post_format' ) && get_post_format( $postID ) == 'video') {
		return 'video';
	}
	return 'camera';
}

function the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo ' ...';
	} else {
		echo $excerpt;
	}
}

function get_the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		
		if ( $excut < 0 ) {
			return mb_substr( $subex, 0, $excut ). ' ...';
		} else {
			return $subex. ' ...';
		}
	} else {
		return $excerpt;
	}
}

function boc_cart_in_header() {
		global $woocommerce;
		$woocommerce_cart_in_header = ot_get_option('woocommerce_cart_in_header', 'on');		
		if ($woocommerce_cart_in_header == 'on') { 		
			if ($woocommerce) { 
				return true;
			}
		}else return false;
}


function boc_render_cart_in_header () {
	
	global $woocommerce;
?>
				<div class="header_cart">
				<div class="cart_widget_holder">
				
					<div class="cart_contents">
						<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>">
							<div class="cart-icon-wrap">
								<span class="savia_icon_cart"></span>
								<div class="cart-wrap"><span><?php echo $woocommerce->cart->cart_contents_count; ?> </span></div>
							</div>
						</a>
					</div>
					
					<div class="cart-notification">
						<span class="item-name"></span> <?php echo __('was successfully added to your cart.','Savia'); ?>
					</div>
					
					
					<?php
						// Check for WooCommerce 2.0 and display the cart widget
						if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
							the_widget( 'WC_Widget_Cart', 'title= ' );
						} else {
							the_widget( 'WooCommerce_Widget_Cart', 'title= ' );
						}
					?>
				</div>
				
			</div>
<?php
}


function boc_theme_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
	
		array(
			'name' 		=> 'WooCommerce',
			'slug' 		=> 'woocommerce',
			'required' 	=> false,
		),	
		array(
			'name' 		=> 'Custom Post Template',
			'slug' 		=> 'custom-post-template',
			'required' 	=> false,
		),	

	);

	// Change this to your theme text domain, used for internationalising strings
	$theme_text_domain = 'Savia';

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', $theme_text_domain ),
			'menu_title'                       			=> __( 'Install Plugins', $theme_text_domain ),
			'installing'                       			=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', $theme_text_domain ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', $theme_text_domain ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', $theme_text_domain ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );

}