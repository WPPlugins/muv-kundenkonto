<?php

use muv\KundenKonto\Classes\DefaultSettings;


defined( 'ABSPATH' ) OR exit;






add_option( 'muv-kk-zugang-loeschen', DefaultSettings::ALLGEMEIN_ZUGANG_LOESCHEN, false, 'no' );


add_option( 'muv-kk-login-domain', DefaultSettings::ALLGEMEIN_ANMELDUNG_DOMAIN(), false, 'no' );



add_option( 'muv-kk-logout', DefaultSettings::ALLGEMEIN_ABMELDUNG, false, 'no' );



add_option( 'muv-kk-email-von-name', DefaultSettings::EMAIL_VON_NAME(), false, 'no' );
add_option( 'muv-kk-email-von-mail', DefaultSettings::EMAIL_VON_MAIL(), false, 'no' );
add_option( 'muv-kk-email-vorlage-logo', DefaultSettings::EMAIL_VORLAGE_LOGO, false, 'no' );
add_option( 'muv-kk-email-vorlage-footer', DefaultSettings::EMAIL_VORLAGE_FOOTER(), false, 'no' );
add_option( 'muv-kk-email-vorlage-color', DefaultSettings::EMAIL_VORLAGE_COLOR, false, 'no' );
add_option( 'muv-kk-email-vorlage-color-text', DefaultSettings::EMAIL_VORLAGE_COLOR_TEXT, false, 'no' );
add_option( 'muv-kk-email-vorlage-bgcolor-body', DefaultSettings::EMAIL_VORLAGE_BGCOLOR_BODY, false, 'no' );
add_option( 'muv-kk-email-vorlage-bgcolor-content', DefaultSettings::EMAIL_VORLAGE_BGCOLOR_CONTENT, false, 'no' );


add_option( 'muv-kk-email-vorlage-konto-aktivieren-betreff', DefaultSettings::EMAIL_VORLAGE_KONTO_AKTIVIEREN_BETREFF, false, 'no' );
add_option( 'muv-kk-email-vorlage-konto-aktivieren-typ', DefaultSettings::EMAIL_VORLAGE_TYP, false, 'no' );
add_option( 'muv-kk-email-vorlage-konto-aktivieren-text', DefaultSettings::EMAIL_VORLAGE_KONTO_AKTIVIEREN_TEXT, false, 'no' );
add_option( 'muv-kk-email-vorlage-konto-aktivieren-html', DefaultSettings::EMAIL_VORLAGE_KONTO_AKTIVIEREN_HTML, false, 'no' );

add_option( 'muv-kk-email-vorlage-pwd-vergessen-betreff', DefaultSettings::EMAIL_VORLAGE_PWD_VERGESSEN_BETREFF, false, 'no' );
add_option( 'muv-kk-email-vorlage-pwd-vergessen-typ', DefaultSettings::EMAIL_VORLAGE_TYP, false, 'no' );
add_option( 'muv-kk-email-vorlage-pwd-vergessen-text', DefaultSettings::EMAIL_VORLAGE_PWD_VERGESSEN_TEXT, false, 'no' );
add_option( 'muv-kk-email-vorlage-pwd-vergessen-html', DefaultSettings::EMAIL_VORLAGE_PWD_VERGESSEN_HTML, false, 'no' );
