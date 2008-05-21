<?php
/**
 * Polish texts
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team
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
 * @author Michael Paluchowski, http://genealogy.nethut.pl
 * @author Tymoteusz Motylewski www.motylewscy.com
 * @author Katarzyna Adamska <adamska_k AT wp DOT pl>
 * @author Łukasz Wileński <wooc@users.sourceforge.net>
 * @package PhpGedView
 * @subpackage Languages
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Nie można uzyskać bezpośredniego dostępu do pliku.";
	exit;
}

// -- Tabela faktów GedCom
$factarray["ABBR"] 	= "Skrót";
$factarray["ADDR"] 	= "Adres";
$factarray["ADR1"] 	= "Adres 1";
$factarray["ADR2"] 	= "Adres 2";
$factarray["ADOP"] 	= "Adopcja";
$factarray["AFN"] 	= "Numer pliku genealogicznego (AFN)";
$factarray["AGE"] 	= "W wieku";
$factarray["AGNC"] 	= "Agencja";
$factarray["ALIA"] 	= "Znany(a) także jako";
$factarray["ANCE"] 	= "Przodkowie";
$factarray["ANCI"] 	= "Udział przodków";
$factarray["ANUL"] 	= "Unieważnienie";
$factarray["ASSO"] 	= "Powiązanie";
$factarray["AUTH"] 	= "Autor";
$factarray["BAPL"] 	= "Chrzest mormoński";
$factarray["BAPM"] 	= "Chrzest";
$factarray["BARM"] 	= "Bar micwa";
$factarray["BASM"] 	= "Bas micwa";
$factarray["BIRT"] 	= "Urodziny";
$factarray["BLES"] 	= "Błogosławieństwo";
$factarray["BLOB"] 	= "Obiekt binarny";
$factarray["BURI"] 	= "Pogrzeb";
$factarray["CALN"] 	= "Numer źródła";
$factarray["CAST"] 	= "Kasta / Status społeczny";
$factarray["CAUS"] 	= "Przyczyna śmierci";
$factarray["CEME"] 	= "Cmentarz";
$factarray["CENS"] 	= "Spis ludności";
$factarray["CHAN"] 	= "Ostatnia zmiana";
$factarray["CHAR"] 	= "Zestaw znaków";
$factarray["CHIL"] 	= "Dziecko";
$factarray["CHR"] 	= "Chrzest";
$factarray["CHRA"] 	= "Chrzest dorosłego";
$factarray["CITY"] 	= "Miasto";
$factarray["CONF"] 	= "Bierzmowanie";
$factarray["CONL"] 	= "Bierzmowanie mormońskie";
$factarray["COPR"] 	= "Prawa autorskie";
$factarray["CORP"] 	= "Korporacja / Firma";
$factarray["CREM"] 	= "Kremacja";
$factarray["CTRY"] 	= "Kraj";
$factarray["DATA"] 	= "Dane";
$factarray["DATE"] 	= "Data";
$factarray["DEAT"] 	= "Śmierć";
$factarray["DESC"] 	= "Potomkowie";
$factarray["DESI"] 	= "Udział potomków";
$factarray["DEST"] 	= "Cel";
$factarray["DIV"] 	= "Rozwód";
$factarray["DIVF"] 	= "Uzyskanie rozwodu";
$factarray["DSCR"] 	= "Opis";
$factarray["EDUC"] 	= "Wykształcenie";
$factarray["EMIG"] 	= "Emigracja";
$factarray["ENDL"] 	= "Obdarowanie mormońskie";
$factarray["ENGA"] 	= "Zaręczyny";
$factarray["EVEN"] 	= "Wydarzenie";
$factarray["FAM"] 	= "Rodzina";
$factarray["FAMC"] 	= "W rodzinie jako dziecko";
$factarray["FAMF"] 	= "Plik rodziny";
$factarray["FAMS"] 	= "W rodzinie jako małżonek";
$factarray["FCOM"] 	= "Pierwsza Komunia";
$factarray["FILE"] 	= "Plik zewnętrzny";
$factarray["FORM"] 	= "Format";
$factarray["GIVN"] 	= "Imiona";
$factarray["GRAD"] 	= "Ukończenie szkoły";
$factarray["HUSB"]  = "Mąż";
$factarray["IDNO"] 	= "Numer identyfikacyjny";
$factarray["IMMI"] 	= "Imigracja";
$factarray["LEGA"] 	= "Legatariusz / Zapisobiorca";
$factarray["MARB"] 	= "Zapowiedzi przedmałżeńskie";
$factarray["MARC"] 	= "Kontrakt małżeński";
$factarray["MARL"] 	= "Akt ślubu";
$factarray["MARR"] 	= "Ślub";
$factarray["MARS"] 	= "Ugoda małżeńska";
$factarray["MEDI"] 	= "Typ multimediów";
$factarray["NAME"] 	= "Nazwisko i imię";
$factarray["NATI"] 	= "Narodowość";
$factarray["NATU"] 	= "Naturalizacja";
$factarray["NCHI"] 	= "Liczba dzieci";
$factarray["NICK"] 	= "Przezwisko";
$factarray["NMR"] 	= "Liczba małżeństw";
$factarray["NOTE"] 	= "Notatka";
$factarray["NPFX"] 	= "Przedrostek";
$factarray["NSFX"] 	= "Przyrostek";
$factarray["OBJE"] 	= "Obiekt multimedialny";
$factarray["OCCU"] 	= "Zawód";
$factarray["ORDI"]	= "Obrządek";
$factarray["ORDN"] 	= "Święcenia";
$factarray["PAGE"] 	= "Szczegóły cytatu";
$factarray["PEDI"] 	= "Pochodzenie";
$factarray["PLAC"] 	= "Miejsce";
$factarray["PHON"] 	= "Telefon";
$factarray["POST"] 	= "Kod pocztowy";
$factarray["PROB"] 	= "Poświadczenie autentyczności testamentu";
$factarray["PROP"] 	= "Własność";
$factarray["PUBL"] 	= "Publikacja";
$factarray["QUAY"] 	= "Jakość danych";
$factarray["REPO"] 	= "Repozytorium";
$factarray["REFN"] 	= "Numer referencyjny";
$factarray["RELA"] 	= "Pokrewieństwo";
$factarray["RELI"] 	= "Wyznanie";
$factarray["RESI"] 	= "Miejsce zamieszkania";
$factarray["RESN"] 	= "Ograniczenie";
$factarray["RETI"] 	= "Przejście na emeryturę";
$factarray["RFN"] 	= "Numer katalogowy wpisu";
$factarray["RIN"] 	= "Identyfikator wpisu";
$factarray["ROLE"] 	= "Rola";
$factarray["SEX"] 	= "Płeć";
$factarray["SLGC"] 	= "Mormońskie Naznaczenie Dziecka";
$factarray["SLGS"] 	= "Mormońskie Naznaczenie Małżonka";
$factarray["SOUR"] 	= "Źródło";
$factarray["SPFX"] 	= "Przedrostek nazwiska";
$factarray["SSN"] 	= "Numer ubezpieczenia (SSN)";
$factarray["STAE"] 	= "Stan";
$factarray["STAT"] 	= "Status";
$factarray["SUBM"] 	= "Nadesłane przez";
$factarray["SUBN"] 	= "Wpis";
$factarray["SURN"] 	= "Nazwisko";
$factarray["TEMP"] 	= "Świątynia";
$factarray["TEXT"] 	= "Tekst";
$factarray["TIME"] 	= "Czas";
$factarray["TITL"] 	= "Tytuł";
$factarray["TYPE"] 	= "Typ";
$factarray["WIFE"] 	= "Żona";
$factarray["WILL"] 	= "Testament";
$factarray["_EMAIL"] 	= "Adres email";
$factarray["EMAIL"] 	= "Adres email";
$factarray["_TODO"] 	= "Do zrobienia";
$factarray["_UID"] 	= "Uniwersalny identyfikator";
$factarray["_PGVU"] 	= "przez";
$factarray["SERV"] 	= "Zdalny serwer";
$factarray["_GEDF"] 	= "Plik GEDCOM";
$factarray["_PRIM"] 	= "Wyróżnione zdjęcie";
$factarray["_DBID"] = "Indentyfikator dołączonej bazy danych";
$factarray["STAT:DATE"] = "Data zmiany statusu";
$factarray["FAMC:HUSB:SURN"] = "Nazwisko ojca";
$factarray["FAMC:WIFE:SURN"] = "Nazwisko matki";
$factarray["FAMC:HUSB:BIRT:PLAC"] = "Miejsce urodzenia ojca";
$factarray["FAMC:WIFE:BIRT:PLAC"] = "Miejsce urodzenia matki";
$factarray["FAMC:MARR:PLAC"] = "Miejsce ślubu rodziców";
$factarray["FAMC:HUSB:OCCU"] = "Zawód ojca";
$factarray[":BIRT:PLAC"] = "Miejsce urodzenia";
$factarray["FAMS:MARR:PLAC"] = "Miejsce ślubu";
$factarray["FAMS:SPOUSE:DEAT:PLAC"] = "Miejsce śmierci małżonka";
$factarray["FAMC:HUSB:GIVN"] = "Imię ojca";
$factarray["FAMS:SPOUSE:BIRT:PLAC"] = "Miejsce urodzenia małżonka";
$factarray["FAMC:WIFE:GIVN"] = "Imię matki";
$factarray["FAMC:HUSB:FAMC:HUSB:GIVN"] = "Imię dziadka stryjecznego";
$factarray["FAMC:WIFE:FAMC:WIFE:GIVN"] = "Imię babci ciotecznej";
$factarray["FAMC:WIFE:FAMC:HUSB:GIVN"] = "Imię dziadka wujecznego";
$factarray["FAMC:HUSB:FAMC:WIFE:GIVN"] = "Imię babci stryjecznej";
$factarray["FAMS:CHIL:BIRT:PLAC"] = "Miejsce urodzin dziecka";
$factarray["BIRT:PLAC"] = "Miejsce urodzenia";
$factarray["DEAT:PLAC"] = "Miejsce śmierci";
$factarray["CHR:PLAC"] = "Miejsce chrztu";
$factarray["BAPM:PLAC"] = "Miejsce chrztu";
$factarray["BURI:PLAC"] = "Miejsce pochówku";
$factarray["MARR:PLAC"] = "Miejsce ślubu";
$factarray["_THUM"] 	= "Czy użyć tego zdjęcia do miniaturki?";

// Fakty specyficzne dla eksportu GedCom z programu Family Tree Maker
$factarray["_MDCL"] 	= "Służba medyczna";
$factarray["_DEG"] 	= "Stopień";
$factarray["_MILT"] 	= "Służba wojskowa";
$factarray["_SEPR"] 	= "Separacja";
$factarray["_DETS"] 	= "Śmierć jednego z małżonków";
$factarray["CITN"] 	= "Obywatelstwo";
$factarray["_FA1"]	= "Fakt 1";
$factarray["_FA2"]	= "Fakt 2";
$factarray["_FA3"]	= "Fakt 3";
$factarray["_FA4"]	= "Fakt 4";
$factarray["_FA5"]	= "Fakt 5";
$factarray["_FA6"]	= "Fakt 6";
$factarray["_FA7"]	= "Fakt 7";
$factarray["_FA8"]	= "Fakt 8";
$factarray["_FA9"]	= "Fakt 9";
$factarray["_FA10"]	= "Fakt 10";
$factarray["_FA11"]	= "Fakt 11";
$factarray["_FA12"]	= "Fakt 12";
$factarray["_FA13"]	= "Fakt 13";
$factarray["_MREL"]	= "Związek z matką";
$factarray["_FREL"]	= "Związek z ojcem";
$factarray["_MSTAT"]	= "Status na początku małżeństwa";
$factarray["_MEND"]	= "Status na końcu małżeństwa";

// Fakty związane z GedCom 5.5.1 
$factarray["FAX"] 	= "Fax";
$factarray["FACT"] 	= "Fakt";
$factarray["WWW"] 	= "Strona WWW";
$factarray["MAP"] 	= "Mapa";
$factarray["LATI"] 	= "Szerokość geograficzna";
$factarray["LONG"] 	= "Długość geograficzna";
$factarray["FONE"] 	= "Fonetycznie";
$factarray["ROMN"] 	= "Zlatynizowane";

// Fakty zwi�zane z PAF
$factarray["_NAME"] 	= "Nazwisko adresowe";
$factarray["URL"] 	= "Adres URL";
$factarray["_URL"] 	= "Adres URL";
$factarray["_HEB"] 	= "Hebrajskie";
$factarray["_SCBK"] 	= "Kronika";
$factarray["_TYPE"] 	= "Rodzaj mediów";
$factarray["_SSHOW"] 	= "Pokaz slajdów";

// Rootsmagic
$factarray["_SUBQ"]	= "Wersja skrócona";
$factarray["_BIBL"] 	= "Bibliografia";
$factarray["EMAL"]	= "Adres email";

// Other common customized facts
$factarray["_ADPF"]	= "Adopcja przez ojca";
$factarray["_ADPM"]	= "Adopcja przez matkę";
$factarray["_AKAN"]	= "Znany(a) także jako";
$factarray["_AKA"] 	= "Znany(a) także jako";
$factarray["_BRTM"]	= "Brit Mila";
$factarray["_COML"]	= "Małżeństwo zwyczajowe";
$factarray["_EYEC"]	= "Kolor oczu";
$factarray["_FNRL"]	= "Pogrzeb";
$factarray["_HAIR"]	= "Kolor włosów";
$factarray["_HEIG"]	= "Wzrost";
$factarray["_HOL"]  	= "Holokaust";
$factarray["_INTE"]	= "Pochowany(a)";
$factarray["_MARI"]	= "Zapowiedzi";
$factarray["_MBON"]	= "Intercyza";
$factarray["_MEDC"]	= "Stan zdrowia";
$factarray["_MILI"]	= "Służba wojskowa";
$factarray["_NMR"]	= "Nieżonaty/niezamężna";
$factarray["_NLIV"]	= "Nie żyje";
$factarray["_NMAR"]	= "Nigdy nie żonaty/zamężna";
$factarray["_PRMN"]	= "Stały numer";
$factarray["_WEIG"]	= "Waga";
$factarray["_YART"]	= "Nadchodzące wydarzenie";
$factarray["_MARNM"]	= "Po ślubie";
$factarray["_MARNM_SURN"] = "Nazwisko po ślubie";
$factarray["_STAT"]	= "Stan cywilny";
$factarray["MARR_RELIGIOUS"] 	= "Ślub kościelny";
$factarray["MARR_PARTNERS"] 	= "Zarejestrowane partnerstwo";
$factarray["MARR_UNKNOWN"] 	= "Małżeństwo nieznanego typu";
$factarray["_DEAT_GPAR"] 	= "Śmierć babci/dziadka";
$factarray["_BURI_GPAR"] = "Pogrzeb dziadka/babci";
$factarray["_CREM_GPAR"] = "Kremacja dziadka/babci";
$factarray["_DEAT_GGPA"] = "Śmierć pradziadka/prababci";
$factarray["_BURI_GGPA"] = "Pogrzeb pradziadka/prababci";
$factarray["_CREM_GGPA"] = "Kremacja pradziadka/prababci";
$factarray["_BIRT_FSIB"] = "Narodziny brata/siostry ojca";
$factarray["_MARR_FSIB"] = "Ślub brata/siostry ojca";
$factarray["_DEAT_FSIB"] = "Śmierć brata/siostry ojca";
$factarray["_BURI_FSIB"] = "Pogrzeb rodzeństwa ojca";
$factarray["_CREM_FSIB"] = "Kremacja rodzeństwa ojca";
$factarray["_BIRT_MSIB"] = "Narodziny brata/siostry matki";
$factarray["_MARR_MSIB"] = "Ślub brata/siostry matki";
$factarray["_DEAT_MSIB"] = "Śmierć brata/siostry matki";
$factarray["_BURI_MSIB"] = "Pogrzeb rodzeństwa matki";
$factarray["_CREM_MSIB"] = "Kremacja rodzeństwa matki";
$factarray["_BIRT_COUS"] = "Narodziny pierwszego kuzyna/kuzynki";
$factarray["_MARR_COUS"] = "Ślub pierwszego kuzyna/kuzynki";
$factarray["_DEAT_COUS"] = "Śmierć pierwszego kuzyna/kuzynki";
$factarray["_BURI_COUS"] = "Pogrzeb kuzyna";
$factarray["_CREM_COUS"] = "Kremacja kuzyna";
$factarray["_FAMC_EMIG"] = "Emigracja rodziców";
$factarray["_FAMC_RESI"] = "Miejsce zamieszkania rodziców";
$factarray["_HNM"] 		= "Nazwisko hebrajskie";
$factarray["_DEAT_SPOU"] 	= "Śmierć małżonka";
$factarray["_BURI_SPOU"] = "Pogrzeb współmałżonka";
$factarray["_CREM_SPOU"] = "Kremacja współmałżonka";
$factarray["_BIRT_SIBL"] 	= "Narodziny brata/siostry";
$factarray["_MARR_SIBL"] = "Ślub brata/siostry";
$factarray["_DEAT_SIBL"] = "Śmierć brata/siostry";
$factarray["_BURI_SIBL"] = "Pogrzeb brata/siostry";
$factarray["_CREM_SIBL"] = "Kremacja brata/siostry";
$factarray["_BIRT_HSIB"] = "Narodziny brata przyrodniego/siostry przyrodniej";
$factarray["_MARR_HSIB"] = "Ślub brata przyrodniego/siostry przyrodniej";
$factarray["_DEAT_HSIB"] = "Śmierć brata przyrodniego/siostry przyrodniej";
$factarray["_BURI_HSIB"] = "Pogrzeb rodzeństwa przyrodniego";
$factarray["_CREM_HSIB"] = "Kremacja rodzeństwa przyrodniego";
$factarray["_BIRT_NEPH"] = "Narodziny siostrzeńca/bratanka lub siostrzenicy/bratanicy";
$factarray["_MARR_NEPH"] = "Ślub siostrzeńca/bratanka lub siostrzenicy/bratanicy";
$factarray["_DEAT_NEPH"] = "Śmierć siostrzeńca/bratanka lub siostrzenicy/bratanicy";
$factarray["_BURI_NEPH"] = "Pogrzeb bratanka lub bratanicy";
$factarray["_CREM_NEPH"] = "Kremacja bratanka lub bratanicy";
$factarray["_DEAT_MOTH"] 	= "Śmierć matki";
$factarray["_BURI_MOTH"] = "Pogrzeb matki";
$factarray["_CREM_MOTH"] = "Kremacja matki";
$factarray["_MARR_MOTH"] 	= "Ślub matki";
$factarray["_DEAT_FATH"] 	= "Śmierć ojca";
$factarray["_BURI_FATH"] = "Pogrzeb ojca";
$factarray["_CREM_FATH"] = "Kremacja ojca";
$factarray["_MARR_FATH"] 	= "Ślub ojca";
$factarray["_DEAT_GCHI"] 	= "Śmierć wnuka/wnuczki";
$factarray["_BURI_GCHI"] = "Pogrzeb wnuka";
$factarray["_CREM_GCHI"] = "Kremacja wnuka";
$factarray["_BIRT_GGCH"] = "Urodziny prawnuka";
$factarray["_MARR_GGCH"] = "Ślub prawnuka";
$factarray["_DEAT_GGCH"] = "Śmierć prawnuka";
$factarray["_BURI_GGCH"] = "Pogrzeb prawnuka";
$factarray["_CREM_GGCH"] = "Kremacja prawnuka";
$factarray["_MARR_GCHI"] 	= "Ślub wnuka/wnuczki";
$factarray["_BIRT_GCHI"] 	= "Narodziny wnuka/wnuczki";
$factarray["_DEAT_CHIL"] 	= "Śmierć dziecka";
$factarray["_BURI_CHIL"] = "Pogrzeb dziecka";
$factarray["_CREM_CHIL"] = "Kremacja dziecka";
$factarray["_MARR_CHIL"] 	= "Ślub dziecka";
$factarray["_BIRT_CHIL"] 	= "Narodziny dziecka";
$factarray["MARR_CIVIL"] 	= "Ślub cywilny";
$factarray["COMM"]		= "Komentarz";

?>
