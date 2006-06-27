<?php
/**
 * Online UI for editing config.php site configuration variables
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
 * This Page Is Valid XHTML 1.0 Transitional! > 17 September 2005
 *
 * @package PhpGedView
 * @subpackage GoogleMap
 * @see config.php
 * @version $Id: editconfig.php,v$
 */

//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"menu.php")) {
    print "Now, why would you want to do that.  You're not hacking are you?";
    exit;
}

require('modules/googlemap/defaultconfig.php');
if (file_exists('modules/googlemap/config.php')) require('modules/googlemap/config.php');

require( $pgv_language["english"]);
if (file_exists( $pgv_language[$LANGUAGE])) require  $pgv_language[$LANGUAGE];
require $confighelpfile["english"];
if (file_exists($confighelpfile[$LANGUAGE])) require $confighelpfile[$LANGUAGE];
require $helptextfile["english"];
if (file_exists($helptextfile[$LANGUAGE])) require $helptextfile[$LANGUAGE];

require( "modules/googlemap/".$pgv_language["english"]);
if (file_exists( "modules/googlemap/".$pgv_language[$LANGUAGE])) require  "modules/googlemap/".$pgv_language[$LANGUAGE];
require( "modules/googlemap/".$helptextfile["english"]);
if (file_exists("modules/googlemap/".$helptextfile[$LANGUAGE])) require "modules/googlemap/".$helptextfile[$LANGUAGE];

print_header($pgv_lang["configure_googlemap"]);

print "<span class=\"subheaders\">".$pgv_lang["configure_googlemap"]."</span>";

if (!userIsAdmin(getUserName())) {
    print "<table class=\"facts_table\">\n";
    print "<tr><td colspan=\"2\" class=\"facts_value\">".$pgv_lang["gm_admin_error"];
    print "</td></tr></table>\n";
    print "<br><br><br>\n";
    print_footer();
    exit;
}

