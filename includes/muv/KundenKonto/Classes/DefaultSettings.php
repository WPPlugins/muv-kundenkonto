<?php

namespace muv\KundenKonto\Classes;


defined( 'ABSPATH' ) OR exit;


class DefaultSettings {
				
	const ALLGEMEIN_ZUGANG_LOESCHEN = array( 'check' => true, 'tage' => 7 );
		const ERLAUBE_PSEUDO_LOGIN = true;
		const ALLGEMEIN_ABMELDUNG = array( 'idle' => 30, 'gesamt' => 10 );

		public static function ALLGEMEIN_ANMELDUNG_DOMAIN() {
		return parse_url( get_home_url(), PHP_URL_HOST );
	}

				
	public static function EMAIL_VON_NAME() {
		return get_bloginfo( 'name' );
	}

		public static function EMAIL_VON_MAIL() {
		return get_bloginfo( 'admin_email' );
	}

		const EMAIL_VORLAGE_LOGO = '';

		public static function EMAIL_VORLAGE_FOOTER() {
		return get_bloginfo( 'name' ) . ' powered by muv KundenKonto';
	}

		const EMAIL_VORLAGE_COLOR = '#127BCA';
	const EMAIL_VORLAGE_COLOR_TEXT = '#555555';
	const EMAIL_VORLAGE_BGCOLOR_BODY = '#F4F4F4';
	const EMAIL_VORLAGE_BGCOLOR_CONTENT = '#FFFFFF';
		const EMAIL_VORLAGE_TYP = 2; 		const EMAIL_VORLAGE_PWD_VERGESSEN_BETREFF = 'Neues Passwort für Ihr Kunden-Konto';
	const EMAIL_VORLAGE_PWD_VERGESSEN_TEXT = <<<'EOT'
Hallo ##NAME##!

Sie haben Ihr Passwort vergessen? Das ist gar kein Problem!

Klicken Sie einfach auf den folgenden Link und schon können Sie ein Neues vergeben.

##LINK##


Liebe Grüße!
EOT;
	const EMAIL_VORLAGE_PWD_VERGESSEN_HTML = <<<'EOT'
<h1>Hallo##NAME##!</h1>
Sie haben Ihr Passwort vergessen? Das ist gar kein Problem!<br><br>
<strong>Klicken Sie einfach auf den folgenden Link und schon können Sie ein Neues vergeben.</strong>
<br /> <br />
<a href="##LINK##">Neues Passwort vergeben</a>
<br /> <br />
Liebe Grüße!
EOT;
		const EMAIL_VORLAGE_KONTO_AKTIVIEREN_BETREFF = 'Bitte bestätigen Sie Ihre E-Mail Adresse';
	const EMAIL_VORLAGE_KONTO_AKTIVIEREN_TEXT = <<<'EOT'
Hallo ##NAME##, Sie haben es fast geschafft!

Bitte bestätigen Sie als nächstes Ihre E-Mail Adresse, damit wir Ihren Zugang aktivieren können.

Klicken Sie dazu einfach auf folgenden Link:

##LINK##


Liebe Grüße!


Sollten Sie diese E-Mail irrtümlich erhalten haben, brauchen Sie nichts weiter zu tun. 
Sie erhalten keine weiteren Mails mehr von uns. 

Bei Missbrauch wenden Sie sich bitte an ##EMAIL-TO##.
EOT;
	const EMAIL_VORLAGE_KONTO_AKTIVIEREN_HTML = <<<'EOT'
<h1>Hallo ##NAME##, Sie haben es fast geschafft!</h1>
Bitte bestätigen Sie als nächstes Ihre E-Mail Adresse, damit wir Ihren Zugang aktivieren können. <br />
<br />
<strong>Klicken Sie dazu einfach auf den folgenden Link</strong>
<br />
<br />
<a href="##LINK##">E-Mail Adresse bestätigen und Zugang aktivieren</a>
<br />
<br />
Liebe Grüße
<br />
<br />
<span style="font-size:11px">Sollten Sie diese E-Mail irrtümlich erhalten haben, brauchen Sie nichts weiter zu tun. 
Sie erhalten keine weiteren Mails mehr von uns. Bei Missbrauch wenden Sie sich bitte an 
<a href="mailto:##EMAIL-TO##">##EMAIL-TO##</a>.</span>
EOT;

		const EMAIL_VORLAGE_PWD_GEAENDERT_BETREFF = 'Ihr Passwort wurde geändert';
	const EMAIL_VORLAGE_PWD_GEAENDERT_TEXT = <<<'EOT'
Hallo ##NAME##!

Sie erhalten diese E-Mail aus Sicherheitsgründen, um Ihnen mitzuteilen, dass Ihr Passwort soeben in unserem System geändert wurde.

Sollte diese Änderung nicht von Ihnen veranlasst worden sein, setzen Sie sich bitte schnellstmöglich mit uns in Verbindung!

Liebe Grüße!
EOT;
	const EMAIL_VORLAGE_PWD_GEAENDERT_HTML = <<<'EOT'
<h1>Hallo##NAME##!</h1>
Sie erhalten diese E-Mail aus Sicherheitsgründen, um Ihnen mitzuteilen, dass Ihr Passwort soeben in unserem System geändert wurde.<br />
<br />
<strong>Sollte diese Änderung nicht von Ihnen veranlasst worden sein, setzen Sie sich bitte schnellstmöglich mit uns in Verbindung!</strong>
<br /> <br />
Liebe Grüße!
EOT;

