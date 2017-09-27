<?php
/**
 * Analytics Example using the DeviceAtlas CloudApi
 *
 * Scenario: let's say we have a log with the user-agents of users who visited our
 * website. We can iterate over the user-agents and get properties of each one
 * from DeviceAtlas and aggregate the traffic for the properties of our interest.
 *
 * In this example we will:
 * - open a sample user-agent list file
 * - iterate over the user-agents
 * - get properties of each user agent using DeviceAtlas
 * - aggregate few properties
 * - display the results
 *
 * @copyright Copyright (c) 2008-2014 by Afilias Technologies Limited (dotMobi). All rights reserved.
 * @author dotMobi
 */

// to see all errors only when in development environment
error_reporting(E_ALL);        
ini_set('display_errors', 1);


/* (1) Edit the DeviceAtlasCloud "Client.php" file and set your licence key:
       const LICENCE_KEY = 'YOUR-KEY'; */

/* (2) Include the CloudApi library */
require_once dirname(__FILE__).'/../../Api/Client.php';


// Place the path to your User Agent list here > > >
define('USERAGENT_FILE', 'ua.data');

// it is highly recommended to use the API in a try/catch block
try {

    /* (3) Aggregate property traffic */


    // the below variables will keep the count of each tracked property
    $trafficDesktopBrowsers = 0;
    $trafficMobileDevices   = array();
    $trafficByMobileVendor  = array();
    $trafficByOs            = array();


    // Open a list of user-agents which the analysis will be done on them
    // for large user-agent files using fopen() is preferred to save memory
    $uas = file(USERAGENT_FILE);
    foreach ($uas as $ua) {

        // get properties from the user-agent
        $result = DeviceAtlasCloudClient::getDeviceData($ua);
        if (isset($result[DeviceAtlasCloudClient::KEY_PROPERTIES])) {
            $properties = $result[DeviceAtlasCloudClient::KEY_PROPERTIES];
        } else {
            continue;
        }

        // if it's a browser add one to the browsers count
        if (isset($properties['isBrowser']) && $properties['isBrowser']) {
            $trafficDesktopBrowsers++;

        // if it's a mobile device check the type
        } elseif (isset($properties['mobileDevice']) && $properties['mobileDevice']) {

            // aggregate mobile device types
            $primaryDeviceType = isset($properties['primaryHardwareType'])?
                $properties['primaryHardwareType']: 'Other';

            if (isset($trafficMobileDevices[$primaryDeviceType])) {
                $trafficMobileDevices[$primaryDeviceType]++;
            } else {
                $trafficMobileDevices[$primaryDeviceType] = 1;
            }

            // detect device vendor and aggregate it
            $vendor = isset($properties['vendor'])? $properties['vendor']: null;
            if ($vendor) {
                if (isset($trafficByMobileVendor[$vendor])) {
                    $trafficByMobileVendor[$vendor]++;
                } else {
                    $trafficByMobileVendor[$vendor] = 1;
                }
            }
        }

        // detect the operating system aggregate it
        $os = isset($properties['osName'])? $properties['osName']: null;
        if ($os) {
            if (isset($trafficByOs[$os])) {
                $trafficByOs[$os]++;
            } else {
                $trafficByOs[$os] = 1;
            }
        }

    }

} catch (Mobi_Mtld_Da_Exception_DataFileException $ex) {
    die("Could not find Device Data file. Did you download a data file from https://deviceatlas.com/user to be referenced by this example?\n");

} catch (Exception $ex) {

    /* if errors have happened then we can't do much but removing the cause */
    echo $ex->getMessage();
    exit;
}




// DISPLAY RESULTS
echo
'<!doctype html>
<html>
  <head>
    <title>Analytics Example using the DeviceAtlas CloudApi</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="css/style.css" media="all" />
    <script type="text/javascript" src="js/sortable.js"></script>
  </head>
  <body>
    <h1>Analytics Example using the DeviceAtlas CloudApi</h1>';

// Display traffic by device type
$totalMobileTraffic = array_sum($trafficMobileDevices);
$total              = $trafficDesktopBrowsers + $totalMobileTraffic;
echo
   '<h2>Division of Desktop vs Mobile Traffic</h2>
    <table>'.
      '<tr>'.
        '<td class="name">Mobile device</td>'.
        '<td class="hits">'.$totalMobileTraffic.'</td>'.
        '<td class="line"><div style="width:'.($totalMobileTraffic*200/$total).'px"></div></td>'.
      '</tr>'.
      '<tr>'.
        '<td class="name">Desktop browser</td>'.
        '<td class="hits">'.$trafficDesktopBrowsers.'</td>'.
        '<td class="line"><div style="width:'.($trafficDesktopBrowsers*200/$total).'px"></div></td>'.
      '</tr>'.
    '</table>';

// Display traffic by mobile types

echo
   '<h2>Divison of Traffic by Mobile Device Type</h2>
    <table>';

arsort($trafficMobileDevices);

foreach ($trafficMobileDevices as $deviceTypeName => $traffic) {
    echo
      '<tr>'.
        '<td class="name">'.$deviceTypeName.'</td>'.
        '<td class="hits">'.$traffic.'</td>'.
        '<td class="line"><div style="width:'.($traffic*200/$total).'px"></div></td>'.
      '</tr>';
}

echo
    '</table>';

// Display hits by mobile vendors in a table and chart
echo
   '<h2>Display traffic by mobile vendor</h2>
    <table class="sortable">'.
        '<tr>'.
          '<th>Vendor</th>'.
          '<th>Hits</th>'.
          '<th class="sorttable_nosort"></th>'.
        '</tr>';
arsort($trafficByMobileVendor);
$total = array_sum($trafficByMobileVendor);
foreach ($trafficByMobileVendor as $vendor => $hits) {
    echo
        '<tr>'.
          '<td class="name">'.$vendor.'</td>'.
          '<td class="hits">'.$hits.'</td>'.
          '<td class="line"><div style="width:'.($hits*200/$total).'px"></div></td>'.
        '</tr>';
}
echo '</table>';


// Display hits by OS
echo
   '<h2>Display traffic by operating systems</h2>
    <table class="sortable">'.
        '<tr>'.
          '<th>OS</th>'.
          '<th>Hits</th>'.
          '<th class="sorttable_nosort"></th>'.
        '</tr>';

arsort($trafficByOs);
$total = array_sum($trafficByOs);
foreach ($trafficByOs as $os => $hits) {
    echo
        '<tr>'.
          '<td class="name">'.$os.'</td>'.
          '<td class="hits">'.$hits.'</td>'.
          '<td class="line"><div style="width:'.($hits*200/$total).'px"></div></td>'.
        '</tr>';
}
echo '</table>
  </body>
</html>';
