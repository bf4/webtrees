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
 * @subpackage Admin
 * @version $Id: config.php$
 */

// Key as provided by Google
$GOOGLEMAP_API_KEY  = "<Fill in your own key>";

$GOOGLEMAP_MAP_TYPE = "G_HYBRID_MAP";   // possible values: G_NORMAL_MAP, G_SATELLITE_MAP or G_HYBRID_MAP.

$GOOGLEMAP_MIN_ZOON = "2";              // min zoom level
$GOOGLEMAP_MAX_ZOON = "14";             // max zoom level

$GOOGLEMAP_XSIZE    = "600";            // X-size of Google map
$GOOGLEMAP_YSIZE    = "400";            // Y-size of Google map

?>
