<?php
/**
 * Spanish language file for Lightbox Album module
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PhpGedView developers
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
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 * @translator: Julio Sánchez Fernández
 */

//-- security check, only allow access from module.php
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Usted no puede acceder a este archivo de idioma directamente.";
	exit;
}

$pgv_lang["mediatabLegend"]				= "Media Tab Appearance";
$pgv_lang["mediatab_help"]				= "~#pgv_lang[mediatabLegend]#~<br />Esta opción le permite determinar si se muestra la pestaña de Objetos en la página #pgv_lang[indi_info]#.<br /><br />Si se fija esta opción a <b>#pgv_lang[hide]#</b>, sólo se muestra la perstaña <b>#pgv_lang[lightbox]#</b> y, además, aparecerá como <b>#pgv_lang[media]#</b>.<br />";
$pgv_lang["lb_al_head_linksLegend"]		= "Album Tab Header Link appearance";
$pgv_lang["lb_al_head_links_help"]		= "~#pgv_lang[lb_al_head_linksLegend]#~<br />Esta opción le permite determinar si el área de encabezamientos de la pestaña #pgv_lang[lightbox]#, que contiene vínculos para controlar diversos aspectos del módulo Lightbox, debe contener sólo iconos, sólo texto o ambos.<br /><br />La opción <b>#pgv_lang[lb_icon]#</b> probablemente no es muy útil, pues no verá ninguna indicación de la función de cada icono hasta que su puntero flote sobre el icono.<br />";
$pgv_lang["lb_al_thumb_linksLegend"]	= "Album Tab Thumbnails Link appearance";
$pgv_lang["lb_al_thumb_links_help"]		= "~#pgv_lang[lb_al_thumb_linksLegend]#~<br />Esta opción le permite determinar si los vínculos bajo cada miniatura deben mostrar un icono o texto.  Los vínculos que se muestran aquí le permiten modificar los detalles de un objeto o eliminarlo.<br />";
$pgv_lang["lb_ml_thumb_linksLegend"]	= "Thumbnails Link appearance";
$pgv_lang["lb_ml_thumb_links_help"]		= "~#pgv_lang[lb_ml_thumb_linksLegend]#~<br />Esta opción le permite determinar si el área de vínculos sobre los detalles del objeto audiovisual debe contener sólo iconos, sólo texto o ambos.  Los vínculos que se muestran aquí le permiten realizar varias acciones de modificación sobre el objeto en cuestión.<br /><br />La opción <b>#pgv_lang[lb_none]#</b> oculta completamente estos vínculos y, por tanto, actúa como si el usuario no tuviera privilegios de modificación.<br />";
$pgv_lang["lb_ss_speedLegend"]			= "Slide Show speed";
$pgv_lang["lb_ss_speed_help"]			= "~#pgv_lang[lb_ss_speedLegend]#~<br />Esta opción determina cuánto tiempo se mostrará cada imagen en la presentación antes de pasar a la siguiente imagen.<br />";
$pgv_lang["lb_music_fileLegend"]		= "Slideshow Sound Track";
$pgv_lang["lb_music_file_help"]			= "~#pgv_lang[lb_music_fileLegend]#~<br />Esta opción le permite especificar una banda sonora a reproducir cada vez que se active la presentación.  Si deja este campo en blanco, no se reproducirá ningún sonido durante la presentación.<br /><br />Esta característica sólo admite archivos en formato mp3.<br />";
// $pgv_lang["lb_transitionLegend"]		= "Image Transition speed";
// $pgv_lang["lb_transition_help"]			= "~#pgv_lang[lb_transitionLegend]#~<br />This option lets you specify the transition speed when the image changes.  This selection is applied during the slideshow.  It is also applied when you move to the next or previous image when the slideshow is not running.<br /><br />The <b>#pgv_lang[lb_none]#</b> option eliminates image transitions so that the new image immediately replaces the old without visible adjustment of the new image's dimensions.<br />";
// $pgv_lang["lb_url_dimensionsLegend"]	= "Lightbox URL Window dimensions"; 
// $pgv_lang["lb_url_dimensions_help"]		= "~#pgv_lang[lb_url_dimensionsLegend]#~<br />When clicking on a URL image thumbnail, this option lets you specify the Lightbox URL Window dimensions in pixels.<br /><br />This should normally be less than your current browser window dimensions, and certainly less than your screen resolution.<br />";

?>
