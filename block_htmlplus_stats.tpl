gedcom_stats_block|gedcom_stats_descr
<div class="gedcom_stats">
<b><a href="index.php?command=gedcom">#GEDCOM_TITLE#</a></b><br />
#pgv_lang[gedcom_created_using]##pgv_lang[gedcom_created_on2]#<br />
<table>
	<tr>
		<td valign="top" class="width20">
			<table cellspacing="1" cellpadding="0">
				<tr>
					<td>#stat_individuals# </td>
					<td><b>&nbsp;<a href="indilist.php?surname_sublist=no">#TOTAL_INDI#</a></b></td>
				</tr>
				<tr>
					<td>#stat_surnames# </td>
					<td><b>&nbsp;<a href="indilist.php?surname_sublist=yes">#TOTAL_SURNAMES#</a></b></td>
				</tr>
				<tr>
					<td>#stat_families# </td>
					<td><b>&nbsp;<a href="famlist.php">#TOTAL_FAM#</a></b></td>
				</tr>
				<tr>
					<td>#stat_sources# </td>
					<td><b>&nbsp;<a href="sourcelist.php">#TOTAL_SOUR#</a></b></td>
				</tr>
				<tr>
					<td>#stat_other# </td>
					<td><b>&nbsp;#TOTAL_OTHER#</b></td>
				</tr>
				<tr>
					<td>#stat_events# </td>
					<td><b>&nbsp;#TOTAL_EVENTS#</b></td>
				</tr>
				<tr>
					<td>#stat_users# </td>
					<td><b>&nbsp;#TOTAL_USERS#</b></td>
				</tr>
			</table>
		</td>
		<td><br /></td>
		<td valign="top">
			<table cellspacing="0" cellpadding="1" border="0">
				<tr>
					<td valign="top">#stat_earliest_birth#</td>
					<td>&nbsp;<span style="font-weight: bold">#FIRST_BIRTH_YEAR#</span>&nbsp;</td>
					<td valign="top">#FIRST_BIRTH#</td>
				</tr>
				<tr>
					<td valign="top">#stat_latest_birth#</td>
					<td>&nbsp;<span style="font-weight: bold">#LAST_BIRTH_YEAR#</span>&nbsp;</td>
					<td valign="top">#LAST_BIRTH#</td>
				</tr>
				<tr>
					<td valign="top">#stat_longest_life#</td>
					<td>&nbsp;<span style="font-weight: bold">#LONG_LIFE_AGE#</span>&nbsp;</td>
					<td valign="top">#LONG_LIFE#</td>
				</tr>
				<tr>
					<td valign="top">#stat_avg_age_at_death#</td>
					<td valign="top">&nbsp;<span style="font-weight: bold">#AVG_LIFE#</span>&nbsp;</td>
					<td></td>
				</tr>
				<tr>
					<td valign="top">#stat_most_children#</td>
					<td>&nbsp;<span style="font-weight: bold">#MOST_CHILD_TOTAL#</span>&nbsp;</td>
					<td valign="top">#MOST_CHILD#</td>
				</tr>
				<tr>
					<td valign="top">#stat_average_children#</td>
					<td valign="top">&nbsp;<span style="font-weight: bold">#AVG_CHILD#</span>&nbsp;</td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
</table><br />
<b>#common_surnames#</b><br />
#COMMON_SURNAMES#
</div>