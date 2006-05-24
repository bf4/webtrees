<?php
/**
 * Display a timeline chart for a group of individuals
 *
 * Use the $pids array to set which individuals to show on the chart
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
 * This Page Is Valid XHTML 1.0 Transitional! > 08 August 2005
 *
 * @package PhpGedView
 * @subpackage Charts
 * @version $Id$
 */

require_once("includes/controllers/timeline2_ctrl.php");

//if peeps !null then pass new array for zooming

print_header($pgv_lang["timeline_title"]);
?>
<h2><?php print $pgv_lang["timeline_chart"]; ?></h2>
<style type="text/css">
<!--
a.showit {
   position: relative;
   z-index: 24;
  
   color: #000000;
   text-decoration: none;
}
a.showit:hover {
   z-index: 25;
   background-color: #ffff9b;
   cursor: crosshair;
}
a.showit span {
   display: none;
}
a.showit:hover span {
   display: block;
   position: absolute;
   top: 22px;
   left: 0px;
   width: 225px;
   border-style: outset;
   border-left: 15px outset #1f1f1f;
   border-top: 2px solid #1f1f1f;
   border-bottom: 2px solid #000000;
   border-right: 2px solid #000000;
   padding: 3px;
   background: #4f4f4f;
   color: #ffffff;
   font-family: arial;
   font-size: 15px;
   text-align: left;
   FILTER: Alpha(Opacity=90, FinishOpacity=90, Style=2);
   -moz-opacity: .85;
   -khtml-opacity: .85;
   z-index: 5;
}
span:first-letter {
   color: #ff0000;
   font-size: 20px;
   font-weight: bold;
   font-family: arial;
   font-variant: small-caps;
   padding: 1px;
}
-->
</style>

<form name="people" action="timeline2.php">
<table>
<?php
//This is the box that adds one person at a time.  Not sure if we want to keep this functionality.
if (!$controller->isPrintPreview()) {
		if (!isset($col)) $col = 0;
		?>
		<td class="person<?php print $col; ?>" style="padding: 5px" valign="top">
			<?php print_help_link("add_person_help", "qm"); ?>
			<?php print $pgv_lang["add_another"];?>&nbsp;
			<input class="pedigree_form" type="text" size="5" id="newpid" name="newpid" />&nbsp;
			<?php print_findindi_link("newpid",""); ?>
			<br />		
			<div style="text-align: center"><input type="checkbox" checked="checked" value="yes" name="addFamily"/><?php print $pgv_lang["include_family"];?></div>
			<br />
			<div style="text-align: center"><input type="submit" value="<?php print $pgv_lang["show"]; ?>" /></div>			
		</td>
	<?php }?>
</table>
</form>
<script type="text/javascript">
<!--

var timer;
var offSetNum = 20; // amount timeline moves with each mouse click
var speed;
// method for scrolling timeline around in portal. takes in a string for the direction the timeline is moving "Left" "Right" "Top" "Down"
function startScroll(move)
{
	speed = parseInt(document.buttons.speedMenu.options[document.buttons.speedMenu.selectedIndex].value) * 25; //Sets the speed of the scroll feature
	timer = 1;
	scroll(move);
}
function scroll(move)
{
	if (timer==null) return;  // If timer is not set timeline doesn't scroll'
	timer = setTimeout("scroll('"+move+"')",speed); // Keeps the timeline moving as long as the user holds down the mouse button on one of the direction arrows
	topInnerDiv = document.getElementById("topInner");
	innerDiv = document.getElementById("inner");	
	myouterDiv = document.getElementById("outerDiv");
	
	//compares the direction the timeline is moving and how far it can move in each direction.	
	if(move == "left" && ((maxX+topInnerDiv.offsetLeft+350) > (myouterDiv.offsetLeft+myouterDiv.offsetWidth))){
		left = (innerDiv.offsetLeft - offSetNum)+"px";
		innerDiv.style.left = left;
		topInnerDiv.style.left = left;
	}
	else if(move == "right" && topInnerDiv.offsetLeft < (-10)){
		right = (innerDiv.offsetLeft + offSetNum)+"px";
		innerDiv.style.left = right;
		topInnerDiv.style.left = right;
	}
	else if(move == "up" && innerDiv.offsetTop > maxY){
		up = (innerDiv.offsetTop - offSetNum)+"px";		
		innerDiv.style.top = up;
	}
	else if(move == "down" && innerDiv.offsetTop < -60){
		down = (innerDiv.offsetTop + offSetNum)+"px";
		innerDiv.style.top = down;
	}
}
//method used to stop scrolling
function stopScroll()
{
	if (timer) clearTimeout(timer);
	timer=null;
}

