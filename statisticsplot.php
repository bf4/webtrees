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

function bimo($i) {
	global $x_as,$y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;

	$m= $persgeg[$i]["mbirth"];
	if ($z_as == 301) $ys= $persgeg[$i]["sex"]-1;
	else $ys= $persgeg[$i]["ybirth"];

 	if ($m > 0) {
		fill_ydata($ys,$m-1,1);
		$n1++;
	}
}

function bimo1($i) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;

	if (isset($famgeg[$i])) {
		$m= $famgeg[$i]["mbirth1"];
		if ($z_as == 301) $ys= $famgeg[$i]["sex1"]-1;
		else $ys= $famgeg[$i]["ybirth1"];

		if ($m > 0) {
			fill_ydata($ys,$m-1,1);
			$n1++;
		}
	}
}

function demo($i) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;

	$m= $persgeg[$i]["mdeath"];
	if ($z_as == 301) $ys= $persgeg[$i]["sex"]-1;
	else $ys= $persgeg[$i]["ybirth"];
	if ($m > 0) {
		fill_ydata($ys,$m-1,1);
		$n1++;
	}
}

function mamo($i) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;

	if (isset($famgeg[$i])) {
		$m= $famgeg[$i]["mmarr"]; $y= $famgeg[$i]["ymarr"];
		if ($m > 0) {
			fill_ydata($y,$m-1,1);
			$n1++;
		}
	}
}

function mamo1($i) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;

	if (isset($famgeg[$i]["mmarr1"])) {
		$m= $famgeg[$i]["mmarr1"]; $y= $famgeg[$i]["ymarr1"];
		if ($m > 0) {
			fill_ydata($y,$m-1,1);
			$n1++;
		}
	}
}

function mamam($i) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;

	if (isset($famgeg[$i])) {
		$m= $famgeg[$i]["mmarr"]; $y= $famgeg[$i]["ymarr"];
		if ($m > 0) {
			$m2= $famgeg[$i]["mbirth1"]; $y2= $famgeg[$i]["ybirth1"];
			if ($z_as == 301) $ys= $famgeg[$i]["sex1"] - 1;
			else $ys= $famgeg[$i]["ybirth1"];
			if ($m2 > 0) {
				$mm= ($y2 - $y) * 12 + $m2 - $m;
				fill_ydata($ys,$mm,1);
				$n1++;
			}
		}
	}
}

function agbi($i) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;

	$yb= $persgeg[$i]["ybirth"];
	$yd= $persgeg[$i]["ydeath"];
	if (($yb > 0) and ($yd > 0)) {
		$age= $yd - $yb;
		if ($z_as == 301) $yb= $persgeg[$i]["sex"]-1;
		fill_ydata($yb,$age,1);
		$n1++;
	}
}

function agde($i) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;

	$yb= $persgeg[$i]["ybirth"];
	$yd= $persgeg[$i]["ydeath"];
	if (($yb > 0) and ($yd > 0)) {
		$age= $yd - $yb;
		if ($z_as == 301) $yd= $persgeg[$i]["sex"]-1;
		fill_ydata($yd,$age,1);
		$n1++;
	}
}

function agma($i) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;

	if (isset($famgeg[$i])) {
		$ym= $famgeg[$i]["ymarr"];
		if ($ym > 0) {
			$xfather= $famgeg[$i]["male"];
			$xmother= $famgeg[$i]["female"];
			$j= $key2ind[$xfather];
			$j2= $key2ind[$xmother];
			$ybirth= -1; $ybirth2= -1; $age= 0; $age2= 0;
			if (isset($j)  and ($xfather !== "")) {$ybirth= $persgeg[$j]["ybirth"];}
			if (isset($j2) and ($xmother !== "")) {$ybirth2= $persgeg[$j2]["ybirth"];}
			$z= $ym; $z1= $ym;
			if ($z_as == 301) {$z= 0; $z1= 1;}
			if ($ybirth > -1) {
				$age= $ym - $ybirth;
				fill_ydata($z,$age,1);
				$n1++;
			}
			if ($ybirth2 > -1) {
				$age2= $ym - $ybirth2;
				fill_ydata($z1,$age2,1);
				$n1++;
			}
		}
	}
}

