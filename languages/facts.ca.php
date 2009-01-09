<?php
/**
 * Catalan texts
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team. All rights reserved.
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
 * @subpackage Languages
 * @author Antoni Planas i Vilà
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

// -- Define a fact array to map Gedcom tags with their Catalan values

$factarray["ABBR"] = "Abreujament";
$factarray["ADDR"] = "Direcció";
$factarray["ADR1"] = "Direcció 1";
$factarray["ADR2"] = "Direcció 2";
$factarray["ADOP"] = "Adopció";
$factarray["AFN"]  = "Número de fitxer 'Ancestral' (AFN)";
$factarray["AGE"]  = "Edat";
$factarray["AGNC"] = "Agència";
$factarray["ALIA"] = "Àlies";
$factarray["ANCE"] = "Avantpassats";
$factarray["ANCI"] = "Avantpassats insignes";
$factarray["ANUL"] = "Anul·lació";
$factarray["ASSO"] = "Associats";
$factarray["AUTH"] = "Autor";
$factarray["BAPL"] = "Bateig mormó";
$factarray["BAPM"] = "Bateig";
$factarray["BARM"] = "Bar Mitzvà";
$factarray["BASM"] = "Bas Mitzvà";
$factarray["BIRT"] = "Naixement";
$factarray["BLES"] = "Benedicció";
$factarray["BLOB"] = "Objecte de dades binàries";
$factarray["BURI"] = "Enterrament";
$factarray["CALN"] = "Telèfon";
$factarray["CAST"] = "Casta/Estatus social";
$factarray["CAUS"] = "Causa de la mort";
$factarray["CEME"] = "Cementiri";
$factarray["CENS"] = "Empadronament";
$factarray["CHAN"] = "Darrer canvi";
$factarray["CHAR"] = "Joc de Caràcters";
$factarray["CHIL"] = "Fill";
$factarray["CHR"]  = "Bateig";
$factarray["CHRA"] = "Bateig adult";
$factarray["CITY"] = "Població";
$factarray["CONF"] = "Confirmació";
$factarray["CONL"] = "Confirmació mormona";
$factarray["COPR"] = "Copyright";
$factarray["CORP"] = "Corporació / Empresa";
$factarray["CREM"] = "Cremació";
$factarray["CTRY"] = "País";
$factarray["DATA"] = "Dades";
$factarray["DATE"] = "Data";
$factarray["DEAT"] = "Defunció";
$factarray["DESC"] = "Descendents";
$factarray["DESI"] = "Descendents insignes";
$factarray["DEST"] = "Destí";
$factarray["DIV"]  = "Divorci";
$factarray["DIVF"] = "Divorci arxivat";
$factarray["DSCR"] = "Descripció";
$factarray["EDUC"] = "Educació";
$factarray["EMIG"] = "Emigració";
$factarray["ENDL"] = "Dot mormona";
$factarray["ENGA"] = "Prometatge";
$factarray["EVEN"] = "Esdeveniment";
$factarray["FAM"]  = "Família";
$factarray["FAMC"] = "Família com a fill";
$factarray["FAMF"] = "Fitxer de Família";
$factarray["FAMS"] = "Família com a cònjuge";
$factarray["FCOM"] = "Primera Comunió";
$factarray["FILE"] = "Fitxer Extern";
$factarray["FORM"] = "Format";
$factarray["GIVN"] = "Nom/s de Pila";
$factarray["GRAD"] = "Graduació";
$factarray["HUSB"]  = "Marit";
$factarray["IDNO"] = "Número d'Identificació";
$factarray["IMMI"] = "Immigració";
$factarray["LEGA"] = "Legatari/a";
$factarray["MARB"] = "Amonestacions";
$factarray["MARC"] = "Capítols Matrimonials";
$factarray["MARL"] = "Llicència Matrimonial";
$factarray["MARR"] = "Enllaç";
$factarray["MARS"] = "Compromís Matrimonial";
$factarray["MEDI"] = "Tipus de multimèdia";
$factarray["NAME"] = "Nom";
$factarray["NATI"] = "Nacionalitat";
$factarray["NATU"] = "Nacionalització";
$factarray["NCHI"] = "Nombre de fills";
$factarray["NICK"] = "Àlies";
$factarray["NMR"]  = "Nombre de matrimonis";
$factarray["NOTE"] = "Nota";
$factarray["NPFX"] = "Prefix";
$factarray["NSFX"] = "Sufix";
$factarray["OBJE"] = "Objecte multimèdia";
$factarray["OCCU"] = "Ocupació";
$factarray["ORDI"] = "Ordenació mormona";
$factarray["ORDN"] = "Ordenació";
$factarray["PAGE"] = "Detalls de la citació";
$factarray["PEDI"] = "Avantpassats";
$factarray["PLAC"] = "Lloc";
$factarray["PHON"] = "Telèfon";
$factarray["POST"] = "Codi Postal";
$factarray["PROB"] = "Verificació de Testament";
$factarray["PROP"] = "Propietat";
$factarray["PUBL"] = "Publicació";
$factarray["QUAY"] = "Qualitat de les dades";
$factarray["REPO"] = "Arxiu";
$factarray["REFN"] = "Número de referència";
$factarray["RELA"] = "Parentiu";
$factarray["RELI"] = "Religió";
$factarray["RESI"] = "Residència";
$factarray["RESN"] = "Restricció";
$factarray["RETI"] = "Jubilació";
$factarray["RFN"]  = "Número de fitxer de registre";
$factarray["RIN"]  = "Número ID";
$factarray["ROLE"] = "Rol";
$factarray["SEX"]  = "Sexe";
$factarray["SLGC"] = "Segellament mormó d'un fill";
$factarray["SLGS"] = "Segellament matrimonial mormó";
$factarray["SOUR"] = "Font";
$factarray["SPFX"] = "Prefix del Cognom";
$factarray["SSN"]  = "Número Seguretat Social";
$factarray["STAE"] = "Situació";
$factarray["STAT"] = "Estatus";
$factarray["SUBM"] = "Presentador";
$factarray["SUBN"] = "Presentació";
$factarray["SURN"] = "Cognom";
$factarray["TEMP"] = "Temple";
$factarray["TEXT"] = "Text";
$factarray["TIME"] = "Hora";
$factarray["TITL"] = "Títol";
$factarray["TYPE"] = "Tipus";
$factarray["WIFE"]  = "Esposa";
$factarray["TYPE"] = "Tipus";
$factarray["WILL"] = "Testament";
$factarray["_EMAIL"]= "Correu electrònic";
$factarray["EMAIL"] = "Correu electrònic";
$factarray["_TODO"] = "Fer Ítem";
$factarray["_UID"]  = "Identificador Universal";
$factarray["_PRIM"] = "Imatge principal";
$factarray["_DBID"] = "ID de la base de dades vinculada";
$factarray["STAT:DATE"] = "Data del canvi d'estatus";
$factarray["FAMC:HUSB:SURN"] = "Cognom del pare";
$factarray["FAMC:WIFE:SURN"] = "Cognom de la mare";
$factarray["FAMC:HUSB:BIRT:PLAC"] = "Lloc de naixement del pare";
$factarray["FAMC:WIFE:BIRT:PLAC"] = "Lloc de naixement de la mare";
$factarray["FAMC:MARR:PLAC"] = "Lloc de casament dels pares";
$factarray["FAMC:HUSB:OCCU"] = "Ofici del pare";
$factarray[":BIRT:PLAC"] = "Lloc de naixement";
$factarray["FAMS:MARR:PLAC"] = "Lloc de casament";
$factarray["FAMS:SPOUSE:DEAT:PLAC"] = "Lloc de defunció del cònjuge";
$factarray["FAMC:HUSB:GIVN"] = "Nom de pila del pare";
$factarray["FAMS:SPOUSE:BIRT:PLAC"] = "Lloc de naixement del cònjuge";
$factarray["FAMC:WIFE:GIVN"] = "Nom de pila de la mare";
$factarray["FAMC:HUSB:FAMC:HUSB:GIVN"] = "Nom de pila de l'avi patern";
$factarray["FAMC:WIFE:FAMC:WIFE:GIVN"] = "Nom de pila de l'àvia materna";
$factarray["FAMC:WIFE:FAMC:HUSB:GIVN"] = "Nom de pila de l'avi matern";
$factarray["FAMC:HUSB:FAMC:WIFE:GIVN"] = "Nom de pila de l'àvia paterna";
$factarray["FAMS:CHIL:BIRT:PLAC"] = "Lloc de naixement del fill/a";
$factarray["BIRT:PLAC"] = "Lloc de naixement";
$factarray["DEAT:PLAC"] = "Lloc de defunció";
$factarray["CHR:PLAC"] = "Lloc de bateig";
$factarray["BAPM:PLAC"] = "Lloc de bateig";
$factarray["BURI:PLAC"] = "Lloc d'enterrament";
$factarray["MARR:PLAC"] = "Lloc de casament";


// These facts are specific to gedcom exports from Family Tree Maker
$factarray["_MDCL"]	= "Metge";
$factarray["_DEG"]	= "Grau";
$factarray["_MILT"]	= "Servei Militar";
$factarray["_SEPR"]	= "Separat";
$factarray["_DETS"]	= "Defunció d'un Cònjuge";
$factarray["CITN"]	= "Ciutadania";
$factarray["_FA1"] 	= "Esdeveniment 1";
$factarray["_FA2"] 	= "Esdeveniment 2";
$factarray["_FA3"] 	= "Esdeveniment 3";
$factarray["_FA4"] 	= "Esdeveniment 4";
$factarray["_FA5"] 	= "Esdeveniment 5";
$factarray["_FA6"] 	= "Esdeveniment 6";
$factarray["_FA7"] 	= "Esdeveniment 7";
$factarray["_FA8"] 	= "Esdeveniment 8";
$factarray["_FA9"] 	= "Esdeveniment 9";
$factarray["_FA10"] 	= "Esdeveniment 10";
$factarray["_FA11"] 	= "Esdeveniment 11";
$factarray["_FA12"] 	= "Esdeveniment 12";
$factarray["_FA13"] 	= "Esdeveniment 13";
$factarray["_MREL"] 	= "Enllaç amb la Mare";
$factarray["_FREL"] 	= "Enllaç amb el Pare";
$factarray["_FA1"] 	= "Esdeveniment: Matrimoni";
$factarray["_MSTAT"] 	= "Inici d'estatus matrimonial";
$factarray["_MEND"] 	= "Casament, fi de situació";

// GEDCOM 5.5.1 related facts
$factarray["FAX"] 	= "FAX";
$factarray["FACT"] 	= "Esdeveniment";
$factarray["WWW"] 	= "Pàgina Web";
$factarray["MAP"] 	= "Mapa";
$factarray["LATI"] 	= "Latitud";
$factarray["LONG"] 	= "Longitud";
$factarray["FONE"] 	= "Fonètic";
$factarray["ROMN"] 	= "Romanitzat";

// PAF related facts
$factarray["_NAME"] 	= "Nom de correu";
$factarray["URL"] 	= "Web URL";
$factarray["_URL"] 	= "Web URL";
$factarray["_HEB"] 	= "Hebreu";
$factarray["_SCBK"] 	= "Àlbum de retalls";
$factarray["_TYPE"] 	= "Tipus de multimèdia";
$factarray["_SSHOW"] 	= "Presentació de diapositives";

// Rootsmagic
$factarray["_SUBQ"]	= "Versió breu";
$factarray["_BIBL"] 	= "Bibliografia";

// Reunion
$factarray["EMAL"]	= "Adreça de correu electrònic";

// Other common customized facts
$factarray["_ADPF"]	= "Adoptat pel pare";
$factarray["_ADPM"]	= "Adoptat per la mare";
$factarray["_AKAN"]	= "Àlies";
$factarray["_AKA"] 	= "Àlies";
$factarray["_BRTM"]	= "Circumcisió";
$factarray["_COML"]	= "Matrimoni Civil";
$factarray["_EYEC"]	= "Color d'ulls";
$factarray["_FNRL"]	= "Funeral";
$factarray["_HAIR"]	= "Color dels cabells";
$factarray["_HEIG"]	= "Alçada";
$factarray["_HOL"]  = "Holocaustre";
$factarray["_INTE"]	= "Enterrat";
$factarray["_MARI"]	= "Petició de matrimoni";
$factarray["_MBON"]	= "Compromís matrimonial";
$factarray["_MEDC"]	= "Condició mèdica";
$factarray["_MILI"]	= "Militar";
$factarray["_NMR"]	= "No casats";
$factarray["_NLIV"]	= "No és viu";
$factarray["_NMAR"]	= "Mai casat";
$factarray["_PRMN"]	= "Número Permanent";
$factarray["_SEPR"]	= "Separat";
$factarray["_WEIG"]	= "Pes";
$factarray["_YART"]	= "Yartzeit";
$factarray["_MARNM"]	= "Nom de casada";
$factarray["_MARNM_SURN"] = "Cognom de casada";
$factarray["_STAT"]	= "Situació matrimonial";
$factarray["COMM"]	= "Comentari";

// Aldfaer related facts
$factarray["MARR_CIVIL"] 	= "Matrimoni civil";
$factarray["MARR_RELIGIOUS"] 	= "Matrimoni religiós";
$factarray["MARR_PARTNERS"] 	= "Parella de fet";
$factarray["MARR_UNKNOWN"] 	= "Tipus d'enllaç desconegut";

$factarray["_HNM"] = "Hebrew Name";

// Pseudo-facts for relatives
$factarray["_DEAT_SPOU"] = "Defunció del cònjuge";

$factarray["_BIRT_CHIL"] = "Naixement d'un/a fill/a";
$factarray["_MARR_CHIL"] = "Casament d'un/a fill/a";
$factarray["_DEAT_CHIL"] = "Defunció d'un/a fill/a";

$factarray["_BIRT_GCHI"] = "Naixement d'un/a nét/a";
$factarray["_MARR_GCHI"] = "Casament d'un/a nét/a";
$factarray["_DEAT_GCHI"] = "Defunció d'un/a nét/a";
$factarray["_BIRT_GGCH"] = "Naixement d'un/a besnét/a";
$factarray["_MARR_GGCH"] = "Casament d'un/na besnét/ta";
$factarray["_DEAT_GGCH"] = "Defunció d'un/a besnét/a";

$factarray["_MARR_FATH"] = "Casament del pare";
$factarray["_DEAT_FATH"] = "Defunció del pare";

$factarray["_MARR_MOTH"] = "Casament de la mare";
$factarray["_DEAT_MOTH"] = "Defunció de la mare";

$factarray["_BIRT_SIBL"] = "Naixement d'un/a germà/na";
$factarray["_MARR_SIBL"] = "Casament d'un/a germà/na";
$factarray["_DEAT_SIBL"] = "Defunció d'un/a germà/na";

$factarray["_BIRT_HSIB"] = "Naixement d'un/a germanastre/a";
$factarray["_MARR_HSIB"] = "Casament d'un/a germanastre/a";
$factarray["_DEAT_HSIB"] = "Defunció d'un/a germanastre/a";
$factarray["_BIRT_NEPH"] = "Naixement d'un nét o néta";
$factarray["_MARR_NEPH"] = "Casament d'un nét o néta";
$factarray["_DEAT_NEPH"] = "Defunció d'un nét o néta";

$factarray["_DEAT_GPAR"] = "Defunció d'un/a avi/a";
$factarray["_DEAT_GGPA"] = "Defunció d'un besavi";

$factarray["_BIRT_FSIB"] = "Naixement d'un oncle/tieta patern/a";
$factarray["_MARR_FSIB"] = "Casament d'un oncle/tieta patern/a";
$factarray["_DEAT_FSIB"] = "Defunció d'un oncle/tieta patern/a";

$factarray["_BIRT_MSIB"] = "Naixement d'un oncle/tieta matern/a";
$factarray["_MARR_MSIB"] = "Casament d'un oncle/tieta matern/a";
$factarray["_DEAT_MSIB"] = "Defunció d'un oncle/tieta matern/a";

$factarray["_BIRT_COUS"] = "Naixement d'un/a cosí/na germà/na";
$factarray["_MARR_COUS"] = "Casament d'un/a cosí/na germà/na";
$factarray["_DEAT_COUS"] = "Defunció d'un/a cosí/na germà/na";
$factarray["_FAMC_EMIG"] = "Emigració dels pares";
$factarray["_FAMC_RESI"] = "Residència dels pares";

//-- PGV Only facts
$factarray["_THUM"]	= "Emprar aquesta imatge per la miniatura?";
$factarray["_PGVU"]	= "Autor";
$factarray["SERV"] 	= "Servidor remot";
$factarray["_GEDF"] = "Fitxer GEDCOM";
?>
