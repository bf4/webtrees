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

$pgv_lang["accept_changes"]		= "Veritabanındaki değişiklikleri kabul/ret et";
$pgv_lang["replace"]			= "Kayıtı değiştir";
$pgv_lang["append"] 			= "Kayıtı ekle";
$pgv_lang["review_changes"]		= "GEDCOM içindeki değişiklikleri göster";
$pgv_lang["show_thumbnail"]		= "Tırnak resimleri göster";
$pgv_lang["link_media"]			= "Mültimedya bağla";
$pgv_lang["add_unlinked_person"]	= "Yeni şahıs ekle";
$pgv_lang["add_asso"]				= "Yeni bir ilişki / eş ekle";
$pgv_lang["add_obje"]			= "Yeni mültimedya nesnesini ekle";
$pgv_lang["add_name"]			= "Yeni isim ekle";
$pgv_lang["edit_raw"]			= "Sade GEDCOM kayıtını düzenle";
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
$pgv_lang["changes_occurred"]		= "Bu şahısın hakkında yapılan değişiklikler";
$pgv_lang["create_source"]		= "Yeni kaynak ekle";
$pgv_lang["date"]			= "Tarih";
$pgv_lang["family"]			= "Aile";
$pgv_lang["file_missing"]		= "Dosya buraya ulaşmadı. Tekrar yollayın.";
$pgv_lang["file_partial"]		= "Dosya tamamen yollanamadı. Lütfen tekrar deneyin";
$pgv_lang["file_success"]		= "Dosya başarı ile yollandı.";
$pgv_lang["file_too_big"]		= "Yollanılan dosya izin verilen büyüklüğü geçiyor.";
$pgv_lang["gedcom_editing_disabled"]	= "Bu GEDCOM veritabanının düzenlenmesi sistem yöneticisi tarafından engellenmiştir.";
$pgv_lang["gedcomid"]			= "Şahsın GEDCOM kayıt numarası";
$pgv_lang["gedrec_deleted"] 		= "GEDCOM kayıtı başarı ile silindi.";
$pgv_lang["hide_changes"]		= "Buraya tıklayıp değişiklikleri saklayın.";
$pgv_lang["highlighted"]		= "Vurgulanan resim";
$pgv_lang["invalid_search_input"]	= "Lütfen sene ile beraber bir isim, soy isim ya da bir yerin ismini ekleyin";
$pgv_lang["media_file"]			= "Medya dosyası";
$pgv_lang["new_source_created"] 	= "Yeni kaynak başarı ile eklenmiştir.";
$pgv_lang["no_changes"]			= "Aktüel olarak yapılmış hiç bir değişiklik yoktur.";
$pgv_lang["photo_replace"]		= "Bu fotoğrafı eski bir fotoğraf yerine mi koymak istiyorsunuz?";
$pgv_lang["privacy_prevented_editing"]	= "Mahremiyet ayarları bu kayıtı düzenlemenizi engellemektedir.";
$pgv_lang["show_changes"]		= "Bu kayıt güncelleştirilmiştir. Buraya tıklayıp değişiklikleri gözden geçirin.";
$pgv_lang["thumbnail"]			= "Tırnak resim";
$pgv_lang["undo"]			= "Geri al";
$pgv_lang["undo_successful"]		= "Değişiklikler geri alındı";
$pgv_lang["update_successful"]		= "Güncelleştirme başarılıydı";
$pgv_lang["upload_error"]		= "Dosyayı yollarken bir hata oldu.";
$pgv_lang["upload_media"]		= "Medya dosyalarını yolla";
$pgv_lang["upload_successful"]		= "Yollama başarılı idi";
$pgv_lang["view_change_diff"]		= "Değişiklikler dosyasına bak";


?>
