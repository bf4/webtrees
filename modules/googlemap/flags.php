<?php
/**
 * Interface to edit place locations
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @subpackage Edit
 * @version $Id$
 */

require_once "config.php";
require "modules/googlemap/defaultconfig.php";
if (file_exists('modules/googlemap/config.php')) require('modules/googlemap/config.php');
require "includes/functions_edit.php";
require $INDEX_DIRECTORY."pgv_changes.php";

loadLangFile("pgv_facts, gm_lang, gm_help");

$saveLanguage = $LANGUAGE;
$LANGUAGE = $deflang;
loadLangFile("pgv_country");
$LANGUAGE = $saveLanguage;

if(!function_exists('scandir')) {
   function scandir($dir, $sortorder = 0) {
       if(is_dir($dir))        {
           $dirlist = opendir($dir);
           while( ($file = readdir($dirlist)) !== false) {
               if(!is_dir($file)) {
                   $files[] = $file;
               }
           }
           ($sortorder == 0) ? asort($files) : rsort($files); // arsort was replaced with rsort
           return $files;
       } else {
       return FALSE;
       break;
       }
   }
}

if(!isset($countrySelected)) $countrySelected="Countries";
if (!isset($_POST)) $_POST = $HTTP_POST_VARS;

print_simple_header($pgv_lang["flags_edit"]);

$country = array();
$rep = opendir('./places/flags/');
while ($file = readdir($rep)) {
    if (stristr($file, ".gif")) {
        $country[] = substr($file, 0, strlen($file)-4);
    }
}
closedir($rep);
sort($country);

if($countrySelected == "Countries") {
    $flags = $country;
}
else {
    $flags = array();
    $rep = opendir('./places/'.$countrySelected.'/flags/');
    while ($file = readdir($rep)) {
        if (stristr($file, ".gif")) {
            $flags[] = substr($file, 0, strlen($file)-4);
        }
    }
    closedir($rep);
    sort($flags);
}

if ($action == "ChangeFlag") {
?>
    <script type="text/javascript">
    <!--
        function edit_close() {
<?php if($_POST["selcountry"] == "Countries") { ?>
            window.opener.document.editplaces.icon.value = "places/flags/<?php print $flags[$_POST["FLAGS"]];?>.gif";
            window.opener.document.getElementById('flagsDiv').innerHTML = "<img src=\"places/flags/<?php print $country[$_POST["FLAGS"]];?>.gif\">&nbsp;&nbsp;<a href=\"javascript:;\" onclick=\"change_icon();return false;\"><?php print $pgv_lang["pl_change_flag"]?></a>&nbsp;&nbsp;<a href=\"javascript:;\" onclick=\"remove_icon();return false;\"><?php print $pgv_lang["pl_remove_flag"]?></a>";
<?php } else { ?>
            window.opener.document.editplaces.icon.value = "places/<?php print $countrySelected."/flags/".$flags[$_POST["FLAGS"]];?>.gif";
            window.opener.document.getElementById('flagsDiv').innerHTML = "<img src=\"places/<?php print $countrySelected."/flags/".$flags[$_POST["FLAGS"]];?>.gif\">&nbsp;&nbsp;<a href=\"javascript:;\" onclick=\"change_icon();return false;\"><?php print $pgv_lang["pl_change_flag"]?></a>&nbsp;&nbsp;<a href=\"javascript:;\" onclick=\"remove_icon();return false;\"><?php print $pgv_lang["pl_remove_flag"]?></a>";
<?php } ?>
            if (window.opener.showchanges) window.opener.showchanges();
            window.close();
        }
    //-->
    </script>
<?php
    if ($EDIT_AUTOCLOSE and !$GLOBALS["DEBUG"]) print "\n<script type=\"text/javascript\">\n<!--\nedit_close();\n//-->\n</script>";
    print "<div class=\"center\"><a href=\"javascript:;\" onclick=\"edit_close();\">".$pgv_lang["close_window"]."</a></div><br />\n";
    print_simple_footer();
    exit;
}
else {
?>
<script type="text/javascript">
<!--
    function enableButtons() {
        document.flags.save1.disabled = "";
        document.flags.save2.disabled = "";
    }

    function selectCountry() {
        if (document.flags.COUNTRYSELECT.value == "Countries") {
            window.location="module.php?mod=googlemap&pgvaction=flags";
        }
        else {
            window.location="module.php?mod=googlemap&pgvaction=flags&countrySelected=" + document.flags.COUNTRYSELECT.value;
        }
    }

    function edit_close() {
        if (window.opener.showchanges) window.opener.showchanges();
        window.close();
    }

var helpWin;
function helpPopup(which) {
    if ((!helpWin)||(helpWin.closed)) helpWin = window.open('module.php?mod=googlemap&pgvaction=editconfig_help&help='+which,'_blank','left=50,top=50,width=500,height=320,resizable=1,scrollbars=1');
    else helpWin.location = 'modules/googlemap/editconfig_help.php?help='+which;
    return false;
}

function getHelp(which) {
    if ((helpWin)&&(!helpWin.closed)) helpWin.location='module.php?mod=googlemap&pgvaction=editconfig_help&help='+which;
}

//-->
</script>
<?php

}
    if (!isset($_SESSION['flags_countrylist'])) {
        $countryList = array();
        $placesDir = scandir('./places/');
        for ($i = 0; $i < count($country); $i++) {
			if (count(preg_grep('/'.$country[$i].'/', $placesDir)) != 0) {
                $rep = opendir('./places/'.$country[$i].'/');
                while ($file = readdir($rep)) {
                    if (stristr($file, "flags")) {
                        $countryList[] = $country[$i];
                    }
				}
				closedir($rep);
			}
		}
		$_SESSION['flags_countrylist'] = serialize($countryList);
	} else {
		$countryList = unserialize($_SESSION['flags_countrylist']);
	}
