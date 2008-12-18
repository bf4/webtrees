<?php
/**
 * Creates some statistics out of the GEDCOM information.
 * We will start with the following possibilities
 * number of persons -> periodes of 50 years from 1700-2000
 * age -> periodes of 10 years (different for 0-1,1-5,5-10,10-20 etc)
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
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
 * @version $Id$
 * @package PhpGedView
 * @subpackage Lists
 */

require './config.php';
require_once 'includes/classes/class_stats.php';
/*
 * Initiate the stats object.
 */
$stats = new stats($GEDCOM);

// Month of birth
function bimo() {
	global $z_as, $months, $zgrenzen, $stats, $n1;

	if ($z_as == 300){
		$num = $stats->statsBirth();
		foreach ($num as $values) {
			foreach ($values as $month_key=>$month_value) {
				$data_value=0;
				if ($month_key=='d_month') {
					$data_key=-1;
					foreach ($months as $key=>$month) {
						if($month==$month_value) {
							$data_key=$key;
						}
					}
				}
				else if ($data_key>=0) {
					$data_value=$month_value;
				}
			}
			if ($data_key>=0) {
				fill_ydata(0, $data_key, $data_value);
				$n1+=$data_value;
			}
		}
	}
	else if ($z_as == 301) {
		$num = $stats->statsBirth(true);
		foreach ($num as $values) {
			foreach ($values as $month_key=>$month_value) {
				if ($month_key=='d_month') {
					$data_key = -1;
					foreach ($months as $key=>$month) {
						if($month==$month_value) {
							$data_key=$key;
						}
					}
				}
				else if ($data_key>=0 && $month_key=='i_sex') {
					if ($month_value=='M')
						$sex_value = 0;
					else if ($month_value=='F')
						$sex_value = 1;
				}
				else if ($data_key>=0) {
					$data_value=$month_value;
				}
			}
			if ($data_key>=0) {
				fill_ydata($sex_value, $data_key, $data_value);
				$n1+=$data_value;
			}
		}
	}
	else {
		$zstart = 0;
		foreach ($zgrenzen as $boundary) {
			$num = $stats->statsBirth(false, $zstart, $boundary);
			foreach ($num as $values) {
				foreach ($values as $month_key=>$month_value) {
					$data_value=0;
					if ($month_key=='d_month') {
						$data_key=-1;
						foreach ($months as $key=>$month) {
							if($month==$month_value) {
								$data_key=$key;
							}
						}
					}
					else if ($data_key>=0) {
						$data_value=$month_value;
					}
				}
				if ($data_key>=0) {
					fill_ydata($boundary, $data_key, $data_value);
					$n1+=$data_value;
				}
			}
			$zstart=$boundary+1;
		}
	}
}

//Month of birth of first child in a relation
function bimo1() {
	global $z_as, $famgeg, $n1;
//TODO

echo "not work yet";
}

//Month of death
function demo() {
	global $z_as, $months, $zgrenzen, $stats, $n1;

	if ($z_as == 300){
		$num = $stats->statsDeath();
		foreach ($num as $values) {
			foreach ($values as $month_key=>$month_value) {
				$data_value=0;
				if ($month_key=='d_month') {
					$data_key=-1;
					foreach ($months as $key=>$month) {
						if($month==$month_value) {
							$data_key=$key;
						}
					}
				}
				else if ($data_key>=0) {
					$data_value=$month_value;
				}
			}
			if ($data_key>=0) {
				//print $data_key." ".$data_value."<br />";
				fill_ydata(0, $data_key, $data_value);
				$n1+=$data_value;
			}
		}
	}
	else if ($z_as == 301) {
		$num = $stats->statsDeath(true);
		foreach ($num as $values) {
			foreach ($values as $month_key=>$month_value) {
				if ($month_key=='d_month') {
					$data_key = -1;
					foreach ($months as $key=>$month) {
						if($month==$month_value) {
							$data_key=$key;
						}
					}
				}
				else if ($data_key>=0 && $month_key=='i_sex') {
					if ($month_value=='M')
						$sex_value = 0;
					else if ($month_value=='F')
						$sex_value = 1;
				}
				else if ($data_key>=0) {
					$data_value=$month_value;
				}
			}
			if ($data_key>=0) {
				fill_ydata($sex_value, $data_key, $data_value);
				$n1+=$data_value;
			}
		}
	}
	else {
		$zstart = 0;
		foreach ($zgrenzen as $boundary) {
			$num = $stats->statsDeath(false, $zstart, $boundary);
			foreach ($num as $values) {
				foreach ($values as $month_key=>$month_value) {
					$data_value=0;
					if ($month_key=='d_month') {
						$data_key=-1;
						foreach ($months as $key=>$month) {
							if($month==$month_value) {
								$data_key=$key;
							}
						}
					}
					else if ($data_key>=0) {
						$data_value=$month_value;
					}
				}
				if ($data_key>=0) {
					fill_ydata($boundary, $data_key, $data_value);
					$n1+=$data_value;
				}
			}
			$zstart=$boundary+1;
		}
	}
}

//Month of marriage
function mamo() {
	global $z_as, $months, $zgrenzen, $stats, $n1;

	if ($z_as == 300){
		$num = $stats->statsMarr(false);
		foreach ($num as $values) {
			foreach ($values as $month_key=>$month_value) {
				$data_value=0;
				if ($month_key=='d_month') {
					$data_key=-1;
					foreach ($months as $key=>$month) {
						if($month==$month_value) {
							$data_key=$key;
						}
					}
				}
				else if ($data_key>=0) {
					$data_value=$month_value;
				}
			}
			if ($data_key>=0) {
				fill_ydata(0, $data_key, $data_value);
				$n1+=$data_value;
			}
		}
	}
	else {
		$zstart = 0;
		foreach ($zgrenzen as $boundary) {
			$num = $stats->statsMarr(false, $zstart, $boundary);
			foreach ($num as $values) {
				foreach ($values as $month_key=>$month_value) {
					$data_value=0;
					if ($month_key=='d_month') {
						$data_key=-1;
						foreach ($months as $key=>$month) {
							if($month==$month_value) {
								$data_key=$key;
							}
						}
					}
					else if ($data_key>=0) {
						$data_value=$month_value;
					}
				}
				if ($data_key>=0) {
					fill_ydata($boundary, $data_key, $data_value);
					$n1+=$data_value;
				}
			}
			$zstart=$boundary+1;
		}
	}
}

