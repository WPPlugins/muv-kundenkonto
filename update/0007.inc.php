<?php

use muv\KundenKonto\Classes\DefaultSettings;


defined( 'ABSPATH' ) OR exit;




add_option( 'muv-kk-email-vorlage-pwd-geaendert-betreff', DefaultSettings::EMAIL_VORLAGE_PWD_GEAENDERT_BETREFF, false, 'no' );
add_option( 'muv-kk-email-vorlage-pwd-geaendert-typ', DefaultSettings::EMAIL_VORLAGE_TYP, false, 'no' );
add_option( 'muv-kk-email-vorlage-pwd-geaendert-text', DefaultSettings::EMAIL_VORLAGE_PWD_GEAENDERT_TEXT, false, 'no' );
add_option( 'muv-kk-email-vorlage-pwd-geaendert-html', DefaultSettings::EMAIL_VORLAGE_PWD_GEAENDERT_HTML, false, 'no' );
