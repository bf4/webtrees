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
 * @version $Id: lightbox_print_media.php 1358 2007-07-31 22:28:49Z windmillway $
 * @author Brian Holland
 */
/**
 * -----------------------------------------------------------------------------
 * Print the links to multi-media objects
 * @param string $pid        The the xref id of the object to find media records related to
 * @param int $level        The level of media object to find
 * @param boolean $related        Whether or not to grab media from related records
 */

// -----------------------------------------------------------------------------
// function lightbox_print_main_media($pid, $level=1, $related=false, $noedit=false) {
// -----------------------------------------------------------------------------
	
$t=$kind ;
$edit="1";

	global $MULTI_MEDIA, $TBLPREFIX, $SHOW_ID_NUMBERS, $MEDIA_EXTERNAL;
	global $pgv_lang, $pgv_changes, $factarray, $view;
	global $GEDCOMS, $GEDCOM, $MEDIATYPE, $pgv_changes, $DBCONN, $DBTYPE;
	global $WORD_WRAPPED_NOTES, $MEDIA_DIRECTORY, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION;
	global $is_media, $cntm1, $cntm2, $cntm3, $cntm4, $t, $mgedrec;
	global $typ2b, $edit, $tabno, $reorder, $SERVER_URL;
	
	require_once( $SERVER_URL . "js/prototype.js.htm" );
	require_once( $SERVER_URL . "js/scriptaculous.js.htm" ); 	

// Set type of media from call in album
if ($t==1) {
$tt      = $pgv_lang["TYPE__photo"];
$typ2b   = "(";
$typ2b  .= " (m_gedrec LIKE '%TYPE photo%')       OR ";
$typ2b  .= " (m_gedrec LIKE '%TYPE map%')         OR ";
$typ2b  .= " (m_gedrec LIKE '%TYPE tombstone%')      ";
$typ2b  .= ")";
}
if ($t==2){
$tt      = $pgv_lang["TYPE__document"];
$typ2b   = "(";
$typ2b  .= " (m_gedrec LIKE '%TYPE card%')        OR ";
$typ2b  .= " (m_gedrec LIKE '%TYPE certificate%') OR ";
$typ2b  .= " (m_gedrec LIKE '%TYPE document%')    OR ";
$typ2b  .= " (m_gedrec LIKE '%TYPE magazine%')    OR ";
$typ2b  .= " (m_gedrec LIKE '%TYPE manuscript%')  OR ";
$typ2b  .= " (m_gedrec LIKE '%TYPE newspaper%')      ";
$typ2b  .= ")";
}
if ($t==3){
$tt      = $factarray["CENS"];
$typ2b   = "(";
$typ2b  .= " (m_gedrec LIKE '%TYPE electronic%')  OR ";
$typ2b  .= " (m_gedrec LIKE '%TYPE fiche%')       OR ";
$typ2b  .= " (m_gedrec LIKE '%TYPE film%')           ";
$typ2b  .= ")";
}
if ($t==4){
$tt      = $pgv_lang["TYPE__other"];
$typ2b   = "(";
$typ2b  .= " (m_gedrec NOT LIKE '%TYPE %')        OR ";
$typ2b  .= " (m_gedrec LIKE     '%TYPE book%')    OR ";
$typ2b  .= " (m_gedrec LIKE     '%TYPE audio%')   OR ";
$typ2b  .= " (m_gedrec LIKE     '%TYPE video%')      ";
$typ2b  .= ")";
}

      if (!showFact("OBJE", $pid)) return false;
      if (!isset($pgv_changes[$pid."_".$GEDCOM])) $gedrec = find_gedcom_record($pid);
      else $gedrec = find_updated_record($pid);
      $ids = array($pid);
      //-- find all of the related ids
      if ($related) {
              $ct = preg_match_all("/1 FAMS @(.*)@/", $gedrec, $match, PREG_SET_ORDER);
              for($i=0; $i<$ct; $i++) {
                      $ids[] = trim($match[$i][1]);
              }
      }

      //-- get a list of the current objects in the record
      $current_objes = array();
      if ($level>0) $regexp = "/".$level." OBJE @(.*)@/";
      else $regexp = "/OBJE @(.*)@/";
      $ct = preg_match_all($regexp, $gedrec, $match, PREG_SET_ORDER);
      for($i=0; $i<$ct; $i++) {
              if (!isset($current_objes[$match[$i][1]])) $current_objes[$match[$i][1]] = 1;
              else $current_objes[$match[$i][1]]++;
              $obje_links[$match[$i][1]][] = $match[$i][0];
      }

