<?php


defined('ABSPATH') OR exit;




$sql = "CREATE TABLE " . $tables['kunden'] . " (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Der Primärindex',
	`erstellt_am` DATETIME NOT NULL,
	`erstellt_am_utc` DATETIME NOT NULL,
	`erstellt_ip` VARCHAR(45) NOT NULL COMMENT 'Die IP Adresse (v4 oder v6 oder v4 mit v6 Notation)' COLLATE 'utf8_unicode_ci',
	`email` VARCHAR(100) NOT NULL COMMENT 'E-Mail ist gleichzeitig der Login' COLLATE 'utf8_unicode_ci',
	`passwort` VARCHAR(255) NOT NULL COMMENT 'gehashed' COLLATE 'utf8_unicode_ci',
	`vorname` VARCHAR(100) NOT NULL DEFAULT '' COMMENT 'Der Vorname' COLLATE 'utf8_unicode_ci',
	`nachname` VARCHAR(100) NOT NULL DEFAULT '' COMMENT 'Der Nachname' COLLATE 'utf8_unicode_ci',
	`kunden_nr` DATETIME NULL DEFAULT NULL,
	`letzter_login` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Wann war der letzte Login',
	`letzter_login_utc` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Wann war der letzte Login',
	`letzte_aktivitaet` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Wann wurde die letzte Seite neu geladen',
	`letzte_aktivitaet_utc` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Wann wurde die letzte Seite neu geladen',
	`email_neu` VARCHAR(150) NOT NULL DEFAULT '' COMMENT 'Falls der User die E-Mail ändern will. Diese E-Mail ist nicht verifiziert' COLLATE 'utf8_unicode_ci',
	`email_verifiziert_am` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Wann wurde die E-Mail verifiziert',
	`email_verifiziert_am_utc` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Wann wurde die E-Mail verifiziert',
	`email_token` VARCHAR(128) NOT NULL DEFAULT '' COMMENT 'Zum Verifizieren der E-Mail Adresse' COLLATE 'utf8_unicode_ci',
	`pwd_token` VARCHAR(128) NOT NULL DEFAULT '' COMMENT 'Zum neu Setzen des PWD' COLLATE 'utf8_unicode_ci',
	`api_token` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Zum Zugriff auf die API',
	`login_token` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Das Token des Login-Cookies',
	`letztes_passwort` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Das zuletzt verwednete Pwd (gehashed)' COLLATE 'utf8_unicode_ci',
	`password_geaendert_am` DATETIME NOT NULL  DEFAULT '0000-00-00 00:00:00' COMMENT 'Wann wurde das Pwd zum letzten mal geändert' COLLATE 'utf8_unicode_ci',
	`password_geaendert_am_utc` DATETIME NOT NULL  DEFAULT '0000-00-00 00:00:00' COMMENT 'Wann wurde das Pwd zum letzten mal geändert' COLLATE 'utf8_unicode_ci',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `U__email` (`email`),
	UNIQUE INDEX `U__api_token` (`api_token`),
	UNIQUE INDEX `U__login_token` (`login_token`),
	INDEX `K__erstellt_am` (`erstellt_am`),
	INDEX `K__erstellt_am_utc` (`erstellt_am_utc`),
	INDEX `K__email_token` (`email_token`),
	INDEX `K__pwd_token` (`pwd_token`),
	INDEX `K__letzter_login` (`letzter_login`),
	INDEX `K__letzter_login_utc` (`letzter_login_utc`),
	INDEX `K__email_verifiziert_am` (`email_verifiziert_am`),
	INDEX `K__email_verifiziert_am_utc` (`email_verifiziert_am_utc`)
)
COMMENT='Die Tabelle die die Grunddaten aller Kunden beinhaltet'
";

$wpdb->query($sql);


$sql = "CREATE TABLE " . $tables['kundendaten'] . " (
	`user_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Die id des Kunden, zu dem die Daten gehören',
	`key1` VARCHAR(50) NOT NULL COMMENT 'Die \"Section\" der Daten',
	`key2` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Der \"Key\" der Daten oder leer, falls nicht benötigt',
	`daten` LONGTEXT NULL COMMENT 'Die zu speichernden textuelle Daten',
	`daten_bin` LONGBLOB NULL COMMENT 'Die zu speichernden binären Daten',
	PRIMARY KEY (`user_id`, `key1`, `key2`),
	INDEX `K__daten` (`daten`(255))
)
COMMENT='Die Zusatzinformationen zum einzelnen Kunden'
";

$wpdb->query( $sql );
