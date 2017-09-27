<?php
  //AMP Session stitcher
  retrieve_gtm_json();

  function generate_ga_client_id() {
    return rand(100000000,999999999) . '.' . time();
  }


  function get_cookie_expire_date() {
      return date('D, j F Y H:i:s', time() + 60*60*24*365*2);
  }

  //function retrieve_gtm_json( $data ) {
  function retrieve_gtm_json() {

      $domain = explode(':', $_SERVER['HTTP_HOST']);
      $domain = $domain[0];
      $domainName = str_replace('www.', '', $domain);
      // Get the number of parts in the domain name
      $domainLength = count(explode('.', $domainName));
  
      $cid = $_COOKIE['_ga'];
      if (!isset($cid)) {
          $cid = "GA1.{$domainLength}." . generate_ga_client_id();
      }
   
      $cidNumber = preg_replace('/^GA.\.[^.]+\./','',$cid);
    
      // Get all HTTP request parameters
      $query = $_SERVER['QUERY_STRING'];
      

      //Just load the config JSON from local file
      //$container = file_get_contents("https://www.googletagmanager.com/amp.json?{$query}");

      $config = file_get_contents("amp-analytics-config.json");
      
      // Replace the &cid parameter value with ${clientId}
      //$container = preg_replace('/(&cid=)[^&]+/','${1}${clientId}', $container);
    
      // Add the clientId to the "vars" object in the container JSON.
      $config = json_decode($config);
      $config->vars->clientId = $cidNumber;
    
      // Build a new HTTP response from the modified configuration file.
      
      
      // Add the required headers (Set-Cookie, most importantly) to the Request
      //$response->header( 'Set-Cookie', "_ga={$cid}; Path=/; Expires=" . getcookieExpirationDate() . " GMT; Domain={$domainName};");
      header('Content-type: application/json');
      header('Set-Cookie: '. "_ga={$cid}; Path=/; Expires=" . get_cookie_expire_date() . " GMT; Domain={$domainName};");

      //$response->header( 'Access-Control-Allow-Origin', 'https://cdn.ampproject.org');
      header('Access-Control-Allow-Origin: https://theampbook-com.cdn.ampproject.org');
      
      // Remember to check the protocol and change to http if that's where your domain is
      //$response->header( 'AMP-Access-Control-Allow-Source-Origin', "https://{$domain}");
      header('AMP-Access-Control-Allow-Source-Origin: '."https://{$domain}");
      header('Access-Control-Allow-Credentials: true');
      //$response->header( 'Access-Control-Expose-Headers', 'AMP-Access-Control-Allow-Source-Origin');    
      header('Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin');

      // Return the HTTP response.
      //echo $response;
      echo json_encode($config);exit;
      //echo '{"vars":{"clientId":"'.$cidNumber.'"}}';
  }
