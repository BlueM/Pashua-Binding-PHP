Overview
===========

This is a PHP language binding (glue code) for using [Pashua](www.bluem.net/jump/pashua) from PHP. Pashua is a Mac OS X application for using native GUI dialog windows in various programming languages.

The only code file in this repository, `example.php`, contains a generic function `pashua_run()` which can be used to manage the communication with Pashua. The way `pashua_run()` works is neither the best nor the only way to “talk” to Pashua from within PHP, but rather one out of several possibe ways.

Requirements
=============
This code requires PHP 5.3 and Pashua. The CLI versions of PHP shipped by Apple as part of the last few releases of Mac OS X can be used to run the code.
