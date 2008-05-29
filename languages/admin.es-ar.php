<?php
/**
 * Latin American Spanish texts
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
 * @package PhpGedView
 * @subpackage Languages
 * @author Eduardo Cociña
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Usted no puede acceder a este archivo de idioma directamente.";
	exit;
}

$pgv_lang["step2"]	= "Paso 2 de 4";
$pgv_lang["gedcom_deleted"]	= "GEDCOM [#GED#] correctamente eliminado.";
$pgv_lang["full_name"]	= "Nombre Completo";
$pgv_lang["error_header"] 	= "El archivo GEDCOM, [#GEDCOM#], No existe en la ubicación especificada.";
$pgv_lang["manage_gedcoms"]	= "Administración de GEDCOMs y Edición de Privacidad";
$pgv_lang["created_indis"]	= "Tabla de <i>Individuos</i> creada exitosamente.";
$pgv_lang["created_indis_fail"]	= "No se pudo crear la tabla <i>Individuos</i>";
$pgv_lang["created_fams"]	= "Tabla de <i>Familias</i> creada exitosamente.";
$pgv_lang["created_fams_fail"]	= "No se pudo crear la tabla <i>Familias</i>.";
$pgv_lang["created_sources"]	= "Tabla de <i>Fuentes</i> creada exitosamente.";
$pgv_lang["created_sources_fail"]	= "No se pudo crear la tabla <i>Fuentes</i>.";
$pgv_lang["created_other"]	= "Tabla <i>Otros</i> creada exitosamente.";
$pgv_lang["created_other_fail"]	= "No se pudo crear la tabla <i>Otros</i>.";
$pgv_lang["created_places"]	= "Tabla de <i>Lugares</i> creada exitosamente.";
$pgv_lang["created_places_fail"]	= "No se pudo crear la tabla <i>Lugares</i>.";
$pgv_lang["folder_created"]	= "Carpeta creada";
$pgv_lang["add_gedcom"]	= "Añadir otro archivo GEDCOM";
$pgv_lang["add_new_gedcom"]	= "Crear un nuevo GEDCOM";
$pgv_lang["admin_approved"]	= "Su cuenta en #SERVER_NAME# ha sido aprobada";
$pgv_lang["administration"]	= "Administración";
$pgv_lang["ansi_encoding_detected"]	= "Archivo detectado con codificación ANSI. PhpGedView funciona mejor con archivos codificados en UTF-8.";
$pgv_lang["ansi_to_utf8"]	= "Convertir este GEDCOM codificado ANSI a UTF-8?";
$pgv_lang["bytes_read"]	= "Bytes leídos:";
$pgv_lang["change_id"]	= "Cambiar el ID individual a:";
$pgv_lang["cleanup_places"]		= "Lugares depurados";
$pgv_lang["click_here_to_go_to_pedigree_tree"] = "Presione aquí para ver el árbol de antepasados.";
$pgv_lang["configuration"]	= "Configuración";
$pgv_lang["confirm_user_delete"]	= "Seguro que desea borrar el usuario";
$pgv_lang["create_user"]	= "Crear Usuario";
$pgv_lang["dataset_exists"]	= "Un GEDCOM con este nombre ya ha sido importado en esta Base de datos.";
$pgv_lang["day_before_month"]	= "Día antes del mes (DD MM YYYY)";
$pgv_lang["do_not_change"]	= "No modificar";
$pgv_lang["download_gedcom"]	= "Grabar GEDCOM";
$pgv_lang["download_note"]		= "NOTA: Los archivos GEDCOM de gran tamaño pueden demorar mucho tiempo de proceso antes de su descarga. Si PHP produce un 'time-out' antes de completar la descarga, esta podría quedar incompleta. Puede controlar la existencia de la línea 0 TRLR al final del archivo para asegurarse de su descarga completa. En general insume tanto tiempo descargar como importar un archivo GEDCOM.";
$pgv_lang["empty_dataset"]	= "Desea eliminar los datos anteriores y reemplazarlos con la nueva información?";
$pgv_lang["empty_lines_detected"]	= "Se detectaron líneas vacías en su archivo GEDCOM. Al realizar limpieza, las líneas vacías se eliminarán.";
$pgv_lang["error_header_write"]	= "El archivo GEDCOM, [#GEDCOM#], no es grabable. Controle sus atributos y privilegios de acceso.";
$pgv_lang["example_date"]	= "Ejemplo de fecha inválida para su GEDCOM:";
$pgv_lang["found_record"]	= "Registros encontrados";
$pgv_lang["ged_import"]	= "Importar GEDCOM";
$pgv_lang["gedcom_file"]	= "Archivo GEDCOM:";
$pgv_lang["img_admin_settings"]	= "Configuración de Manejo de Imágenes";
$pgv_lang["import_complete"]	= "Importación completa";
$pgv_lang["import_progress"]	= "Progreso de la importación...";
$pgv_lang["inc_languages"]	= "Idiomas";
$pgv_lang["invalid_dates"]	= "Se detectó formatos de fecha inválidos, al realizar 'Limpieza' se cambiarán al formato DD MMM YYYY (p.ej. 1 ENE 2004).";
$pgv_lang["invalid_header"]		= "Se detectaron registros antes del encabezamiento GEDCOM (0 HEAD). Al realizar limpieza estos registros se eliminarán.";
$pgv_lang["logfile_content"]	= "Contenido del archivo de registro";
$pgv_lang["macfile_detected"]	= "Se detectó un archivo Macintosh. Al realizar limpieza será convertido a archivo DOS.";
$pgv_lang["month_before_day"]	= "Mes antes del día (MM DD YYYY)";
$pgv_lang["performing_validation"]	= "Realizando validación del GEDCOM. Seleccione las opciones necesarias y presione 'Limpieza'";
$pgv_lang["pgv_registry"]		= "Ver otros sitios usando PhpGedView";
$pgv_lang["place_cleanup_detected"]	= "Se detectaron codificaciones de lugar incorrectas. Estos errores deben ser corregidos. El ejemplo siguiente muestra el lugar inválido detectado:";
$pgv_lang["please_be_patient"]	= "POR FAVOR SEA PACIENTE";
$pgv_lang["reading_file"]	= "Leyendo archivo GEDCOM";
$pgv_lang["readme_documentation"]	= "Documentación \"LÉAME\"";
$pgv_lang["rootid"]	= "Persona inicial en el Gráfico de Antepasados";
$pgv_lang["select_an_option"]	= "Seleccione una opción:";
$pgv_lang["update_myaccount"]	= "Actualizar mi cuenta";
$pgv_lang["update_user"]	= "Actualizar Cuenta de Usuario";
$pgv_lang["upload_gedcom"]	= "Cargar GEDCOM";
$pgv_lang["user_contact_method"] = "Método de Contacto Preferido";
$pgv_lang["user_create_error"]	= "No es posible añadir el usuario. Por favor retroceda y comience nuevamente.";
$pgv_lang["user_created"]	= "Usuario creado exitosamente.";
$pgv_lang["valid_gedcom"]	= "Se detectó un GEDCOM válido. No se requiere 'Limpieza'.";
$pgv_lang["validate_gedcom"]	= "Validar GEDCOM";
$pgv_lang["verified"]	= "El usuario se ha autoverificado";
$pgv_lang["verified_by_admin"]	= "Usuario aprobado por el Administrador";
$pgv_lang["view_logs"]	= "Ver archivos de registro";
$pgv_lang["you_may_login"]	= " por el administrador. Ud. puede ingresar al sitio PhpGedView presionando el vínculo siguiente:";


?>
