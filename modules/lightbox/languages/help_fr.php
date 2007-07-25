<?php
/**
 * =============================================================================
 * Album module for phpGedView.  Author: Brian Holland
 * help.php
*/
// -----------------------------------------------------------------------------
// Version 097i
// 07/May/2007
// =============================================================================


echo "<font size=2 face=\"Verdana\"> ";
echo "<h3>AIDE de Lightbox-Album : </h3>";
echo "<ol> ";

echo "<li>";
echo "<b><font color=\"blue\">Pour afficher une image </font></b><br>";
echo "Cliquer sur une icône d'image. ";
echo "Le titre de l'image apparaîtra au fond de l'image. ";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">Pour employer le mode de bourdonnement [zoom] avec une image montrée</font></b><br>" ;
echo "<b> Permettre le bourdonnement : </b><br>";
echo "Cliquer sur l'étiquette [ZOOM] (mode de BOURDONNEMENT) à la gauche supérieure de l'image.<br> ";
echo "Utiliser maintenant la roue de souris en haut et en bas pour remettre à la côte.<br>";
echo "Quand l'image est plus grande remis à la côte que la page vue, utiliser le bouton gauche de souris (maintenu) « pour traîner » l'image autour. <br>";
echo "<b> Neutraliser le bourdonnement : </b><br>";
echo "Cliquer sur l'étiquette [NORMAL] (Normale) à la gauche supérieure encore pour sortir du mode de bourdonnement, et revoir le titre (au fond), et l'étiquette [CLOSE X] (droite supérieure).";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">Pour clôturer une image (trois manières)</font></b><br><ol>";
echo "<li /> Quand le curseur est dans l'image, cliquer sur l'étiquette [CLOSE X] la droite en haut.<br>";
echo "<li /> Cliquer n'importe où ailleurs dans l'image<br>";
echo " (notez si vous faites ceci quand en mode de bourdonnement, vous devrez cliquer l'étiquette [ZOOM] deux fois encore quand regardant la prochaine image pour voir le titre et l'étiquette [CLOSE X] une fois de plus.";
echo "<li /> Cliquer en dehors de l'image <br>";
echo " (notez si vous faites ceci quand en mode de bourdonnement, vous devrez cliquer l'étiquette [ZOOM] deux fois encore quand regardant la prochaine image pour voir le titre et l'étiquette [CLOSE X] une fois de plus.";
echo "</li></ol><br>";

echo "<li>";
echo "<b><font color=\"blue\"> Pour regarder la prochaine image dans la section</font></b><br>";
echo "Cliquer sur l'étiquette [PREV] (précédent), ou [NEXT] (prochain) qui apparaissent des côtés de l'image quand vous « excédent de souris » l'image.";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">Navigation ...</b></font><br>";
echo "Employer la table  « Afficher 'Album' »  à la droite de la table d'icône d'image de choisir directement la vue de l'album d'une autre personne.<br>";

echo "</ol>";

echo "<br><b>Notes:</b><br>";
echo "Des icônes d'image qui apparaissent dans la rangée « Autres » , ne seront pas montrées avec « Lightbox » <br> ";
echo "par exemple: les fichiers « PDF », et les « TYPES » de media, acoustique, livre, et visuel.<br>";
echo "Ceci est fait intentionnellement car « Lightbox » ne peut pas manipuler ces dossiers actuellement.<br><br>";
echo "<b> Note pour Administrator :</b><br>";
echo "Si des fichiers d' « image » (JPEG etc.) de « TYPE » (photo, certificat, document etc.) apparaissent dans la rangée « Autres » ,   il signifie qu'aucun « TYPE » n'a été placé.<br>";
echo " Vous pouvez souhaiter reclassifier le « TYPE » de média pour ces articles. ";

echo " </font> ";
 ?>