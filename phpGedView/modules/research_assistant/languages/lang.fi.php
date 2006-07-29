<?php
/**
 * phpGedView Research Assistant Tool - Form Loader Engine.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 * @subpackage Research_Assistant
 * @version $Id: research_assistant.php 47 2005-10-25 19:32:25Z wlasson $
 * @author Jason Porter
 * @author Wade Lasson
 * @author Brandon Gagnon
 * @author Brian Kramer
 * @author Julian Gautier
 * @author Hector Pena
 * @translator Matti Valve
 */

//-- security check, only allow access from module.php
if (preg_match("/ra_lang\...\.php$/", $_SERVER["PHP_SELF"])>0) {
	print "You cannot access a language file directly.";
	exit;
}
$pgv_lang["missing_info"] 		= "Puuttuva tieto";
$temp_out_autosearch 			= "Tämä ominaisuus suorittaa automaattisen esipolvihaun ja perhehaun, ";
$temp_out_autosearch 			.= "voit hakea nimen ja syntymä-/kuolinpäivän mukaan<br />";
$pgv_lang["auto_search"]		= $temp_out_autosearch;
$pgv_lang["auto_search_text"]	= "Automaattihaku";
$pgv_lang["task_list"]			= "Tehtävät";
$pgv_lang["task_list_text"]		= "Tällä alueella näytetään luomasi tehtävät. Näpäytä NÄYTÄ nähdäksesi tehtävän";

// -- HELP COMMENTS
$temp_out_comments 				= "Tähän voidaan lisätä henkilöitä koskevia kommentteja muille käyttäjille näytettäväksi ja palautteen antamiseksi";
$pgv_lang["help_comments"] 		= $temp_out_comments;

// -- MENU ITEM MESSAGES
$pgv_lang["my_tasks"]						= "Tehtäväni";
$pgv_lang["add_task"]						= "Lisää tehtävä";
$pgv_lang["view_folders"]					= "Näytä hakemistot";
$pgv_lang["view_probabilities"]				= "Näytä todennäköisyydet";
$pgv_lang["up_folder"]						= "Up Folder";
$pgv_lang["edit_folder"]					= "Lisää/editoi hakemisto(a)";
$pgv_lang["gen_tasks"]						= "Luo automaattisesti tehtäviä";


// -- RA GENERAL MESSAGES
//$pgv_lang["delete"]						= "Poista";
$pgv_lang["edit_task"]						= "Editoi tehtävää";
//$pgv_lang["view"]							= "Näytä";
//$pgv_lang["name"]							= "Nimi";
//$pgv_lang["folder"]						= "Hakemisto";   // Exists as "Folder on Server"
$pgv_lang["completed"]						= "Valmis";
$pgv_lang["comres"]							= "Kommentit/tulokset";
$pgv_lang["description"]					= "Kuvaus";
$pgv_lang["created"]						= "Luotu";
$pgv_lang["modified"]						= "Muutettu";
$pgv_lang["folder_list"]					= "Hakemistoluettelo";
$pgv_lang["details"]						= "Yksityiskohdat";
$pgv_lang["result"]                     	= "Tulos";
$pgv_lang["okay"]                           = "OK";

// -- RA_FOLDER MESSAGES
$pgv_lang["Edit_Task"]                 		= "Editoi tehtävää";
$pgv_lang["End_Date"]                 		= "Loppetuspäivämäärä";
$pgv_lang["Start_Date"]                 	= "Aloituspäivämäärä";
$pgv_lang["Task_Name"]                		= "Tehtävän nimi";
$pgv_lang["Folder_Name"]                	= "Hakemiston nimi";
$pgv_lang["Folder_View"]                	= "Hakemistonäkymä";
$pgv_lang["Task_View"]                  	= "Tehtävänäkymä";
$pgv_lang["page_header"]					= "Research Assistant Folders";
$pgv_lang["folder_new"]						= "Luo uusi hakemisto";
$pgv_lang["folder_delete_check"]			= "Haluatko varmasti poistaa tämän hakemiston?";
$pgv_lang["no_folder_name"]             	= "Hakemistonimi on pakollinen";
$pgv_lang["add_folder"]                 	= "Lisää hakemisto";
$pgv_lang["edit_folder"]                	= "Editoi hakemistoa";
$pgv_lang["folder_name"]                	= "Hakemiston nimi:";
$pgv_lang["Parent_Folder:"]             	= "Päähakemisto:";
$pgv_lang["No_Parent"]                  	= "Ei päähakemistoa";
$pgv_lang["Folder_Description:"]        	= "Hakemiston kuvaus:";
$pgv_lang["Folder_names_must_be_unique"]	= "Ei samoja hakemistonimiä.";
$pgv_lang["folder_submitted"]          		= "Hakemistosi on lähetetty";
$pgv_lang["folder_problem"]             	= "Hakemistosi lisääminen ei onnistunut, yritä uudelleen";

