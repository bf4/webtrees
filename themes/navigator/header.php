<?php global $SEARCH_SPIDER, $TEXT_DIRECTION, $GEDCOM; ?>
<?php
$menubar = new MenuBar();
$username = getUserName();
$user = getUser($username);
?>
<div id="header" class="<?php print $TEXT_DIRECTION; ?>">
<table width="100%">
	<tr>
		<td>
			<a href="javascript: navigate" onclick="new Effect.toggle($('navbar'),'blind'); if (!firstClick) navFirstClick(); return false;">
			<img src="images/gedcom.gif" alt="Navigate" title="Navigate" border="0" />
			</a>
		</td>
		<td><?php print_gedcom_title_link(TRUE); ?> 
		<?php print_favorite_selector(); ?>
		</td>
		<td>
		<div><img src="themes/navigator/header.jpg" border="0" /></div>
		</td>
		<td><?php print_user_links(); ?></td>
		<td>
		<div id="langform"><?php if(empty($SEARCH_SPIDER)) { print_lang_form(); } ?>
		</div>
		<div id="themeform"><?php if(empty($SEARCH_SPIDER)) { print_theme_dropdown(); } ?>
		</div>
		</td>
	</tr>
</table>