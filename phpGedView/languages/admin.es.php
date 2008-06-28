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
 * @subpackage Languages
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Usted no puede acceder a este archivo de idioma directamente.";
	exit;
}

$pgv_lang["associated_files"]		= "Archivos asociados:";
$pgv_lang["remove_all_files"]		= "Borrar todos los archivos no esenciales";
$pgv_lang["warn_file_delete"]		= "Este archivo contiene información importante como ajustes de idioma o datos de los cambios pendientes.  ¿Está seguro de que desea borrar este archivo?";
$pgv_lang["deleted_files"]          = "Archivos borrados:";
$pgv_lang["index_dir_cleanup_inst"]	= "Para borrar un archivo del directorio de índice, arrástrelo a la basura o marque su casilla de selección.  Haga clic en el botón de borrar para eliminar permanentemente los archivos de la basura.<br /><br />Los archivos marcados con <img src=\"./images/RESN_confidential.gif\" /> se requieren para el correcto funcionamiento y no pueden eliminarse.<br />Los archivos marcados con <img src=\"./images/RESN_locked.gif\" /> tienen ajustes importantes o datos de cambios pendientes y sólo debería borrarlos si está seguro de lo que hace.<br /><br />";
$pgv_lang["index_dir_cleanup"]		= "Limpieza del directorio de índice";
$pgv_lang["clear_cache_succes"]		= "Los archivos recordados se han borrado.";
$pgv_lang["clear_cache"]			= "Limpiar los archivos de recuerdo";
$pgv_lang["sanity_err0"]			= "Errores:";
$pgv_lang["sanity_err1"]			= "Necesita tener PHP versión 4.3.5 o superior.";
$pgv_lang["sanity_err2"]			= "El archivo o directorio <i>#GLOBALS[whichFile]#</i> no existe. Verifique por favor que el archivo o directorio existe, su nombre está escrito correctamente y que los permisos de lectura son correctos.";
$pgv_lang["sanity_err3"]			= "No se pudo subir el archivo <i>#GLOBALS[whichFile]#</i> correctamente. Intente por favor subirlo de nuevo.";
$pgv_lang["sanity_err4"]			= "El archivo <i>config.php</i> está corrompido.";
$pgv_lang["sanity_err5"]			= "No se puede escribir en el archivo <i>config.php</i>.";
$pgv_lang["sanity_err6"]			= "No se puede escribir el directorio <i>#GLOBALS[INDEX_DIRECTORY]#</i>.";
$pgv_lang["sanity_warn0"]			= "Advertencias:";
$pgv_lang["sanity_warn1"]			= "No se puede escribir en el directorio <i>#GLOBALS[MEDIA_DIRECTORY]#</i>.  No podrá subir archivos audiovisuales o generar miniaturas en PhpGedView.";
$pgv_lang["sanity_warn2"]			= "El directorio <i>#GLOBALS[MEDIA_DIRECTORY]#thumbs</i> no se puede escribir.  No podrá subir miniaturas ni generarlas con PhpGedView.";
$pgv_lang["sanity_warn3"]			= "No existe la biblioteca de manejo de imágenes GD. PhpGedView funcionará a pesar de ello, pero algunas funciones, como la generación de miniaturas y el diagrama en círculo, no funcionarán sin la biblioteca GD.  Consulte por favor <a href='http://www.php.net/manual/en/ref.image.php'>http://www.php.net/manual/en/ref.image.php</a> (en inglés) para más información.";
$pgv_lang["sanity_warn4"]			= "No existe la biblioteca XML Parser. PhpGedView funcionará a pesar de ello, pero algunas de las funciones, como la generación de informes y los servicios web, no funcionarán sin la biblioteca XML Parser. Consulte por favor <a href='http://www.php.net/manual/en/ref.xml.php'>http://www.php.net/manual/en/ref.xml.php</a> (en inglés) para más información.";
$pgv_lang["sanity_warn5"]			= "No existe la biblioteca DOM XML. PhpGedView funcionará a pesar de ello, pero algunas funciones, como la exportación en formato Gramps en el carrito genealógico, la descarga y los servicios web no funcionarán. Consulte por favor <a href='http://www.php.net/manual/en/ref.domxml.php'>http://www.php.net/manual/en/ref.domxml.php</a> (en inglés) para más información.";
$pgv_lang["sanity_warn6"]			= "No existe la biblioteca Calendar. PhpGedView funcionará a pesar de ello, pero algunas funciones, como la conversión a otros calendarios como el hebreo o el francés revolucionario, no funcionarán.  No es esencial para utilizar PhpGedView. Consulte por favor <a href='http://www.php.net/manual/en/ref.calendar.php'>http://www.php.net/manual/en/ref.calendar.php</a> para más información.";
$pgv_lang["ip_address"]				= "Dirección IP";
$pgv_lang["date_time"]				= "Fecha y hora";
$pgv_lang["log_message"]			= "Mensaje en el diario";
$pgv_lang["searchtype"]				= "Tipo de búsqueda";
$pgv_lang["query"]					= "Búsqueda";
$pgv_lang["user"]					= "Usuario autenticado";
$pgv_lang["thumbnail_deleted"]		= "Archivo de miniatura borrado con éxito.";
$pgv_lang["thumbnail_not_deleted"]	= "No se pudo borrar el archivo de miniatura.";
$pgv_lang["step2"]					= "Paso 2 de 4:";
$pgv_lang["refresh"]				= "Refrescar";
$pgv_lang["move_file_success"]		= "Archivos principal y miniatura movidos con éxito.";
$pgv_lang["media_folder_corrupt"]	= "La carpeta de archivos audiovisuales está corrupta.";
$pgv_lang["media_file_not_deleted"]	= "No se pudo borrar el archivo audiovisual.";
$pgv_lang["gedcom_deleted"] 		= "GEDCOM [#GED#] eliminado correctamente.";
$pgv_lang["gedadmin"]				= "Administrador del GEDCOM";
$pgv_lang["full_name"]				= "Nombre Completo";
$pgv_lang["error_header"]			= "El archivo GEDCOM, [#GEDCOM#], No existe en la localización especificada.";
$pgv_lang["confirm_delete_file"]	= "¿Está seguro de querer borrar este archivo?";
$pgv_lang["confirm_folder_delete"] = "¿Está seguro de querer borrar esta carpeta?";
$pgv_lang["confirm_remove_links"]	= "¿Está seguro de querer borrar todos los vínculos a este objeto?";
$pgv_lang["PRIV_PUBLIC"]			= "Todo el mundo";
$pgv_lang["PRIV_USER"]				= "Sólo usuarios registrados";
$pgv_lang["PRIV_NONE"]				= "Sólo administradores";
$pgv_lang["PRIV_HIDE"]				= "Ocultar incluso a los administradores";
$pgv_lang["manage_gedcoms"] 		= "Administrar GEDCOMs";
$pgv_lang["keep_media"]				= "Conservar los vínculos a objetos audiovisuales";
$pgv_lang["files_in_backup"]		= "Archivos incluidos en esta copia de seguridad";
$pgv_lang["created_remotelinks"]	= "Creada la tabla <i>Vínculos remotos</i> con éxito.";
$pgv_lang["created_remotelinks_fail"] 	= "No se pudo crear la tabla de <i>Vínculos remotos</i>.";
$pgv_lang["created_indis"]			= "Tabla de <i>Personas</i> creada con éxito.";
$pgv_lang["created_indis_fail"] 	= "No se pudo crear la tabla de <i>Personas</i>";
$pgv_lang["created_fams"]			= "Tabla de <i>Familias</i> creada con éxito.";
$pgv_lang["created_fams_fail"]		= "No se pudo crear la tabla de <i>Familias</i>.";
$pgv_lang["created_sources"]		= "Tabla de <i>Fuentes</i> creada con éxito.";
$pgv_lang["created_sources_fail"]	= "No se pudo crear la tabla de <i>Fuentes</i>.";
$pgv_lang["created_other"]			= "Tabla de <i>Otros</i> creada con éxito.";
$pgv_lang["created_other_fail"] 	= "No se pudo crear la tabla de <i>Otros</i>.";
$pgv_lang["created_places"] 		= "Tabla de <i>Lugares</i> creada con éxito.";
$pgv_lang["created_places_fail"]	= "No se pudo crear la tabla de <i>Lugares</i>.";
$pgv_lang["created_placelinks"] 	= "Tabla de <i>Vínculos a lugares</i> creada con éxito.";
$pgv_lang["created_placelinks_fail"]	= "No se pudo crear la tabla de <i>Vínculos a lugares</i>.";
$pgv_lang["created_media_fail"]	= "No se pudo crear la tabla de <i>Objetos</i>.";
$pgv_lang["created_media_mapping_fail"]	= "No se pudo crear la tabla <i>Media mappings</i>.";
$pgv_lang["no_thumb_dir"]			= " directorio para miniaturas no existe y no se pudo crear.";
$pgv_lang["folder_created"]			= "Directorio creado";
$pgv_lang["folder_no_create"]		= "No se pudo crear el directorio";
$pgv_lang["security_no_create"]		= "Advertencia de seguridad: No existe el archivo <b><i>index.php</i></b> en ";
$pgv_lang["security_not_exist"]		= "Advertencia de seguridad: No se pudo crear el archivo <b><i>index.php</i></b> en ";
$pgv_lang["label_add_search_server"]	= "Agregar IP"; 
$pgv_lang["label_add_server"]      		= "Agregar";
$pgv_lang["label_ban_server"]			= "Enviar";
$pgv_lang["label_delete"]           	= "Borrar";
$pgv_lang["progress_bars_info"]			= "Las barras de estado que aparecen abajo le permiten ver el progreso de la importación.  Si el límite de tiempo se acaba, se detendrá la importación y se le pedirá que haga clic en el botón <b>Continuar</b>.  Si no ve el botón <b>Continuar</b>, tiene que reintentar la importación con un límite de tiempo más bajo.";
$pgv_lang["upload_replacement"]			= "Subir sustitución";
$pgv_lang["about_user"]					= "Primero debe crear el usuario de administración principal.  Este usuario tendrá privilegios para actualizar los archivos de configuración, ver datos privados, y crear otros usuarios.";
$pgv_lang["access"]						= "Acceso";
$pgv_lang["add_gedcom"] 				= "Añadir otro GEDCOM";
$pgv_lang["add_new_gedcom"] 			= "Crear un nuevo GEDCOM";
$pgv_lang["add_new_language"]			= "Agregar archivos y definiciones para un nuevo idioma";
$pgv_lang["add_user"]					= "Agregar un nuevo usuario";
$pgv_lang["admin_gedcom"]				= "Administrar GEDCOM";
$pgv_lang["admin_gedcoms"]				= "Haga clic aquí para administrar los archivos GEDCOMS.";
$pgv_lang["admin_geds"]					= "Administración de Datos y de archivos GEDCOM";
$pgv_lang["admin_info"]					= "Informativo";
$pgv_lang["admin_site"]					= "Administración del sitio";
$pgv_lang["admin_user_warnings"]		= "Una o más cuentas tienen avisos";
$pgv_lang["admin_verification_waiting"] = "Cuenta(s) de usuario(s) esperando verificación del Administrador";
$pgv_lang["administration"] 			= "Administración";
$pgv_lang["ALLOW_CHANGE_GEDCOM"]		= "Permitir a los visitantes elegir gedcoms:";
$pgv_lang["ALLOW_REMEMBER_ME"]			= "¿Mostrar la opción <b>Recordarme</b> en la página de entrada.";
$pgv_lang["ALLOW_USER_THEMES"]			= "Seleccionar tema por usuarios:";
$pgv_lang["ansi_encoding_detected"] 	= "Archivo detectado con codificación ANSI. PhpGedView funciona mejor con archivos codificados en UTF-8.";
$pgv_lang["ansi_to_utf8"]				= "¿Quiere convertir este GEDCOM desde ANSI (ISO-8859-1) a UTF-8?";
$pgv_lang["apply_privacy"]				= "¿Aplicar ajustes de privacidad?";
$pgv_lang["back_useradmin"]				= "Volver a Administración de Usuarios";
$pgv_lang["bytes_read"] 				= "Octetos leídos:";
$pgv_lang["calc_marr_names"]			= "Calculando Nombres de Casada";
$pgv_lang["can_admin"]					= "El usuario puede Administrar";
$pgv_lang["can_edit"]					= "Nivel de acceso";
$pgv_lang["change_id"]					= "Cambiar el ID de persona a:";
$pgv_lang["choose_priv"]				= "Escoja nivel de privacidad:";
$pgv_lang["cleanup_places"] 			= "Lugares depurados";
$pgv_lang["cleanup_users"]				= "Limpiar usuarios";
$pgv_lang["click_here_to_continue"]		= "Haga clic aquí para continuar.";
$pgv_lang["click_here_to_go_to_pedigree_tree"]	= "Haga clic aquí para ir al Árbol de ascendientes.";
$pgv_lang["comment"]							= "Comentarios del administrador acerca del usuario";
$pgv_lang["comment_exp"]						= "Avisar al administrador en la fecha";
$pgv_lang["config_help"]						= "Ayuda de la Configuración";
$pgv_lang["config_still_writable"]				= "Su archivo config.php todavía se puede escribir. Por seguridad, si ha finalizado la configuración de su sitio, debería modificar los permisos de escritura de este archivo a sólo-lectura.";
$pgv_lang["configuration"]						= "Configuración";
$pgv_lang["configure"]							= "Configurar PHPGedView";
$pgv_lang["configure_head"]						= "Configuración PhpGedView";
$pgv_lang["confirm_gedcom_delete"]				= "Está seguro de querer eliminar este archivo Gedcom";
$pgv_lang["confirm_user_delete"]				= "Seguro que quiere borrar el usuario";
$pgv_lang["create_user"]						= "Crear Usuario";
$pgv_lang["current_users"]						= "Lista de Usuarios";
$pgv_lang["daily"]								= "Diariamente";
$pgv_lang["dataset_exists"] 					= "Un archivo GEDCOM con este nombre ya ha sido importado en esta Base de datos.";
$pgv_lang["unsync_warning"] 					= "Este archivo GEDCOM <em>not</em> está sincronizado con la base de datos.  Puede que no contenga la última versión de sus datos.  Para reimportar a partir de la base de datos en vez de a partir del archivo, descargue el archivo a partir de la base de datos y vuelva a subir el archivo resultante.";
$pgv_lang["date_registered"]					= "Fecha de Registro";
$pgv_lang["day_before_month"]					= "Día antes del mes (DD MM YYYY)";
$pgv_lang["DEFAULT_GEDCOM"]						= "GEDCOM por defecto";
$pgv_lang["default_user"]						= "Cree el usuario de administración por defecto.";
$pgv_lang["del_gedrights"]						= "El GEDCOM ya no está activo, eliminar referencias de usuarios.";
$pgv_lang["del_proceed"]						= "Continuar";
$pgv_lang["del_unvera"]							= "Usuario no verificado por un administrador.";
$pgv_lang["del_unveru"]							= "El usuario no verificó su dirección en 7 días.";
$pgv_lang["do_not_change"]						= "No modificar";
$pgv_lang["download_gedcom"]					= "Descargar GEDCOM";
$pgv_lang["download_here"]						= "Haga clic aquí para descargar el archivo.";
$pgv_lang["download_note"]						= "NOTA: Los archivos GEDCOM de gran tamaño pueden demorar mucho tiempo de proceso antes de su descarga. Si expira el plazo para el proceso PHP antes de que termine la descarga, ésta podría quedar incompleta.<br /><br />Para asegurarse de que el GEDCOM se descargó correctamente, compruebe la existencia de la línea <b>0&nbsp;TRLR</b> al final del archivo.  Los archivos GEDCOM son de texto, puede utilizar cualquier editor de texto para comprobarlo, pero asegúrese de <u>no salvar</u> el archivo GEDCOM después de haberlo inspeccionado.<br /><br />En general, podría costar tanto tiempo descargarlo como costó importar su archivo GEDCOM.";
$pgv_lang["editaccount"]						= "Permitir que este usuario modifique la información de su cuenta";
$pgv_lang["empty_dataset"]						= "¿Quiere vaciar los datos?";
$pgv_lang["empty_lines_detected"]				= "Se detectaron líneas vacías en su archivo GEDCOM. Al realizar limpieza, las líneas vacías se eliminarán.";
$pgv_lang["enable_disable_lang"]				= "Configurar los idiomas soportados";
$pgv_lang["error_ban_server"]       			= "Dirección IP inválida.";
$pgv_lang["error_delete_person"]   				= "Debe seleccionar la persona cuyo vínculo remoto desee borrar.";
$pgv_lang["error_header_write"] 				= "El archivo GEDCOM, [#GEDCOM#], no es grabable. Controle sus atributos y privilegios de acceso.";
$pgv_lang["error_siteauth_failed"]				= "Falló la autenticación con el sitio remoto";
$pgv_lang["error_url_blank"]					= "Por favor, no deje en blanco ni el título del sitio remoto ni la URL";
$pgv_lang["error_view_info"]       				= "Debe seleccionar la persona cuya información desee ver.";
$pgv_lang["example_date"]						= "Ejemplo de fecha inválida en su GEDCOM:";
$pgv_lang["example_place"]						= "Ejemplo de lugar inválido en su GEDCOM:";
$pgv_lang["fbsql"]								= "FrontBase";
$pgv_lang["found_record"]						= "Registros encontrados";
$pgv_lang["ged_download"]						= "Descargar";
$pgv_lang["ged_import"] 						= "Importar gedcom";
$pgv_lang["ged_check"] 							= "Comprobar";
$pgv_lang["gedcom_adm_head"]					= "Administración GEDCOM";
$pgv_lang["gedcom_config_write_error"]			= "¡¡¡Error!!! No se pudo escribir el archivo de Configuración GEDCOM.";
$pgv_lang["gedcom_downloadable"] 				= "¡Este archivo GEDCOM puede descargarse desde la Internet!<br/>Por favor, vea la sección SECURITY del archivo <a hef=\"readme.txt\">readme.txt</a> para solventar este problema.";
$pgv_lang["gedcom_file"]						= "Archivo GEDCOM:";
$pgv_lang["gedcom_not_imported"]				= "Este Gedcom no ha sido importado todavía.";
$pgv_lang["ibase"]								= "InterBase";
$pgv_lang["ifx"]								= "Informix";
$pgv_lang["img_admin_settings"] 				= "Configuración de Manejo de Imágenes";
$pgv_lang["autoContinue"]						= "Pulsar automáticamente el botón «Continuar»";
$pgv_lang["import_complete"]					= "Importación completa";
$pgv_lang["import_marr_names"]					= "Calcular nombres de casada";
$pgv_lang["import_options"]						= "Opciones de importación";
$pgv_lang["import_progress"]					= "Progreso de la importación...";
$pgv_lang["import_statistics"]					= "Estadísticas de la importación";
$pgv_lang["import_time_exceeded"]				= "Se sobrepasó el límite de tiempo de ejecución.  Haga clic en el botón Continuar para proseguir la importación del archivo GEDCOM.";
$pgv_lang["inc_languages"]						= " Idiomas";
$pgv_lang["INDEX_DIRECTORY"]					= "Directorio de los archivos de índice:";
$pgv_lang["invalid_dates"]						= "Se detectaron formatos de fecha inválidos, al realizar 'Limpieza' se cambiarán al formato DD MMM YYYY (p.ej. 1 ENE 2004).";
$pgv_lang["BOM_detected"] 						= "Se detectó una Marca de Orden de Octetos (BOM) al principio del archivo.  Al limpiar, este código especial se eliminará.";
$pgv_lang["invalid_header"] 					= "Se detectaron registros antes del encabezamiento GEDCOM (0 HEAD). Al realizar limpieza estos registros se eliminarán.";
$pgv_lang["label_added_servers"]				= "Servidores remotos añadidos";
$pgv_lang["label_banned_servers"]  				= "Excluir sitios por IP";
$pgv_lang["label_families"]         			= "Familias";
$pgv_lang["label_gedcom_id2"]       			= "ID GEDCOM:";
$pgv_lang["label_individuals"]      			= "Personas";
$pgv_lang["label_manual_search_engines"]		= "Etiquetar manualmente motores de búsqueda por IP";
$pgv_lang["label_new_server"]     				= "Agregar nuevo sitio";
$pgv_lang["label_password_id"]					= "Contraseña";
$pgv_lang["label_remove_ip"]					= "Excluir dirección IP (p.ej.: 198.128.*.*): ";
$pgv_lang["label_remove_search"]				= "Marcar direcciones IP como arañas de motor de búsqueda: ";
$pgv_lang["label_server_info"]     				= "Todas las personas vinculadas remotamente a través de este sitio:";
$pgv_lang["label_server_url"]       			= "URL/IP del sitio";
$pgv_lang["label_username_id"]					= "Nombre de usuario";
$pgv_lang["label_view_local"]       			= "Ver la información local de la persona";
$pgv_lang["label_view_remote"]     			 	= "Ver la información remota de la persona";
$pgv_lang["LANG_SELECTION"] 					= "Idiomas seleccionables";
$pgv_lang["LANGUAGE_DEFAULT"]					= "No ha configurado los idiomas de su sitio.<br />PhpGedView utilizará sus opciones predeterminadas.";
$pgv_lang["last_login"]							= "Última entrada";
$pgv_lang["lasttab"]							= "Última pestaña visitada en Personas";
$pgv_lang["leave_blank"]						= "Deje la contraseña en blanco si quiere conservar la contraseña actual.";
$pgv_lang["link_manage_servers"]   				= "Gestionar sitios";
$pgv_lang["logfile_content"]					= "Contenido del archivo de registro";
$pgv_lang["macfile_detected"]					= "Se detectó un archivo Macintosh. Al realizar limpieza será convertido a archivo DOS.";
$pgv_lang["mailto"]								= "Vínculo mailto";
$pgv_lang["merge_records"]						= "Mezclar registros";
$pgv_lang["message_to_all"]						= "Enviar un mensaje a todos los usuarios";
$pgv_lang["messaging"]							= "Mensajes privados";
$pgv_lang["messaging2"]							= "Mensajes privados con correo electrónico";
$pgv_lang["messaging3"]							= "PhpGedView envía correo sin almacenamiento";
$pgv_lang["month_before_day"]					= "Mes antes del día (MM DD YYYY)";
$pgv_lang["monthly"]							= "Mensualmente";
$pgv_lang["msql"]								= "Mini SQL";
$pgv_lang["mssql"]								= "Microsoft SQL server";
$pgv_lang["mysql"]								= "MySQL";
$pgv_lang["mysqli"]								= "MySQL 4.1+ y PHP 5";
$pgv_lang["never"]								= "Nunca";
$pgv_lang["no_logs"]							= "Registro de usuarios desactivado";
$pgv_lang["no_messaging"]						= "Ningún método de contacto";
$pgv_lang["oci8"]								= "Oracle 7+";
$pgv_lang["page_views"]							= "&nbsp;&nbsp;visualizaciones de páginas en&nbsp;&nbsp;";
$pgv_lang["performing_validation"]				= "Realizando validación del GEDCOM...";
$pgv_lang["pgsql"]								= "PostgreSQL";
$pgv_lang["pgv_config_write_error"] 			= "¡¡¡Error!!! No se pudo escribir el archivo de configuración de PhpGedView.  Por favor, compruebe los permisos de archivo y directorio y pruebe de nuevo.";
$pgv_lang["PGV_MEMORY_LIMIT"]					= "Límite máximo de memoria.";
$pgv_lang["pgv_registry"]						= "Ver otros sitios que usan PhpGedView";
$pgv_lang["PGV_SESSION_SAVE_PATH"]				= "Ruta para Guardar Sesión:";
$pgv_lang["PGV_SESSION_TIME"]					= "Duración de la Sesión";
$pgv_lang["PGV_SIMPLE_MAIL"] 					= "Usar encabezamientos simples en los correos externos";
$pgv_lang["PGV_STORE_MESSAGES"]					= "Permitir el archivo de mensajes en línea:";
$pgv_lang["phpinfo"]							= "PHPInfo";
$pgv_lang["place_cleanup_detected"] 			= "Se detectaron codificaciones de lugar incorrectas. Estos errores deberían corregirse.";
$pgv_lang["please_be_patient"]					= "POR FAVOR SEA PACIENTE";
$pgv_lang["privileges"]							= "Privilegios";
$pgv_lang["reading_file"]						= "Leyendo archivo GEDCOM";
$pgv_lang["readme_documentation"]				= "Lea la documentación";
$pgv_lang["remove_ip"] 							= "Eliminar IP";
$pgv_lang["REQUIRE_ADMIN_AUTH_REGISTRATION"] 	= "Requerir que un administrador apruebe el registro de nuevos usuarios";
$pgv_lang["review_readme"]						= "Debería repasar primero el archivo <a href=\"readme.txt\" target=\"_blank\">readme.txt</a> antes de continuar configurando PhpGedView.<br /><br />";
$pgv_lang["rootid"] 							= "Persona Inicial para el Árbol de Ascendientes";
$pgv_lang["seconds"]							= "&nbsp;&nbsp;segundos";
$pgv_lang["select_an_option"]					= "Seleccione una opción:";
$pgv_lang["SERVER_URL"]							= "URL del Servidor:";
$pgv_lang["show_phpinfo"]						= "Ver Información PHP";
$pgv_lang["siteadmin"]							= "Administrador del sitio";
$pgv_lang["skip_cleanup"]						= "Omitir Limpieza";
$pgv_lang["sqlite"]								= "SQLite";
$pgv_lang["sybase"]								= "Sybase";
$pgv_lang["sync_gedcom"]						= "Sincronizar los ajustes de usuario con los datos GEDCOM";
$pgv_lang["system_time"]						= "Hora actual del sistema:";
$pgv_lang["user_time"]							= "Hora del usuario actual:";
$pgv_lang["TBLPREFIX"]							= "Prefijo de las tablas de la base de datos";
$pgv_lang["themecustomization"]					= "Personalización del tema";
$pgv_lang["time_limit"]							= "Límite de tiempo:";
$pgv_lang["title_manage_servers"]   			= "Gestionar sitios";
$pgv_lang["title_view_conns"]       			= "Ver conexiones";
$pgv_lang["translator_tools"]					= "Herramientas de traductor";
$pgv_lang["update_myaccount"]					= "Actualizar mi cuenta";
$pgv_lang["update_user"]						= "Actualizar usuario";
$pgv_lang["upload_gedcom"]						= "Subir GEDCOM";
$pgv_lang["USE_REGISTRATION_MODULE"]			= "Permitir registrarse a los usuarios:";
$pgv_lang["user_auto_accept"]					= "Aceptar automáticamente los cambios hechos por este usuario";
$pgv_lang["user_contact_method"]				= "Método de Contacto Preferido";
$pgv_lang["user_create_error"]					= "No es posible agregar el usuario.  Por favor vuelva atrás y comience de nuevo.";
$pgv_lang["user_created"]						= "Usuario creado correctamente.";
$pgv_lang["user_default_tab"]					= "Pestaña a mostrar por defecto en la página de información de una persona";
$pgv_lang["user_path_length"]					= "Máxima longitud de la ruta de privacidad por parentesco";
$pgv_lang["user_relationship_priv"]				= "Limitar el acceso a personas emparentadas";
$pgv_lang["users_admin"]						= "Administradores del sitio";
$pgv_lang["users_gedadmin"]						= "Administradores de GEDCOM";
$pgv_lang["users_total"]						= "Número total de usuarios";
$pgv_lang["users_unver"]						= "No verificados por el usuario";
$pgv_lang["users_unver_admin"]					= "No verificados por un administrador";
$pgv_lang["usr_deleted"]						= "Usuario borrado: ";
$pgv_lang["usr_idle"]							= "Número de meses desde la última conexión para que una cuenta de usuario se considere inactiva: ";
$pgv_lang["usr_idle_toolong"]					= "La cuenta de usuario ha estado inactiva demasiado tiempo: ";
$pgv_lang["usr_no_cleanup"]						= "No se encontró nada que limpiar";
$pgv_lang["usr_unset_gedcomid"]					= "Borrando el ID GEDCOM de ";
$pgv_lang["usr_unset_rights"]					= "Eliminando derechos de acceso de ";
$pgv_lang["usr_unset_rootid"]					= "Cancelando derechos de administrador de ";
$pgv_lang["valid_gedcom"]						= "Se detectó un GEDCOM válido. No se requiere 'Limpieza'.";
$pgv_lang["validate_gedcom"]					= "Validar GEDCOM";
$pgv_lang["verified"]							= "Usuario autoverificado";
$pgv_lang["verified_by_admin"]					= "Usuario aprobado por Admin.";
$pgv_lang["verify_gedcom"]						= "Verificar el GEDCOM";
$pgv_lang["verify_upload_instructions"]			= "Se ha encontrado un archivo GEDCOM con el mismo nombre. Si elige continuar, el viejo archivo GEDCOM será reemplazado por el archivo que subió y el proceso de Importación comenzará de nuevo.  Si elije cancelar, el viejo GEDCOM permanecerá intacto.";
$pgv_lang["view_changelog"]						= "Ver el archivo changelog.txt";
$pgv_lang["view_logs"]							= "Ver archivos log";
$pgv_lang["view_readme"]						= "Ver el archivo readme.txt";
$pgv_lang["visibleonline"]						= "Visible para los demás usuarios cuando esté conectado";
$pgv_lang["visitor"]							= "Visitante";
$pgv_lang["warn_users"]							= "Usuarios con avisos";
$pgv_lang["weekly"]								= "Semanalmente";
$pgv_lang["welcome_new"]						= "Bienvenido a su nuevo sitio PhpGedView. Dado que está viendo esta página, ha instalado PhpGedView con éxito y puede comenzar a configurarlo en la forma que desee.<br>";
$pgv_lang["yearly"]								= "Anualmente";
$pgv_lang["admin_OK_subject"]					= "Aprobación de la cuenta en #SERVER_NAME#";
$pgv_lang["admin_OK_message"]					= "El administrador del sitio PhpGedView #SERVER_NAME# ha aprobado su solicitud de cuenta.  Ya puede entrar de forma identificada accediendo al siguiente vínculo:\r\n\r\n#SERVER_NAME#\r\n";

