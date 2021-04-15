<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://profiles.wordpress.org/mndpsingh287
 * @since      1.0
 *
 * @package    WPAS_Advance_Search
 * @subpackage WPAS_Advance_Search/inc/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$current_user = wp_get_current_user(); 
$opt = get_option('wp_advance_search_settings');

?>
<script>
var vle_nonce = "<?php echo wp_create_nonce('verify-wpas-email');?>";
</script>
<link rel='stylesheet' href="<?php echo plugins_url().'/'.$this->plugin_name; ?>/inc/admin/css/popup.css" type='text/css' media='all' />

<?php
global $wpdb;
$search_form_table = $wpdb->prefix."wpas_index";
$search_forms = $wpdb->get_results("SELECT id, name FROM $search_form_table");

$default_search_form_id = get_option($this->plugin_name.'_default_search');
$default_woo_search_form_id = get_option($this->plugin_name.'_default_woo_search');
?>
<div class="wrap">
<div class="advance_serach_sec">
<div class="search_box">
	<h3><?php echo esc_html( get_admin_page_title() ); ?></h3>
	<form class="form" action="<?php echo (count($search_forms) < 3) ? esc_url( admin_url( 'admin-post.php' ) ) : '#'; ?>" method="post" id="">
		<input type="hidden" name="action" value="<?php echo (count($search_forms) < 3) ? 'wpas_search_form_response' : '';?>">
		<input type="hidden" name="wpas-search" value="<?php echo (count($search_forms) < 3) ? wp_create_nonce($this->plugin_text_domain) : ''; ?>" />
		
        <div>
			<label for="<?php echo $this->plugin_text_domain; ?>-search_form"> <?php _e('Shortcode Name:', $this->plugin_text_domain); ?> </label>
			<input required maxlength="20" id="<?php echo $this->plugin_text_domain; ?>-search_form" type="text" name="<?php echo "wpas"; ?>[search_form_name]" value="" placeholder="<?php _e('Enter Shortcode Name', $this->plugin_text_domain);?>"<?php echo (count($search_forms) < 3) ? '' : ' readonly="readonly"' ;?>/>
            <div class="submit"><input type="submit" name="submit" id="submit" class="<?php echo (count($search_forms) == 3) ? 'pointer-event-none' : '';?> btn-submit button button-primary" value="<?php _e('Create', $this->plugin_text_domain); ?>" <?php echo (count($search_forms) < 3) ? '' : ' ';?>/></div>
			<?php if(count($search_forms) >= 3){ ?>
			<p class="pro-info">* <?php _e( 'You have reached the maximum form limit of 3 forms. <a href="https://searchpro.ai/" target="_blank" class="go_pro_link">Buy Pro</a> for more search forms.', $this->plugin_text_domain); ?></p>
			<?php }?>
            <?php if(isset($_GET['name-already-exists'])){ ?>
			<p class="pro-info">* <?php _e('This name is already exists.', $this->plugin_text_domain); ?></p>
			<?php }?>
		</div>
	</form>
