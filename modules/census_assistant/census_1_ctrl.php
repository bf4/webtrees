<?php
$pid = safe_get('pid');

$censevent = new Event("1 CENS\n2 DATE 03 JAN 1901");
$censdate  = $censevent->getDate();
$censyear  = $censdate->date1->y;

$person=Person::getInstance($pid);
// print_r($person);
$nam = $person->getAllNames();
if (PrintReady($person->getDeathYear()) == 0) { $DeathYr = ""; }else{ $DeathYr = PrintReady($person->getDeathYear()); }
if (PrintReady($person->getBirthYear()) == 0) { $BirthYr = ""; }else{ $BirthYr = PrintReady($person->getBirthYear()); }
$wholename = rtrim($nam[0]['givn'],'*')."&nbsp;".$nam[0]['surn'];

$currpid=$pid;
global $summary;

// Various Debugs =========================================
// var_dump($person->getAllNames()); 

/*
$fred = ($person->getAllBirthPlaces());
$fredrev = explode(", ", $fred[0]);
$fredrev = array_reverse($fredrev);
$fredrev = implode(", ", $fredrev);
echo $fred[0];
echo "<br />";
echo $fredrev;
*/

//=========================================================



echo "<table border=0><tr>";
echo "<td width=\"20%\">";
echo "<center><font size=\"3\"><b>" . $censyear . " Census Assistant</b></font></center>";
echo "</td><td width=\"48%\">";
echo "<center><font size=\"3\"><b>&nbsp;&nbsp; </font><font size=\"2\">";
echo " Head of Family &nbsp;&nbsp;</font><font size=\"3\">:";
echo " &nbsp;&nbsp;" . $wholename . "&nbsp; (" . $pid . ")";
// echo "&nbsp;&nbsp;  :";
// echo " &nbsp;</font><font size=\"2\">";
// echo $BirthYr . " - " . $DeathYr ;
// echo " &nbsp;&nbsp;</b></font>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;</td><td width=\"35%\">";
if ($summary) {
	echo '<table><tr><td width="10"><br /></td><td valign="top" colspan="', $maxcols-$col, '"><font size="1">', $summary, '</font></td></tr></table>';
}
echo "</td>";
//echo "<td>&nbsp;</td>";
echo "</tr></table>";


?>

<form name="form" action="modules/census_assistant/tableaddrow_nw.html" method="get">
<!-- <form method="post" name="form" onsubmit="return checknames(this);" action="module.php?mod=census_assistant&pgvaction=census_a&pid=<?php print $pid; ?>"> -->

<table class="facts_table" width="100%" border=0>
	<tr>
		<td valign="top" width="60%">
			<?php 
			//-- Census & Source Information Area ============================================= 
			include('modules/census_assistant/census_2_source_input.php');
			?>
		</td>
		
		<td rowspan="2" valign="top" width ="220">
			<?php 
			//-- Search  and Add Family Members Area ========================================= 
			include('modules/census_assistant/census_3_search_add.php'); ?>
		</td>
	</tr>
	<tr>
		<td align="left" valign="bottom" width="80%">
			<?php
			//-- Proposed Census Text Area ==================================================
			include('modules/census_assistant/census_4_text.php');
			?>
		</td>
	</tr>
	<tr>
		<td colspan=2 align="center" width="100%">
			<?php
			//-- Census Text Input Area ===========================================================
			?>
			<br />
			<table width="95%" border="0">
				<tr>
					<td colspan="2" align="center">

						<table width="100%" border='3' cellspacing="1" >
							<tr>
								<td colspan="12" id="5678" class="option_box" style="border: 0px solid transparent;">
									<?php
									include('modules/census_assistant/census_5_input.php');
									?> 
								</td>
							</tr>
						</table>

					</td>
				</tr>
			</table>
		</td>
	</tr>

</table>
</form>
