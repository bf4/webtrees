<?php
/**
 * Spanish language file for PhpGedView
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PGV Development Team
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
 * @subpackage GoogleMap
 * @version $Id$
 */
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Usted no puede acceder a este archivo de idioma directamente.";
	exit;
}

$pgv_lang["googlemap"]              = "Mapa";
$pgv_lang["no_gmtab"]               = "No hay datos de mapa para esta persona";
$pgv_lang["gm_disabled"]            = "Módulo GoogleMap desactivado";

$pgv_lang['gm_redraw_map']          = "Redibujar mapa";
$pgv_lang["gm_map"]                 = "Mapa";
$pgv_lang["gm_satellite"]           = "Satélite";
$pgv_lang["gm_hybrid"]              = "Híbrido";

// Configuration texts
$pgv_lang["gm_manage"]              = "Idioma";
$pgv_lang["configure_googlemap"]    = "Configuración de GoogleMap";
$pgv_lang["gm_admin_error"]         = "Página sólo para administradores";
$pgv_lang["gm_db_error"]            = "No se encontró la tabla placelocation en la base de datos";
$pgv_lang["gm_table_created"]       = "Creada la tabla placelocation";
$pgv_lang["googlemap_enable"]       = "Activar GoogleMap";
$pgv_lang["googlemapkey"]           = "Clave para el API de GoogleMap";
$pgv_lang["gm_map_type"]            = "Tipo de mapa predeterminado";
$pgv_lang["gm_map_size"]            = "Tamaño del mapa (en píxeles)";
$pgv_lang["gm_map_size_x"]          = "Ancho";
$pgv_lang["gm_map_size_y"]          = "Alto";
$pgv_lang["gm_map_zoom"]            = "Factor de ampliación del mapa";
$pgv_lang["gm_digits"]              = "dígitos";
$pgv_lang["gm_min"]                 = "Mín.";
$pgv_lang["gm_max"]                 = "Máx.";
$pgv_lang["gm_default_level0"]      = "Valor predeterminado para el nivel más alto";
$pgv_lang["gm_nof_levels"]          = "Número de niveles";
$pgv_lang["gm_config_per_level"]    = "Configuración por nivel";
$pgv_lang["gm_name_prefix"]         = "Prefijo";
$pgv_lang["gm_name_postfix"]        = "Sufijo";
$pgv_lang["gm_name_pre_post"]       = "Orden de Prefijo / Sufijo";
$pgv_lang["gm_level"]               = "Nivel";
$pgv_lang["gm_pp_none"]             = "Ni prefijo ni sufijo";
$pgv_lang["gm_pp_n_pr_po_b"]        = "Normal, prefijo, sufijo, ambos";
$pgv_lang["gm_pp_n_po_pr_b"]        = "Normal, sufijo, prefijo, ambos";
$pgv_lang["gm_pp_pr_po_b_n"]        = "Prefijo, sufijo, ambos, normal";
$pgv_lang["gm_pp_po_pr_b_n"]        = "Sufijo, prefijo, ambos, normal";
$pgv_lang["gm_pp_pr_po_n_b"]        = "Prefijo, sufijo, normal, ambos";
$pgv_lang["gm_pp_po_pr_n_b"]        = "Sufijo, prefijo, normal, ambos";
$pgv_lang["googlemap_coord"]        = "Mostrar coordenadas del mapa";


