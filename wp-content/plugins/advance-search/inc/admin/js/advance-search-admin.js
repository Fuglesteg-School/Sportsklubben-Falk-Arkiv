( function( $ ) {
	"use strict";

	/**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     *
     * The file is enqueued from inc/admin/class-admin.php.
     */

	jQuery(document).on('click', '#ad_dismiss', function(){
	  var admin_page_url = jQuery(this).attr('data_url');
	  window.history.replaceState({}, document.title, admin_page_url);
	  jQuery(this).closest('.notice').remove();
	});
	jQuery(document).ready(function() {

     jQuery('.toggle').click(function(e) {
     e.preventDefault();
  
    var $this = jQuery(this);
  
    if ($this.next().hasClass('show')) {
        $this.next().removeClass('show');
        $this.next().slideUp(350);
        $this.removeClass('active');
    } else {
        $this.parent().parent().find('li .inner').removeClass('show');
        $this.parent().parent().find('li .inner').slideUp(350);
        $this.next().toggleClass('show');
        $this.next().slideToggle(350);
        jQuery('.toggle').removeClass('active');
        $this.addClass('active');
    }
});


});

})( jQuery );

    // color picker

jQuery(document).ready(function(){
  
  if(jQuery('#advance_search_posttype_chkbox .checkarea').is(":checked"))
  {
    jQuery('#postSearch').show();
  }else{
    jQuery('#postSearch').hide();
  }

  if(jQuery('#advance_search_taxonomy_chkbox .checkarea').is(":checked"))
  {
    jQuery('#taxonomySearch').show();
  }else{
    jQuery('#taxonomySearch').hide();
  }

  jQuery('#advance_search_posttype_chkbox .checkarea').click(function() {
    
    posttype_with_taxonomies(jQuery('#advance_search_posttype_chkbox .checkarea'), jQuery('#postSearch'),'subposting');
  });
  jQuery('#advance_search_taxonomy_chkbox .checkarea').click(function() {
    
    posttype_with_taxonomies(jQuery('#advance_search_taxonomy_chkbox .checkarea'), jQuery('#taxonomySearch'),'subtaxonoy');
  });

  function posttype_with_taxonomies(parentselector,hideselector,data_check){
    var advanced_search_check_posttype = [];
    parentselector.each(function(index, value) {
        if(jQuery(this).is(":checked")){
          advanced_search_check_posttype.push(value);
        }
      });
    if(advanced_search_check_posttype.length == 0){
      hideselector.hide();
      jQuery("[data-check="+data_check+"]").prop('checked', false);
    }
    else{
      hideselector.show();
    }
  }


    jQuery('.my-color-field').wpColorPicker();
    jQuery('.wpas_color_field').each(function(){
        jQuery(this).wpColorPicker();
    });
});

// clone and delete search

