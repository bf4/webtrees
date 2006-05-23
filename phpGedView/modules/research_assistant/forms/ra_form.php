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
 * @version $Id: ra_form.php,v 1.4 2006/04/06 20:12:35 yalnifj Exp $
 * @author Jason Porter
 */
//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"module.php")===false) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
/**
 * Base class for Research Assistant forms
 * 
 */
class ra_form {
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
}
