<?php
/**
 * DeviceAtlas Cloud Example
 *
 * This sample code fetches data from the DeviceAtlas Cloud service.
 *
 * NOTE: if cookie caching is turned on then the getDeviceData() method must
 * be called before any output to the browser.
 *
 * NOTE: when deviceatlas-X.X.min.js is included on a page, DeviceAtlas Client
 * Side Component device data and puts it into a cookie, when using
 * DeviceAtlas Cloud service the cookie data will be used to create the final
 * result.
 *
 * @copyright Copyright (c) 2008-2014 by Afilias Technologies Limited (dotMobi). All rights reserved.
 * @author dotMobi
 */

error_reporting(E_ALL);       // for testing to help seeing problems
ini_set('display_errors', 1); // for testing to help seeing problems
$startTime = microtime(true); // timer to see how long it takes to get device data



/* (1) Edit the DeviceAtlasCloud "Client.php" file and set your licence key: */
//     const LICENCE_KEY = 'YOUR-DA-LICENCE-KEY';



/* (2) Include DeviceAtlasCloud: */
require_once dirname(__FILE__).'/../../../Api/Client.php';



/* (3) Get data: */
$errors = null;

// it is highly recommended to use the API in a try/catch block
try {
    // get device properties for the current request
    $properties = DeviceAtlasCloudClient::getDeviceData();

    // if errors happend within the cloud call/API
    if (isset($properties[DeviceAtlasCloudClient::KEY_ERROR])) {
        $errors = trim($properties[DeviceAtlasCloudClient::KEY_ERROR]);
    }

} catch (Exception $ex) {
    // all errors must be taken care ok
    $errors = $ex->getMessage();
}

// time spent for getting device data
$timeSpent = round((microtime(true) - $startTime) * 1000);

// use the device data...
// in this example the data will be printed on the page:
?>



<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DeviceAtlas Cloud Example</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" media="all"/>

    <!-- (4) ADD DeviceAtlas JavaScript library. This is optional. -->
    <script type="text/javascript" src="js/deviceatlas-1.5.min.js"></script>

  </head>
  <body>
    <h1>DeviceAtlas Cloud Example</h1>
    <div id="api">
      <ul>
        <li>
          <label>Licence Key: </label>
          <span><?php echo DeviceAtlasCloudClient::LICENCE_KEY?></span>
        </li>
        <li>
          <label>API Version: </label>
          <span><?php echo DeviceAtlasCloudClient::API_VERSION?></span>
        </li>
        <li>
          <label>Test Mode: </label>
          <span><?php echo isset($testMode) && $testMode? 'true': 'false'?></span>
        </li>
        <li>
          <label>Use Cookie Cache: </label>
          <span><?php echo DeviceAtlasCloudClient::USE_COOKIE_CACHE? 'true': 'false'?></span>
        </li>
        <li>
          <label>Use File Cache: </label>
          <span><?php echo DeviceAtlasCloudClient::USE_FILE_CACHE? 'true': 'false'?></span>
        </li>
        <li>
          <label>Cache Path: </label>
          <span><?php echo DeviceAtlasCloudClient::getCacheBasePath()?></span>
        </li>
        <li>
          <label>Auto Server Ranking: </label>
          <span><?php echo DeviceAtlasCloudClient::AUTO_SERVER_RANKING? 'true': 'false'?></span>
        </li>
        <li>
          <label>Cloud server: </label>
          <span>
          <?php
              $server = DeviceAtlasCloudClient::getCloudUrl();
              echo $server? $server: 'n/a';
          ?>
          </span>
        </li>
      </ul>
    </div>

    <div id="results">
      <h2>Results:</h2>

