<?php
  class twitterAPI {
    var $oauth_access_token;
    var $oauth_access_token_secret;
    var $consumer_key;
    var $consumer_secret;
  //
  // intialized variables
  //
  function init() {
    $this-> oauth_access_token = "2449687518-vnBcm6X3ZuLqsLxYBDRMFPTrp45tJgje3wLjY97";
    $this->oauth_access_token_secret = "iTY6Kw7m7Nf9LhvnZXmuaxmsr8K281TXXV1zBB8EgIuEP";
    $this->consumer_key = "lyTIyhlCDNZI1T8TCpIMt0pdP";
    $this->consumer_secret = "HAGmz8xdAJQ9AuSi27VDIOCss2xgeUVKENeINcLzh5r7IKWf5I";
  }
  //
  // build URL 
  //
  function buildBaseString($baseURI, $method, $params) {
    $r = array();
    ksort($params);
      foreach($params as $key=>$value) {
      $r[] = "$key=" . rawurlencode($value);
      }
    return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
  }
  //
  //build authorized header
  //
  function buildAuthorizationHeader($oauth) {
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key=>$value)
    $values[] = "$key=\"" . rawurlencode($value) . "\"";
    $r .= implode(', ', $values);
    return $r;
  }
  //
  // retriev tweets 
  //
  function gettweets() {
    $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
    $oauth = array( 'oauth_consumer_key' => $this->consumer_key,
                    'oauth_nonce' => time(),
                    'oauth_signature_method' => 'HMAC-SHA1',
                    'oauth_token' => $this->oauth_access_token,
                    'oauth_timestamp' => time(),
                    'oauth_version' => '1.0');

    $base_info = $this->buildBaseString($url, 'GET', $oauth);
    $composite_key = rawurlencode($this->consumer_secret) . '&' . rawurlencode($this->oauth_access_token_secret);
    $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
    $oauth['oauth_signature'] = $oauth_signature;
    // Make requests
    $header = array($this->buildAuthorizationHeader($oauth), 'Expect:');
    $options = array( CURLOPT_HTTPHEADER => $header,
                     // CURLOPT_POSTFIELDS => $postfields,
                      CURLOPT_HEADER => false,
                      CURLOPT_URL => $url,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_SSL_VERIFYPEER => false);

    $feed = curl_init();
    curl_setopt_array($feed, $options);
    $json = curl_exec($feed);
    curl_close($feed);
    $twitter_data = json_decode($json,true);
  // print_r ($twitter_data);
    print_r ($twitter_data[0]['id']);
    print_r ($twitter_data[0]['text']);
    }
  }

$obj=new twitterAPI();
$obj->init();
$obj->gettweets();

?>