//Month of first marriage
//TODO first only
function mamo1() {
	global $z_as, $months, $zgrenzen, $stats, $n1;

	
echo "not work property yet";
	if ($z_as == 300){
		$num = $stats->statsMarr(true);
		foreach ($num as $values) {
			foreach ($values as $month_key=>$month_value) {
				$data_value=0;
				if ($month_key=='d_month') {
					$data_key=-1;
					foreach ($months as $key=>$month) {
						if($month==$month_value) {
							$data_key=$key;
						}
					}
				}
				else if ($data_key>=0) {
					$data_value=$month_value;
				}
			}
			if ($data_key>=0) {
				fill_ydata(0, $data_key, $data_value);
				$n1+=$data_value;
			}
		}
	}
	else {
		$zstart = 0;
		foreach ($zgrenzen as $boundary) {
			$num = $stats->statsMarr(true, $zstart, $boundary);
			foreach ($num as $values) {
				foreach ($values as $month_key=>$month_value) {
					$data_value=0;
					if ($month_key=='d_month') {
						$data_key=-1;
						foreach ($months as $key=>$month) {
							if($month==$month_value) {
								$data_key=$key;
							}
						}
					}
					else if ($data_key>=0) {
						$data_value=$month_value;
					}
				}
				if ($data_key>=0) {
					fill_ydata($boundary, $data_key, $data_value);
					$n1+=$data_value;
				}
			}
			$zstart=$boundary+1;
		}
	}
}

//Months between marriage and first child
function mamam() {
	global $z_as, $n1;
//TODO

echo "not work yet";
}

//Age related to birth year
function agbi() {
	global $z_as, $zgrenzen, $stats, $n1;

	if ($z_as == 300){
		$num = $stats->statsAge('BIRT');
		foreach ($num as $values) {
			foreach ($values as $age_value) {
				fill_ydata(0, floor($age_value/365.25), 1);
				$n1++;
			}
		}
	}
	else if ($z_as == 301) {
		$num = $stats->statsAge('BIRT', 'M');
		foreach ($num as $values) {
			foreach ($values as $age_value) {
				fill_ydata(0, floor($age_value/365.25), 1);
				$n1++;
			}
		}
		$num = $stats->statsAge('BIRT', 'F');
		foreach ($num as $values) {
			foreach ($values as $age_value) {
				fill_ydata(1, floor($age_value/365.25), 1);
				$n1++;
			}
		}
	}
	else {
		$zstart = 0;
		foreach ($zgrenzen as $boundary) {
			$num = $stats->statsAge('BIRT', 'BOTH', $zstart, $boundary);
			foreach ($num as $values) {
				foreach ($values as $age_value) {
					fill_ydata($boundary, floor($age_value/365.25), 1);
					$n1++;
				}
			}
			$zstart=$boundary+1;
		}
	}
}

//Age related to death year
function agde() {
	global $z_as, $zgrenzen, $stats, $n1;

	if ($z_as == 300){
		$num = $stats->statsAge('DEAT');
		foreach ($num as $values) {
			foreach ($values as $age_value) {
				fill_ydata(0, floor($age_value/365.25), 1);
				$n1++;
			}
		}
	}
	else if ($z_as == 301) {
		$num = $stats->statsAge('DEAT', 'M');
		foreach ($num as $values) {
			foreach ($values as $age_value) {
				fill_ydata(0, floor($age_value/365.25), 1);
				$n1++;
			}
		}
		$num = $stats->statsAge('DEAT', 'F');
		foreach ($num as $values) {
			foreach ($values as $age_value) {
				fill_ydata(1, floor($age_value/365.25), 1);
				$n1++;
			}
		}
	}
	else {
		$zstart = 0;
		foreach ($zgrenzen as $boundary) {
			$num = $stats->statsAge('DEAT', 'BOTH', $zstart, $boundary);
			foreach ($num as $values) {
				foreach ($values as $age_value) {
					fill_ydata($boundary, floor($age_value/365.25), 1);
					$n1++;
				}
			}
			$zstart=$boundary+1;
		}
	}
}

//Age in year of marriage
function agma() {
	global $z_as, $zgrenzen, $stats, $n1;

	if ($z_as == 300){
		$num = $stats->statsMarrAge('M');
		foreach ($num as $values) {
			foreach ($values as $key=>$age_value) {
				if ($key=='age') {
					fill_ydata(0, floor($age_value/365.25), 1);
					$n1++;
				}
			}
		}
		$num = $stats->statsMarrAge('F');
		foreach ($num as $values) {
			foreach ($values as $key=>$age_value) {
				if ($key=='age') {
					fill_ydata(0, floor($age_value/365.25), 1);
					$n1++;
				}
			}
		}
	}
	else if ($z_as == 301) {
		$num = $stats->statsMarrAge('M');
		foreach ($num as $values) {
			foreach ($values as $key=>$age_value) {
				if ($key=='age') {
					fill_ydata(0, floor($age_value/365.25), 1);
					$n1++;
				}
			}
		}
		$num = $stats->statsMarrAge('F');
		foreach ($num as $values) {
			foreach ($values as $key=>$age_value) {
				if ($key=='age') {
					fill_ydata(1, floor($age_value/365.25), 1);
					$n1++;
				}
			}
		}
	}
	else {
		$zstart = 0;
		foreach ($zgrenzen as $boundary) {
			$num = $stats->statsMarrAge('M', $zstart, $boundary);
			foreach ($num as $values) {
				foreach ($values as $key=>$age_value) {
					if ($key=='age') {
						fill_ydata($boundary, floor($age_value/365.25), 1);
						$n1++;
					}
				}
			}
			$num = $stats->statsMarrAge('F', $zstart, $boundary);
			foreach ($num as $values) {
				foreach ($values as $key=>$age_value) {
					if ($key=='age') {
						fill_ydata($boundary, floor($age_value/365.25), 1);
						$n1++;
					}
				}
			}
			$zstart=$boundary+1;
		}
	}
}

