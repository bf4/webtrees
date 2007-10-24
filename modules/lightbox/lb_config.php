<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  PHPGedView Development Team
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
 
// ----------------------------------------------------//
// Configuration parameters for Lightbox Album  //
// ----------------------------------------------------//
 
$mediatab = "1";  				// Individual Page Media Tab
										// Set to 	0	to hide Media Tab on Indi page from All Users, 
										// Set to 	1	to show Media Tab on Indi page to All Users,  [Default]
										
					
$LB_AL_HEAD_LINKS = "icon";			// Album Tab Page Header Links
										// Set to "icon"	to view icon links 
										// Set to "text"	to view text links ,
										// Set to "both"	to view both. [Default]
								
$LB_AL_THUMB_LINKS = "icon"; 		// Album Tab Page below Thumbnail Links
										// Set to "icon"	to view icon links [Default]
										// Set to "text"	to view text links ,
																						
$LB_ML_THUMB_LINKS = "both"; 		// MultiMedia List Page Thumbnail Links
										// Set to "icon"	to view icon links 
										// Set to "text"	to view text links ,
										// Set to "both"	to view both. [Default]
										// Set to "none"	to view neither.
										
$LB_SS_SPEED = "4";					// SlideShow speed in seconds.  [Min 2  max 25] 										
							
$LB_MUSIC_FILE = "modules/lightbox/music/Father_to_Son.mp3";  // The music file. [mp3 only]
						

// --------------------------------------------------------- //
//  End Configuration parameters for Lightbox Album.  //
// --------------------------------------------------------- //					
?>						





				
					
<?php
// Do not change parameters below this line -------------------------------------------
	
	// Tab id no for Lightbox
	if 			($mediatab == 1 && userCanEdit(getUserName())) {
		$tabno=7;
	}else if 	($mediatab == 1 && !userCanEdit(getUserName())) {
		$tabno=7;		
	}else if	($mediatab == 0 && userCanEdit(getUserName())) {
		$tabno=3;
	}else{
		$tabno=3;	
	}
	
							
	
?>