<?php

use muv\KundenKonto\Classes\DefaultSettings;


defined( 'ABSPATH' ) OR exit;




add_option( 'muv-kk-email-vorlage-email-aktivieren-betreff', DefaultSettings::EMAIL_VORLAGE_EMAIL_AKTIVIEREN_BETREFF, false, 'no' );
add_option( 'muv-kk-email-vorlage-email-aktivieren-typ', DefaultSettings::EMAIL_VORLAGE_TYP, false, 'no' );
add_option( 'muv-kk-email-vorlage-email-aktivieren-text', DefaultSettings::EMAIL_VORLAGE_EMAIL_AKTIVIEREN_TEXT, false, 'no' );
add_option( 'muv-kk-email-vorlage-email-aktivieren-html', DefaultSettings::EMAIL_VORLAGE_EMAIL_AKTIVIEREN_HTML, false, 'no' );


add_option( 'muv-kk-email-vorlage-email-geaendert-betreff', DefaultSettings::EMAIL_VORLAGE_EMAIL_GEAENDERT_BETREFF, false, 'no' );
add_option( 'muv-kk-email-vorlage-email-geaendert-typ', DefaultSettings::EMAIL_VORLAGE_TYP, false, 'no' );
add_option( 'muv-kk-email-vorlage-email-geaendert-text', DefaultSettings::EMAIL_VORLAGE_EMAIL_GEAENDERT_TEXT, false, 'no' );
add_option( 'muv-kk-email-vorlage-email-geaendert-html', DefaultSettings::EMAIL_VORLAGE_EMAIL_GEAENDERT_HTML, false, 'no' );
