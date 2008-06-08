<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
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
 * @package PhpGedView
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 */
global $tabno ;

loadLangFile("lb_lang");	// Load Lightbox language file

// Get Javascript variables from lb_config.php --------------------------- 
//			include('modules/lightbox/lb_config.php'); 
//			if ($theme_name=="Minimal") {
				// Force icon options to "text" when we're dealing with the Minimal theme
//				if ($LB_AL_HEAD_LINKS!="none") $LB_AL_HEAD_LINKS = "text";
//				if ($LB_AL_THUMB_LINKS!="none") $LB_AL_THUMB_LINKS = "text";
//				if ($LB_ML_THUMB_LINKS!="none") $LB_ML_THUMB_LINKS = "text";
//			}			
			
			?>
			<script language="javascript" type="text/javascript">
			
			var CB_ImgDetails		= "<?php print "\- ".$pgv_lang["lb_details"]." \-";	?>";		// Detail Text
			var CB_Detail_Info		= "<?php print $pgv_lang["lb_detail_info"];			?>";		// Detail Info			
			var CB_Pause_SS			= "<?php print $pgv_lang["lb_pause_ss"]; 			?>";		// Pause Slideshow
			var CB_Start_SS			= "<?php print $pgv_lang["lb_start_ss"]; 			?>";		// Start Slideshow
			var CB_Music			= "<?php print $pgv_lang["lb_music"];				?>";		// Music On/Off 
			var CB_Zoom_Off			= "<?php print $pgv_lang["lb_zoom_off"];			?>";		// Disable Zoom
			var CB_Zoom_On			= "<?php print $pgv_lang["lb_zoom_on"];				?>";		// Zoom is Enabled		
			var CB_Close_Win		= "<?php print $pgv_lang["lb_close_win"];			?>";		// Close Lightbox Window
		
			<?php if ($LB_MUSIC_FILE == "") { ?>
				var myMusic = null;
			<?php }else{ ?>
				var myMusic 	= '<?php print $LB_MUSIC_FILE; 			?>';  	// The music file
			<?php } ?>
			var CB_SlShowTime 	= '<?php print $LB_SS_SPEED; 			?>';	// Slide show timer
			var CB_Animation	= '<?php print $LB_TRANSITION; 			?>';	// Next/Prev Image transition effect
	
			</script>	
			<?php
// ------------------------------------------------------------------------------- 	
?>
	
<?php if ($TEXT_DIRECTION == "rtl") { ?>
		<script src="modules/lightbox/js/Sound.js" 					type="text/javascript"></script>
		<script src="modules/lightbox/js/clearbox.js" 				type="text/javascript"></script>
		<!--[if lte IE 7]>
		<link href ="modules/lightbox/css/album_page_RTL.css" 				rel="stylesheet" type="text/css" media="screen" /> 
		<![endif]-->				
		
<?php }else{ ?>
		<script src="modules/lightbox/js/Sound.js" 					type="text/javascript"></script>
		<script src="modules/lightbox/js/clearbox.js" 				type="text/javascript"></script>
		
<?php  } ?>

		<script src="modules/lightbox/js/wz_tooltip.js" 			type="text/javascript"></script>
		<script src="modules/lightbox/js/tip_balloon.js" 			type="text/javascript"></script> 
<?php

		require_once("js/prototype.js.htm");
		require_once("js/scriptaculous.js.htm");


// ------------------------------------------------------------------------------- 

?>