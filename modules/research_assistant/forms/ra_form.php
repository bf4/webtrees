<?php
/**
 * phpGedView Research Assistant Tool - ra_form.
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
 * @subpackage Research_Assistant
 * @version $Id: ra_form.php 948 2006-05-30 20:40:29Z jfinlay $
 * @author Jason Porter
 */
//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"module.php")===false) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
global $factsfile, $LANGUAGE, $factarray;
require_once($factsfile["english"]);
if (file_exists($factsfile[$LANGUAGE])) require_once($factsfile[$LANGUAGE]);
require_once "includes/person_class.php";

/**
 * Base class for Research Assistant forms
 * 
 */
class ra_form {
	/**
	 * GETS all PEOPLE associated with the task given taskid
	 * 
	 * @return array people associated with the task
	 */
	function getPeople(){
        global $TBLPREFIX, $DBCONN;

		$sql = 	"SELECT it_i_id FROM " . $TBLPREFIX . "individualtask WHERE it_t_id='" . $DBCONN->escapeSimple($_REQUEST["taskid"]) . "'";
		$res = dbquery($sql);

		$people = array();
		while($row = $res->fetchRow()){
			$people[$row[0]] = Person::getInstance($row[0]);
		}
		$res->free();
		return $people;
	}
	
	/**
	 * GETS all SOURCES associated with the task given taskid
	 * 
	 * @return sources associated with the task
	 */
	function getSources(){
        global $TBLPREFIX, $DBCONN;

		$sql = 	"SELECT s_name, s_id FROM " . $TBLPREFIX . "sources WHERE s_id IN " .
				"(SELECT ts_s_id FROM " . $TBLPREFIX . "tasksource WHERE ts_t_id='" . $DBCONN->escapeSimple($_REQUEST["taskid"]) . "')";
		$res = dbquery($sql);

		$sources = array();
		while($source =& $res->fetchRow(DB_FETCHMODE_ASSOC)){
			$sources[$source["s_id"]] = $source["s_name"];
		}
		$res->free();
		return $sources;
	}
	
	function getSourceCitationData() {
		global $TBLPREFIX, $DBCONN;

		$sql = 	"SELECT * FROM " . $TBLPREFIX . "tasksource WHERE ts_t_id='" . $DBCONN->escapeSimple($_REQUEST["taskid"]) . "'";
		$res = dbquery($sql);

		if ($res->numRows()>0) {
			$row = $res->fetchRow(DB_FETCHMODE_ASSOC);
			$row = db_cleanup($row);
			$row['ts_array'] = unserialize($row['ts_array']);
		}
		else $row = array('ts_page'=>'','ts_quay'=>'','ts_text'=>'','ts_date'=>'','ts_obje'=>'','ts_array'=>array());
		$res->free();
		return $row;
	}
	
	function getFactData() {
		global $TBLPREFIX, $DBCONN;

		$sql = 	"SELECT * FROM " . $TBLPREFIX . "taskfacts WHERE tf_t_id='" . $DBCONN->escapeSimple($_REQUEST["taskid"]) . "'";
		$res = dbquery($sql);

		$tasks = array();
		if ($res->numRows()>0) {
			while($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
				$tasks[] = db_cleanup($row);
			}
		}
		$res->free();
		return $tasks;
	}
	
    /**
     * heading 
     * 
     * @param string $action 
     * @param string $tableAlign 
     * @param string $heading 
     * @access public
     * @return void
     */
    function heading($action = 'self', $tableAlign = 'center', $heading = '') {
        $out = '<form action="' . $action . '" method="post">
            <table class="list_table" align="' . $tableAlign . '">
                <tr>
                    <th colpsan="4" align="right"><h2>' . $heading . '</h2></th>
                </tr>';
        return $out;
    }

    /**
     * title 
     * 
     * @param string $title 
     * @access public
     * @return void
     */
    function title($title = '') {
        $out = '<tr>
                    <th>' . $title . '</th>
                </tr>';
        return $out;
    }

    /**
     * footer 
     * 
     * @access public
     * @return void
     */
    function footer() {
        $out = '</table></form>';
        return $out;
    }
    
    /**
     * content 
     * 
     * @access public
     * @return void
     */
    function content() {
        return '<tr><td>Content here</td></tr>';
    }

