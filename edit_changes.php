<?php
/**
 * Interface to moderate pending changes.
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
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
 * @package webtrees
 * @subpackage Edit
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'edit_changes.php');
require './includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';

if (!WT_USER_CAN_ACCEPT) {
	header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH.'login.php?url='.WT_SCRIPT_NAME);
	exit;
}

$action   =safe_GET('action');
$change_id=safe_GET('change_id');
$index    =safe_GET('index');
$ged      =safe_GET('ged');

print_simple_header(/* I18N: Moderate as a verb, not adjective  */ i18n::translate('Moderate pending changes'));
echo WT_JS_START;
?>
	function show_gedcom_record(xref) {
		var recwin = window.open("gedrecord.php?fromfile=1&pid="+xref, "_blank", "top=50, left=50, width=600, height=400, scrollbars=1, scrollable=1, resizable=1");
	}

	function showchanges() {
		window.location = '<?php echo WT_SCRIPT_NAME; ?>';
	}

	function show_diff(diffurl) {
		window.opener.location = diffurl;
		return false;
	}
<?php
echo WT_JS_END;
echo '<div class="center"><span class="subheaders">', i18n::translate('Moderate pending changes'), '</span><br /><br />';

switch ($action) {
case 'undo':
	$gedcom_id=WT_DB::prepare("SELECT gedcom_id FROM `##change` WHERE change_id=?")->execute(array($change_id))->fetchOne();
	$xref     =WT_DB::prepare("SELECT xref      FROM `##change` WHERE change_id=?")->execute(array($change_id))->fetchOne();
	// Undo a change, and subsequent changes to the same record
	WT_DB::prepare(
		"UPDATE `##change`".
		" SET   status     = 'rejected'".
		" WHERE status     = 'pending'".
		" AND   gedcom_id  = ?".
		" AND   xref       = ?".
		" AND   change_id >= ?"
	)->execute(array($gedcom_id, $xref, $change_id));
	break;
case 'accept':
	$gedcom_id=WT_DB::prepare("SELECT gedcom_id FROM `##change` WHERE change_id=?")->execute(array($change_id))->fetchOne();
	$xref     =WT_DB::prepare("SELECT xref      FROM `##change` WHERE change_id=?")->execute(array($change_id))->fetchOne();
	// Accept a change, and all previous changes to the same record
	$changes=WT_DB::prepare(
		"SELECT change_id, gedcom_id, gedcom_name, xref, old_gedcom, new_gedcom".
		" FROM  `##change` c".
		" JOIN  `##gedcom` g USING (gedcom_id)".
		" WHERE c.status   = 'pending'".
		" AND   gedcom_id  = ?".
		" AND   xref       = ?".
		" AND   change_id <= ?".
		" ORDER BY change_id"
	)->execute(array($gedcom_id, $xref, $change_id))->fetchAll();
	foreach ($changes as $change) {
		if (empty($change->new_gedcom)) {
			// delete
			update_record($change->old_gedcom, $ged_id, true);
		} else {
			// add/update
			update_record($change->new_gedcom, $ged_id, false);
		}
		WT_DB::prepare("UPDATE `##change` SET status='accepted' WHERE change_id=?")->execute(array($change->change_id));
		AddToLog("Accepted change {$change->change_id} for {$change->xref} / {$change->gedcom_name} into database", 'edit');
	}
	break;
case 'undoall':
	WT_DB::prepare(
		"UPDATE `##change`".
		" SET status='rejected'".
		" WHERE status='pending' AND gedcom_id=?"
	)->execute(array(get_id_from_gedcom($ged)));
	break;
case 'acceptall':
	$changes=WT_DB::prepare(
		"SELECT change_id, gedcom_id, gedcom_name, xref, old_gedcom, new_gedcom".
		" FROM `##change` c".
		" JOIN `##gedcom` g USING (gedcom_id)".
		" WHERE c.status='pending' AND gedcom_id=?".
		" ORDER BY change_id"
	)->execute(array(get_id_from_gedcom($ged)))->fetchAll();
	foreach ($changes as $change) {
		if (empty($change->new_gedcom)) {
			// delete
			update_record($change->old_gedcom, $ged_id, true);
		} else {
			// add/update
			update_record($change->new_gedcom, $ged_id, false);
		}
		WT_DB::prepare("UPDATE `##change` SET status='accepted' WHERE change_id=?")->execute(array($change->change_id));
		AddToLog("Accepted change {$change->change_id} for {$change->xref} / {$change->gedcom_name} into database", 'edit');
	}
	break;
}

$changed_gedcoms=WT_DB::prepare(
	"SELECT g.gedcom_name".
	" FROM `##change` c".
	" JOIN `##gedcom` g USING (gedcom_id)".
	" WHERE c.status='pending'".
	" GROUP BY g.gedcom_name"
)->fetchOneColumn();

