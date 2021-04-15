<?php
namespace Advance_Search\Inc\Admin;
use Advance_Search as WPAS;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://profiles.wordpress.org/mndpsingh287
 * @since      1.0
 *
 * @author    mndpsingh287
 */
class Admin {

	protected $SERVER = 'http://ikon.digital/plugindata/api.php';
	
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The text domain of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $plugin_text_domain    The text domain of this plugin.
	 */
	private $plugin_text_domain;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 * @param string $plugin_text_domain The text domain of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;
		$this->shortcode_counter = get_option('advance-search_shortcode_number');
        $this->plugin_shortcode = empty($this->shortcode_counter  )?3:$this->shortcode_counter ;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0
	 */
	public function enqueue_styles() {

		//custom css for admin
		
		$screen = get_current_screen();
			 
		 if ( in_array( $screen->id, array( 'toplevel_page_advance-search', 'advanced-search_page_wpas-statistics', 'advanced-search_page_wpas_export_import', 'advanced-search_page_wpas_help_update' ) ) )
			{
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/advance-search-admin.css', array(), $this->version, 'all' );
			}
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/common.css', array(), $this->version, 'all' );

		// include fontawesome

		wp_enqueue_style( 'wpas-font-awesome',  plugin_dir_url( dirname( __DIR__ ) ) . 'assets/css/font-awesome.min.css', array(), '', 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0
	 */
	public function enqueue_scripts() {
		
		$screen = get_current_screen();
			 
		if ( in_array( $screen->id, array( 'toplevel_page_advance-search', 'advanced-search_page_wpas-statistics', 'advanced-search_page_wpas_export_import', 'advanced-search_page_wpas_help_update' ) ) )
			{
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advance-search-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );
			}

		wp_enqueue_style( 'wp-color-picker');
        wp_enqueue_script( 'wp-color-picker');

	}

	/**
	 * Callback for the admin menu
	 *
	 * @since    1.0
	 */
	public function add_plugin_admin_menu() {

		$plugin_screen_hook_suffix = add_menu_page(
			__( 'Advanced Search', $this->plugin_text_domain ), // page title.
			__( 'Advanced Search', $this->plugin_text_domain ), // menu title.
			'manage_options', // capability.
			$this->plugin_name, // menu_slug.
			array( $this, 'load_settings_page' ),
			plugin_dir_url( __FILE__ ).'/images/icon_search-pro-wp.svg'
		);

		// Search Statistics
		$search_statistics = add_submenu_page(
			$this->plugin_name,
			__( 'Search Statistics', $this->plugin_text_domain ), // page title.
			__( 'Search Statistics', $this->plugin_text_domain ), // menu title.
			'manage_options', // capability.
			'wpas-statistics', // menu_slug.
			array( $this, 'statistics_settings_page' )
		);

		// import export
		$search_import_export = add_submenu_page(
			$this->plugin_name,
			__( 'Export/Import', $this->plugin_text_domain ), // page title.
			__( 'Export/Import', $this->plugin_text_domain ), // menu title.
			'manage_options', // capability.
			'wpas_export_import', // menu_slug.
			array( $this, 'import_export_settings_page' )
		);

		// help and update
		$wpas_help_update = add_submenu_page(
			$this->plugin_name,
			__( 'Help & Update', $this->plugin_text_domain ), // page title.
			__( 'Help & Update', $this->plugin_text_domain ), // menu title.
			'manage_options', // capability.
			'wpas_help_update', // menu_slug.
			array( $this, 'wpas_update_help_template' )
		);

	}

	// extra setting link

	public function wpas_plugin_link( $links ) {
	    
	    /*
         * Insert the link at the beginning
         */

        $wpas_setting_link[] = '<a href="admin.php?page='.$this->plugin_name.'">' . __('Settings',$this->plugin_text_domain ) . '</a>';
        $wpas_setting_link[] = '<a href="https://searchpro.ai/" target="_blank">' . __('Go Pro' , $this->plugin_text_domain ) . '</a>';
       
        $links = $wpas_setting_link + $links;
    	
    	return $links;
	}

	/**
	 * Callback to load the admin menu page
	 *
	 * @since    1.0
	 */
	public function load_settings_page() {
		// check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		if(isset($_GET['wpas_id']) && intval($_GET['wpas_id']) && intval($_GET['wpas_id']) != '') {
			include_once( 'views/html-advance-search-admin-options.php' );
		}
		else {	
			include_once( 'views/wpas-search.php' );
		}

	}

