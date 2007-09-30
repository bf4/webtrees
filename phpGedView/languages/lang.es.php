<<?php
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

// $pgv_lang["switch_lifespan"]		= "Show Lifespan chart";
$pgv_lang["switch_timeline"]		= "Mostrar Cronograma";
$pgv_lang["differences"]			= "Diferencias";
$pgv_lang["charts_block"]			= "Bloque de diagramas";
$pgv_lang["charts_block_descr"]		= "El bloque de Diagramas le permite colocar un diagrama en la página de bienvenida o en la del portal MiGedView.  Puede configurar que el bloque muestre ascendientes, descendientes o reloj de arena.  También puede elegir la persona raíz para el diagrama.";
$pgv_lang["chart_type"]				= "Tipo de diagrama";
$pgv_lang["changedate1"]			= "Fin del intervalo de fechas de cambio";
$pgv_lang["changedate2"]			= "Comienzo del intervalo de fechas de cambio";
$pgv_lang["changes_report"]			= "Informe de cambios";
$pgv_lang["search_place_word"]		= "Sólo palabras completas";
$pgv_lang["invalid_search_input"] 	= "Por favor, introduzca un Nombre, Apellido o Lugar además del Año";
$pgv_lang["duplicate_username"] 	= "Nombre de usuario duplicado.  Ya existe un usuario con ese nombre.  Por favor regrese y cree un usuario con otro nombre.";
$pgv_lang["cache_life"]				= "Tiempo de recuerdo";
$pgv_lang["genealogy"]					= "genealogía";
$pgv_lang["activate"]					= "Activar";
$pgv_lang["deactivate"]					= "Desactivar";
$pgv_lang["play"]					= "Reproducir";
$pgv_lang["stop"]					= "Detener";
$pgv_lang["random_media_start_slide"]	= "¿Comenzar la presentación al cargar la página?";
$pgv_lang["random_media_ajax_controls"]	= "¿Mostrar controles de presentación?";
$pgv_lang["description"]			= "Descripción";
$pgv_lang["current_dir"]			= "Directorio actual";
$pgv_lang["SHOW_ID_NUMBERS"]		= "Mostrar números de ID a continuación de los nombres:";
$pgv_lang["SHOW_HIGHLIGHT_IMAGES"]	= "Mostrar miniaturas en las cajas de las personas:";
$pgv_lang["view_img_details"]		= "Ver detalles de la imagen";
$pgv_lang["server_folder"]			= "Nombre de carpeta en el servidor";
$pgv_lang["media_options"]			= "Opciones para los objetos audiovisuales";
$pgv_lang["confirm_password"]					= "Confirme la contraseña.";
$pgv_lang["enter_email"]						= "Escriba una dirección de correo electrónico.";
$pgv_lang["enter_fullname"] 					= "Escriba su nombre completo.";
$pgv_lang["name"]					= "Nombre";
$pgv_lang["children"]				= "Hijos:";
$pgv_lang["child"]					= "Hijo";
$pgv_lang["family"] 				= "Familia";
$pgv_lang["as_child"]				= "Familia con los Padres";
$pgv_lang["source_menu"]			= "Opciones para la fuente";
$pgv_lang["other_records"]			= "Otros registros que vinculan con esta fuente:";
$pgv_lang["other_repo_records"]		= "Registros que enlazan con este repositorio:";
$pgv_lang["repo_info"]				= "Información del repositorio";
$pgv_lang["enter_terms"]			= "Escriba una palabra";
$pgv_lang["search_asso_label"]		= "Asociados";
$pgv_lang["search_asso_text"]		= "Mostrar personas/familias relacionadas        ";
$pgv_lang["search_DM"]				= "Daitch-Mokotoff";
$pgv_lang["search_fams"]			= "Familias";
$pgv_lang["search_gedcom"]			= "Buscar bases de datos";
$pgv_lang["search_geds"]			= "Bases de datos en las que buscar";
$pgv_lang["search_indis"]			= "Personas";
$pgv_lang["search_inrecs"]			= "Buscar";
$pgv_lang["search_prtall"]			= "Todos los nombres";
$pgv_lang["search_prthit"]			= "Nombres con correspondencia";
$pgv_lang["results_per_page"]		= "Resultados por página";
$pgv_lang["firstname_search"]		= "Nombre: ";
$pgv_lang["search_prtnames"]		= "Nombres de las<br />personas a mostrar:";
$pgv_lang["other_searches"]			= "Otras búsquedas";
$pgv_lang["add_to_cart"]			= "Añadir al Carrito";
$pgv_lang["view_gedcom"]			= "Ver GEDCOM";
$pgv_lang["welcome"]				= "Bienvenido";
$pgv_lang["son"]					= "Hijo";
$pgv_lang["daughter"]				= "Hija";
$pgv_lang["welcome_page"]			= "Página de Bienvenida";
$pgv_lang["editowndata"]			= "Mi Cuenta";
$pgv_lang["user_admin"] 			= "Administrador";
$pgv_lang["manage_media"]			= "Gestionar objetos multimedia";
$pgv_lang["search_general"]			= "Búsqueda general";
$pgv_lang["clipping_privacy"]		= "Algunos elementos no pudieron añadirse debido a restricciones de privacidad";
$pgv_lang["chart_new"]				= "Cuadro de Árbol Genealógico";
$pgv_lang["loading"]				= "Cargando...";
$pgv_lang["clear_chart"]			= "Limpiar el diagrama";
$pgv_lang["file_information"]		= "Información del archivo";
$pgv_lang["choose_file_type"]		= "Escoja el tipo de archivo";
$pgv_lang["add_individual_by_id"]		= "Agregar persona por ID";
$pgv_lang["advanced_options"]		= "Opciones avanzadas";
$pgv_lang["zip_files"]				= "Archivo(s) Zip";
$pgv_lang["include_media"]			= "Incluir objetos audiovisuales (implica utilizar archivos ZIP)";
$pgv_lang["roman_surn"]				= "Apellidos romanizados";
$pgv_lang["roman_givn"]				= "Nombres de pila romanizados";
$pgv_lang["include"]				= "Incluir:";
$pgv_lang["page_x_of_y"]				= "Página #GLOBALS[currentPage]# de #GLOBALS[lastPage]#";
$pgv_lang["options"]				= "Opciones:";
$pgv_lang["config_update_ok"]			= "Archivo de configuración actualizado correctamente.";
$pgv_lang["page_size"]					= "Tamaño de página";
$pgv_lang["record_not_found"]			= "No se pudo hallar el registro GEDCOM solicitado.  Puede deberse por haber un vínculo a una persona inexistente o por un archivo GEDCOM corrupto.";
$pgv_lang["result_page"]				= "Página de resultado";
$pgv_lang["edit_media"]					= "Editar objeto audiovisual";
$pgv_lang["wiki_main_page"]				= "Página principal de la Wiki";
$pgv_lang["wiki_users_guide"]			= "Guía de Usuario de la Wiki";
$pgv_lang["wiki_admin_guide"]			= "Guía del Administrador de la Wiki";
$pgv_lang["no_search_for"]			= "Asegúrese de seleccionar una opción por la que buscar.";
$pgv_lang["no_search_site"]			= "Asegúrese de seleccionar al menos un sitio remoto.";
$pgv_lang["search_sites"] 			= "Sitios a buscar";
$pgv_lang["site_list"]				= "Sitio: ";
$pgv_lang["site_had"]				= " contenía lo siguiente";
$pgv_lang["indi_is_remote"]			= "La información de esta persona se vinculó desde un sitio remoto.";
$pgv_lang["link_remote"]            = "Vincular persona remota";
$pgv_lang["label_search_engine_detected"]  = "Detectada araña de motor de búsqueda";

$pgv_lang["ex-spouse"] = "Ex-cónyuge";
$pgv_lang["ex-wife"] = "Ex-esposa";
$pgv_lang["ex-husband"] = "Ex-esposo";
$pgv_lang["noemail"] 				= "Direcciones sin correo electrónico";
$pgv_lang["onlyemail"] 				= "Sólo direcciones con correo electrónico";
$pgv_lang["maxviews_exceeded"]		= "Ha excedido la velocidad máxima de visitas, pruebe más tarde.";
$pgv_lang["broadcast_not_logged_6mo"]	= "Enviar mensaje a los usuarios que no han desde hace seis meses";
$pgv_lang["broadcast_never_logged_in"]	= "Enviar un mensaje a los usuarios que nunca se han conectado";
$pgv_lang["stats_to_show"]			= "Seleccione las estadísticas a mostrar en este bloque";
$pgv_lang["stat_avg_age_at_death"]	= "Edad media de fallecimiento";
$pgv_lang["stat_longest_life"]		= "Persona que vivió mas tiempo";
$pgv_lang["stat_most_children"]		= "Pareja con más hijos";
$pgv_lang["stat_average_children"]	= "Media de hijos por pareja";
$pgv_lang["stat_events"]			= "Total eventos";
$pgv_lang["stat_media"]				= "Objetos audiovisuales";
$pgv_lang["stat_surnames"]			= "Total apellidos";
$pgv_lang["stat_users"]				= "Total usuarios";
$pgv_lang["no_family_facts"]		= "No hay hechos para esta familia.";
$pgv_lang["stat_males"]				= "Total de hombres";
$pgv_lang["stat_females"]			= "Total de mujeres";

$pgv_lang["sunday_1st"]				= "D";
$pgv_lang["monday_1st"]				= "L";
$pgv_lang["tuesday_1st"]			= "M";
$pgv_lang["wednesday_1st"]			= "X";
$pgv_lang["thursday_1st"]			= "J";
$pgv_lang["friday_1st"]				= "V";
$pgv_lang["saturday_1st"]			= "S";

// $pgv_lang["jan_1st"]					= "Jan";
// $pgv_lang["feb_1st"]					= "Feb";
// $pgv_lang["mar_1st"]					= "March";
// $pgv_lang["apr_1st"]					= "April";
// $pgv_lang["may_1st"]					= "May";
// $pgv_lang["jun_1st"]					= "June";
// $pgv_lang["jul_1st"]					= "July";
// $pgv_lang["aug_1st"]					= "Aug";
// $pgv_lang["sep_1st"]					= "Sep";
// $pgv_lang["oct_1st"]					= "Oct";
// $pgv_lang["nov_1st"]					= "Nov";
// $pgv_lang["dec_1st"]					= "Dec";

$pgv_lang["edit_source"]			= "Editar fuente";
$pgv_lang["familybook_chart"]		= "Diagrama Libro Familiar";
$pgv_lang["family_of"]				= "Familia de:&nbsp;";
$pgv_lang["descent_steps"]			= "Pasos de descendencia";

$pgv_lang["cancel"]					= "Cancelar";
$pgv_lang["cookie_help"]			= "Esto sitio utiliza <i>cookies</i> para llevar la pista de su estado de conexión.<br /><br />Parece que las <i>cookies</i> no están habilitadas en su navegador. Debe habilitar las <i>cookies</i> para este sitio para poder entrar.  Puede consultar la ayuda de su navegador para averiguar como habilitarlas.";
//new stuff
//Individual
$pgv_lang["indi_is_remote"]			= "La información de esta persona se vinculó desde un sitio remoto.";
$pgv_lang["link_remote"]            = "Vincular persona remota";
//Add Remote Link
$pgv_lang["title_search_link"]      = "Agregar vínculo local";
$pgv_lang["label_site_url2"]        = "URL del sitio";
//new stuff