jQuery(document).ready(function(){

    // clone popup
    jQuery('.aclone_search').click(function(e) {
      jQuery("div#ClonePopup").css({"visibility":"visible", "opacity":"1"});
      var scname = jQuery(this).attr('data-scname');
      var target = jQuery(this).data('target');
      jQuery("span.csname-heading").text(scname);
      jQuery("#advance-search-ajaxsearch_form").val(scname);
    });

    jQuery('.aspopup-header .close').click(function(e) {
      jQuery("div#ClonePopup").removeAttr("style");
	  jQuery('#ClonePopup .pro-info').hide();
	  
    });

    // clone / delete setting 

    jQuery('.search_imp_ajax').click(function() {
      
        var search_id = jQuery(this).attr('data-id');
        var type = jQuery(this).attr('data-type');
        var nonce = jQuery("#extra_ajax_hidden").val();
        var clonediv = jQuery('.search_box_list ul li:last');
        var clonenum = jQuery(this).attr('data-num');
       
        var shortcodeName = jQuery("#advance-search-ajaxsearch_form").val();
        var dataAjax = jQuery(this).attr('data-ajax');
        if (jQuery(this).hasClass('delete_search')){
          var del=confirm("Are you sure you want to delete this record?");
          if(del==true){
            jQuery(".delete_search").attr("data-ajax", "Yes");
            dataAjax = "Yes"
            dataDeleteAjax = "Yes"
          }else{
            jQuery(".delete_search").attr("data-ajax", "No");
            dataAjax = "No"
          }
          
        }
        
       if(dataAjax == 'Yes'){          
          var test=0;
          jQuery.ajax({
              url: ajaxurl, // domain/wp-admin/admin-ajax.php
              type: "POST",
              dataType: "json",
              data: {
                  action: "WPAS_Advanced_Search_extra_ajax",
                  ajax_type: type,
                  security:nonce,
                  form_id : search_id,
                  'search_form_name': shortcodeName,
                  "cloneNum" : clonenum
          },
              success: function(data) {
                  if(data.astext == 'true' ){
                    jQuery('.as-alreadyexists').hide();
                    jQuery('.as-validname').hide();
                     jQuery('.as-success').show();                     
                    setTimeout(function(){
                    var admin_url = jQuery('.aclone_search').attr('data_url');
                   window.location.href = admin_url;
                  }, 300);
                    
                  }
                  else if(data.astext == 'already exists' ){
                    jQuery('.as-alreadyexists').show();
                    jQuery('.as-validname').hide(); 
                    return false;
                   }else if(data.astext == 'empty'){
                    jQuery('.as-validname').show();
                      jQuery('.as-alreadyexists').hide();
                      jQuery('.as-success').hide();
                   }
                   else{
					setTimeout(function(){
                    alert('Something went wrong. Please try again.');
					    location.reload();
					  }, 300);
                   }
  
              },
              error: function(data){
                
              }
          });
      }
        
    });
    jQuery('.btn-submit').addClass('pointer-event-none');
    jQuery('#advance-search-search_form').on('keyup', function () {
      var nameInput = jQuery(this).val().replace(/^\s+|\s+$/g, "").length != 0;
      let isValid = nameInput != "" && nameInput != null;
      if(nameInput == "" || nameInput == null){
        jQuery('.btn-submit').addClass('pointer-event-none');
      }
      else{
        jQuery('.btn-submit').removeClass('pointer-event-none');
      }
    });
	
    // export / import ajax

    jQuery('#export_search').click(function() {
        var export_list = document.getElementsByName('wpas_export_form_list')[0];
        var export_forms_id = [];
        for(i=0; i < export_list.length; i++){
            if(export_list.options[i].selected){
                export_forms_id.push(export_list.options[i].value);
            }
        }

        if(export_forms_id.length > 0) {
          var nonce = jQuery("#export_form_hidden").val();
          jQuery(".export_loader").css({'display':'inline-block'});
            jQuery.ajax({
                url: ajaxurl, // domain/wp-admin/admin-ajax.php
                type: "POST",
                dataType: "json",
                data: {
                    action: "WPAS_Advanced_Search_export",
                    security : nonce,
                    form_ids : export_forms_id
                },
                success: function(data) {
                    if(data.result == true) {
                        jQuery(".export_loader").css({'display':'none'});
                        jQuery("#export_data").html('').html(data.string);
                    }
                    if(data.result == false) {
                        jQuery(".export_loader").css({'display':'none'});
                        setTimeout(function(){
							alert('Something went wrong. Please try again.');
					    location.reload();
					  }, 300);
                        
                    }
                }
            });

        }
        else {
            alert('Please select form from export List !');
        }

    });

    // import form

    jQuery('#import_search').click(function() {

        var import_data = jQuery("#import_data").val();

        if(import_data == '') {
          alert('Please enter import data !');
          jQuery("#import_data").focus();
        }
        else {
        var nonce = jQuery("#import_form_hidden").val();
        jQuery(".import_loader").css({'display':'inline-block'});
        jQuery.ajax({
            url: ajaxurl, // domain/wp-admin/admin-ajax.php
            type: "POST",
            dataType: "json",
            data: {
                action: "WPAS_Advanced_Search_import",
                security : nonce,
                import_data : import_data
            },
            success: function(data) {
                if(data.result == 'true') {
                    jQuery(".import_loader").css({'display':'none'});
                    alert('Data Import successfully.');
                    location.reload(true);
                }
                if(data.result == 'false') {
                    setTimeout(function(){
                    alert('Something went wrong. Please try again.');
                    jQuery(".import_loader").css({'display':'none'});
					    location.reload();
					  }, 300);
                }
            }
        });
      }

    });
	
	jQuery("input.restricted").keyup(function (e) {
			var str = jQuery(this).val();
			var string = str.replace("-", '');
			jQuery(this).val(string);
		});

});

    // chart

(function ($) {
  "use strict"; //You will be happier

  $.fn.horizBarChart = function( options ) {

    var settings = $.extend({
      // default settings
      selector: '.bar',
      speed: 3000
    }, options);

    // Cycle through all charts on page
      return this.each(function(){
        // Start highest number variable as 0
        // Nowhere to go but up!
      var highestNumber = 0;

      // Set highest number and use that as 100%
      // This will always make sure the graph is a decent size and all numbers are relative to each other
        $(this).find($(settings.selector)).each(function() {
          var num = $(this).data('number');
        if (num > highestNumber) {
          highestNumber = num;
        }
        });

      // Time to set the widths
        $(this).find($(settings.selector)).each(function() {
            var bar = $(this),
                // get all the numbers
                num = bar.data('number'),
                // math to convert numbers to percentage and round to closest number (no decimal)
                percentage = Math.round((num / highestNumber) * 100) + '%';
            // Time to assign and animate the bar widths
            $(this).animate({ 'width' : percentage }, settings.speed);
        });
      });

  }; // horizChart

}(jQuery));

  // chart js

jQuery(document).ready(function(){
  jQuery('.chart').horizBarChart({
    selector: '.bar',
    speed: 1000
  });
});