	/**
	 * Callback to load the Statistics Settings Page
	 *
	 * @since    1.0
	 */
	public function statistics_settings_page() {
		// check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		// include statistics template

		include_once( 'views/search-statistics.php' );

	}

	/**
	 * Callback to load import export template
	 *
	 * @since    1.0
	 */
	public function import_export_settings_page() {
		// check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		// include statistics template

		include_once( 'views/import-export.php' );

	}

	/**
	 * Callback to load update and help template
	 *
	 * @since    1.0
	 */
	public function wpas_update_help_template() {
		// check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		// include statistics template

		include_once( 'views/help-update.php' );

	}


	/**
	 * Callback to save the plugin options
	 *
	 * @since    1.0
	 */
	public function update_plugin_options() {
		register_setting(
			$this->plugin_name, // option group.
			$this->plugin_name, // option name.
			array( $this, 'validate_settings' ) // santize callback.
		);
	}

	/**
	 * 
	 * @since    1.0
	 */
	public function wpas_search_form() {
		
		if( isset( $_POST['wpas-search'] ) && wp_verify_nonce( $_POST['wpas-search'], $this->plugin_name) ) {
			$search_form_name=trim(sanitize_text_field( $_POST['wpas']['search_form_name'] ));
			$data = $this->search_default_settings();

			global $wpdb;
  			$search_form_table = $wpdb->prefix."wpas_index";
			$existing_search_forms = $wpdb->get_var("SELECT count(id) FROM $search_form_table");
			$num_short = $this->plugin_shortcode;
			if($existing_search_forms < $num_short){
				$checkName = $wpdb->get_var ("SELECT COUNT(*) from $search_form_table where name='$search_form_name'");
				if ($checkName == 0) {
					$sql = "INSERT ignore INTO $search_form_table (name, data) VALUES ('$search_form_name', '$data')";
					
					if($wpdb->query($sql)) {
						$form_id = $wpdb->insert_id;
							wp_redirect(admin_url('admin.php?page='. $this->plugin_name.'&wpas_id='.$form_id));
							exit;
					}
				}
				else{	
					wp_redirect(admin_url('admin.php?page='. $this->plugin_name.'&name-already-exists'));
					exit;
				}	
			}
			else {
				wp_redirect(admin_url('admin.php?page='. $this->plugin_name));
				exit;
			}
	}
	}


	/**
	 * 
	 * @since    1.0
	 */
	public function wpas_search_extra_ajax() {

		if(isset($_POST)) {

		$requested_data = sanitize_post($_POST);
		
		$nonce = $requested_data['security'];

		if ( ! wp_verify_nonce( $nonce, 'extra_ajax_nonce' ) ) {
		    echo "false";
		    die();
		}

		$form_id = intval($_POST['form_id']);
		$ajax_type = sanitize_text_field($_POST['ajax_type']);
		
		if (isset($form_id) && $form_id != '') {
			
			// check ajax type

			if(isset($ajax_type) && $ajax_type != '') {

				// check if ajax for clone

				if(isset($ajax_type) && $ajax_type == 'clone_search') {

					$search_form_name=trim(sanitize_text_field( $_POST['search_form_name'] ));
					
					global $wpdb;
					$search_form_table = $wpdb->prefix."wpas_index";
                  	$existing_search_num = get_option('advance-search_shortcode_number');
					$existing_search_forms = $wpdb->get_var("SELECT count(id) FROM $search_form_table");
					
					if($existing_search_forms < $existing_search_num){
						if(!empty($search_form_name)){
						$checkName = $wpdb->get_var ("SELECT COUNT(*) from $search_form_table where name='$search_form_name'");

						$search_form_setting = $wpdb->get_results("SELECT * FROM $search_form_table where id='$form_id'");
						if ($checkName == 0) {
						if(!empty($search_form_setting)) {
							$data = $search_form_setting[0];
							$form_setting = $data->data;
							
							$search_form_table = $wpdb->prefix."wpas_index";
							
							$sql = "INSERT INTO $search_form_table (name, data) VALUES ('$search_form_name', '$form_setting')";
							
							if($wpdb->query($sql)) {
								$lastid = $wpdb->insert_id;
								$responsearray = array('astext' => 'true' );
								echo json_encode($responsearray);
								exit;
							}
						}
						else {
							$responsearray = array('astext' => 'false' );
							echo json_encode($responsearray);
							exit;
						}
					  }
					  else{
						$responsearray = array('astext' => 'already exists');
						echo json_encode($responsearray);
						exit;
					}
					}
					else{
						$responsearray = array('astext' => 'empty');
						echo json_encode($responsearray);
						exit;
					}
				}
					else {
						echo "limit";
						exit;
					}

				} // end clone condition

				// delete search condition

				if(isset($ajax_type) && $ajax_type == 'delete_search') {

					global $wpdb;
					$search_form_table = $wpdb->prefix."wpas_index";
					
					$sql = "DELETE FROM $search_form_table WHERE id='$form_id'";

		            if($wpdb->query($sql)) {
		            	$responsearray = array('astext' => 'true');
						echo json_encode($responsearray);
		            	exit;
		            }
		            else {
		            	$responsearray = array('astext' => 'false');
						echo json_encode($responsearray);
		            	exit;
		            }

				} // end delete condition

			}

		}
	}
	}

