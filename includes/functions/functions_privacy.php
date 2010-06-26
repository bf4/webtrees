<?php
/**
* Privacy Functions
*
* See http://www.phpgedview.net/privacy.php for more information on privacy in webtrees
*
* webtrees: Web based Family History software
* Copyright (C) 2010 webtrees development team.
*
* Derived from PhpGedView
* Copyright (C) 2002 to 2009 PGV Development Team.  All rights reserved.
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
* @version $Id$
* @package webtrees
* @subpackage Privacy
*/

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('WT_FUNCTIONS_PRIVACY_PHP', '');

if ($USE_RELATIONSHIP_PRIVACY) {
	/**
	* store relationship paths in a cache
	*
	* the <var>$NODE_CACHE</var> is an array of nodes that have been previously checked
	* by the relationship calculator.  This cache greatly speed up the relationship privacy
	* checking on charts as many relationships on charts are in the same relationship path.
	*
	* See the documentation for the get_relationship() function in the functions.php file.
	*/
	$NODE_CACHE = array();
}

//-- allow users to overide functions in privacy file
if (!function_exists("showLivingNameById")) {
/**
* check if the name for a GEDCOM XRef ID should be shown
*
* This function uses the settings in the global variables above to determine if the current user
* has sufficient privileges to access the GEDCOM resource.  It first checks the
* <var>$SHOW_LIVING_NAMES</var> variable to see if names are shown to the public.  If they are
* then this function will always return true.  If the name is hidden then all relationships
* connected with the individual are also hidden such that arriving at this record results in a dead
* end.
*
* @author yalnifj
* @param string $pid the GEDCOM XRef ID for the entity to check privacy settings for
* @return boolean return true to show the person's name, return false to keep it private
*/
function showLivingNameById($pid) {
	global $SHOW_LIVING_NAMES;

	if ($_SESSION["wt_user"]==WT_USER_ID) {
		// Normal operation
		$pgv_GED_ID            = WT_GED_ID;
		$pgv_USER_ACCESS_LEVEL = WT_USER_ACCESS_LEVEL;
	} else {
		// We're in the middle of a Download -- get overriding information from cache
		$pgv_GED_ID            = $_SESSION["pgv_GED_ID"];
		$pgv_USER_ACCESS_LEVEL = $_SESSION["pgv_USER_ACCESS_LEVEL"];
	}

	return $SHOW_LIVING_NAMES>=$pgv_USER_ACCESS_LEVEL || canDisplayRecord($pgv_GED_ID, find_person_record($pid, $pgv_GED_ID));
}
}


