<?php
// British English definitions file for PhpGedView.
// Based on differences from US English 11 Jan 2010.
//
// Copyright (C) 2010 Greg Roach.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// $Id$

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$pgv_lang["add_facts_general_help"]="~General info about adding~<br />When you have added a fact, note, source, or multimedia file to a record in the database, the addition still has to be approved by a user who has Accept rights.<br /><br />Until the changes have been Accepted, they are identified as \"pending\" by a differently coloured border.  All users with Edit rights can see these changes as well as the original information.  Users who do not have Edit rights will only see the original information. When the addition has been Accepted, the borders will disappear and the new data will display normally, replacing the old.  At that time, users without Edit rights will see the new data too.<br /><br />";
$pgv_lang["add_gedcom_help"]="~#pgv_lang[add_gedcom]#~<br /><br />When you use the <b>#pgv_lang[add_gedcom]#</b> function, it is assumed that you have already uploaded the GEDCOM file to your server using a program or method <u>external</u> to PhpGedView, for example, <i>ftp</i> or <i>network connection</i>.  The file you wish to add could also have been left over from a previous <b>#pgv_lang[upload_gedcom]#</b> procedure.<br /><br />If the input GEDCOM file is not yet on your server, you <u>have to</u> get it there first, before you can start with Adding.<br /><br />Instead of uploading a GEDCOM file, you can also upload a ZIP file containing the GEDCOM file, either with PhpGedView, or using an external program. PhpGedView will recognise the ZIP file automatically and will extract the GEDCOM file and filename from the ZIP file.<br /><br />If a GEDCOM file with the same name already exists in PhpGedView, it will be overwritten. However, all GEDCOM settings made previously will be preserved.<br /><br />You are guided step by step through the procedure.<br /><br />";
$pgv_lang["add_media_help"]="~#pgv_lang[add_media]#~<br /><br />Adding multimedia files (MM) to the GEDCOM is a very nice feature.  Although this program already has a great look without media, if you add pictures or other MM to your relatives, it will only get better.<br /><br /><b>What you should understand about MM.</b><br />There are many formats of MM. Although PhpGedView can handle most of them, there some things to consider.<br /><ul><li><b>Formats</b><br />Pictures can be edited and saved in many formats.  For example, .jpg, .png, .bmp, .gif, etc.  If the same original picture was used to create each of the formats, the viewed image will appear to be the same size no matter which format is used.  However, the image files stored in the database will vary considerably in size.  Generally, .jpg images are considered to the most efficient in terms of storage space.</li><li><b>Image size</b><br />The larger the original image, the larger will be the resultant file's size. The picture should fit on the screen without scrolling; the maximum width or height should not be more than the width or height of the screen. PhpGedView is designed for screens of 1024x768 pixels but not all of this space is available for viewing pictures; the picture's size should be set accordingly.  To reduce file sizes, smaller pictures are more desirable.</li><li><b>Resolution</b><br />The resolution of a picture is usually measured in \"dpi\" (dots/inch), but this is valid only for printed pictures.  When considering pictures shown on screen, the only correct way is to use total dots or pixels. When printed, the picture could have a resolution of 150 - 300 dpi or more depending on the printer. Screen resolutions are rarely better than 50 pixels per inch.  If your picture will never be printed, you can safely lower its resolution (and consequently its file size) without affecting picture quality.  If a low-resolution picture is printed with too great a magnification, its quality will suffer; it will have a grainy appearance.</li><li><b>Color depth</b><br />Another way to keep a file small is to decrease the number of colours that you use.  The number of colours can differ from pure black and white (two colours) to true colours (millions of colours) and anything in between.  You can see that the more colours are used, the bigger the size of the files.</li></ul><b>Why is it important to keep the file size small?</b><br /><ul><li>First of all: Our webspace is limited.  The more large files there are, the more web space we need on the server. The more space we need, the higher our costs.</li><li>Bandwidth.  The more data our server has to send to the remote location (your location), the more we have to pay.  This is because the carrying capacity of the server's connection to the Internet is limited, and the link has to be shared (and paid for) by all of the applications running on the server.  PhpGedView is one of many applications that share the server.  The cost is normally apportioned according to the amount of data each application sends and receives.</li><li>Download time. If you have large files, the user (also you) will have to wait long for the page to download from the server.  Not everybody is blessed with a cable connection, broadband or DSL.</li></ul><b>How to upload your MM</b><br />There are two ways to upload media to the site.  If you have a lot of media items to upload you should contact the site administrator to discuss the best ways.  If it has been enabled by your site administrator, you can use the Upload Media form under your #pgv_lang[mgv]# menu.  You can also use the Upload option on the Multimedia form to upload media items.<br /><br />";
$pgv_lang["alpha_help"]="~ALPHABETICAL INDEX~<br /><br />Clicking a letter in the Alphabetical index will display a list of the names that start with the letter you clicked.<br /><br />The second to last item in the Alphabetical index can be <b>#pgv_lang[NN]#</b>.  This entry will be present when there are people in the database whose surname has not been recorded or does not contain any recognizable letters.  Unknown surnames are often recorded as <b>?</b>, and these will be recognised as <b>#pgv_lang[NN]#</b>.  This will also happen if the person is unknown.<br /><br /><b>Note:</b><br />Surnames entered as, for example, <b>Nn</b>, <b>NN</b>, <b>Unknown</b>, or even <b>N.N.</b> will <u>not</u> be found in the <b>#pgv_lang[NN]#</b> list. Instead, you will find these persons by clicking <b>N</b> or <b>U</b> because these are the initial letters of those names.  PhpGedView cannot possibly account for all possible ways of entering unknown surnames;  there is no recognised convention for this.<br /><br />At the end of the Alphabetical index you see <b>ALL</b>. When you click on this item, you will see a list of all surnames in the database.<br /><br /><b>Missing letters?</b><br />If your Alphabetical index appears to be incomplete, with missing letters, your database doesn't contain any surnames that start with that missing letter.<br /><br />";
$pgv_lang["apply_privacy_help"]="~#pgv_lang[apply_privacy]#~<br /><br />When this option is checked, the output file will pass through privacy checks according to the selected option.  This can result in the removal of certain information.  The output file will contain only the information that is normally visible to a user with the indicated privilege level.<br /><br />If you only have #pgv_lang[gedadmin]# rights, you cannot specify that the output file should be privatised according to the #pgv_lang[siteadmin]# privilege level.<br /><br />";
$pgv_lang["block_default_index"]="~Default blocks on #pgv_lang[welcome]# page~<br /><br />When you remove all entries from the #pgv_lang[main_section]# and #pgv_lang[right_section]# lists, or when you click the <b>#pgv_lang[reset_default_blocks]#</b> button, the block list will be set as follows:<br /><br /><center><table border=\"1\"><tr><td class=\"list_value\"><b>#pgv_lang[main_section]#</b></td><td class=\"list_value\"><b>#pgv_lang[right_section]#</b></td></tr><tr><td>#pgv_lang[gedcom_stats_block]#<br />#pgv_lang[gedcom_news_block]#<br />#pgv_lang[gedcom_favourites_block]#<br />#pgv_lang[review_changes_block]#</td><td>#pgv_lang[gedcom_block]#<br />#pgv_lang[random_media_block]#<br />#pgv_lang[todays_events_block]#<br />#pgv_lang[logged_in_users_block]#</td></tr></table></center><br />";
$pgv_lang["block_default_portal"]="~Default blocks on #pgv_lang[mygedview]# page~<br /><br />When you remove all entries from the #pgv_lang[main_section]# and #pgv_lang[right_section]# lists, or when you click the <b>#pgv_lang[reset_default_blocks]#</b> button, the block list will be set as follows:<br /><br /><center><table border=\"1\"><tr><td class=\"list_value\"><b>#pgv_lang[main_section]#</b></td><td class=\"list_value\"><b>#pgv_lang[right_section]#</b></td></tr><tr><td>#pgv_lang[todays_events_block]#<br />#pgv_lang[user_messages_block]#<br />#pgv_lang[user_favourites_block]#<br />&nbsp;</td><td>#pgv_lang[welcome_block]#<br />#pgv_lang[random_media_block]#<br />#pgv_lang[upcoming_events_block]#<br />#pgv_lang[logged_in_users_block]#</td></tr></table></center><br />";
$pgv_lang["cache_life_help"]="~#pgv_lang[cache_life]#~<br /><br />To improve performance, this PhpGedCom Welcome Page block is saved as a cache file.  You can control how often this block's cache file is refreshed.<br /><br /><ul><li><b>-1</b> means that the cache file is never refreshed automatically.  To get a fresh copy, you need to delete all cache files.  You can do this on the Customise Welcome Page page.</li><li><b>0</b> (Zero) means that this block is never cached, and every time the block is displayed on the PhpGedView Welcome page, you see a fresh copy.  This setting is used automatically for blocks that change frequently, such as the #pgv_lang[logged_in_users_block]# and the #pgv_lang[random_media_block]# blocks.</li><li><b>1</b> (One) means that a fresh copy of this block's cache file is created daily, <b>2</b> means that a fresh copy is created every two days, <b>7</b> means that a fresh copy is created weekly, etc.</li></ul><br /><br />";
$pgv_lang["def_gedcom_help"]="~GEDCOM file~<br />A quote from the Introduction to the GEDCOM 5.5.1 Standard:<div class=\"list_value_wrap\">GEDCOM was developed by the Family History Department of The Church of Jesus Christ of Latter-day Saints (LDS Church) to provide a flexible, uniform format for exchanging computerised genealogical data.&nbsp; GEDCOM is an acronym for <i><b>GE</b></i>nealogical <i><b>D</b></i>ata <i><b>Com</b></i>munication.&nbsp; Its purpose is to foster the sharing of genealogical information and the development of a wide range of inter-operable software products to assist genealogists, historians, and other researchers.</div><br />A copy of the GEDCOM 5.5.1 <u>draft</u> Standard, to which PhpGedView adheres, can be downloaded in PDF format here:&nbsp; <a href=\"http://www.phpgedview.net/ged551-5.pdf\" target=\"_blank\">GEDCOM 5.5.1 Standard</a>  This Standard is only available in English.<br /><br />The GEDCOM file contains all the information about the family. All facts, dates, events, etc. are stored here. GEDCOM files have to follow strict rules because they must be exchangeable between many programs, independent of platforms or operating systems.<br /><br />";
$pgv_lang["def_pgv_help"]="~PhpGedView~<br />PhpGedView (or PGV) does not just put static pages on the Web; it is dynamic and can be customised in many ways.<br /><br />PhpGedView was created by John Finlay to view GEDCOM files online.  John started developing the program on his own.  An international team of developers and translators has since joined him and is working to improve the program.  Among the more significant features that have been added or improved in the program are its extensive support of languages other than English, and the ability to add and edit events online.<br /><br />";
$pgv_lang["edit_ROMN_help"]="~ROMANIZED NAME~<br /><br />In many cultures it is customary to have a traditional name spelled in the traditional characters and also a romanised version of the name as it would be spelled or pronounced in languages based on the Latin alphabet, such as English.<br /><br />If you prefer to use a non-Latin alphabet such as Hebrew, Greek, Russian, Chinese, or Arabic to enter the name in the standard name fields, then you can use this field to enter the same name using the Latin alphabet.  Both versions of the name will appear in lists and charts.<br /><br />Although this field is labelled \"Romanised\", it is not restricted to containing only characters based on the Latin alphabet.  This might be of use with Japanese names, where three different alphabets may occur.";
$pgv_lang["edit__HEB_help"]="~HEBREW NAME~<br /><br />In many cultures it is customary to have a traditional name spelled in the traditional characters and also a romanised version of the name as it would be spelled or pronounced in languages based on the Latin alphabet, such as English.<br /><br />If you prefer to use the Latin alphabet to enter the name in the standard name fields, then you can use this field to enter the same name in the non-Latin alphabet such as Greek, Hebrew, Russian, Arabic, or Chinese.  Both versions of the name will appear in lists and charts.<br /><br />Although this field is labelled \"Hebrew\", it is not restricted to containing only Hebrew characters.";
$pgv_lang["edit_gedcoms_help"]="~#pgv_lang[gedcom_adm_head]#~<br /><br />The #pgv_lang[gedcom_adm_head]# page is the control centre for administering all of your genealogical databases.<br /><br /><b>#pgv_lang[current_gedcoms]#</b><br />At the head of the <b>#pgv_lang[current_gedcoms]#</b> table, you see an action bar with four links.<ul><li>#pgv_lang[add_gedcom]#</li><li>#pgv_lang[upload_gedcom]#</li><li>#pgv_lang[add_new_gedcom]#</li><li>#pgv_lang[lang_back_admin]#</li></ul>In the <b>#pgv_lang[current_gedcoms]#</b> table each genealogical database is listed separately, and you have the following options for each of them:<ul><li>Import</li><li>Delete</li><li>Download</li><li>Edit configuration</li><li>Edit privacy</li><li>SearchLog files</li></ul>Edit privacy appears here because every GEDCOM has its own privacy file.<br /><br />Each line in this table should be self-explanatory.  PhpGedView can be configured to log all database searches.  The SearchLog files can be inspected through links found on this page.<br />#pgv_lang[more_help]#<br />";
$pgv_lang["edit_sex_help"]="~#pgv_lang[sex]#~<br /><br />Choose the appropriate sex from the drop-down list.  The <b>unknown</b> option indicates that the sex is unknown.<br /><br />";
$pgv_lang["header_favorites_help"]="~Header Area: Favourites~<br />The Favourites drop-down list shows the favourites that you have selected on your personalised Portal page.  It also shows the favourites that the site administrator has selected for the currently active GEDCOM.  Clicking on one of the favourites entries will take you directly to the #pgv_lang[indi_info]# page of that person.<br /><br />More help about adding Favourites is available in your personalised Portal page.<br /><br />";
$pgv_lang["header_help"]="<div class=\"name_head\"><center><b>HEADER AREA</b></center></div><br />The header is shown at the top of every page.  The header contains some useful links that you can use throughout the site.<br /><br />Since this site can have a different look depending on the selected <a href=\"#def_theme\">theme</a>, headers can be affected and links may vary.<br /><br />The links that you might find are:<ul><li><a href=\"#header_search\"><b>Search Box</b></a></li><li><a href=\"#header_lang_select\"><b>Language Selector</b></a></li><li><a href=\"#header_user_links\"><b>User Links</b></a></li><li><a href=\"#header_favourites\"><b>Favourites</b></a></li><li><a href=\"#header_theme\"><b>Change Theme</b></a></li></ul>";
$pgv_lang["help_edit_merge.php"]="~#pgv_lang[merge_records]#~<br /><br />This page will allow you to merge two GEDCOM records from the same GEDCOM file.<br /><br />This is useful for people who have merged GEDCOMs and now have many people, families, and sources that are the same.<br /><br />The page consists of three steps.<br /><ol><li>You enter two GEDCOM IDs.  The IDs <u>must</u> be of the same type.  You cannot merge an individual and a family or family and source, for example.<br />In the <b>#pgv_lang[merge_to]#</b> field enter the ID of the record you want to be the new record after the merge is complete.<br />In the <b>#pgv_lang[merge_from]#</b> field enter the ID of the record whose information will be merged into the #pgv_lang[merge_to]# record.  This record will be deleted after the Merge.</li><li>You select what facts you want to keep from the two records when they are merged.  Just click the checkboxes next to the ones you want to keep.</li><li>You inspect the results of the merge, just like with all other changes made online.</li></ol>Someone with Accept rights will have to authorise your changes to make them permanent.<br />";
$pgv_lang["help_familybook.php"]="~#pgv_lang[familybook_chart]#~<br /><br />This chart is very similar to the Hourglass chart.  It will show the ancestors and descendants of the selected root person on the same chart.  It will also show the descendants of the root person in the same Hourglass format.<br /><br />The root person is centred in the middle of the page with his descendants listed to the left and his ancestors listed to the right.  In this view, each generation is lined up across the page starting with the earliest generation and ending with the latest.<br /><br />Each descendant of the root person will become the root person of an additional hourglass chart, printed on the same page.  This process repeats until the specified number of descendant generations have been printed.";
$pgv_lang["help_fanchart.php"]="~#pgv_lang[fan_chart]# page~<br /><br />The Circle Diagram is very similar to the <a href=\"?help=help_pedigree.php\">#pgv_lang[index_header]#</a>, but in a more graphical way.<br /><br />The Root person is shown in the centre, his parents on the first ring, grandparents on the second ring, and so on.<br /><br />Years of birth and death are printed under the name when known.<br /><br />Clicking on a name on the chart will open a links menu specific to that person.  From this menu you can choose to centre the diagram on that person or on one of that person's close relatives, or you can jump to that person's #pgv_lang[indi_info]# page or a different chart for that person.<br /><br />";
$pgv_lang["help_hourglass.php"]="~#pgv_lang[hourglass_chart]#~<br /><br />The Hourglass chart will show the ancestors and descendants of the selected root person on the same chart.  This chart is a mix between the Descendancy chart and the Pedigree chart.<br /><br />The root person is centred in the middle of the page with his descendants listed to the left and his ancestors listed to the right.  In this view, each generation is lined up across the page starting with the earliest generation and ending with the latest.<br /><br />If there is a downwards arrow on the screen under the root person, clicking on it will display a list of the root person's close family members that you can use the navigate down the chart.  Selecting a name from this list will reload the chart with the selected person as the new root person.";
$pgv_lang["help_manual_search_engines"]="~Manual Search Engine Spider Marking~<br /><br />PhpGedView automatically provides search engines with smaller data files with fewer links.  The data is limited to the individual and immediate family, without adding information about grand parents or grand children.  Many reports and server-intensive pages like the calendar are off limits to the spiders.<br /><br />If a search engine is not automatically recognised and you wish to provide it data to index, you can list it here.  If you do not want to provide it data, you list it below in the banned IP section.<br /><br />If you wish to see what data is provided to search engines to index, list your own IP address here.  <font color=\"red\">WARNING:</font> This will lock you out of the admin interface, and you must remove the IP by logging in from a different machine or manually editing the <i>/index/search_engines.php</i> file.<br /><br />To manually mark a remote site as a search engine spider, provide a specific IP address or a valid IP address range, for example, 212.10.*.* and click the Submit button.  Many popular search engines like Google and Yahoo will be detected automatically.";
$pgv_lang["help_timeline.php"]="~TIMELINE CHART~<br /><br />On this chart you can display one or more persons along a timeline.  You can, for example, visualise the status of two or more persons at a certain moment.<br /><br />If you click the <b>Time Line</b> link on an other page you will already see one person on the Time Line.  If you clicked the <b>Time Line</b> menu item in a page header, you have to supply the starting person's ID.<br /><br />";
$pgv_lang["index_add_favorites_help"]="~ADD A FAVOURITE~<br />This form allows you to add a new favourite item to your list of favourites.<br /><br />You must enter either an ID for the person, family, or source you want to store as a favourite, or you must enter a URL and a title.  The Note field is optional and can be used to describe the favourite.  Anything entered in the Note field will be displayed in the Favourites block after the item.<br /><br />";
$pgv_lang["index_charts_help"]="~#pgv_lang[charts_block]#~<br />This block allows a pedigree, descendancy, or hourglass chart to appear on the Welcome or the MyGedView page.  Because of space limitations, the charts should be placed only on the left side of the page.<br /><br />When this block appears on the Welcome page, the root person and the type of chart to be displayed are determined by the administrator.  When this block appears on the user's personalised MyGedView page, these options are determined by the user.<br /><br />The behaviour of these charts is identical to their behaviour when they are called up from the menus.  Click on the box of a person to see more details about them.<br /><br />";
$pgv_lang["index_favorites_help"]="~GEDCOM FAVOURITES BLOCK~<br />The GEDCOM Favourites block is much the same as the \"My Favourites\" block of the #pgv_lang[mygedview]# page. Unlike the Portal page configuration, only the administrator or a user with Admin rights can change the list of favourites in this block.<br /><br />The purpose of the GEDCOM Favourites block is to draw the visitor's attention to persons of special interest.  This GEDCOM's favourites are available for selection from a drop-down list in the header on every page.<br /><br />When you click on one of the listed site favourites, you will be taken to the #pgv_lang[indi_info]# page of that person.<br /><br />";
$pgv_lang["index_htmlplus_compat_help"]="~#pgv_lang[htmlplus_block_compat]#~<br />Enable compatibility with older versions of this block.  When checked, both old and new keywords will be recognised and acted upon.<br /><br />For example, the text <b>&#35;TOTAL_FAM&#35;</b> will be recognised as being equivalent to <b>&#35;totalFamilies&#35;</b>, <b>&#35;FIRST_DEATH_PLACE&#35;</b> to <b>&#35;firstDeathPlace&#35;</b>, <b>&#35;TOP10_BIGFAM&#35;</b> to <b>&#35;topTenLargestFamily&#35;</b>, etc.<br /><br />Unless absolutely necessary, you should not use Compatibility mode.<br /><br />";
$pgv_lang["index_portal_help"]="The Welcome page consists of several separate blocks, and can be customised. On sites that have more than one genealogical database, you may see a different Welcome page for each.  Depending on how the administrator customised the site, you may see any of the following blocks on the Welcome page:<ul><li><a href=\"#index_welcome\"><b>Welcome</b></a></li><li><a href=\"#index_login\"><b>Login</b></a></li><li><a href=\"#index_events\"><b>Upcoming events</b></a></li><li><a href=\"#index_onthisday\"><b>On this Day in Your History</b></a></li><li><a href=\"#index_charts\"><b>Charts</b></a></li><li><a href=\"#index_favorites\"><b>GEDCOM Favorites</b></a></li><li><a href=\"#index_stats\"><b>GEDCOM Statistics</b></a></li><li><a href=\"#index_common_surnames\"><b>Most Common Surnames</b></a></li><li><a href=\"#index_media\"><b>Random Media</b></a></li><li><a href=\"#index_loggedin\"><b>Logged in Users</b></a></li><li><a href=\"#gedcom_news\"><b>GEDCOM News</b></a></li><li><a href=\"#recent_changes\"><b>Recent Changes</b></a></li></ul><br />";
$pgv_lang["login_buttons_aut_help"]="~AUTHENTICATION MODE LOGIN BUTTONS~<br /><br />Here you see two buttons to login to the system.<br /><br />The page you will be taken to depends on which button you click after typing your user name and password.<br /><ul><li>The <b>#pgv_lang[login]#</b> button<br />If you click this button, you will be logged in and go directly to your #pgv_lang[mygedview]# page, where you can edit your settings, add or edit favourites, send and read messages, etc.</li><li>The <b>#pgv_lang[admin]#</b> button<br />If you have Admin rights, you can click this button to go directly to the main Administration page.</li></ul><br />";
$pgv_lang["menu_famtree_help"]="~Welcome Page menu~<br />All of this site's available genealogical databases are listed in this menu. Each database has its own customised Welcome page, like this one.  If there is only one database at this site, there is no sub-menu under the Welcome Page icon.<br /><br />";
$pgv_lang["mygedview_customize_help"]="~CUSTOMIZE #pgv_lang[mygedview]#~<br />When you entered here for the first time, you already had some blocks on this page.  If you like, you can customise this Portal page.<br /><br />When you click this link you will be taken to a form where you can add, move, or delete blocks.  More explanation is available on that form.<br /><br />";
$pgv_lang["mygedview_favorites_help"]="~MY FAVOURITES BLOCK~<br />Favourites are similar to bookmarks.<br /><br />Suppose you have somebody in the family tree whose record you want to check regularly.  Just go to the person's #pgv_lang[indi_info]# page and select the <b>Add to My Favourites</b> option from the Favourites drop-down list. This person is now book marked and added to your list of favourites.<br /><br />Wherever you are on this site, you can click on a name in the \"My Favourites\" drop-down list in the header.  This will take you to the #pgv_lang[indi_info]# page of that person.<br /><br />";
$pgv_lang["mygedview_login_help"]="In order to access the #pgv_lang[mygedview]# page, you must be a registered user on the system.  On the #pgv_lang[mygedview]# page you can bookmark your favorite people, keep a user journal, manage messages, see other logged in users, and customise various aspects of PhpGedView pages.<br /><br />Enter your User name and Password in the appropriate fields to login to #pgv_lang[mgv]#.<br /><br />";
$pgv_lang["mygedview_portal_help"]="~#pgv_lang[mygedview]#~<br />This is your Personal #pgv_lang[mgv]# page.<br /><br />Here you will find easy links to access your personal data such as <b>My Account</b>, <b>My Indi</b> (this is your #pgv_lang[indi_info]# page), and <b>My Pedigree</b>.  You can have blocks with <b>Messages</b>, a <b>Journal</b> (like a Notepad) and many more.<br /><br />The layout of this page is similar to the Welcome page that you see when you first access this site.  While the parts of the Welcome page are selected by the site administrator, you can select what parts to include on this personalised page.  You will find the link to customise this page in the Welcome block or separately when the Welcome block is not present.<br /><br />You can choose from the following blocks:<ul><li><a href=\"#mygedview_welcome\"><b>Welcome</b></a></li><li><a href=\"#mygedview_customize\"><b>Customise MyGedView</b></a></li><li><a href=\"#mygedview_message\"><b>Messages</b></a></li><li><a href=\"#mygedview_events\"><b>Upcoming events</b></a></li><li><a href=\"#mygedview_onthisday\"><b>On this Day in Your History</b></a></li><li><a href=\"#mygedview_charts\"><b>Charts</b></a></li><li><a href=\"#mygedview_favorites\"><b>My Favourites</b></a></li><li><a href=\"#mygedview_stats\"><b>GEDCOM Statistics</b></a></li><li><a href=\"#mygedview_myjournal\"><b>My Journal</b></a></li><li><a href=\"#mygedview_media\"><b>Random Media</b></a></li><li><a href=\"#mygedview_loggedin\"><b>Logged In Users</b></a></li><li><a href=\"#mygedview_recent_changes\"><b>Recent Changes</b></a></li></ul><br />";
$pgv_lang["new_dir_help"]="~#pgv_lang[add_directory]#~<br /><br />As an admin user you can create the directory structure you require to keep your media files organised. Creating directories from this page ensures that the thumbnail directories are created as well as creating a suitable index.php in each directory.<br /><br />Click on this link to enter the name of the directory you wish to create.<br /><br />";
$pgv_lang["register_info_02"]="~REQUEST NEW USER ACCOUNT~<br /><br />The amount of data that can be publicly viewed on this website may be limited due to applicable law concerning privacy protection. Many people do not want their personal data publicly available on the Internet. Personal data could be misused for spam or identity theft.<br /><br />Access to this site is permitted to <u>authorised</u> users only. After the administrator has verified and approved your account application, you will be able to login and view the private data.<br /><br />If Relationship Privacy has been activated you will only be able to access your own close relatives' private information after logging in. The administrator can also allow database editing for certain users, so that they can change or add information.<br /><br />If you need any further support, please use the link below to contact the administrator.<br /><br />";
$pgv_lang["rss_feed_help"]="~RSS FEED SETTINGS~<br /><br />The ATOM/RSS feed available in PhpGedView allows anyone to view, using a suitable feed aggregator, the contents of your site's Welcome page without visiting the site. Most aggregators will pop up a notice letting the user know when something has changed on a page being monitored. This essentially allows anyone to monitor your PhpGedView site without needing to visit it regularly.<br /><br />The Feed block is used to customise the link to the feed, allowing specific feed types (most readers can deal with most types so this can usually be left at the default), and the specific module you would like in your feed. The language of the feed and the GEDCOM used will be based on the language and GEDCOM active in PhpGedView when you select the feed.<br /><br />The types of feed that can be generated include ATOM, RSS 2.0, RSS 1.0, RSS 0.92, HTML and JavaScript. The first four types are for feed aggregators, while JavaScript and HTML are meant to enable inclusion of the feeds in other web pages.  Note that the numbers of the RSS feed indicate different styles, not a different version.<br /><br />There is an option to select authentication that will log the user in, and allow the user to view, using a suitable RSS aggregator, any information that he could normally view if logged in. Basic Authentication uses <i>Basic HTTP Authentication</i> to log the user in. Future enhancements might allow <i>Digest Authentication</i>.<br /><br />This <a href='http://en.wikipedia.org/wiki/RSS_(file_format)' target='_blank' alt='Wikipedia article' title='Wikipedia article'><b>Wikipedia article</b></a> contains comprehensive information and links about RSS and the various RSS formats. <i>Basic HTTP Authentication</i> is discussed in this <a href='http://en.wikipedia.org/wiki/Basic_authentication_scheme' target='_blank' alt='Wikipedia article' title='Wikipedia article'><b>Wikipedia article</b></a>, while <i>Digest Authentication</i> is discussed in this <a http://en.wikipedia.org/wiki/Digest_access_authentication' target='_blank' alt='Wikipedia article' title='Wikipedia article'><b>Wikipedia article</b></a>.<br /><br />";
$pgv_lang["talloffset_help"]="~PAGE LAYOUT~<br /><br />With this option you determine the page layout orientation.<br /><br />Changing this setting might be useful if you want to make a screen print or if you have a different type of screen.<ul><li><b>#pgv_lang[portrait]#</b> mode will make the tree taller, such that a 4 generation chart should fit on a single page printed vertically.</li><li><b>#pgv_lang[landscape]#</b> mode will make a wider tree that should print on a single page printed horizontally.</li><li><b>#pgv_lang[landscape_top]#</b> mode rotates the chart, but not its boxes, by 90 degrees anticlockwise, so that the oldest generation is at the top of the chart.</li><li><b>#pgv_lang[landscape_down]#</b> mode rotates the chart, but not its boxes, by 90 degrees clockwise, so that the oldest generation is at the bottom of the chart.</li></ul<br />";
$pgv_lang["upload_gedcom_help"]="~#pgv_lang[upload_gedcom]#~<br /><br />Unlike the <b>#pgv_lang[add_gedcom]#</b> function, the GEDCOM file you wish to add to your database does not have to be on your server.<br /><br />In Step 1 you select a GEDCOM file from your local computer. Type the complete path and file name in the text box or use the <b>Browse</b> button on the page.<br /><br />You can also use this function to upload a ZIP file containing the GEDCOM file. PhpGedView will recognise the ZIP file and extract the file and the filename automatically.<br /><br />If a GEDCOM file with the same name already exists in PhpGedView, it will, after your confirmation, be overwritten. However, all GEDCOM settings made previously will be preserved.<br /><br />You will find more help on other pages of the procedure.<br /><br />";
$pgv_lang["upload_media_folder_help"]="~#pgv_lang[folder]#~<br /><br />Your GEDCOM configuration allows up to #GLOBALS[MEDIA_DIRECTORY_LEVELS]# directory levels beyond the default <b>#GLOBALS[MEDIA_DIRECTORY]#</b> where uploaded media files are normally stored. This lets you organise your media files, and you don't need to be as concerned about maintaining unique names for each media file.<br /><br />In this field you specify the destination directory on your server where the uploaded media file is to be stored.  Be sure to pay attention to the case (upper or lower case) of what you enter or select here, since file and directory names are case sensitive.<br /><br />If the directory name you enter here does not exist, it will be created automatically. If you enter more than the additional #GLOBALS[MEDIA_DIRECTORY_LEVELS]# directory levels permitted by your GEDCOM configuration, your input will be truncated accordingly.<br /><br />Thumbnails will be uploaded or created in an identical directory structure, starting with <b>#GLOBALS[MEDIA_DIRECTORY]#thumbs/</b>.<br /><br />";
$pgv_lang["upload_server_folder_help"]="~#pgv_lang[server_folder]#~<br /><br />The administrator has enabled up to #GLOBALS[MEDIA_DIRECTORY_LEVELS]# folder levels below the default <b>#GLOBALS[MEDIA_DIRECTORY]#</b>.  This helps to organise the media files and reduces the possibility of name collisions.<br /><br />In this field, you specify the destination folder where the uploaded media file should be stored.  The matching thumbnail file, either uploaded separately or generated automatically, will be stored in a similar folder structure starting at <b>#GLOBALS[MEDIA_DIRECTORY]#thumbs/</b> instead of <b>#GLOBALS[MEDIA_DIRECTORY]#</b>.  You do not need to enter the <b>#GLOBALS[MEDIA_DIRECTORY]#</b> part of the destination folder name.<br /><br />If you are not sure about what to enter here, you should contact your site administrator for advice.<br /><br />";
$pgv_lang["view_server_folder_help"]="~#pgv_lang[server_folder]#~<br /><br />The administrator has enabled up to #GLOBALS[MEDIA_DIRECTORY_LEVELS]# folder levels below the default <b>#GLOBALS[MEDIA_DIRECTORY]#</b>.  This helps to organise the media files and reduces the possibility of name collisions.<br /><br />In this field, you select the media folder whose contents you wish to view.  When you select <b>#pgv_lang[all]#</b>, all media files will be shown without regard to the folder in which they are stored.  This can produce a very long list of media items.<br /><br />";
