<?php
	class WpFastestCacheAdmin extends WpFastestCache{
		private $menuTitle = "WP Fastest Cache";
		private $pageTitle = "WP Fastest Cache Settings";
		private $adminPageUrl = "wp-fastest-cache/admin/index.php";
		private $systemMessage = array();
		private $options = array();
		private $cronJobSettings;
		private $startTime;
		private $blockCache = false;

		public function __construct(){
			$this->options = $this->getOptions();
			
			$this->optionsPageRequest();
			$this->iconUrl = plugins_url("wp-fastest-cache/images/icon-left.png");
			$this->setCronJobSettings();
			$this->addButtonOnEditor();
			add_action('admin_enqueue_scripts', array($this, 'addJavaScript'));

		}

		public function addButtonOnEditor(){
			add_action('admin_print_footer_scripts', array($this, 'addButtonOnQuicktagsEditor'));
			add_action('init', array($this, 'myplugin_buttonhooks'));
		}

		public function checkShortCode($content){
			preg_match("/\[wpfcNOT\]/", $content, $wpfcNOT);
			if(count($wpfcNOT) > 0){
				if(is_single() || is_page()){
					$this->blockCache = true;
				}
				$content = str_replace("[wpfcNOT]", "", $content);
			}
			return $content;
		}

		public function myplugin_buttonhooks() {
		   // Only add hooks when the current user has permissions AND is in Rich Text editor mode
		   if ( ( current_user_can('edit_posts') || current_user_can('edit_pages') ) && get_user_option('rich_editing') ) {
		     add_filter("mce_external_plugins", array($this, "myplugin_register_tinymce_javascript"));
		     add_filter('mce_buttons', array($this, 'myplugin_register_buttons'));
		   }
		}
		// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
		public function myplugin_register_tinymce_javascript($plugin_array) {
		   $plugin_array['wpfc'] = plugins_url('../js/button.js?v='.time(),__file__);
		   return $plugin_array;
		}

		public function myplugin_register_buttons($buttons) {
		   array_push($buttons, 'wpfc');
		   return $buttons;
		}

		public function addButtonOnQuicktagsEditor(){
			if (wp_script_is('quicktags')){ ?>
				<script type="text/javascript">
				    QTags.addButton('wpfc_not', 'wpfcNOT', '<!--[wpfcNOT]-->', '', '', 'Block caching for this page');
			    </script>
		    <?php }
		}

		public function optionsPageRequest(){
			if(!empty($_POST)){
				if(isset($_POST["wpFastestCachePage"])){
					if($_POST["wpFastestCachePage"] == "options"){
						$this->saveOption();
					}else if($_POST["wpFastestCachePage"] == "deleteCache"){
						$this->deleteCache();
					}else if($_POST["wpFastestCachePage"] == "deleteCssAndJsCache"){
						$this->deleteCssAndJsCache();
					}else if($_POST["wpFastestCachePage"] == "cacheTimeout"){
						$this->addCacheTimeout();
					}
				}
			}
		}

		public function addCacheTimeout(){
			if(isset($_POST["wpFastestCacheTimeOut"])){
				if($_POST["wpFastestCacheTimeOut"]){
					wp_clear_scheduled_hook($this->slug());
					wp_schedule_event(time() + 120, $_POST["wpFastestCacheTimeOut"], $this->slug());
				}else{
					wp_clear_scheduled_hook($this->slug());
				}
			}
		}

		public function deleteCssAndJsCache(){
			if(is_dir($this->getWpContentDir()."/cache/wpfc-minified")){
				$this->rm_folder_recursively($this->getWpContentDir()."/cache/wpfc-minified");
				$this->deleteCache();
			}else{
				$this->systemMessage = array("Already deleted","success");
			}
		}

		public function setCronJobSettings(){
			if(wp_next_scheduled($this->slug())){
				$this->cronJobSettings["period"] = wp_get_schedule($this->slug());
				$this->cronJobSettings["time"] = wp_next_scheduled($this->slug());
			}
		}

		public function addMenuPage(){
			add_action('admin_menu', array($this, 'register_my_custom_menu_page'));
		}

		public function addJavaScript(){
			wp_enqueue_script("language", plugins_url("wp-fastest-cache/js/language.js"), array(), time(), false);
			wp_enqueue_script("info", plugins_url("wp-fastest-cache/js/info.js"), array(), time(), true);
			if(isset($this->options->wpFastestCacheLanguage) && $this->options->wpFastestCacheLanguage != "eng"){
				wp_enqueue_script("dictionary", plugins_url("wp-fastest-cache/js/lang/".$this->options->wpFastestCacheLanguage.".js"), array(), time(), false);
			}
		}

		public function register_my_custom_menu_page(){
			if(function_exists('add_menu_page')){ 
				add_menu_page($this->pageTitle, $this->menuTitle, 'manage_options', "WpFastestCacheOptions", array($this, 'optionsPage'), $this->iconUrl, 99 );
				wp_enqueue_style("wp-fastest-cache", plugins_url("wp-fastest-cache/css/style.css"), array(), time(), "all");
			}
		}

		public function saveOption(){
			unset($_POST["wpFastestCachePage"]);
			$data = json_encode($_POST);
			//for optionsPage() $_POST is array and json_decode() converts to stdObj
			$this->options = json_decode($data);

			$this->systemMessage = $this->modifyHtaccess($_POST);

			if(isset($this->systemMessage[1]) && $this->systemMessage[1] != "error"){

				if($message = $this->checkCachePathWriteable()){


					if(is_array($message)){
						$this->systemMessage = $message;
					}else{
						if(get_option("WpFastestCache")){
							update_option("WpFastestCache", $data);
						}else{
							add_option("WpFastestCache", $data, null, "yes");
						}
					}
				}
			}
		}

		public function checkCachePathWriteable(){
			$message = array();

			if(!is_dir($this->getWpContentDir()."/cache/")){
				if (@mkdir($this->getWpContentDir()."/cache/", 0755, true)){
					//
				}else{
					array_push($message, "- /wp-content/cache/ is needed to be created");
				}
			}else{
				if (@mkdir($this->getWpContentDir()."/cache/testWpFc/", 0755, true)){
					rmdir($this->getWpContentDir()."/cache/testWpFc/");
				}else{
					array_push($message, "- /wp-content/cache/ permission has to be 755");
				}
			}

			if(!is_dir($this->getWpContentDir()."/cache/all/")){
				if (@mkdir($this->getWpContentDir()."/cache/all/", 0755, true)){
					//
				}else{
					array_push($message, "- /wp-content/cache/all/ is needed to be created");
				}
			}else{
				if (@mkdir($this->getWpContentDir()."/cache/all/testWpFc/", 0755, true)){
					rmdir($this->getWpContentDir()."/cache/all/testWpFc/");
				}else{
					array_push($message, "- /wp-content/cache/all/ permission has to be 755");
				}	
			}

			if(count($message) > 0){
				return array(implode("<br>", $message), "error");
			}else{
				return true;
			}
		}

		public function modifyHtaccess($post){
			$path = ABSPATH;
			if($this->is_subdirectory_install()){
				$path = $this->getABSPATH();
			}

			if((isset($post["wpFastestCacheStatus"]) && $post["wpFastestCacheStatus"] == "on") || (isset($post["wpFastestCacheGzip"]) && $post["wpFastestCacheGzip"] == "on") || (isset($post["wpFastestCacheLBC"]) && $post["wpFastestCacheLBC"] == "on")){
				//include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				if($this->isPluginActive('gzippy/gzippy.php')){
					return array("GZippy needs to be deactive<br>This plugin has aldready Gzip feature", "error");
				}else if(!is_file($path.".htaccess")){
					return array(".htaccess was not found", "error");
				}else if(is_writable($path.".htaccess")){
					$htaccess = file_get_contents($path.".htaccess");
					$htaccess = $this->insertRewriteRule($htaccess);
					$htaccess = $this->insertGzipRule($htaccess, $post);
					$htaccess = $this->insertLBCRule($htaccess, $post);
					$htaccess = preg_replace("/\n+/","\n", $htaccess);

					file_put_contents($path.".htaccess", $htaccess);
				}else{
					return array(".htaccess is not writable", "error");
				}
				return array("Options have been saved", "success");
			}else{
				//disable
				$this->deleteCache();
				return array("Options have been saved", "success");
			}
		}

		public function insertLBCRule($htaccess, $post){
			if(isset($post["wpFastestCacheLBC"]) && $post["wpFastestCacheLBC"] == "on"){


			$data = "# BEGIN LBCWpFastestCache"."\n".
					'<FilesMatch "\.(ico|pdf|flv|jpg|jpeg|png|gif|js|css|swf|x-html|css|xml|js|woff|ttf|svg|eot)(\.gz)?$">'."\n".
					'<IfModule mod_expires.c>'."\n".
					'ExpiresActive On'."\n".
					'ExpiresDefault A0'."\n".
					'ExpiresByType image/gif A2592000'."\n".
					'ExpiresByType image/png A2592000'."\n".
					'ExpiresByType image/jpg A2592000'."\n".
					'ExpiresByType image/jpeg A2592000'."\n".
					'ExpiresByType image/ico A2592000'."\n".
					'ExpiresByType text/css A2592000'."\n".
					'ExpiresByType text/javascript A2592000'."\n".
					'ExpiresByType application/javascript A2592000'."\n".
					'</IfModule>'."\n".
					'<IfModule mod_headers.c>'."\n".
					'Header set Expires "max-age=2592000, public"'."\n".
					'Header unset ETag'."\n".
					'</IfModule>'."\n".
					'FileETag None'."\n".
					'</FilesMatch>'."\n".
					"# END LBCWpFastestCache"."\n";

				preg_match("/BEGIN LBCWpFastestCache/", $htaccess, $check);
				if(count($check) === 0){
					return $data.$htaccess;
				}else{
					return $htaccess;
				}
			}else{
				//delete levere browser caching
				$htaccess = preg_replace("/#\s?BEGIN\s?LBCWpFastestCache.*?#\s?END\s?LBCWpFastestCache/s", "", $htaccess);
				return $htaccess;
			}
		}

		public function insertGzipRule($htaccess, $post){
			if(isset($post["wpFastestCacheGzip"]) && $post["wpFastestCacheGzip"] == "on"){
		    	$data = "# BEGIN GzipWpFastestCache"."\n".
		          		"<IfModule mod_deflate.c>"."\n".
		  				"AddOutputFilterByType DEFLATE text/plain"."\n".
		  				"AddOutputFilterByType DEFLATE text/html"."\n".
		  				"AddOutputFilterByType DEFLATE text/xml"."\n".
		  				"AddOutputFilterByType DEFLATE text/css"."\n".
		  				"AddOutputFilterByType DEFLATE application/xml"."\n".
		  				"AddOutputFilterByType DEFLATE application/xhtml+xml"."\n".
		  				"AddOutputFilterByType DEFLATE application/rss+xml"."\n".
		  				"AddOutputFilterByType DEFLATE application/javascript"."\n".
		  				"AddOutputFilterByType DEFLATE application/x-javascript"."\n".
		  				"</IfModule>"."\n".
						"# END GzipWpFastestCache"."\n";

				preg_match("/BEGIN GzipWpFastestCache/", $htaccess, $check);
				if(count($check) === 0){
					return $data.$htaccess;
				}else{
					return $htaccess;
				}	
			}else{
				//delete gzip rules
				$htaccess = preg_replace("/#\s?BEGIN\s?GzipWpFastestCache.*?#\s?END\s?GzipWpFastestCache/s", "", $htaccess);
				return $htaccess;
			}
		}

		public function insertRewriteRule($htaccess){
			$htaccess = preg_replace("/#\s?BEGIN\s?WpFastestCache.*?#\s?END\s?WpFastestCache/s", "", $htaccess);
			$htaccess = $this->getHtaccess().$htaccess;

			return $htaccess;
		}

		public function prefixRedirect(){
			$forceTo = "";

			if(preg_match("/^https:\/\//", home_url())){
				if(preg_match("/^https:\/\/www\./", home_url())){
					$forceTo = "\nRewriteCond %{HTTPS} !=on"."\n".
							   "RewriteCond %{HTTP_HOST} !^www\."."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-login.php"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-admin"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-content"."\n".
							   "RewriteRule ^(.*)$ http://www.%{HTTP_HOST}/$1 [R=301,L]"."\n\n".

							   "RewriteCond %{HTTPS} !=on"."\n".
							   "RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-login.php"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-admin"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-content"."\n".
							   "RewriteRule ^(.*)$ https://www.%1/$1 [R=301,L]"."\n\n";
				}else{
					$forceTo = "\nRewriteCond %{HTTPS} !=on"."\n".
							   "RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-login.php"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-admin"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-content"."\n".
							   "RewriteRule ^(.*)$ http://%1/$1 [R=301,L]"."\n\n".

							   "RewriteCond %{HTTPS} !=on"."\n".
							   "RewriteCond %{HTTP_HOST} !^www\."."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-login.php"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-admin"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-content"."\n".
							   "RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]"."\n\n";
				}
			}else{
				if(preg_match("/^http:\/\/www\./", home_url())){
					$forceTo = "\nRewriteCond %{HTTP_HOST} !^www\."."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-login.php"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-admin"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-content"."\n".
							   "RewriteRule ^(.*)$ http://www.%{HTTP_HOST}/$1 [R=301,L]"."\n\n";
				}else{
					$forceTo = "\nRewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-login.php"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-admin"."\n".
							   "RewriteCond %{REQUEST_URI} !^/wp-content"."\n".
							   "RewriteRule ^(.*)$ http://%1/$1 [R=301,L]"."\n\n";
				}
			}
			return $forceTo;
		}

		public function getHtaccess(){
			$mobile = "";
			$loggedInUser = "";

			if(isset($_POST["wpFastestCacheMobile"]) && $_POST["wpFastestCacheMobile"] == "on"){
				$mobile = "RewriteCond %{HTTP_USER_AGENT} !^.*(".$this->getMobileUserAgents().").*$ [NC]"."\n";
			}

			if(isset($_POST["wpFastestCacheLoggedInUser"]) && $_POST["wpFastestCacheLoggedInUser"] == "on"){
				$loggedInUser = "RewriteCond %{HTTP:Cookie} !^.*(comment_author_|wordpress_logged_in|wp-postpass_).*$"."\n";
			}

			$data = "# BEGIN WpFastestCache"."\n".
					"<IfModule mod_rewrite.c>"."\n".
					"RewriteEngine On"."\n".
					"RewriteBase /"."\n".
					"AddDefaultCharset UTF-8"."\n".$this->prefixRedirect().
					"RewriteCond %{REQUEST_METHOD} !POST"."\n".
					"RewriteCond %{QUERY_STRING} !.*=.*"."\n".$loggedInUser.
					'RewriteCond %{HTTP:X-Wap-Profile} !^[a-z0-9\"]+ [NC]'."\n".
					'RewriteCond %{HTTP:Profile} !^[a-z0-9\"]+ [NC]'."\n".$mobile;

			if(ABSPATH == "//"){
				$data = $data."RewriteCond %{DOCUMENT_ROOT}/wp-content/cache/all/$1/index.html -f"."\n";
			}else{
				$data = $data."RewriteCond ".ABSPATH."wp-content/cache/all/".$this->getRewriteBase(true)."$1/index.html -f"."\n";
			}

			$data = $data.'RewriteRule ^(.*) "/'.$this->getRewriteBase().'wp-content/cache/all/'.$this->getRewriteBase(true).'$1/index.html" [L]'."\n".
					"</IfModule>"."\n".
					"# END WpFastestCache"."\n";
			return $data;
		}

		public function getRewriteBase($sub = ""){
			if($sub && $this->is_subdirectory_install()){
				return "";
			}
			$url = rtrim(site_url(), "/");
			preg_match("/https?:\/\/[^\/]+(.*)/", $url, $out);

			if(isset($out[1]) && $out[1]){
				return trim($out[1], "/")."/";
			}else{
				return "";
			}
		}

		public function isPluginActive( $plugin ) {
			return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || $this->isPluginActiveForNetwork( $plugin );
		}
		public function isPluginActiveForNetwork( $plugin ) {
			if ( !is_multisite() )
				return false;

			$plugins = get_site_option( 'active_sitewide_plugins');
			if ( isset($plugins[$plugin]) )
				return true;

			return false;
		}

		public function optionsPage(){
			$this->systemMessage = count($this->systemMessage) > 0 ? $this->systemMessage : $this->getSystemMessage();

			$wpFastestCacheGzip = isset($this->options->wpFastestCacheGzip) ? 'checked="checked"' : "";
			$wpFastestCacheLanguage = isset($this->options->wpFastestCacheLanguage) ? $this->options->wpFastestCacheLanguage : "eng";
			$wpFastestCacheLBC = isset($this->options->wpFastestCacheLBC) ? 'checked="checked"' : "";
			$wpFastestCacheLoggedInUser = isset($this->options->wpFastestCacheLoggedInUser) ? 'checked="checked"' : "";
			$wpFastestCacheMinifyCss = isset($this->options->wpFastestCacheMinifyCss) ? 'checked="checked"' : "";
			$wpFastestCacheCombineCss = isset($this->options->wpFastestCacheCombineCss) ? 'checked="checked"' : "";
			$wpFastestCacheMinifyHtml = isset($this->options->wpFastestCacheMinifyHtml) ? 'checked="checked"' : "";
			$wpFastestCacheMobile = isset($this->options->wpFastestCacheMobile) ? 'checked="checked"' : "";
			$wpFastestCacheNewPost = isset($this->options->wpFastestCacheNewPost) ? 'checked="checked"' : "";
			$wpFastestCacheStatus = isset($this->options->wpFastestCacheStatus) ? 'checked="checked"' : "";
			$wpFastestCacheTimeOut = isset($this->cronJobSettings["period"]) ? $this->cronJobSettings["period"] : "";
			?>
			<div class="wrap">
				<h2>WP Fastest Cache Options</h2>
				<?php if($this->systemMessage){ ?>
					<div class="updated <?php echo $this->systemMessage[1]."-wpfc"; ?>" id="message"><p><?php echo $this->systemMessage[0]; ?></p></div>
				<?php } ?>
				<div class="tabGroup">
					<?php
						$tabs = array(array("id"=>"wpfc-options","title"=>"Settings"),
									  array("id"=>"wpfc-deleteCache","title"=>"Delete Cache"),
									  array("id"=>"wpfc-deleteCssAndJsCache","title"=>"Delete Minified Css"),
									  array("id"=>"wpfc-cacheTimeout","title"=>"Cache Timeout"));

						foreach ($tabs as $key => $value){
							$checked = "";
							if(!isset($_POST["wpFastestCachePage"]) && $value["id"] == "wpfc-options"){
								$checked = ' checked="checked" ';
							}else if((isset($_POST["wpFastestCachePage"])) && ("wpfc-".$_POST["wpFastestCachePage"] == $value["id"])){
								$checked = ' checked="checked" ';
							}
							echo '<input '.$checked.' type="radio" id="'.$value["id"].'" name="tabGroup1">'."\n";
							echo '<label for="'.$value["id"].'">'.$value["title"].'</label>'."\n";
						}
					?>
				    <br>
				    <div class="tab1">
						<form method="post" name="wp_manager">
							<input type="hidden" value="options" name="wpFastestCachePage">
							<div class="questionCon">
								<div class="question">Cache System</div>
								<div class="inputCon"><input type="checkbox" <?php echo $wpFastestCacheStatus; ?> id="wpFastestCacheStatus" name="wpFastestCacheStatus"><label for="wpFastestCacheStatus">Enable</label></div>
							</div>

							<div class="questionCon">
								<div class="question">Logged-in Users</div>
								<div class="inputCon"><input type="checkbox" <?php echo $wpFastestCacheLoggedInUser; ?> id="wpFastestCacheLoggedInUser" name="wpFastestCacheLoggedInUser"><label for="wpFastestCacheLoggedInUser">Don't show the cached version for logged-in users</label></div>
							</div>

							<div class="questionCon">
								<div class="question">Mobile</div>
								<div class="inputCon"><input type="checkbox" <?php echo $wpFastestCacheMobile; ?> id="wpFastestCacheMobile" name="wpFastestCacheMobile"><label for="wpFastestCacheMobile">Don't show the cached version for mobile devices</label></div>
							</div>

							<div class="questionCon">
								<div class="question">New Post</div>
								<div class="inputCon"><input type="checkbox" <?php echo $wpFastestCacheNewPost; ?> id="wpFastestCacheNewPost" name="wpFastestCacheNewPost"><label for="wpFastestCacheNewPost">Clear all cache files when a post or page is published</label></div>
							</div>
							<div class="questionCon">
								<div class="question">Minify HTML</div>
								<div class="inputCon"><input type="checkbox" <?php echo $wpFastestCacheMinifyHtml; ?> id="wpFastestCacheMinifyHtml" name="wpFastestCacheMinifyHtml"><label for="wpFastestCacheMinifyHtml">You can decrease the size of page</label></div>
								<div class="get-info"><img src="<?php echo plugins_url("wp-fastest-cache/images/info.png"); ?>" /></div>
							</div>

							<div class="questionCon">
								<div class="question">Minify Css</div>
								<div class="inputCon"><input type="checkbox" <?php echo $wpFastestCacheMinifyCss; ?> id="wpFastestCacheMinifyCss" name="wpFastestCacheMinifyCss"><label for="wpFastestCacheMinifyCss">You can decrease the size of css files</label></div>
								<div class="get-info"><img src="<?php echo plugins_url("wp-fastest-cache/images/info.png"); ?>" /></div>
							</div>

							<div class="questionCon">
								<div class="question">Combine Css</div>
								<div class="inputCon"><input type="checkbox" <?php echo $wpFastestCacheCombineCss; ?> id="wpFastestCacheCombineCss" name="wpFastestCacheCombineCss"><span id="wpfc-beta">(Beta)</span><label for="wpFastestCacheCombineCss">Reduce HTTP requests through combined files</label></div>
								<div class="get-info"><img src="<?php echo plugins_url("wp-fastest-cache/images/info.png"); ?>" /></div>
							</div>

							<div class="questionCon">
								<div class="question">Gzip</div>
								<div class="inputCon"><input type="checkbox" <?php echo $wpFastestCacheGzip; ?> id="wpFastestCacheGzip" name="wpFastestCacheGzip"><label for="wpFastestCacheGzip">Reduce the size of files sent from your server</label></div>
								<div class="get-info"><img src="<?php echo plugins_url("wp-fastest-cache/images/info.png"); ?>" /></div>
							</div>

							<div class="questionCon">
								<div class="question">Browser Caching</div>
								<div class="inputCon"><input type="checkbox" <?php echo $wpFastestCacheLBC; ?> id="wpFastestCacheLBC" name="wpFastestCacheLBC"><label for="wpFastestCacheLBC">Reduce page load times for repeat visitors</label></div>
								<div class="get-info"><img src="<?php echo plugins_url("wp-fastest-cache/images/info.png"); ?>" /></div>
							</div>

							<div class="questionCon">
								<div class="question">Language</div>
								<div class="inputCon">
									<select id="wpFastestCacheLanguage" name="wpFastestCacheLanguage">
									  <option value="de">Deutsch</option>
									  <option value="eng">English</option>
									  <option value="es">Español</option>
									  <option value="pt">Português</option>
									  <option value="ru">Русский</option>
									  <!-- <option value="ro">Română</option> -->
									  <option value="tr">Türkçe</option>
									  <option value="ukr">Українська</option>
									</select> 
								</div>
							</div>
							<div class="questionCon qsubmit">
								<div class="submit"><input type="submit" value="Submit" class="button-primary"></div>
							</div>
						</form>
				    </div>
				    <div class="tab2">
				    	<form method="post" name="wp_manager">
				    		<input type="hidden" value="deleteCache" name="wpFastestCachePage">
				    		<div class="questionCon">
				    			<div style="padding-left:11px;">
				    			<label>You can delete all cache files</label><br>
				    			<label>Target folder</label> <b><?php echo $this->getWpContentDir(); ?>/cache/all</b>
				    			</div>
				    		</div>
				    		<div class="questionCon qsubmit">
				    			<div class="submit"><input type="submit" value="Delete Now" class="button-primary"></div>
				    		</div>
				   		</form>
				    </div>

				    <div class="tab3">
				    	<form method="post" name="wp_manager">
				    		<input type="hidden" value="deleteCssAndJsCache" name="wpFastestCachePage">
				    		<div class="questionCon">
				    			<div style="padding-left:11px;">
				    			<label>If you modify any css file, you have to delete minified css files</label><br>
				    			<label>All cache files will be removed as well</label><br>
				    			<label>Target folder</label> <b><?php echo $this->getWpContentDir(); ?>/cache/wpfc-minified</b><br>
				    			<label>Target folder</label> <b><?php echo $this->getWpContentDir(); ?>/cache/all</b>
				    			</div>
				    		</div>
				    		<div class="questionCon qsubmit">
				    			<div class="submit"><input type="submit" value="Delete Now" class="button-primary"></div>
				    		</div>
				   		</form>
				    </div>

				    <div class="tab4">
				    	<form method="post" name="wp_manager">
				    		<input type="hidden" value="cacheTimeout" name="wpFastestCachePage">
				    		<div class="questionCon">
				    			<label style="padding-left:11px;">All cached files are deleted at the determinated time.</label>
				    		</div>
				    		<div class="questionCon" style="text-align: center;padding-top: 10px;">
									<select id="wpFastestCacheTimeOut" name="wpFastestCacheTimeOut">
										<?php
											$arrSettings = array(array("value" => "", "text" => "Choose One"),
																array("value" => "hourly", "text" => "Once an hour"),
																array("value" => "daily", "text" => "Once a day"),
																array("value" => "twicedaily", "text" => "Twice a day"));

											foreach ($arrSettings as $key => $value) {
												$checked = $value["value"] == $wpFastestCacheTimeOut ? 'selected=""' : "";
												echo "<option {$checked} value='{$value["value"]}'>{$value["text"]}</option>";
											}
										?>
									</select> 
							</div>
							<?php if($wpFastestCacheTimeOut){ ?>
								<div class="questionCon">
									<table class="widefat fixed" style="border:0;border-top:1px solid #DEDBD1;border-radius:0;margin: 5px 4% 0 4%;width: 92%;">
										<thead>
											<tr>
												<th scope="col" style="border-left:1px solid #DEDBD1;border-top-left-radius:0;">Next due</th>
												<th scope="col" style="border-right:1px solid #DEDBD1;border-top-right-radius:0;">Schedule</th>
											</tr>
										</thead>
											<tbody>
												<tr>
													<th scope="row" style="border-left:1px solid #DEDBD1;"><?php echo date("d-m-Y @ H:i", $this->cronJobSettings["time"]); ?></th>
													<td style="border-right:1px solid #DEDBD1;"><?php echo $this->cronJobSettings["period"]; ?>
														<label id="deleteCron" style="float:right;padding-right:5px;">[ x ]</label>
														<script>
															jQuery("#deleteCron").click(function(){
																var selectPeriod = jQuery("#wpFastestCacheTimeOut");
																selectPeriod.val("");
																var submit = selectPeriod.closest("form").find('input[type="submit"]');
																submit.click();
															})
														</script>
													</td>
												</tr>
											</tbody>
									</table>
					    		</div>
				    		<?php } ?>
				    		<div class="questionCon" style="text-align: center;padding-top: 10px;">
				    			<strong><label>Server time</label>: <?php echo date("Y-m-d H:i:s"); ?></strong>
				    		</div>
				    		<div class="questionCon qsubmit">
				    			<div class="submit"><input type="submit" value="Submit" class="button-primary"></div>
				    		</div>
				   		</form>
				    </div>
				</div>
				<div class="omni_admin_sidebar">

				<div class="omni_admin_sidebar_section" id="vote-us">
					<h3 style="color: antiquewhite;">Support Us</h3>
					<ul>
						<li><label>If you like it, Please vote and support us.</label></li>
					</ul>
					<script>
						jQuery("#vote-us").click(function(){
							var win=window.open("http://wordpress.org/support/view/plugin-reviews/wp-fastest-cache?free-counter?rate=5#postform", '_blank');
							win.focus();
						});
					</script>
				</div>
				<div class="omni_admin_sidebar_section">
				  <h3>Having Issues?</h3>
				  <ul>
				    <li><label>You can create a ticket</label> <a target="_blank" href="http://wordpress.org/support/plugin/wp-fastest-cache"><label>WordPress support forum</label></a></li>
				  <?php
				  	if(isset($this->options->wpFastestCacheLanguage) && $this->options->wpFastestCacheLanguage == "tr"){
				  		?>
				  		<li><label>R10 Üzerinden Sorabilirsiniz</label> <a target="_blank" href="http://www.r10.net/wordpress/1096868-wp-fastest-cache-wp-en-hizli-ve-en-basit-cache-sistemi.html"><label>R10.net destek başlığı</label></a></li>
				  		<?php
				  	}
				  ?>
				  </ul>
				  </div>
				</div>
			</div>
			<script>Wpfclang.init("<?php echo $wpFastestCacheLanguage; ?>");</script>
			<?php
		}
	}
?>