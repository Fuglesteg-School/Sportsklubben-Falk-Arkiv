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


$search_form_id = isset($_GET['wpas_id']) ? intval($_GET['wpas_id']) : 0;

global $wpdb;
$search_form_table = $wpdb->prefix."wpas_index";
$search_form_setting = $wpdb->get_results("SELECT * FROM $search_form_table where id='$search_form_id'");

$form_name = $search_form_setting[0]->name;

if($search_form_id == 0 || $wpdb->num_rows == 0) {
	echo "Search form not found !";
	exit;
}

if($wpdb->num_rows > 0) {

$settings = json_decode($search_form_setting[0]->data, true);

$args = array(
	'public' => true,
	//'_builtin' => false, //exclude attachment, revision etc.
);
$advance_search_excludeTaxonomy = array('product_shipping_class');
// taxonomies args
$taxonomies_args = array(
  'public'   => true,
  //'_builtin' => false
); 

// append posts and pages to the post types.
$post_types = get_post_types( $args, 'objects' );

unset($post_types['attachment']); // exclude attachment from loop

$output = 'names'; // or objects
$operator = 'and'; // 'and' or 'or'
$taxonomies = get_taxonomies( $taxonomies_args, $output, $operator );

unset($taxonomies['post_format']);

?>

<div class="wrap">
<div class="accordion_section advance_serach_sec">
	<div class="wpas_search">

	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="">
		<input type="hidden" name="action" value="wpas_search_form_settings">
		<input type="hidden" name="wpas-search_setting" value="<?php echo wp_create_nonce('search_form_settings'); ?>" />
		<input type="hidden" name="search_form_setting[form_id]" value="<?php echo $search_form_id; ?>">

	<div class="heading">
		<h3><?php echo esc_html( get_admin_page_title() ); ?></h3>
		<div class="imp_link">
			<a class="back" href="<?php echo admin_url().'admin.php?page='.$this->plugin_name; ?>"><?php echo esc_attr__('Back to the search list', $this->plugin_text_domain); ?></a>
			<a class="statistics" href="<?php echo admin_url().'admin.php?page=wpas-statistics' ?>"><?php echo esc_attr__('Search Statistics', $this->plugin_text_domain); ?></a>
			<a class="go_pro_button" href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro ', $this->plugin_text_domain); ?> <i class="fa fa-diamond" aria-hidden="true"></i></a>
		</div>
	</div>

	<div class="wpas_search_left">

	<?php 
	if(isset($_GET["msg"])){
		$msg = (int) sanitize_text_field($_GET["msg"]);
		switch($msg){
			case 0:
				?>
				<div class="error notice is-dismissible">
					<p><?php echo esc_attr__( 'You haven\'t made any changes in settings to be saved.', $this->plugin_text_domain ); ?><button type="button" id="ad_dismiss" class="notice-dismiss" data_url="<?php echo admin_url('admin.php?page=advance-search')."&wpas_id=".$_GET["wpas_id"];?>"><span class="screen-reader-text"><?php echo esc_attr__( 'Dismiss this notice.', $this->plugin_text_domain ); ?></span></button></p>
				</div>
				<?php
			break;
			case 1:
				?>
				<div class="updated notice is-dismissible">
					<p><?php echo esc_attr__( 'Settings updated successfully.', $this->plugin_text_domain ); ?><button type="button" id="ad_dismiss" class="notice-dismiss" data_url="<?php echo admin_url('admin.php?page=advance-search')."&wpas_id=".$_GET["wpas_id"];?>"><span class="screen-reader-text"><?php echo esc_attr__( 'Dismiss this notice.', $this->plugin_text_domain ); ?></span></button></p>
				</div>
				<?php
			break;
			case 2:
				?>
				<div class="updated notice is-dismissible">
					<p><?php echo esc_attr__( 'Settings has been restored successfully.', $this->plugin_text_domain ); ?><button type="button" id="ad_dismiss" class="notice-dismiss" data_url="<?php echo admin_url('admin.php?page=advance-search')."&wpas_id=".$_GET["wpas_id"];?>"><span class="screen-reader-text"><?php echo esc_attr__( 'Dismiss this notice.', $this->plugin_text_domain ); ?></span></button></p>
				</div>
				<?php
			break;
		}
	}
	?>
		<ul class="accordion">
		  <li>
		    <a class="toggle" href="#"><i class="fa fa-cogs" aria-hidden="true"></i> <?php echo esc_attr__('Shortcode for ', $this->plugin_text_domain); ?><b><?php echo $form_name; ?></b></a>
		    <div class="inner shortcode_inputSec">
		    	<h4 class="title_heading">
					<?php echo esc_attr__( 'Simple Shortcode', $this->plugin_text_domain ); ?>
				</h4>
		    	<?php
		    		$simple_shortcode_template = '<?php echo do_shortcode("[wpas id='.$search_form_id.' title=CustomTitle]"); ?>';
		    	?>
              <div class="shortCol">
				<label>Search Shortcode:</label>
				<input type="text" value="[wpas id=<?php echo $search_form_id; ?>]" readonly="readonly">
		        </div>
                 <div class="shortCol">
		     	<label>Add title for Search:</label><br/>
		     	<input type="text" value="[wpas id=<?php echo $search_form_id; ?> title='CUSTOM TITLE GOES HERE']" readonly="readonly">
				</div>	
				<h4>Extra for php template use</h4>
				<?php highlight_string($simple_shortcode_template); ?>
		    </div>
		  </li>
		  
		  <li id="advance_search_posttype_chkbox">
		    <a class="toggle" href="#"><i class="fa fa-list" aria-hidden="true"></i> <?php echo esc_attr__( 'Post Types', $this->plugin_text_domain ); ?></a>
		    <div class="inner">
		    <h4 class="title_heading">
				<?php echo esc_attr__( 'Select Post Types to Include in the Advanced Search', $this->plugin_text_domain ); ?>
			</h4>
		
			<?php
				foreach ( $post_types  as $post_type ) {
					$the_post_type = $post_type->name;
					$the_post_type_label = $post_type->label;
					?>
					<fieldset class="checkbox_toggleSec">
						<legend class="screen-reader-text"><span><?php echo esc_attr__( 'Setting for ', $this->plugin_text_domain ) . $the_post_type_label; ?></span></legend>

						<label for="<?php echo esc_attr( $this->plugin_name . '_' . $the_post_type ); ?>">
							<div>
					        <label class="el-switch el-switch-blue">
						        <input type="checkbox" data-ptag="postSearch" class="checkarea" id="<?php echo esc_attr( $this->plugin_name . '-' . $the_post_type ); ?>" class="postCheckbox" name="search_form_setting[post_types][post_types][<?php echo esc_attr( $the_post_type ); ?>]" value="<?php echo esc_attr( $the_post_type ); ?>" <?php if(array_key_exists('post_types', $settings['post_types'])) { if (in_array($the_post_type, $settings['post_types']['post_types'])) { echo 'checked="checked"'; } } ?> />
						        <span class="el-switch-style"></span>
					        </label>
							<span class="title"><?php echo esc_attr__( $the_post_type_label, $this->plugin_text_domain ); ?></span>
						 </div></label> 
					</fieldset>
				<?php
				} // post type foreach end
			?>

		<hr class="section_seprate search_box_expand" />
		
		<div class="search_areas sep_section search_box_expand" id="postSearch">
			<h4 class="title_heading">
				<?php echo esc_attr__('Search Area', $this->plugin_text_domain ); ?>
			</h4>

			<p class="content_Style"><?php echo esc_attr__('* By enable specific area of search, search result relative from specific area i.e title, description etc.', $this->plugin_text_domain ); ?></p>
			<fieldset class="checkbox_toggleSec">
				<label for="<?php echo esc_attr( $this->plugin_name . '_post_search_title' ); ?>">
					<div>
					  <label class="el-switch el-switch-blue">
						  <input type="checkbox" class="subcheckbox subposting" data-check="subposting" id="<?php echo esc_attr( $this->plugin_name . '-_post_search_title' ); ?>" name="search_form_setting[post_types][search_areas][]" value="<?php echo esc_attr('post_title'); ?>" <?php if(array_key_exists('search_areas', $settings['post_types'])) { if (in_array('post_title', $settings['post_types']['search_areas'])) { echo 'checked="checked"'; } } ?> />
						  <span class="el-switch-style"></span>
					  </label>
					<span class="title"><?php echo esc_attr__( 'Search in Title', $this->plugin_text_domain ); ?></span>
				</div>
				</label>
			</fieldset>

			<fieldset class="checkbox_toggleSec">
				<label for="<?php echo esc_attr( $this->plugin_name . '_post_search_desc' ); ?>">
				<div>
				 <label class="el-switch el-switch-blue">
					<input type="checkbox" class="subcheckbox subposting" data-check="subposting" id="<?php echo esc_attr( $this->plugin_name . '-_post_search_desc' ); ?>" name="search_form_setting[post_types][search_areas][]" value="<?php echo esc_attr('post_content'); ?>" <?php if(array_key_exists('search_areas', $settings['post_types'])) { if (in_array('post_content', $settings['post_types']['search_areas'])) { echo 'checked="checked"'; }} ?> />
					 <span class="el-switch-style"></span>
				 </label>
					<span class="title"><?php echo esc_attr__( 'Search in Content', $this->plugin_text_domain ); ?></span>
				</div>
				</label>
			</fieldset>
			<hr class="section_seprate" />
		</div>

		
		
		<div class="post_meta_keyvalue_section sep_section">
			<h4 class="title_heading">
				<?php echo esc_attr__('Search by Post Meta key and Value', $this->plugin_text_domain ); ?>
			</h4>

			<p class="content_Style warning"><?php echo esc_attr__('* Search may slower by post meta key search.', $this->plugin_text_domain ); ?></p>
			
			<label><?php echo esc_attr__('List of Post Meta Keys', $this->plugin_text_domain ); ?></label>
				<input type="text" name="search_form_setting[post_types][meta_keys][]" value="<?php if (array_key_exists('meta_keys', $settings['post_types'])) { echo $settings['post_types']['meta_keys'][0]; } ?>" />
			<p class="content_Style"><?php echo esc_attr__('* Comma "," separated list of post meta keys i.e Metakey1,Metakey2 etc.', $this->plugin_text_domain ); ?></p>


		</div>

		<hr class="section_seprate" />
      
        <div class="ex_postSec buy_pro_wrapper">
			<h4 class="title_heading">
				<?php echo esc_attr__('Exclude post by ID', $this->plugin_text_domain ); ?>
				<span><?php echo esc_attr__('* This feature is available in pro version.', $this->plugin_text_domain ); ?><span>
			</h4>
				<div class="buy_pro"><a href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro', $this->plugin_text_domain ); ?><i class="fa fa-diamond" aria-hidden="true"></i></a></div>
        	<div class="disable_section">
				<div class="element">
					<label for="<?php echo esc_attr( $this->plugin_name . '_post_search_exclude' ); ?>">
						<?php echo esc_attr__('Exclude Post ids', $this->plugin_text_domain ); ?> 
					</label>
					<textarea disable></textarea>
						<span class="message_info"><?php echo esc_attr__( 'Comma "," separated list of post IDs', $this->plugin_text_domain ); ?></span>
				</div>
				</div>
		</div>

		    </div>
		  </li>
		  
		  <li id="advance_search_taxonomy_chkbox">
		    <a class="toggle" href="#"><i class="fa fa-list-alt" aria-hidden="true"></i> <?php echo esc_attr__( 'Taxonomies', $this->plugin_text_domain ); ?></a>
		    <div class="inner">
		
			<h4 class="title_heading">
				<?php echo esc_attr__('Select Taxonomies to Include in the Advanced Search', $this->plugin_text_domain ); ?>
			</h4>

			<?php

			if($taxonomies != '') {

				foreach ($taxonomies as $taxonomy) {
					
					if( in_array( $taxonomy, $advance_search_excludeTaxonomy ) ) {
							continue;
						}
					?>
					<fieldset class="checkbox_toggleSec">
						<legend class="screen-reader-text"><span><?php echo esc_attr__( 'Setting for ', $this->plugin_text_domain ) . $the_post_type_label; ?></span></legend>
						<label for="<?php echo esc_attr( $this->plugin_name . '_' . $taxonomy ); ?>">
							<div>
				            <label class="el-switch el-switch-blue">
								<input type="checkbox" data-ptag="taxonomySearch" class="checkarea" id="<?php echo esc_attr( $this->plugin_name . '-' . $taxonomy ); ?>" name="search_form_setting[taxonomies][taxonomies][<?php echo esc_attr( $taxonomy ); ?>]" value="<?php echo esc_attr( $taxonomy ); ?>" 
								<?php 
								if(!empty($settings['taxonomies']) && array_key_exists('taxonomies', $settings)) {
									 if (!empty($settings['taxonomies']['taxonomies']) &&  in_array($taxonomy, $settings['taxonomies']['taxonomies'], true)) { echo 'checked="checked"'; 
								} 
								} ?> class="taxonomyCheck" />
								 <span class="el-switch-style"></span>
				            </label>
							<span class="title"><?php echo esc_attr__(ucwords(str_replace('_', ' ', $taxonomy)), $this->plugin_text_domain ); ?></span>
						</div>
						</label>
					</fieldset>
					<?php
				}
			}

			?>

<hr class="section_seprate search_box_expand" />
		
		<div class="sep_section searchTaxonomy search_box_expand" id="taxonomySearch">
			<h4 class="title_heading">
				<?php echo esc_attr__('Search Area', $this->plugin_text_domain ); ?>
			</h4>

			<p class="content_Style"><?php echo esc_attr__('* By enable specific area of search, search result relative from specific area i.e title, description etc.', $this->plugin_text_domain ); ?></p>

			<fieldset class="checkbox_toggleSec">
				<label for="<?php echo esc_attr( $this->plugin_name . '_post_search_title' ); ?>">
				  <div>
				    <label class="el-switch el-switch-blue">
					<input type="checkbox" data-check="subtaxonoy" class="subcheckbox" id="<?php echo esc_attr( $this->plugin_name . '-_post_search_title' ); ?>" name="search_form_setting[taxonomies][search_areas][title]" value="<?php echo esc_attr('title'); ?>" 
					
					<?php
					
					if (!empty($settings['taxonomies']) && array_key_exists('taxonomies', (array)$settings)) { 
						if (!empty($settings['taxonomies']['search_areas']) && in_array('title', $settings['taxonomies']['search_areas'], true)) { echo 'checked="checked"';
						 } 
						} ?> />
					     <span class="el-switch-style"></span>
				    </label>
					<span class="title"><?php echo esc_attr__( 'Search in Title', $this->plugin_text_domain ); ?></span>
				</div>
				</label>
			</fieldset>

			<fieldset class="checkbox_toggleSec">
				<label for="<?php echo esc_attr( $this->plugin_name . '_post_search_desc' ); ?>">
				<div>
				    <label class="el-switch el-switch-blue">
					<input type="checkbox" class="subcheckbox" data-check="subtaxonoy" id="<?php echo esc_attr( $this->plugin_name . '-_post_search_desc' ); ?>" name="search_form_setting[taxonomies][search_areas][content]" value="<?php echo esc_attr('content'); ?>" <?php 
					if (!empty($settings['taxonomies']) && array_key_exists('taxonomies', (array)$settings)) { 
						if (!empty($settings['taxonomies']['search_areas']) && in_array('content', $settings['taxonomies']['search_areas'], TRUE)) { echo 'checked="checked"'; } 
					} 
					?> />
					     <span class="el-switch-style"></span>
				    </label>
					<span class="title"><?php echo esc_attr__( 'Search in Content', $this->plugin_text_domain ); ?></span>
				</div>
				</label>
			</fieldset>

			<hr class="section_seprate" />
		</div>

		
		
		<div class="sep_section buy_pro_wrapper">
			<h4 class="title_heading">
				<?php echo esc_attr__('Show/Hide empty taxonomies', $this->plugin_text_domain ); ?>
				<span><?php echo esc_attr__('* This feature is available in pro version.', $this->plugin_text_domain ); ?><span>
			</h4>

			<div class="buy_pro"><a href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro', $this->plugin_text_domain ); ?><i class="fa fa-diamond" aria-hidden="true"></i></a></div>

			<p class="content_Style"><?php echo esc_attr__('* By enable this option empty taxonomies show in search results.', $this->plugin_text_domain ); ?></p>
		<div class="disable_section">
			<fieldset class="checkbox_toggleSec show_hide_empty_taxonomies">
				<label for="<?php echo esc_attr( $this->plugin_name . '_show_hide_empty_taxonomies' ); ?>">
				  <div>
				    <label class="el-switch el-switch-blue">
					<input type="checkbox" id="<?php echo esc_attr( $this->plugin_name . '-_show_hide_empty_taxonomies' ); ?>" disabled />
					     <span class="el-switch-style"></span>
				    </label>
					<span class="title"><?php echo esc_attr__( 'Show Empty Taxonomies', $this->plugin_text_domain ); ?></span>
				</div>
				</label>
			</fieldset>
		</div>
		<br/><br/>
		</div>
		    </div>
		  </li>
		  
		  <li>
		    <a class="toggle" href="#"><i class="fa fa-file-image-o" aria-hidden="true"></i> <?php echo esc_attr__( 'Attachments', $this->plugin_text_domain ); ?></a>
		    <div class="inner">
		    	<div class="buy_pro_wrapper">
		    <h4 class="title_heading">
				<?php echo esc_attr__('Select Attachment type to Include in the Advanced Search', $this->plugin_text_domain ); ?>
				<span><?php echo esc_attr__('* Only Image name search available in free version.', $this->plugin_text_domain ); ?><span>
			</h4>

			<p class="content_Style"><?php echo esc_attr__('* Select attachment type to include in search result.', $this->plugin_text_domain ); ?></p>
			<p class="content_Style"><?php echo esc_attr__('* By enable png, jpg, gif, zip attachment search only by title.', $this->plugin_text_domain ); ?></p>

			<?php

			$attachments = array(
				'image/jpeg'=>'Jpeg',
				'image/gif'=>'Gif',
				'image/png'=>'Png',
			);

			foreach ($attachments as $key => $value) {
				?>
				<fieldset class="checkbox_toggleSec">
					<label for="<?php echo esc_attr( $this->plugin_name . '_' . $key ); ?>">
					<div>
				    <label class="el-switch el-switch-blue">
					    <input type="checkbox" id="<?php echo esc_attr( $this->plugin_name . '-' . $key ); ?>" name="search_form_setting[attachments][<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $key ); ?>" <?php if (array_key_exists('attachments', $settings)) { if (in_array($key, $settings['attachments'])) { echo 'checked="checked"'; } } ?> />
					     <span class="el-switch-style"></span>
				    </label>
						<span class="title"><?php echo esc_attr__( $value, $this->plugin_text_domain ); ?></span>
					</div>
					</label>
				</fieldset>
				<?php
			}
			?>

			<div class="pro_version" style="position: relative;">
				
				<div class="buy_pro" style="height:100%;"><a href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro', $this->plugin_text_domain ); ?><i class="fa fa-diamond" aria-hidden="true"></i></a></div>

				<?php
				$pro_attachments = array(
					'application/pdf'=>'PDF',
					'application/msword'=>'Word',
					'application/vnd.ms-excel'=>'Excel',
					'text/csv'=>'Csv',
					'application/zip'=>'Zip'
				);

				foreach ($pro_attachments as $key => $value) {
					?>
					<fieldset class="checkbox_toggleSec">
						<label for="<?php echo esc_attr( $this->plugin_name . '_' . $key ); ?>">
						<div>
					    <label class="el-switch el-switch-blue">
						    <input type="checkbox" disabled />
						     <span class="el-switch-style"></span>
					    </label>
							<span class="title"><?php echo esc_attr__( $value, $this->plugin_text_domain ); ?></span>
						</div>
						</label>
					</fieldset>
					<?php
				}
				?>
			</div>
		</div>
		    </div>
		  </li>

		   <li>
		    <a class="toggle" href="#"><i class="fa fa-user" aria-hidden="true"></i> <?php echo esc_attr__( 'User Search', $this->plugin_text_domain ); ?> <span>* This feature is available in pro version.</span></a>

			    <div class="inner">
			    	<div class="buy_pro_wrapper">

			    <div class="buy_pro" style="height:100%;"><a href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro', $this->plugin_text_domain ); ?><i class="fa fa-diamond" aria-hidden="true"></i></a></div>

			    <h4 class="title_heading">
					<?php echo esc_attr__('Enable User Search By Specific Column', $this->plugin_text_domain ); ?>
				</h4>
			     
			     <!-- <p class="content_Style">* .</p> -->

				<fieldset class="checkbox_toggleSec toggle2">
					<label for="<?php echo esc_attr( $this->plugin_name . '_user_search_by_fname' ); ?>">
						<div>
				          <label class="el-switch el-switch-blue">
							<input type="checkbox" disabled />
					           <span class="el-switch-style"></span>
				          </label>
						<span class="title"><?php echo esc_attr__( 'By First Name', $this->plugin_text_domain ); ?></span>
					</div>
					</label>
				</fieldset>

				<fieldset class="checkbox_toggleSec toggle2">
					<label for="<?php echo esc_attr( $this->plugin_name . '_user_search_by_lname' ); ?>">
						<div>
				          <label class="el-switch el-switch-blue">
					     	<input type="checkbox" disabled />
					           <span class="el-switch-style"></span>
				          </label>
						<span class="title"><?php echo esc_attr__( 'By Last Name', $this->plugin_text_domain ); ?></span>
					</div>
					</label>
				</fieldset>

				<fieldset class="checkbox_toggleSec toggle2">
					<label for="<?php echo esc_attr( $this->plugin_name . '_user_search_by_login_name' ); ?>">
						<div>
				          <label class="el-switch el-switch-blue">
					    <input type="checkbox" disabled />
					           <span class="el-switch-style"></span>
				          </label>
						<span class="title"><?php echo esc_attr__( 'By Login Name', $this->plugin_text_domain ); ?></span>
					</div>
					</label>
				</fieldset>

				<fieldset class="checkbox_toggleSec toggle2">
					<label for="<?php echo esc_attr( $this->plugin_name . '_user_search_by_display_name' ); ?>">
						<div>
				          <label class="el-switch el-switch-blue">
					    <input type="checkbox" disabled />
					           <span class="el-switch-style"></span>
				          </label>
						<span class="title"><?php echo esc_attr__( 'By Display Name', $this->plugin_text_domain ); ?></span>
					</div>
					</label>
				</fieldset>

				<fieldset class="checkbox_toggleSec toggle2">
					<label for="<?php echo esc_attr( $this->plugin_name . '_user_search_by_email' ); ?>">
						<div>
				          <label class="el-switch el-switch-blue">
					         <input type="checkbox" disabled />
					           <span class="el-switch-style"></span>
				          </label>
						<span class="title"><?php echo esc_attr__( 'By Email', $this->plugin_text_domain ); ?></span>
					</div>
					</label>
				</fieldset>

				<fieldset class="checkbox_toggleSec toggle2">
					<label for="<?php echo esc_attr( $this->plugin_name . '_user_search_by_user_bio' ); ?>">
						<div>
				          <label class="el-switch el-switch-blue">
					    	<input type="checkbox" disabled />
					           <span class="el-switch-style"></span>
				          </label>
						<span class="title"><?php echo esc_attr__( 'By User Bio', $this->plugin_text_domain ); ?></span>
					</div>
					</label>
				</fieldset>

				<hr class="section_seprate" />
		
		<div class="sep_section">
			<h4 class="title_heading">
				<?php echo esc_attr__('Search by Specific Role', $this->plugin_text_domain ); ?>
			</h4>

				<?php
				$editable_roles = get_editable_roles();
			    foreach ($editable_roles as $role => $details) {
			    	?>
			    <fieldset class="checkbox_toggleSec">
					<label for="<?php echo esc_attr( $this->plugin_name . '_user_search_by_role' ); ?>">
						<div>
				          <label class="el-switch el-switch-blue">
					    	<input type="checkbox" disabled />
					           <span class="el-switch-style"></span>
				          </label>
						<span class="title"><?php echo esc_attr__( $details['name'], $this->plugin_text_domain ); ?></span>
					</div>
					</label>
				</fieldset>
			    <?php
			    } // end user role foreach
				?>
			</div>

		<hr class="section_seprate" />
		
		<div class="sep_section exclude_Sec">
			<h4 class="title_heading">
				<?php echo esc_attr__('Exclude user By ID', $this->plugin_text_domain ); ?>
			</h4>
					<label for="<?php echo esc_attr( $this->plugin_name . '_user_search_exclude' ); ?>"></label>
						<input type="text" disabled />
						<p class="content_Style"><?php echo esc_attr__( 'Comma "," separated list of user IDs', $this->plugin_text_domain ); ?>
						</p>
				</div>
	</div>
			    </div>
		  	</li>

		  <li>
		    <a class="toggle" href="#"><i class="fa fa-microphone" aria-hidden="true"></i> <?php echo esc_attr__( 'Voice Search', $this->plugin_text_domain ); ?> <span>* This feature is available in pro version.</span></a>
		    <div class="inner">

		    	<div class="buy_pro_wrapper">

		    <h4 class="title_heading">
				<?php echo esc_attr__('Enable Voice Search', $this->plugin_text_domain ); ?>
			</h4>

			<div class="buy_pro" style=""><a href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro', $this->plugin_text_domain ); ?><i class="fa fa-diamond" aria-hidden="true"></i></a></div>


			<p class="content_Style"><?php echo esc_attr__('* By enable voice option user can search by voice from frontend.', $this->plugin_text_domain ); ?></p>

			<p class="content_Style warning"><?php echo esc_attr__('* Voice search support only working on those web browsers which supports webkitSpeechRecognition API..', $this->plugin_text_domain ); ?></p>

			<fieldset class="checkbox_toggleSec toggle2">
				<label for="<?php echo esc_attr( $this->plugin_name . '_voice_search' ); ?>">
				<div>
				 <label class="el-switch el-switch-blue">
					<input type="checkbox" disabled />
					  <span class="el-switch-style"></span>
				 </label>
					<span class="title"><?php echo esc_attr__( 'Enable Voice Search ', $this->plugin_text_domain ); ?></span>
				</div>
				</label>
			</fieldset>
			</div>
		    </div>
		  </li>

		  <li>
		    <a class="toggle" href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?php echo esc_attr__( 'Theme & Styling', $this->plugin_text_domain ); ?></a>	    
		    <div class="inner">
		    <?php
		    	$border_type = array('none','hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset');
		    ?>
              <div class="themeStyleSec">
		      
		      <h4 class="title_heading alert"><?php echo esc_attr__('Style is not implemented if this search is set as default search.', $this->plugin_text_domain ); ?></h4>

		      <h4 class="title_heading"><?php echo esc_attr__( 'Overall Box layout', $this->plugin_text_domain ); ?></h4>

		      <div class="search_box_layout" style="float: left;">
		      	<div class="search_box_width searchBox_info">
		      		<h5><?php echo esc_attr__( 'Search Box Widths', $this->plugin_text_domain ); ?></h5>
		      		<ul>
					
		      		<li>
		      		<div class="col_liStle"><span class="tooltip" title="Desktop"><i class="fa fa-desktop" aria-hidden="true"></i></span>
					<input type="text" name="search_form_setting[styling][search_box_outer][width][desktop]" value="<?php echo $settings['styling']['search_box_outer']['width']['desktop']; ?>" class="restricted"/></div>
					</li>
					
		      		<li><div class="col_liStle"><span class="tooltip" title="Tablet"><i class="fa fa-tablet" aria-hidden="true"></i></span><input type="text" name="search_form_setting[styling][search_box_outer][width][tablet]" value="<?php echo $settings['styling']['search_box_outer']['width']['tablet']; ?>" class="restricted"/></div></li>
					
		      		<li><div class="col_liStle"><span class="tooltip" title="Mobile"><i class="fa fa-mobile" aria-hidden="true"></i></span><input type="text" name="search_form_setting[styling][search_box_outer][width][mobile]" value="<?php echo $settings['styling']['search_box_outer']['width']['mobile']; ?>" class="restricted"/></div></li>
					
		      		</ul>
		      	</div>
				
				<div class="search_box_height searchBox_info">
		      	<h5><?php echo esc_attr__( 'Search Box Height', $this->plugin_text_domain ); ?></h5>
				  	<ul class="searchMBox">
		      			<li>  
							<div class="col_liStle">
							<input type="text" minlength="2" name="search_form_setting[styling][search_box_outer][height]" value="<?php echo $settings['styling']['search_box_outer']['height']; ?>" class="restricted"/><span class="pxValue"><?php echo esc_attr__('px', $this->plugin_text_domain); ?></span>
							</div>
						</li>	
					</ul>
		      	</div>

		      	<div class="search_box_margin searchBox_info">
		      	<h5><?php echo esc_attr__( 'Search Box Margin', $this->plugin_text_domain ); ?></h5>
		      	<ul class="searchMBox">
		      		<li>
		      			<div class="col_liStle"><i class="fa fa-arrow-up" aria-hidden="true" title="Top"></i>
		      				<input type="number" name="search_form_setting[styling][search_box_outer][margin][top]" value="<?php echo $settings['styling']['search_box_outer']['margin']['top']; ?>" /><span class="pxValue"><?php echo esc_attr__('px', $this->plugin_text_domain); ?></span></div>
		      	    </li>
                    
		       	</ul>
		      	</div>

		      	<div class="search_box_bg_color searchBox_info">
		      		<h5><?php echo esc_attr__( 'BackGround Color', $this->plugin_text_domain ); ?></h5>
					<ul class="searchMBox">
		      			<li>  
							<input type="text" class="wpas_color_field" name="search_form_setting[styling][search_box_outer][bg_color]" value="<?php echo $settings['styling']['search_box_outer']['bg_color']; ?>" />
						</li>
					</ul>
				</div>

		      	<div class="search_box_border searchBox_info">
                  <h5><?php echo esc_attr__( 'Border Type', $this->plugin_text_domain ); ?></h5>
		      	<ul class="searchMBox" id="InputBorderBox">
		      		<li>
		      		<div class="col_liStle">
		      		<div class="box_dropdown_advance">
		      			<select name="search_form_setting[styling][search_box_outer][border_type]" id="BoxLayoutBorder" class="smaller _xx_style_xx_ borderType">
		      				<?php
		      				foreach ($border_type as $key => $value) {
		      					if($value == $settings['styling']['search_box_outer']['border_type']) {
		      				?>
		      					<option value="<?php echo $value; ?>" selected="selected"><?php echo esc_attr__($value, $this->plugin_text_domain); ?></option>
		      				<?php
		      					}
		      					else {
		      						?>
		      					<option value="<?php echo $value; ?>"><?php echo esc_attr__($value, $this->plugin_text_domain); ?></option>	
		      						<?php
		      					}
		      				}
		      				?>
                        </select>
		      		</div>
		      	</div>
		      	</li>
                 <li>
                 	<div class="col_liStle hideBorder">
		      		<label><?php echo esc_attr__( 'Width:', $this->plugin_text_domain ); ?></label>
		      		 <input class="input_style" type="number" name="search_form_setting[styling][search_box_outer][border_px]" value="<?php echo $settings['styling']['search_box_outer']['border_px']; ?>"><span class="pxValue nwPxValue"><?php echo esc_attr__('px', $this->plugin_text_domain); ?></span>
		      	</div>
		      	</li>
                  
                  <li>
                  	<div class="col_liStle hideBorder">
		      		<label><?php echo esc_attr__( 'Border Color:', $this->plugin_text_domain ); ?></label>
		      			<input type="text" name="search_form_setting[styling][search_box_outer][border_color]" class="wpas_color_field" value="<?php echo $settings['styling']['search_box_outer']['border_color']; ?>">
		      		</div>
					  </li>
		      		</ul>
		      		<h5><?php echo esc_attr__( 'Border Radius ', $this->plugin_text_domain ); ?></h5>
		      		 <ul class="searchMBox newBoxSearch">
		      		 	<li>
		      			<div class="col_liStle"><i class="fa fa-arrow-up" title="Top" aria-hidden="true"></i><input type="text" name="search_form_setting[styling][search_box_outer][border_radius][top]" value="<?php echo $settings['styling']['search_box_outer']['border_radius']['top']; ?>" class="restricted"><span class="pxValue"><?php echo esc_attr__('px', $this->plugin_text_domain); ?></span></div>
		      		</li>
		      		<li>
		      			<div  class="col_liStle"><i class="fa fa-arrow-right" title="Right" aria-hidden="true"></i><input type="text" name="search_form_setting[styling][search_box_outer][border_radius][right]" value="<?php echo $settings['styling']['search_box_outer']['border_radius']['right']; ?>" class="restricted"><span class="pxValue"><?php echo esc_attr__('px', $this->plugin_text_domain); ?></span></div>
		      		</li>
		      		<li>
		      			<div  class="col_liStle"><i class="fa fa-arrow-down" title="Bottom" aria-hidden="true"></i><input type="text" name="search_form_setting[styling][search_box_outer][border_radius][bottom]" value="<?php echo $settings['styling']['search_box_outer']['border_radius']['bottom']; ?>" class="restricted"><span class="pxValue"><?php echo esc_attr__('px', $this->plugin_text_domain); ?></span></div>
		      		</li>
		      		<li>
		      		<div class="col_liStle"><i class="fa fa-arrow-left" title="Left" aria-hidden="true"></i><input type="text" name="search_form_setting[styling][search_box_outer][border_radius][left]" value="<?php echo $settings['styling']['search_box_outer']['border_radius']['left']; ?>" class="restricted"><span class="pxValue">px</span></div>
		      		</li>
		      		</ul>

		      	</div>
		      </div>
		      
		      <hr class="section_seprate" />

		      <div class="search_input_layout search_box_layout" style="float: left;">

               <h4 class="title_heading"><?php echo esc_attr__( 'Search Input Design', $this->plugin_text_domain ); ?></h4>

              <div class="searchBox_info">
               <ul class="searchMBox">
               	<li>
		      <div class="col_liStle">
		      	<label><?php echo esc_attr__( 'Search Input Background Color:', $this->plugin_text_domain ); ?></label>
		      	<input type="text" name="search_form_setting[styling][search_input][bg_color]" class="wpas_color_field" value="<?php echo $settings['styling']['search_input']['bg_color']; ?>">
		      </div>
		    </li>
		   </ul>
		  </div>

		  <div class="searchBox_info">
		      <h5><?php echo esc_attr__( 'Input Font Style', $this->plugin_text_domain ); ?></h5>
		      <ul class="searchMBox">
		      <li>
		      <div  class="col_liStle"><label><?php echo esc_attr__( 'Search Input Font Color :', $this->plugin_text_domain ); ?></label> 
		      	<input type="text" name="search_form_setting[styling][search_input][font_color]" class="wpas_color_field" value="<?php echo $settings['styling']['search_input']['font_color']; ?>"></div>
		      </li>
              <li>
		      <div  class="col_liStle"><label><?php echo esc_attr__( 'Font size:', $this->plugin_text_domain ); ?></label> <input class="restricted input_style" type="text" name="search_form_setting[styling][search_input][font_size]" value="<?php echo $settings['styling']['search_input']['font_size']; ?>" min="0"><span class="pxValue nwPxValue"><?php echo esc_attr__('px', $this->plugin_text_domain); ?></span></div>
		     </li>
		     <li>
		      <div class="col_liStle"><label><?php echo esc_attr__( 'Line Height:', $this->plugin_text_domain ); ?></label> <input class="restricted input_style" type="text" name="search_form_setting[styling][search_input][line_height]" value="<?php echo $settings['styling']['search_input']['line_height']; ?>" min="0" ><span class="pxValue nwPxValue"><?php echo esc_attr__('px', $this->plugin_text_domain); ?></span></div>
		    </li>
		     </ul>
             </div>


 <div class="searchBox_info">
   <h5><?php echo esc_attr__( 'Input Border', $this->plugin_text_domain ); ?></h5>
          <ul class="searchMBox" id="InputBorder">
          	<li>
          		<div class="col_liStle">
		      	<label><?php echo esc_attr__( 'Border Type:', $this->plugin_text_domain ); ?></label>
		      	 <div class="box_dropdown_advance">
		      			<select name="search_form_setting[styling][search_input][border_type]" id="InputLayoutBorder" class="smaller _xx_style_xx_ borderType">
                            <?php
		      				foreach ($border_type as $key => $value) {
		      					if($value == $settings['styling']['search_input']['border_type']) {
		      				?>
		      					<option value="<?php echo $value; ?>" selected="selected"><?php echo esc_attr__($value, $this->plugin_text_domain); ?></option>
		      				<?php
		      					}
		      					else {
		      						?>
		      					<option value="<?php echo $value; ?>"><?php echo esc_attr__($value, $this->plugin_text_domain); ?></option>	
		      						<?php
		      					}
		      				}
		      				?>
                        </select>
		      		</div>
		      	</div>
		      	</li>
                 
                <li>
                	<div class="col_liStle hideBorder">
		      		<label><?php echo esc_attr__( 'Width: ', $this->plugin_text_domain ); ?></label>
		      			<input type="number" class="input_style" name="search_form_setting[styling][search_input][border_px]" value="<?php echo $settings['styling']['search_input']['border_px']; ?>"><span class="pxValue nwPxValue"><?php echo esc_attr__('px', $this->plugin_text_domain); ?></span>
		      		</div>
		      		</li>

		      		<li>
		      		<div class="col_liStle hideBorder">
		      		<label><?php echo esc_attr__( 'Border Color:', $this->plugin_text_domain ); ?></label><input type="text" name="search_form_setting[styling][search_input][border_color]" class="wpas_color_field" value="<?php echo $settings['styling']['search_input']['border_color']; ?>">
		      	     </div>
		      	    </li>
		      	</ul>
                 
                 <ul class="searchMBox newBoxSearch">
		      	    <li>
		      	    <div class="col_liStle">
		      		<label><?php echo esc_attr__( 'Border Radius:', $this->plugin_text_domain ); ?></label>
		      		<i class="fa fa-arrow-up" title="Top" aria-hidden="true"></i><input type="text" name="search_form_setting[styling][search_input][border_radius][top]" value="<?php echo $settings['styling']['search_input']['border_radius']['top']; ?>" class="restricted"><span class="pxValue">px</span>
		      		</div>
		      		</li>
		      		<li>
		      		<div class="col_liStle"><i class="fa fa-arrow-right" title="Right" aria-hidden="true"></i><input type="text" name="search_form_setting[styling][search_input][border_radius][right]" value="<?php echo $settings['styling']['search_input']['border_radius']['right']; ?>" class="restricted"><span class="pxValue">px</span>
		      		</div>
		      		</li>
		      		<li>
		      		<div class="col_liStle">
		      			<i class="fa fa-arrow-down" title="Bottom" aria-hidden="true"></i><input type="text" name="search_form_setting[styling][search_input][border_radius][bottom]" value="<?php echo $settings['styling']['search_input']['border_radius']['bottom']; ?>" class="restricted"><span class="pxValue">px</span>
		      		</div>
		      		</li>
		      		<li>
		      		<div class="col_liStle">
		      		<i class="fa fa-arrow-left" title="Left" aria-hidden="true"></i><input type="text" name="search_form_setting[styling][search_input][border_radius][left]" value="<?php echo $settings['styling']['search_input']['border_radius']['left']; ?>" class="restricted"><span class="pxValue">px</span>
		      	    </div>
		      		</li>
                  </ul>
				</div>
			</div>

<hr class="section_seprate" />

				 <div class="loading_icon search_box_layout" style="float: left; width: 100%;">
				 <div class="searchBox_info buy_pro_wrapper">
				  <h4 class="title_heading"><?php echo esc_attr__( 'Icons', $this->plugin_text_domain ); ?>
				  	<span><?php echo esc_attr__('* Only 1 magnifier and loading icon available in free version.', $this->plugin_text_domain ); ?><span>
				  </h4>
				  <div class="col_liStle">
				  <label class="lable_magn"><?php echo esc_attr__( 'Magnifier Icons:', $this->plugin_text_domain ); ?></label>
				  <ul class="magnifier_icon_design">
				  <li class="active" data-icon="search"><i class="fa fa-search" aria-hidden="true"></i></li>
				</ul>
			    </div>
               
				<div class="searchBox_info magnifier_sec">
               <ul class="searchMBox">
		      <li>
		      <div class="col_liStle">
               	<label><?php echo esc_attr__( 'Magnifier Icon Color:', $this->plugin_text_domain ); ?></label>
               	<input type="text" name="search_form_setting[styling][magnifire][color]" value="<?php echo ($settings['styling']['magnifire']['color']) ? $settings['styling']['magnifire']['color'] : '#ffffff'; ?>" class="wpas_color_field">
               
               </div>
		      </li>
              <li>
				<div class="col_liStle">
					<label><?php echo esc_attr__( 'Magnifier Background Color:', $this->plugin_text_domain ); ?></label>
					<input type="text" name="search_form_setting[styling][magnifire][bg_color]" value="<?php echo ($settings['styling']['magnifire']['bg_color']) ? $settings['styling']['magnifire']['bg_color'] : '#cccccc'; ?>" class="wpas_color_field">
               </div>
		     </li>
		     <li>
		      <div class="col_liStle">
                 <fieldset>
					<label><?php echo esc_attr__( 'Button Position:', $this->plugin_text_domain ); ?> </label>
						<div class="box_dropdown_advance">
						<select name="search_form_setting[styling][magnifire][position]">
							<option value="right" <?php echo ($settings['styling']['magnifire']['position'] == 'right') ?  'selected="selected"' : ''; ?>><?php echo esc_attr__( 'Right To input', $this->plugin_text_domain ); ?></option>
							<option value="left" <?php echo ($settings['styling']['magnifire']['position'] == 'left') ?  'selected="selected"' : ''; ?>><?php echo esc_attr__( 'Left To input', $this->plugin_text_domain ); ?></option>
						</select>
					</div>
				</fieldset>
                </div>
		    </li>
		     </ul>
            </div>

				</div>



 <div class="searchBox_info">
  <h5><?php echo esc_attr__( 'Loading Icons', $this->plugin_text_domain ); ?></h5>
<div class="col_liStle">
<ul class="loader_lists">
<li class="active" data-icon="sbl-circ"><div class="sbl-circ"></div></li>
</ul>
</div>
<label><?php echo esc_attr__( 'Loading Icon Color:', $this->plugin_text_domain ); ?></label> <input type="text" name="search_form_setting[styling][loader][color]" value="<?php echo ($settings['styling']['loader']['color']) ? $settings['styling']['loader']['color'] : '#ffffff'; ?>" class="wpas_color_field">
</div>

	

</div>
<hr class="section_seprate" />

			<div class="search_box_layout search_button" style="float: left; width: 100%;">
             <div class="searchBox_info">
			 <h4 class="title_heading"><?php echo esc_attr__( 'Search Button', $this->plugin_text_domain ); ?></h4>
              <div class="col_liStle">
              	<ul>
              	<li>
				<fieldset>
				<label><?php echo esc_attr__( 'Search Button Text:', $this->plugin_text_domain ); ?></label>
				 <input class="serach_input_style" type="text" name="search_form_setting[styling][search_button][text]" value="<?php echo ($settings['styling']['search_button']['text']) ? $settings['styling']['search_button']['text'] : 'Search'; ?>" />
				</fieldset>
			    </li>
                <li>
				<label><?php echo esc_attr__( 'Search Text Color:', $this->plugin_text_domain ); ?></label> 
				<input type="text" name="search_form_setting[styling][search_button][font_color]" class="wpas_color_field" value="<?php echo ($settings['styling']['search_button']['font_color']) ? $settings['styling']['search_button']['font_color'] : '#000000'; ?>">
			     </li>
			     </ul>
			    </div>

                <div class="col_liStle">
                <ul>
				<li>
				<fieldset>
				<label><input type="checkbox" name="search_form_setting[styling][search_button][show_search_text]" value="show_search_text" <?php if (in_array('show_search_text', $settings['styling']['search_button'])) { echo 'checked="checked"'; } ?> />
						<span><?php echo esc_attr__( 'Show Search Button Text', $this->plugin_text_domain ); ?></span>	
					</label>
				</fieldset>
                </li>
                <li>
                <fieldset>
				<label><input type="checkbox" name="search_form_setting[styling][search_button][show_maginfier_icon]" value="show_maginfier_icon" <?php if (in_array('show_maginfier_icon', $settings['styling']['search_button'])) { echo 'checked="checked"'; } ?> />
						<span><?php echo esc_attr__( 'Show Maginfier Icon', $this->plugin_text_domain ); ?></span>
					</label>
				</fieldset>

			   </li>
			   </ul>
               </div>
               
               <div class="col_liStle">
               	<ul class="searchMBox">
               	<li>
		    	<fieldset>
				<label><?php echo esc_attr__( 'Search Text Font Size:', $this->plugin_text_domain ); ?></label>
				<input class="restricted input_style" type="text" name="search_form_setting[styling][search_button][font_size]" value="<?php echo $settings['styling']['search_button']['font_size']?>" />
				<span class="pxValue nwPxValue"> <?php echo esc_attr__('px', $this->plugin_text_domain); ?></span>
				</fieldset>
			</li>
			</ul>
			</div>

            </div>

			</div>

		    </div>
</div>
		  </li>

		   <li>
		    <a class="toggle" href="#"><i class="fa fa-tasks" aria-hidden="true"></i> <?php echo esc_attr__( 'Fuzzy Matching', $this->plugin_text_domain ); ?></a>
		    <div class="inner">
			
			<h4 class="title_heading alert"><?php echo esc_attr__("'Full/Exact Word' search will work for post types only.", $this->plugin_text_domain ); ?></h4>
		      
			<h4 class="title_heading">
				<?php echo esc_attr__('Fuzzy Matching', $this->plugin_text_domain ); ?>
			</h4>
		      <div>
		      	<p class="content_Style"><?php echo esc_attr__('* This additional option is for more efficient search results.', $this->plugin_text_domain ); ?></p>
		      	
		      	<label>
		      	<input type="radio" name="search_form_setting[search_type]" value="partial_word" <?php echo ($settings['search_type'] == 'partial_word') ? 'checked="checked"': ''; ?> />
		      	<?php echo esc_attr__('Partial Word', $this->plugin_text_domain ); ?>
				</label>

		      	<label>
		      	<input type="radio" name="search_form_setting[search_type]" value="full_word" <?php echo ($settings['search_type'] == 'full_word' || $settings['search_type'] == '') ? 'checked="checked"': ''; ?> />
		      	<?php echo esc_attr__('Full/Exact  Word', $this->plugin_text_domain ); ?>
				</label>

		      </div>

		      <p class="content_Style warning"><?php echo esc_attr__('* Search may slower by choosing Partial Word Search.', $this->plugin_text_domain ); ?></p>

		    </div>
		  </li> 
		  
		</ul>

	<div class="wpas_search_right">
		<button class="button button-primary" type="submit"><?php echo esc_attr__('Save all changes', $this->plugin_text_domain); ?></button>
		<?php //submit_button( 'Save all changes', 'primary','submit', true ); ?>
		</form> 

		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="">
			<input type="hidden" name="action" value="wpas_search_form_settings">
			<input type="hidden" name="search_setting_reset" value="<?php echo wp_create_nonce('reset_form_settings'); ?>" />
			<input type="hidden" name="search_form_id" value="<?php echo $search_form_id; ?>">
			<button class="button button-secondary" type="submit"><?php echo esc_attr__('Restore defaults', $this->plugin_text_domain); ?></button>
		</form>

	</div>

	</div>


<div class="sideBar_Section">
<div class="secColStyle">
<h3><?php echo esc_attr__('Advanced Search', $this->plugin_text_domain); ?> </h3>
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
<p><?php echo esc_attr__('Even more features available in Advanced Search Pro.',$this->plugin_text_domain); ?></p>
<div class="btn"><a class="go_pro_button" href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro', $this->plugin_text_domain); ?> <i class="fa fa-diamond" aria-hidden="true"></i></a></div>
</div>
</div>

</div>
</div>
</div>
<?php 
} // end check num rows
?>