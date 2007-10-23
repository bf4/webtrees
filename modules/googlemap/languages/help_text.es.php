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

$pgv_lang["GOOGLEMAP_CONFIG"]           = "Configurar Google-map";
$pgv_lang["GOOGLEMAP_CONFIG_help"]      = "~#pgv_lang[GOOGLEMAP_CONFIG]#~<br /><br />Configure todos los aspectos del módulo Google Map aquí.";

$pgv_lang["GOOGLEMAP_ENABLE"]           = "Activar GoogleMap";
$pgv_lang["GOOGLEMAP_ENABLE_help"]      = "~#pgv_lang[GOOGLEMAP_ENABLE]#~<br /><br />Mediante esta opción se puede activar o desactivar la funcionalidad de Googlemap.<br/>Si se desactiva, la pestaña Mapa de la página persona, todavía se muestra, pero aparecerá vacía. El vínculo de configuración para los administradores permanecerá disponible.";

$pgv_lang["GOOGLEMAP_API_KEY"]          = "Clave para el API de Google-map";
$pgv_lang["GOOGLEMAP_API_KEY_help"]     = "~#pgv_lang[GOOGLEMAP_API_KEY]#~<br /><br />Inserte su clave para el API de Google Maps.  Puede solicitar una clave en: <a target=\"_blank\" href=\"http://www.google.com/apis/maps/\">http://www.google.com/apis/maps/</a>";

