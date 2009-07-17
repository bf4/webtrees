<?php
/**
* Individual Page
*
* Display all of the information about an individual
*
* phpGedView: Genealogy Viewer
* Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
* @subpackage Charts
* @version $Id: individual.php 5625 2009-05-31 16:20:32Z volschin $
*/

require './config.php';
require './includes/controllers/new_individual_ctrl.php';

// We have finished writing to $_SESSION, so release the lock
session_write_close();
$showFull = ($PEDIGREE_FULL_DETAILS) ? 1 : 0;

$controller=new IndividualController();
$controller->init();

print_header($controller->getPageTitle());

if (!$controller->indi->canDisplayName()) {
	print_privacy_error($CONTACT_EMAIL);
	print_footer();
	exit;
}
$linkToID=$controller->pid; // -- Tell addmedia.php what to link to

?>

<script language="JavaScript" type="text/javascript">
// <![CDATA[
// javascript function to open a window with the raw gedcom in it
function show_gedcom_record(shownew) {
	fromfile="";
	if (shownew=="yes") fromfile='&fromfile=1';
	var recwin = window.open("gedrecord.php?pid=<?php echo $controller->pid; ?>"+fromfile, "_blank", "top=50,left=50,width=600,height=400,scrollbars=1,scrollable=1,resizable=1");
}
<?php if (PGV_USER_CAN_EDIT) { ?>
function open_link_remote(pid){
	window.open("addremotelink.php?pid="+pid, "_blank", "top=50,left=50,width=600,height=500,scrollbars=1,scrollable=1,resizable=1");
	return false;
}

function showchanges() {
	window.location = '<?php echo $controller->indi->getLinkUrl(); ?>&show_changes=yes';
}
<?php } ?>

function setfamnav() {
	<?php if (isset($_COOKIE['famnav']) && $_COOKIE['famnav']=="YES" ) { ?>
		document.cookie = "famnav=NO";
	<?php }else{ ?>
		document.cookie = "famnav=YES";
	<?php } ?>
	forceReloadFromServer = true;
	window.location.reload(forceReloadFromServer);
}

// function is required by cloudy theme
function resize_content_div(i) {
	// check for container ..
	var cont = document.getElementById("content");
	if (!cont) cont = document.getElementById("container");
	if (cont) {
		if (document.getElementById("marker"+i)) {
			var y = getAbsoluteTop("marker"+i);
			if (y<300) y=600;
			cont.style.height =y.toString()+'px';
		}
	}
}
//]]>
</script>
<link type="text/css" href="<?php echo PGV_THEME_DIR?>jquery/jquery-ui-1.7.1.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui-1.7.1.custom.min.js"></script>
<script type="text/javascript">
//<![CDATA[
  jQuery.noConflict();
  jQuery(document).ready(function(){
    jQuery("#tabs").tabs({ cache: true });
    jQuery("#navigation").accordion({
			collapsible: true,
			active: "h3:last",
			autoHeight: false
		});
  });
//]]>
  </script>
