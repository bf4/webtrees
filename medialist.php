<?php
/**
 * Displays a list of the multimedia objects
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
 * @subpackage Lists
 * @version $Id$
 */
require("config.php");
require_once 'includes/functions_print_facts.php';

global $MEDIA_EXTERNAL, $THUMBNAIL_WIDTH;
global $GEDCOM, $GEDCOMS;
global $currentPage, $lastPage;

$lrm = chr(0xE2).chr(0x80).chr(0x8E);
$rlm = chr(0xE2).chr(0x80).chr(0x8F);

if (!isset($level)) $level = 0;
if (!isset($action)) $action = "";
if (!isset($filter)) $filter = "";
else {
	$filter = stripslashes($filter);
	$filter = str_replace(array($lrm, $rlm), "", $filter);
}
if (!isset($search)) $search = "yes";
if (!isset($folder)) $folder = "ALL";
if (!isset($_SESSION["medialist"])) $search = "yes";
print_header($pgv_lang["multi_title"]);
print "\n\t<div class=\"center\"><h2>".$pgv_lang["multi_title"]."</h2></div>\n\t";

$isEditUser = userCanEdit(getUserName());		//-- Determines whether to show file names

//-- automatically generate an image
if (userIsAdmin(getUserName()) && $action=="generate" && !empty($file) && !empty($thumb)) {
	generate_thumbnail($file, $thumb);
}
if ($search == "yes") {
	$medialist = array();
	if ($folder=="ALL") get_medialist(false, "", true);
	else get_medialist(true, $folder, true);

	//-- remove all private media objects
	foreach($medialist as $key => $media) {
	    print " ";

	    // Display when user has Edit rights or when object belongs to current GEDCOM
	    $disp = $isEditUser || $media["GEDFILE"]==$GEDCOMS[$GEDCOM]["id"];
	    // Display when Media objects aren't restricted by global privacy
	    $disp &= displayDetailsById($media["XREF"], "OBJE");
	    // Display when this Media object isn't restricted
	    $disp &= !FactViewRestricted($media["XREF"], $media["GEDCOM"]);
		if ($disp) {
		    $links = $media["LINKS"];
		    //-- make sure that only media with links are shown
			if (count($links) != 0) {
		        foreach($links as $id=>$type) {
		        	$disp &= displayDetailsByID($id, $type);
		        }
		    }
		}
		if (!$disp) unset($medialist[$key]);
	}
	usort($medialist, "mediasort"); // Reset numbering of medialist array
	
}
	// Create the JavaScript Arrays to hold the images
	echo "<script language='JavaScript'>\n";
	echo "var Pic = new Array();\n";	// The main images
	echo "var myImages = new Array();\n";	// The preview images
	$ix = 0;
	$dbq = "select m_file from ".$TBLPREFIX."media";	// Get the file list from the DB
	$dbr = dbquery($dbq);
	// Loop through the query reults and store the images in each array
	while($row = $dbr->fetchRow()) {	
 		echo "Pic[$ix] = '".$row[0]."';\n";
 		echo "myImages[$ix] = '".$row[0]."';\n";
 		$ix++;
	}
	echo "</script>\n"; 	
	
// A form for filtering the media items

?>
<html>
<link type="text/css" rel="StyleSheet" href="css/bluecurve.css" />
<script language="JavaScript1.2">
////////////////////////////////////////////////////////////////////
//  MOVEABLE SLIDESHOW SCRIPT
////////////////////////////////////////////////////////////////////

// Set slideShowSpeed (milliseconds)
var slideShowSpeed = 300

// Duration of crossfade (seconds)
var crossFadeDuration = 3

// =======================================
// do not edit anything below this line
// =======================================

isIE=document.all;
isNN=!document.all&&document.getElementById;
isN4=document.layers;
isHot=false;

function ddInit(e){	
  topDog=isIE ? "BODY" : "HTML";
  whichDog=document.getElementById("theLayer");  
  hotDog=isIE ? event.srcElement : e.target;  
  while (hotDog.id!="titleBar" && hotDog.tagName!=topDog){
    hotDog=isIE ? hotDog.parentElement : hotDog.parentNode;
  }  
  if (hotDog.id=="titleBar"){
    offsetx=isIE ? event.clientX : e.clientX;
    offsety=isIE ? event.clientY : e.clientY;
    nowX=whichDog.offsetLeft;
    nowY=whichDog.offsetTop;
    ddEnabled=true;
    document.onmousemove=dd;
    return false;
  }
  else ddEnabled = false;
}

