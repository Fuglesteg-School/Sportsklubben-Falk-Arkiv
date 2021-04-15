<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://profiles.wordpress.org/mndpsingh287
 * @since      1.0
 *
 * @package    Advance_Search
 * @subpackage Advance_Search/inc/admin/views
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
?>
<div class="wrap">
 <div class="accordion_section advance_serach_sec wpas_search">
    <h3 class="mb_0"><?php echo esc_html( get_admin_page_title() ); ?></h3>
    <br/>
    <div class="imp_link">
      <a class="back" href="<?php echo admin_url().'admin.php?page='.$this->plugin_name; ?>"><?php echo esc_attr__('Search list', $this->plugin_text_domain); ?></a>
      <a class="statistics" href="<?php echo admin_url().'admin.php?page=wpas-statistics' ?>"><?php echo esc_attr__('Search Statistics', $this->plugin_text_domain); ?></a>
      <a class="go_pro_button" href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro', $this->plugin_text_domain);?> <i class="fa fa-diamond" aria-hidden="true"></i></a>
    </div>

  </div>
  <div class="advance_serach_sec accordion_section">
    <div class="help_section commanDiv">
      <div class="left">
        
        <div class="column">
    <h4><?php echo esc_attr__('Support', $this->plugin_text_domain);?></h4>
    <p><?php echo esc_attr__('If you didn\'t find the answer from the FAQ, or if you are having other issues, feel free to ', $this->plugin_text_domain); ?><a href="https://wordpress.org/support/plugin/advance-search/">open a support ticket.</a></p>
    </div>
    <!-- <div class="column">
    <h4><?php echo esc_attr__('Documentation', $this->plugin_text_domain);?></h4>
    <p><?php echo esc_attr__('Please check online ', $this->plugin_text_domain);?><a href="#"><?php echo esc_attr__('documentation', $this->plugin_text_domain);?></a>.</p>
   </div> -->
    <div class="column">
    <h4><?php echo esc_attr__('FAQ', $this->plugin_text_domain); ?></h4>
    
    <div class="faq_wrapper">
      <ul class="accordion">
        <li>
          <a class="toggle" href="#"><?php echo esc_attr__('Does the plugin provide search shortcodes ?', $this->plugin_text_domain); ?></a>
          <div class="inner">
      <p><?php echo esc_attr__('Yes, Advance Search provides a shortcode for each search form which you can use to embed it on your WordPress site using our powerful and easy to use shortcode editing tool. We provide integration with Gutenburg and classic editor.', $this->plugin_text_domain); ?></p>
      <p><?php echo esc_attr__('For integration with most widely used editors like Visual Composer, Elementor and BB builder, you have to upgrade to our', $this->plugin_text_domain).' <a href="https://searchpro.ai/" target="_blank">'.esc_attr__('pro version', $this->plugin_text_domain).'</a>.'; ?></p>
          </div>
        </li>

        <li>
          <a class="toggle" href="#"><?php echo esc_attr__('Is the plugin compatible with WooCommerce ?', $this->plugin_text_domain); ?></a>
          <div class="inner">
            <p><?php echo esc_attr__('Yes, Advance Search plugin cater you integration with WooCommerce to provide a powerful and advanced woocommerce search. Not only you can use Fuzzy searching, you can exclude/include specific WooCommerce products from search and much more.', $this->plugin_text_domain); ?></p>
          </div>
        </li>

        <li>
          <a class="toggle" href="#"><?php echo esc_attr__('Does the plugin provide search widgets ?', $this->plugin_text_domain); ?></a>
          <div class="inner">
            <p><?php echo esc_attr__('No. Search widgets is a premium feature. You have to upgrade to our', $this->plugin_text_domain).' <a href="https://searchpro.ai/" target="_blank">'.esc_attr__('Pro Version', $this->plugin_text_domain).'</a> '.esc_attr__('to enable this feature.', $this->plugin_text_domain); ?></p>
          </div>
        </li>

        <li>
          <a class="toggle" href="#"><?php echo esc_attr__('Will the advance search work with my theme ?', $this->plugin_text_domain); ?></a>
          <div class="inner">
            <p><?php echo esc_attr__('Yes. Advance Search, has been tested and works perfectly with a range of themes, including popular themes like Divi, Avada, Impreza, OceanWP and many more.', $this->plugin_text_domain); ?></p>
          </div>
        </li>

        <li>
          <a class="toggle" href="#"><?php echo esc_attr__('Does plugin provide voice search ?', $this->plugin_text_domain); ?></a>
          <div class="inner">
            <p><?php echo esc_attr__('No. Voice search is a premium feature. You have to upgrade to our', $this->plugin_text_domain).' <a href="https://searchpro.ai/" target="_blank">'.esc_attr__('Pro Version', $this->plugin_text_domain).'</a> '.esc_attr__('to enable this feature.', $this->plugin_text_domain); ?></p>
          </div>
        </li>

        <li>
          <a class="toggle" href="#"><?php echo esc_attr__('When I type in something, the search wheel is spinning, but nothing happens', $this->plugin_text_domain); ?></a>
          <div class="inner">
            <p><?php echo esc_attr__('It is most likely, that another plugin or the template is throwing errors while the ajax request is generated. Try disabling all the plugins one by one can help you figure out which plugin is creating the issue.', $this->plugin_text_domain); ?></p>
          </div>
        </li>

        <li>
          <a class="toggle" href="#"><?php echo esc_attr__('I disabled all the plugins but the search wheel is still spinning to infinity, nothing happens', $this->plugin_text_domain); ?></a>
          <div class="inner">
            <p><?php echo esc_attr__('You should contact us on the support forum with your website url. We will check your website and will let you know what to do.', $this->plugin_text_domain); ?></p>
          </div>
        </li>

        <li>
          <a class="toggle" href="#"><?php echo esc_attr__('Do You Offer Customization Support ?', $this->plugin_text_domain); ?></a>
          <div class="inner">
            <p><?php echo esc_attr__('Yes, we offer free/premium customization to our customers.', $this->plugin_text_domain).' <a href="https://searchpro.ai/contact" target="_blank">'.esc_attr__('Contact us', $this->plugin_text_domain).'</a> '.esc_attr__('now.', $this->plugin_text_domain);?></p>
          </div>
        </li>
       
      </ul>
    </div>

   </div>

      </div>
      <div class="right">
    <div class="column">
    <h4><?php echo esc_attr__('Checkout Pro features', $this->plugin_text_domain);?></h4>
    <p><a class="go_pro_button" href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro', $this->plugin_text_domain);?> <i class="fa fa-diamond" aria-hidden="true"></i></a></p>
   </div>
    
  </div>
    </div>

</div>
</div>


<div class="sideBar_Section" style="display:none;">
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
<button class="button button-primary">
  <a href="https://wordpress.org/plugins/advance-search/#review" target="_blank"><?php echo esc_attr__('Rate Us', $this->plugin_text_domain);?></a>
</button>
</div>
<div class="secColStyle pro_sec">
<h3><?php echo esc_attr__('Go Pro', $this->plugin_text_domain);?></h3>
<p><?php echo esc_attr__('Even more features available in Advanced Search Pro.', $this->plugin_text_domain);?></p>
<div class="btn"><a class="go_pro_button" href="https://searchpro.ai/" target="_blank"><?php echo esc_attr__('Go Pro', $this->plugin_text_domain); ?> <i class="fa fa-diamond" aria-hidden="true"></i></a></div>
</div>
</div>