$pgv_lang["cancel"]					= "Cancelar";
$pgv_lang["delete_family_confirm"]	= "Borrar la familia preservará las personas, pero las dejará desconectadas unas de otras. ¿Está seguro de que desea borrar esta familia?";
$pgv_lang["delete_family"]			= "Borrar familia";
$pgv_lang["add_favorite"]			= "Agregar un nuevo favorito";
$pgv_lang["url"]					= "URL";
$pgv_lang["add_fav_enter_note"]		= "Introduzca opcionalmente una nota acerca de este favorito";
$pgv_lang["add_fav_or_enter_url"]	= "O<br />\nIntroduzca una URL y un título";
$pgv_lang["add_fav_enter_id"]		= "Introduzca un ID de Persona, Familia o Fuente";
$pgv_lang["next_email_sent"]		= "El próximo recordatorio por correo se enviará después del ";
$pgv_lang["last_email_sent"]		= "El último recordatorio por correo se envió el ";
$pgv_lang["remove_child"]			= "Desconectar este hijo de la familia";
$pgv_lang["link_new_husb"]			= "Agregar como marido una persona ya existente";
$pgv_lang["link_new_wife"]			= "Agregar como esposa una persona ya existente";
$pgv_lang["address_labels"]			= "Etiquetas de dirección";
$pgv_lang["filter_address"]			= "Mostrar direcciones que contengan:";
$pgv_lang["address_list"]			= "Lista de direcciones";
$pgv_lang["autocomplete"]			= "Autocompletar";
$pgv_lang["index_edit_advice"]		= "Resalte un nombre de bloque y haga clic a continuación en uno de los iconos de flecha para mover ese bloque en la dirección indicada.";
$pgv_lang["changelog"]				= "Cambios en la versión #VERSION#";
$pgv_lang["html_block_descr"]		= "Es un bloque de HTML simple que puede colocar en su página para añadir cualquier tipo de mensaje que desee.";
$pgv_lang["html_block_sample_part1"]	= "<p class=\"blockhc\"><b>Ponga su título aquí</b></p><br /><p>Haga clic en el botón de configurar";
$pgv_lang["html_block_sample_part2"]	= "para cambiar lo que se muestra aquí.</p>";
$pgv_lang["html_block_name"]		= "Bloque HTML";
$pgv_lang["htmlplus_block_name"]	= "HTML avanzado";
$pgv_lang["htmlplus_block_descr"]	= "Es un bloque de HTML que puede colocar en su página para añadir cualquier tipo de mensaje que desee.  Puede insertar referencias a la información de su GEDCOM en el texto HTML.";
$pgv_lang["htmlplus_block_templates"] = "Plantillas";
$pgv_lang["htmlplus_block_content"] = "Contenido";
$pgv_lang["htmlplus_block_narrative"] = "Estilo narrativo (sólo inglés)";
$pgv_lang["htmlplus_block_custom"]	= "Personalizado";
$pgv_lang["htmlplus_block_keyword"]	= "Ejemplos de palabras clave (sólo inglés)";
$pgv_lang["htmlplus_block_taglist"]	= "Lista de etiquetas";
$pgv_lang["htmlplus_block_compat"]	= "Modo de Compatibilidad";
$pgv_lang["htmlplus_block_current"]	= "Actual";
$pgv_lang["htmlplus_block_default"]	= "Predeterminado";
$pgv_lang["htmlplus_block_gedcom"]	= "Árbol genealógico";
$pgv_lang["htmlplus_block_birth"]	= "nacimiento";
$pgv_lang["htmlplus_block_death"]	= "defunción";
$pgv_lang["htmlplus_block_marrage"]	= "matrimonio";
$pgv_lang["htmlplus_block_adoption"]= "adopción";
$pgv_lang["htmlplus_block_burial"]	= "entierro";
$pgv_lang["htmlplus_block_census"]	= "añadido al censo";
$pgv_lang["num_to_show"]			= "Número de elementos a mostrar";
$pgv_lang["days_to_show"]			= "Número de días a mostrar";
$pgv_lang["before_or_after"]		= "¿Colocar contador antes o después del nombre?";
$pgv_lang["before"]					= "antes";
$pgv_lang["after"]					= "después";
$pgv_lang["config_block"]			= "Bloque de Configuración";
$pgv_lang["enter_comments"]			= "Por favor, indique cuál es su relación con estos datos en el campo Comentarios.";
$pgv_lang["comments"]				= "Comentarios";
$pgv_lang["child-family"]			= "Padres y hermanos";
$pgv_lang["spouse-family"]			= "Cónyuge e hijos";
$pgv_lang["direct-ancestors"]		= "Ascendientes por línea directa";
$pgv_lang["ancestors"]				= "Ascendientes por línea directa y sus familias";
$pgv_lang["descendants"]			= "Descendientes";
$pgv_lang["choose_relatives"]		= "Escoja parientes";
$pgv_lang["relatives_report"]		= "Informe de parientes";
$pgv_lang["total_living"]			= "Número total de personas vivas";
$pgv_lang["total_dead"]				= "Número total de personas fallecidas";
$pgv_lang["total_not_born"]			= "Número total de personas no nacidas aún";
$pgv_lang["remove_custom_tags"]		= "¿Eliminar etiquetas propias de PGV? (p.ej. _PGVU, _THUM)";
$pgv_lang["cookie_login_help"]		= "Este sitio le recuerda de una entrada previa. Esto le permite acceder a información privada o a otros servicios de usuario, pero para editar o administrar el sitio, debe entrar de nuevo para más seguridad.";
$pgv_lang["remember_me"]			= "¿Recordarme desde este equipo?";
$pgv_lang["fams_with_surname"]		= "Familias con el apellido #surname#";
$pgv_lang["support_contact"]		= "Contacto para ayuda técnica";
$pgv_lang["genealogy_contact"]		= "Contacto para genealogía";
$pgv_lang["common_upload_errors"]	= "Este error significa probablemente que el archivo que intentó subir tenía un tamaño mayor que el límite fijado por su servidor. El límite por defecto en PHP es de 2MB.  Puede ponerse en contacto con el grupo de soporte de su servidor para que eleven el límite en el archivo php.ini o puede subir el archivo usando FTP. Utilice la página <a href=\"uploadgedcom.php?action=add_form\"><b>Añadir GEDCOM</b></a> para añadir un archivo GEDCOM que haya subido mediante FTP.";
$pgv_lang["total_memory_usage"]		= "Memoria total utilizada:";
$pgv_lang["mothers_family_with"]	= "Familia de la madre con ";
$pgv_lang["fathers_family_with"]	= "Familia del padre con ";
$pgv_lang["family_with"]			= "Familia con";
$pgv_lang["halfsibling"]			= "Medio hermano o hermana";
$pgv_lang["halfbrother"]			= "Medio hermano";
$pgv_lang["halfsister"]				= "Medio hermana";
$pgv_lang["family_timeline"]		= "Mostrar la familia en el cronograma";
$pgv_lang["children_timeline"]		= "Mostrar los hijos en el cronograma";
$pgv_lang["other"]					= "Otro";
$pgv_lang["sort_by_marriage"]		= "Ordenar por fecha de matrimonio";
$pgv_lang["reorder_families"]		= "Reordenar familias";
$pgv_lang["indis_with_surname"]		= "Personas con el apellido #surname#";
$pgv_lang["first_letter_fname"]		= "Elija una letra para mostrar las personas cuyo nombre empieza por esa letra.";
$pgv_lang["total_names"]			= "Número total de nombres";
$pgv_lang["top10_pageviews_nohits"]	= "No existen actualmente visitas que mostrar.";
$pgv_lang["top10_pageviews_msg"]	= "Para que funcione este bloque, Deben activarse los contadores en la configuración del GEDCOM.";
$pgv_lang["review_changes_descr"]	= "El bloque de Cambios Pendientes dará a los usuarios con derechos de modificación una lista de los registros que se han modificado en el sitio y que han aún de ser revisados y aceptados. Estos cambios están pendientes de su aceptación o rechazo.<br /><br />Si se activa este bloque, Los usuarios con derechos de aceptación recibirán un mensaje de correo electrónico una vez al día notificándoles la existencia de cambios pendientes de revisión.";
$pgv_lang["review_changes_block"]	= "Cambios pendientes";
$pgv_lang["review_changes_email"]	= "¿Enviar correos recordatorios?";
$pgv_lang["review_changes_email_freq"]	= "Frecuencia de los correos recordatorios (días)";
$pgv_lang["review_changes_subject"]	= "PhpGedView - Revisar cambios";
$pgv_lang["review_changes_body"]	= "Se han introducido cambios a la base de datos genealógica.  Estos cambios deben ser revisados y aceptados antes de que sean visibles a todos los usuarios. Por favor, utilice la URL que se indica a continuación para entrar en ese sitio PhpGedView y revisar los cambios.";
$pgv_lang["show_pending"]		= "Mostrar cambios pendientes";
$pgv_lang["show_spouses"]		= "Mostrar cónyuges";
$pgv_lang["quick_update_title"] = "Modificación rápida";
$pgv_lang["quick_update_instructions"] = "Esta página le permite modificar de forma rápida la información de una persona. Sólo necesita indicar la información que es nueva o que es diferente de lo que consta en la base de datos. Los cambios que envíe serán revisados posteriormente por un administrador antes de que resulten visibles para todos los usuarios.";
$pgv_lang["update_name"] = "Modificar Nombre";
$pgv_lang["update_fact"] = "Modificar un Hecho";
$pgv_lang["update_fact_restricted"] = "Las modificaciones a este hecho están restringidas.";
$pgv_lang["update_photo"] = "Cambiar Foto";
$pgv_lang["select_fact"] = "Seleccione un hecho...";
$pgv_lang["update_address"] = "Cambiar Dirección";
$pgv_lang["top10_pageviews_descr"]	= "Este bloque mostrará los 10 registros que hayan sido vistos más veces. Este bloque requiere que se activen los Contadores de Visitas en la configuración de GEDCOM.";
$pgv_lang["top10_pageviews"]		= "Elementos Más Vistos";
$pgv_lang["top10_pageviews_block"]		= "Bloque de registros más vistos";
$pgv_lang["stepdad"]				= "Padrastro";
$pgv_lang["stepmom"]				= "Madrastra";
$pgv_lang["stepsister"]				= "Hermanastra";
$pgv_lang["stepbrother"]			= "Hermanastro";
$pgv_lang["fams_charts"]			= "Opciones para la Familia";
$pgv_lang["indis_charts"]			= "Opciones para la persona";
$pgv_lang["locked"]					= "no modificable";
$pgv_lang["privacy"]				= "privacidad";
$pgv_lang["number_sign"]			= "nº";

//-- GENERAL HELP MESSAGES
$pgv_lang["qm"] 					= "?";
$pgv_lang["qm_ah"]					= "?";
$pgv_lang["page_help"]				= "Ayuda";
$pgv_lang["help_for_this_page"] 	= "Ayuda con ésta página";
$pgv_lang["help_contents"]			= "Contenido de ayuda";
$pgv_lang["show_context_help"]		= "Mostrar ayuda contextual";
$pgv_lang["hide_context_help"]		= "Ocultar ayuda contextual";
$pgv_lang["sorry"]					= "<b>Lo siento, no se ha finalizado aún el texto de ayuda para esta página</b>";
$pgv_lang["help_not_exist"] 		= "<b>Todavía no existe un texto de ayuda para esta página o elemento</b>";
$pgv_lang["var_not_exist"]			= "<span style=font-weight: bold>La variable de idioma no existe. Por favor, repórtelo pues se trata de un error.</span>";
$pgv_lang["resolution"] 			= "Resolución de pantalla";
$pgv_lang["menu"]					= "Menú";
$pgv_lang["header"] 				= "Encabezamiento";
$pgv_lang["imageview"]				= "Visualizador de imágenes";

//-- CONFIG FILE MESSAGES
$pgv_lang["login_head"] 			= "Entrada de usuario PhpGedView";
$pgv_lang["for_support"]			= "Para ayuda o información póngase en contacto con";
$pgv_lang["for_contact"]			= "Para ayuda por cuestiones de genealogía contacte con";
$pgv_lang["for_all_contact"]		= "Para soporte técnico o por cuestiones de genealogía, por favor contacte con";
$pgv_lang["build_error"]			= "El archivo GEDCOM ha sido actualizado.";
$pgv_lang["choose_username"]		= "Nombre de usuario deseado";
$pgv_lang["username"]				= "Usuario:";
$pgv_lang["invalid_username"]		= "El nombre de usuario contiene caracteres inválidos";
$pgv_lang["firstname"]				= "Nombre de pila";
$pgv_lang["lastname"]				= "Apellidos";
$pgv_lang["choose_password"]		= "Contraseña deseada";
$pgv_lang["password"]				= "Contraseña:";
$pgv_lang["confirm"]				= "Confirme Contraseña:";
$pgv_lang["login"]					= "Entrar";
$pgv_lang["logout"] 				= "Salir";
$pgv_lang["admin"]					= "Admin.";
$pgv_lang["logged_in_as"]			= "Registrado ";
$pgv_lang["my_pedigree"]			= "Mi Árbol";
$pgv_lang["my_indi"]				= "Mi Ficha";
$pgv_lang["yes"]					= "Sí";
$pgv_lang["no"] 					= "No";
$pgv_lang["change_theme"]			= "Cambiar el Tema";

//-- INDEX (PEDIGREE_TREE) FILE MESSAGES
$pgv_lang["index_header"]			= "Árbol de ascendientes";
$pgv_lang["gen_ped_chart"]			= "#PEDIGREE_GENERATIONS# Generaciones de Ascendientes";
$pgv_lang["generations"]			= "Generaciones";
$pgv_lang["view"]					= "Ver";
$pgv_lang["fam_spouse"] 			= "Familia con el cónyuge:";
$pgv_lang["root_person"]			= "ID persona raíz:";
$pgv_lang["hide_details"]			= "Ocultar detalles";
$pgv_lang["show_details"]			= "Ver detalles";
$pgv_lang["person_links"]			= "Vínculos a informes y familiares cercanos relacionados con esta persona.";
$pgv_lang["zoom_box"]				= "Aumentar/Disminuir registro.";
$pgv_lang["orientation"]			= "Orientación";
$pgv_lang["portrait"]				= "Natural";
$pgv_lang["landscape"]				= "Apaisado";
$pgv_lang["start_at_parents"]		= "Comenzar en los padres";
$pgv_lang["charts"] 				= "Diagramas";
$pgv_lang["lists"]					= "Listas";
$pgv_lang["max_generation"] 		= "El número máximo de generaciones es #PEDIGREE_GENERATIONS#.";
$pgv_lang["min_generation"] 		= "El número mínimo de generaciones es 3";
$pgv_lang["box_width"] 				= "Ancho de la caja";

//-- FUNCTIONS FILE MESSAGES
$pgv_lang["unable_to_find_family"]	= "Imposible encontrar una familia con el ID";
$pgv_lang["unable_to_find_record"]	= "Imposible encontrar un registro con el ID";
$pgv_lang["title"]					= "Títle:";
$pgv_lang["living"] 				= "Viva";
$pgv_lang["private"]				= "Privada";
$pgv_lang["birth"]					= "Nac:";
$pgv_lang["death"]					= "Def:";
$pgv_lang["descend_chart"]			= "Descendientes";
$pgv_lang["individual_list"]		= "Personas";
$pgv_lang["family_list"]			= "Familias";
$pgv_lang["source_list"]			= "Fuentes";
$pgv_lang["place_list"] 			= "Lugares";
$pgv_lang["place_list_aft"] 		= "Jerarquía de lugares tras";
$pgv_lang["media_list"] 			= "Multimedia";
$pgv_lang["search"] 				= "Buscar";
$pgv_lang["clippings_cart"] 		= "Carrito genealógico";
$pgv_lang["print_preview"]			= "Vista Preliminar";
$pgv_lang["cancel_preview"] 		= "Regresar a la vista normal";
$pgv_lang["change_lang"]			= "Cambiar el Idioma";
$pgv_lang["print"]					= "Imprimir";
$pgv_lang["total_queries"]			= "Consultas a la Base de Datos: ";
$pgv_lang["total_privacy_checks"]	= "Total de comprobaciones de privacidad:";
$pgv_lang["back"]					= "Regresar";

//-- INDIVIDUAL FILE MESSAGES
$pgv_lang["aka"]					= "También conocido/a como";
$pgv_lang["male"]					= "Hombre";
$pgv_lang["female"] 				= "Mujer";
$pgv_lang["temple"] 				= "Templo SUD";
$pgv_lang["temple_code"]			= "Código Templo SUD:";
$pgv_lang["status"] 				= "Estatus";
$pgv_lang["source"] 				= "Fuente";
$pgv_lang["text"]					= "Texto:";
$pgv_lang["note"]					= "Nota";
$pgv_lang["NN"] 					= "Apellido desconocido";
$pgv_lang["PN"] 					= "Nombre desconocido";
$pgv_lang["unrecognized_code"]		= "Código GEDCOM desconocido";
$pgv_lang["unrecognized_code_msg"]	= "Este es un error y desearíamos corregirlo. Por favor reporte este error a";
$pgv_lang["indi_info"]				= "Ficha Personal";
$pgv_lang["pedigree_chart"] 		= "Árbol de Ascendientes";
$pgv_lang["individual"]				= "Persona";
$pgv_lang["as_spouse"]				= "Familia con el Cónyuge";
$pgv_lang["privacy_error"]			= "Los detalles de esta persona son privados.<br />Para más información contactar con";
$pgv_lang["more_information"]		= "Para más información contacte con";
$pgv_lang["given_name"] 			= "Nombre habitual:";
$pgv_lang["surname"]				= "Apellido:";
$pgv_lang["suffix"] 				= "Sufijo:";
$pgv_lang["sex"]					= "Sexo";
$pgv_lang["personal_facts"] 		= "Detalles Personales";
$pgv_lang["type"]					= "Tipo";
$pgv_lang["parents"]				= "Padres:";
$pgv_lang["siblings"]				= "Hermano";
$pgv_lang["father"] 				= "Padre";
$pgv_lang["mother"] 				= "Madre";
$pgv_lang["parent"] 				= "Padre o madre";
$pgv_lang["relatives"]				= "Familiares Cercanos";
$pgv_lang["relatives_events"]		= "Eventos de familiares cercanos";
$pgv_lang["spouse"] 				= "Cónyuge";
$pgv_lang["spouses"] 				= "Cónyuges";
$pgv_lang["surnames"]				= "Apellidos";
$pgv_lang["adopted"]				= "Adoptado";
$pgv_lang["foster"] 				= "Adoptivo";
$pgv_lang["sealing"]				= "Sellamiento";
// $pgv_lang["challenged"]				= "Challenged";
// $pgv_lang["disproved"]				= "Disproved";
$pgv_lang["infant"]					= "Niño de corta edad";
$pgv_lang["stillborn"]				= "Nacido muerto";
$pgv_lang["deceased"]				= "Difunto";
$pgv_lang["link_as_wife"]			= "Vincular esta persona como esposa en una familia ya existente";
$pgv_lang["no_tab1"]				= "No hay hechos para esta persona.";
$pgv_lang["no_tab2"]				= "No hay notas para esta persona.";
$pgv_lang["no_tab3"]				= "No hay citas de fuentes para esta persona.";
$pgv_lang["no_tab4"]				= "No hay objetos audiovisuales para esta persona.";
$pgv_lang["no_tab5"]				= "No hay parientes cercanos de esta persona.";
$pgv_lang["no_tab6"]				= "No hay diario de investigación conectado a esta persona";

//-- FAMILY FILE MESSAGES
$pgv_lang["family_info"]			= "Información Familiar";
$pgv_lang["family_group_info"]		= "Información del Grupo Familiar";
$pgv_lang["husband"]				= "Esposo";
$pgv_lang["wife"]					= "Esposa";
$pgv_lang["marriage"]				= "Matrimonio:";
$pgv_lang["lds_sealing"]			= "Sellamiento SUD:";
$pgv_lang["marriage_license"]		= "Licencia matrimonial:";
$pgv_lang["no_children"]			= "Sin Hijos";
$pgv_lang["childless_family"]		= "Esta familia no tuvo hijos";
$pgv_lang["parents_timeline"]		= "Ver a los padres en<br />el cronograma";

