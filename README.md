Joomla Downloader
================

Script downloads Joomla .zip package from official source and unzip it to the same directory as the script is.

Usage
-----

Upload joomlaDownloader.php to the folder on your server where you want to install Joomla CMS.

Then trigger the script by typing to the browser http://yourwebsite.com/joomlaDownloader.php. Don't forget to write right URL address.

How does it work
----------------

joomlaDownloader.php downloads zipped CMS Joomla 3.2.1 right from joomlacode.org. Communication is between two servers, so it takes few seconds.

When download is successfull, script unzips the package. When all files are ready, script deletes Joomla zip package and itself. 

Warning
-------

Please, check that joomlaDownloader.php is not there after it finishes it's work. It is security risk to have this script at live site.

Contribution
------------

This is very simple script. Let me know what troubles you had or what could be better. Write a comment at http://www.escope.cz/en/extensions/joomla-downloader. 

Pull requests are very welcome.