<?php
$propertiesKey = DeviceAtlasCloudClient::KEY_PROPERTIES;
$uaComment = '';
if (isset($properties[$propertiesKey]) && $properties[$propertiesKey]) {
    if (isset($testMode) && $testMode && !is_string($testMode)) {
        $uaComment =
            '<p id="ua-comment">(This is a test User-Agent. Set "Test Mode" to false to use real User-Agents)</p>';
    }

    $ua = isset($properties[DeviceAtlasCloudClient::KEY_USERAGENT])?
        $properties[DeviceAtlasCloudClient::KEY_USERAGENT]: 'None';

    $source = isset($properties[DeviceAtlasCloudClient::KEY_SOURCE])?
        $properties[DeviceAtlasCloudClient::KEY_SOURCE]: 'None';
?>

      <h3>User-agent:</h3>
      <p id="ua"><?php echo $ua.$uaComment?></p>

      <h3>Data Source:</h3>
      <p><?php echo $source?></p>

      <h3>Time taken:</h3>
      <p><?php echo $timeSpent?>ms</p>

      <h3>Properties:</h3>
      <table>

<?php
    // show properties
    // first take out the more important properties to show them on top
    // then order the rest in alphabetical order

    $properties = $properties[$propertiesKey];

    $top_props = array(
        'vendor'        => '',
        'model'         => '',
        'marketingName' => '',
        'displayWidth'  => '',
        'displayHeight' => '',
        'touchScreen'   => '',
    );

    foreach ($top_props as $name => $value) {
        if (isset($properties[$name])) {
            $top_props[$name] = $properties[$name];
            unset($properties[$name]);
        }
    }

    ksort($properties);
    $properties = array_merge($top_props, $properties);

    foreach ($properties as $name => $value) {
        $type = gettype($value);
        if ($value === true) {
            $value = 'true';
        } elseif ($value === false) {
            $value = 'false';
        } elseif ($value === null) {
            $value = 'null';
        }
        echo
          '<tr>
            <td class="prop-name">'.$name.':</td>
            <td class="type">('.$type.')</td>
            <td>'.$value.'</td>
          </tr>';
    }

    echo '</table>';

} else {
    echo '<p>No results returned.</p>';
}

// IF ERRORS
if ($errors) {
    echo '<h3>Errors:</h3><p id="error">'.nl2br($errors).'</p>';
}
?>

    </div>

<!-- 

DEVICE ATLAS CLOUD SERVER SETTINGS

