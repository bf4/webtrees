<?php
/**
 * see http://unstats.un.org/unsd/methods/m49/m49alpha.htm
 * see http://www.foreignword.com/countries/  for a comprehensive list, with translations
 * see http://susning.nu/Landskod  (list #7) for another list, taken from ISO standards
 * see http://helpdesk.rootsweb.com/codes for a comprehensive list of Chapman codes.
 * see http://www.rootsweb.com/~wlsgfhs/ChapmanCodes.htm for another list of Chapman codes
 *
 * The list that follows is the list of Chapman country codes, with additions from the
 * other sources mentioned above.
 *
 * These codes do not appear in the two Chapman lists cited:
 *		ALA		Åland Islands
 *		COD		Congo (Brazzaville)		This country was known as Zaire
 *		NFK		Norfolk Island
 *		PRI		Puerto Rico				Chapman lists this as a state of the USA
 *		SCG		Serbia and Montenegro	Chapman lists these separately
 *		TLS		Timor-Leste
 *		UMI		US Minor Outlying Islands
 *		VIR		US Virgin Islands		Chapman lists this as a state of the USA
 *		
 * These Chapman country codes do not appear in the list following:
 *		UEL		United Empire Loyalist		This is NOT a country or region, it's
 *											a group of people
 *		UK		United Kingdom				This is the only two-letter country code,
 *											and GBR or one of its components should be
 *											used instead.
 *		SLK		Slovakia					This code, listed in the last source cited,
 *											should be SVK
 *		SLO		Slovenia					This code, listed in the last source cited,
 *											should be SVN
 *		SAM		South America				This code, listed in the last source cited,
 *											is not precise enough
 *		TMP		East Timor					Official name is TLS "Timor-Leste"
 *		HOL		Holland						Official name is NLD "Netherlands"
 *		ESM		Western Samoa				Official name is WSM "Samoa"
 *											
 * @package PhpGedView
 * @subpackage Languages
 * @version $Id$
 */
if (preg_match("/countries\...\.php$/", $_SERVER["SCRIPT_NAME"])>0) {
		print "You cannot access a language file directly.";
		exit;
}
$countries["ABW"]="Aruba";
$countries["ACA"]="Acadia";
$countries["AFG"]="Afghanistan";
$countries["AGO"]="Angola";
$countries["AIA"]="Anguilla";
$countries["ALA"]="Åland";
$countries["ALB"]="Albanien";
$countries["AND"]="Andorra";
$countries["ANT"]="Nederländska Antillerna";
$countries["ARE"]="Förenade arabemiraten";
$countries["ARG"]="Argentina";
$countries["ARM"]="Armenien";
$countries["ASM"]="Amerikanska Samoa";
$countries["ATA"]="Antarktis";
$countries["ATF"]="Fransla s�dra teritorierna";
$countries["ATG"]="Antigua och Barbuda";
$countries["AUS"]="Australien";
$countries["AUT"]="Österrike";
$countries["AZE"]="Azerbajdzjan";
$countries["AZR"]="Azorererna";

$countries["BDI"]="Burundi";
$countries["BEL"]="Belgien";
$countries["BEN"]="Benin";
$countries["BFA"]="Burkina Faso";
$countries["BGD"]="Bangladesh";
$countries["BGR"]="Bulgarien";
$countries["BHR"]="Bahrain";
$countries["BHS"]="Bahamas";
$countries["BIH"]="Bosnien-Hercegovina";
$countries["BLR"]="Vitryssland";
$countries["BLZ"]="Belize";
$countries["BMU"]="Bermuda";
$countries["BOL"]="Bolivia";
$countries["BRA"]="Brasilien";
$countries["BRB"]="Barbados";
$countries["BRN"]="Brunei Darussalam";
$countries["BTN"]="Bhutan";
$countries["BVT"]="Bouvetön";
$countries["BWA"]="Botswana";
$countries["BWI"]="Britiska väst Indien";

$countries["CAF"]="Centralafrikanska republiken";
$countries["CAN"]="Kanada";
$countries["CAP"]="Kap Kolonien?";
$countries["CCK"]="Kokosöarna";
$countries["CHE"]="Schweiz";
$countries["CHI"]="Kanalöarna?";
$countries["CHL"]="Chile";
$countries["CHN"]="Kina";
$countries["CIV"]="Elfenbenskusten";
$countries["CMR"]="Kamerun";
$countries["COD"]="Kongo-Kinshasa";
$countries["COG"]="Kongo-Brazzaville";
$countries["COK"]="Cooköarna";
$countries["COL"]="Colombia";
$countries["COM"]="Comorerna";
$countries["CPV"]="Kap Verde";
$countries["CRI"]="Costa Rica";
$countries["CSK"]="Tjekoslovakien";
$countries["CUB"]="Kuba";
$countries["CXR"]="Julön";
$countries["CYM"]="Caymanöarna";
$countries["CYP"]="Cypern";
$countries["CZE"]="Tjeckien";