function agma1($i) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;

	if (isset($famgeg[$i])) {
		$ym= $famgeg[$i]["ymarr"];
		if ($ym > -1) {
			$xfather= $famgeg[$i]["male"];
			$xmother= $famgeg[$i]["female"];
			$j= $key2ind[$xfather];
			$j2= $key2ind[$xmother];
			$ybirth= -1; $ybirth2= -1; $age= 0; $age2= 0;
			if (isset($j)  and ($xfather !== "")) {
				$ybirth=  $persgeg[$j]["ybirth"];
				$ymf= $persgeg[$j]["ymarr1"];
			}
			if (isset($j2) and ($xmother !== "")) {
				$ybirth2= $persgeg[$j2]["ybirth"];
				$ymm= $persgeg[$j2]["ymarr1"];
			}
			$z= $ym; $z1= $ym;
			if ($z_as == 301) {
				$z= 0;
				$z1= 1;
			}
			if (($ybirth > -1) and ($ymf > -1) and ($ym == $ymf)) {
				$age= $ym - $ybirth;
				fill_ydata($z,$age,1);
				$n1++;
			}
			if (($ybirth2 > -1) and ($ymm > -1) and ($ym == $ymm)) {
				$age2= $ym - $ybirth2;
				fill_ydata($z1,$age2,1);
				$n1++;
			}
		}
	}
}


function nuch($i) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;

	if (isset($famgeg[$i])) {
		$c= $famgeg[$i]["mmarr"];
		$y= $famgeg[$i]["ymarr"];
		if ($c>0 && $y>0) {
			fill_ydata($y,$c,1);
			$n1++;
		}
	}
}


function fill_ydata($z,$x,$val) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;
	global $legend, $xdata, $ydata, $xmax, $xgrenzen, $zmax, $zgrenzen, $xgiven, $zgiven, $percentage, $man_vrouw;
	global $pgv_lang;
//--	calculate index $i out of given z value
//--	calculate index $j out of given x value

	if ($xgiven) $j= $x;
	else {
		$j=0;
		while (($x > $xgrenzen[$j]) and ($j < $xmax)) {
			$j++;
		}
	}
	if ($zgiven) $i= $z;
	else {
		$i=0;
		while (($z > $zgrenzen[$i]) and ($i < $zmax)) {
			$i++;
		}
	}
	if (isset($ydata[$i][$j])) $ydata[$i][$j] += $val;
	else $ydata[$i][$j] = $val;
}

