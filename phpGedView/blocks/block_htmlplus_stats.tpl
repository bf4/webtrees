gedcom_stats_block|gedcom_stats_descr
<div class="gedcom_stats">
<span style="font-weight: bold"><a href="index.php?command=gedcom">#GEDCOM_TITLE#</a></span><br />
#pgv_lang[gedcom_created_using]##pgv_lang[gedcom_created_on2]#<br />
<table>
	<tr>
		<td valign="top" class="width20">
			<table cellspacing="1" cellpadding="0">
				<tr>
					<td class="facts_label">#stat_individuals# </td>
					<td class="facts_value">&nbsp;<a href="indilist.php?surname_sublist=no">#TOTAL_INDI#</a></td>
				</tr>
				<tr>
					<td class="facts_label">#stat_surnames# </td>
					<td class="facts_value">&nbsp;<a href="indilist.php?surname_sublist=yes">#TOTAL_SURNAMES#</a></td>
				</tr>
				<tr>
					<td class="facts_label">#stat_families# </td>
					<td class="facts_value">&nbsp;<a href="famlist.php">#TOTAL_FAM#</a></td>
				</tr>
				<tr>
					<td class="facts_label">#stat_sources# </td>
					<td class="facts_value">&nbsp;<a href="sourcelist.php">#TOTAL_SOUR#</a></td>
				</tr>
				<tr>
					<td class="facts_label">#stat_other# </td>
					<td class="facts_value">&nbsp;#TOTAL_OTHER#</td>
				</tr>
				<tr>
					<td class="facts_label">#stat_events# </td>
					<td class="facts_value">&nbsp;#TOTAL_EVENTS#</td>
				</tr>
				<tr>
					<td class="facts_label">#stat_users# </td>
					<td class="facts_value">&nbsp;#TOTAL_USERS#</td>
				</tr>
				<tr>
					<td class="facts_label">#stat_media# </td>
					<td class="facts_value">&nbsp;#TOTAL_MEDIA#</td>
				</tr>
			</table>
		</td>
		<td><br /></td>
		<td valign="top">
			<table cellspacing="1" cellpadding="0" border="0">
				<tr>
					<td class="facts_label" valign="top">#stat_earliest_birth#</td>
					<td class="facts_value">&nbsp;#FIRST_BIRTH_YEAR#&nbsp;</td>
					<td class="facts_value" valign="top">#FIRST_BIRTH#</td>
				</tr>
				<tr>
					<td class="facts_label" valign="top">#stat_latest_birth#</td>
					<td class="facts_value">&nbsp;#LAST_BIRTH_YEAR#&nbsp;</td>
					<td class="facts_value" valign="top">#LAST_BIRTH#</td>
				</tr>
				<tr>
					<td class="facts_label" valign="top">#stat_earliest_death#</td>
					<td class="facts_value">&nbsp;#FIRST_DEATH_YEAR#&nbsp;</td>
					<td class="facts_value" valign="top">#FIRST_DEATH#</td>
				</tr>
				<tr>
					<td class="facts_label" valign="top">#stat_latest_death#</td>
					<td class="facts_value">&nbsp;#LAST_DEATH_YEAR#&nbsp;</td>
					<td class="facts_value" valign="top">#LAST_DEATH#</td>
				</tr>
				<tr>
					<td class="facts_label" valign="top">#stat_longest_life#</td>
					<td class="facts_value">&nbsp;#LONG_LIFE_AGE#&nbsp;</td>
					<td class="facts_value" valign="top">#LONG_LIFE#</td>
				</tr>
				<tr>
					<td class="facts_label" valign="top">#stat_avg_age_at_death#</td>
					<td class="facts_value" valign="top">&nbsp;#AVG_LIFE#&nbsp;</td>
					<td class="facts_value"></td>
				</tr>
				<tr>
					<td class="facts_label" valign="top">#stat_most_children#</td>
					<td class="facts_value">&nbsp;#MOST_CHILD_TOTAL#&nbsp;</td>
					<td class="facts_value" valign="top">#MOST_CHILD#</td>
				</tr>
				<tr>
					<td class="facts_label" valign="top">#stat_average_children#</td>
					<td class="facts_value" valign="top">&nbsp;#AVG_CHILD#&nbsp;</td>
					<td class="facts_value"></td>
				</tr>
			</table>
		</td>
	</tr>
</table><br />
<span style="font-weight: bold">#common_surnames#</span><br />
#COMMON_SURNAMES#
</div>