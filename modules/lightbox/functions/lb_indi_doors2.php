<?php //  ----------    The following lines changed by Brian Holland for lightbox/album module   ----------------------------- ?>
<?php if (!file_exists("modules/googlemap/defaultconfig.php")) {  ?>
          <?php if (file_exists("modules/lightbox/album.php") && (!userCanEdit(getUserName())) ) {?>
          <dd id="door4"><a href="javascript:;" onclick="tabswitch(7); return false;" ><?php print "Album" ?></a></dd>
          <?php }else{ ?>
          <dd id="door4"><a href="javascript:;" onclick="tabswitch(4); return false;" ><?php print $pgv_lang["media"]?></a></dd>
          <?php } ?>
          <?php if ( file_exists("modules/lightbox/album.php") && (userCanEdit(getUserName())) ) {?>
          <dd id="door7"><a href="javascript:;" onclick="tabswitch(7); return false;" ><?php print "Album" ?></a></dd>
          <?php } ?>
<?php }elseif (file_exists("modules/googlemap/defaultconfig.php")) {  ?>
          <?php if (file_exists("modules/lightbox/album.php") && (!userCanEdit(getUserName())) ) {?>
          <dd id="door4"><a href="javascript:;" onclick="tabswitch(4); return false;" ><?php print "Album" ?></a></dd>
          <?php }else{ ?>
          <dd id="door4"><a href="javascript:;" onclick="tabswitch(4); return false;" ><?php print "Album" ?></a></dd>
          <?php } ?>
          <?php if (file_exists("modules/lightbox/album.php") && (userCanEdit(getUserName())) ) {?>
          <dd id="door4"><a href="javascript:;" onclick="tabswitch(4); return false;" ><?php print "Album" ?></a></dd>
          <?php } ?>
<?php } ?>
<?php //  --------------------------------------------------------------------------------------------------------------------  ?>

<dd id="door5"><a href="javascript:;" onclick="tabswitch(5); return false;" ><?php print $pgv_lang["relatives"]?></a></dd>
<dd id="door6"><a href="javascript:;" onclick="tabswitch(6); return false;" ><?php print $pgv_lang["research_assistant"]?></a></dd>
<?php if (file_exists("modules/googlemap/defaultconfig.php")) {?>
<dd id="door7"><a href="javascript:;" onclick="tabswitch(7); if (loadedTabs[7]) {ResizeMap(); ResizeMap();} return false;" ><?php print $pgv_lang["googlemap"]?></a></dd>
<?php }?>
<dd id="door0"><a href="javascript:;" onclick="tabswitch(0); if (loadedTabs[7]) {ResizeMap(); ResizeMap();} return false;" ><?php print $pgv_lang["all"]?></a></dd>

<?php //  ----------       The following lines added by Brian Holland for lightbox/album module      -------------------------  ?>
<?php if (!file_exists("modules/googlemap/defaultconfig.php") && (file_exists("modules/lightbox/album.php") ) ) {  ?>
 <dd id="door7"></dd> 
<?php }elseif (file_exists("modules/googlemap/defaultconfig.php") && (file_exists("modules/lightbox/album.php") ) ) { ?>
 <dd id="door8"></dd> 
<?php } ?>
<?php //  --------------------------------------------------------------------------------------------------------------------  ?>