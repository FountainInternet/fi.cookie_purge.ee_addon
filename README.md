# FI Cookie Purge - Destroys [ExpressionEngine][] cookies unless the user has indicated their acceptance to receive them. It helps ExpressionEngine sites to comply with the The Privacy and Electronic Communications (EC Directive) Regulations 2003

**Author**: [Simon Jones][]
**Source**: [Github][]
**Licence**: [Creative Commons Attribution-Share Alike 3.0 Unported Licence][]

## Compatibility

* ExpressionEngine Version 1.x
* PHP 5.x

## Licence

FI Cookie Purge is free for personal and commercial use, and is licensed under the [Creative Commons Attribution-Share Alike 3.0 Unported Licence][].

## Installation

1. Place the file ext.fi\_cookie\_purge.php in the /system/extensions/ folder of your ExpressionEngine installation
2. Place the file lang.fi\_cookie\_purge.php in the /system/language/english/ folder
3. Go to the **Extensions Manager** (Admin › Utilities › Extensions Manager) and enable the **FI Cookie Purge** extension
4. In the Extensions Manager, click Settings for the FI Cookie Purge extension. There are two settings: the first setting contains the cookie names that you want to purge if the "accept" cookie isn't set, and the other contains the names of any "accept" cookies. By default, the first setting contains the names of the three standard ExpressionEngine cookies that are normally set for anonymous users:

    exp_last_visit
    exp_last_activity
    exp_tracker

The second setting contains the name of an example "accept" cookie:

    exp_accept_cookies

## Name

FI Cookie Purge

## Synopsis

Destroys cookies set by ExpressionEngine

## Description

This extension destroys specified cookies that are automatically set by ExpressionEngine, unless the user has indicated their acceptance to receive them. It helps ExpressionEngine sites to comply with the The Privacy and Electronic Communications (EC Directive) Regulations 2003.

After installing the extension using the default settings, anonymous users will find that the three standard ExpressionEngine cookies are deleted after they have been set by ExpressionEngine. If you are logged into ExpressionEngine, other ExpressionEngine cookies will remain on your computer.

## Display of Copyright Notices

All copyright notices and logos in the Control Panel and within the Software files must remain intact.

## Disclaimer Of Warranty

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF QUALITY, PERFORMANCE, NON-INFRINGEMENT, MERCHANTABILITY, OR FITNESS FOR A PARTICULAR PURPOSE. FURTHER, NEITHER SIMON JONES NOR FOUNTAIN INTERNET MARKETING WARRANT THAT THE SOFTWARE OR ANY RELATED SERVICE WILL ALWAYS BE AVAILABLE.

## Limitations Of Liability

YOU ASSUME ALL RISK ASSOCIATED WITH THE INSTALLATION AND USE OF THE SOFTWARE. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS OF THE SOFTWARE BE LIABLE FOR CLAIMS, DAMAGES OR OTHER LIABILITY ARISING FROM, OUT OF, OR IN CONNECTION WITH THE SOFTWARE. LICENCE HOLDERS ARE SOLELY RESPONSIBLE FOR DETERMINING THE APPROPRIATENESS OF USE AND ASSUME ALL RISKS ASSOCIATED WITH ITS USE, INCLUDING BUT NOT LIMITED TO THE RISKS OF PROGRAM ERRORS, DAMAGE TO EQUIPMENT, LOSS OF DATA OR SOFTWARE PROGRAMS, OR UNAVAILABILITY OR INTERRUPTION OF OPERATIONS.

[Simon Jones]: http://www.fountaininternet.co.uk/
[Github]: https://github.com/FountainInternet/fi.cookie_purge.ee_addon
[ExpressionEngine]: http://www.expressionengine.com/
[Creative Commons Attribution-Share Alike 3.0 Unported Licence]: http://creativecommons.org/licenses/by-sa/3.0/