<?php
global $pgv_lang, $TEXT_DIRECTION;
?>
<script type="text/javascript">
<!--
function ajaxLogin(frm) {
	username = frm.username.value;
	password = frm.password.value;
	jQuery("#tabs").tabs('url', selectedTab, 'individual.php?action=ajax&module=FamilySearch&pid=<?php echo $this->controller->pid?>&FSAction=login&username='+username+'&password='+password);
	jQuery("#tabs").tabs('load', selectedTab);
	return false;
}
//-->
</script>
<form method="post" action="module.php" onsubmit="return ajaxLogin(this);">
<input type="hidden" name="mod" value="FamilySearch" />
<input type="hidden" name="pgvaction" value="FSLogin" />
<table class="center facts_table width50">
	<tr>
		<td class="topbottombar" colspan="2">Login to New FamilySearch</td>
	</tr>
	<tr>
		<td
			class="descriptionbox <?php print $TEXT_DIRECTION; ?> wrap width50"><?php print $pgv_lang["username"]; ?></td>
		<td class="optionbox <?php print $TEXT_DIRECTION; ?>"><input
			type="text" name="username" value="" size="20" class="formField" /></td>
	</tr>
	<tr>
		<td
			class="descriptionbox <?php print $TEXT_DIRECTION; ?> wrap width50"><?php print $pgv_lang["password"]; ?></td>
		<td class="optionbox <?php print $TEXT_DIRECTION; ?>"><input
			type="password" name="password" size="20" class="formField" /></td>
	</tr>
	<tr>
		<td class="topbottombar" colspan="2"><input type="submit"
			value="<?php print $pgv_lang["login"]; ?>" />&nbsp;</td>
	</tr>
</table>
To use the FamilySearch module, you must login with your FamilySearch username and password.  
You can register for a FamilySearch username and password by going to <a href="https://new.familysearch.org" target="_blank">https://new.familysearch.org</a>
</form>
