<?php
require_once("config.php");
require_once("includes/treenav_class.php");

$zoom = 0;
$rootid = '';
if (isset($_REQUEST['zoom'])) $zoom = $_REQUEST['zoom'];
if (isset($_REQUEST['rootid'])) $rootid = $_REQUEST['rootid'];
$nav = new TreeNav($rootid, $zoom);

print_header('test');
$nav->drawViewport('', "", "450px");
print_footer();
?>