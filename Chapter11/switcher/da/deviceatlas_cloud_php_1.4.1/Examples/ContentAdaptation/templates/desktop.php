<!doctype html>
<html>
  <head>
    <title>DeviceAtlas Content Adaptation example | Desktop Computer Browser</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="css/desktop.css" media="all" />

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
    if (!isset($properties['js.json']) || !$properties['js.json']) {
        // load custom json libs
    }
    ?>
  </head>
  <body>
    <h1>Desktop Computer Browser Experience</h1>
    <div id="contents" class="clearfix">

      <div id="panel-left">
        <div id="description" class="row">
          <p>
            This is the layout of our sample site intended for desktop experience.
            It means one of the following situations is true:
          </p>
          <p>You are using a desktop browser.</p>
          <p>
            You are using a device that is masquerading itself as a desktop
            browser to get a desktop experience.
          </p>
          <p>
            Your machine is not detected as a desktop, tablet or mobile but you
            are getting this layout because this is the default layout template.
          </p>
        </div>

        <div class="row">
          <h2>Content Adaptation</h2>
          <p>
            To adapt contents dynamically, so each visitor gets the optimum
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
            and styles are tuned for different devices. 
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

        <div class="row">
          <h2>Example directories and files</h2>
          <p>
            <strong>/Examples/ContentAdaptation/index.php</strong>
            <br/>
            This script uses DeviceAtlas and decides which template should be
            used to wrap around the contents.
          </p>
          <p>
            <strong>/Examples/ContentAdaptation/templates/desktop.php</strong>
            <br/>
            This is the template of the page you are seeing now.
          </p>
        </div>

      </div>

      <div id="panel-right">
        <div class="row">
          <h2>Properties</h2>
          <table>
            <?php
            foreach ($properties as $name => $value) {
                if (is_bool($value)) {
                    $value = $value? 'true': 'false';
                }
                echo "<tr><td class='label'>$name</td><td>$value</td></tr>";
            }
            ?>
          </table>
        </div>
        <div class="row">
          <h2>Can we display videos on this browser?</h2>
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
        Let's say we want to display a photo, as we now know the type of device
        viewing the page, we can select a photo which is the most suitable for
        the device. We can even optimize the page furthur and use property
        values such as "displayWidth", "displayHeight", "usableDisplayWidth" and
        "usableDisplayHeight" to provide the optimal photo.
      </p>
      <p>
        As we already know a desktop computer is viewing the page we have plenty
        of room so we can show our largest photo.
      </p>
      <img src="images/homa-large.jpg" />
    </div>

  </body>
</html>
