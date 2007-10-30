README file for the Googlemap module for phpGedView

Versions supported: V4.0 (preferred) and V3.3.8.

The files in this archive should be extracted into you modules directory.

These are the instruction on getting your Googlemap interface to work:

If you are using version 3.3.8 of phpGedview you also need to do the
following:
1. Backup your individual.php in the PGV root
2. Copy individual-3.3.8.php from the googlemap directory to PGV root.

Next follow the following steps (both 3.3.8 and 4.0):
1. Get your personal Google Map API key from
   http://www.google.com/apis/maps/signup.html
2. Go to the Googlemap confiuration page. You can find this page under the
   Map-tab on the individual page.
3. Enter the key in the input field, and click save.

The map will only be shown if at least one fact has a place with coordinates
attached to it. Attaching a coordinate can be done through the generic
place-location interface (located at the Googlamp configuration page) or by
specifying a MAP record with an event.

The coordinates for an event cannot be entered through the normal
windows. You have to add it directly into the GEDCOM record. The correct
way to do this is for a PLAC record:
2 PLAC <Placename>
3 MAP
4 LONG <Longitude>
4 LATI <Latitude>
(Make sure you use the "3 MAP" record after a PLAC record.)
The MAP, LONG and LATI lines should be added directly after the PLAC line.

It is also possible to define a MAP record within a ADDR record, even though
this is not according to the standard (these records are created by Legacy).

Some special remarks for version 3.3.8:
1. The Place-location pages look a bit empty. This is because the styles used to 
   format the table are not defined in version 3.3.8. You can create these by
   doing the following:
   - In your themes/<selected theme> open style.css
   - Search for ".facts_label"
   - Copy the lines until the first "}"
   - Insert these lines after the "}" and change ".facts_label" to 
     ".descriptionbox"
   - Change the font-size to 12.
   - Repeat this for ".facts_value" (change ".factsvalue" to ".optionbox")
2. To make the flags work, copy the flags directory from images to places, or
   download the flags from version 4.0.


Contributers:
- Norwegian translation: Geir Eikland (eikland)
- German translation: Christian Helms (nolensvolens)
- French translation: Thierry Durand (tdurand)
- Hebrew translations: Meliza Amity (meliza)
- Portugese translation: Clovis Bombardelli (cb-BR)
- Countries and US-states CSV file: Glen Carreras (carrerasg)
- Testing and additional comments: Mike Elliott (Colored Pixels), Nigel
  Osborn (nigelo)

Changelog:
Version 0.5:
- Added extra table to store place-locations, included interface to add, edit
  and remove items from this table
- Norwegian language files
- Possibility to add flags to locations
- Process ADDR records

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
