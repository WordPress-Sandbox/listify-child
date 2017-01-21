<?php 

/* Search Users */
add_action('wp_ajax_SearchUser', 'SearchUser_func');

/* debit credit user balance */
add_action('wp_ajax_debitCreditUserBalance', 'debitCreditUserBalance_func');