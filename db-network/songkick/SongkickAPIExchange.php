<?php

/**
 * SONGKICK-API-PHP : Simple PHP wrapper 
 * 
 * @package  SONGKICK-API-PHP
 * @author   Jacob Raccuia 
 * i removed all the comments cause they were about twitter. desolais je ne desolais pas
 *
 *
 */
class SongkickAPIExchange {

    private $api_key;
    public $url;

    public function __construct(array $settings) {
        if (!in_array('curl', get_loaded_extensions()))  {
            throw new Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');
        }
        
        if (!isset($settings['api_key'])){
            throw new Exception('api key is required!');
        }

        $this->api_key = $settings['api_key'];
    }
    
    public function buildURL($url) {
        if($url == '') {
            throw new Exception('songkick query URL required');
        }

        $this->url = $url . '?apikey=' . $this->api_key;
        return $this;
    }

    
    public function performRequest() {

        $options = array(
            CURLOPT_HEADER => false,
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            );


        $feed = curl_init();
        curl_setopt_array($feed, $options);
        $json = curl_exec($feed);

        if (($error = curl_error($feed)) !== '') {
            curl_close($feed);
            throw new \Exception($error);
        }

        curl_close($feed);
        return $json;
    }

}
