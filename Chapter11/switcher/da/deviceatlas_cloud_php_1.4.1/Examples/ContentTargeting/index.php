<?php
/**
 * Content Targeting Example using the DeviceAtlas CloudApi
 *
 * Scenario: First, let's plan what we want to do and what this example is about.
 * In this example we want to show two samples of content targeting.
 *
 * (1) Advertising, we have a database which contains our ads and a knowledge base
 *     that includes some logic that relates the ads to property values. For example
 *     our knowledge base would tell us "people who have old and low-end phones
 *     are more likely to be interested in new high-end smart phones" or "those
 *     who have specific devices (brand, model, type of device, etc.) would probably
 *     be interested in specific devices or accessories". DeviceAtlas can be used
 *     to get device properties, then used against the knowledge base to get the
 *     set of ads the user would probably be interested in. In this example we
 *     use a small array and a few if conditions to mimic the knowledge base.
 *
 * (2) Downloading an app, we have an app written in different platforms, when a
 *     user comes to download the app we can use DeviceAtlas to detect user's
 *     operating system and automatically show him the link to the app which is
 *     created for her/his device.
 *
 * Note: In this example the DeviceAtpiWeb will optimize the data file on the
 * first request. The cached optimized data files will be used by the next lookups.
 * The default config setting for using the data-file optimizer in CloudApi
 * is set to true.
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

/* (3) Get properties for current request */

$errors     = false;
$properties = null;

// it is highly recommended to use the API in a try/catch block
try {

    $result = DeviceAtlasCloudClient::getDeviceData();
    if (isset($result[DeviceAtlasCloudClient::KEY_PROPERTIES])) {
        $properties = $result[DeviceAtlasCloudClient::KEY_PROPERTIES];
    } else {
        $error = true;
    }

} catch (Exception $ex) {
    // It is very important to be aware of any error thrown by the DeviceAtlas API
    //
    // For example the DeviceAtlas API tries to locally cache data, if the
    // (temp/cache) directory is now writable an error is thrown. It is important
    // to see this error and make the directory writable.
    //
    // Recommended: show errors at test time and log them at other times
    $errors = true;
}






/* (A) Advertise */


// the selected ads to be shown to the user will be placed in this array
$toAdvertise = array();

if (!$errors) {
    // get properties from DeviceAtlas
    $vendor       = isset($properties['vendor'])?        strtolower($properties['vendor']): null;
    $model        = isset($properties['marketingName'])? $properties['marketingName']:      null;
    $yearReleased = isset($properties['yearReleased'])?  $properties['yearReleased']:       null;

    // lets target Samsung devices
    if ($vendor === 'samsung') {
        // if the device is an older model smart-phone, lets add the newer similar devices 
        if (stripos($model, 'galaxy s') !== false && stripos($model, 'galaxy s5') === false) {

            putAdvertise($toAdvertise, 'samsung', 's5');
            // probably an Android guy, lets advertise similar Android devices
            putAdvertise($toAdvertise, 'android');
            unset($toAdvertise['s5']);
        }
        // this guy probably likes big mobiles, he has an older one, lets add the newer model
        elseif (stripos($model, 'galaxy note') !== false && stripos($model, 'galaxy note 3') === false) {
            putAdvertise($toAdvertise, 'samsung', 'note3');
        }
        // no more clues! let's add Samsung devices
        else {
            putAdvertise($toAdvertise, 'samsung');
        }
    }

    // lets target Apple devices
    elseif ($vendor === 'apple') {
        // if the device is an older model lets add the newer models
        if (stripos($model, 'iphone') !== false && stripos($model, 'iphone 5') === false) {
            putAdvertise($toAdvertise, 'apple', 'iphone5');
            // this guy is probably not interested in Android, but lets offer an HTC
            putAdvertise($toAdvertise, 'htc');
        }
        // no more clues! let's add Apple devices
        else {
            putAdvertise($toAdvertise, 'apple');
        }
    }
    // is the device to old? lets show some our new smart phones 
    elseif ($yearReleased && $yearReleased < 2011) {
        putAdvertise($toAdvertise, 'newest');
    }
    // not included in our target, lets show some random ads
    else {
        putAdvertise($toAdvertise, 'random');
    }

// if errors the show some random ads
} else {
    putAdvertise($toAdvertise, 'random');
}


