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

/* verify unverify bank account */
add_action('wp_ajax_verify_unverify_banks', 'verify_unverify_banks_func');

/* approve decline customer withdraw */
add_action('wp_ajax_approve_decline_withdraw', 'approve_decline_withdraw_func');

/* update admin bank note */
add_action('wp_ajax_update_admin_notes', 'update_admin_notes_func');

/* generate reports */
add_action('wp_ajax_generate_report', 'generate_report_func');