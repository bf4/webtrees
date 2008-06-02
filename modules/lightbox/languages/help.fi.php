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
 * @translator Matti Valve 
 */


echo "<font size=2 face=\"Verdana\"> ";
echo "<h3>Valolaatikkoalbumin OHJE: </h3>";
echo "<ol> ";

echo "<li>";
echo "<b><font color=\"blue\">Katsoaksesi kuvaa</font></b><br />";
echo "Näpäytä mitä tahansa pienoiskuvaa. Kuvan nimi ilmestyy esiin tulevan kuvan alle. ";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Zoomaustilan käyttö</font></b><br />" ;
echo "HUOM: Diaesitys on pysäytettävä, jotta zoomauskuvakkeet näkyisivät.<br />";
echo "<b> Salli zoomaus: </b><br />";
echo "Kun vihreä plusmerkki kuvan oikeassa alareunassa näkyy, zoomaus on jo käytössä. Käytä vieritysrullaa koon muuttamiseen (tai käytä näppäimiä  <b>i</b> ja <b>o</b>) Kuvake muuttuu punaiseksi miinukseksi.<br /> ";
echo "Kun kuva suurennetaan näyttöä suuremmaksi, käytä nuolinäppäimiä kuvan siirtämiseksi näytöllä.<br />";
echo "<b> Estä zoomaus: </b><br />";
echo "Näpäytä punaista miinusmerkkiä näytön  alalaidassa poistuaksesi zoomaustilasta (tai käytä näppäintä  <b>z</b>)";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Kuvan sulkeminen </font></b><br />";
echo "Näpäytä punaista X-kuvaketta alhaalla oikealla.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Seuraavan tai edellisen kuvan näyttäminen</font></b><br />";
echo "Kun kuljetat kursoria kuvan päällä silloin, kun se EI ole zoomaustilassa, kuvan oikealle puolelle ilmestyy <b>&gt;</b>-merkki ja vasemmalle  <b>&lt;</b>-merkki. Näpäytä mihin tahansa kuvan oikealla puoliskolla siirtyäksesi seuraavaan kuvaan ja vasemmalla puoliskolla edelliseen kuvaan.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Mihin tahansa kuvaan siirtyminen kuvastossa</font></b><br />";
echo "Kun liikutat kursoria noin yhden senttimetrin päässä kuvan yläpuolella, kun ET ole zoomaustilassa, näkyviin ilmestyy pienoiskuvakokoelma. Tarvittaessa siirrä kursoria vasemmalle tai oikealle saadaksesi lisää pienoiskuvia näkyviin tästä kokoelmasta. Näpäytä pienoiskuvaketta avataksesi siihen liittyvän kuvan. <b>Seuraava</b>, <b>Edellinen</b> ja <b>Hyppy</b> on mahdollista riippumatta siitä, onko diaesitys menossa tai taukotilassa.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Diaesityksen käynnistäminen</font></b><br />";
echo "Näpäytä Käynnistä-kuvaketta alhaalla vasemmalla. Mikäli mukana on äänitiedosto, kaiutinkuvake ilmestyy näkyviin. Näpäytä kaiutinkuvaketta käynnistääksesi tai sulkeaksesi äänirata. Näpäytä taukopainiketta diaesityksen pysäyttämiseksi.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b><font color=\"blue\">Navigointi ...</font></b><br />";
echo "Käytä Näytä kuvasto taulukkoa kuva-kuvaketaulukon oikealla puolella valitaksesi suoraan toisen henkilön kuvastonäkymän.";
echo "<br /><br /></li>";

echo "</ol>";
echo "<ul>";

echo "<li>";
echo "<b>Huomautus:</b><br />";
echo "Sellaiset pienoiskuvat, jotka eivät viittaa kuviin, kuten PDF-tiedostot, äänet, kirjat ja videot, voidaan katsoa erikseen. Ne eivät näy diaesityksessä.";
echo "<br /><br /></li>";

echo "<li>";
echo "<b>Huomautus ylläpitäjälle:</b><br />";
echo "Mikäli joku tavanomaista kuvaformaattia (jpg, gif, bmp jne) oleva tiedosto, joka edustaa valokuvaa, todistusta, dokumenttia jne ilmestyy riville <b>Muut</b>, olet unohtanut määritellä näiden kohteiden mediatyypin. Kannattaisi ehkä editoida näiden kohteiden mediatyyppi.";
echo "<br /><br /></li>";

echo "</ul>";
?>