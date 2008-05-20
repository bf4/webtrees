<?php
/**
 * Danish Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PGV Development Team
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
 * @version $Id$
 */
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Du kan ikke tilgå en sprogfil direkte.";
	exit;
}
// -- Define a fact array to map GEDCOM tags with their danish values
$factarray["ABBR"] = "Forkortelse";
$factarray["ADDR"] = "Adresse";
$factarray["ADR1"] = "Adresse 1";
$factarray["ADR2"] = "Adresse 2";
$factarray["ADOP"] = "Adoption";
$factarray["AFN"]  = "Slægtsfil nr. (Mormoner)";
$factarray["AGE"]  = "Alder";
$factarray["AGNC"] = "Agentur";
$factarray["ALIA"] = "Alias";
$factarray["ANCE"] = "Forfædre";
$factarray["ANCI"] = "Mangler forfædre...";
$factarray["ANUL"] = "Annuleret ægteskab";
$factarray["ASSO"] = "Forbindelser";
$factarray["AUTH"] = "Forfatter";
$factarray["BAPL"] = "Voksendåb (mormoner)";
$factarray["BAPM"] = "Dåb";
$factarray["BARM"] = "Bar Mitzvah (Jødisk ceremoni for drenge)";
$factarray["BASM"] = "Bat Mitzvah (Jødisk ceremoni for piger)";
$factarray["BIRT"] = "Født";
$factarray["BLES"] = "Velsignet / Navnefest";
$factarray["BLOB"] = "Binært dataobjekt";
$factarray["BURI"] = "Begravelse";
$factarray["CALN"] = "Arkivnr./ISBN/ISSN";
$factarray["CAST"] = "Kaste / Social status";
$factarray["CAUS"] = "Dødsårsag";
$factarray["CEME"] = "Kirkegård";
$factarray["CENS"] = "Folketælling";
$factarray["CHAN"] = "Sidst ændret";
$factarray["CHAR"] = "Tegnsæt";
$factarray["CHIL"] = "Barn";
$factarray["CHR"]  = "Dåb";
$factarray["CHRA"] = "Voksendåb";
$factarray["CITY"] = "Sted/by";
$factarray["CONF"] = "Konfirmeret";
$factarray["CONL"] = "Konfirmerede (mormoner)";
$factarray["COPR"] = "Ophavsret/Copyright";
$factarray["CORP"] = "Virksomhed/firmanavn";
$factarray["CREM"] = "Kremeret";
$factarray["CTRY"] = "Land";
$factarray["DATA"] = "Data";
$factarray["DATE"] = "Dato";
$factarray["DEAT"] = "Død";
$factarray["DESC"] = "Efterkommere";
$factarray["DESI"] = "Mangler efterkommer(e)...";
$factarray["DEST"] = "Mål";
$factarray["DIV"]  = "Skilsmisse";
$factarray["DIVF"] = "Skilsmissebegæring";
$factarray["DSCR"] = "Beskrivelse";
$factarray["EDUC"] = "Uddannelse";
$factarray["EMIG"] = "Udvandret";
$factarray["ENDL"] = "Gave (Mormoner)";
$factarray["ENGA"] = "Forlovet";
$factarray["EVEN"] = "Begivenhed";
$factarray["FAM"]  = "Familie";
$factarray["FAMC"] = "Familie ID for barn";
$factarray["FAMF"] = "Familie fil for mormoner";
$factarray["FAMS"] = "Familie ID for ægtefælle/partner";
$factarray["FCOM"] = "Første altergang";
$factarray["FILE"] = "Ekstern fil";
$factarray["FORM"] = "Filformat";
$factarray["GIVN"] = "Fornavn";
$factarray["GRAD"] = "Eksamen";
$factarray["HUSB"] = "Ægtemand";
$factarray["IDNO"] = "Person ID";
$factarray["IMMI"] = "Indvandret";
$factarray["LEGA"] = "Arving";
$factarray["MARB"] = "Lysning af giftemål";
$factarray["MARC"] = "Ægteskabskontrakt";
$factarray["MARL"] = "Kongebrev";
$factarray["MARR"] = "Ægteskab";
$factarray["MARS"] = "Ægtepagt";
$factarray["MEDI"] = "Medietype";
$factarray["NAME"] = "Navn";
$factarray["NATI"] = "Nationalitet";
$factarray["NATU"] = "Statsborgerskab";
$factarray["NCHI"] = "Antal børn";
$factarray["NICK"] = "Kaldenavn";
$factarray["NMR"]  = "Antal ægteskaber";
$factarray["NOTE"] = "Note";
$factarray["NPFX"] = "Præfiks";
$factarray["NSFX"] = "Suffiks";
$factarray["OBJE"] = "Multimedie objekt";
$factarray["OCCU"] = "Erhverv";
$factarray["ORDI"] = "Ritual rel. tjeneste";
$factarray["ORDN"] = "Ordineret rel. tjeneste";
$factarray["PAGE"] = "Dokument reference";
$factarray["PEDI"] = "Stamtavle";
$factarray["PLAC"] = "Stednavn";
$factarray["PHON"] = "Tlf. nr.";
$factarray["POST"] = "Postnummer";
$factarray["PROB"] = "Skifte";
$factarray["PROP"] = "Ejendom";
$factarray["PUBL"] = "Publikation";
$factarray["QUAY"] = "Datakvalitet (0-3)";
$factarray["REPO"] = "Opbevaringssted";
$factarray["REFN"] = "Referencenummer";
$factarray["RELA"] = "Slægtskab";
$factarray["RELI"] = "Religion";
$factarray["RESI"] = "Bopæl";
$factarray["RESN"] = "Restriktion";
$factarray["RETI"] = "Pension";
$factarray["RFN"]  = "Ref.nr. (statisk)";
$factarray["RIN"]  = "Ref.nr. (dynamisk)";
$factarray["ROLE"] = "Rolle i begivenhed";
$factarray["SEX"]  = "Køn";
$factarray["SLGC"] = "Besegling af barn (Mormoner)";
$factarray["SLGS"] = "Ægteskabsbesegling (Mormoner)";
$factarray["SOUR"] = "Kilde";
$factarray["SPFX"] = "Præfiks";
$factarray["SSN"]  = "Personnummer";
$factarray["STAE"] = "Stat/Region";
$factarray["STAT"] = "Status";
$factarray["SUBM"] = "Bidragsgiver/Afsender";
$factarray["SUBN"] = "Del af datasamling";
$factarray["SURN"] = "Efternavn";
$factarray["TEMP"] = "Tempel (Mormoner)";
$factarray["TEXT"] = "Kildetekst";
$factarray["TIME"] = "Klokkeslæt";
$factarray["TITL"] = "Titel";
$factarray["TYPE"] = "Type";
$factarray["WIFE"]  = "Hustru";
$factarray["WILL"] = "Testamente";
$factarray["EMAIL"] = "E-mail-adresse";
$factarray["_EMAIL"] = "E-mail adresse";
$factarray["_TODO"] = "Udestående gøremål";
$factarray["_UID"] = "Universal ID";
$factarray["_PRIM"]	= "Markeret som hovedbillede";
$factarray["_DBID"] = "Linket database ID";
$factarray["STAT:DATE"] = "Ændringsdato for status";
$factarray["FAMC:WIFE:GIVN"] = "Mors pigenavn";
$factarray["FAMC:HUSB:FAMC:HUSB:GIVN"] = "Bedstefars efternavn";
$factarray["FAMC:WIFE:FAMC:WIFE:GIVN"] = "Bedstemors pigenavn";
$factarray["FAMC:WIFE:FAMC:HUSB:GIVN"] = "Bedstefar på moders sides fornavn";
$factarray["FAMC:HUSB:FAMC:WIFE:GIVN"] = "Bedstemor på faders sides fornavn";
$factarray["FAMS:CHIL:BIRT:PLAC"] = "Barn's Fødsels sted";
$factarray["MARR:PLAC"] = "Bryllupssted";
$factarray["BURI:PLAC"] = "Begravelsessted";
$factarray["BAPM:PLAC"] = "Dåbssted";
$factarray["BIRT:PLAC"] = "Fødselssted";
$factarray["DEAT:PLAC"] = "Dødssted";
$factarray["CHR:PLAC"] = "Dåbssted";
$factarray["FAMS:SPOUSE:BIRT:PLAC"] = "Ægtefælles fødselssted";
$factarray["FAMC:HUSB:GIVN"] = "Fars efternavn";
$factarray["FAMS:SPOUSE:DEAT:PLAC"] = "Ægtefælles dødssted";
$factarray["FAMS:MARR:PLAC"] = "Bryllupssted";
$factarray[":BIRT:PLAC"] = "Fødselssted";
$factarray["FAMC:HUSB:OCCU"] = "Fars erhverv";
$factarray["FAMC:MARR:PLAC"] = "Forældres bryllupssted";
$factarray["FAMC:WIFE:BIRT:PLAC"] = "Mors fødselssted";
$factarray["FAMC:HUSB:BIRT:PLAC"] = "Fars fødselssted";
$factarray["FAMC:WIFE:SURN"] = "Mors efternavn";
$factarray["FAMC:HUSB:SURN"] = "Fars efternavn";