//-- CLIPPINGS FILE MESSAGES
$pgv_lang["clip_cart"]				= "Carrito Genealógico";
$pgv_lang["which_links"]			= "¿Qué vínculos de esta familia le gustaría añadir?";
$pgv_lang["just_family"]			= "Añadir este registro familiar.";
$pgv_lang["parents_and_family"] 	= "Añadir los padres con este registro familiar.";
$pgv_lang["parents_and_child"]		= "Añadir los padres e hijos con este registro familiar.";
$pgv_lang["parents_desc"]			= "Añadir los padres y todos los registros de los descendientes con este registro familiar.";
$pgv_lang["continue"]				= "Continúe Añadiendo";
$pgv_lang["which_p_links"]			= "¿Qué vínculos de esta persona le gustaría añadir también?";
$pgv_lang["just_person"]			= "Añadir esta persona.";
$pgv_lang["person_parents_sibs"]	= "Añadir esta persona, sus padres y hermanos.";
$pgv_lang["person_ancestors"]		= "Añadir esta persona y su línea de ascendientes directos.";
$pgv_lang["person_ancestor_fams"]	= "Añadir esta persona, su línea de ascendientes directos y sus familias.";
$pgv_lang["person_spouse"]			= "Añadir esta persona, su cónyuge, e hijos.";
$pgv_lang["person_desc"]			= "Añadir esta persona, su cónyuge, y todos los registros de sus descendientes.";
$pgv_lang["which_s_links"]			= "¿Qué registros vinculados a esta fuente le gustaría añadir?";
$pgv_lang["just_source"]		= "Agregar sólo esta fuente.";
$pgv_lang["linked_source"]		= "Agregar esta fuente y las familias y personas vinculadas a ella.";
$pgv_lang["person_private"] 		= "Los detalles sobre esta persona son privados. Los detalles personales no se pueden ver.";
$pgv_lang["family_private"] 		= "Los detalles sobre esta familia son privados. Los detalles de esta familia no se pueden ver.";
$pgv_lang["download"]				= "Haga clic con el botón derecho (control-click en un Mac) en los vínculos y seleccione \"Salvar como\" para descargar los archivos.";
$pgv_lang["cart_is_empty"]			= "Su carrito está vacío.";
$pgv_lang["id"] 					= "ID";
$pgv_lang["name_description"]		= "Nombre / Descripción";
$pgv_lang["remove"] 				= "Vaciar";
$pgv_lang["empty_cart"] 			= "Vaciar carrito";
$pgv_lang["download_now"]			= "Descargar ahora";
$pgv_lang["download_file"]			= "Descargar archivo";
$pgv_lang["indi_downloaded_from"]	= "La información de esta persona se descargó desde:";
$pgv_lang["family_downloaded_from"] = "La información de esta familia se descargó desde:";
$pgv_lang["source_downloaded_from"] = "La información de esta fuente se descargó desde:";

//-- PLACELIST FILE MESSAGES
$pgv_lang["connections"]			= "Lugares encontrados<br />Ver resultados";
$pgv_lang["top_level"]				= "Raíz";
$pgv_lang["form"]					= "Los lugares se codifican en la forma: ";
$pgv_lang["default_form"]			= "Parroquia, Ayuntamiento, Provincia, País/Estado";
$pgv_lang["default_form_info"]		= "(Por defecto)";
$pgv_lang["unknown"]				= "Desconocido";
$pgv_lang["individuals"]			= "Personas";
$pgv_lang["view_records_in_place"]	= "Ver todos los registros encontrados para este lugar";
$pgv_lang["place_list2"] 			= "Lista de lugares";
$pgv_lang["show_place_hierarchy"]	= "Mostrar Lugares Jerárquicamente";
$pgv_lang["show_place_list"]		= "Mostrar todos los lugares en una lista";
$pgv_lang["total_unic_places"]		= "Total de Lugares Distintos";

//-- MEDIALIST FILE MESSAGES
$pgv_lang["external_objects"]		= "Objetos externos";
$pgv_lang["multi_title"]			= "Lista Multimedia";
$pgv_lang["media_found"]			= "Objetos Multimedia";
$pgv_lang["view_person"]			= "Ver Persona";
$pgv_lang["view_family"]			= "Ver Familia";
$pgv_lang["view_source"]			= "Ver Fuente";
$pgv_lang["view_object"]			= "Ver objeto";
$pgv_lang["prev"]					= "< Anterior";
$pgv_lang["next"]					= "Siguiente &gt;";
$pgv_lang["next_image"]				= "Siguiente imagen";
$pgv_lang["file_not_found"] 		= "No encontrado.";
$pgv_lang["medialist_show"] 		= "Mostrar";
$pgv_lang["per_page"]				= "objetos multimedia por página";
$pgv_lang["media_format"]			= "Formato del archivo multimedia";
$pgv_lang["image_size"]				= "Dimensiones de la imagen -- ";
$pgv_lang["media_id"]				= "ID objeto multimedia";
$pgv_lang["invalid_id"]				= "No existe ese ID en este archivo GEDCOM";
$pgv_lang["record_updated"]			= "Registro #pid# borrado con éxito.";
$pgv_lang["record_not_updated"]		= "No se pudo actualizar el archivo #pid#.";
$pgv_lang["record_removed"]			= "Registro #xref# eliminado con éxito del GEDCOM.";
$pgv_lang["record_not_removed"]		= "No se pudo eliminar el registro #xref# del GEDCOM.";
$pgv_lang["record_added"]			= "Registro #xref# añadido con éxito al GEDCOM.";
$pgv_lang["record_not_added"]		= "No se pudo añadir el registro #xref# al GEDCOM.";

//-- SEARCH FILE MESSAGES
$pgv_lang["soundex_search"] 		= "Búsqueda por Soundex";
$pgv_lang["sources"]				= "Fuentes";
$pgv_lang["lastname_search"]		= "Apellido: ";
$pgv_lang["search_place"]			= "Lugar: ";
$pgv_lang["search_year"]			= "Año: ";
$pgv_lang["no_results"] 			= "No se encontraron resultados.";
$pgv_lang["search_soundex"]			= "Búsqueda Soundex";
$pgv_lang["search_replace"]			= "Buscar y reemplazar";
$pgv_lang["search_sources"]			= "Fuentes";
$pgv_lang["search_more_chars"]      = "Por favor, introduzca más de un carácter";
$pgv_lang["search_soundextype"]		= "Tipo de Soundex:";
$pgv_lang["search_russell"]			= "Básico";
$pgv_lang["search_tagfilter"]		= "Filtro de exclusión";
$pgv_lang["search_tagfon"]			= "Excluir algunos datos no genealógicos";
$pgv_lang["search_tagfoff"]			= "Desactivado";
$pgv_lang["associate"]				= "asociado";
$pgv_lang["search_record"]			= "Registro completo";
$pgv_lang["search_to"]				= "a";

//-- SOURCELIST FILE MESSAGES
$pgv_lang["titles_found"]			= "Títulos";
$pgv_lang["find_source"]			= "Encontrar fuente";

//-- REPOLIST FILE MESSAGES
$pgv_lang["repo_list"]				= "Repositorios";
$pgv_lang["repos_found"]			= "Repositorios encontrados";
$pgv_lang["find_repository"]		= "Encontrar repositorio";
$pgv_lang["total_repositories"]		= "Número total de repositorios";
$pgv_lang["confirm_delete_repo"]	= "¿Seguro que quiere borrar este repositorio de la base de datos?";


//-- SOURCE FILE MESSAGES
$pgv_lang["source_info"]			= "Información de la Fuente";
$pgv_lang["people"] 				= "Personas";
$pgv_lang["families"]				= "Familias";
$pgv_lang["total_sources"]			= "Número total de fuentes";

//-- BUILDINDEX FILE MESSAGES
$pgv_lang["invalid_gedformat"]		= "Formato GEDCOM 5.5 inválido";
$pgv_lang["exec_time"]				= "Tiempo de ejecución:";
$pgv_lang["unable_to_create_index"] = "No se pudo crear un archivo índice. Asegúrese de que los permisos de escritura están habilitados en el directorio de PhpGedView.";
$pgv_lang["changes_present"]		= "Hay cambios pendientes de revisión en el GEDCOM actual.  Si prosigue con la Importación, los cambios pendientes se incorporarán a la base de datos inmediatamente. Debería revisar los cambios pendientes antes de proseguir con la Importación.";
$pgv_lang["sec"]					= "sec.";

//-- INDIVIDUAL AND FAMILYLIST FILE MESSAGES
$pgv_lang["total_fams"] 			= "Familias encontradas";
$pgv_lang["total_indis"]			= "Total personas";
$pgv_lang["notes"]					= "Notas";
$pgv_lang["ssourcess"]				= "Fuentes";
$pgv_lang["media"]					= "Medios";
$pgv_lang["name_contains"]			= "El nombre contiene:";
$pgv_lang["filter"] 				= "Filtro";
$pgv_lang["find_individual"]		= "Encontrar persona con ID";
$pgv_lang["find_familyid"]			= "Encontrar ID de la Familia";
$pgv_lang["find_sourceid"]			= "Encontrar ID de la Fuente";
$pgv_lang["find_specialchar"]		= "Encontrar caracteres especiales";
$pgv_lang["magnify"]				= "Agrandar";
$pgv_lang["skip_surnames"]			= "Ocultar listas de apellidos";
$pgv_lang["show_surnames"]			= "Mostrar listas de apellidos";
$pgv_lang["all"]					= "Todas";
$pgv_lang["hidden"]					= "Oculto";
$pgv_lang["confidential"]			= "Confidencial";
$pgv_lang["alpha_index"]				= "Índice alfabético";
$pgv_lang["name_list"] 				= "Lista de nombres";
$pgv_lang["firstname_alpha_index"] 	= "Índice alfabético de nombres de pila";
$pgv_lang["roots"]		 				= "Raíces";
$pgv_lang["leaves"] 					= "Hojas";
$pgv_lang["widow"] 					= "Viuda";
$pgv_lang["widower"] 				= "Viudo";

//-- TIMELINE FILE MESSAGES
$pgv_lang["age"]					= "Edad";
$pgv_lang["days"]					= "días";
$pgv_lang["months"]					= "meses";
$pgv_lang["years"]					= "años";
$pgv_lang["day1"]					= "día";
$pgv_lang["month1"]					= "mes";
$pgv_lang["year1"]					= "año";
$pgv_lang["timeline_title"] 		= "Cronograma";
$pgv_lang["timeline_chart"] 		= "Cronograma";
$pgv_lang["remove_person"]			= "Borrar Persona";
$pgv_lang["show_age"]				= "Ver Edad";
$pgv_lang["add_another"]			= "Agregar otra persona al diagrama:<br />Id Persona:";
$pgv_lang["find_id"]				= "Buscar ID";
$pgv_lang["show"]					= "Ver";
$pgv_lang["year"]					= "Año:";
$pgv_lang["timeline_instructions"]	= "En los navegadores actuales puede hacer clic en las cajas y arrastrarlas por el diagrama.";
$pgv_lang["zoom_in"]				= "Acercar";
$pgv_lang["zoom_out"]				= "Alejar";
$pgv_lang["timeline_beginYear"] = "Año de comienzo";
$pgv_lang["timeline_endYear"] = "Año de fin";
$pgv_lang["timeline_scrollSpeed"] = "Velocidad";
$pgv_lang["timeline_controls"] = "Controles del cronograma";
$pgv_lang["include_family"] = "Incluir la familia inmediata";
// $pgv_lang["lifespan_chart"] = "Lifespan Chart";

$pgv_lang["cal_gregorian"]            = "Gregoriano";
$pgv_lang["cal_julian"]               = "Juliano";
$pgv_lang["cal_french"]               = "Francés";
$pgv_lang["cal_jewish"]               = "Judío";
$pgv_lang["cal_hebrew"]               = "Hebreo";
$pgv_lang["cal_jewish_and_gregorian"] = "Judío y Gregoriano";
$pgv_lang["cal_hebrew_and_gregorian"] = "Hebreo y Gregoriano";
$pgv_lang["cal_hijri"]                = "Hijri";
$pgv_lang["cal_arabic"]               = "Árabe";

//-- MONTH NAMES
$pgv_lang["jan"]					= "enero";
$pgv_lang["feb"]					= "febrero";
$pgv_lang["mar"]					= "marzo";
$pgv_lang["apr"]					= "abril";
$pgv_lang["may"]					= "mayo";
$pgv_lang["jun"]					= "junio";
$pgv_lang["jul"]					= "julio";
$pgv_lang["aug"]					= "agosto";
$pgv_lang["sep"]					= "septiembre";
$pgv_lang["oct"]					= "octubre";
$pgv_lang["nov"]					= "noviembre";
$pgv_lang["dec"]					= "diciembre";

$pgv_lang["vend"]         = "vendimiario";
$pgv_lang["brum"]         = "brumario";
$pgv_lang["frim"]         = "frimario";
$pgv_lang["nivo"]         = "nivoso";
$pgv_lang["pluv"]         = "pluvioso";
$pgv_lang["vent"]         = "ventoso";
$pgv_lang["germ"]         = "germinal";
$pgv_lang["flor"]         = "floreal";
$pgv_lang["prai"]         = "pradeal";
$pgv_lang["mess"]         = "messidor";
$pgv_lang["ther"]         = "termidor";
$pgv_lang["fruc"]         = "fructidor";
$pgv_lang["comp"]         = "días complementarios";

$pgv_lang["tsh"]          = "Tishrei";
$pgv_lang["csh"]          = "Jeshván";
$pgv_lang["ksl"]          = "Kislev";
$pgv_lang["tvt"]          = "Tevet";
$pgv_lang["shv"]          = "Shevat";
$pgv_lang["adr"]          = "Adar";
$pgv_lang["adr_leap_year"]= "Adar I";
$pgv_lang["ads"]          = "Adar II";
$pgv_lang["nsn"]          = "Nisán";
$pgv_lang["iyr"]          = "Iyar";
$pgv_lang["svn"]          = "Siván";
$pgv_lang["tmz"]          = "Tamuz";
$pgv_lang["aav"]          = "Av";
$pgv_lang["ell"]          = "Elul";

// $pgv_lang["muhar"]        = "Muharram";
// $pgv_lang["safar"]        = "Safar";
// $pgv_lang["rabi1"]        = "Rabi' al-awwal";
// $pgv_lang["rabi2"]        = "Rabi' al-thani";
// $pgv_lang["juma1"]        = "Jumada al-awwal";
// $pgv_lang["juma2"]        = "Jumada al-thani";
$pgv_lang["rajab"]        = "Rabí";
// $pgv_lang["shaab"]        = "Sha'aban";
// $pgv_lang["ramad"]        = "Ramadan";
// $pgv_lang["shaww"]        = "Shawwal";
// $pgv_lang["dhuaq"]        = "Dhu al-Qi'dah";
// $pgv_lang["dhuah"]        = "Dhu al-Hijjah";

// $pgv_lang["b.c."]         = "B.C.";

$pgv_lang["abt"]					= "hacia";
$pgv_lang["aft"]					= "después de";
$pgv_lang["and"]					= "y";
$pgv_lang["bef"]					= "antes de";
$pgv_lang["bet"]					= "entre";
$pgv_lang["cal"]					= "calculada";
$pgv_lang["est"]					= "estimada";
$pgv_lang["from"]					= "desde";
$pgv_lang["int"]					= "interpretada";
$pgv_lang["to"] 					= "a";
$pgv_lang["cir"]					= "cercana";
$pgv_lang["apx"]					= "aprox.";

