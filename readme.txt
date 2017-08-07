=== muv - Kundenkonto ===
Contributors: meinsundvogel
Plugin URI: https://wordpress.org/plugins/muv-kundenkonto
Stable tag: 1.0.1
Tags: kunden, konto, login, passwort, frontend, account, kundenkonto
Requires at least: 4.7
Tested up to: 4.7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Erweitert Ihre Website um die Möglichkeit, ein Kundenkonto anzubieten. Ihre Kunden können sich registrieren, anmelden, Ihr Passwort ändern, ...
 

== Description ==
Dieses Plugin erweitert Ihren Internet-Auftritt um die Möglichkeit, Ihren Kunden ein Kundenkonto anzubieten. Kunden können sich registrieren, anmelden, Ihr Passwort ändern, ...

= Erstellen einer beliebigen Seite des Kundenkontos =
Um eine "normale" Seite Ihres Internet-Auftritts in eine Seite des Kundenkontos um zu wandeln, fügen Sie einfach den Shortcode

[muv-kk-kunde-ist-angemeldet msg="Bitte melden Sie sich an um Ihre Daten zu sehen" show-login=1]

Hier kommt der Text, den nur der angemeldete Kunde sehen darf rein.

[/muv-kk-kunde-ist-angemeldet]

in Ihre Seite ein.

== Installation ==
1. Entpacken Sie die ZIP-Datei und laden Sie den Ordner muv-kundenkonto in das Plugin-Verzeichnis von WordPress hoch: wp-content/plugins/.
2. Loggen Sie sich dann als Admin unter WordPress ein. Unter dem Menüpunkt "Plugins" können Sie "muv KundenKonto" nun aktivieren. 

== Frequently Asked Questions ==

= Was muss ich tun, welche Optionen muss ich einstellen, damit das Plugin funktioniert? =
Wir empfehlen Ihnen, die E-Mail Einstellungen anzupassen.

== Changelog ==

= 1.0.1 =
Veröffentlicht am: 17.05.2017

* Fehlerbehebung:
    * ShortCode [muv-kk-aendere-pwd] in [muv-kk-aendere-pwt] umbenannt, um alles zu Vereinheitlichen.
    * Fehler beim Anzeigen der Kunden-Listen behoben.

* Verbesserungen:
    * Autofocus in den Templates geändert.
    * Die Bestätigungs-Links zum Bestätigen der Funktionen Passwort ändern und E-Mail ändern funktionieren nun auch, wenn der Kunde nicht angemeldet ist oder wenn ein anderer Kunde angemeldet ist.
    * Die Bestätigungs-Links zum Bestätigen der Funktionen Konto aktivieren und Passwort ändern funktionieren nun auch, wenn ein anderer Kunde angemeldet ist.
    * Brut-Force Angriffe wurden deutlich erschwert

= 1.0.0 =
Veröffentlicht am: 15.05.2017

* Verbesserungen:
    * WordPress - Quelltextformatierung angewendet.
    * Ab sofort ist ein "Pseudo-Login" möglich. Dies bedeutet: Wenn sich der Kunde bei der letzten Sitzung angemeldet hat, so wird er auch dieses mal wieder als angemeldet angezeigt (ohne es zu sein). So kann er z.B. begrüßt werden. Inhalte, für die ein richtiger, aktiver Login notwendig ist, sieht er dadurch aber nicht!
    * POT-Datei für Übersetzungen erstellt.

* Erweiterungen:
    * Neuer Shortcode [muv-kk-kunde-vorname] zum Anzeigen des Vornamens des (pseudo) angemeldeten Kunden.
    * Neuer Shortcode [muv-kk-kunde-nachname] zum Anzeigen des Nachnamens des (pseudo) angemeldeten Kunden.
    * Neuer Shortcode [muv-kk-aendere-pwd] zum Ändern des PWD des AKTIV angemeldeten Kunden.
    * Neuer Shortcode [muv-kk-aendere-email] zum Ändern der E-Mail Adresse des AKTIV angemeldeten Kunden.
    * Neuer Shortcode [muv-kk-loesche-zugang] zum Löschen des Zugangs des AKTIV angemeldeten Kunden.

* Fehlerbehebung: 
    * Das Deaktivieren der Einstellung "Konten löschen" hat nicht immer funktioniert.
    * Der Cronjob zum Löschen der veralteten Konten löscht nun alle veralteten Konten.

= 0.5.2 =

Veröffentlicht am: 24.04.2017

* Verbesserungen: 
    * Design an andere Plugins angepasst.
    * Erklärungen überarbeitet, damit sie sprechender werden
    * Der alte Media Uploader wurde durch den aktuellen Media-Selektor ersetzt

* Fehlerbehebung: 
    * Bei der Registrierung wird das Feld E-Mail darauf hin überprüft, ob es sich wirklich um eine E-Mail handelt.
    * PHP Notice bei Passwort ändern entfernt.
    * Fehler beim Ändern des Passworts behoben (falsche URL des Formulars).
    * Bei Multipart E-Mails wird das Header-Bild nun über die Wordpress eigenen Methoden eingelesen. Dadurch wird die Kompatibilität mit verschiedenen Server-Konfigurationen verbessert.

= 0.5.1 =

Veröffentlicht am: 16.03.2017

* Verbesserungen: 
    * Die Settings wurden neu Strukturiert
    * Kleine Verbesserungen am Design der Admin-Seiten

* Erweiterungen:
    * Es gibt einen API-Key zum Zugriff auf die API

= 0.5.0 =

Veröffentlicht am: 14.03.2017

* Erstes Release

== Support ==
Bei Fragen senden Sie uns bitte eine Mail an: [support@muv.com](mailto:support@muv.com) wir antworten schnellstmöglich!

