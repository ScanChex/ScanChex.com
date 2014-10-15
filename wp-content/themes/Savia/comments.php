<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="no-comments"><?php _e('This post is password protected. Enter the password to view comments.', 'Savia'); ?></p>
	<?php
		return;
	}
?>
	
<?php if ( have_comments() ) : ?>

	<div class="comment_list" id="comments">
		<h3 class="left_title"><?php comments_number(__('No Comments', 'Savia'), __('One Comment', 'Savia'), __('% Comments', 'Savia'));?></h3>

		<!-- Comment list -->
		<ol>
			<?php wp_list_comments('type=comment&callback=boc_comment'); ?>
		</ol>
		<!-- Comment list::END -->
		
		<div class="clearfix">
		    <div style="float: left;"><?php previous_comments_link(); ?></div>
		    <div style="float: right;"><?php next_comments_link(); ?></div>
		</div>
	</div>

<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="no-comments"><?php _e('Comments are closed.', 'Savia'); ?></p>

	<?php endif; ?>

<?php endif; ?>

<?php if ( comments_open() ) : ?>

				
	<?php

$args = array(
  'id_form'           => 'commentform',
  'id_submit'         => 'submit',
  'title_reply'       => '<span>'.__('Leave A Comment', 'Savia').'</span>',
  'label_submit'      => __('Comment', 'Savia'),

  'comment_field' =>  '<div id="comment-textarea">
					<p>		
						<label for="comment">'.__('Comment', 'Savia').'<span class="required">*</span></label>
						<textarea id="comment" rows="8" class="aqua_input" name="comment"></textarea>
					</p>
				</div>',
	
  'must_log_in' => '<p>You must be <a href="'.wp_login_url( get_permalink() ).'">logged in</a> to post a comment.</p>',

  'logged_in_as' => '<p>'.__('Logged in as', 'Savia').' <a href="'.get_option('siteurl').'/wp-admin/profile.php">'.$user_identity.'</a>. <a href="'.wp_logout_url(get_permalink()).'" title="Log out of this account">'.__('Log out &raquo;', 'Savia').'</a></p>',

  'comment_notes_before' => '',  
  'comment_notes_after' => '',

  'fields' => apply_filters( 'comment_form_default_fields', array(

    'author' =>
      '<p>
			<label for="author">'.__('Name', 'Savia').'<span class="required">*</span></label>
			<input id="author" class="aqua_input" name="author" type="text" value=""/>
		</p>',

    'email' =>
      '<p>	
			<label for="email">'.__('Email', 'Savia').'<span class="required">*</span></label> 
			<input id="email" class="aqua_input" name="email" type="email" value=""/>
		</p>',

    'url' =>
      '<p>		
			<label for="url">'.__('Website', 'Savia').'</label>
			<input id="url" class="aqua_input" name="url" type="text" value="" size="30"/>
		</p>'
    )
  ),
);	
		?>		
				

				
		<!-- Comment Section -->	

		<?php comment_form($args); ?>
					
		<!-- Comment Section::END -->


<?php endif; ?>