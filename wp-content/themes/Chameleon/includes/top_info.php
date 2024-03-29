<div id="category-name">
	<div id="category-inner">
		<?php 
			$et_tagline = '';
			if( is_tag() ) {
				$et_page_title = esc_html__('Posts Tagged &quot;','Chameleon') . single_tag_title('',false) . '&quot;';
			} elseif (is_day()) {
				$et_page_title = esc_html__('Posts made in','Chameleon') . ' ' . get_the_time('F jS, Y');
			} elseif (is_month()) {
				$et_page_title = esc_html__('Posts made in','Chameleon') . ' ' . get_the_time('F, Y');
			} elseif (is_year()) {
				$et_page_title = esc_html__('Posts made in','Chameleon') . ' ' . get_the_time('Y');
			} elseif (is_search()) {
				$et_page_title = esc_html__('Search results for','Chameleon') . ' ' . get_search_query();
			} elseif (is_category()) {
				$et_page_title = single_cat_title('',false);
				$et_tagline = category_description();
			} elseif (is_author()) {
				global $wp_query;
				$curauth = $wp_query->get_queried_object();
				$et_page_title = esc_html__('Posts by ','Chameleon') . $curauth->nickname;
			} elseif ( is_single() || is_page() ) {
				$et_page_title = get_post_meta($post->ID,'headline',true);     /*  get_the_title();  <-- put this back if things crash.*/
				if ( is_page() ) $et_tagline = get_post_meta($post->ID,'Description',true) ? get_post_meta($post->ID,'Description',true) : '';
			}
		?>
		<h6 class="category-title"><?php echo wp_kses( $et_page_title, array( 'span' => array() ) ); ?></h6>
		<?php if ( $et_tagline <> '' ) { ?>
			<p class="description"><?php echo wp_kses( $et_tagline, array( 'span' => array() ) ); ?></p>
		<?php } ?>
		
		<?php if ( is_single() ) { ?>
			<?php get_template_part('includes/postinfo'); ?>
		<?php } ?>
	</div> <!-- end #category-inner -->
</div> <!-- end #category-name -->