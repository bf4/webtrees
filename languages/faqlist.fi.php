<?php
/**
 * Finnish FAQ file for PhpGedView.
 *
 * PhpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team
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
 * @translator Matti Valve
 * @created 2007-11-09
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

//-- Define Finnish Frequently Asked Questions
$faqlist["FAQ_000_head"] = "\"FAQ\": OLEN KUULLUT TÄSTÄ, MUTTA MISTÄ OIKEIN ON KYSYMYS?";
$faqlist["FAQ_000_body"] = "<b>FAQ</b> on lyhenne <b>F</b>requently <b>A</b>sked <b>Q</b>uestion, suomeksi käytetään joskus lyhennettä <b>UKK</b> (<b>U</b>sein <b>K</b>ysytyt <b>K</b>ysymykset).<br /><br />FAQ-luettelo on luettelo usein kysytyistä kysymyksistä (yhdessä vastausten kanssa). Sen on laatinut PhpGedView-tiimi ja sitä päivitetään usein.";

$faqlist["FAQ_010_head"] = "TERVETULOA #GLOBALS[GEDCOM_TITLE]# Usein esitettyihin kysymyksiin (FAQ)";
$faqlist["FAQ_010_body"] = "#GLOBALS[GEDCOM_TITLE]#en perheenjäsenet toivottavat kaikki sukututkimusta harrastavat \'serkkumme\' tervetulleiksi kannustaen tutkimaan esivanhempiaan. Tästä voi muodostua viha-rakkaussuhde koska se vaatii tolkuttomasti aikaa, mutta palkitsee myös valtavasti. Tarjoamme mahdollisuuden nauttia sukututkimuksesta käyttämällä ohjelmaa <a href=\"#PGV_PHPGEDVIEW_URL#\" target=\"_blank\">#PGV_PHPGEDVIEW#</a>, jonka ovat luoneet taitavat ohjelmoijat John Finlay ja hänen PGV-ryhmänsä - erinomainen avoimen lähdekoodin sukututkimusohjelma.";

$faqlist["FAQ_015_head"] = "MIKÄ ON ERO TÄMÄN PhpGedView PUUN JA MUIDEN TEKSTIMUOTOISTEN JA DYNAAMISTEN PUIDEN VÄLILLÄ?";
$faqlist["FAQ_015_body"] = "Tekstimuotoiset ja dynaamiset puut näyttävät puun eri tavoin, mutta mitään niistä et itse voi konfiguroida, muuttaa tai päivittää. Vain verkkovastaavalla on päivitysmahdollisuus. <br /><br />PhpGedView on vuorovaikutteinen puu. Kuka tahansa, jonka perhe on tässä laajennetussa puussa voi päivittää, lisätä ja muuttaa läheisiä oksiaan. (Sinun on ensin rekistöidyttävä tehdäksesi muutoksia.)";

$faqlist["FAQ_017_head"] = "MITKÄ OVAT TÄMÄN PUUN ERITYISET OMINAISUUDET?";
$faqlist["FAQ_017_body"] = "PhpGedView-ohjelmalla voit:<ul><li>Säilyttää elävien henkilöiden yksityisyyden; verkkovastaava määrittelee kenen tietoja voit nähdä.</li><li>Tarkastella puuta monella eri tavalla: erilaisilla kaavioilla, raporteilla tai luetteloilla.</li><li>Puu on yhteiskäyttöinen. Verkkovastaavan luvalla jokainen voi osallistua puun päivittämiseen.</li></ul>";

$faqlist["FAQ_020_head"] = "TARVITSENKO KÄYTTÄJÄTILIN? KUINKA SAADA SELLAINEN?";
$faqlist["FAQ_020_body"] = "Sanottaisiinko \"Tervetuloa, serkku\"?<br /><br /><b>NOTE: #GLOBALS[GEDCOM_TITLE]# EI VAADI REKSITERÖITYMISTÄ nähdäksesi kuolleiden henkilöiden tietoja. Lisätäksesi tietoja tai tutkiaksesi eläviä sukulaisia sinun on rekisteröidyttävä ja kerrottava meille sukulaisuussuhteestasi.</b>";
$faqlist["FAQ_020_body2"] = "Rekisteröityneet käyttäjät näkevät kaikki sivuston elävät henkilöt. He näkevät kuolleiden henkilöiden sekä lähisukulaistensa yksityiskohtaiset tiedot.<br /><br />Käyttäjät joilla ei ole yhteyttä sukulaisiin näkevät vain elävien henkilöiden nimet ja kuolleiden henkilöiden yksityiskohtaiset tiedot.";
$faqlist["FAQ_020_body3"] = "<ol><li>Rekisteröityjen henkilöiden tulee olla sukua, kaukaistakin useissa tapauksissa, tai jotenkin yhteydessä johonkin sivustoillamme jo luetteloituun henkilöön. </li><li>Rekisteröityjen henkilöiden tulee olla valmiita säännöllisesti vierailemaan sivustoillamme ja päivittämään tietojaan. Aluksi toimittamalla tietoja itsestään ja perheestään ja myöhemmin muokkaamalla, täydentämällä ja laajentamalla olemassa olevaa aineistoa. </li><li>Rekisteröityjen henkilöiden tulee sitoutua suojelemaan kaikkien sivustolla esiintyvien elävien henkilöiden yksityisyyttä ja toimittamaan, kuten yllä on sanottu, omaa henkilökohtaista tietoa sivustolle. Tätä sääntöä rikkovilta kielletään heti pääsyoikeudet ja ryhdytään mahdollisesti lain suomiin toimenpiteisiin. Suhtaudumme henkilötietovarkauksiin ja tiedon väärinkäytöksiin vakavasti. Tutustu yksityisyyttä koskevaan osioon lisätietojen saamiseksi. </li></ol>Mikäli olet oikeutettu rekisteröitymään ja suostut noudattamaan näitä yksikertaisia periaatteita ja menettelytapoja, täytä sivustomme <b><a target=\"_blank\" href=\"/phpGedView/login_register.php?action=register\">rekisteröitymislomake</a></b>. Muista vastata pieneen kyselyyn, missä selostat sukulaisuutesi sivustolla oleviin henkilöihin ja jossa myös selkeästi osoitat noudattavasi periaatteitamme ja sääntöjämme. Me tulemme arvioimaan ja harkitsemaan hakemustasi.";
$faqlist["FAQ_020_body4"] = "Verkkovastaava hyväksyy uuden käyttäjätilin. Tähän menee yleensä muutamasta minuutista 24 tuntiin.";

$faqlist["FAQ_022_head"] = "MIKSI MINUN ON REKISTERÖIDYTTÄVÄ?";
$faqlist["FAQ_022_body"] = "Vain reksiteröityneet käyttäjät näkevät elossa olevien henkilöiden nimet. Mikäli et ole rekisteröitynyt, näet vain \"Yksityinen\" elossa olevan henkilön nimen sijaan.";

$faqlist["FAQ_025_head"] = "KAUANKO REKISTERÖITYMINEN KESTÄÄ?";
$faqlist["FAQ_025_body"] = "Verkkovastaava hyväksyy uuden käyttäjätilin manuaalisesti. Yleensä siihen menee aikaa muutamasta minuutista 24 tuntiin.";

$faqlist["FAQ_027_head"] = "OLEN REKISTERÖITYNYT JA MINUT ON HYVÄKSYTTY. NÄEN ELOSSA OLEVIEN HENKILÖIDEN TIEDOT MUTTA EN YKSITYISKOHTIA.";
$faqlist["FAQ_027_body"] = "Jotta näkisit yksityiskohtaisia tietoja (vain lähimmistä oksista), sinun tulee olla osa puuta ja sinun tulee informoida siitä verkkovastaavalle/sukuvastaavalle sähköpostitse.";

$faqlist["FAQ_030_head"] = "KUINKA LATAAN TIETOJA? MITÄ FORMAATTIA TULEE KÄYTTÄÄ?";
$faqlist["FAQ_030_body"] = "Tässä muutamia asioita";
$faqlist["FAQ_030_body2"] = " käyttäjille, joilla on lupa EDITOIDA on-line-tilassa.";
$faqlist["FAQ_030_body3"] = "Voit myös lähettää päivitystietosi sähköpostitse.";
$faqlist["FAQ_030_HELP"] = "<strong>OHJE</strong>: Ohjeita annetaan runsaasti sivustolla, jokaisen sivun otsikkokentässä ja muualla useimpien linkkien ja termien vieressä \"?\"-kuvakkeella. Mikäli pallo on vielä hukassa, kysy meiltä sähköpostilla.";
$faqlist["FAQ_030_DATES"] = "<strong>PÄIVÄMÄÄRÄT</strong>: käytämme GEDCOM v5.5 vakiomuotoa. DD MMM YYYY tai 01 JAN 1822 muotojen 1 tammikuuta, 1822 tai tammikuun 1., 1822 asemasta. Järjestelmä voi tehdä pieniä korjauksia syöttövirheisiin, mutta tähän ei kannata luottaa.";
$faqlist["FAQ_030_HDATES"] = "<strong>HEPREALAISET PÄIVÄMÄÄRÄT</strong> syötetän muodossa @#DHEBREW@ DD MMM YYYY tai @#DHEBREW@ 21 AAV 5705 - Kuukaudet syötetään muodossa TSH, CSH, KSL, TVT, SHV, ADR, ADS, NSN, IYR, SVN, TMZ, AAV ja ELL kuten GEDCOM v5.5 standardissa on määritelty.";
$faqlist["FAQ_030_PLACES"] = "<strong>PAIKAT</strong>: Pyrimme aina kun se on tiedossa, täydelliseen paikan määrittelyyn: kaupunki ja/tai kaupunkikunta, maa, osavaltio, ja lisäämme vielä USA (ei US, U.S. tai U.S.A) osavaltion jälkeen. Muille maille käytetään GEDCOMin hyväksymää kolmen kirjaimen maalyhennettä koko maan nimen sijasta: Englanti (ENG), Skotlanti (SCT), Irlanti (IRL), Suomi (FIN), jne. Suositeltavin muoto on (kun kyseessä on USA): <i>Indianapolis, Center Twp, Marion Co, Indiana, USA</i>. Osavaltiot lyhennetään kahteen kirjaimeen eikä pistettä (.)käytetä nimien yhteydessä: <i>Shelbyville, Addison Twp, Shelby Co, Indiana, USA</i> eikä <i>Shelbyville, Addison Twp., Shelby Co., IN</i> tai <i>Shelbyville, Addison Township, Shelby County, Indiana, U.S.A.</i>";
$faqlist["FAQ_030_PLACES2"] = "<strong>PAIKAT</strong>: Pyrimme aina kun se on tiedossa Yhdysvaltojen osalta sisällyttämään täydellinen paikan määrittely: kaupunki tai kaupunkikunta (Twp), kaksikirjaiminen osavaltiotunnus ja sen perään USA (ei US, U.S. tai U.S.A). Muiden maiden kohdalla käytetään kaupunki ja/tai kaupunkikunta ja maan nimi. Käytettävät muodot ovat: <i>Indianapolis, IN, USA</i> ja <i>Vilnius, Lithuania</i>.";
$faqlist["FAQ_030_PLACES3"] = "Paikan oikea muoto löytyy kahdella tavalla:<br /><ul><li>käytä pientä \"maailma\"-kuvaketta paikkakentän vieressä nähdäksesi tietokantaamme jo tallennetut paikat. Tällä tavoin löydät helposti sen maan jossa kaupunki on, koska se luultavasti on jo olemassa tiedoissamme. Käytä suodatinta tarkentamaan hakua ja näpäytä haluttua tulosta, joka sitten kopioituu paikkakenttään.</li><li>Näpäytä <b>+</b>-merkkiä paikkakentän alapuolella. Näkyviin tulee lisää kenttiä maalle, osavaltiolle/läänille/maakunnalle, maalle ja  kaupungille. Maakentän vieressä on pudotusvalikko, jossa on maakohtaiset kolmekirjaimiset lyhenteet.</li></ul>";
$faqlist["FAQ_030_PLACES4"] = "Paikan oikea muoto löytyy kahdella tavalla:<br /><ul><li>käytä pientä \"maailma\"-kuvaketta paikkakentän vieressä nähdäksesi tietokantaamme jo tallennetut paikat. Tällä tavoin löydät helposti sen osavaltion jossa kaupunki on, koska se luultavasti on jo olemassa tiedoissamme. Käytä suodatinta tarkentamaan hakua ja näpäytä haluttua tulosta, joka sitten kopioituu tyhjään paikkakenttään.</li><li>Näpäytä <b>+</b>-merkkiä paikkakentän alapuolella. Näkyviin tulee lisää kenttiä maalle, osavaltiolle/läänille/maakunnalle, maalle(N/A)  ja kaupungille. Maakentän vieressä on pudotusvalikko, jossa on maakohtaiset kolmekirjaimiset lyhenteet ja joita ei tässä tapauksessa käytetä.</li></ul>";
$faqlist["FAQ_030_PLACES5"] = "Kenttää \"heprealainen\" \"paikka\"-kentän alapuolella on tarkoitus käyttää, kun halutaan kirjoittaa paikan nimi heprealaisilla kirjaimilla latinalaisten kirjainten lisäksi.";
$faqlist["FAQ_030_NAMES"] = "<strong>NIMET</strong>: Nimien lisääminen on yksinkertaista lomakkeen ja ohjeen avulla. INDI syöttölaatikossa tulisi olla jo valmiiksi avatut nimikentät. Mikäli näin ei ole, sekä se että paikkalaatikko saadaan avattua näpäyttämällä vastaavaa <b>+</b>-merkkiä.";
$faqlist["FAQ_030_PREFIX"] = "Nimien <u>etuliitteet (PREFIX)</u> ovat yleensä arvo- tai kunnianimikkeitä kuten tri, Rabbi, varatuomari jne. Tavallisia etuliitteitä kuten herra, rouva tai neiti ei käytetä.";
$faqlist["FAQ_030_GIVN"] = "<u>GIVN</u> - Etunimet ovat yleensä ensimmäinen ja toinen nimi joka annetaan lapselle.";
$faqlist["FAQ_030_GIVN1"] = " Mikäli nimiä muutetaan syntymän jälkeen ne voidaan lisätä erikseen kunhan henkilö on ensin tallennettu tietokantaan. Ne voidaan myös lisätä lisäniminä (AKA).<br /><br />Oletamme, että henkilöitä kutsutaan ensimmäisellä etunimellään. Mikäli näin ei ole, osoita kutsumanimi laittamalla tähti (*) kutsumanimen jälkeen. Esimerkiksi <i>Matti Johannes* Meikäläisen </i> kutsumanimi on \"Johannes\". Tässä tapauksessa \"Johannes\" ei ole lempinimi, mutta \"Jussi\" voisi olla.";
$faqlist["FAQ_030_GIVN2"] = " Etunimen ensimmäinen kirjain kirjoitetaan isolla, muut pienellä kirjasimella.";
$faqlist["FAQ_030_SURNAME"] = "<u>SUKUNIMI</u> on perheen viimeinen nimi. Se on vihityn henkilön <u>syntymänimi</u> eikä avioliiton solmimisen jälkeen otettu nimi. Katso <i>avionimi</i> alempana. Mikäli nimiä muutetaan syntymän jälkeen ne voidaan lisätä erikseen kunhan henkilö on ensin tallennettu tietokantaan. Ne voidaan myös lisätä lisäniminä (AKA, tunnetaan myös nimellä).";
$faqlist["FAQ_030_SURNAME2"] = "Sukunimen ensimmäinen kirjain kirjoitetaan isolla, muut pienellä kirjasimella.";
$faqlist["FAQ_030_SUFFIX"] = "Nimien jälkiliitteet <u>SUFFIX</u> ovat jr, Sr, III jne.";
$faqlist["FAQ_030_NICKNAME"] = "Lempinimi<u>NICKNAME</u> on usein eri kuin henkilön etunimi; esimerkiksi <i>Masa</i> on Matti \"Masa\" Meikäläisen lempinimi.";
$faqlist["FAQ_030_HEBNAME"] = "Heprealainen nimi <u>HEBREW NAME</u> on heprealainen käännös henkilön latinalaisin kirjaimin kirjoitetusta nimestä. Kenelläkään ei voi olla enempää kuin yksi heprealainen nimi. PhpGedView olettaa, että asetat sukunimen kauttaviivojen väliin. Nimi יעקב לוי kirjoitetaan muodossa יעקב /לוי/.";
$faqlist["FAQ_030_AKANAME"] = "Lisänimi <u>AKA NAME</u> on nimi, jolla henkilö myös tunnetaan. Se voi olla henkilön syntymänimi, joka on muutettu myöhemmin elämässä tai se voi olla alias tai taiteilijanimi. Se voi myös olla avionimi. Tähän kirjoitettu etunimi voi olla eri kuin päänimen etunimi. PhpGedView olettaa, että asetat sukunimen kauttaviivojen väliin. <i>Matti Meikäläinen</i> kirjoitetaan muodossa <i>Matti /Meikäläinen/</i>.";
$faqlist["FAQ_030_AKANAME2"] = " Erilliset heprealaiset nimet tai jiddishkieliset nimet syötetään myös AKA-niminä.";
$faqlist["FAQ_030_MARRNAME"] = "<u>AVIONIMI</u> on henkilön uusi nimi, joka otetaan/tai annetaan avioliittoon vihkimisen yhteydessä (yleensä aviopuolison nimi). Ohjelma luo sen automaattisesti kun se syötetään avionimikenttään. Esimerkiksi <i>Matti Meikäläinen vihitään <Maija Ihanaisen</i> kanssa ja Maijasta tulee <i>Maija Meikäläinen</i> kun <i>Meikäläinen</i> syötetään Avionimi-kenttään. Avionimet eivät ole sukupuolisidonnaisia; sen voi liittää sekä miehen että naisen nimeen. ";
$faqlist["FAQ_030_MARRNAME2"] = "Heprealainen avionimi otetaan Heprealaisen nimen sukunimestä.";
$faqlist["FAQ_030_SOURC"] = "<strong>LÄHTEET</strong> and <strong>LAINAUKSET</strong>: Sukututkimuksessa ei riitä, että \"jotakin tapahtui silloin ja silloin\". Historioitsija vaatii todisteita. Niin mekin! Lisää kaikki tietolähteestäsi käytettävissä oleva tieto. Tarkista kaikki käytettävissä oleva lähdeaineisto ja käytä LISÄTIETOA-optiota mikäli olet erpävarma tai tarvitset lisätilaa kirjoittamiseen. Kirjoita enemmän kuin katsot tarpeelliseksi, se ei kuitenkaan ole liikaa. Kysymyksiä? Sen kun kysyt, jos et ymmärrä jotakin. Autamme mielellämme.";
$faqlist["FAQ_030_CHNG"] = "<strong>MUUTOKSET</strong> ja <strong>SYÖTTEET</strong>: Henkilön tai perheen tietoihin tehdyt muutokset eivät päivity ennekuin verkkovastaava on ne hyväksynyt. Vaikka me tarkastamme sivuston usein, lähetä meille sähköpostia kun haluat meidän nopeammin tarkastavan ja hyväksyvän lisäykset ja muutokset. Tiedot, jotka liittyvät tietyn perheyksikön luontiin tai muutokseen syötetään  Lähisukulaiset/Perhelinkkisivulla. Tänne merkitset avioliitot ja -erot, lapset, perhetapahtumat - siis kaikki tapahtumat jotka vaikuttavat perheyksikköön. Paras tapa lisätä useita lapsia on ottaa esille asianomaisen aviomiehen/aviovaimon Katso perhettä-linkki ja lisätä kukin lapsi sivun lopussa olevan \"Lisää lapsi tähän perheeseen\"-linkistä. Se on nopeampi tapa kuin käyttää Lähisukulaiset-sivua, koska jokainen lisäys palauttaa näkymän oletussivulle Katso INDI. Kysymyksiä? Sen kun kysyt. Korjauksia, neuvoja ja ohjeita on vapaasti saatavilla.";
$faqlist["FAQ_030_MEDIA"] = "<strong>MEDIA</strong>: Olemme hyvin mielissämme, jos lataat sivustolle kuvia, sytymä-,  vihki- ja kuolintodistuksia. Ne on helppo ladata omalta kovalevyltäsi käyttämällä MEDIA-välilehteä, LISÄÄ MEDIA-linkkiä ja LATAA PALVELIMELLE/Selaa-ominaisuutta. Mikäli sinulla on kysymyksiä, ehdotuksia tai vain haluat apua, lähetä digikuvasi meille sähköpostitse ja voimme itse lisätä ne.";
$faqlist["FAQ_030_MEDIA2"] = "Kun lisäät uutta mediaa sovella sellaista nimeämiskäytäntöä, joka ei ole ristiriidassa olemassa olevan median kansa. Järjestelmässä voit selata kovalevyäsi ja ladata dokumentti palvelimelle eri nimisenä ilman, että dokumenttisi nimi muuttuu kovalevyllä. Ajattele vaikkapa kuinka monta \"kuva1.jpg\"-nimistä tiedostoa voisi löytyä, jolloin voit erehdyksessä korvata jo olemassa olevan \"kuva1.jpg\" omalla samannimisellä kuvalla. Pidä dokumentin nimi lyhyenä (korkeintaan 35 merkkiä), mutta kuvaavana - esimerkiksi J-nimi_-b1820-I23445.jpg tai K_nimi_-I23444-Espoo.jpg. Kysy tarvittaessa meiltä osviittaa.";
$faqlist["FAQ_030_NAVIGATE"] = "<strong>NAVIGOINTI</strong>: Navigoimme käyttäen YMPYRÄDIAGRAMMIA ja esipolvi- sekä jälkipolvikaavioita. Kokeile niitä. Muista, että monet toiminnot eivät ole käytössä ennenkuin olet luonut oman henkilökohtaisen INDI tietosivun, joka on linkitetty esivanhemmistasi.";
$faqlist["FAQ_032_head"] = "VOINKO LISÄTÄ/EDITOIDA/PÄIVITTÄÄ KENEN TAHANSA PUUSSA OLEVAN HENKILÖN TIETOJA?";
$faqlist["FAQ_032_body"] = "Kyllä voit. <br />Sinun täytyy olla osa puuta ja sinulla tulee olla on-line editointilupa. Voit muuttaa ja tehdä lisäyksiä vain omaan ja läheisimpiin oksiin. Voit myös toimittaa päivitystiedot sähköpostitse.";
$faqlist["FAQ_037_head"] = "MINULLA OLI ONGELMIA PUUN EDITOINNIN KANSSA. MITÄ TEEN?";
$faqlist["FAQ_037_body"] = "Voit lähettää päivitykset/muutokset/lisäykset sähköpostilla verkkovastaavalle <br />lähetä osoitteella #GLOBALS[Webmaster_EMAIL]#";
$faqlist["FAQ_037_body2"] = "Voit lähettää päivitykset/muutokset/lisäykset sähköpostilla sukuvastaavalle <br />lähetä osoitteella #GLOBALS[CONTACT_EMAIL]#";
$faqlist["FAQ_040_head"] = "MITEN ON YKSITYISYYDEN LAITA?";
$faqlist["FAQ_040_body"] = "<b>#GLOBALS[GEDCOM_TITLE]# pitää yksityisyyden suojaa erittäin tärkeänä.</b> PhpGedView-ohjelman yksityisyystoiminnot ovat erinomaiset toteuttamaan yksityisyyssääntöjä - sivustomme piilottaa elossa olevien henkilöiden tiedot sekä niiden tiedot, jotka eivät ole sukua sinulle. Elossa olevien henkilöiden tietojen tarkastelu edellyttää, että olet kirjautunu järjestelmään käyttäjätunnuksella ja salasanalla. Tämä on linkitetty paikkaasi sukupuussa. Sivustomme käyttää myös \"sukulaisuusyksityisyyttä\". Silloin näet vain lähisukulaistesi tietoja. Mikäli kirjautuneena näet jonkun nimen tai perheen kohdalla \"Yksityinen\", tämä ominaisuus on aktivoitu. Mikäli omasta mielstäsi rajoitukset ovat liian tiukat, läheta verkkovastaavalle sähköpostia ja selitä yksityiskohtaisesti ID-numeroin mihin pääsysi oli estynyt ja miksi sinun tulisi saada nähdä kyseinen tieto.";
$faqlist["FAQ_040_body2"] = "Mikään järjestelmä ei tietenkään ole täydellinen joten on mahdollista, että asiattomat pääsevät käsiksi aineistoon. Teemme kuitenkin kaikkemme ratkaistaksemme heti yksityisyyttä koskevat ongelmat. Mikäli olet erityisen huolestunut jostakin tänne tallennetusta tiedosta, ota yhteyttä verkkovastaavaan tai sukuvastaavaan alla olevien sähköpostilinkkien avulla. Tietosi voidaan poistaa järjestelmästä, mutta myös pääsyäsi voidaan rajoittaa. Katso myös kysymystä siitä, mitä tietoa on sivustolla.<br /><br /><b> Suhtaudumme tietojen väärinkäyttöön ja tietovarkauteen vakavasti ja nostamme syytteet kaikkia niitä vastaan jotka ovat osallisena tai edes yritykseen henkilötietovarkauteen silloin kun se liittyy palvelussamme olevaan tietoon. ÄLÄ KOPIOI elossa olevan sukulaisesi tietoja mihinkään muuhun palveluun tai sivustoon koska heillä ei ehkä ole mahdollisuutta suojata yksityisyyttä ja sinua voidaan pitää vastuussa.";
$faqlist["FAQ_050_head"] = "KIITOS";
$faqlist["FAQ_050_body"] = "Tämän tietomäärän saanti ja ylläpito ei olisi mahdollista ilman niin monen sukulaisen osallistumista. Sukututkimus on hauska ja opettavainen kokemus syventäen sekä tietämystämme sukulaisistamme että yleistä tietämystämme maantieteestä ja sosiologiasta. Toivottavasti nautit siitä yhtä paljon kuin mekin. Odotamme innolla keskinäistä yhteistyötä ja ystävyyttä PhpGedView-ohjelman ja #GLOBALS[GEDCOM_TITLE]#-sivuston puitteissa.<br /><br />Toivomme, että lähetät meille sähköpostia ihan vain tervehdykseksi tai jos tiedot vaativat korjausta tai lisäystä tai kysyäksesi sukulaisuussuhteista. Kaiken minkä tiedämme näytämme, vain elossa olevien henkilöiden tiedot jäävät piiloon.<br /><br />Kiitos vielä kerran";
$faqlist["FAQ_030_PLACES2"] = "<strong>PAIKAT</strong>: Pyrimme aina kun se on tiedossa Amerikan osalta sisällyttämään täydellinen paikan määrittely: kaupunki tai kaupunginosa (Twp, kaksikirjaiminen osavaltiotunnus ja sen perään USA (ei US, U.S. tai U.S.A). Muiden maiden kohdalla käytetään kaupunki ja/tai kaupunginosa ja maan nimi. Käytettävät muodot ovat: <i>Indianapolis, IN, USA</i> ja <i>Vilnius, Lithuania</i>.";
$faqlist["FAQ_030_PLACES"] = "<strong>PAIKAT</strong>: Pyrimme aina kun se on tiedossa, täydelliseen paikan määrittelyyn: kaupunki ja/tai township(Twp), maa, osavaltio, ja lisäämme vielä USA (ei US, U.S. tai U.S.A) osavaltion jälkeen. Muille maille käytetään GEDCOMin hyväksymää kolmen kirjaimen maalyhennettä koko maan nimen sijasta: Englanti (ENG), Skotlanti (SCT), Irlanti (IRL), Suomi (FIN), jne. Suositeltavin muoto on (kn kyseessä on USA): <i>Indianapolis, Center Twp, Marion Co, Indiana, USA</i>. Osavaltiot lyhennetään kahteen kirjaimeen eikä pistettä (.)käytetä nimien yhteydessä: <i>Shelbyville, Addison Twp, Shelby Co, Indiana, USA</i> eikä <i>Shelbyville, Addison Twp., Shelby Co., IN</i> tai <i>Shelbyville, Addison Township, Shelby County, Indiana, U.S.A.</i>";
$faqlist["FAQ_030_head"] = "KUINKA LATAAN TIETOJA? MITÄ FORMAATTIA TULEE KÄYTTÄÄ?";

?>
