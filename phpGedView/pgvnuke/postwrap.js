// ----------------------------------------------------------------------
// POST-NUKE Content Management System
// Copyright (C) 2001 by the Post-Nuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// ShowInMain for phpWebSite by Jim Flowers (jflowers@ezo.net)
//
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: postwrap.js
// Original Author of file:	Shawn McKenzie (AbraCadaver)
// Purpose of file: Auto resize IFRAME
// ----------------------------------------------------------------------

// Get all TDs in document
  var docTD = document.getElementsByTagName('TD');

// Define the IFRAME
  var theIframe = document.getElementById('PostWrap');

// Find the TD with the greatest height
  var i = 0;
  var theHeight = 0;

  while(i != docTD.length)
  {
	theTD = docTD[i];

	if(theTD.offsetHeight > theHeight)
	{
		var theHeight = theTD.offsetHeight;
	}

	i++;

  }

// Size the IFRAME
  if(theHeight != 0)
  {
	theIframe.height = theHeight;
  }
  
