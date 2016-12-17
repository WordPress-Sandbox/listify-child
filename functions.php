<?php
/**
 * Listify child theme.
 */
function listify_child_styles() {

	/* CSS */
	wp_enqueue_style( 'inttelinput', get_stylesheet_directory_uri() . '/assets/inttelinput/css/intlTelInput.css');
    wp_enqueue_style( 'remodal_css', get_stylesheet_directory_uri() . '/assets/remodal/remodal.css');
    wp_enqueue_style( 'remodal_default', get_stylesheet_directory_uri() . '/assets/remodal/remodal-default-theme.css');
    wp_enqueue_style( 'listify-child', get_stylesheet_uri() );

    /* JS */
    wp_enqueue_script( 'inttelinput', get_stylesheet_directory_uri() . '/assets/inttelinput/js/intlTelInput.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'utils', get_stylesheet_directory_uri() . '/assets/inttelinput/js/utils.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'remodal_js', get_stylesheet_directory_uri() . '/assets/remodal/remodal.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'geolocate', get_stylesheet_directory_uri() . '/js/geolocate.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'bs_gelocate', get_stylesheet_directory_uri() . '/js/bs_geolocate.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'googlemap_api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDB0s2f700pfcaEjKUrYBkes4F9A3yg40M&libraries=places', array('jquery'), '1.0', true);
    wp_enqueue_script( 'listify-child-bs_script', get_stylesheet_directory_uri() . '/js/bs_scripts.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'listify-child-script', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery'), '1.0', true);
    $data = array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'   =>  wp_create_nonce( "test_nonce" ),
    	'themepath' => get_stylesheet_directory_uri()
    );
    wp_localize_script('listify-child-script', 'local', $data);
}
add_action( 'wp_enqueue_scripts', 'listify_child_styles', 999 );

function listify_child_theme_setup() {
    load_child_theme_textdomain( 'lisify_child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'listify_child_theme_setup' );


/* add business role */
add_role('business', __('Business'));


/* dynamic link to tempate */
function get_template_page_link($t) {
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => '_wp_page_template',
                'value' => $t
            )
        )
    );
    $pages = new WP_Query( $args );

    if( ! empty( $pages->posts ) )
        return get_permalink( $pages->post->ID );
}


/**
 * redirect after login
 */
function mysavingwallet_redirect_after_login( $url, $request, $user ){
    if( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
        if( $user->has_cap( 'administrator' ) ) {
            $url = admin_url();
        } else {
            $url = get_template_page_link('profile.php');
        }
    }
    return $url;
}
add_filter('login_redirect', 'mysavingwallet_redirect_after_login', 10, 3 );

function mysavingwallet_redirect_after_logout(){
   $redirect_to = home_url();
   wp_redirect($redirect_to);
   exit();
}

add_action('wp_logout', 'mysavingwallet_redirect_after_logout');

// redirect if admin page 
function mysavingwallet_redirect_if_admin_page()
{   
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX )  
        return;

    if ( is_admin() && !current_user_can('administrator') ) {
            wp_redirect(get_template_page_link('profile.php'));
            exit();
    }
}

add_action( 'admin_init', 'mysavingwallet_redirect_if_admin_page' );


require get_stylesheet_directory() . '/inc/ajax.php';