<style>
#indi_main_blocks {
	clear: none;
	float: left;
	width: 75%;
}
#indi_small_blocks {
	clear: none;
	float: right;
	width: 24%;
}
</style>
<div id="indi_main_blocks">
		<?php if ((empty($SEARCH_SPIDER))&&($controller->accept_success)) echo "<b>".$pgv_lang["accept_successful"]."</b><br />"; ?>
		<?php if ($controller->indi->isMarkedDeleted()) echo "<span class=\"error\">".$pgv_lang["record_marked_deleted"]."</span>"; ?>
		<span class="name_head">
		<?php
		if ($TEXT_DIRECTION=="rtl") echo "&nbsp;";
			echo PrintReady($controller->indi->getFullName());
			echo "&nbsp;&nbsp;";
			echo PrintReady("(".$controller->pid.")");
			if (PGV_USER_IS_ADMIN) {
				$pgvuser=get_user_from_gedcom_xref($GEDCOM, $controller->pid);
				if ($pgvuser) {
					echo "&nbsp;";
					echo printReady("(<a href=\"useradmin.php?action=edituser&amp;username={$pgvuser}\">{$pgvuser}</a>)");
				}
			}
		?>
		</span><br />
		<?php if (strlen($controller->indi->getAddName()) > 0) echo "<span class=\"name_head\">".PrintReady($controller->indi->getAddName())."</span><br />"; ?>
		<?php if ($controller->indi->canDisplayDetails()) { ?>
		<table><tr>
		<?php
			$col=0; $maxcols=7; // 4 with data and 3 spacers
			$globalfacts=$controller->getGlobalFacts();
			$nameSex = array('NAME','SEX');
			foreach ($globalfacts as $key=>$value) {
				$fact = $value->getTag();
				if (in_array($fact, $nameSex)) {
						if ($col>0) {
							echo "<td width=\"10\"><br /></td>";
							++$col;
						}
					if ($fact=="SEX") $controller->print_sex_record($value);
					if ($fact=="NAME") $controller->print_name_record($value);
						++$col;
						$FACT_COUNT++;
						if ($col==$maxcols) {
							echo "</tr><tr>";
							$col=0;
						}
					}
			}
			// Display summary birth/death info.  Note this info can come from various BIRT/CHR/BAPM/etc. records
			$summary=$controller->indi->format_first_major_fact(PGV_EVENTS_BIRT, 2);
			if (!($controller->indi->isDead())) {
				// If alive display age
				$bdate=$controller->indi->getBirthDate();
				$age = GedcomDate::GetAgeGedcom($bdate);
				if ($age!="")
					$summary.= "<span class=\"label\">".$pgv_lang["age"].":</span><span class=\"field\"> ".get_age_at_event($age, true)."</span>";
			}
			$summary.=$controller->indi->format_first_major_fact(PGV_EVENTS_DEAT, 2);
			if ($SHOW_LDS_AT_GLANCE) {
				$summary.='<b>'.get_lds_glance($controller->indi->getGedcomRecord()).'</b>';
			}
			if ($summary) {
				++$col;
				echo '<td width="10"><br /></td><td valign="top" colspan="', $maxcols-$col, '">', $summary, '</td>';
			}
		?>
		</tr>
		</table>
		<?php
		if($SHOW_COUNTER && (empty($SEARCH_SPIDER))) {
			//print indi counter only if displaying a non-private person
			require './includes/hitcount.php';
			echo "<br />{$pgv_lang["hit_count"]} {$hitCount}";
		}
		// if individual is a remote individual
		// if information for this information is based on a remote site
		if ($controller->indi->isRemote())
		{
			?><br />
			<?php echo $pgv_lang["indi_is_remote"]; ?><!--<br />--><!--take this out if you want break the remote site and the fact that it was remote into two separate lines-->
			<a href="<?php echo encode_url($controller->indi->getLinkUrl()); ?>"><?php echo $controller->indi->getLinkTitle(); ?></a>
			<?php
		}
		// if indivual is not a remote individual
		// if information for this individual is based on this local site
		// this is not need to be printed, but may be uncommented if desired
		/*else
			echo("This is a local individual.");*/
	}
	
		
	?>
	
<?php
foreach($controller->modules as $mod) {
	if ($mod->hasTab() && $mod->getTab()) {
		echo $mod->getTab()->getPreLoadContent();
	}
} 
?>
<div id="tabs">
<ul>
	<?php
	$tabcount = 0; 
	foreach($controller->modules as $mod) {
		if ($mod!=$controller->static_tab && $mod->hasTab()) {
		if ($tabcount==$controller->default_tab) {?>
			<li><a href="#<?php echo $mod->getName()?>"><span><?php echo $pgv_lang[$mod->getName()]?></span></a></li>
		<?php } else if ($mod->hasTab() && $mod->getTab() && ($mod->getTab()->hasContent() || PGV_USER_CAN_EDIT)) { ?>
			<li><a href="new_individual.php?action=ajax&amp;module=<?php echo $mod->getName()?>&amp;pid=<?php echo $controller->pid?>">
				<span><?php echo $pgv_lang[$mod->getName()]?></span>
				</a></li>
		<?php } 
		$tabcount++; 
		}
	 } ?>
</ul>
<?php 
$tabcount = 0; 
foreach($controller->modules as $mod) {
	if ($mod!=$controller->static_tab && $mod->hasTab()) {
	if ($tabcount==$controller->default_tab) {?>
	<div id="<?php echo $mod->getName()?>">
		<?php echo $mod->getTab()->getContent(); ?>
	</div>	
	<?php }
	$tabcount++; 
	}
 } ?>
</div>


</div> <!--  end column 1 -->

