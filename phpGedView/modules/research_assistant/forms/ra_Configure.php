<?php
 /**
 * phpGedView Research Assistant Tool - ra_Configure
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2007  John Finlay and Others
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
 * 
 * @author Hector Pena
 */
//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"module.php")===false) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
// Require our base class
require_once'ra_form.php';
require_once'config.php';

global $pgv_lang;
loadLangFile("pgv_confighelp");

/**
 * @uses ra_form
 */
 
 global $PRIV_HIDE, $PRIV_PUBLIC, $PRIV_USER, $PRIV_NONE;
$PRIVACY_CONSTANTS = array();
$PRIVACY_CONSTANTS[$PRIV_HIDE] = "\$PRIV_HIDE";
$PRIVACY_CONSTANTS[$PRIV_PUBLIC] = "\$PRIV_PUBLIC";
$PRIVACY_CONSTANTS[$PRIV_USER] = "\$PRIV_USER";
$PRIVACY_CONSTANTS[$PRIV_NONE] = "\$PRIV_NONE";

global $SHOW_MY_TASKS, $SHOW_ADD_TASK, $SHOW_AUTO_GEN_TASK, $SHOW_VIEW_FOLDERS, $SHOW_ADD_FOLDER, $SHOW_ADD_UNLINKED_SOURCE, $SHOW_VIEW_PROBABILITIES;
global $INDEX_DIRECTORY, $GEDCOM, $person_privacy;
include_once("modules/research_assistant/forms/ra_privacy.php");
if (file_exists($INDEX_DIRECTORY.$GEDCOM."_ra_priv.php")) include_once($INDEX_DIRECTORY.$GEDCOM."_ra_priv.php");

/**
 * print write_access option
 * prints all the available privacy options defined in privacy.php
 * 
 * @param string $checkVar
 */
function write_access_option($checkVar) {
 global $PRIV_HIDE, $PRIV_PUBLIC, $PRIV_USER, $PRIV_NONE;
 global $pgv_lang;
 
  //print 'show to public'
  print "<option value=\"\$PRIV_PUBLIC\"";
  if ($checkVar==$PRIV_PUBLIC) print " selected=\"selected\"";
  print ">".$pgv_lang["PRIV_PUBLIC"]."</option>\n";
  
  //print 'show only to authenticated'
  print "<option value=\"\$PRIV_USER\"";
  if ($checkVar==$PRIV_USER) print " selected=\"selected\"";
  print ">".$pgv_lang["PRIV_USER"]."</option>\n";
  
  //print 'show to admin only'
  print "<option value=\"\$PRIV_NONE\"";
  if ($checkVar==$PRIV_NONE) print " selected=\"selected\"";
  print ">".$pgv_lang["PRIV_NONE"]."</option>\n";
  
  //print 'hide even from admin'
  print "<option value=\"\$PRIV_HIDE\"";
  if ($checkVar==$PRIV_HIDE) print " selected=\"selected\"";
  print ">".$pgv_lang["PRIV_HIDE"]."</option>\n";
}

if (isset($_REQUEST['subaction']) && $_REQUEST['subaction']=="submitconfig") {

	$fRAprivacy = "modules/research_assistant/forms/ra_privacy.php";
	
	$configtext = implode('', file($fRAprivacy));
	$configtext = preg_replace('/\$SHOW_MY_TASKS\s*=\s*.*;/',                       "\$SHOW_MY_TASKS = ".$_POST["v_SHOW_MY_TASKS"].";",            $configtext);
	$configtext = preg_replace('/\$SHOW_ADD_TASK\s*=\s*.*;/',                       "\$SHOW_ADD_TASK = ".$_POST["v_SHOW_ADD_TASK"].";",            $configtext);
	$configtext = preg_replace('/\$SHOW_AUTO_GEN_TASK\s*=\s*.*;/',             "\$SHOW_AUTO_GEN_TASK = ".$_POST["v_SHOW_AUTO_GEN_TASK"].";",       $configtext);
	$configtext = preg_replace('/\$SHOW_VIEW_FOLDERS\s*=\s*.*;/',               "\$SHOW_VIEW_FOLDERS = ".$_POST["v_SHOW_VIEW_FOLDERS"].";",        $configtext);
	$configtext = preg_replace('/\$SHOW_ADD_FOLDER\s*=\s*.*;/',                   "\$SHOW_ADD_FOLDER = ".$_POST["v_SHOW_ADD_FOLDER"].";",          $configtext);
	$configtext = preg_replace('/\$SHOW_ADD_UNLINKED_SOURCE\s*=\s*.*;/', "\$SHOW_ADD_UNLINKED_SOURCE = ".$_POST["v_SHOW_ADD_UNLINKED_SOURCE"].";", $configtext);
	$configtext = preg_replace('/\$SHOW_VIEW_PROBABILITIES\s*=\s*.*;/',   "\$SHOW_VIEW_PROBABILITIES = ".$_POST["v_SHOW_VIEW_PROBABILITIES"].";",  $configtext);
	
	$PRIVACY_MODULE = $INDEX_DIRECTORY.$GEDCOM."_ra_priv.php";
	$fp = fopen($PRIVACY_MODULE, "wb");
	if (!$fp) {
		print "<span class=\"error\">";
		print $pgv_lang["gedcom_config_write_error"];
		print "<br /></span>\n";
	}
	else {
		fwrite($fp, $configtext);
		fclose($fp);
	}
	
	include $INDEX_DIRECTORY.$GEDCOM."_ra_priv.php";
	$logline = AddToLog("Privacy file $PRIVACY_MODULE updated by >".getUserName()."<");
 	$gedcomprivname = $GEDCOM."_ra_priv.php";
 	if (!empty($COMMIT_COMMAND)) check_in($logline, $gedcomprivname, $INDEX_DIRECTORY);	

}
?>
    <!--JAVASCRIPT-->
