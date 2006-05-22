===================================================================================================
INTRODUCTION

This is a simplistic interface for PostNuke and  phpNuke (Xoops has been removed) for phpGedView. 
It is very basic but does the job of allowing you to call the phpGedView module and pass over the 
username (if logged in) to phpGedView. If logged in you will also be logged in at phpGedView. If 
you aren't defined in phpGedView then the program will create you as a user in there. If you 
aren't logged in then you will simply be passed over in a non logged in state as a guest to 
phpGedView

As part of the interface call it picks up as series of default setings for any new users from a 
file caled post-config.php - so remember to review this and update to your defaults

Note - phpGedView must be up and running in a directory on your site before interfacing with the 
phpnuke or postnuke system - see Installation below


===================================================================================================
INSTALLATION

Create a phpGedView directory in your PostNuke/phpNuke modules directory and upload the 
postwrap.js, pgvindex.php and post-config.php to this directory. If you are using a separate 
directory for the phpGedView code then you can rename pgvindex.php to index.php to make it easy to 
call from phpNuke or postNuke.

Edit the post-config.php defaults to make sure that new users coming from the Nuke application
are treated the way you want in PhpGedView.  See the POST-CONFIG.PHP section below for details
about this file.

Upload postgedview.php into the phpGedView main code directory (which may be the same as the rest)

Now make sure that phpGedView works by calling it like:
http://carey.id.au/phpGedView/index.php  (or wherever you have uploaded the phpGedView code to)

Make sure that you have uploaded your gedcom, created your admin user etc.

If you are using PostNuke then use the following steps.  Otherwise skip to the phpNuke section 
below.

---------------------------------------------------------------------------------------------------
PostNuke

Go to postnuke administration and then to modules.
Regenerate the list
Initialise and then activate phpGedView (or whatever you have called phpGedView) - note that the 
admin function doesn't work for phpGedView - so expect errors if you click on it (this is a future 
change that I intend to make).

Build a link somewhere on your web site for phpGedView - similar to the one below - note that the 
module it calls is pgvindex.php. I add mine to the Main Menu

http://carey.id.au/modules.php?op=modload&name=phpGedView&file=pgvindex

If you have separated the phpGedView code from the interface code and renamed pgvindex.php to 
index.php then you can use the interface by specifying [phpGedView] in the blocks admin


---------------------------------------------------------------------------------------------------
phpNuke

Important:  If you see that you have compressed output - will occur in releases prior to 7.3 of 
phpNuke then upload pgv_footer.php to the base phpNuke directory, rename the existing footer.php 
to footer-bak.php and then rename pgv_footer.php to footer.php.  This won't be necessary in 
release 7.3 pf phpNuke

Go to phpnuke administration (ie admin.php) and then click on modules.

Activate phpGedView (or whatever you have called phpGedView)

Build a link somewhere for phpGedView - similar to the one below as a custom block

http://carey.id.au/modules.php?op=modload&name=phpGedView&file=pgvindex

If you have separated the phpGedView code from the interface code and renamed pgvindex.php to 
index.php then you can use the interface by specifying that the module should be visible in the 
modules block in the Modules part of admin.php

If you visit http://carey.id.au/phpnuke then you will see that it is shown in both forms


===================================================================================================
POST-CONFIG.PHP CONFIGURATION FILE

This sets default information for the interface.

If you dont want new users to be created - ie only login if they are already there - you can 
specify that. If you want new users to be created but have to be verified by admin then you can 
specify that - etc.

Make sure you specify where the phpGedView code is stored (if you leave that blank then it assumes 
that all is in the same directory - ie modules/phpGedView for example

NOTE: no admin funtion under PostNuke - so if you click on the phpGedView admin icon when in admin 
mode you will get module not found - that is a futures activity.

NOTE2 - again - make sure that you have a working version of phpGedView in phpGedView directory 
before setting all this up by calling phpGedView directly - make sure admin user is setup and 
GEDCOM uploaded

NOTE3 - see NOTE2

Futures:
pnAdmin module for postNuke and admin functions for phpNuke to setup the post-config.php file as 
part of initialisation and ongoing maintenance

any ideas ?


good luck - hope others find it useful and make it better - coz I am a PHP and PostNuke hacker not 
a guru

Jim Carey
jim@carey.id.au

===================================================================================================
How it Works

You create a link (I do it in Main Menu) somewhere in php/PostNuke to the phpGedView module like:
 http://carey.id.au/modules.php?op=modload&name=phpGedView&file=pgvindex
 
 (see below for an alternative)
 
This calls the pgvindex.php in the phpGedView module directory which picks up user info from 
php/postNuke and that then passes control to postgedview.php after setting relevant cookies with 
data from php/PostNuke. Postgedview.php calls getUser which checks if the user is defined to 
phpgedview. If not it creates a user using addUser (if the post-config.php says that it is ok to 
do so)  using information passed over plus the defaults in post-config.php. 

There are no mods to phpGedView at all - hooray.

This works for both mysql and index versions so far :-)
===================================================================================================