<?php
global $tabno;

// Get Javascript variables from lb_config.php --------------------------- 
//			include('modules/lightbox/lb_config.php'); 
//			if ($theme_name=="Minimal") {
				// Force icon options to "text" when we're dealing with the Minimal theme
//				if ($LB_AL_HEAD_LINKS!="none") $LB_AL_HEAD_LINKS = "text";
//				if ($LB_AL_THUMB_LINKS!="none") $LB_AL_THUMB_LINKS = "text";
//				if ($LB_ML_THUMB_LINKS!="none") $LB_ML_THUMB_LINKS = "text";
//			}			
			
			?>
			<SCRIPT LANGUAGE=Javascript>
			<?php if ($LB_MUSIC_FILE == "") { ?>
				var myMusic = null;
			<?php }else{ ?>
				var myMusic 		= '<?php print $LB_MUSIC_FILE; ?>';  	// The music file
			<?php } ?>
			var CB_SlShowTime 	= '<?php print $LB_SS_SPEED; 	?>';	// Slide show timer
			var CB_Animation	= '<?php print $LB_TRANSITION; 	?>';	// Next/Prev Image transition effect
	
			</script>	
			<?php
// ------------------------------------------------------------------------------- 	
?>
	
<?php if ($TEXT_DIRECTION == "rtl") { ?>
		<link href ="modules/lightbox/css/clearbox_music_RTL.css" 	rel="stylesheet" type="text/css" />
		<link href ="modules/lightbox/css/album_page_RTL_ff.css" 	rel="stylesheet" type="text/css" media="screen" /> 
		<script src="modules/lightbox/js/prototype.js" 				type="text/javascript"></script> 
		<script src="modules/lightbox/js/Sound.js" 					type="text/javascript"></script>
		<script src="modules/lightbox/js/clearbox.js" 				type="text/javascript"></script>
		<!--[if lte IE 7]>
		<link href ="modules/lightbox/css/album_page_RTL.css" 				rel="stylesheet" type="text/css" media="screen" /> 
		<![endif]-->				
		
<?php }else{ ?>
		<link href ="modules/lightbox/css/clearbox_music.css" 		rel="stylesheet" type="text/css" />
		<link href ="modules/lightbox/css/album_page.css" 			rel="stylesheet" type="text/css" media="screen" />  
		<script src="modules/lightbox/js/prototype.js" 				type="text/javascript"></script>  
		<script src="modules/lightbox/js/Sound.js" 					type="text/javascript"></script>
		<script src="modules/lightbox/js/clearbox.js" 				type="text/javascript"></script>		
<?php } ?>
  

  

	<script src="modules/lightbox/js/wz_tooltip.js" 			type="text/javascript"></script>  
	
<?php
/*	
// -------------------------------------------------------------------------------
			if ($TEXT_DIRECTION == "rtl") { ?>
			
				<link href ="modules/lightbox/css/clearbox_music_RTL.css" 	rel="stylesheet" type="text/css" />
				<link href ="modules/lightbox/css/album_page.css" 			rel="stylesheet" type="text/css" media="screen" /> 
				<!--[if lte IE 7]>
				<link href ="modules/lightbox/css/album_page_RTL.css" 		rel="stylesheet" type="text/css" media="screen" /> 
				<![endif]-->
			<?php 
			}else{ ?>
				<link href ="modules/lightbox/css/clearbox_music.css" 		rel="stylesheet" type="text/css" />
				<link href ="modules/lightbox/css/album_page.css" 			rel="stylesheet" type="text/css" media="screen" />  
			<?php 
			} ?>

			<script src="modules/lightbox/js/prototype.js" 				type="text/javascript"></script>  
			<script src="modules/lightbox/js/Sound.js" 					type="text/javascript"></script>
			<script src="modules/lightbox/js/clearbox.js" 				type="text/javascript"></script>

			<script src="modules/lightbox/js/wz_tooltip.js" 			type="text/javascript"></script> 
<?php

*/

// ------------------------------------------------------------------------------- 

?>