// These facts are specific to GEDCOM exports from Family Tree Maker
$factarray["_MDCL"] = "Helbredsoplysninger";
$factarray["_DEG"] 	= "Akademisk grad";
$factarray["_MILT"] = "Militærtjeneste";
$factarray["_SEPR"] = "Separeret";
$factarray["_DETS"] = "Ægtefælles død";
$factarray["CITN"] 	= "Statsborgerskab";
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
$factarray["_MREL"]	= "Relation til mor";
$factarray["_FREL"]	= "Relation til far";
$factarray["_MSTAT"] = "Ægteskab start status";
$factarray["_MEND"]	= "Ægteskab slut status";

// GEDCOM 5.5.1 related facts
$factarray["FAX"]	= "Fax";
$factarray["FACT"]	= "Fakta";
$factarray["WWW"]	= "Hjemmeside";
$factarray["MAP"]	= "Kort";
$factarray["LATI"]	= "Breddegrad";
$factarray["LONG"]	= "Længdegrad";
$factarray["FONE"]	= "Fonetisk";
$factarray["ROMN"]	= "Latinsk alfabet";

// PAF related facts
$factarray["_NAME"]	= "Navn på postmodtager";
$factarray["URL"]	= "URL (internet adresse)";
$factarray["_URL"] = "URL (internet adresse)";
$factarray["_HEB"]	= "Hebræisk";
$factarray["_SCBK"] = "Scrap bog";
$factarray["_TYPE"] = "Medietype";
$factarray["_SSHOW"] = "Slide show";

