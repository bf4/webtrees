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
 * @subpackage Languages
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Usted no puede acceder a este archivo de idioma directamente.";
	exit;
}

$pgv_lang["add_marriage"]			= "Agregar un nuevo matrimonio";
$pgv_lang["edit_concurrency_change"] = "Este registro fue modificado por última vez por <i>#CHANGEUSER#</i> el #CHANGEDATE#";
$pgv_lang["edit_concurrency_msg2"]	= "El registro con id #PID# fue modificado por otro usuario desde que accedió a él por última vez.";
$pgv_lang["edit_concurrency_msg1"]	= "Ocurrió un error mientras se creaba el formulario de Edición.  Otro usuario puede haber cambiado este registro desde que lo visualizó.";
$pgv_lang["edit_concurrency_reload"]	= "Por favor utilice el botón de Página Anterior de su navegador y recargue la página anterior para asegurarse de que trabaja con el registro más reciente.";
$pgv_lang["admin_override"]			= "Opción de administración";
$pgv_lang["no_update_CHAN"]			= "No actualizar el registro CHAN (último cambio)";
$pgv_lang["select_events"]			= "Seleccionar eventos";
$pgv_lang["source_events"]			= "Asociar eventos con esta fuente";
$pgv_lang["advanced_name_fields"]	= "Nombres adicionales (apodo, nombre de casada, etc.)";
$pgv_lang["accept_changes"] 		= "Aceptar / Rechazar Cambios";
$pgv_lang["replace"]				= "Sustituir registro";
$pgv_lang["append"] 				= "Agregar registro";
$pgv_lang["review_changes"] 		= "Reseña de los cambios en el GEDCOM";
$pgv_lang["remove_object"]			= "Eliminar objeto";
$pgv_lang["remove_links"]			= "Eliminar vinculos";
$pgv_lang["media_not_deleted"]		= "Directorio de objetos audiovisuales no eliminado.";
$pgv_lang["thumbs_not_deleted"]		= "Directorio de miniaturas no eliminado.";
$pgv_lang["thumbs_deleted"]			= "Directorio de miniaturas eliminado con éxito.";
$pgv_lang["show_thumbnail"]			= "Mostrar miniaturas";
$pgv_lang["link_media"]				= "Vincular objetos multimedia";
$pgv_lang["to_person"]				= "A Persona";
$pgv_lang["to_family"]				= "A Familia";
$pgv_lang["to_source"]				= "A Fuente";
$pgv_lang["edit_fam"]				= "Editar familia";
$pgv_lang["copy"]					= "Copiar";
$pgv_lang["cut"]					= "Cortar";
$pgv_lang["sort_by_birth"]			= "Ordenar por fecha de nacimiento";
$pgv_lang["reorder_children"]		= "Reordenar hijos";
$pgv_lang["add_from_clipboard"]		= "Agregar desde el portapapeles: ";
$pgv_lang["record_copied"]			= "Registro copiado al portapapeles";
$pgv_lang["add_unlinked_person"]	= "Agregar una nueva persona sin vincular";
$pgv_lang["add_unlinked_source"]	= "Agregar una fuente no vinculada";
$pgv_lang["server_file"]				= "Nombre de archivo en el servidor";
$pgv_lang["server_file_advice"]			= "No cambie para preservar en nombre del archivo original.";
$pgv_lang["server_file_advice2"]		= "Puede introducir una URL, comenzando por &laquo;http://&raquo;.";
$pgv_lang["server_folder_advice"]		= "Puede introducir hasta #GLOBALS[MEDIA_DIRECTORY_LEVELS]# nombres de carpeta detrás del &laquo;#GLOBALS[MEDIA_DIRECTORY]#&raquo; predeterminado.<br />No introduzca la parte &laquo;#GLOBALS[MEDIA_DIRECTORY]#&raquo; del nombre de carpeta destino.";
$pgv_lang["server_folder_advice2"]		= "Esta entrada se ignora si introdujo una URL en el campo de nombre del archivo.";
$pgv_lang["add_linkid_advice"]			= "Introduzca o busque el ID de la persona, familia o fuente donde hay que vincular este objeto.";
$pgv_lang["use_browse_advice"]			= "Utilice el botón &laquo;Examinar&raquo; para localizar en su equipo local el archivo deseado.";
$pgv_lang["add_media_other_folder"]		= "Otra carpeta... por favor, tecléela";
$pgv_lang["add_media_file"]				= "Archivo audiovisual existente en el servidor";
$pgv_lang["main_media_ok1"]				= "El archivo audiovisual principal <b>#GLOBALS[oldMediaName]#</b> ha sido renombrado con éxito a <b>#GLOBALS[newMediaName]#</b>.";
$pgv_lang["main_media_ok2"]				= "El archivo audiovisual principal <b>#GLOBALS[oldMediaName]#</b> se movió con éxito de <b>#GLOBALS[oldMediaFolder]#</b> a <b>#GLOBALS[newMediaFolder]#</b>.";
$pgv_lang["main_media_ok3"]				= "El archivo audiovisual principarl se movió y renombró con éxito de <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b> a <b>#GLOBALS[newMediaFolder]##GLOBALS[newMediaName]#</b>.";
$pgv_lang["main_media_fail0"]			= "El archivo audiovisual <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b> no existe.";
$pgv_lang["main_media_fail1"]			= "No se pudo renombrar el archivo audiovisual principal de <b>#GLOBALS[oldMediaName]#</b> a <b>#GLOBALS[newMediaName]#</b>.";
$pgv_lang["main_media_fail2"]			= "No se pudo mover el archivo audiovisual principal <b>#GLOBALS[oldMediaName]#</b> de <b>#GLOBALS[oldMediaFolder]#</b> a <b>#GLOBALS[newMediaFolder]#</b>.";
$pgv_lang["main_media_fail3"]			= "No se pudo mover y renombrar el archivo audiovisual principal de <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b> a <b>#GLOBALS[newMediaFolder]##GLOBALS[newMediaName]#</b>.";
$pgv_lang["resn_disabled"]				= "Nota: Debe activar la función 'Utilizar la restricción de privacidad de GEDCOM (RESN)' para que este ajuste surta efecto.";
$pgv_lang["thumb_media_ok1"]			= "El archivo de miniatura <b>#GLOBALS[oldMediaName]#</b> se renombró con éxito a <b>#GLOBALS[newMediaName]#</b>.";
$pgv_lang["thumb_media_ok2"]			= "El archivo de miniatura <b>#GLOBALS[oldMediaName]#</b> se movió con éxito de <b>#GLOBALS[oldThumbFolder]#</b> a <b>#GLOBALS[newThumbFolder]#</b>.";
$pgv_lang["thumb_media_ok3"]			= "El archivo de miniatura se movió y renombró con éxito de <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b> a <b>#GLOBALS[newThumbFolder]##GLOBALS[newMediaName]#</b>.";
$pgv_lang["thumb_media_fail0"]			= "El archivo de miniatura <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b> no existe.";
$pgv_lang["thumb_media_fail1"]			= "No se pudo renombrar el archivo de miniatura de <b>#GLOBALS[oldMediaName]#</b> a <b>#GLOBALS[newMediaName]#</b>.";
$pgv_lang["thumb_media_fail2"]			= "No se pudo mover el archivo de miniatura <b>#GLOBALS[oldMediaName]#</b> de <b>#GLOBALS[oldThumbFolder]#</b> a <b>#GLOBALS[newThumbFolder]#</b>.";
$pgv_lang["thumb_media_fail3"]			= "No se pudo mover y renombrar el archivo de miniatura de <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b> a <b>#GLOBALS[newThumbFolder]##GLOBALS[newMediaName]#</b>.";
$pgv_lang["add_asso"]				= "Añadir un nuevo asociado";
$pgv_lang["edit_sex"]				= "Modificar sexo";
$pgv_lang["add_obje"]				= "Agregar un nuevo Objeto MultiMedio";
$pgv_lang["add_name"]				= "Añadir nuevo nombre";
$pgv_lang["edit_raw"]				= "Editar el registro GEDCOM en bruto";
$pgv_lang["label_add_remote_link"]  = "Agregar vínculo";
$pgv_lang["label_gedcom_id"]        = "ID GEDCOM";
$pgv_lang["label_local_id"]         = "ID de la persona";
$pgv_lang["accept"] 				= "Accept";
$pgv_lang["accept_all"] 			= "Aceptar todas las modificaciones";
$pgv_lang["accept_gedcom"]			= "Para rechazar un cambio, seleccione el vínculo Deshacer.  Para aceptar todos los cambios de un Gedcom, reimportar el archivo de nuevo.";
$pgv_lang["accept_successful"]		= "Modificaciones aceptadas con éxito en la base de datos";
$pgv_lang["add_child"]				= "Agregar hijo";
$pgv_lang["add_child_to_family"]	= "Agregar un hijo a esta familia";
$pgv_lang["add_fact"]				= "Añadir nuevo hecho";
$pgv_lang["add_father"] 			= "Agregar un nuevo padre";
$pgv_lang["add_husb"]				= "Agregar esposo";
$pgv_lang["add_husb_to_family"] 	= "Agregar esposo a esta familia";
$pgv_lang["add_media"]				= "Agregar un nuevo objeto audiovisual";
$pgv_lang["add_media_lbl"]			= "Agregar objetos audiovisuales";
$pgv_lang["add_mother"] 			= "Agregar una nueva madre";
$pgv_lang["add_new_chil"] 			= "Agregar un nuevo hijo";
$pgv_lang["add_new_husb"]			= "Agregar un nuevo esposo";
$pgv_lang["add_new_wife"]			= "Agregar una nueva esposa";
$pgv_lang["add_note"]				= "Añadir una nota";
$pgv_lang["add_note_lbl"]			= "Agregar Nota";
$pgv_lang["add_sibling"]			= "Agregar un hermano o hermana";
$pgv_lang["add_son_daughter"]		= "Agregar un hijo o una hija";
$pgv_lang["add_source"] 			= "Añadir una nueva cita de fuente";
$pgv_lang["add_source_lbl"] 		= "Agregar Cita de Fuente";
$pgv_lang["add_wife"]				= "Agregar esposa";
$pgv_lang["add_wife_to_family"] 	= "Agregar esposa a esta familia";
$pgv_lang["advanced_search_discription"] = "Búsqueda avanzada del sitio";
$pgv_lang["auto_thumbnail"]			= "Miniatura automática";
$pgv_lang["basic_search"]			= "buscar";
$pgv_lang["basic_search_discription"] = "Búsqueda básica del sitio";
$pgv_lang["birthdate_search"]		= "Fecha de nacimiento: ";
$pgv_lang["birthplace_search"]		= "Lugar de nacimiento: ";
$pgv_lang["change"]					= "Cambiar";
$pgv_lang["change_family_instr"]	= "Utilice esta página para cambiar o eliminar miembros de esta familia.<br /><br />Para cada miembro de esta familia, puede usar el vínculo Cambiar para escoger otra persona diferente para ese papel en la familia. También puede utilizar el vínculo Borrar para eliminar esa persona de la familia.<br /><br />Cuando haya terminado de cambiar los miembros de la familia, haga clic en el botón Salvar para salvar los cambios.<br />";
$pgv_lang["change_family_members"]	= "Modificar los miembros de la familia";
$pgv_lang["changes_occurred"]		= "Este registro tuvo los cambios siguientes:";
$pgv_lang["confirm_remove"]			= "¿Está seguro de que quiere desconectar este hijo de la familia?";
$pgv_lang["confirm_remove_object"]	= "¿Está seguro de querer borrar este objeto de la base de datos?";
$pgv_lang["create_repository"]		= "Crear Repositorio";
$pgv_lang["create_source"]			= "Crear una nueva fuente";
$pgv_lang["current_person"]         = "La misma persona";
$pgv_lang["date"]					= "Fecha";
$pgv_lang["deathdate_search"]		= "Fecha de defunción: ";
$pgv_lang["deathplace_search"]		= "Lugar de defunción: ";
$pgv_lang["delete_dir_success"]		= "Directorios de objetos audiovisuales y miniaturas eliminados con éxito.";
$pgv_lang["delete_file"]			= "Borrar archivo";
$pgv_lang["delete_repo"]			= "Borrar repositorio";
$pgv_lang["directory_not_empty"]	= "El directorio no está vacío.";
$pgv_lang["directory_not_exist"]	= "El directorio no existe.";
$pgv_lang["error_remote"]           = "Ha seleccionado un sitio remoto.";
$pgv_lang["error_same"]             = "Ha seleccionado el mismo sitio.";
$pgv_lang["external_file"]			= "El objeto audiovisual no existe como archivo en este servidor.  No puede borrarse, moverse o cambiársele el nombre.";
$pgv_lang["file_missing"]			= "Archivo no recibido. Envíelo de nuevo.";
$pgv_lang["file_partial"]			= "Archivo parcialmente subido, por favor inténtelo de nuevo";
$pgv_lang["file_success"]			= "Archivo correctamente subido";
$pgv_lang["file_too_big"]			= "El archivo a subir excede el tamaño permitido";
$pgv_lang["folder"]		 			= "Carpeta en el servidor";
$pgv_lang["gedcom_editing_disabled"]	= "El administrador ha deshabilitado las modificaciones a este GEDCOM.";
$pgv_lang["gedcomid"]				= "ID personal";
$pgv_lang["gedrec_deleted"] 		= "Registro GEDCOM correctamente eliminado.";
$pgv_lang["gen_thumb"]				= "Generar miniatura";
$pgv_lang["gender_search"]			= "Sexo: ";
$pgv_lang["generate_thumbnail"]		= "Generar miniatura automáticamente a partir de ";
$pgv_lang["hebrew_givn"]			= "Nombres de pila hebreos";
$pgv_lang["hebrew_surn"]			= "Apellido hebreo";
$pgv_lang["hide_changes"]			= "Haga clic aquí para ocultar los cambios.";
$pgv_lang["highlighted"]			= "Imagen destacada";
$pgv_lang["illegal_chars"]			= "Nombre en blanco o con caracteres no permitidos";
$pgv_lang["invalid_search_multisite_input"] = "Por favor, introduzca uno de los siguientes:  Nombre, fecha de nacimiento, lugar de nacimiento, fecha de defunción, lugar de defunción o sexo ";
$pgv_lang["invalid_search_multisite_input_gender"] = "Por favor, busque de nuevo dando más información que el sexo";
$pgv_lang["label_diff_server"]      = "Distinto sitio web";
$pgv_lang["label_location"]         = "Ubicación";
$pgv_lang["label_password_id2"]		= "Contraseña: ";
$pgv_lang["label_rel_to_current"]   = "Parentesco con la persona actual";
$pgv_lang["label_remote_id"]        = "ID de la persona remota";
$pgv_lang["label_same_server"]      = "Mismo sitio web";
$pgv_lang["label_site"]             = "Sitio";
$pgv_lang["label_site_url"]         = "URL del sitio:";
$pgv_lang["label_username_id2"]		= "Nombre de usuario: ";
$pgv_lang["lbl_server_list"]        = "Utilizar un sitio existente.";
$pgv_lang["lbl_type_server"]         = "Teclee un nuevo sitio.";
$pgv_lang["link_as_child"]			= "Vincular esta persona como hijo o hija de una familia ya existente";
$pgv_lang["link_as_husband"]		= "Vincular esta persona como esposo en una familia ya existente";
$pgv_lang["link_success"]			= "Vínculo agregado con éxito";
$pgv_lang["link_to_existing_media"]		= "Vincular un objeto audiovisual ya existente";
$pgv_lang["max_media_depth"]		= "Sólo puede descender #MEDIA_DIRECTORY_LEVELS# directorios";
$pgv_lang["max_upload_size"]		= "Tamaño Máximo de Subida: ";
$pgv_lang["media_deleted"]			= "Directorio de objetos audiovisuales eliminado con éxito.";
$pgv_lang["media_exists"]			= "El archivo audiovisual ya existe.";
$pgv_lang["media_file"] 			= "Archivos Multimedia";
$pgv_lang["media_file_deleted"]		= "Archivo audiovisual borrado con éxito.";
$pgv_lang["media_file_moved"]			= "Se trasladó el archivo audiovisual.";
$pgv_lang["media_file_not_moved"]	= "No se pudo trasladar el archivo audiovisual.";
$pgv_lang["media_file_not_renamed"]	= "No se pudo trasladar o cambiar el nombre del archivo audiovisual.";
$pgv_lang["media_thumb_exists"]		= "La miniatura del archivo audiovisual ya existe.";
$pgv_lang["multiple_gedcoms"]		= "Este archivo está vinculado con otra base de datos genealógica en este servidor. No puede borrarse, moverse o se le puede cambiar de nombre hasta que se hayan eliminado estos vínculos.";
$pgv_lang["must_provide"]			= "Debe suministrar un ";
$pgv_lang["name_search"]			= "Nombre: ";
$pgv_lang["new_repo_created"]		= "Creado el nuevo repositorio";
$pgv_lang["new_source_created"] 	= "Nueva Fuente creada exitosamente.";
$pgv_lang["no_changes"] 			= "Actualmente no existen cambios que deban ser revisados.";
$pgv_lang["no_known_servers"]		= "No se conocen servidores<br />No se obtendrán resultados";
$pgv_lang["no_temple"]				= "No Temple - Living Ordinance";
$pgv_lang["no_upload"]				= "La subida de archivos multimedia no se permite porque ha sido desactivada o porque no existen permisos de escritura en el directorio.";
$pgv_lang["paste_id_into_field"]	= "Pegar el siguiente ID de fuente en los campos de edición para referenciar esta fuente";
$pgv_lang["paste_rid_into_field"]	= "Pegue el siguiente ID de repositorio en sus campos de edición para referenciar este repositorio.";
$pgv_lang["photo_replace"] = "¿Quiere sustituir la foto vieja por ésta?";
$pgv_lang["privacy_not_granted"]	= "No tiene acceso a";
$pgv_lang["privacy_prevented_editing"]	= "La configuración de privacidad le impide editar este registro.";
$pgv_lang["record_marked_deleted"]		= "Este registro ha sido marcado para borrar si lo aprueba el administrador.";
$pgv_lang["replace_with"]			= "Reemplazar por";
$pgv_lang["show_changes"]			= "Este registro ha sido actualizado.  Haga clic aquí para ver los cambios.";
$pgv_lang["thumb_genned"]			= "Miniatura #thumbnail# generada automáticamente.";
$pgv_lang["thumbgen_error"]			= "No se pudo generar miniatura para ";
$pgv_lang["thumbnail"]				= "Miniaturas";
$pgv_lang["title_remote_link"]      = "Agregar vínculo remoto";
$pgv_lang["undo"]					= "Deshacer";
$pgv_lang["undo_all"]				= "Deshacer todos los cambios";
$pgv_lang["undo_all_confirm"]		= "¿Está seguro de que quiere deshacer todos los cambios a este GEDCOM?";
$pgv_lang["undo_successful"]		= "Se ha deshecho correctamente";
$pgv_lang["update_successful"]		= "Actualizado correctamente";
$pgv_lang["upload"]					= "Subir";
$pgv_lang["upload_error"]			= "Hubo un error subiendo su archivo GEDCOM.";
$pgv_lang["upload_media"]			= "Subir archivos multimedia";
$pgv_lang["upload_media_help"]		= "~#pgv_lang[upload_media]#~<br /><br />Seleccione los archivos de su equipo local a subir a su servidor.  Todos los archivos se subirán al directorio <b>#MEDIA_DIRECTORY#</b> o a uno de sus subdirectorios.<br /><br />Los nombres de carpeta que indique se añadirán a #MEDIA_DIRECTORY#. Por ejemplo, #MEDIA_DIRECTORY#myfamily. Si no existe el directorio de miniaturas, se creará automáticamente.";
$pgv_lang["upload_successful"]		= "Proceso completo";
$pgv_lang["view_change_diff"]		= "Ver modificaciones";

?>