// Text for the Gedcom Checker
$pgv_lang["gedcheck"]     = "Comprobador de Gedcom";          // Module title
$pgv_lang["gedcheck_text"]= "Este módulo comprueba el formato de un archivo GEDCOM contra la <a href=\"http://phpgedview.sourceforge.net/ged551-5.pdf\">Especificación GEDCOM 5.5.1</a>.  También comprueba una serie de errores corrientes en sus datos.  Nótese que hay muchas versiones, extensiones y variaciones de la especificación así que no debería preocuparse por problemas salvo los señalados como \"Críticos\".  La explicación para cada uno de los errores se encuentra en la especificación, consúltela, por favor, antes de solicitar ayuda.";
$pgv_lang["level"]        = "Nivel";                   // Levels of checking
$pgv_lang["critical"]     = "Crítico";
$pgv_lang["error"]        = "Error";
$pgv_lang["warning"]      = "Aviso";
$pgv_lang["info"]         = "Información";
$pgv_lang["open_link"]    = "Abrir vínculos en";           // Where to open links
$pgv_lang["same_win"]     = "La misma pestaña/ventana";
$pgv_lang["new_win"]      = "Una nueva pestaña/ventana";
$pgv_lang["context_lines"]= "Líneas de contexto GEDCOM"; // Number of lines either side of error
$pgv_lang["all_rec"]      = "Todos los registros";             // What to show
$pgv_lang["err_rec"]      = "Registros con errores";
$pgv_lang["missing"]      = "faltan";                 // General error messages
$pgv_lang["multiple"]     = "múltiples";
$pgv_lang["invalid"]      = "inválido";
$pgv_lang["too_many"]     = "demasiados";
$pgv_lang["too_few"]      = "demasiados pocos";
$pgv_lang["no_link"]      = "no vincula de vuelta";
$pgv_lang["data"]         = "datos";                    // Specific errors (used with general errors)
$pgv_lang["see"]          = "ver";
$pgv_lang["noref"]        = "Ningún registro referencia éste";
$pgv_lang["tag"]          = "etiqueta";
$pgv_lang["spacing"]      = "espaciado";
$pgv_lang["ADVANCED_NAME_FACTS"] = "Hechos avanzados para nombres";
$pgv_lang["ADVANCED_PLAC_FACTS"] = "Hechos avanzados para los nombres de lugares";
$pgv_lang["SURNAME_TRADITION"]		= "Tradición de apellidos"; // Default surname inheritance
$pgv_lang["tradition_spanish"]		= "Español";
$pgv_lang["tradition_portuguese"]	= "Portugués";
$pgv_lang["tradition_icelandic"]	= "Islandés";
$pgv_lang["tradition_paternal"]		= "Paterno";
$pgv_lang["tradition_none"]			= "Ninguno";				

?>