    /**
     * display_form 
     * 
     * @access public
     * @return void
     */
    function display_form() {
        $output = ra_form::heading();
        $output .= ra_form::title();
        $output .= ra_form::content();
        $output .= ra_form::footer();
        return $output;
    }
    
    function simpleCitationForm($citation) {
    	global $pgv_lang, $factarray;
    	$out = '<tr>
			<td class="descriptionbox">'.print_help_link("edit_PAGE_help", "qm",'',false,true).$factarray['PAGE'].'</td>
			<td class="optionbox"><input type="text" name="PAGE" value="'.$citation["ts_page"].'" /></td>
		</tr>
		<tr>
			<td class="descriptionbox">'.print_help_link("edit_TEXT_help", "qm",'',false,true).$factarray['TEXT'].'</td>
			<td class="optionbox"><textarea name="TEXT" rows="5" cols="55">'.$citation['ts_text'].'</textarea></td>
		</tr>
		<tr>
			<td class="descriptionbox">'.print_help_link("edit_DATE_help", "qm",'',false,true).$pgv_lang['date_of_entry'].'</td>
			<td class="optionbox"><input type="text" name="DATE" id="date" onblur="valid_date(this);" value="'.$citation['ts_date'].'" />'.print_calendar_popup('date', true).'</td>
		</tr>
		<tr>
			<td class="descriptionbox">'.print_help_link("edit_QUAY_help", "qm",'',false,true).$factarray['QUAY'].'</td>
			<td class="optionbox"><input type="text" name="QUAY" value="'.$citation['ts_quay'].'" /></td>
		</tr>
		<tr>
			<td class="descriptionbox">'.print_help_link("edit_media_help", "qm",'',false,true).$factarray['OBJE'].'</td>
			<td class="optionbox"><input type="text" name="OBJE" id="OBJE" size="5" value="'.$citation['ts_obje'].'"/>';
		$out .= print_findmedia_link("OBJE", true, '', true);
		$out .= '<br /><a href="javascript:;" onclick="pastefield=document.getElementById(\'OBJE\'); window.open(\'addmedia.php?action=showmediaform\', \'\', \'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1\'); return false;">'.$pgv_lang["add_media"].'</a>';
		$out .= '</td></tr>';
		return $out;
    }
    
