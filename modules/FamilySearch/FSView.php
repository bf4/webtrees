<?php
include_once("includes/controllers/individual_ctrl.php");
require_once('modules/FamilySearch/RA_AutoMatch.php');

$showFull = ($PEDIGREE_FULL_DETAILS) ? 1 : 0;
class FS_IndividualController extends IndividualControllerRoot {
	function FS_IndividualController() {
		parent::IndividualControllerRoot();
	}
	
/**
	* Initialization function
	*/
	function init() {
		global $USE_RIN, $MAX_ALIVE_AGE, $GEDCOM, $GEDCOM_DEFAULT_TAB, $pgv_changes, $pgv_lang, $CHARACTER_SET;
		global $USE_QUICK_UPDATE, $pid;
		global $Fam_Navigator;

		$this->sexarray["M"] = $pgv_lang["male"];
		$this->sexarray["F"] = $pgv_lang["female"];
		$this->sexarray["U"] = $pgv_lang["unknown"];

		$this->pid = safe_GET_xref('pid');

		$pid = $this->pid;

		$this->default_tab = 0;
		$indirec = find_gedcom_record($pid, get_id_from_gedcom($GEDCOM));

		if (empty($indirec)) {
			$ct = preg_match("/(\w+):(.+)/", $this->pid, $match);
			if ($ct>0) {
				$servid = trim($match[1]);
				$remoteid = trim($match[2]);
				include_once('includes/classes/class_serviceclient.php');
				$service = ServiceClient::getInstance($servid);
				if ($service != null) {
					$newrec= $service->mergeGedcomRecord($remoteid, "0 @".$this->pid."@ INDI\n1 RFN ".$this->pid, false);
					$indirec = $newrec;
				}
			} else {
				$indirec = "0 @".$this->pid."@ INDI\n";
			}
		}
//		print "<pre>".$indirec."</pre>";

		//-- set the default tab from a request parameter
		if (isset($_REQUEST['tab'])) {
			$this->default_tab = $_REQUEST['tab'];
		}

		$this->indi = new Person($indirec, false);
		$this->indi->ged_id=PGV_GED_ID; // This record is from a file

		$this->canedit = FALSE;
		
		$this->modules = array();
		$this->modules["personal_facts"] = PGVModule::getModuleByName("personal_facts");
		$this->modules["sources_tab"] = PGVModule::getModuleByName("sources_tab");
		$this->modules["notes"] = PGVModule::getModuleByName("notes");
		$this->modules["tree"] = PGVModule::getModuleByName("tree");
		$this->modules["relatives"] = PGVModule::getModuleByName("relatives");
		uasort($this->modules, "PGVModule::compare_tab_order");
		foreach($this->modules as $mod) {
			if ($mod->hasTab()) {
				$tab = $mod->getTab();
				if ($tab) {
					$tab->setController($this);
				}
			}
		}

		//-- handle ajax calls
		if ($this->action=="ajax") {
			$tab = 0;
			if (isset($_REQUEST['module'])) $tabname = $_REQUEST['module'];
			header("Content-Type: text/html; charset=$CHARACTER_SET");//AJAX calls do not have the meta tag headers and need this set
			$mod = $this->modules[$tabname];
			if ($mod) {
				echo $mod->getTab()->getContent();
			}
			
			//-- only get the requested tab and then exit
			if (PGV_DEBUG_SQL) {
				echo PGV_DB::getQueryLog();
			}
			exit;
		}
	}
}
 
$controller=new FS_IndividualController();
$controller->init();

// We have finished writing to $_SESSION, so release the lock
session_write_close();
print_simple_header($controller->getPageTitle());

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
<script type="text/javascript">
//<![CDATA[
	var selectedTab = 0;
	jQuery.noConflict();	
	jQuery(document).ready(function(){
	jQuery("#tabs").tabs({ cache: true });
	var $tabs = jQuery('#tabs');
    jQuery('#tabs').bind('tabsshow', function(event, ui) {
    	if ($tabs) selectedTab = $tabs.tabs('option', 'selected');
	<?php
	foreach($controller->modules as $mod) {
		if ($mod->hasTab() && $mod->getTab()) {
			echo $mod->getTab()->getJSCallbackAllTabs()."\n";
			$modjs = $mod->getTab()->getJSCallback();
			if (!empty($modjs)) {
				echo 'if (ui.tab.name == "'.$mod->getName().'") {
	'.$modjs.'
}
';
			}
		}
	}
	?>
  });
  });

//]]>
  </script>
<div id="indi_main_blocks">
<div id="indi_top">
		<table class="width100"><tr><td valign="bottom">
		<span class="name_head">
		<?php
		if ($TEXT_DIRECTION=="rtl") echo "&nbsp;";
			echo PrintReady($controller->indi->getFullName());
			echo "&nbsp;&nbsp;";
			echo PrintReady("(".$controller->pid.")");
		?>
		</span>
		<?php if (strlen($controller->indi->getAddName()) > 0) echo "<span class=\"name_head\">".PrintReady($controller->indi->getAddName())."</span><br />"; ?>
		<?php
		// if individual is a remote individual
		// if information for this information is based on a remote site
		if ($controller->indi->isRemote())
		{
			?><br />
			<?php echo $pgv_lang["indi_is_remote"]; ?><!--<br />--><!--take this out if you want break the remote site and the fact that it was remote into two separate lines-->
			<a href="<?php echo encode_url($controller->indi->getLinkUrl()); ?>"><?php echo $controller->indi->getLinkTitle(); ?></a>
			<?php
		}
	?>
	<br />
	
	</td>
	</tr></table>
<?php
foreach($controller->modules as $mod) {
	if ($mod->hasTab() && $mod->getTab()) {
		echo $mod->getTab()->getPreLoadContent();
	}
} 
?>
</div>
<div id="tabs">
<ul>
	<?php
	$tabcount = 0;
	foreach($controller->modules as $mod) {
		if ($mod!=$controller->static_tab && $mod->hasTab()) {
		if ($tabcount==$controller->default_tab || !$mod->getTab()->canLoadAjax()) {?>
			<li><a name="<?php echo $mod->getName(); ?>" href="#<?php echo $mod->getName()?>"><span><?php echo $pgv_lang[$mod->getName()]?></span></a></li>
		<?php } else if ($mod->hasTab() && $mod->getTab() && ($mod->getTab()->hasContent() || PGV_USER_CAN_EDIT)) { ?>
			<li><a name="<?php echo $mod->getName(); ?>" href="module.php?mod=FamilySearch&amp;pgvaction=FSView&amp;action=ajax&amp;module=<?php echo $mod->getName()?>&amp;pid=<?php echo $controller->pid?>">
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
	if ($tabcount==$controller->default_tab || !$mod->getTab()->canLoadAjax()) {?>
	<div id="<?php echo $mod->getName()?>">
		<?php echo $mod->getTab()->getContent(); ?>
	</div>	
	<?php }
	$tabcount++; 
	}
 } ?>
</div>
</div> <!--  end column 1 -->
<?php 
	print_simple_footer();
?>