//Age in year of first marriage
function agma1() {
	global $z_as, $zgrenzen, $stats, $n1;

	if ($z_as == 300) {
		$num = $stats->statsMarrAge('M');
		$first=true;
		$indi='';
		foreach ($num as $values) {
			foreach ($values as $key=>$age_value) {
				if ($key=='age' && $first) {
					fill_ydata(0, floor($age_value/365.25), 1);
					$n1++;
				}
				else if ($key=='indi') {
					if ($indi!=$age_value) {
						$indi=$age_value;
						$first=true;
					}
					else $first=false;
				}
			}
		}
		$num = $stats->statsMarrAge('F');
		$first=true;
		$indi='';
		foreach ($num as $values) {
			foreach ($values as $key=>$age_value) {
				if ($key=='age' && $first) {
					fill_ydata(0, floor($age_value/365.25), 1);
					$n1++;
				}
				else if ($key=='indi') {
					if ($indi!=$age_value) {
						$indi=$age_value;
						$first=true;
					}
					else $first=false;
				}
			}
		}
	}
	else if ($z_as == 301) {
		$num = $stats->statsMarrAge('M');
		$first=true;
		$indi='';
		foreach ($num as $values) {
			foreach ($values as $key=>$age_value) {
				if ($key=='age' && $first) {
					fill_ydata(0, floor($age_value/365.25), 1);
					$n1++;
				}
				else if ($key=='indi') {
					if ($indi!=$age_value) {
						$indi=$age_value;
						$first=true;
					}
					else $first=false;
				}
			}
		}
		$num = $stats->statsMarrAge('F');
		$first=true;
		$indi='';
		foreach ($num as $values) {
			foreach ($values as $key=>$age_value) {
				if ($key=='age' && $first) {
					fill_ydata(1, floor($age_value/365.25), 1);
					$n1++;
				}
				else if ($key=='indi') {
					if ($indi!=$age_value) {
						$indi=$age_value;
						$first=true;
					}
					else $first=false;
				}
			}
		}
	}
	else {
		$zstart = 0;
		$indi=array();
		foreach ($zgrenzen as $boundary) {
			$num = $stats->statsMarrAge('M', $zstart, $boundary);
			$first=true;
			foreach ($num as $values) {
				foreach ($values as $key=>$age_value) {
					if ($key=='age' && $first) {
						fill_ydata($boundary, floor($age_value/365.25), 1);
						$n1++;
					}
					else if ($key=='indi') {
						if (!in_array($age_value, $indi)) {
							$indi[]=$age_value;
							$first=true;
						}
						else $first=false;
					}
				}
			}
			$num = $stats->statsMarrAge('F', $zstart, $boundary);
			$first=true;
			foreach ($num as $values) {
				foreach ($values as $key=>$age_value) {
					if ($key=='age' && $first) {
						fill_ydata($boundary, floor($age_value/365.25), 1);
						$n1++;
					}
					else if ($key=='indi') {
						if (!in_array($age_value, $indi)) {
							$indi[]=$age_value;
							$first=true;
						}
						else $first=false;
					}
				}
			}
			$zstart=$boundary+1;
		}
		unset($indi);
	}
}

//Number of children
function nuch() {
	global $z_as, $n1;

//TODO

echo "not work yet";
}

function fill_ydata($z, $x, $val) {
	global $ydata, $xmax, $xgrenzen, $zmax, $zgrenzen, $xgiven, $zgiven;
	//--	calculate index $i out of given z value
	//--	calculate index $j out of given x value
	if ($xgiven) $j = $x;
	else {
		$j=0;
		while (($x > $xgrenzen[$j]) && ($j < $xmax)) {
			$j++;
		}
	}
	if ($zgiven) $i = $z;
	else {
		$i=0;
		while (($z > $zgrenzen[$i]) && ($i < $zmax)) {
			$i++;
		}
	}
	if (isset($ydata[$i][$j])) $ydata[$i][$j] += $val;
	else $ydata[$i][$j] = $val;
}

function myplot($mytitle, $n, $xdata, $xtitle, $ydata, $ytitle, $legend) {
	global $percentage, $male_female;
	global $ymax, $scalefactor, $datastring, $imgurl;
	//Google Chart API only allows text encoding for numbers less than 100
	//and it does not allow adjusting the y-axis range, so we must find the maximum y-value
	//in order to adjust beforehand by changing the numbers

	if ($male_female==1) $stop = 2;
	else $stop = count($ydata);
	$yprocentmax = 0;
	if ($percentage) {
		for($i=0; $i<$stop; $i++) {
			$ytotal = 0;
			$ymax = 0;
			$yprocent = 0;
			if (isset($ydata[$i])) {
				for($j=0; $j<count($ydata[$i]); $j++) {
					if ($ydata[$i][$j] > $ymax) {
						$ymax = $ydata[$i][$j];
					}
					$ytotal += $ydata[$i][$j];
				}
				$yt[$i] = $ytotal;
				if ($ytotal>0)
					$yprocent = round($ymax/$ytotal*100, 1);
				if ($yprocentmax < $yprocent) $yprocentmax = $yprocent;
			}
		}
		$ymax = $yprocentmax;
		if ($ymax>0) $scalefactor = 100.0/$ymax;
		else $scalefactor = 0;
		$datastring = "chd=t:";
		for($i=0; $i<$stop; $i++) {
			if (isset($ydata[$i])) {
				for($j=0; $j<count($ydata[$i]); $j++){
					if ($yt[$i] > 0) {
						$datastring .= round($ydata[$i][$j]/$yt[$i]*100*$scalefactor, 1);
					}
					else {
						$datastring .= "0";
					}
					if (!($j == (count($ydata[$i])-1))){
						$datastring .= ",";
					}
				}
				if (!($i == ($stop-1))) {
					$datastring .= "|";
				}
			}
		}
	}
	else {
		for($i=0; $i<$stop; $i++) {
			for($j=0; $j<count($ydata[$i]); $j++) {
				if ($ydata[$i][$j]>$ymax) {
					$ymax = $ydata[$i][$j];
				}
			}
		}
		if ($ymax>0) $scalefactor = 100.0/$ymax;
		else $scalefactor = 0;
		$datastring = "chd=t:";
		for($i=0; $i<$stop; $i++) {
			for($j=0; $j<count($ydata[$i]); $j++){
				$datastring .= round($ydata[$i][$j]*$scalefactor, 1);
				if (!($j == (count($ydata[$i])-1))){
					$datastring .= ",";
				}
			}
			if (!($i == ($stop-1))) {
				$datastring .= "|";
			}
		}
	}
	$colors = array("0000FF","FFA0CB","9F00FF","FF7000","905030","FF0000","00FF00","F0F000");
	$colorstring = "chco=";
	for($i=0; $i<$stop; $i++) {
		if (isset($colors[$i])) {
			$colorstring .= $colors[$i];
			if ($i != ($stop-1)){
				$colorstring .= ",";
			}
		}
	}

	$titleLength = strpos($mytitle."\n", "\n");
	$title = substr($mytitle, 0, $titleLength);

	$imgurl = "http://chart.apis.google.com/chart?cht=bvg&chs=950x300&chf=bg,s,ffffff99|c,s,ffffff00&chtt=".$title."&".$datastring."&".$colorstring."&chbh=";
	if (count($ydata) > 3) $imgurl .= "5,1";
	else if (count($ydata) < 2) $imgurl .= "45,1";
	else $imgurl .= "20,3";
	$imgurl .= "&chxt=x,x,y,y&chxl=0:|";
	for($i=0; $i<count($xdata); $i++) {
		$imgurl .= $xdata[$i]."|";
	}

	$imgurl .= "1:||||".$xtitle."|2:|";
	$imgurl .= "0|";
	if ($percentage){
		for($i=1; $i<11; $i++) {
			if ($ymax < 11)
				$imgurl .= round($ymax*$i/10,1)."|";
			else
				$imgurl .= round($ymax*$i/10,0)."|";
		}
		$imgurl .= "3:||%|";
	}
	else {
		if ($ymax < 11) {
			for($i=1; $i<$ymax+1; $i++) {
				$imgurl .= round($ymax*$i/($ymax), 0)."|";
			}
		}
		else {
			for($i=1; $i<11; $i++) {
				$imgurl .= round($ymax*$i/10, 0)."|";
			}
		}
		$imgurl .= "3:||".$ytitle."|";
	}
	//only show legend if y-data is non-2-dimensional
	if (count($ydata) > 1) {
		$imgurl .= "&chdl=";
		for($i=0; $i<count($legend); $i++){
			$imgurl .= $legend[$i];
			if (!($i == (count($legend)-1))){
				$imgurl .= "|";
			}
		}
	}
	echo "<center>";
	echo "<img src=\"".encode_url($imgurl)."\" width=\"950\" height=\"300\"  border=\"0\" alt=\"".$mytitle."\" title=\"".$mytitle."\"/>";
	echo "</center><br /><br />";
}

