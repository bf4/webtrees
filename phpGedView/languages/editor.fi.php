<?php
/**
 * Finnish texts
 *
 * phpGedView: Genealogy Viewer
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
 *
 * @author PGV Developers
 * @package PhpGedView
 * @subpackage Languages
 * @translator Matti Valve
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Et pääse suoraan kielitiedostoon.";
	exit;
}

$pgv_lang["advanced_name_fields"]	= "Lisänimiä (lempinimi, avionimi, jne.)";
$pgv_lang["accept_changes"]		= "Hyväksy/Hylkää muutokset";
$pgv_lang["replace"]		= "Vaihda tietue";
$pgv_lang["append"]		= "Lisää tietue";
$pgv_lang["review_changes"]		= "Tutki GEDCOM muutokset";
$pgv_lang["remove_object"]		= "Poista kohde";
$pgv_lang["remove_links"]		= "Poista linkit";
$pgv_lang["media_not_deleted"]	= "Mediahakemistoa ei poistettu.";
$pgv_lang["thumbs_not_deleted"]	= "Pienoiskuvahakemistoa ei poistettu.";
$pgv_lang["thumbs_deleted"]		= "Pienoiskuvahakemisto poistettu.";
$pgv_lang["show_thumbnail"]		= "Näytä pienoiskuvat";
$pgv_lang["link_media"]		= "Linkitä media";
$pgv_lang["to_person"]		= "henkilöön";
$pgv_lang["to_family"]		= "perheeseen";
$pgv_lang["to_source"]		= "lähteeseen";
$pgv_lang["edit_fam"]		= "Editoi perhettä";
$pgv_lang["edit_repo"]				= "Editoi tietovarasto";
$pgv_lang["copy"]			= "Kopioi";
$pgv_lang["cut"]			= "Leikkaa";
$pgv_lang["sort_by_birth"]		= "Lajittele syntymäpäivän mukaan";
$pgv_lang["reorder_children"]		= "Järjestä lapset uudelleen";
$pgv_lang["add_from_clipboard"]	= "Lisää leikepöydältä";
$pgv_lang["record_copied"]		= "Tietue kopioitu leikepöydälle";
$pgv_lang["add_unlinked_person"]	= "Lisää linkittämätön henkilö";
$pgv_lang["add_unlinked_source"]	= "Lisää linkittämätön lähde";
$pgv_lang["server_file"]		= "Tiedoston nimi palvelimella";
$pgv_lang["server_file_advice"]	= "Älä muuta säilyttääksesi vanhan nimen.";
$pgv_lang["server_file_advice2"]	= "Voit lisätä URL-osoitteen laittaen alkuun &laquo;http://&raquo;.";
$pgv_lang["server_folder_advice"]	= "Voit lisätä korkeintaan #GLOBALS[MEDIA_DIRECTORY_LEVELS]# hakemistonimeä &laquo;#GLOBALS[MEDIA_DIRECTORY]#&raquo;:n lisäksi.<br />Älä kirjoita &laquo;#GLOBALS[MEDIA_DIRECTORY]#&raquo;-osuutta kohdehakemiston nimestä.";
$pgv_lang["server_folder_advice2"]	= "Tämä jätetään huomiotta,mikäli olet lisännyt URL-osoitteen tiedostonimikenttään.";
$pgv_lang["add_linkid_advice"]	= "Lisää tai etsi sen henkilön, perheen tai lähteen ID-tunnus, johon tämä media linkitetään.";
$pgv_lang["use_browse_advice"]	= "Käytä &laquo;Selaa&raquo;-painiketta hakeaksesi tiedostoa omalta tietokoneeltasi.";
$pgv_lang["add_media_other_folder"]	= "Toinen hakemisto ... ole hyvä ja kirjoita";
$pgv_lang["add_media_file"]		= "Palvelimella oleva mediatiedosto";
$pgv_lang["main_media_ok1"]		= "Mediatiedoston <b>#GLOBALS[oldMediaName]#</b> nimi muutettu nimeksi <b>#GLOBALS[newMediaName]#</b>.";
$pgv_lang["main_media_ok2"]		= "Mediatiedosto <b>#GLOBALS[oldMediaName]#</b> siirretty hakemistosta <b>#GLOBALS[oldMediaFolder]#</b> hakemistoon <b>#GLOBALS[newMediaFolder]#</b>.";
$pgv_lang["main_media_ok3"]		= "Mediatiedosto siirretty ja nimetty uudestaan seuraavasti:</ br>Vanha: <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b></ br>Uusi: <b>#GLOBALS[newMediaFolder]##GLOBALS[newMediaName]#</b>.";
$pgv_lang["main_media_fail0"]		= "Mediatiedostoa <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b> ei ole olemassa.";
$pgv_lang["main_media_fail1"]		= "Mediatiedoston nimeä <b>#GLOBALS[oldMediaName]#</b> ei voitu muuttaa nimeksi <b>#GLOBALS[newMediaName]#</b>.";
$pgv_lang["main_media_fail2"]		= "Mediatiedostoa <b>#GLOBALS[oldMediaName]#</b> ei voitu siirtää hakemistosta <b>#GLOBALS[oldMediaFolder]#</b> hakemistoon <b>#GLOBALS[newMediaFolder]#</b>.";
$pgv_lang["main_media_fail3"]		= "Mediatiedostoa <b>ei</b> voitu siirtää eikä nimetä uudestaan. Vanha: <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b></ br> Uusi: <b>#GLOBALS[newMediaFolder]##GLOBALS[newMediaName]#</b>";
$pgv_lang["resn_disabled"]				= "Huomaa: Sinun on aktivoitava \'Use GEDCOM (RESN) Privacy restriction\'-ominaisuus jotta tämä asetus astuu voimaan.";
$pgv_lang["thumb_media_ok1"]	= "Pienoiskuva <b>#GLOBALS[oldMediaName]#</b> nimetty uudestaan nimeksi <b>#GLOBALS[newMediaName]#</b>.";
$pgv_lang["thumb_media_ok2"]	= "Pienoiskuvatiedosto <b>#GLOBALS[oldMediaName]#</b> siirretty hakemistosta <b>#GLOBALS[oldThumbFolder]#</b> hakemistoon <b>#GLOBALS[newThumbFolder]#</b>.";
$pgv_lang["thumb_media_ok3"]	= "Pienoiskuvatiedosto siirretty ja nimetty uudestaan seuraavasti:</ br>Vanha: <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b></ br>Uusi: <b>#GLOBALS[newThumbFolder]##GLOBALS[newMediaName]#</b>.";
$pgv_lang["thumb_media_fail0"]	= "Pienoiskuvaa <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b> ei ole olemassa.";
$pgv_lang["thumb_media_fail1"]	= "Pienoiskuvatiedostoa <b>#GLOBALS[oldMediaName]#</b> ei voitu nimetä uudelleen nimeksi <b>#GLOBALS[newMediaName]#</b>.";
$pgv_lang["thumb_media_fail2"]	= "Pienoiskuvatiedostoa <b>#GLOBALS[oldMediaName]#</b> ei voituu siirtää hakemistosta <b>#GLOBALS[oldThumbFolder]#</b> hakemistoon <b>#GLOBALS[newThumbFolder]#</b>.";
$pgv_lang["thumb_media_fail3"]	= "Pienoiskuvatiedostoa ei voitu siirtää tai nimetä uudestaan.</ br>Vanha: <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b></ br>Uusi: <b>#GLOBALS[newThumbFolder]##GLOBALS[newMediaName]#</b>";
$pgv_lang["add_asso"]		= "Lisää uusi läheinen";
$pgv_lang["edit_sex"]		= "Editoi sukupuoli";
$pgv_lang["add_obje"]		= "Lisää multimediakohde";
$pgv_lang["add_name"]		= "Lisää nimi";
$pgv_lang["edit_raw"]		= "Editoi GEDCOM raakadataa";
$pgv_lang["label_add_remote_link"]  	= "Lisää linkki";
$pgv_lang["label_gedcom_id"]        	= "GEDCOM ID-tunnus";
$pgv_lang["label_local_id"]         	= "Henkilön ID-tunnus";
$pgv_lang["accept"]			= "Hyväksy";
$pgv_lang["accept_all"]		= "Hyväksy kaikki muutokset";
$pgv_lang["accept_gedcom"]		= "Päätä hyväksytkö vai hylkäätkö kunkin muutoksen.<br /><br />Hyväksyäksesi kaikki muutokset kerralla, napauta <b>\"Hyväksy kaikki muutokset\"</b> alla olevassa laatikossa.<br />Saadaksesi lisätietoa muutoksesta,<br />napauta <b>\"Katso muutoksen erot\"</b> nähdäksesi vanhan ja uuden  tilanteen eroavuudet,<br /> tai napauta <b>\"Katso GEDCOM tietuetta\"</b> nähdäksesi uuden tilanteen GEDCOM-muodossa.";
$pgv_lang["accept_successful"]	= "Hyväksy tiedosto muutokset";
$pgv_lang["add_child"]		= "Lisää lapsi";
$pgv_lang["add_child_to_family"]	= "Lisää lapsi perheeseen";
$pgv_lang["add_fact"]		= "Lisää tieto";
$pgv_lang["add_father"]		= "Lisää isä";
$pgv_lang["add_husb"]		= "Lisää aviomies";
$pgv_lang["add_husb_to_family"]	= "Lisää aviomies perheeseen";
$pgv_lang["add_media"]		= "Lisää uusi media";
$pgv_lang["add_media_lbl"]		= "Lisää media";
$pgv_lang["add_mother"]		= "Lisää äiti";
$pgv_lang["add_new_chil"] 		= "Lisää uusi lapsi";
$pgv_lang["add_new_husb"]		= "Lisää uusi puoliso";
$pgv_lang["add_new_wife"]		= "Lisää uusi puoliso";
$pgv_lang["add_note"]		= "Lisää uusi lisätieto";
$pgv_lang["add_note_lbl"]		= "Lisää lisätieto";
$pgv_lang["add_sibling"]		= "Lisää sisarus";
$pgv_lang["add_son_daughter"]	= "Lisää poika tai tytär";
$pgv_lang["add_source"]		= "Lisää uusi lähde";
$pgv_lang["add_source_lbl"]		= "Lisää lähde";
$pgv_lang["add_wife"]		= "Lisää vaimo";
$pgv_lang["add_wife_to_family"]	= "Lisää vaimo perheeseen";
$pgv_lang["advanced_search_discription"] = "Tarkempi haku sivustolta";
$pgv_lang["auto_thumbnail"]		= "Automattinen pienoiskuva";
$pgv_lang["basic_search"]		= "hae";
$pgv_lang["basic_search_discription"] 	= "Perushaku sivustolta";
$pgv_lang["birthdate_search"]		= "Syntymäaika";
$pgv_lang["birthplace_search"]	= "Syntymäpaikka: ";
$pgv_lang["change"]		= "Muuta";
$pgv_lang["change_family_instr"]	= "Tällä sivulla voi muuttaa tai poistaa perheenjäseniä. <br /><br />Jokaselle perheenjäsenelle voit käyttää \"Muuta\"-linkkiä valitsemaan eri  henkilön kyseiseen asemaan perheessä. Henkilön voi myös poistaa perheestä käyttämällä \"Poista\"-linkkiä.<br /><br />Muutosten jälkeen näpäytä \"Tallenna\"-painiketta muutosten tallentamiseksi.<br />";
$pgv_lang["change_family_members"]	= "Muuta perheenjäseniä";
$pgv_lang["changes_occurred"]	= "Seuraavat muutokset on tehty henkilölle:";
$pgv_lang["confirm_remove"]		= "Haluatko todella poistaa tämän henkilön perheestä?";
$pgv_lang["confirm_remove_object"]	= "Haluatko varmasti poistaa tämän kohteen tietokannasta?";
$pgv_lang["create_repository"]		= "Luo tietovarasto";
$pgv_lang["create_source"]		= "Luo uusi lähde";
$pgv_lang["current_person"]         	= "Sama kuin nykyinen";
$pgv_lang["date"]			= "Päiväys";
$pgv_lang["deathdate_search"]	= "Kuolinaika: ";
$pgv_lang["deathplace_search"]	= "Kuolinpaikka: ";
$pgv_lang["delete_dir_success"]	= "Media- ja pienoiskuvahakemistot poistettu.";
$pgv_lang["delete_file"]		= "Poista tiedosto";
$pgv_lang["delete_repo"]		= "Poista tietovarasto";
$pgv_lang["directory_not_empty"]	= "Hakemisto ei ole tyhjä.";
$pgv_lang["directory_not_exist"]	= "Hakemistoa ei ole.";
$pgv_lang["error_remote"]           	= "Olet valinnut etäpalvelimen";
$pgv_lang["error_same"]             	= "Olet valinnut saman palvelimen.";
$pgv_lang["external_file"]		= "Tätä mediakohdetta ei ole tiedostona tällä palvelimella. Sitä ei voi poistaa, siirtää tai nimetä uudelleen. ";
$pgv_lang["file_missing"]		= "Yksikään tiedosto ei latautunut. Yritä uudelleen.";
$pgv_lang["file_partial"]		= "Tiedosto latautui vain osittain, yritä uudelleen.";
$pgv_lang["file_success"]		= "Tiedoston lataus onnistui.";
$pgv_lang["file_too_big"]		= "Ladattava tiedosto on liian suuri.";
$pgv_lang["folder"]		 	= "Tiedostokansio";
$pgv_lang["gedcom_editing_disabled"]	= "Järjestelmävalvoja on estänyt tämän GEDCOM-tiedoston editoinnin.";
$pgv_lang["gedcomid"]		= "GEDCOM INDI-tietueen ID";
$pgv_lang["gedrec_deleted"]		= "GEDCOM tietue poisettu.";
$pgv_lang["gen_thumb"]		= "Luo pienoiskuva";
$pgv_lang["gender_search"]		= "Sukupuoli: ";
$pgv_lang["generate_thumbnail"]	= "Luo pienoiskuvat automaattisesti";
$pgv_lang["hebrew_givn"]			= "Heprealaiset etunimet";
$pgv_lang["hebrew_surn"]			= "Heprealainen sukunimi";
$pgv_lang["hide_changes"]		= "Klikkaa jos haluat kätkeä muutokset.";
$pgv_lang["highlighted"]		= "Korostettu kuva";
$pgv_lang["illegal_chars"]		= "Vääriä merkkejä nimessä";
$pgv_lang["invalid_search_multisite_input"] = "Anna joku seuraavista: nimi, syntymäaika, syntymäpaikka, kuolinaika, kuolinpaikka ja sukupuoli";
$pgv_lang["invalid_search_multisite_input_gender"] = "Hae uudestaan, mutta anna enemmän tietoja pelkästään sukupuolen lisäksi.";
$pgv_lang["label_diff_server"]      	= "Eri palvelin";
$pgv_lang["label_location"]         	= "Sijainti";
$pgv_lang["label_password_id2"]	= "Salasana: ";
$pgv_lang["label_rel_to_current"]   	= "Sukulaisuus nykyiseen henkilöön";
//$pgv_lang["label_remote_id"]        	= "Toisen verkkopalvelun henkilön ID-tunnus";
$pgv_lang["label_same_server"]      	= "Sama palvelin";
$pgv_lang["label_site"]             		= "Verkkopalvelu";
$pgv_lang["label_site_url"]         	= "Verkko-osoite (URL)";
$pgv_lang["label_username_id2"]	= "Käyttäjätunnus: ";
$pgv_lang["lbl_server_list"]        	= "Käytä olemassaolevaa verkkopalvelua.";
$pgv_lang["lbl_type_server"]        	= "Lisää uusi verkkopalvelu.";
$pgv_lang["link_as_child"]		= "Linkitä tämä henkilö lapseksi olemassa olevaan perheeseen.";
$pgv_lang["link_as_husband"]		= "Linkitä tämä henkilö aviomieheksi olemassa olevaan perheeseen.";
$pgv_lang["link_success"]		= "Linkki lisätty";
$pgv_lang["link_to_existing_media"]	= "Linkitä olemassaolevaan mediaan";
$pgv_lang["max_media_depth"]	= "Voit mennä ainoastaan #MEDIA_DIRECTORY_LEVELS# hakemistotasoa alaspäin.";
$pgv_lang["max_upload_size"]		= "Suurin ladattava tiedostokoko:";
$pgv_lang["media_deleted"]		= "Mediahakemisto poistettu.";
$pgv_lang["media_exists"]		= "Mediatiedosto on jo olemassa.";
$pgv_lang["media_file"]		= "Mediasuodatin";
$pgv_lang["media_file_deleted"]	= "Media poistettu.";
$pgv_lang["media_file_moved"]			= "Mediatiedosto siirretty.";
$pgv_lang["media_file_not_moved"]	= "Mediatiedostoa ei voitu siirtää.";
$pgv_lang["media_file_not_renamed"]	= "Mediatiedostoa ei voitu siirtää tai nimetä uudestaan.";
$pgv_lang["media_thumb_exists"]	= "Median pienoiskuva on jo olemassa.";
$pgv_lang["multiple_gedcoms"]	= "Tämä tiedosto on linkitetty toiseen sukututkimustietokantaan tällä palvelimella. Sitä ei voi poistaa, siirtää tai nimetä uudelleen ennenkuin nämä linkitykset on poistettu.";
$pgv_lang["must_provide"]		= "Sinun tulee antaa ";
$pgv_lang["name_search"]		= "Nimi:";
$pgv_lang["new_repo_created"]	= "Luotu uusi tietovarasto";
$pgv_lang["new_source_created"] 	= "Uuden lähteen luominen onnistui.";
$pgv_lang["no_changes"] 		= "Ei muutoksia tarkasteltavaksi";
$pgv_lang["no_known_servers"]	= "Ei tunnettuja palvelimia<br />Tuloksia ei löydy";
$pgv_lang["no_temple"]		= "Ei temppeliä - elävä";
$pgv_lang["no_upload"]		= "Mediatiedostojen lataaminen palvelimelle ei ole saallittu, koska multimediakohteet eivät ole sallittu tai koska mediahakemistoon ei voi kirjoittaa. ";
$pgv_lang["paste_id_into_field"]	= "Liitä seuraava lähteen ID editointikenttiisi viitataksesi tähän lähteeseen.";
$pgv_lang["paste_rid_into_field"]	= "Liitä seuraava tietovaraston ID editoitaviin kenttiisi viittaukseksi tähän tietovarastoon.";
$pgv_lang["photo_replace"] 		= "Haluatko korvata vanhemman valokuvan tällä?";
$pgv_lang["privacy_not_granted"]	= "Sinulla ei ole pääsyoikeutta";
$pgv_lang["privacy_prevented_editing"]	= "Yksityisyysasetukset estävät tämän GEDCOM-tiedoston editoinnin.";
$pgv_lang["record_marked_deleted"]	= "Tämä tietue on merkitty ylläpitäjän hyväksymisen jälkeen poistettavaksi.";
$pgv_lang["replace_with"]			= "Korvaa tällä:";
$pgv_lang["show_changes"]		= "Tämä tietue on muuttunut. Klikkaa, jos haluat nähdä muutokset.";
$pgv_lang["thumb_genned"]		= "Pienoiskuvat on luotu automaattisesti.";
$pgv_lang["thumbgen_error"]		= "Pienoiskuvaa ei voi luoda ";
$pgv_lang["thumbnail"]		= "Pienoiskuva";
$pgv_lang["title_remote_link"]      	= "Lisää ulkoinen linkki";
$pgv_lang["undo"]			= "Peru";
$pgv_lang["undo_all"]		= "Peruuta kaikki muutokset";
$pgv_lang["undo_all_confirm"]		= "Oletko varma, että haluat peruutta kaikki tähän GEDCOM-tiedostoon tekemäsi muutokset?";
$pgv_lang["undo_successful"]		= "Peruaminen onnistui";
$pgv_lang["update_successful"]	= "Päivitys onnistui.";
$pgv_lang["upload"]			= "Lataa palvelimelle";
$pgv_lang["upload_error"]		= "Tiedoston latauksessa tapahtui virhe.";
$pgv_lang["upload_media"]		= "Lataa mediatiedostot palvelimelle";
$pgv_lang["upload_media_help"]	= "Valitse ne tiedostot omalta tietokoneeltasi, jotka haluat ladata palvelimelle. Kaikki tiedostot ladataan hakemistoon <b>#MEDIA_DIRECTORY#</b> tai johonkin sen alihakemistoon.<br /><br />Määrittelemäsi hakemistonimet lisätään  #MEDIA_DIRECTORY#:n perään. Esimerkiksi  #MEDIA_DIRECTORY#myfamily- Mikäli pienoiskuvahakemistoa ei vielä ole olemassa, se luodaan automaattisesti.<br /><br />";
$pgv_lang["upload_successful"]	= "Koneelle lataaminen onnistui";
$pgv_lang["view_change_diff"]		= "Näytä muutokset";
$pgv_lang["admin_override"]			= "Ylläpitäjävalinta";
$pgv_lang["no_update_CHAN"]			= "Älä päivitä CHAN (viimeinen muutos) tietuetta";
$pgv_lang["select_events"]			= "Valitse tapahtumat";
$pgv_lang["source_events"]			= "Kytke tapahtumat tähän lähteeseen";
$pgv_lang["add_marriage"]			= "Lisää uusi avioliitto";
$pgv_lang["edit_concurrency_change"] = "Tämän tietueen muutti viimeksi <i>#CHANGEUSER#</i> #CHANGEDATE#";
$pgv_lang["edit_concurrency_msg2"]	= "Tietuetta, jonka tunnus on #PID# on muuttanut toinen käyttäjä sen jälkeen kun viimeksi käsittelit sitä.";
$pgv_lang["edit_concurrency_msg1"]	= "Editointilomakkeen luonnissa tapahtui virhe. Joku toinen käyttäjä on ehkä muuttanut tätä tietuetta sen jälkeen kun viimeksi katsoit sitä.";
$pgv_lang["edit_concurrency_reload"]	= "Näpäytä selaimesi \"Edellinen sivu\" (Previous Page)-painiketta ja lataa edellinen sivu uudestaan jotta varmistetaan, että työskentelet viimeismmän tietueen kanssa.";
$pgv_lang["reorder_media"]					= "Järjestä media uudelleen";
$pgv_lang["reorder_media_window"]			= "Järjestä media uudelleen (ikkuna)";
$pgv_lang["reorder_media_window_title"]		= "Napsauta riville, vedä-ja-pudota uudelleen, jotta tiedotusvälineiden";
$pgv_lang["reorder_media_title"]			= "Vedä-ja-pudota pikkukuvia, jotta media järjestyy uudelleen";
?>