	// default search form setting

	public function wpas_default_search_form_settings() {

		if( isset( $_POST['wpas-default-search-form'] ) && wp_verify_nonce( $_POST['wpas-default-search-form'], 'default_search_form') ) {

			$search_type = sanitize_text_field($_POST['search_type']);
			
			if(isset($search_type) && $search_type != '') {

				if($search_type == 'default_theme_search_form') {

					$search_form_id = intval($_POST['default_search_form_id']);
					
					$update_option = update_option($this->plugin_name.'_default_search', $search_form_id);

					if(isset($update_option)) {
						wp_redirect(admin_url('admin.php?page='. $this->plugin_name.'&theme-search-replaced' ));
						exit;
					}

				} // end default theme search

				if($search_type == 'default_woo_search_form') {

					$search_form_id = intval($_POST['default_woo_search_form_id']);

					$update_option = update_option($this->plugin_name.'_default_woo_search', $search_form_id);

					if(isset($update_option)) {
						wp_redirect(admin_url('admin.php?page='. $this->plugin_name ));
						exit;
					}

				} // end default woo search				


			}



		}

	}

	/**
	 * 
	 * @since    1.0
	 */
	public function wpas_search_form_settings() {
		
		global $wpdb;
  		$search_form_table = $wpdb->prefix."wpas_index"; // index table
		$post_table = $wpdb->prefix."posts"; // post table
		$attachment_table = $wpdb->prefix."wpas_attachment"; // attachment table
		
		// reset for setting
		if( isset( $_POST['search_setting_reset'] ) && wp_verify_nonce( $_POST['search_setting_reset'], 'reset_form_settings') ) {
			
			$form_id = intval($_POST['search_form_id']);
			$data = $this->search_default_settings();
			
			$sql = "Update $search_form_table SET data='$data' where id='$form_id'";
			
            if($wpdb->query($sql)) {
            	$redirect_url = admin_url().'admin.php?page='.$this->plugin_name.'&wpas_id='.$form_id."&msg=2";
				wp_redirect($redirect_url);
				exit;
			}
			else{
				$redirect_url = admin_url().'admin.php?page='.$this->plugin_name.'&wpas_id='.$form_id."&msg=2";
				wp_redirect($redirect_url);
				exit;
			}

		}

		// update form settings

		if( isset( $_POST['wpas-search_setting'] ) && wp_verify_nonce( $_POST['wpas-search_setting'], 'search_form_settings') ) {
			$search_form_setting = sanitize_post($_POST['search_form_setting']);

			$form_id = intval($_POST['search_form_setting']['form_id']);
			unset($search_form_setting['form_id']);
			$data = json_encode($search_form_setting);
  			
  			// update index table settings

			$sql = "Update $search_form_table SET data='$data' where id='$form_id'";
            if($wpdb->query($sql)) {
            	$redirect_url = admin_url().'admin.php?page='.$this->plugin_name.'&wpas_id='.$form_id."&msg=1";
				wp_redirect($redirect_url);
				exit;
			} else {
				$redirect_url = admin_url().'admin.php?page='.$this->plugin_name.'&wpas_id='.$form_id."&msg=0";
				wp_redirect($redirect_url);
				exit;
			}
		}

	}


	// default data for new search added

