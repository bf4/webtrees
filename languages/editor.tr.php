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
 * @translator Kurt Norgaz
 * @translator Adem Genç
 * @version $Id$
 */
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Doğrudan lisan dosyasına erişemezsiniz.";
	exit;
}

$pgv_lang["accept_changes"]		= "Veritabanındaki değişiklikleri kabul/ret et";
$pgv_lang["replace"]			= "Kayıdı değiştirdi";
$pgv_lang["append"] 			= "Kayıt ekledi";
$pgv_lang["review_changes"]		= "GEDCOM içindeki değişiklikleri göster";
$pgv_lang["remove_object"]			= "Nesneyi Kaldır";
$pgv_lang["remove_links"]			= "Linkleri Kaldır";
$pgv_lang["media_not_deleted"]		= "Media klasörü taşınamadı.";
$pgv_lang["thumbs_not_deleted"]		= "Tırnak önizleme klasörü kaldırılamadı.";
$pgv_lang["thumbs_deleted"]			= "Tırnak önizleme klasörü başarılı biçimde kaldırıldı.";
$pgv_lang["show_thumbnail"]		= "Tırnak resimleri göster";
$pgv_lang["link_media"]			= "Mültimedya bağla";
$pgv_lang["to_person"]				= "Kişiye";
$pgv_lang["to_family"]				= "Aileye";
$pgv_lang["to_source"]				= "Kaynağa";
$pgv_lang["edit_fam"]				= "Aile Düzenle";
$pgv_lang["copy"]					= "Kopyala";
$pgv_lang["cut"]					= "Kes";
$pgv_lang["sort_by_birth"]			= "Doğum tarihi yoluyla sırala";
$pgv_lang["reorder_children"]		= "Tekrar çocukları sırala";
$pgv_lang["add_unlinked_person"]	= "Yeni şahıs ekle";
$pgv_lang["add_unlinked_source"]	= "Yeni kaynak linklenmedi";
$pgv_lang["server_file"]				= "Serverde dosya ismi";
$pgv_lang["server_file_advice"]			= "Orijinal dosya adı değiştirilemedi duruyor.";
$pgv_lang["server_file_advice2"]		= "&laquo;http://&raquo; ile başlayan URL gire bilirsiniz.";
$pgv_lang["server_folder_advice2"]		= "Eğer dosya isim alanına URL girdiyseniz bu giriş dikkate alınmaz.";
$pgv_lang["add_linkid_advice"]			= "Bu media bağlı olduğu yeri bulmak için girip kişinin, ailenin yada kaynağın ID bilgileriniz ara.";
$pgv_lang["use_browse_advice"]			= "Yerel bilgisayarınızdan dosya aramak için &laquo;Gözat&raquo; butonunu kullanın.";
$pgv_lang["add_media_other_folder"]		= "Diğer klasör... lütfen tipini gir ";
$pgv_lang["add_media_file"]				= "Sunucuda var olan media dosyalar";
$pgv_lang["date_of_entry"]				= "Orijinal kaynağın giriş tarihi";
$pgv_lang["main_media_ok1"]				= "Ana media dosya <b>#GLOBALS[oldMediaName]#</b> yeniden başarılı biçimde <b>#GLOBALS[newMediaName]#</b> olarak adlandırıldı.";
$pgv_lang["main_media_fail0"]			= "Ana media dosya <b>#GLOBALS[oldMediaFolder]##GLOBALS[oldMediaName]#</b> mevcut değil.";
$pgv_lang["thumb_media_ok1"]			= "Tırnak önizleme dosya <b>#GLOBALS[oldMediaName]#</b> yeniden başarılı biçimde <b>#GLOBALS[newMediaName]#</b> olarak adlandırıldı.";
$pgv_lang["thumb_media_fail0"]			= "Tırnak önizleme dosya <b>#GLOBALS[oldThumbFolder]##GLOBALS[oldMediaName]#</b> mevcut değil.";
$pgv_lang["add_asso"]				= "Yeni bir ilişki / eş ekle";
$pgv_lang["edit_sex"]				= "Cinsiyet Düzenle";
$pgv_lang["add_obje"]			= "Yeni mültimedya nesnesini ekle";
$pgv_lang["add_name"]			= "Yeni isim ekle";
$pgv_lang["edit_raw"]			= "Ham GEDCOM kayıdını düzenle";
$pgv_lang["label_add_remote_link"]  = "Link Ekle";
$pgv_lang["label_local_id"]         = "Kişisel ID";
$pgv_lang["accept"]			= "Kabul et";
$pgv_lang["accept_all"] 		= "Tüm değişiklikleri kabul et";
$pgv_lang["accept_successful"]		= "Değişiklikler başarı ile veritabanına işlenmiştir";
$pgv_lang["add_child"]			= "Çocuk ekle";
$pgv_lang["add_child_to_family"]	= "Bu aileye bir çocuk ekle";
$pgv_lang["add_fact"]			= "Yeni hadise ekle";
$pgv_lang["add_father"]			= "Yeni bir baba ekle";
$pgv_lang["add_husb"]			= "Erkek eş ekle";
$pgv_lang["add_husb_to_family"]		= "Bu aileye erkek eş ekle";
$pgv_lang["add_media"]			= "Yeni mültimedya nesnesini ekle";
$pgv_lang["add_media_lbl"]		= "Mültimedya nesnesini ekle";
$pgv_lang["add_mother"]			= "Yeni bir anne ekle";
$pgv_lang["add_new_chil"]		= "Yeni çocuk ekle";
$pgv_lang["add_new_husb"]		= "Yeni bir erkek eş ekle";
$pgv_lang["add_new_wife"]		= "Yeni bir bayan eş ekle";
$pgv_lang["add_note"]			= "Hadiseye not ekle";
$pgv_lang["add_note_lbl"]		= "Not ekle";
$pgv_lang["add_sibling"]		= "Erkek ya da kız kardeş ekle";
$pgv_lang["add_son_daughter"]		= "Erkek ya da kız çocuk ekle";
$pgv_lang["add_source"]			= "Hadiseye yeni bir kaynak alıntısını ekle";
$pgv_lang["add_source_lbl"]		= "Kaynak alıntısını ekle";
$pgv_lang["add_wife"]			= "Bayan eş ekle";
$pgv_lang["add_wife_to_family"]		= "Bu aileye bayan eş ekle";
$pgv_lang["advanced_search_discription"] = "Gelişmiş site arama";
$pgv_lang["auto_thumbnail"]			= "Otomatik tırnak önizleme";
$pgv_lang["basic_search"]			= "ara";
$pgv_lang["basic_search_discription"] = "Basit site arama";
$pgv_lang["birthdate_search"]		= "Doğum tarihi: ";
$pgv_lang["birthplace_search"]		= "Doğum yeri: ";
$pgv_lang["change"]					= "Değiştir";
$pgv_lang["change_family_members"]	= "Aile Üyeleri Değiş";
$pgv_lang["changes_occurred"]		= "Bu şahısın hakkında yapılan değişiklikler";
$pgv_lang["confirm_remove"]			= "Aileden bu kişiyi kaldırmak istediğinizden emin misiniz?";
$pgv_lang["confirm_remove_object"]	= "Databaseden bu nesneyi kaldırmak istediğinizden eminmisiniz?";
$pgv_lang["create_repository"]		= "Saklama yeri oluştur";
$pgv_lang["create_source"]		= "Yeni kaynak ekle";
$pgv_lang["current_person"]         = "Geçerli olarak aynı";
$pgv_lang["date"]			= "Tarih";
$pgv_lang["deathdate_search"]		= "Ölüm tarihi: ";
$pgv_lang["deathplace_search"]		= "Ölüm yeri: ";
$pgv_lang["delete_dir_success"]		= "Media ve tırnak önizleme klasörleri başarılı biçimde kaldırıldı.";
$pgv_lang["delete_file"]			= "Dosya sil";
$pgv_lang["delete_repo"]			= "Saklama yeri sil";
$pgv_lang["directory_not_empty"]	= "Klasör boş.";
$pgv_lang["directory_not_exist"]	= "Klasör mevcut değil";
$pgv_lang["error_remote"]           = "Bir uzak siteyi seçtiniz.";
$pgv_lang["error_same"]             = "Aynı siteyi seçtiniz.";
$pgv_lang["external_file"]			= "Bu medya nesne bu serverdeki bir dosya olarak mevcut değil. Bu nesne silinmiş, taşınmış veya yeniden adlandırılmış olabilir.";
$pgv_lang["family"]			= "Aile";
$pgv_lang["file_missing"]		= "Dosya buraya ulaşmadı. Tekrar yollayın.";
$pgv_lang["file_partial"]		= "Dosya tamamen yollanamadı. Lütfen tekrar deneyin";
$pgv_lang["file_success"]		= "Dosya başarı ile yollandı.";
$pgv_lang["file_too_big"]		= "Yollanılan dosya izin verilen büyüklüğü geçiyor.";
$pgv_lang["folder"]		 			= "Serverdeki dosya";
$pgv_lang["gedcom_editing_disabled"]	= "Bu GEDCOM veritabanının düzenlenmesi sistem yöneticisi tarafından engellenmiştir.";
$pgv_lang["gedcomid"]			= "Şahsın GEDCOM kayıt numarası";
$pgv_lang["gedrec_deleted"] 		= "GEDCOM kayıtı başarı ile silindi.";
$pgv_lang["gen_thumb"]				= "Tırnak önizleme oluştur";
$pgv_lang["gender_search"]			= "Cinsiyet: ";
$pgv_lang["generate_thumbnail"]		= "Otomatik olarak tırnak önizleme üret";
$pgv_lang["hebrew_givn"]			= "İbranice Verilmiş İsimler";
$pgv_lang["hebrew_surn"]			= "İbranice Soyad";
$pgv_lang["hide_changes"]		= "Buraya tıklayıp değişiklikleri saklayın.";
$pgv_lang["highlighted"]		= "Vurgulanan resim";
$pgv_lang["illegal_chars"]			= "Boş isim yada isimdeki geçersiz karakterler";
$pgv_lang["invalid_search_multisite_input"] = "Lütfen birini girin: İsim, Doğum Tarihi, Doğum Yeri, Ölüm Tarihi, Ölüm Yeri ve Cinsiyet ";
$pgv_lang["invalid_search_multisite_input_gender"] = "Lütfen daha fazla bilgi için cinsiyet arama yap";
$pgv_lang["label_diff_server"]      = "Farklı Site ";
$pgv_lang["label_location"]         = "Site Konumu ";
$pgv_lang["label_password_id2"]		= "Şifre: ";
$pgv_lang["label_remote_id"]        = "Uzak Kişi ID";
$pgv_lang["label_same_server"]      = "Aynı site";
$pgv_lang["label_site"]             = "Site";
$pgv_lang["label_site_url"]         = "Site URL:";
$pgv_lang["label_username_id2"]		= "Kullanıcı adı: ";
$pgv_lang["lbl_server_list"]        = "Varolan bir siteyi kullan.";
$pgv_lang["lbl_type_server"]         = "Yeni site tipi gir.";
$pgv_lang["link_as_child"]			= "Bir çocuk olarak bir var olan aileye bu kişiyi bağla";
$pgv_lang["link_as_husband"]		= "Bir koca olarak bir var olan aileye bu kişiyi bağla";
$pgv_lang["link_success"]			= "Link başarılı biçimde eklendi";
$pgv_lang["link_to_existing_media"]		= "Bir var olan medya nesneye bağla";
$pgv_lang["max_media_depth"]		= "Sadece #MEDIA_DIRECTORY_LEVELS# dizinlere derin gidebilirsiniz";
$pgv_lang["max_upload_size"]		= "Enfazla yükleme boyutu: ";
$pgv_lang["media_deleted"]			= "Media klasörü başarılı biçimde kaldırıldı.";
$pgv_lang["media_exists"]			= "Media dosyası zaten var,";
$pgv_lang["invalid_search_input"]	= "Lütfen sene ile beraber bir isim, soy isim ya da bir yerin ismini ekleyin";
$pgv_lang["media_file"]			= "Medya dosyası";
$pgv_lang["media_file_deleted"]		= "Media dosyası başarılı biçimde silindi.";
$pgv_lang["media_file_not_moved"]	= "Media dosya taşınamaz.";
$pgv_lang["media_file_not_renamed"]	= "Media dosya taşınamaz veya yeniden adlandırılanmaz.";
$pgv_lang["media_thumb_exists"]		= "Media tırnak önizleme zaten mevcut.";
$pgv_lang["multi_site_search"] 		= "Çoklu site arama";
$pgv_lang["name_search"]			= "İsim: ";
$pgv_lang["new_repo_created"]		= "Yeni saklama yeri oluşturuldu";
$pgv_lang["new_source_created"] 	= "Yeni kaynak başarı ile eklenmiştir.";
$pgv_lang["no_changes"]			= "Aktüel olarak yapılmış hiç bir değişiklik yoktur.";
$pgv_lang["photo_replace"]		= "Bu fotoğrafı eski bir fotoğraf yerine mi koymak istiyorsunuz?";
$pgv_lang["privacy_prevented_editing"]	= "Mahremiyet ayarları bu kayıtı düzenlemenizi engellemektedir.";
$pgv_lang["replace_with"]			= "İle değiştir";
$pgv_lang["show_changes"]		= "Bu kayıt güncelleştirilmiştir. Buraya tıklayıp değişiklikleri gözden geçirin.";
$pgv_lang["thumbnail"]			= "Tırnak resim";
$pgv_lang["title_remote_link"]      = "Uzak link ekle";
$pgv_lang["undo"]			= "Geri al";
$pgv_lang["undo_all"]				= "Tüm değişiklikleri geri al";
$pgv_lang["undo_all_confirm"]		= "Bu GEDCOM daki tüm değişiklikleri geri almak istediğinizden eminmisiniz?";
$pgv_lang["undo_successful"]		= "Değişiklikler geri alındı";
$pgv_lang["update_successful"]		= "Güncelleştirme başarılıydı";
$pgv_lang["upload"]					= "Yükle";
$pgv_lang["upload_error"]		= "Dosyayı yollarken bir hata oldu.";
$pgv_lang["upload_media"]		= "Medya dosyalarını yolla";
$pgv_lang["upload_successful"]		= "Yollama başarılı idi";
$pgv_lang["view_change_diff"]		= "Değiştirilen Dosyalara Bak";


$pgv_lang["admin_override"]			= "Yönetici Seçeneği";
$pgv_lang["no_update_CHAN"]			= "Değişen kayıdı güncelleştirme (Son Değişen)";
$pgv_lang["select_events"]			= "Olayları Seç";
$pgv_lang["source_events"]			= "Bu kaynakla olayları ilişkilendir";
$pgv_lang["advanced_name_fields"]	= "Ek isimler (takma ad, evlilik adı, vs)";
?>
