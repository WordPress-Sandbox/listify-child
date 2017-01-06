<?php 


// http://techslides.com/creating-a-wordpress-plugin-admin-page
// https://pippinsplugins.com/loading-scripts-correctly-in-the-wordpress-admin/

add_action( 'admin_menu', 'savingwallet_admin_page' );

function savingwallet_admin_page() {
	add_menu_page( 'Saving Wallet', 'Saving Wallet', 'manage_options', 'savingwallet-admin.php', 'savingwallet_admin', 'dashicons-tickets', 8);
	add_submenu_page( 'savingwallet-admin.php', 'Withdraw Request', 'Withdraw Request', 'manage_options', 'withdraw-request.php', 'withdraw_request' ); 
	add_submenu_page( 'savingwallet-admin.php', 'Bank Verification', 'Bank Verification', 'manage_options', 'bank-verification.php', 'bank_verification' ); 
}


function enqueue_admin_scripts($hook) {

  // if( 'bank-verification.php' != $hook ) {
  // 	return;
  // }  

  /* css */
  wp_enqueue_style('accordion', get_stylesheet_directory_uri() . '/inc/admin/assets/css/jquery.accordion.css');
  wp_enqueue_style('savingwallet', get_stylesheet_directory_uri() . '/inc/admin/assets/css/style.css');
  /* js */
  wp_enqueue_script('accordion', get_stylesheet_directory_uri() . '/inc/admin/assets/js/jquery.accordion.js', array('jquery'));
  wp_enqueue_script('savingwallet', get_stylesheet_directory_uri() . '/inc/admin/assets/js/script.js', array('jquery'));
}

add_action( 'admin_enqueue_scripts', 'enqueue_admin_scripts' );



function bank_verification() {
	echo '<h1> Hey! What\'s up? </h1>';
}