	public function search_default_settings() {

		$option = array (
		  'post_types' =>
		  array (
		    'post_types' =>
		    array (
		      'post' => 'post',
		      'page' => 'page',
		      ),
		    'post_exclude' => ''
		  ),
		  'styling' =>
		  array (
		    'search_box_outer' =>
		    array (
		      'width' =>
		      array (
		        'desktop' => '100%',
		        'tablet' => '100%',
		        'mobile' => '100%',
		      ),
		      'height' => '40',
		      'margin' =>
		      array (
		        'top' => '0',
		      ),
		      'bg_color' => '',
		      'border_type' => 'none',
		      'border_px' => '0',
		      'border_color' => '#1e73be',
		      'border_radius' =>
		      array (
		        'top' => '5',
		        'right' => '5',
		        'bottom' => '5',
		        'left' => '5',
		      ),
		    ),
		    'search_input' =>
		    array (
		      'bg_color' => '#f7f7f7',
		      'font_color' => '#1a1a1a',
		      'font_size' => '12',
		      'line_height' => '12',
		      'border_type' => 'none',
		      'border_px' => '2',
		      'border_color' => '#1e73be',
		      'border_radius' =>
		      array (
		        'top' => '0',
		        'right' => '0',
		        'bottom' => '0',
		        'left' => '0',
		      ),
		    ),
		    'magnifire' =>
		    array (
		      'icon' => 'search',
		      'color' => '#ffffff',
		      'bg_color' => '#1a1a1a',
		      'position' => 'right',
		    ),
		    'loader' =>
		    array (
		      'icon' => 'sbl-circ',
		      'color' => '#1a1a1a',
		    ),
		    'search_button' =>
		    array (
		      'text' => 'Search',
		      'font_color' => '#ffffff',
		      'show_search_text' => 'show_search_text',
		      'show_maginfier_icon' => 'show_maginfier_icon',
		      'font_size' => '14',
		    ),
		  ),
		'search_type' => 'partial_word',
		);

	$default_options = json_encode($option);
	return $default_options;

	}

	// Beaver Builder element

	public function editor_element_init_actions() {

	    // include gutnburg widget
	    require_once 'call-to-action-editable.php';

	}


	/************************ wp custom editor element ****************************/

	// Hooks your functions into the correct filters