function myplot($mytitle,$n,$xdata,$xtitle,$ydata,$ytitle,$legend) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;
	global $legend, $xdata, $ydata, $xmax, $xgrenzen, $zmax, $zgrenzen, $xgiven, $zgiven, $percentage, $man_vrouw;
	global $pgv_lang, $SERVER_URL;
	global $ymax, $ymaxgraph, $scalefactor, $datastring, $imgurl;
	//Google Chart API only allows text encoding for numbers less than 100
	//and it does not allow adjusting the y-axis range, so we must find the maximum y-value
	//in order to adjust beforehand by changing the numbers

	if ($man_vrouw==1) $stop=2;
	else $stop=count($ydata);
	$yprocentmax = 0;
	if ($percentage) {
		for($i=0; $i<$stop;$i++) {
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
					$yprocent=round($ymax/$ytotal*100,1);
				if ($yprocentmax < $yprocent) $yprocentmax = $yprocent;
			}
		}
		$ymax = $yprocentmax;
		if ($ymax>0) $scalefactor = 100.0/$ymax;
		else $scalefactor = 0;
		$datastring = "chd=t:";
		for($i=0; $i<$stop;$i++) {
			if (isset($ydata[$i])) {
				for($j=0; $j<count($ydata[$i]); $j++){
					if ($yt[$i] > 0) {
						$datastring .= round($ydata[$i][$j]/$yt[$i]*100*$scalefactor,1);
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
		for($i=0; $i<$stop;$i++) {
			for($j=0; $j<count($ydata[$i]); $j++) {
				if ($ydata[$i][$j]>$ymax) {
					$ymax = $ydata[$i][$j];
				}
			}
		}
		if ($ymax>0) $scalefactor = 100.0/$ymax;
		else $scalefactor = 0;
		$datastring = "chd=t:";
		for($i=0; $i<$stop;$i++) {
			for($j=0; $j<count($ydata[$i]); $j++){
				$datastring .= round($ydata[$i][$j]*$scalefactor,1);
				if (!($j == (count($ydata[$i])-1))){
					$datastring .= ",";
				}
			}
			if (!($i == ($stop-1))) {
				$datastring .= "|";
			}
		}
	}
	$colors= array("0000FF","FF7000","905030","FF0000","00FF00","F0F000","FFC0CB","9F00FF");
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

	$imgurl = "http://chart.apis.google.com/chart?cht=bvg&chs=900x300&chf=bg,s,ffffff00|c,s,ffffff00&chtt=".$title."&".$datastring."&".$colorstring."&chbh=";
	if (count($ydata) > 2) $imgurl .="5,1";
	else if (count($ydata) < 2) $imgurl .="25,1";
	else $imgurl .="15,3";
	$imgurl .= "&chxt=x,x,y,y&chxl=0:|";
	for($i=0; $i<count($xdata); $i++) {
		$imgurl .= $xdata[$i]."|";
	}

	$imgurl .= "1:||".$xtitle."|2:|";
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
				$imgurl .= round($ymax*$i/($ymax),0)."|";
			}
		}
		else {
			for($i=1; $i<11; $i++) {
				$imgurl .= round($ymax*$i/10,0)."|";
			}
		}
		$imgurl .= "3:||".$ytitle."|";
	}
	//only show legend if y-data is non-2-dimensional
	if (count($ydata) > 1) {
		$imgurl .="&chdl=";
		for($i=0; $i<count($legend); $i++){
			$imgurl .= $legend[$i];
			if (!($i == (count($legend)-1))){
				$imgurl .= "|";
			}
		}
	}
	echo "<center>";
	echo "<img src=\"".encode_url($imgurl)."\" width=\"900\" height=\"300\"  border=\"0\" alt=\"".$mytitle."\" title=\"$mytitle\"/>";
	echo "</center><br /><br />";
}

function get_plot_data() {
	global $GEDCOM, $GEDCOMS, $INDEX_DIRECTORY, $BUILDING_INDEX;
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;
	global $legend, $xdata, $ydata, $xmax, $xgrenzen, $zmax, $zgrenzen, $xgiven, $zgiven, $percentage, $man_vrouw;
	global $pgv_lang;

	$indexfile = $INDEX_DIRECTORY.$GEDCOM."_statistiek.php";
	if (file_exists($indexfile)) {
		$fp = fopen($indexfile, "rb");
		$fcontents = fread($fp, filesize($indexfile));
		fclose($fp);
		$lists = unserialize($fcontents);
		unset($fcontents);
		$famgeg = $lists["famgeg"];
		$persgeg = $lists["persgeg"];
		$key2ind = $lists["key2ind"];
		$nrfam= count($famgeg);
		$nrpers= count($persgeg);
	}
}

