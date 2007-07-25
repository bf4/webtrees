<?php

/**
 * -----------------------------------------------------------------------------
 *  lightbox_ctrl.php version 1.00k
 *
 *  Author Brian Holland 25 Jly 2007
 * -----------------------------------------------------------------------------
*/

// -----------------------------------------------------------------------------
// Function lightbox() for Lightbox Album - called by individual_ctrl.php
// -----------------------------------------------------------------------------
// function lightbox_relatives2() {
global $edit;

$edit=$edit;

     if (!file_exists("modules/googlemap/defaultconfig.php")) {
         $tabno = "6";
         }else{
         $tabno = "7";
         }

//     echo "Light Box Stuff";
     echo "<table width='230'><tr><td>";



                global $pgv_lang, $SHOW_ID_NUMBERS, $PGV_IMAGE_DIR, $PGV_IMAGES;
                $personcount=0;
                $families = $this->indi->getChildFamilies();
                //-- parent families
                foreach($families as $famid=>$family) {
                        ?>

                                <?php
                                //$personcount = 0;
                                $people = $this->buildFamilyList($family, "parents");
                                $styleadd = "";
                                if (isset($people["husb"])) {
                                        ?>
                                        <tr>
                                                <td class="facts_label<?php print $styleadd; ?>"><?php print $people["husb"]->getLabel(); ?></td>
                                                <td class="<?php print $this->getPersonStyle($people["husb"]); ?>">
                                                <?php
                                                if ( ($people["husb"]->canDisplayDetails()) ) {
                                                     print "<a href=\"individual.php?tab=" . $tabno . "&pid=" . $people["husb"]->getXref() . "&edit=" . $edit . "\">";
                                                     print get_person_name($people["husb"]->getXref());
                                                     print "</a>" . "\n" ;
                                                }else{
                                                      print "PRIVATE";
                                                }
                                                ?>
                                                </td>
                                        </tr>
                                        <?php
                                }

                                if (isset($people["wife"])) {
                                        ?>
                                        <tr>
                                                <td class="facts_label<?php print $styleadd; ?>"><?php print $people["wife"]->getLabel(); ?></td>
                                                <td class="<?php print $this->getPersonStyle($people["wife"]); ?>">
                                                <?php
                                                if ( ($people["wife"]->canDisplayDetails()) ) {
                                                     print "<a href=\"individual.php?tab=" . $tabno . "&pid=" . $people["wife"]->getXref() . "&edit=" . $edit . "\">";
                                                     print get_person_name($people["wife"]->getXref());
                                                     print "</a>" . "\n" ;
                                                }else{
                                                      print "PRIVATE";
                                                }
                                                ?>
                                                </td>
                                        </tr>
                                        <?php
                                }
                                ?>
                         <?php
                }



                //-- spouses and children
                $families = $this->indi->getSpouseFamilies();
                foreach($families as $famid=>$family) {
                                echo "<tr><td><br></td><td></td></tr>";
                                //$personcount = 0;
                                $people = $this->buildFamilyList($family, "spouse");

                                if ($this->indi->equals($people["husb"])) $spousetag = 'WIFE';
                                else $spousetag = 'HUSB';

                                $styleadd = "";
                                if ( isset($people["husb"]) && $spousetag == 'HUSB' ) {
                                        ?>
                                        <tr>
                                                <td nowrap="nowrap" class="facts_label<?php print $styleadd; ?>"><?php print $people["husb"]->getLabel(); ?></td>
                                                <td class="<?php print $this->getPersonStyle($people["husb"]); ?>">
                                                <?php
                                                if ( ($people["husb"]->canDisplayDetails()) ) {
                                                     print "<a href=\"individual.php?tab=" . $tabno . "&pid=" . $people["husb"]->getXref() . "&edit=" . $edit . "\">";
                                                     print get_person_name($people["husb"]->getXref());
                                                     print "</a>" . "\n" ;
                                                }else{
                                                      print "PRIVATE";
                                                }
                                                ?>
                                                </td>
                                        </tr>
                                        <?php
                                }

                                if ( isset($people["wife"]) && $spousetag == 'WIFE') {
                                        ?>
                                        <tr>
                                                <td nowrap="nowrap" class="facts_label<?php print $styleadd; ?>"><?php print $people["wife"]->getLabel(); ?></td>
                                                <td class="<?php print $this->getPersonStyle($people["wife"]); ?>">
                                                <?php
                                                if ( ($people["wife"]->canDisplayDetails()) ) {
                                                     print "<a href=\"individual.php?tab=" . $tabno . "&pid=" . $people["wife"]->getXref() . "&edit=" . $edit . "\">";
                                                     print get_person_name($people["wife"]->getXref());
                                                     print "</a>" . "\n" ;
                                                }else{
                                                      print "PRIVATE";
                                                }
                                                ?>
                                                </td>
                                        </tr>
                                        <?php

                                }

                                $styleadd = "";
                                if (isset($people["children"])) {
                                        foreach($people["children"] as $key=>$child) {
                                        ?>
                                        <tr>
                                                <td class="facts_label<?php print $styleadd; ?>"><?php print $child->getLabel(); ?></td>
                                                <td class="<?php print $this->getPersonStyle($child); ?>">
                                                <?php
                                                if ( ($child->canDisplayDetails()) ) {
                                                      print "<a href=\"individual.php?tab=" . $tabno . "&pid=" . $child->getXref() . "&edit=" . $edit . "\">";
                                                      print get_person_name($child->getXref());
                                                      print "</a>" . "\n" ;
                                                }else{
                                                      print "PRIVATE";
                                                }
                                                ?>
                                                </td>
                                        </tr>
                                        <?php
                                        }
                                }

                                ?>
                <?php
                }

     echo "</td></tr></table>";

// }
// -----------------------------------------------------------------------------
// End LightBox Album Functions
// -----------------------------------------------------------------------------



?>