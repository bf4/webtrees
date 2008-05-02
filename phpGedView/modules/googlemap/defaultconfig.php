<?php
/**
 * Configuration file required by Googlemap module
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2003  John Finlay and Others
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package PhpGedView
 * @subpackage Googlemap
 * @version $Id: defaultconfig.php$
 * $Id: defaultconfig.php 2666 2008-04-23 21:38:56Z wooc$
 */

$GOOGLEMAP_ENABLED = "false";          // Enable or disable Googlemap

$GOOGLEMAP_API_KEY = "Fill in your key here. Request key from http://www.google.com/apis/maps/";

$GOOGLEMAP_MAP_TYPE = "G_NORMAL_MAP";  // possible values: G_PHYSICAL_MAP, G_NORMAL_MAP, G_SATELLITE_MAP or G_HYBRID_MAP.

$GOOGLEMAP_MIN_ZOOM = "2";              // min zoom level
$GOOGLEMAP_MAX_ZOOM = "13";             // max zoom level

$GOOGLEMAP_XSIZE = "600";               // X-size of Google map
$GOOGLEMAP_YSIZE = "400";               // Y-size of Google map

$GOOGLEMAP_PRECISION_0 = "0";           // Country level
$GOOGLEMAP_PRECISION_1 = "1";           // State level
$GOOGLEMAP_PRECISION_2 = "2";           // City level
$GOOGLEMAP_PRECISION_3 = "3";           // Neighborhood level
$GOOGLEMAP_PRECISION_4 = "4";           // House level
$GOOGLEMAP_PRECISION_5 = "9";           // Max prcision level

$GM_MAX_NOF_LEVELS = "4";               // Max nr of levels to use in Googlemap
$GM_DEFAULT_TOP_VALUE = "";             // Default value, inserted when no location can be found

$GOOGLEMAP_COORD = "false";          	// Enable or disable Display Map Co-ordinates

//Place Hierarchy - wooc
$GOOGLEMAP_PLACE_HIERARCHY = "true";	// Enable or disable Display Map in place herarchy
$GOOGLEMAP_PH_XSIZE = "500";			// X-size of Place Hierarchy Google map
$GOOGLEMAP_PH_YSIZE = "350";			// Y-size of Place Hierarchy Google map
$GOOGLEMAP_PH_MARKER = "G_FLAG";		// Type of marker to be used in place herarchy, possible values: G_FLAG = Flag, G_DEFAULT_ICON = Standard icon
$GM_DISP_SHORT_PLACE = "false";			// Display full place name or only the actual level name
$GM_DISP_COUNT = "false";				// Display the count of individuals and families connected to the place
$GOOGLEMAP_PH_WHEEL = "false";			// Use mouse wheel for zooming


// Configuration-options per location-level
$GM_MARKER_COLOR[1] = "Red";            // Marker to be used
$GM_MARKER_SIZE[1] = "Large";           // "Small" or "Large"
$GM_PREFIX[1] = "";                     // Text to be placed in front of the name
$GM_POSTFIX[1] = "";                    // Text to be placed after the name
$GM_PRE_POST_MODE[1] = "0";             // Prefix/Postfix mode. Possible value are:
                                        // 0 = no pre/postfix
                                        // 1 = Normal name, Prefix, Postfix, Both
                                        // 2 = Normal name, Postfix, Prefxi, Both
                                        // 3 = Prefix, Postfix, Both, Normal name
                                        // 4 = Postfix, Prefix, Both, Normal name
                                        // 5 = Prefix, Postfix, Normal name, Both
                                        // 6 = Postfix, Prefix, Normal name, Both

$GM_MARKER_COLOR[2] = "Red";            // Marker to be used
$GM_MARKER_SIZE[2] = "Large";           // "Small" or "Large"
$GM_PREFIX[2] = "";                     // Text to be placed in front of the name
$GM_POSTFIX[2] = "";                    // Text to be placed after the name
$GM_PRE_POST_MODE[2] = "0";             // See above for description

$GM_MARKER_COLOR[3] = "Red";            // Marker to be used
$GM_MARKER_SIZE[3] = "Large";           // "Small" or "Large"
$GM_PREFIX[3] = "";                     // Text to be placed in front of the name
$GM_POSTFIX[3] = "";                    // Text to be placed after the name
$GM_PRE_POST_MODE[3] = "0";             // See above for description

$GM_MARKER_COLOR[4] = "Red";            // Marker to be used
$GM_MARKER_SIZE[4] = "Large";           // "Small" or "Large"
$GM_PREFIX[4] = "";                     // Text to be placed in front of the name
$GM_POSTFIX[4] = "";                    // Text to be placed after the name
$GM_PRE_POST_MODE[4] = "0";             // See above for description

$GM_MARKER_COLOR[5] = "Red";            // Marker to be used
$GM_MARKER_SIZE[5] = "Large";           // "Small" or "Large"
$GM_PREFIX[5] = "";                     // Text to be placed in front of the name
$GM_POSTFIX[5] = "";                    // Text to be placed after the name
$GM_PRE_POST_MODE[5] = "0";             // See above for description

$GM_MARKER_COLOR[6] = "Red";            // Marker to be used
$GM_MARKER_SIZE[6] = "Large";           // "Small" or "Large"
$GM_PREFIX[6] = "";                     // Text to be placed in front of the name
$GM_POSTFIX[6] = "";                    // Text to be placed after the name
$GM_PRE_POST_MODE[6] = "0";             // See above for description

$GM_MARKER_COLOR[7] = "Red";            // Marker to be used
$GM_MARKER_SIZE[7] = "Large";           // "Small" or "Large"
$GM_PREFIX[7] = "";                     // Text to be placed in front of the name
$GM_POSTFIX[7] = "";                    // Text to be placed after the name
$GM_PRE_POST_MODE[7] = "0";             // See above for description

$GM_MARKER_COLOR[8] = "Red";            // Marker to be used
$GM_MARKER_SIZE[8] = "Large";           // "Small" or "Large"
$GM_PREFIX[8] = "";                     // Text to be placed in front of the name
$GM_POSTFIX[8] = "";                    // Text to be placed after the name
$GM_PRE_POST_MODE[8] = "0";             // See above for description

$GM_MARKER_COLOR[9] = "Red";            // Marker to be used
$GM_MARKER_SIZE[9] = "Large";           // "Small" or "Large"
$GM_PREFIX[9] = "";                     // Text to be placed in front of the name
$GM_POSTFIX[9] = "";                    // Text to be placed after the name
$GM_PRE_POST_MODE[9] = "0";             // See above for description

?>
