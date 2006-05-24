INSTALLATION
============

1. Move the files from this archive to the modules directory in the PGV root
2. Get your personal Google Map API key from
   http://www.google.com/apis/maps/signup.html
3. Put this key in the config.php file in modules/googlemap.
4. Backup your individual.php in the PGV root
5. Copy individual-3.3.8.php or individual-4.0b8.php from the googlemap
   directory to individual.php in your PGV root, depending on the version you
   are running.
6. Rename config1.php in the modules/googlemap directory to config.php. (This
   is needed to protect the original config.php for people who already installed
   this module)

Configuration of the Googlemap module can be done through the Googlemap
configuration page. When logged into PGV as administrator you will see a
"Manage"-link just below the map. Click on this link and the Configuration page
will be shown. Here you can enter your API-key and do some more settings.

The map will only be shown if at least one fact has a PLAC with coordinates
attached to it. The coordinates cannot yet be entered through the normal
windows. You have to add it directly into the GEDCOM record. The correct
way to do this is:
2 PLAC <Placename>
3 MAP
4 LONG <Longitude>
4 LATI <Latitude>

The MAP, LONG and LATI lines should be added directly after the PLAC line.


Changelog
=========

Version 0.4:
- Improved privacy handling
- German language file
- Configuration using Googlemap configuration page
- Accept LATI/LONG in both N/S/E/W and +/- notation

Version 0.3:
- If more than 4 events at one place, then create new marker
- Fixed including of language files
- Added support for Version 4.0 Beta 8
- Select correct tab in info-window when using the event-table

