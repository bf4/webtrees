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
  print "Du kan ikke tilgang til en språkfil direkte.";
  exit;
}

$pgv_lang["GOOGLEMAP_API_KEY"]          = "Google kart - API-nøkkel";
$pgv_lang["GOOGLEMAP_API_KEY_help"]     = "~#pgv_lang[GOOGLEMAP_API_KEY]#~<br /><br />Oppgi nøkkelen din til Google Map API her.  Dersom du ikke har en nøkkel, kan du be om å få en på nettsiden til <a target=\"_blank\" href=\"http://www.google.com/apis/maps/\">Google kart</a>";

$pgv_lang["GOOGLEMAP_MAP_TYPE"]	        = "Google kart - type";
$pgv_lang["GOOGLEMAP_MAP_TYPE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_TYPE]#~<br /><br />Type kart som vil bli vist som standard.  Valgene er Kart, Satelitt eller Kombinert.";

$pgv_lang["GOOGLEMAP_MAP_SIZE"]	        = "Google kart - størrelse";
$pgv_lang["GOOGLEMAP_MAP_SIZE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_SIZE]#~<br /><br />Størrelsen på kartet (i punkter) som vil vises på siden med opplysninger for en person.";

$pgv_lang["GOOGLEMAP_MAP_ZOOM"]	        = "Google kart - zoom-faktor";
$pgv_lang["GOOGLEMAP_MAP_ZOOM_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_ZOOM]#~<br /><br />Her angir du hvilken forstørrelse kortet skal vise med.<br />1 viser hele jordkloden<br />15 viser enkelte hus<br /><br />NB! Det er ikke alle områder som har tilgjengelig kart som kan vise forstørrelse på 15.";

$pgv_lang["GOOGLEMAP_PRECISION"]        = "Nøyaktighet for lengde- og breddegrad";
$pgv_lang["GOOGLEMAP_PRECISION_help"]   = "~#pgv_lang[GOOGLEMAP_PRECISION]#~<br /><br />Dette angir hvor nøyaktig de forskjellige nivåene skal benevnes når en registerer nye steder. For eksempel vil et land bli angitt med nøyaktighet 0 (dvs. 0 siffer etter desimaltegnet), mens en by trenger 3 eller 4 siffer.";

$pgv_lang["PL_EDIT_LOCATION"]           = "Redigere eller slette et sted";
$pgv_lang["PL_EDIT_LOCATION_help"]      = "Her kan du enten:<br><dl><dt>Redigere</dt><dd>Du vil få opp et nytt vindu der du kan endre verdiene til stedet.</dd><dt>Slette</dt><dd>Stedet vil bli slettet.<br>NB! Dette kan bare gjøres dersom det ikke er registrert andre steder \"under\" dette stedet (f.eks. kommuner i et fylke). Velget vil være deaktivert hvis dette er tilfelle.";

$pgv_lang["PL_ADD_LOCATION"]            = "Legg til sted";
$pgv_lang["PL_ADD_LOCATION_help"]       = "Bruk dette valget for å legge til et nytt sted. Stedet vil bli lagt til på dette nivået.";

$pgv_lang["PL_IMPORT_GEDCOM"]           = "Importer steder fra slektsfil (GED)";
$pgv_lang["PL_IMPORT_GEDCOM_help"]      = "Her kan du importere alle stedene fra den aktive slektsbasen. Dersom bredde-og lengdegrad er tilgjengelig, vil disse også bli importert.";

$pgv_lang["PL_IMPORT_ALL_GEDCOM"]       = "Importer steder fra alle slektsfiler (GED)";
$pgv_lang["PL_IMPORT_ALL_GEDCOM_help"]  = "Her kan du importere alle stedene fra alle tilgjengelige slektsbaser. Dersom bredde-og lengdegrad er tilgjengelig, vil disse også bli importert.";

$pgv_lang["PL_IMPORT_FILE"]             = "Importer steder fra fil";
$pgv_lang["PL_IMPORT_FILE_help"]        = "Her kan du importere stedsdata fra en tekstfil. Filen bør være formatert som en CSV-fil (feltene på en stedslinje er separert med ';') på datamaskinen din..";

$pgv_lang["PL_EXPORT_FILE"]             = "Eksportere steder til fil";
$pgv_lang["PL_EXPORT_FILE_help"]        = "Her kan du eksportere stedsdata til en fil. Dette valget vil lagre opplysninger fra det viste stedet i en fil (CSV-format). Det vil for eksempel bety at dersom et land er valgt og fylkene vises, vil dette valget lagre dataene til alle fylkene, alle kommuner og alle stedene som er registert tilhørende dette landet, og overføre dem til din lokale datamaskin.";

$pgv_lang["PL_EXPORT_ALL_FILE"]         = "Eksporter alla steder til fil";
$pgv_lang["PL_EXPORT_ALL_FILE_help"]    = "Her kan du eksportere alle stedsdata til en fil (CSV-format). Dette valget vil lagre alle stedsdata og overføre dem til din lokale datamaskin.";

// Help texts for places_edit.php

$pgv_lang["PLE_PLACES"]                 = "Angi stedsnavn";
$pgv_lang["PLE_PLACES_help"]            = "Her kan du angi eller endre navnet til stedet.";

$pgv_lang["PLE_PRECISION"]              = "Angi nøyaktighet";
$pgv_lang["PLE_PRECISION_help"]         = "Her kan du angi hvor nøyaktig et sted skal benevnes. Basert på denne innstillingen, vil dette bestemme hvor mange siffer som skal brukes i lengde- og breddegradene for stedet.";

$pgv_lang["PLE_LATLON_CTRL"]            = "Angi lengde- og breddegrad";
$pgv_lang["PLE_LATLON_CTRL_help"]       = "Her kan du angi lengde- og breddegraden til stedet. Først velger du området du ønsker å angi (E/W eller N/S). Deretter angir du verdien for lengde- eller breddegraden. Dett bør være en desimalverdi.<br>Desimalverdien kan bestemmes ved å konvertere minuttene og sekundene ved å bruke følgende format:<br>degrees_decimal = ((sekunder / 60) + minutter) / 60 + grader.";

$pgv_lang["PLE_ZOOM"]                   = "Angi nivå for zoom";
$pgv_lang["PLE_ZOOM_help"]              = "Her angir du ønsket nivå for zoom av kartutsnitt. Denne verdien vil bli brukt som minimumsverdi når stedet blir vist på et kart.";

$pgv_lang["PLE_ICON"]                   = "Velg et ikon";
$pgv_lang["PLE_ICON_help"]              = "Her kan du angi hva slags markering som skal brukes for å angi stedet. Ikonet kan også være et flagg.";

$pgv_lang["PLE_FLAGS"]                  = "Velg flagg";
$pgv_lang["PLE_FLAGS_help"]             = "Her kan du velge et land hvor flagget skal brukes. Dersom ingen flagg blir vist, er det ikke bestemt noe flagg for dette landet.";

?>
