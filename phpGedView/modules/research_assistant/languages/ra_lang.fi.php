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
$temp_out_autosearch 			= "T‰m‰ ominaisuus suorittaa automaattisen esipolvihaun ja perhehaun, ";
$temp_out_autosearch 			.= "voit hakea nimen ja syntym‰-/kuolinp‰iv‰n mukaan<br />";
$pgv_lang["auto_search"]		= $temp_out_autosearch;
$pgv_lang["auto_search_text"]	= "Automaattihaku";
$pgv_lang["task_list"]			= "Teht‰v‰t";
$pgv_lang["task_list_text"]		= "T‰ll‰ alueella n‰ytet‰‰n luomasi teht‰v‰t. N‰p‰yt‰ NƒYTƒ n‰hd‰ksesi teht‰v‰n";

// -- HELP COMMENTS
$temp_out_comments 				= "T‰h‰n voidaan lis‰t‰ henkilˆit‰ koskevia kommentteja muille k‰ytt‰jille n‰ytett‰v‰ksi ja palautteen antamiseksi";
$pgv_lang["help_comments"] 		= $temp_out_comments;

// -- MENU ITEM MESSAGES
$pgv_lang["my_tasks"]						= "Teht‰v‰ni";
$pgv_lang["add_task"]						= "Lis‰‰ teht‰v‰";
$pgv_lang["view_folders"]					= "N‰yt‰ hakemistot";
$pgv_lang["view_probabilities"]				= "N‰yt‰ todenn‰kˆisyydet";
$pgv_lang["up_folder"]						= "Up Folder";
$pgv_lang["edit_folder"]					= "Lis‰‰/editoi hakemisto(a)";
$pgv_lang["gen_tasks"]						= "Luo automaattisesti teht‰vi‰";


// -- RA GENERAL MESSAGES
//$pgv_lang["delete"]						= "Poista";
$pgv_lang["edit_task"]						= "Editoi teht‰v‰‰";
//$pgv_lang["view"]							= "N‰yt‰";
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
$pgv_lang["Edit_Task"]                 		= "Editoi teht‰v‰‰";
$pgv_lang["End_Date"]                 		= "Loppetusp‰iv‰m‰‰r‰";
$pgv_lang["Start_Date"]                 	= "Aloitusp‰iv‰m‰‰r‰";
$pgv_lang["Task_Name"]                		= "Teht‰v‰n nimi";
$pgv_lang["Folder_Name"]                	= "Hakemiston nimi";
$pgv_lang["Folder_View"]                	= "Hakemiston‰kym‰";
$pgv_lang["Task_View"]                  	= "Teht‰v‰n‰kym‰";
$pgv_lang["page_header"]					= "Research Assistant Folders";
$pgv_lang["folder_new"]						= "Luo uusi hakemisto";
$pgv_lang["folder_delete_check"]			= "Haluatko varmasti poistaa t‰m‰n hakemiston?";
$pgv_lang["no_folder_name"]             	= "Hakemistonimi on pakollinen";
$pgv_lang["add_folder"]                 	= "Lis‰‰ hakemisto";
$pgv_lang["edit_folder"]                	= "Editoi hakemistoa";
$pgv_lang["folder_name"]                	= "Hakemiston nimi:";
$pgv_lang["Parent_Folder:"]             	= "P‰‰hakemisto:";
$pgv_lang["No_Parent"]                  	= "Ei p‰‰hakemistoa";
$pgv_lang["Folder_Description:"]        	= "Hakemiston kuvaus:";
$pgv_lang["Folder_names_must_be_unique"]	= "Ei samoja hakemistonimi‰.";
$pgv_lang["folder_submitted"]          		= "Hakemistosi on l‰hetetty";
$pgv_lang["folder_problem"]             	= "Hakemistosi lis‰‰minen ei onnistunut, yrit‰ uudelleen";