	public function wpas_add_mce_button() {
	    // check user permissions
	    if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
	        return;
	    }
	    // check if WYSIWYG is enabled
	    if ( 'true' == get_user_option( 'rich_editing' ) ) {
	        add_filter( 'mce_external_plugins', array($this, 'wpas_add_tinymce_plugin' ));
	        add_filter( 'mce_buttons', array($this, 'wpas_register_mce_button' ));
	    }
	}

	// Declare script for new button
	public function wpas_add_tinymce_plugin( $plugin_array ) {
	    $plugin_array['wasp_mce_button'] = plugins_url( '/', __FILE__ )."js/buttons.js";
	    return $plugin_array;
	}

	// Register new button in the editor
	public function wpas_register_mce_button( $buttons ) {
	    array_push( $buttons, 'wasp_mce_button' );
	    return $buttons;
	}

	// Generate the buttons JS variable

	public function wpas_mce_generate_variable($settings) {
	    global $wpdb;
	    $asp_instances = $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."wpas_index", ARRAY_A);

	    $menu_items = array();
	    $menu_result_items = array();
	    $menu_setting_items = array();
	    $menu_two_column_items = array();

	    if (is_array($asp_instances)) {
	      foreach ($asp_instances as $x => $instance) {
	          $id = $instance['id'];
	          $menu_items[] = "{text: '".$instance['name']." ([wpas id=$id])',onclick: function() {editor.insertContent('[wpas id=$id]');}}";
	      }
	    }
	    ?>
	    
	    <?php if (count($menu_items)>0): ?>
	    <?php $menu_items = implode(", ", $menu_items); ?>
	    <script type="text/javascript">
	        wpas_mce_button_menu = "<?php echo $menu_items; ?>";
	    </script>
	<?php endif;
	    return $settings;
	}

	/* Verify Email*/
    
    public function advance_search_verify_email_callback() {
        $current_user = wp_get_current_user();
        $nonce = $_REQUEST['vle_nonce'];
        if (wp_verify_nonce($nonce, 'verify-wpas-email')) {
            $action = sanitize_text_field($_POST['todo']);
            $lokhal_email = sanitize_text_field($_POST['lokhal_email']);
            $lokhal_fname = sanitize_text_field($_POST['lokhal_fname']);
            $lokhal_lname = sanitize_text_field($_POST['lokhal_lname']);
            // case - 1 - close
            if ($action == 'cancel') {
                set_transient('wpas_cancel_lk_popup_'.$current_user->ID, 'wpas_cancel_lk_popup_'.$current_user->ID, 60 * 60 * 24 * 30);
                update_option('wpas_email_verified_'.$current_user->ID, 'yes');
            } elseif ($action == 'verify') {
                $engagement = '75';
                update_option('wpas_email_address_'.$current_user->ID, $lokhal_email);
                update_option('verify_wpas_fname_'.$current_user->ID, $lokhal_fname);
                update_option('verify_wpas_lname_'.$current_user->ID, $lokhal_lname);
                update_option('wpas_email_verified_'.$current_user->ID, 'yes');
                /* Send Email Code */
                $subject = 'Email Verification';
                $message = "
				<html>
				<head>
				<title>Email Verification</title>
				</head>
				<body>
				<p>Thanks for signing up! Just click the link below to verify your email and we’ll keep you up-to-date with the latest and greatest brewing in our dev labs!</p>	
				<p><a href='".admin_url('admin-ajax.php?action=verify_wpas_email&token='.md5($lokhal_email))."'>Click Here to Verify
</a></p>				
				</body>
				</html>
				";
                // Always set content-type when sending HTML email
                $headers = 'MIME-Version: 1.0'."\r\n";
                $headers .= 'Content-type:text/html;charset=UTF-8'."\r\n";
                $headers .= 'From: noreply@ikon.digital'."\r\n";
                $mail = mail($lokhal_email, $subject, $message, $headers);
                $data = $this->verify_on_server($lokhal_email, $lokhal_fname, $lokhal_lname, $engagement, 'verify', '0');
                if ($mail) {
                    echo '1';
                } else {
                    echo '2';
                }
            }
        } else {
            echo 'Nonce';
        }
        die;
    }

    /*
    * Verify Email
    */
    public function verify_wpas_email_callback() {
        $email = sanitize_text_field($_GET['token']);
        $current_user = wp_get_current_user();
        $lokhal_email_address = md5(get_option('wpas_email_address_'.$current_user->ID));
        if ($email == $lokhal_email_address) {
            $this->verify_on_server(get_option('wpas_email_address_'.$current_user->ID), get_option('verify_wpas_fname_'.$current_user->ID), get_option('verify_wpas_lname_'.$current_user->ID), '100', 'verified', '1');
            update_option('wpas_email_verified_'.$current_user->ID, 'yes');
            echo '<p>Email Verified Successfully. Redirecting please wait.</p>';
            echo '<script>';
            echo 'setTimeout(function(){window.location.href="https://searchpro.ai/?utm_redirect=wp" }, 2000);';
            echo '</script>';
        }
        die;
    }

    /*
    Send Data To Server
    */
    public function verify_on_server($email, $fname, $lname, $engagement, $todo, $verified) {
        global $wpdb, $wp_version;
        if (get_bloginfo('version') < '3.4') {
            $theme_data = get_theme_data(get_stylesheet_directory().'/style.css');
            $theme = $theme_data['Name'].' '.$theme_data['Version'];
        } else {
            $theme_data = wp_get_theme();
            $theme = $theme_data->Name.' '.$theme_data->Version;
        }

        // Try to identify the hosting provider
        $host = false;
        if (defined('WPE_APIKEY')) {
            $host = 'WP Engine';
        } elseif (defined('PAGELYBIN')) {
            $host = 'Pagely';
        }
        $mysql_ver = @mysqli_get_server_info($wpdb->dbh);
        $id = get_option('page_on_front');
        $info = array(
                     'email' => $email,
                     'first_name' => $fname,
                     'last_name' => $lname,
                     'engagement' => $engagement,
                     'SITE_URL' => site_url(),
                     'PHP_version' => phpversion(),
                     'upload_max_filesize' => ini_get('upload_max_filesize'),
                     'post_max_size' => ini_get('post_max_size'),
                     'memory_limit' => ini_get('memory_limit'),
                     'max_execution_time' => ini_get('max_execution_time'),
                     'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
                     'wp_version' => $wp_version,
                     'plugin' => 'advanced search',
                     'nonce' => 'um235gt9duqwghndewi87s34dhg',
                     'todo' => $todo,
                     'verified' => $verified,
            );
        $str = http_build_query($info);
        $args = array(
            'body' => $str,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
        );

        $response = wp_remote_post($this->SERVER, $args);

        return $response;
    }

}