// Can we display a level 0 record?
function canDisplayRecord($ged_id, $gedrec) {
	// TODO - use the privacy settings for $ged_id, not the default gedcom.
	global $USE_RELATIONSHIP_PRIVACY, $CHECK_MARRIAGE_RELATIONS, $MAX_RELATION_PATH_LENGTH;
	global $person_privacy, $person_facts, $global_facts, $HIDE_LIVE_PEOPLE, $GEDCOM, $SHOW_DEAD_PEOPLE, $MAX_ALIVE_AGE;
	global $PRIVACY_CHECKS, $SHOW_LIVING_NAMES, $KEEP_ALIVE_YEARS_BIRTH, $KEEP_ALIVE_YEARS_DEATH;

	// Only need to check each record once.
	static $cache; if ($cache===null) {$cache=array();}

	if ($_SESSION["wt_user"]==WT_USER_ID) {
		// Normal operation
		$pgv_GEDCOM            = WT_GEDCOM;
		$pgv_GED_ID            = WT_GED_ID;
		$pgv_USER_ID           = WT_USER_ID;
		$pgv_USER_NAME         = WT_USER_NAME;
		$pgv_USER_GEDCOM_ADMIN = WT_USER_GEDCOM_ADMIN;
		$pgv_USER_CAN_ACCESS   = WT_USER_CAN_ACCESS;
		$pgv_USER_ACCESS_LEVEL = WT_USER_ACCESS_LEVEL;
		$pgv_USER_GEDCOM_ID    = WT_USER_GEDCOM_ID;
	} else {
		// We're in the middle of a Download -- get overriding information from cache
		$pgv_GEDCOM            = $_SESSION["pgv_GEDCOM"];
		$pgv_GED_ID            = $_SESSION["pgv_GED_ID"];
		$pgv_USER_ID           = $_SESSION["pgv_USER_ID"];
		$pgv_USER_NAME         = $_SESSION["pgv_USER_NAME"];
		$pgv_USER_GEDCOM_ADMIN = $_SESSION["pgv_USER_GEDCOM_ADMIN"];
		$pgv_USER_CAN_ACCESS   = $_SESSION["pgv_USER_CAN_ACCESS"];
		$pgv_USER_ACCESS_LEVEL = $_SESSION["pgv_USER_ACCESS_LEVEL"];
		$pgv_USER_GEDCOM_ID    = $_SESSION["pgv_USER_GEDCOM_ID"];
	}

	if (preg_match('/^0 @('.WT_REGEX_XREF.')@ ('.WT_REGEX_TAG.')/', $gedrec, $match)) {
		$xref=$match[1];
		$type=$match[2];
		$cache_key="$xref@$ged_id";
		if (array_key_exists($cache_key, $cache)) {
			return $cache[$cache_key];
		}
	} else {
		// Missing data or broken link?
		return true;
	}

	//-- keep a count of how many times we have checked for privacy
	++$PRIVACY_CHECKS;

	// This setting would better be called "$ENABLE_PRIVACY"
	if (!$HIDE_LIVE_PEOPLE) {
		return $cache[$cache_key]=true;
	}

	// We should always be able to see our own record
	if ($xref==WT_USER_GEDCOM_ID && $ged_id=WT_GED_ID) {
		return $cache[$cache_key]=true;
	}

	// Does this record have a RESN?
	if (strpos($gedrec, "\n1 RESN none")) {
		return $cache[$cache_key]=true;
	}
	if (strpos($gedrec, "\n1 RESN privacy")) {
		return $cache[$cache_key]=(WT_PRIV_USER>=$pgv_USER_ACCESS_LEVEL);
	}
	if (strpos($gedrec, "\n1 RESN confidential")) {
		return $cache[$cache_key]=(WT_PRIV_NONE>=$pgv_USER_ACCESS_LEVEL);
	}

	// Does this record have a default RESN?
	if (isset($person_privacy[$xref])) {
		return $cache[$cache_key]=($person_privacy[$xref]>=$pgv_USER_ACCESS_LEVEL);
	}

	// Privacy rules do not apply to admins
	if ($pgv_USER_GEDCOM_ADMIN) {
		return $cache[$cache_key]=true;
	}

	// Different types of record have different privacy rules
	switch ($type) {
	case 'INDI':
		// Dead people...
		if ($SHOW_DEAD_PEOPLE>=$pgv_USER_ACCESS_LEVEL && WT_DB::prepare("SELECT `##is_dead`(?,?,?)")->execute(array($xref, $pgv_GED_ID, $gedrec))->fetchOne()) {
			return $cache[$cache_key]=true;
		}
		// Consider relationship privacy
		if ($pgv_USER_GEDCOM_ID && get_user_setting($pgv_USER_ID, 'relationship_privacy', $USE_RELATIONSHIP_PRIVACY)) {
			$path_length=get_user_setting($pgv_USER_ID, 'max_relation_path', $MAX_RELATION_PATH_LENGTH);
			$relationship=get_relationship($pgv_USER_GEDCOM_ID, $xref, $CHECK_MARRIAGE_RELATIONS, $path_length);
			return $cache[$cache_key]=($relationship!==false);
		}
		// No restriction found - show living people to authenticated users only:
		return WT_PRIV_USER>=$pgv_USER_ACCESS_LEVEL;
	case 'FAM':
		// Hide a family if either spouse is private
		if (preg_match_all('/\n1 (?:HUSB|WIFE) @('.WT_REGEX_XREF.')@/', $gedrec, $matches)) {
			foreach ($matches[1] as $spouse_id) {
				if (!canDisplayRecord($ged_id, find_person_record($spouse_id, $ged_id))) {
					return $cache[$cache_key]=false;
				}
			}
		}
		return true;
	case 'OBJE':
		// Hide media objects that are linked to private records
		foreach (get_media_relations($xref) as $gid=>$type2) {
			if (!canDisplayRecord($ged_id, find_gedcom_record($gid, $ged_id))) {
				return $cache[$cache_key]=false;
			}
		}
		break;
	case 'SOUR':
		// Hide sources if they are attached to private repositories.
		$repoid = get_gedcom_value("REPO", 1, $gedrec);
		if ($repoid && !canDisplayRecord($ged_id, find_other_record($repoid, $ged_id))) {
			return $cache[$cache_key]=false;
		}
		break;
	}

	// Level 1 tags (except INDI and FAM) can be controlled by global tag settings
	if (isset($global_facts[$type])) {
		return $cache[$cache_key]=($global_facts[$type]>=$pgv_USER_ACCESS_LEVEL);
	}
	
	// No restriction found - must be public:
	return $cache[$cache_key]=true;
}