/**
 * This function simulates the knowledge base and logics which select ads based
 * on property values.
 * Puts advertise into $toAdvertise, relevant to the keywords
 */
function putAdvertise(&$toAdvertise, $keyword1, $keyword2=null) {
    $ads = array(
        'apple' => array(
            'iphone5s'  => 'Apple iPhone 5s',
            'ipadmini2' => 'Apple iPad mini 2',
        ),
        'htc' => array(
            'htc1'   => 'HTC One',
            'desire' => 'HTC Desire 310',
        ),
        'samsung' => array(
            's5'    => 'Samsung Galaxy S5',
            'note3' => 'Samsung Galaxy Note 3',
        ),
        'sony' => array(
            'z' => 'Sony Xperia Z',
            'm' => 'Sony Xperia M',
        ),
        'android' => array(
            'htc1'   => 'HTC One',
            'desire' => 'HTC Desire 310',
            's5'     => 'Samsung Galaxy S5',
            'note3'  => 'Samsung Galaxy Note 3',
            'z'      => 'Sony Xperia Z',
            'm'      => 'Sony Xperia M',
        ),
        'newest' => array(
            'HTC One',
            'HTC Desire 310',
            'Samsung Galaxy S5',
            'Samsung Galaxy Note 3',
            'Sony Xperia Z',
            'Apple iPhone 5s',
        ),
    );

    if ($keyword2 && isset($ads[$keyword1][$keyword2])) {
        $toAdvertise = $ads[$keyword1][$keyword2];
    } 

    if (isset($ads[$keyword1])) {
        $toAdvertise = $ads[$keyword1];
    } else {
        foreach (array_rand($ads, 5) as $key) {
            $element = array_values($ads[$key]);
            $toAdvertise[] = $element[rand(0, count($element) - 1)];
        }
    }
}

// now $toAdvertise contains the ads we want to show in the page banner
// the page HTML is created at the bottom of this script



/* (B) Find a suitable App download link for the device */


// all available download links for our app
$allDownloadLinks = array(
    'Android'        => '#download-android-app',
    'Bada'           => '#download-bada-app',
    'iOS'            => '#download-ios-app',
    'RIM'            => '#download-rim-app',
    'Symbian'        => '#download-symbian-app',
    'Windows Mobile' => '#download-windows-mobile-app',
    'Windows Phone'  => '#download-windows-phone-app',
    'Windows RT'     => '#download-windows-rt-app',
    'webOS'          => '#download-webos-app',
    'Windows'        => '#download-desktop-windows-app',
    'Linux'          => '#download-desktop-linux-app',
    'Mac'            => '#download-desktop-mac-app',
);

// we will put the links to be displayed on the page in $downloadLinks
$downloadLinks = null;

if (!$errors) {
    // if osName is detected
    if (isset($properties['osName'])) {

        $osName = $properties['osName'];

        // try to find desktop os names
        if (stripos($osName, 'linux') !== false) {
            $osName = 'Linux';
        } elseif (stripos($osName, 'mac') !== false || strpos($osName, 'apple') !== false) {
            $osName = 'Mac';
        } elseif (stripos($osName, 'win') !== false) {
            $osName = 'Windows';
        }
        // get the download link for the os
        if (isset($allDownloadLinks[$osName])) {
            $downloadLinks[$osName] = $allDownloadLinks[$osName];
        }
    }
}

// by default (if errors or unknown os) we want to show all the links
if (!$downloadLinks) {
    $downloadLinks = $allDownloadLinks;
}



/* DISPLAY THE PAGE */

echo
'<!doctype html>
<html>
  <head>
    <title>Content Targeting Example using DeviceAtlas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="css/style.css" media="all" />
  </head>
  <body>
    <h1>Content Targeting Example using DeviceAtlas</h1>';

// Display ADS
echo '
    <h2>Ads targeted for this device</h2>
    <ul>';
foreach ($toAdvertise as $advertise) {
    echo '<li>'.$advertise.'</li>';
}
echo '
    </ul>';

// Display App download link(s)
echo '
    <h2>App download</h2>';

foreach ($downloadLinks as $osName => $downloadLink) {
    echo '<p><a href="'.$downloadLink.'">Download app for '.$osName.'</a></p>';
}

echo '
    <br/>
    <br/>
  </body>
</html>';