// loader and magifire icons active

jQuery(document).ready(function() {

  // loader icon

  jQuery('.loader_lists li').click(function() {
    jQuery('.loader_lists li').removeClass('active');
    jQuery(this).addClass('active');
    var icon = jQuery(this).attr('data-icon');
    jQuery("#loader_icon").val(icon);
  });

  // maginfire icon

  jQuery('.magnifier_icon_design li').click(function() {
    jQuery('.magnifier_icon_design li').removeClass('active');
    jQuery(this).addClass('active');
    var icon = jQuery(this).attr('data-icon');
    jQuery("#magnifire_icon").val(icon);
  });
});

/*********** verify email popup *************/

jQuery(window).load(function (e) {
  jQuery('.wfmrs').delay(10000).slideDown('slow');
  jQuery('.lokhal_verify_email_popup').slideDown();
  jQuery('.lokhal_verify_email_popup_overlay').show();
});

jQuery(document).ready(function () {

jQuery('.lokhal_cancel').click(function (e) {
    e.preventDefault();
    var email = jQuery('#verify_lokhal_email').val();
    var fname = jQuery('#verify_lokhal_fname').val();
    var lname = jQuery('#verify_lokhal_lname').val();
    jQuery('.lokhal_verify_email_popup').slideUp();
    jQuery('.lokhal_verify_email_popup_overlay').hide();
    send_ajax('cancel', email, fname, lname);
  });
  jQuery('.verify_local_email').click(function (e) {
    e.preventDefault();
    var email = jQuery('#verify_lokhal_email').val();
    var fname = jQuery('#verify_lokhal_fname').val();
    var lname = jQuery('#verify_lokhal_lname').val();
	var checkEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	
    var send_mail = true;
    jQuery('.error_msg').hide();
    if (fname == '') {
      jQuery('#fname_error').show();
      send_mail = false;
    }
    if (lname == '') {
      jQuery('#lname_error').show();
      send_mail = false;
    }
    if (email == '') {
      jQuery('#email_error').show();
      send_mail = false;
    } 
	else if (!checkEmail.test(email)){
	jQuery('#email_error_valid').show();
	jQuery('#email_error').hide();
	 send_mail = false;
	}
    if (send_mail) {
      jQuery('.lokhal_verify_email_popup').slideUp();
      jQuery('.lokhal_verify_email_popup_overlay').hide();
      send_ajax('verify', email, fname, lname);
    }
  });
  // mac
  if (navigator.userAgent.indexOf('Mac OS X') != -1) {
    jQuery("body").addClass("mac");
  } else {
    jQuery("body").addClass("windows");
  }

  jQuery('.fm_close_msg').click(function (e) {
    jQuery('.fm_msg_popup').fadeOut();
  });

});

function send_ajax(todo, email, fname, lname) {
  jQuery.ajax({
    type: "post",
    url: ajaxurl,
    data: {
      action: "wpas_verify_email",
      'todo': todo,
      'vle_nonce': vle_nonce,
      'lokhal_email': email,
      'lokhal_fname': fname,
      'lokhal_lname': lname
    },
    success: function (response) {
      if (response == '1') {
        alert('A confirmation link has been sent to your email address. Please click on the link to verify your email address.');
      } else if (response == '2') {
      }
    }
  });
}

//characters only
jQuery(document).ready(function () {

 /* open border width and color if selected value is not none start */
	var SelectedborderVal= jQuery('#InputLayoutBorder').val();

	if(SelectedborderVal != 'none'){
		jQuery('#InputBorder .hideBorder').show(500);
	}else{
		jQuery('#InputBorder .hideBorder').hide(500);
	}
	
	var SelectedborderVal= jQuery('#BoxLayoutBorder').val();

	if(SelectedborderVal != 'none'){
		jQuery('#InputBorderBox .hideBorder').show(500);
	}else{
		jQuery('#InputBorderBox .hideBorder').hide(500);
	}
	/* open border width and color if selected value is not none end */
	
  jQuery('.serach_input_style').keypress(function(e) {
    var inputValue = event.charCode;
      if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)){
        event.preventDefault();
    }
});
	
jQuery('select.borderType').on('change', function() {
  var $this = jQuery(this);
  var borderVal= this.value;
  
  if (borderVal == 'none'){
	
    $this.parents('.searchMBox li').siblings().find('.hideBorder').hide(500);
	$this.parents('.searchMBox li').siblings().find('.input_style').val('0');
	$this.parents('.searchMBox li').siblings().find('.wp-picker-clear').trigger('click');
	
    }
  else{
    $this.parents('.searchMBox li').siblings().find('.hideBorder').show(500);
  }
});
jQuery('select.theme_replaced').on('change', function() {  
  var themeVal= jQuery(this).find(":selected").val()
  if (jQuery(themeVal) === ""){
    
  }
  else{
    jQuery('.none-option').val('0'); 
  }
});
  
});