$countries["DEU"]="Tyskland";
$countries["DJI"]="Djibouti";
$countries["DMA"]="Dominica";
$countries["DNK"]="Danmark";
$countries["DOM"]="Dominikanska republiken";
$countries["DZA"]="Algeriet";


$countries["ECU"]="Ecuador";
$countries["EGY"]="Egypten";
$countries["EIR"]="Eire";
$countries["ENG"]="England";
$countries["ERI"]="Eritrea";
$countries["ESH"]="Västsahara";
$countries["ESP"]="Spanien";
$countries["EST"]="Estland";
$countries["ETH"]="Etiopien";

$countries["FIN"]="Finland";
$countries["FJI"]="Fiji";
$countries["FLD"]="Flandern";
$countries["FLK"]="Falklandsöarna";
$countries["FRA"]="Frankrike";
$countries["FRO"]="Färöarna";
$countries["FSM"]="Mikronesien";

$countries["GAB"]="Gabon";
$countries["GBR"]="Storbritannien";
$countries["GEO"]="Georgien";
$countries["GHA"]="Ghana";
$countries["GIB"]="Gibraltar";
$countries["GIN"]="Guinea";
$countries["GLP"]="Guadeloupe";
$countries["GMB"]="Gambia";
$countries["GNB"]="Guinea Bissau";
$countries["GNQ"]="Ekvatorialguinea";
$countries["GRC"]="Grekland";
$countries["GRD"]="Grenada";
$countries["GRL"]="Grönland";
$countries["GTM"]="Guatemala";
$countries["GUF"]="Franska Guyana";
$countries["GUM"]="Guam";
$countries["GUY"]="Guyana";

$countries["HKG"]="Hong Kong";
$countries["HMD"]="Heardön och McDonaldsöarna";
$countries["HND"]="Honduras";
$countries["HRV"]="Kroatien";
$countries["HTI"]="Haiti";
$countries["HUN"]="Ungern";

$countries["IDN"]="Indonesien";
$countries["IND"]="Indien";
$countries["IOT"]="Brittiska territoriet i Indiska Oceanen";
$countries["IRL"]="Irland";

$countries["IRN"]="Iran";
$countries["IRQ"]="Irak";
$countries["ISL"]="Island";
$countries["ISR"]="Israel";
$countries["ITA"]="Italien";

$countries["JAM"]="Jamaica";
$countries["JOR"]="Jordanien";
$countries["JPN"]="Japan";

$countries["KAZ"]="Kazakstan";
$countries["KEN"]="Kenya";
$countries["KGZ"]="Kirgizistan";
$countries["KHM"]="Kambodja";
$countries["KIR"]="Kiribati";
$countries["KNA"]="St Christopher och Nevis";
$countries["KOR"]="Sydkorea";
$countries["KWT"]="Kuwait";

$countries["LAO"]="Laos";
$countries["LBN"]="Libanon";
$countries["LBR"]="Liberia";
$countries["LBY"]="Libyen";
$countries["LCA"]="St Lucia";
$countries["LIE"]="Liechtenstein";
$countries["LKA"]="Sri Lanka";
$countries["LSO"]="Lesotho";
$countries["LTU"]="Litauen";
$countries["LUX"]="Luxemburg";
$countries["LVA"]="Lettland";

$countries["MAC"]="Macau";
$countries["MAR"]="Marocko";
$countries["MCO"]="Monaco";
$countries["MDA"]="Moldavien";
$countries["MDG"]="Madagaskar";
$countries["MDV"]="Maldiverna";
$countries["MEX"]="Mexiko";
$countries["MHL"]="Marshallöarna";
$countries["MKD"]="Makedonien";
$countries["MLI"]="Mali";
$countries["MLT"]="Malta";
$countries["MMR"]="Burma";
$countries["MNG"]="Mongoliet";
$countries["MNP"]="Nordmarianerna";
$countries["MNT"]="Montenegro";
$countries["MOZ"]="Moçambique";
$countries["MRT"]="Mauretanien";
$countries["MSR"]="Montserrat";
$countries["MTQ"]="Martinique";
$countries["MUS"]="Mauritius";
$countries["MWI"]="Malawi";
$countries["MYS"]="Malaysia";
$countries["MYT"]="Mayotte";