function dd(e){
  if (!ddEnabled) return;
  selection = null;
  x = isIE ? nowX+event.clientX-offsetx : nowX+e.clientX-offsetx;
  whichDog.style.left=x+"px";
  y = isIE ? nowY+event.clientY-offsety : nowY+e.clientY-offsety; 
  whichDog.style.top=y+"px";
  return false;  
}

function ddN4(whatDog){
  if (!isN4) return;
  N4=eval(whatDog);
  N4.captureEvents(Event.MOUSEDOWN|Event.MOUSEUP);
  N4.onmousedown=function(e){
    N4.captureEvents(Event.MOUSEMOVE);
    N4x=e.x;
    N4y=e.y;
  }
  N4.onmousemove=function(e){
    if (isHot){
      N4.moveBy(e.x-N4x,e.y-N4y);
      return false;
    }
  }
  N4.onmouseup=function(){
    N4.releaseEvents(Event.MOUSEMOVE);
  }
}

// Hide the SlideShow
function hideMe(){
  if (isIE||isNN) whichDog.style.visibility="hidden";
  else if (isN4) document.theLayer.visibility="hide";
}

// Show the SlideShow
function showMe(){
  if (isIE||isNN) whichDog.style.visibility="visible";
  else if (isN4) document.theLayer.visibility="show";
}

document.onmousedown=ddInit;	// Called when the mouse has been clicked
document.onmouseup=Function("ddEnabled=false");	// Called when the mouse has been released

var t;
var j = 0;
var p = Pic.length;

var preLoad = new Array()
for (i = 0; i < p; i++){
   preLoad[i] = new Image();
   preLoad[i].src = Pic[i];
}

function runSlideShow(){
   if (document.all){
      document.images.SlideShow.style.filter="blendTrans(duration=2)";
      document.images.SlideShow.style.filter="blendTrans(duration=crossFadeDuration)";
      document.images.SlideShow.filters.blendTrans.Apply();    
   }
   if (!isN4) document.images.SlideShow.src = preLoad[j].src;
   if (isN4) document.layers['theLayer'].document.images['SlideShow'].src = preLoad[j].src;
   if (document.all){
      document.images.SlideShow.filters.blendTrans.Play();
   }
   j = j + 1;
   //if(document.forms[1].elements[0].checked==true){
   if(document.getElementById("cbx").checked==true){	
	   
    if(j > (p-1)){
        j=0;
        btnStartClick();
    }
   }
   else{
    if( j > (p-1)){
        j = j - 1;
    }
   }
   getImageInfo(preload[j-1]);
   t = setTimeout('runSlideShow()', slideShowSpeed);
}

// Set the current images to the preview image
function previewImage(p){
   var args = previewImage.arguments; 
   document.images.SlideShow.src = myImages[p];
   clearTimeout(t)
}

// Pause the slideshow
function btnPauseClick(){
    clearTimeout(t)
    getImageInfo();
}

// Start the slideshow
function btnStartClick(){
    t = setTimeout('runSlideShow()', slideShowSpeed);
}

// View Previous image
function btnPreviousClick(){
    clearTimeout(t);
    if(j == 0){
        j = preLoad.length - 1;
    }
    else{
        j = j - 1;
    }
    document.images.SlideShow.src = preLoad[j].src;
    t = setTimeout('runSlideShow()', slideShowSpeed);
}

// View next image
function btnNextClick(){
    clearTimeout(t);
    document.images.SlideShow.src = preLoad[j].src;
    if(j == preLoad.length - 1){
        j = 0;
    }
    else
    {
        j = j + 1;
    }
    t = setTimeout('runSlideShow()', slideShowSpeed);
}

//////////////////////////////////////////////////////////////////
// END SLIDESHOW SCRIPT
//////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////
// SCROLL IMAGES SCRIPT
//////////////////////////////////////////////////////////////////


