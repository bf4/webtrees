<?php
/**
 * English texts
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  PGV Development Team
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

$pgv_lang["step2"]			= "4 adımdan 2'ncisi:";
$pgv_lang["gedcom_deleted"] 		= "[#GED#] isimli GEDCOM veritabanı başarı ile silindi.";
$pgv_lang["full_name"]			= "Komple isim";
$pgv_lang["error_header"]		= "[#GEDCOM#], isimli GEDCOM dosyası, belirlenen yerde bulunamadı.";
$pgv_lang["manage_gedcoms"]		= "GEDCOM - Veritabanı ayarları";
$pgv_lang["folder_created"]		= "Klasör yaratıldı";
$pgv_lang["add_gedcom"]			= "GEDCOM ekle";
$pgv_lang["add_new_gedcom"]		= "Yeni bir GEDCOM yarat";
$pgv_lang["admin_approved"]		= "#SERVER_NAME# sitesindeki üyeliğiniz yönetici tarafından tasdik edilmiştir.";
$pgv_lang["admin_gedcom"]		= "GEDCOM ayarlarını düzenle";
$pgv_lang["administration"]		= "İdare / Ayarlar";
$pgv_lang["ansi_to_utf8"]		= "ANSİ ile kodlanmısş bu GEDCOM veritabanı UTF-8'e dönüştürülsün mü?";
$pgv_lang["bytes_read"]			= "Okunan byte miktarı:";
$pgv_lang["cleanup_places"]		= "Yerleri temizle";
$pgv_lang["click_here_to_go_to_pedigree_tree"]	= "Soy ağacı - seçere tablosuna ulaşmak için buraya tıklayın";
$pgv_lang["configuration"]		= "Genel ayarlar";
$pgv_lang["confirm_user_delete"]	= "Üyeyi hakikatten silmek mi istiyorsunuz";
$pgv_lang["create_user"]		= "Yeni üye yarat";
$pgv_lang["dataset_exists"]		= "Veri tabanına bu isim altında başka bir GEDCOM-Dosyası ithal edilmiştir.";
$pgv_lang["day_before_month"]		= "Önce gün sonra ay (GG AA SSSS)";
$pgv_lang["do_not_change"]		= "Değiştirme";
$pgv_lang["download_gedcom"]		= "GEDCOM dosyasını indir";
$pgv_lang["duplicate_username"]		= "Çifte rumuz. Siteye bu rumuz ile başka bir üye kayıtlıdır. Lütfen geri dönüp başka bir rumuz seçiniz.";
$pgv_lang["editaccount"]		= "Bu üyeye kendi üyelik verilerini işleme hakkı ver";
$pgv_lang["empty_dataset"]		= "Veri kümesini hakikatten silmek istiyor musunuz?";
$pgv_lang["empty_lines_detected"]	= "GEDCOM veritabanınızda boş sıralar bulunmuştur. Temizlemeyi seçerseniz bunlar silinecektir.";
$pgv_lang["error_header_write"] 	= "[#GEDCOM#] isimli GEDCOM dosyasına yazma izni yoktur. Check attributes and access rights.";
$pgv_lang["example_date"]		= "GEDCOM veritabanınızdan hatalı bir tarih biçiminin örneği:";
$pgv_lang["found_record"]		= "Bulunan kayıt";
$pgv_lang["ged_import"]			= "İçeri aktarımı (import)";
$pgv_lang["gedcom_config_write_error"]	= "HATA!!! GEDCOM yapılandırma dosyasına yazamıyorum.";
$pgv_lang["gedcom_downloadable"]	= "Bu GEDECOM dosyası İnternet üzerinden indirilebilinir!<br />Lütfen <a href=\"readme.txt\">readme.txt</a> dosyasının \"SECURITY\" bölümünü okuyup bu sorunu ortadan kaldırın.";
$pgv_lang["gedcom_file"]		= "GEDCOM dosyası";
$pgv_lang["import_complete"]		= "İçerik aktarımı tamamlandı";
$pgv_lang["import_progress"]		= "İthal gelişimi...";
$pgv_lang["inc_languages"]		= "Diller";
$pgv_lang["invalid_dates"]		= "Hatalı tarih biçimleri bulunmuştur. Temizlemeyi seçerseniz bunlar GG AAA SSSS (örnek: 1 JAN 2004) biçimine çevirilecektir.";
$pgv_lang["logfile_content"]		= "Sistemin günlük raporunun içeriği. Günlük dosyasının isimi:";
$pgv_lang["merge_records"]		= "Kayıtları birleştir";
$pgv_lang["month_before_day"]		= "Önce ay sonra gün (AA GG SSSS)";
$pgv_lang["none"]					= "Hiç biri";
$pgv_lang["pgv_registry"]		= "PhpGedView kullanan diğer sitelerin listesi";
$pgv_lang["please_be_patient"]		= "LÜTFEN BİRAZ SABIRLI OLUN";
$pgv_lang["reading_file"]		= "GEDCOM dosyası okunuyor";
$pgv_lang["readme_documentation"]	= "README belgelemesi";
$pgv_lang["rootid"]			= "Soyağacının kök şahsı";
$pgv_lang["select_an_option"]		= "Aşağıdaki seçeneklerden birini seçin:";
$pgv_lang["skip_cleanup"]		= "Temizlemeyi atla";
$pgv_lang["update_myaccount"]		= "Benim üyelik verilerimi güncelleştir";
$pgv_lang["update_user"]		= "Üye verilerini güncelleştir";
$pgv_lang["upload_gedcom"]		= "GEDCOM dosyasını yolla";
$pgv_lang["user_contact_method"]	= "Tercih edilen ilişki yöntemi";
$pgv_lang["user_create_error"]		= "Üye eklenemedi. Lütfen bir sayfa geri dönüp tekrar deneyin.";
$pgv_lang["user_created"]		= "Üye başarıyla eklendi.";
$pgv_lang["user_default_tab"]		= "Şahısların bilgileri sayfasında gösterilecek ilk sekme";
$pgv_lang["valid_gedcom"]		= "Geçerli GEDCOM bulundu. Temizlemeye gerek yok. ";
$pgv_lang["validate_gedcom"]		= "GEDCOM veritabanının geçerliğini denetle";
$pgv_lang["verified"]			= "Üye kendini tasdik etti";
$pgv_lang["verified_by_admin"]		= "Yönetici üyeyi tasdik etti";
$pgv_lang["view_logs"]			= "Sistemin günlük raporuna bak";
$pgv_lang["visibleonline"]		= "Siteye bağlantılı iken diğer üyelere görünür";
$pgv_lang["you_may_login"]		= "Şimdi isterseniz aşağıdaki bağlantıya giderek PhpGedView sitesine girebilirsiniz...";


?>
