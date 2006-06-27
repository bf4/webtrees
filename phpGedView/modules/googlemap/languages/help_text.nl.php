<?php
/**
 * Dutch Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  PGV Development Team
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
  print "You cannot access a language file directly.";
  exit;
}

$pgv_lang["GOOGLEMAP_API_KEY"]      = "Google-map API code";
$pgv_lang["GOOGLEMAP_API_KEY_help"] = "~#pgv_lang[GOOGLEMAP_API_KEY]#~<br /><br />Voer hier uw Google Map API code in. U kunt een code aanvragen op <a target=\"_blank\" href=\"http://www.google.com/apis/maps/\">http://www.google.com/apis/maps/</a>";

$pgv_lang["GOOGLEMAP_MAP_TYPE"]         = "Google-map type";
$pgv_lang["GOOGLEMAP_MAP_TYPE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_TYPE]#~<br /><br />Het type kaart dat standaard word getoond. Dit kan zijn Kaart, Satelliet of Combinatie";

$pgv_lang["GOOGLEMAP_MAP_SIZE"]         = "Google-map afmeting";
$pgv_lang["GOOGLEMAP_MAP_SIZE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_SIZE]#~<br /><br />De afmeting van de kaart (in pixels) zoals deze getoond word op de pagina met Persoonsinformatie";

$pgv_lang["GOOGLEMAP_MAP_ZOOM"]         = "Google-map zoom factor";
$pgv_lang["GOOGLEMAP_MAP_ZOOM_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_ZOOM]#~<br /><br />Minimum en maximum zoom factor van de Google map. 1 is de gehele aarde, 15 is enkele huizen. Houd er rekeing mee dat factor 15 niet overal beschikbaar is.";

$pgv_lang["PL_EDIT_LOCATION"]           = "Wijzig of verwijder locatie";
$pgv_lang["PL_EDIT_LOCATION_help"]      = "Hier kunnen de gegevens van een locatie worden aangepast of kan een locatie worden verwijderd. Vie de Wijzig-link wordt een nieuw venster geopend, waarin de waardes van de locatie kunnen worden aangepast.<br>Via het ikoon \"Verwijderen\" kan de betreffende locatie worden verwijderd. Dit is echter alleen mogelijk indien er geen locaties afhankelijk zijn van deze locatie. Indien een locatie verwijderd kan worden is deze actief, anders is het ikoon inactief.";

$pgv_lang["PL_ADD_LOCATION"]            = "Locatie toevoegen";
$pgv_lang["PL_ADD_LOCATION_help"]       = "Gebruik deze link om een locatie toe te voegen aan de tabel.";

$pgv_lang["PL_IMPORT_GEDCOM"]           = "Importeer data uit GEDCOM";
$pgv_lang["PL_IMPORT_GEDCOM_help"]      = "Via deze link worden alle locaties uit de huidige GEDCOM ingelezen in de tabel. De huidge GEDCOM wordt ingelezen en alle locaties (in PLAC records) worden toegevoegd aan de tabel. Van locaties waar de lengte en breedtegraad beschikbaar is worden deze ook overgenomen.";

$pgv_lang["PL_IMPORT_ALL_GEDCOM"]       = "Importeer data uit alle GEDCOM-bestanden";
$pgv_lang["PL_IMPORT_ALL_GEDCOM_help"]  = "Via deze link worden alle locaties uit alle GEDCOM-bestanden ingelezen in de tabel. Alle GEDCOM-bestanden wordt ingelezen en alle locaties (in PLAC records) worden toegevoegd aan de tabel. Van locaties waar de lengte en breedtegraad beschikbaar is worden deze ook overgenomen.";

$pgv_lang["PL_IMPORT_FILE"]             = "Importeer data uit file";
$pgv_lang["PL_IMPORT_FILE_help"]        = "Importeer gegevens uit een file. De file moet als CSV file zijn opgeslagen op de lokale computer. Het gebruikte scheidingsteken tussen de velden is een ';'.";

$pgv_lang["PL_EXPORT_FILE"]             = "Exporteer data naar file";
$pgv_lang["PL_EXPORT_FILE_help"]        = "Schrijf de gegevens naar een file en download deze naar de lokale computer. De gegevens welke in de file geschreven worden zijn de gegevens van de huidige tabel, en alle onderliggende gegevens. Als er bijvoorbeeld een land is geselecteerd, dan worden nu de provicies getoond. Via deze optie zullen de gegevens van dit provincie, van de gemeentes en de plaatsen worden opgeslagen.";

$pgv_lang["PL_EXPORT_ALL_FILE"]         = "Exporteer alle data naar file";
$pgv_lang["PL_EXPORT_ALL_FILE_help"]    = "Schrijf alle gegevens naar een file en download deze naar de lokale computer. Via deze optie is het mogelijk om alle locatie gegevens op te slaan op de lokale computer.";

// Help texts for places_edit.php

$pgv_lang["PLE_PLACES"]                 = "Voer plaatsnaam in";
$pgv_lang["PLE_PLACES_help"]            = "Hier kan een plaatsnaam worden ingevoerd of gewijzigd.";

$pgv_lang["PLE_PRECISION"]              = "Voer precisie in";
$pgv_lang["PLE_PRECISION_help"]         = "Hier kan de precisie worden ingevoerd. Deze waarde wordt gebruikt om het aantal cijfers te bepalen wat gebruikt wordt voor de lengtebraad en breedtegraad.";

$pgv_lang["PLE_LATLON_CTRL"]            = "Voer lengte en breedtegraad in";
$pgv_lang["PLE_LATLON_CTRL_help"]       = "Hier kan de lengte en breedtegraad worden ingevoerd. Selecteer eerst het kwadrant (N/Z en O/W), daarna kan de waarde voor lengte en breedtegraad worden ingevoerd. Deze waarde moet decimaal worden ingevoerd.<br>De decimale waarde kan worden bepaald door de minuten en seconden om te zetten volgend de volgende formule:<br>decimale_waarde = ((seconden / 60) _ minuten ) / 60 + geheel aantal graden.";

$pgv_lang["PLE_ZOOM"]                   = "Voer zoom-factor in";
$pgv_lang["PLE_ZOOM_help"]              = "Hier kan de zoom-factor worden ingevoerd. Met deze waarde wordt de maximale zoomfactor bepaald als deze locatie op een kaart wordt getoond.";

$pgv_lang["PLE_ICON"]                   = "Selecteer vlag";
$pgv_lang["PLE_ICON_help"]              = "Hier kan een vlag worden geselcteerd. Deze vlag zal worden getoond in plaats van het standaard symbool.";

$pgv_lang["PLE_FLAGS"]                  = "Selecteer vlag";
$pgv_lang["PLE_FLAGS_help"]             = "Via het pulldown menu kan een land worden geselcteerd, waarvan daarna een vlag kan worden geselecteerd. Indien geen vlaggen worden getoond, zijn er voor dit land geen vlaggen gedefinieerd.";

?>
