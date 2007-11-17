<?php
$olddir = "PhpGedView-all-4.1.3";
$newdir = 'PhpGedView-4.1.2-to-4.1.3';

mkdir($newdir);

$xml = simplexml_load_file('PhpGedView-4.1.2-to-4.1.3.xml');

foreach($xml->xpath('//filediff') as $filediff) {
	if (!empty($filediff->WDirHdr_Rmtime)) {
		$parts = explode("\\", $filediff->WDirHdr_Path);
		$base = "\\";
		foreach($parts as $part) {
			if (!file_exists($newdir.$base.$part)) mkdir($newdir.$base.$part);
			$base .= $part."\\";
		}
		if (file_exists($olddir."/".$filediff->WDirHdr_Path."\\".$filediff->WDirHdr_Name)) {
			copy($olddir."/".$filediff->WDirHdr_Path."\\".$filediff->WDirHdr_Name,$newdir."\\".$filediff->WDirHdr_Path."\\".$filediff->WDirHdr_Name);
			print "Copied ".$olddir."\\".$filediff->WDirHdr_Path."\\".$filediff->WDirHdr_Name."\r\n";
		}
		else print "*** Unable to copy ".$olddir."/".$filediff->WDirHdr_Path."\\".$filediff->WDirHdr_Name."\r\n";
	}
}
?>