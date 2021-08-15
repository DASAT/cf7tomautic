<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class mauticipn {
    /*********************************************************/
    public function mautic_remove_user_from_segment($user_data, $segment_data){
        /*********************************************************/
        $curl = curl_init();
        // Set some options - we are passing in a user agent too here
        $url = "https://".$this->un.":".$this->pw."@".$this->url."/api/segments/$segment_data/contact/".$user_data['contact']['id']."/remove";
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Mautic Connector',
            CURLOPT_POST => 1,
            
        ));
        $res = curl_exec($curl);
        curl_close($curl);
        $j_result = json_decode($res, true);
        return($j_result);
    }
    
    /*********************************************************/
    public function mautic_add_segment_to_user($user_data, $segment_data){
        /*********************************************************/
        $curl = curl_init();
        // Set some options - we are passing in a user agent too here
        $url = "https://".$this->un.":".$this->pw."@".$this->url."/api/segments/$segment_data/contact/".$user_data['contact']['id']."/add";
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Mautic Connector',
            CURLOPT_POST => 1,
            
        ));
        $res = curl_exec($curl);
        curl_close($curl);
        $j_result = json_decode($res, true);
        return($j_result);
    }
    /*********************************************************/
    public function mautic_check_if_segment_exists($YourArray){
        /*********************************************************/
        $curl = curl_init();
        
        $temp_parameter =  $YourArray['segment'];
        
        $url = "https://".$this->un.":".$this->pw."@".$this->url."/api/segments?search=name:".$temp_parameter;
        //$url = "https://".$newslettercfg['un'].":".$newslettercfg['pw']."@".$newslettercfg['url']."/api/segments?search=alias:".$temp_parameter;
        
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Mautic Connector',
            CURLOPT_TIMEOUT => 3
        ));
        $res = curl_exec($curl);
        curl_close($curl);
        $j_result = json_decode($res, true);
        if($j_result["total"] <> "1"){
            $return_result =  $this->mautic_add_segment($YourArray);
            
            
            $my_lists_id = $return_result["list"]["id"];
        }else{
            $my_lists_id = key($j_result['lists']);
        }
        return($my_lists_id);
        
        
    }
    /*********************************************************/
    public function mautic_add_segment($YourArray){
    /*********************************************************/
        $curl = curl_init();
        $temp_parameter = preg_replace("/[^a-zA-Z0-9\-]/", "", $YourArray['segment']);

        if(!isset($YourArray['segment'])){
          return false;
        }
        $temp_alias =  $YourArray['segment'];
  
        // Set some options - we are passing in a user agent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://".$this->un.":".$this->pw."@".$this->url."/api/segments/new",
            CURLOPT_USERAGENT => 'Mautic Connector',
            CURLOPT_POST => 1,
            // posting the payload
            CURLOPT_POSTFIELDS => array(
                // 'email' => $email,
                // 'verification_status' => $verification_status
                'name' => $temp_alias,
                'alias' =>  $temp_alias,
                'description' =>  $YourArray['segment'],
                'isPublished' =>  1,
                'isGlobal' =>  1
                
                
            )
        ));
        $res = curl_exec($curl);
        curl_close($curl);
        $j_result = json_decode($res, true);
        return($j_result);
    }
    /*********************************************************/
    public function mautic_get_user_by_id($YourID){
        /*********************************************************/
        $curl = curl_init();
        // Set some options - we are passing in a user agent too here
        $url = "https://".$this->un.":".$this->pw."@".$this->url."/api/contacts/".$YourID;
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Mautic Connector',
            CURLOPT_TIMEOUT => 3
        ));
        $res = curl_exec($curl);
        curl_close($curl);
        $j_result = json_decode($res, true);
        
        if(isset($j_result["errors"])){
            return("error");
        }else{
            return($j_result);
        }
    }
    
    
    
    /*********************************************************/
    public function mautic_check_if_email_exists($YourArray){
        /*********************************************************/
        $curl = curl_init();
        // Set some options - we are passing in a user agent too here
        $url = "https://".$this->un.":".$this->pw."@".$this->url."/api/contacts?search=email:".$YourArray['email']."&";
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Mautic Connector',
            CURLOPT_TIMEOUT => 3
        ));
        $res = curl_exec($curl);
        curl_close($curl);
        $j_result = json_decode($res, true);
        
        if($j_result["total"] <> "1"){
            $return_result =  $this->mautic_add_user($YourArray);
            $my_contact_id = $return_result["contact"]["id"];
        }else{
            $my_contact_id = key($j_result['contacts']);
        }
        unset($return_result);
        $return_result  = $this->mautic_get_user_by_id($my_contact_id);
        return($return_result);
    }
    /*********************************************************/
    
    
    /*********************************************************/
    public function mautic_check_if_user_is_in_segment($YourArray){
        /*********************************************************/
        $curl = curl_init();
        // Set some options - we are passing in a user agent too here
        $temp=urlencode("email:".$YourArray['email']." segment:".$YourArray['segment']);
        $url = "https://".$this->un.":".$this->pw."@".$this->url. "/api/contacts?search=$temp&";
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Mautic Connector',
            CURLOPT_TIMEOUT => 3
        ));
        $res = curl_exec($curl);
        curl_close($curl);
        $j_result = json_decode($res, true);
        
        return($j_result);
    }
    
    /*********************************************************/
    public function mautic_add_user($YourArray){
        /*********************************************************/
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://".$this->un.":".$this->pw."@".$this->url."/api/contacts/new",
            CURLOPT_USERAGENT => 'Mautic Connector',
            CURLOPT_POST => 1,
            // posting the payload
            CURLOPT_POSTFIELDS => $YourArray

        ));
        $res = curl_exec($curl);
        curl_close($curl);
        $j_result = json_decode($res, true);
        return($j_result);
    }
    /*********************************************************/
    public function mautic_add_user_and_segment($YourArray){
        /*********************************************************/
        $user_data = $this->mautic_check_if_email_exists($YourArray);
        $segement_data = $this->mautic_check_if_segment_exists($YourArray);
        $this->mautic_add_segment_to_user($user_data,$segement_data);
    }
    
    
    /*********************************************************/
    public function mautic_remove_segment_from_user($YourArray){
        /*********************************************************/
        $user_data = $this->mautic_check_if_email_exists($YourArray);
        $segement_data = $this->mautic_check_if_segment_exists($YourArray);
        $this->mautic_remove_user_from_segment($user_data,$segement_data);
    }
    
    
    /*********************************************************/
    public function mautic_start($YourArray){
        /*********************************************************/
        $this->un = $YourArray['un'];
        $this->pw = $YourArray['pw'];
        $this->url = $YourArray['url'];
        
    }
    /*********************************************************/
} //end of class
/*********************************************************/
$mauticipn = new mauticipn();
$mauticipn->mautic_start($mauticcfg);



?>