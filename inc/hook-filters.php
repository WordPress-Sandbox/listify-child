<?php 

/* balance to users columns */
function manage_users_columns_head( $column ) {

	  $columns = array(
        'cb' => '<input type="checkbox" />',
        'username' => __('Username', 'listify_child'),
        'name' => __('Name', 'listify_child'),
        'email' => __('Email', 'listify_child'),
        'role' => __('Role', 'listify_child'),
        'user_id' => __('User ID', 'listify_child'),
        'posts' => __('Posts', 'listify_child'),
        'cashback' => __('Cashback', 'listify_child'),
        'wallet_balance' => __('Wallet Balance', 'listify_child')
    );
    return $columns;
}

function manage_users_custom_column_content( $val, $column_name, $user_id ) {
    global $msw;
    switch ($column_name) {
        case 'user_id' :
            return $user_id;
            break;     

        case 'cashback' :
            $cashback = get_the_author_meta( 'cashback_percentage', $user_id );
            if($cashback) {
            	return $cashback . '%';
            }
            break;        

        case 'wallet_balance' :
            $balance = get_the_author_meta( 'wallet_balance', $user_id );
            return $balance ? $msw->currency_symbol . $balance : $msw->currency_symbol . '0.00';
            break;

    }

    return $val;

}

add_filter( 'manage_users_columns', 'manage_users_columns_head' );
add_filter( 'manage_users_custom_column', 'manage_users_custom_column_content', 10, 3 );


// custom cashback percentage field in user profile 
add_action( 'show_user_profile', 'user_extra_profile_fields' );
add_action( 'edit_user_profile', 'user_extra_profile_fields' );

function user_extra_profile_fields() {
	locate_template( array('inc/templates/cashback_percentage_user_profile.php'), true, true);
}

// save user cashback percentage field 
add_action( 'personal_options_update', 'user_extra_profile_fields_save' );
add_action( 'edit_user_profile_update', 'user_extra_profile_fields_save' );

function user_extra_profile_fields_save( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	update_usermeta( $user_id, 'cashback_percentage', $_POST['cashback_percentage'] );
}


/* show cashback percentage */
function listify_content_job_listing_before_func() {
	global $msw;
	$listing = get_post();
	$cashback_percentage = get_user_meta($listing->post_author, 'cashback_percentage', true);
	$show_half_customer = $cashback_percentage / 2; 
	if($show_half_customer > 0 ) {
		echo "<span class=\"cashback_percentage\"> {$show_half_customer}%</span>";
	}
}
add_action('listify_content_job_listing_before', 'listify_content_job_listing_before_func');

// https://wpjobmanager.com/document/tutorial-adding-a-salary-field-for-jobs/
add_action( 'job_manager_job_filters_search_jobs_end', 'filter_by_cashback_percentage' );
function filter_by_cashback_percentage() {
	locate_template( array('inc/templates/search_by_cashback.php'), true, true);
}

add_filter( 'job_manager_get_listings', 'filter_by_cashback_query_args', 10, 2 );
function filter_by_cashback_query_args( $query_args, $args ) {

	global $msw;

	if ( isset( $_POST['form_data'] ) ) {
		parse_str( $_POST['form_data'], $form_data );

		// min cashback 
		if ( ! empty( $form_data['min_cashback'] ) ) {
			$min_cashback = sanitize_text_field( $form_data['min_cashback'] );
			$min_cashback_users = $msw->getUserByCashbackAmount($min_cashback*2, '>=');
			// This will show the 'reset' link
			add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );
		}		

		// max cashback 
		if ( ! empty( $form_data['max_cashback'] ) ) {
			$max_cashback = sanitize_text_field( $form_data['max_cashback'] );
			$max_cashback_users = $msw->getUserByCashbackAmount($max_cashback*2, '<=');
			// This will show the 'reset' link
			add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );
		}

		// combine all authors
		$authors = array_merge( (array) $min_cashback_users, (array) $max_cashback_users);
		$query_args['author__in'] = $authors;
	}
	
	return $query_args;

}

function single_page_cashback_badge() {
	global $msw;
	$listing = get_post();
	$cashback_percentage = get_user_meta($listing->post_author, 'cashback_percentage', true);
	$show_half_customer = $cashback_percentage / 2; 
	if($show_half_customer > 0 ) {
		echo "<span class=\"single_listing_page cashback_percentage\"> {$show_half_customer}%</span>";
	}
}

add_action('single_job_listing_meta_start', 'single_page_cashback_badge');
