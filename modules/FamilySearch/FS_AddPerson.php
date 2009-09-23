<?php
/**
 * This is the Add Person page that adds a PGV person to Family Search.
 *
 * FamilySearch PhpGedView Module
 * Copyright (C) 2008  Neumont University
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * See LICENSE.txt for the full license.  If you did not receive a
 * copy of the license with this code, you may find a copy online
 * at http://www.opensource.org/licenses/lgpl-license.php
 *
 * @author Jarrett Coggin
 * */

include_once("modules/FamilySearch/PHP-FamilySearchAPI/FSParse/XMLGEDCOM.php");
include_once("modules/FamilySearch/PHP-FamilySearchAPI/FSAPI/FamilySearchProxy.php");
include_once("includes/classes/class_person.php");
require_once("modules/FamilySearch/config.php");
include_once("modules/FamilySearch/RA_AutoMatch.php");

if (!userGedcomAdmin(getUserName())) {
	header('Location: index.php');
	exit;
}

$pid="i3";
if (isset($_REQUEST['pid'])) $pid = $_REQUEST['pid'];

$person = Person::GetInstance($pid);

print_header("Add Person to FamilySearch");

if (empty($person)) {
	print "Could not find person with PID ".$pid;
}
else {
	$adder = new RA_AutoMatch();
	$fsid = $adder->addPerson($person);
	$adder->addLink($person, $fsid);
	print $person->getFullName();
	?>
	was successfully added.  This person's ID on the remote site is <?php print $fsid; ?><br /><br />
	
	<a href="module.php?mod=FamilySearch&amp;pgvaction=FS_Relatives&pid=<?php echo $pid;?>">Continue to Relatives</a>
	<?php
}

print_footer();
?>