dir=1 // 0 = left 1 = right
speed=0
imageSize=0  // % set to zero to use fixedWidth and fixedHeight values
fixedWidth=40 // set a fixed width
fixedHeight=40 // set a fixed height
spacerWidth=5 // space between images

popupLeft= 100 // pixels
popupTop= 100 // pixels

biggest=0
ieBorder=0
totalWidth=0
hs3Timer=null

preload=new Array()
for(var i=0;i<myImages.length;i++){
preload[i]=new Image()
preload[i].src=myImages[i]
}

function initHS3(){
scroll1=document.getElementById("scroller1")

for(var j=0;j<myImages.length;j++){
<!--scroll1.innerHTML+='<img id="pic'+j+'" src="'+preload[j].src+'" alt="'+myImages[j][2]+'" onclick="previewImage('+j+')" style="cursor:pointer;">'-->
scroll1.innerHTML+='<img id="pic'+j+'" src="'+preload[j].src+'" alt="'+myImages[j]+'" onclick="previewImage('+j+')" style="cursor:pointer;">'
if(imageSize!=0){ // use percentage size
newWidth=preload[j].width/2000*imageSize
newHeight=preload[j].height/1600*imageSize
}
else{ // use fixed size
newWidth=fixedWidth
newHeight=fixedHeight

}

document.getElementById("pic"+j).style.width=newWidth+"px"
document.getElementById("pic"+j).style.height=newHeight+"px"


if(document.getElementById("pic"+j).offsetHeight>biggest){
biggest=document.getElementById("pic"+j).offsetHeight
}

document.getElementById("pic"+j).style.marginLeft=spacerWidth+"px"

totalWidth+=document.getElementById("pic"+j).offsetWidth+spacerWidth

}

totalWidth+=1

for(var k=0;k<myImages.length;k++){ // vertically center images
document.getElementById("pic"+k).style.marginBottom = (biggest-document.getElementById("pic"+k).offsetHeight)/2+"px"
}

if(document.getElementById&&document.all){
ieBorder=parseInt(document.getElementById("scrollbox").style.borderTopWidth)*2
}

document.getElementById("scrollbox").style.height=biggest+ieBorder+"px"

scroll1.style.width=totalWidth+"px"

scroll2=document.getElementById("scroller2")
scroll2.innerHTML=scroll1.innerHTML
scroll2.style.left= (-scroll1.offsetWidth)+"px"
scroll2.style.top= -scroll1.offsetHeight+"px"
scroll2.style.width=totalWidth+"px"

if(dir==1){
speed= -speed
}
<!--scrollHS3()-->
}


function scrollHS3(){
if(paused==1){return}
clearTimeout(hs3Timer)

//speed=-3
scroll1Pos=parseInt(scroll1.style.left)
scroll2Pos=parseInt(scroll2.style.left)

scroll1Pos-=speed
scroll2Pos-=speed

scroll1.style.left=scroll1Pos+"px"
scroll2.style.left=scroll2Pos+"px"

hs3Timer=setTimeout("scrollHS3()",50)

if(dir==0){
if(scroll1Pos< -scroll1.offsetWidth){
scroll1.style.left=scroll1.offsetWidth+"px"
}

if(scroll2Pos< -scroll1.offsetWidth){
scroll2.style.left=scroll1.offsetWidth+"px"
}
}

if(dir==1){
if(scroll1Pos>parseInt(document.getElementById("scrollbox").style.width)){
//scroll1.style.left=scroll2Pos+ (-scroll1.offsetWidth)+"px"
scroll1.style.left=-scroll1.offsetWidth+"px"
}

if(scroll2Pos>parseInt(document.getElementById("scrollbox").style.width)){
//scroll2.style.left=scroll1Pos+ (-scroll2.offsetWidth)+"px"
scroll2.style.left=-scroll1.offsetWidth+"px"
}
}

}

function pause(){
clearTimeout(hs3Timer)
}

paused=0
picWin=null
chkTimer=null

