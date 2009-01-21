<?php
/**
 * Norwegian texts
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * # $Id$
 *
 * @translator Geir Håkon Eikland
 * @translator Thomas Rindal
 * @package PhpGedView
 * @subpackage Languages
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

// -- Define a fact array to map GEDCOM tags with their Norwegian values
$factarray["ABBR"] = "Forkortelse";
$factarray["ADDR"] = "Adresse";
$factarray["ADR1"] = "Adresse 1";
$factarray["ADR2"] = "Adresse 2";
$factarray["ADOP"] = "Adoptert";
$factarray["AFN"]  = "Slekts-filnr";
$factarray["AGE"]  = "Alder";
$factarray["AGNC"] = "Agentur";
$factarray["ALIA"] = "Alias";
$factarray["ANCE"] = "Forfedre";
$factarray["ANCI"] = "Mangler forfedre...";
$factarray["ANUL"] = "Annulert ekteskap";
$factarray["ASSO"] = "Forbindelser";
$factarray["AUTH"] = "Forfatter";
$factarray["BAPL"] = "Døpt Mormoner";
$factarray["BAPM"] = "Døpt på bekjennelse";
$factarray["BARM"] = "Bar Mitzvah Jødisk sermoni gutt";
$factarray["BASM"] = "Bat Mitzvah Jødisk sermoni jente";
$factarray["BIRT"] = "Født";
$factarray["BLES"] = "Velsignet / Navnefest";
$factarray["BLOB"] = "Binært dataobjekt";
$factarray["BURI"] = "Gravlagt";
$factarray["CALN"] = "Arkivnr./ISBN/ISSN";
$factarray["CAST"] = "Kaste / Sosial status";
$factarray["CAUS"] = "Dødsårsak";
$factarray["CEME"] = "Kirkegård";
$factarray["CENS"] = "Folketelling";
$factarray["CHAN"] = "Sist endret";
$factarray["CHAR"] = "Tegnsett";
$factarray["CHIL"] = "Barn";
$factarray["CHR"]  = "Døpt som barn";
$factarray["CHRA"] = "Døpt som voksen";
$factarray["CITY"] = "Sted/by";
$factarray["CONF"] = "Konfirmert";
$factarray["CONL"] = "Konfirmert Mormoner";
$factarray["COPR"] = "Opphavsrett / Copyright";
$factarray["CORP"] = "Bedrift-/firmanavn";
$factarray["CREM"] = "Kremert";
$factarray["CTRY"] = "Land";
$factarray["DATE"] = "Dato";
$factarray["DATA"] = "Data";
$factarray["DEAT"] = "Død";
$factarray["DESC"] = "Etterkommere";
$factarray["DESI"] = "Mangler etterkommer(e)...";
$factarray["DEST"] = "Mål";
$factarray["DIV"]  = "Skilsmisse";
$factarray["DIVF"] = "Skilsmissebegjæring";
$factarray["DSCR"] = "Beskrivelse";
$factarray["EDUC"] = "Utdannelse";
$factarray["EMIG"] = "Utvandret";
$factarray["ENDL"] = "Gave Mormorer";
$factarray["ENGA"] = "Forlovet";
$factarray["EVEN"] = "Hendelse";
$factarray["FAM"]  = "Familie";
$factarray["FAMC"] = "Familie-ID for barn";
$factarray["FAMF"] = "Familiekort";
$factarray["FAMS"] = "Familie-ID for ektefelle/partner";
$factarray["FCOM"] = "Første nattverd-måltid";
$factarray["FILE"] = "Ekstern fil";
$factarray["FORM"] = "Format / type";
$factarray["GIVN"] = "Fornavn";
$factarray["GRAD"] = "Uteksaminert";
$factarray["HUSB"] = "Ektemann";
$factarray["IDNO"] = "Person-ID-nr.";
$factarray["IMMI"] = "Innvandret";
$factarray["LEGA"] = "Arving";
$factarray["MARB"] = "Lysing av giftemål";
$factarray["MARC"] = "Ekteskapskontrakt";
$factarray["MARL"] = "Ekteskapsattest";
$factarray["MARR"] = "Ekteskap";
$factarray["MARS"] = "Ekteskapsavtale";
$factarray["MEDI"] = "Media-type";
$factarray["NAME"] = "Navn";
$factarray["NATI"] = "Nasjonalitet";
$factarray["NATU"] = "Statsborgerskap";
$factarray["NCHI"] = "Antall barn";
$factarray["NICK"] = "Klengenavn";
$factarray["NMR"]  = "Antall ekteskap";
$factarray["NOTE"] = "Note";
$factarray["NPFX"] = "Prefiks";
$factarray["NSFX"] = "Postfiks";
$factarray["OBJE"] = "Multimedia objekt";
$factarray["OCCU"] = "Yrke";
$factarray["ORDI"] = "Rituale Mormoner";
$factarray["ORDN"] = "Ordinert rel. tjeneste";
$factarray["PAGE"] = "Dokument referanse";
$factarray["PEDI"] = "Slektsgrein";
$factarray["PLAC"] = "Stedsnavn";
$factarray["PHON"] = "Tlf.nr.";
$factarray["POST"] = "Postnummer";
$factarray["PROB"] = "Skifte";
$factarray["PROP"] = "Eiendom";
$factarray["PUBL"] = "Publikasjon";
$factarray["QUAY"] = "Datakvalitet (0-3)";
$factarray["REPO"] = "Oppbevaringssted";
$factarray["REFN"] = "Referensenummer";
$factarray["RELA"] = "Slektskap";
$factarray["RELI"] = "Religion";
$factarray["RESI"] = "Bosted";
$factarray["RESN"] = "Restriksjon";
$factarray["RETI"] = "Pensjonert";
$factarray["RFN"]  = "Ref.nr. (statisk)";
$factarray["RIN"]  = "Ref.nr. (dynamisk)";
$factarray["ROLE"] = "Rolle i hendelse";
$factarray["SEX"]  = "Kjønn";
$factarray["SLGC"] = "Barn-kobling Mormoner";
$factarray["SLGS"] = "Ekteskap-kobling Mormoner";
$factarray["SOUR"] = "Kilde";
$factarray["SPFX"] = "Etternavn prefiks";
$factarray["SSN"]  = "Personnummer";
$factarray["STAE"] = "Stat/Region";
$factarray["STAT"] = "Status";
$factarray["SUBM"] = "Bidragsgiver/Avsender";
$factarray["SUBN"] = "Del av datasamling";
$factarray["SURN"] = "Etternavn";
$factarray["TEMP"] = "Mormoner kirkekode";
$factarray["TEXT"] = "Kildetekst";
$factarray["TIME"] = "Klokkeslett";
$factarray["TITL"] = "Tittel";
$factarray["TYPE"] = "Type";
$factarray["WIFE"] = "Hustru";
$factarray["WILL"] = "Testamente";
$factarray["_EMAIL"] = "E-post-adresse";
$factarray["EMAIL"] = "E-post-adresse";
$factarray["_TODO"] = "Utestående gjøremål";
$factarray["_UID"] = "Universell ID";
$factarray["_PRIM"]	= "Merket som hovedbilde";
##$factarray["_DBID"] = "Linked database ID";

// These facts are used in specific contexts
##$factarray["STAT:DATE"] = "Status Change Date";

//These facts are compounds for the view probabilities and the advanced search pages
##$factarray["FAMC:HUSB:SURN"] = "Father's Surname";
##$factarray["FAMC:WIFE:SURN"] = "Mother's Surname";
##$factarray["FAMC:HUSB:BIRT:PLAC"] = "Father's Birthplace";
##$factarray["FAMC:WIFE:BIRT:PLAC"] = "Mother's Birthplace";
##$factarray["FAMC:MARR:PLAC"] = "Parents' Marriage Place";
##$factarray["FAMC:HUSB:OCCU"] = "Father's Occupation";
##$factarray[":BIRT:PLAC"] = "Birthplace";
##$factarray["FAMS:MARR:PLAC"] = "Marriage Place";
##$factarray["FAMS:MARR:DATE"] = "Marriage Date";
##$factarray["FAMS:SPOUSE:DEAT:PLAC"] = "Spouse's Death Place";
##$factarray["FAMC:HUSB:GIVN"] = "Father's Given Name";
##$factarray["FAMS:SPOUSE:BIRT:PLAC"] = "Spouse's Birth Place";
##$factarray["FAMC:WIFE:GIVN"] = "Mother's Given Name";
##$factarray["FAMC:HUSB:FAMC:HUSB:GIVN"] = "Paternal Grandfather's Given Name";
##$factarray["FAMC:WIFE:FAMC:WIFE:GIVN"] = "Maternal Grandmother's Given Name";
##$factarray["FAMC:WIFE:FAMC:HUSB:GIVN"] = "Maternal Grandfather's Given Name";
##$factarray["FAMC:HUSB:FAMC:WIFE:GIVN"] = "Paternal Grandmother's Given Name";
##$factarray["FAMS:CHIL:BIRT:PLAC"] = "Child's Birth Place";
##$factarray["FAMS:NOTE"] = "Spouse Note";
##$factarray["FAMS:CENS:DATE"] = "Spouse Census Date";
##$factarray["FAMS:CENS:PLAC"] = "Spouse Census Place";
##$factarray["FAMS:DIV:DATE"] = "Spouse Divorce Date";
##$factarray["FAMS:DIV:PLAC"] = "Spouse Divorce Place";
##$factarray["FAMS:SLGS:DATE"] = "LDS Spouse Sealing Date";
##$factarray["FAMS:SLGS:PLAC"] = "LDS Spouse Sealing Place";
##$factarray["FAMS:SLGS:TEMP"] = "LDS Spouse Sealing Temple";

// These facts are all colon delimited
##$factarray["BIRT:PLAC"] = "Birth Place";
##$factarray["BIRT:DATE"] = "Birth Date";
##$factarray["DEAT:PLAC"] = "Death Place";
##$factarray["DEAT:DATE"] = "Death Date";
##$factarray["CHR:PLAC"] = "Christening Place";
##$factarray["CHR:DATE"] = "Christening Date";
##$factarray["BAPM:PLAC"] = "Baptism Place";
##$factarray["BAPM:DATE"] = "Baptism Date";
##$factarray["BURI:PLAC"] = "Burial Place";
##$factarray["BURI:DATE"] = "Burial Date";
##$factarray["MARR:PLAC"] = "Marriage Place";
##$factarray["MARR:DATE"] = "Marriage Date";

// These facts are specific to GEDCOM exports from Family Tree Maker
$factarray["_MDCL"] = "Helse";
$factarray["_DEG"] 	= "Akademisk grad";
$factarray["_MILT"] = "Militærtjeneste";
$factarray["_SEPR"] = "Separert";
$factarray["_DETS"] = "Ektefelles død";
$factarray["CITN"] 	= "Statsborgerskap";
$factarray["_FA1"]	= "Fakta 1";
$factarray["_FA2"]	= "Fakta 2";
$factarray["_FA3"]	= "Fakta 3";
$factarray["_FA4"]	= "Fakta 4";
$factarray["_FA5"]	= "Fakta 5";
$factarray["_FA6"]	= "Fakta 6";
$factarray["_FA7"]	= "Fakta 7";
$factarray["_FA8"]	= "Fakta 8";
$factarray["_FA9"]	= "Fakta 9";
$factarray["_FA10"]	= "Fakta 10";
$factarray["_FA11"]	= "Fakta 11";
$factarray["_FA12"]	= "Fakta 12";
$factarray["_FA13"]	= "Fakta 13";
$factarray["_MREL"]	= "Tilknytning til mor";
$factarray["_FREL"]	= "Tilknytning til far";
$factarray["_MSTAT"] = "Ekteskap start-status";
$factarray["_MEND"]	= "Ekteskap slutt-status";
##$factarray["_NAMS"]	= "Namesake";

// GEDCOM 5.5.1 related facts
$factarray["FAX"]	= "Faks";
$factarray["FACT"]	= "Fakta";
$factarray["WWW"]	= "Hjemmeside";
$factarray["MAP"]	= "Kart";
$factarray["LATI"]	= "Breddegrad";
$factarray["LONG"]	= "Lengdegrad";
$factarray["FONE"]	= "Fonetisk";
$factarray["ROMN"]	= "Romanisert";

// PAF related facts
$factarray["_NAME"]	= "Navn på postmottager";
$factarray["URL"]	= "URL (internett-adresse)";
$factarray["_URL"]	= "URL (internett-adresse)";
$factarray["_HEB"]	= "Hebraisk";
$factarray["_SCBK"] = "Utklippsbok";
$factarray["_TYPE"] = "Media-type";
$factarray["_SSHOW"] = "Lysbildeframvining";

// Rootsmagic
$factarray["_SUBQ"]	= "Kortversjon";
$factarray["_BIBL"] = "Bibliografi";

// Reunion
##$factarray["EMAL"]	= "Email Address";

// Other common customized facts
$factarray["_ADPF"] = "Adopteret av faren";
$factarray["_ADPM"] = "Adopteret av moren";
$factarray["_AKAN"] = "Også kjent som";
$factarray["_AKA"] 	= "Også kjent som";
$factarray["_BRTM"] = "Brit mila Jødisk omskjæring";
$factarray["_COML"]	= "Samboerskap";
$factarray["_EYEC"] = "Øyefarge";
$factarray["_FNRL"] = "Bisettelse";
$factarray["_HAIR"] = "Hårfarve";
$factarray["_HEIG"] = "Høyde";
$factarray["_HOL"]  = "Holocaust";
$factarray["_INTE"] = "Urnenedsettelse";
$factarray["_MARI"] = "Ekteskaps-intensjon";
$factarray["_MBON"] = "Ekteskapsgaranti";
$factarray["_MEDC"] = "Helsetilstand";
$factarray["_MILI"] = "Milit&oelig;rtjeneste";
$factarray["_NMR"] = "Ikke gift";
$factarray["_NLIV"] = "Lever ikke";
$factarray["_NMAR"] = "Aldri gift";
$factarray["_PRMN"] = "Permanent nummer";
$factarray["_WEIG"] = "Vekt";
$factarray["_YART"] = "Yartzeit Jødisk fødselsdag";
$factarray["_MARNM"] = "Navn som gift";
$factarray["_STAT"]	= "Sivilstatus";
$factarray["COMM"]	= "Kommentar";

// Aldfaer related facts
$factarray["MARR_CIVIL"] = "Borgelig vielse";
$factarray["MARR_RELIGIOUS"] = "Kirkelig vielse";
$factarray["MARR_PARTNERS"] = "Registert partnerskap";
$factarray["MARR_UNKNOWN"] = "Ukjent form for vielse";

$factarray["_HNM"] = "Hebraisk navn";

// Pseudo-facts for relatives
$factarray["_DEAT_SPOU"] = "Dødsfall til ektefelle";
##$factarray["_BURI_SPOU"] = "Burial of spouse";
##$factarray["_CREM_SPOU"] = "Cremation of spouse";

$factarray["_BIRT_CHIL"] = "Fødsel til barn";
##$factarray["_CHR_CHIL" ] = "Christening of a child";
##$factarray["_BAPM_CHIL"] = "Baptism of a child";
##$factarray["__BRTM_CHIL"] = "Brit Mila of a child";
##$factarray["_ADOP_CHIL"] = "Adoption of a child";
$factarray["_MARR_CHIL"] = "Ekteskap til barn";
##$factarray["_MARB_CHIL"] = "Marriage Bann of a child";
$factarray["_DEAT_CHIL"] = "Dødsfall til barn";
##$factarray["_BURI_CHIL"] = "Burial of a child";
##$factarray["_CREM_CHIL"] = "Cremation of a child";

$factarray["_BIRT_GCHI"] = "Fødsel til barnebarn";
##$factarray["_CHR_GCHI" ] = "Christening of a grandchild";
##$factarray["_BAPM_GCHI"] = "Baptism of a grandchild";
##$factarray["__BRTM_GCHI"] = "Brit Mila of a grandchild";
##$factarray["_ADOP_GCHI"] = "Adoption of a grandchild";
$factarray["_MARR_GCHI"] = "Ekteskap til barnenarn";
##$factarray["_MARB_GCHI"] = "Marriage Bann of a grandchild";
$factarray["_DEAT_GCHI"] = "Dødsfall til barnebarn";
##$factarray["_BURI_GCHI"] = "Burial of a grandchild";
##$factarray["_CREM_GCHI"] = "Cremation of a grandchild";

##$factarray["_BIRT_GGCH"] = "Birth of a great-grandchild";
##$factarray["_CHR_GGCH" ] = "Christening of a great-grandchild";
##$factarray["_BAPM_GGCH"] = "Baptism of a great-grandchild";
##$factarray["__BRTM_GGCH"] = "Brit Mila of a great-grandchild";
##$factarray["_ADOP_GGCH"] = "Adoption of a great-grandchild";
##$factarray["_MARR_GGCH"] = "Marriage of a great-grandchild";
##$factarray["_MARB_GGCH"] = "Marriage Bann of a great-grandchild";
##$factarray["_DEAT_GGCH"] = "Death of a great-grandchild";
##$factarray["_BURI_GGCH"] = "Burial of a great-grandchild";
##$factarray["_CREM_GGCH"] = "Cremation of a great-grandchild";

$factarray["_MARR_FATH"] = "Ekteskap til faren";
##$factarray["_MARB_FATH"] = "Marriage Bann of father";
$factarray["_DEAT_FATH"] = "Dødsfall til faren";
##$factarray["_BURI_FATH"] = "Burial of father";
##$factarray["_CREM_FATH"] = "Cremation of father";

##$factarray["_MARR_FAMC"] = "Marriage of parents";
##$factarray["_MARB_FAMC"] = "Marriage Bann of parents";

$factarray["_MARR_MOTH"] = "Ekteskap til moren";
##$factarray["_MARB_MOTH"] = "Marriage Bann of mother";
$factarray["_DEAT_MOTH"] = "Dødsfall til moren";
##$factarray["_BURI_MOTH"] = "Burial of mother";
##$factarray["_CREM_MOTH"] = "Cremation of mother";

$factarray["_BIRT_SIBL"] = "Fødsel: en av søsknene";
##$factarray["_CHR_SIBL" ] = "Christening of sibling";
##$factarray["_BAPM_SIBL"] = "Baptism of sibling";
##$factarray["__BRTM_SIBL"] = "Brit Mila of sibling";
##$factarray["_ADOP_SIBL"] = "Adoption of sibling";
$factarray["_MARR_SIBL"] = "Ekteskap: en av søsknene";
##$factarray["_MARB_SIBL"] = "Marriage Bann of sibling";
$factarray["_DEAT_SIBL"] = "Dødsfall: en av søsknene";
##$factarray["_BURI_SIBL"] = "Burial of sibling";
##$factarray["_CREM_SIBL"] = "Cremation of sibling";

$factarray["_BIRT_HSIB"] = "Fødsel: en av halvsøsknene";
##$factarray["_CHR_HSIB" ] = "Christening of half-sibling";
##$factarray["_BAPM_HSIB"] = "Baptism of half-sibling";
##$factarray["__BRTM_HSIB"] = "Brit Mila of half-sibling";
##$factarray["_ADOP_HSIB"] = "Adoption of half-sibling";
$factarray["_MARR_HSIB"] = "Ekteskap: en av halvsøsknene";
##$factarray["_MARB_HSIB"] = "Marriage Bann of half-sibling";
$factarray["_DEAT_HSIB"] = "Dødsfall: en av halvsøsknene";
##$factarray["_BURI_HSIB"] = "Burial of half-sibling";
##$factarray["_CREM_HSIB"] = "Cremation of half-sibling";

##$factarray["_BIRT_NEPH"] = "Birth of a nephew or niece";
##$factarray["_CHR_NEPH" ] = "Christening of a nephew or niece";
##$factarray["_BAPM_NEPH"] = "Baptism of a nephew or niece";
##$factarray["__BRTM_NEPH"] = "Brit Mila of a nephew";
##$factarray["_ADOP_NEPH"] = "Adoption of a nephew or niece";
##$factarray["_MARR_NEPH"] = "Marriage of a nephew or niece";
##$factarray["_MARB_NEPH"] = "Marriage Bann of a nephew or niece";
##$factarray["_DEAT_NEPH"] = "Death of a nephew or niece";
##$factarray["_BURI_NEPH"] = "Burial of a nephew or niece";
##$factarray["_CREM_NEPH"] = "Cremation of a nephew or niece";

$factarray["_DEAT_GPAR"] = "Dødsfall: en av besteforeldrene";
##$factarray["_BURI_GPAR"] = "Burial of a grand-parent";
##$factarray["_CREM_GPAR"] = "Cremation of a grand-parent";

##$factarray["_DEAT_GGPA"] = "Death of a great-grand-parent";
##$factarray["_BURI_GGPA"] = "Burial of a great-grand-parent";
##$factarray["_CREM_GGPA"] = "Cremation of a great-grand-parent";

$factarray["_BIRT_FSIB"] = "Fødsel: en av søsknene til faren";
##$factarray["_CHR_FSIB" ] = "Christening of father's sibling";
##$factarray["_BAPM_FSIB"] = "Baptism of father's sibling";
##$factarray["__BRTM_FSIB"] = "Brit Mila of father's sibling";
##$factarray["_ADOP_FSIB"] = "Adoption of father's sibling";
$factarray["_MARR_FSIB"] = "Ekteskap: en av søknene til faren";
##$factarray["_MARB_FSIB"] = "Marriage Bann of father's sibling";
$factarray["_DEAT_FSIB"] = "Dødsfall: en av søsknene til faren";
##$factarray["_BURI_FSIB"] = "Burial of father's sibling";
##$factarray["_CREM_FSIB"] = "Cremation of father's sibling";

$factarray["_BIRT_MSIB"] = "Fødsel: en av søsknene til moren";
##$factarray["_CHR_MSIB" ] = "Christening of mother's sibling";
##$factarray["_BAPM_MSIB"] = "Baptism of mother's sibling";
##$factarray["__BRTM_MSIB"] = "Brit Mila of mother's sibling";
##$factarray["_ADOP_MSIB"] = "Adoption of mother's sibling";
$factarray["_MARR_MSIB"] = "Ekteskap: en av søsknene til moren";
##$factarray["_MARB_MSIB"] = "Marriage Bann of mother's sibling";
$factarray["_DEAT_MSIB"] = "Dødsfall: en av søsknene til moren";
##$factarray["_BURI_MSIB"] = "Burial of mother's sibling";
##$factarray["_CREM_MSIB"] = "Cremation of mother's sibling";

##$factarray["_BIRT_COUS"] = "Birth of a first cousin";
##$factarray["_CHR_COUS"]  = "Christening of a first cousin";
##$factarray["_BAPM_COUS"] = "Baptism of a first cousin";
##$factarray["__BRTM_COUS"] = "Brit Mila of a first cousin";
##$factarray["_ADOP_COUS"] = "Adoption of a first cousin";
##$factarray["_MARR_COUS"] = "Marriage of a first cousin";
##$factarray["_MARB_COUS"] = "Marriage Bann of a first cousin";
##$factarray["_DEAT_COUS"] = "Death of a first cousin";
##$factarray["_BURI_COUS"] = "Burial of a first cousin";
##$factarray["_CREM_COUS"] = "Cremation of a first cousin";

##$factarray["_FAMC_EMIG"] = "Emigration of parents";
##$factarray["_FAMC_RESI"] = "Residence of parents";

//-- PGV Only facts
$factarray["_THUM"]	= "Bruke dette bilde som passfoto?";
$factarray["_PGVU"]	= "Sist oppdatert av";
##$factarray["SERV"] = "Remote Server";
##$factarray["_GEDF"] = "GEDCOM File";

/*-- Fact abbreviations for use in Chart boxes.
 *		Use these abbreviations in cases where the standard method of using the first
 *		letter of the spelled-out name results in an undesirable abbreviation or where
 *		you want to produce a different result (eg: "x" instead of "M" for "Married").
 *
 *		You can abbreviate any Fact label this way.  The list of abbreviations is
 *		open-ended.
 *
 *		These abbreviations are user-customizable. Just put them into file "extra.xx.php".
 *		The length of these abbreviations is not restricted to 1 letter.
 */

/*-- The following lines have been commented out.  They should serve as examples.

$factAbbrev["BIRT"]		= "B";
$factAbbrev["MARR"]		= "M";
$factAbbrev["DEAT"]		= "D";

 */

?>
