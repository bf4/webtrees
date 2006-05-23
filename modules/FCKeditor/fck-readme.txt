The FCKeditor module allows PGV to iuse the FCKeditor for adding and editing news items for the home page. 

This module has been tested with the FCKeditor 2.0 on August 7th, 2005.  The code in PGV required to use this is only in PGV 3.4 (and 3.3.6 if there will be a release > 3.3.5) and greater.

To install this module, download FCKeditor from the official
website (see below) and upload it to the modules/FCKeditor
directory. make sure that the directory structure is:
PhpGedView directory\
----modules\
--------FCKeditor\
------------editor\....
------------fckeditor.js
------------......

As long as this is present, PGV will use this as the default editor as opposed to a plain textarea.

The FCKeditor homepage is at:
http://www.fckeditor.net/default.html
Sourceforge Project page is at:
http://sourceforge.net/projects/fckeditor/