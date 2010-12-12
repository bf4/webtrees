<?php
/**
 * Administrative User Interface.
 *
 * Provides links for administrators to get to other administrative areas of the site
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2010  PGV Development Team
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
 * @subpackage Administration
 * @version $Id: user_info.php 9190 2010-07-28 02:50:49Z nigel $
 */

$INDEX_DIRECTORY=get_site_setting('INDEX_DIRECTORY');


echo '<h2>', i18n::translate('Family tree statistics'), '</h2>';
echo '<h4><a href="statistics.php">', i18n::translate('[Click here for more details on the Statistics page]'), '</a></h4>';

$gedcom_titles=get_gedcom_titles();
foreach ($gedcom_titles as $gedcom_title) {
	$stats = new stats($gedcom_title->gedcom_name);
	
	echo '<table id="tree_stats">
			<tr>
				<th colspan="3">', $gedcom_title->gedcom_title, '</th>
			</tr>
			<tr>
				<td width="20px">&nbsp;</td>
				<td>', i18n::translate('Total individuals'), ':</td>
				<td>', $stats->totalIndividuals(), '</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>', i18n::translate('Total families'), ':</td>
				<td>', $stats->totalFamilies(), '</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>', i18n::translate('Earliest birth'), ':</td>
				<td>', $stats->firstBirthYear(), '</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>', i18n::translate('Latest birth'), ':</td>
				<td>', $stats->lastBirthYear(), '</td>
			</tr>
		</table>';
}

