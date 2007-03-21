<?php
/**
 * English texts
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
 *
 * @author PGV Developers
 * @package PhpGedView
 * @subpackage Languages
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Usted no puede acceder a este archivo de idioma directamente.";
	exit;
}

$pgv_lang["accept_changes"]		    = "Aceptar / Rechazar Cambios";
$pgv_lang["review_changes"]		    = "Reseña de los cambios en el GEDCOM";
$pgv_lang["add_unlinked_person"]    = "Agregar una nueva persona sin enlazar";
$pgv_lang["accept_gedcom"]		    = "Para rechazar un cambio, seleccione el enlace Deshacer.  Para aceptar todos los cambios de un Geedcom, reimportar el archivo de nuevo.";
$pgv_lang["add_child"]			    = "Agregar hijo";
$pgv_lang["add_child_to_family"]    = "Agregar un hijo a esta familia";
$pgv_lang["add_fact"]			    = "Añadir nuevo acontecimiento";
$pgv_lang["add_husb"]		     	= "Agregar esposo";
$pgv_lang["add_husb_to_family"]	    = "Agregar esposo a esta familia";
$pgv_lang["add_new_husb"]	    	= "Agregar un nuevo esposo";
$pgv_lang["add_new_wife"]		    = "Agregar una nueva esposa";
$pgv_lang["add_note"]			    = "Añadir Nota al Acontecimiento";
$pgv_lang["add_source"]			    = "Añadir Fuente al Acontecimiento";
$pgv_lang["add_wife"]		    	= "Agregar esposa";
$pgv_lang["add_wife_to_family"] 	= "Agregar esposa a esta familia";
$pgv_lang["changes_occurred"]	    = "Esta persona tuvo los cambios siguientes:";
$pgv_lang["date"]				= "Fecha";
$pgv_lang["family"]				= "Familia";
$pgv_lang["file_missing"]			= "Archivo no recibido. Envíelo de nuevo.";
$pgv_lang["file_partial"]			= "Archivo parcialmente subido, por favor inténtelo de nuevo";
$pgv_lang["file_success"]			= "Archivo correctamente subido";
$pgv_lang["file_too_big"]			= "El archivo a subir excede el tamaño permitido";
$pgv_lang["gedcomid"]				= "ID personal";
$pgv_lang["gedrec_deleted"]		    = "Registro GEDCOM correctamente eliminado.";
$pgv_lang["media_file"]			= "Archivos Multimedia";
$pgv_lang["must_provide"]		    = "Debe suministrar un ";
$pgv_lang["no_temple"]			    = "No Temple - Living Ordinance";
$pgv_lang["show_changes"]		    = "Este registro ha sido actualizado.  Pulse aquí para ver los cambios.";
$pgv_lang["thumbnail"]			= "Miniaturas";
$pgv_lang["undo"]				    = "Deshacer";
$pgv_lang["undo_successful"]	    = "Se ha deshecho correctamente";
$pgv_lang["update_successful"]	    = "Actualizado correctamente";
$pgv_lang["upload_error"]			= "Hubo un error subiendo su archivo GEDCOM.";
$pgv_lang["upload_media"]		= "Subir archivos multimedia";
$pgv_lang["upload_successful"]	= "Proceso completo";
$pgv_lang["view_change_diff"]	    = "Ver modificaciones";


?>
