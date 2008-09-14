<?php
/**
 * Turkish Language file for PhpGedView.
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
 * @author Kurt Norgaz
 * @author Adem GENÇ uzayuydu@gmail.com http://www.muttafi.com
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

// -- Define a fact array to map Gedcom tags with their Turkish values
$factarray["ABBR"]	= "Kısa başlık";
$factarray["ADDR"]	= "Adres";
$factarray["ADR1"]	= "1. adres";
$factarray["ADR2"]	= "2. adres";
$factarray["ADOP"]	= "Evlât edinme";
$factarray["AFN"]	= "Geçmiş Dosya Numara (AFN)";
$factarray["AGE"]	= "Yaşı";
$factarray["AGNC"]	= "Temsilci";
$factarray["ALIA"]	= "Takma adı";
$factarray["ANCE"]	= "Geçmişler / Atalar";
$factarray["ANCI"]	= "Atalar İlgilendirirler";
$factarray["ANUL"]	= "İptal";
$factarray["ASSO"]	= "İş Arkadaşı";
$factarray["AUTH"]	= "Yazar";
$factarray["BAPL"]	= "LDS vaftizi";
$factarray["BAPM"]	= "Vaftiz";
$factarray["BARM"]	= "Koca";
$factarray["BIRT"]	= "Doğum";
$factarray["BLES"]	= "Kutsama";
$factarray["BLOB"]	= "'Binary' veri nesnesi";
$factarray["BURI"]	= "Defin";
$factarray["CALN"]	= "Telefon numarası";
$factarray["CAST"]	= "Kast / Sosyal Durumlar";
$factarray["CAUS"]	= "Vefat nedeni";
$factarray["CEME"]  = "Mezarlık";
$factarray["CENS"]	= "Nüfus sayımı";
$factarray["CHAN"]	= "Son değişiklik";
$factarray["CHAR"]	= "Karakter kümesi";
$factarray["CHIL"]	= "Çocuk";
$factarray["CHR"]	= "Vaftiz";
$factarray["CHRA"]	= "Erişkin vaftizi";
$factarray["CITY"]	= "Şehir";
$factarray["CONF"]	= "Protestanlikta<br />Kiliseye kabul edinme ayini";
$factarray["CONL"]	= "LDS kilisesine kabul edinme ayini";
$factarray["COPR"]	= "Telif hakkı";
$factarray["CORP"]	= "Şirket / Kuruluş";
$factarray["CREM"]	= "Ölüyü yakma";
$factarray["CTRY"]	= "Ülke";
$factarray["DATA"]	= "Veri";
$factarray["DATE"]	= "Tarih";
$factarray["DEAT"]	= "Vefat";
$factarray["DESC"]	= "Soyundan gelen şahıslar";
$factarray["DESI"]	= "Neslinden Olanlar İlgilendirirler";
$factarray["DEST"]	= "Gidilecek yer";
$factarray["DIV"]	= "Boşanma";
$factarray["DIVF"]	= "Boşanma dosyası";
$factarray["DSCR"]	= "Tarif";
$factarray["EDUC"]	= "Eğitim";
$factarray["EMIG"]	= "İçinden göç ettiği ülke";
$factarray["ENDL"]	= "LDS Bağışı";
$factarray["ENGA"]	= "Nişan";
$factarray["EVEN"]	= "Hadise";
$factarray["FAM"]	= "Aile";
$factarray["FAMC"]	= "Çocuk olarak aile";
$factarray["FAMF"]	= "Aile dosyası";
$factarray["FAMS"]	= "Eş olarak aile";
$factarray["FCOM"]	= "Hrist. İlk komünyon";
$factarray["FILE"]	= "Harici dosya";
$factarray["FORM"]	= "Biçim";
$factarray["GIVN"]	= "İsim";
$factarray["GRAD"]	= "Mezuniyet";
$factarray["HUSB"]  = "Koca";
$factarray["IDNO"]	= "GEDCOM varitabanı içindeki tanıtım numarası";
$factarray["IMMI"]	= "İçine göç ettiği ülke";
$factarray["LEGA"]	= "Vasiyette kendisine mal bırakılan kişi";
$factarray["MARB"]	= "Yasaklanılan Evlilik";
$factarray["MARC"]	= "Evlilik kontratı";
$factarray["MARL"]	= "Evlilik Lisansı";
$factarray["MARR"]	= "Nikâh";
$factarray["MARS"]	= "Evlilik Yerleşimi";
$factarray["MEDI"]	= "Multimedya türü";
$factarray["NAME"]	= "Soy isim";
$factarray["NATI"]	= "Vatandaşlık";
$factarray["NATU"]	= "Yeni yurttaşlık";
$factarray["NICK"]	= "Lakabı";
$factarray["NCHI"]	= "Çocuk sayısı";
$factarray["NMR"]	= "Evlilik sayısı";
$factarray["NOTE"]	= "Not";
$factarray["NPFX"]	= "İsmin önüne konulan unvan";
$factarray["NSFX"]	= "İsmin sonuna konan ek";
$factarray["OBJE"]	= "Multimedya nesnesi";
$factarray["OCCU"]	= "Meslek";
$factarray["ORDI"]	= "Düzen";
$factarray["ORDN"]	= "Atanma";
$factarray["PAGE"]	= "Davet Detayları";
$factarray["PEDI"]	= "Soyağacı / Seçere";
$factarray["PLAC"]	= "Yer";
$factarray["PHON"]	= "Telefon";
$factarray["POST"]	= "Posta kodu";
$factarray["PROB"]	= "Vasiyetname";
$factarray["PROP"]	= "Mülkiyet";
$factarray["PUBL"]	= "Yayın";
$factarray["QUAY"]	= "Verinin kalitesi";
$factarray["REPO"]	= "Depolanan yer";
$factarray["REFN"]	= "Kaynak numarası";
$factarray["RELA"]	= "Yakınlık";
$factarray["RELI"]	= "Din";
$factarray["RESI"]	= "Ev";
$factarray["RESN"]	= "Sınırlama";
$factarray["RETI"]	= "Emeklilik";
$factarray["RFN"]	= "Kayıt dosya numarası";
$factarray["RIN"]	= "Kayıt kişisel numarası";
$factarray["ROLE"]	= "Rol";
$factarray["SEX"]	= "Cinsiyet";
$factarray["SLGC"]	= "LDS Çocuk Mühürlemesi";
$factarray["SLGS"]	= "LDS Eş Mühürlemesi";
$factarray["SOUR"]	= "Kaynak";
$factarray["SPFX"]	= "Soyismin sonuna konan ek";
$factarray["SSN"]	= "Sosyal sigorta numarası";
$factarray["STAE"]	= "Durum";
$factarray["STAT"]	= "Durumlar";
$factarray["SUBM"]	= "Gönderen";
$factarray["SUBN"]	= "Teslim";
$factarray["SURN"]	= "Soy isim";
$factarray["TEMP"]	= "Tapınak";
$factarray["TEXT"]	= "Metin";
$factarray["TIME"]	= "Saat";
$factarray["TITL"]	= "Başlık";
$factarray["TYPE"]	= "Tür / Tip";
$factarray["WIFE"]  = "Hanım";
$factarray["WILL"]	= "Vasiyetname";
$factarray["_EMAIL"]	= "E-posta adresi";
$factarray["EMAIL"]	= "E-posta adresi:";
$factarray["_TODO"]	= "Yapılması gereken iş";
$factarray["_UID"]	= "Üniversel tanıtıcı";
$factarray["_PGVU"]	= "Son değişikliği yapan";
$factarray["SERV"] = "Uzak Server";
$factarray["_GEDF"] = "GEDCOM Dosya";
$factarray["_PRIM"]	= "Tercih edilen fotoğraf";
$factarray["_DBID"] = "Veritabanı ID bağlandı";
$factarray["STAT:DATE"] = "İstatistik Değişim Tarihi";
$factarray["FAMC:HUSB:SURN"] = "Babaların Soyadı";
$factarray["FAMC:WIFE:SURN"] = "Annelerin Soyadı";
$factarray["FAMC:HUSB:BIRT:PLAC"] = "Babaların Doğumyeri";
$factarray["FAMC:WIFE:BIRT:PLAC"] = "Annelerin Doğumyeri";
$factarray["FAMC:MARR:PLAC"] = "Ebeveynlerin Evlilik Yeri";
$factarray["FAMC:HUSB:OCCU"] = "Baba Mesleği";
$factarray[":BIRT:PLAC"] = "Doğumyeri";
$factarray["FAMS:MARR:PLAC"] = "Evlilik Yeri";
$factarray["FAMS:SPOUSE:DEAT:PLAC"] = "Hayat Arkadaşı Ölüm Yeri";
$factarray["FAMC:HUSB:GIVN"] = "Babanın İlk Adı";
$factarray["FAMS:SPOUSE:BIRT:PLAC"] = "Hayat Arkadaşın Doğum Yeri";
$factarray["FAMC:WIFE:GIVN"] = "Annenin İlk Adı";
$factarray["FAMC:HUSB:FAMC:HUSB:GIVN"] = "Baba Tarafındaki Büyükbabanın Verilen İsmi ";
$factarray["FAMC:WIFE:FAMC:WIFE:GIVN"] = "Anne Tarafındaki Anneannenin Verilen İsmi ";
$factarray["FAMC:WIFE:FAMC:HUSB:GIVN"] = "Anne Tarafındaki Dedenin Verilen İsmi ";
$factarray["FAMC:HUSB:FAMC:WIFE:GIVN"] = "Baba Tarafındaki Babaannenin Verilen İsmi ";
$factarray["FAMS:CHIL:BIRT:PLAC"] = "Çocukların Doğum Yeri";
$factarray["BIRT:PLAC"] = "Doğum Yeri";
$factarray["DEAT:PLAC"] = "Ölüm Yeri";
$factarray["CHR:PLAC"] = "Vaftiz eden yer ";
$factarray["BAPM:PLAC"] = "Vaftiz yer";
$factarray["BURI:PLAC"] = "Gömüldüğü Yer";
$factarray["MARR:PLAC"] = "Evlendiği Yer";
$factarray["_THUM"]	= "Tırnak önizlemede thumbs yerine media dan kullan?";

// These facts are specific to gedcom exports from Family Tree Maker
$factarray["_MDCL"]	= "Sağlıksal bilgiler";
$factarray["_DEG"]	= "Derece";
$factarray["_MILT"]	= "Askerlik görevi";
$factarray["_SEPR"]	= "Ayrılmış";
$factarray["_WEIG"]	= "Ağırlık";
$factarray["_DETS"]	= "Bir eşin vefatı";
$factarray["CITN"]	= "Vatandaşlık";
$factarray["_FA1"]	= "1. Hadise";
$factarray["_FA2"]	= "2. Hadise";
$factarray["_FA3"]	= "3. Hadise";
$factarray["_FA4"]	= "4. Hadise";
$factarray["_FA5"]	= "5. Hadise";
$factarray["_FA6"]	= "6. Hadise";
$factarray["_FA7"]	= "7. Hadise";
$factarray["_FA8"]	= "8. Hadise";
$factarray["_FA9"]	= "9. Hadise";
$factarray["_FA10"]	= "10. Hadise";
$factarray["_FA11"]	= "11. Hadise";
$factarray["_FA12"]	= "12. Hadise";
$factarray["_FA13"]	= "13. Hadise";
$factarray["_MREL"]	= "Anne'ye akrabalık derecesi";
$factarray["_FREL"]	= "Baba'ya akrabalık derecesi";
$factarray["_MSTAT"]	= "Başlayan Evlilik Durumları";
$factarray["_MEND"]	= "Evlilik Biten Durumlar";
$factarray["FAX"] = "Faks";
$factarray["FACT"] = "Hadise";
$factarray["WWW"]	= "İnternet sitesi";
$factarray["MAP"] = "Harıta";
$factarray["LATI"] = "Enlem";
$factarray["LONG"] = "Boylam";
$factarray["FONE"]	= "Fonetik";
$factarray["_NAME"] = "İsmin Gönder";
$factarray["URL"] = "Web URL";
$factarray["_URL"] = "Web URL";
$factarray["_HEB"]	= "İbranice";
$factarray["_TYPE"] = "Media Tipi";
$factarray["_SSHOW"] = "Kayma Gösteri";
$factarray["_SUBQ"]= "Kısa Versiyon";
$factarray["_BIBL"]	= "Kaynakça";
$factarray["EMAL"]	= "Email Adresi";

// Other common customized facts
$factarray["_ADPF"]	= "Baba tarafından evlât edindi";
$factarray["_ADPM"]	= "Anne tarafından evlât edindi";
$factarray["_AKAN"]	= "Bu isimle de tanınıyor";
$factarray["_AKA"]	= "Bu isimle de tanınıyor";
$factarray["_COML"]	= "Umumi Hukuk Evliliği";
$factarray["_EYEC"]	= "Göz rengi";
$factarray["_FNRL"]	= "Cenaze töreni";
$factarray["_HAIR"]	= "Saç rengi";
$factarray["_HEIG"]	= "Boy";
$factarray["_HOL"]  = "Facia";
$factarray["_INTE"]	= "Defin etme";
$factarray["_MARI"]	= "Evlilik Niyeti";
$factarray["_MBON"]	= "Resmi Evlilik";
$factarray["_MEDC"]	= "Sağlıksal durumu";
$factarray["_MILI"]	= "Askerlik";
$factarray["_NMR"]	= "Evli değil";
$factarray["_NLIV"]	= "Yaşamıyor";
$factarray["_NMAR"]	= "Hiçbir zaman evli değildi";
$factarray["_PRMN"]	= "Kalıcı numarası";
$factarray["_MARNM"]	= "Evlilik soyismi";
$factarray["_MARNM_SURN"] = "Evlilik soyismi";
$factarray["_STAT"]	= "Evlilik durumu";
$factarray["COMM"]	= "Açıklama";
$factarray["MARR_CIVIL"] = "Medeni Nikah";
$factarray["MARR_RELIGIOUS"] = "Dini Evlilik";
$factarray["MARR_PARTNERS"] = "Ortaklık Kaydet";
$factarray["MARR_UNKNOWN"] = "Bilinmeyen Evlilik Tipi";
$factarray["_HNM"] = "İbranice İsim";
$factarray["_DEAT_SPOU"] = "Hayat arkadaşının ölümü";
$factarray["_BIRT_CHIL"] = "Çocuğunun doğumu";
$factarray["_MARR_CHIL"] = "Çocuğunun evliliği";
$factarray["_DEAT_CHIL"] = "Çocuğunun ölümü";
$factarray["_BIRT_GCHI"] = "Torununun doğumu";
$factarray["_MARR_GCHI"] = "Torununun evliliği";
$factarray["_DEAT_GCHI"] = "Torununun ölümü";
$factarray["_MARR_FATH"] = "Babasının evliliği";
$factarray["_DEAT_FATH"] = "Babasının ölümü";
$factarray["_MARR_MOTH"] = "Annesinin evliliği";
$factarray["_DEAT_MOTH"] = "Annesinin ölümü";
$factarray["_BIRT_SIBL"] = "Kardeşinin doğumu";
$factarray["_MARR_SIBL"] = "Kardeşinin evliliği";
$factarray["_BIRT_HSIB"] = "Üvey kardeşinin doğumu";
$factarray["_MARR_HSIB"] = "Üvey kardeşinin evliliği";
$factarray["_DEAT_HSIB"] = "Üvey kardeşinin ölümü";
$factarray["_BIRT_NEPH"] = "Erkek veya Kız yeğenin doğumu";
$factarray["_MARR_NEPH"] = "Erkek veya Kız yeğenin evliliği";
$factarray["_DEAT_NEPH"] = "Erken veya Kız yeğenin ölümü";
$factarray["_DEAT_SIBL"] = "Kardeşinin ölümü";
$factarray["_DEAT_GPAR"] = "Büyük ebeveynin ölümü";
$factarray["_BIRT_FSIB"] = "Amcanın doğumu";
$factarray["_MARR_FSIB"] = "Amcanın evliliği";
$factarray["_DEAT_FSIB"] = "Amcasının ölümü";
$factarray["_BIRT_MSIB"] = "Üvey annesinin doğumu";
$factarray["_MARR_MSIB"] = "Üvey annesinin evliliği";
$factarray["_DEAT_MSIB"] = "Üvey annesinin ölümü";
$factarray["_BIRT_COUS"] = "İlk kuzeninin doğumu";
$factarray["_MARR_COUS"] = "İlk kuzeninin evliliği";
$factarray["_DEAT_COUS"] = "İlk kuzeninin ölümü";
$factarray["_FAMC_EMIG"] = "Ebeveynlerin göçü";
$factarray["_FAMC_RESI"] = "Ebeveynlerin ikâmeti";

// GEDCOM 5.5.1 related facts
$factarray["ROMN"] = "Latince";

if (file_exists( "languages/facts.tr.extra.php")) require  "languages/facts.tr.extra.php";

?>