function calc_axis($xas_grenzen) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;
	global $legend, $xdata, $ydata, $xmax, $xgrenzen, $zmax, $zgrenzen, $xgiven,$zgiven, $percentage, $man_vrouw;
	global $pgv_lang;

	//calculate xdata and zdata elements out of given POST values
	$hulpar= array();

	$hulpar= explode(",",$xas_grenzen);
	$i=1;
	$xdata[0]= "<" . "$hulpar[0]"; $xgrenzen[0]= $hulpar[0]-1;
	while (isset($hulpar[$i])) {
		$i1= $i-1;
		if (($hulpar[$i] - $hulpar[$i1]) == 1) $xdata[$i]= "$hulpar[$i1]";
		else $xdata[$i]= "$hulpar[$i1]" . "-" . "$hulpar[$i]";
		$xgrenzen[$i]= $hulpar[$i]; $i++;
	}
	$xmax= $i;
	$xmax1= $xmax-1;
	$xdata[$xmax]= ">" . "$hulpar[$xmax1]";
	$xgrenzen[$xmax]= 10000;
	$xmax= $xmax+1;
	if ($xmax > 20) $xmax=20;
}

function calc_legend($grenzen_zas) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;
	global $legend, $xdata, $ydata, $xmax, $xgrenzen, $zmax, $zgrenzen, $xgiven,$zgiven, $percentage, $man_vrouw;
	global $pgv_lang;

// calculate the legend values
	$hulpar= array();
//-- get numbers out of $grenzen_zas

	$hulpar= explode(",",$grenzen_zas);
	$i=1;
	$legend[0]= "<" . "$hulpar[0]"; $zgrenzen[0]= $hulpar[0]-1;
	while (isset($hulpar[$i])) {
		$i1= $i-1;
		$legend[$i]= "$hulpar[$i1]" . "-" . "$hulpar[$i]";
		$zgrenzen[$i]= $hulpar[$i];
		$i++;
	}
	$zmax= $i; $zmax1= $zmax-1;
	$legend[$zmax]= ">" . "$hulpar[$zmax1]";
	$zgrenzen[$zmax]= 10000;
	$zmax= $zmax+1;
	if ($zmax > 8) $zmax=8;
}

//--------------------nr,-----bron ,xgiven,zgiven,title, xtitle,ytitle,grenzen_xas, grenzen-zas,functie,
function set_params($current, $indfam, $xg,  $zg, $titstr,  $xt, $yt, $gx, $gz, $myfunc) {
	global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind, $n1;
	global $legend, $xdata, $ydata, $xmax, $xgrenzen, $zmax, $zgrenzen, $xgiven, $zgiven, $percentage, $man_vrouw;
	global $pgv_lang;

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

	if ($x_as == $current) {
		$xgiven= $xg; $zgiven= $zg;
		$title= $pgv_lang["$titstr"];
		$xtitle= $pgv_lang["$xt"]; $ytitle= $pgv_lang["stplnumbers"];
		$grenzen_xas= $gx; $grenzen_zas= $gz;
		if ($xg == true) {
			$xdata=$monthdata;
			$xmax=12;
		} else calc_axis($grenzen_xas);
		if ($z_as != 300 && $z_as != 301) calc_legend($grenzen_zas);

		$percentage= false;
		$ytitle= $pgv_lang["stplnumbers"];
		if ($y_as == 201) {
			$percentage= false;
			$ytitle= $pgv_lang["stplnumbers"];
		} else if ($y_as == 202) {
			$percentage= true;
			$ytitle= $pgv_lang["stplperc"];
		}

		$man_vrouw= false;
		if ($z_as == 300) {
			$zgiven= false;
			$legend[0]= "all";
			$zmax=1;
			$zgrenzen[0]= 100000;
		} else if ($z_as == 301) {
			$man_vrouw= true;
			$zgiven= true;
			$legend[0]= $pgv_lang["male"];
			$legend[1]= $pgv_lang["female"];
			$zmax=2;
			$xtitle= $xtitle . $pgv_lang["stplmf"];
		} else if ($z_as == 302) $xtitle= $xtitle . $pgv_lang["stplipot"];


//-- reset the data array
		for($i=0; $i<$zmax; $i++) {
			for($j=0; $j<$xmax; $j++) {
				$ydata[$i][$j]= 0;
			}
		}

		if ($indfam == "IND") $nrmax= $nrpers;
		else $nrmax= $nrfam;
		if (!function_exists($myfunc)) {
			echo "not implemented function" . $myfunc . "<br/>";
			exit;
		}
		for ($i=0; $i < $nrmax; $i++) {
			$myfunc($i);
		}
		$hstr= $title . " \n" . $pgv_lang["stplnumof"] . " N=" . $n1 . " (max= " . $nrmax. ").";
		myplot($hstr,$zmax,$xdata,$xtitle,$ydata,$ytitle,$legend);
	}
}

