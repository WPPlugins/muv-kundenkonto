<?php

/* Diese Datei darf nicht direkt aufgerufen werden... */
defined( 'ABSPATH' ) OR exit;

function muv_kk_getLoggedInKunde( $pseudo = false ) {
	return \muv\KundenKonto\Classes\Auth::getLoggedInKunde( $pseudo );
}

function muv_kk_kundeIstAngemeldet( $pseudo = false ) {
	return ( ! empty( \muv\KundenKonto\Classes\Auth::getLoggedInKunde( $pseudo ) ) );
}

function muv_kk_getLogoutUrl( $goto = '' ) {
	$url = '/muv-kundenkonto/logout';
	if ( ! empty( $goto ) ) {

	}

	return $url;
}