function calc_axis($xas_grenzen) {
	global $x_as, $xdata, $xmax, $xgrenzen, $pgv_lang;

	//calculate xdata and zdata elements out of given POST values
	$hulpar = explode(",", $xas_grenzen);
	$i=1;
	if ($x_as==21 && $hulpar[0]==1)
		$xdata[0] = 0;
	else if ($x_as==16 && $hulpar[0]==0)
		$xdata[0] = $pgv_lang["bef"];
	else if ($x_as==16 && $hulpar[0]<0)
		$xdata[0] = $pgv_lang["over"]." ".$hulpar[0];
	else
		$xdata[0] = $pgv_lang["less"]." ".$hulpar[0];
	$xgrenzen[0] = $hulpar[0]-1;
	while (isset($hulpar[$i])) {
		$i1 = $i-1;
		if (($hulpar[$i] - $hulpar[$i1]) == 1) {
			$xdata[$i] = $hulpar[$i1];
			$xgrenzen[$i] = $hulpar[$i1];
		}
		else if ($hulpar[$i1]==$hulpar[0]){
			$xdata[$i]= $hulpar[$i1]."-".$hulpar[$i];
			$xgrenzen[$i] = $hulpar[$i];
		}
		else {
			$xdata[$i]= ($hulpar[$i1]+1)."-".$hulpar[$i];
			$xgrenzen[$i] = $hulpar[$i];
		}
		$i++;
	}
	$xdata[$i] = $hulpar[$i-1];
	$xgrenzen[$i] = $hulpar[$i-1];
	if ($hulpar[$i-1]==$i)
		$xmax = $i+1;
	else
		$xmax = $i;
	$xdata[$xmax] = $pgv_lang["over"]." ".$hulpar[$i-1];
	$xgrenzen[$xmax] = 10000;
	$xmax = $xmax+1;
	if ($xmax > 20) $xmax = 20;
}

function calc_legend($grenzen_zas) {
	global $legend, $zmax, $zgrenzen, $pgv_lang;

	// calculate the legend values
	$hulpar = array();
	//-- get numbers out of $grenzen_zas
	$hulpar = explode(",", $grenzen_zas);
	$i=1;
	$legend[0] = $pgv_lang["bef"]." ".$hulpar[0];
	$zgrenzen[0] = $hulpar[0]-1;
	while (isset($hulpar[$i])) {
		$i1 = $i-1;
		$legend[$i] = $hulpar[$i1]."-".($hulpar[$i]-1);
		$zgrenzen[$i] = $hulpar[$i]-1;
		$i++;
	}
	$zmax = $i;
	$zmax1 = $zmax-1;
	$legend[$zmax] = $pgv_lang["from"]." ".$hulpar[$zmax1];
	$zgrenzen[$zmax] = 10000;
	$zmax = $zmax+1;
	if ($zmax > 8) $zmax=8;
}

//--------------------nr,-----bron ,xgiven,zgiven,title, xtitle,ytitle,grenzen_xas, grenzen-zas,functie,
function set_params($current, $indfam, $xg, $zg, $titstr, $xt, $yt, $gx, $gz, $myfunc) {
	global $x_as, $y_as, $z_as, $nrfam, $nrpers, $n1, $months;
	global $legend, $xdata, $ydata, $xmax, $zmax, $zgrenzen, $xgiven, $zgiven, $percentage, $male_female;
	global $pgv_lang;

	if (!function_exists($myfunc)) {
		echo $myfunc." ".$pgv_lang["stplnoim"];
		exit;
	}

	$monthdata= array();
	$monthdata[] = $pgv_lang["jan_1st"];
	$monthdata[] = $pgv_lang["feb_1st"];
	$monthdata[] = $pgv_lang["mar_1st"];
	$monthdata[] = $pgv_lang["apr_1st"];
	$monthdata[] = $pgv_lang["may_1st"];
	$monthdata[] = $pgv_lang["jun_1st"];
	$monthdata[] = $pgv_lang["jul_1st"];
	$monthdata[] = $pgv_lang["aug_1st"];
	$monthdata[] = $pgv_lang["sep_1st"];
	$monthdata[] = $pgv_lang["oct_1st"];
	$monthdata[] = $pgv_lang["nov_1st"];
	$monthdata[] = $pgv_lang["dec_1st"];
	foreach ($monthdata as $key=>$month) {
		$monthdata[$key] = $month;
	}
	$months= array();
	$months[] = 'JAN';
	$months[] = 'FEB';
	$months[] = 'MAR';
	$months[] = 'APR';
	$months[] = 'MAY';
	$months[] = 'JUN';
	$months[] = 'JUL';
	$months[] = 'AUG';
	$months[] = 'SEP';
	$months[] = 'OCT';
	$months[] = 'NOV';
	$months[] = 'DEC';
	foreach ($months as $key=>$month) {
		$months[$key] = $month;
	}

	if ($x_as == $current) {
		if ($x_as==13 || $x_as==15 && $z_as == 301) {
			$z_as = 300;
		}
		$xgiven = $xg;
		$zgiven = $zg;
		$title = $pgv_lang["$titstr"];
		$xtitle = $pgv_lang["$xt"];
		$ytitle = $pgv_lang["stplnumbers"];
		$grenzen_xas = $gx;
		$grenzen_zas = $gz;
		if ($xg == true) {
			$xdata = $monthdata;
			$xmax = 12;
		}
		else
			calc_axis($grenzen_xas);
		if ($z_as != 300 && $z_as != 301)
			calc_legend($grenzen_zas);

		$percentage = false;
		if ($y_as == 201) {
			$percentage = false;
			if ($current == 13 || $current == 15 || $current == 16 || $current == 21)
				$ytitle = $pgv_lang["statnfam"];
			else if ($current == 14)
				$ytitle = $pgv_lang["stat_21_nok"];
			else
				$ytitle = $pgv_lang["statnnames"];
		}
		else if ($y_as == 202) {
			$percentage = true;
			$ytitle = $pgv_lang["stplperc"];
		}
		$male_female = false;
		if ($z_as == 300) {
			$zgiven = false;
			$legend[0] = "all";
			$zmax = 1;
			$zgrenzen[0] = 100000;
		}
		else if ($z_as == 301) {
			$male_female = true;
			$zgiven = true;
			$legend[0] = $pgv_lang["male"];
			$legend[1] = $pgv_lang["female"];
			$zmax = 2;
			$xtitle = $xtitle.$pgv_lang["stplmf"];
		}
		else if ($z_as == 302)
			$xtitle= $xtitle.$pgv_lang["stplipot"];
		//-- reset the data array
		for($i=0; $i<$zmax; $i++) {
			for($j=0; $j<$xmax; $j++) {
				$ydata[$i][$j] = 0;
			}
		}
		$myfunc();
		if ($indfam == "IND") {
			$hstr = $title."|" .$pgv_lang["stplnumof"]." ".$n1." ".$pgv_lang["of"]." ".$nrpers;
		}
		else {
			$hstr = $title."|" .$pgv_lang["stplnumof"]." ".$n1." ".$pgv_lang["of"]." ".$nrfam;
		}
		myplot($hstr, $zmax, $xdata, $xtitle, $ydata, $ytitle, $legend);
	}
}

