<?php
/**
 * Individual Page
 *
 * Display all of the information about an individual
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  PGV Development Team
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
 * @version $Id$
 */

require_once("includes/controllers/individual_ctrl.php");
require_once("includes/serviceclient_class.php");

if (file_exists("modules/googlemap/".$pgv_language["english"])) require("modules/googlemap/".$pgv_language["english"]);
if (file_exists("modules/googlemap/".$pgv_language[$LANGUAGE])) require("modules/googlemap/".$pgv_language[$LANGUAGE]);

global $USE_THUMBS_MAIN;
global $linkToID;
global $SEARCH_SPIDER;

print_header($controller->getPageTitle());

if (!$controller->indi->canDisplayName()) {
   print_privacy_error($CONTACT_EMAIL);
   print_footer();
   exit;
}
$linkToID = $controller->pid;	// -- Tell addmedia.php what to link to
?>
<table border="0" cellspacing="0" cellpadding="0" class="facts_table">
	<tr>
	<?php if ($controller->canShowHighlightedObject()) { ?>
		<td <?php if (!$USE_THUMBS_MAIN) print "rowspan=\"2\" ";?> valign="top">
			<?php print $controller->getHighlightedObject(); ?>
		</td>
	<?php } ?>
	<td valign="top">
		<?php if ((empty($SEARCH_SPIDER))&&($controller->accept_success)) print "<b>".$pgv_lang["accept_successful"]."</b><br />"; ?>
		<span class="name_head">
		<?php
			print PrintReady($controller->indi->getName());
			print "&nbsp;&nbsp;";
			if ($TEXT_DIRECTION=="rtl") print "&rlm;";
			print "(".$controller->pid.")";
			if ($TEXT_DIRECTION=="rtl") print "&rlm;";
		?>
		</span><br />
		<?php if (strlen($controller->indi->getAddName()) > 0) print "<span class=\"name_head\">".PrintReady($controller->indi->getAddName())."</span><br />"; ?>
		<?php if ($controller->indi->canDisplayDetails()) { ?>
		<table><tr>
		<?php
			$i=0;
			$maxi=0;
			$globalfacts = $controller->getGlobalFacts();
			foreach ($globalfacts as $key => $value) {
				$ft = preg_match("/\d\s(\w+)(.*)/", $value[1], $match);
				if ($ft>0) $fact = $match[1];
				else $fact="";
				$fact = trim($fact);
				if ($fact=="SEX") $controller->print_sex_record($value[1], $value[0]);
				if ($fact=="NAME") $controller->print_name_record($value[1], $value[0]);
				$FACT_COUNT++;
				print "<td width=\"10\"><br /></td>\n";
				$i++;
				$maxi++;
				if ($i>3) {
					print "</tr><tr>";
					$i=0;
				}
			}
			//-- - put the birth info in this section
			$birthrec = $controller->indi->getBirthRecord(false);
			$deathrec = $controller->indi->getDeathRecord(false);
			if ((!empty($birthrec)) || (!empty($deathrec)) || $SHOW_LDS_AT_GLANCE) {
				$colspan = 0;
				if ($i<$maxi) $colspan = $maxi-$i;
			?>
			<td valign="top" colspan="<?php print $colspan; ?>">
			<?php if (!empty($birthrec)) { ?>
				<span class="label"><?php print $factarray["BIRT"].":"; ?></span>
				<span class="field">
					<?php print_fact_date($birthrec); ?>
					<?php print_fact_place($birthrec); ?>
				</span><br />
			<?php } ?>
			<?php
				// RFE [ 1229233 ] "DEAT" vs "DEAT Y"
				// The check $deathrec != "1 DEAT" will not show any records that only have 1 DEAT in them
				if ((!empty($deathrec)) && (trim($deathrec) != "1 DEAT")) {
			?>
				<span class="label"><?php print $factarray["DEAT"].":"; ?></span>
				<span class="field">
				<?php
					print_fact_date($deathrec);
					print_fact_place($deathrec);
				?>
				</span><br />
			<?php }
				if ($SHOW_LDS_AT_GLANCE) print "<b>".get_lds_glance($controller->indi->getGedcomRecord())."</b>";
			?>
			</td>
			<?php } ?>
		</tr>
		</table>
		<?php
		if($SHOW_COUNTER && (empty($SEARCH_SPIDER))) {
			//print indi counter only if displaying a non-private person
			require("hitcount.php");
			print "\n<br />".$pgv_lang["hit_count"]."	".$hits."\n";
		}
		// if individual is a remote individual
		// if information for this information is based on a remote site
		if ($controller->indi->isRemote())
		{
			?><br />
			<?php print $pgv_lang["indi_is_remote"]; ?><!--<br />--><!--take this out if you want break the remote site and the fact that it was remote into two separate lines-->
			<a href="<?php print $controller->indi->getLinkUrl(); ?>"><?php print $controller->indi->getLinkUrl(); ?></a>
			<?php
		}
		// if indivual is not a remote individual
		// if information for this individual is based on this local site
		// this is not need to be printed, but may be uncommented if desired
		/*else
			echo("This is a local individual.");*/
	}
	if ((!$controller->isPrintPreview())&&(empty($SEARCH_SPIDER))) {
	?>
	</td><td class="<?php echo $TEXT_DIRECTION; ?> noprint" valign="top">
		<div class="accesskeys">
			<a class="accesskeys" href="<?php print "pedigree.php?rootid=$pid";?>" title="<?php print $pgv_lang["pedigree_chart"] ?>" tabindex="-1" accesskey="<?php print $pgv_lang["accesskey_individual_pedigree"]; ?>"><?php print $pgv_lang["pedigree_chart"] ?></a>
			<a class="accesskeys" href="<?php print "descendancy.php?pid=$pid";?>" title="<?php print $pgv_lang["descend_chart"] ?>" tabindex="-1" accesskey="<?php print $pgv_lang["accesskey_individual_descendancy"]; ?>"><?php print $pgv_lang["descend_chart"] ?></a>
			<a class="accesskeys" href="<?php print "timeline.php?pids[]=$pid";?>" title="<?php print $pgv_lang["timeline_chart"] ?>" tabindex="-1" accesskey="<?php print $pgv_lang["accesskey_individual_timeline"]; ?>"><?php print $pgv_lang["timeline_chart"] ?></a>
			<?php
				if (!empty($controller->user["gedcomid"][$GEDCOM])) {
			?>
			<a class="accesskeys" href="<?php print "relationship.php?pid1=".$controller->user["gedcomid"][$GEDCOM]."&amp;pid2=".$controller->pid;?>" title="<?php print $pgv_lang["relationship_to_me"] ?>" tabindex="-1" accesskey="<?php print $pgv_lang["accesskey_individual_relation_to_me"]; ?>"><?php print $pgv_lang["relationship_to_me"] ?></a>
			<?php 	}
			if ($controller->canShowGedcomRecord()) {
			?>
			<a class="accesskeys" href="javascript:show_gedcom_record();" title="<?php print $pgv_lang["view_gedcom"] ?>" tabindex="-1" accesskey="<?php print $pgv_lang["accesskey_individual_gedcom"]; ?>"><?php print $pgv_lang["view_gedcom"] ?></a>
			<?php
			}
		?>
		</div>
		<table class="sublinks_table" cellspacing="4" cellpadding="0">
			<tr>
				<td class="list_label <?php echo $TEXT_DIRECTION; ?>" colspan="5"><?php echo $pgv_lang["indis_charts"]; ?></td>
			</tr>
			<tr>
				<td class="sublinks_cell <?php echo $TEXT_DIRECTION; ?>">
				<?php
				//-- get charts menu from menubar
				$menubar = new MenuBar();
				$menu = $menubar->getChartsMenu($controller->pid); $menu->printMenu();
				?>
				</td>
				<td class="sublinks_cell <?php echo $TEXT_DIRECTION; ?>">
				<?php
				$menu = $menubar->getListsMenu($controller->indi->getSurname()); $menu->printMenu();
				if (file_exists("reports/individual.xml")) {?>
					</td><td class="sublinks_cell <?php echo $TEXT_DIRECTION; ?>">
					<?php
					//-- get reports menu from menubar
					$menu = $menubar->getReportsMenu($controller->pid); $menu->printMenu();
				}
				if ($controller->userCanEdit()) {
				?>
				</td>
				<td class="sublinks_cell <?php echo $TEXT_DIRECTION;?>">
				<?php $menu = $controller->getEditMenu(); $menu->printMenu();
				}
				if ($controller->canShowOtherMenu()) {
				?>
				</td>
				<td class="sublinks_cell <?php echo $TEXT_DIRECTION; ?>">
				<?php $menu = $controller->getOtherMenu(); $menu->printMenu();
				}
				?>
				</td>
			</tr>
		</table><br />
	<?php } ?>
	</td>
	<td width="10"><br /></td>
	</tr>
	<tr>
	<td valign="bottom" colspan="5">
	<?php if ($controller->indi->isMarkedDeleted()) print "<span class=\"error\">".$pgv_lang["record_marked_deleted"]."</span>"; ?>
