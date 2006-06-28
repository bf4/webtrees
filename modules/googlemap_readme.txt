README file for the Googlemap module for phpGedView

Versions supported: V4.0 and V3.3.8.

1. Get your personal Google Map API key from http://www.google.com/apis/maps/signup.html
2. Put this key in the config.php file in modules/googlemap.

If you are using version 3.3.8 of phpGedview you also need to do the
following:
3. Backup your individual.php in the PGV root
4. Copy individual.php from the modules directory to PGV root.

The map will only be shown if at least one fact has a place with coordinates
attached to it. Attaching a coordinate can be done through the generic
place-location interface (located at the Googlamp configuration page) or by
specifying a MAP record with an event.
NOTE: The place-location interface only works with version 4.0 of phpGedView!

The coordinates for an event cannot yet be entered through the normal
windows. You have to add it directly into the GEDCOM record. The correct
way to do this is:
2 PLAC <Placename>
3 MAP
4 LONG <Longitude>
4 LATI <Latitude>
(Make sure you use the "3 MAP" record after a PLAC record. Only MAP records in
a PLAC record are recognised and used).

The MAP, LONG and LATI lines should be added directly after the PLAC line.

Contributers:
- Norwegian translation: Geir Eikland (eikland)
- German translation: Christian Helms (nolensvolens)
- Countries and US-states CSV file: Glen Carreras (carrerasg)

Changelog:
Version 0.5:
- Added extra table to store place-locations, included interface to add, edit
  and remove items from this table
- Norwegian language files
- Possibility to add flags to locations


Version 0.4:
- Improved privacy handling
- German language file
- Configuration using Googlemap configuration page
- Accept LATI/LONG in both N/S or E/W and +/- notation
- Max 4 events per marker, new marker will be created for other events


Version 0.3:
- If more than 4 events at one place, then create new marker
- Fixed including of language files
- Added support for Version 4.0 Beta 7
- Select correct tab in info-window when using the event-table
