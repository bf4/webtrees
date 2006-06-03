<?php
/**
 * Norwegian Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2006  PGV Development Team
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
 * @subpackage GoogleMap
 * @version $Id$
 */
if (preg_match("/help_text\...\.php$/", $_SERVER["SCRIPT_NAME"])>0) {
  print "Du kan ikke tilgang til en spr�kfil direkte.";
  exit;
}

$pgv_lang["GOOGLEMAP_API_KEY"]          = "Google kart - API-n�kkel";
$pgv_lang["GOOGLEMAP_API_KEY_help"]     = "~#pgv_lang[GOOGLEMAP_API_KEY]#~<br /><br />Oppgi n�kkelen din til Google Map API her.  Dersom du ikke har en n�kkel, kan du be om � f� en p� nettsiden til <a target=\"_blank\" href=\"http://www.google.com/apis/maps/\">Google kart</a>";

$pgv_lang["GOOGLEMAP_MAP_TYPE"]	        = "Google kart - type";
$pgv_lang["GOOGLEMAP_MAP_TYPE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_TYPE]#~<br /><br />Type kart som vil bli vist som standard.  Valgene er Kart, Satelitt eller Kombinert.";

$pgv_lang["GOOGLEMAP_MAP_SIZE"]	        = "Google kart - st�rrelse";
$pgv_lang["GOOGLEMAP_MAP_SIZE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_SIZE]#~<br /><br />St�rrelsen p� kartet (i punkter) som vil vises p� siden med opplysninger for en person.";

$pgv_lang["GOOGLEMAP_MAP_ZOOM"]	        = "Google kart - zoom-faktor";
$pgv_lang["GOOGLEMAP_MAP_ZOOM_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_ZOOM]#~<br /><br />Her angir du hvilken forst�rrelse kortet skal vise med.<br />1 viser hele jordkloden<br />15 viser enkelte hus<br /><br />NB! Det er ikke alle omr�der som har tilgjengelig kart som kan vise forst�rrelse p� 15.";

?>
