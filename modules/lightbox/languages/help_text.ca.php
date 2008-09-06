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
 * @subpackage Lightbox
 * @version $Id$
 */

//-- security check, only allow access from module.php
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "No podeu accedir directament a aquest arxiu d'idioma.";
	exit;
}

$pgv_lang["mediatabLegend"]				= "Aspecte de la pestanya d'objectes";
$pgv_lang["mediatab_help"]				= "~#pgv_lang[mediatabLegend]#~<br />Aquesta opció us permet determinar si es mostra la pestaña d'Objectes a la pàgina #pgv_lang[indi_info]#.<br /><br />Si es deixa a <b>#pgv_lang[hide]#</b>, solament veureu la pestanya <b>#pgv_lang[lightbox]#</b> i, a més, sortirà com <b>#pgv_lang[media]#</b>.<br />";
$pgv_lang["lb_al_head_linksLegend"]		= "Aspecte de la pestanya de Capçalera de vincles de l'Àlbum";
$pgv_lang["lb_al_head_links_help"]		= "~#pgv_lang[lb_al_head_linksLegend]#~<br />Aquesta opció us permet determinar si l'àrea d'encapçalament de la pestanya #pgv_lang[lightbox]#, que conté vincles per controlar diversos aspectes del mòdul Projector d'Imatges, ha de contenir solament icones, solament text o ambdós.<br /><br />La opció <b>#pgv_lang[lb_icon]#</b> probablement no és gaire útil, dons no veureu cap indicació de la funció de cada icona fins que el vostre punter passi per sobre d'alguna.<br />";
$pgv_lang["lb_al_thumb_linksLegend"]	= "Aspecte de la pestanya de Miniatures de l'Àlbum";
$pgv_lang["lb_al_thumb_links_help"]		= "~#pgv_lang[lb_al_thumb_linksLegend]#~<br />Aquesta opció us permet determinar si els víncles de sota de cada miniatura han de mostrar una icona o text. Els vincles que es mostrin aquí us permeten modificar els detalls d'un objecte o eliminar-lo.<br />";
$pgv_lang["lb_ml_thumb_linksLegend"]	= "Aspecte dels Vincles de Miniatures";
$pgv_lang["lb_ml_thumb_links_help"]		= "~#pgv_lang[lb_ml_thumb_linksLegend]#~<br />Aquesta opció us permet determinar si l'àrea de víncles sobre els detalls de l'objecte audiovisual cal que continguin solament icones, solament text o o ambdòs. Els vincles que es mostrin aquí us permeten realitzar diverses accions de modificació sobre l'objecte en questió.<br /><br />L'opció <b>#pgv_lang[lb_none]#</b> amaga completament aquests víncles i, per tant, actua como si l'usuari no tingués privilegis de modificació.<br />";
$pgv_lang["lb_ss_speedLegend"]			= "Velocitat de la projecció de diapositives";
$pgv_lang["lb_ss_speed_help"]			= "~#pgv_lang[lb_ss_speedLegend]#~<br />Aquesta opció determina quan de temps es mostrarà cada imatge a la presentació, abans de passar a la següent.<br />";
$pgv_lang["lb_music_fileLegend"]		= "Pista de so de la projecció de diapositives";
$pgv_lang["lb_music_file_help"]			= "~#pgv_lang[lb_music_fileLegend]#~<br />Aquesta opció us permet especificar una banda sonora a reproduir cada cop que s'activi la presentación. Si deixeu aquest camp en blanc, no es reproduirá cap só durant la presentació.<br /><br />Aquesta característica solament admet arxius en format mp3.<br />";
$pgv_lang["lb_transitionLegend"]		= "Velocitat de transició entre imatges";
$pgv_lang["lb_transition_help"]			= "~#pgv_lang[lb_transitionLegend]#~<br />Aquesta opció us permet especificar la velocitat de transició en el canvi d'imatges. Aquesta selecció s'aplica durant la presentació. També s'aplica si aneu a la imatge anterior o posterior, si la presentació està detinguda.<br /><br />La opció <b>#pgv_lang[lb_none]#</b> elimina les transicions entre imatges de forma que la nova imatge substitueix de forma immediata a l'anterior, sense adaptació perceptible a les mides de la nova imatge.<br />";
$pgv_lang["lb_url_dimensionsLegend"]	= "Mides de les finestres per a la URL del Projector d'Imatges"; 
$pgv_lang["lb_url_dimensions_help"]		= "~#pgv_lang[lb_url_dimensionsLegend]#~<br />Si polseu sobre la miniatura d'una imatge que correspon a una URL, aquesta opció us permet especificar les mides en píxels de la finestra del Projector d'Imatges per la URL.<br /><br />Les mides cal que siguin més petites que les de la finestra actual del navegador i, tanmateix, més petites que les de la vostra pantalla.<br />";

?>
