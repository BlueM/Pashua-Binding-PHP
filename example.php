#!/usr/bin/php
<?php

//
// USAGE INFORMATION:
// As you can see this text, you obviously have opened the file in a text editor.
//
// If you would like to *run* this example rather than *read* it, you
// should open Terminal.app, drag this document's icon onto the terminal
// window, bring Terminal.app to the foreground (if necessary) and hit return.
//

$minVersion = '5.3';
if (version_compare(PHP_VERSION, $minVersion) < 0) {
    fwrite(STDERR, "Sorry, this script requires PHP $minVersion or higher\n");
    exit(1);
}

// Define what the dialog should be like
// Take a look at Pashua's Readme file for more info on the syntax
$conf = <<<EOCONF
# Set transparency: 0 is transparent, 1 is opaque
*.transparency=0.95

# Set window title
*.title = Introducing Pashua

# Introductory text
txt.type = text
txt.default = Pashua is an application for generating dialog windows from programming languages which lack support for creating native GUIs on Mac OS X. Any information you enter in this example window will be returned to the calling script when you hit “OK”; if you decide to click “Cancel” or press “Esc” instead, no values will be returned.[return][return]This window demonstrates nine of the GUI widgets that are currently available. You can find a full list of all GUI elements and their corresponding attributes in the documentation that is included with Pashua.
txt.height = 276
txt.width = 310
txt.x = 340
txt.y = 44

# Add a text field
tf.type = textfield
tf.label = Example textfield
tf.default = Textfield content
tf.width = 310

# Add a filesystem browser
ob.type = openbrowser
ob.label = Example filesystem browser (textfield + open panel)
ob.width=310
ob.tooltip = Blabla filesystem browser

# Define radiobuttons
rb.type = radiobutton
rb.label = Example radiobuttons
rb.option = Radiobutton item #1
rb.option = Radiobutton item #2
rb.option = Radiobutton item #3
rb.option = Radiobutton item #4
rb.default = Radiobutton item #2

# Add a popup menu
pop.type = popup
pop.label = Example popup menu
pop.width = 310
pop.option = Popup menu item #1
pop.option = Popup menu item #2
pop.option = Popup menu item #3
pop.default = Popup menu item #2

# Add a checkbox
chk1.type = checkbox
chk1.label = Pashua offers checkboxes, too
chk1.rely = -18
chk1.default = 1

# Add another one
chk2.type = checkbox
chk2.label = But this one is disabled
chk2.disabled = 1

# Add a cancel button with default label
cb.type=cancelbutton

EOCONF;

// Set the images' paths relative to this file's path /
// skip images if they can not be found in this file's path
$bgimg = __DIR__ . '/.demo.png';
$icon  = __DIR__ . '/.icon.png';

if (file_exists($icon)) {
    // Display Pashua's icon
    $conf .= "img.type = image
              img.x = 530
              img.y = 255
              img.path = $icon\n";
}

if (file_exists($bgimg)) {
    // Display background image
    $conf .= "bg.type = image
              bg.x = 30
              bg.y = 2
              bg.path = $bgimg";
}



# Pass the configuration string to the Pashua module
$result = pashua_run($conf, 'utf8');

print "Pashua returned the following array:\n";
var_export($result);


/**
 * Wrapper function for accessing Pashua from PHP
 *
 * @param string $conf     Configuration string to pass to Pashua
 * @param string $encoding [optional] Configuration string's text encoding (default: "macroman")
 * @param string $apppath  [optional] Absolute filesystem path to directory containing Pashua
 *
 * @throws \RuntimeException
 * @return array Associative array of values returned by Pashua
 */
function pashua_run($conf, $encoding = 'macroman', $apppath = null) {

    // Check for safe mode
    if (ini_get('safe_mode')) {
        $msg = "To use Pashua you will have to disable safe mode or ".
               "change " . __FUNCTION__ . "() to fit your environment.\n";
        fwrite(STDERR, $msg);
        exit(1);
    }

    // Write configuration string to temporary config file
    $configfile = tempnam('/tmp', 'Pashua_');
    if (false === $fp = @fopen($configfile, 'w')) {
        throw new \RuntimeException("Error trying to open $configfile");
    }
    fwrite($fp, $conf);
    fclose ($fp);

    // Try to figure out the path to pashua
    $bundlepath = "Pashua.app/Contents/MacOS/Pashua";

    if ($apppath) {
        // A directory path was given
        $path = str_replace('//', '/', $apppath.'/'.$bundlepath);
    } else {
        // Try find Pashua in one of the common places
        $paths = array(
            __DIR__ . '/Pashua',
            __DIR__ . "/$bundlepath",
            "./$bundlepath",
            "/Applications/$bundlepath",
            "$_SERVER[HOME]/Applications/$bundlepath"
        );
        // Then, look in each of these places
        foreach ($paths as $searchpath) {
            if (file_exists($searchpath) and is_executable($searchpath)) {
                // Looks like Pashua is in $dir --> exit the loop
                $path = $searchpath;
                break;
            }
        }

        // Raise an error if we didn't find the application
        if (empty($path)) {
            throw new \RuntimeException(
                'Unable to locate Pashua. Tried to find it in: '.join(', ', $paths)
            );
        }
    }

    // Call pashua binary with config file as argument and read result
    $cmd = escapeshellarg($path).' '.
           (preg_match('#^\w+$#', $encoding) ? "-e $encoding " : '').
           escapeshellarg($configfile);
    $result = shell_exec($cmd);

    @unlink($configfile);

    $parsed = array();

    // Parse result
    foreach (explode("\n", $result) as $line) {
        preg_match('/^(\w+)=(.*)$/', $line, $matches);
        if (empty($matches) or empty($matches[1])) {
            continue;
        }
        $parsed[$matches[1]] = $matches[2];
    }

    return $parsed;
}