<div class="secColInfo">
	<h3><?php echo esc_attr__('Replace search bar', $this->plugin_text_domain); ?></h3>
	
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="themeSearch_option">
		<label><?php echo esc_attr__('Replace default search bar with:', $this->plugin_text_domain); ?></label>
		<input type="hidden" name="action" value="wpas_default_search_form_response" />
		<input type="hidden" name="wpas-default-search-form" value="<?php echo wp_create_nonce('default_search_form'); ?>" />
		<input type="hidden" name="search_type" value="default_theme_search_form" />
     
       <div class="box_dropdown_advance">
		<select name="default_search_form_id" class="theme_replaced" required>
			<option class="none-option" value="" <?php if($default_search_form_id == '0') {echo 'selected="selected"'; } ?>><?php echo esc_attr__('None', $this->plugin_text_domain); ?></option>
			<?php
			if(!empty($search_forms)){
				foreach ($search_forms as $search_form_name) {
					?>
					<option value="<?php echo $search_form_name->id; ?>" <?php if($default_search_form_id == $search_form_name->id) {echo 'selected="selected"'; } ?>><?php echo $search_form_name->name; ?></option>
					<?php
				}
			}
			?>
		</select>
        <?php if(isset($_GET['theme-search-replaced'])){ ?>
            <div class="updated notice is-dismissible">
					<p>Successfully Replaced.<button type="button" id="ad_dismiss" class="notice-dismiss" data_url=""><span class="screen-reader-text">Dismiss this notice.</span></button></p>
				</div>
			
			<?php }?>
	</div>
		<p class="submit"><input type="submit" name="submit" id="replacesubmit" class="button button-primary" value="Save"></p>
		
	</form>


	<!-- replace woo search -->

	<?php
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	?>
	<div class="pro_feature buy_pro_wrapper">
		<div class="buy_pro">
			<a href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro', $this->plugin_text_domain ); ?>
				<i class="fa fa-diamond" aria-hidden="true"></i>
			</a>
		</div>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<label><?php echo esc_attr__('Replace the default woocommerce search with:', $this->plugin_text_domain); ?></label>
       <div class="box_dropdown_advance">
		<select name="default_woo_search_form_id">
			<option value="0" <?php if($default_woo_search_form_id == '0') {echo 'selected="selected"'; } ?>>None</option>
			<?php
			if(!empty($search_forms)){
				foreach ($search_forms as $search_form_name) {
					?>
					<option value="<?php echo $search_form_name->id; ?>" <?php if($default_woo_search_form_id == $search_form_name->id) {echo 'selected="selected"'; } ?>><?php echo $search_form_name->name; ?></option>
					<?php
				}
			}
			?>
		</select>
	</div>
		<p class="submit"><input type="submit" name="submit" id="prosubmit" class="btn-submit button button-primary" value="Save"></p>
	</form>
</div>

	<?php } // endif ?>

</div>
</div>
<div class="search_box_list">
	<h3><?php echo esc_attr__('List of shortcodes', $this->plugin_text_domain); ?></h3>
    
    <ul>
	<?php
		if(!empty($search_forms)){
			?>
			<input type="hidden" value="<?php echo wp_create_nonce('extra_ajax_nonce'); ?>" id="extra_ajax_hidden" />
			<?php
        $i = 1;
            
			foreach ($search_forms as $search_form_name) {
			?>
			<li>
                <span class="num_style"><?php echo $i;?>.</span> <span class="content_style">
                <?php echo $search_form_name->name;?></span><input type="text" class="quick_shortcode" value="[wpas id=<?php echo $search_form_name->id; ?>]" readonly="readonly"> 
				<span class="icons_sec">
				   <a href="<?php echo admin_url().'admin.php?page='.$this->plugin_name.'&wpas_id='.$search_form_name->id; ?>" title="Edit Settings"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | <a href="javascript:void(0)" data-targent="ClonePopup" data-id="<?php echo $search_form_name->id; ?>" data-scname="<?php echo $search_form_name->name; ?>" data-type="clone_search" class="asearch_imp_ajax aclone_search" id="aclonesearch" data-ajax='Yes' title="Clone Settings" data_url="<?php echo admin_url('admin.php?page=advance-search')?>"><i class="fa fa-clone" aria-hidden="true"></i></a>| <a href="javascript:void(0)" data-id="<?php echo $search_form_name->id; ?>" data-type="delete_search" data-ajax='No' class="search_imp_ajax delete_search" title="Delete Search"><i class="fa fa-trash" aria-hidden="true"></i></a>
			   </span> 
		   </li>
			<?php
			$i++;
			}
		}
		else {
			echo "<p class='pl-10'>Oops! Shortcode(s) not found.<p>";
		}
	?>
</ul>
</div>
</div>

