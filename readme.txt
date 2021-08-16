====== CF72Mautic ======
Contributors: Ulrich Eckardt
Tags: Mautic, Mautic IPN, Contact Form 
Requires at least: 5.8
Requires PHP: 7.3.1
Tested up to: 7.3.1
Stable tag: 0.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sends submitted data from a Contact Form 7 Formular to Mautic

==== Description ====

Sends submitted data from a Contact Form 7 Formular to Mautic

==== Installation ====

Upload the Zip-File of this plugin if you want to install it.

=== CF7 to Mautic === 

Dieses einfache Plugin erlaubt es Daten, die über ein CF7 Formular geschickt werden, in Mautic einzubinden. Es werden ein Benutzer und ein Segment angelegt, sollten der Benutzer oder das Segment in Mautic noch nicht vorhanden sein, und fügt zum Benutzer das gewünschte Segment hinzu, falls dieses mit dem Benutzer noch nicht verknüpft sein sollte.
Das Plugin übermittelt auch Daten an ein Mautic Formular.

=== CF7 ANBINDUNG AN MAUTIC / CONNECT CF7 WITH MAUTIC (required) === 
Erstelle einen Benutzer in Mautic mit der Rolle MailsenderIPN.
Trage Deine Mautic-URL, den Benutzernamen und das Passwort in die wp-config.php bei WordPress ein. (Wird Deine Datenbank gehacked, sind die Mautic-Zugangsdaten nicht erkennbar)
define('MAUTICURL', 'mauticurl'); // KEIN https am Anfang! Should NOT start with https!
define('MAUTICUN', 'mauticIPNUser');
define('MAUTICPW', 'mauticIPNPassword');


=== MAUTIC SEGMENT IN CF7 (required) === 
Füge den Namen des Segments ein, welches mit dem Formular verbunden werden soll
[hidden segment"whateversegment"]

=== MAUTIC FORM SUBMISSION (optional) === 
Füge die ID des Mautic-Formulars ein, welche die Daten erhalten soll Bsp. [hidden formId"16"]
Wenn die Daten in ein Mautic-Formular geschickt werden sollen, müssen die Feldnamen im CF7-Formular identisch mit den Feldnamen sein, die Du im Mautic-Forumular verwendet hast.
Falls Du Dir unsicher bist, welche Namen die Felder haben, schaue Dir unter "Manual Copy" bei Mautic die Feldnamen an.

== Mautic Form & CF7 ==
<input id="mauticform_input_demo_email" name="mauticform[email]" value="" class="mauticform-input" type="email">
<input id="mauticform_input_demo_lastname" name="mauticform[lastname]" value="" class="mauticform-input" type="text">
<textarea id="mauticform_input_demo_f_message" name="mauticform[f_message]" class="mauticform-textarea"></textarea>

== CF7 ==
<label> Email (required)[email* your-email] </label>
<label> Lastname [text your-lastname] </label>
<label> Your Message? [textarea your-f_message] </label>
[hidden formId"16"]
[hidden segment"whateversegment"]

Viel Erfolg
