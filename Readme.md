Overview
===========

This is a PHP language binding (glue code) for using Pashua from PHP. Pashua is a macOS application for using native GUI dialog windows in various programming languages.

This code can be found in a GitHub repository at https://github.com/BlueM/Pashua-Binding-PHP. For examples in other programming languages, see https://github.com/BlueM/Pashua-Bindings.

Other related links:
* [Pashua homepage](https://www.bluem.net/jump/pashua)
* [Pashua repository on GitHub](https://github.com/BlueM/Pashua)

Usage
======
This repository contains two source code files:

* “example.php” is an example, which does not do much more than define how the dialog window should look like and use the class in the second file.
* “Pashua.php” contains a (namespaced) class `BlueM\Pashua`, which declares two static methods. Usually you will only need `showDialog()`, but if you need to find out where Pashua is, you can also use `getPashuaPath()`. You can put “Pashua.php” anywhere you like, and if the place is in your include path, you can be sure you just need a `require` statement to use it. Of course, you can also just take the methods out of the class and use them inlined as functions in your code.

Of course, you will need Pashua on your Mac to run the example. The code expects Pashua.app in one of the “typical” locations, such as the global or the user’s “Applications” folder, or in the folder which contains “example.php”, but will prefer a Pashua version in `/Volumes/Pashua` (which is the mounted path of the Pashua distribution disk image).


Compatibility
=============
This code requires PHP 5.3 or newer and should run with the default PHP installation that ships with Mac OS X 10.6 or later.

It is compatible with Pashua 0.10. It will work with earlier versions of Pashua, but non-ASCII characters will not be displayed correctly, as any versions before 0.10 required an argument for marking input as UTF-8.


Author
=========
This code was written by Carsten Blüm.


License
=========
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