//--	========= start of main program =========
global $x_as, $y_as, $z_as, $nrfam, $famgeg, $nrpers, $persgeg, $key2ind;
global $legend, $xdata, $ydata, $xmax, $xgrenzen, $zmax, $zgrenzen, $xgiven, $zgiven, $percentage, $man_vrouw;


$action	= safe_REQUEST($_REQUEST, 'action', PGV_REGEX_XREF);

$legend= array();
$xdata= array();
$ydata= array();
$xgrenzen= array();
$zgrenzen= array();
$famgeg = array();
$persgeg= array();
$key2ind= array();

if ($action=="update") {
	if (!isset($_POST)) $_POST = $HTTP_POST_VARS;
	$x_as= $_POST["x-as"];
	$y_as= $_POST["y-as"];
	$z_as= $_POST["z-as"];
	$xas_grenzen_leeftijden= $_POST["xas-grenzen-leeftijden"];
	$xas_grenzen_maanden= $_POST["xas-grenzen-maanden"];
	$xas_grenzen_aantallen= $_POST["xas-grenzen-aantallen"];
	$zas_grenzen_periode= $_POST["zas-grenzen-periode"];

	$_SESSION[$GEDCOM."statTicks"]["xasGrLeeftijden"] = $xas_grenzen_leeftijden;
	$_SESSION[$GEDCOM."statTicks"]["xasGrMaanden"] = $xas_grenzen_maanden;
	$_SESSION[$GEDCOM."statTicks"]["xasGrAantallen"] = $xas_grenzen_aantallen;
	$_SESSION[$GEDCOM."statTicks"]["zasGrPeriode"] = $zas_grenzen_periode;


	// Save the input variables
	$savedInput = array();
	$savedInput["x_as"] = $x_as;
	$savedInput["y_as"] = $y_as;
	$savedInput["z_as"] = $z_as;
	$savedInput["xas_grenzen_leeftijden"] = $xas_grenzen_leeftijden;
	$savedInput["xas_grenzen_maanden"] = $xas_grenzen_maanden;
	$savedInput["xas_grenzen_aantallen"] = $xas_grenzen_aantallen;
	$savedInput["zas_grenzen_periode"] = $zas_grenzen_periode;
	$_SESSION[$GEDCOM."statisticsplot"] = $savedInput;
	unset($savedInput);
} else {
	// Recover the saved input variables
	$savedInput = $_SESSION[$GEDCOM."statisticsplot"];
	$x_as = $savedInput["x_as"];
	$y_as = $savedInput["y_as"];
	$z_as = $savedInput["z_as"];
	$xas_grenzen_leeftijden = $savedInput["xas_grenzen_leeftijden"];
	$xas_grenzen_maanden = $savedInput["xas_grenzen_maanden"];
	$xas_grenzen_aantallen = $savedInput["xas_grenzen_aantallen"];
	$zas_grenzen_periode = $savedInput["zas_grenzen_periode"];
	unset($savedInput);
}
	print_header($pgv_lang["statistiek_list"]);
	echo "\n\t<center><h2>".$pgv_lang["statistiek_list"]."</h2>\n\t";
	echo "</center><br />";

	$nrpers=$_SESSION[$GEDCOM."nrpers"];
	$nrfam=$_SESSION[$GEDCOM."nrfam"];
	$nrman=$_SESSION[$GEDCOM."nrman"];
	$nrvrouw=$_SESSION[$GEDCOM."nrvrouw"];

	get_plot_data();
	error_reporting(E_ALL ^E_NOTICE);
