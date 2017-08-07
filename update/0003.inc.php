<?php

defined( 'ABSPATH' ) OR exit;




add_option( 'muv-kk-api-key', bin2hex( openssl_random_pseudo_bytes( 50 ) ), false, 'no' );


$sql = "ALTER TABLE " . $tables['kunden'] . " DROP COLUMN `api_token`;";
$wpdb->query( $sql );