<div class="sideBar_Section" style="margin-top:20px;">
<div class="secColStyle">
<h3><?php echo esc_attr__('Advanced Search', $this->plugin_text_domain); ?></h3>
<div class="starts">
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
</div>
<p><?php echo esc_attr__('We love and care about you. Our team is putting maximum efforts to provide you the best functionalities. It would be highly appreciable if you could spend a couple of seconds to give a Nice Review to the plugin to appreciate our efforts. So we can work hard to provide new features regularly.', $this->plugin_text_domain); ?></p>
<button class="button button-primary"><a href="https://wordpress.org/plugins/advance-search#reviews" target="_blank"><?php echo esc_attr__('Rate Us', $this->plugin_text_domain); ?></a></button>
</div>
<div class="secColStyle pro_sec">
<h3><?php echo esc_attr__('Go Pro', $this->plugin_text_domain); ?></h3>
<p><?php echo esc_attr__('Even more features available in Advanced Search Pro.', $this->plugin_text_domain); ?></p>
<div class="btn"><a class="go_pro_button" href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro', $this->plugin_text_domain); ?> <i class="fa fa-diamond" aria-hidden="true"></i></a></div>
</div>
</div>

</div>
<div class="wpas_loader"><img src="<?php echo plugins_url().'/'.$this->plugin_name; ?>/inc/admin/images/loader3.gif" /></div>

<style type="text/css">
.pro_feature .buy_pro a {
    position: absolute;
    top: 50%;
    text-align: center;
    left: 50%;
    background-color: #ff3547 !important;
    color: #fff;
    text-decoration: none;
    padding: 6px 14px !important;
    transform: translate(-50%, -50%);
}
.pro_feature .buy_pro {
    height: 100%;
    top: 0;
    z-index: 99;
}
</style>

<div class="popup_wrapper">

<?php ///***** Verify Lokhal Popup Start *****///
          //delete_transient( 'wpas_cancel_lk_popup_'.$current_user->ID );
        if (false === get_option('wpas_email_verified_'.$current_user->ID) && (false === (get_transient('wpas_cancel_lk_popup_'.$current_user->ID)))) {
        ?>
        <div id="lokhal_verify_email_popup" class="lokhal_verify_email_popup">
            <div class="lokhal_verify_email_popup_overlay"></div>
            <div class="lokhal_verify_email_popup_tbl">
                <div class="lokhal_verify_email_popup_cel">
                    <div class="lokhal_verify_email_popup_content">
                        <a href="javascript:void(0)" class="lokhal_cancel"> <img src="<?php echo plugins_url('images/fm_close_icon.png', dirname(__FILE__)); ?>"
                                class="wp_fm_loader" /></a>
                        <div class="popup_inner_lokhal">
                            <h3>
                                <?php _e('Welcome to Advanced Search', 'advance-search'); ?>
                            </h3>
                            <p class="lokhal_desc">
                                <?php _e('We love making new friends! Subscribe below and we promise to
    keep you up-to-date with our latest new plugins, updates,
    awesome deals and a few special offers.', 'advance-search'); ?>
                            </p>
                            <form>
                                <div class="form_grp">
                                    <div class="form_twocol">
                                        <input name="verify_lokhal_fname" id="verify_lokhal_fname" class="regular-text"
                                            type="text" value="<?php echo (null == get_option('verify_wpas_fname_'.$current_user->ID)) ? $current_user->user_firstname : get_option('verify_wpas_fname_'.$current_user->ID); ?>"
                                            placeholder="First Name" maxlength="20"/>
                                        <span id="fname_error" class="error_msg">
                                            <?php _e('Please Enter First Name.', 'advance-search'); ?></span>
                                    </div>
                                    <div class="form_twocol">
                                        <input name="verify_lokhal_lname" id="verify_lokhal_lname" maxlength="20" class="regular-text"
                                            type="text" value="<?php echo (null ==
            get_option('verify_wpas_lname_'.$current_user->ID)) ? $current_user->user_lastname : get_option('verify_wpas_lname_'.$current_user->ID); ?>"
                                            placeholder="Last Name" />
                                        <span id="lname_error" class="error_msg">
                                            <?php _e('Please Enter Last Name.', 'advance-search'); ?></span>
                                    </div>
                                </div>
                                <div class="form_grp">
                                    <div class="form_onecol">
                                        <input name="verify_lokhal_email" id="verify_lokhal_email" class="regular-text"
                                            type="email" value="<?php echo (null == get_option('wpas_email_address_'.$current_user->ID)) ? $current_user->user_email : get_option('wpas_email_address_'.$current_user->ID); ?>"
                                            placeholder="Email Address" />
                                        <span id="email_error" class="error_msg">
                                            <?php _e('Please Enter Email Address.', 'advance-search'); ?></span>
											<span id="email_error_valid" class="error_msg"><?php _e('Please Enter Valid Email Address.', 'advance-search'); ?></span>
                                    </div>
                                </div>
                                <div class="btn_dv">
                                    <button class="verify verify_local_email button button-primary "><span class="btn-text"><?php _e('Verify', 'advance-search'); ?>
                                        </span>
                                        <span class="btn-text-icon">
                                            <img src="<?php echo plugins_url('images/btn-arrow-icon.png', dirname(__FILE__)); ?>" />
                                        </span></button>
                                    <button class="lokhal_cancel button">
                                        <?php _e('No Thanks', 'advance-search'); ?></button>
                                </div>
                            </form>
                        </div>
                        <div class="fm_bot_links">
                            <a href="https://searchpro.ai/terms-condition/" target="_blank">
                                <?php _e('Terms of Service', 'advance-search'); ?></a> <a href="https://searchpro.ai/privacy-policy/"
                                target="_blank">
                                <?php _e('Privacy Policy', 'advance-search'); ?></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php
   } ///***** Verify Lokhal Popup End *****///?>

