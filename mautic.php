<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 *
 * Plugin Name:       CF7 to Mautic
 * Description:       The plugin sends CF7 submissions to Mautic.
 * Version:           0.4
 * Author:            DASAT UG
 * Author URI:        http://dasat.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cf72mautic
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with slmsmetals slmsmetals. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
 */



////////////////////////////////////////////////////////////
define( 'MAUTIC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
function mautic_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
////////////////////////////////////////////////////////////
    
    if ( MAUTIC_PLUGIN_BASENAME == $plugin_file )
        $plugin_meta[] = 'Author: <a href="https://dasat.com" target="_blank"">Ulrich Eckardt</a> | <a href="mailto:support@dasat.com">Support</a> | <a href="/wp-admin/options-general.php?page=cf72mautic">Documentation</a>';
        return $plugin_meta;
}
add_filter( 'plugin_row_meta', 'mautic_row_meta', 10, 4 );
////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////
add_action( 'admin_menu', 'mautic_plugin_menu' );

function mautic_plugin_menu() {
    add_options_page( 'CF7 to Mautic', 'CF7 to Mautic', 'manage_options', 'cf72mautic', 'mautic_plugin_options' );
}
////////////////////////////////////////////////////////////

function mautic_plugin_options($dummy) {
 $mautic_content = '
    <div style="font-size:1.2em;line-height:2em">
    <h1>CF7 to Mautic</h1><br>
    Dieses einfache Plugin erlaubt es Daten, die über ein CF7 Formular geschickt werden, in Mautic einzubinden. Es werden ein Benutzer und ein Segment angelegt, sollten der Benutzer oder das Segment in Mautic noch nicht vorhanden sein, und fügt zum Benutzer das gewünschte Segment hinzu, falls dieses mit dem Benutzer noch nicht verknüpft sein sollte.<br>
Das Plugin übermittelt auch Daten an ein Mautic Formular.
            
    <ul>
    <li style="border:1px solid blue;padding:10px;">

    <h2>CF7 ANBINDUNG AN MAUTIC / CONNECT CF7 WITH MAUTIC (required)</h2>


Erstelle einen Benutzer in Mautic mit der Rolle MailsenderIPN.<br>
    Trage Deine Mautic-URL, den Benutzernamen und das Passwort in die wp-config.php bei WordPress ein. (Wird Deine Datenbank gehacked, sind die Mautic-Zugangsdaten nicht erkennbar)<br>
    <xmp style="margin:0px;">define(\'MAUTICURL\', \'mauticurl\'); // KEIN https am Anfang! Should NOT start with https!
define(\'MAUTICUN\', \'mauticIPNUser\');
define(\'MAUTICPW\', \'mauticIPNPassword\');</xmp>
    </li>
        
   
        <li style="border:1px solid blue;padding:10px;">
    <h2>MAUTIC SEGMENT IN CF7 (required)</h2>

    Füge den Namen des <b>Segments</b> ein, welches mit dem Formular verbunden werden soll<br>
    <xmp style="margin:0px;">[hidden segment"whateversegment"]</xmp>
    </li>
    <li style="border:1px solid blue;padding:10px;">
    <h2>MAUTIC FORM SUBMISSION (optional)</h2>

    Füge die ID des Mautic-Formulars ein, welche die Daten erhalten soll Bsp. [hidden formId"16"]<br>
    Wenn die Daten in ein Mautic-Formular geschickt werden sollen, müssen die Feldnamen im CF7-Formular identisch mit den Feldnamen sein, die Du im Mautic-Forumular verwendet hast.<br>
    Falls Du Dir unsicher bist, welche Namen die Felder haben, schaue Dir unter "Manual Copy" bei Mautic die Feldnamen an.<br>
<b>Mautic Form & CF7</b><br>
<xmp style="margin:0px;"><input id="mauticform_input_demo_email" name="mauticform[email]" value="" class="mauticform-input" type="email">
<input id="mauticform_input_demo_lastname" name="mauticform[lastname]" value="" class="mauticform-input" type="text">
<textarea id="mauticform_input_demo_f_message" name="mauticform[f_message]" class="mauticform-textarea"></textarea>
</xmp>
<hr>
<b>CF7</b><br>
<xmp style="margin:0px;"><label> Email (required)[email* your-email] </label>
<label> Lastname [text your-lastname] </label>
<label> Your Message? [textarea your-f_message] </label>
[hidden formId"16"]
[hidden segment"whateversegment"]

</xmp>
    </li>
    </ul>
        
    <hr>
    Viel Erfolg
        
    </div>';
        
echo $mautic_content;

}

////////////////////////////////////////////////////////////
add_action('wpcf7_mail_sent','mautic_get_submitted_data');
function mautic_get_submitted_data($contact_form){    
////////////////////////////////////////////////////////////
   $submission = WPCF7_Submission::get_instance();
   if($submission){
      $posted_data = $submission->get_posted_data();
      if(isset($posted_data['segment'])){
         $send_array['segment']  = $posted_data['segment'];
         if(is_array($posted_data)){
             foreach($posted_data as $key => $value){
                 if(strpos($key, "your-") !== false){
                      $tempstring = str_replace("your-", "", $key);
                      $send_array[$tempstring] = $value;
                  }else{
                      $send_array[$key] = $value;
                  }
             }
        }
        $mauticcfg['url'] = MAUTICURL;
        $mauticcfg['un']  = MAUTICUN;
        $mauticcfg['pw']  = MAUTICPW;
        require_once plugin_dir_path( __FILE__ ) . 'curl.php';
        require_once plugin_dir_path( __FILE__ ) . 'mauticipn.php';
        $mauticipn->mautic_add_user_and_segment($send_array);
      }
   }
}

?>