<?php
/**
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2003  John Finlay and Others
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
 * @package PhpGedView
 * @version $Id:$
 */

/**
 * security check to prevent hackers from directly accessing this file
 */
if (strstr($_SERVER["SCRIPT_NAME"],"functions.en.php")) {
	print "Why do you want to do that?";
	exit;
}

function getRelationshipText_en($relationshipDescription, $node, $pid1, $pid2)
{
    global $pgv_lang, $lang_short_cut, $LANGUAGE;
    $started = false;
    $finished = false;
	$numberOfSiblings = 0;
	$generationsOlder = 0;
	$generationsYounger = 0;
	$sosa = 1;
	$bosa = 1;
	$numberOfSpouses = 0;
	$lastRelationshipIsSpouse = false;

    // sanity check - helps to prevent the possibility of recursing too deeply
    if($pid1 == $pid2)
        return false;

    foreach($node["path"] as $index=>$pid)
    {
        // only start looking for relationships from the first pid passed in
        if($pid == $pid1)
        {
            $started = true;
			continue;
        }

        if($started)
        {
			$lastRelationshipIsSpouse = false;
            // look to see if we can find a relationship
            switch( $node["relations"][$index])
			{
			    case "self":
				    break;

			    case "sister":
			    case "brother":
					$numberOfSiblings++;
					break;

			    case "mother":
					$generationsOlder++;
					$sosa = $sosa * 2 + 1;
					break;

			    case "father":
					$generationsOlder++;
					$sosa = $sosa * 2;
					break;

			    case "son":
					$generationsYounger++;
					$bosa = $bosa * 2;
					break;

			    case "daughter":
					$generationsYounger++;
					$bosa = $bosa * 2 + 1;
					break;

			    case "husband":
			    case "wife":
					$numberOfSpouses++;
					$lastRelationshipIsSpouse = true;
				    break;
			}
        }

        if($pid == $pid2)
        {
            // we have found the second individual - look no further
            $finished = true;
            break;
        }
        
    }
    // sanity check
    if(!$started || !$finished)
    {
        // passed in pid's are not found in the array!!!
        return false;
    }

	$person2 = find_person_record($_SESSION["pid2"]);
	$person1 = find_person_record($_SESSION["pid1"]);
	$mf = "NN";
	if (preg_match("/1 SEX F/", $person2, $smatch)>0) $mf="F";
	if (preg_match("/1 SEX M/", $person2, $smatch)>0) $mf="M";

    //check if relationship is parent or grandparent        
    if ($numberOfSiblings == 0 && $generationsOlder > 0 && $generationsYounger == 0 && $numberOfSpouses == 0)
    {
		if($generationsOlder > 3)
		{
			// I don't like the format from get_sosa_name for large numbers of generations
			// so do our own thing here. Once I have SVN access maybe I should patch get_sos_name instead.
            if (isset($pgv_lang["n_x_great_grandmother"]) && ($mf=="F"))
			{
	            $relationshipDescription = sprintf( $pgv_lang["n_x_great_grandmother"], $generationsOlder-2);
		    }
			else if (isset($pgv_lang["n_x_great_grandfather"]))
			{
	            $relationshipDescription = sprintf( $pgv_lang["n_x_great_grandfather"], $generationsOlder-2);
			}
	    }
    }
    //checks for nth cousin n times removed
    else if ($numberOfSiblings == 1 && $generationsYounger > 0 && $generationsOlder > 0 && ($generationsYounger != $generationsOlder))
    {
        $degree = $generationsOlder;
        if ($mf=="F")
        {
            if(isset($pgv_lang["female_cousin_" . $degree]))
            {
                $relationshipDescription = $pgv_lang["female_cousin_" . $degree];
            }
        }
        else
        {
            // treat unknown gender as male
            if(isset($pgv_lang["male_cousin_" . $degree]))
            {
                $relationshipDescription = $pgv_lang["male_cousin_" . $degree];
            }
        }
		if($relationshipDescription != false)
		{
			$removed = $generationsOlder-$generationsYounger;
			// relationship description should already be set for the Nth cousin
			if($removed > 0)
			{
			    if(isset($pgv_lang["removed_ascending_" . $removed]))
				{
		    		$relationshipDescription = $relationshipDescription . $pgv_lang["removed_ascending_" . $removed];
		    	}
				else
				{
					$relationshipDescription = false;
				}
			}
			else if($removed < 0)
			{
				if(isset($pgv_lang["removed_descending_" . -$removed]))
				{
		    		$relationshipDescription = $relationshipDescription . $pgv_lang["removed_descending_" . -$removed];
		    	}
				else
				{
					$relationshipDescription = false;
				}
			}
		}
    }
	

    if($relationshipDescription != false)
	{
	    return  strtolower($relationshipDescription);
	}
	return false;
}
?>