if ($changed_gedcoms) {
	$changes=WT_DB::prepare(
		"SELECT c.*, u.user_name, u.real_name, g.gedcom_name, IF(new_gedcom='', old_gedcom, new_gedcom) AS gedcom".
		" FROM `##change` c".
		" JOIN `##user`   u USING (user_id)".
		" JOIN `##gedcom` g USING (gedcom_id)".
		" WHERE c.status='pending'".
		" ORDER BY gedcom_id, c.xref, c.change_id"
	)->fetchAll();

	$output = '<br /><br /><table class="list_table">';
	$prev_xref=null;
	$prev_gedcom_id=null;
	foreach ($changes as $change) {
		if ($change->xref!=$prev_xref || $change->gedcom_id!=$prev_gedcom_id) {
			if ($prev_xref) {
				$output.='</table></td></tr>';
			}
			$prev_xref     =$change->xref;
			$prev_gedcom_id=$change->gedcom_id;
			$output.='<tr><td class="list_value '.$TEXT_DIRECTION.'">';
			$GEDCOM=$change->gedcom_name;
			$record=GedcomRecord::getInstance($change->xref);
			if (!$record) {
				// When a record has been both added and deleted, then
				// neither the original nor latest version will exist.
				// This prevents us from displaying it...
				// This generates a record of some sorts from the last-but-one
				// version of the record.
				$record=new GedcomRecord($change->gedcom);
			}
			$output.='<b>'.PrintReady($record->getFullName()).'</b> '.getLRM().'('.$record->getXref().')'.getLRM().'<br />';
			$output.='<a href="javascript:;" onclick="return show_diff(\''.$record->getHtmlUrl().'&amp;show_changes=yes'.'\');">'.i18n::translate('View Change Diff').'</a> | ';
			$output.="<a href=\"javascript:show_gedcom_record('".$change->xref."');\">".i18n::translate('View GEDCOM Record')."</a> | ";
			$output.="<a href=\"javascript:;\" onclick=\"return edit_raw('".$change->xref."');\">".i18n::translate('Edit raw GEDCOM record').'</a><br />';
			$output.='<div class="indent">';
			$output.=i18n::translate('The following changes were made to this record:').'<br />';
			$output.='<table class="list_table"><tr>';
			$output.='<td class="list_label">'.i18n::translate('Accept').'</td>';
			$output.='<td class="list_label">'.i18n::translate('Type').'</td>';
			$output.='<td class="list_label">'.i18n::translate('User').'</td>';
			$output.='<td class="list_label">'.i18n::translate('Date').'</td>';
			$output.='<td class="list_label">'.i18n::translate('Family tree').'</td>';
			$output.='<td class="list_label">'.i18n::translate('Undo').'</td>';
			$output.='</tr>';
		}
		$output .= '<td class="list_value"><a href="edit_changes.php?action=accept&amp;ged='.rawurlencode($change->gedcom_name).'&amp;change_id='.$change->change_id.'">'.i18n::translate('Accept').'</a></td>';
		$output .= '<td class="list_value"><b>';
		if ($change->old_gedcom=='') {
			$output.=i18n::translate('Append record');
		} elseif ($change->new_gedcom=='') {
			$output.=i18n::translate('Delete record');
		} else {
			$output.=i18n::translate('Replace record');
		}
		echo '</b></td>';
		$output .= "<td class=\"list_value\"><a href=\"javascript:;\" onclick=\"return reply('".$change->user_name."', '".i18n::translate('Moderate pending changes')."')\" alt=\"".i18n::translate('Send Message')."\">";
		$output .= PrintReady($change->real_name);
		$output .= PrintReady('&nbsp;('.$change->user_name.')').'</a></td>';
		$output .= '<td class="list_value">'.$change->change_time.'</td>';
		$output .= '<td class="list_value">'.$change->gedcom_name.'</td>';
		$output .= '<td class="list_value"><a href="edit_changes.php?action=undo&amp;ged='.rawurlencode($change->gedcom_name).'&amp;change_id='.$change->change_id.'">'.i18n::translate('Undo').'</a></td>';
		$output.='</tr>';
	}
	$output .= '</table></td></tr></td></tr></table>';

	//-- Now for the global Action bar:
	$output2 = '<br /><table class="list_table">';
	// Row 1 column 1: title "Accept all"
	$output2 .= '<tr><td class="list_label">'.i18n::translate('Approve all changes').'</td>';
	// Row 1 column 2: title "Undo all"
	$output2 .= '<td class="list_label">'.i18n::translate('Undo all changes').'</td></tr>';

	// Row 2 column 1: action "Accept all"
	$output2 .= '<tr><td class="list_value">';
	$count = 0;
	foreach ($changed_gedcoms as $gedcom_name) {
		if ($count!=0) $output2.='<br />';
		$output2 .= '<a href="edit_changes.php?action=acceptall&amp;ged='.rawurlencode($gedcom_name).'">'.$gedcom_name.' - '.i18n::translate('Approve all changes').'</a>';
		$count ++;
	}
	$output2 .= '</td>';
	// Row 2 column 2: action "Undo all"
	$output2 .= '<td class="list_value">';
	$count = 0;
	foreach ($changed_gedcoms as $gedcom_name) {
		if ($count!=0) {
			$output2.='<br />';
		}
		$output2 .= '<a href="edit_changes.php?action=undoall&amp;ged='.rawurlencode($gedcom_name)."\" onclick=\"return confirm('".i18n::translate('Are you sure you want to undo all of the changes for this GEDCOM?')."');\">$gedcom_name - ".i18n::translate('Undo all changes').'</a>';
		$count++;
	}
	$output2 .= '</td></tr></table>';

	echo
		$output2, $output, $output2, '<br /><br />',
		'<a href="javascript:;" onclick="if (window.opener.showchanges) window.opener.showchanges(); window.close();">',
		i18n::translate('Close Window'),
		'</a>';
} else {
	// No pending changes - refresh the parent window and close this one
	echo
		WT_JS_START,
		'if (window.opener.showchanges)	window.opener.showchanges();',
		'window.close();',
		WT_JS_END;
}

echo '</div>';
print_simple_footer();