//-- no errors because then I cannot plot

//-- out of range values
	if (($x_as <  11) or ($x_as >  21)) {
		echo $pgv_lang["stpl_type"] .$x_as . $pgv_lang["stplnoim"]  . "<br/>";
		exit;
	}
	if (($y_as < 201) or ($y_as > 202)) {
		echo $pgv_lang["stpl_type"] .$y_as . $pgv_lang["stplnoim"]  . "<br/>";
		exit;
	}
	if (($z_as < 300) or ($z_as > 302)) {
		echo $pgv_lang["stpl_type"] .$z_as . $pgv_lang["stplnoim"]  . "<br/>";
		exit;
	}

	$xstr="";
	$ystr="";
//-- Set params for request out of the information for plot
	$g_xas= "1,2,3,4,5,6,7,8,9,10,11,12"; //should not be needed. but just for month
	$xgl= $xas_grenzen_leeftijden;
	$xgm= $xas_grenzen_maanden;
	$xga= $xas_grenzen_aantallen;
	$zgp= $zas_grenzen_periode;

//-- end of setting variables

//---------nr,bron ,xgiven,zgiven,	title,      xtitle,   ytitle, grenzen_xas, grenzen-zas,,
set_params(11,"IND", true,  false, "stat_11_mb",  "stplmonth", $y_as, $g_xas, $zgp,"bimo");  //plot aantal geboorten per maand
set_params(12,"IND", true,  false, "stat_12_md",  "stplmonth", $y_as, $g_xas, $zgp,"demo");  //plot aantal overlijdens per maand
set_params(13,"FAM", true,  false, "stat_13_mm",  "stplmonth", $y_as, $g_xas, $zgp,"mamo");  //plot aantal huwelijken per maand
set_params(14,"FAM", true,  false, "stat_14_mb1", "stplmonth", $y_as, $g_xas, $zgp,"bimo1"); //plot aantal 1e geboorten per huwelijk per maand
set_params(15,"FAM", true,  false, "stat_15_mm1", "stplmonth", $y_as, $g_xas, $zgp,"mamo1"); //plot 1e huwelijken per maand
set_params(16,"FAM", false, false, "stat_16_mmb", "stplmarrbirth",$y_as, $xgm,$zgp,"mamam"); //plot tijd tussen 1e geboort en huwelijksdatum
set_params(17,"IND", false, false, "stat_17_arb", "stplage",   $y_as, $xgl,   $zgp,"agbi");  //plot leeftijd t.o.v. geboortedatum
set_params(18,"IND", false, false, "stat_18_ard", "stplage",   $y_as, $xgl,   $zgp,"agde");  //plot leeftijd t.o.v. overlijdensdatum
set_params(19,"FAM", false, false, "stat_19_arm", "stplage",   $y_as, $xgl,   $zgp,"agma");  //plot leeftijd op de huwelijksdatum
set_params(20,"FAM", false, false, "stat_20_arm1","stplage",   $y_as, $xgl,   $zgp,"agma1"); //plot leeftijd op de 1e huwelijksdatum
set_params(21,"FAM", false, false, "stat_21_nok", "stplnumbers",$y_as,$xga,   $zgp,"nuch");  //plot plot aantal kinderen in een maand

echo "<div class =\"center\">";
echo "<input type=\"submit\" value=\"".$pgv_lang["back"]."\" onclick=\"javascript:history.go(-1);\"><br /><br />";
echo "</div>\n";
print_footer();
?>
