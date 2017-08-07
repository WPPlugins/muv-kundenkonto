<?php


defined( 'ABSPATH' ) OR exit;




$sql = "ALTER TABLE " . $tables['kunden'] . "
	CHANGE COLUMN `kunden_nr` `kundennr` DATETIME NULL DEFAULT NULL AFTER `nachname`;";
$wpdb->query( $sql );


$sql = "ALTER TABLE " . $tables['kundendaten'] . " ALTER `user_id` DROP DEFAULT;";
$wpdb->query( $sql );

$sql = "ALTER TABLE " . $tables['kundendaten'] . "
	CHANGE COLUMN `user_id` `kunde_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Die id des Kunden, zu dem die Daten gehören' FIRST;";
$wpdb->query( $sql );

$sql = "ALTER TABLE " . $tables['kundendaten_ext'] . "	ALTER `user_id` DROP DEFAULT;";
$wpdb->query( $sql );

$sql = "ALTER TABLE " . $tables['kundendaten_ext'] . "
	CHANGE COLUMN `user_id` `kunde_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Die id des Kunden, zu dem die Daten gehören' FIRST;";
$wpdb->query( $sql );
