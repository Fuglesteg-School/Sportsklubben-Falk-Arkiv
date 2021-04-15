<?php
/*
Plugin Name: Onedrive filer til wordpress post
*/

function log_me($message) {
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));
        } else {
            error_log($message);
        }
    }
}

add_action('admin_menu', 'OFTP_menu_pages');
add_action("admin_init", "OFTP_registrer_innstillinger");
function OFTP_menu_pages() {
    // Add the top-level admin menu
    $page_title = 'Falk avis';
    $menu_title = 'Falk Arkiv';
    $capability = 'manage_options';
    $menu_slug = 'Falk-avis';
    $function = 'Falk_avis';
    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function);

    // Add submenu page with same slug as parent to ensure no duplicates
    $sub_menu_title = 'Lagre i arkivet';
    add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);

	//Innstillinger
	$submenu_page_title = 'Falk innstillinger';
    $submenu_title = 'Innstillinger';
    $submenu_slug = 'Falk-innstillinger';
    $submenu_function = 'Falk_innstillinger';
    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);

    // Now add the submenu page for Help
    $submenu_page_title = 'Falk hjelp';
    $submenu_title = 'Hjelp';
    $submenu_slug = 'Falk-hjelp';
    $submenu_function = 'Falk_hjelp';
    add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
}

function Falk_avis() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // Render the HTML for the Settings page 
	
	print("
	<script type = 'text/javascript' src = '../wp-content/plugins/OnedriveFilTilPost/OnedriveFilTilPost.js'> </script>
	<link rel='stylesheet' href='../wp-content/plugins/OnedriveFilTilPost/OnedriveFilTilPost.css'>
	<h1> Lagre avisartikkel </h1> <br/> <br/>
	
	<label for = 'tittelPåInnlegg'> Tittel På Innlegg </labell><br/>
	<input id = 'tittelPåInnlegg' type = 'text' placeholder = 'Tittel på innlegg'> <br/> <br/>
	
	<div id = 'sider' name = 'sider'>
	
	</div>
	<p id = 'statusTekst' name = 'statusTekst'> &nbsp </p>
	");
	
	
	//tidliger html greier
	// <div class = 'side'>
	// <button type='button' class = 'collapsible' id = 'side1'> Åpne side </button>
	// <div class = 'innhold'>
	// <form name = 'filOpplastingForm' id = 'filOpplastingForm'> 
	// <label for = 'filOpplasting'> Avisartikkel </label> <br/>
	// <input type = 'file' name = 'filOpplasting' id = 'filOpplasting' multiple> <br/><br/>
	
	// </form>
	
	
	//debug greier
	//print(plugin_dir_path( __FILE__ ) . "UtklipteBilder.js");
	
}

function OFTP_registrer_innstillinger(){
	
	register_setting("Falk-innstillinger", "Tesseract_File_Path");
	
}

function Falk_innstillinger(){
	
	if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
	
	?>
		<div class='wrap'>
		<h1>Falk arkiv - Innstillinger</h1>
		<form method='post' action='options.php'> 
	
	<?php
	settings_fields("Falk-innstillinger");
	do_settings_sections("Falk-innstillinger");
	$options = get_option("Tesseract_File_Path");
	$option = $options;
	echo "<br/>
	<label for = 'Falk-innstillinger_Tesseract_File_Path'> File path til Tesseract.exe </label> <br/>
	<input id='Falk-innstillinger_Tesseract_File_Path' name='Tesseract_File_Path' size='40' type='text' value='$option' />
	";

	submit_button(); 
	?>
		</form>
		</div>
	<?php

	
}

function Falk_hjelp() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // Render the HTML for the Help page or include a file that does
}



?>