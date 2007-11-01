<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
 * @subpackage Module
 * @author Adem GENÇ uzayuydu@gmail.com http://www.muttafi.com 
 * @version $Id$
 */

//-- security check, only allow access from module.php
if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "Doğrudan lisan dosyasına erişemezsiniz.";
	exit;
}

//Config Parameters -------------------------------------
$pgv_lang["configure_lightbox"]		= "Lightbox-Albüm konfigürasyonu";
$pgv_lang["mediatab"]       		= "<b>Medya Etiketi - Kişisel Sayfada</b><br />&nbsp;&nbsp;&nbsp;&nbsp;Görünüm";
$pgv_lang["lb_admin_error"]         = "Sadece Yöneticiler için sayfa";

$pgv_lang["lb_icon"]				= "İkon";
$pgv_lang["lb_text"]				= "Metin";
$pgv_lang["lb_both"]				= "Her ikisi";
$pgv_lang["lb_none"]				= "Hiç biri";
$pgv_lang["lb_ml_ThumbLinkAdvice"]	= "İkon, Metin, Her ikisi, yada Hiç biri";

$pgv_lang["lb_al_head_links"]		= "<b>Kişisel Sayfada - Albüm Etiketi</b><br />&nbsp;&nbsp;&nbsp;&nbsp;Link görünümü";
$pgv_lang["lb_al_thumb_links"]		= "<b>Kişisel Sayfada - Tırnak Önizleme Etiketi</b><br />&nbsp;&nbsp;&nbsp;&nbsp;Link görünümü";
$pgv_lang["lb_ml_thumb_links"]		= "<b>Tırnak önizlemeleri - Çoklu sayfa</b><br />&nbsp;&nbsp;&nbsp;&nbsp;Link görünümü";
$pgv_lang["lb_music_file"]			= "<b>Slayt gösterimde müzik</b><br />&nbsp;&nbsp;&nbsp;&nbsp;(sadece mp3)";
$pgv_lang["lb_musicFileAdvice"]		= "Ses dosyanın sunucudaki konumu (Müziksiz için burayı boşalt)";
$pgv_lang["lb_ss_speed"]			= "<b>Slayt gösterim hızı</b>";
$pgv_lang["lb_ss_SpeedAdvice"]		= "Slayt değiştirme zamanı saniye olarak";

// ---------------------------------------------------------------------


$pgv_lang["lb_help"] 		 = "Albüm Yardım";
$pgv_lang["lightbox"]		 = "Albüm";
$pgv_lang["showmenu"] 		 = "Menü Göster:";
$pgv_lang["active"] 		 = "Aktif";
$pgv_lang["TYPE__other"] 	 = "Diğer";
$pgv_lang["no_media"] 		 = "Hiç biri";
$pgv_lang["TYPE__footnotes"] = "Medya notları";

$pgv_lang["census_text"]  	 = "\"Bu nüfüs sayımı görüntüleri \"Ulusal Arşivden\" orjinal kayıtlarından elde edildiler ";
$pgv_lang["census_text"] 	.= "ve ticari kullanmamak şartıyla burada gösterildiler." . "\n" ;
$pgv_lang["census_text"] 	.= "Bu web sitesinden beliren bunlar veya diğer nüfüs sayımı görüntülerinin ticari yayınlamak için talepler yöneticlerden izin alınması gerekir: ";
$pgv_lang["census_text"] 	.= "Resim kütüphanesi, Ulusal arşivler, Kew, Surrey, TW9 4DU, Birleşik Krallık.\"" . "\n" ;

$pgv_lang["lb_edit_details"] 	= "Düzenle";
$pgv_lang["lb_view_details"] 	= "Görüntüle";
$pgv_lang["lb_edit_media"] 		= "Bu medya nesne detayları düzenle";
$pgv_lang["lb_delete_media"] 	= "Bu medya nesnesini kaldır - Sadece bu kişiden bağlantıyı kaldır - Medya dosyası veya diğer bağlantıları silme";
$pgv_lang["lb_view_media"] 		= "Bu medya nesnelerini görüntüle. \nDiğer Ekstra Medya Seçenekler - Medya Görüntüleme Sayfası";
$pgv_lang["lb_add_media"] 		= "Yeni Medya Nesnesi Ekle";
$pgv_lang["lb_add_media_full"] 	= "Bu kişiye yeni bir medya nesnesi ekle";
$pgv_lang["lb_link_media"] 		= "Var olan medya nesnesini bağla";
$pgv_lang["lb_link_media_full"] = "Bu kişiye var olan bir medya nesnesini bağla";

$pgv_lang["lb_slide_show"] 		= "Slayt Gösterisi";
$pgv_lang["turn_edit_ON"] 		= "Düzenleme Modu AÇ";
$pgv_lang["turn_edit_OFF"] 		= "Düzenleme Modu KAPAT";

$pgv_lang["lb_source_avail"] 	= "Bilgi kaynağı mevcut - Burayı tıkla.";

$pgv_lang["lb_private"] 		= "Resim bağlandı <br /> bireysele özel";
$pgv_lang["lb_view_source_tip"] = "Kaynağı Görüntüle : ";
$pgv_lang["lb_view_details_tip"] = "Medya Nesnesi Görüntüle : ";

?>
