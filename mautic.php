<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 *
 * Plugin Name:       CF7 to Mautic
 * Description:       The plugin sends CF7 submissions to Mautic.
 * Version:           0.2
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
    Dieses einfache Plugin erlaubt es Daten, die über ein CF7 Formular geschickt werden, in Mautic einzubinden. Es werden ein Benutzer und ein Segment angelegt, sollten der Benutzer oder das Segment in Mautic noch nicht vorhanden sein, und fügt zum Benutzer das gewünschte Segment hinzu, falls dieses mit dem Benutzer noch nicht verknüpft sein sollte.<p>
            
    <h2>Anbindung an Mautic</h2>
    <ul>
    <li>Erstelle einen Benutzer in Mautic mit der Rolle MailsenderIPN</li>
    <li>Trage Deine Mautic-URL, den Benutzernamen und das Passwort in die wp-config.php bei WordPress ein. (Wird Deine Datenbank gehacked, sind die Mautic-Zugangsdaten nicht erkennbar)<br>
    define(\'MAUTICURL\', \'mauticurl\'); <strong>KEIN https am Anfang! Should NOT start with https!</strong><br>
    define(\'MAUTICUN\', \'mauticIPNUser\');<br>
    define(\'MAUTICPW\', \'mauticIPNPassword\');<br>
    </li>
        
    <li>Erstelle wie gewohnt Dein Formular mit CF7 und verwende die Mautic-Feldnamen<br>
    Your email (required) [text* your-email] <strong>oder</strong> Your email (required) [text* email]<br>
    
    Your firstname (required) [text* your-firstname] <strong>oder</strong> Your firstname (required) [text* firstname]<br>
    Your lasttname (required) [text* your-lastname] <strong>oder</strong> Your lasttname (required) [text* lastname]<br>
    </li>
    <li>
    Füge den Namen des Segments ein, welches mit dem Formular verbunden werden soll<br>
    [hidden segment"whateversegment"]
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