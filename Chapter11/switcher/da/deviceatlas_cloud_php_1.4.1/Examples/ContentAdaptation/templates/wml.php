<?php echo '<?xml version="1.0"?>'."\n"; ?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.2//EN" "http://www.wapforum.org/DTD/wml12.dtd">
<wml>
  <head>
  </head>
  <card id="index">

    <p>Low-end Device Experience</p>
    <p>
      This is the layout of our sample site intended for low-end devices which
      can only display WML.
    </p>

    <p>Content Adaptation</p>
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

    <p>Example directories and files</p>
    <p>
      <strong>/Examples/ContentAdaptation/index.php</strong>
      <br/>
      This script uses DeviceAtlas and decides which template should be
      used to wrap around the contents.
    </p>
    <p>
      <strong>/Examples/ContentAdaptation/templates/wml.php</strong>
      <br/>
      This is the template of the page you are seeing now.
    </p>

    <p>Properties</p>
    <table>
      <?php
      foreach ($properties as $name => $value) {
          if (is_bool($value)) {
              $value = $value? 'true': 'false';
          }
          echo "<tr><td>$name</td><td>$value</td></tr>";
      }
      ?>
    </table>

  </card>
</wml>