		const EMAIL_VORLAGE_EMAIL_AKTIVIEREN_BETREFF = 'Bitte bestätigen Sie Ihre E-Mail Adresse';
	const EMAIL_VORLAGE_EMAIL_AKTIVIEREN_TEXT = <<<'EOT'
Hallo ##NAME##, Sie haben es fast geschafft!

Bitte bestätigen Sie als nächstes Ihre neue E-Mail Adresse (##EMAIL-NEU##), damit wir sie in unserem System ändern können.

Klicken Sie dazu einfach auf folgenden Link:

##LINK##

Bitte beachten Sie: Bis zur Bestätigung behält Ihre alte E-Mail Adresse weiterhin ihre Gültigkeit.


Liebe Grüße!


Sollten Sie diese E-Mail irrtümlich erhalten haben, brauchen Sie nichts weiter zu tun. 
Sie erhalten keine weiteren Mails mehr von uns. 

Bei Missbrauch wenden Sie sich bitte an ##EMAIL-TO##.
EOT;
	const EMAIL_VORLAGE_EMAIL_AKTIVIEREN_HTML = <<<'EOT'
<h1>Hallo ##NAME##, Sie haben es fast geschafft!</h1>
Bitte bestätigen Sie als nächstes Ihre neue E-Mail Adresse (##EMAIL-NEU##), damit wir sie in unserem System ändern können.<br />
<br />
<strong>Klicken Sie dazu einfach auf den folgenden Link</strong>
<br />
<br />
<a href="##LINK##">E-Mail Adresse ##EMAIL-NEU## bestätigen</a>
<br />
Bitte beachten Sie: Bis zur Bestätigung behält Ihre alte E-Mail Adresse weiterhin ihre Gültigkeit.<br>
<br />
<br />
Liebe Grüße
<br />
<br />
<span style="font-size:11px">Sollten Sie diese E-Mail irrtümlich erhalten haben, brauchen Sie nichts weiter zu tun. 
Sie erhalten keine weiteren Mails mehr von uns. Bei Missbrauch wenden Sie sich bitte an 
<a href="mailto:##EMAIL-TO##">##EMAIL-TO##</a>.</span>
EOT;
		const EMAIL_VORLAGE_EMAIL_GEAENDERT_BETREFF = 'Ihre E-Mail Adresse wurde geändert';
	const EMAIL_VORLAGE_EMAIL_GEAENDERT_TEXT = <<<'EOT'
Hallo ##NAME##!

Sie erhalten diese E-Mail aus Sicherheitsgründen, um Ihnen mitzuteilen, dass Ihre E-Mail Adresse soeben in unserem System geändert wurde.

Ihre neue E-Mail Adresse lautet: ##EMAIL##

Sollte diese Änderung nicht von Ihnen veranlasst worden sein, setzen Sie sich bitte schnellstmöglich mit uns in Verbindung!

Liebe Grüße!
EOT;
	const EMAIL_VORLAGE_EMAIL_GEAENDERT_HTML = <<<'EOT'
<h1>Hallo##NAME##!</h1>
Sie erhalten diese E-Mail aus Sicherheitsgründen, um Ihnen mitzuteilen, dass Ihre E-Mail Adresse soeben in unserem System geändert wurde.<br />
<br />
Ihre neue E-Mail Adresse lautet: ##EMAIL##<br />
<br />
<strong>Sollte diese Änderung nicht von Ihnen veranlasst worden sein, setzen Sie sich bitte schnellstmöglich mit uns in Verbindung!</strong>
<br /> <br />
Liebe Grüße!
EOT;

		const EMAIL_VORLAGE_KONTO_LOESCHEN_BETREFF = 'Bitte bestätigen Sie die Löschung Ihres Kontos';
	const EMAIL_VORLAGE_KONTO_LOESCHEN_TEXT = <<<'EOT'
Hallo ##NAME##, schade, dass Sie uns verlassen möchten!

Um Missbrauch zu verhindern bitten wir Sie, uns die Löschung Ihres Kontos noch einmal zu Bestätigen.

Klicken Sie dazu einfach auf folgenden Link:

##LINK##

Bitte beachten Sie: Mit Löschung Ihres Kontos werden auch alle darin enthaltenen Daten, Produkte, Lizenzen usw. unwiderbringlich gelöscht!


Liebe Grüße!


Sollte diese Löschung nicht von Ihnen veranlasst worden sein, setzen Sie sich bitte schnellstmöglich mit uns in Verbindung!
EOT;
	const EMAIL_VORLAGE_KONTO_LOESCHEN_HTML = <<<'EOT'
<h1>Hallo ##NAME##, schade, dass Sie uns verlassen möchten!</h1>
Um Missbrauch zu verhindern bitten wir Sie, uns die Löschung Ihres Kontos noch einmal zu Bestätigen.<br />
<br />
<strong>Klicken Sie dazu einfach auf den folgenden Link</strong>
<br />
<br />
<a href="##LINK##">Löschung des Kontos bestätigen</a>
<br />
Bitte beachten Sie: Mit Löschung Ihres Kontos werden auch alle darin enthaltenen Daten, Produkte, Lizenzen usw. unwiderbringlich gelöscht!<br>
<br />
<br />
Liebe Grüße
<br />
<br />
<span style="font-size:11px">Sollte diese Löschung nicht von Ihnen veranlasst worden sein, setzen Sie sich bitte schnellstmöglich mit uns in Verbindung!</span>
EOT;


}