// prints a map charts
function print_map_charts($chart_shows, $chart_type, $x_as, $surname) {
	global $GEDCOM, $pgv_lang;
	global $iso3166, $country_to_iso3166;

	if ($surname=="") {
		require_once 'includes/classes/class_stats.php';
		$stats = new stats($GEDCOM);
		$surname = $stats->getCommonSurname();
	}
	switch ($chart_type) {
	case 'indi_distribution_chart':
		$chart_title=$pgv_lang["indi_distribution_chart"];
		break;
	case 'surname_distribution_chart':
		$chart_title=$pgv_lang["surname_distribution_chart"].': '.$surname;
		break;
	}
	switch ($x_as) {
	case '2':
		$chart_type='birth_distribution_chart';
		$chart_title=$pgv_lang["stat_2_map"];
		break;
	case '3':
		$chart_type='death_distribution_chart';
		$chart_title=$pgv_lang["stat_3_map"];
		break;
	case '4':
		$chart_type='marriage_distribution_chart';
		$chart_title=$pgv_lang["stat_4_map"];
		break;
	}

	$title = $chart_title;

	switch ($chart_type) {
	case 'indi_distribution_chart':
		// Count how many people are events in each country
		$surn_countries=array();
		foreach (get_indi_list() as $person) {
			if (preg_match_all('/^2 PLAC (?:.*, *)*(.*)/m', $person->gedrec, $matches)) {
				// PGV uses 3 letter country codes and localised country names, but google uses 2 letter codes.
				foreach ($matches[1] as $country) {
					$country=UTF8_strtolower(trim($country));
					if (array_key_exists($country, $country_to_iso3166)) {
						if (array_key_exists($country_to_iso3166[$country], $surn_countries)) {
							$surn_countries[$country_to_iso3166[$country]]++;
						} else {
							$surn_countries[$country_to_iso3166[$country]]=1;
						}
					}
				}
			}
		};
		$chart_url ="http://chart.apis.google.com/chart?cht=t&amp;chtm=".$chart_shows;
		$chart_url.="&amp;chco=ffffff,c3dfff,84beff"; // country colours
		$chart_url.="&amp;chf=bg,s,EAF7FE"; // sea colour
		$chart_url.="&amp;chs=440x220"; // max size for maps is 440x220
		$chart_url.="&amp;chld=".implode('', array_keys($surn_countries))."&amp;chd=s:";
		foreach ($surn_countries as $count) {
			$chart_url.=substr(PGV_GOOGLE_CHART_ENCODING, floor($count/max($surn_countries)*61), 1);
		}
		break;
	case 'surname_distribution_chart':
		// Count how many people are events in each country
		$surn_countries=array();
		$indis = get_indilist_indis(UTF8_strtoupper($surname), '', '', false, false, PGV_GED_ID);
		foreach ($indis as $person) {
			if (preg_match_all('/^2 PLAC (?:.*, *)*(.*)/m', $person->gedrec, $matches)) {
				// PGV uses 3 letter country codes and localised country names, but google uses 2 letter codes.
				foreach ($matches[1] as $country) {
					$country=UTF8_strtolower(trim($country));
					if (array_key_exists($country, $country_to_iso3166)) {
						if (array_key_exists($country_to_iso3166[$country], $surn_countries)) {
							$surn_countries[$country_to_iso3166[$country]]++;
						} else {
							$surn_countries[$country_to_iso3166[$country]]=1;
						}
					}
				}
			}
		};
		$chart_url ="http://chart.apis.google.com/chart?cht=t&amp;chtm=".$chart_shows;
		$chart_url.="&amp;chco=ffffff,c3dfff,84beff"; // country colours
		$chart_url.="&amp;chf=bg,s,EAF7FE"; // sea colour
		$chart_url.="&amp;chs=440x220"; // max size for maps is 440x220
		$chart_url.="&amp;chld=".implode('', array_keys($surn_countries))."&amp;chd=s:";
		foreach ($surn_countries as $count) {
			$chart_url.=substr(PGV_GOOGLE_CHART_ENCODING, floor($count/max($surn_countries)*61), 1);
		}
		break;
	case 'birth_distribution_chart':
		// Count how many people were born in each country
		$surn_countries=array();
		foreach (get_indi_list() as $person) {
			$birthplace = $person->getBirthPlace();
			if ($birthplace != "") {
				$birthplace = getPlaceCountry($birthplace);
				$country=UTF8_strtolower(trim($birthplace));
				// PGV uses 3 letter country codes and localised country names, but google uses 2 letter codes.
				if (array_key_exists($country, $country_to_iso3166)) {
					if (array_key_exists($country_to_iso3166[$country], $surn_countries)) {
						$surn_countries[$country_to_iso3166[$country]]++;
					} else {
						$surn_countries[$country_to_iso3166[$country]]=1;
					}
				}
			}
		};
		$chart_url ="http://chart.apis.google.com/chart?cht=t&amp;chtm=".$chart_shows;
		$chart_url.="&amp;chco=ffffff,c3dfff,84beff"; // country colours
		$chart_url.="&amp;chf=bg,s,EAF7FE"; // sea colour
		$chart_url.="&amp;chs=440x220"; // max size for maps is 440x220
		$chart_url.="&amp;chld=".implode('', array_keys($surn_countries))."&amp;chd=s:";
		foreach ($surn_countries as $count) {
			$chart_url.=substr(PGV_GOOGLE_CHART_ENCODING, floor($count/max($surn_countries)*61), 1);
		}
		break;
	case 'death_distribution_chart':
		// Count how many people were death in each country
		$surn_countries=array();
		foreach (get_indi_list() as $person) {
			$deathplace = $person->getDeathPlace();
			if ($deathplace != "") {
				$deathplace = getPlaceCountry($deathplace);
				$country=UTF8_strtolower(trim($deathplace));
				// PGV uses 3 letter country codes and localised country names, but google uses 2 letter codes.
				if (array_key_exists($country, $country_to_iso3166)) {
					if (array_key_exists($country_to_iso3166[$country], $surn_countries)) {
						$surn_countries[$country_to_iso3166[$country]]++;
					} else {
						$surn_countries[$country_to_iso3166[$country]]=1;
					}
				}
			}
		};
		$chart_url ="http://chart.apis.google.com/chart?cht=t&amp;chtm=".$chart_shows;
		$chart_url.="&amp;chco=ffffff,c3dfff,84beff"; // country colours
		$chart_url.="&amp;chf=bg,s,EAF7FE"; // sea colour
		$chart_url.="&amp;chs=440x220"; // max size for maps is 440x220
		$chart_url.="&amp;chld=".implode('', array_keys($surn_countries))."&amp;chd=s:";
		foreach ($surn_countries as $count) {
			$chart_url.=substr(PGV_GOOGLE_CHART_ENCODING, floor($count/max($surn_countries)*61), 1);
		}
		break;
	case 'marriage_distribution_chart':
		// Count how many families got marriage in each country
		$surn_countries=array();
		foreach (get_fam_list() as $family) {
			$marriageplace = $family->getMarriagePlace();
			if ($marriageplace != "") {
				$marriageplace = getPlaceCountry($marriageplace);
				$country=UTF8_strtolower(trim($marriageplace));
				// PGV uses 3 letter country codes and localised country names, but google uses 2 letter codes.
				if (array_key_exists($country, $country_to_iso3166)) {
					if (array_key_exists($country_to_iso3166[$country], $surn_countries)) {
						$surn_countries[$country_to_iso3166[$country]]++;
					} else {
						$surn_countries[$country_to_iso3166[$country]]=1;
					}
				}
			}
		};
		$chart_url ="http://chart.apis.google.com/chart?cht=t&amp;chtm=".$chart_shows;
		$chart_url.="&amp;chco=ffffff,c3dfff,84beff"; // country colours
		$chart_url.="&amp;chf=bg,s,EAF7FE"; // sea colour
		$chart_url.="&amp;chs=440x220"; // max size for maps is 440x220
		$chart_url.="&amp;chld=".implode('', array_keys($surn_countries))."&amp;chd=s:";
		foreach ($surn_countries as $count) {
			$chart_url.=substr(PGV_GOOGLE_CHART_ENCODING, floor($count/max($surn_countries)*61), 1);
		}
		break;
	}
	$content='<div align="center"><img src="'.$chart_url.'" alt="'.$chart_title.'" title="'.$chart_title.'" class="gchart" />';

	echo '<div id="google_charts" class="center">';
	echo '<b>'.$title.'</b><br /><br />';
	echo $content;
	echo '<br /><table align="center" border="0" cellpadding="1" cellspacing="1"><tr><td bgcolor="84beff" width="12"></td><td>'.$pgv_lang["g_chart_high"].'&nbsp;&nbsp;</td><td bgcolor="c3dfff" width="12"></td><td>'.$pgv_lang["g_chart_low"].'&nbsp;&nbsp;</td><td bgcolor="ffffff" width="12"></td><td>'.$pgv_lang["g_chart_nobody"].'&nbsp;&nbsp;</td></tr></table>';
	echo '</div></div>';
}

