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

?>

<div class="wrap">
<div class="advance_serach_sec wpas_search">
    <h3><?php echo esc_html( get_admin_page_title() ); ?></h3>
    <div class="imp_link">
      <a class="back" href="<?php echo admin_url().'admin.php?page='.$this->plugin_name; ?>"><?php echo esc_attr__('Search list', $this->plugin_text_domain); ?></a>
      <a class="go_pro_button" href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro', $this->plugin_text_domain); ?> <i class="fa fa-diamond" aria-hidden="true"></i></a>
    </div>
  </div>
    <br/>

  <div class="advance_serach_sec">

<div class="statistics_searchSecDiv pro_version">

  <div class="buy_pro_wrapper">
      <h4 class="title_heading">
        <?php echo esc_attr__('* This feature only for pro version', $this->plugin_text_domain ); ?><a href="https://searchpro.ai/" target="_blank">Buy Now</a>
      </h4>
  </div>

  <img class="statistics_demo" src="<?php echo plugins_url().'/'.$this->plugin_name; ?>/inc/admin/images/statistics.png">

</div>

</div>

<div class="sideBar_Section">
<div class="secColStyle">
<h3><?php echo esc_attr__('Advanced Search', $this->plugin_text_domain);?> </h3>
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