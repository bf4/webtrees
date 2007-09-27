<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 * @version $Id: lb_config.php 1358 2007-07-31 22:28:49Z windmillway $
 * @author Brian Holland
 */
 
// ----------------------------------------------------//
// Configuration parameters for Lightbox Album  //
// ----------------------------------------------------//
 
$mediatab = 1 ;   	//  Media Tab on Individual Page for Editors and Admin.   
					// Set 0 to hide Media Tab from Editors and Admin, 
					// Set 1 to show Media Tab for Editors and Admin, 
					// Media Tab is ALWAYS hidden from users


					
					

// Do not change parameters below this line -------------------------------------------
	
	// Tab id no for Lightbox
	if ($mediatab==1 && userCanEdit(getUserName())) {
		$tabno=7;
	}else{
		$tabno=3;
	}
							
	
?>