function print_sources_stats_chart($type){
	global $pgv_lang, $GEDCOM;
	require_once 'includes/classes/class_stats.php';
	$stats = new stats($GEDCOM);
	$params[0] = "700x200";
	$params[1] = "ffffff";
	$params[2] = "84beff";
	switch ($type) {
	case '9':
		echo '<div id="google_charts" class="center">';
		echo '<b>'.$pgv_lang["stat_9_indi"].'</b><br /><br />';
		echo $stats->chartIndisWithSources($params);
		echo '</div><br />';
		break;
	case '8':
		echo '<div id="google_charts" class="center">';
		echo '<b>'.$pgv_lang["stat_8_fam"].'</b><br /><br />';
		echo $stats->chartFamsWithSources($params);
		echo '</div><br />';
		break;
	}
}

//--	========= start of main program =========
$action	= safe_REQUEST($_REQUEST, 'action', PGV_REGEX_XREF);

if ($action=="update") {
	$x_as = $_POST["x-as"];
	$y_as = $_POST["y-as"];
	if (isset($_POST["z-as"])) $z_as = $_POST["z-as"];
	else $z_as = 300;
	$xgl = $_POST["xas-grenzen-leeftijden"];
	$xglm = $_POST["xas-grenzen-leeftijden_m"];
	$xgm = $_POST["xas-grenzen-maanden"];
	$xga = $_POST["xas-grenzen-aantallen"];
	if (isset($_POST["zas-grenzen-periode"])) $zgp = $_POST["zas-grenzen-periode"];
	else $zgp = 0;
	$chart_shows = $_POST["chart_shows"];
	$chart_type  = $_POST["chart_type"];
	$surname	 = $_POST["SURN"];

	$_SESSION[$GEDCOM."statTicks"]["xasGrLeeftijden"] = $xgl;
	$_SESSION[$GEDCOM."statTicks"]["xasGrLeeftijden_m"] = $xglm;
	$_SESSION[$GEDCOM."statTicks"]["xasGrMaanden"] = $xgm;
	$_SESSION[$GEDCOM."statTicks"]["xasGrAantallen"] = $xga;
	$_SESSION[$GEDCOM."statTicks"]["zasGrPeriode"] = $zgp;
	$_SESSION[$GEDCOM."statTicks"]["chart_shows"] = $chart_shows;
	$_SESSION[$GEDCOM."statTicks"]["chart_type"] = $chart_type;
	$_SESSION[$GEDCOM."statTicks"]["SURN"] = $surname;

	// Save the input variables
	$savedInput = array();
	$savedInput["x_as"] = $x_as;
	$savedInput["y_as"] = $y_as;
	$savedInput["z_as"] = $z_as;
	$savedInput["xgl"] = $xgl;
	$savedInput["xglm"] = $xglm;
	$savedInput["xgm"] = $xgm;
	$savedInput["xga"] = $xga;
	$savedInput["zgp"] = $zgp;
	$savedInput["chart_shows"] = $chart_shows;
	$savedInput["chart_type"] = $chart_type;
	$savedInput["SURN"] = $surname;
	$_SESSION[$GEDCOM."statisticsplot"] = $savedInput;
	unset($savedInput);
}
else {
	// Recover the saved input variables
	$savedInput = $_SESSION[$GEDCOM."statisticsplot"];
	$x_as = $savedInput["x_as"];
	$y_as = $savedInput["y_as"];
	$z_as = $savedInput["z_as"];
	$xgl = $savedInput["xgl"];
	$xglm = $savedInput["xglm"];
	$xgm = $savedInput["xgm"];
	$xga = $savedInput["xga"];
	$zgp = $savedInput["zgp"];
	$chart_shows = $savedInput["chart_shows"];
	$chart_type = $savedInput["chart_type"];
	$surname = $savedInput["SURN"];
	unset($savedInput);
}

