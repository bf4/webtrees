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

require './includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';

// Only admin users can access this page
if (!WT_USER_GEDCOM_ADMIN) {
	header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH.'login.php?url='.WT_SCRIPT_NAME);
	exit;
}

$earliest=WT_DB::prepare("SELECT DATE(MIN(log_time)) FROM `##log`")->execute(array())->fetchOne();
$latest  =WT_DB::prepare("SELECT DATE(MAX(log_time)) FROM `##log`")->execute(array())->fetchOne();

// Filtering
$action=safe_GET('action');
$from  =safe_GET('from', '\d\d\d\d-\d\d-\d\d', $earliest);
$to    =safe_GET('to',   '\d\d\d\d-\d\d-\d\d', $latest);
$type  =safe_GET('type', array('auth','change','config','debug','edit','error','media','search'));
$text  =safe_GET('text');
$ip    =safe_GET('ip');
$user  =safe_GET('user');
if (WT_USER_IS_ADMIN) {
	// Administrators can see all logs
	$gedc=safe_GET('gedc');
} else {
	// Managers can only see logs relating to this gedcom
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

$SELECT1=
	"SELECT SQL_CACHE SQL_CALC_FOUND_ROWS log_time, log_type, log_message, ip_address, IFNULL(user_name, '<none>') AS user_name, IFNULL(gedcom_name, '<none>') AS gedcom_name".
	" FROM `##log`".
	" LEFT JOIN `##user`   USING (user_id)".   // user may be deleted
	" LEFT JOIN `##gedcom` USING (gedcom_id)"; // gedcom may be deleted
$SELECT2=
	"SELECT COUNT(*) FROM `##log`".
	" LEFT JOIN `##user`   USING (user_id)".   // user may be deleted
	" LEFT JOIN `##gedcom` USING (gedcom_id)"; // gedcom may be deleted
if ($query) {
	$WHERE=" WHERE ".implode(' AND ', $query);
} else {
	$WHERE='';
}

switch($action) {
case 'delete':
	$DELETE=
		"DELETE `##log` FROM `##log`".
		" LEFT JOIN `##user`   USING (user_id)".   // user may be deleted
		" LEFT JOIN `##gedcom` USING (gedcom_id)". // gedcom may be deleted
		$WHERE;
	WT_DB::prepare($DELETE)->execute($args);
	break;
case 'export':
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="webtrees-logs.csv"');
	$rows=WT_DB::prepare($SELECT1.$WHERE.' ORDER BY log_id')->execute($args)->fetchAll();
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
case 'load_json':
	$iDisplayStart =(int)safe_GET('iDisplayStart');
	$iDisplayLength=(int)safe_GET('iDisplayLength');
	if ($iDisplayLength>0) {
		$LIMIT=" LIMIT " . $iDisplayStart . ',' . $iDisplayLength;
	} else {
		$LIMIT="";
	}
	$iSortingCols=safe_GET('iSortingCols');
	if ($iSortingCols) {
		$ORDER_BY=' ORDER BY ';
		for ($i=0; $i<$iSortingCols; ++$i) {
			// Datatables numbers columns 0, 1, 2, ...
			// MySQL numbers columns 1, 2, 3, ...
			switch (safe_GET('sSortDir_'.$i)) {
			case 'asc':
				$ORDER_BY.=(1+(int)safe_GET('iSortCol_'.$i)).' ASC ';
				break;
			case 'desc':
				$ORDER_BY.=(1+(int)safe_GET('iSortCol_'.$i)).' DESC ';
				break;
			}
			if ($i<$iSortingCols-1) {
				$ORDER_BY.=',';
			}
		}
	} else {
		$ORDER_BY='1 DESC';
	}

	// This becomes a JSON list, not array, so need to fetch with numeric keys.
	$aaData=WT_DB::prepare($SELECT1.$WHERE.$ORDER_BY.$LIMIT)->execute($args)->fetchAll(PDO::FETCH_NUM);
	
	// Total filtered/unfiltered rows
	$iTotalDisplayRecords=WT_DB::prepare("SELECT FOUND_ROWS()")->fetchColumn();
	$iTotalRecords=WT_DB::prepare($SELECT2.$WHERE)->execute($args)->fetchColumn();

	header('Content-type: application/json; charset=utf8');	
	echo json_encode(array( // See http://www.datatables.net/usage/server-side
		'sEcho'               =>(int)safe_GET('sEcho'),
		'iTotalRecords'       =>$iTotalRecords,
		'iTotalDisplayRecords'=>$iTotalDisplayRecords,
		'aaData'              =>$aaData
	));
	exit;
}

print_header(i18n::translate('Logs'));
echo WT_JS_START;

?>
	jQuery(document).ready(function(){
		var oTable = jQuery('#log_list').dataTable( {
			"sDom": '<"H"lpr>t<"F"i>',
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "<?php echo WT_SERVER_NAME.WT_SCRIPT_PATH.WT_SCRIPT_NAME.'?action=load_json&from=', $from,'&to=', $to, '&type=', $type, '&text=', rawurlencode($text), '&ip=', rawurlencode($ip), '&user=', rawurlencode($user), '&gedc=', rawurlencode($gedc); ?>",
			"oLanguage": {
				"sLengthMenu": 'Display <select><option value="10">10</option><option value="20">20</option><option value="50">50</option><option value="100">100</option></select> records'
			},
			"bJQueryUI": true,
			"bAutoWidth":false,
			"aaSorting": [[ 1, "desc" ]],
			"iDisplayLength": 20,
			"sPaginationType": "full_numbers",
		});
	});

<?php

$url=
	WT_SCRIPT_NAME.'?from='.rawurlencode($from).
	'&amp;to='.rawurlencode($to).
	'&amp;type='.rawurlencode($type).
	'&amp;text='.rawurlencode($text).
	'&amp;ip='.rawurlencode($ip).
	'&amp;user='.rawurlencode($user).
	'&amp;gedc='.rawurlencode($gedc);

echo
	WT_JS_END,
	'<form name="logs" method="get" action="'.WT_SCRIPT_NAME.'">',
		'<input type="hidden" name="action", value="show"/>',
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
					'<input type="submit" value="', i18n::translate('Filter'), '" />',
					'<br/>',
					'<input type="submit" value="', i18n::translate('Export'), '" onclick="document.logs.action.value=\'export\';return true;" ', ($action ? '' : 'disabled="disabled"'),' />',
					'<br/>',
					'<input type="submit" value="', i18n::translate('Delete'), '" onclick="if (confirm(\'', htmlspecialchars(i18n::translate('Permanently delete these records?')) , '\')) {document.logs.action.value=\"export\";return true;} else {return false;}" ', ($action ? '' : 'disabled="disabled"'),' />',
				'</td>',
			'</tr>',
		'</table>',
	'</form>';

if ($action) {
	echo
		'<br/>',
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
			'<tbody>',
	 	'</tbody>',
		'</table>';
}

print_footer();