// -- Missing Information Help
$temp_out_missinginfo 						= "T‰ll‰ alueella n‰ytet‰‰n mitk‰ tiedot puuttuvat tietueesta .";
$temp_out_missinginfo 						.= "Valitse valintaruutu ja ja hakemisto ja paina Lis‰‰ teht‰v‰ luodaksesi teht‰v‰n puuttuvalle kohteelle.";
$temp_out_missinginfo 						.= "Jo luodut teht‰v‰t n‰ytt‰v‰t 'n‰yt‰' valintaruudun asemesta <br />";
$temp_out_missinginfo 						.= " <a href=\"javascript:void(0);\" onClick=\"fullScreen('helpvids/MissingInformationUserHelp.htm');\">N‰p‰yt‰ t‰st‰ avataksesi K‰ytt‰j‰n ohjeet kokoruutun‰kym‰ss‰</a>";
$pgv_lang["ra_missing_info_help"] 			= $temp_out_missinginfo;

// -- RA_EDITFOLDER MESSAGES
$pgv_lang["edit_research_folder"]			= "Editoi tutkimushakemistoa";
$pgv_lang["folder_not_exist"]				= "T‰t‰ hakemistoa ei ole: ";
$pgv_lang["folder_parent"]					= "P‰‰hakemisto";
$pgv_lang["parent_id"]						= "Tyhj‰";
$pgv_lang["folder_users"]					= "Muut k‰ytt‰j‰t, jotka voivat n‰hd‰ t‰m‰n hakemiston";

// -- RA_EDITLOG MESSAGES
$pgv_lang["edit_research_log"]				= "Editoi tutkimuslokia";
$pgv_lang["log_not_exist"]					= "T‰t‰ lokia ei ole: ";

// -- RA_LOG MESSAGES
$pgv_lang["edit_log_entry"]					= "Editoi tutkimuslokitietoja";
$pgv_lang["log_no_entry"]					= "VIRHE: Sinulla ei ole oikeutta t‰h‰n.";
$pgv_lang["log_modified"]					= "Viimeksi muokattu";
$pgv_lang["log_modified_by"]				= "Viimeisin muokkaaja";
$pgv_lang["log_edit_entry"]					= "Editoi t‰t‰";

// -- RA_LISTLOGS MESSAGES
$pgv_lang["research_logs"]					= "Tutkimuslokit";
$pgv_lang["log_no_entry_folder"]			= "VIRHE: Sinulla ei ole p‰‰syoikeutta t‰h‰n hakemistoon.";
$pgv_lang["folder_sub"]						= "Alihakemistot";
$pgv_lang["folder_sub_new"]					= "Luo uusi alihakemisto";
$pgv_lang["task_entry"]						= "Luo uusi teht‰v‰.";
$pgv_lang["log_show"]						= "Show All Logs";
$pgv_lang["log_show_uncomplete"]			= "N‰yt‰ ei-valmiit lokit";
$pgv_lang["log_show_complete"]				= "N‰yt‰ valmiit lokit";
$pgv_lang["log_delete_check"]				= "Haluatko varmasti poistaa t‰m‰n lokitiedon?";

// -- RA_FUNCTIONS MESSAGES
$pgv_lang["function_folder_delete"]			= "VIRHE: T‰t‰ hakemistoa ei voi poistaa, koska se sis‰lt‰‰ tutkimuslokitietoja.<br />Siirr‰ tai poista ensin n‰m‰ tutkimuslokitiedot ja yrit‰ sen j‰lkeen poistaa hakemisto uudelleen.";
$pgv_lang["function_subfolder_delete"]		= "VIRHE: T‰t‰ hakemistoa ei voi poistaa, koska se sis‰lt‰‰ alihakemistoja.<br />Siirr‰ tai poista ensin n‰m‰ alihakemistot ja yrit‰ sen j‰lkeen poistaa hakemisto uudelleen.";
$pgv_lang["folder_delete_ok"]				= "Hakemiston #folder_name# poistaminen onnistui.";
$pgv_lang["folder_update_ok"]				= "Hakemiston #folder_name# p‰ivitys onnistui.";
$_SESSION['pgv_lang["keywords"]']			= "Avainsanat:";
$pgv_lang["folder_added"]					= "Hakemiston #folder_name# luonti onnistui.";
$_SESSION['pgv_lang["search"]']				= "Hae";

