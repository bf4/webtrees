<?php
/**
 * =============================================================================
 * Album module for phpGedView.  Author: Brian Holland
 * album.php version 3.0  29/Jun/2007
*/
// -----------------------------------------------------------------------------
// Version
//   print "&nbsp;&nbsp; v3.0a &nbsp;&nbsp;" ;
// 25/Jly/2007
// =============================================================================
/**
 * =============================================================================
 * Brian Holland 2007
 * =============================================================================
 *
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
 * @version $Id: album.php,
 * @author Brian Holland
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>

<META HTTP-EQUIV="imagetoolbar" CONTENT="no">

  <title>Personal | Media Album v2.0</title>
 
  <link  href="modules/lightbox/css/clearbox.css" rel="stylesheet" type="text/css" />
  <link  href="modules/lightbox/css/lightbox_plus.css" rel="stylesheet" type="text/css" media="screen" />
  <script src="modules/lightbox/js/clearbox.js" type="text/javascript"></script>
  <script src="modules/lightbox/js/spica.js" type="text/javascript"></script>
  <script src="modules/lightbox/js/lightbox_plus.js" type="text/javascript"></script>

</head>

<body>

  <script src="modules/lightbox/js/wz_tooltip.js" type="text/javascript"></script> 

<?php

global $edit;

//------------------------------------------------------------------------------
// Start Main Table
//------------------------------------------------------------------------------
echo "<table border=0 width='100%'><tr>" . "\n\n";

     echo "<td>";
     for ($t=1; $t <=4; $t++) {

           if ($t==1) {
                lightbox_print_media($pid, 0, true, 1);
           }
           elseif ($t==2) {
                lightbox_print_media($pid, 0, true, 2);
           }
           elseif ($t==3) {
                lightbox_print_media($pid, 0, true, 3);
           }
           elseif ($t==4) {
                lightbox_print_media($pid, 0, true, 4);
           }
           else{
           }

     }
     echo '</td>';


//------------------------------------------------------------------------------
// Build Relatives navigator from includes/controllers/individual_ctrl
//------------------------------------------------------------------------------
     echo '<td border=0 valign="top" align="center" width=220 class="optionbox" >' . "\n" ;
     echo "<b>" . $pgv_lang["view"] . " 'Album'</b><br><br>" . "\n" ;


     echo '<table><tr><td>';
     $controller->lightbox();
     echo '</td></tr></table>';


     echo '<br></td>' . "\n\n" ;
// -----------------------------------------------------------------------------
// end Relatives navigator
// -----------------------------------------------------------------------------


//------------------------------------------------------------------------------
// End Main Table
//------------------------------------------------------------------------------
echo "</tr></table>";

echo "<center>";


?>


<script language="JavaScript1.2">

/*
Disable right click script II (on images)- By Dynamicdrive.com
For full source, Terms of service, and 100s DTHML scripts
Visit http://www.dynamicdrive.com
*/

var clickmessage="Please do not take copyrighted images!"

function disableclick(e) {
if (document.all) {
if (event.button==2||event.button==3) {
if (event.srcElement.tagName=="IMG"){
alert(clickmessage);
return false;
}
}
}
else if (document.layers) {
if (e.which == 3) {
alert(clickmessage);
return false;
}
}
else if (document.getElementById){
if (e.which==3&&e.target.tagName=="IMG"){
alert(clickmessage)
return false
}
}
}

function associateimages(){
for(i=0;i<document.images.length;i++)
document.images[i].onmousedown=disableclick;
}

if (document.all)
document.onmousedown=disableclick
else if (document.getElementById)
document.onmouseup=disableclick
else if (document.layers)
associateimages()
</script>

</body>
</html>
<?php  echo "\n" ;  ?>