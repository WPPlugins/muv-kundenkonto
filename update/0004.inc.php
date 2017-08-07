<?php

use muv\KundenKonto\Classes\DefaultSettings;


defined( 'ABSPATH' ) OR exit;




add_option( 'muv-kk-erlaube-pseudo-login', DefaultSettings::ERLAUBE_PSEUDO_LOGIN, false, 'no' );


$sql = "ALTER TABLE " . $tables['kunden'] . "
	ADD COLUMN `pseudo_login_token` VARCHAR(32) NULL DEFAULT NULL COMMENT 'Das Token des Pseudo Login-Cookies' COLLATE 'utf8_unicode_ci' AFTER `login_token`,
	ADD UNIQUE INDEX `U__pseudo_login_token` (`pseudo_login_token`);
	";
$wpdb->query( $sql );