print_header($pgv_lang["statistiek_list"]);
echo "\n\t<center><h2>".$pgv_lang["statistiek_list"]."</h2>\n\t";
echo "</center><br />";

//if ($x_as >  10) {
	$nrpers = $_SESSION[$GEDCOM."nrpers"];
	$nrfam = $_SESSION[$GEDCOM."nrfam"];
	$nrmale = $_SESSION[$GEDCOM."nrmale"];
	$nrfemale = $_SESSION[$GEDCOM."nrfemale"];

	//error_reporting(E_ALL ^E_NOTICE);
	//-- no errors because then I cannot plot

	//-- out of range values
	if (($y_as < 201) || ($y_as > 202)) {
		echo $pgv_lang["stpl"].$y_as.$pgv_lang["stplnoim"]."<br/>";
		exit;
	}
	if (($z_as < 300) || ($z_as > 302)) {
		echo $pgv_lang["stpl"].$z_as.$pgv_lang["stplnoim"]."<br/>";
		exit;
	}

	//-- Set params for request out of the information for plot
	$g_xas = "1,2,3,4,5,6,7,8,9,10,11,12"; //should not be needed. but just for month

	switch ($x_as) {
	case '11':
		//---------		nr,  type,	  xgiven,	zgiven,	title,				xtitle,		ytitle,	boundaries_x, boundaries-z, function
		set_params(11,"IND", true,	false, "stat_11_mb",  "stplmonth",	$y_as,	$g_xas,	$zgp, "bimo");  //plot Month of birth
		break;
	case '12':
		set_params(12,"IND", true,	false, "stat_12_md",  "stplmonth",	$y_as,	$g_xas,	$zgp, "demo");  //plot Month of death
		break;
	case '13':
		set_params(13,"FAM", true,	false, "stat_13_mm",  "stplmonth",	$y_as,	$g_xas,	$zgp, "mamo");  //plot Month of marriage
		break;
	case '14':
		set_params(14,"FAM", true,	false, "stat_14_mb1", "stplmonth",	$y_as,	$g_xas,	$zgp, "bimo1"); //plot Month of birth of first child in a relation
		break;
	case '15':
		set_params(15,"FAM", true,	false, "stat_15_mm1", "stplmonth",	$y_as,	$g_xas,	$zgp, "mamo1"); //plot Month of first marriage
		break;
	case '16':
		set_params(16,"FAM", false,	false, "stat_16_mmb", "stplmarrbirth",$y_as,$xgm,	$zgp, "mamam"); //plot Months between marriage and first child
		break;
	case '17':
		set_params(17,"IND", false,	false, "stat_17_arb", "stplage",	$y_as,	$xgl,	$zgp, "agbi");  //plot Age related to birth year
		break;
	case '18':
		set_params(18,"IND", false,	false, "stat_18_ard", "stplage",	$y_as,	$xgl,	$zgp, "agde");  //plot Age related to death year
		break;
	case '19':
		set_params(19,"IND", false,	false, "stat_19_arm", "stplage",	$y_as,	$xglm,	$zgp, "agma");  //plot Age in year of marriage
		break;
	case '20':
		set_params(20,"IND", false,	false, "stat_20_arm1","stplage",	$y_as,	$xglm,	$zgp, "agma1"); //plot Age in year of first marriage
		break;
	case '21':
		set_params(21,"FAM", false,	false, "stat_21_nok", "stplnuch",	$y_as,	$xga,	$zgp, "nuch");  //plot Number of children
		break;
	case '1':
	case '2':
	case '3':
	case '4':
		// PGV uses 3-letter ISO/chapman codes, but google uses 2-letter ISO codes.  There is not a 1:1
		// mapping, so Wales/Scotland/England all become GB, etc.
		if (!isset($iso3166)) {
			$iso3166=array(
			'ABW'=>'AW', 'AFG'=>'AF', 'AGO'=>'AO', 'AIA'=>'AI', 'ALA'=>'AX', 'ALB'=>'AL', 'AND'=>'AD', 'ANT'=>'AN',
			'ARE'=>'AE', 'ARG'=>'AR', 'ARM'=>'AM', 'ASM'=>'AS', 'ATA'=>'AQ', 'ATF'=>'TF', 'ATG'=>'AG', 'AUS'=>'AU',
			'AUT'=>'AT', 'AZE'=>'AZ', 'BDI'=>'BI', 'BEL'=>'BE', 'BEN'=>'BJ', 'BFA'=>'BF', 'BGD'=>'BD', 'BGR'=>'BG',
			'BHR'=>'BH', 'BHS'=>'BS', 'BIH'=>'BA', 'BLR'=>'BY', 'BLZ'=>'BZ', 'BMU'=>'BM', 'BOL'=>'BO', 'BRA'=>'BR',
			'BRB'=>'BB', 'BRN'=>'BN', 'BTN'=>'BT', 'BVT'=>'BV', 'BWA'=>'BW', 'CAF'=>'CF', 'CAN'=>'CA', 'CCK'=>'CC',
			'CHE'=>'CH', 'CHL'=>'CL', 'CHN'=>'CN', 'CHI'=>'JE', 'CIV'=>'CI', 'CMR'=>'CM', 'COD'=>'CD', 'COG'=>'CG',
			'COK'=>'CK', 'COL'=>'CO', 'COM'=>'KM', 'CPV'=>'CV', 'CRI'=>'CR', 'CUB'=>'CU', 'CXR'=>'CX', 'CYM'=>'KY',
			'CYP'=>'CY', 'CZE'=>'CZ', 'DEU'=>'DE', 'DJI'=>'DJ', 'DMA'=>'DM', 'DNK'=>'DK', 'DOM'=>'DO', 'DZA'=>'DZ',
			'ECU'=>'EC', 'EGY'=>'EG', 'ENG'=>'GB', 'ERI'=>'ER', 'ESH'=>'EH', 'ESP'=>'ES', 'EST'=>'EE', 'ETH'=>'ET',
			'FIN'=>'FI', 'FJI'=>'FJ', 'FLK'=>'FK', 'FRA'=>'FR', 'FRO'=>'FO', 'FSM'=>'FM', 'GAB'=>'GA', 'GBR'=>'GB',
			'GEO'=>'GE', 'GHA'=>'GH', 'GIB'=>'GI', 'GIN'=>'GN', 'GLP'=>'GP', 'GMB'=>'GM', 'GNB'=>'GW', 'GNQ'=>'GQ',
			'GRC'=>'GR', 'GRD'=>'GD', 'GRL'=>'GL', 'GTM'=>'GT', 'GUF'=>'GF', 'GUM'=>'GU', 'GUY'=>'GY', 'HKG'=>'HK',
			'HMD'=>'HM', 'HND'=>'HN', 'HRV'=>'HR', 'HTI'=>'HT', 'HUN'=>'HU', 'IDN'=>'ID', 'IND'=>'IN', 'IOT'=>'IO',
			'IRL'=>'IE', 'IRN'=>'IR', 'IRQ'=>'IQ', 'ISL'=>'IS', 'ISR'=>'IL', 'ITA'=>'IT', 'JAM'=>'JM', 'JOR'=>'JO',
			'JPN'=>'JA', 'KAZ'=>'KZ', 'KEN'=>'KE', 'KGZ'=>'KG', 'KHM'=>'KH', 'KIR'=>'KI', 'KNA'=>'KN', 'KOR'=>'KO',
			'KWT'=>'KW', 'LAO'=>'LA', 'LBN'=>'LB', 'LBR'=>'LR', 'LBY'=>'LY', 'LCA'=>'LC', 'LIE'=>'LI', 'LKA'=>'LK',
			'LSO'=>'LS', 'LTU'=>'LT', 'LUX'=>'LU', 'LVA'=>'LV', 'MAC'=>'MO', 'MAR'=>'MA', 'MCO'=>'MC', 'MDA'=>'MD',
			'MDG'=>'MG', 'MDV'=>'MV', 'MEX'=>'ME', 'MHL'=>'MH', 'MKD'=>'MK', 'MLI'=>'ML', 'MLT'=>'MT', 'MMR'=>'MM',
			'MNG'=>'MN', 'MNP'=>'MP', 'MNT'=>'ME', 'MOZ'=>'MZ', 'MRT'=>'MR', 'MSR'=>'MS', 'MTQ'=>'MQ', 'MUS'=>'MU',
			'MWI'=>'MW', 'MYS'=>'MY', 'MYT'=>'YT', 'NAM'=>'NA', 'NCL'=>'NC', 'NER'=>'NE', 'NFK'=>'NF', 'NGA'=>'NG',
			'NIC'=>'NI', 'NIR'=>'GB', 'NIU'=>'NU', 'NLD'=>'NL', 'NOR'=>'NO', 'NPL'=>'NP', 'NRU'=>'NR', 'NZL'=>'NZ',
			'OMN'=>'OM', 'PAK'=>'PK', 'PAN'=>'PA', 'PCN'=>'PN', 'PER'=>'PE', 'PHL'=>'PH', 'PLW'=>'PW', 'PNG'=>'PG',
			'POL'=>'PL', 'PRI'=>'PR', 'PRK'=>'KP', 'PRT'=>'PO', 'PRY'=>'PY', 'PSE'=>'PS', 'PYF'=>'PF', 'QAT'=>'QA',
			'REU'=>'RE', 'ROM'=>'RO', 'RUS'=>'RU', 'RWA'=>'RW', 'SAU'=>'SA', 'SCT'=>'GB', 'SDN'=>'SD', 'SEN'=>'SN',
			'SER'=>'RS', 'SGP'=>'SG', 'SGS'=>'GS', 'SHN'=>'SH', 'SIC'=>'IT', 'SJM'=>'SJ', 'SLB'=>'SB', 'SLE'=>'SL',
			'SLV'=>'SV', 'SMR'=>'SM', 'SOM'=>'SO', 'SPM'=>'PM', 'STP'=>'ST', 'SUN'=>'RU', 'SUR'=>'SR', 'SVK'=>'SK',
			'SVN'=>'SI', 'SWE'=>'SE', 'SWZ'=>'SZ', 'SYC'=>'SC', 'SYR'=>'SY', 'TCA'=>'TC', 'TCD'=>'TD', 'TGO'=>'TG',
			'THA'=>'TH', 'TJK'=>'TJ', 'TKL'=>'TK', 'TKM'=>'TM', 'TLS'=>'TL', 'TON'=>'TO', 'TTO'=>'TT', 'TUN'=>'TN',
			'TUR'=>'TR', 'TUV'=>'TV', 'TWN'=>'TW', 'TZA'=>'TZ', 'UGA'=>'UG', 'UKR'=>'UA', 'UMI'=>'UM', 'URY'=>'UY',
			'USA'=>'US', 'UZB'=>'UZ', 'VAT'=>'VA', 'VCT'=>'VC', 'VEN'=>'VE', 'VGB'=>'VG', 'VIR'=>'VI', 'VNM'=>'VN',
			'VUT'=>'VU', 'WLF'=>'WF', 'WLS'=>'GB', 'WSM'=>'WS', 'YEM'=>'YE', 'ZAF'=>'ZA', 'ZMB'=>'ZM', 'ZWE'=>'ZW'
			);
		}
		// The country names can be specified in any language or in the chapman code.
		// Generate a combined list.
		if (!isset($country_to_iso3166)) {
			$country_to_iso3166=array();
			foreach ($iso3166 as $three=>$two) {
				$country_to_iso3166[UTF8_strtolower($three)]=$two;
			}
			loadLangFile('pgv_country');
			foreach (array_keys($pgv_language) as $LANGUAGE) {
				foreach ($countries as $code => $country) {
				if (array_key_exists($code, $iso3166)) {
						$country_to_iso3166[UTF8_strtolower($country)]=$iso3166[$code];
					}
				}
			}
		}
		print_map_charts($chart_shows, $chart_type, $x_as, $surname);
		break;
	case '8':
	case '9':
		print_sources_stats_chart($x_as);
		break;
	default:
		echo $pgv_lang["stpl"].$x_as.$pgv_lang["stplnoim"]."<br/>";
		exit;
	}
//}
echo "<br /><div class =\"center noprint\">";
echo "<input type=\"submit\" value=\"".$pgv_lang["back"]."\" onclick=\"javascript:history.go(-1);\" /><br /><br />";
echo "</div>\n";
print_footer();
?>