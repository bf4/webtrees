<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2008 to 2009  PGV Development Team.  All rights reserved.
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
 * @translator: Antoni Planas i Vilà
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

//Config Parameters -------------------------------------
// Added in VERSION 4.1.4

$pgv_lang["lb_details"]			= "Detalls";
$pgv_lang["lb_detail_info"]		= "Veure els detalls d'aquest objecte ...  I altres opcions d'objectes - Pàgina del visor d'objectes";
$pgv_lang["lb_pause_ss"]		= "Pausa la presentació";
$pgv_lang["lb_start_ss"]		= "Comença la presentació";
$pgv_lang["lb_music"]			= "Activa/descativa la música";
$pgv_lang["lb_zoom_off"]		= "Desactiva el zoom";
$pgv_lang["lb_zoom_on"]			= "Zoom activat ... Empreu la roda del ratolí o les tecles 'i' i 'o' per acostar o allunyar";
$pgv_lang["lb_close_win"]		= "Tanca la finestra del 'Projector d'imatges'";




// VERSION 4.1.3

//Config Parameters -------------------------------------
$pgv_lang["configure_lightbox"]			= "Configuració de l'Àlbum del Projector d'imatges";
$pgv_lang["mediatab"]       			= "<b>Pàgina de persona - Pestanya d'objectes</b><br />&nbsp;&nbsp;&nbsp;&nbsp;Aspecte";
$pgv_lang["lb_appearance"]				= "Aspecte";
$pgv_lang["lb_linkAppearance"]			= "Aspecte del vincle";
$pgv_lang["lb_MP3Only"]					= "(solament mp3)";
$pgv_lang["lb_admin_error"]				= "Pàgina solament per a administradors";
$pgv_lang["lb_toAlbumPage"]				= "Torna a la pàgina de l'Àlbum";

$pgv_lang["lb_icon"]					= "Icona";
$pgv_lang["lb_text"]					= "Text";
$pgv_lang["lb_both"]					= "Ambdós";
$pgv_lang["lb_none"]					= "Cap";

$pgv_lang["lb_al_head_links"]			= "<b>Pàgina de persona - Encapçalament de la pestanya Àlbum</b><br />&nbsp;&nbsp;&nbsp;&nbsp;Aspecte del víncle";
$pgv_lang["lb_al_thumb_links"]			= "<b>Pàgina de persona - Miniatures a la pestanya Àlbum</b><br />&nbsp;&nbsp;&nbsp;&nbsp;Aspecte dels vincles";
$pgv_lang["lb_ml_thumb_links"]			= "<b>Pàgina d'objectes audiovisuals - Miniatures</b><br />&nbsp;&nbsp;&nbsp;&nbsp;Aspecte dels vincles";
$pgv_lang["lb_music_file"]				= "<b>Banda sonora per a la presentació</b><br />&nbsp;&nbsp;&nbsp;&nbsp;(solament mp3)";
$pgv_lang["lb_musicFileAdvice"]			= "Ubicació de l'arxiu amb la banda sonora (deixeu-ho en blanc si no hi ha banda sonora)";
$pgv_lang["lb_ss_speed"]				= "<b>Velocitat de la Presentació</b>";
$pgv_lang["lb_ss_SpeedAdvice"]			= "Temps, en segons, de permanència de la imatge";

$pgv_lang["lb_transition"]				= "Velocitat de transició entre imatges";
$pgv_lang["lb_normal"]					= "Normal";
$pgv_lang["lb_double"]					= "Doble";
// $pgv_lang["lb_warp"]					= "Warp";
$pgv_lang["lb_url_dimensions"]			= "Mides de les finestres de URL";
$pgv_lang["lb_url_dimensionsAdvice"]	= "Amplada i alçada en píxels de les finestres per URL";
$pgv_lang["lb_width"]					= "Amplada";
$pgv_lang["lb_height"]					= "Alçada";


// ---------------------------------------------------------------------


$pgv_lang["lb_help"] 		 = "Ajuda per l'Àlbum";
$pgv_lang["lightbox"]		 = "Àlbum";
$pgv_lang["showmenu"] 		 = "Mostra Menú:";

$pgv_lang["TYPE__other"] 	 = "Un altre";

$pgv_lang["TYPE__footnotes"] = "Notes al peu";

$pgv_lang["census_text"]  	 = "\"Aquestes imatges del cens s'han obtingut d'\"Arxius Oficials\", custodis dels registres originals, ";
$pgv_lang["census_text"] 	.= "i surten aquí amb llur autorització sota la condició de que no se'n faci ús comercial sense llur permís." . "\n" ;
$pgv_lang["census_text"] 	.= "Les sol·licituts per la utilització comercial d'aquestes o altres imatges de censos que apareixen en aquest lloc web cal adreçar-les a: ";
$pgv_lang["census_text"] 	.= "Image Library, The National Archives, Kew, Surrey, TW9 4DU, United Kingdom.\"" . "\n" ;

$pgv_lang["lb_edit_details"] 	= "Edita detalls";
$pgv_lang["lb_view_details"] 	= "Veure detalls";
$pgv_lang["lb_edit_media"] 		= "Edita els detalls d'aquest objecte audiovisual";
$pgv_lang["lb_delete_media"] 	= "Elimina aquest objecte audiovisual - Solament s'esborrarà el vincle a aquesta persona - No s'esborrarà l'arxiu audiovisual ni altres vincles";
$pgv_lang["lb_view_media"] 		= "Veure els detalls d'aquest objecte audiovisual.\nMés altres opcions d'objectes - Pàgina del visor d'objectes";
$pgv_lang["lb_add_media"] 		= "Afegeix un nou objecte audiovisual";
$pgv_lang["lb_add_media_full"] 	= "Afegeix un nou objecte audiovisual a aquesta persona";
$pgv_lang["lb_link_media"] 		= "Vincula a un objecte audiovisual existent";
$pgv_lang["lb_link_media_full"] = "Vincula aquesta persona a un objecte audiovisual existent";

$pgv_lang["lb_slide_show"] 		= "Projecció de diapositives";
$pgv_lang["turn_edit_ON"] 		= "Activa el modus d'edició";
$pgv_lang["turn_edit_OFF"] 		= "Desactiva el modus d'edició";

$pgv_lang["lb_source_avail"] 	= "Hi ha informació de la font - Polseu aquí.";

$pgv_lang["lb_private"] 		= "Imatge vinculada a <br> una persona privada";
$pgv_lang["lb_view_source_tip"] = "Veure la font : ";
$pgv_lang["lb_view_details_tip"] = "Veure els detalls de l'objecte : ";



?>