//-- RA_SEARCH MESSAGES
$pgv_lang["search_results"]					= "Hakutulokset";
$pgv_lang["nothing_found"]					= "Sopivia lokeja ei lˆytynyt.";

//-- ERROR MESSAGES
$pgv_lang["no_folder"]						= "Yht‰‰n hakemistoa ei viel‰ ole. Luo ensin uusi hakemisto.";

//-- HELP MESSAGES
$pgv_lang["help_rs_folders.php"]			= "Research Assistant Folders<br /> #pgv_lang[sorry]#";
$pgv_lang["help_rs_editfolder.php"]			= "Research Assistant Edit Folders<br />#pgv_lang[sorry]#";
$pgv_lang["help_rs_editlog.php"]			= "Research Assistant Edit Log<br />#pgv_lang[sorry]#";
$pgv_lang["ra_fold_name_help"]				= "<H2><B>Hakemiston‰kym‰:</B></H2><ul><li><B>Hakemiston nimi:</B> T‰ll‰ palstalla ovat kaikkien luomiesi hakemistojen nimet.</li><li><B>Kuvaus:</B> T‰ll‰ palstalla ovat hakemistojen kuvaukset.</li></ul><br /><br /><a href=\"helpvids/ResearchAssistantUserHelp.htm\">Research Assistant Tutorial</a>";

$pgv_lang["ra_add_task_help"]				= "<H2><B>Lis‰‰ uusi teht‰v‰:</B></H2></H2><ul><li><B>Otsikko:</B>T‰ss‰ pit‰isi olla lis‰‰m‰si teht‰v‰n otsikko.</li><li><B>Hakemisto:</B>T‰h‰n kentt‰‰n voit merkit‰, mihin hakemistoon uusi teht‰v‰ tulee.</li><li><B>Kuvaus:</B> Lis‰‰ teht‰v‰‰si kuvaava kuvaus.</li><li><B>L‰hteet:</B> Lis‰‰ teht‰v‰‰si liittyv‰t l‰hteet.</li><li><B>Henkilˆt:</B> Lis‰‰ teht‰v‰‰n liittyv‰t henkilˆt.</li></ul>";

$pgv_lang["ra_edit_folder_help"]			= "<H2><B>Editoi hakemistoa:</B></H2><ul><li><B>Hakemiston nimi:</B> Lis‰‰ t‰h‰n editoitavan hakemiston nimi.</B></li><li><B>P‰‰hakemisto:</B> Voit m‰‰ritt‰‰ editoitavan hakemiston p‰‰hakemistoksi.</B></li><li><B>Hakemiston kuvaus:</B> Lis‰‰ hakemistosi kuvaus.</B></li><ul>";

$pgv_lang["ra_add_folder_help"]				= "<H2><B>Lis‰‰ hakemisto:</B></H2><ul><li><B>Hakemiston nimi:</B> Lis‰‰ t‰h‰n lis‰tt‰v‰n hakemiston nimi.</B></li><li><B>P‰‰hakemisto:</B> Voit m‰‰ritt‰‰ editoitavan hakemiston p‰‰hakemistoksi.</B></li><li><B>Hakemiston kuvaus:</B> Lis‰‰ hakemistosi kuvaus.</B></li><ul>;

$pgv_lang["ra_view_task_help"]				= "<H2><B>Teht‰v‰n‰kym‰:</B></H2><ul><li><B>Teht‰v‰n nimi:</B> T‰ll‰ palstalla ovat kaikkien luomiesi teht‰vien nimet.</B></li><li><B>Kuvaus:</B> T‰ll‰ palstalla ovat teht‰viesi kuvaukset.</li><li><B>Aloitusp‰iv‰m‰‰r‰:</B> T‰ss‰ ovat kaikkien teht‰vien aloitusp‰iv‰m‰‰r‰t.</li><li><B>Valmis:</B>T‰ss‰ n‰kyy, onko teht‰v‰ suoritettu valmiiksi.</li><li><B>Yksityiskohdat:</B>T‰ss‰ n‰kyv‰t teht‰vien yksityiskohdat.</li><li><B>Poista:</B>T‰ss‰ poistetaan teht‰v‰.</li><ul><br /><a href=\"helpvids/MissingInformationUserHelp.htm\">K‰ytt‰j‰opastus</a>";