$ord = array('M20', 'M515',);	  
	  
      $media_found = false;
      $sqlmm = "SELECT DISTINCT ";
      // Adding DISTINCT is the fix for: [ 1488550 ] Family/Individual Media Duplications
      // but it may not work for all RDBMS.
	  // $sqlmm  = "SELECT ";
      $sqlmm .= "m_media, m_ext, m_file, m_titl, m_gedfile, m_gedrec, mm_gid, mm_gedrec FROM ".$TBLPREFIX."media, ".$TBLPREFIX."media_mapping where ";
      $sqlmm .= "mm_gid IN (";
      $i=0;
      foreach($ids as $key=>$id) {
                if ($i>0) $sqlmm .= ",";
                $sqlmm .= "'".$DBCONN->escapeSimple($id)."'";
                $i++;
      }
      $sqlmm .= ") AND mm_gedfile = '".$GEDCOMS[$GEDCOM]["id"]."' AND mm_media=m_media AND mm_gedfile=m_gedfile ";
        //-- for family and source page only show level 1 obje references
      if ($level>0) $sqlmm .= "AND mm_gedrec LIKE '$level OBJE%'";

// BH Next line added
      $sqlmm .= " AND $typ2b ";

      $sqlmm .= " ORDER BY m_titl ";
      $resmm = dbquery($sqlmm);
      $foundObjs = array();

      $resmm1 = mysql_query($sqlmm);
      $numm = mysql_num_rows($resmm1);
	  

//BH NEW FOR ORDERING MEDIA =======================================================================================
$show_order="NO";
//$resmm2 = dbquery($sqlmm);
if ($show_order=="NO") { //do nothing in this section
}else{
//include(\"" . $SERVER_URL . "modules/lightbox/functions/sort.php ");

	$resmm2 = dbquery($sqlmm);
	if($numm>0){
		echo " Ged Objects found = ";
		while ($rowm42 = $resmm2->fetchRow(DB_FETCHMODE_ASSOC)){
			print_r($rowm42['m_media']. ", ");
			$found[] = $rowm42['m_media'];
		}
		//print "<br>Found = ";
		//print_r($found);
		if ($t==1 && $pid=="I7") {
			$preordered_objects = array('M20', 'M515',);
		}else{
			$preordered_objects = array();		
		}
		//BH print "<br>Preordered = ";
		//print_r($preordered_objects);
		if (isset($found)){
			$order = array_flip($preordered_objects);
			$fnd = array_flip($found);
		

			$new_order_objs = $order + $fnd;

			$new = (array_flip($new_order_objs));

			//BH print "<br>New order = "; 
			//print_r(($new_order_objs));

			print "<br>";
			print " New Objects found = ";
			foreach ($new_order_objs as $key => $value) {
				print "$key, ";
			}
		}else{
		}
	}else{
	}
}
//BH END FOR ORDERING MEDIA ==================================================================================

	  
//  BH added - Start If $t= etc ------------------------------------------------
      if ( $t==1 && $numm>0 || $t==2 && $numm>0 || $t==3 && $numm>0 || $t==4 && $numm>0 ) {
// -----------------------------------------------------------------------------
            echo "\n\n";
            echo '<table border=0 class="facts_table"><tr>' . "\n";;
            echo '<td width="80" align="center" class="descriptionbox">' ;
            echo "<b><br><br>" . $tt . "</b><br><br>(" . $numm . ")";
            echo '</td><td class="facts_value">';
            echo "<center>" . "\n";
            echo '<table><tr><td>' . "\n";
            echo "<center>" . "\n\n";  // needed for Firefox
            echo "<div id=\"thumbcontainer\">" . "\n";
            echo "<ul id=\"thumblist_".$t."\">" . "\n\n";

            while ($rowm = $resmm->fetchRow(DB_FETCHMODE_ASSOC)) {
		
                 if (isset($foundObjs[$rowm['m_media']])) {
                        if (isset($current_objes[$rowm['m_media']])) $current_objes[$rowm['m_media']]--;
                        continue;

                 }

                 // NOTE: Determine the size of the mediafile
                 $imgwidth = 300+40;
                 $imgheight = 300+150;
                 if (preg_match("'://'", $rowm["m_file"])) {
                        if (in_array($rowm["m_ext"], $MEDIATYPE)) {
                                $imgwidth = 400+40;
                                $imgheight = 500+150;
                        }
                        else {
                                $imgwidth = 800+40;
                                $imgheight = 400+150;
                        }
                 }
                 else if (file_exists(filename_decode(check_media_depth($rowm["m_file"], "NOTRUNC")))) {
                        $imgsize = findImageSize(check_media_depth($rowm["m_file"], "NOTRUNC"));
                        $imgwidth = $imgsize[0]+40;
                        $imgheight = $imgsize[1]+150;
                 }
                 $rows=array();

                 $rows['normal'] = $rowm;
                 if (isset($current_objes[$rowm['m_media']]))  $current_objes[$rowm['m_media']]--; {
                 }

                 foreach($rows as $rtype => $rowm) {
//                  if  ( FactViewRestricted($rowm['m_media'], $rowm['m_gedrec']) == "true" )

                         $res = lightbox_print_media_row($rtype, $rowm, $pid);
                         $media_found = $media_found || $res;
                         $foundObjs[$rowm['m_media']]=true;

                 }

                 $mgedrec[] = $rowm["m_gedrec"];
				 

            }

           echo "</ul>";
			
if (isset($reorder) && $reorder==1 && ($t==$t) ) {
?>			
<script type="text/javascript" language="javascript">
// <![CDATA[
	new Effect.BlindDown('thumblist_<?php print $t ?>', {duration: 1});
	Sortable.create('thumblist_<?php print $t ?>',
		{
			onUpdate : function() {
				inputs = $('thumblist_<?php print $t ?>').getElementsByTagName("input");
				for (var i = 0; i < inputs.length; i++) inputs[i].value = i;
			}
		}
	);
// ]]>
</script>	

	
<?php
		

}else{
}				
            echo "</div>";
            echo "<div id=clearlist>";
            echo "</div>";
            echo "</center>";
            echo '</td></tr></table>' . "\n";
			
		

            if ($t==3 && $numm > 0) {
                 echo "<font size='1'>";
                 echo $pgv_lang["census_text"];
                 echo "</font>";
            }else{
            }

            echo "</center>" . "\n";
             echo '</td>'. "\n";
            echo '</td></tr></table>' . "\n\n";

// end if $t== etc -------------------------------------------------------------
      }
