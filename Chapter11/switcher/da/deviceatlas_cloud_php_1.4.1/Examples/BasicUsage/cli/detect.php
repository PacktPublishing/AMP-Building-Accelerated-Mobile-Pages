#!/usr/bin/php
<?php
/**
 * DeviceAtlas CloudApi CLI Basic Usage Example.
 *
 * This example demonstrates using the CloudApi, for the sake of simplicity it
 * is made as a command line application.
 * Run this example from the command line as "php cli.php"
 *
 * @copyright Copyright (c) 2008-2014 by Afilias Technologies Limited (dotMobi). All rights reserved.
 * @author dotMobi
 */

// to see all errors only when in development environment
error_reporting(E_ALL);
ini_set('display_errors', 1);


/* (1) Edit the DeviceAtlasCloud "Client.php" file and set your licence key: */
//     const LICENCE_KEY = 'YOUR-DA-LICENCE-KEY';

/* (2) Include the CloudApi library */
require_once dirname(__FILE__).'/../../../Api/Client.php';


/* Inputs for the API */

// a set of HTTP headers for the detection and getting the properties
$headers = array(
    'accept-language'      => 'en',
    'user-agent'           => 'Opera/9.80 (Android; Opera Mini/5.0.18302/34.1000; U; en) Presto/2.8.119 Version/11.10',
    'x-operamini-phone-ua' => 'Mozilla/5.0 (Linux; U; Android 2.3.6; en-gb; GT-S6102 Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
);




// it is highly recommended to use the API in a try/catch block
try {

    /* (3) now we can use the CloudApi to get properties

       - we pass the set of HTTP headers

       the function below will pass a set of HTTP headers to the CloudApi
       and gets the properties then uses and displays them in various ways
     */
    basicUsage($headers);

} catch (Exception $ex) {
    // all errors must be taken care of
    print "\nErrors:\n";
    print $ex->getMessage()."\n";
}



/**
 * Displaying properties in various ways
 * demonstrates the usage of "getProperties()" method and how to use it's output
 */
function basicUsage($headers) {

    /* Get the properties */

    $properties = DeviceAtlasCloudClient::getDeviceData($headers);
    if (isset($properties[DeviceAtlasCloudClient::KEY_PROPERTIES])) {
        $properties = $properties[DeviceAtlasCloudClient::KEY_PROPERTIES];
    } else {
        return;
    }


    // iterate over the properties to display property names, data types and value
    print "-------------------------------------------------------------\n";
    print "All Properties:\n";

    foreach ($properties as $name => $property) {
        print 
            $name.
            ' (' . gettype($property) . ") :\n".
            "\t" . $property . "\n";
    }





    // iterating over the properties may not be a good example of real life usage
    // so here we demonstrate usages that are more likely to happen after a detection
    print "-------------------------------------------------------------\n";
    print "Using the properties:\n";

    // check if mobileDevice is true?
    $isMobileDevice = isset($properties['mobileDevice'])? $properties['mobileDevice']: null;

    // check is vendor is Samsung (case-sensitive)
    $isSamsung = isset($properties['mobileDevice'])? $properties['mobileDevice'] === 'Samsung': false;

    // lets display something based on what we got
    print $isMobileDevice?
        "\n*** it's a mobile device ***\n":
        "\n*** it's not a mobile device ***\n";

    print $isSamsung?
        "\n*** Vendor is Samsung ***\n":
        "\n*** Vendor is not Samsung ***\n";






    // to get the property value without considering the data type strictly
    $browserName  = isset($properties['browserName'])? $properties['browserName']: null;

    // if you know the exact data type of a property you can get it as typed
    $yearReleased = isset($properties['yearReleased'])? $properties['yearReleased']: null;

    // you can use Properties.__get() - returns boolean or null if property not exists
    $isBrowser    = isset($properties['isBrowser']) && $properties['isBrowser'];

    // lets display the results
    print "\n*** 'browserName'  = $browserName ***\n";
    print "\n*** 'yearReleased' = $yearReleased ***\n";
    print "\n*** 'isBrowser'    = ".($isBrowser? 'true': 'false')." ***\n";

}