function showBigPic(p){

//if(myImages[p][1]!=""){
if(myImages[p]!=""){
paused=1

if(picWin&&picWin.open&&!picWin.closed){picWin.close()} // if picWin exists close it

//if(myImages[p][1].indexOf("htm")==-1){
if(myImages[p].indexOf("htm")==-1){
bigImg=new Image()
//bigImg.src=myImages[p][1]
bigImg.src=myImages[p]
data="\n<center>\n<img src='"+bigImg.src+"'>\n</center>\n"

var winProps = "left= "+popupLeft+", top = "+popupTop+", width="+(bigImg.width+20)+", height="+(bigImg.height+20)+", scrollbars=no, toolbar=no, directories=no, menu bar=no, resizable=yes, status=no"

picWin=window.open("","win1",winProps)
picWin.document.write("<HTML>\n<HEAD>\n<TITLE></TITLE>\n")
picWin.document.write("</HEAD>\n")
picWin.document.write("<BODY bgcolor='black' topmargin=10px leftmargin=10>\n")
picWin.document.write("<div id=\"display\">"+data+"</div>")
picWin.document.write("\n</BODY>\n</HTML>")
}
else{
picWin=window.open(myImages[p][1])
}

}
clearTimeout(chkTimer)
}

window.onfocus=function(){
clearTimeout(chkTimer)
if(picWin&&picWin.open&&!picWin.closed){
//chkTimer=setTimeout("picWin.close()",5000) // uncomment to have popup closed when blurred
}
paused=0
<!--scrollHS3()-->
}

function setDirLeft(){
dir=0
speed=3
scrollHS3()
}

function setDirRight(){
dir=1
speed=-3
scrollHS3()
}

// Get and display information about the current image
function getImageInfo(someImage){
    //var strName = preLoad[0].src;
    var strName = someImage.src;
    strName = strName.substr(strName.lastIndexOf("/") + 1, strName.length - strName.lastIndexOf("/"));
    
    var strSize = someImage.width + "x" + someImage.height;
    var strFileSize = someImage.fileSize + " bytes";
    nameCell = document.getElementById("imgName");
    if (nameCell) nameCell.innerHTML = "Name: "+ strName +
    "<br><br>" + "Dimensions: " + strSize +
    "<br><br>" + "Size: " + strFileSize;
    
}
//////////////////////////////////////////////////////////////////////
// END SCROLL IMAGES SCRIPT
//////////////////////////////////////////////////////////////////////


</script>

<title></title>
<style>

#scroller1 img, #scroller2 img{
border:0px solid #7777aa;
}


#slider-1 {
	margin:	10px;
	width:	auto;
}

</style>
<script type="text/javascript">
	cssFile = "css/bluecurve/bluecurve.css";
	document.write("<link type=\"text/css\" rel=\"StyleSheet\" href=\"" + cssFile + "\" />" );
</script>
<script type="text/javascript" src="js/range.js"></script>
<script type="text/javascript" src="js/timer.js"></script>
<script type="text/javascript" src="js/slider.js"></script>

<form action="medialist.php" method="get">
	<input type="hidden" name="action" value="filter" />
	<input type="hidden" name="search" value="yes" />
	<table class="list-table center width50 <?php print $TEXT_DIRECTION; ?>">
		<tr>
			<td class="list_label" colspan="2">
				<?php print_help_link("simple_filter_help","qm"); print $pgv_lang["filter"]; ?>
				&nbsp;<input id="filter" name="filter" value="<?php print PrintReady($filter); ?>"/>
			</td>
		</tr>
		<?php
			// Box for user to choose the folder
			if ($MEDIA_DIRECTORY_LEVELS > 0) {
				print "<tr><td class=\"list_label width25\">";
				print_help_link("upload_server_folder_help", "qm");
				if (empty($folder)) {
					if (!empty($_SESSION['upload_folder'])) $folder = $_SESSION['upload_folder'];
					else $folder = "ALL";
				}
				print $pgv_lang["server_folder"]."</td><td class=\"list_label wrap\">";
				$folders = array_merge(array("ALL"), get_media_folders());
				print "<span dir=\"ltr\"><select name=\"folder\">\n";
				foreach($folders as $f) {
					print "<option value=\"".$f."\"";
					if ($folder==$f) print " selected=\"selected\"";
					print ">";
					if ($f=="ALL") print $pgv_lang["all"];
					else print $f;
					print "</option>\n";
				}
				print "</select></td></tr>";
			} else print "<input name=\"folder\" type=\"hidden\" value=\"ALL\" />";
		?>
		<tr>
			<td class="list_label" colspan="2">
				<select name="max">
				<?php
					if (empty($max)) $max=20;
					foreach (array("10", "20", "30", "40", "50", "75", "100", "125", "150", "200") as $selectEntry) {
						print "<option value=\"$selectEntry\"";
						if ($selectEntry==$max) print " selected=\"selected\"";
						print ">".$selectEntry."</option>";
					}
					print "</select> ".$pgv_lang["per_page"];
				?>
			</td>
		</tr>
		<tr>
			<td class="list_label" colspan="2">
				<input type="submit" value=" &gt; "/>
			</td>
		</tr>
		<tr>
			<td class="list_label" colspan="2">				
  				<?php 
  				print "<a href=\"#\" onclick=showMe()>$pgv_lang[view_slideshow]</a>\n";
  				?> 
			</td>
		</tr>	
	</table>
