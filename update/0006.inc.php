<?php


defined( 'ABSPATH' ) OR exit;




$sql = "ALTER TABLE " . $tables['kunden'] . " 
	CHANGE COLUMN `letzter_login` `letzter_login` DATETIME NULL DEFAULT NULL COMMENT 'Wann war der letzte Login' AFTER `kunden_nr`,
	CHANGE COLUMN `letzter_login_utc` `letzter_login_utc` DATETIME NULL DEFAULT NULL COMMENT 'Wann war der letzte Login' AFTER `letzter_login`,
	CHANGE COLUMN `letzte_aktivitaet` `letzte_aktivitaet` DATETIME NULL DEFAULT NULL COMMENT 'Wann wurde die letzte Seite neu geladen' AFTER `letzter_login_utc`,
	CHANGE COLUMN `letzte_aktivitaet_utc` `letzte_aktivitaet_utc` DATETIME NULL DEFAULT NULL COMMENT 'Wann wurde die letzte Seite neu geladen' AFTER `letzte_aktivitaet`,
	CHANGE COLUMN `email_verifiziert_am` `email_verifiziert_am` DATETIME NULL DEFAULT NULL COMMENT 'Wann wurde die E-Mail verifiziert' AFTER `email_neu`,
	CHANGE COLUMN `email_verifiziert_am_utc` `email_verifiziert_am_utc` DATETIME NULL DEFAULT NULL COMMENT 'Wann wurde die E-Mail verifiziert' AFTER `email_verifiziert_am`,
	CHANGE COLUMN `passwort_geaendert_am` `passwort_geaendert_am` DATETIME NULL DEFAULT NULL COMMENT 'Wann wurde das Pwd zum letzten mal geändert' AFTER `letztes_passwort`,
	CHANGE COLUMN `passwort_geaendert_am_utc` `passwort_geaendert_am_utc` DATETIME NULL DEFAULT NULL COMMENT 'Wann wurde das Pwd zum letzten mal geändert' AFTER `passwort_geaendert_am`;";

$wpdb->query( $sql );

$sql = "UPDATE " . $tables['kunden'] . " SET `letzter_login` = NULL WHERE `letzter_login` = '0000-00-00 00:00:00'";
$wpdb->query( $sql );

$sql = "UPDATE " . $tables['kunden'] . " SET `letzter_login_utc` = NULL WHERE `letzter_login_utc` = '0000-00-00 00:00:00'";
$wpdb->query( $sql );

$sql = "UPDATE " . $tables['kunden'] . " SET `letzte_aktivitaet` = NULL WHERE `letzte_aktivitaet` = '0000-00-00 00:00:00'";
$wpdb->query( $sql );

$sql = "UPDATE " . $tables['kunden'] . " SET `letzte_aktivitaet_utc` = NULL WHERE `letzte_aktivitaet_utc` = '0000-00-00 00:00:00'";
$wpdb->query( $sql );

$sql = "UPDATE " . $tables['kunden'] . " SET `email_verifiziert_am` = NULL WHERE `email_verifiziert_am` = '0000-00-00 00:00:00'";
$wpdb->query( $sql );

$sql = "UPDATE " . $tables['kunden'] . " SET `email_verifiziert_am_utc` = NULL WHERE `email_verifiziert_am_utc` = '0000-00-00 00:00:00'";
$wpdb->query( $sql );

$sql = "UPDATE " . $tables['kunden'] . " SET `passwort_geaendert_am` = NULL WHERE `passwort_geaendert_am` = '0000-00-00 00:00:00'";
$wpdb->query( $sql );

$sql = "UPDATE " . $tables['kunden'] . " SET `passwort_geaendert_am_utc` = NULL WHERE `passwort_geaendert_am_utc` = '0000-00-00 00:00:00'";
$wpdb->query( $sql );

