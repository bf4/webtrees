<?php
/**
 * Serbian Language file for PhpGedView.
 *
 * PhpGedView: Genealogy Viewer
 * Copyright (C) 2009  PGV Development Team.  All rights reserved.
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
 * @translator Vojin Damjanac
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

// -- Define a fact array to map GEDCOM tags with their English values
$factarray["ABBR"]	= "Skraćenica";
$factarray["ADDR"]	= "Adresa";
$factarray["ADR1"]	= "Prva adresa";
$factarray["ADR2"]	= "Druga adresa";
$factarray["ADOP"]	= "Usvojenje";
##$factarray["AFN"]	= "Ancestral File Number (AFN)";
$factarray["AGE"]	= "Starost";
$factarray["AGNC"]	= "Ustanova/Firma";
$factarray["ALIA"]	= "Pseudonim";
$factarray["ANCE"]	= "Preci";
##$factarray["ANCI"]	= "Ancestors Interest";
##$factarray["ANUL"]	= "Annulment";
##$factarray["ASSO"]	= "Associate";
##$factarray["AUTH"]	= "Author";
##$factarray["BAPL"]	= "LDS Baptism";
##$factarray["BAPM"]	= "Baptism";
$factarray["BARM"]	= "Bar Mitzvah";
$factarray["BASM"]	= "Bas Mitzvah";
$factarray["BIRT"]	= "Rođen/a";
##$factarray["BLES"]	= "Blessing";
##$factarray["BLOB"]	= "Binary Data Object";
$factarray["BURI"]	= "Pokop";
##$factarray["CALN"]	= "Call Number";
##$factarray["CAST"]	= "Caste / Social Status";
$factarray["CAUS"]	= "Uzrok smrti";
$factarray["CEME"]  = "Groblje";
##$factarray["CENS"]	= "Census";
$factarray["CHAN"]	= "Poslednja izmena";
##$factarray["CHAR"]	= "Character Set";
$factarray["CHIL"]	= "Dete";
$factarray["CHR"]	= "Krštenje";
##$factarray["CHRA"]	= "Adult Christening";
$factarray["CITY"]	= "Naselje";
##$factarray["CONF"]	= "Confirmation";
##$factarray["CONL"]	= "LDS Confirmation";
##$factarray["COPR"]	= "Copyright";
$factarray["CORP"]	= "Kompanija";
$factarray["CREM"]	= "Kremacija";
$factarray["CTRY"]	= "Država";
$factarray["DATA"]	= "Data";
$factarray["DATE"]	= "Datum";
$factarray["DEAT"]	= "Umro/la";
$factarray["DESC"]	= "Potomci";
##$factarray["DESI"]	= "Descendants Interest";
##$factarray["DEST"]	= "Destination";
$factarray["DIV"]	= "Razvod";
$factarray["DIVF"]	= "Podnet zahtev za razvod braka";
$factarray["DSCR"]	= "Opis";
$factarray["EDUC"]	= "Završene škole";
##$factarray["EMIG"]	= "Emigration";
##$factarray["ENDL"]	= "LDS Endowment";
$factarray["ENGA"]	= "Zaruke";
$factarray["EVEN"]	= "Događaj";
$factarray["FAM"]	= "Porodica";
##$factarray["FAMC"]	= "Family as a Child";
##$factarray["FAMF"]	= "Family File";
##$factarray["FAMS"]	= "Family as a Spouse";
##$factarray["FCOM"]	= "First Communion";
##$factarray["FILE"]	= "External File";
##$factarray["FORM"]	= "Format";
$factarray["GIVN"]	= "Lično ime";
$factarray["GRAD"]	= "Dodela diploma";
$factarray["HUSB"]  = "Suprug";
$factarray["IDNO"]	= "Lični broj";
$factarray["IMMI"]	= "Imigriranje";
##$factarray["LEGA"]	= "Legatee";
##$factarray["MARB"]	= "Marriage Bann";
$factarray["MARC"]	= "Bračni ugovor";
##$factarray["MARL"]	= "Marriage Licence";
$factarray["MARR"]	= "Sklapanje braka";
##$factarray["MARS"]	= "Marriage Settlement";
##$factarray["MEDI"]	= "Media Type";
$factarray["NAME"]	= "Ime i prezime";
$factarray["NATI"]	= "Nacionalnost";
$factarray["NATU"]	= "Promena državljanstva";
$factarray["NCHI"]	= "Broj dece";
$factarray["NICK"]	= "Nadimak";
##$factarray["NMR"]	= "Number of Marriages";
$factarray["NOTE"]	= "Beleška";
##$factarray["NPFX"]	= "Prefix";
##$factarray["NSFX"]	= "Suffix";
##$factarray["OBJE"]	= "Multimedia Object";
$factarray["OCCU"]	= "Zaposlenje";
##$factarray["ORDI"]	= "Ordinance";
##$factarray["ORDN"]	= "Ordination";
##$factarray["PAGE"]	= "Citation Details";
##$factarray["PEDI"]	= "Pedigree";
$factarray["PLAC"]	= "Mesto";
$factarray["PHON"]	= "Telefon";
$factarray["POST"]	= "Poštanski broj";
##$factarray["PROB"]	= "Probate";
##$factarray["PROP"]	= "Property";
$factarray["PUBL"]	= "Publikacija";
##$factarray["QUAY"]	= "Quality of Data";
##$factarray["REPO"]	= "Repository";
##$factarray["REFN"]	= "Reference Number";
##$factarray["RELA"]	= "Relationship";
$factarray["RELI"]	= "Religija";
$factarray["RESI"]	= "Prebivalište";
##$factarray["RESN"]	= "Restriction";
$factarray["RETI"]	= "Penzija";
##$factarray["RFN"]	= "Record File Number";
##$factarray["RIN"]	= "Record ID Number";
##$factarray["ROLE"]	= "Role";
$factarray["SEX"]	= "Pol";
##$factarray["SLGC"]	= "LDS Child Sealing";
##$factarray["SLGS"]	= "LDS Spouse Sealing";
$factarray["SOUR"]	= "Izvor";
##$factarray["SPFX"]	= "Surname Prefix";
$factarray["SSN"]	= "Identifikacioni broj";
$factarray["STAE"]	= "Region/Provincija";
$factarray["STAT"]	= "Status";
$factarray["SUBM"]	= "Podnosioc";
$factarray["SUBN"]	= "Podnošenje";
$factarray["SURN"]	= "Prezime";
##$factarray["TEMP"]	= "Temple";
$factarray["TEXT"]	= "Tekst";
##$factarray["TIME"]	= "Time";
##$factarray["TITL"]	= "Title";
##$factarray["TYPE"]	= "Type";
$factarray["WIFE"]  = "Supruga";
$factarray["WILL"]	= "Oporuka";
$factarray["_EMAIL"]	= "Email adresa";
$factarray["EMAIL"]	= "Email Adresa";
##$factarray["_TODO"]	= "To Do Item";
##$factarray["_UID"]	= "Globally unique Identifier";
$factarray["_PRIM"]	= "Važna slika";
##$factarray["_DBID"] = "Linked database ID";

// These facts are used in specific contexts
##$factarray["STAT:DATE"] = "Status Change Date";

//These facts are compounds for the view probabilities and the advanced search pages
$factarray["FAMC:HUSB:SURN"] = "Očevo prezime";
$factarray["FAMC:WIFE:SURN"] = "Majčino prezime";
$factarray["FAMC:HUSB:BIRT:PLAC"] = "Mesto rođenja oca";
$factarray["FAMC:WIFE:BIRT:PLAC"] = "Mesto rođenja majke";
$factarray["FAMC:MARR:PLAC"] = "Mesto venčanja roditelja";
$factarray["FAMC:HUSB:OCCU"] = "Očevo zanimanje";
$factarray[":BIRT:PLAC"] = "Mesto rođenja";
$factarray["FAMS:MARR:PLAC"] = "Mesto sklapanja braka";
$factarray["FAMS:MARR:DATE"] = "Datum sklapanja braka";
##$factarray["FAMS:SPOUSE:DEAT:PLAC"] = "Spouse's Death Place";
$factarray["FAMC:HUSB:GIVN"] = "Očevo lično ime";
##$factarray["FAMS:SPOUSE:BIRT:PLAC"] = "Spouse's Birth Place";
$factarray["FAMC:WIFE:GIVN"] = "Majčino lično ime";
##$factarray["FAMC:HUSB:FAMC:HUSB:GIVN"] = "Paternal Grandfather's Given Name";
##$factarray["FAMC:WIFE:FAMC:WIFE:GIVN"] = "Maternal Grandmother's Given Name";
##$factarray["FAMC:WIFE:FAMC:HUSB:GIVN"] = "Maternal Grandfather's Given Name";
##$factarray["FAMC:HUSB:FAMC:WIFE:GIVN"] = "Paternal Grandmother's Given Name";
$factarray["FAMS:CHIL:BIRT:PLAC"] = "Mesto rođenja deteta";
##$factarray["FAMS:NOTE"] = "Spouse Note";
##$factarray["FAMS:CENS:DATE"] = "Spouse Census Date";
##$factarray["FAMS:CENS:PLAC"] = "Spouse Census Place";
##$factarray["FAMS:DIV:DATE"] = "Spouse Divorce Date";
##$factarray["FAMS:DIV:PLAC"] = "Spouse Divorce Place";
##$factarray["FAMS:SLGS:DATE"] = "LDS Spouse Sealing Date";
##$factarray["FAMS:SLGS:PLAC"] = "LDS Spouse Sealing Place";
##$factarray["FAMS:SLGS:TEMP"] = "LDS Spouse Sealing Temple";

// These facts are all colon delimited
$factarray["BIRT:PLAC"] = "Mesto rođenja";
$factarray["BIRT:DATE"] = "Datum rođenja";
$factarray["DEAT:PLAC"] = "Mesto smrti";
$factarray["DEAT:DATE"] = "Datum smrti";
$factarray["CHR:PLAC"] = "Mesto krštenja";
$factarray["CHR:DATE"] = "Datum krštenja";
##$factarray["BAPM:PLAC"] = "Baptism Place";
##$factarray["BAPM:DATE"] = "Baptism Date";
$factarray["BURI:PLAC"] = "Mesto pokopa";
$factarray["BURI:DATE"] = "Datum pokopa";
$factarray["MARR:PLAC"] = "Mesto sklapanja braka";
$factarray["MARR:DATE"] = "Datum sklapanja braka";

// These facts are specific to GEDCOM exports from Family Tree Maker
##$factarray["_MDCL"]	= "Medical";
##$factarray["_DEG"]	= "Degree";
$factarray["_MILT"]	= "Vojni rok";
##$factarray["_SEPR"]	= "Separated";
##$factarray["_DETS"]	= "Death of One Spouse";
$factarray["CITN"]	= "Državljanstvo";
$factarray["_FA1"]	= "Podatak 1";
$factarray["_FA2"]	= "Podatak 2";
$factarray["_FA3"]	= "Podatak 3";
$factarray["_FA4"]	= "Podatak 4";
$factarray["_FA5"]	= "Podatak 5";
$factarray["_FA6"]	= "Podatak 6";
$factarray["_FA7"]	= "Podatak 7";
$factarray["_FA8"]	= "Podatak 8";
$factarray["_FA9"]	= "Podatak 9";
$factarray["_FA10"]	= "Podatak 10";
$factarray["_FA11"]	= "Podatak 11";
$factarray["_FA12"]	= "Podatak 12";
$factarray["_FA13"]	= "Podatak 13";
##$factarray["_MREL"]	= "Relationship to Mother";
##$factarray["_FREL"]	= "Relationship to Father";
##$factarray["_MSTAT"]	= "Marriage Beginning Status";
##$factarray["_MEND"]	= "Marriage Ending Status";
##$factarray["_NAMS"]	= "Namesake";

// GEDCOM 5.5.1 related facts
##$factarray["FAX"] = "FAX";
$factarray["FACT"] = "Podatak";
##$factarray["WWW"] = "Web Home Page";
$factarray["MAP"] = "Karta";
##$factarray["LATI"] = "Latitude";
##$factarray["LONG"] = "Longitude";
##$factarray["FONE"] = "Phonetic";
##$factarray["ROMN"] = "Romanized";

// PAF related facts
##$factarray["_NAME"] = "Mailing Name";
##$factarray["URL"] = "Web URL";
##$factarray["_URL"] = "Web URL";
##$factarray["_HEB"] = "Hebrew";
##$factarray["_SCBK"] = "Scrapbook";
##$factarray["_TYPE"] = "Media Type";
##$factarray["_SSHOW"] = "Slide Show";

// Rootsmagic
##$factarray["_SUBQ"]= "Short Version";
##$factarray["_BIBL"] = "Bibliography";

// Reunion
$factarray["EMAL"]	= "Email adresa";

// Other common customized facts
##$factarray["_ADPF"]	= "Adopted by Father";
##$factarray["_ADPM"]	= "Adopted by Mother";
##$factarray["_AKAN"]	= "Also known as";
##$factarray["_AKA"] 	= "Also known as";
$factarray["_BRTM"]	= "Brit Mila";
##$factarray["_COML"]	= "Common Law Marriage";
##$factarray["_EYEC"]	= "Eye Color";
$factarray["_FNRL"]	= "Sahrana";
##$factarray["_HAIR"]	= "Hair Color";
$factarray["_HEIG"]	= "Visina";
##$factarray["_HOL"]  = "Holocaust";
##$factarray["_INTE"]	= "Interred";
##$factarray["_MARI"]	= "Marriage Intention";
##$factarray["_MBON"]	= "Marriage Bond";
##$factarray["_MEDC"]	= "Medical Condition";
$factarray["_MILI"]	= "Vojna služba";
$factarray["_NMR"]	= "Neoženjen";
$factarray["_NLIV"]	= "Nije živ";
$factarray["_NMAR"]	= "Nije se ženio";
##$factarray["_PRMN"]	= "Permanent Number";
$factarray["_WEIG"]	= "Težina";
##$factarray["_YART"]	= "Yahrzeit";
$factarray["_MARNM"] = "Ime i prezime posle udaje";
$factarray["_MARNM_SURN"] = "Prezime posle udaje";
$factarray["_STAT"]	= "Bračno stanje";
$factarray["COMM"]	= "Primedba";

// Aldfaer related facts
$factarray["MARR_CIVIL"] = "Građanski brak";
$factarray["MARR_RELIGIOUS"] = "Crkveni brak";
##$factarray["MARR_PARTNERS"] = "Registered Partnership";
$factarray["MARR_UNKNOWN"] = "Nepoznata vrsta braka";

$factarray["_HNM"] = "Jevrejsko ime";

// Pseudo-facts for relatives
$factarray["_DEAT_SPOU"] = "Smrt supružnika";
$factarray["_BURI_SPOU"] = "Pokop supružnika";
$factarray["_CREM_SPOU"] = "Kremacija supružnika";

$factarray["_BIRT_CHIL"] = "Rođenje deteta";
$factarray["_CHR_CHIL"] = "Krštenje deteta";
##$factarray["_BAPM_CHIL"] = "Baptism of a child";
##$factarray["__BRTM_CHIL"] = "Brit Mila of a child";
$factarray["_ADOP_CHIL"] = "Usvojenje deteta";
$factarray["_MARR_CHIL"] = "Sklapanje braka deteta";
##$factarray["_MARB_CHIL"] = "Marriage Bann of a child";
$factarray["_DEAT_CHIL"] = "Smrt deteta";
$factarray["_BURI_CHIL"] = "Pokop deteta";
$factarray["_CREM_CHIL"] = "Kremacija deteta";

$factarray["_BIRT_GCHI"] = "Rođenje unučeta";
$factarray["_CHR_GCHI"] = "Krštenje unučeta";
##$factarray["_BAPM_GCHI"] = "Baptism of a grandchild";
##$factarray["__BRTM_GCHI"] = "Brit Mila of a grandchild";
##$factarray["_ADOP_GCHI"] = "Adoption of a grandchild";
##$factarray["_MARR_GCHI"] = "Marriage of a grandchild";
##$factarray["_MARB_GCHI"] = "Marriage Bann of a grandchild";
##$factarray["_DEAT_GCHI"] = "Death of a grandchild";
##$factarray["_BURI_GCHI"] = "Burial of a grandchild";
##$factarray["_CREM_GCHI"] = "Cremation of a grandchild";

##$factarray["_BIRT_GGCH"] = "Birth of a great-grandchild";
##$factarray["_CHR_GGCH"] = "Christening of a great-grandchild";
##$factarray["_BAPM_GGCH"] = "Baptism of a great-grandchild";
##$factarray["__BRTM_GGCH"] = "Brit Mila of a great-grandchild";
##$factarray["_ADOP_GGCH"] = "Adoption of a great-grandchild";
##$factarray["_MARR_GGCH"] = "Marriage of a great-grandchild";
##$factarray["_MARB_GGCH"] = "Marriage Bann of a great-grandchild";
##$factarray["_DEAT_GGCH"] = "Death of a great-grandchild";
##$factarray["_BURI_GGCH"] = "Burial of a great-grandchild";
##$factarray["_CREM_GGCH"] = "Cremation of a great-grandchild";

##$factarray["_MARR_FATH"] = "Marriage of father";
##$factarray["_MARB_FATH"] = "Marriage Bann of father";
$factarray["_DEAT_FATH"] = "Smrt oca";
##$factarray["_BURI_FATH"] = "Burial of father";
##$factarray["_CREM_FATH"] = "Cremation of father";

##$factarray["_MARR_FAMC"] = "Marriage of parents";
##$factarray["_MARB_FAMC"] = "Marriage Bann of parents";

##$factarray["_MARR_MOTH"] = "Marriage of mother";
##$factarray["_MARB_MOTH"] = "Marriage Bann of mother";
$factarray["_DEAT_MOTH"] = "Smrt majke";
##$factarray["_BURI_MOTH"] = "Burial of mother";
##$factarray["_CREM_MOTH"] = "Cremation of mother";

$factarray["_BIRT_SIBL"] = "Rođenje brata/sestre";
##$factarray["_CHR_SIBL"] = "Christening of sibling";
##$factarray["_BAPM_SIBL"] = "Baptism of sibling";
##$factarray["__BRTM_SIBL"] = "Brit Mila of sibling";
##$factarray["_ADOP_SIBL"] = "Adoption of sibling";
##$factarray["_MARR_SIBL"] = "Marriage of sibling";
##$factarray["_MARB_SIBL"] = "Marriage Bann of sibling";
$factarray["_DEAT_SIBL"] = "Smrt brata/sestre";
##$factarray["_BURI_SIBL"] = "Burial of sibling";
##$factarray["_CREM_SIBL"] = "Cremation of sibling";

##$factarray["_BIRT_HSIB"] = "Birth of half-sibling";
##$factarray["_CHR_HSIB"] = "Christening of half-sibling";
##$factarray["_BAPM_HSIB"] = "Baptism of half-sibling";
##$factarray["__BRTM_HSIB"] = "Brit Mila of half-sibling";
##$factarray["_ADOP_HSIB"] = "Adoption of half-sibling";
##$factarray["_MARR_HSIB"] = "Marriage of half-sibling";
##$factarray["_MARB_HSIB"] = "Marriage Bann of half-sibling";
##$factarray["_DEAT_HSIB"] = "Death of half-sibling";
##$factarray["_BURI_HSIB"] = "Burial of half-sibling";
##$factarray["_CREM_HSIB"] = "Cremation of half-sibling";

##$factarray["_BIRT_NEPH"] = "Birth of a nephew or niece";
##$factarray["_CHR_NEPH"] = "Christening of a nephew or niece";
##$factarray["_BAPM_NEPH"] = "Baptism of a nephew or niece";
##$factarray["__BRTM_NEPH"] = "Brit Mila of a nephew";
##$factarray["_ADOP_NEPH"] = "Adoption of a nephew or niece";
##$factarray["_MARR_NEPH"] = "Marriage of a nephew or niece";
##$factarray["_MARB_NEPH"] = "Marriage Bann of a nephew or niece";
##$factarray["_DEAT_NEPH"] = "Death of a nephew or niece";
##$factarray["_BURI_NEPH"] = "Burial of a nephew or niece";
##$factarray["_CREM_NEPH"] = "Cremation of a nephew or niece";

$factarray["_DEAT_GPAR"] = "Smrt babe ili dede";
$factarray["_BURI_GPAR"] = "Sahrana babe ili dede";
$factarray["_CREM_GPAR"] = "Kremacija babe ili dede";

$factarray["_DEAT_GGPA"] = "Smrt pradede ili prababe";
##$factarray["_BURI_GGPA"] = "Burial of a great-grand-parent";
##$factarray["_CREM_GGPA"] = "Cremation of a great-grand-parent";

$factarray["_BIRT_FSIB"] = "Rođenje očevog brata ili sestre";
##$factarray["_CHR_FSIB"] = "Christening of father's sibling";
##$factarray["_BAPM_FSIB"] = "Baptism of father's sibling";
##$factarray["__BRTM_FSIB"] = "Brit Mila of father's sibling";
##$factarray["_ADOP_FSIB"] = "Adoption of father's sibling";
##$factarray["_MARR_FSIB"] = "Marriage of father's sibling";
##$factarray["_MARB_FSIB"] = "Marriage Bann of father's sibling";
##$factarray["_DEAT_FSIB"] = "Death of father's sibling";
##$factarray["_BURI_FSIB"] = "Burial of father's sibling";
##$factarray["_CREM_FSIB"] = "Cremation of father's sibling";

##$factarray["_BIRT_MSIB"] = "Birth of mother's sibling";
##$factarray["_CHR_MSIB"] = "Christening of mother's sibling";
##$factarray["_BAPM_MSIB"] = "Baptism of mother's sibling";
##$factarray["__BRTM_MSIB"] = "Brit Mila of mother's sibling";
##$factarray["_ADOP_MSIB"] = "Adoption of mother's sibling";
##$factarray["_MARR_MSIB"] = "Marriage of mother's sibling";
##$factarray["_MARB_MSIB"] = "Marriage Bann of mother's sibling";
##$factarray["_DEAT_MSIB"] = "Death of mother's sibling";
##$factarray["_BURI_MSIB"] = "Burial of mother's sibling";
##$factarray["_CREM_MSIB"] = "Cremation of mother's sibling";

$factarray["_BIRT_COUS"] = "Rođenje rođaka ";
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
##$factarray["_THUM"]	= "Use this image as the thumbnail?";
##$factarray["_PGVU"]	= "by"; // last changed by
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
