<?php
/**
 * Turkish Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2010  PGV Development Team.  All rights reserved.
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

$pgv_lang["upload_a_gedcom"] 		= "GEDCOM Dosya Yükle";
$pgv_lang["start_entering"] 		= "Başlama girş tarihi";
$pgv_lang["add_gedcom_from_path"] 	= "Yerelden GEDCOM Dosya ekle";
$pgv_lang["get_started_instructions"]	= "PhpGedView kullanmaya başlamak için bu seçeneklerden birini seçin";

$pgv_lang["admin_users_exists"]		= "Aşağıda zaten yönetici kullanıcı mevcut:";
$pgv_lang["install_step_1"] = "Ortamı Kontrol Et";
$pgv_lang["install_step_2"] = "Veritabanı Bağlantı";
$pgv_lang["install_step_3"] = "Tablo Oluştur";
$pgv_lang["install_step_4"] = "Site Konfigürasyonu";
$pgv_lang["install_step_5"] = "Lisan";
$pgv_lang["install_step_6"] = "Konfigürasyonu Kaydet";
$pgv_lang["install_step_7"] = "Yönetici kullanıcı oluştur";
$pgv_lang["install_wizard"] = "Kurulum Sihirbazi";
$pgv_lang["basic_site_config"] = "Temel Ayarlar";
$pgv_lang["adv_site_config"] = "Gelişmiş Ayarlar";
$pgv_lang["config_not_saved"] = "*Ayarlarını 6 adıma kadar<br />korunmayacaktır";
$pgv_lang["download_config"] = "config.php indir";
$pgv_lang["site_unavailable"] = "Güncel site mevcut değildir";
$pgv_lang["to_manage_users"] = "Kullanıcıları yönetmek için <a href=\"useradmin.php\">Kullanıcı Yönetimi</a> sayfasını kullanın.";
$pgv_lang["db_tables_created"] = "Veritabanı tabloları başarılı biçimde oluşturuldu";
$pgv_lang["config_saved"] = "Konfigürasyon başarılı biçimde kaydedildi";
$pgv_lang["checking_errors"]		= "Hatalar için kontrol et...";
$pgv_lang["checking_php_version"]		= "Gerekli PHP versiyonu kontrol et:";
$pgv_lang["failed"]		= "Başarısız";
$pgv_lang["pgv_requires_version"]		= "PhpGedView için gerekli PHP versiyon #PGV_REQUIRED_PHP_VERSION# yada daha üstü.";
$pgv_lang["using_php_version"]		= "Kullandığınız PHP versiyonu #PGV_ACTUAL_PHP_VERSION#";
$pgv_lang["checking_db_support"]		= "Minimum veritabanı desteği için kontrol et:";
$pgv_lang["no_db_extensions"]		= "Sizin desteklenen veritabanı uzantıların herhangi biri yok.";
$pgv_lang["db_ext_support"]		= "Sizin #DBEXT# desteğiniz var";
$pgv_lang["checking_config.php"]		= "config.php kontrol et:";
$pgv_lang["config.php_missing"]		= "config.php dosya bulunamadım.";
$pgv_lang["config.php_missing_instr"]		= "Bu kurulum sihirbazı config.php dosyasına ayarlarınızı yazamayacaktır.  config.php dosyasını yeniden adlandırıp config.dist diye bir kopyasını yapabilirsiniz.  Bu sihirbazı sırayla değiştirip tamamladığında size indirmeniz için seçenek verecektir ve sonuçlanan config.php dosyayı yükleyebilirsini.";
$pgv_lang["config.php_not_writable"]		= "config.php dosyası yazılabilir değildir.";
$pgv_lang["config.php_not_writable_instr"]		= "Bu kurulum sihirbazı config.php dosyasına ayarlarınızı yazamayacaktır. Dosyanıza siz izinleri yazabilirsiniz yada kurulum sihirbazı tamamlandıktan sonra config.php dosyanızı indirme seçeneği verecektir ve config.php dosyayı sunucunuza yüklemeniz gerekir.";
$pgv_lang["passed"]		= "Geçti";
$pgv_lang["config.php_writable"]		= "config.php mevcut ve yazılabilirdir";
$pgv_lang["checking_warnings"]		= "Uyarılar için kontrol et...";
$pgv_lang["checking_timelimit"]		= "Zaman sınırı değiştirme yeterlilik için kontrol et:";
$pgv_lang["cannot_change_timelimit"]		= "Zaman sınırını değiştirmek için uygunsuz.";
$pgv_lang["cannot_change_timelimit_instr"]		= "Siz birçok bireyle büyük veritabanlarında bütün görevleri koşmayabilirsin.";
$pgv_lang["current_max_timelimit"]		= "Sızın maximum zaman sınırlıdır";
$pgv_lang["check_memlimit"]		= "Hafıza sınırını değiştirme gerekliliği için kontrol et:";
$pgv_lang["cannot_change_memlimit"]		= "Hafıza sınırını değiştirmek için uygunsuz.";
$pgv_lang["cannot_change_memlimit_instr"]		= "Siz birçok bireyle büyük veritabanlarında bütün görevleri koşmayabilirsin.";
$pgv_lang["current_max_memlimit"]		= "Sizin güncel hafıza sınırındır";
$pgv_lang["check_upload"]		= "Dosyaları yükleme yeterlilik için kontrol et:";
$pgv_lang["current_max_upload"]		= "Sizin maksimum yükleme dosya boyutundur:";
$pgv_lang["check_gd"]		= "GD resim kütüphane için kontrol et:";
$pgv_lang["cannot_use_gd"]		= "GD kütüphanesine sahip değilsiniz, Otomatik olarak tırnak resim önizleme oluşturamazsınız.";
$pgv_lang["check_sax"]		= "SAX XML kütüphane için kontrol et:";
$pgv_lang["cannot_use_sax"]		= "You do not have the SAX XML library.  You will not be able to run any reports or some other auxiliary functions.";
$pgv_lang["check_dom"]		= "DOM XML kütüphane için kontrol et:";
$pgv_lang["cannot_use_dom"]		= "Sizin DOM XML kütüphane yok. Sizin XML dışarı aktaramazsınız.";
$pgv_lang["check_calendar"]		= "Gelişmiş Takvim kütüphanesi için kontrol et:";
$pgv_lang["cannot_use_calendar"]		= "Gelişmiş takvimi destekleyemezsiniz. Bazı gelişmiş takvim fonksiyonları çalıştıramazsınız.";
$pgv_lang["warnings_passed"]		= "Tüm uyarı kontroleri geçti.";
$pgv_lang["warning_instr"]		= "If any of the warnings do not pass you may still be able to run PhpGedView on this server, but some functionality may be disabled or you may experience poor performance.";

$pgv_lang["associated_files"]		= "Ortak dosyalar:";
$pgv_lang["remove_all_files"]		= "Kaldırılabilir tüm dosyaları kaldır";
$pgv_lang["warn_file_delete"]		= "Bu dosya değişiklik verisi esnasında veya dil kurması gibi önemli bilgiyi kapsar. Bu dosyayı silmek istediğinizden emin misiniz?";
$pgv_lang["deleted_files"]          = " Silinen dosyalar:";
$pgv_lang["index_dir_cleanup_inst"]	= "İndex klasöründen dosya veya alt-klasöru silmek için onu çöp kutusuna sürükle veya kutuyu seç. Kalıcı olarak kaldırmak için Sil butona basın. <br /><br /><img src=\"./images/RESN_confidential.gif\" /> ile işaretlenen dosyalar uygun çalışma için gereklidir ve kaldırılamazlar.<br /> <img src=\"./images/RESN_locked.gif\" /> ile işaretlenen dosyalar önemli dosyalar veya değiştirilmiş bunu silmek için gerekli izinleri verip silmek istediğinizden emin olup silebilirsiniz.";
$pgv_lang["index_dir_cleanup"]		= "Index klasörü temizle";
$pgv_lang["clear_cache_succes"]		= "Cache dosyalar temizlendi.";
$pgv_lang["clear_cache"]			= "Önbellek Dosyaları Temizle";
$pgv_lang["sanity_err0"]			= "Hatalar:";
$pgv_lang["sanity_err1"]			= "PHP versiyonu daha yüksek veya 4.3 e sahip olmanız gerekiyor.";
$pgv_lang["sanity_err2"]			= "Dosya veya klasör <i>#GLOBALS[whichFile]#</i> mevcut değil. Lütfen bu dosya veya klasörü doğrula var olan dosya okuma izinleri kontrol edin yada yeniden adlandırın.";
$pgv_lang["sanity_err3"]			= "Dosya <i>#GLOBALS[whichFile]#</i> doğru yüklenemedi. Lütfen tekrar yüklemeyi deneyin.";
$pgv_lang["sanity_err4"]			= "<i>config.php</i> dosya bozuk.";
$pgv_lang["sanity_err5"]			= "<i>config.php</i> dosya yazılabilir değil.";
$pgv_lang["sanity_err6"]			= "<i>#GLOBALS[INDEX_DIRECTORY]#</i> klasör yazılabilir değil.";
$pgv_lang["sanity_warn0"]			= "Uyarılar:";
$pgv_lang["sanity_warn1"]			= "<i>#GLOBALS[MEDIA_DIRECTORY]#</i> klasör yazılabilir değil. Media dosya siz yükleyemezsiniz PhpGedView kendisi otomatik tırnak önizleme üretir.";
$pgv_lang["sanity_warn2"]			= "<i>#GLOBALS[MEDIA_DIRECTORY]#thumbs</i> klasör yazılabilir değil. Media dosya siz yükleyemezsiniz PhpGedView kendisi otomatik tırnak önizleme üretir.";
$pgv_lang["sanity_warn3"]			= "GD resim kütüphanesi mevcut değil. PhpGedView tırnak önizleme fonksiyonları çalışacak ancak Çizgelerde GD kütüphanesi olmadan Diyagram Daire Çizelgesi çalışmayacaktır. Bu sorun hakkında bilgi görmek için lütfen <a href='http://www.php.net/manual/en/ref.image.php'>http://www.php.net/manual/en/ref.image.php</a> burayı tıklayıp görün. PHP.ini düzenleme imkanınız varsa extension=php_gd2.dll açın.";
$pgv_lang["sanity_warn5"]			= "DOM XML kütüphanesi mevcut değil. PhpGedView fonksiyonları çalışacak ancak Grams (aile ağacı yazılımı) export dışarı verme özellikleri ile indirme servisleri çalışmayacaktır. Bu sorun hakkında bilgi görmek için lütfen <a href='http://www.php.net/manual/en/ref.domxml.php'>http://www.php.net/manual/en/ref.domxml.php</a> burayı tıklayıp görün. PHP.ini düzenleme imkanınız varsa extension=php_domxml.dll açın.";
$pgv_lang["ip_address"]				= "IP adresi";
$pgv_lang["date_time"]				= "Tarih ve Saat";
$pgv_lang["log_message"]			= "Kütük Mesajı";
$pgv_lang["searchtype"]				= "Arama tipi";
$pgv_lang["query"]					= "Sorgula";
$pgv_lang["user"]					= "Onaylanmış kullanıcı";
$pgv_lang["thumbnail_deleted"]		= "Tırnak önizleme dosya başarılı biçimde silindi.";
$pgv_lang["thumbnail_not_deleted"]	= "Tırnak önizleme dosyası silinemiyor.";
$pgv_lang["step2"]			= "4 adımdan 2'ncisi:";
$pgv_lang["refresh"]				= "Yenile";
$pgv_lang["move_file_success"]		= "Media ve tırnak önizleme dosyalar başarılı biimde taşındı.";
$pgv_lang["media_folder_corrupt"]	= "Media klasörü bozuk.";
$pgv_lang["media_file_not_deleted"]	= "Media dosyası silinemiyor.";
$pgv_lang["gedcom_deleted"] 		= "[#GED#] isimli GEDCOM veritabanı başarı ile silindi.";
$pgv_lang["gedadmin"]				= "GEDCOM yönetici";
$pgv_lang["full_name"]			= "Komple isim";
$pgv_lang["error_header"]		= "[#GEDCOM#], isimli GEDCOM dosyası, belirlenen yerde bulunamadı.";
$pgv_lang["confirm_delete_file"]	= "Bu dosyayı silmek istediğinizden eminmisiniz?";
$pgv_lang["confirm_folder_delete"] = "Bu klasörü silmek istediğinizden eminmisiniz?";
$pgv_lang["confirm_remove_links"]	= "Bu nesneye bağlı tüm linkleri kaldırmak istediğinizden emnmisiniz?";
$pgv_lang["PRIV_PUBLIC"]			= "Herkese Göster";
$pgv_lang["PRIV_USER"]			= "Sırf tasdik edilmiş ziyaretçiye göster";
$pgv_lang["PRIV_NONE"]			= "Sırf yöneticilere göster";
$pgv_lang["PRIV_HIDE"]			= "Yöneticilerden bile sakla";
$pgv_lang["manage_gedcoms"]		= "GEDCOM - Veritabanı ayarları";
$pgv_lang["keep_media"]				= "Media linkleri koru";
$pgv_lang["files_in_backup"]		= "Yedek dosyada içerenler";
$pgv_lang["created_remotelinks"]	= "Tablo <i>Uzak Bağlantılar</i> başarılı biçimde oluşturuldu..";
$pgv_lang["created_remotelinks_fail"] 	= "Tablo <i>Uzak bağlantılar</i> oluşturulamadı.";
$pgv_lang["created_indis"]			= "Tablo <i>Bireyler</i> başarılı biçimde oluşturuldu.";
$pgv_lang["created_indis_fail"] 	= "Tablo <i>Bireyler</i> oluşturulamadı.";
$pgv_lang["created_fams"]			= "Tablo <i>Aileler</i> başarılı biçimde oluşturuldu.";
$pgv_lang["created_fams_fail"]		= "Tablo <i>Aileler</i> oluşturulamadı.";
$pgv_lang["created_sources"]		= "Tablo <i>Kaynaklar</i> başarılı biçimde ouşturuldu.";
$pgv_lang["created_sources_fail"]	= "Tablo <i>Kaynaklar</i> oluşturulamadı.";
$pgv_lang["created_other"]			= "Tablo <i>Diğer</i> başarılı biçimde ouşturuldu.";
$pgv_lang["created_other_fail"] 	= "Tablo <i>Diğer</i> oluşturulamadı.";
$pgv_lang["created_places"] 		= "Tablo <i>Yerler</i> başarılı biçimde ouşturuldu.";
$pgv_lang["created_places_fail"]	= "Tablo <i>Yerler</i> oluşturulamadı.";
$pgv_lang["created_placelinks"] 	= "Tablo <i>Yerler linkleri</i> başarılı biçimde ouşturuldu.";
$pgv_lang["created_placelinks_fail"]	= "Tablo <i>Yerler linkleri</i> oluşturulamadı.";
$pgv_lang["created_media_fail"]	= "Tablo <i>Media</i> oluşturulamadı.";
$pgv_lang["created_media_mapping_fail"]	= "Tablo <i>Media Harıtaları</i> oluşturulamadı.";
$pgv_lang["no_thumb_dir"]			= "Tırnak önizleme klasörü yok ve oluşturulamıyor.";
$pgv_lang["folder_created"]		= "Klasör oluşturuldu";
$pgv_lang["folder_no_create"]		= "Klasör oluşturulamıyor";
$pgv_lang["security_no_create"]		= "Güvenlik Uyarısı: içinde <b><i>index.php</i></b> dosya oluşturulamıyor ";
$pgv_lang["security_not_exist"]		= "Güvenlik Uyarısı: <b><i>index.php</i></b> dosya mevcut değil ";
$pgv_lang["label_delete"]           	= "Sil";
$pgv_lang["progress_bars_info"]			= "Aşağıdaki istatistikte içeri aktarım hakkında ilerleme bilgileri görebilirsiniz. Zaman kotası biterse içeri aktarım duracak ve aşağıda göreceğiniz <b>Devam</b> butonuna basarak içeri aktarıma devam edebilirsiniz. Eğer <b>Devam</b> butonu göremez iseniz küçük zaman kotasi ile içeri aktarımı yeniden başlatın.";
$pgv_lang["upload_replacement"]			= "Yenisini yükle eskisi ile değiştir";
$pgv_lang["about_user"]			= "İlk önce genel yönetici üyeyi oluşturmanız gerekiyor. Bu üye yapılan dosyalarını güncelleştirme, özel verileri izleme ve diğer üyeleri oluşturma ve işleme haklarına sahip olacaktır.";
$pgv_lang["access"]						= "Erişim";
$pgv_lang["add_gedcom"]			= "GEDCOM ekle";
$pgv_lang["add_new_gedcom"]		= "Yeni bir GEDCOM oluştur";
$pgv_lang["add_new_language"]		= "Yeni bir dil için gerekli olan dosya ve ayarları ekle";
$pgv_lang["add_user"]					= "Yeni bir kullanıcı ekle";
$pgv_lang["admin_gedcom"]		= "GEDCOM ayarlarını düzenle";
$pgv_lang["admin_gedcoms"]		= "GEDCOM veritabanı ayarlarını değiştirmek için buraya tıklayın.";
$pgv_lang["admin_geds"]					= "Veri ve GEDCOM yönetimi";
$pgv_lang["admin_info"]					= "Bilgilendirme";
$pgv_lang["admin_site"]					= "Site yönetimi";
$pgv_lang["admin_user_warnings"]		= "Uyarılar alan bir yada birden çok kullanıcı hesapları";
$pgv_lang["admin_verification_waiting"] = "Yönetici onayı bekleyen kullanıcı var";
$pgv_lang["administration"]		= "Yönetim";
$pgv_lang["ALLOW_CHANGE_GEDCOM"]		= "GEDCOM seçme izni ver";
$pgv_lang["ALLOW_USER_THEMES"]		= "Üye tema seçmesine izin ver";
$pgv_lang["ansi_encoding_detected"] 	= "ANSI ile kotlanmış saptanan dosya. PhpGedView UTF-8 e dönüştürülen dosyalar en iyi şekilde çalışır.";
$pgv_lang["ansi_to_utf8"]		= "ANSİ ile kodlanmış bu GEDCOM veritabanı UTF-8\'e dönüştürülsün mü?";
$pgv_lang["apply_privacy"]				= "Mahremiyet ayarları uygula";
$pgv_lang["back_useradmin"]				= "Kullanıcı Yönetimine geri don";
$pgv_lang["bytes_read"]			= "Okunan bayt miktarı:";
$pgv_lang["can_admin"]					= "Üye yöneticilik yapabilir";
$pgv_lang["can_edit"]					= "Verilecek izinler";
$pgv_lang["change_id"]					= "Bireysel ID sini Değiştir:";
$pgv_lang["choose_priv"]				= "Gizlilik düzeyi seç: ";
$pgv_lang["cleanup_places"]		= "Yerleri temizle";
$pgv_lang["cleanup_users"]				= "Kullanıcıları temizle";
$pgv_lang["click_here_to_continue"]		= "Devam etmek için buraya tıkla.";
$pgv_lang["click_here_to_go_to_pedigree_tree"]	= "Soyağacı Ağaç Çizge tablosuna ulaşmak için buraya tıklayın";
$pgv_lang["comment"]							= "Yönetici kullanıcıyı yorumlar";
$pgv_lang["comment_exp"]						= "Yöneticiyi uyaracak tarih";
$pgv_lang["config_help"]						= "Yapılandırma yardımı";
$pgv_lang["config_still_writable"]				= "<i>config.php</i> dosyanız hala yazılabilirdir. Güvenlik için, bu dosyanın sadece-okuma(read-only) izne ayarlayarak sitenizi güvenli hale getirmelisiniz.";
$pgv_lang["configuration"]		= "Genel ayarlar";
$pgv_lang["configure"]							= "PhpGedView Genel ayarlar";
$pgv_lang["configure_head"]						= "PhpGedView Genel ayarlar";
$pgv_lang["confirm_gedcom_delete"]				= "Bu GEDCOM\'u silmek istediğinizden emin misiniz";
$pgv_lang["confirm_user_delete"]	= "Üyeyi hakikatten silmek mi istiyorsunuz";
$pgv_lang["create_user"]		= "Yeni üye oluştur";
$pgv_lang["current_users"]						= "Kullanıcı Listesi";
$pgv_lang["daily"]								= "Günlük";
$pgv_lang["dataset_exists"]		= "Veri tabanına bu isim altında başka bir GEDCOM-Dosyası ithal edilmiştir.";
$pgv_lang["unsync_warning"] 					= "Bu GEDCOM dosyası veritabanı ile senkronize <em>edilemez</em>. Veriniz en son versiyon için kapsamıyor. Dosyadan daha ziyade veritabanından tekrar içeri aktarın, indirip ve tekrar yüklemeniz önerilir.";
$pgv_lang["date_registered"]					= "Üyelik tarihi";
$pgv_lang["day_before_month"]		= "Önce gün sonra ay (GG AA SSSS)";
$pgv_lang["DEFAULT_GEDCOM"]						= "Varsayılan GEDCOM";
$pgv_lang["default_user"]						= "Varsayılan yönetici kullanıcı oluşturun.";
$pgv_lang["del_gedrights"]						= "GEDCOM artık aktif değil, kullanıcı referansları kaldır.";
$pgv_lang["del_proceed"]						= "Devam";
$pgv_lang["del_unvera"]							= "Kullanıcılar yönetici onaylamaz.";
$pgv_lang["del_unveru"]							= "Kullnıcı 7 gün içinde doğrulamadı.";
$pgv_lang["do_not_change"]		= "Değiştirme";
$pgv_lang["download_gedcom"]		= "GEDCOM dosyasını indir";
$pgv_lang["download_here"]						= "Dosyayı indirmek için buraya tıkla.";
$pgv_lang["editaccount"]		= "Profilini düzenleyebilir";
$pgv_lang["empty_dataset"]		= "Veri kümesini hakikatten silmek istiyor musunuz?";
$pgv_lang["empty_lines_detected"]	= "GEDCOM veritabanınızda boş sıralar bulunmuştur. Temizlemeyi seçerseniz bunlar silinecektir.";
$pgv_lang["enable_disable_lang"]				= "Desteklenecek Lisanların Ayarları";
$pgv_lang["error_ban_server"]       			= "Geçersiz IP adresi";
$pgv_lang["error_delete_person"]   				= "Uzak serverdeki kişiyi silmek için sileneceğiniz kişiyi seçmediniz.";
$pgv_lang["error_header_write"] 	= "[#GEDCOM#] isimli GEDCOM dosyasına yazma izni yoktur. Check attributes and access rights.";
$pgv_lang["error_remove_site"]					= "Uzak sunucu kaldırılamaz.";
$pgv_lang["error_remove_site_linked"]			= "Uzak sunucu kaldırılamaz çünkü bağlantı listesi boş değil.";
$pgv_lang["error_remote_duplicate"]				= "Bu uzak sunucu veritabanı <i>#GLOBALS[whichFile]#</i> olarak zaten listede";
$pgv_lang["error_siteauth_failed"]				= "Uzak serveri doğrulamak başarısız oldu";
$pgv_lang["error_url_blank"]					= "Lütfen uzak server başlığı veya adresi boş geçmeyin";
$pgv_lang["error_view_info"]       				= "Kişiel bilgiyi görüntülemek için kişiyi seçmelisiniz.";
$pgv_lang["example_date"]		= "GEDCOM veritabanınızdan hatalı bir tarih biçiminin örneği:";
$pgv_lang["example_place"]						= "GEDCOM'unuzda geçersiz yer örneği:";
$pgv_lang["found_record"]		= "Bulunan kayıt";
$pgv_lang["ged_download"]						= "İndir";
$pgv_lang["ged_import"]			= "İçeri aktar";
$pgv_lang["ged_export"] 						= "Dışarı aktar";
$pgv_lang["ged_check"] 							= "Kontrol et";
$pgv_lang["gedcom_adm_head"]					= "GEDCOM Yönetimi";
$pgv_lang["gedcom_config_write_error"]	= "HATA!!! GEDCOM yapılandırma dosyasına yazamıyorum.";
$pgv_lang["gedcom_downloadable"]	= "Bu GEDECOM dosyası İnternet üzerinden indirilebilinir!<br />Lütfen <a href=\"readme.txt\">readme.txt</a> dosyasının \"SECURITY\" bölümünü okuyup bu sorunu ortadan kaldırın.";
$pgv_lang["gedcom_file"]		= "GEDCOM dosyası";
$pgv_lang["gedcom_not_imported"]				= "Bu GEDCOM henüz içeri aktarımadı.";
$pgv_lang["img_admin_settings"] 				= "Resim işleme konfigürasyonunu düzenle";
$pgv_lang["autoContinue"]						= "Otomatikman «Devam» butonu";
$pgv_lang["import_complete"]		= "İçerik aktarım tamamlandı";
$pgv_lang["import_options"]						= "İçeri aktarma Seçenekleri";
$pgv_lang["import_progress"]		= "İthal gelişimi...";
$pgv_lang["import_statistics"]					= "İçeri aktarma İstatistikleri";
$pgv_lang["import_time_exceeded"]				= "İletişim sınır süresi aşıldı. GEDCOM dosyasını içeri aktarmaya yeniden başlamak için alttaki devam düğmesini tılayın.";
$pgv_lang["inc_languages"]		= "Diller";
$pgv_lang["INDEX_DIRECTORY"]		= "İndeks dosyalarının dizini";
$pgv_lang["invalid_dates"]		= "Hatalı tarih biçimleri bulunmuştur. Temizlemeyi seçerseniz bunlar GG AAA SSSS (örnek: 1 JAN 2004) biçimine çevirilecektir.";
$pgv_lang["BOM_detected"] 						= "Bayt düzen notu (BOM) dosyanın başlangıcında belirlendi. Temizlemede bu özel kod kaldırılacaktır.";
$pgv_lang["invalid_header"] 					= "GEDCOM başlık <b>0&nbsp;HEAD</b> dan önce satırlar belirlendi. Temizlemede bu satırlar kaldırılacaktır.";
$pgv_lang["label_added_servers"]				= "Eklenen Uzak Sunucular";
$pgv_lang["label_banned_servers"]  				= "IP yoluyla Siteleri Banla";
$pgv_lang["label_families"]         			= "Aileler";
$pgv_lang["label_gedcom_id2"]       			= "GEDCOM ID:";
$pgv_lang["label_individuals"]      			= "Kişiseller";
$pgv_lang["label_manual_search_engines"]		= "Elle IP yoluyla arama motorlarını işaretle";
$pgv_lang["label_new_server"]     				= "Yeni site ekle";
$pgv_lang["label_password_id"]					= "Şifre";
$pgv_lang["label_server_info"]     				= "Tüm kişiler Başka sisteden bağlandılar:";
$pgv_lang["label_server_url"]       			= "Site URL/IP";
$pgv_lang["label_username_id"]					= "Kullanıcı adı";
$pgv_lang["label_view_local"]       			= "Kişinin buradaki bilgisini görüntüle";
$pgv_lang["label_view_remote"]     			 	= "Kişinin başka siteden gelen bilgisini görüntüle";
$pgv_lang["LANG_SELECTION"] 					= "Desteklenecek Lisanlar";
$pgv_lang["LANGUAGE_DEFAULT"]					= "Sitenizi destekleyecek lisanları ayarlamadınız. PhpGedView faaliyetinde hazın gelen varsayılanı kullanacaktır.";
$pgv_lang["last_login"]							= "Son giriş";
$pgv_lang["lasttab"]							= "Kişisel İçin Son Ziyaretindeki Gibi";
$pgv_lang["leave_blank"]						= "Eğer güncel şifreyi kullanmaya devam etmek istiyorsanız şifre alanını boş geçin.";
$pgv_lang["link_manage_servers"]   				= "Site Yönetimi";
$pgv_lang["logfile_content"]		= "Sistemin günlük raporunun içeriği. Günlük dosyasının isimi:";
$pgv_lang["macfile_detected"]					= "Macintosh dosyası belirlendi. Temizlemede sizin dosya DOS dosyasına dönüştürülecektir.";
$pgv_lang["mailto"]								= "Mailto link";
$pgv_lang["merge_records"]		= "Kayıtları birleştir";
$pgv_lang["message_to_all"]						= "Tüm kullanıcılara mesaj gönder";
$pgv_lang["messaging"]							= "PhpGedView dahili mesaj sistemi";
$pgv_lang["messaging2"]							= "PhpGedView dahili mesaj sistemi ve E-posta";
$pgv_lang["messaging3"]							= "PhpGedView sunucuda Kaydetmeden E-postaları yolla";
$pgv_lang["month_before_day"]		= "Önce ay sonra gün (AA GG SSSS)";
$pgv_lang["monthly"]							= "Aylık";
$pgv_lang["mysql"]								= "MySQL";
$pgv_lang["never"]								= "Şimdilik yok";
$pgv_lang["no_logs"]							= "Giriş Devre dışı";
$pgv_lang["no_messaging"]						= "İletişim metodu yok";
$pgv_lang["page_views"]							= "&nbsp;&nbsp;sayfa içinde görüntüle&nbsp;&nbsp;";
$pgv_lang["performing_validation"]				= "GEDCOM onaylaması yapılıyor...";
$pgv_lang["pgv_config_write_error"] 			= "Hata!!! Php soy görüntüleme konfigürasyon dosyasına yazamıyorum. Lütfen dosya ve klasörün izinlerini kontrol edin.";
$pgv_lang["PGV_MEMORY_LIMIT"]					= "Hafiza Kotası";
$pgv_lang["PGV_SESSION_SAVE_PATH"]	= "Oturum (session) kaydetme yolu";
$pgv_lang["PGV_SESSION_TIME"]		= "Oturum (session) zaman aşımı";
$pgv_lang["PGV_SIMPLE_MAIL"] = "Basit email alma sistemi kullan";
$pgv_lang["PGV_SMTP_ACTIVE"] 					= "Emailleri göndermede harici SMTP kullan";
$pgv_lang["PGV_SMTP_HOST"] 						= "Gönderen sunucu (SMTP) adı";
$pgv_lang["PGV_SMTP_HELO"] 						= "Gönderen domain adı";
$pgv_lang["PGV_SMTP_PORT"] 						= "SMTP Portu";
$pgv_lang["PGV_SMTP_AUTH"] 						= "Kullanıcı adı ve şifre";
$pgv_lang["PGV_SMTP_AUTH_USER"] 				= "Kullanıcı adı";
$pgv_lang["PGV_SMTP_AUTH_PASS"] 				= "Şifre";
$pgv_lang["PGV_SMTP_FROM_NAME"] 				= "Gönderen adı";
$pgv_lang["PGV_STORE_MESSAGES"]		= "Mesajları veritabanına kaydet";
$pgv_lang["phpinfo"]							= "PHP bilgisi";
$pgv_lang["place_cleanup_detected"] 			= "Geçersiz yer şifrelemesi saptandı. Bu hatalar sabitleştirilmeliler.";
$pgv_lang["please_be_patient"]		= "LÜTFEN BİRAZ SABIRLI OLUN";
$pgv_lang["privileges"]							= "Verilen haklar";
$pgv_lang["reading_file"]		= "GEDCOM dosyası okunuyor";
$pgv_lang["readme_documentation"]	= "Beni oku dokümanı";
$pgv_lang["remove_ip"] 							= "IP Kaldır";
$pgv_lang["REQUIRE_ADMIN_AUTH_REGISTRATION"]		= "Üyeleri yönetici onaylar";
$pgv_lang["review_readme"]		= "Bu PhpGedView yazılımını yapılandırmaya devam etmeden önce <a href=\"readme.txt\" target=\"_blank\">readme.txt</a> dosyasını okumanızı tavsiye ederiz.<br /><br />";
$pgv_lang["seconds"]							= "&nbsp;&nbsp;ikincileri";
$pgv_lang["select_an_option"]		= "Aşağıdaki seçeneklerden birini seçin:";
$pgv_lang["SERVER_URL"]			= "PhpGedView URL adresi";
$pgv_lang["show_phpinfo"]						= "PHP bilgi sayfasını göster";
$pgv_lang["siteadmin"]							= "Site yönetimi";
$pgv_lang["sqlite"]								= "SQLite";
$pgv_lang["sync_gedcom"]						= "GEDCOM verisi ile kullanıcı ayarlarını senkronize et";
$pgv_lang["system_time"]						= "Sunucunun Geçerli Zamanı: ";
$pgv_lang["user_time"]							= "Kullanıcının Gerçerli Zamanı: ";
$pgv_lang["TBLPREFIX"]							= "Veritabanı Tablo Öneki";
$pgv_lang["themecustomization"]					= "Özelleştirilen Konu";
$pgv_lang["time_limit"]							= "Zaman Kostası: ";
$pgv_lang["title_manage_servers"]   			= "Site Yönetimi";
$pgv_lang["title_view_conns"]       			= "Bağlantıları Görüntüle";
$pgv_lang["translator_tools"]					= "Tercüme araçları";
$pgv_lang["update_myaccount"]		= "Benim üyelik verilerimi güncelleştir";
$pgv_lang["update_user"]		= "Üye verilerini güncelleştir";
$pgv_lang["upload_gedcom"]		= "GEDCOM dosyasını yolla";
$pgv_lang["USE_REGISTRATION_MODULE"]	= "Üyelik istemine izin ver";
$pgv_lang["user_auto_accept"]					= "Bu kullanıcının yapacağı değişiklikleri otomatikman kabul et";
$pgv_lang["user_contact_method"]	= "Kullanıcı iletişim yöntemi";
$pgv_lang["user_create_error"]		= "Üye eklenemedi. Lütfen bir sayfa geri dönüp tekrar deneyin.";
$pgv_lang["user_created"]		= "Üye başarıyla eklendi.";
$pgv_lang["user_default_tab"]		= "Şahısların bilgileri sayfasında gösterilecek ilk sekme";
$pgv_lang["user_path_length"]	= "En fazla akrabalık mayremiyet uzaklığı";
$pgv_lang["user_relationship_priv"]	= "Üyelerle iletişim kurmasını kısıtla";
$pgv_lang["users_admin"]						= "Site Yöneticileri";
$pgv_lang["users_gedadmin"]						= "GEDCOM Yöneticileri";
$pgv_lang["users_total"]						= "Toplam kullanıcı sayısı";
$pgv_lang["users_unver"]			= "Kendini Onaylamayan Kullanıcı";
$pgv_lang["users_unver_admin"]		= "Yönetici Onaylamadığı Kullanıcı";
$pgv_lang["usr_deleted"]						= "Kullanıcı silindi: ";
$pgv_lang["usr_idle"]				= "Son x aydan önce giriş yapan kullanıcıları listelemek için ay seçin, Silinecek üyeleri işaretleyin: ";
$pgv_lang["usr_idle_toolong"]		= "Üyenin en son giriş yaptığı tarih: ";
$pgv_lang["usr_no_cleanup"]						= "Temizlenecek hiçbir kullanıcı bulunamadı";
$pgv_lang["usr_unset_gedcomid"]					= "GEDCOM ID için Kurulmayan";
$pgv_lang["usr_unset_rights"]					= "Doğru GEDCOM için Kurulmayan";
$pgv_lang["usr_unset_rootid"]					= "Kök ID için Kurulmayan";
$pgv_lang["valid_gedcom"]		= "Geçerli GEDCOM bulundu. Temizlemeye gerek yok. ";
$pgv_lang["validate_gedcom"]		= "GEDCOM veritabanının geçerliğini denetle";
$pgv_lang["verified"]			= "Üye onayı";
$pgv_lang["verified_by_admin"]		= "Yönetici onayı";
$pgv_lang["verify_gedcom"]						= "GEDCOM Onayla";
$pgv_lang["verify_upload_instructions"]			= "Aynı isimli bir GEDCOM dosya bulundu. Eğer devam etmeyi seçerseniz GEDCOM dosyası değiştirilecek, dosya o sizle yükledi ve dışalım işlemi tekrar başlatacak. Eğer tercih ederseniz eskiyi iptal etmek için GEDCOM değiştirilmemiş kalacak.";
$pgv_lang["view_logs"]			= "Sistemin günlük raporuna bak";
$pgv_lang["view_readme"]						= "readme.txt dosya görüntüle";
$pgv_lang["visibleonline"]		= "Çevrimiçinde diğer üyeler görsünmü?";
$pgv_lang["visitor"]							= "Ziyaretçi";
$pgv_lang["warn_users"]							= "Uyarılan kullanıcılar";
$pgv_lang["weekly"]								= "Haftalık";
$pgv_lang["welcome_new"]			= "Yeni PhpGedView websitesine hoş geldiniz.";
$pgv_lang["yearly"]								= "Yıllık";
$pgv_lang["admin_OK_subject"]					= "#SERVER_NAME# hesabı onayla";
$pgv_lang["admin_OK_message"]					= "PhpGedView site #SERVER_NAME# daki yönetici üyelik hesabınızı onayladı. Şimdi aşağıdaki linki tıklayıp PhpGedView sitesine bağlanıp giriş yapabilirsiniz: \r\n\r\n#SERVER_NAME#\r\n";

$pgv_lang["batch_update"]="GEDCOM kümeyi güncelleme/düzenleme yönetimi";

// Text for the Gedcom Checker
$pgv_lang["gedcheck"]     = "Damalı gedcom";

$pgv_lang["level"]        = "Değer";
$pgv_lang["critical"]     = "Kritik";
$pgv_lang["error"]        = "Hata";
$pgv_lang["warning"]      = "Uyarı";
$pgv_lang["info"]         = "Bilgi";
$pgv_lang["open_link"]    = "Sayfadaki Linkler için";
$pgv_lang["same_win"]     = "Aynı pencere/tablo";
$pgv_lang["new_win"]      = "Yeni pencere/tablo";
$pgv_lang["context_lines"]= "GEDCOM içereceği satır";
$pgv_lang["all_rec"]      = "Tüm kayıtlar";
$pgv_lang["err_rec"]      = "Hatalar ile kayıtlar";
$pgv_lang["missing"]      = "eksik";
$pgv_lang["multiple"]     = "çoklu";
$pgv_lang["invalid"]      = "geçersiz";
$pgv_lang["too_many"]     = "çok fazla";
$pgv_lang["too_few"]      = "Birkaç fazla";
$pgv_lang["no_link"]      = "geriye bağlanmamış";
$pgv_lang["data"]         = "veri";
$pgv_lang["see"]          = "gör";
$pgv_lang["noref"]        = "Hiçbir şey bu kayıda baş vurmaz";
$pgv_lang["tag"]          = "etiket";
$pgv_lang["spacing"]      = "aralıklı dız";
$pgv_lang["ADVANCED_NAME_FACTS"] = "Gelişmiş isim gerçekleri";
$pgv_lang["ADVANCED_PLAC_FACTS"] = "Gelişmiş yer isim gerçekleri";
$pgv_lang["SURNAME_TRADITION"] = "Soyadı geleneği";
$pgv_lang["tradition_spanish"]		= "İspanyol";
$pgv_lang["tradition_portuguese"]	= "Portekiz";
$pgv_lang["tradition_icelandic"]	= "İzlanda";
$pgv_lang["tradition_paternal"]		= "Babaya ait";
$pgv_lang["tradition_none"]			= "Hiçbiri";

$pgv_lang["check_upload"]		= "Dosyaları yükleme yeterlilik için kontrol et:";
$pgv_lang["check_gd"]		= "GD resim kütüphane için kontrol et:";
$pgv_lang["check_dom"]		= "DOM XML kütüphane için kontrol et:";
$pgv_lang["check_calendar"]		= "Gelişmiş Takvim kütüphanesi için kontrol et:";
$pgv_lang["batch_update"]="GEDCOM kümeyi güncelleme/düzenleme yönetimi";

$pgv_lang["sanity_warn4"]			= "XML işleyici kütüphanesi mevcut değil. PhpGedView yinede iş görecek ancak özelliklerin bir kismi, rapor üretimi ve sunucu hizmetleri gibi XML işleyici kütüphanesi olmadan çalışmayacaktır. Lütfen daha çok bilgi için <a href='http://www.php.net/manual/en/ref.xml.php'>http://www.php.net/manual/en/ref.xml.php</a> siteye bakın.";
$pgv_lang["sanity_warn6"]			= "Takvim kütüphanesi mevcut değil. PhpGedView yinede iş görecek görecek ancak özelliklerin bir kismi, İbrani veya Fransız gibi diğer takvimlere dönüşüm gibi özellikler çalışmayacaktır. PhpGedView scripti çalıştırmak için bir engel değildir. Lütfen daha geniç bilgi için <a href='http://www.php.net/manual/en/ref.calendar.php'>http://www.php.net/manual/en/ref.calendar.php</a> siteye bakın.";
$pgv_lang["download_note"]						= "<b>Not:</b> Büyük veritabanları indirme vermeden önce uzun bir zaman işlem yapabilir. Eğer PHP zaman aşımı uygularsa indirme bitmeden önce indirilen veritabanı dosyası tam olmayabilir.<br /><br /> Dosya doğru olarak indirildiğinden emin olmak için GEDCOM biçiminde indiriyorsanız son satırında <b>0&nbsp;TRLR</b> olduğunu kontrol edin, yada XML biçiminde indiriyorsanız son satırında <b>&lt;/database&gt;</b> olduğunu kontrol edin. <br /><br />Bu dosyalar metindir siz herhangi bir uygun metin editörü kullanarak açabilir içeriğini görebilirsiniz. İndirdiğiniz dosyaları kontrol için açtığınızda kesinlikle <b><u>kaydetmeyin</u></b> dosyanız bozulabilir.<br /><br /> Genelde GEDCOM dosya indirildiğinde uzun zaman alıyorsa içeri aktarırken de uzun zaman alabilir.";
$pgv_lang["gedcheck_text"] = "Bu modül <a href=\"http://phpgedview.sourceforge.net/ged551-5.pdf\">5.5.1 GEDCOM tanımlamasına karşı</a> bir GEDCOM dosyanın biçimini kontrol eder. Ayrıca verinizin içindeki hataların sayısınız kontrol eder. Not tanımlamada birçok verisyonlar, uzantılar ve değişkenler olduğuna dikkat edin bü yüzden \"Kritik\" olarak o bayraktan başka herhangi bir konular ile ilgilenmemelisiniz. Yardım istemeden önce tüm hataların satırında yine aynı satırda hatalar hakkında tanımlamalar bulunabilir bu yüzden lütfen kontrol etmenizi rica ediyoruz.";
$pgv_lang["tradition_polish"]		= "Polonya";
$pgv_lang["editors"]				= "Editör";
$pgv_lang["gedcom_admins"]			= "GEDCOM Yönetici";
$pgv_lang["site_admins"]			= "Site Yönetici";
$pgv_lang["nobody"]					= "Hiç kimse";
$pgv_lang["elapsedYear1"]	=	"1 yıl";
$pgv_lang["elapsedYear2"]	=	"#pgv_lang[global_num1]# yıl";
$pgv_lang["elapsedYears"]	=	"#pgv_lang[global_num1]# yıl";
$pgv_lang["elapsedMonth1"]	=	"1 ay";
$pgv_lang["elapsedMonth2"]	=	"#pgv_lang[global_num1]# ay";
$pgv_lang["elapsedMonths"]	=	"#pgv_lang[global_num1]# ay";
$pgv_lang["elapsedDay1"]	=	"1 gün";
$pgv_lang["elapsedDay2"]	=	"#pgv_lang[global_num1]# gün";
$pgv_lang["elapsedDays"]	=	"#pgv_lang[global_num1]# gün";
$pgv_lang["elapsedHour1"]	=	"1 saat";
$pgv_lang["elapsedHour2"]	=	"#pgv_lang[global_num1]# saat";
$pgv_lang["elapsedHours"]	=	"#pgv_lang[global_num1]# saat";
$pgv_lang["elapsedMinute1"]	=	"1 dakika";
$pgv_lang["elapsedMinute2"]	=	"#pgv_lang[global_num1]# dakika";
$pgv_lang["elapsedMinutes"]	=	"#pgv_lang[global_num1]# dakika";
$pgv_lang["elapsedAgo"]		=	"#pgv_lang[global_string1]# önce";
$pgv_lang["PGV_SMTP_SSL"] 						= "SSL Giriş";
$pgv_lang["enter_comment"]	= "Burada bir açıklama girebilirsiniz.";
$pgv_lang["current_links"]			= "Linkler";
$pgv_lang["add_more_links"]			= "Link ekle";
$pgv_lang["enter_pid_or_name"]		= "Bireysel numarası ya da isim girin";
$pgv_lang["add_or_remove_links"]	= "Link yönetimi";
$pgv_lang["keep"]					= "Sakla";
$pgv_lang["unlink"]					= "Linksiz";
$pgv_lang["remove"]					= "Kaldır";
$pgv_lang["keep_link"]				= "Liste içindeki link sakla";
$pgv_lang["link_exists"]			= "Bu link zaten mevcut";
$pgv_lang["id_not_valid"]			= "Geçerli bir Kişisel, Aile veya Kaynak Kimliği yok";
$pgv_lang["enter_name"]				= "Bir isim girin";
$pgv_lang["gedcheck_nothing"] = "Bu düzeyde hata bulunamadı.";
$pgv_lang["remove_link"]			= "Listeden link kaldır";
$pgv_lang["fam_nav"]				= "Aile Rehberi";
$pgv_lang["open_nav"]				= "Aile Rehberi Açık";
$pgv_lang["nav"]					= "Rehber";
$pgv_lang["add_fam_other_links"]	= "Aile Ekle, ve Link Ara";
$pgv_lang["set_links"]				= "Link Belirle";
$pgv_lang["click_choose_head_text"]	= "Ailenin Başı olarak seçmek için kişiyi tıkla.";
$pgv_lang["head"]					= "Başı";
$pgv_lang["click_choose_head"]		= "Ailenin Başı olarak kişiyi seçmek için #GLOBALS[tempStringHead]# tıklayın.";
$pgv_lang["link_added"]				= "#GLOBALS[addLinkId]# link eklendi";
$pgv_lang["link_deleted"]			= "#GLOBALS[remLinkId]# link silindi";
$pgv_lang["gedcheck_sync"] = "Veritabanına yapılmış düzeltmeler #GLOBALS[ged]# dosyaya senkronize edilemez. Dosya içeriği güncel olmayabilir. Şimdi <b><a \"#GLOBALS[ged_link]#\">dışarı</a></b> aktararak veritabanıyla onu senkronize edebilirsiniz.";
$pgv_lang["id_empty"]				= "Bir bağlantı eklemek için ID alanı boş bırakılmaz.";
$pgv_lang["no_update_CHANs"]		= "DEĞİŞİKLİK (Son Değişiklik) kayıtlarını güncelleme hayır";
$pgv_lang["no_CHANs_update"]		= "Güncellenen kayıtlar (Son Değişiklik) değiştirme";
$pgv_lang["module_admin"]			= "Modül Yönetimi";
$pgv_lang["mod_admin_installed"]	= "Modüller Kuruldu";
$pgv_lang["mod_admin_tabs"]			= "Sekmeler Yönetimi";
$pgv_lang["mod_admin_menus"]		= "Menüler Yönetimi";
$pgv_lang["add_indi_to_link_list"]	= "Link Listesine Eklemek için eklenecek kişi Adını tıkla.";
$pgv_lang["search_add_links"]		= "Link Listesine Eklemek için eklenecek kişi için ara.";
$pgv_lang["ret_admin"]				= "Yönetim sayfasına geri dön";
$pgv_lang["ret_module_admin"]		= "Modül Yönetim sayfasına geri dön";
$pgv_lang["mod_admin_settings"]		= "Modül Yapılandırma Ayarları";
$pgv_lang["mod_admin_config"]		= "Mod Ayarları";
$pgv_lang["mod_admin_order"]		= "Sıra";
$pgv_lang["mod_admin_access_level"]	= "Erişim Seviyesi";
$pgv_lang["mod_admin_hasmenu"]		= "Menü?";
$pgv_lang["mod_admin_hastab"]		= "Sekme?";
$pgv_lang["mod_admin_description"]	= "Açıklama";
$pgv_lang["mod_admin_name"]			= "Modül Adı";
$pgv_lang["mod_admin_active"]		= "Aktif";
$pgv_lang["mod_admin_version"]		= "PGV / Versiyon";
?>