$pgv_lang["ra_task_view_help"]				= "<H2><B>N‰yt‰ teht‰v‰:</B></H2><ul><li><B>Otsikko:</B>T‰ss‰ pit‰isi olla lis‰‰m‰si teht‰v‰n otsikko.</li><li><B>Henkilˆt:</B> Liit‰ henkilˆ, joka liittyy teht‰v‰‰n</li><li><B>Kuvaus:</B> Lis‰‰ teht‰v‰‰si kuvaava kuvaus.</li><li><B>L‰hteet:</B> Lis‰‰ teht‰v‰‰si liittyv‰t l‰hteet.</li><li>N‰p‰yt‰ 'Editoi teht‰v‰‰'-painiketta muokataksesi teht‰v‰n yksityiskohtia.</li></ul>";

$pgv_lang["ra_comments_help"]				= "<H2><B>Kommentit:</B></H2><ul><li>T‰h‰n tulevat teht‰v‰‰n liittyv‰t kommentit. N‰p‰yt‰ 'Lis‰‰ uusi kommentti'-painiketta lis‰t‰ksesi kommentin.</li></ul>";

$pgv_lang["ra_GenerateTasks_help"]			= "<H2><B>Luo teht‰vi‰:</B></H2><p>T‰m‰ lomake luo teht‰vi‰ _TODO-merkitsimist‰ GEDCOM-tiedostossasi.</p><ul><li><B>Luo:</B> Tarkista jokainen luotava teht‰v‰, kun painat 'Luo teht‰v‰'-painiketta.</li><li><B>Teht‰v‰n nimi:</B> T‰m‰ nimi annetaan teht‰v‰lle.  This defaults to the text in the actual _TODO tag, excluding any CONT tag&quot;s</li><li><B>Teht‰v‰n kuvaus:</B> Teht‰v‰lle annettava kuvaus. Se luodaan _TODO-merkitsimen tekstist‰ sek‰ kaikesta, joka on liittynyt CON-merkitsimiin.  </li><li><B>Editoi:</B> n‰p‰yt‰ linkki‰ editoidaksesi teht‰v‰‰.</li><li><B>Valitse hakemisto:</B> valitse se hakemisto, johon teht‰v‰ sijoitetaan.</li><li><B>Luo:</B> luom rastitetut teht‰v‰t.</li><li><B>Valmis:</B> vie sinut hakemiston‰kym‰sivulle.</li></ul>";

$pgv_lang["ra_EditGenerateTasks_help"]		= "<H2><B>Editoi luotuja tht‰vi‰:</B></H2><p>T‰ll‰ lomakkeella voit editoida teht‰vi‰, jotka on luotu _TODO-merkitsimist‰ GEDCOM-tiedostossasi.</p><ul><li><B>Teht‰v‰n nimi:</B> T‰m‰ nimi annetaan teht‰v‰lle.  </li><li><B>Teht‰v‰n kuvaus:</B> Teht‰v‰n kuvaus annetaan. </li><li><B>Henkilˆ:</B> n‰p‰yt‰ linkki‰ valitaksesi henkilˆn, joka liitet‰‰n teht‰v‰‰n.</li><li><B>L‰hde:</B> n‰p‰yt‰ linkki‰ valitaksesi l‰hde, joka liitet‰‰n teht‰v‰‰n..</li><li><B>Tallenna:</B> tallentaa kaikki muutokset ja vie sinut 'Luo teht‰v‰'-sivulle.</li><li><B>Peruuta:</B> peruuttaa kaikki muutokset ja vie sinut 'Luo teht‰v‰'-sivulle.</li></ul>";