</div>

<div class="fm_msg_popup">
    <div class="fm_msg_popup_tbl">
        <div class="fm_msg_popup_cell">
            <div class="fm_msg_popup_inner">
                <div class="fm_msg_text">
                    <?php _e('Saving...', 'advance-search'); ?>
                </div>
                <div class="fm_msg_btn_dv"><a href="javascript:void(0)" class="fm_close_msg button button-primary"><?php _e('OK', 'advance-search'); ?></a></div>
            </div>
        </div>
    </div>
</div>

<div id="ClonePopup" class="asoverlay">
	<div class="aspopup">
        <div class="aspopup-header">
            <h2>Clone Shortcode: <span class="csname-heading"></span></h2>
    		<a class="close" href="#">&times;</a>
        </div>
		<div class="content aspopup-content">
        <form class="form" action="" method="post" >
            <input type="hidden" name="action"  value="<?php echo (count($search_forms) < 3) ? 'wpas_search_form_response' : '';?>">
             <span class="icons_sec">
            <input type="hidden" name="wpas-search" id="wpas-search" value="<?php echo (count($search_forms) < 3) ? wp_create_nonce($this->plugin_text_domain) : ''; ?>" />
            <div>
				
				 <?php if(count($search_forms) >= 3){ ?>
                
                <p class="pro-infomax pt-0">* <?php _e( 'You have reached the maximum form limit of 3 forms. <a href="https://searchpro.ai/" target="_blank" class="go_pro_link">Buy Pro</a> for more search forms.', $this->plugin_text_domain); ?>
				</p>
				 <?php } else{ ?>
				 <div class="aspopup-form-area">
                
                <input required maxlength="20" id="<?php echo $this->plugin_text_domain; ?>-ajaxsearch_form" type="text" name="<?php echo "wpas"; ?>[search_form_name]" value="" placeholder="<?php _e('Enter Shortcode Name', $this->plugin_text_domain);?>"<?php echo (count($search_forms) < 3) ? '' : ' readonly="readonly"' ;?>/>
                
                <input type="button" name="submit" id="clone_search" data-ajax='Yes' title="Clone Settings" data_url="<?php echo admin_url('admin.php?page=advance-search')?>" data-id="<?php echo $search_form_name->id; ?>" data-type="clone_search" class="search_imp_ajax clone_search" value="<?php _e('Clone', $this->plugin_text_domain); ?>" <?php echo (count($search_forms) < 3) ? '' : ' ';?>/>
				</div>

               <?php }?>
                <p class="pro-info as-alreadyexists" style="display:none">* <?php _e('This name is already exists.', $this->plugin_text_domain); ?></p>
                <p class="pro-info as-validname" style="display:none">* <?php _e('Please enter Shortcode Name.', $this->plugin_text_domain); ?></p>
                <p class="pro-infoq as-success" style="display:none"><?php _e('Shortcode Successfully created.', $this->plugin_text_domain); ?></p>
            </div>
	    </form>
		</div>
	</div>
</div>