if ($action=="update" && !isset($security_user)) {
    if (!isset($_POST)) $_POST = $HTTP_POST_VARS;
    $configtext = implode('', file("modules/googlemap/defaultconfig.php"));
    $configtext = preg_replace('/\$GOOGLEMAP_API_KEY\s*=\s*".*";/', "\$GOOGLEMAP_API_KEY = \"".$_POST["NEW_GOOGLEMAP_API_KEY"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_MAP_TYPE\s*=\s*".*";/', "\$GOOGLEMAP_MAP_TYPE = \"".$_POST["NEW_GOOGLEMAP_MAP_TYPE"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_MIN_ZOOM\s*=\s*".*";/', "\$GOOGLEMAP_MIN_ZOOM = \"".$_POST["NEW_GOOGLEMAP_MIN_ZOOM"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_MAX_ZOOM\s*=\s*".*";/', "\$GOOGLEMAP_MAX_ZOOM = \"".$_POST["NEW_GOOGLEMAP_MAX_ZOOM"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_XSIZE\s*=\s*".*";/', "\$GOOGLEMAP_XSIZE = \"".$_POST["NEW_GOOGLEMAP_XSIZE"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_YSIZE\s*=\s*".*";/', "\$GOOGLEMAP_YSIZE = \"".$_POST["NEW_GOOGLEMAP_YSIZE"]."\";", $configtext);
    $configtext = preg_replace('/\$GOOGLEMAP_PRECISION_0\s*=\s*".*";/', "\$GOOGLEMAP_PRECISION_0 = \"".$_POST["NEW_GOOGLEMAP_PRECISION_0"]."\";", $configtext, -1, $count);
    $configtext = preg_replace('/\$GOOGLEMAP_PRECISION_1\s*=\s*".*";/', "\$GOOGLEMAP_PRECISION_1 = \"".$_POST["NEW_GOOGLEMAP_PRECISION_1"]."\";", $configtext, -1, $count);
    $configtext = preg_replace('/\$GOOGLEMAP_PRECISION_2\s*=\s*".*";/', "\$GOOGLEMAP_PRECISION_2 = \"".$_POST["NEW_GOOGLEMAP_PRECISION_2"]."\";", $configtext, -1, $count);
    $configtext = preg_replace('/\$GOOGLEMAP_PRECISION_3\s*=\s*".*";/', "\$GOOGLEMAP_PRECISION_3 = \"".$_POST["NEW_GOOGLEMAP_PRECISION_3"]."\";", $configtext, -1, $count);
    $configtext = preg_replace('/\$GOOGLEMAP_PRECISION_4\s*=\s*".*";/', "\$GOOGLEMAP_PRECISION_4 = \"".$_POST["NEW_GOOGLEMAP_PRECISION_4"]."\";", $configtext, -1, $count);
    $configtext = preg_replace('/\$GOOGLEMAP_PRECISION_5\s*=\s*".*";/', "\$GOOGLEMAP_PRECISION_5 = \"".$_POST["NEW_GOOGLEMAP_PRECISION_5"]."\";", $configtext, -1, $count);

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
            $logline = AddToLog("Googlemap config updated by >".getUserName()."<");
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
	//-->
</script>

<form method="post" name="configform" action="module.php?mod=googlemap&pgvaction=editconfig">
<input type="hidden" name="action" value="update" />

    <table class="facts_table">
    <tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_API_KEY_help", "qm", "GOOGLEMAP_API_KEY"); print $pgv_lang["googlemapkey"];?></td>
        <td class="optionbox"><input type="text" dir="ltr" name="NEW_GOOGLEMAP_API_KEY" value="<?php print $GOOGLEMAP_API_KEY;?>" size="60" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_API_KEY_help');" /></td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_MAP_TYPE_help", "qm", "GOOGLEMAP_MAP_TYPE"); print $pgv_lang["gm_map_type"];?></td>
        <td class="optionbox"><select name="NEW_GOOGLEMAP_MAP_TYPE" dir="ltr" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_MAP_TYPE_help');">
                <option value="G_NORMAL_MAP" <?php if ($GOOGLEMAP_MAP_TYPE=="G_NORMAL_MAP") print "selected=\"selected\""; ?>><?php print $pgv_lang["gm_map"];?></option>
                <option value="G_SATELLITE_MAP" <?php if ($GOOGLEMAP_MAP_TYPE=="G_SATELLITE_MAP") print "selected=\"selected\""; ?>><?php print $pgv_lang["gm_satellite"];?></option>
                <option value="G_HYBRID_MAP" <?php if ($GOOGLEMAP_MAP_TYPE=="G_HYBRID_MAP") print "selected=\"selected\""; ?>><?php print $pgv_lang["gm_hybrid"];?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_MAP_SIZE_help", "qm", "GOOGLEMAP_MAP_SIZE"); print $pgv_lang["gm_map_size"];?></td>
        <td class="optionbox">
            <?php print $pgv_lang["gm_map_size_x"]; ?>
            <input type="text" dir="ltr" name="NEW_GOOGLEMAP_XSIZE" value="<?php print $GOOGLEMAP_XSIZE;?>" size="10" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_MAP_SIZE_help');" />
            <?php print $pgv_lang["gm_map_size_y"]; ?>
            <input type="text" dir="ltr" name="NEW_GOOGLEMAP_YSIZE" value="<?php print $GOOGLEMAP_YSIZE;?>" size="10" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_MAP_SIZE_help');" />
        </td>
    </tr>
    <tr>
        <td class="descriptionbox"><?php print_help_link("GOOGLEMAP_MAP_ZOOM_help", "qm", "GOOGLEMAP_MAP_ZOOM"); print $pgv_lang["gm_map_zoom"];?></td>
        <td class="optionbox">
            Min.: <select name="NEW_GOOGLEMAP_MIN_ZOOM" dir="ltr" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_MAP_ZOOM_help');">
                <?php for ($j=1; $j < 15; $j++) { ?>
                <option value="<?php print $j."\""; if ($GOOGLEMAP_MIN_ZOOM==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>
            Max.: <select name="NEW_GOOGLEMAP_MAX_ZOOM" dir="ltr" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_MAP_ZOOM_help');">
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
            <td><select name="NEW_GOOGLEMAP_PRECISION_0" dir="ltr" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PRECISION_help');">
                <?php for ($j=0; $j < 10; $j++) { ?>
                <option value="<?php print $j;?>"<?php if ($GOOGLEMAP_PRECISION_0==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;<?php print $pgv_lang["gm_digits"];?></td></tr>
            <tr><td><?php print $pgv_lang["pl_state"];?>&nbsp;&nbsp;</td>
            <td><select name="NEW_GOOGLEMAP_PRECISION_1" dir="ltr" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PRECISION_help');">
                <?php for ($j=0; $j < 10; $j++) { ?>
                <option value="<?php print $j;?>"<?php if ($GOOGLEMAP_PRECISION_1==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;<?php print $pgv_lang["gm_digits"];?></td></tr>
            <tr><td><?php print $pgv_lang["pl_city"];?>&nbsp;&nbsp;</td>
            <td><select name="NEW_GOOGLEMAP_PRECISION_2" dir="ltr" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PRECISION_help');">
                <?php for ($j=0; $j < 10; $j++) { ?>
                <option value="<?php print $j;?>"<?php if ($GOOGLEMAP_PRECISION_2==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;<?php print $pgv_lang["gm_digits"];?></td></tr>
            <tr><td><?php print $pgv_lang["pl_neighborhood"];?>&nbsp;&nbsp;</td>
            <td><select name="NEW_GOOGLEMAP_PRECISION_3" dir="ltr" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PRECISION_help');">
                <?php for ($j=0; $j < 10; $j++) { ?>
                <option value="<?php print $j;?>"<?php if ($GOOGLEMAP_PRECISION_3==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;<?php print $pgv_lang["gm_digits"];?></td></tr>
            <tr><td><?php print $pgv_lang["pl_house"];?>&nbsp;&nbsp;</td>
            <td><select name="NEW_GOOGLEMAP_PRECISION_4" dir="ltr" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PRECISION_help');">
                <?php for ($j=0; $j < 10; $j++) { ?>
                <option value="<?php print $j;?>"<?php if ($GOOGLEMAP_PRECISION_4==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;<?php print $pgv_lang["gm_digits"];?></td></tr>
            <tr><td><?php print $pgv_lang["pl_max"];?>&nbsp;&nbsp;</td>
            <td><select name="NEW_GOOGLEMAP_PRECISION_5" dir="ltr" tabindex="<?php $i++; print $i?>" onfocus="getHelp('GOOGLEMAP_PRECISION_help');">
                <?php for ($j=0; $j < 10; $j++) { ?>
                <option value="<?php print $j;?>"<?php if ($GOOGLEMAP_PRECISION_5==$j) print " selected=\"selected\""; print ">".$j;?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;<?php print $pgv_lang["gm_digits"];?></td></tr>
            </tr></table>
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
