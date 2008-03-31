<?php global $SEARCH_SPIDER, $TEXT_DIRECTION, $GEDCOM; ?>
<?php
$menubar = new MenuBar();

include_once('js/prototype.js.htm');
include_once('js/scriptaculous.js.htm');
?>
<div id="header" style=" z-index: 50;" class="<?php print $TEXT_DIRECTION; ?>">
<table width="100%">
	<tr>
		<td>
		<div><img src="themes/navigator/header.jpg" border="0" /></div>
		</td>
		<td>
			<a href="javascript: navigate" onclick="Effect.toggle('navbar','blind'); if (!firstClick) navFirstClick(); return false;">
			<img src="images/gedcom.gif" alt="Navigate" title="Navigate" border="0" />
			</a>
		</td>
		<td><?php print_gedcom_title_link(TRUE); ?> 
			<div id="langform"><?php if(empty($SEARCH_SPIDER)) { print_lang_form(1); } ?></div> 
		</td>
		<td><?php print_user_links(); ?></td>
		<td>
		<?php if(empty($SEARCH_SPIDER)) { ?>
					<form action="search.php" method="get">
						<input type="hidden" name="action" value="general" />
						<input type="hidden" name="topsearch" value="yes" />
						<input type="text" name="query" accesskey="<?php print $pgv_lang["accesskey_search"]?>" size="12" value="" onfocus="if (this.value == '<?php print $pgv_lang['search']?>') this.value=''; focusHandler();" onblur="if (this.value == '') this.value='<?php print $pgv_lang['search']?>';" />
						<input type="submit" name="search" value="<?php print $pgv_lang['search']?> &gt;" />
					</form>
				<?php } ?>
		<div id="langform"><?php print_favorite_selector(); ?></div>
		</td>
	</tr>
</table>
