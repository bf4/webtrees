<?php
/**
 * 
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005	John Finlay and Others
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
 * @subpackage Charts
 * @version $Id: individual.php,v 1.252.2.98 2005/08/16 04:04:25 kosherjava Exp $
 */

require("config.php");
require("includes/functions_edit.php");

print_header('Fix Media Errors');
$manual_save = true;

print "Finding media errors in individuals...";
$indis = search_indis("[1-9]+ OBJE @.*@");
print " Found ".count($indis)."<br /><br />\n";

foreach($indis as $pid=>$indi) {
	print "Checking record ".$pid."... ";
	$newrec = "";
	$oldrecord = $indi['gedcom'];
	if (isset($pgv_changes[$pid."_".$GEDCOM])) $oldrecord = find_record_in_file($pid);
	$lines = preg_split("/[\r\n]+/", $oldrecord);
	for($i=0; $i<count($lines); $i++) {
		$line = $lines[$i];
		if (!empty($line)) {
			$fields = preg_split("/\s+/", $line);
			$level = $fields[0];
			if ($fields[1]!="OBJE") $newrec .= $line."\r\n";
			else {
				$newrec .= $line."\r\n";
				$oldi = $i;
				do {
					$i++;
					if (isset($lines[$i])) {
						$line = $lines[$i];
						$fields = preg_split("/\s+/", $line);
						$sublevel = $fields[0];
					}
				} while($sublevel>$level && $i<count($lines));
				if ($i!=$oldi && $i<count($lines)) $newrec .= $line."\r\n";
			}
		}
	}
	$newrec = trim($newrec);
	if ($newrec!=trim($oldrecord)) {
		print "Fixing record ".$pid."<br />\n";
		replace_gedrec($pid, $newrec, false);
	}
	else print "No changes needed for record ".$pid."<br />\n";
}

print "<br /><br />Finding media errors in families...";
$indis = search_fams("[1-9]+ OBJE @.*@");
print " Found ".count($indis)."<br /><br />\n";

foreach($indis as $pid=>$indi) {
	print "Checking record ".$pid."... ";
	$newrec = "";
	$oldrecord = $indi['gedcom'];
	if (isset($pgv_changes[$pid."_".$GEDCOM])) $oldrecord = find_record_in_file($pid);
	$lines = preg_split("/[\r\n]+/", $oldrecord);
	for($i=0; $i<count($lines); $i++) {
		$line = $lines[$i];
		if (!empty($line)) {
			$fields = preg_split("/\s+/", $line);
			$level = $fields[0];
			if ($fields[1]!="OBJE") $newrec .= $line."\r\n";
			else {
				$newrec .= $line."\r\n";
				$oldi = $i;
				do {
					$i++;
					if (isset($lines[$i])) {
						$line = $lines[$i];
						$fields = preg_split("/\s+/", $line);
						$sublevel = $fields[0];
					}
				} while($sublevel>$level && $i<count($lines));
				if ($i!=$oldi && $i<count($lines)) $newrec .= $line."\r\n";
			}
		}
	}
	$newrec = trim($newrec);
	if ($newrec!=trim($oldrecord)) {
		print "Fixing record ".$pid."<br />\n";
		replace_gedrec($pid, $newrec, false);
	}
	else print "No changes needed for record ".$pid."<br />\n";
}

print "<br />Saving GEDCOM file ".$GEDCOM."<br />\n";
write_file();

print "<br /><b>Updates completed</b><br />";
print_footer();
?>