//-- Admin File Messages
$pgv_lang["rebuild_indexes"]		= "Reconstruir los índices";
$pgv_lang["password_mismatch"]		= "Las contraseñas no coinciden.";
$pgv_lang["enter_username"] 		= "Escriba un nombre de usuario.";
$pgv_lang["enter_password"] 		= "Escriba una contraseña.";
$pgv_lang["save"]					= "Guardar";
$pgv_lang["delete"] 				= "Borrar";
$pgv_lang["edit"]					= "Editar";
$pgv_lang["no_login"]				= "No es posible autenticar al usuario.";
// $pgv_lang["basic_realm"]			= "PhpGedView Authentication System";
$pgv_lang["basic_auth_failure"]		= "Debe introducir usuario y contraseña válidos para acceder a este recurso";
$pgv_lang["basic_auth"]				= "Autenticación básica";
$pgv_lang["digest_auth"]				= "Autenticación por función resumen (Digest Authentication)"; //not used in code yet
$pgv_lang["no_auth_needed"]			= "Sin autenticación";
$pgv_lang["file_not_exists"]		= "El archivo introducido no existe.";
$pgv_lang["research_assistant"]		= "Asistente de investigación";
$pgv_lang["utf8_to_ansi"]			= "¿Quiere convertir este GEDCOM desde UTF-8 a ANSI (ISO-8859-1)?";
$pgv_lang["media_linked"]			= "Este objeto audiovisual está vinculado con los siguientes:";
$pgv_lang["media_not_linked"]		= "Este objeto audiovisual no está vinculado a ningún registro GEDCOM.";

//-- Relationship chart messages
// $pgv_lang["relationship_great"]		= "Great";
$pgv_lang["relationship_chart"] 	= "Parentesco";
$pgv_lang["person1"]				= "Persona 1";
$pgv_lang["person2"]				= "Persona 2";
$pgv_lang["no_link_found"]			= "No se encontró ninguna relación entre estas dos personas.";
$pgv_lang["sibling"]				= "Hermano";
$pgv_lang["follow_spouse"]			= "Verificar parentesco por matrimonio.";
$pgv_lang["timeout_error"]			= "Fuera de tiempo antes de que se encontrase un parentesco.";
$pgv_lang["son-in-law"]				= "Yerno";  // the husband of your daughter
$pgv_lang["daughter-in-law"]		= "Nuera"; // the wife of your son
$pgv_lang["grandchild"]				= "Nieto/a";
$pgv_lang["grandson"]				= "Nieto";
$pgv_lang["granddaughter"]			= "Nieta";
$pgv_lang["brother"]				= "Hermano";
$pgv_lang["sister"] 				= "Hermana";
$pgv_lang["brother-in-law"]		= "Cuñado";
$pgv_lang["sister-in-law"]			= "Cuñada";
$pgv_lang["aunt"]					= "Tía";
$pgv_lang["uncle"]				= "Tío";
$pgv_lang["firstcousin"]			= "Primo/a hermano/a";
$pgv_lang["femalecousin"]			= "Prima";
$pgv_lang["malecousin"]				= "Primo";
$pgv_lang["cousin-in-law"]			= "Primo político";
$pgv_lang["relationship_to_me"] 	= "Parentesco conmigo";
$pgv_lang["rela_husb"]				= "Parentesco con el esposo";
$pgv_lang["rela_wife"]				= "Parentesco con la esposa";
$pgv_lang["next_path"]				= "Buscar otro camino";
$pgv_lang["show_path"]				= "Ver camino";
$pgv_lang["line_up_generations"]	= "Alinear las mismas generaciones";
$pgv_lang["oldest_top"]             = "Mostrar lo más antiguo antes";

// %1\$s replaced by first person, %2\$s by the relationship and %3\$s by the second person.
$pgv_lang["relationship_male_1_is_the_2_of_3"] = "%1\$s es el %2\$s de %3\$s.";
$pgv_lang["relationship_female_1_is_the_2_of_3"] = "%1\$s es la %2\$s de %3\$s.";

$pgv_lang["mother_in_law"]		    = "suegra";
$pgv_lang["father_in_law"]		    = "suegro";
$pgv_lang["brother_in_law"]		    = "cuñado";
$pgv_lang["sister_in_law"]		    = "cuñada";
$pgv_lang["son_in_law"]		        = "yerno";
$pgv_lang["daughter_in_law"]		= "nuera";

$pgv_lang["step_son"]		        = "hijastro";
$pgv_lang["step_daughter"]	    	= "hijastra";

// the bosa_brothers_offspring name is used for fraternal nephews and nieces - the names below can be extended to any number
// of generations just by adding more translations.
// 1st generation
$pgv_lang["bosa_brothers_offspring_2"] 				= "sobrino";             // brother's son
$pgv_lang["bosa_brothers_offspring_3"] 				= "sobrina";              // brother's daughter
// 2nd generation
$pgv_lang["bosa_brothers_offspring_4"] 				= "sobrino nieto";       // brother's son's son
$pgv_lang["bosa_brothers_offspring_5"] 				= "sobrina nieta";        // brother's son's daughter
$pgv_lang["bosa_brothers_offspring_6"] 				= "sobrino nieto";       // brother's daughter's son
$pgv_lang["bosa_brothers_offspring_7"] 				= "sobrina nieta";        // brother's daughter's daughter
// for the general case of offspring of the nth generation use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_brothers_son"]	  = "%2\$dº sobrino nieto";
$pgv_lang["n_x_brothers_daughter"] = "%2\$dª sobrina nieta";
// the bosa_sisters_offspring name is used for sisters nephews and nieces - the names below can be extended to any number
// of generations just by adding more translations.
// 1st generation
$pgv_lang["bosa_sisters_offspring_2"] 				= "sobrino";             // sister's son
$pgv_lang["bosa_sisters_offspring_3"] 				= "sobrina";              // sister's daughter
// 2nd generation
$pgv_lang["bosa_sisters_offspring_4"] 				= "sobrino nieto";       // sister's son's son
$pgv_lang["bosa_sisters_offspring_5"] 				= "sobrina nieta";        // sister's son's daughter
$pgv_lang["bosa_sisters_offspring_6"] 				= "sobrino nieto";       // sister's daughter's son
$pgv_lang["bosa_sisters_offspring_7"] 				= "sobrina nieta";        // sister's daughter's daughter
// for the general case of offspring of the nth generation use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_sisters_son"]	  = "%2\$dº sobrino nieto";
$pgv_lang["n_x_sisters_daughter"] = "%2\$dª sobrina nieta";

// the bosa name is used for offspring - the names below can be extended to any number
// of generations just by adding more translations.
// 1st generation
$pgv_lang["bosa_2"] 				= "hijo";                   // son
$pgv_lang["bosa_3"] 				= "hija";              // daughter
// 2nd generation
$pgv_lang["bosa_4"] 				= "nieto";              // son's son
$pgv_lang["bosa_5"] 				= "nieta";         // son's daughter
$pgv_lang["bosa_6"] 				= "nieto";              // daughter's son
$pgv_lang["bosa_7"] 				= "nieta";         // daughter's daughter
// 3rd generation
$pgv_lang["bosa_8"] 				= "bisnieto";        // son's son's son
$pgv_lang["bosa_9"] 				= "bisnieta";   // son's son's daughter
$pgv_lang["bosa_10"] 				= "bisnieto";		   // son's daughter's son
$pgv_lang["bosa_11"] 				= "bisnieta";   // son's daughter's daughter
$pgv_lang["bosa_12"] 				= "bisnieto";        // daughter's son's son
$pgv_lang["bosa_13"] 				= "bisnieta";   // daughter's son's daughter
$pgv_lang["bosa_14"] 				= "bisnieto";		   // daughter's daughter's son
$pgv_lang["bosa_15"] 				= "bisnieta";   // daughter's daughter's daughter
// for the general case of offspring of the nth generation use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_grandson_from_son"]	  = "";
$pgv_lang["n_x_granddaughter_from_son"] = "";
$pgv_lang["n_x_grandson_from_daughter"]	  = "";
$pgv_lang["n_x_granddaughter_from_daughter"] = "";

// the sosa_uncle name is used for uncles - the names below can be extended to any number
// of generations just by adding more translations.
// to allow fo language variations we specify different relationships for paternal and maternal
// aunts and uncles
// 1st generation
$pgv_lang["sosa_uncle_2"] 				= "tío";            // father's brother
$pgv_lang["sosa_uncle_3"] 				= "tío";            // mother's brother
// 2nd generation
$pgv_lang["sosa_uncle_4"] 				= "tío abuelo";      // fathers's father's brother
$pgv_lang["sosa_uncle_5"] 				= "tío abuelo";      // father's mother's brother
$pgv_lang["sosa_uncle_6"] 				= "tío abuelo";      // mother's father's brother
$pgv_lang["sosa_uncle_7"] 				= "tío abuelo";      // mother's mother's brother
// for the general case of uncles of the nth degree use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_paternal_uncle"]		= "%2\$dº tío";
$pgv_lang["n_x_maternal_uncle"]	    = "%2\$dº tío";

// the sosa_aunt name is used for aunts - the names below can be extended to any number
// of generations just by adding more translations.
// to allow fo language variations we specify different relationships for paternal and maternal
// aunts and aunts
// 1st generation
$pgv_lang["sosa_aunt_2"] 				= "tía";            // father's sister
$pgv_lang["sosa_aunt_3"] 				= "tía";            // mother's sister
// 2nd generation
$pgv_lang["sosa_aunt_4"] 				= "tía abuela";      // fathers's father's sister
$pgv_lang["sosa_aunt_5"] 				= "tía abuela";      // father's mother's sister
$pgv_lang["sosa_aunt_6"] 				= "tía abuela";      // mother's father's sister
$pgv_lang["sosa_aunt_7"] 				= "tía abuela";      // mother's mother's sister
// for the general case of aunts of the nth degree use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_paternal_aunt"]		= "%2\$dª tía";
$pgv_lang["n_x_maternal_aunt"]	    = "%2\$dª tía";

// the sosa_uncle name is used for uncles(by marriage) - the names below can be extended to any number
// of generations just by adding more translations.
// to allow fo language variations we specify different relationships for paternal and maternal
// aunts and uncles
// 1st generation
$pgv_lang["sosa_uncle_bm_2"] 				= "tío";            // father's brother
$pgv_lang["sosa_uncle_bm_3"] 				= "tío";            // mother's brother
// 2nd generation
$pgv_lang["sosa_uncle_bm_4"] 				= "tío abuelo";      // fathers's father's brother
$pgv_lang["sosa_uncle_bm_5"] 				= "tío abuelo";      // father's mother's brother
$pgv_lang["sosa_uncle_bm_6"] 				= "tío abuelo";      // mother's father's brother
$pgv_lang["sosa_uncle_bm_7"] 				= "tío abuelo";      // mother's mother's brother
// for the general case of uncles of the nth degree use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_paternal_uncle_bm"]		= "%2\$dº tío abuelo";
$pgv_lang["n_x_maternal_uncle_bm"]	    = "%2\$dº tío abuelo";

// the sosa_aunt name is used for aunts (by marriage)- the names below can be extended to any number
// of generations just by adding more translations.
// to allow fo language variations we specify different relationships for paternal and maternal
// aunts and aunts
// 1st generation
$pgv_lang["sosa_aunt_bm_2"] 				= "tía";            // father's sister
$pgv_lang["sosa_aunt_bm_3"] 				= "tía";            // mother's sister
// 2nd generation
$pgv_lang["sosa_aunt_bm_4"] 				= "tía abuela";      // fathers's father's sister
$pgv_lang["sosa_aunt_bm_5"] 				= "tía abuela";      // father's mother's sister
$pgv_lang["sosa_aunt_bm_6"] 				= "tía abuela";      // mother's father's sister
$pgv_lang["sosa_aunt_bm_7"] 				= "tía abuela";      // mother's mother's sister
// for the general case of aunts of the nth degree use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["n_x_paternal_aunt_bm"]		= "%2\$dª tía abuela";
$pgv_lang["n_x_maternal_aunt_bm"]	    = "%2\$dª tía abuela";


// if a specific cousin relationship cannot be represented in a language translate as "";
$pgv_lang["male_cousin_1"]              = "primo hermano";
$pgv_lang["male_cousin_2"]              = "primo segundo";
$pgv_lang["male_cousin_3"]              = "primo tercero";
$pgv_lang["male_cousin_4"]              = "primo cuarto";
$pgv_lang["male_cousin_5"]              = "primo quinto";
$pgv_lang["male_cousin_6"]              = "primo sexto";
$pgv_lang["male_cousin_7"]              = "primo séptimo";
$pgv_lang["male_cousin_8"]              = "primo octavo";
$pgv_lang["male_cousin_9"]              = "primo noveno";
$pgv_lang["male_cousin_10"]             = "primo décimo";
$pgv_lang["male_cousin_11"]             = "primo undécimo";
$pgv_lang["male_cousin_12"]             = "primo duodécimo";
$pgv_lang["male_cousin_13"]             = "primo decimotercero";
$pgv_lang["male_cousin_14"]             = "primo decimocuarto";
$pgv_lang["male_cousin_15"]             = "primo decimoquinto";
$pgv_lang["male_cousin_16"]             = "primo decimosexto";
$pgv_lang["male_cousin_17"]             = "primo decimoséptimo";
$pgv_lang["male_cousin_18"]             = "primo decimooctavo";
$pgv_lang["male_cousin_19"]             = "primo decimonoveno";
$pgv_lang["male_cousin_20"]             = "primo vigésimo";
$pgv_lang["male_cousin_n"]              = "%dº primo";
$pgv_lang["female_cousin_1"]            = "prima hermana";
$pgv_lang["female_cousin_2"]            = "prima segunda";
$pgv_lang["female_cousin_3"]            = "prima tercera";
$pgv_lang["female_cousin_4"]            = "prima cuarta";
$pgv_lang["female_cousin_5"]            = "prima quinta";
$pgv_lang["female_cousin_6"]            = "prima sexta";
$pgv_lang["female_cousin_7"]            = "prima séptima";
$pgv_lang["female_cousin_8"]            = "prima octava";
$pgv_lang["female_cousin_9"]            = "prima novena";
$pgv_lang["female_cousin_10"]           = "prima décima";
$pgv_lang["female_cousin_11"]           = "prima undécima";
$pgv_lang["female_cousin_12"]           = "prima duodécima";
$pgv_lang["female_cousin_13"]           = "prima decimotercera";
$pgv_lang["female_cousin_14"]           = "prima decimocuarta";
$pgv_lang["female_cousin_15"]           = "prima decimoquinta";
$pgv_lang["female_cousin_16"]           = "prima decimosexta";
$pgv_lang["female_cousin_17"]           = "prima decimoseptima";
$pgv_lang["female_cousin_18"]           = "prima decimooctava";
$pgv_lang["female_cousin_19"]           = "prima decimonovena";
$pgv_lang["female_cousin_20"]           = "prima vigésima";
$pgv_lang["female_cousin_n"]            = "%dª prima";

