<!doctype html>
<html>
  <head>
    <title>DeviceAtlas Content Adaptation example | Tablet Device</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link type="text/css" rel="stylesheet" href="css/tablet.css" media="all" />

    <?php
    // if supports touch we can show bigger buttons and links
    if (isset($properties['touchScreen']) && $properties['touchScreen']) {
        echo '<link type="text/css" rel="stylesheet" href="css/touch.css" media="all" />';
    }
    ?>

<!--
    DeviceAtlas Client Side Component

    * using the DeviceAtlas Client Side Component will add the client-side
      properties to the DeviceAtlas property set

    * using the DeviceAtlas Client Side Component is as easy as including this
      JavaScript file to your page:
 -->    
    <script type="text/javascript" src="js/deviceatlas-1.5.min.js"></script>

    <?php
    // the js.xxxxx properties can be used to check if a certain JavaScript
    // feature is supported or not.
    if (isset($properties['supportBasicJavaScript']) && $properties['supportBasicJavaScript']) {
        echo '<script>';
        // do some JavaScript here ...
        echo '</script>';
    }
    ?>

  </head>
  <body <?php echo "style='max-width:".(isset($property['displayWidth'])? $property['displayWidth']: '1024')."px'"; ?>>

<!--
  Getting Device height and displaying two dotted vertical lines left and right
  of the screen > > > 
 -->    
    <?php
    $displayHeight       = isset($properties['displayHeight'])?    $properties['displayHeight']: 1;
    $devicePixelRatio    = isset($properties['devicePixelRatio'])? $properties['devicePixelRatio']: 1;
    $usableDisplayHeight = $displayHeight / (float)$devicePixelRatio;
    ?>

    <div style="height: <?=$displayHeight?>px;
                border-left: 4px dotted white;
                position: absolute;
                top: 0px; left: 0px"></div>
    <div style="height: <?=$usableDisplayHeight?>px;
                border-left: 4px dotted white;
                position: absolute;
                top: 0px; right: 0px"></div>
<!--
  < < <
 -->    

    <h1>Tablet Device Experience</h1>
    <div id="contents" class="clearfix">

      <div id="panel-left">

        <div id="description" class="cell">
          <p>
            This is the layout of our sample site intended for tablet mobile
            computers.
          </p>
        </div>

        <div class="cell">
          <h2>Content Adaptation</h2>
          <p>
            To adapt content dynamically, so that each visitor gets the optimum
            user experience. Whether the device used is a smartphone, tablet,
            eReader, or a low end device, you only get one chance to make a first
            impression. Ensure your site loads quickly and is easy to navigate.
            DeviceAtlas provides all the information needed to support content
            adaptation decisions.
          </p>
          <p>
            On a page request, DeviceAtlas API is used to get the device
            properties. By putting conditions on some of the properties which
            are usually about the device type a layout template is chosen loaded
            wraped around the data and sent back to the user.
          </p>
          <p>
            Usually most of the contents remain the same and only the templates
            and styles differ from each other.
          </p>
          <p>
            Using the handful of DeviceAtlas properties each template can be
            fine-tuned for the device and browser.
          </p>
        </div>

        <div class="row">
          <h2>Links and buttons</h2>
          <div id="links">
            <a href="#">Goto somewhere</a>
            <button>Do something</button>
          </div>
        </div>

        <div class="cell">
          <h2>Example directories and files</h2>
          <p>
            <strong>/Examples/ContentAdaptation/index.php</strong>
            <br/>
            This script uses DeviceAtlas and decides which template should be
            used to wrap around the contents.
          </p>
          <p>
            <strong>/Examples/ContentAdaptation/templates/mobile.php</strong>
            <br/>
            This is the template of the page you are seeing now.
          </p>
        </div>

      </div>

      <div id="panel-right">

        <div class="cell">
          <h2>Properties</h2>
          <table>
            <?php
            // show this properties above others
            $propsToShow = array(
                'vendor'              => null,
                'model'               => null,
                'marketingName'       => null,
                'yearReleased'        => null,
                'primaryHardwareType' => null,
                'displayWidth'        => null,
                'displayHeight'       => null,
                'touchScreen'         => null,
            );

            foreach ($properties as $name => $value) {
                if (is_bool($value)) {
                    $value = $value? 'true': 'false';
                }
                $propsToShow[$name] = "<tr><td class='label'>$name</td><td>$value</td></tr>";
            }

            echo implode('', $propsToShow);

            ?>
          </table>
        </div>

        <div class="cell">
          <h2>Sample property usage</h2>
          <?php

          if (isset($properties['html.video']) && $properties['html.video']) {
              echo '<p>This browser supports the video HTML tag, we can use it to '.
                'show a video here</p>';
          }

          if (isset($properties['flashCapable']) && $properties['flashCapable']) {
              echo '<p>Flash is supported we can display flash here</p>';
          } else {
              echo '<p>Flash is not supported</p>';
          }

          ?>
        </div>
      </div>
    </div>

    <div id="image-container">
      <p>
        Let's say we want to display a photo, as we know what type of device is
        viewing the page we can select a photo which is the most suitable for that
        type of device. We can even optimize the page furthur and use property
        values such as "displayWidth", "displayHeight", "usableDisplayWidth" and
        "usableDisplayHeight" to provide the optimal photo.
      </p>
      <p>
        As we already know a tablet is viewing the page it seems showing the medium
        sized photo is a good go.
      </p>
      <img src="images/homa-medium.jpg" />
    </div>

  </body>
</html>