<div id="indi_small_blocks">
		<div class="accesskeys">
			<a class="accesskeys" href="<?php echo "pedigree.php?rootid=$pid&amp;show_full=$showFull";?>" title="<?php echo $pgv_lang["pedigree_chart"] ?>" tabindex="-1" accesskey="<?php echo $pgv_lang["accesskey_individual_pedigree"]; ?>"><?php echo $pgv_lang["pedigree_chart"] ?></a>
			<a class="accesskeys" href="<?php echo "descendancy.php?pid=$pid&amp;show_full=$showFull";?>" title="<?php echo $pgv_lang["descend_chart"] ?>" tabindex="-1" accesskey="<?php echo $pgv_lang["accesskey_individual_descendancy"]; ?>"><?php echo $pgv_lang["descend_chart"] ?></a>
			<a class="accesskeys" href="<?php echo "timeline.php?pids[]=$pid";?>" title="<?php echo $pgv_lang["timeline_chart"] ?>" tabindex="-1" accesskey="<?php echo $pgv_lang["accesskey_individual_timeline"]; ?>"><?php echo $pgv_lang["timeline_chart"] ?></a>
			<?php
				if (PGV_USER_GEDCOM_ID) {
			?>
			<a class="accesskeys" href="<?php echo "relationship.php?show_full=$showFull&amp;pid1=".PGV_USER_GEDCOM_ID."&amp;pid2=".$controller->pid;?>" title="<?php echo $pgv_lang["relationship_to_me"] ?>" tabindex="-1" accesskey="<?php echo $pgv_lang["accesskey_individual_relation_to_me"]; ?>"><?php echo $pgv_lang["relationship_to_me"] ?></a>
			<?php }
			if ($controller->canShowGedcomRecord()) {
			?>
			<a class="accesskeys" href="javascript:show_gedcom_record();" title="<?php echo $pgv_lang["view_gedcom"] ?>" tabindex="-1" accesskey="<?php echo $pgv_lang["accesskey_individual_gedcom"]; ?>"><?php echo $pgv_lang["view_gedcom"] ?></a>
			<?php
			}
		?>
		</div>
		<table align="center">
		<?php if ($controller->canShowHighlightedObject()) { ?>
		<tr><td class="center" align="center">
			<?php echo $controller->getHighlightedObject(); ?>
		</td></tr>
		<?php } ?>
		<tr><td>
		<div id="navigation" class="<?php echo $TEXT_DIRECTION; ?> noprint">
			<?php 
			//-- get charts menu from menubar
			$menubar = new MenuBar();
			$menu = $menubar->getChartsMenu($controller->pid); 
			?>
		<h3><a href="#"><?php echo $menu->label; ?></a></h3>
		<div>
			<ul class="<?php echo $TEXT_DIRECTION; ?>">
				<?php
				echo $menu->getMenuAsList();
				?>
			</ul>
		</div>
		<?php 
			list($surname)=explode(',', $controller->indi->getSortName());
			if (!$surname) {
				$surname='@N.N.'; // TODO empty surname is not the same as @N.N.
			}
			$menu = $menubar->getListsMenu($surname); 
		?>
		<h3><a href="#"><?php echo $menu->label; ?></a></h3>
		<div>
			<ul class="<?php echo $TEXT_DIRECTION; ?>">
				<?php 
					echo $menu->getMenuAsList();
				?>
			</ul>
		</div>
			<?php 
			if (file_exists("reports/individual.xml")) {
					//-- get reports menu from menubar
					$menu = $menubar->getReportsMenu($controller->pid); 
				?>
				<h3><a href="#"><?php echo $menu->label; ?></a></h3>
				<div>
				<ul class="<?php echo $TEXT_DIRECTION; ?>">
					<?php
					echo $menu->getMenuAsList();
				?>
				</ul>
				</div>
			<?php }
			if ($controller->userCanEdit()) {
				$menu = $controller->getEditMenu(); 
				?>
				<h3><a href="#"><?php echo $menu->label; ?></a></h3>
				<div>
				<ul class="<?php echo $TEXT_DIRECTION; ?>">
				<?php
	 				echo $menu->getMenuAsList();
				?>
				</ul>
				</div>
				<?php }
				if ($controller->canShowOtherMenu()) {
					$menu = $controller->getOtherMenu(); 
				?>
				<h3><a href="#"><?php echo $menu->label; ?></a></h3>
				<div>
				<ul class="<?php echo $TEXT_DIRECTION; ?>">
				<?php 
				echo $menu->getMenuAsList();
				?>
				</ul>
				</div>
				<?php }
				?>
<?php
	// ==================== Start Details Tab Navigator ========================================
	//if ($Fam_Navigator=="YES") {
		//<b> print $pgv_lang["view_fam_nav_notes"]; </b><br /><br />
		//<b><?php print $pgv_lang["view_fam_nav_sources"]; </b><br /><br />
		//<b><?php print $pgv_lang["view_fam_nav_media"]; </b><br /><br />
		//<b><?php print $pgv_lang["view_fam_nav_relatives"]; </b><br /><br />
		//<b><?php print $pgv_lang["view_fam_nav_map"]; </b><br /><br />
		//<b><?php print $pgv_lang["view_fam_nav_album"]; </b><br /><br />
		?>
		<h3><a href="#"><?php print $pgv_lang["view_fam_nav_details"]; ?></a></h3>
		<div>
			<table class="optionbox" cellpadding="0"><tr><td align="center">
			<b><?php print $pgv_lang["view_fam_nav_details"]; ?></b><br /><br />
			<?php if ($controller->static_tab) echo $controller->static_tab->getTab()->getContent(); ?>
			<br />
			</td></tr></table>
		</div>
		<?php
	//}
	// ==================== End Details Tab Navigator ========================================= */
?>
	</div>
	</td></tr></table>
</div> <!--  end column 2 -->

<?php 
echo PGV_JS_START;
echo 'var catch_and_ignore; function paste_id(value) {catch_and_ignore = value;}';
echo 'if (typeof toggleByClassName == "undefined") {';
echo 'alert("phpgedview.js: A javascript function is missing.  Please clear your Web browser cache");';
echo '}';
echo PGV_JS_END;

if ($SEARCH_SPIDER) {
	if($SHOW_SPIDER_TAGLINE)
		echo $pgv_lang["label_search_engine_detected"].": ".$SEARCH_SPIDER;
	echo "</div></body></html>";
} else {
	print_footer();
}
?>
