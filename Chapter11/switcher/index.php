<?php
require_once dirname(__FILE__).'/da/Api/Client.php';

// Our device oriented website templates
define('DESKTOP_TEMPLATE', 'desktop.html');
define('AMP_TEMPLATE', 'amp.html');
 
/* Retrieve device data from the AMP */
$properties = array();
try {
  $result = DeviceAtlasCloudClient::getDeviceData();
  if (isset($result[DeviceAtlasCloudClient::KEY_PROPERTIES])) {
    $properties = $result[DeviceAtlasCloudClient::KEY_PROPERTIES];
  } 
  else {
    throw new Exception('No properties returned');
  }
} catch (Exception $ex) {
  /* If it didn't work use the desktop page by default */
  include(DESKTOP_TEMPLATE);
}


/* It's a desktop browser or mobile device masquerading as desktop browser */
if ((isset($properties['isBrowser']) && $properties['isBrowser']) ||
    (isset($properties['isMasqueradingAsDesktop']) && $properties['isMasqueradingAsDesktop'])) {

  include(DESKTOP_TEMPLATE);
  exit;
}
 
if (isset($properties['mobileDevice']) && $properties['mobileDevice']) {
  include(AMP_TEMPLATE);
  exit;
}

/* If we got here just show DESKTOP by default */
include(DESKTOP_TEMPLATE);
