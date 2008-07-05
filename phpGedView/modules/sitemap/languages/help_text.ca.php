<?php
/**
 * Catalan language file for PhpGedView
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team. All rights reserved.
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
 * @translator: Antoni Planas i Vilà
 * @package PhpGedView
 * @subpackage SiteMap
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "No podeu accedir directament a aquest arxiu d'idioma.";
	exit;
}

$pgv_lang["SITEMAP"]                = "Generar arxius del mapa del lloc";
$pgv_lang["SITEMAP_help"]           = "~#pgv_lang[SITEMAP]#~<br /><br />Mitjançant aquesta pàgina podeu generar un mapa del lloc que poden utilitzar els motors de recerca per optimitzar la seva indexació. Això es fa proporcionant un arxiu de vincles a totes les pàgines que vulgueu que mostri el motor de recerca.<br/>Aquesta utilitat generarà un arxiu 'sitemap' per cada GEDCOM i (si es genera més d'un arxiu sitemap), un arxiu 'siteindex'. Caldria que coloquessiu els arxius generats a l'arrel de la vostra instal·lació de phpGedView.<br/>En aquest moment, solament Google utiliza els arxius. Per més informació, mireu-vos aquesta pàgina (en anglès):<br/><a href=\"https://www.google.com/webmasters/sitemaps/docs/en/about.html\">About Google webmaster tools</a>";

$pgv_lang["SM_GEDCOM_SELECT"]       = "Seleccionar GEDCOMs";
$pgv_lang["SM_GEDCOM_SELECT_help"]  = "~#pgv_lang[SM_GEDCOM_SELECT]#~<br /><br />Seleccionar els GEDCOMs per als que vulgueu crear un arxiu sitemap. Seleccioneu-ne al menys un.<br/>Si seleccioneu la opció \"Ssense víncles a informació privada\", solament s'inclouran els vincles a les dades píblicament disponibles.";

$pgv_lang["SM_ITEM_SELECT"]         = "~#pgv_lang[SM_ITEM_SELECT]#~<br /><br />Seleccioneu els elements a posar a l'arxiu sitemap. Per a tots els elements seleccionats podeu donar-hi una prioritat. Aquesta prioritat és relativa a les altres prioritats de l'arxiu.<br/>També poder informar la periodicitat d'actualització. És la freqüència amb la que poden variar les dades d'aquells elements. Això pot influir el temps entre visites del robot del motor de recerca i, per tant, en el volum de tràfec generat pel lloc.";
$pgv_lang["SM_ITEM_SELECT_help"]    = "~#pgv_lang[SM_ITEM_SELECT]#~<br /><br />Seleccioneu els elements a posar a l'arxiu sitemap. Per a tots els elements seleccionats podeu donar-hi una prioritat. Aquesta prioritat és relativa a les altres prioritats de l'arxiu.<br/>També poder informar la periodicitat d'actualització. És la freqüència amb la que poden variar les dades d'aquells elements. Això pot influir el temps entre visites del robot del motor de recerca i, per tant, en el volum de tràfec generat pel lloc.";

?>