</form>
<!-- BEGIN FLOATING SLIDE SHOW CODE //-->
<div id="theLayer" style="position:absolute;left:100;top:300;visibility:hidden;z-index:	100;">
<!-- Set thin outer border color here //-->
<table border="0" bgcolor="#CCCCFF" cellspacing="0" cellpadding="2" >
<tr>
<td>
<!-- Set thicker inner border color here //-->
<table border="0" bgcolor="#A1BFE0" cellspacing="0" cellpadding="5" >
<tr>
<td>
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td id="titleBar" style="cursor:move" align="middle">
  <ilayer onSelectStart="return false">
  <layer onMouseover="isHot=true;if (isN4) ddN4(theLayer)" onMouseout="isHot=false">
<!--  <font face="Arial" color="#FFFFFF">Draggable Slide Show</font> -->
<!--<input type="button" name="btnPlay" value="" onclick="btnStartClick()"> -->
<input type="image" src="images/previous.gif" onclick="btnPreviousClick()" style="cursor:pointer">
<input type="image" src="images/pause.gif" onclick="btnPauseClick()" style="cursor:pointer">
<input type="image" src="images/play.gif" onclick="btnStartClick()" style="cursor:pointer">
<input type="image" src="images/next.gif" onclick="btnNextClick()" style="cursor:pointer">
  </layer>
  </ilayer>
  </td>
  <td style="cursor:hand" align="right">
  <a style="text-decoration:none" href="#" onClick="hideMe();return false"><font color=#ffffff size=2 face=arial>X </font></a>
  </td>
  </tr>
  <tr>
  <td bgcolor="#FFFFFF" style="padding:0px" colspan="2" >
<table border="0" cellpadding="0" cellspacing="0" >
<tr>
<td id="VU" >
<!-- Set your image URL and dimensions here //-->
<img src="SS1.jpg" name='SlideShow' width=300 height=300 ></td>
<td id="imgName" valign="top" style="padding:5px" bgcolor="#ddddee" width="150" />
</tr>
</table>
  </td>
  </tr>
  </table>
  <form id="myForm">
    <input id="cbx" name="cb1" type="checkbox" value="ON">Automatically restart slideshow
    <br>
    <br>
    Slideshow Speed
    <br>
	<div class="slider" id="slider-1" tabIndex="1">
		<input class="slider-input" id="slider-input-1"/>
	</div>
	<p>
		Value: <input id="h-value" maxlength=3 width=9 onchange="s.setValue(parseInt(this.value))"/>
	</p>
	<script type="text/javascript">

	var s = new Slider(document.getElementById("slider-1"), document.getElementById("slider-input-1"));
	s.onchange = function () {
		slideShowSpeed = s.getValue() * 60;
		document.getElementById("h-value").value = s.getValue();
		document.getElementById("h-min").value = s.getMinimum();
		document.getElementById("h-max").value = s.getMaximum();		
	};
	s.setValue(50);

	window.onresize = function () {
		s.recalculate();
	};