    /**
     * displays the form for editing the source citation information
     */
    function sourceCitationForm($colspan=3) {
    	global $pgv_lang, $factarray;
	
		$citation = $this->getSourceCitationData();
		$task = ra_functions::getTask($_REQUEST['taskid']);

		$out = '<tr>
			<td class="descriptionbox">'.$pgv_lang['title'].'</td>
			<td class="optionbox" colspan="'.$colspan.'">'.$task['t_title'].'</td>
		</tr>';
		
		$out .= '<!--SOURCES-->
			<td class="descriptionbox">'.print_help_link("edit_SOUR_help", "qm",'',false,true).$pgv_lang["source"].'</td>
                <td class="optionbox" colspan="'.$colspan.'">
                <script language="JavaScript" type="text/javascript">
	<!--
	var pastefield;
	var nameElement;
	var lastId;
	function paste_id(value) {
		pastefield.value = pastefield.value + ";" + value;
		lastId = value;
	}
	function pastename(name) {
		nameElement.innerHTML = nameElement.innerHTML + \'<a id="link_\'+lastId+\'" href="source.php?sid=\'+pastefield.value+\'">\'+name+\'</a> <a id="rem_\'+lastId+\'" href="#" onclick="clearname(\\\'\'+pastefield.id+\'\\\', \\\'link_\'+lastId+\'\\\', \\\'\'+lastId+\'\\\'); return false;" ><img src="images/remove.gif" border="0" alt="" /><br /></a>\n\';
	}
	function clearname(hiddenName, name, id) {
		pastefield = document.getElementById(hiddenName);
		if (pastefield) {
			pos1 = pastefield.value.indexOf(";"+id);
			if (pos1>-1) {
				pos2 = pastefield.value.indexOf(";", pos1+1);
				if (pos2==-1) pos2 = pastefield.value.length;
				pastefield.value = pastefield.value.substring(0, pos1)+pastefield.value.substring(pos2);
			}
		}
		nameElement = document.getElementById(name);
		if (nameElement) {
			nameElement.innerHTML = \'\';
		}
		nameElement = document.getElementById(\'rem_\'+id);
		if (nameElement) {
			nameElement.innerHTML = \'\';
		}
	}
	//-->
	</script>
                   <div id="sourcelink">';
                   			$sources = $this->getSources();
                   			$sval = '';
                   			foreach($sources as $sid=>$source) {
                   				$sval .= ';'.$sid;
                   				$out .= '<a id="link_'.$sid.'" href="source.php?sid='.$sid.'">'.$source.'</a> <a id="rem_'.$sid.'" href="#" onclick="clearname(\'sourceid\', \'link_'.$sid.'\', \''.$sid.'\'); return false;" ><img src="images/remove.gif" border="0" alt="" /><br /></a>';
                   			}
                   $out .= '</div>
                   <input type="hidden" id="sourceid" name="sourceid" size="3" value="'.$sval.'" />';
                   $out .= print_findsource_link("sourceid", "sourcelink", true);
                   $out .= '<br />
                </td>
            </tr>';
            
		$out .= $this->simpleCitationForm($citation);
		$out .= '<tr>
			<td class="descriptionbox">'.$pgv_lang["people"].'</td>
            <td id="peoplecell" class="optionbox" colspan="'.$colspan.'">
                   <div id="peoplelink">';
                   			$people = $this->getPeople();
                   			$pval = '';
                   			foreach($people as $pid=>$person) {
                   				$pval .= ';'.$person->getXref();
                   				$out .= '<a id="link_'.$pid.'" href="individual.php?pid='.$pid.'">'.$person->getName().'</a> <a id="rem_'.$pid.'" href="#" onclick="clearname(\'personid\', \'link_'.$pid.'\', \''.$pid.'\'); return false;" ><img src="images/remove.gif" border="0" alt="" /><br /></a>';
                   			}
                   $out .= '</div>
                   <input type="hidden" id="personid" name="personid" size="3" value="'.$pval.'" />';
                   $out .= print_findindi_link("personid", "peoplelink", true);
                   $out .= '<br />
            </td>
        </tr>';
        $out .= '<tr><td class="descriptionbox" align="center" colspan="'.($colspan+1).'"><input type="submit" value="Next &gt;&gt;"></td></tr>';
		return $out;
    }
    
    /**
     * process the data from the source citation form
     */
    function processSourceCitation() {
    	global $GEDCOMS, $GEDCOM, $TBLPREFIX, $DBCONN;
    	if (empty($_REQUEST['sourceid'])) {
			return $this->display_form("You must select a source.");
		}
		if (empty($_REQUEST['personid'])) {
			return $this->display_form("You must select at least one person.");
		}
		
		// UPDATE PEOPLE
		$oldpeople = $this->getPeople();
		
		//  -Delete old people
		$sql = "DELETE FROM ".$TBLPREFIX."individualtask WHERE it_t_id='".$_REQUEST["taskid"]."'";
		$res = dbquery($sql);

		if (isset ($_REQUEST['personid'])) {
			$people = preg_split("/;/", $_REQUEST['personid']);
			//-- delete any existing facts from old people
			foreach($oldpeople as $pid=>$person) {
				if (!isset($people[$pid])) {
					$newrec = ra_functions::deleteRAFacts($_REQUEST['taskid'], $person->getGedcomRecord());
					if ($newrec!=$person->getGedcomRecord()) replace_gedrec($pid, $newrec);
				}
					
			}
			//-- add the new people to the database
			foreach($people as $i=>$pid) {
				if (!empty($pid)) {
					$sql = 'INSERT INTO '.$TBLPREFIX.'individualtask (it_t_id, it_i_id, it_i_file) '."VALUES ('" . $DBCONN->escapeSimple($_REQUEST["taskid"]) . "', '".$DBCONN->escapeSimple($pid)."', '".$DBCONN->escapeSimple($GEDCOMS[$GEDCOM]['id'])."')";
					$res = dbquery($sql);
				}
			}
		}
		else {
			//-- delete all records from old people
			foreach($oldpeople as $pid=>$person) {
				$newrec = ra_functions::deleteRAFacts($_REQUEST['taskid'], $person->getGedcomRecord());
				if ($newrec!=$person->getGedcomRecord()) replace_gedrec($pid, $newrec);
			}
		}
		
		// UPDATE SOURCES
				//  -Delete old sources
		$sql = "DELETE FROM ".$TBLPREFIX."tasksource WHERE ts_t_id='".$_REQUEST["taskid"]."'";
		$res = dbquery($sql);

		if (isset ($_REQUEST['sourceid'])) {
			$sources = preg_split("/;/", $_REQUEST['sourceid']);
			foreach($sources as $i=>$sid) {
				if (!empty($sid)) {
					$citation = $this->processSimpleCitation();
					$sql = 'INSERT INTO '.$TBLPREFIX.'tasksource (ts_t_id, ts_s_id, ts_page, ts_quay, ts_date, ts_text, ts_obje, ts_array) '."VALUES ('" . $DBCONN->escapeSimple($_REQUEST["taskid"]) . "', '".$DBCONN->escapeSimple($sid)."'" .
							",'".$DBCONN->escapeSimple($citation['PAGE'])."'" .
							",'".$DBCONN->escapeSimple($citation['QUAY'])."'" .
							",'".$DBCONN->escapeSimple($citation['DATE'])."'" .
							",'".$DBCONN->escapeSimple($citation['TEXT'])."'" .
							",'".$DBCONN->escapeSimple($citation['OBJE'])."'" .
							",'".$DBCONN->escapeSimple(serialize($citation['array']))."')";
					$res = dbquery($sql);
				}
			}
		}
    }
    
    function processSimpleCitation() {
    	$citation = array("PAGE"=>$_REQUEST['PAGE'], "QUAY"=>$_REQUEST['QUAY'], 
    		"DATE"=>$_REQUEST['DATE'], "TEXT"=>$_REQUEST['TEXT'], "OBJE"=>$_REQUEST['OBJE'], "array"=>array());
    	return $citation;
    }
    
    /**
     * display the form for adding and editing facts
     */
    function editFactsForm() {
    	global $pgv_lang, $INDI_FACTS_ADD, $factarray;
    	$task = ra_functions::getTask($_REQUEST['taskid']);
    	$out = <<<END_OUT
		<tr>
			<td class="descriptionbox">{$pgv_lang['title']}</td>
			<td class="optionbox">{$task['t_title']}</td>
		</tr>
		<tr>
			<td class="descriptionbox">Associate Facts</td>
			<td class="optionbox"><select id="newfact" name="newfact">
END_OUT;
			$facts = preg_split("/[, ;:]+/", $INDI_FACTS_ADD, -1, PREG_SPLIT_NO_EMPTY);
			foreach($facts as $f=>$fact) {
				$out .= '<option value="'.$fact.'">'.$factarray[$fact]. ' ['.$fact.']</option>';
			}
			$peopleList = "";
			$people = $this->getPeople();
			foreach($people as $pid=>$person) {
				$peopleList .= "<option value=\"$pid\" selected=\"selected\">".$person->getName()."</option>";
			}
			$out .= <<<END_OUT
			<option value="EVEN">{$pgv_lang['custom_event']}</option>
			</select>
			<script language="JavaScript" type="text/javascript">
			<!--
			var facts = new Array();
			var factnames = new Array();
			var peopleList = new Array();
			var factcount = 0;
END_OUT;
			$facts = $this->getFactData();
			if (count($facts)>0) {
				$out .= "\r\nfactcount = ".count($facts).";\r\n";
				foreach($facts as $i=>$fact) {
					$out .= "facts[$i] = '".preg_replace("/\r?\n/", "\\r\\n",$fact['tf_factrec'])."'\r\n";
					$ct = preg_match("/1 (\w+)/", $fact['tf_factrec'], $match);
					$factname = trim($match[1]);
					if (isset($factarray[$factname])) $factname = $factarray[$factname];
					$out .= "factnames[".$i."] = '".$factname."'\r\n";
					$peopleList = "";
					$people = $this->getPeople();
					$selectedPeople = explode(";", $fact['tf_people']);
					foreach($people as $pid=>$person) {
						$peopleList .= "<option value=\"$pid\" ";
						if (in_array($pid, $selectedPeople)) $peopleList .= "selected=\"selected\"";
						$peopleList .= ">".$person->getName()."</option>";
					}
					$out .= "peopleList[$i] = '$peopleList';\r\n";
				}
			}
			
			$out .= <<<END_OUT
			function add_ra_fact(fact) {
				factfield = document.getElementById(fact);
				if (factfield) {
					factvalue = factfield.options[factfield.selectedIndex].value;
					window.open('edit_interface.php?action=mod_edit_fact&mod=research_assistant&command=add&fact='+factvalue+"&"+sessionname+"="+sessionid, '', 'top=50,left=50,width=710,height=500,resizable=1,scrollbars=1');
				}
				return false;
			}
			var editi = 0;
			function edit_ra_fact(i) {
				editi = i;
				factfield = document.getElementById('fact'+i);
//				alert(factfield);
				if (factfield) {
					factvalue = factfield.value;
					window.open('edit_interface.php?action=mod_edit_fact&mod=research_assistant&command=edit&factrec='+escape(factvalue)+"&"+sessionname+"="+sessionid, '', 'top=50,left=50,width=710,height=500,resizable=1,scrollbars=1');
				}
				return false;
			}
			function paste_data(data, factname) {
				facts[factcount] = data;
				factnames[factcount] = factname;
				factcount++;
				build_table();
			}
			function paste_edit_data(data, factname) {
				facts[editi] = data;
				factnames[editi] = factname;
				factfield = document.getElementById('fact'+editi);
				if (factfield) {
					factfield.value = data;
				}
				factfield = document.getElementById('factname'+editi);
				if (factfield) {
					out = factnames[editi] + '<br />';
					pos1 = facts[editi].indexOf('2 DATE ');
					if (pos1>-1) {
						pos1 += 7;
						pos2 = facts[editi].indexOf('\\n', pos1);
						if (pos2>-1) out += facts[editi].slice(pos1, pos2);
						else out += facts[editi].slice(pos1, facts[editi].length);
					}
					out += '<input type="hidden" name="fact'+editi+'" id="fact'+editi+'" value="'+facts[editi]+'" />';
					factfield.innerHTML = out;
				}
			}
			
			function remove_fact(i) {
				newfacts = new Array();
				newfactnames = new Array();
				k=0;
				for(j=0; j<factcount; j++) {
					if (j!=i) {
						newfacts[k]=facts[j];
						newfactnames[k]=factnames[j];
						k++;
					}
				}
				facts = newfacts;
				factcount = k;
				build_table();
			}
			
			function build_table() {
				tempdata = document.getElementById('tempdata');
				if (!tempdata) return;
				if (facts.length==0) {
					tempdata.innerHTML = "";
					return;
				}
				out = '<table class="facts_table"><tr><td colspan="3" class="topbottombar">Facts</td></tr>';
				out += '<tr><td class="descriptionbox">Fact</td><td class="descriptionbox">People</td><td class="descriptionbox">remove</td></tr>';
				for(i=0; i<facts.length; i++) {
					out += '<tr><td id="factname'+i+'" class="optionbox">'+factnames[i];
					out += '<br />';
					pos1 = facts[i].indexOf('2 DATE ');
					if (pos1>-1) {
						pos1 += 7;
						pos2 = facts[i].indexOf('\\n', pos1);
						if (pos2>-1) out += facts[i].slice(pos1, pos2);
						else out += facts[i].slice(pos1, facts[i].length);
					}
					out += '<input type="hidden" name="fact'+i+'" id="fact'+i+'" value="'+facts[i]+'" />';
					out += '</td>';
					out += '<td class="optionbox"><select name="people'+i+'[]" size="3" multiple="multiple">';
					if (peopleList[i]) out += peopleList[i]; 
					else out += '$peopleList';
					out += '</select></td>';
					out += '<td class="optionbox"><a href="#" onclick="remove_fact('+i+'); return false;"><img src="images/remove.gif" border="0" /></a><br />' +
							'<a href="#" onclick="edit_ra_fact('+i+'); return false;">{$pgv_lang["edit"]}</a>' +
							'</td></tr>';
				}
				tempdata.innerHTML = out+'</table><input type="hidden" name="factcount" value="'+facts.length+'" />';
			}
			//-->
			</script>
			<input type="button" value="{$pgv_lang["add"]}" onclick="add_ra_fact('newfact');" />
			</td>
		</tr>
		<tr>
			<td colspan="2" id="tempdata">
			<script language="JavaScript" type="text/javascript">
			<!--
			if (facts.length>0) build_table();
			//-->
			</script>
			</td>
		</tr>
END_OUT;
		$out .= '<tr><td class="descriptionbox" align="center" colspan="2"><input type="submit" value="Complete"></td></tr>';
		return $out;
    }
    
    /**
     * process the added/edited facts
     */
    function processFactsForm() {
    	global $pgv_lang, $TBLPREFIX, $factarray, $GEDCOMS, $GEDCOM, $DBCONN;
    	//-- generate the text for the citation
		$citation = $this->getSourceCitationData();
		$citationTxt = "";
		if (!empty($citation['ts_s_id'])) {
			$citationTxt = "2 SOUR @".$citation['ts_s_id']."@\r\n";
			if (!empty($citation['ts_page'])) $citationTxt .= "3 PAGE ".$citation['ts_page']."\r\n";
			if (!empty($citation['ts_date'])||!empty($citation['ts_text'])) {
				$citationTxt .= "3 DATA\r\n";
				if (!empty($citation['ts_date'])) $citationTxt .= "4 DATE ".$citation['ts_date']."\r\n";
				if (!empty($citation['ts_text'])) $citationTxt .= "4 TEXT ".breakConts($citation['ts_text'], 5)."\r\n";
			}
			if (!empty($citation['ts_quay'])) $citationTxt .= "3 QUAY ".$citation['ts_quay']."\r\n";
			if (!empty($citation['ts_obje'])) $citationTxt .= "3 OBJE ".$citation['ts_obje']."\r\n";
		}
		// Set our output to nothing, this supresses a warning that we would otherwise get.
		$out = "";

		$sql = "DELETE FROM ".$TBLPREFIX."taskfacts WHERE tf_t_id='".$DBCONN->escapeSimple($_REQUEST['taskid'])."'";
		$res = dbquery($sql);

		//-- delete all records from old people
		$oldpeople = $this->getPeople();
		$newpeoplerecs = array();
		foreach($oldpeople as $pid=>$person) {
			$newrec = ra_functions::deleteRAFacts($_REQUEST['taskid'], $person->getGedcomRecord());
			$newpeoplerecs[$pid]=$newrec;
		}
		$factcount = $_REQUEST['factcount'];
		for($i=0; $i<$factcount; $i++) {
			if (isset($_REQUEST['fact'.$i])) {
				$factrec = $_REQUEST['fact'.$i];
				$people = array();
				$peopleTxt = "";
				if (isset($_REQUEST['people'.$i])) {
					$people = $_REQUEST['people'.$i];
					$peopleTxt = implode(';',$_REQUEST['people'.$i]);
				}
				//-- store the fact associations in the database
				$sql = "INSERT INTO ".$TBLPREFIX."taskfacts VALUES('".get_next_id("taskfacts", "tf_id")."'," .
					"'".$DBCONN->escapeSimple($_REQUEST['taskid'])."'," .
					"'".$DBCONN->escapeSimple($factrec)."'," .
					"'".$DBCONN->escapeSimple($peopleTxt)."')";
				$res = dbquery($sql);
				foreach($people as $in=>$pid) {
					if (!empty($pid) && isset($newpeoplerecs[$pid])) {
						$indirec = $newpeoplerecs[$pid];
						if (!empty($indirec)) {
							$newpeoplerecs[$pid] .= "\r\n".$factrec."\r\n2 _RATID ".$_REQUEST['taskid']."\r\n".$citationTxt;
						}
					}
				}
			}
		}
		
		foreach($newpeoplerecs as $pid=>$indirec) {
			if ($indirec!=$oldpeople[$pid]->getGedcomRecord()) {
				//$out .= "Replacing gedrec for ".$pid."<br />";
				replace_gedrec($pid, $indirec);
			}
			//else $out .= "NOT for ".$pid."<br />";
		}
    }
}
