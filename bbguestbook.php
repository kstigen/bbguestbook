<?php
/*
Plugin Name: BB Guestbook
Plugin URI: http://stigen.dyndns.org
Version: 0.1
Author: Kenneth Stigen
Author URI: http://stigen.dyndns.org
License: GPL2
Description: Guestbook for a brassband
*/

// Include necessary files
require("BBGuestbookPrinter.php");

// Declare class if not declared
if(!class_exists("BBGuestbook")){
	class BBGuestbook{
		var $adminOptionsName = "BBGuestbookAdminOptions";
		
		function BBGuestbook(){ }
		
		function init(){
			$this->getAdminOptions();     
			$this->installDatabase();
		}
		                             
		function installDatabase(){
			global $wpdb;
			$table_name = $wpdb->prefix . "bbguestbook";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
					  id mediumint(9) NOT NULL AUTO_INCREMENT,
					  date datetime NOT NULL,
					  name tinytext NOT NULL,
					  email tinytext,
					  instrument tinytext,
					  member_of tinytext,					
					  topic tinytext,
					  comments text NOT NULL,
					  UNIQUE KEY id (id)
					);";
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);           
				
				$welcome_name = "Mr. Wordpress";
				$welcome_email = "kenneth@stigen.net";
				$welcome_instrument = "Flugelhorn";
				$welcome_member_of = "JHMF";
				$welcome_topic = "Korps";
				$welcome_comment = "Congratulations, you just completed the installation!";     
				

				$rows_affected = $wpdb->insert( $table_name, array( 
						'date' => current_time('mysql'), 
						'name' => $welcome_name, 
						'email' => $welcome_email, 
						'instrument' => $welcome_instrument, 
						'member_of' => $welcome_member_of,  
						'topic' => $welcome_topic,
						'comments' => $welcome_comment 
						) );
			}
		}
		
		function getAdminOptions(){
			$bbGuestbookAdminOptions = array('bbguestbook_page_id' => '-1');
			$savedOptions = get_option($this->adminOptionsName);
			
			if(!empty($savedOptions)){
				foreach($savedOptions as $key => $option){
					$bbGuestbookAdminOptions[$key] = $option;
				}
 			}

			update_option($this->adminOptionsName, $bbGuestbookAdminOptions);
			return $bbGuestbookAdminOptions;
		}
		
		function printAdminPage(){
			$adminOptions = $this->getAdminOptions();
			
			if(isset($_POST['update_bbguestbook'])){
				if(isset($_POST['bbguestbook_page_id'])){
					$adminOptions['bbguestbook_page_id'] = $_POST['bbguestbook_page_id'];
					update_option($this->adminOptionsName, $adminOptions);
				}
				
				?>
				<div class="updated">
					<p>
						<strong><?php _e("Settings updated", "BBGuestbook"); ?></strong>
					</p>
				</div>
				<?php
			}
			?>
			<div class="wrap">
				<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
					<h2>BBGuestbook Settings</h2>
					<h3>BBGuestbook Page Id</h3>
					<input type="text" name="bbguestbook_page_id" value="<?php _e(apply_filters('format_to_edit', $adminOptions['bbguestbook_page_id']), 'BBGuestbook'); ?>" />
					<div class="submit">
						<input type="submit" name="update_bbguestbook" value="<?php _e('Update settings', 'BBGuestbook'); ?>"/>
					</div>
				</form>
			</div>
			
			<?php
		}		
		
		function showGuestbook($content = ''){
			$adminOptions = $this->getAdminOptions();
			$page_id = $adminOptions['bbguestbook_page_id'];
			
			if(is_page($page_id)){
				$bbguestbookPrinter = new BBGuestbookPrinter();
				$content = $bbguestbookPrinter->fillPage($content);
				//$content = $content . "<p><strong>Kenneth was here with a plugin</strong></p>";				
				
			}
			return $content;
		}
	}
}

// Instantiate class
if(class_exists("BBGuestbook")){
	$bbGuestbook = new BBGuestbook();
}

if(!function_exists("BBGuestbook_ap")){
	function BBGuestbook_ap(){
		global $bbGuestbook;
		if(!isset($bbGuestbook)){
			return;
		}
		
		if(function_exists('add_options_page')){
			add_options_page('BB Guestbook', 'BB Guestbook', 7, basename(__FILE__), array(&$bbGuestbook, 'printAdminPage'));
		}
	}
}

if(isset($bbGuestbook)){
	add_action('activate_bbguestbook/bbguestbook.php', array(&$bbGuestbook, 'init'));
	add_filter('the_content', array(&$bbGuestbook, 'showGuestbook'));
	add_action('admin_menu', 'BBGuestbook_ap', 1);
}        
?>