</script>
	
    </form>
  <table align="center">
  <tr>
      <td>
        <input type="image" src="images/larrow17.gif" onmousedown="setDirLeft()" onmouseup="pause()">
        </td>
        <td>
        <DIV id="scrollbox" style="width:200px;overflow:hidden;background-color:#ddddee;border:0px solid #bbbbcc;text-align:left">
            <div id="scroller1" style="position:relative;left:0px;top:0px"></div>
            <div id="scroller2" style="position:relative"></div>
        </DIV>
        </td>
        <td>
        <input type="image" src="images/rarrow17.gif" onmousedown="setDirRight()" onmouseup="pause()">
        </td>
  </tr>
  </table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>

<!-- END FLOATING SLIDESHOW CODE //--> 
</form>
</html>
<?php

if ($action=="filter") {
	if (strlen($filter) > 1) {
		foreach($medialist as $key => $media) {
			if (!filterMedia($media, $filter, "http")) unset($medialist[$key]);
		}
	}
	usort($medialist, "mediasort"); // Reset numbering of medialist array
}
if ($search=="yes") {
	$_SESSION["medialist"] = $medialist;
} else {
	$medialist = $_SESSION["medialist"];
}
// Count the number of items in the medialist
$ct=count($medialist);
if (!isset($start)) $start = 0;
if (!isset($max)) $max = 20;
$count = $max;
if ($start+$count > $ct) $count = $ct-$start;

