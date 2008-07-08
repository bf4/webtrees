<?php
/**
 * Spanish language file for PhpGedView
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 *
 * @author PGV Developers
 * @translator: Julio Sánchez Fernández
 * @package PhpGedView
 * @subpackage SiteMap
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Usted no puede acceder a este archivo de idioma directamente.";
	exit;
}

$pgv_lang["SITEMAP"]                = "Información del mapa del sitio";
$pgv_lang["SITEMAP_help"]           = "~#pgv_lang[SITEMAP]#~<br /><br />Mediante esta página puede generar un mapa del sitio. Un mapa del sitio pueden utilizarlo los motores de búsqueda para optimizar la indexación de su sitio.  Esto se hace proporcionando un archivo con vínculos a todas las páginas que quiera que muestre el motor de búsqueda.<br/>Esta herramienta generará un archivo sitemap por cada GEDCOM y (si se genera más de un archivo sitemap), un archivo siteindex. Los archivos generados deberían colocarse en la raíz de su instalación de phpGedView.<br/>En este momento, solamente Google utiliza los archivos. Para más información, vea esta página (en inglés):<br/><a href=\"https://www.google.com/webmasters/sitemaps/docs/en/about.html\">About Google webmaster tools</a>";

$pgv_lang["SM_GEDCOM_SELECT"]       = "Seleccionar GEDCOMs";
$pgv_lang["SM_GEDCOM_SELECT_help"]  = "~#pgv_lang[SM_GEDCOM_SELECT]#~<br /><br />Seleccionar los GEDCOMs para los que desea crear un archivo sitemap. Seleccione al menos uno.<br/>Si se selecciona la opción \"Sin vínculos a información privada\", sólo se incluirán los vínculos a los datos disponibles públicamente.";

$pgv_lang["SM_ITEM_SELECT"]         = "~#pgv_lang[SM_ITEM_SELECT]#~<br /><br />Seleccione los elementos a poner en el archivo sitemap. Para todos los elementos seleccionados se puede dar una prioridad. Esta prioridad es relativa a las otras prioridades del archivo.<br/>También se puede dar la frecuencia de actualización. Ésta es la frecuencia con la que podrían cambiar los datos en estos elementos. Esto puede influenciar el tiempo entre visitas del robot del motor de búsqueda y, por tanto, influenciar el volumen de tráfico generado por el sitio.";
$pgv_lang["SM_ITEM_SELECT_help"]    = "~#pgv_lang[SM_ITEM_SELECT]#~<br /><br />Seleccione los elementos a poner en el archivo sitemap. Para todos los elementos seleccionados se puede dar una prioridad. Esta prioridad es relativa a las otras prioridades del archivo.<br/>También se puede dar la frecuencia de actualización. Ésta es la frecuencia con la que podrían cambiar los datos en estos elementos. Esto puede influenciar el tiempo entre visitas del robot del motor de búsqueda y, por tanto, influenciar el volumen de tráfico generado por el sitio.";

?>