// -- Missing Information Help
$temp_out_missinginfo 						= "Tällä alueella näytetään mitkä tiedot puuttuvat tietueesta .";
$temp_out_missinginfo 						.= "Valitse valintaruutu ja ja hakemisto ja paina Lisää tehtävä luodaksesi tehtävän puuttuvalle kohteelle.";
$temp_out_missinginfo 						.= "Jo luodut tehtävät näyttävät 'näytä' valintaruudun asemesta <br />";
$temp_out_missinginfo 						.= " <a href=\"javascript:void(0);\" onClick=\"fullScreen('helpvids/MissingInformationUserHelp.htm');\">Näpäytä tästä avataksesi Käyttäjän ohjeet kokoruutunäkymässä</a>";
$pgv_lang["ra_missing_info_help"] 			= $temp_out_missinginfo;

// -- RA_EDITFOLDER MESSAGES
$pgv_lang["edit_research_folder"]			= "Editoi tutkimushakemistoa";
$pgv_lang["folder_not_exist"]				= "Tätä hakemistoa ei ole: ";
$pgv_lang["folder_parent"]					= "Päähakemisto";
$pgv_lang["parent_id"]						= "Tyhjä";
$pgv_lang["folder_users"]					= "Muut käyttäjät, jotka voivat nähdä tämän hakemiston";

// -- RA_EDITLOG MESSAGES
$pgv_lang["edit_research_log"]				= "Editoi tutkimuslokia";
$pgv_lang["log_not_exist"]					= "Tätä lokia ei ole: ";

// -- RA_LOG MESSAGES
$pgv_lang["edit_log_entry"]					= "Editoi tutkimuslokitietoja";
$pgv_lang["log_no_entry"]					= "VIRHE: Sinulla ei ole oikeutta tähän.";
$pgv_lang["log_modified"]					= "Viimeksi muokattu";
$pgv_lang["log_modified_by"]				= "Viimeisin muokkaaja";
$pgv_lang["log_edit_entry"]					= "Editoi tätä";

// -- RA_LISTLOGS MESSAGES
$pgv_lang["research_logs"]					= "Tutkimuslokit";
$pgv_lang["log_no_entry_folder"]			= "VIRHE: Sinulla ei ole pääsyoikeutta tähän hakemistoon.";
$pgv_lang["folder_sub"]						= "Alihakemistot";
$pgv_lang["folder_sub_new"]					= "Luo uusi alihakemisto";
$pgv_lang["task_entry"]						= "Luo uusi tehtävä.";
$pgv_lang["log_show"]						= "Show All Logs";
$pgv_lang["log_show_uncomplete"]			= "Näytä ei-valmiit lokit";
$pgv_lang["log_show_complete"]				= "Näytä valmiit lokit";
$pgv_lang["log_delete_check"]				= "Haluatko varmasti poistaa tämän lokitiedon?";

// -- RA_FUNCTIONS MESSAGES
$pgv_lang["function_folder_delete"]			= "VIRHE: Tätä hakemistoa ei voi poistaa, koska se sisältää tutkimuslokitietoja.<br />Siirrä tai poista ensin nämä tutkimuslokitiedot ja yritä sen jälkeen poistaa hakemisto uudelleen.";
$pgv_lang["function_subfolder_delete"]		= "VIRHE: Tätä hakemistoa ei voi poistaa, koska se sisältää alihakemistoja.<br />Siirrä tai poista ensin nämä alihakemistot ja yritä sen jälkeen poistaa hakemisto uudelleen.";
$pgv_lang["folder_delete_ok"]				= "Hakemiston #folder_name# poistaminen onnistui.";
$pgv_lang["folder_update_ok"]				= "Hakemiston #folder_name# päivitys onnistui.";
$_SESSION['pgv_lang["keywords"]']			= "Avainsanat:";
$pgv_lang["folder_added"]					= "Hakemiston #folder_name# luonti onnistui.";
$_SESSION['pgv_lang["search"]']				= "Hae";

//-- RA_SEARCH MESSAGES
$pgv_lang["search_results"]					= "Hakutulokset";
$pgv_lang["nothing_found"]					= "Sopivia lokeja ei löytynyt.";

//-- ERROR MESSAGES
$pgv_lang["no_folder"]						= "Yhtään hakemistoa ei vielä ole. Luo ensin uusi hakemisto.";

