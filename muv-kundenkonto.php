<?php

/*
 * Plugin Name: muv - Kundenkonto
 * Plugin URI: https://wordpress.org/plugins/muv-kundenkonto
 * Description: Dieses Plugin erweitert Ihren Internet-Auftritt um die Möglichkeit, Ihren Kunden ein Kundenkonto anzubieten. Kunden können sich registrieren, anmelden, Ihr Passwort ändern, ...
 * Version: 1.0.1
 * Requires at least: 4.7
 * Tested up to: 4.7.4
 * Author: Meins und Vogel
 * Author URI: https://muv.com
 * Text Domain: muv-kundenkonto
 * Domain Path: /languages 
 * License: GPLv2 or later
 */

/*
 * muv - Kundenkonto
 * Copyright (C) 2015 - 2017, Meins und Vogel GmbH / muv.com - info@muv.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * Zugriff nur als Plugin innerhalb von Wordpress
 */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/*
 * benötigte Konstanten 
 */

/* die interne Version-Nummer */
if ( ! defined( 'MUV_KK_VER' ) ) {
	define( 'MUV_KK_VER', 2 );
}

/* Der Dateiname (inkl. Pfad) */
if ( ! defined( 'MUV_KK_FILE' ) ) {
	define( 'MUV_KK_FILE', __FILE__ );
}

/* Der Ordner */
if ( ! defined( 'MUV_KK_DIR' ) ) {
	define( 'MUV_KK_DIR', dirname( __FILE__ ) );
}

/* Der UNTER-Order inkl Dateiname des Plugins */
if ( ! defined( 'MUV_KK_BASE' ) ) {
	define( 'MUV_KK_BASE', plugin_basename( __FILE__ ) );
}

/* Die URL zu den Plugin-Dateien */
if ( ! defined( 'MUV_KK_URL' ) ) {
	define( 'MUV_KK_URL', plugins_url( dirname( MUV_KK_BASE ) ) );
}

/* Der inlcude - Ordner, der die Klassen beinhaltet */
if ( ! defined( 'MUV_KK_INC' ) ) {
	define( 'MUV_KK_INC', MUV_KK_DIR . '/includes/' );
}

/* Die Update-Kennung innerhalb unserer Update-Tabelle */
if ( ! defined( 'MUV_KK_UPATE_IDENTIFIER' ) ) {
	define( 'MUV_KK_UPATE_IDENTIFIER', 'muv-kundenkonto' );
}

/* Autoload */
spl_autoload_register( 'muv_kk_autoload' );


/* Hooks */
register_activation_hook( MUV_KK_FILE, array( muv\KundenKonto\Wordpress\Install::class, 'init' ) );
register_deactivation_hook( MUV_KK_FILE, array( muv\KundenKonto\Wordpress\Deactivate::class, 'init' ) );
register_uninstall_hook( MUV_KK_FILE, array( muv\KundenKonto\Wordpress\Uninstall::class, 'init' ) );

/*
 * Unser Plugin besitzt eine Flash - Klasse. Diese benötigt die Session
 */
if ( ! session_id() ) {
	session_start();
}

/* Go... */
add_action( 'plugins_loaded', array( muv\KundenKonto\Plugin\Main::class, 'init' ) );

/* Es gibt einige Funktionen, die von anderen Plugins und Themes verwendet werden können. Diese hier einbinden */
require_once( MUV_KK_DIR . '/muv-kk-funktionen.php' );

function muv_kk_autoload( $class ) {
	if ( strpos( $class, 'muv\KundenKonto' ) === 0 ) {
		$libFile = MUV_KK_INC . $class . '.php';
		$libFile = str_replace( '\\', '/', $libFile );
		if ( file_exists( $libFile ) ) {
			require_once( $libFile );
		}
	}
}
