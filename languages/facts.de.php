<?php
/**
 * German Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team
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
 * @author Jürgen Bach
 * @author Gerd Kroll
 * @author Kurt Norgaz
 * @author Peter Pluntke
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

// -- Define a fact array to map GEDCOM tags with their German values
$factarray["ABBR"]	= "Abkürzung";
$factarray["ADDR"]	= "Adresse";
$factarray["ADR1"]	= "Adresse 1";
$factarray["ADR2"]	= "Adresse 2";
$factarray["ADOP"]	= "Adoption";
$factarray["AFN"]	= "Vorfahren Nummer (AFN)";
$factarray["AGE"]	= "Alter";
$factarray["AGNC"]	= "Behörde";
$factarray["ALIA"]	= "Alias";
$factarray["ANCE"]	= "Vorfahren";
$factarray["ANCI"]	= "Ahnenforscher";
$factarray["ANUL"]	= "Annullierung";
$factarray["ASSO"]	= "Beziehung";
$factarray["AUTH"]	= "Verfasser";
$factarray["BAPL"]	= "HLT Taufe";
$factarray["BAPM"]	= "Taufe";
$factarray["BARM"]	= "Bar Mitzvah";
$factarray["BASM"]	= "Bas Mitzvah";
$factarray["BIRT"]	= "Geburt";
$factarray["BLES"]	= "Segen";
$factarray["BLOB"]	= "Binäres Daten-Objekt";
$factarray["BURI"]	= "Beerdigung";
$factarray["CALN"]	= "Rufnummer";
$factarray["CAST"]	= "Kaste / Soziale Stellung / Status";
$factarray["CAUS"]	= "Todesursache";
$factarray["CEME"]  = "Friedhof";
$factarray["CENS"]	= "Volkszählung";
$factarray["CHAN"]	= "Letzte Änderung";
$factarray["CHAR"]	= "Zeichensatz";
$factarray["CHIL"]	= "Kind";
$factarray["CHR"]	= "Taufe";
$factarray["CHRA"]	= "Taufe als Erwachsener";
$factarray["CITY"]	= "Stadt";
$factarray["CONF"]	= "Konfirmation";
$factarray["CONL"]	= "HLT Konfirmation";
$factarray["COPR"]	= "Copyright";
$factarray["CORP"]	= "Firma";
$factarray["CREM"]	= "Einäscherung";
$factarray["CTRY"]	= "Land";
$factarray["DATA"]	= "Daten";
$factarray["DATE"]	= "Datum";
$factarray["DEAT"]	= "Tod";
$factarray["DESC"]	= "Nachfahren";
$factarray["DESI"]	= "Nachfahrenforscher";
$factarray["DEST"]	= "Bestimmung";
$factarray["DIV"]	= "Scheidung";
$factarray["DIVF"]	= "Scheidung beantragt";
$factarray["DSCR"]	= "Beschreibung";
$factarray["EDUC"]	= "Ausbildung";
$factarray["EMIG"]	= "Auswanderung";
$factarray["ENDL"]	= "HLT Stiftung";
$factarray["ENGA"]	= "Verlobung";
$factarray["EVEN"]	= "Ereignis";
$factarray["FAM"]	= "Familie";
$factarray["FAMC"]	= "Familie des Kindes";
$factarray["FAMF"]	= "Familien-Akte";
$factarray["FAMS"]	= "Familie des Ehepartners";
$factarray["FCOM"]	= "Erstkommunion";
$factarray["FILE"]	= "Externe Datei";
$factarray["FORM"]	= "Format";
$factarray["GIVN"]	= "Vornamen";
$factarray["GRAD"]	= "Schulabschluß";
$factarray["HUSB"]  = "Ehemann";
$factarray["IDNO"]	= "Identifikationsnummer";
$factarray["IMMI"]	= "Einwanderung";
$factarray["LEGA"]	= "Erbe";
$factarray["MARB"]	= "Eheaufgebot";
$factarray["MARC"]	= "Ehevertrag";
$factarray["MARL"]	= "Ehegenehmigung";
$factarray["MARR"]	= "Ehe";
$factarray["MARS"]	= "Ehevertrag";
$factarray["MEDI"]	= "Multimedia Typ";
$factarray["NAME"]	= "Name";
$factarray["NATI"]	= "Nationalität";
$factarray["NATU"]	= "Einbürgerung";
$factarray["NCHI"]	= "Anzahl der Kinder";
$factarray["NICK"]	= "Spitzname";
$factarray["NMR"]	= "Anzahl der Ehen";
$factarray["NOTE"]	= "Bemerkung";
$factarray["NPFX"]	= "Präfix";
$factarray["NSFX"]	= "Namenszusatz";
$factarray["OBJE"]	= "Multimedia Objekt";
$factarray["OCCU"]	= "Beruf";
$factarray["ORDI"]	= "Anordnung";
$factarray["ORDN"]	= "Ordination";
$factarray["PAGE"]	= "Zitat Einzelheiten";
$factarray["PEDI"]	= "Stammbaum";
$factarray["PLAC"]	= "Ort";
$factarray["PHON"]	= "Telefon";
$factarray["POST"]	= "Postleitzahl";
$factarray["PROB"]	= "Testamentsbestätigung";
$factarray["PROP"]	= "Besitz";
$factarray["PUBL"]	= "Veröffentlichung";
$factarray["QUAY"]	= "Qualität der Daten";
$factarray["REPO"]	= "Lagerort";
$factarray["REFN"]	= "Referenz-Nummer";
$factarray["RELA"]	= "Verwandtschaft";
$factarray["RELI"]	= "Religion";
$factarray["RESI"]	= "Wohnort";
$factarray["RESN"]	= "Beschränkung";
$factarray["RETI"]	= "Ruhestand";
$factarray["RFN"]	= "Datensatznummer";
$factarray["RIN"]	= "Daten ID-Nummer";
$factarray["ROLE"]	= "Rolle";
$factarray["SEX"]	= "Geschlecht";
$factarray["SLGC"]	= "HLT Kindes-Siegelung";
$factarray["SLGS"]	= "HLT Ehepartner-Siegelung";
$factarray["SOUR"]	= "Quelle";
$factarray["SPFX"]	= "Nachnamenspräfix";
$factarray["SSN"]	= "Sozialversicherungs-Nummer";
$factarray["STAE"]	= "Staat";
$factarray["STAT"]	= "Status";
$factarray["SUBM"]	= "Übermittler";
$factarray["SUBN"]	= "Übermittlung";
$factarray["SURN"]	= "Nachname";
$factarray["TEMP"]	= "Tempel";
$factarray["TEXT"]	= "Text";
$factarray["TIME"]	= "Uhrzeit";
$factarray["TITL"]	= "Titel";
$factarray["TYPE"]	= "Typ";
$factarray["WIFE"]  = "Ehefrau";
$factarray["WILL"]	= "Testament";
$factarray["_EMAIL"]	= "Mail-Adresse";
$factarray["EMAIL"]	= "Mail-Adresse";
$factarray["_TODO"]	= "Unerledigt";
$factarray["_UID"]	= "Einmalig vorkommende ID-Nummer";
$factarray["_PRIM"]	= "Bevorzugtes Bild";
$factarray["_DBID"] = "ID der fremden Datenbank";

// These facts are used in specific contexts
$factarray["STAT:DATE"] = "Datum der Statusänderung";
$factarray["DATA:DATE"] = "Datum des Eintrages in der ursprünglichen Quelle";

//These facts are compounds for the view probabilities and the advanced search pages
$factarray["FAMC:HUSB:SURN"] = "Nachname des Vaters";
$factarray["FAMC:WIFE:SURN"] = "Nachname der Mutter";
$factarray["FAMC:HUSB:BIRT:PLAC"] = "Geburtsort des Vaters";
$factarray["FAMC:WIFE:BIRT:PLAC"] = "Geburtsort der Mutter";
$factarray["FAMC:MARR:PLAC"] = "Eheort der Eltern";
$factarray["FAMC:HUSB:OCCU"] = "Beruf des Vaters";
$factarray[":BIRT:PLAC"] = "Geburtsort";
$factarray["FAMS:MARR:PLAC"] = "Eheort";
$factarray["FAMS:MARR:DATE"] = "Ehedatum";
$factarray["FAMS:SPOUSE:DEAT:PLAC"] = "Todesort des Ehepartners";
$factarray["FAMC:HUSB:GIVN"] = "Vornamen des Vaters";
$factarray["FAMS:SPOUSE:BIRT:PLAC"] = "Geburtsort des Ehepartners";
$factarray["FAMC:WIFE:GIVN"] = "Vornamen der Mutter";
$factarray["FAMC:HUSB:FAMC:HUSB:GIVN"] = "Vornamen des väterlichen Großvaters";
$factarray["FAMC:WIFE:FAMC:WIFE:GIVN"] = "Vornamen der mütterlichen Großmutter";
$factarray["FAMC:WIFE:FAMC:HUSB:GIVN"] = "Vornamen des mütterlichen Großvaters";
$factarray["FAMC:HUSB:FAMC:WIFE:GIVN"] = "Vornamen der väterlichen Großmutter";
$factarray["FAMS:CHIL:BIRT:PLAC"] = "Geburtsort des Kindes";
$factarray["FAMS:NOTE"] = "Ehepartner Anmerkung";
$factarray["FAMS:CENS:DATE"] = "Volkszählung-Ehepartner Datum";
$factarray["FAMS:CENS:PLAC"] = "Volkszählung-Ehepartner Ort";
$factarray["FAMS:DIV:DATE"] = "Scheidung-Ehepartner Datum";
$factarray["FAMS:DIV:PLAC"] = "Scheidung-Ehepartner Ort";
$factarray["FAMS:SLGS:DATE"] = "HLT Ehepartner-Siegelung Datum";
$factarray["FAMS:SLGS:PLAC"] = "HLT Ehepartner-Siegelung Ort";
$factarray["FAMS:SLGS:TEMP"] = "HLT Ehepartner-Siegelung Tempel";

// These facts are all colon delimited
$factarray["BIRT:PLAC"] = "Geburtsort";
$factarray["BIRT:DATE"] = "Geburtsdatum";
$factarray["DEAT:PLAC"] = "Todesort";
$factarray["DEAT:DATE"] = "Todesdatum";
$factarray["CHR:PLAC"] = "Taufort";
$factarray["CHR:DATE"] = "Taufdatum";
$factarray["BAPM:PLAC"] = "Taufort";
$factarray["BAPM:DATE"] = "Taufdatum";
$factarray["BURI:PLAC"] = "Beerdigungsort";
$factarray["BURI:DATE"] = "Beerdigungsdatum";
$factarray["MARR:PLAC"] = "Eheort";
$factarray["MARR:DATE"] = "Ehedatum";

// These facts are specific to GEDCOM exports from Family Tree Maker
$factarray["_MDCL"]	= "Medizinische Information";
$factarray["_DEG"]	= "Akademischer Grad";
$factarray["_MILT"]	= "Militärdienst";
$factarray["_SEPR"]	= "Getrennt";
$factarray["_DETS"]	= "Tod eines Ehepartners";
$factarray["CITN"]	= "Staatsangehörigkeit";
$factarray["_FA1"]	= "Ereignis 1";
$factarray["_FA2"]	= "Ereignis 2";
$factarray["_FA3"]	= "Ereignis 3";
$factarray["_FA4"]	= "Ereignis 4";
$factarray["_FA5"]	= "Ereignis 5";
$factarray["_FA6"]	= "Ereignis 6";
$factarray["_FA7"]	= "Ereignis 7";
$factarray["_FA8"]	= "Ereignis 8";
$factarray["_FA9"]	= "Ereignis 9";
$factarray["_FA10"]	= "Ereignis 10";
$factarray["_FA11"]	= "Ereignis 11";
$factarray["_FA12"]	= "Ereignis 12";
$factarray["_FA13"]	= "Ereignis 13";
$factarray["_MREL"]	= "Verwandtschaft zur Mutter";
$factarray["_FREL"]	= "Verwandtschaft zum Vater";
$factarray["_MSTAT"]	= "Familienstand seit";
$factarray["_MEND"]	= "Heutiger Familienstand";
$factarray["_NAMS"]	= "Namensvetter";

// GEDCOM 5.5.1 related facts
$factarray["FAX"] = "FAX";
$factarray["FACT"] = "Ereignis";
$factarray["WWW"] = "Internetseite";
$factarray["MAP"] = "Karte";
$factarray["LATI"] = "Breitengrad";
$factarray["LONG"] = "Längengrad";
$factarray["FONE"] = "Phonetisch";
$factarray["ROMN"] = "Romanisiert";

// PAF related facts
$factarray["_NAME"] = "Name";
$factarray["URL"] = "Internet-Adresse";
$factarray["_URL"] = "Internet-Adresse";
$factarray["_HEB"] = "Hebräisch";
$factarray["_SCBK"] = "Sammelalbum";
$factarray["_TYPE"] = "Multimedia-Typ";
$factarray["_SSHOW"] = "Diavortrag";

// Rootsmagic
$factarray["_SUBQ"]= "Kurzfassung";
$factarray["_BIBL"] = "Bibliographie";

// Reunion
$factarray["EMAL"]	= "E-Mail Adresse";

// Other common customized facts
$factarray["_ADPF"]	= "Vom Vater adoptiert";
$factarray["_ADPM"]	= "Von der Mutter adoptiert";
$factarray["_AKAN"]	= "Auch bekannt als";
$factarray["_AKA"]	= "Auch bekannt als";
$factarray["_BRTM"]	= "Brit mila";
$factarray["_COML"]	= "eheähnliche Lebensgemeinschaft";
$factarray["_EYEC"]	= "Augenfarbe";
$factarray["_FNRL"]	= "Bestattung";
$factarray["_HAIR"]	= "Haarfarbe";
$factarray["_HEIG"]	= "Größe";
$factarray["_HOL"]  = "Holocaust";
$factarray["_INTE"]	= "Begraben";
$factarray["_MARI"]	= "Eheabsicht";
$factarray["_MBON"]	= "Verlobung";
$factarray["_MEDC"]	= "Gesundheitszustand";
$factarray["_MILI"]	= "Militär";
$factarray["_NMR"]	= "unverheiratet";
$factarray["_NLIV"]	= "nicht lebend";
$factarray["_NMAR"]	= "nie verheiratet";
$factarray["_PRMN"]	= "permanente Nummer";
$factarray["_WEIG"]	= "Gewicht";
$factarray["_YART"]	= "Yartzeit";
$factarray["_MARNM"]	= "Ehename";
$factarray["_MARNM_SURN"] = "Nachname nach der Ehe";
$factarray["_STAT"] = "Ehestand";
$factarray["COMM"]	= "Bemerkung";

// Aldfaer related facts
$factarray["MARR_CIVIL"] = "standesamtliche Ehe";
$factarray["MARR_RELIGIOUS"] = "kirchliche Ehe";
$factarray["MARR_PARTNERS"] = "eingetragene Lebensgemeinschaft";
$factarray["MARR_UNKNOWN"] = "Art der Ehe unbekannt";

$factarray["_HNM"] = "Hebräischer Name";

// Pseudo-facts for relatives
$factarray["_DEAT_SPOU"] = "Ehegatte: Tod";
$factarray["_BURI_SPOU"] = "Ehegatte: Beerdigung";
$factarray["_CREM_SPOU"] = "Ehegatte: Einäscherung";

$factarray["_BIRT_CHIL"] = "Kind: Geburt";
$factarray["_CHR_CHIL" ] = "Kind: Taufe";
$factarray["_BAPM_CHIL"] = "Kind: Taufe";
$factarray["__BRTM_CHIL"] = "Kind: Brit mila";
$factarray["_ADOP_CHIL"] = "Kind: Adoption";
$factarray["_MARR_CHIL"] = "Kind: Ehe";
$factarray["_MARB_CHIL"] = "Kind: Eheaufgebot";
$factarray["_DEAT_CHIL"] = "Kind: Tod";
$factarray["_BURI_CHIL"] = "Kind: Beerdigung";
$factarray["_CREM_CHIL"] = "Kind: Einäscherung";

$factarray["_BIRT_GCHI"] = "Enkel: Geburt";
$factarray["_CHR_GCHI" ] = "Enkel: Taufe";
$factarray["_BAPM_GCHI"] = "Enkel: Taufe";
$factarray["__BRTM_GCHI"] = "Enkel: Brit mila";
$factarray["_ADOP_GCHI"] = "Enkel: Adoption";
$factarray["_MARR_GCHI"] = "Enkel: Ehe";
$factarray["_MARB_GCHI"] = "Enkel: Eheaufgebot";
$factarray["_DEAT_GCHI"] = "Enkel: Tod";
$factarray["_BURI_GCHI"] = "Enkel: Beerdigung";
$factarray["_CREM_GCHI"] = "Enkel: Einäscherung";

$factarray["_BIRT_GGCH"] = "Urenkel: Geburt";
$factarray["_CHR_GGCH" ] = "Urenkel: Taufe";
$factarray["_BAPM_GGCH"] = "Urenkel: Taufe";
$factarray["__BRTM_GGCH"] = "Urenkel: Brit mila";
$factarray["_ADOP_GGCH"] = "Urenkel: Adoption";
$factarray["_MARR_GGCH"] = "Urenkel: Ehe";
$factarray["_MARB_GGCH"] = "Urenkel: Eheaufgebot";
$factarray["_DEAT_GGCH"] = "Urenkel: Tod";
$factarray["_BURI_GGCH"] = "Urenkel: Beerdigung";
$factarray["_CREM_GGCH"] = "Urenkel: Einäscherung";

$factarray["_MARR_FATH"] = "Vater: Ehe";
$factarray["_MARB_FATH"] = "Vater: Eheaufgebot";
$factarray["_DEAT_FATH"] = "Vater: Tod";
$factarray["_BURI_FATH"] = "Vater: Beerdigung";
$factarray["_CREM_FATH"] = "Vater: Einäscherung";

$factarray["_MARR_FAMC"] = "Eltern: Ehe";
$factarray["_MARB_FAMC"] = "Eltern: Eheaufgebot";

$factarray["_MARR_MOTH"] = "Mutter: Ehe";
$factarray["_MARB_MOTH"] = "Mutter: Eheaufgebot";
$factarray["_DEAT_MOTH"] = "Mutter: Tod";
$factarray["_BURI_MOTH"] = "Mutter: Beerdigung";
$factarray["_CREM_MOTH"] = "Mutter: Einäscherung";

$factarray["_BIRT_SIBL"] = "Geschwister: Geburt";
$factarray["_CHR_SIBL" ] = "Geschwister: Taufe";
$factarray["_BAPM_SIBL"] = "Geschwister: Taufe";
$factarray["__BRTM_SIBL"] = "Geschwister: Brit mila";
$factarray["_ADOP_SIBL"] = "Geschwister: Adoption";
$factarray["_MARR_SIBL"] = "Geschwister: Ehe";
$factarray["_MARB_SIBL"] = "Geschwister: Eheaufgebot";
$factarray["_DEAT_SIBL"] = "Geschwister: Tod";
$factarray["_BURI_SIBL"] = "Geschwister: Beerdigung";
$factarray["_CREM_SIBL"] = "Geschwister: Einäscherung";

$factarray["_BIRT_HSIB"] = "Halbgeschwister: Geburt";
$factarray["_CHR_HSIB" ] = "Halbgeschwister: Taufe";
$factarray["_BAPM_HSIB"] = "Halbgeschwister: Taufe";
$factarray["__BRTM_HSIB"] = "Halbgeschwister: Brit mila";
$factarray["_ADOP_HSIB"] = "Halbgeschwister: Adoption";
$factarray["_MARR_HSIB"] = "Halbgeschwister: Ehe";
$factarray["_MARB_HSIB"] = "Halbgeschwister: Eheaufgebot";
$factarray["_DEAT_HSIB"] = "Halbgeschwister: Tod";
$factarray["_BURI_HSIB"] = "Halbgeschwister: Beerdigung";
$factarray["_CREM_HSIB"] = "Halbgeschwister: Einäscherung";

$factarray["_BIRT_NEPH"] = "Neffe oder Nichte: Geburt";
$factarray["_CHR_NEPH" ] = "Neffe oder Nichte: Taufe";
$factarray["_BAPM_NEPH"] = "Neffe oder Nichte: Taufe";
$factarray["__BRTM_NEPH"] = "Neffe oder Nichte: Brit mila";
$factarray["_ADOP_NEPH"] = "Neffe oder Nichte: Adoption";
$factarray["_MARR_NEPH"] = "Neffe oder Nichte: Ehe";
$factarray["_MARB_NEPH"] = "Neffe oder Nichte: Eheaufgebot";
$factarray["_DEAT_NEPH"] = "Neffe oder Nichte: Tod";
$factarray["_BURI_NEPH"] = "Neffe oder Nichte: Beerdigung";
$factarray["_CREM_NEPH"] = "Neffe oder Nichte: Einäscherung";

$factarray["_DEAT_GPAR"] = "Großeltern: Tod";
$factarray["_BURI_GPAR"] = "Großeltern: Beerdigung";
$factarray["_CREM_GPAR"] = "Großeltern: Einäscherung";

$factarray["_DEAT_GGPA"] = "Urgroßeltern: Tod";
$factarray["_BURI_GGPA"] = "Urgroßeltern: Beerdigung";
$factarray["_CREM_GGPA"] = "Urgroßeltern: Einäscherung";

$factarray["_BIRT_FSIB"] = "Vaters Geschwister: Geburt";
$factarray["_CHR_FSIB" ] = "Vaters Geschwister: Taufe";
$factarray["_BAPM_FSIB"] = "Vaters Geschwister: Taufe";
$factarray["__BRTM_FSIB"] = "Vaters Geschwister: Brit mila";
$factarray["_ADOP_FSIB"] = "Vaters Geschwister: Adoption";
$factarray["_MARR_FSIB"] = "Vaters Geschwister: Ehe";
$factarray["_MARB_FSIB"] = "Vaters Geschwister: Eheaufgebot";
$factarray["_DEAT_FSIB"] = "Vaters Geschwister: Tod";
$factarray["_BURI_FSIB"] = "Vaters Geschwister: Beerdigung";
$factarray["_CREM_FSIB"] = "Vaters Geschwister: Einäscherung";

$factarray["_BIRT_MSIB"] = "Mutters Geschwister: Geburt";
$factarray["_CHR_MSIB" ] = "Mutters Geschwister: Taufe";
$factarray["_BAPM_MSIB"] = "Mutters Geschwister: Taufe";
$factarray["__BRTM_MSIB"] = "Mutters Geschwister: Brit mila";
$factarray["_ADOP_MSIB"] = "Mutters Geschwister: Adoption";
$factarray["_MARR_MSIB"] = "Mutters Geschwister: Ehe";
$factarray["_MARB_MSIB"] = "Mutters Geschwister: Eheaufgebot";
$factarray["_DEAT_MSIB"] = "Mutters Geschwister: Tod";
$factarray["_BURI_MSIB"] = "Mutters Geschwister: Beerdigung";
$factarray["_CREM_MSIB"] = "Mutters Geschwister: Einäscherung";

$factarray["_BIRT_COUS"] = "Cousin: Geburt";
$factarray["_CHR_COUS" ] = "Cousin: Taufe";
$factarray["_BAPM_COUS"] = "Cousin: Taufe";
$factarray["__BRTM_COUS"] = "Cousin: Brit mila";
$factarray["_ADOP_COUS"] = "Cousin: Adoption";
$factarray["_MARR_COUS"] = "Cousin: Ehe";
$factarray["_MARB_COUS"] = "Cousin: Eheaufgebot";
$factarray["_DEAT_COUS"] = "Cousin: Tod";
$factarray["_BURI_COUS"] = "Cousin: Beerdigung";
$factarray["_CREM_COUS"] = "Cousin: Einäscherung";

$factarray["_FAMC_EMIG"] = "Auswanderung der Eltern";
$factarray["_FAMC_RESI"] = "Wohnort der Eltern";

//-- PGV Only facts
$factarray["_THUM"]	= "Dieses Bild auch als Miniaturbild verwenden?";
$factarray["_PGVU"]	= "von"; // Zuletzt geändert von";
$factarray["SERV"] = "Fremder Server";
$factarray["_GEDF"] = "GEDCOM Datei";

?>
