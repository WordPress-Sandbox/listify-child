<?php 
function topup_short_func() {
	$mysavingwallet = new Mysavingwallet;
	return $mysavingwallet->topup();
}
add_shortcode('topup', 'topup_short_func');


function cashbacks_short_func() {
	$mysavingwallet = new Mysavingwallet;
	return $mysavingwallet->cashbacks();
}
add_shortcode('cashbacks', 'cashbacks_short_func');


function withdrawls_short_func() {
	$mysavingwallet = new Mysavingwallet;
	return $mysavingwallet->withdrawls();
}
add_shortcode('withdrawls', 'withdrawls_short_func');