<?php
/**
 * English language file for Lightbox Album module
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PhpGedView developers
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
 * @version $Id:
 * @author Łukasz Wileński
 */

//-- security check, only allow access from module.php
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Nie można uzyskać bezpośredniego dostępu do pliku.";
	exit;
}

$pgv_lang["mediatabLegend"]				= "Właściwości zakładki Multimedia";
$pgv_lang["mediatab_help"]				= "~#pgv_lang[mediatab]#~<br />Ta opcja pozwala na określenie czy zakładka Multimediów ma być widoczna na stronie #pgv_lang[indi_info]#.<br /><br />Jeśli ta opcja jest ustawiona na <b>#pgv_lang[hide]#</b>, tylko zakładka <b>#pgv_lang[lightbox]#</b> będzie widoczna i będzie jej nazwa zmieniona na <b>#pgv_lang[media]#</b>.<br />";
$pgv_lang["lb_al_head_linksLegend"]		= "Właściwości zakładki Album";
$pgv_lang["lb_al_head_links_help"]		= "~#pgv_lang[lb_al_head_linksLegend]#~<br />Ta opcja pozwala na określenie czy nagłowek zakładki #pgv_lang[lightbox]#, który zawiera odnośniki kontroli różnych opcji Albumu, powinien zawierać tylko ikony, tylko tekst, czy ikony wraz z tekstem.<br /><br />Opcja <b>#pgv_lang[lb_icon]#</b> raczej nie jest bardzo pomocna dopóki nie zobaczysz podpisów gdy najedziesz kursorem na nie.<br />";
$pgv_lang["lb_al_thumb_linksLegend"]	= "Wygląd odnośnika";
$pgv_lang["lb_al_thumb_links_help"]		= "~#pgv_lang[lb_al_thumb_linksLegend]#~<br />Ta opcja pozwala na określenie czy odnośniki znajdujące się przy miniaturkach będą zawierały tekst lub ikony. Odnośniki te pozwalają na edytowanie szczegółów lub usuwanie multimediów.<br />";
$pgv_lang["lb_ml_thumb_linksLegend"]	= "Strona Multimediów - wygląd odnośników";
$pgv_lang["lb_ml_thumb_links_help"]		= "~#pgv_lang[lb_ml_thumb_linksLegend]#~<br />Ta opcja pozwala na określenie czy odnośniki znajdujące się przy miniaturkach będą zawierały tekst, ikony lub obie rzeczy jednocześnie. Odnośniki te pozwalają na edytownie różnych szczegółów multimediów.<br /><br />Opcja <b>#pgv_lang[lb_none]#</b> ukrywa te odnośniki i sprawia, że użytkownik nie będzie posiadał żadnych praw do edycji.<br />";
$pgv_lang["lb_ss_speedLegend"]			= "Prędkość pokazu slajdów";
$pgv_lang["lb_ss_speed_help"]			= "~#pgv_lang[lb_ss_speedLegend]#~<br />Ta opcja określa czas przez jaki ma być wyświetlany jeden element w pokazie slajdów.<br />";
$pgv_lang["lb_music_fileLegend"]		= "Muzyka podczas pokazu slajdów";
$pgv_lang["lb_music_file_help"]			= "~#pgv_lang[lb_music_fileLegend]#~<br />Ta opcja pozwala na określenie muzyki, jaka ma być odtwarzana podczas pokazu slajdów. Jeśli zostawisz to pole puste muzyka nie będzie grała.<br /><br />Ta opcja pozwala na ustawienie jedynie plików muzycznych w formcie mp3. Inne nie będą odtwarzane.<br />";
$pgv_lang["lb_transitionLegend"]		= "Opóźnienie zmiany obrazów";
$pgv_lang["lb_transition_help"]			= "~#pgv_lang[lb_transitionLegend]#~<br />Ta opcja pozwala na określenie opóźnienia zmiany obrazów i ma zastosowanie podczas pokazu slajdów, jak również podczas zmiany obrazu na następny przy zatrzymanym pokazie slajdów.<br /><br />Opcja <b>#pgv_lang[lb_none]#</b> wyłącza opóźnienie i następny obraz natychmiast zastępuje poprzedni bez widocznych przejść.<br />";
$pgv_lang["lb_url_dimensionsLegend"]	= "Szerokość i wysokość okna w pikselach"; 
$pgv_lang["lb_url_dimensions_help"]		= "~#pgv_lang[lb_url_dimensionsLegend]#~<br />Określa rozmiary okna, w którym zostanie wyświetlony obraz, gdy klikniesz na jego miniaturkę.<br /><br />Powinna być mniejsza niż rozmiar okna przeglądarki i rozdzielczość ekranu, na którym będą wyświetlane obrazy.<br />";

?>