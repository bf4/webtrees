<?php
/**
 * Online UI for editing config.php site configuration variables
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team
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
 * This Page Is Valid XHTML 1.0 Transitional! > 17 September 2005
 *
 * @package PhpGedView
 * @subpackage GoogleMap
 * @see config.php
 * @version $Id: editconfig.php,v$
 * $Id: editconfig.php 2698 2008-04-23 21:38:56Z wooc$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require('modules/googlemap/defaultconfig.php');
if (file_exists('modules/googlemap/config.php')) require('modules/googlemap/config.php');

loadLangFile("pgv_lang, pgv_confighelp, pgv_help, googlemap:lang, googlemap:help_text");

function print_level_config_table($level) {
    global $pgv_lang, $GM_MARKER_COLOR, $GM_MARKER_SIZE, $GM_PREFIX;
    global $GM_POSTFIX, $GM_PRE_POST_MODE, $GM_MAX_NOF_LEVELS, $i;
?>
            <div id="level<?php print $level;?>" style="display:<?php if ($GM_MAX_NOF_LEVELS >= $level) {print "block";} else {print "none";}?>">
            <table class="facts_table">
                <tr>
                    <td class="descriptionbox" colspan="2">
                        <?php print $pgv_lang["gm_level"]." ".$level."\n";?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php print_help_link("GM_NAME_PREFIX_help", "qm", "GM_NAME_PREFIX"); print $pgv_lang["gm_name_prefix"];?>
                    </td>
                    <td>
                        <input type="text" name="NEW_NAME_PREFIX_<?php print $level;?>" value="<?php print $GM_PREFIX[$level];?>" size="20" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GM_NAME_PREFIX_help');" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php print_help_link("GM_NAME_POSTFIX_help", "qm", "GM_NAME_POSTFIX"); print $pgv_lang["gm_name_postfix"];?>
                    </td>
                    <td>
                        <input type="text" name="NEW_NAME_POSTFIX_<?php print $level;?>" value="<?php print $GM_POSTFIX[$level];?>" size="20" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GM_NAME_POSTFIX_help');" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php print_help_link("GM_NAME_PRE_POST_help", "qm", "GM_NAME_PRE_POST"); print $pgv_lang["gm_name_pre_post"];?>
                    </td>
                    <td>
                        <select name="NEW_PRE_POST_LEVEL_<?php print $level;?>" dir="ltr" tabindex="<?php $i++; print $i?>" onchange="showSelectedLevels()">
                            <option value="0"<?php if ($GM_PRE_POST_MODE[$level] == 0) print " selected=\"selected\"";?>><?php print $pgv_lang["gm_pp_none"];?></option>
                            <option value="1"<?php if ($GM_PRE_POST_MODE[$level] == 1) print " selected=\"selected\"";?>><?php print $pgv_lang["gm_pp_n_pr_po_b"];?></option>
                            <option value="2"<?php if ($GM_PRE_POST_MODE[$level] == 2) print " selected=\"selected\"";?>><?php print $pgv_lang["gm_pp_n_po_pr_b"];?></option>
                            <option value="3"<?php if ($GM_PRE_POST_MODE[$level] == 3) print " selected=\"selected\"";?>><?php print $pgv_lang["gm_pp_pr_po_b_n"];?></option>
                            <option value="4"<?php if ($GM_PRE_POST_MODE[$level] == 4) print " selected=\"selected\"";?>><?php print $pgv_lang["gm_pp_po_pr_b_n"];?></option>
                            <option value="5"<?php if ($GM_PRE_POST_MODE[$level] == 5) print " selected=\"selected\"";?>><?php print $pgv_lang["gm_pp_pr_po_n_b"];?></option>
                            <option value="6"<?php if ($GM_PRE_POST_MODE[$level] == 6) print " selected=\"selected\"";?>><?php print $pgv_lang["gm_pp_po_pr_n_b"];?></option>
                        </select>
                    </td>
                </tr>
            </table>
            </div>
<?php
}

print_header($pgv_lang["configure_googlemap"]);

print "<span class=\"subheaders\">".$pgv_lang["configure_googlemap"]."</span>";

if (!PGV_USER_IS_ADMIN) {
    print "<table class=\"facts_table\">\n";
    print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["gm_admin_error"];
    print "</td></tr></table>\n";
    print "<br /><br /><br />\n";
    print_footer();
    exit;
}

if ($action=="update" && !isset($security_user)) {
    if (!isset($_POST)) $_POST = $HTTP_POST_VARS;
    $configtext = implode('', file("modules/googlemap/defaultconfig.php"));
    $configtext = preg_replace('/\$GOOGLEMAP_ENABLED\s*=\s*".*";/', "\$GOOGLEMAP_ENABLED = \"".$_POST["NEW_GOOGLEMAP_ENABLE"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_API_KEY\s*=\s*".*";/', "\$GOOGLEMAP_API_KEY = \"".$_POST["NEW_GOOGLEMAP_API_KEY"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_MAP_TYPE\s*=\s*".*";/', "\$GOOGLEMAP_MAP_TYPE = \"".$_POST["NEW_GOOGLEMAP_MAP_TYPE"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_MIN_ZOOM\s*=\s*".*";/', "\$GOOGLEMAP_MIN_ZOOM = \"".$_POST["NEW_GOOGLEMAP_MIN_ZOOM"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_MAX_ZOOM\s*=\s*".*";/', "\$GOOGLEMAP_MAX_ZOOM = \"".$_POST["NEW_GOOGLEMAP_MAX_ZOOM"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_XSIZE\s*=\s*".*";/', "\$GOOGLEMAP_XSIZE = \"".$_POST["NEW_GOOGLEMAP_XSIZE"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_YSIZE\s*=\s*".*";/', "\$GOOGLEMAP_YSIZE = \"".$_POST["NEW_GOOGLEMAP_YSIZE"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_PRECISION_0\s*=\s*".*";/', "\$GOOGLEMAP_PRECISION_0 = \"".$_POST["NEW_GOOGLEMAP_PRECISION_0"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_PRECISION_1\s*=\s*".*";/', "\$GOOGLEMAP_PRECISION_1 = \"".$_POST["NEW_GOOGLEMAP_PRECISION_1"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_PRECISION_2\s*=\s*".*";/', "\$GOOGLEMAP_PRECISION_2 = \"".$_POST["NEW_GOOGLEMAP_PRECISION_2"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_PRECISION_3\s*=\s*".*";/', "\$GOOGLEMAP_PRECISION_3 = \"".$_POST["NEW_GOOGLEMAP_PRECISION_3"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_PRECISION_4\s*=\s*".*";/', "\$GOOGLEMAP_PRECISION_4 = \"".$_POST["NEW_GOOGLEMAP_PRECISION_4"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_PRECISION_5\s*=\s*".*";/', "\$GOOGLEMAP_PRECISION_5 = \"".$_POST["NEW_GOOGLEMAP_PRECISION_5"]."\";", $configtext);
    $configtext = preg_replace('/\$GM_DEFAULT_TOP_VALUE\s*=\s*".*";/', "\$GM_DEFAULT_TOP_VALUE = \"".$_POST["NEW_DEFAULT_TOP_LEVEL"]."\";", $configtext);
    $configtext = preg_replace('/\$GM_MAX_NOF_LEVELS\s*=\s*".*";/', "\$GM_MAX_NOF_LEVELS = \"".$_POST["NEW_LEVEL_COUNT"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_COORD\s*=\s*".*";/', "\$GOOGLEMAP_COORD = \"".$_POST["NEW_GOOGLEMAP_COORD"]."\";", $configtext);
	//wooc place hierarchy
	$configtext = preg_replace('/\$GOOGLEMAP_PLACE_HIERARCHY\s*=\s*".*";/', "\$GOOGLEMAP_PLACE_HIERARCHY = \"".$_POST["NEW_GOOGLEMAP_PLACE_HIERARCHY"]."\";", $configtext);
	$configtext = preg_replace('/\$GOOGLEMAP_PH_XSIZE\s*=\s*".*";/', "\$GOOGLEMAP_PH_XSIZE = \"".$_POST["NEW_GOOGLEMAP_PH_XSIZE"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_PH_YSIZE\s*=\s*".*";/', "\$GOOGLEMAP_PH_YSIZE = \"".$_POST["NEW_GOOGLEMAP_PH_YSIZE"]."\";", $configtext);
	$configtext = preg_replace('/\$GOOGLEMAP_PH_MARKER\s*=\s*".*";/', "\$GOOGLEMAP_PH_MARKER = \"".$_POST["NEW_GOOGLEMAP_PH_MARKER"]."\";", $configtext);
	$configtext = preg_replace('/\$GM_DISP_SHORT_PLACE\s*=\s*".*";/', "\$GM_DISP_SHORT_PLACE = \"".$_POST["NEW_GM_DISP_SHORT_PLACE"]."\";", $configtext);
	$configtext = preg_replace('/\$GOOGLEMAP_PH_WHEEL\s*=\s*".*";/', "\$GOOGLEMAP_PH_WHEEL = \"".$_POST["NEW_GOOGLEMAP_PH_WHEEL"]."\";", $configtext);
	$configtext = preg_replace('/\$GOOGLEMAP_PH_CONTROLS\s*=\s*".*";/', "\$GOOGLEMAP_PH_CONTROLS = \"".$_POST["NEW_GOOGLEMAP_PH_CONTROLS"]."\";", $configtext);
	$configtext = preg_replace('/\$GM_DISP_COUNT\s*=\s*".*";/', "\$GM_DISP_COUNT = \"".$_POST["NEW_GM_DISP_COUNT"]."\";", $configtext);

    for($i = 1; $i <= 9; $i++) {
        $configtext = preg_replace('/\$GM_PREFIX\['.$i.'\]\s*=\s*".*";/', '\$GM_PREFIX['.$i.'] = "'.$_POST["NEW_NAME_PREFIX_".$i].'";', $configtext);
        $configtext = preg_replace('/\$GM_POSTFIX\['.$i.'\]\s*=\s*".*";/', '\$GM_POSTFIX['.$i.'] = "'.$_POST["NEW_NAME_POSTFIX_".$i].'";', $configtext);
        $configtext = preg_replace('/\$GM_PRE_POST_MODE\['.$i.'\]\s*=\s*".*";/', '\$GM_PRE_POST_MODE['.$i.'] = "'.$_POST["NEW_PRE_POST_LEVEL_".$i].'";', $configtext);
    }

    $res = @eval($configtext);
    if ($res===false) {
        $fp = fopen("modules/googlemap/config.php", "wb");
        if (!$fp) {
            print "<span class=\"error\">";
            print $pgv_lang["pgv_config_write_error"];
            print "<br /></span>\n";
        }
        else {
            fwrite($fp, $configtext);
            fclose($fp);
            $logline = AddToLog("Googlemap config updated");
            // read the config file again, to set the vars
            require("modules/googlemap/config.php");
        }
    }
}

$i = 0;

?>
<script language="JavaScript" type="text/javascript">
<!--
	var helpWin;
	function helpPopup(which) {
		if ((!helpWin)||(helpWin.closed)) helpWin = window.open('module.php?mod=googlemap&pgvaction=editconfig_help&help='+which,'_blank','left=50,top=50,width=500,height=320,resizable=1,scrollbars=1');
		else helpWin.location = 'modules/googlemap/editconfig_help.php?help='+which;
		return false;
	}
	function getHelp(which) {
		if ((helpWin)&&(!helpWin.closed)) helpWin.location='module.php?mod=googlemap&pgvaction=editconfig_help&help='+which;
	}

	function closeHelp() {
		if (helpWin) helpWin.close();
	}

    function showSelectedLevels() {
        if (document.configform.NEW_LEVEL_COUNT.value >= 1) {
            document.getElementById('level1').style.display = 'block';
        }
        else {
            document.getElementById('level1').style.display = 'none';
        }
        if (document.configform.NEW_LEVEL_COUNT.value >= 2) {
            document.getElementById('level2').style.display = 'block';
        }
        else {
            document.getElementById('level2').style.display = 'none';
        }
        if (document.configform.NEW_LEVEL_COUNT.value >= 3) {
            document.getElementById('level3').style.display = 'block';
        }
        else {
            document.getElementById('level3').style.display = 'none';
        }
        if (document.configform.NEW_LEVEL_COUNT.value >= 4) {
            document.getElementById('level4').style.display = 'block';
        }
        else {
            document.getElementById('level4').style.display = 'none';
        }
        if (document.configform.NEW_LEVEL_COUNT.value >= 5) {
            document.getElementById('level5').style.display = 'block';
        }
        else {
            document.getElementById('level5').style.display = 'none';
        }
        if (document.configform.NEW_LEVEL_COUNT.value >= 6) {
            document.getElementById('level6').style.display = 'block';
        }
        else {
            document.getElementById('level6').style.display = 'none';
        }
        if (document.configform.NEW_LEVEL_COUNT.value >= 7) {
            document.getElementById('level7').style.display = 'block';
        }
        else {
            document.getElementById('level7').style.display = 'none';
        }
        if (document.configform.NEW_LEVEL_COUNT.value >= 8) {
            document.getElementById('level8').style.display = 'block';
        }
        else {
            document.getElementById('level8').style.display = 'none';
        }
        if (document.configform.NEW_LEVEL_COUNT.value >= 9) {
            document.getElementById('level9').style.display = 'block';
        }
        else {
            document.getElementById('level9').style.display = 'none';
        }
	}

	//-->
</script>

<form method="post" name="configform" action="module.php?mod=googlemap&pgvaction=editconfig">
<input type="hidden" name="action" value="update" />

    <table class="facts_table">
    <tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_ENABLE_help", "qm", "GOOGLEMAP_ENABLE"); print $pgv_lang["googlemap_enable"];?></td>
        <td class="optionbox"><select name="NEW_GOOGLEMAP_ENABLE" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_ENABLED_help');">
                <option value="false" <?php if ($GOOGLEMAP_ENABLED=="false") print "selected=\"selected\""; ?>><?php print $pgv_lang["no"];?></option>
                <option value="true" <?php if ($GOOGLEMAP_ENABLED=="true") print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"];?></option>
            </select>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_API_KEY_help", "qm", "GOOGLEMAP_API_KEY"); print $pgv_lang["googlemapkey"];?></td>
        <td class="optionbox"><input type="text" name="NEW_GOOGLEMAP_API_KEY" value="<?php print $GOOGLEMAP_API_KEY;?>" size="60" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_API_KEY_help');" /></td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_MAP_TYPE_help", "qm", "GOOGLEMAP_MAP_TYPE"); print $pgv_lang["gm_map_type"];?></td>
        <td class="optionbox"><select name="NEW_GOOGLEMAP_MAP_TYPE" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_MAP_TYPE_help');">
                <option value="G_NORMAL_MAP" <?php if ($GOOGLEMAP_MAP_TYPE=="G_NORMAL_MAP") print "selected=\"selected\""; ?>><?php print $pgv_lang["gm_map"];?></option>
                <option value="G_SATELLITE_MAP" <?php if ($GOOGLEMAP_MAP_TYPE=="G_SATELLITE_MAP") print "selected=\"selected\""; ?>><?php print $pgv_lang["gm_satellite"];?></option>
                <option value="G_HYBRID_MAP" <?php if ($GOOGLEMAP_MAP_TYPE=="G_HYBRID_MAP") print "selected=\"selected\""; ?>><?php print $pgv_lang["gm_hybrid"];?></option>
				<option value="G_PHYSICAL_MAP" <?php if ($GOOGLEMAP_MAP_TYPE=="G_PHYSICAL_MAP") print "selected=\"selected\""; ?>><?php print $pgv_lang["gm_physical"];?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_MAP_SIZE_help", "qm", "GOOGLEMAP_MAP_SIZE"); print $pgv_lang["gm_map_size"];?></td>
        <td class="optionbox">
            <?php print $pgv_lang["gm_map_size_x"]; ?>
            <input type="text" name="NEW_GOOGLEMAP_XSIZE" value="<?php print $GOOGLEMAP_XSIZE;?>" size="10" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_MAP_SIZE_help');" />
            <?php print $pgv_lang["gm_map_size_y"]; ?>
            <input type="text" name="NEW_GOOGLEMAP_YSIZE" value="<?php print $GOOGLEMAP_YSIZE;?>" size="10" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_MAP_SIZE_help');" />
        </td>
    </tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("GOOGLEMAP_PH_help", "qm", "GOOGLEMAP_PH"); print $pgv_lang["gm_place_hierarchy"];?></td>
        <td class="optionbox"><select name="NEW_GOOGLEMAP_PLACE_HIERARCHY" tabindex="<?php $i++; print $i?>;">
                <option value="false" <?php if ($GOOGLEMAP_PLACE_HIERARCHY=="false") print "selected=\"selected\""; ?>><?php print $pgv_lang["no"];?></option>
                <option value="true" <?php if ($GOOGLEMAP_PLACE_HIERARCHY=="true") print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"];?></option>
            </select>
		</td>
	</tr>
	<tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_PH_MAP_SIZE_help", "qm", "GOOGLEMAP_PH_MAP_SIZE"); print $pgv_lang["gm_ph_map_size"];?></td>
        <td class="optionbox">
            <?php print $pgv_lang["gm_map_size_x"]; ?>
            <input type="text" name="NEW_GOOGLEMAP_PH_XSIZE" value="<?php print $GOOGLEMAP_PH_XSIZE;?>" size="10" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PH_MAP_SIZE_help');" />
            <?php print $pgv_lang["gm_map_size_y"]; ?>
            <input type="text" name="NEW_GOOGLEMAP_PH_YSIZE" value="<?php print $GOOGLEMAP_PH_YSIZE;?>" size="10" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PH_MAP_SIZE_help');" />
        </td>
    </tr>
	<tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_PH_MARKER_help", "qm", "GOOGLEMAP_PH_MARKER"); print $pgv_lang["gm_ph_marker_type"];?></td>
        <td class="optionbox"><select name="NEW_GOOGLEMAP_PH_MARKER" tabindex="<?php $i++; print $i?>;">
                <option value="G_DEFAULT_ICON" <?php if ($GOOGLEMAP_PH_MARKER=="G_DEFAULT_ICON") print "selected=\"selected\""; ?>><?php print $pgv_lang["gm_standard_marker"];?></option>
                <option value="G_FLAG" <?php if ($GOOGLEMAP_PH_MARKER=="G_FLAG") print "selected=\"selected\""; ?>><?php print $pgv_lang["pl_flag"];?></option>
            </select>
		</td>
    </tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("GM_DISP_SHORT_PLACE_help", "qm", "GM_DISP_SHORT_PLACE"); print $pgv_lang["gm_ph_placenames"];?></td>
        <td class="optionbox"><select name="NEW_GM_DISP_SHORT_PLACE" tabindex="<?php $i++; print $i?>;">
                <option value="false" <?php if ($GM_DISP_SHORT_PLACE=="false") print "selected=\"selected\""; ?>><?php print $pgv_lang["no"];?></option>
                <option value="true" <?php if ($GM_DISP_SHORT_PLACE=="true") print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"];?></option>
            </select>
		</td>
    </tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("GM_DISP_COUNT_help", "qm", "GM_DISP_COUNT"); print $pgv_lang["gm_ph_count"];?></td>
        <td class="optionbox"><select name="NEW_GM_DISP_COUNT" tabindex="<?php $i++; print $i?>;">
                <option value="false" <?php if ($GM_DISP_COUNT=="false") print "selected=\"selected\""; ?>><?php print $pgv_lang["no"];?></option>
                <option value="true" <?php if ($GM_DISP_COUNT=="true") print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"];?></option>
            </select>
		</td>
    </tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("GOOGLEMAP_PH_WHEEL_help", "qm", "GOOGLEMAP_PH_WHEEL"); print $pgv_lang["gm_ph_wheel"];?></td>
        <td class="optionbox"><select name="NEW_GOOGLEMAP_PH_WHEEL" tabindex="<?php $i++; print $i?>;">
                <option value="false" <?php if ($GOOGLEMAP_PH_WHEEL=="false") print "selected=\"selected\""; ?>><?php print $pgv_lang["no"];?></option>
                <option value="true" <?php if ($GOOGLEMAP_PH_WHEEL=="true") print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"];?></option>
            </select>
		</td>
    </tr>
	<tr>
		<td class="descriptionbox"><?php print_help_link("GOOGLEMAP_PH_CONTROLS_help", "qm", "GOOGLEMAP_PH_CONTROLS"); print $pgv_lang["gm_ph_controls"];?></td>
        <td class="optionbox"><select name="NEW_GOOGLEMAP_PH_CONTROLS" tabindex="<?php $i++; print $i?>;">
                <option value="false" <?php if ($GOOGLEMAP_PH_CONTROLS=="false") print "selected=\"selected\""; ?>><?php print $pgv_lang["no"];?></option>
                <option value="true" <?php if ($GOOGLEMAP_PH_CONTROLS=="true") print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"];?></option>
            </select>
		</td>
    </tr>
	<tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_COORD_help", "qm", "GOOGLEMAP_COORD"); print $pgv_lang["googlemap_coord"];?></td>
        <td class="optionbox"><select name="NEW_GOOGLEMAP_COORD" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_COORD_help');">
                <option value="false" <?php if ($GOOGLEMAP_COORD=="false") print "selected=\"selected\""; ?>><?php print $pgv_lang["no"];?></option>
                <option value="true" <?php if ($GOOGLEMAP_COORD=="true") print "selected=\"selected\""; ?>><?php print $pgv_lang["yes"];?></option>
            </select>
		</td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_MAP_ZOOM_help", "qm", "GOOGLEMAP_MAP_ZOOM"); print $pgv_lang["gm_map_zoom"];?></td>
        <td class="optionbox">
                <?php print $pgv_lang["gm_min"];?>: <select name="NEW_GOOGLEMAP_MIN_ZOOM" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_MAP_ZOOM_help');">
                <?php for ($j=1; $j < 15; $j++) { ?>
                <option value="<?php print $j."\""; if ($GOOGLEMAP_MIN_ZOOM==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>
                <?php print $pgv_lang["gm_max"];?>: <select name="NEW_GOOGLEMAP_MAX_ZOOM" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_MAP_ZOOM_help');">
                <?php for ($j=1; $j < 15; $j++) { ?>
                <option value="<?php print $j."\""; if ($GOOGLEMAP_MAX_ZOOM==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_PRECISION_help", "qm", "GOOGLEMAP_PRECISION"); print $pgv_lang["pl_precision"];?></td>
        <td class="optionbox">
            <table><tr>
            <td><?php print $pgv_lang["pl_country"];?>&nbsp;&nbsp;</td>
            <td><select name="NEW_GOOGLEMAP_PRECISION_0" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PRECISION_help');">
                <?php for ($j=0; $j < 10; $j++) { ?>
                <option value="<?php print $j;?>"<?php if ($GOOGLEMAP_PRECISION_0==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;<?php print $pgv_lang["gm_digits"];?></td></tr>
            <tr><td><?php print $pgv_lang["pl_state"];?>&nbsp;&nbsp;</td>
            <td><select name="NEW_GOOGLEMAP_PRECISION_1" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PRECISION_help');">
                <?php for ($j=0; $j < 10; $j++) { ?>
                <option value="<?php print $j;?>"<?php if ($GOOGLEMAP_PRECISION_1==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;<?php print $pgv_lang["gm_digits"];?></td></tr>
            <tr><td><?php print $pgv_lang["pl_city"];?>&nbsp;&nbsp;</td>
            <td><select name="NEW_GOOGLEMAP_PRECISION_2" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PRECISION_help');">
                <?php for ($j=0; $j < 10; $j++) { ?>
                <option value="<?php print $j;?>"<?php if ($GOOGLEMAP_PRECISION_2==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;<?php print $pgv_lang["gm_digits"];?></td></tr>
            <tr><td><?php print $pgv_lang["pl_neighborhood"];?>&nbsp;&nbsp;</td>
            <td><select name="NEW_GOOGLEMAP_PRECISION_3" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PRECISION_help');">
                <?php for ($j=0; $j < 10; $j++) { ?>
                <option value="<?php print $j;?>"<?php if ($GOOGLEMAP_PRECISION_3==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;<?php print $pgv_lang["gm_digits"];?></td></tr>
            <tr><td><?php print $pgv_lang["pl_house"];?>&nbsp;&nbsp;</td>
            <td><select name="NEW_GOOGLEMAP_PRECISION_4" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PRECISION_help');">
                <?php for ($j=0; $j < 10; $j++) { ?>
                <option value="<?php print $j;?>"<?php if ($GOOGLEMAP_PRECISION_4==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;<?php print $pgv_lang["gm_digits"];?></td></tr>
            <tr><td><?php print $pgv_lang["pl_max"];?>&nbsp;&nbsp;</td>
            <td><select name="NEW_GOOGLEMAP_PRECISION_5" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PRECISION_help');">
                <?php for ($j=0; $j < 10; $j++) { ?>
                <option value="<?php print $j;?>"<?php if ($GOOGLEMAP_PRECISION_5==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;<?php print $pgv_lang["gm_digits"];?></td></tr>
            </tr></table>
        </td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("GM_DEFAULT_LEVEL_0_help", "qm", "GM_DEFAULT_LEVEL_0"); print $pgv_lang["gm_default_level0"];?></td>
        <td class="optionbox"><input type="text" name="NEW_DEFAULT_TOP_LEVEL" value="<?php print $GM_DEFAULT_TOP_VALUE;?>" size="20" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GM_DEFAULT_LEVEL_0_help');" /></td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("GM_NOF_LEVELS_help", "qm", "GM_NOF_LEVELS"); print $pgv_lang["gm_nof_levels"];?></td>
        <td class="optionbox">
            <select name="NEW_LEVEL_COUNT" dir="ltr" tabindex="<?php $i++; print $i?>" onchange="showSelectedLevels()">
                <option value="1"<?php if ($GM_MAX_NOF_LEVELS == 1) print " selected=\"selected\"";?>>1</option>
                <option value="2"<?php if ($GM_MAX_NOF_LEVELS == 2) print " selected=\"selected\"";?>>2</option>
                <option value="3"<?php if ($GM_MAX_NOF_LEVELS == 3) print " selected=\"selected\"";?>>3</option>
                <option value="4"<?php if ($GM_MAX_NOF_LEVELS == 4) print " selected=\"selected\"";?>>4</option>
                <option value="5"<?php if ($GM_MAX_NOF_LEVELS == 5) print " selected=\"selected\"";?>>5</option>
                <option value="6"<?php if ($GM_MAX_NOF_LEVELS == 6) print " selected=\"selected\"";?>>6</option>
                <option value="7"<?php if ($GM_MAX_NOF_LEVELS == 7) print " selected=\"selected\"";?>>7</option>
                <option value="8"<?php if ($GM_MAX_NOF_LEVELS == 8) print " selected=\"selected\"";?>>8</option>
                <option value="9"<?php if ($GM_MAX_NOF_LEVELS == 9) print " selected=\"selected\"";?>>9</option>
            </select>
        </td>
    </tr>
    <tr>
        <td class="descriptionbox">
            <?php print $pgv_lang["gm_config_per_level"];?>
            </div>
        </td>
        <td class="optionbox">
            <?php
                print_level_config_table(1, $i);
                print_level_config_table(2, $i);
                print_level_config_table(3, $i);
                print_level_config_table(4, $i);
                print_level_config_table(5, $i);
                print_level_config_table(6, $i);
                print_level_config_table(7, $i);
                print_level_config_table(8, $i);
                print_level_config_table(9, $i);
            ?>
            </div>
        </td>
    </tr>
    </table>
    <table class="facts_table">
    <tr>
        <td class="descriptionbox" colspan="2" align="center">
            <a href="module.php?mod=googlemap&pgvaction=places"><?php print $pgv_lang["edit_place_locations"];?></a>
        </td>

    <tr>
        <td class="descriptionbox" colspan="2" align="center">
            <input type="submit" tabindex="<?php $i++; print $i?>" value="<?php print $pgv_lang["save_config"];?>" onclick="closeHelp();" />
            &nbsp;&nbsp;
            <input type="reset" tabindex="<?php $i++; print $i?>" value="<?php print $pgv_lang["reset"];?>" />
        </td>

    </tr>
    </table>
</form>
<?php
if(empty($SEARCH_SPIDER))
    print_footer();
else {
    print $pgv_lang["label_search_engine_detected"].": ".$SEARCH_SPIDER;
    print "\n</div>\n\t</body>\n</html>";
}
?>
