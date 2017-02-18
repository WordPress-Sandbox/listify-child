<?php
/**
 * Listify child theme.
 */

function listify_child_styles() {
    global $msw;
    $api = get_theme_mod( 'map-behavior-api-key', false );
	/* CSS */
    wp_enqueue_style('font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
	wp_enqueue_style( 'inttelinput', get_stylesheet_directory_uri() . '/assets/inttelinput/css/intlTelInput.css');
    wp_enqueue_style( 'remodal_css', get_stylesheet_directory_uri() . '/assets/remodal/remodal.css');
    wp_enqueue_style( 'remodal_default', get_stylesheet_directory_uri() . '/assets/remodal/remodal-default-theme.css');
    wp_enqueue_style( 'magnific-popup', get_stylesheet_directory_uri() . '/assets/magnific-popup/magnific-popup.css');
    wp_enqueue_style( 'listify-child', get_stylesheet_uri() );

    /* JS */
    wp_enqueue_script( 'inttelinput', get_stylesheet_directory_uri() . '/assets/inttelinput/js/intlTelInput.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'utils', get_stylesheet_directory_uri() . '/assets/inttelinput/js/utils.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'remodal_js', get_stylesheet_directory_uri() . '/assets/remodal/remodal.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'geolocate', get_stylesheet_directory_uri() . '/js/geolocate.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'bs_gelocate', get_stylesheet_directory_uri() . '/js/bs_geolocate.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'magnific-popup', get_stylesheet_directory_uri() . '/assets/magnific-popup/magnific-popup.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'googlemap_api', 'https://maps.googleapis.com/maps/api/js?key='.$api.'&libraries=places', array('jquery'), '1.0', true);
    wp_enqueue_script( 'listify-child-bs_script', get_stylesheet_directory_uri() . '/js/bs_scripts.js', array('jquery'), '1.0', true);
    wp_enqueue_script( 'listify-child-script', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery'), '1.0', true);
    $data = array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'upload_url' => admin_url('async-upload.php'),
        'currency_symbol' => $msw->currency_symbol,
        'cashback_percentage' => $msw->getMetaValue('cashback_percentage'),
        'nonce'   =>  wp_create_nonce( "listify_none" ),
    	'themepath' => get_stylesheet_directory_uri()
    );
    wp_localize_script('listify-child-script', 'local', $data);
}
add_action( 'wp_enqueue_scripts', 'listify_child_styles', 999 );

function listify_child_theme_setup() {
    load_child_theme_textdomain( 'listify_child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'listify_child_theme_setup' );


/* add business role */
add_role('business', __('Business'));

/* set businesses cashback percentage as meta box to each listing */
add_action('init', 'update_all_cashback_to_listing_metabox');
function update_all_cashback_to_listing_metabox()
{
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'job_listing'
    );
    $posts_array = get_posts( $args );
    foreach($posts_array as $post)
    {
        $user_cb_percentage = get_user_meta($post->post_author, 'cashback_percentage', true);
        update_post_meta($post->ID, 'cashback_percentage', $user_cb_percentage);
    }
}


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

/* remove box shadow padding on myaccount page */
function add_css_if_has_shortcode( $content ) { 
    if (is_page() && has_shortcode($content, 'woocommerce_my_account')) {
        $style = '<style>
                    .content-box {
                        box-shadow: none;
                    }
                    .type-page.content-box.content-box-wrapper .content-box-inner {
                        padding: 0 !important;
                    }
                </style>';
        $content = $content . $style;
    }
    return $content;
}
add_filter( 'the_content', 'add_css_if_has_shortcode' );


/**
 * redirect after login
 */
function mysavingwallet_redirect_after_login( $url, $request, $user ){
    if( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
        if( $user->has_cap( 'administrator' ) ) {
            $url = admin_url();
        } else {
            $url = get_permalink( get_option('woocommerce_myaccount_page_id') );
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
            wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ));
            exit();
    }
}

add_action( 'admin_init', 'mysavingwallet_redirect_if_admin_page' );


require_once locate_template('inc/ajax.php');
require_once locate_template('inc/shortcodes.php');
require_once locate_template('inc/hook-filters.php');
require_once locate_template('/inc/class.mysavingwallet.php');
require_once locate_template('/inc/class.MagicPayGateway.php');

if(is_admin()) {
    require_once locate_template('inc/admin/savingwallet_page.php');
    require_once locate_template('inc/admin/ajax_functions.php');
    require_once locate_template('inc/admin/ajax_hook.php');
}


/* allow customers to upload files */
function su_allow_subscriber_to_uploads() {
    $customers = get_role('customer');
    if ( ! $customers->has_cap('upload_files') ) {
        $customers->add_cap('upload_files');
    }
}
add_action('admin_init', 'su_allow_subscriber_to_uploads');

/* modify wp job manager shortcodes */
function savingwallet_submit_job_form_func() {
    global $msw;
    if($msw->get_user_role() == 'customer') {
        echo '<p>Only businesses can add listing</p>';
    } else {
        echo do_shortcode('[submit_job_form]');
    }
}
add_shortcode('savingwallet_submit_job_form', 'savingwallet_submit_job_form_func');