-->

    <div id="servers">
      <h2>Servers:</h2>
      <p>
        The DeviceAtlas Cloud service is powered by independent clusters of servers
        spread around the world. This ensures optimum speed and reliability. The Client
        API is able to automatically switch to a different end-point if the current 
        end-point becomes unavailable. It can also (optionally) auto-rank all of the
        service end-points to choose the end-point with the lowest latency for your
        location.
      </p>
      <p>
        The Cloud service end-points are defined in the $SERVERS variable at the top of
        the Client.php file.
      </p>
      <p>
        By default the API will analyse the end-points from time to time to rank them by 
        their stability and response speed. The ranked list is then cached and used
        whenever the Client API needs to query the DeviceAtlas Cloud Service. If an end-
        point fails, the Client API will automatically switch to the next end-point on 
        the list.
      </p>
      <p>
        There is no need to edit the $SERVERS array if auto-ranking is turned on. If you
        wish, you may re-order the array and turn auto-ranking off. In this case the API
        will respect your preferred order of end-points and only switch to a different
        end-point should the primary one fail to resolve.
      </p>

      <br/>
      <h3>Auto server ranking (default)</h3>
      <div>
        <p><code>AUTO_SERVER_RANKING = true;</code></p>
        <p>
          The API will automatically check, rank, re-sorts and cache end-point list.
          The ranked cached end-point list will be used to get service from DeviceAtlas
          cloud. Based on the next settings the end-point list will be updated and
          re-ranked on periods.
        </p>
        <p>
          <code>AUTO_SERVER_RANKING_LIFETIME</code><br/>
          Time in minutes. How often to auto rank servers. Default value is 1440.
          0 = servers will be ranked and cached only once and this list will not be
          updated automatically. You can update this list manually:
          <code>DeviceAtlasCloudClient::rankServers();</code>
          Note: AUTO_SERVER_RANKING must be set true so this cached server list will
          be used by the API, even if <code>AUTO_SERVER_RANKING_LIFETIME</code> is set to 0.
          if <code>AUTO_SERVER_RANKING = false</code> then the cached server list will be 
          totally ignored.
        </p>
        <p>
          <code>AUTO_SERVER_RANKING_MAX_FAILURE</code><br/>
          This value is max number of times that a server can fail during testing/ranking.
          If a server fails more than this number it will be assumed unreliable and will
          not be included in the cached server list.
        </p>
        <p>
          <code>AUTO_SERVER_RANKING_NUM_REQUESTS</code><br/>
          When testing servers, requests are sent and the service time is measured.
          <code>AUTO_SERVER_RANKING_NUM_REQUESTS</code> is the number of requests to
          send to each server when ranking.
        </p>
        <p>
          <code>CLOUD_SERVICE_TIMEOUT</code><br/>
          Time in seconds. If an end-point fails to respond in this amount of time 
          the API will fail-over to the next end-point on the list.
        </p>
      </div>

      <br/>
      <h3>Manual server ranking</h3>
      <div>
        <p><code>AUTO_SERVER_RANKING = false;</code></p>
        <p>
          To turn auto ranking off, to manually rank the servers set to "false"
          and edit the $SERVERS array to set your preferred order of end-points. 
          The API will not rank the servers and will use the $SERVERS list items 
          directly with the topmost server used first to get device data. On fail-
          over the next end-point in the list will be used.
        </p>
        <p>
          The API provides few methods which can help you:
        </p>
        <p>
          You can test servers in <code>$SERVERS</code> and view the result then
          decide how to sort <code>$SERVERS</code> manually:
        </p>
        <p>
          <code>print_r(DeviceAtlasCloudClient::getServersLatencies());</code>
        </p>
        <p>
          <code>AUTO_SERVER_RANKING_LIFETIME</code><br/>
          Has no effect.
        </p>
        <p>
          <code>AUTO_SERVER_RANKING_MAX_FAILURE</code><br/>
          Has no effect.
        </p>
        <p>
          <code>AUTO_SERVER_RANKING_NUM_REQUESTS</code><br/>
          Has no effect. But
          <code>DeviceAtlasCloudClient::getServersLatencies();</code>
          respects this number for manual tests.
        </p>
        <p>
          <code>CLOUD_SERVICE_TIMEOUT</code><br/>
          Ignore a cloud server request if it is not answered after this amount of
          time (seconds) and go for the next server in the list.
        </p>
        <p>
          <code>SERVER_PHASEOUT_LIFETIME</code><br/>
            Used when auto ranking is OFF. Specifies how long to use the fail-over
            endpoints before the preferred end-point is re-checked. If the preferred
            end-point is available it will be added back into the list of end-points
            and used for future requests.
        </p>
      </div>

      <br/>
      <h3>Other methods:</h3>
      <div>
        <p>
          To see the server list which is used for cloud requests:
        </p>
        <p><code>DeviceAtlasCloudClient::getServers();</code></p>
        <?php
        foreach (DeviceAtlasCloudClient::getServers() as $server)
            echo '<p>'.$server['host'].':'.$server['port'].
                (isset($server['avg'])? ' <span title="Server service latency">('.
                round($server['avg'], 1).
                'ms)</span>': '').'</p>';
        ?>
        <p>
          To get the server which was called upon a request, if the API returns device data
          from cache then this method will return null.
        </p>
        <p><code>DeviceAtlasCloudClient::getCloudUrl();</code></p>
        <?php
        $server = DeviceAtlasCloudClient::getCloudUrl();
        echo '<p>'.($server? $server: 'n/a').'</p>';
        ?>
      </div>
    </div>
  </body>
</html>

