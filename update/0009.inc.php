<?php

use muv\KundenKonto\Classes\DefaultSettings;


defined( 'ABSPATH' ) OR exit;




add_option( 'muv-kk-email-vorlage-konto-loeschen-betreff', DefaultSettings::EMAIL_VORLAGE_KONTO_LOESCHEN_BETREFF, false, 'no' );
add_option( 'muv-kk-email-vorlage-konto-loeschen-typ', DefaultSettings::EMAIL_VORLAGE_TYP, false, 'no' );
add_option( 'muv-kk-email-vorlage-konto-loeschen-text', DefaultSettings::EMAIL_VORLAGE_KONTO_LOESCHEN_TEXT, false, 'no' );
add_option( 'muv-kk-email-vorlage-konto-loeschen-html', DefaultSettings::EMAIL_VORLAGE_KONTO_LOESCHEN_HTML, false, 'no' );
