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

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

//Config Parameters -------------------------------------
$pgv_lang["configure_lightbox"]		= "Lightbox-Albüm konfigürasyonu";
$pgv_lang["mediatab"]       		= "<b>Kişisel Sayfada - Medya Etiketi";
$pgv_lang["lb_appearance"]				= "Görünüm";
$pgv_lang["lb_linkAppearance"]			= "Link görünümü";
$pgv_lang["lb_MP3Only"]					= "(Sadece mp3)";
$pgv_lang["lb_admin_error"]         = "Sadece Yöneticiler için sayfa";
$pgv_lang["lb_toAlbumPage"]				= "Albüm Sayfasına Geri Dön";

$pgv_lang["lb_icon"]				= "İkon";
$pgv_lang["lb_text"]				= "Metin";
$pgv_lang["lb_both"]				= "Her ikisi";
$pgv_lang["lb_none"]				= "Hiç biri";
$pgv_lang["lb_ml_ThumbLinkAdvice"]	= "İkon, Metin, Her ikisi, yada Hiç biri";

$pgv_lang["lb_transition"]				= "Resim Geçiş Hızı";
$pgv_lang["lb_normal"]					= "Normal";
$pgv_lang["lb_double"]					= "Çift";
$pgv_lang["lb_warp"]					= "Çarpıklı";
$pgv_lang["lb_url_dimensions"]			= "URL Pencere Boyutu";
$pgv_lang["lb_url_dimensionsAdvice"]	= "Piksel olarak URL penceresinin genişiliği ve yüksekliği";
$pgv_lang["lb_width"]					= "Genişlik";
$pgv_lang["lb_height"]					= "Yükseklik";
$pgv_lang["lb_al_head_links"]		= "Kişisel Sayfada - Albüm Etiketin Başında";
$pgv_lang["lb_al_thumb_links"]		= "Kişisel Sayfada - Tırnak Önizleme Etiketi";
$pgv_lang["lb_ml_thumb_links"]		= "Tırnak önizlemeleri - Çoklu sayfa";
$pgv_lang["lb_music_file"]			= "Slayt gösterimde müzik";
$pgv_lang["lb_musicFileAdvice"]		= "Ses dosyanın sunucudaki konumu (Müziksiz için burayı boşalt)";
$pgv_lang["lb_ss_speed"]			= "Slayt gösterim hızı";
$pgv_lang["lb_ss_SpeedAdvice"]		= "Slayt değiştirme zamanı saniye olarak";

// ---------------------------------------------------------------------


$pgv_lang["lb_help"] 		 = "Albüm Yardım";
$pgv_lang["lightbox"]		 = "Albüm";
$pgv_lang["showmenu"] 		 = "Menü Göster:";
$pgv_lang["active"] 		 = "Aktif";
$pgv_lang["TYPE__other"] 	 = "Diğer";
$pgv_lang["no_media"] 		 = "Hiç biri";
$pgv_lang["TYPE__footnotes"] = "Medya notları";

$pgv_lang["ROW_TYPE__photo"] 		= $pgv_lang["TYPE__photo"];
$pgv_lang["ROW_TYPE__document"] 	= $pgv_lang["TYPE__document"];
$pgv_lang["ROW_TYPE__census"] 		= $factarray["CENS"];
$pgv_lang["ROW_TYPE__other"] 		= $pgv_lang["TYPE__other"];
$pgv_lang["ROW_TYPE__footnotes"]	= $pgv_lang["TYPE__footnotes"];

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