<script language="JavaScript" type="text/javascript">
<!--
function open_link_remote(pid){
	window.open("addremotelink.php?pid="+pid, "_blank", "top=50,left=50,width=600,height=500,scrollbars=1,scrollable=1,resizable=1");
	return false;
}

// javascript function to open a window with the raw gedcom in it
function show_gedcom_record(shownew) {
	fromfile="";
	if (shownew=="yes") fromfile='&fromfile=1';
	var recwin = window.open("gedrecord.php?pid=<?php print $controller->pid; ?>"+fromfile, "_blank", "top=50,left=50,width=600,height=400,scrollbars=1,scrollable=1,resizable=1");
}

function showchanges() {
	window.location = 'individual.php?pid=<?php print $controller->pid; ?>&show_changes=yes';
}
// The function below does not go well with validation.
// The option to use getElementsByName is used in connection with code from
// the functions_print.php file.
function togglerow(label) {
	ebn = document.getElementsByName(label);
	if (ebn.length) disp = ebn[0].style.display;
	else disp="";
	if (disp=="none") {
		disp="table-row";
		if (document.all && !window.opera) disp = "inline"; // IE
		document.getElementById('rela_plus').style.display="none";
		document.getElementById('rela_minus').style.display="inline";
	}
	else {
		disp="none";
		document.getElementById('rela_plus').style.display="inline";
		document.getElementById('rela_minus').style.display="none";
	}
	for (i=0; i<ebn.length; i++) ebn[i].style.display=disp;
}


