<?php
//  your default rootid assigned to new users to be created by interface
$def_rootid = "";

// by default can new users act as administrators
$def_canadmin = false;

// can new users use the edit feature
$def_canedit = "no";

// advise this be set to yes - if they are in postnuke I assume they are verified
$def_verified = "yes";

// do you want them to have immediate accees ? 
// if not then set to no and admin will have to verify - additional work for admin
$def_verified_by_admin = "yes";

// this will allow you to make sure that new users are not created if they don't
// already exist in phpGedView. This allows total control - but adds work to 
// the admin
$def_create_user = "yes";

// language - one of the supported languages to be used by default
$def_language = "english";

// default password to be allocated until they reset it in My Account - 
// allows them to login directly rather than through postnuke
$def_upass = "phpgedview";

// default gedview - set to yours
$def_gedcom = "carey.ged";

// default contact method
// can be mailto, 
// none, 
// messaging (phpGedview Internal) 
// or messaging2 (internal + email)
$def_contact_method = "messaging2";

// define the default phpGedView theme for your new user
// this has to be in the form themes/themename - ie directory theme is in
// Note that this must have a slash / on the end or you will get Default Theme
$def_theme = ""; 

// where is phpGedView? If the phpGedView progs are in this directory then leave blank
// else point to the directory where they are.
// if you want to mix the interface modules together with phpGedView remember to rename the interface index to
// something else like pgvindex.php and then call that
// eg $def_gedbasedir = "../../phpGedView/";
$def_gedbasedir = "../../phpGedView/";
?>