?>


<form method="post" id="flags" name="flags" action="module.php?mod=googlemap&pgvaction=flags&countrySelected=<?php print $countrySelected;?>">
    <input type="hidden" name="action" value="ChangeFlag" />
    <input type="hidden" name="selcountry" value="<?php print $countrySelected;?>" />
    <input id="savebutton" name="save1" type="submit" disabled="true" value="<?php print $pgv_lang["save"];?>" /><br />
    <table class="facts_table">
        <tr>
            <td class="optionbox" colspan="4">
                <?php print_help_link("PLE_FLAGS_help", "qm", "PLE_FLAGS");?>
                <select name="COUNTRYSELECT" dir="ltr" tabindex="0" onchange="selectCountry()">
                    <option value="Countries"><?php print $pgv_lang["pl_countries"]; ?></option>
<?php               for ($i = 0; $i < count($countryList); $i++) {
                        print "                    <option value=\"".$countryList[$i]."\"";
                        if ($countrySelected == $countryList[$i]) print " selected=\"selected\" ";
                        print ">".$countries[$countryList[$i]]."</option>\n";
                    } ?>
                    
                </select>
            </td>
        </tr>
        <tr>
<?php
    $j = 1;
    for ($i = 0; $i < count($flags); $i++) {
        if ($countrySelected == "Countries") {
			$tempstr = "            <td><input type=\"radio\" dir=\"ltr\" tabindex=\"".($i+1)."\" name=\"FLAGS\" value=\"".$i."\" onchange=\"enableButtons();\"><img src=\"places/flags/".$flags[$i].".gif\" alt=\"".$flags[$i]."\"  title=\"";
			if (array_key_exists( $country[$i], $countries))
				$tempstr .=$countries[$country[$i]];
			else if ($flags[$i]=="blank") {
				$tempstr .=$countries["???"];
				$flags[$i]="???";
			}
			else
				$tempstr .= $flags[$i];
			print $tempstr."\">&nbsp;&nbsp;".$flags[$i]."</input></td>\n";
		}
        else {
            print "            <td><input type=\"radio\" dir=\"ltr\" tabindex=\"".($i+1)."\" name=\"FLAGS\" value=\"".$i."\" onchange=\"enableButtons();\"><img src=\"places/".$countrySelected."/flags/".$flags[$i].".gif\">&nbsp;&nbsp;".$flags[$i]."</input></td>\n";
        }
        if ($j == 4) {
            print "        </tr><tr>\n";
            $j = 0;
        }
        $j = $j + 1;
    }
?>
        </tr>
    </table>
    <input id="savebutton" name="save2" type="submit" disabled="true" value="<?php print $pgv_lang["save"];?>" /><br />
</form>
<?php
print "<div class=\"center\"><a href=\"javascript:;\" onclick=\"edit_close();\">".$pgv_lang["close_window"]."</a></div><br />\n";
        
print_simple_footer();
?>
