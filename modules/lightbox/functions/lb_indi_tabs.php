<?php if ( file_exists("modules/googlemap/defaultconfig.php") && file_exists("modules/lightbox/album.php") && !userCanEdit(getUserName()) ) { ?>
       var tabid = new Array('0', 'facts','notes','sources','lightbox2','relatives','researchlog','googlemap');
       var loadedTabs = new Array(false,false,false,false,false,false,false,false);
<?php }else if (file_exists("modules/googlemap/defaultconfig.php") && file_exists("modules/lightbox/album.php")) { ?>
       var tabid = new Array('0', 'facts','notes','sources','media','relatives','researchlog','googlemap','lightbox2');
       var loadedTabs = new Array(false,false,false,false,false,false,false,false,false);
<?php }else if (file_exists("modules/googlemap/defaultconfig.php") && !file_exists("modules/lightbox/album.php")) { ?>
       var tabid = new Array('0', 'facts','notes','sources','media','relatives','researchlog','googlemap');
       var loadedTabs = new Array(false,false,false,false,false,false,false,false);
<?php }else if (!file_exists("modules/googlemap/defaultconfig.php") && file_exists("modules/lightbox/album.php")) { ?>
       var tabid = new Array('0', 'facts','notes','sources','media','relatives','researchlog','lightbox2');
       var loadedTabs = new Array(false,false,false,false,false,false,false,false);
<?php }else{ ?>
       var tabid = new Array('0', 'facts','notes','sources','media','relatives','researchlog');
       var loadedTabs = new Array(false,false,false,false,false,false,false);
<?php } ?>