//-- HELP MESSAGES
$pgv_lang["help_rs_folders.php"]			= "Research Assistant Folders<br /> #pgv_lang[sorry]#";
$pgv_lang["help_rs_editfolder.php"]			= "Research Assistant Edit Folders<br />#pgv_lang[sorry]#";
$pgv_lang["help_rs_editlog.php"]			= "Research Assistant Edit Log<br />#pgv_lang[sorry]#";
$pgv_lang["ra_fold_name_help"]				= "<H2><B>Hakemistonäkymä:</B></H2><ul><li><B>Hakemiston nimi:</B> Tällä palstalla ovat kaikkien luomiesi hakemistojen nimet.</li><li><B>Kuvaus:</B> Tällä palstalla ovat hakemistojen kuvaukset.</li></ul><br /><br /><a href=\"helpvids/ResearchAssistantUserHelp.htm\">Research Assistant Tutorial</a>";

$pgv_lang["ra_add_task_help"]				= "<H2><B>Lisää uusi tehtävä:</B></H2></H2><ul><li><B>Otsikko:</B>Tässä pitäisi olla lisäämäsi tehtävän otsikko.</li><li><B>Hakemisto:</B>Tähän kenttään voit merkitä, mihin hakemistoon uusi tehtävä tulee.</li><li><B>Kuvaus:</B> Lisää tehtävääsi kuvaava kuvaus.</li><li><B>Lähteet:</B> Lisää tehtävääsi liittyvät lähteet.</li><li><B>Henkilöt:</B> Lisää tehtävään liittyvät henkilöt.</li></ul>";

$pgv_lang["ra_edit_folder_help"]			= "<H2><B>Editoi hakemistoa:</B></H2><ul><li><B>Hakemiston nimi:</B> Lisää tähän editoitavan hakemiston nimi.</B></li><li><B>Päähakemisto:</B> Voit määrittää editoitavan hakemiston päähakemistoksi.</B></li><li><B>Hakemiston kuvaus:</B> Lisää hakemistosi kuvaus.</B></li><ul>";

$pgv_lang["ra_add_folder_help"]				= "<H2><B>Lisää hakemisto:</B></H2><ul><li><B>Hakemiston nimi:</B> Lisää tähän lisättävän hakemiston nimi.</B></li><li><B>Päähakemisto:</B> Voit määrittää editoitavan hakemiston päähakemistoksi.</B></li><li><B>Hakemiston kuvaus:</B> Lisää hakemistosi kuvaus.</B></li><ul>";

$pgv_lang["ra_view_task_help"]				= "<H2><B>Tehtävänäkymä:</B></H2><ul><li><B>Tehtävän nimi:</B> Tällä palstalla ovat kaikkien luomiesi tehtävien nimet.</B></li><li><B>Kuvaus:</B> Tällä palstalla ovat tehtäviesi kuvaukset.</li><li><B>Aloituspäivämäärä:</B> Tässä ovat kaikkien tehtävien aloituspäivämäärät.</li><li><B>Valmis:</B>Tässä näkyy, onko tehtävä suoritettu valmiiksi.</li><li><B>Yksityiskohdat:</B>Tässä näkyvät tehtävien yksityiskohdat.</li><li><B>Poista:</B>Tässä poistetaan tehtävä.</li><ul><br /><a href=\"helpvids/MissingInformationUserHelp.htm\">Käyttäjäopastus</a>";

$pgv_lang["ra_task_view_help"]				= "<H2><B>Näytä tehtävä:</B></H2><ul><li><B>Otsikko:</B>Tässä pitäisi olla lisäämäsi tehtävän otsikko.</li><li><B>Henkilöt:</B> Liitä henkilö, joka liittyy tehtävään</li><li><B>Kuvaus:</B> Lisää tehtävääsi kuvaava kuvaus.</li><li><B>Lähteet:</B> Lisää tehtävääsi liittyvät lähteet.</li><li>Näpäytä 'Editoi tehtävää'-painiketta muokataksesi tehtävän yksityiskohtia.</li></ul>";

$pgv_lang["ra_comments_help"]				= "<H2><B>Kommentit:</B></H2><ul><li>Tähän tulevat tehtävään liittyvät kommentit. Näpäytä 'Lisää uusi kommentti'-painiketta lisätäksesi kommentin.</li></ul>";

$pgv_lang["ra_GenerateTasks_help"]			= "<H2><B>Luo tehtäviä:</B></H2><p>Tämä lomake luo tehtäviä _TODO-merkitsimistä GEDCOM-tiedostossasi.</p><ul><li><B>Luo:</B> Tarkista jokainen luotava tehtävä, kun painat 'Luo tehtävä'-painiketta.</li><li><B>Tehtävän nimi:</B> Tämä nimi annetaan tehtävälle.  This defaults to the text in the actual _TODO tag, excluding any CONT tag&quot;s</li><li><B>Tehtävän kuvaus:</B> Tehtävälle annettava kuvaus. Se luodaan _TODO-merkitsimen tekstistä sekä kaikesta, joka on liittynyt CONT-merkitsimiin.  </li><li><B>Editoi:</B> näpäytä linkkiä editoidaksesi tehtävää.</li><li><B>Valitse hakemisto:</B> valitse se hakemisto, johon tehtävä sijoitetaan.</li><li><B>Luo:</B> luom rastitetut tehtävät.</li><li><B>Valmis:</B> vie sinut hakemistonäkymäsivulle.</li></ul>";