// Only referenced from english specific functions
$pgv_lang["removed_ascending_1"]   = "";
$pgv_lang["removed_ascending_2"]   = "";
$pgv_lang["removed_ascending_3"]   = "";
$pgv_lang["removed_ascending_4"]   = "";
$pgv_lang["removed_ascending_5"]   = "";
$pgv_lang["removed_ascending_6"]   = "";
$pgv_lang["removed_ascending_7"]   = "";
$pgv_lang["removed_ascending_8"]   = "";
$pgv_lang["removed_ascending_9"]   = "";
$pgv_lang["removed_ascending_10"]  = "";
$pgv_lang["removed_ascending_11"]  = "";
$pgv_lang["removed_ascending_12"]  = "";
$pgv_lang["removed_ascending_13"]  = "";
$pgv_lang["removed_ascending_14"]  = "";
$pgv_lang["removed_ascending_15"]  = "";
$pgv_lang["removed_ascending_16"]  = "";
$pgv_lang["removed_ascending_17"]  = "";
$pgv_lang["removed_ascending_18"]  = "";
$pgv_lang["removed_ascending_19"]  = "";
$pgv_lang["removed_ascending_20"]  = "";
$pgv_lang["removed_descending_1"]  = "";
$pgv_lang["removed_descending_2"]  = "";
$pgv_lang["removed_descending_3"]  = "";
$pgv_lang["removed_descending_4"]  = "";
$pgv_lang["removed_descending_5"]  = "";
$pgv_lang["removed_descending_6"]  = "";
$pgv_lang["removed_descending_7"]  = "";
$pgv_lang["removed_descending_8"]  = "";
$pgv_lang["removed_descending_9"]  = "";
$pgv_lang["removed_descending_10"] = "";
$pgv_lang["removed_descending_11"] = "";
$pgv_lang["removed_descending_12"] = "";
$pgv_lang["removed_descending_13"] = "";
$pgv_lang["removed_descending_14"] = "";
$pgv_lang["removed_descending_15"] = "";
$pgv_lang["removed_descending_16"] = "";
$pgv_lang["removed_descending_17"] = "";
$pgv_lang["removed_descending_18"] = "";
$pgv_lang["removed_descending_19"] = "";
$pgv_lang["removed_descending_20"] = "";


//-- GEDCOM edit utility
$pgv_lang["check_delete"]			= "¿Está seguro que desea eliminar este hecho GEDCOM?";
$pgv_lang["access_denied"]			= "<b>Acceso denegado</b><br />No tiene permiso para acceder a este recurso.";
$pgv_lang["changes_exist"]			= "Los cambios han sido aplicados a este GEDCOM.";
$pgv_lang["find_place"] 			= "Encontrar Lugar";
$pgv_lang["close_window"]			= "Cerrar Ventana";
$pgv_lang["close_window_without_refresh"] = "Cerrar ventana sin actualizar";
$pgv_lang["place_contains"] 		= "Los Lugares tienen:";
$pgv_lang["add"]					= "Añadir";
$pgv_lang["custom_event"]			= "Evento personalizado";
$pgv_lang["delete_person"]			= "Borrar esta persona";
$pgv_lang["confirm_delete_person"]	= "¿Seguro que quiere borrar esta persona del archivo GEDCOM?";
$pgv_lang["find_media"] 			= "Buscar Multimedia";
$pgv_lang["set_link"]				= "Fijar vínculo";
$pgv_lang["delete_source"]			= "Borrar esta Fuente";
$pgv_lang["confirm_delete_source"]	= "¿Seguro que quiere borrar esta Fuente del archivo GEDCOM?";
$pgv_lang["find_family"]			= "Buscar Familia";
$pgv_lang["find_fam_list"]			= "Encontrar la lista de Familia";
$pgv_lang["edit_name"]				= "Editar Nombre";
$pgv_lang["delete_name"]			= "Borrar Nombre";
$pgv_lang["select_date"]			= "Seleccione una fecha";
$pgv_lang["user_cannot_edit"]		= "Este usuario no puede editar este GEDCOM.";
$pgv_lang["ged_noshow"]				= "Esta página ha sido deshabilitada por el administrador del sitio";

//-- calendar.php messages
$pgv_lang["bdm"]					= "Nacimientos, defunciones y matrimonios";
$pgv_lang["on_this_day"]			= "En un día como éste...";
$pgv_lang["in_this_month"]			= "En un mes como éste...";
$pgv_lang["in_this_year"]			= "En este año, en su Historia";
$pgv_lang["year_anniversary"]		= "hace #year_var# años";
$pgv_lang["today"]					= "Hoy";
$pgv_lang["day"]					= "Día:";
$pgv_lang["month"]					= "Mes:";
$pgv_lang["showcal"]				= "Mostrar eventos de:";
$pgv_lang["anniversary"]			= "Aniversario";
$pgv_lang["anniversary_calendar"]	= "Aniversarios";
$pgv_lang["sunday"] 				= "domingo";
$pgv_lang["monday"] 				= "lunes";
$pgv_lang["tuesday"]				= "martes";
$pgv_lang["wednesday"]				= "miércoles";
$pgv_lang["thursday"]				= "jueves";
$pgv_lang["friday"] 				= "viernes";
$pgv_lang["saturday"]				= "sábado";
$pgv_lang["viewday"]				= "Ver día";
$pgv_lang["viewmonth"]				= "Ver mes";
$pgv_lang["viewyear"]				= "Ver año";
$pgv_lang["all_people"] 			= "Todas las personas";
$pgv_lang["living_only"]			= "Personas vivas";
$pgv_lang["recent_events"]			= "Eventos recientes (&lt; 100 años)";
$pgv_lang["day_not_set"]			= "Día no fijado";

//-- user self registration module
$pgv_lang["lost_password"]			= "¿Ha olvidado la contraseña?";
$pgv_lang["requestpassword"]		= "Solicitar una nueva contraseña";
$pgv_lang["no_account_yet"] 		= "¿No tiene cuenta aún?";
$pgv_lang["requestaccount"] 		= "Solicitar una nueva cuenta de usuario";
$pgv_lang["emailadress"]			= "Correo Electrónico:";
$pgv_lang["mandatory"] 			= "Los campos marcados con * son obligatorios.";
$pgv_lang["mail01_line01"]			= "Hola #user_fullname# ...";
$pgv_lang["mail01_line02"]			= "Se ha hecho una petición a ( #SERVER_NAME# ) para acceder con su dirección de correo ( #user_email# ).";
$pgv_lang["mail01_line03"]			= "Fueron utilizados los siguientes datos.";
$pgv_lang["mail01_line04"]			= "Seleccione el vínculo inferior y rellene los datos requeridos para verificar su Cuenta y su Dirección de correo .";
$pgv_lang["mail01_line05"]			= "Si no está conforme con los datos puede eliminar este mensaje.";
$pgv_lang["mail01_line06"]			= "No recibirá más mensajes desde este sistema, y se eliminará la cuenta sino la verifica antes de siete días.";
$pgv_lang["mail01_subject"] 		= "Su registro en #SERVER_NAME#";

$pgv_lang["mail02_line01"]			= "Hola Administrador ...";
$pgv_lang["mail02_line02"]			= "Nuevo usuario registrado en ( #SERVER_NAME# ).";
$pgv_lang["mail02_line03"]			= "Se le ha enviado un correo con los datos necesarios para verificar su cuenta.";
$pgv_lang["mail02_line04"]			= "Tan pronto como el usuario haga esta verificación será informado por correo, entonces podrá autorizar a este usuario para entrar en el sitio.";
$pgv_lang["mail02_line04a"]			= "Tan pronto como el usuario haga esta verificación será informado por correo. Tras la verificación, el usuario podrá entrar sin que Vd. tenga que hacer nada más.";
$pgv_lang["mail02_subject"] 		= "Nuevo registro en #SERVER_NAME#";

$pgv_lang["hashcode"]				= "Código de verificación:";
$pgv_lang["thankyou"]				= "Hola #user_fullname# ...<br />Gracias por registrarse";
$pgv_lang["pls_note06"] 			= "Ahora recibirá un correo para  confirmar su dirección de correo:( #user_email# ). Utilizando el correo de confirmación, activará su cuenta; si no la activa antes de siete días, ésta se borrará (en ese momento podría registrar su cuenta de nuevo). Para acceder a este sitio, necesitará saber su nombre de usuario y su contraseña.";
$pgv_lang["pls_note06a"] 			= "Ahora recibirá un mensaje de correo electrónico para confirmar su dirección de correo:( #user_email# ). Siga las instrucciones en ese mensaje de correo para completar la verificación y activar su cuenta. Si no lo hace antes de siete días, se borrará su solicitud automáticamente.  En ese caso, tendrá que repetir el proceso con una nueva solicitud.<br><br />Cuando haya seguido los pasos indicados en ese mensaje de correo, podrá entrar. Para entrar, necesitará hacer uso de su usuario y su contraseña.<br /><br />";

$pgv_lang["registernew"]			= "Nueva confirmación de Cuenta";
$pgv_lang["user_verify"]			= "Verificación de usuario";
$pgv_lang["send"]					= "Enviar";

$pgv_lang["pls_note07"] 			= "Por favor, escriba su nombre de usuario, su contraseña, y el código de verificación que ha recibido por correo desde este sistema para verificar su petición de una cuenta.";
$pgv_lang["pls_note08"] 			= "Se han comprobado los datos del usuario #user_name#.";

$pgv_lang["mail03_line01"]			= "Hola Administrador ...";
$pgv_lang["mail03_line02"]			= "#newuser[username]# ( #newuser[fullname]# ) ha verificado los datos de registro.";
$pgv_lang["mail03_line03"]			= "Por favor, seleccione el vínculo siguiente para editar los datos del usuario, y darle el permiso para acceder a su sitio web.";
$pgv_lang["mail03_line03a"]			= "No es necesaria ninguna acción de su parte, el usuario ya puede entrar.";
$pgv_lang["mail03_subject"] 		= "Nueva verificación en #SERVER_NAME#";

$pgv_lang["pls_note09"] 			= "Ha sido identificado como un usuario registrado.";
$pgv_lang["pls_note10"] 			= "El Administrador ha sido informado.<br />Tan pronto como le dé el permiso para acceder, puede entrar con su nombre de usuario y su contraseña.";
$pgv_lang["pls_note10a"]			= "Ya puede entrar con su usuario y contraseña.";
$pgv_lang["data_incorrect"] 		= "Los datos no son correctos!<br />Por favor, inténtelo de nuevo!";
$pgv_lang["user_not_found"] 		= "No se pudo verificar la información que ha enviado.  Por favor, regrese e inténtelo de nuevo.";

$pgv_lang["lost_pw_reset"]			= "Solicitud de nueva contraseña por pérdida";
$pgv_lang["pls_note11"] 			= "Para obtener su contraseña, envíe el nombre de usuario y la dirección de correo de su cuenta. <br /><br />Le enviaremos una URL especial por correo electrónico, con una confirmación para su cuenta. Visitando la dirección URL suministrada, podrá cambiar su contraseña y acceder a este sitio. Por razones de seguridad, no suministre esta confirmación a nadie, administradores de este sitio incluidos (nosotros no se lo pedimos).<br /><br />Si necesita asistencia del administrador del sitio, por favor, contacte con él directamente.";

$pgv_lang["mail04_line01"]			= "Hola #user_fullname# ...";
$pgv_lang["mail04_line02"]			= "Ha sido solicitada una nueva contraseña para su nombre de usuario!";
$pgv_lang["mail04_line03"]			= "Recomendación:";
$pgv_lang["mail04_line04"]			= "Ahora, por favor, seleccione el siguiente vínculo, entre con la nueva contraseña y cámbiela para preservar la seguridad de sus datos.";
$pgv_lang["mail04_line05"]			= "Una vez haya entrado, seleccione el vínculo '#pgv_lang[myuserdata]#' del menú '#pgv_lang[mygedview]#' y rellene los campos de contraseña para cambiar su contraseña por una de su elección.";
$pgv_lang["mail04_subject"] 		= "Solicitud de datos en #SERVER_NAME#";

$pgv_lang["pwreqinfo"]				= "Hola...<br /><br />Se ha enviado un correo a la dirección (#user[email]#) incluyendo la nueva contraseña.<br /><br />Por favor, revise su cuenta de correo porque el mensaje debería de llegarle en los próximos minutos.<br /><br />Recomendación:<br /><br />Después de recibir el mensaje, debería acceder a este sitio con su nueva contraseña y cambiarla preservar la seguridad de sus datos.";

$pgv_lang["myuserdata"] 			= "Mi Cuenta";
$pgv_lang["user_theme"] 			= "Mi Tema";
$pgv_lang["mgv"]					= "Mi GedView";
$pgv_lang["mygedview"]				= "MiGedView";
$pgv_lang["passwordlength"] 		= "La contraseña debe tener al menos 6 caracteres .";
$pgv_lang["welcome_text_auth_mode_1"]	= "\nW.r.t. language variants, it is possible to ";
$pgv_lang["welcome_text_auth_mode_2"]	= "<center><b>Bienvenido a este sitio de Genealogía</b></center><br />Se permite el acceso solamente a usuarios <u>autorizados</u>.<br /><br />Si ya tiene una cuenta de usuario, puede identificarse en esta página.  Si no tiene cuenta de usuario, puede solicitarla haciendo clic en el vínculo apropiado más abajo.<br /><br />Después de verificar su solicitud, el administrador del sitio aprobará o rechazará su solicitud.  Recibirá un correo electrónico cuando se apruebe su solicitud.";
$pgv_lang["welcome_text_auth_mode_3"]	= "<center><b>Bienvenido a este sitio de Genealogía</b></center><br />Se permite el acceso <u>solamente a parientes</u>.<br /><br />Si ya tiene una cuenta de usuario, puede identificarse en esta página.  Si no tiene cuenta de usuario, puede solicitarla haciendo clic en el vínculo apropiado más abajo.<br /><br />Después de verificar la información proporcionada, el administrador del sitio aprobará o rechazará su solicitud.  Recibirá un correo electrónico cuando se apruebe su solicitud.";
$pgv_lang["welcome_text_cust_head"] 	= "<b>BIENVENIDO A ESTE SITIO DE GENEALOGÍA</b></br></br>Se permite el acceso a usuarios que posean una cuenta de usuario y una contraseña asignadas.</br>";
$pgv_lang["acceptable_use"]			= "<div class=\"largeError\">Notice:</div><div class=\"error\">Rellenando y enviando este formulario, Vd. acepta:<ul><li>preservar la privacidad de las personas vivas listadas en nuestro sitio;</li><li>y en el campo de texto inferior explicar con quién está emparentado o darnos información de las personas que deberían constar en nuestro sitio.</li></ul></div>";


