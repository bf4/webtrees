<?php
/**
 * Log viewer.
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
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
 * @subpackage Admin
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'admin_site_logs.php');
define('WT_THEME_DIR', 'themes/_administration/');
require './includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';
require WT_ROOT.'includes/functions/functions_admin.php';

// Only admin users can access this page
if (!WT_USER_GEDCOM_ADMIN) {
	header('Location: login.php?url='.WT_SCRIPT_NAME);
	exit;
}

$earliest=WT_DB::prepare("SELECT DATE(MIN(log_time)) FROM `##log`")->execute(array())->fetchOne();
$latest  =WT_DB::prepare("SELECT DATE(MAX(log_time)) FROM `##log`")->execute(array())->fetchOne();

// Filtering
$from=safe_GET('from', '\d\d\d\d-\d\d-\d\d', $earliest);
$to  =safe_GET('to',   '\d\d\d\d-\d\d-\d\d', $latest);
$type=safe_GET('type', array('auth','change','config','debug','edit','error','media','search'));
$text=safe_GET('text');
$ip  =safe_GET('ip');
$user=safe_GET('user');
if (WT_USER_IS_ADMIN) {
	// Site admins can see all logs
	$gedc=safe_GET('gedc');
} else {
	// Gedcom admins can only see logs relating to this gedcom
	$gedc=WT_GEDCOM;
}

$query=array();
$args =array();
if ($from) {
	$query[]='log_time>=?';
	$args []=$from;
}
if ($to) {
	$query[]='log_time<TIMESTAMPADD(DAY, 1 , ?)'; // before end of the day
	$args []=$to;
}
if ($type) {
	$query[]='log_type=?';
	$args []=$type;
}
if ($text) {
	$query[]="log_message LIKE CONCAT('%', ?, '%')";
	$args []=$text;
}
if ($ip) {
	$query[]="ip_address LIKE CONCAT('%', ?, '%')";
	$args []=$ip;
}
if ($user) {
	$query[]="user_name LIKE CONCAT('%', ?, '%')";
	$args []=$user;
}
if ($gedc) {
	$query[]="gedcom_name LIKE CONCAT('%', ?, '%')";
	$args []=$gedc;
}

$sql1=
	"SELECT COUNT(*)".
	" FROM `##log`".
	" LEFT JOIN `##user`   USING (user_id)".   // user may be deleted
	" LEFT JOIN `##gedcom` USING (gedcom_id)"; // gedcom may be deleted

$sql2=
	"SELECT log_time, log_type, log_message, ip_address, IFNULL(user_name, '<none>') AS user_name, IFNULL(gedcom_name, '<none>') AS gedcom_name".
	" FROM `##log`".
	" LEFT JOIN `##user`   USING (user_id)".   // user may be deleted
	" LEFT JOIN `##gedcom` USING (gedcom_id)"; // gedcom may be deleted

if ($query) {
	$sql1.=" WHERE ".implode(' AND ', $query);
	// Order ascending, otherwise the current OFFSET/LIMIT will change when new events are logged
	$sql2.=" WHERE ".implode(' AND ', $query)." ORDER BY log_id";
}

if (safe_GET('export', 'yes')=='yes') {
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="webtrees-logs.csv"');
	$rows=WT_DB::prepare($sql2)->execute($args)->fetchAll();
	foreach ($rows as $row) {
		echo
			'"', $row->log_time, '",',
			'"', $row->log_type, '",',
			'"', str_replace('"', '""', $row->log_message), '",',
			'"', $row->ip_address, '",',
			'"', str_replace('"', '""', $row->user_name), '",',
			'"', str_replace('"', '""', $row->gedcom_name), '"',
			"\n";
	}
	exit;
}

if (safe_GET('delete', 'yes')=='yes') {
	$sql3=
		"DELETE `##log` FROM `##log`".
		" LEFT JOIN `##user`   USING (user_id)".   // user may be deleted
		" LEFT JOIN `##gedcom` USING (gedcom_id)"; // gedcom may be deleted
	if ($query) {
		$sql3.=" WHERE ".implode(' AND ', $query);
	}
	WT_DB::prepare($sql3)->execute($args);
}

$total_rows=WT_DB::prepare($sql1)->execute($args)->fetchOne();

$rows=WT_DB::prepare($sql2)->execute($args)->fetchAll();

print_header(i18n::translate('Logs'));
?>
<script type="text/javascript">
	jQuery(document).ready(function(){

		var oTable = jQuery('#log_list').dataTable( {
			"oLanguage": {
				"sLengthMenu": 'Display <select><option value="10">10</option><option value="20">20</option><option value="30">30</option><option value="40">40</option><option value="50">50</option><option value="-1">All</option></select> records'
			},
			"bJQueryUI": true,
			"bAutoWidth":false,
			"aaSorting": [[ 1, "asc" ]],
			"iDisplayLength": 20,
			"sPaginationType": "full_numbers",
		});
		
	
	});

</script>
<?php
echo
	'<form name="logs" method="get" action="'.WT_SCRIPT_NAME.'">',
		'<table class="site_logs">',
			'<tr>',
				'<td>',
					// I18N: %s are both user-input date fields
					i18n::translate('From %s To %s', '<input name="from" size="8" value="'.htmlspecialchars($from).'" /><br />', '&nbsp;&nbsp;&nbsp;<input name="to" size="8" value="'.htmlspecialchars($to).'" />'),
				'</td>',
				'<td>',
					i18n::translate('Type'), '<br />', select_edit_control('type', array(''=>'', 'auth'=>'auth','config'=>'config','debug'=>'debug','edit'=>'edit','error'=>'error','media'=>'media','search'=>'search'), null, $type, ''),
				'</td>',
				'<td>',
					i18n::translate('Message'), '<br /><input name="text" size="12" value="', htmlspecialchars($text), '" /> ',
				'</td>',
				'<td>',
					i18n::translate('IP address'), '<br /><input name="ip" size="12" value="', htmlspecialchars($ip), '" /> ',
				'</td>',
				'<td>',
					i18n::translate('User'), '<br /><input name="user" size="12" value="', htmlspecialchars($user), '" /> ',
				'</td>',
				'<td>',
					i18n::translate('Gedcom'), '<br /><input name="gedc" size="12" value="', htmlspecialchars($gedc), '" ', WT_USER_IS_ADMIN ? '' : 'disabled', '/> ',
				'</td>',
				'<td class="button" rowspan="2">',
					'<input type="submit" value="', i18n::translate('Filter'), '"/>',
				'</td>',
			'</tr>',
		'</table>',
	'</form>';
if ($rows) {
echo
	'<p align="center">',
		i18n::translate('%d Results', $total_rows);
		$url=
			WT_SCRIPT_NAME.'?from='.rawurlencode($from).
			'&amp;to='.rawurlencode($to).
			'&amp;type='.rawurlencode($type).
			'&amp;text='.rawurlencode($text).
			'&amp;ip='.rawurlencode($ip).
			'&amp;user='.rawurlencode($user).
			'&amp;gedc='.rawurlencode($gedc);

//		if (WT_USER_IS_ADMIN) {
			echo '&nbsp;-&nbsp;<a href="', $url, '&amp;export=yes">', i18n::translate('Export'), '</a>';
			echo ' | <a href="', $url, '&amp;delete=yes" onclick="return confirm(\'', htmlspecialchars(i18n::plural('Permanently delete this %s record?', 'Permanently delete these %s records?', $total_rows, $total_rows)) , '\')">', i18n::translate('Delete'), '</a>';
//		}
echo
	'</p>',
	'<table id="log_list">',
		'<thead>',
			'<tr>',
				'<th>', i18n::translate('Timestamp'), '</th>',
				'<th>', i18n::translate('Type'), '</th>',
				'<th>', i18n::translate('Message'), '</th>',
				'<th>', i18n::translate('IP address'), '</th>',
				'<th>', i18n::translate('User'), '</th>',
				'<th>', i18n::translate('GEDCOM'), '</th>',
			'</tr>',
		'</thead>',
		'<tbody>';
			foreach ($rows as $row) {
				echo
					'<tr>',
						'<td>', $row->log_time, '</td>',
						'<td>', $row->log_type, '</td>',
						'<td>', nl2br(htmlspecialchars($row->log_message)), '</td>',
						'<td>', $row->ip_address, '</td>',
						'<td>', htmlspecialchars($row->user_name), '</td>',
						'<td>', htmlspecialchars($row->gedcom_name), '</td>',
					'</tr>';
			}
		echo '</tbody>',
	'</table>';
}
print_footer();
