<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
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

// Added in VERSION 4.1.6
$pgv_lang["lb_generalhelp"]     = "Página personal - Ayuda general de Lightbox";
$pgv_lang["lb_viewedit"]		= "Ver/Modificar";
$pgv_lang["lb_viewnotes"]		= "Ver notas";
$pgv_lang["lb_viewdetails"]		= "Ver detalles";
$pgv_lang["lb_viewsource"]		= "Ver fuente";
$pgv_lang["lb_editmedia"]		= "Modificar objeto";
$pgv_lang["lb_unlinkmedia"]		= "Desvincular objeto";
$pgv_lang["lb_balloon_true"]	= "Globo";
$pgv_lang["lb_balloon_false"]	= "Normal";
$pgv_lang["lb_tt_balloon"]		= "Página personal - Miniatura de pestaña de álbum - Ayuda de los enlaces superiores";
$pgv_lang["lb_ttAppearance"]	= "Vínculos superiores - Apariencia de la ayuda";
$pgv_lang["view_lightbox"]		= "Ver álbum de ...";
$pgv_lang["lb_notes"]			= "Notas";
// $pgv_lang["lb_notes_info"]		= "";
 

// Added in VERSION 4.1.4 
$pgv_lang["lb_details"]			= "Detalles";
$pgv_lang["lb_detail_info"]		= "Ver los detalles de este objeto ...  Y otras opciones de objetos - Página del visor de objetos";
$pgv_lang["lb_pause_ss"]		= "Pausar la presentación";
$pgv_lang["lb_start_ss"]		= "Comenzar la presentación";
$pgv_lang["lb_music"]			= "Activar/descativar música";
$pgv_lang["lb_zoom_off"]		= "Desactivar el zoom";
$pgv_lang["lb_zoom_on"]			= "Zoom activado ... Use la rueda o las teclas i y o para acercar y alejar";
$pgv_lang["lb_close_win"]		= "Cerrar la ventana de Lightbox";


// VERSION 4.1.3 

//Config Parameters -------------------------------------
$pgv_lang["configure_lightbox"]			= "Configuración del Álbum Lightbox";
$pgv_lang["mediatab"]       			= "<b>Página de persona - Pestaña de objetos</b><br />&nbsp;&nbsp;&nbsp;&nbsp;Apariencia";
$pgv_lang["lb_appearance"]				= "Apariencia";
$pgv_lang["lb_linkAppearance"]			= "Apariencia del vínculo";
$pgv_lang["lb_MP3Only"]					= "(sólo mp3)";
$pgv_lang["lb_admin_error"]				= "Página sólo para administradores";
$pgv_lang["lb_toAlbumPage"]				= "Volver a la página de Álbum";

$pgv_lang["lb_icon"]					= "Icono";
$pgv_lang["lb_text"]					= "Texto";
$pgv_lang["lb_both"]					= "Ambos";
$pgv_lang["lb_none"]					= "Ninguno";

$pgv_lang["lb_al_head_links"]			= "<b>Página de persona - Encabezamiento de la pestaña Álbum</b><br />&nbsp;&nbsp;&nbsp;&nbsp;Apariencia del vínculo";
$pgv_lang["lb_al_thumb_links"]			= "<b>Página de persona - Miniaturas en la pestaña Álbum</b><br />&nbsp;&nbsp;&nbsp;&nbsp;Apariencia de los vínculos";
$pgv_lang["lb_ml_thumb_links"]			= "<b>Página de objetos audiovisuales - Miniaturas</b><br />&nbsp;&nbsp;&nbsp;&nbsp;Apariencia de los vínculos";
$pgv_lang["lb_music_file"]				= "<b>Banda sonora para la presentación</b><br />&nbsp;&nbsp;&nbsp;&nbsp;(sólo mp3)";
$pgv_lang["lb_musicFileAdvice"]			= "Ubicación del archivo con la banda sonora (déjelo en blanco si no hay banda sonora)";
$pgv_lang["lb_ss_speed"]				= "<b>Velocidad de la Presentación</b>";
$pgv_lang["lb_ss_SpeedAdvice"]			= "Tiempo de permanencia de la imagen en segundos";

$pgv_lang["lb_transition"]				= "Velocidad de transición entre imágenes";
$pgv_lang["lb_normal"]					= "Normal";
$pgv_lang["lb_double"]					= "Doble";
$pgv_lang["lb_warp"]					= "Salto";
$pgv_lang["lb_url_dimensions"]			= "Dimensiones de las ventanas de URL";
$pgv_lang["lb_url_dimensionsAdvice"]	= "Anchura y altura en píxeles de las ventanas para URL";
$pgv_lang["lb_width"]					= "Anchura";
$pgv_lang["lb_height"]					= "Altura";
									

// ---------------------------------------------------------------------


$pgv_lang["lb_help"] 		 = "Ayuda de Álbum";
$pgv_lang["lightbox"]		 = "Álbum";
$pgv_lang["showmenu"] 		 = "Mostrar menú:";

$pgv_lang["TYPE__other"] 	 = "Otro";

$pgv_lang["TYPE__footnotes"] = "Notas al pie";

$pgv_lang["census_text"]  	 = "\"Estas imágenes del censo se han obtenido de \"Archivos Nacionales\", custodio de los registros originales, ";
$pgv_lang["census_text"] 	.= "and appear here with their approval on the condition that no commercial use is made of them without permission." . "\n" ;
$pgv_lang["census_text"] 	.= "Requests for commercial publication of these or other UK census images appearing on this website should be directed to: ";
$pgv_lang["census_text"] 	.= "Image Library, The National Archives, Kew, Surrey, TW9 4DU, United Kingdom.\"" . "\n" ;

$pgv_lang["lb_edit_details"] 	= "Editar detalles";
$pgv_lang["lb_view_details"] 	= "Ver detalles";
$pgv_lang["lb_edit_media"] 		= "Editar los detalles de este objeto audiovisual";
$pgv_lang["lb_delete_media"] 	= "Eliminar este objeto audiovisual - Sólo borra el vínculo a esta persona - No borra el archivo audiovisual ni otros vínculos";
$pgv_lang["lb_view_media"] 		= "Ver los detalles de este objeto audiovisual.\nMás otras opciones de objetos - Página del visor de objetos";
$pgv_lang["lb_add_media"] 		= "Agregar un nuevo objeto audiovisual";
$pgv_lang["lb_add_media_full"] 	= "Agregar un nuevo objeto audiovisual a esta persona";
$pgv_lang["lb_link_media"] 		= "Vincular a un objeto audiovisual existente";
$pgv_lang["lb_link_media_full"] = "Vincular esta persona a un objeto audiovisual existente";

$pgv_lang["lb_slide_show"] 		= "Presentación";
$pgv_lang["turn_edit_ON"] 		= "Activar el modo de edición";
$pgv_lang["turn_edit_OFF"] 		= "Desactivar el modo de edición";

$pgv_lang["lb_source_avail"] 	= "Hay información de la fuente - Haga clic aquí.";

$pgv_lang["lb_private"] 		= "Imagen vinculada a <br> una persona privada";
$pgv_lang["lb_view_source_tip"] = "Ver fuente : ";
$pgv_lang["lb_view_details_tip"] = "Ver detalles del objeto : ";



?>