// Can we display a level 1 record?
// Assume we have already called canDisplayRecord() to check the parent level 0 object
function canDisplayFact($xref, $ged_id, $gedrec) {
	// TODO - use the privacy settings for $ged_id, not the default gedcom.
	global $HIDE_LIVE_PEOPLE, $person_facts, $global_facts;

	// This setting would better be called "$ENABLE_PRIVACY"
	if (!$HIDE_LIVE_PEOPLE) {
		return true;
	}
	// We should always be able to see details of our own record
	if ($xref==WT_USER_GEDCOM_ID && $ged_id=WT_GED_ID) {
		return true;
	}

	// Does this record have a RESN?
	if (strpos($gedrec, "\n2 RESN none")) {
		return true;
	}
	if (strpos($gedrec, "\n2 RESN privacy")) {
		return WT_PRIV_USER>=WT_USER_ACCESS_LEVEL;
	}
	if (strpos($gedrec, "\n2 RESN confidential")) {
		return WT_PRIV_NONE>=WT_USER_ACCESS_LEVEL;
	}

	// Does this record have a default RESN?
	if (preg_match('/^1 ('.WT_REGEX_TAG.')/', $gedrec, $match)) {
		$tag=$match[1];
		if (isset($person_facts[$xref][$tag])) {
			return $person_facts[$xref][$tag]>=WT_USER_ACCESS_LEVEL;
		}
		if (isset($global_facts[$tag])) {
			return $global_facts[$tag]>=WT_USER_ACCESS_LEVEL;
		}
	}

	// No restrictions - it must be public
	return true;
}

