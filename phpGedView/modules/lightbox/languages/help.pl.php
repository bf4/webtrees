<?php
/**
 * Polish language file for Lightbox Album module
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team. All rights reserved.
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
 * @author Łukasz Wileński <wooc@users.sourceforge.net>
 * @version $Id$
 */


echo "<font size=\"2\" face=\"Verdana\"> ";
echo "<h3>Lightbox-Album Pomoc: </h3>";
echo "<ol> ";

echo "<li>";
echo "<b><font color=\"blue\">Oglądanie obrazów</font></b><br />";
echo "Kliknij na miniaturkę. Tytuł obrazu będzie widoczny nad wyświetlanym obrazem.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Powiększenie</font></b><br />" ;
echo "Ważne: Pokaz slajdów musi być wyłączony aby była dostępna ikona powiększenia.<br />";
echo "<b> Włącz powiększenie: </b><br />";
echo "Jeśli jest widoczny zielony znak plusa w prawym dolnym rogu obrazka, powiększenie jest dostępne. Użyj rolki myszki aby zamieniać rozmiar obrazka. (Lub użyj klawiszy <b>I</b> oraz <b>O</b>) Ikona się zmieni na czerwony minus.<br /> ";
echo "Jeśli rozmiar powiększonego obrazu jest większy niż rozmiar strony, użyj strzałek aby przesuwać obraz.<br />";
echo "<b> Wyłącz powiększenie: </b><br />";
echo "Kliknij na czerwony znak minusa w prawym dolnym rogu aby powrócić do normalnego widoku. (Lub użyj klawisza <b>Z</b>)";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Zamknij obraz</font></b><br />";
echo "Kliknij na czerwony X w prawym dolnym rogu.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Pokaż następny lub poprzedni obraz</font></b><br />";
echo "Kiedy kursor myszki jest nad obrazem i jeśli powiększenie jest wyłączone, po lewej stronie pojawi się symbol <b>&lt;</b>, a po prawej <b>&gt;</b>. Kliknij w dowolne miejsce po prawej stronie obrazu aby zobaczyć następny lub po lewej stronie by zobaczyć poprzedni obraz.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Przejście do innego obrazu w Albumie</font></b><br />";
echo "Kiedy kursor myszki jest w górnej części obrazu i jeśli powiększenie jest wyłączone, będzie dostępna galeria miniaturek. Jeśli to konieczne przesuń kursor myszki w lewo lub w prawo by zobaczyć kolejne cześci galerii. Kliknij na wybraną miniaturkę aby zobaczyć obraz. Działa również podczas pokazu slajdów.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Uruchomienie pokazu slajdów</font></b><br />";
echo "Kliknij na ikonkę startu w lewym dolnym rogu. Jeśli jest dostępny plik z muzyką, pojawi się ikonka głośnika. Kliknij na nią aby włączyć lub wyłączyć muzykę. Kliknij na przycisk pauzy aby zatrzymać pokaz.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Nawigacja...</font></b><br />";
echo "Użyj tabeli Pokaż 'Album' znajdującej się po prawej stronie aby wybrać Album innej osoby.";
echo "<br /><br /></li>";

echo "</ol>";
echo "<ul>";

echo "<li>";
echo "<b>Uwaga:</b><br />";
echo "Miniaturki, które nie są obrazami, takie jak pliki PDF, audio lub wideo, mogą być oglądane oddzielnie, ale nie będą dostępne w pokazie slajdów.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b>Uwaga dla Administratora:</b><br />";
echo "Jeśli jakieś pliki obrazów (jpg, bmp, gif, itp.) reprezentujące typy obrazów jak fotografie, certyfikaty, dokumenty, itp. pojawią się w sekcji <b>Inne</b>, będziesz musiał ustawić typ multimediów dla tych obrazów.";
echo "<br /><br /></li>";

echo "</ul>";
 ?>