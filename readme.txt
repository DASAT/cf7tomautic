=== CF72Mautic ===
Contributors: Ulrich Eckardt
Tags: Mautic, Mautic IPN, Contact Form 
Requires at least: 5.8
Requires PHP: 7.3.1
Tested up to: 7.3.1
Stable tag: 0.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sends submitted data from a Contact Form 7 Formular to Mautic

== Description ==

Sends submitted data from a Contact Form 7 Formular to Mautic

== Installation ==

Upload the Zip-File of this plugin if you want to install it.

Dieses einfache Plugin erlaubt es Daten, die über ein CF7 Formular geschickt werden, in Mautic einzubinden. Es werden ein Benutzer und ein Segment angelegt, sollten der Benutzer oder das Segment in Mautic noch nicht vorhanden sein, und fügt zum Benutzer das gewünschte Segment hinzu, falls dieses mit dem Benutzer noch nicht verknüpft sein sollte.
Anbindung an Mautic
Erstelle einen Benutzer in Mautic mit der Rolle MailsenderIPN
Trage Deine Mautic-URL, den Benutzernamen und das Passwort in die wp-config.php bei WordPress ein. (Wird Deine Datenbank gehacked, sind die Mautic-Zugangsdaten nicht erkennbar)
define('MAUTICURL', 'mauticurl'); KEIN https am Anfang! Should NOT start with https!
define('MAUTICUN', 'mauticIPNUser');
define('MAUTICPW', 'mauticIPNPassword');
Erstelle wie gewohnt Dein Formular mit CF7 und verwende die Mautic-Feldnamen
Your email (required) [text* your-email] oder Your email (required) [text* email]
Your firstname (required) [text* your-firstname] oder Your firstname (required) [text* firstname]
Your lasttname (required) [text* your-lastname] oder Your lasttname (required) [text* lastname]
Füge den Namen des Segments ein, welches mit dem Formular verbunden werden soll
[hidden segment"whateversegment"]