/**
* remove all private information from a gedcom record
*
* this function will analyze and gedcom record and privatize it by removing all private
* information that should be hidden from the user trying to access it.
* @param string $gedrec the raw gedcom record to privatize
* @return string the privatized gedcom record
*/
function privatize_gedcom($gedrec) {
	global $SHOW_PRIVATE_RELATIONSHIPS, $pgv_private_records;
	global $global_facts, $person_facts, $GEDCOM;
	$gedcom_id=get_id_from_gedcom($GEDCOM);

	if (preg_match('/^0 @('.WT_REGEX_XREF.')@ ('.WT_REGEX_TAG.')(.*)/', $gedrec, $match)) {
		$gid = $match[1];
		$type = $match[2];
		$data = $match[3];
		if (canDisplayRecord($gedcom_id, $gedrec)) {
			// The record is not private, but the individual facts may be.
			if (
				!strpos($gedrec, "\n2 RESN") &&
				!isset($person_facts[$gid]) &&
				!preg_match('/\n1 (?:'.implode('|', array_keys($global_facts)).')/', $gedrec) &&
				!preg_match('/\n2 TYPE (?:'.implode('|', array_keys($global_facts)).')/', $gedrec)
			) {
				// Nothing to indicate fact privacy needed
				return $gedrec;
			}

			$newrec="0 @{$gid}@ {$type}{$data}";
			$private_record='';
			// Check each of the sub facts for access
			if (preg_match_all('/\n1 ('.WT_REGEX_TAG.').*(?:\n[2-9].*)*/', $gedrec, $matches, PREG_SET_ORDER)) {
				foreach ($matches as $match) {
					if (($match[1]=='FACT' || $match[1]=='EVEN') && preg_match('/\n2 TYPE ([A-Z]{3,5})/', $match[0], $tmatch)) {
						$tag=$tmatch[1];
					} else {
						$tag=$match[1];
					}
					if (canDisplayFact($gid, $gedcom_id, $match[0])) {
						$newrec.=$match[0];
					} else {
						$private_record.=$match[0];
					}
				}
			}
			// Store the private data, so we can add it back in after an edit.
			$pgv_private_records[$gid]=$private_record;
			return $newrec;
		} else {
			// The whole record is private - although there are a few things we need to show.
			switch($type) {
			case 'INDI':
				$newrec="0 @{$gid}@ INDI";
				if (showLivingNameById($gid)) {
					// Show all the NAME tags, including subtags
					if (preg_match_all('/\n1 (NAME|_HNM).*(\n[2-9].*)*/', $gedrec, $matches, PREG_SET_ORDER)) {
						foreach ($matches as $match) {
							$newrec.=$match[0];
						}
					}
				} else {
					$newrec.="\n1 NAME ".i18n::translate('Private');
				}
				// Just show the 1 FAMC/FAMS tag, not any subtags, which may contain private data
				if (preg_match_all('/\n1 FAM[CS] @('.WT_REGEX_XREF.')@/', $gedrec, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						if ($SHOW_PRIVATE_RELATIONSHIPS || canDisplayRecord($gedcom_id, find_family_record($match[1], $gedcom_id))) {
							$newrec.=$match[0];
						}
					}
				}
				// Don't privatize sex
				if (preg_match('/\n1 SEX [MFU]/', $gedrec, $match)) {
					$newrec.=$match[0];
				}
				$newrec .= "\n1 NOTE ".i18n::translate('Details about this person are private. Personal details will not be included.');
				break;
			case 'FAM':
				$newrec="0 @{$gid}@ FAM";
				// Just show the 1 CHIL/HUSB/WIFE tag, not any subtags, which may contain private data
				if (preg_match_all('/\n1 (CHIL|HUSB|WIFE) @('.WT_REGEX_XREF.')@/', $gedrec, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						if ($SHOW_PRIVATE_RELATIONSHIPS || canDisplayRecord($gedcom_id, find_person_record($match[1], $gedcom_id))) {
							$newrec.=$match[0];
						}
					}
				}
				$newrec .= "\n1 NOTE ".i18n::translate('Details about this family are private. Family details will not be included.');
				break;
			case 'SOUR':
				$newrec="0 @{$gid}@ SOUR\n1 TITL ".i18n::translate('Private');
				break;
			case 'OBJE':
				$newrec="0 @{$gid}@ OBJE\n1 NOTE ".i18n::translate('Details about this media are private. Media details will not be included.');
				break;
			default:
				$newrec="0 @{$gid}@ {$type}\n1 NOTE ".i18n::translate('Private');
			}
			return $newrec;
		}
	} else {
		// Invalid gedcom record, so nothing to privatize.
		return $gedrec;
	}
}

function get_last_private_data($gid) {
	global $pgv_private_records;

	if (!isset($pgv_private_records[$gid])) return false;
	return $pgv_private_records[$gid];
}

/**
* Check fact record for editing restrictions
*
* Checks if the user is allowed to change fact information,
* based on the existence of the RESN tag in the fact record.
*
* @return int Allowed or not allowed
*/
function FactEditRestricted($pid, $factrec) {
	if (WT_USER_GEDCOM_ADMIN) {
		return false;
	}

	if (preg_match("/2 RESN (.*)/", $factrec, $match)) {
		$match[1] = strtolower(trim($match[1]));
		if ($match[1] == "privacy" || $match[1]=="locked") {
			$myindi=WT_USER_GEDCOM_ID;
			if ($myindi == $pid) {
				return false;
			}
			if (gedcom_record_type($pid, WT_GED_ID)=='FAM') {
				$famrec = find_family_record($pid, WT_GED_ID);
				$parents = find_parents_in_record($famrec);
				if ($myindi == $parents["HUSB"] || $myindi == $parents["WIFE"]) {
					return false;
				}
			}
			return true;
		}
	}
	return false;
}