//-- mygedview page
$pgv_lang["upcoming_events"]		= "Próximos eventos";
$pgv_lang["living_or_all"]			= "¿Mostrar solamente eventos de personas vivas?";
$pgv_lang["basic_or_all"]			= "¿Mostrar sólo nacimientos, defunciones y matrimonios?";
$pgv_lang["style"]					= "Estilo de presentación";
$pgv_lang["style1"]					= "Lista";
$pgv_lang["style2"]					= "Tabla";
$pgv_lang["style3"]					= "Nube de etiquetas";
$pgv_lang["cal_download"]			= "¿Permitir la descarga de eventos del calendario?";
$pgv_lang["no_events_living"]		= "No hay eventos para personas vivas en los próximos #pgv_lang[global_num1]# días.";
$pgv_lang["no_events_living1"]		= "No hay eventos para personas vivas mañana.";
$pgv_lang["no_events_all"]			= "No hay eventos para los próximos #pgv_lang[global_num1]# días.";
$pgv_lang["no_events_all1"]			= "No hay eventos para mañana.";
$pgv_lang["no_events_privacy"]		= "Hay eventos para los próximos #pgv_lang[global_num1]# días, pero las restricciones de privacidad le impiden verlos.";
$pgv_lang["no_events_privacy1"]		= "Hay eventos para mañana, pero las restricciones de privacidad le impiden verlos.";
$pgv_lang["more_events_privacy"]	= "<br />Hay más eventos para los próximos #pgv_lang[global_num1]# días, pero las restricciones de privacidad le impiden verlos.";
$pgv_lang["more_events_privacy1"]	= "<br />Hay más eventos para mañana, pero las restricciones de privacidad le impiden verlos.";
$pgv_lang["none_today_living"]		= "No hay eventos de personas vivas para hoy.";
$pgv_lang["none_today_all"]			= "No hay evebtos para hoy.";
$pgv_lang["none_today_privacy"]		= "Hay eventos para hoy, pero las restricciones de privacidad le impiden verlos.";
$pgv_lang["more_today_privacy"]		= "<br />Hay más eventos para hoy, pero las restricciones de privacidad le impiden verlos.";
$pgv_lang["chat"]					= "Chat";
$pgv_lang["users_logged_in"]		= "Usuarios presentes";
$pgv_lang["anon_user"]				= "1 usuario anónimo conectado";
$pgv_lang["anon_users"]				= "#pgv_lang[global_num1]# usuarios anónimos conectados";
$pgv_lang["login_user"]				= "1 usuario identificado";
$pgv_lang["login_users"]			= "#pgv_lang[global_num1]# usuarios identificados";
$pgv_lang["no_login_users"]			= "No hay usuarios identificados ni anónimos";
$pgv_lang["message"]				= "Enviar Mensaje";
$pgv_lang["my_messages"]			= "Mensajes";
$pgv_lang["date_created"]			= "Enviado el:";
$pgv_lang["message_from"]			= "Dirección de correo electrónico:";
$pgv_lang["message_from_name"]		= "De:";
$pgv_lang["message_to"] 			= "Para:";
$pgv_lang["message_subject"]		= "Asunto:";
$pgv_lang["message_body"]			= "Mensaje:";
$pgv_lang["no_to_user"] 			= "No es posible enviar el mensaje.  Debe de especificar el destinatario.";
$pgv_lang["provide_email"]			= "Recuerde especificar su dirección de correo para que nosotros podamos responder a su mensaje.  Su dirección de correo electrónico no será utilizada en ningún caso para nada diferente que no sea responder a su mensaje.";
$pgv_lang["reply"]					= "Responder";
$pgv_lang["message_deleted"]		= "Mensaje eliminado";
$pgv_lang["message_sent"]			= "Mensaje enviado";
$pgv_lang["reset"]					= "Restaurar";
$pgv_lang["site_default"]			= "Predeterminado del Sitio";
$pgv_lang["mygedview_desc"] 		= "Su página MiGedView le permite guardar marcadores a sus personas favoritas, estar al tanto de los próximos eventos y colaborar con otros usuarios de PhpGedView.";
$pgv_lang["no_messages"]			= "No tiene mensajes pendientes.";
$pgv_lang["clicking_ok"]			= "Seleccionando OK, se abrirá otra ventana donde contactar con #user[fullname]#";
$pgv_lang["favorites"]				= "Favoritos";
$pgv_lang["my_favorites"]			= "Favoritos";
$pgv_lang["no_favorites"]			= "No tiene a nadie seleccionado en Favoritos.  Para agregar una persona a Favoritos, busque en la página de la persona que le gustaría agregar y seleccione \"Agregar a Favoritos\"  o bien utilice la caja del ID inferior para agregar una persona por su número de ID.";
$pgv_lang["add_to_my_favorites"]	= "Agregar a Favoritos";
$pgv_lang["gedcom_favorites"]		= "Favoritos de este GEDCOM";
$pgv_lang["no_gedcom_favorites"]	= "Al momento no se han seleccionado Favoritos. El Administrador puede agregar Favoritos para mostrarse al comienzo.";
$pgv_lang["confirm_fav_remove"] 	= "Seguro que quiere eliminar este vínculo de sus favoritos?";
$pgv_lang["invalid_email"]			= "Por favor introduzca una dirección de correo válida.";
$pgv_lang["enter_subject"]			= "Por favor introduzca el Asunto.";
$pgv_lang["enter_body"] 			= "Por favor escriba el texto del mensaje antes de enviarlo.";
$pgv_lang["confirm_message_delete"] = "Realmente desea eliminar definitivamente este mensaje?";
$pgv_lang["message_email1"] 		= "Mensaje recibido de ";
$pgv_lang["message_email2"] 		= "Mensaje enviado a un usuario:";
$pgv_lang["message_email3"] 		= "Mensaje enviado a un administrador:";
$pgv_lang["viewing_url"]			= "Este mensaje fue enviado desde la página url: ";
$pgv_lang["messaging2_help"]		= "Cuando envíe este mensaje recibirá una copia del mismo en la dirección de correo suministrada.";
$pgv_lang["random_picture"] 		= "Imagen al azar";
$pgv_lang["message_instructions"]	= "<b>AVISO:</b> La información privada de las personas vivas solamente se facilitará a familiares cercanos y amigos íntimos.  Se le solicitará su relación de parentesco para poder recibir datos privados.  En ocasiones la información sobre personas ya fallecidas también puede ser privada.  Esto es así cuando no hay información suficiente para determinar con seguridad si estas personas están vivas o no, por otra parte probablemente no dispongamos de más información sobre ellas.<br /><br />Antes de hacer una solicitud, revise todos los datos de la persona, fechas, lugares y detalles personales para asegurarse de que efectivamente sea la persona de su interés.  Si está enviando modificaciones sobre los datos genealógicos, no se olvide de incluir las fuentes de donde obtuvo la información.<br /><br />";
$pgv_lang["sending_to"] 			= "Este mensaje será enviado a #TO_USER#";
$pgv_lang["preferred_lang"] 		= "Este usuario prefiere recibir mensajes en #USERLANG#";
$pgv_lang["gedcom_created_using"]	= "Este GEDCOM fue creado usando <b>#SOFTWARE# #VERSION#</b> ";
$pgv_lang["gedcom_created_on"]		= "Este GEDCOM fue creado el <b>#DATE#</b>.";
$pgv_lang["gedcom_created_on2"] 	= "el <b>#DATE#</b>";
$pgv_lang["gedcom_stats"]			= "Estadísticas del GEDCOM";
$pgv_lang["stat_individuals"]		= "Personas";
$pgv_lang["stat_families"]			= "Familias";
$pgv_lang["stat_sources"]			= "Fuentes";
$pgv_lang["stat_other"] 			= "Otros Registros";
$pgv_lang["stat_earliest_birth"] 	= "Año de nacimiento más lejano";
$pgv_lang["stat_latest_birth"] 	= "Año de nacimiento más reciente";
$pgv_lang["stat_earliest_death"] 	= "Año de defunción más lejano";
$pgv_lang["stat_latest_death"] 	= "Año de defunción más reciente";
$pgv_lang["customize_page"] 		= "Personalice su Portal personal";
$pgv_lang["customize_gedcom_page"]	= "Personalice la Página de Bienvenida de este GEDCOM";
$pgv_lang["upcoming_events_block"]	= "Próximos eventos";
$pgv_lang["upcoming_events_descr"]	= "El bloque Próximos Eventos muestra aniversarios de eventos que van a ocurrir en el futuro próximo.  Puede configurar la cantidad de detalle que se mostrará y el administrador puede configurar cuántos días en el futuro considerará este bloque.";
$pgv_lang["todays_events_block"]	= "Bloque En este Día";
$pgv_lang["todays_events_descr"]	= "El bloque \"En este día en su Historia...\" muestra los aniversarios de los eventos para hoy.  Puede configurar la cantidad de detalle que se muestra.";
$pgv_lang["logged_in_users_block"]	= "Bloque de Usuarios Registrados";
$pgv_lang["logged_in_users_descr"]	= "El bloque de Usuarios Conectados muestra una lista de los usuarios conectados actualmente.";
$pgv_lang["user_messages_block"]	= "Bloque de Mensajes al Usuario";
$pgv_lang["user_messages_descr"]	= "El Bloque de Mensajes al Usuario muestra una lista de los mensajes enviados al usuario activo.";
$pgv_lang["user_favorites_block"]	= "Bloque de Favoritos del Usuario";
$pgv_lang["user_favorites_descr"]	= "El Bloque de Favoritos del Usuario muestra al usuario un listado de sus personas favoritas de la base de datos haciéndolas fácilmente accesibles.";
$pgv_lang["welcome_block"]			= "Bloque de Bienvenida al Usuario";
$pgv_lang["welcome_descr"]			= "El Bloque de Bienvenida al Usuario muestra al usuario la fecha y hora actual, vínculos rápidos para modificar su cuenta o dirigirse a su propio Árbol de Ascendientes y un vínculo para personalizar su página Mi GedView.";
$pgv_lang["random_media_block"] 	= "Bloque de Medios al Azar";
$pgv_lang["random_media_descr"] 	= "El bloque de Objeto Audiovisual al Azar muestra de forma aleatoria una foto u otro elemento audiovisual de la base de datos activa en el momento y lo muestra al usuario.<br /><br />El administrador determina si este bloque puede mostrar elementos asociados con personas o con eventos.";
$pgv_lang["random_media_persons_or_all"]	= "¿Mostrar sólo personas, eventos o todo?";
$pgv_lang["random_media_persons"]	= "Personas";
$pgv_lang["random_media_events"]	= "Eventos";
$pgv_lang["gedcom_block"]			= "Bloque de Bienvenida al GEDCOM";
$pgv_lang["gedcom_descr"]			= "El Bloque de Bienvenida al GEDCOM funciona en forma similar al Bloque de Bienvenida al Usuario, dándole la bienvenida al usuario, mostrando el título del GEDCOM activo, la hora y fecha actuales.";
$pgv_lang["gedcom_favorites_block"] = "Bloque de Favoritos del GEDCOM";
$pgv_lang["gedcom_favorites_descr"] = "El Bloque de Favoritos del GEDCOM permite al administrador seleccionar a sus personas favoritas de forma tal que los usuarios puedan encontrarlas fácilmente. Esta es una forma de destacar a aquellas personas importantes en su historia familiar.";
$pgv_lang["gedcom_stats_block"] 	= "Bloque de Estadísticas del GEDCOM";
$pgv_lang["gedcom_stats_descr"] 	= "El Bloque de Estadísticas del GEDCOM muestra al usuario información básica acerca del GEDCOM, tal como cuando fue creado y cuantas personas integran el GEDCOM.<br /><br />También tiene una lista de los apellidos más frecuentes.  Puede configurar este bloque para no mostrar la lista de apellidos frecuentes y también puede configurar el GEDCOM para eliminar o agregar nombres a esta lista.  Puede fijar el umbral de repeticiones para esta lista en la configuración del GEDCOM.";
$pgv_lang["gedcom_stats_show_surnames"]	= "¿Mostrar apellidos frecuentes?";
$pgv_lang["portal_config_intructions"]	= "Aquí puede personalizar la página, posicionando los bloques en la forma que desee. La página está dividida en dos secciones, la sección 'Principal' y la sección 'Derecha'. Los bloques de la sección 'Principal' aparecen de mayor tamaño y bajo el título de la página. La sección 'Derecha' comienza a la derecha del título orientada hacia abajo y a la derecha de la página. Cada sección posee su propia lista de bloques que serán desplegados en la página en el orden en que están listados. Puede agregar, quitar y reordenar los bloques como desee.";
$pgv_lang["login_block"]			= "Entrada identificada";
$pgv_lang["login_descr"]			= "El Bloque de Entrada Identificada recibe el Nombre de Usuario y Contraseña para que los usuarios se identifiquen.";
$pgv_lang["theme_select_block"] 	= "Bloque de selección de tema";
$pgv_lang["theme_select_descr"] 	= "El bloque de Selección de Tema muestra el selector de temas incluso si el se ha desactivado la facilidad de cambio de tema.";
$pgv_lang["block_top10_title"]		= "Apellidos más comunes";
$pgv_lang["block_top10"]			= "Bloque de 10 apellidos más frecuentes";
$pgv_lang["block_top10_descr"]		= "Este bloque muestra una tabla de los 10 apellidos más frecuentes en la base de datos. El número real de apellidos mostrados en este bloque es configurable  Puede configurar el GEDCOM para eliminar nombres de esta lista.";

$pgv_lang["gedcom_news_block"]		= "Bloque de Novedades GEDCOM";
$pgv_lang["gedcom_news_descr"]		= "El bloque de Novedades del GEDCOM muestra al usuario novedades o artículos agragados por un usuario administrador.<br /><br />El bloque de Novedades es un buen sitio para anunciar una actualización de la base de datos, una reunión familiar o el nacimiento de un hijo.";
$pgv_lang["gedcom_news_limit"]		= "Limitar la presentación por:";
$pgv_lang["gedcom_news_limit_nolimit"]	= "Sin límite";
$pgv_lang["gedcom_news_limit_date"]		= "Antigüedad de la entrada";
$pgv_lang["gedcom_news_limit_count"]	= "Número de entradas";
$pgv_lang["gedcom_news_flag"]		= "Límite:";
$pgv_lang["gedcom_news_archive"] 	= "Ver archivo";
$pgv_lang["user_news_block"]		= "Bloque Boletín del Usuario";
$pgv_lang["user_news_descr"]		= "El Bloque de Diario del Usuario permita a los usuarios guardar notas en un diario en línea.";
$pgv_lang["my_journal"] 			= "Mi Boletín";
$pgv_lang["no_journal"] 			= "No ha creado ninguna entrada en el Boletín.";
$pgv_lang["confirm_journal_delete"] = "¿Está seguro de que desea borrar esta entrada del diario?";
$pgv_lang["add_journal"]			= "Agregar una nueva entrada al diario";
$pgv_lang["gedcom_news"]			= "Novedades";
$pgv_lang["confirm_news_delete"]	= "¿Está seguro de que desea eliminar esta entrada en Novedades?";
$pgv_lang["add_news"]				= "Agregar un artículo a Novedades";
$pgv_lang["no_news"]				= "No se han remitido artículos a Novedades";
$pgv_lang["edit_news"]				= "Agregar/editar entradas a Boletín/Novedades";
$pgv_lang["enter_title"]			= "Por favor introduzca un título.";
$pgv_lang["enter_text"] 			= "Por favor introduzca algún texto para esta novedad o artículo.";
$pgv_lang["news_saved"] 			= "Entrada en Novedades/Boletín guardada con éxito.";
$pgv_lang["article_text"]			= "Texto de la entrada:";
$pgv_lang["main_section"]			= "Bloques de la Sección Principal";
$pgv_lang["right_section"]			= "Bloques de la Sección Derecha";
$pgv_lang["available_blocks"]		= "Bloques disponibles";
$pgv_lang["move_up"]				= "Hacia arriba";
$pgv_lang["move_down"]				= "Hacia abajo";
$pgv_lang["move_right"] 			= "Mover a la derecha";
$pgv_lang["move_left"]				= "Mover a la izquierda";
$pgv_lang["broadcast_all"]			= "Difundir a todos los usuarios";
$pgv_lang["hit_count"]				= "Número de accesos:";
$pgv_lang["phpgedview_message"] 	= "Mensaje de PhpGedView";
$pgv_lang["common_surnames"]		= "Apellidos más comunes";
$pgv_lang["default_news_title"] 	= "Bienvenido a Su Genealogía";
$pgv_lang["default_news_text"]		= "La información genealógica de este sitio está gestionada por <a href=\"http://www.phpgedview.net/\" target=\"_blank\">PhpGedView #VERSION#</a>.  Esta página suministra una introducción e información general de esta genealogía.<br /><br />Para comenzar a trabajar con los datos, elija una de las páginas de diagramas del menú de Diagramas, diríjase a la lista de Personas o busque un nombre o un lugar.<br /><br />Si tiene problemas con el uso del sitio, puede hacer clic en el icono de Ayuda para obtener información sobre cómo utilizar la página en la que se encuentra.<br /><br />Gracias por visitar este sitio.";
$pgv_lang["reset_default_blocks"]	= "Reestablecer los bloques por defecto";
$pgv_lang["recent_changes"] 		= "Cambios recientes";
$pgv_lang["recent_changes_block"]	= "Bloque de modificaciones recientes";
$pgv_lang["recent_changes_descr"]	= "El Bloque de modificaciones recientes indicará todos los cambios realizados al GEDCOM en el último mes. Este bloque lo ayudará a mantenerse actualizado con las modificaciones realizadas. Los cambios se detectan automáticamente por la etiquete CHAN definida en el estándar GEDCOM.";
$pgv_lang["recent_changes_none"]	= "<b>No ha habido cambios en los últimos #pgv_lang[global_num1]# días.</b><br />";
$pgv_lang["recent_changes_some"]	= "<b>Cambios habidos en los últimos #pgv_lang[global_num1]# días</b><br />";
$pgv_lang["show_empty_block"]		= "¿Debe ocultarse este bloque si está vacío?";
$pgv_lang["hide_block_warn"]		= "Si oculta un bloque vacío, no podrá cambiar su configuración hasta que tenga datos nuevos que hagan que sea visible otra vez.";
$pgv_lang["delete_selected_messages"]	= "Eliminar los mensajes seleccionados";
$pgv_lang["use_blocks_for_default"]	= "¿Usar estos bloques como configuración predeterminada de bloques para todos los usuarios?";
$pgv_lang["block_not_configure"]	=	"No se puede configurar este bloque.";