$pgv_lang["ra_EditGenerateTasks_help"]		= "<H2><B>Editoi luotuja thtäviä:</B></H2><p>Tällä lomakkeella voit editoida tehtäviä, jotka on luotu _TODO-merkitsimistä GEDCOM-tiedostossasi.</p><ul><li><B>Tehtävän nimi:</B> Tämä nimi annetaan tehtävälle.  </li><li><B>Tehtävän kuvaus:</B> Tehtävän kuvaus annetaan. </li><li><B>Henkilö:</B> näpäytä linkkiä valitaksesi henkilön, joka liitetään tehtävään.</li><li><B>Lähde:</B> näpäytä linkkiä valitaksesi lähde, joka liitetään tehtävään..</li><li><B>Tallenna:</B> tallentaa kaikki muutokset ja vie sinut 'Luo tehtävä'-sivulle.</li><li><B>Peruuta:</B> peruuttaa kaikki muutokset ja vie sinut 'Luo tehtävä'-sivulle.</li></ul>";

$pgv_lang["ra_configure_privacy_help"]		= "<H2><B>Konfiguroi yksityisyysasetuksia:</B></H2></H2><ul><li><B>Näytä kaikille:</B> Näyttää määritellyt tehtävät kaikille.</li><li><B>Näytä autentikoiduille käyttäjille:</B> Näyttää määritellyt tehtävät vain autentikoiduille käyttäjille.</li><li><B> Näytä ylläpitäjille:</B> Näyttää määritellyt tehtävät vain ylläpitäjille.</li><li><B> Piilota myös ylläpitäjiltä:</B> Kukaan ei näe määriteltyjä tehtäviä.</li></ul>";

//-- RA_VIEWTASK MESSAGES
$pgv_lang["view_task"]						= "Näytä tehtävä";
//$pgv_lang["comments"]						= "Kommentit";
$pgv_lang["add_new_comment"]				= "Lisää uusi kommentti";
$pgv_lang["no_sources"]						= "Tähän tehtävään ei ole liitetty lähteitä.";
$pgv_lang["no_people"]						= "Tähän tehtävään ei ole liitetty henkilöitä.";
$pgv_lang["no_indi_tasks"]					= "Tähän tehtävään ei ole liitetty yhtään henkilöä.";
$pgv_lang["edit_comment"]					= "Editoi kommenttia";
$pgv_lang["comment_success"]				= "Kommenttisi on lisätty.";
$pgv_lang['comment_body']					= 'Kommentti';

//-- RA_COMMENT MESSAGES
$pgv_lang["comment_delete_check"]			= "Haluatko varmasti poistaa tämän kommentin?";

//-- RA_ADDTASK MESSAGES
$pgv_lang["add_new_task"]					= "Lisää uusi tehtävä";
//$pgv_lang["title"]						= "Otsikko";
$pgv_lang["submit"]							= "Lähetä";

//-- RA_EDITTASK MESSAGES
//$pgv_lang["edit_task"]					= "Editoi tehtävää";

//-- RA_CONFIGURE PRIVACY MESSAGES
$pgv_lang["configure_privacy"]		    	= "Konfiguroi yksityisyysasetuksia";
$pgv_lang["show_my_tasks"]         		    = "Näytä tehtäväni";
$pgv_lang["show_add_task"]		        	= "Näytä Lisää tehtävä";
$pgv_lang["show_auto_gen_task"]         	= "Näytä Luo tehtävä automaattisesti";
$pgv_lang["show_view_folders"]		    	= "Näytä Näytä hakemisto";
$pgv_lang["show_add_folder"]		    	= "Näytä Lisää hakemisto";
$pgv_lang["show_add_unlinked_source"]   	= "Näytä Lisää linkittämätön lähde";
$pgv_lang["show_view_probabilities"]		= "Näytä Näytä todennäköisyydet";




//-- COMMENT HELP
$pgv_lang["comment_title_help"]				= "Comment Title Help here.";
$pgv_lang["comment_help"]					= "Näpäytä tästä saadaksesi ohjeita.";

//-- Census Forms
$pgv_lang["rows"]                       	= "Rivien lukumäärä";
$pgv_lang["state"]                      	= "Tila";
$pgv_lang["call/url"]                   	= "Soita numeroon/URL:ään";
$pgv_lang["enumDate"]                   	= "Numerointipäiväys";
$pgv_lang["county"]                     	= "Maa";
$pgv_lang["city"]                       	= "Kaupunki";
//$pgv_lang["page"]                       	= "Sivu";
?>
