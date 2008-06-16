<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  John Finlay and Others
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
 * @translator Julio Sánchez Fernández
 */


echo "<font size=\"2\" face=\"Verdana\"> ";
echo "<h3>Lightbox-Album HELP: </h3>";
echo "<ol> ";

echo "<li>";
echo "<b><font color=\"blue\">Para ver una imagen</font></b><br />";
echo "Haga clic en cualquier miniatura.  El título de la imagen aparecera en la parte inferior de la imagen superpuesta. ";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Para usar el modo de ampliación</font></b><br />" ;
echo "NOTA: La presentación debe ponerse en Pausa para que se vean los iconos de Ampliación.<br />";
echo "<b> Activar Ampliación: </b><br />";
echo "Si está visible el icono verde Más en la parte inferior derecha de la imagen, el modo Ampliación ya está activado.  Utilice la rueda del ratón arriba y abajo para cambiar el tamaño. (o utilice las teclas <b>i</b> y <b>o</b>) El icono cambiará a un Menos rojo.<br /> ";
echo "Si la imagen es mayor que la página, utilice las teclas de flecha para mover la imagen.<br />";
echo "<b> Desactivar Ampliación: </b><br />";
echo "Haga clic en el icono rojo Menos en la esquina inferior derecha para abandonar el modo Ampliación. (O utilice la tecla <b>z</b>)";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Para cerrar una imagen </font></b><br />";
echo "Haga clic en el icono rojo X abajo a la derecha.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Para ver la imagen siguiente o anterior</font></b><br />";
echo "Al mover el puntero sobre la imagen NO estando en modo Ampliación, un símbolo <b>&lt;</b> aparecerá en el lado izquierdo y un <b>&gt;</b> a la derecha. Haga clic en cualquier lugar de la mitad derecha de la imagen para ver la siguiente. Haga clic en cualquier lugar de la mitad izquierda para ver la imagen anterior.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Para saltar a cualquier otra imagen del Álbum</font></b><br />";
echo "Si se mueve el puntero a menos de un centímetros del borde superior de la imagen NO estando en modo Ampliación, aparecerá una galería de miniaturas. Si es necesario, mueva el puntero a izquierda y derecha para que se muestren otras secciones de esta galería de miniaturas.  Haga clic en cualquier miniatura de la galería para saltar directamente a la imagen asociada. Se puede usar <b>Siguiente</b>, <b>Anterior</b> y <b>Saltar</b> tanto si la presentación está avanzado como si está en pausa.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Para comenzar la presentación</font></b><br />";
echo "Haga clic en el icono Comenzar abajo a la izquierda. Si hay una pista de sonido, aparecerá un icono de Altavoz.  Haga clic en el icono de Altavoz para activar o desactivar el pista de sonido. Haga clic en el icono de Pausa para detener la presentación.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Navegación ...</b></font><br />";
echo "Utilice la tabla Ver Álbum a la derecha de la tabla de icono de imagen para escoger directamente ver el Álbum de otra persona.";
echo "<br /><br /></li>";

echo "</ol>";
echo "<ul>";

echo "<li>";
echo "<b>Nota:</b><br />";
echo "Las miniaturas que NO son imágenes, como archivos PDF, audio y vídeo pueden verse de uno en uno, pero no se mostrarán en la presentación.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b>Nota para el administrador:</b><br />";
echo "Si aparecen en la fila <b>Otros</b> archivos de los formatos de imagen normales (jpg, bmp, gif, etc.) representando tipos de imágenes como fotos, certificados, documentos, etc., debe ser que ha olvidado fijar el tipo de objeto para estos objetos.  Puede convenir modificar el tipo de objeto para estos elementos.";
echo "<br /><br /></li>";

echo "</ul>";
 ?>