//-- validate GEDCOM
$pgv_lang["add_media_tool"] 		= "Herramienta para Agregar Objetos Audiovisuales";
//-- hourglass chart
$pgv_lang["hourglass_chart"]		= "Diagrama Reloj de Arena";

//-- report engine
$pgv_lang["choose_report"]			= "Seleccione un informe";
$pgv_lang["enter_report_values"]	= "Introduzca los valores para el informe";
$pgv_lang["selected_report"]		= "Informe seleccionado";
$pgv_lang["select_report"]			= "Seleccionar informe";
$pgv_lang["download_report"]		= "Descargar informe";
$pgv_lang["reports"]				= "Informes";
$pgv_lang["pdf_reports"]			= "Informes PDF";
$pgv_lang["html_reports"]			= "Informes HTML";

//-- Ahnentafel report
$pgv_lang["ahnentafel_report"]		= "Informe Ahnentafel";
$pgv_lang["ahnentafel_header"]		= "Informe Ahnentafel para ";
$pgv_lang["ahnentafel_generation"]	= "Generación ";
$pgv_lang["ahnentafel_pronoun_m"]	= "Él ";
$pgv_lang["ahnentafel_pronoun_f"]	= "Ella ";
$pgv_lang["ahnentafel_born_m"]		= "nació";			// male
$pgv_lang["ahnentafel_born_f"]		= "nació";			// female
$pgv_lang["ahnentafel_christened_m"] = "fue bautizada";	// male
$pgv_lang["ahnentafel_christened_f"] = "fue bautizada";	// female
$pgv_lang["ahnentafel_married_m"]	= "se casó con";			// male
$pgv_lang["ahnentafel_married_f"]	= "se casó con";			// female
$pgv_lang["ahnentafel_died_m"]		= "falleció";				// male
$pgv_lang["ahnentafel_died_f"]		= "falleció";				// female
$pgv_lang["ahnentafel_buried_m"]	= "recibió sepultura";			// male
$pgv_lang["ahnentafel_buried_f"]	= "recibió sepultura";			// female
$pgv_lang["ahnentafel_place"]		= " en ";				// place name follows this
$pgv_lang["ahnentafel_no_details"]	= " pero los detalles son desconocidos";

//-- Descendancy report
$pgv_lang["descend_report"]		= "Informe de descendientes";
$pgv_lang["descendancy_header"]		= "Informe de descendientes para ";

$pgv_lang["family_group_report"]	= "Informe de grupo familiar";
$pgv_lang["page"]					= "Página";
$pgv_lang["of"] 					= "de";
$pgv_lang["enter_famid"]			= "Introducir ID de la familia";
$pgv_lang["show_sources"]			= "¿Mostrar fuentes?";
$pgv_lang["show_notes"] 			= "¿Mostrar notas?";
$pgv_lang["show_basic"] 			= "¿Mostrar siempre eventos básicos aunque no haya datos?";
$pgv_lang["show_photos"]			= "¿Mostrar fotos?";
$pgv_lang["relatives_report_ext"]	= "Informe expandido de parientes";
$pgv_lang["with"]					= "con";
$pgv_lang["on"]						= "a";			// for precise dates
$pgv_lang["in"]						= "en";			// for imprecise dates
$pgv_lang["individual_report"]		= "Informe de Persona";
$pgv_lang["enter_pid"]				= "Introduzca el ID de la persona";
$pgv_lang["generated_by"]			= "Generado por";
$pgv_lang["list_children"]			= "Listar cada hijo por orden de nacimiento.";
$pgv_lang["birth_report"]			= "Informe de fechas y lugares de nacimiento";
$pgv_lang["birthplace"]				= "El lugar de nacimiento contiene";
$pgv_lang["birthdate1"]				= "Comienzo del rango de fecha de nacimiento";
$pgv_lang["birthdate2"]				= "Fin del rango de fecha de nacimiento";
$pgv_lang["death_report"]			= "Informe de fechas y lugares de defunción";
$pgv_lang["deathplace"]				= "El lugar de defunción contiene";
$pgv_lang["deathdate1"]				= "Comienzo del rango de fechas de defunción";
$pgv_lang["deathdate2"]				= "Fin del rango de fechas de defunción";
$pgv_lang["marr_report"]			= "Informe de fechas y lugares de matrimonio";
$pgv_lang["marrplace"]				= "El lugar de matrimonio contiene";
$pgv_lang["marrdate1"]				= "Comienzo del rango de fechas de matrimonio";
$pgv_lang["marrdate2"]				= "Fin del rango de fechas de matrimonio";
$pgv_lang["sort_by"]				= "Ordenar por";

$pgv_lang["cleanup"]				= "Limpieza";

//-- CONFIGURE (extra) messages for programs patriarch and statistics
$pgv_lang["dynasty_list"]			= "Información general de familias";
$pgv_lang["patriarch_list"] 		= "Patriarcas";
$pgv_lang["statistics"] 			= "Estadísticas";

//-- Merge Records
$pgv_lang["merge_same"] 			= "Los registros no son del mismo tipo.  No se pueden mezclar registros de tipos distintos.";
$pgv_lang["merge_step1"]			= "Paso de mezcla 1 de 3";
$pgv_lang["merge_step2"]			= "Paso de mezcla 2 de 3";
$pgv_lang["merge_step3"]			= "Paso de mezcla 3 de 3";
$pgv_lang["select_gedcom_records"]	= "Seleccione los dos registros GEDCOM a mezclar.  Los registros deben ser del mismo tipo.";
$pgv_lang["merge_to"]				= "Mezclar al ID:";
$pgv_lang["merge_from"] 			= "Mezclar desde el ID:";
$pgv_lang["merge_facts_same"]		= "Los siguiente hechos eran exactamente iguales en ambos registros y se mezclarán automáticamente.";
$pgv_lang["no_matches_found"]		= "No se encontraron hechos que coincidieran";
$pgv_lang["unmatching_facts"]		= "Los siguientes hechos no coincidieron.  Seleccione qué información desea mantener.";
$pgv_lang["record"] 				= "Registro";
$pgv_lang["adding"] 				= "Agregando";
$pgv_lang["updating_linked"]		= "Actualizando registro vinculado";
$pgv_lang["merge_more"] 			= "Mezclar más registros.";
$pgv_lang["same_ids"]				= "Indicó el mismo ID ambas veces.  No puede mezclar un registro consigo mismo.";

//-- ANCESTRY FILE MESSAGES
$pgv_lang["ancestry_chart"] 		= "Diagrama de ascendencia";
$pgv_lang["gen_ancestry_chart"]		= "Diagrama de Ascendencia de #PEDIGREE_GENERATIONS# generaciones";
$pgv_lang["chart_style"]			= "Estilo del diagrama";
$pgv_lang["chart_list"]			= "Lista";
$pgv_lang["chart_booklet"]   	= "Libreta";
$pgv_lang["show_cousins"]			= "Mostrar primos";
// 1st generation
$pgv_lang["sosa_2"] 				= "Padre";
$pgv_lang["sosa_3"] 				= "Madre";
// 2nd generation
$pgv_lang["sosa_4"] 				= "Abuelo Paterno";
$pgv_lang["sosa_5"] 				= "Abuela Paterna";
$pgv_lang["sosa_6"] 				= "Abuelo Materno";
$pgv_lang["sosa_7"] 				= "Abuela Materna";
// 3rd generation
$pgv_lang["sosa_8"] 				= "Bisabuelo";
$pgv_lang["sosa_9"] 				= "Bisabuela";
$pgv_lang["sosa_10"]				= "Bisabuelo";
$pgv_lang["sosa_11"]				= "Bisabuela";
$pgv_lang["sosa_12"]				= "Bisabuelo";
$pgv_lang["sosa_13"]				= "Bisabuela";
$pgv_lang["sosa_14"]				= "Bisabuelo";
$pgv_lang["sosa_15"]				= "Bisabuela";
// 4th generation
$pgv_lang["sosa_16"]				= "Tatarabuelo";
$pgv_lang["sosa_17"]				= "Tatarabuela";
$pgv_lang["sosa_18"]				= "Tatarabuelo";
$pgv_lang["sosa_19"]				= "Tatarabuela";
$pgv_lang["sosa_20"]				= "Tatarabuelo";
$pgv_lang["sosa_21"]				= "Tatarabuela";
$pgv_lang["sosa_22"]				= "Tatarabuelo";
$pgv_lang["sosa_23"]				= "Tatarabuela";
$pgv_lang["sosa_24"]				= "Tatarabuelo";
$pgv_lang["sosa_25"]				= "Tatarabuela";
$pgv_lang["sosa_26"]				= "Tatarabuelo";
$pgv_lang["sosa_27"]				= "Tatarabuela";
$pgv_lang["sosa_28"]				= "Tatarabuelo";
$pgv_lang["sosa_29"]				= "Tatarabuela";
$pgv_lang["sosa_30"]				= "Tatarabuelo";
$pgv_lang["sosa_31"]				= "Tatarabuela";

// for the general case of ancestors of the nth generation use the text below
// in this text %1\$d is replaced with the number of generations
//              %2\$d is replaced with the number of generations - 1
//              %3\$d is replaced with the number of generations - 2
$pgv_lang["sosa_paternal_female_n_generations"]	= "%2\$dª abuela";
$pgv_lang["sosa_paternal_male_n_generations"]	= "%2\$dº abuelo";
$pgv_lang["sosa_maternal_female_n_generations"]	= "%2\$dª abuela";
$pgv_lang["sosa_maternal_male_n_generations"]	= "%2\$dº abuelo";

//-- FAN CHART
$pgv_lang["compact_chart"]			= "Diagrama compacto";
$pgv_lang["fan_chart"]				= "Diagrama circular";
$pgv_lang["gen_fan_chart"]  		= "Diagrama en abanico de #PEDIGREE_GENERATIONS# generaciones";
$pgv_lang["fan_width"]				= "Anchura del abanico";
$pgv_lang["gd_library"]				= "Problema en la configuración del servidor PHP: se necesita la biblioteca GD 2.x para utilizar las funciones de imágenes.";
$pgv_lang["gd_freetype"]			= "Problema en la configuración del servidor PHP: se necesita la biblioteca FreeType para utilizar las fuentes TrueType.";
$pgv_lang["gd_helplink"]			= "http://www.php.net/gd";
$pgv_lang["fontfile_error"]			= "No se encontró el archivo de fuente en el servidor PHP";
$pgv_lang["fanchart_IE"]			= "No es posible con su navegador imprimir directamente esta diagrama en abanico. Use el botón derecho del #pgv_lang[pgv_lang_es_mouse]# para guardarla e imprimirla posteriormente.";

//-- RSS Feed
$pgv_lang["rss_descr"]				= "Noticias y vínculos del sitio #GEDCOM_TITLE#";
$pgv_lang["rss_logo_descr"]			= "Canal creado por PhpGedView";
$pgv_lang["rss_feeds"]				= "Suscripciones RSS";
$pgv_lang["no_feed_title"]			= "No está disponible el flujo de datos";
$pgv_lang["no_feed"]				= "No hay información RSS disponible en este sitio PhpGedView";
$pgv_lang["feed_login"]				= "Si tiene una cuente en este sitio PhpGedView, puede <a href=\"#AUTH_URL#\">autenticarse</a> con el servidor usando Autenticación HTTP Básica para ver la información privada.";
$pgv_lang["authenticated_feed"]		= "Flujo autenticado";

//-- ASSOciates RELAtionship
// After any change in the following list, please check $assokeys in edit_interface.php
$pgv_lang["attendant"] = "Celador";
$pgv_lang["attending"] = "Presente";
$pgv_lang["best_man"] = "Padrino de boda";
$pgv_lang["bridesmaid"] = "Dama de honor";
$pgv_lang["buyer"] = "Comprador";
$pgv_lang["circumciser"] = "Circuncidador";
$pgv_lang["civil_registrar"] = "Registrador civil";
$pgv_lang["friend"] = "Amigo";
$pgv_lang["godfather"] = "Padrino";
$pgv_lang["godmother"] = "Madrina";
$pgv_lang["godparent"] = "Padrino o madrina";
$pgv_lang["informant"] = "Informador";
$pgv_lang["lodger"] = "Huésped";
$pgv_lang["nurse"] = "Enfermera";
$pgv_lang["priest"] = "Sacerdote";
$pgv_lang["rabbi"] = "Rabí";
$pgv_lang["registry_officer"] = "Funcionario del Registro";
$pgv_lang["seller"] = "Vendedor";
$pgv_lang["servant"] = "Criado";
$pgv_lang["twin"] = "Mellizo";
$pgv_lang["twin_brother"] = "Hermano mellizo";
$pgv_lang["twin_sister"] = "Hermana melliza";
$pgv_lang["witness"] = "Testigo";

//-- statistics utility
$pgv_lang["statutci"]			= "no se pudo crear el índice";
$pgv_lang["statnnames"]                = "número de nombres  =";
$pgv_lang["statnfam"]                  = "número de familias =";
$pgv_lang["statnmale"]                 = "número de hombres  =";
$pgv_lang["statnfemale"]               = "número de mujeres  =";
$pgv_lang["statvars"]			 = "Rellene las siguientes variables para el gráfico";
$pgv_lang["statlxa"]			 = "en el eje x:";
$pgv_lang["statlya"]			 = "en el eje y:";
$pgv_lang["statlza"]			 = "en el eje z";
$pgv_lang["stat_10_none"]		 = "ninguno";
$pgv_lang["stat_11_mb"]			 = "Mes de nacimiento";
$pgv_lang["stat_12_md"]			 = "Mes de fallecimiento";
$pgv_lang["stat_13_mm"]			 = "Mes de matrimonio";
$pgv_lang["stat_14_mb1"]		= "Mes de nacimiento del primer hijo de una relación";
$pgv_lang["stat_15_mm1"]		= "Mes del primer matrimonio";
$pgv_lang["stat_16_mmb"]		= "Meses entre matrimonio y primer hijo";
$pgv_lang["stat_17_arb"]			 = "edad con respecto al año de nacimiento.";
$pgv_lang["stat_18_ard"]			 = "edad con respecto al año de fallecimiento.";
$pgv_lang["stat_19_arm"]			 = "edad el año de matrimonio.";
$pgv_lang["stat_20_arm1"]			 = "edad el año del primer matrimonio.";
$pgv_lang["stat_21_nok"]			 = "número de hijos.";
$pgv_lang["stat_gmx"]			= " comprobar las marcas para mes";
$pgv_lang["stat_gax"]			= " comprobar las marcas para edades";
$pgv_lang["stat_gnx"]			= " comprobar las marcas para números";
$pgv_lang["stat_200_none"]			 = "todos (o en blanco)";
$pgv_lang["stat_201_num"]			 = "números";
$pgv_lang["stat_202_perc"]			 = "porcentaje";
$pgv_lang["stat_300_none"]		= "ninguno";
$pgv_lang["stat_301_mf"]			 = "hombres/mujeres";
$pgv_lang["stat_302_cgp"]			 = "periodos.  Comprobar marcas para periodos (eje-z).";
$pgv_lang["statmess1"]			 = "<b>Complete las siguientes filas en función de los que haya indicado para el eje x o el eje z</b>";
$pgv_lang["statar_xgp"]			 = "marcas para periodos (eje-x):";
$pgv_lang["statar_xgl"]			 = "marcas para edades    (eje x):";
$pgv_lang["statar_xgm"]			 = "marcas para mes   (eje x):";
$pgv_lang["statar_xga"]			 = "marcas para números (eje x):";
$pgv_lang["statar_zgp"]			 = "marcas para periodos (eje z):";
$pgv_lang["statreset"]			 = "reiniciar";
$pgv_lang["statsubmit"]			 = "mostrar el gráfico";