// Texts used on the Places location page
$pgv_lang["edit_place_locations"]   = "Editar las posiciones geográficas de los lugares";
$pgv_lang["pl_no_places_found"]     = "No se encontraron lugares";
$pgv_lang["pl_zoom_factor"]         = "Factor de aumento";
$pgv_lang["pl_place_icon"]          = "Icono";
$pgv_lang["pl_edit"]                = "Modificar la posición geográfica";
$pgv_lang["pl_add_place"]           = "Agregar lugar";
$pgv_lang["pl_import_gedcom"]       = "Importar del GEDCOM actual";
$pgv_lang["pl_import_all_gedcom"]   = "Importar de todos los GEDCOM";
$pgv_lang["pl_import_file"]         = "Importar del archivo";
$pgv_lang["pl_export_file"]         = "Exportar la vista actual a un archivo";
$pgv_lang["pl_export_all_file"]     = "Exportar todas las posicione a un archivo";
$pgv_lang["pl_north_short"]         = "N";
$pgv_lang["pl_south_short"]         = "S";
$pgv_lang["pl_east_short"]          = "E";
$pgv_lang["pl_west_short"]          = "O";
$pgv_lang["pl_places_localfile"]	= "Archivo en el servidor con los lugares (CSV)";
$pgv_lang["pl_places_filename"]     = "Archivo con los lugares (CSV)";
$pgv_lang["pl_clean_db"]            = "¿Limpiar todas las posiciones de lugares antes de la importación?";
$pgv_lang["pl_update_only"]         = "¿Actualizar solamente los lugares existentes?";
$pgv_lang["pl_overwrite_data"]      = "¿Sustituir los datos actuales de ubicación con los del archivo?";
$pgv_lang["pl_no_places_found"]     = "No se encontraron lugares";
$pgv_lang["pl_use_this_value"]      = "Usar este valor";
$pgv_lang["pl_precision"]           = "Precisión";
$pgv_lang["pl_country"]             = "País";
$pgv_lang["pl_state"]               = "Estado/Provincia";
$pgv_lang["pl_city"]                = "Población";
$pgv_lang["pl_neighborhood"]        = "Barrio";
$pgv_lang["pl_house"]               = "Edificio";
$pgv_lang["pl_max"]                 = "Máximo";
$pgv_lang["pl_delete"]              = "Borrar la posición geográfica";

$pgv_lang["pl_flag"]                = "Bandera";
$pgv_lang["flags_edit"]             = "Seleccionar bandera";
$pgv_lang["pl_change_flag"]         = "Cambiar la bandera";
$pgv_lang["pl_remove_flag"]         = "Borrar bandera";

$pgv_lang["pl_remove_location"]     = "¿Borrar esta ubicación?";
$pgv_lang["pl_delete_error"]        = "Ubicación no borrada.  Esta ubicación tiene otras ubicaciones subordinadas";
$pgv_lang["list_inactive"]        	= "Haga clic aquí para mostrar los lugares inactivos";

//Placecheck specific text
$pgv_lang["placecheck"]				= "Comprobación de lugares";
$pgv_lang['placecheck_text']		= "Esto listará todos los sitios del archivo GEDCOM seleccionado. Por defecto NO SE INCLUIRÁN lugares que que corresponden completamente entre el archivo GEDCOM y las tablas GoogleMap";
$pgv_lang['placecheck_top']			= "Nivel más alto de la Jerarquía de Lugares";
$pgv_lang['placecheck_one']			= "Lugar de Nivel Uno";
$pgv_lang['placecheck_select1']		= "Seleccione el nivel más alto...";
$pgv_lang['placecheck_select2']		= "Seleccione siguiente nivel...";
$pgv_lang['placecheck_key']			= "Explicación de los colores usados a continuación";
$pgv_lang['placecheck_key1']		= "este lugar y sus coordenadas no existe en las tablas de GoogleMap";
$pgv_lang['placecheck_key2']		= "este sitio existe en las tablas de GoogleMap, pero no tiene coordenadas";
$pgv_lang['placecheck_key3']		= "este lugar está en blanco en su archivo GEDCOM. Debería agregarse a los sitios<br/>GoogleMap como \"desconocido\" con las coordenadas de su nivel superior<br/>antes de agregar ningún otro lugar al siguiente nivel";
$pgv_lang['placecheck_key4']		= "el nivel de este lugar está en blanco en su archivo GEDCOM, pero existe como 'desconocido'<br/>en la tabla de lugares GoogleMap con coordenadas. No se requiere ninguna acción<br/>hasta que el nivel que falta se pueda introducir";
$pgv_lang['placecheck_head']		= "Lista de lugares del archivo GEDCOM";
$pgv_lang['placecheck_gedheader']	= "Datos de lugares en archivo GEDCOM<br/>(etiqueta 2 PLAC)";
$pgv_lang['placecheck_gm_header']	= "Datos de la tabla de lugares GoogleMap";
$pgv_lang['placecheck_unique']		= "Total de lugares distintos";
$pgv_lang["placecheck_zoom"]        = "Zoom=";
$pgv_lang["placecheck_options"]     = "Listar opciones para la comprobación de lugares";
$pgv_lang["placecheck_filter_text"] = "Opciones de filtrado de listas";
$pgv_lang["placecheck_match"] 		= "Incluir sitios que coincidan completamente -";
?>
