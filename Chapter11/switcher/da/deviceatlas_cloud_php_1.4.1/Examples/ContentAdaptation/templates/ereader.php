<!doctype html>
<html>
  <head>
    <title>DeviceAtlas Content Adaptation example | E-Reader Device</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  </head>
  <body <?php echo "style='max-width:".(isset($property['displayWidth'])? $property['displayWidth']: '1024')."px'"; ?>>
    <h1>E-Reader Device Experience</h1>
    <p>
      This is the layout of our sample site intended for E-Reader experience.
    </p>

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

    <h2>Example directories and files</h2>
    <p>
      <strong>/Examples/ContentAdaptation/index.php</strong>
      <br/>
      This script uses DeviceAtlas and decides which template should be
      used to wrap around the contents.
    </p>
    <p>
      <strong>/Examples/ContentAdaptation/templates/ereader.php</strong>
      <br/>
      This is the template of the page you are seeing now.
    </p>

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

  </body>
</html>