// -----------------------------------------------------------------------------
      //-- objects are removed from the $current_objes list as they are printed
      //-- any objects left in the list are new objects recently added to the gedcom
      //-- but not yet accepted into the database.  We will print them too.
      foreach($current_objes as $media_id=>$value) {

           while($value>0) {
                        $objSubrec = array_pop($obje_links[$media_id]);


                //-- check if we need to get the object from a remote location
                $ct = ( preg_match("/(.*):(.*)/", $media_id, $match) );

                if ($ct>0) {
                     $client = ServiceClient::getInstance($match[1]);
                     if (!is_null($client)) {
                          $newrec = $client->getRemoteRecord($match[2]);
                          $row['m_media'] = $media_id;
                          $row['m_file'] = get_gedcom_value("FILE", 1, $newrec);
                          $row['m_titl'] = get_gedcom_value("TITL", 1, $newrec);
                          if (empty($row['m_titl'])) $row['m_titl'] = get_gedcom_value("FILE:TITL", 1, $newrec);
                               $row['m_gedrec'] = $newrec;
                               $et = preg_match("/(\.\w+)$/", $row['m_file'], $ematch);
                               $ext = "";
                          if ($et>0) $ext = substr(trim($ematch[1]),1);
                               $row['m_ext'] = $ext;
                               $row['mm_gid'] = $pid;
                               $row['mm_gedrec'] = get_sub_record($objSubrec{0}, $objSubrec, $gedrec);

// BH added "if" qualifiers for time $t ----------------------------------------
                          if ($t==1 && $ct>0 ) {
                               $typ2a  = ( (eregi("TYPE photo",$row['m_gedrec']) || eregi("TYPE map",$row['m_gedrec']) || eregi("TYPE tombstone",$row['m_gedrec'])) && !eregi(".pdf",$row['m_file']) );
                          }
                          if ($t==2 && $ct>0 ) {
                               $typ2a  = ( (eregi("TYPE card",$row['m_gedrec']) || eregi("TYPE certificate",$row['m_gedrec']) || eregi("TYPE document",$row['m_gedrec']) || eregi("TYPE magazine",$row['m_gedrec']) || eregi("TYPE manuscript",$row['m_gedrec']) || eregi("TYPE newspaper",$row['m_gedrec'])) ) ;
                          }
                          if ($t==3 && $ct>0 ) {
                               $typ2a  = ( (eregi("TYPE electronic",$row['m_gedrec']) || eregi("TYPE film",$row['m_gedrec']) || eregi("TYPE fiche",$row['m_gedrec'])) );
                          }
                          if ($t==4 && $ct>0 ) {
                               $typ2a  = ( !eregi("TYPE",$row['m_gedrec']) || eregi("TYPE book",$row['m_gedrec']) || eregi("TYPE audio",$row['m_gedrec']) || eregi("TYPE video",$row['m_gedrec']) );
                          }

                          if ( $typ2a ) {
                                echo '<table border=0 class="facts_table"><tr>';
                                echo '<td width="80" align="center" class="descriptionbox">' ;
                                echo "<b><br><br>" . $tt . "</b><br><br>(" . $ct . ")";
                                echo '</td>' . "\n";
                                echo '<td class="facts_value">';
                                echo "<center>" . "\n\n";
                                echo '<table><tr><td>' . "\n";
                                echo "<center>" . "\n\n";  // needed for Firefox
                                echo "<div id=\"thumbcontainer\">" . "\n";
                                echo "<ul id=\"thumblist\">" . "\n\n";

                                          $res = lightbox_print_media_row('normal', $row, $pid);
                                          $media_found = $media_found || $res;

                                echo "</ul>";
                                echo "</div>";
                                echo "<div id=clearlist>";
                                echo "</div";
                                echo "</center>";
                                echo '</td></tr></table>';
                                echo "</center>";
                                echo '</td>';
                                echo '</td></tr></table>' . "\n\n";
                          }

                     }
                }
                $value--;
           }
      }

// -----------------------------------------------------------------------------

       if ($media_found) return $is_media="YES" ;
       else return $is_media="NO" ;

// -----------------------------------------------------------------------------
// }
// -----------------------------------------------------------------------------

?>