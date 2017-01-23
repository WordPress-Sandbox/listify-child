<?php 

/* Search Users */
add_action('wp_ajax_SearchUser', 'SearchUser_func');

/* debit credit user balance */
add_action('wp_ajax_debitCreditUserBalance', 'debitCreditUserBalance_func');

/* withdrawls report admin */
add_action('wp_ajax_withdrawls_report', 'withdrawls_report_admin_func');

/* cashback report admin */
add_action('wp_ajax_cashback_report', 'cashback_report_admin_func');

/* banks reports */
add_action('wp_ajax_bank_report', 'bank_report_admin_func');