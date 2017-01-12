<?php 

function listify_content_job_listing_before_func() {
	$listing = get_post();
	$cashback_percentage = get_user_meta($listing->post_author, 'cashback_percentage', true);
	$show_half_customer = $cashback_percentage / 2; 
	if($show_half_customer > 0 ) {
		echo "<span class=\"cashback_percentage\"> {$show_half_customer}%</span>";
	}
}
add_action('listify_content_job_listing_before', 'listify_content_job_listing_before_func');