$pgv_lang["ra_configure_privacy_help"]		= "<H2><B>Konfiguroi yksityisyysasetuksia:</B></H2></H2><ul><li><B>N‰yt‰ kaikille:</B> N‰ytt‰‰ m‰‰ritellyt teht‰v‰t kaikille.</li><li><B>N‰yt‰ autentikoiduille k‰ytt‰jille:</B> N‰ytt‰‰ m‰‰ritellyt teht‰v‰t vain autentikoiduille k‰ytt‰jille.</li><li><B> N‰yt‰ yll‰pit‰jille:</B> N‰ytt‰‰ m‰‰ritellyt teht‰v‰t vain yll‰pit‰jille.</li><li><B> Piilota myˆs yll‰pit‰jilt‰:</B> Kukaan ei n‰e m‰‰riteltyj‰ teht‰vi‰.</li></ul>";

//-- RA_VIEWTASK MESSAGES
$pgv_lang["view_task"]						= "N‰yt‰ teht‰v‰";
//$pgv_lang["comments"]						= "Kommentit";
$pgv_lang["add_new_comment"]				= "Lis‰‰ uusi kommentti";
$pgv_lang["no_sources"]						= "T‰h‰n teht‰v‰‰n ei ole liitetty l‰hteit‰.";
$pgv_lang["no_people"]						= "T‰h‰n teht‰v‰‰n ei ole liitetty henkilˆit‰.";
$pgv_lang["no_indi_tasks"]					= "T‰h‰n teht‰v‰‰n ei ole liitetty yht‰‰n henkilˆ‰.";
$pgv_lang["edit_comment"]					= "Editoi kommenttia";
$pgv_lang["comment_success"]				= "Kommenttisi on lis‰tty.";
$pgv_lang['comment_body']					= 'Kommentti';

//-- RA_COMMENT MESSAGES
$pgv_lang["comment_delete_check"]			= "Haluatko varmasti poistaa t‰m‰n kommentin?";

//-- RA_ADDTASK MESSAGES
$pgv_lang["add_new_task"]					= "Lis‰‰ uusi teht‰v‰";
//$pgv_lang["title"]						= "Otsikko";
$pgv_lang["submit"]							= "L‰het‰";

//-- RA_EDITTASK MESSAGES
//$pgv_lang["edit_task"]					= "Editoi teht‰v‰‰";

//-- RA_CONFIGURE PRIVACY MESSAGES
$pgv_lang["configure_privacy"]		    	= "Konfiguroi yksityisyysasetuksia";
$pgv_lang["show_my_tasks"]         		    = "N‰yt‰ teht‰v‰ni";
$pgv_lang["show_add_task"]		        	= "N‰yt‰ Lis‰‰ teht‰v‰";
$pgv_lang["show_auto_gen_task"]         	= "N‰yt‰ Luo teht‰v‰ automaattisesti";
$pgv_lang["show_view_folders"]		    	= "N‰yt‰ N‰yt‰ hakemisto";
$pgv_lang["show_add_folder"]		    	= "N‰yt‰ Lis‰‰ hakemisto";
$pgv_lang["show_add_unlinked_source"]   	= "N‰yt‰ Lis‰‰ linkitt‰m‰tˆn l‰hde";
$pgv_lang["show_view_probabilities"]		= "N‰yt‰ N‰yt‰ todenn‰kˆisyydet";




//-- COMMENT HELP
$pgv_lang["comment_title_help"]				= "Comment Title Help here.";
$pgv_lang["comment_help"]					= "N‰p‰yt‰ t‰st‰ saadaksesi ohjeita.";

//-- Census Forms
$pgv_lang["rows"]                       	= "Rivien lukum‰‰r‰";
$pgv_lang["state"]                      	= "Tila";
$pgv_lang["call/url"]                   	= "Soita numeroon/URL:‰‰n";
$pgv_lang["enumDate"]                   	= "Numerointip‰iv‰ys";
$pgv_lang["county"]                     	= "Maa";
$pgv_lang["city"]                       	= "Kaupunki";
//$pgv_lang["page"]                       	= "Sivu";
?>