$pgv_lang["GOOGLEMAP_MAP_TYPE"]         = "Tipo de Google-map";
$pgv_lang["GOOGLEMAP_MAP_TYPE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_TYPE]#~<br /><br />El tipo de mapa que se mostrará por defecto. Puede ser Mapa, Satélite o Híbrido.";

$pgv_lang["GOOGLEMAP_MAP_SIZE"]         = "Tamaño de Google-map";
$pgv_lang["GOOGLEMAP_MAP_SIZE_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_SIZE]#~<br /><br />Tamaño del mapa (en pixels) en la página de Persona.";

$pgv_lang["GOOGLEMAP_MAP_ZOOM"]         = "Factor de ampliación de Google-map";
$pgv_lang["GOOGLEMAP_MAP_ZOOM_help"]    = "~#pgv_lang[GOOGLEMAP_MAP_ZOOM]#~<br /><br />Los factores mínimo y máximo de ampliación para el mapa Google. Un 1 indica el mapa completo, 15 sería un edificio específico. Tome nota de que 15 sólo está disponible en algunas áreas.";

$pgv_lang["GOOGLEMAP_PRECISION"]        = "Precisión de la latitud y la longitud";
$pgv_lang["GOOGLEMAP_PRECISION_help"]   = "~#pgv_lang[GOOGLEMAP_PRECISION]#~<br /><br />Esto especifica la precisión de los diferentes niveles al introducir nuevas ubicaciones geográficas. Por ejemplo, un país se especificaría con precisión 0 (=0 dígitos tras la coma decimal), mientras que una población necesita 3 o 4 dígitos.";

$pgv_lang["GM_DEFAULT_LEVEL_0"]         = "Valor predeterminado para el nivel más alto";
$pgv_lang["GM_DEFAULT_LEVEL_0_help"]    = "~#pgv_lang[GM_DEFAULT_LEVEL_0]#~<br /><br />Aquí se define el nivel predeterminado para el nivel más alto de la jerarquía de lugares. Si un lugar no puede encontrarse, este nombre se le añade como nivel más alto (país) y se busca en la base de datos de nuevo.";

$pgv_lang["GM_NOF_LEVELS"]              = "~#pgv_lang[GM_NOF_LEVELS]#~<br /><br />Este campo indica el número de niveles en la jerarquía de lugares que se utiliza en los módulos Googlemap.<br/>El valor predeterminado es 4 (País, Estado/Provincia, Condado, Lugar), que es generalmente adecuado. Si desea añadir un nivel extra (por ejemplo, para añadir lugares específicos como cementerios o escuelas) cambie este valor.  Si quiere eliminar un nivel (por ejemplo, condado) también puede cambiar este valor, pero tenga en cuenta que los archivos que contienen las ubicaciones de los lugares contienen una estructura de cuatro niveles.";
$pgv_lang["GM_NOF_LEVELS_help"]         = "~#pgv_lang[GM_NOF_LEVELS]#~<br /><br />Este campo indica el número de niveles en la jerarquía de lugares que se utiliza en los módulos Googlemap.<br/>El valor predeterminado es 4 (País, Estado/Provincia, Condado, Lugar), que es generalmente adecuado. Si desea añadir un nivel extra (por ejemplo, para añadir lugares específicos como cementerios o escuelas) cambie este valor.  Si quiere eliminar un nivel (por ejemplo, condado) también puede cambiar este valor, pero tenga en cuenta que los archivos que contienen las ubicaciones de los lugares contienen una estructura de cuatro niveles.";

$pgv_lang["GM_NAME_PREFIX"]             = "Prefijo para los nombres utilizados en este nivel";
$pgv_lang["GM_NAME_PREFIX_help"]        = "~#pgv_lang[GM_NAME_PREFIX]#~<br /><br />Este valor puede anteponerse a los nombres de este nivel. Pueden usarse varios valores, separados por punto y coma";

$pgv_lang["GM_NAME_POSTFIX"]            = "Sufijo para los nombres utilizados en este nivel";
$pgv_lang["GM_NAME_POSTFIX_help"]       = "~#pgv_lang[GM_NAME_POSTFIX]#~<br /><br />Este valor se añadirá detrás de los nombres de este nivel. Pueden usarse varios valores, separados por punto y coma";

$pgv_lang["GM_NAME_PRE_POST"]           = "Orden de prefijo y sufijo a utilizar.";
$pgv_lang["GM_NAME_PRE_POST_help"]      = "~#pgv_lang[GM_NAME_PRE_POST]#~<br /><br />Este campo indica el orden en que se prueban los nombres usando el prefijo y el sufijo. Los valores posibles son:<br/><ul><li>Ni prefijo ni sufijo</li><li>Nombre normal, Prefijo, Sufijo, ambos</li><li>Nombre normal, Sufijo, Prefijo, ambos</li><li>Prefijo, Sufijo, ambos, Nombre normal</li><li>Sufijo, Prefijo, ambos, Nombre normal</li><li>Prefijo, Sufijo, Nombre normal, ambos</li><li>Sufijo, Prefijo, Nombre normal, ambos</li></ul>";

$pgv_lang["PL_EDIT_LOCATION"]           = "Modificar o borrar posición";
$pgv_lang["PL_EDIT_LOCATION_help"]      = "Aquí puede modificar la ubicación o borrarla. Si hace clic en Modificar, se abrirá una nueva ventana en la que podrá cambiar los valores de la posición geográfica.<br/>Si hace clic en el icono Borrar, el registro se borrará. Sólo se puede hacer esto si no hay registros conectados a esta ubicación.  El icono se activa o desactiva según sea posible usarlo o no.";

$pgv_lang["PL_ADD_LOCATION"]            = "Agregar posición geográfica";
$pgv_lang["PL_ADD_LOCATION_help"]       = "Utilice esto para agregar un lugar a la tabla de posiciones.  La posición se agregará a este nivel.";

$pgv_lang["PL_IMPORT_GEDCOM"]           = "Importar posiciones geográficas del GEDCOM";
$pgv_lang["PL_IMPORT_GEDCOM_help"]      = "Importar datos de posición geográfica del GEDCOM actual.  El GEDCOM actual se analizará y todos los lugares se añadirán a la tabla.  Si hay datos de latitud y longitud, también se importarán éstos.";

$pgv_lang["PL_IMPORT_ALL_GEDCOM"]       = "Importar posiciones geográficas de todos los GEDCOM";
$pgv_lang["PL_IMPORT_ALL_GEDCOM_help"]  = "Importar datos de posición geográfica de todos los GEDCOM. Todos los GEDCOM se analizarán y todos los lugares se añadirán a la tabla. Si hay datos de latitud y longitud, también se importarán éstos.";

$pgv_lang["PL_IMPORT_FILE"]             = "Importar posiciones geográficas de un archivo";
$pgv_lang["PL_IMPORT_FILE_help"]        = "Importar los datos de posición geográficos de un archivo. El archivo debería tener formato CSV en el equipo local. El separador de registros utilizado dentro de las líneas es ';'.";

$pgv_lang["PL_EXPORT_FILE"]             = "Exportar posiciones a un archivo";
$pgv_lang["PL_EXPORT_FILE_help"]        = "Exportar los datos de posición a un archivo. Esta opción salvará los datos de la vista actual y todos los datos dependientes a un archivo. Esto significa que si se selecciona un país y se muestran los estados o provincias, esta opción salvará los datos de los estados o provincias, todos los condados definidos en esos estados o provincias y todos los lugares en esos condados.";

$pgv_lang["PL_EXPORT_ALL_FILE"]         = "Exportar todas las posiciones a un archivo";
$pgv_lang["PL_EXPORT_ALL_FILE_help"]    = "Exportar todos los datos de posición a un archivo. Esta opción salvará todos los datos de posición y los transferirá al equipo local.";

$pgv_lang["GOOGLEMAP_COORD"]            = "Mostrar coordenadas del mapa";
$pgv_lang["GOOGLEMAP_COORD_help"]       = "~#pgv_lang[GOOGLEMAP_COORD]#~<br /><br />Esta opción determina si se muestran la latitud y la longitud en la ventana emergente asociada a los marcadores del mapa";

// Help texts for places_edit.php
$pgv_lang["PLE_EDIT"]               	= "Modificar los lugares de Google Map";
$pgv_lang["PLE_EDIT_help"]              = "Aquí puede agregar, modificar o borrar los detalles de los lugares para Google Map.";

$pgv_lang["PLE_PLACES"]                 = "Introduzca el nombre del lugar";
$pgv_lang["PLE_PLACES_help"]            = "Aquí puede introducir o cambiar el nombre del sitio.";

$pgv_lang["PLE_PRECISION"]              = "Introduzca la precisión";
$pgv_lang["PLE_PRECISION_help"]         = "Aquí puede introducir la precisión. En función de este ajuste se determina el número de dígitos que se usarán para la latitud y la longitud.";

$pgv_lang["PLE_LATLON_CTRL"]            = "Introduzca latitud o longitud";
$pgv_lang["PLE_LATLON_CTRL_help"]       = "Aquí se pueden introducir la latitud y la longitud. Seleccione primero el área que desea fijar (E/O o N/S). Introduzca a continuación el valor para la latitud o la longitud. El formato es de grados y fracción decimal.<br/>El valor según este formato puede determinarse convirtiendo los minutos y segundos según la siguiente fórmula:<br/>grados_y_fracción_decimal = ((segundos / 60) + minutos) / 60 + grados.";

$pgv_lang["PLE_ZOOM"]                   = "Aquí se puede introducir el nivel de ampliación. Este valor se utilizará como el valor mínimo al mostrar esta posición geográfica en un mapa.";
$pgv_lang["PLE_ZOOM_help"]              = "Aquí se puede introducir el nivel de ampliación. Este valor se utilizará como el valor mínimo al mostrar esta posición geográfica en un mapa.";

$pgv_lang["PLE_ICON"]                   = "Seleccione un icono";
$pgv_lang["PLE_ICON_help"]              = "Aquí se puede fijar o eliminar un icono. Utilizando este vínculo se puede seleccionar una bandera. Cuando se muestre esta posición geográfica, se mostrará esta bandera.";

$pgv_lang["PLE_FLAGS"]                  = "Seleccione bandera";
$pgv_lang["PLE_FLAGS_help"]             = "Utilizando el menú desplegable es posible seleccionar un país, para el que se puede seleccionar una bandera. Si no se muestra ninguna bandera, entonces es que no hay banderas definidas para este país.";

$pgv_lang["PLIF_FILENAME"]              = "Introduzca nombre de archivo";
$pgv_lang["PLIF_FILENAME_help"]         = "Introduzca el nombre del archivo que contiene las posiciones de los lugares en formato CSV.";

$pgv_lang["PLIF_CLEAN"]                 = "Limpiar la base de datos de posiciones de lugares";
$pgv_lang["PLIF_CLEAN_help"]            = "Si se selecciona esta opción se borrará la base de datos placelocation. Esto quiere decir que sólo la posición almacenada en esta tabla se borrará. Esto no cambiará nada en el GEDCOM.";

$pgv_lang["PLIF_UPDATE"]                = "Actualizar los registros existentes";
$pgv_lang["PLIF_UPDATE_help"]           = "Sólo actualizar los registros existentes.<br/>Si se selecciona esta opción, sólo se actualizarán los registros existentes. Esto puede utilizarse para rellenar la latitud y longitud de los lugares que se han importado de un GEDCOM. No se agregarán nuevos lugares a la base de datos.";

$pgv_lang["PLIF_OVERWRITE"]             = "Sobreescribir los datos de posición";
$pgv_lang["PLIF_OVERWRITE_help"]        = "Sobreescribir los datos de posición de la base de datos con los datos del archivo.<br/>Si se selecciona esta opción, los datos de posición en la base de datos ((latitud, longitud, nivel de ampliación y bandera) se sobreescriben con los datos del archivo, si están disponibles. Si el registro no está ya en la base de datos, se creará uno nuevo, a menos que se seleccione la opción de Sólo Actualizar se seleccione también.";

$pgv_lang["PLE_ACTIVE"]             	= "Listar lugares inactivos";
$pgv_lang["PLE_ACTIVE_help"]        	= "<strong>Lista los lugares en la tabla GoogleMaps que no están usados por ningún GEDCOM actual.</strong><br/><br/>La presentación se fija, por defecto, para mostrar solamente para modificación los lugares que existen tanto en sus archivos GEDCOM como en sus tablas GoogleMaps.<br/><br/>Si se marca esta opción y se hace clic en \"Ver\", la lista de lugares mostrará TODOS los lugares a este nivel.<br/><br/>Esto está diseñado para acelerar la presentación de la lista cuando se han importado grandes listas de lugares, pero no se han utilizado todos.<br/><br/>NOTA - si la opción se marca, la lista completa puede costar mostrarla varios minutos";

// Help text for placecheck.php
$pgv_lang["GOOGLEMAP_PLACECHECK"]       = "Herramienta de comprobación de lugares";
$pgv_lang["GOOGLEMAP_PLACECHECK_help"]  = "~#pgv_lang[GOOGLEMAP_PLACECHECK]#~<br /><br /><strong>Esta herramienta</strong> proporciona un medio de comparar lugares en su archivo gedcom con las entradas coincidentes de la tabla 'placelocations' de googlemaps.<BR/><BR/><strong>La presentación</strong> puede estructurarse para un archivo gedcom específico; para un país específico en ese archivo y para un área particular (p.ej. estado o condado) en ese país.<BR/><BR/><strong>Los lugares</strong>se listan alfabéticamente así que las pequeñas diferencias de escritura se puedan detectar fácilmente y corregir.<BR/><BR/><strong>A partir de </strong> los resultados de la comparación puede hacer clic en los nombres de lugares para estas tres opciones:<BR/><BR/><strong>1 - </strong>Para los lugares del archivo gedcom se le llevará a la vista de Jerarquía de Lugares. Aquí verá todos los registros que vinculan con ese lugar.<BR/><BR/><strong>2 - </strong>Para los lugares que existen en el archivo gedcom, pero no en la tabla googlemap (resaltada en rojo), obtendrá la pantalla \"Agregar lugar\" de googlemap.<BR/><BR/><strong>3 - </strong>Para los lugares que existen tanto en el archivo gedcom como en la tabla googlemap (quizá sin coordenadas) obtendrá la pantalla de googlemap \"modificar lugar\". Aquí puede modificar cualquier aspecto del registro del lugar para la presentación googlemap.<BR/><BR/><strong>Sobrevolar</strong> el puntero sobre cualquier lugar de las columnas de la tabla googlemap mostrará el nivel de ampliación fijado actualmente para ese lugar.";
$pgv_lang["PLACECHECK_FILTER"]       	= "Comprobación de lugares - Opciones de filtrado de la lista";
$pgv_lang["PLACECHECK_FILTER_help"]  	= "~#pgv_lang[PLACECHECK_FILTER]#~<br /><br />Esta sección incluye opciones para limitar o extender el alcance de los lugares listados.<br /><br />Se espera agregar más opciones en el futuro.";
$pgv_lang["PLACECHECK_MATCH"]       	= "Incluir sitios con correspondencia";
$pgv_lang["PLACECHECK_MATCH_help"]  	= "~#pgv_lang[PLACECHECK_MATCH]#~<br /><br />Por defecto, la lista NO INCLUYE lugares que correspondan completamente entre el archivo GEDCOM y las tablas de GoogleMap.<br/>Las correspondencias completas son aquellas en las que todos los niveles existen tanto en el archivo gedcom como en las tablas de GoogleMap y los lugares de GoogleMap tiene coordenadas para cada nivel.<br/><br/>Marque esta casilla para incluir esos sitios también";

?>
