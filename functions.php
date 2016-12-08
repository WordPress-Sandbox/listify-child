<?php
/**
 * Listify child theme.
 */
function listify_child_styles() {

	/* CSS */
	wp_enqueue_style( 'datedropper', get_stylesheet_directory_uri() . '/assets/datedropper3/datedropper.min.css');
	wp_enqueue_style( 'inttelinput', get_stylesheet_directory_uri() . '/assets/inttelinput/css/intlTelInput.css');
    wp_enqueue_style( 'listify-child', get_stylesheet_uri() );

    /* JS */
    wp_enqueue_script( 'datedropper', get_stylesheet_directory_uri() . '/assets/datedropper3/datedropper.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'inttelinput', get_stylesheet_directory_uri() . '/assets/inttelinput/js/intlTelInput.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'listify-child-script', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery'), '1.0', true);
    $data = array(
    	'themepath' => get_stylesheet_directory_uri()
    );
    wp_localize_script('listify-child-script', 'local', $data);
}
add_action( 'wp_enqueue_scripts', 'listify_child_styles', 999 );

function listify_child_theme_setup() {
    load_child_theme_textdomain( 'lisify_child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'listify_child_theme_setup' );

require get_stylesheet_directory() . '/inc/required_plugins.php';