// Rootsmagic
$factarray["_SUBQ"]	= "Kort version";
$factarray["_BIBL"] = "Bibliografi";

// Reunion
$factarray["EMAL"]	= "E-mail-adresse";

// Other common customized facts
$factarray["_ADPF"] = "Adopteret af faderen";
$factarray["_ADPM"] = "Adopteret af moderen";
$factarray["_AKAN"] = "Også kendt som";
$factarray["_AKA"] 	= "Også kendt som";
$factarray["_BRTM"] = "Brit mila (Jødisk omskæring)";
$factarray["_COML"]	= "Samlevende";
$factarray["_EYEC"] = "Øjenfarve";
$factarray["_FNRL"] = "Begravelse";
$factarray["_HAIR"] = "Hårfarve";
$factarray["_HEIG"] = "Højde";
$factarray["_HOL"]  = "Holocaust";
$factarray["_INTE"] = "Urnenedsættelse";
$factarray["_MARI"] = "Ægteskabsintention";
$factarray["_MBON"] = "Ægteskabsløfte";
$factarray["_MEDC"] = "Helbredstilstand";
$factarray["_MILI"] = "Militærtjeneste";
$factarray["_NMR"] = "Ugift";
$factarray["_NLIV"] = "Lever ikke";
$factarray["_NMAR"] = "Aldrig gift";
$factarray["_PRMN"] = "Permanent nummer";
$factarray["_WEIG"] = "Vægt";
$factarray["_YART"] = "Yartzeit (Jødisk fødselsdag)";
$factarray["_MARNM"] = "Vielsesnavn";
$factarray["_MARNM_SURN"] = "Tilgiftet efternavn";
$factarray["_STAT"]	= "Civil status";
$factarray["COMM"]	= "Kommentar";

// Aldfaer related facts
$factarray["MARR_CIVIL"] = "Borgerlig vielse";
$factarray["MARR_PARTNERS"] = "Registreret partnerskab";
$factarray["MARR_RELIGIOUS"] = "Religiøs vielse";
$factarray["MARR_UNKNOWN"] = "Ukendt form for ægteskab";

$factarray["_HNM"] = "Hebræisk navn";

// Pseudo-facts for relatives
$factarray["_DEAT_SPOU"] = "Ægtefælles dødsfald";
$factarray["_CREM_SPOU"] = "Ægtefælles kremering";
$factarray["_BURI_SPOU"] = "Ægtefælles begravelse";

$factarray["_BIRT_CHIL"] = "Barns fødsel";
$factarray["_BURI_CHIL"] = "Et barns begravelse";
$factarray["_CREM_CHIL"] = "Et barns kremering";
$factarray["_MARR_CHIL"] = "Ægteskab for barn";
$factarray["_DEAT_CHIL"] = "Et barns død";

