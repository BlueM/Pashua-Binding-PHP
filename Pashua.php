<?php

// Pashua binding for PHP
// See Readme.md for authors/contributors and license

// Usage: either take the methods out of this file and use them inlined in your code
// as functions, or require this file and call the method(s) as demonstrated in the
// accompanying file example.php

namespace BlueM;

/**
 * Static class which wraps the two simple methods used for communicating with Pashua
 */
class Pashua
{
    /**
     * Invokes a Pashua dialog window with the given window configuration
     *
     * @param string $conf           Configuration string to pass to Pashua
     * @param string $customLocation Filesystem path to directory containing Pashua
     *
     * @throws \RuntimeException
     * @return array Associative array of values returned by Pashua
     */
    public static function showDialog($conf, $customLocation = null)
    {
        if (ini_get('safe_mode')) {
            $msg = "To use Pashua you will have to disable safe mode or " .
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
        fclose($fp);

        $path = static::getPashuaPath($customLocation);

        // Call pashua binary with config file as argument and read result
        $result = shell_exec(escapeshellarg($path) . ' ' . escapeshellarg($configfile));

        @unlink($configfile);

        // Parse result
        $parsed = array();
        foreach (explode("\n", $result) as $line) {
            preg_match('/^(\w+)=(.*)$/', $line, $matches);
            if (empty($matches) or empty($matches[1])) {
                continue;
            }
            $parsed[$matches[1]] = $matches[2];
        }

        return $parsed;
    }

    /**
     * Returns the filesystem path to Pashua
     *
     * Will throw a RuntimeException if Pashua.app cannot be found
     *
     * @param string|null $customLocation Folder which contains Pashua.app. Will be
     *                                    prepended to the default search paths.
     *
     * @throws \RuntimeException
     *
     * @return string Path to the executable inside the application bundle
     */
    public static function getPashuaPath($customLocation = null)
    {
        // Try to figure out the path to pashua
        $bundlepath = "Pashua.app/Contents/MacOS/Pashua";

        // Default search locations
        $paths = array(
            __DIR__ . '/Pashua',
            __DIR__ . "/$bundlepath",
            "./$bundlepath",
            "/Applications/$bundlepath",
            "$_SERVER[HOME]/Applications/$bundlepath"
        );

        if ($customLocation) {
            // Custom search location
            array_unshift($paths, "$customLocation/$bundlepath");
        }

        foreach ($paths as $searchpath) {
            if (file_exists($searchpath) and is_executable($searchpath)) {
                // Looks like Pashua is in $dir --> exit the loop
                return $searchpath;
            }
        }

        // Still here? Then we have a problem.
        throw new \RuntimeException(
            'Unable to locate Pashua. Tried to find it in: ' . join(', ', $paths)
        );
    }
}