var tabid = new Array('0', 'facts','notes','sources','media','relatives','researchlog');
<?php if (file_exists("modules/googlemap/defaultconfig.php")) {?>
var tabid = new Array('0', 'facts','notes','sources','media','relatives','researchlog','googlemap');
<?php }?>
var loadedTabs = new Array(false,false,false,false,false,false,false,false);
<?php print "loadedTabs[".($controller->default_tab+1)."] = true;"; ?>

function tempObj(tab, oXmlHttp) {
	this.processFunc = function()
	{
 			if (oXmlHttp.readyState==4)
 			{
 				target = document.getElementById(tabid[tab]+'_content');
  				evalAjaxJavascript(oXmlHttp.responseText, target);
  				target.style.height = 'auto';
  				loadedTabs[tab] = true;
  				//-- call resizemap for google map module
  				if (tab==6) {
  					ResizeMap();
  					ResizeMap();
  				}
  			}
 		};
}

function tabswitch(n) {
	if (!tabid[n]) n = 1;
	// show all tabs ?
	var disp='none';
	if (n==0) disp='block';
	// reset all tabs areas
	for (i=1; i<tabid.length; i++) {
		document.getElementById(tabid[i]).style.display=disp;
		if (disp=='block' && !loadedTabs[i]) {
			target = document.getElementById(tabid[i]+'_content');
			if (target) {
				var oXmlHttp = createXMLHttp();
				link = "individual.php?action=ajax&pid=<?php print $controller->pid; ?>&tab="+i;
				oXmlHttp.open("get", link, true);
				temp = new tempObj(i, oXmlHttp);
				oXmlHttp.onreadystatechange=temp.processFunc;
		  		oXmlHttp.send(null);
	  		}
  		}
	}
	// current tab area
	if (n>0) {
		document.getElementById(tabid[n]).style.display='block';
		//-- load ajax
		if (!loadedTabs[n]) {
			target = document.getElementById(tabid[n]+'_content');
			if (target) {
				var oXmlHttp = createXMLHttp();
				link = "individual.php?action=ajax&pid=<?php print $controller->pid; ?>&tab="+n;
				oXmlHttp.open("get", link, true);
				temp = new tempObj(n, oXmlHttp);
				oXmlHttp.onreadystatechange=temp.processFunc;
		  		oXmlHttp.send(null);
	  		}
  		}
	}
	// empty tabs
	for (i=0; i<tabid.length; i++) {
		var elt = document.getElementById('door'+i);
		if (document.getElementById('no_tab'+i)) { // empty ?
			if (<?php if (userCanEdit(getUserName())) echo 'true'; else echo 'false';?>) {
				elt.style.display='block';
				elt.style.opacity='0.4';
				elt.style.filter='alpha(opacity=40)';
			}
			else elt.style.display='none'; // empty and not editable ==> hide
			//if (i==3 && <?php if ($SHOW_SOURCES>=getUserAccessLevel(getUserName())) echo 'true'; else echo 'false';?>) elt.style.display='none'; // no sources
			if (i==4 && <?php if (!$MULTI_MEDIA) echo 'true'; else echo 'false';?>) elt.style.display='none'; // no multimedia
			if (i==6) elt.style.display='none'; // hide researchlog
			// ALL : hide empty contents
			if (n==0) document.getElementById(tabid[i]).style.display='none';
		}
		else elt.style.display='block';
	}
	// current door
	for (i=0; i<tabid.length; i++) {
		document.getElementById('door'+i).className='door optionbox rela';
		//document.getElementById('door'+i).className='tab_cell_inactive';
	}
	document.getElementById('door'+n).className='door optionbox';
	//document.getElementById('door'+n).className='tab_cell_active';
	// set a cookie which stores the last tab they clicked on
	document.cookie = "lasttabs=<?php print $controller->getCookieTabString().$controller->pid; ?>="+n;
	return false;
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
//-->
</script>
<script src="phpgedview.js" language="JavaScript" type="text/javascript"></script>
<?php
if ((!$controller->isPrintPreview())&&(empty($SEARCH_SPIDER))) {
?>
<div class="door">
<dl>
<dd id="door1"><a href="javascript:;" onclick="tabswitch(1); return false;" ><?php print $pgv_lang["personal_facts"]?></a></dd>
<dd id="door2"><a href="javascript:;" onclick="tabswitch(2); return false;" ><?php print $pgv_lang["notes"]?></a></dd>
<dd id="door3"><a href="javascript:;" onclick="tabswitch(3); return false;" ><?php print $pgv_lang["ssourcess"]?></a></dd>
<dd id="door4"><a href="javascript:;" onclick="tabswitch(4); return false;" ><?php print $pgv_lang["media"]?></a></dd>
<dd id="door5"><a href="javascript:;" onclick="tabswitch(5); return false;" ><?php print $pgv_lang["relatives"]?></a></dd>
<dd id="door6"><a href="javascript:;" onclick="tabswitch(6); return false;" ><?php print $pgv_lang["research_assistant"]?></a></dd>
<?php if (file_exists("modules/googlemap/defaultconfig.php")) {?>
<dd id="door7"><a href="javascript:;" onclick="tabswitch(7); return false;" ><?php print $pgv_lang["googlemap"]?></a></dd>
<?php }?>
<dd id="door0"><a href="javascript:;" onclick="tabswitch(0); return false;" ><?php print $pgv_lang["all"]?></a></dd>
</dl>
</div>
<br />
<?php
}
?>
	</td>
	</tr>
</table>

<!-- ======================== Start 1st tab individual page ============ Personal Facts and Details -->
<?php
if(empty($SEARCH_SPIDER)) 
	print "<div id=\"facts\" class=\"tab_page\" style=\"display:none;\" >\n";
else
	print "<div id=\"facts\" class=\"tab_page\" style=\"display:block;\" >\n";
print "<span class=\"subheaders\">".$pgv_lang["personal_facts"]."</span><div id=\"facts_content\">";
if (($controller->default_tab==0)||(!empty($SEARCH_SPIDER))) $controller->getTab(0);
else print $pgv_lang['loading'];
?>
</div>
</div>
<script language="JavaScript" type="text/javascript">
<!--
	// hide button if list is empty
	ebn = document.getElementsByName('row_rela');
	if (ebn.length==0) document.getElementById('row_top').style.display="none";
	<?php if (!$EXPAND_RELATIVES_EVENTS) print "togglerow('row_rela');\n"; ?>
//-->
</script>
<!-- ======================== Start 2nd tab individual page ==== Notes ======= -->
<?php
if(empty($SEARCH_SPIDER)) 
	print "<div id=\"notes\" class=\"tab_page\" style=\"display:none;\" >\n";
else
	print "<div id=\"notes\" class=\"tab_page\" style=\"display:block;\" >\n";
print "<span class=\"subheaders\">".$pgv_lang["notes"]."</span><div id=\"notes_content\">";
if (($controller->default_tab==1)||(!empty($SEARCH_SPIDER))) $controller->getTab(1);
else {
	if ($controller->get_note_count()>0) print "<br /><br />".$pgv_lang['loading'];
	else print "<span id=\"no_tab2\">".$pgv_lang["no_tab2"]."</span>";
	}
?>
</div>
</div>
<!-- =========================== Start 3rd tab individual page === Sources -->
<?php
if(empty($SEARCH_SPIDER)) 
	print "<div id=\"sources\" class=\"tab_page\" style=\"display:none;\" >\n";
else
	print "<div id=\"sources\" class=\"tab_page\" style=\"display:block;\" >\n";
if ($SHOW_SOURCES>=getUserAccessLevel(getUserName())) {
	print "<span class=\"subheaders\">".$pgv_lang["ssourcess"]."</span><div id=\"sources_content\">";
	if (($controller->default_tab==2)||(!empty($SEARCH_SPIDER))) $controller->getTab(2);
	else {
		if ($controller->get_source_count()>0) print "<br /><br />".$pgv_lang['loading'];
		else print "<span id=\"no_tab3\">".$pgv_lang["no_tab3"]."</span>";
	}
	print "</div>\n";
}
?>
</div>
<!-- ==================== Start 4th tab individual page ==== Media -->
<?php
if(empty($SEARCH_SPIDER)) 
	print "<div id=\"media\" class=\"tab_page\" style=\"display:none;\" >\n";
else
	print "<div id=\"media\" class=\"tab_page\" style=\"display:block;\" >\n";
?>
<span class="subheaders"><?php print $pgv_lang["media"];?></span>
<div id="media_content">
<?php
	if ($MULTI_MEDIA && ($controller->get_media_count()>0 || userCanEdit(getUserName()))) {
		if (($controller->default_tab==3)||(!empty($SEARCH_SPIDER))) $controller->getTab(3);
		else print "<br /><br />".$pgv_lang['loading'];
   		}
	else print "<table class=\"facts_table\"><tr><td id=\"no_tab4\" colspan=\"2\" class=\"facts_value\">".$pgv_lang["no_tab4"]."</td></tr></table>\n";
?>
</div>
</div>
<!-- ============================= Start 5th tab individual page ==== Close relatives -->
<?php
if(empty($SEARCH_SPIDER)) 
	print "<div id=\"relatives\" class=\"tab_page\" style=\"display:none;\" >\n";
else
	print "<div id=\"relatives\" class=\"tab_page\" style=\"display:block;\" >\n";
?>
<div id="relatives_content">
<?php
	if (($controller->default_tab==4)||(!empty($SEARCH_SPIDER))) $controller->getTab(4);
	else print "<br /><br />".$pgv_lang['loading'];
?>
</div>
</div>

<!-- ===================================== Start 6th tab individual page === Research Assistant -->
<?php
// Only show this section if we are not talking to a search engine.
if(empty($SEARCH_SPIDER)) {
	print "<div id=\"researchlog\" class=\"tab_page\" style=\"display:none;\" >\n";
	print "<span class=\"subheaders\">".$pgv_lang["research_assistant"]."</span>\n";
	print "<div id=\"researchlog_content\">\n";

	if (file_exists("modules/research_assistant/research_assistant.php") && ($SHOW_RESEARCH_ASSISTANT>=getUserAccessLevel())) {
		if ($controller->default_tab==5) $controller->getTab(5);
		else print "<br /><br />".$pgv_lang['loading'];
	}
	else {
		print "<table class=\"facts_table\"><tr><td id=\"no_tab6\" colspan=\"2\" class=\"facts_value\">".$pgv_lang["no_tab6"]."</td></tr></table>\n";
	}
	print "</div>\n";
	print "</div>\n";
}

// Only show this section if we are not talking to a search engine.
//--------------------------------Start 7th tab individual page
//--- Google map
if(empty($SEARCH_SPIDER)) {
	if (file_exists("modules/googlemap/defaultconfig.php")) {
		print "<div id=\"googlemap\" class=\"tab_page\" style=\"display:none;\" >\n";
    		print "<span class=\"subheaders\">".$pgv_lang["googlemap"]."</span>\n";
		print "<div id=\"googlemap_content\">\n";
		if ($controller->default_tab==6) $controller->getTab(6);
		else print "<br /><br />".$pgv_lang['loading'];
		print "</div>\n";
		print "</div>\n";
	}
}
?>

<script language="JavaScript" type="text/javascript">
<!--
	var catch_and_ignore;
	function paste_id(value) {
		catch_and_ignore = value;
	}
<?php if ($controller->isPrintPreview()) print "tabswitch(0)";
else print "tabswitch(". ($controller->default_tab+1) .")";
?>
//-->
</script>
<?php
if(empty($SEARCH_SPIDER))
	print_footer();
else {
	if($SHOW_SPIDER_TAGLINE)
		print $pgv_lang["label_search_engine_detected"].": ".$SEARCH_SPIDER;
	print "\n</div>\n\t</body>\n</html>";
}
?>
