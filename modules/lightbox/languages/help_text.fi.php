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
 * @version $Id$ 
 * @author Brian Holland
 * @translator Matti Valve 
 */

//-- security check, only allow access from module.php
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}

$pgv_lang["mediatabLegend"]				= "Media-välilehden näkyminen";
$pgv_lang["mediatab_help"]				= "~#pgv_lang[mediatab]#~<br />Tämä valinta määrittelee, näkyykö Media-välilehti sivulla  #pgv_lang[indi_info]#.<br /><br />Mikäli valinta on asetettu tilaan <b>#pgv_lang[hide]#</b>, vain <b>#pgv_lang[lightbox]#</b> välilehti näkyy ja se nimetään muotoon <b>#pgv_lang[media]#</b>.<br />";
$pgv_lang["lb_al_head_linksLegend"]		= "Kuvastovälilehden otsikkolinkin näkyminen";
$pgv_lang["lb_al_head_links_help"]		= "~#pgv_lang[lb_al_head_linksLegend]#~<br />Tällä valinnalla määritellään näkyykö pgv_lang[lightbox]#-välilehden otsikkoalueella olevat Valopöytää ohjaavat linkit kuvakkeina, pelkkänä tekstinä tai kumpanakin.<br /><br />Valinta <b>#pgv_lang[lb_icon]#</b> ei luultavasti ole kovin hyödyllinen, koska silloin ei näe kunkin kuvakkeen toimintaa ennenkuin kursoria liikutetaan kuvakkeen päälle.<br />";
$pgv_lang["lb_al_thumb_linksLegend"]	= "Kuvastovälilehden pienoiskuvalinkkien näkyminen";
$pgv_lang["lb_al_thumb_links_help"]		= "~#pgv_lang[lb_al_thumb_linksLegend]#~<br />Tällä valinnalla määritellään näkyykö kunkin pienoiskuvan alla olevassa linkkikentässä kuvake vai teksti. Tässä olevat linkit antavat sinun editoida mediakohteen tiedot tai poistaa sen.<br />";
$pgv_lang["lb_ml_thumb_linksLegend"]	= "Pienoiskuvalinkkien näkyminen";
$pgv_lang["lb_ml_thumb_links_help"]		= "~#pgv_lang[lb_ml_thumb_linksLegend]#~<br />Tällä valinnalla määritellään, näkyvätkö linkit mediakohteiden tietojen yläpuolella olevan linkkialueen multimedialuettelossa vain kuvakkeina, teksteinä vai kumpanakin. Tässä näkyvät linkit sallivat sinun editoida ko. multimediakohdetta. <br /><br />Vaihtoehto <b>#pgv_lang[lb_none]#</b> piilottaa kokonaan nämä linkit jolloin näyttäisi siltä, ettei käyttäjällä ole mitään editointioikeuksia.<br />";
$pgv_lang["lb_ss_speedLegend"]			= "Diaesityksen nopeus";
$pgv_lang["lb_ss_speed_help"]			= "~#pgv_lang[lb_ss_speedLegend]#~<br />Tällä valinnalla määritellään kunkin kuvan esilläoloaika diaesityksessä.<br />";
$pgv_lang["lb_music_fileLegend"]		= "Diaesityksen ääniraita";
$pgv_lang["lb_music_file_help"]			= "~#pgv_lang[lb_music_fileLegend]#~<br />Tällä valinnalla voidaan määritellä, mikä ääniraita soi kun diaesitys on käynnissä. Mikäli tämä kenttä jätetään tyhjäksi, mitään ei soiteta diaesityksen aikana.<br /><br />Vain mp3-typpiset äänitiedostot toimivat.<br />";
$pgv_lang["lb_transitionLegend"]		= "Kuvan siirtymänopeus";
$pgv_lang["lb_transition_help"]			= "~#pgv_lang[lb_transitionLegend]#~<br />Tällä valinnalla määritetään kuvien välinen siirtymänopeus. Sitä käytetään diaesityksessä. Sitä käytetään myös kun siirrytään seuraavaan tai edelliseen kuvaan kun diaesitys ei ole käynnissä.<br /><br />Vaihtoehdolla <b>#pgv_lang[lb_none]#</b> siirtymää ei ole, vaan kuvat korvaavat toisensa suoraan.<br />";
$pgv_lang["lb_url_dimensionsLegend"]	= "Valopöydän URL ikkunan mitat";
$pgv_lang["lb_url_dimensions_help"]		= "~#pgv_lang[lb_url_dimensionsLegend]#~<br />Näpäytettäessä URL pienoiskuvaa, voidaan määritellä valopöydän URL-ikkunan mitat pikseleinä..<br /><br />Tämän tulisi yleensä olla pienempi kuin käytettävänselainikkunan koko ja varmasti pienempi kuin näytön koko.<br />";

?>