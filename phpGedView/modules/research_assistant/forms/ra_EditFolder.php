<?php
/**
 * phpGedView Research Assistant Tool - ra_EditFolder
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
 * @version $Id: ra_EditFolder.php,v 1.4 2006/04/06 20:12:35 yalnifj Exp $
 * @author Jason Porter
 * @author Wade Lasson
 * @author Brandon Gagnon
 * @author Brian Kramer
 * @author Julian Gautier
 */
//-- security check, only allow access from module.php
if (strstr($_SERVER["SCRIPT_NAME"],"module.php")===false) {
	print "Now, why would you want to do that.  You're not hacking are you?";
	exit;
}
// Require our base class
require_once'ra_form.php';
/**
 * Edit Folder class for the editfolder form
 * 
 * @uses ra_form
 */
class ra_editfolder extends ra_form {
    /**
     * content 
     * 
     * @param mixed $folder_id The id of the folder to edit
     * @return mixed
     */
 	function content($folder_id) {
        // Obtain the global vars needed
 		global $pgv_lang, $TBLPREFIX;

		$fr_name="";
		$fr_description="";
		$fr_id="";
		$fr_parentFolder="";
		if (!empty($folder_id)) {
	        // Find the correct form
			$sql='select * from ' . $TBLPREFIX . 'folders where fr_id=\''.$folder_id.'\'';
			$res=dbquery($sql);
	
	        // Setup the form variables from the DB
			$res=$res->fetchRow(DB_FETCHMODE_ASSOC);
			$fr_name=$res['fr_name'];
			$fr_description=$res['fr_description'];
			stripslashes($fr_description);
			$fr_id=$res['fr_id'];
			$fr_parentFolder=$res['fr_parentid'];
		}

		$out='<script language="javascript" type="text/javascript"><!--';
		$out.= "\n";
		$out.='function OnSubmit(){';
		$out.= "\n";
		$out.='if(document.forms["updateFolder"].folderName.value.trim()==\'\') {';
		$out.= "\n";
		$out .= '{alert(\''.$pgv_lang['no_folder_name'].'\'); return false;';
		$out .= "\n";
		$out .= '}';
		$out.= "\n";	
		$out .= 'else{ return true; }';
		$out.= "\n";	
		$out.='}//--></script>';
		$out .= '<form method="post" action="module.php" name="updateFolder" onsubmit="return OnSubmit();">';
		$out .= '<input type="hidden" name="mod" value="research_assistant" />' .
				'<input type="hidden" name="action" value="updateFolder" />';
		$out .= '<input type="hidden" value="'.$fr_id.'" name="folderID" />';
		$out .= '<table align="center" class="list_table">';
		$out .= '<tr><th class="descriptionbox" colspan="2"><h2>';
		if (!empty($folder_id)) $out .= $pgv_lang['edit_folder'] . print_help_link("ra_edit_folder_help", "qm", '', false, true);
		else $out .= $pgv_lang['add_folder'] . print_help_link("ra_add_folder_help", "qm", '', false, true);
		$out .= '</h2></th></tr>';
		$out .=	'<tr><td class="optionbox">'.
			$pgv_lang['folder_name'].'</td><td class="descriptionbox"><input type="text" name="folderName" value="'.$fr_name.'"/></td></tr>'.
				'<tr><td class="optionbox">'.$pgv_lang['Parent_Folder:'].'</td><td class="descriptionbox"><select name="parentFolder">' .
				'<option value="null">'.$pgv_lang['No_Parent'].'</option>';
                
                // Grab name and id for the options
                $sql='select fr_name, fr_id from '.$TBLPREFIX.'folders';
                $res=dbquery($sql);
				while($folder=& $res->fetchRow(DB_FETCHMODE_ASSOC))
				{
					if($fr_parentFolder==$folder['fr_id'])
					{	
						$out.='<option value="'.$folder['fr_id'].'" selected="selected">'.$folder['fr_name'] . '</option>';
					}
					else
					{
					$out.='<option value="'.$folder['fr_id'].'">'.$folder['fr_name'] . '</option>';
					}
				}

                // Finish up
				$out.='</select></td></tr>';
				$out.='<tr><td class="optionbox">'.$pgv_lang['Folder_Description:'].'</td><td class="descriptionbox"><textarea name="folderDescription" cols="50" rows="10">'.stripslashes($fr_description).'</textarea></td></tr>';
				$out.='<tr><td colspan="2"><input type="submit" value="'.$pgv_lang['add'].'" /><input type="button" value="Delete" onclick="window.location=\'module.php?mod=research_assistant&amp;action=deletefolder&amp;folderid='.$fr_id.'\';" /><input type="reset" value="Reset"/></td></tr>';
				$out.='</table></form>';
		return $out;
 	}

    /**
     * Show the form to the user
     * 
     * @return object
     */
 	function display_form()
 	{
 		return $this;
 	}
 }
?>
