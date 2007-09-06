<?php
/**
 * English FAQ texts
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2007  PGV Development Team
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
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}

$faqlist["FAQ_000_head"] = "\"FAQ\": I'VE HEARD OF THIS, BUT WHAT IS IT?";
$faqlist["FAQ_000_body"] = "<b>FAQ</b> is an acronym for <b>F</b>requently <b>A</b>sked <b>Q</b>uestion.<br /><br />The FAQ list is a list of questions (together with their answers) that occur frequently.  It has been compiled by the PhpGedView team, and is updated frequently.";

$faqlist["FAQ_010_head"] = "WELCOME TO THE #GLOBALS[GEDCOM_TITLE]# FAQ";
$faqlist["FAQ_010_body"] = "The family members at #GLOBALS[GEDCOM_TITLE]# would like to take this opportunity to welcome all our \'cousins\' in genealogy and encourage them to catch the bug of researching your ancestors. This can easily become a labor of love - and hate - as it consumes an inordinate amount of time, but the rewards are terrific. We offer you the opportunity to enjoy genealogy through the use of <a href=\"http://www.phpgedview.net\" target=\"_blank\">PhpGedView</a>, created with the talented programming skills of John Finlay and his PGV team - A wonderful open source genealogy program."; 

$faqlist["FAQ_015_head"] = "WHAT IS THE DIFFERENCE BETWEEN THIS PhpGedView TREE AND OTHER TEXTUAL AND DYNAMIC TREES?";
$faqlist["FAQ_015_body"] = "The textual and the dynamic trees, show the tree in different ways, but none of them can be configured, changed, or updated by you, only by the webmaster.<br />The PhpGedView is an interactive tree.<br />Anyone whose family is in this extended tree, can update, add and make changes to their close branches. (You must register first in order to make these changes.)"; 

$faqlist["FAQ_017_head"] = "WHAT ARE THE MAIN SPECIAL FEATURES OF THIS TREE";
$faqlist["FAQ_017_body"] = "With the PhpGedView you can:<ul><li>Keep privacy of living people, the webmaster determines who you can see.</li><li>Enjoy many possibilities to view the tree: as different charts, reports, or lists.</li><li>It's a collaborative tree: everyone can take part in updating the tree.</li>";

$faqlist["FAQ_020_head"] = "DO I NEED AN ACCOUNT FOR ACCESS? IF SO, HOW DO I APPLY FOR ONE?";
$faqlist["FAQ_020_body"] = "Should we say \"Welcome, cousin\"?<br /><br /><B>NOTE: #GLOBALS[GEDCOM_TITLE]# does NOT REQUIRE REGISTRATION to gain access to data on deceased kinfolk on our website. However, to contribute or to see facts on presumed living relatives, you will need to register and advise us of your relationship.</B>";
$faqlist["FAQ_020_body2"] = "Registered users see the names of all the site live individuals. They see detailed data of deceased individuals and of their close relatives.<br />Users who are not linked to any relatives see only names of live individuals and detailed data of deceased individuals.";
$faqlist["FAQ_020_body3"] = "<ol><li>Registrants should be relatives, albeit distant kin in many cases, however somehow related to someone already listed OR someone who should be listed on our site;</li> <li> Registrants should be prepared to regularly visit and contribute to our sites, at first providing us with your personal and immediate family information and later - modifications, augmentation, amplifications and additions to our existing data;</li><li> Registrants must pledge to protect the privacy of the data on all living persons on the site, and, as noted above, contribute their own personal information. Violations will lead to immediate termination of access privileges and may be cause for legal action. We take the possibility of identity theft or information abuse seriously. Please see our Privacy Section below for more details.</li> </ol> If you qualify for registration and agree to abide by these rather simply policies and procedures, please feel free to use the <b><a target=\"_blank\" href=\"/phpGedView/login_register.php?action=register\"> registration form built into the site</a></b>. Be sure to complete the small questionaire, explaining your relationship to relatives contained within the existing site and also clearly state your acknowledgement of your intentions to abide by our policies and access rules and we will review your application.";
$faqlist["FAQ_020_body4"] = "Approval of the new user-account must be done manually by the Webmaster.<br />Usually it will take between a few minutes to 24 hours.";

$faqlist["FAQ_022_head"] = "WHY DO I NEED TO REGISTER?";
$faqlist["FAQ_022_body"] = "Only registered users can see names of living people. As a NON-Registered user, you will only see \"private\" instead of living people's names.";

$faqlist["FAQ_025_head"] = "HOW LONG DOES IT TAKE TO HAVE MY REGISTRATION APPROVED?";
$faqlist["FAQ_025_body"] = "Approval must be done manually by the Webmaster.<br />Usually it might take between a few minutes to 24 hours.";

$faqlist["FAQ_027_head"] = "I HAVE REGISTERED AND BEEN APPROVED, I CAN SEE NAMES OF LIVING PEOPLE, BUT I CANNOT SEE ANY OF THEIR DETAILS?";
$faqlist["FAQ_027_body"] = "In order to see details (only of your close branches), you must be a part of the tree, and must notify this to the webmaster/genmaster by email.";

$faqlist["FAQ_030_head"] = "HOW DO I INPUT DATA?  WHAT FORMATS SHOULD I USE?";
$faqlist["FAQ_030_body"] = "Here are a few pointers";
$faqlist["FAQ_030_body2"] = " for users who are approved to EDIT online.";
$faqlist["FAQ_030_body3"] = "You may also send your updates by email.";
$faqlist["FAQ_030_HELP"] = "<strong>HELP</strong>: It is prolifically provided on the site, in the header and everywhere behind most links and terms with the \"?\" image. Otherwise, if you are still confused, simply ask us via an email.";
$faqlist["FAQ_030_DATES"] = "<strong>DATES</strong>: We use the Gedcom v5.5 standard format. DD MMM YYYY or 01 JAN 1822 instead of January 1, 1822 or Jan 1, 1822.  The system automatically makes some minor corrections to input errors using Java.";
$faqlist["FAQ_030_HDATES"] = "<strong>HEBREW DATES</strong> are filled in the format @#DHEBREW@ DD MMM YYYY or @#DHEBREW@ 21 AAV 5705 - The months are filled as TSH, CSH, KSL, TVT, SHV, ADR, ADS, NSN, IYR, SVN, TMZ, AAV and ELL as per the Gedcom v5.5 standards.";
$faqlist["FAQ_030_PLACES"] = "><strong>PLACES</strong>: We try, wherever known, to include the full place descriptive: city and/or township (Twp) as well as the County, State, and we usually add USA (preferred - not US, U.S., or U.S.A.) behind the state and for foreign countries, we use the use the GEDCOM-approved 3 letter international abbreviation rather than the country name: England [GBR], Ireland [IRE], France [FRA], Italy [ITA], etc. The format we prefer is: Indianapolis, Center Twp, Marion Co, Indiana, USA  All states are not abbreviated to the two letters and we generally do not use periods (.) in names or locations, like Shelbyville, Addison Twp, Shelby Co, Indiana, USA instead of Shelbyville, Addison Twsp., Shelby Co., IN or Shelbyville, Addison Township, Shelby County, Indiana, U.S.";
$faqlist["FAQ_030_PLACES2"] = "<strong>PLACES</strong>: We try, wherever known, to include for American places the full place descriptive: city or township (Twp) as well as the two letter State, and we add USA (preferred - not US, U.S., or U.S.A.) behind the state and for other countries, we use city and/or township and the country name. The formats we prefer are: Indianapolis, IN, USA and Vilnius, Lithuania.";
$faqlist["FAQ_030_PLACES3"] = "There are two helpful methods of acquiring a place's correction format:<br />a) use the tiny \"world\" icon adjacent to the place field to see what places are already entered in our database. This is a good way to look up a city's county if you don't know it as it probably already exists in our data. Use the filter to narrow your search and simply click on the desired result and it will transfer to the empty PLAC field, or<br />b) enter place is clicking on the sign of under the PLACE entry window. It will show a series of windows for country, state/province, county, city. Next to the country window is drop down list of countries with their international 3 letters designation.";
$faqlist["FAQ_030_PLACES4"] = "There are two helpful methods of acquiring a place's correction format:<br />a) use the tiny \"world\" icon adjacent to the place field to see what places are already entered in our database. Use the filter to narrow your search and simply click on the desired result and it will transfer to the empty PLAC field, or<br />b) enter place is clicking on the sign of under the PLACE entry window. It will show a series of windows for country, state/province, county (N/A), city. Next to the country window is drop down list of countries with their international 3 letters designation that we do not use.";
$faqlist["FAQ_030_NAMES"] = "<strong>NAMES</strong>: Entering of names are pretty straight forward via the form and help is provided. The INDI ENTRY BOX should already have expanded name fields. If not, both it and the places box expand by clicking the + sign.";
$faqlist["FAQ_030_PREFIX"] = "Name <u>PREFIXes</u> are Dr, Rabbi, Hon, Judge, etc.";
$faqlist["FAQ_030_GIVN"] = "<u>GIVN</u> - Given names are the first and middle names usually selected at birth.";
$faqlist["FAQ_030_GIVN2"] = "We fill the given name with an initial uppercase letter. The rest of the name is filled in lower case letters";
$faqlist["FAQ_030_SURNAME"] = "<u>SURNAME</u> is the family or last name. This is the <u>maiden name</u> for a married woman, not her new married surname. See married name below.";
$faqlist["FAQ_030_SURNAME2"] = "We fill the SURNAME with an initial uppercase letter. The rest of the name is filled in lower case letters";
$faqlist["FAQ_030_SUFFIX"] = "Name <u>SUFFIXes</u> are Jr, Sr, III, etc.";
$faqlist["FAQ_030_NICKNAME"] = "<u>NICKNAME</u> is the name commonly used for the person if different from their GIVN name; i.e. John \"Jack\" Arnold would be a nickname of Jack, Daniel Wilson Avery had a nickname of Tuggy, and many Margarets had/have a nickname of Maggie, Nancy, Peggy, Polly or other, etc.";
$faqlist["FAQ_030_HEBNAME"] = "<u>HEBREW NAME</u> is a Hebrew translation of the person's NAME. No more than one Hebrew Name should be filled for any NAME. PhpGedView expects you to fill the surname in slashes. The name יעקב לוי would be filled as יעקב /לוי/.";
$faqlist["FAQ_030_AKANAME"] = "<u>AKA NAME</u> is an additional name the person is known by. It could be the birth name of persons who changed their name later in life or it could be an alias, stage or pen name. It could also be a married name. The first name can be different from the first name of the main NAME. PhpGedView expects you to fill the surname in slashes. The name James Adams would be filled as James /Adams/. Additional Hebrew names or Yiddish names are also filled as AKA NAMEs.";
$faqlist["FAQ_030_MARRNAME"] = "<u>MARRIED NAMES</u> are the female's new name when assuming her spouse's SURN (surname). It is auto-created by the program when you enter her new surname in the Married Name field. i.e. Mary Jane Smith marries John Jones and becomes Mary Jane Jones when you enter Jones in the field Married Surname field.";
$faqlist["FAQ_030_MARRNAME2"] = " Hebrew MARRIED names take the first name from the HEBREW NAME's given name";
$faqlist["FAQ_030_SOURC"] = "<strong>SOURCES and CITATIONS</strong>: In genealogy, its not enough to simply say something \"happened on such-and-such a date\". Historians like proof. We do too! Please provide whatever information as to the source of the information you are providing. Look over the various sourcing notations available and use the NOTES option when in doubt or you need space to write. Put in more than you think may be necessary, it won't be too much. Any questions? Just ask if you don't understand and we'll be happy to assist.";
$faqlist["FAQ_030_CHNG"] = "<strong>CHANGES and ENTRIES</strong>: The existing data for an INDI (individual) or family will not readily appear until approved by an administrator. Although we frequently check the site, send us an email if you want us to review and approve additions or modifications more rapidly. And gedcom facts pertaining to the creation or modification of a family unit are entered on the Close Relatives/Family link page. This is where you note marriages, divorces, children, family census - any fact or event that affects the family unit. We find when adding several children, its best to bring up the VIEW FAMILY link for that husband/wife and add each child via the link at the bottom, 'ADD a CHILD to this Family'. It is faster than using the Close Relative page as with each addition it defaults back to the View INDI page rather than the Close Relative page. Any questions? Just ask if you don't understand. Mistakes, corrections and help are readily and freely offered.";
$faqlist["FAQ_030_MEDIA"] = "<strong>MEDIA</strong>: We really appreciate your addition of pictures, Birth Certificates, Marriage Licenses and Certificates, Death Certificates - anything you've got for support. It's easy to add these from your own hard drive by using the MEDIA tab, ADD MEDIA link and UPLOAD/Browse feature. Again, if you have questions, suggestions, or simply wish assistance, send your digital images to us by email and we can add them too.";
$faqlist["FAQ_030_MEDIA2"] = "When entering new media, consider a naming convention that is unlikely to conflict with existing media. The system allows you to browse your hard drive and upload the document with an entirely different name, retaining your filename intact. You just imagine how many \"john.jpg\" files there could be (well - only one) but you would possibly overwrite the existing file if you don't change the filename. We like to keep them short (less than 35 characters) but descriptive - something like J_Name-b1820-I23445.jpg or K_Name-I23444-Headstn.jpg. If in doubt, please, simply ask us.";
$faqlist["FAQ_030_NAVIGATE"] = "<strong>NAVIGATION</strong>: We navigate using the CIRCLE DIAGRAM function and the ancestor and descendancy charts. Try them. Remember that many functions do not work until you have created your personal INDI fact page, linked from your ancestors.";

$faqlist["FAQ_032_head"] = "CAN I ADD/EDIT/UPDATE THE DATA OF ANY OF THE INDIVIDUALS ON THE TREE?";
$faqlist["FAQ_032_body"] = "Yes.<br />You must be part of the tree, and approved to EDIT online.<br />You can only make changes or add your own and close branches.<br />You may also send your updates in by email.";

$faqlist["FAQ_037_head"] = "I EXPERIENCED TROUBLE TRYING TO EDIT THE TREE. WHAT SHOULD I DO?";
$faqlist["FAQ_037_body"] = "You can email your updates/changes/additions to the Webmaster<br />mail to: #GLOBALS[WEBMASTER_EMAIL]#";
$faqlist["FAQ_037_body2"] = "You can email your updates/changes/additions to the Genmaster<br />mail to: #GLOBALS[CONTACT_EMAIL]#";


$faqlist["FAQ_040_head"] = "WHAT ABOUT PRIVACY?";
$faqlist["FAQ_040_body"] = "<b>#GLOBALS[GEDCOM_TITLE]# believes the protection of personal information is very important</b>. PhpGedView's software privacy functions are excellent in enforcing some privacy rules - primarily our sites hides details about people who are alive or those to whom you are not related. Viewing details of living persons will require you to log on to the site with a userid and password. This is linked to your place in the family tree. Also, our sites use a PGV feature called \"relationship privacy.\" This feature may only allow you to view that information of individuals defined as a close relative. If you are logged in and see certain individuals or families marked as \"PRIVATE\", then this site feature has been activated. If you feel your viewing access is too limited, please email the site admin and explain, with details on ID numbers, where you were blocked and why you believe you should see this information.";
$faqlist["FAQ_040_body2"] = "Of course, no system is perfect nor unbreakable, so the possibility of unintended access to the data remains a possibility. We do everything we can to remedy privacy problems promptly. If you feel strongly about some of your personal details being stored here, please contact the site admin via the form-email links below. Your details can be removed from the site, however your access may also be restricted. See also the FAQ on what information is on the site.<br /><br /><b>We take information abuse, theft or misuse seriously and we will prosecute those that participate in or attempt identity theft as it pertains to our sites' data. Do NOT copy our data on living kin to other sites or locations as they may be unable to protect its privacy and you may be held liable.</b>"; 

$faqlist["FAQ_050_head"] = "THANK YOU";
$faqlist["FAQ_050_body"] = "The acquisition and maintenance of this volume of information would not be possible without the support and participation of so many relatives. Genealogy is great fun and a wonderful learning experience, broadening both our knowledge of family and general facts of geography and sociology. We hope you will enjoy it as much as we do and we look forward to our mutual cooperation and friendships established through the functions of PhpGedView and our #GLOBALS[GEDCOM_TITLE]# web site.<br /><br />Don't hesitate to drop us an email to say hello, advise us of a needed correction or addition, or to inquire about a relationship. Most of what we know is displayed online, with only the details of living kin not displayed.<br /><br />Thanks again";

?>
