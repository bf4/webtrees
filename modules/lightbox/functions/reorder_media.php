<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Reorder media Items using drag and drop
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
 * @version $Id: reorder_media.php 1358 2007-07-31 22:28:49Z windmillway $
 * @author Brian Holland
 */
	require_once("js/prototype.js.htm");
	require_once("js/scriptaculous.js.htm");
	include_once("includes/functions_print_facts.php");
	print "<br /><b>Reorder Media</b>";
	print_help_link("reorder_children_help", "qm");
	
	global $MULTI_MEDIA, $TBLPREFIX, $SHOW_ID_NUMBERS, $MEDIA_EXTERNAL;
	global $pgv_lang, $pgv_changes, $factarray, $view;
	global $GEDCOMS, $GEDCOM, $MEDIATYPE, $pgv_changes, $DBCONN, $DBTYPE;
	global $WORD_WRAPPED_NOTES, $MEDIA_DIRECTORY, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION;
	global $is_media, $cntm1, $cntm2, $cntm3, $cntm4, $t, $mgedrec;
	global $typ2b, $edit, $tabno ;
	
	global $ids, $pid, $related, $level, $gedrec;

	?>
	<form name="reorder_form" method="post" action="edit_interface.php">
		<input type="hidden" name="action" value="reorder_update" />
		<input type="hidden" name="pid" value="<?php print $pid; ?>" />  
<!--		<input type="hidden" name="option" value="bybirth" /> -->
		<ul id="reorder_media">
		<?php

      if (!showFact("OBJE", $pid)) return false;
      if (!isset($pgv_changes[$pid."_".$GEDCOM])) $gedrec = find_gedcom_record($pid);
      else $gedrec = find_updated_record($pid);
	  
	  //related=true means show related items
	  $related="true";
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
	  
      $sqlmm .= " ORDER BY m_titl ";
//      $sqlmm .= " ORDER BY mm_gid DESC ";	  
      $resmm = dbquery($sqlmm);
      $foundObjs = array();

//      $resmm1 = mysql_query($sqlmm);
//      $numm = mysql_num_rows($resmm1);

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

                         $res = lightbox_print_media_row_sort($rtype, $rowm, $pid);
                         $media_found = $media_found || $res;
                         $foundObjs[$rowm['m_media']]=true;
                 }

                 $mgedrec[] = $rowm["m_gedrec"];

            }
		?>
		</ul>
<script type="text/javascript" language="javascript">
// <![CDATA[
	new Effect.BlindDown('reorder_media', {duration: 1});
	Sortable.create('reorder_media',
		{
			onUpdate : function() {
				inputs = $('reorder_media').getElementsByTagName("input");
				for (var i = 0; i < inputs.length; i++) inputs[i].value = i;
			}
		}
	);
// ]]>
</script>
		<button type="submit"><?php print $pgv_lang["save"];?></button>
<!--		<button type="submit" onclick="document.reorder_form.action.value='reorder_children'; document.reorder_form.submit();"><?php print $pgv_lang["sort_by_birth"];?></button> -->
		<button type="submit" onclick="window.close();"><?php print $pgv_lang["cancel"];?></button>
	</form>
	<?php
