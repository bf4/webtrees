<?php
/**
 * Italian Language file
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @author Lorenzo Simionato, Fabio Parri
 * @package PhpGedView
 * @subpackage Languages
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

// -- Define a fact array to map GEDCOM tags with their Italian values
$factarray["ABBR"] = "Abbreviazione";
$factarray["ADDR"] = "Indirizzo";
$factarray["ADR1"] = "Indirizzo 1";
$factarray["ADR2"] = "Indirizzo 2";
$factarray["ADOP"] = "Adozione";
$factarray["AFN"] = "Ancestral File Number (AFN)";
$factarray["AGE"] = "Eta' ";
$factarray["AGNC"] = "Istituzione";
$factarray["ALIA"] = "Alias";
$factarray["ANCE"] = "Antenati";
$factarray["ANCI"] = "Interesse Antenati";
$factarray["ANUL"] = "Annullamento";
$factarray["ASSO"] = "Associati";
$factarray["AUTH"] = "Autore";
$factarray["BAPL"] = "Battesimo Mormone";
$factarray["BAPM"] = "Battesimo";
$factarray["BARM"] = "Bar Mitzvah";
$factarray["BASM"] = "Bas Mitzvah";
$factarray["BIRT"] = "Nascita";
$factarray["BLES"] = "Benedizione";
$factarray["BLOB"] = "Oggetto binario contenente i dati";
$factarray["BURI"] = "Sepoltura";
$factarray["CALN"] = "Numero";
$factarray["CAST"] = "Casta / Stato Sociale";
$factarray["CAUS"] = "Causa della morte";
$factarray["CAUS"] = "Cauda della morte";
$factarray["CENS"] = "Censimento";
$factarray["CHAN"] = "Ultima modifica";
$factarray["CHAR"] = "Set di caratteri";
$factarray["CHIL"] = "Bambino";
$factarray["CHR"] = "Cresima";
$factarray["CHRA"] = "Cresima da Adulto";
$factarray["CITY"] = "Citta' ";
$factarray["CONF"] = "Comunione";
$factarray["CONL"] = "Comunione Mormone";
$factarray["COPR"] = "Copyright";
$factarray["CORP"] = "Compagnia / Societa' ";
$factarray["CREM"] = "Cremazione";
$factarray["CTRY"] = "Nazione";
$factarray["DATA"] = "Dati";
$factarray["DATE"]	= "Data";
$factarray["DEAT"] = "Morte";
$factarray["DESC"] = "Discendenti";
$factarray["DESI"] = "Interesse Discendenti";
$factarray["DEST"] = "Destinazione";
$factarray["DIV"] = "Divorzio";
$factarray["DIVF"] = "Dossier Divorzio";
$factarray["DSCR"] = "Descrizione";
$factarray["EDUC"] = "Educazione";
$factarray["EMIG"] = "Emigrazione";
$factarray["ENDL"] = "Costituzione Mormone di dote";
$factarray["ENGA"] = "Fidanzamento";
$factarray["EVEN"] = "Evento";
$factarray["FAM"] = "Famiglia";
$factarray["FAMC"] = "Famiglia da bambino";
$factarray["FAMF"] = "Dossier familiare";
$factarray["FAMS"] = "Famiglia da coniuge";
$factarray["FCOM"] = "Prima comunione";
$factarray["FILE"] = "Dossier esterno";
$factarray["FORM"] = "Formato";
$factarray["GIVN"] = "Nome proprio";
$factarray["GRAD"] = "Laurea";
$factarray["IDNO"] = "Identificativo";
$factarray["IMMI"] = "Immigrazione";
$factarray["LEGA"] = "Legatario";
$factarray["MARB"] = "Pubblicazioni matrimoniali";
$factarray["MARC"] = "Contratto di matrimonio";
$factarray["MARL"] = "Licenza di matrimonio";
$factarray["MARR"] = "Matrimonio";
$factarray["MARS"] = "Accordo pre-matrimoniale";
$factarray["NAME"] = "Nome";
$factarray["NATI"] = "Nazionalita' ";
$factarray["NATU"] = "Naturalizzazione";
$factarray["NCHI"] = "Numero di bambini";
$factarray["NICK"] = "Soprannome";
$factarray["NMR"] = "Numero di matrimoni";
$factarray["NOTE"] = "Note";
$factarray["NPFX"] = "Prefisso";
$factarray["NSFX"] = "Suffisso";
$factarray["OBJE"] = "Oggetto multimediale";
$factarray["OCCU"] = "Occupazione";
$factarray["ORDI"] = "Cerimonia";
$factarray["ORDN"] = "Ordinazione";
$factarray["PAGE"] = "Dettagli";
$factarray["PEDI"] = "Antenati";
$factarray["PLAC"] = "Posto";
$factarray["PHON"] = "Telefono";
$factarray["POST"] = "C.A.P.";
$factarray["PROB"] = "Probate";
$factarray["PROP"] = "Proprieta' ";
$factarray["PUBL"] = "Pubblicazione";
$factarray["QUAY"] = "Qualita'  dei dati";
$factarray["REFN"] = "Numero di riferimento";
$factarray["RELI"] = "Religione";
$factarray["RESI"] = "Residenza";
$factarray["RESN"] = "Restrizione";
$factarray["RETI"] = "Pensionamento";
$factarray["RFN"] = "Numero di archivio del registor";
$factarray["RIN"] = "Numero ID";
$factarray["ROLE"] = "Ruolo";
$factarray["SEX"] = "Sesso";
$factarray["SLGC"] = "Suggellatura del Bambino (Chiesa Mormone)";
$factarray["SLGS"] = "Suggellatura al Coniuge (Chiesa Mormone)";
$factarray["SOUR"] = "Origine";
$factarray["SPFX"] = "Prefisso del Cognome";
$factarray["SSN"] = "Numero di Previdenza Sociale";
$factarray["STAE"] = "Stato";
$factarray["STAT"] = "Stato";
$factarray["SUBM"] = "Inviato da:";
$factarray["SUBN"] = "Dati da trattare";
$factarray["SURN"] = "Cognome";
$factarray["TEMP"] = "Tempio";
$factarray["TEXT"] = "Testo";
$factarray["TIME"] = "Ora";
$factarray["TITL"] = "Titolo";
$factarray["WILL"] = "Testamento";
$factarray["_EMAIL"] = "Indirizzo e-mail";
$factarray["EMAIL"] = "Indirizzo E-mail";
$factarray["_TODO"] = "Item Da Fare";
$factarray["_UID"] = "Identificatore Universale";
$factarray["_PGVU"]	= "Ultima modifica di";

$factarray["_MILT"]	= "Servizio Militare";
// These facts are specific to GEDCOM exports from Family Tree Maker
$factarray["_MDCL"]	= "Medical";
$factarray["_SEPR"] = "Separato";
$factarray["FACT"] = "Fatto";
$factarray["MAP"] = "Mappa";
$factarray["_BIBL"] = "Bibliografia";
$factarray["_DETS"] = "Morte di un coniuge";
$factarray["CITN"] = "Cittadinanza";

// Other common customized facts
$factarray["_ADPF"] = "Adottato dal padre";
$factarray["_ADPM"] = "Adottato dalla madre";
$factarray["_AKAN"] = "Soprannominato";
$factarray["_EYEC"] = "Colore degli occhi";
$factarray["_FNRL"] = "Funerale";
$factarray["_HAIR"] = "Colore dei capelli";
$factarray["_HEIG"] = "Altezza";
$factarray["_NMR"] = "Non sposato";
$factarray["_NLIV"] = "Non in vita";
$factarray["_NMAR"] = "Mai sposato";
$factarray["_WEIG"] = "Peso";
$factarray["_HNM"] = "Nome ebraico";
$factarray["MARR_UNKNOWN"] = "Matrimonio di tipo sconosciuto";
$factarray["MARR_RELIGIOUS"] = "Matrimonio Religioso";
$factarray["MARR_CIVIL"] = "Matrimonio Civile";
$factarray["COMM"]	= "Commento";

$factarray["CEME"]  = "Cimitero";
$factarray["HUSB"]  = "Marito";
$factarray["WIFE"]  = "Moglie";
$factarray["FAMC:HUSB:SURN"] = "Cognome del padre";
$factarray["FAMC:WIFE:SURN"] = "Cognome della madre";
$factarray["FAMC:HUSB:BIRT:PLAC"] = "Luogo di nascita del padre";
$factarray["FAMC:WIFE:BIRT:PLAC"] = "Luogo di nascita della madre";
$factarray["FAMC:MARR:PLAC"] = "Luogo di matrimonio dei genitori";
$factarray[":BIRT:PLAC"] = "Luogo di nascita";
$factarray["FAMS:MARR:PLAC"] = "Luogo di matrimonio";
$factarray["FAMS:MARR:DATE"] = "Data di matrimonio";
$factarray["FAMS:SPOUSE:DEAT:PLAC"] = "Luogo di morte del coniuge";
$factarray["FAMC:HUSB:GIVN"] = "Nome del padre";
$factarray["FAMS:SPOUSE:BIRT:PLAC"] = "Luogo di nascita del coniuge";
$factarray["FAMC:WIFE:GIVN"] = "Nome della madre";
$factarray["BIRT:PLAC"] = "Luogo di nascita";
$factarray["BIRT:DATE"] = "Data di nascita";
$factarray["DEAT:PLAC"] = "Luogo di morte";
$factarray["DEAT:DATE"] = "Data di morte";
$factarray["CHR:PLAC"] = "Luogo di battesimo";
$factarray["CHR:DATE"] = "Data di battesimo";
$factarray["BAPM:DATE"] = "Data di battesimo";
$factarray["BAPM:PLAC"] = "Luogo di battesimo";
$factarray["BURI:PLAC"] = "Luogo di sepoltura";
$factarray["BURI:DATE"] = "Data di sepoltura";
$factarray["MARR:PLAC"] = "Luogo di matrimonio";
$factarray["MARR:DATE"] = "Data di matrimonio";
$factarray["FAX"] = "FAX";
$factarray["LATI"] = "Latitudine";
$factarray["LONG"] = "Longitudine";
$factarray["URL"] = "URL Web";
$factarray["_URL"] = "URL Web";
$factarray["_AKA"] 	= "Soprannominato";
$factarray["_DEAT_SPOU"] = "Morte del coniuge";
$factarray["_BURI_SPOU"] = "Sepoltura del coniuge";
$factarray["_CREM_SPOU"] = "Cremazione del coniuge";
$factarray["_BIRT_CHIL"] = "Nascita di un figlio";
$factarray["_CHR_CHIL" ] = "Battesimo di un figlio";
$factarray["_BAPM_CHIL"] = "Battesimo di un figlio";
$factarray["__BRTM_CHIL"] = "Brit Mila di un figlio";
$factarray["_ADOP_CHIL"] = "Adozione di un figlio ";
$factarray["_MARR_CHIL"] = "Matrimonio di un figlio";
$factarray["_DEAT_CHIL"] = "Morte di un figlio";
$factarray["_BURI_CHIL"] = "Sepoltura di un figlio";
$factarray["_CREM_CHIL"] = "Cremazione di un figlio";
$factarray["SERV"] = "Server remoto";
$factarray["_GEDF"] = "File GEDCOM";
$factarray["_FAMC_RESI"] = "Residenza dei genitori";
?>