$factarray["_BIRT_GCHI"] = "Oldebarns fødsel";
$factarray["_MARR_GCHI"] = "Ægteskab på oldebarn";
$factarray["_DEAT_GCHI"] = "Oldebarns død";
$factarray["_CREM_GCHI"] = "Et barnebarns kremering";
$factarray["_CREM_GGCH"] = "Et oldebarns kremering";
$factarray["_BURI_GGCH"] = "Et oldebarns begravelse";
$factarray["_DEAT_GGCH"] = "Et oldebarns død";
$factarray["_MARR_GGCH"] = "Et oldebarns bryllup";
$factarray["_CREM_FATH"] = "En faders kremering";
$factarray["_BURI_FATH"] = "En faders begravelse";
$factarray["_BIRT_GGCH"] = "Et oldebarns fødsel";
$factarray["_CREM_MOTH"] = "En moders kremering";
$factarray["_BURI_MOTH"] = "En moders begravelse";
$factarray["_BURI_GCHI"] = "Et barnebarns begravelse";

$factarray["_MARR_FATH"] = "Faders ægteskab";
$factarray["_DEAT_FATH"] = "Faders død";

$factarray["_MARR_MOTH"] = "Moders ægteskab";
$factarray["_DEAT_MOTH"] = "Moders død";

$factarray["_BIRT_SIBL"] = "En søskendes fødsel<br />";
$factarray["_MARR_SIBL"] = "En søskendes ægteskab<br />";
$factarray["_DEAT_SIBL"] = "En søskendes dødsfald<br />";
$factarray["_CREM_SIBL"] = "En søskenes kremering";
$factarray["_BURI_SIBL"] = "En søskenes begravelse";

$factarray["_BIRT_HSIB"] = "En halvsøskendes fødsel<br />";
$factarray["_MARR_HSIB"] = "En halvsøskendes ægteskab<br />";
$factarray["_CREM_HSIB"] = "En halvsøskenes kremering";
$factarray["_BURI_HSIB"] = "En halvsøskenes begravelse";
$factarray["_BIRT_NEPH"] = "Fødsel af Nevø eller niece.";
$factarray["_CREM_NEPH"] = "En nevø eller nieces kremering";
$factarray["_CREM_NEPH"] = "En nevø eller nieces kremering";
$factarray["_BURI_NEPH"] = "En nevø eller nieces begravelse";
$factarray["_DEAT_NEPH"] = "En nevø eller nieces død";
$factarray["_MARR_NEPH"] = "En nevø eller nieces bryllup";
$factarray["_DEAT_HSIB"] = "En halvsøskendes dødsfald<br />";

$factarray["_DEAT_GPAR"] = "En bedsteforælders dødsfald<br />";
$factarray["_CREM_GGPA"] = "En oldeforælders kremering";
$factarray["_BURI_GGPA"] = "En oldeforælders begravelse";
$factarray["_DEAT_GGPA"] = "En oldeforælders død";
$factarray["_CREM_GPAR"] = "En bedsteforælders kremering";
$factarray["_BURI_GPAR"] = "En bedsteforælders begravelse";

$factarray["_BIRT_FSIB"] = "En faders søskendes fødsel<br />";
$factarray["_MARR_FSIB"] = "En faders søskendes ægteskab<br />";
$factarray["_DEAT_FSIB"] = "En faders søskendes dødsfald<br />";
$factarray["_CREM_FSIB"] = "En faders søskenes kremering";
$factarray["_BURI_FSIB"] = "En faders søskenes begravelse";

$factarray["_BIRT_MSIB"] = "En moders søskendes fødsel<br />";
$factarray["_CREM_MSIB"] = "En moders søskenes kremering";
$factarray["_BURI_MSIB"] = "En moders søskenes begravelse";
$factarray["_MARR_MSIB"] = "En moders søskendes ægteskab<br />";
$factarray["_DEAT_MSIB"] = "En moders søskendes dødsfald<br />";
$factarray["_CREM_COUS"] = "En fætter eller kusines kremering";
$factarray["_BURI_COUS"] = "En fætter eller kusines begravelse";

$factarray["_BIRT_COUS"] = "En kusines eller fætters fødsel";
$factarray["_MARR_COUS"] = "En kusines eller fætters ægteskab";
$factarray["_DEAT_COUS"] = "En kusines eller fætters dødsfald";
$factarray["_FAMC_EMIG"] = "Forældres emigrering";
$factarray["_FAMC_RESI"] = "Forældre bosted";

//-- PGV Only facts
$factarray["_THUM"]	= "Brug dette billede som miniaturebillede?";
$factarray["_PGVU"]	= "Sidst opdateret af";
$factarray["SERV"] = "Ekstern server";
$factarray["_GEDF"] = "GEDCOM-fil";
?>