<!--BEGIN CONFIGURE FORM-->
<form action="module.php" method="post">
    <input type="hidden" name="mod" value="research_assistant" />
    <input type="hidden" name="action" value="configurePrivacy" />
     <input type="hidden" name="subaction" value="submitconfig" />
     
     
	<table class="list_table" align="center">
        <tbody>
            <tr>
    <!--HEADING-->
                <td colspan="4" align="right" class="topbottombar"> 
                    <h2><?php print $pgv_lang["configure_privacy"]; print_help_link("ra_configure_privacy_help", "qm", '', false, false);?></h2>
                </td>
            </tr>
   <!--MY TASKS-->
    		<tr>
                <td class="descriptionbox">
                    <?php print $pgv_lang["show_my_tasks"]; ?>
                </td>
                <td class="optionbox">
                    <select size="1" name="v_SHOW_MY_TASKS">
                        <?php write_access_option($SHOW_MY_TASKS); ?>
                      </select>   
                </td>
            </tr>	       
            
    <!--ADD TASK-->
    		<tr>
                <td class="descriptionbox">
                    <?php print $pgv_lang["show_add_task"]; ?>
                </td>
                <td class="optionbox">
                      <select size="1" name="v_SHOW_ADD_TASK">
                      	<?php write_access_option($SHOW_ADD_TASK); ?>
                      </select>
                </td>
            </tr>
            
    <!--AUTO GENERATE TASK-->
    		<tr>
                <td class="descriptionbox">
                    <?php print $pgv_lang["show_auto_gen_task"]; ?>
                </td>
                <td class="optionbox">
                      <select size="1" name="v_SHOW_AUTO_GEN_TASK">
                      	<?php write_access_option($SHOW_AUTO_GEN_TASK); ?>
                      </select>
                </td>
            </tr>
            
    <!--VIEW FOLDERS-->
    		<tr>
                <td class="descriptionbox">
                    <?php print $pgv_lang["show_view_folders"]; ?>
                </td>
                <td class="optionbox">
                 <select size="1" name="v_SHOW_VIEW_FOLDERS">
                   	<?php write_access_option($SHOW_VIEW_FOLDERS); ?>
                   </select>
                </td>
            </tr>
            
    <!--ADD FOLDER-->
    		<tr>
                <td class="descriptionbox">
                    <?php print $pgv_lang["show_add_folder"]; ?>
                </td>
                <td class="optionbox">
                   <select size="1" name="v_SHOW_ADD_FOLDER">
                       <?php write_access_option($SHOW_ADD_FOLDER); ?>
                      </select> 
               	</td>
            </tr>
            
   <!--ADD UNLINKED SOURCE-->
    		<tr>
                <td class="descriptionbox">
                    <?php print $pgv_lang["show_add_unlinked_source"]; ?>
                </td>
                <td class="optionbox">
                    <select size="1" name="v_SHOW_ADD_UNLINKED_SOURCE">
                        <?php write_access_option($SHOW_ADD_UNLINKED_SOURCE); ?>
                      </select>  
                </td>
            </tr>	
                            
   <!--VIEW PROBABILITIES-->
    		<tr>
                <td class="descriptionbox">
                    <?php print $pgv_lang["show_view_probabilities"]; ?>
                </td>
                <td class="optionbox">
                    <select size="1" name="v_SHOW_VIEW_PROBABILITIES">
                        <?php write_access_option($SHOW_VIEW_PROBABILITIES); ?>
                      </select> 
                </td>
            </tr>	
                   
    <!--SUBMIT BUTTON-->
   			 <tr>
                <td colspan="4" align="right" class="topbottombar">
                    <input type="submit" value="<?php print $pgv_lang["submit"]; ?>" onclick="" />
                    <input type="reset" value="<?php print $pgv_lang["reset"]?>" /><br />
	            </td>
            </tr>
            
        </tbody>
	</table>
</form>
<!--END CONFIGURE -->