var oldMx = 0;
	var oldMy = 0;
	var movei1 = "";
	var movei2 = "";
	function pandiv() {
		if (movei1=="") {
			oldMx = msX;
			oldMy = msY;
		}
		i = document.getElementById('topInner');
		//alert(i.style.top);
		movei1 = i;
		i = document.getElementById('inner');
		movei2 = i;
		return false;
	}
	function releaseimage() {
		movei1 = "";
		movei2 = "";
		return true;
	}
	// Main function to retrieve mouse x-y pos.s
	function getMouseXY(e) {
	  if (IE) { // grab the x-y pos.s if browser is IE
	    msX = event.clientX + document.documentElement.scrollLeft;
	    msY = event.clientY + document.documentElement.scrollTop;
	  } else {  // grab the x-y pos.s if browser is NS
	    msX = e.pageX;
	    msY = e.pageY;
	  }
	  // catch possible negative values in NS4
	  if (msX < 0){msX = 0;}
	  if (msY < 0){msY = 0;}
	  if (movei1!="") {
		ileft = parseInt(movei1.style.left);
		itop = parseInt(movei2.style.top);
		ileft = ileft - (oldMx-msX);
		itop = itop - (oldMy-msY);
		movei1.style.left = ileft+"px";
		movei2.style.left = ileft+"px";
		movei2.style.top = itop+"px";
		oldMx = msX;
		oldMy = msY;
		return false;
	  }
	}
	
	var IE = document.all?true:false;
	if (!IE) document.captureEvents(Event.MOUSEMOVE | Event.MOUSEUP)
	document.onmousemove = getMouseXY;
	document.onmouseup = releaseimage;
//-->
</script>
<form name="buttons" action="timeline2.php" method="get">

  <table>
   <tr>
   		<td><?php print_help_link("timeline_control_help", "qm"); ?></td>
   		<td><?php print $pgv_lang["timeline_controls"];?></td>
   		<td width="20"></td>
    	<td align="center"><?php print $pgv_lang["timeline_scrollSpeed"];?></td>
      	<td align="center"><?php print $pgv_lang["timeline_beginYear"];?></td>
      	<td align="center"><?php print $pgv_lang["timeline_endYear"];?></td>
      	<td align="center"><?php print $factarray["PLAC"];?></td>
    </tr> 
    <tr>   
     <td colspan="2">
    <table width="100%">
    <tr>
    <td><a href="#" onclick="return false;" onmousedown="startScroll('right')" onmouseup="stopScroll()"><img src="<?php print $PGV_IMAGE_DIR.'/'.$PGV_IMAGES["larrow"]["other"]; ?>" border="0" alt="" /></a></td>
      <td><a href="#" onclick="return false;" onmousedown="startScroll('left')" onmouseup="stopScroll()"><img src="<?php print $PGV_IMAGE_DIR.'/'.$PGV_IMAGES["rarrow"]["other"]; ?>" border="0" alt="" /></a></td>
      <td><a href="#" onclick="return false;" onmousedown="startScroll('up')" onmouseup="stopScroll()"><img src="<?php print $PGV_IMAGE_DIR.'/'.$PGV_IMAGES["uarrow"]["other"]; ?>" border="0" alt="" /></a></td>
      <td><a href="#" onclick="return false;" onmousedown="startScroll('down')" onmouseup="stopScroll()"><img src="<?php print $PGV_IMAGE_DIR.'/'.$PGV_IMAGES["darrow"]["other"]; ?>" border="0" alt="" /></a>

      </td></tr></table></td>
      <td></td>
      <td><select name="speedMenu" size="1">
    		<option value="4">1</option>
    		<option value="3">2</option>
    		<option value="2">3</option>
    		<option value="1">4</option>

  			</select></td>
  		<td><input type="text" name="beginYear" size="5" value="<?php if (isset($beginYear)) print $beginYear; ?>" /></td>
  		<td><input type="text" name="endYear" size="5" value="<?php if (isset($endYear)) print $endYear; ?>" /></td>
  		<td><input type="text" name="place" size="15" value="<?php if (isset($place)) print $place; ?>" /></td>
  		<td><input type="submit" name="search" value="<?php print $pgv_lang["search"]; ?>" /></td>

    </tr>  
  </table> 
  <?php if(count($controller->people) > 0){ foreach($controller->people as $ind=>$person) {
  ?> <input type="hidden" name="pids[]" value="<?php print $person->getXref(); ?>" />
  <?php }} ?>
</form>
<div id="outerDiv" style="position: relative; width: 99.5%; height: 600px; overflow: hidden; border: solid blue 1px;">
	<div id="topInner" style="position: absolute; width: 100%; left: -10px; top:-65px; z-index:2; background-color: white" onmousedown="pandiv(); return false;">		
	<?php $controller->PrintTimeline($controller->timelineMinYear,$controller->timelineMaxYear); ?>
	</div>
		<div id="inner" style="position: absolute; width: 500px; left: -10px; top: -60px; z-index:1;" onmousedown="pandiv(); return false;">
		<?php $maxY = $controller->fillTL($controller->people,$controller->minYear,$controller->YrowLoc); ?>
	<?php 	
	?>
	</div>
</div>
<script language="JavaScript" type="text/javascript">
<!--
var maxY = 80-<?php print $maxY; ?>; // Sets the boundaries for how far the timeline can move in the up direction
var maxX = <?php if(!isset($maxX)) $maxX = 0; print $maxX; ?>;  // Sets the boundaries for how far the timeline can move in the left direction

//-->
</script>