//-- statisticsplot utility
$pgv_lang["statistiek_list"]	= "Estadísticas";
$pgv_lang["stpl"]			 	= "...";
$pgv_lang["stplGDno"]			 = "La Graphics Display Library no está disponible en PHP 4. Por favor, contacte con el administrador de su sistema";
$pgv_lang["stpljpgraphno"]		= "Los módulos JPgraph no están presentes en el directorio <i>phpgedview/jpgraph/</i> . Por favor, obténgalos de http://www.aditus.nu/jpgraph/jpdownload.php<br> <h3>Instale antes JPgraph en el directorio <i>phpgedview/jpgraph/</i></h3><br>";
// $pgv_lang["stplinfo"]			 = "plotting information:";
$pgv_lang["stpltype"]			 = "tipo:";
$pgv_lang["stplnoim"]			 = " no implementado:";
$pgv_lang["stplmf"]			 = " / hombre-mujer";
$pgv_lang["stplipot"]			 = " / por período temporal";
$pgv_lang["stplgzas"]			 = "bordes eje-z:";
$pgv_lang["stplmonth"]			 = "mes";
$pgv_lang["stplnumbers"]		 = "números para una familia";
$pgv_lang["stplage"]			 = "edad";
$pgv_lang["stplperc"]			 = "porcentaje";
$pgv_lang["stplnumof"]			 = "Totales";
$pgv_lang["stplmarrbirth"]		 = "Meses entre el matrimonio y el nacimiento del primer hijo";

//-- alive in year
$pgv_lang["alive_in_year"]			= "Personas con vida en un cierto año";
$pgv_lang["is_alive_in"]			= "Está con vida en #YEAR#";
$pgv_lang["alive"]					= "Vivo/a ";
$pgv_lang["dead"]					= "Difunto/a ";
$pgv_lang["maybe"]					= "Quizá ";
$pgv_lang["both_alive"]					= "Ambos vivos ";
$pgv_lang["both_dead"]					= "Ambos fallecidos ";

//-- Help system
$pgv_lang["definitions"]			= "Definiciones";

//-- Index_edit
$pgv_lang["block_desc"]				= "Descripciones de los bloques";
$pgv_lang["click_here"]				= "Haga clic aquí para continuar";
$pgv_lang["click_here_help"]		= "~#pgv_lang[click_here]#~<br /><br />Haga clic en este botón para salvar sus cambios.<br /><br />Se le enviará a la página de #pgv_lang[welcome]# o de #pgv_lang[mygedview]#, pero sus cambios puede que no se muestren.  Puede ser necesario que utilice la función de Recargar Página de su navegador para ver los cambios correctamente.";
$pgv_lang["block_summaries"]		= "~#pgv_lang[block_desc]#~<br /><br />He aquí una corta descripción de los cada uno de los bloques que puede colocar en las páginas de #pgv_lang[welcome]# o #pgv_lang[mygedview]#.<br /><table border='1' align='center'><tr><td class='list_value'><b>#pgv_lang[name]#</b></td><td class='list_value'><b>#pgv_lang[description]#</b></td></tr>#pgv_lang[block_summary_table]#</table><br /><br />";
// Built in index_edit.php
$pgv_lang["block_summary_table"]	= "&nbsp;";

//-- Find page
$pgv_lang["total_places"]			= "Lugares encontrados";
$pgv_lang["media_contains"]			= "El objeto contiene:";
$pgv_lang["repo_contains"]			= "El repositorio contiene:";
$pgv_lang["source_contains"]		= "La fuente contiene:";
$pgv_lang["display_all"]			= "Mostrar todo";

//-- accesskey navigation
$pgv_lang["accesskeys"]				= "Atajos de teclado";
$pgv_lang["accesskey_skip_to_content"]	= "C";
$pgv_lang["accesskey_search"]	= "S";
$pgv_lang["accesskey_skip_to_content_desc"]	= "Saltar al Contenido";
// $pgv_lang["accesskey_viewing_advice"]	= "0";
$pgv_lang["accesskey_viewing_advice_desc"]	= "Consejos de visualización";
$pgv_lang["accesskey_home_page"]	= "1";
$pgv_lang["accesskey_help_content"]	= "2";
$pgv_lang["accesskey_help_current_page"]	= "3";
$pgv_lang["accesskey_contact"]	= "4";

$pgv_lang["accesskey_individual_details"]	= "I";
$pgv_lang["accesskey_individual_relatives"]	= "R";
$pgv_lang["accesskey_individual_notes"]	= "N";
$pgv_lang["accesskey_individual_sources"]	= "O";
//clash with IE addBookmark but not a likely problem
$pgv_lang["accesskey_individual_media"]	= "A";
$pgv_lang["accesskey_individual_research_log"]	= "L";
$pgv_lang["accesskey_individual_pedigree"]	= "P";
$pgv_lang["accesskey_individual_descendancy"]	= "D";
$pgv_lang["accesskey_individual_timeline"]	= "T";
$pgv_lang["accesskey_individual_relation_to_me"]	= "M";
//clash with rarely used English Netscape/Mozilla Go menu
$pgv_lang["accesskey_individual_gedcom"]	= "G";

$pgv_lang["accesskey_family_parents_timeline"]	= "P";
$pgv_lang["accesskey_family_children_timeline"]	= "D";
$pgv_lang["accesskey_family_timeline"]	= "T";
//clash with rarely used English Netscape/Mozilla English Go menu
$pgv_lang["accesskey_family_gedcom"]	= "G";

// FAQ Page
$pgv_lang["add_faq_header"] = "Encabezado de la Pregunta Frecuente";
$pgv_lang["add_faq_body"] = "Cuerpo de la Pregunta Frecuente";
$pgv_lang["add_faq_order"] = "Posición de la Pregunta Frecuente";
$pgv_lang["add_faq_visibility"] = "Visibilidad de la Pregunta Frecuente";
$pgv_lang["no_faq_items"] = "La lista de Preguntas Frecuentes está vacía.";
$pgv_lang["position_item"] = "Posicionar la entrada";
$pgv_lang["faq_list"] = "Preguntas frecuentes";
$pgv_lang["confirm_faq_delete"] = "¿Está seguro de que desea borrar esta Pregunta Frecuente?";
$pgv_lang["preview"] =  "Vista preliminar";
$pgv_lang["no_id"] = "¡No se ha indicado un ID de Pregunta Frecuente!";

// Help search
$pgv_lang["hs_title"] 			= "Texto de ayuda a la búsqueda";
$pgv_lang["hs_search"] 			= "Buscar";
$pgv_lang["hs_close"] 			= "Cerrar ventana";
$pgv_lang["hs_results"] 		= "Resultados encontrados:";
$pgv_lang["hs_keyword"] 		= "Buscar";
$pgv_lang["hs_searchin"]		= "Buscar en";
$pgv_lang["hs_searchuser"]		= "Ayuda de usuario";
// $pgv_lang["hs_searchmodules"]	= "Modules Help";
$pgv_lang["hs_searchconfig"]	= "Ayuda del administrador";
$pgv_lang["hs_searchhow"]		= "Tipo de búsqueda";
$pgv_lang["hs_searchall"]		= "Todas las palabras";
$pgv_lang["hs_searchany"]		= "Cualquier palabra";
$pgv_lang["hs_searchsentence"]	= "Frase exacta";
$pgv_lang["hs_intruehelp"]		= "Sólo texto de ayuda";
$pgv_lang["hs_inallhelp"]		= "Todo el texto";

// Media import
$pgv_lang["choose"] = "Escoger: ";
$pgv_lang["account_information"] = "Información de la cuenta";

//-- Media item "TYPE" sub-field
$pgv_lang["TYPE__audio"] = "Audio";
$pgv_lang["TYPE__book"] = "Libro";
$pgv_lang["TYPE__card"] = "Tarjeta";
$pgv_lang["TYPE__certificate"] = "Certificado";
$pgv_lang["TYPE__document"] = "Documento";
$pgv_lang["TYPE__electronic"] = "Electrónico";
$pgv_lang["TYPE__fiche"] = "Microficha";
$pgv_lang["TYPE__film"] = "Microfilm";
$pgv_lang["TYPE__magazine"] = "Revista";
$pgv_lang["TYPE__manuscript"] = "Manuscrito";
$pgv_lang["TYPE__map"] = "Mapa";
$pgv_lang["TYPE__newspaper"] = "Periódico";
$pgv_lang["TYPE__photo"] = "Foto";
$pgv_lang["TYPE__tombstone"] = "Lápida";
$pgv_lang["TYPE__video"] = "Vídeo";

//-- Other media suff
$pgv_lang["view_slideshow"] = "Ver como presentación";
$pgv_lang["download_image"]			= "Descargar arhivo";
$pgv_lang["no_media"]				= "No se encontraron objetos audiovisuales";
$pgv_lang["relations_heading"]		= "La imagen está relacionada con:";
$pgv_lang["file_size"]				= "Tamaño del archivo";
$pgv_lang["img_size"]				= "Dimensiones de la imagen";

//-- Modules
$pgv_lang["module_error_unknown_action_v2"] = "Acción desconocida: [acción].";
$pgv_lang["module_error_unknown_type"] = "Tipo de módulo desconocido.";

//-- sortable tables buttons
$pgv_lang["button_alive_in_year"] = "Mostrar las personas con vida en el año indicado.";
$pgv_lang["button_BIRT_Y100"] = "Mostrar las personas nacidas los últimos 100 años.";
$pgv_lang["button_BIRT_YES"] = "Mostrar personas nacidas hace más de 100 años.";
$pgv_lang["button_DEAT_H"] = "Mostrar parejas en las que sólo el hombre ha fallecido.";
$pgv_lang["button_DEAT_N"] = "Mostrar personas vivas o parejas en las que ambos están vivos.";
$pgv_lang["button_DEAT_W"] = "Mostrar las parejas en las que sólo la mujer ha fallecido.";
$pgv_lang["button_DEAT_Y"] = "Mostrar personas difuntas o parejas en las que ambos han fallecido.";
$pgv_lang["button_DEAT_Y100"] = "Mostrar las personas que fallecieron en los últimos 100 años.";
$pgv_lang["button_DEAT_YES"] = "Mostrar las personas que fallecieron hace más de 100 años.";
$pgv_lang["button_MARR_DIV"] = "Mostrar parejas divorciadas.";
$pgv_lang["button_MARR_U"] = "Mostrar parejas con fecha desconocida de matrimonio.";
$pgv_lang["button_MARR_Y100"] = "Mostrar parejas casadas los últimos 100 años.";
$pgv_lang["button_MARR_YES"] = "Mostrar parejas casadas hace más de 100 años.";
$pgv_lang["button_reset"] = "Reiniciar a las opciones predeterminadas para la lista.";
$pgv_lang["button_SEX_F"] = "Mostrar sólo mujeres.";
$pgv_lang["button_SEX_M"] = "Mostrar sólo hombres.";
$pgv_lang["button_SEX_U"] = "Mostrar sólo personas de sexo desconocido.";
$pgv_lang["button_TREE_L"] = "Mostrar parejas o personas «hoja».  Son personas que están vivas pero no tienen hijos registrados en la base de datos.";
$pgv_lang["button_TREE_R"] = "Mostrar parejas o personas «raíz».  También se las conoce como «patriarcas».  Son personas cuyos padres no constan en la base de datos.";
$pgv_lang["sort_column"] = "Clasificar por esta columna.";

# Additional strings
$pgv_lang["bosa_brothers_offspring_8"] = "sobrino bisnieto";
$pgv_lang["bosa_brothers_offspring_9"] = "sobrina bisnieta";
$pgv_lang["bosa_brothers_offspring_10"] = "sobrino bisnieto";
$pgv_lang["bosa_brothers_offspring_11"] = "sobrina bisnieta";
$pgv_lang["bosa_brothers_offspring_12"] = "sobrino bisnieto";
$pgv_lang["bosa_brothers_offspring_13"] = "sobrina bisnieta";
$pgv_lang["bosa_brothers_offspring_14"] = "sobrino bisnieto";
$pgv_lang["bosa_brothers_offspring_15"] = "sobrina bisnieta";
$pgv_lang["bosa_sisters_offspring_8"] = "sobrino nieto";
$pgv_lang["bosa_sisters_offspring_9"] = "sobrina nieta";
$pgv_lang["bosa_sisters_offspring_10"] = "sobrino nieto";
$pgv_lang["bosa_sisters_offspring_11"] = "sobrina nieta";
$pgv_lang["bosa_sisters_offspring_12"] = "sobrino nieto";
$pgv_lang["bosa_sisters_offspring_13"] = "sobrina nieta";
$pgv_lang["bosa_sisters_offspring_14"] = "sobrino nieto";
$pgv_lang["bosa_sisters_offspring_15"] = "sobrina nieta";
$pgv_lang["sosa_32"] = "Trastatarabuelo";
$pgv_lang["sosa_33"] = "Trastatarabuela";
$pgv_lang["sosa_34"] = "Trastatarabuelo";
$pgv_lang["sosa_35"] = "Trastatarabuela";
$pgv_lang["sosa_36"] = "Trastatarabuelo";
$pgv_lang["sosa_37"] = "Trastatarabuela";
$pgv_lang["sosa_38"] = "Trastatarabuelo";
$pgv_lang["sosa_39"] = "Trastatarabuela";
$pgv_lang["sosa_40"] = "Trastatarabuelo";
$pgv_lang["sosa_41"] = "Trastatarabuela";
$pgv_lang["sosa_42"] = "Trastatarabuelo";
$pgv_lang["sosa_43"] = "Trastatarabuela";
$pgv_lang["sosa_44"] = "Trastatarabuelo";
$pgv_lang["sosa_45"] = "Trastatarabuela";
$pgv_lang["sosa_46"] = "Trastatarabuelo";
$pgv_lang["sosa_47"] = "Trastatarabuela";
$pgv_lang["sosa_48"] = "Trastatarabuelo";
$pgv_lang["sosa_49"] = "Trastatarabuela";
$pgv_lang["sosa_50"] = "Trastatarabuelo";
$pgv_lang["sosa_51"] = "Trastatarabuela";
$pgv_lang["sosa_52"] = "Trastatarabuelo";
$pgv_lang["sosa_53"] = "Trastatarabuela";
$pgv_lang["sosa_54"] = "Trastatarabuelo";
$pgv_lang["sosa_55"] = "Trastatarabuela";
$pgv_lang["sosa_56"] = "Trastatarabuelo";
$pgv_lang["sosa_57"] = "Trastatarabuela";
$pgv_lang["sosa_58"] = "Trastatarabuelo";
$pgv_lang["sosa_59"] = "Trastatarabuela";
$pgv_lang["sosa_60"] = "Trastatarabuelo";
$pgv_lang["sosa_61"] = "Trastatarabuela";
$pgv_lang["sosa_62"] = "Trastatarabuelo";
$pgv_lang["sosa_63"] = "Trastatarabuela";

# Variants
$pgv_lang["pgv_lang_es_mouse"] = "ratón";
$pgv_lang["pgv_lang_es_Mouse"] = "Ratón";

?>
