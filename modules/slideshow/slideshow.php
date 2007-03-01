<?php
/**
 * Media List Slide Show module for phpGedView
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
 * @version $Id$
 * @package PhpGedView
 * @subpackage Module - Slideshow
 * @author John Finlay / Neumont students
 */

// TODO - Fix preview bar which is currently commented out

	// Create the JavaScript Arrays to hold the images
	echo "<script language='JavaScript'>\n";
	echo "var Pic = new Array();\n";	// The main images
	echo "var myImages = new Array();\n";	// The preview images
	
	$ix = 0;
	
	$yz = 0;
	$numImages = 0;
	foreach($medialist as $mid=>$media) {
		// privacy has already been checked by medialist.php
		// Check to see if the item is a real image
		$imgsize = @getimagesize($media['FILE']);
		if($imgsize != false){		
	 		echo "Pic[$ix] = '".addcslashes(check_media_depth($media['FILE']), "'")."';\n";
			echo "myImages[$ix] = '".addcslashes(thumbnail_file($media['FILE']), "'")."';\n";
			global $ix;
	 		$ix++;
		}
	}	
echo "</script>\n"; 	
?>
<style>
#scroller1 img, #scroller2 img{
border:0px solid #7777aa;
}
#slider-1 {
	margin:	10px;
	width:	auto;
}
</style>
<script type="text/javascript" src="modules/slideshow/js/slideshow.js"></script>
<script type="text/javascript" src="modules/slideshow/js/range.js"></script>
<script type="text/javascript" src="modules/slideshow/js/timer.js"></script>
<script type="text/javascript" src="modules/slideshow/js/slider.js"></script>

<link type="text/css" rel="StyleSheet" href="modules/slideshow/css/bluecurve.css" />
<!-- BEGIN FLOATING SLIDE SHOW CODE //-->
<div id="theLayer" style="position:absolute;left:0;top:0;visibility:hidden;z-index:	100;">
  <table border="0" cellspacing="0" cellpadding="3" class="person_box">
  <tr>
  <td id="titleBar" style="cursor:move" align="left">
  <div onSelectStart="return false">
  <div onMouseover="isHot=true;if (isN4) ddN4(theLayer)" onMouseout="isHot=false" dir="ltr">
<input type="image" src="modules/slideshow/images/previous.gif" onclick="btnPreviousClick()" style="cursor:pointer">
<input type="image" src="modules/slideshow/images/pause.gif" onclick="btnPauseClick()" style="cursor:pointer">
<input type="image" src="modules/slideshow/images/play.gif" onclick="btnStartClick()" style="cursor:pointer">
<input type="image" src="modules/slideshow/images/next.gif" onclick="btnNextClick()" style="cursor:pointer">
<img src="modules/slideshow/images/spacer.gif" width="64px" height="32px">
  <a href="#" onClick="btnPauseClick(); hideMe(); return false;"><img src="modules/slideshow/images/quit.gif"></a>
  </div>
  </div>
  </td>
  </tr>
  <tr>
  <td style="padding:0px">
<table border="0" cellpadding="0" cellspacing="0" >
<tr>
<td id="VU">
<!-- Set your image URL and dimensions here //-->
<img src="SS1.jpg" name='SlideShow' height="300"></td> 
<td id="imgName" valign="top" style="padding:5px" width="150" />
</tr>
</table>
  </td>
  <tr><td>
  <form id="myForm">
    <!--  <input id="cbx" name="cb1" type="checkbox" value="ON">Automatically restart slideshow 
    <br />
    Slideshow Speed
    <br />
    -->
	<div class="slider" id="slider-1" tabIndex="1" style="width:350px">
		<input class="slider-input" id="slider-input-1"/>
	</div>
	<script type="text/javascript">

	var s = new Slider(document.getElementById("slider-1"), document.getElementById("slider-input-1"));
	s.onchange = function () {
		slideShowSpeed = (100 - s.getValue()) * 60;
//		document.getElementById("h-value").value = s.getValue();
//		document.getElementById("h-min").value = s.getMinimum();
//		document.getElementById("h-max").value = s.getMaximum();		
	};
	s.setValue(50);

	window.onresize = function () {
		s.recalculate();
	};

</script>
	
    </form>
    <?php /* uncomment to show preview bar
  <table align="center">
  <tr>
      <td>
        <input type="image" src="modules/slideshow/images/larrow17.gif" onmousedown="setDirLeft()" onmouseup="pause()">
        </td>
        <td>
        <DIV id="scrollbox" style="width:200px; height:30px;overflow:hidden;background-color:#ddddee;border:0px solid #bbbbcc;text-align:left">
            <div id="scroller1" style="position:relative;left:0px;top:0px"></div>
            <div id="scroller2" style="position:relative"></div>
        </DIV>
        </td>
        <td>
        <input type="image" src="modules/slideshow/images/rarrow17.gif" onmousedown="setDirRight()" onmouseup="pause()">
        </td>
  </tr>
  </table>
  */ ?>
    </td>
    </tr>
  </table>
</div>

<!-- END FLOATING SLIDESHOW CODE //--> 
</form>

<script language="JavaScript" type="text/javascript">
<!--
//runSlideShow(); //-- uncomment to automatically start slide show
//initHS3(); //-- uncomment to show preview bar
//-->
</script>