print "\n\t<div align=\"center\">".$ct." ".$pgv_lang["media_found"]." <br /><br />";
if ($ct>0){
	if (false) {
		print "<form action=\"$SCRIPT_NAME\" method=\"get\" > ".$pgv_lang["medialist_show"];
		print "<input type=\"hidden\" name=\"action\" value=\"filter\" />";
		print "<input type=\"hidden\" name=\"search\" value=\"yes\" />";
		print "<input type=\"hidden\" name=\"filter\" value=".$filter." />";
		print "<select name=\"max\" onchange=\"javascript:submit();\">";
		for ($i=1;($i<=20&&$i-1<ceil($ct/10));$i++) {
		        print "<option value=\"".($i*10)."\" ";
		        if ($i*10==$max) print "selected=\"selected\" ";
		        print " >".($i*10)."</option>";
		}
		print "</select> ".$pgv_lang["per_page"];
		print "</form>";
	}

	$currentPage = ((int) ($start / $max)) + 1;
	$lastPage = (int) (($ct + $max - 1) / $max);
	$IconRarrow = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["rarrow"]["other"]."\" width=\"20\" height=\"20\" alt=\"\" />";
	$IconLarrow = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["larrow"]["other"]."\" width=\"20\" height=\"20\" alt=\"\" />";
	$IconRDarrow = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["rdarrow"]["other"]."\" width=\"20\" height=\"20\" alt=\"\" />";
	$IconLDarrow = "<img src=\"".$PGV_IMAGE_DIR."/".$PGV_IMAGES["ldarrow"]["other"]."\" width=\"20\" height=\"20\" alt=\"\" />";

	print"\n\t<table class=\"list_table\">\n";

	// print page back, page number, page forward controls
	print "\n<tr><td colspan=\"2\">\n";
	print"\n\t<table class=\"list_table width100\">\n";
	print "\n<tr>\n";
	print "<td class=\"width30\" align=\"" . ($TEXT_DIRECTION == "ltr"?"left":"right") . "\">";
	if ($TEXT_DIRECTION=="ltr") {
		if ($ct>$max) {
			if ($currentPage > 1) {
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=0&amp;max=$max\">".$IconLDarrow."</a>\n";
			}
			if ($start>0) {
				$newstart = $start-$max;
				if ($start<0) $start = 0;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=$newstart&amp;max=$max\">".$IconLarrow."</a>\n";
			}
		}
	} else {
		if ($ct>$max) {
			if ($currentPage < $lastPage) {
				$lastStart = ((int) ($ct / $max)) * $max;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=$lastStart&amp;max=$max\">".$IconRDarrow."</a>\n";
			}
			if ($start+$max < $ct) {
				$newstart = $start+$count;
				if ($start<0) $start = 0;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=$newstart&amp;max=$max\">".$IconRarrow."</a>\n";
			}
		}
	}
	print "</td>";
	print "<td align=\"center\">".print_text("page_x_of_y", 0, 1)."</td>";
	print "<td class=\"width30\" align=\"" . ($TEXT_DIRECTION == "ltr"?"right":"left") . "\">";
	if ($TEXT_DIRECTION=="ltr") {
		if ($ct>$max) {
			if ($start+$max < $ct) {
				$newstart = $start+$count;
				if ($start<0) $start = 0;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=$newstart&amp;max=$max\">".$IconRarrow."</a>\n";
			}
			if ($currentPage < $lastPage) {
				$lastStart = ((int) ($ct / $max)) * $max;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=$lastStart&amp;max=$max\">".$IconRDarrow."</a>\n";
			}
		}
	} else {
		if ($ct>$max) {
			if ($start>0) {
				$newstart = $start-$max;
				if ($start<0) $start = 0;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=$newstart&amp;max=$max\">".$IconLarrow."</a>\n";
			}
			if ($currentPage > 1) {
				$lastStart = ((int) ($ct / $max)) * $max;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=0&amp;max=$max\">".$IconLDarrow."</a>\n";
			}
		}
	}
	print "</td>";
	print "</tr>\n</table></td></tr>";

	// -- print the array
	print "\n<tr>\n";

	for($i=0; $i<$count; $i++) {
	    $media = $medialist[$start+$i];

	    $isExternal = strstr($media["FILE"], "://");

		$imgsize = findImageSize($media["FILE"]);
	    $imgwidth = $imgsize[0]+40;
	    $imgheight = $imgsize[1]+150;

	    $name = trim($media["TITL"]);
		$showFile = $isEditUser;
		if ($name=="") {
			$showFile = false;
			if ($isExternal) $name = "URL";
			else $name = $media["FILE"];
	    }

	    print "\n\t\t\t<td class=\"list_value_wrap\" width=\"50%\">";
	    print "<table class=\"$TEXT_DIRECTION\">\n\t<tr>\n\t\t<td valign=\"top\" style=\"white-space: normal;\">";

	    print "<a href=\"#\" onclick=\"return openImage('".rawurlencode($media["FILE"])."',$imgwidth, $imgheight);\">";
		print "<img src=\"".thumbnail_file($media["FILE"])."\" align=\"left\" class=\"thumbnail\" border=\"none\"";
		if ($isExternal) print " width=\"".$THUMBNAIL_WIDTH."\"";
		print " alt=\"" . PrintReady($name) . "\" title=\"" . PrintReady($name) . "\" /></a>";
		print "</td>\n\t\t<td class=\"list_value_wrap\" style=\"border: none;\" width=\"100%\">";
	    print "<a href=\"#\" onclick=\"return openImage('".rawurlencode($media["FILE"])."',$imgwidth, $imgheight);\">";

	    if (begRTLText($name) && $TEXT_DIRECTION=="ltr") {
			print "(".$media["XREF"].")&nbsp;&nbsp;&nbsp;";
			print "<b>".PrintReady($name)."</b>";
	    } else {
			print "<b>".PrintReady($name)."</b>&nbsp;&nbsp;&nbsp;";
			if ($TEXT_DIRECTION=="rtl") print "&rlm;";
			print "(".$media["XREF"].")";
			if ($TEXT_DIRECTION=="rtl") print "&rlm;";
		}
		if ($showFile) {
			if ($isExternal) print "<br /><sub>URL</sub>";
			else print "<br /><sub><span dir=\"ltr\">".PrintReady($media["FILE"])."</span></sub>";
		}
		print "</a><br />";

		PrintMediaLinks($media["LINKS"], "small");

	    if (!$isExternal && !file_exists(filename_decode($media["FILE"]))) {
		    print "<br /><span class=\"error\">".$pgv_lang["file_not_found"]." <span dir=\"ltr\">".PrintReady($media["FILE"])."</span></span>";
	    }
	    print "<br /><div class=\"indent\" style=\"white-space: normal; width: 95%;\">";
	    print_fact_notes($media["GEDCOM"], $media["LEVEL"]+1);

	    print "</div>";
	    if (!$isExternal && file_exists(filename_decode($media["FILE"]))){
			$imageTypes = array("","GIF", "JPG", "PNG", "SWF", "PSD", "BMP", "TIFF", "TIFF", "JPC", "JP2", "JPX", "JB2", "SWC", "IFF", "WBMP", "XBM");
			if(!empty($imgsize[2])){
		    	print "\n\t\t\t<span class=\"label\"><br />".$pgv_lang["media_format"].": </span> <span class=\"field\" style=\"direction: ltr;\">" . $imageTypes[$imgsize[2]] . "</span>";
			} else if(empty($imgsize[2])){
			    $path_end=substr($media["FILE"], strlen($media["FILE"])-5);
			    $imageType = strtoupper(substr($path_end, strpos($path_end, ".")+1));
		    	print "\n\t\t\t<span class=\"label\"><br />".$pgv_lang["media_format"].": </span> <span class=\"field\" style=\"direction: ltr;\">" . $imageType . "</span>";
			}

			$fileSize = filesize(filename_decode($media["FILE"]));
			$sizeString = getfilesize($fileSize);
			print "&nbsp;&nbsp;&nbsp;<span class=\"field\" style=\"direction: ltr;\">" . $sizeString . "</span>";
		}

			if(!empty($imgsize[0]) && !empty($imgsize[1])){
		    	print "\n\t\t\t<span class=\"label\"><br />".$pgv_lang["image_size"].": </span> <span class=\"field\" style=\"direction: ltr;\">" . $imgsize[0] . ($TEXT_DIRECTION =="rtl"?" &rlm;x&rlm; " : " x ") . $imgsize[1] . "</span>";
			}

	    print "</td></tr></table>\n";
	    print "</td>";
	    if ($i%2 == 1 && $i < ($count-1)) print "\n\t\t</tr>\n\t\t<tr>";
	}
	print "\n\t\t</tr>";

	// print page back, page number, page forward controls
	print "\n<tr><td colspan=\"2\">\n";
	print"\n\t<table class=\"list_table width100\">\n";
	print "\n<tr>\n";
	print "<td class=\"width30\" align=\"" . ($TEXT_DIRECTION == "ltr"?"left":"right") . "\">";
	if ($TEXT_DIRECTION=="ltr") {
		if ($ct>$max) {
			if ($currentPage > 1) {
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=0&amp;max=$max\">".$IconLDarrow."</a>\n";
			}
			if ($start>0) {
				$newstart = $start-$max;
				if ($start<0) $start = 0;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=$newstart&amp;max=$max\">".$IconLarrow."</a>\n";
			}
		}
	} else {
		if ($ct>$max) {
			if ($currentPage < $lastPage) {
				$lastStart = ((int) ($ct / $max)) * $max;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=$lastStart&amp;max=$max\">".$IconRDarrow."</a>\n";
			}
			if ($start+$max < $ct) {
				$newstart = $start+$count;
				if ($start<0) $start = 0;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=$newstart&amp;max=$max\">".$IconRarrow."</a>\n";
			}
		}
	}
	print "</td>";
	print "<td align=\"center\">".print_text("page_x_of_y", 0, 1)."</td>";
	print "<td class=\"width30\" align=\"" . ($TEXT_DIRECTION == "ltr"?"right":"left") . "\">";
	if ($TEXT_DIRECTION=="ltr") {
		if ($ct>$max) {
			if ($start+$max < $ct) {
				$newstart = $start+$count;
				if ($start<0) $start = 0;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=$newstart&amp;max=$max\">".$IconRarrow."</a>\n";
			}
			if ($currentPage < $lastPage) {
				$lastStart = ((int) ($ct / $max)) * $max;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=$lastStart&amp;max=$max\">".$IconRDarrow."</a>\n";
			}
		}
	} else {
		if ($ct>$max) {
			if ($start>0) {
				$newstart = $start-$max;
				if ($start<0) $start = 0;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=$newstart&amp;max=$max\">".$IconLarrow."</a>\n";
			}
			if ($currentPage > 1) {
				$lastStart = ((int) ($ct / $max)) * $max;
				print "<a href=\"medialist.php?folder=$folder&amp;filter=$filter&amp;search=no&amp;start=0&amp;max=$max\">".$IconLDarrow."</a>\n";
			}
		}
	}
	print "</td>";
	print "</tr>\n</table></td></tr>";
	print "</table><br />";
}
print "\n</div>\n";
?>
<script language="JavaScript" type="text/javascript">
runSlideShow(); 
initHS3();
</script>
<?php
print_footer();
?>