$countries["NAM"]="Namibia";
$countries["NCL"]="Nya Kaledonien";
$countries["NER"]="Niger";
$countries["NFK"]="Norfolkön";
$countries["NGA"]="Nigeria";
$countries["NIC"]="Nicaragua";
$countries["NIR"]="Nord Irland";
$countries["NIU"]="Niue";
$countries["NLD"]="Nederländerna";
$countries["NOR"]="Norge";

$countries["NPL"]="Nepal";
$countries["NRU"]="Nauru";
$countries["NTZ"]="Neutral Zon";
$countries["NZL"]="Nya Zeeland";

$countries["OMN"]="Oman";

$countries["PAK"]="Pakistan";
$countries["PAN"]="Panama";
$countries["PCN"]="Pitcairn";
$countries["PER"]="Peru";
$countries["PHL"]="Filippinerna";
$countries["PLW"]="Palau";
$countries["PNG"]="Papua Nya Guinea";
$countries["POL"]="Polen";
$countries["PRI"]="Puerto Rico";
$countries["PRK"]="Nordkorea";
$countries["PRT"]="Portugal";
$countries["PRY"]="Paraguay";
$countries["PSE"]="Palestina";
$countries["PYF"]="Franska Polynesien";

$countries["QAT"]="Qatar";

$countries["REU"]="Reunion";
$countries["ROM"]="Rumänien";
$countries["RUS"]="Ryssland";
$countries["RWA"]="Rwanda";

$countries["SAU"]="Saudiarabien";
$countries["SCG"]="Serbien-Montenegro";
$countries["SCT"]="Skotland";
$countries["SDN"]="Sudan";
$countries["SEA"]="Till sjös";
$countries["SEN"]="Senegal";
$countries["SER"]="Serbien";
$countries["SGP"]="Singapore";
$countries["SGS"]="Södra Georgia och Sandwichöarna";
$countries["SHN"]="St Helena";
$countries["SIC"]="Sicillien";
$countries["SJM"]="Svalbard och Jan Mayen";
$countries["SLB"]="Salomonöarna";
$countries["SLE"]="Sierra Leone";
$countries["SLV"]="El Salvador";
$countries["SMR"]="San Marino";
$countries["SOM"]="Somalia";
$countries["SPM"]="St Pierre och Miquelon";
$countries["STP"]="São Tomé och Príncipe";
$countries["SUN"]="U.S.S.R. (Sovjet Unionen)";
$countries["SUR"]="Surinam";
$countries["SVK"]="Slovakien";
$countries["SVN"]="Slovenien";
$countries["SWE"]="Sverige";
$countries["SWZ"]="Swaziland";
$countries["SYC"]="Seychellerna";
$countries["SYR"]="Syrien";

$countries["TCA"]="Turks- och Caicosöarna";
$countries["TCD"]="Tchad";
$countries["TGO"]="Togo";
$countries["THA"]="Thailand";
$countries["TJK"]="Tadzjikistan";
$countries["TKL"]="Tokelau";
$countries["TKM"]="Turkmenistan";
$countries["TLS"]="Östtimor";
$countries["TON"]="Tonga";
$countries["TRN"]="Transylvanien";
$countries["TTO"]="Trinidad och Tobago";
$countries["TUN"]="Tunisien";
$countries["TUR"]="Turkiet";
$countries["TUV"]="Tuvalu";
$countries["TWN"]="Taiwan";
$countries["TZA"]="Tanzania";

$countries["UGA"]="Uganda";
$countries["UKR"]="Ukraina";
$countries["UMI"]="Förenta staternas mindre öar i Oceanien och Västindien";
$countries["URY"]="Uruguay";
$countries["USA"]="USA";
$countries["UZB"]="Uzbekistan";

$countries["VAT"]="Vatikanstaten";
$countries["VCT"]="St Vincent och Grenadinerna";
$countries["VEN"]="Venezuela";
$countries["VGB"]="Brittiska Jungfruöarna";
$countries["VIR"]="Jungfruöarna";
$countries["VNM"]="Vietnam";
$countries["VUT"]="Vanuatu";

$countries["WAF"]="Väst Afrika";
$countries["WLF"]="Wallis- och Futunaöarna";
$countries["WLS"]="Wales";
$countries["WSM"]="Samoa";

$countries["YEM"]="Yemen";
$countries["YUG"]="Jugoslavien";

$countries["ZAF"]="Sydafrika";
$countries["ZAR"]="Zaire";
$countries["ZMB"]="Zambia";
$countries["ZWE"]="Zimbabwe";

?>