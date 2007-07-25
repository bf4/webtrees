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
echo "Cliquer sur une ic�ne d'image. ";
echo "Le titre de l'image appara�tra au fond de l'image. ";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">Pour employer le mode de bourdonnement [zoom] avec une image montr�e</font></b><br>" ;
echo "<b> Permettre le bourdonnement : </b><br>";
echo "Cliquer sur l'�tiquette [ZOOM] (mode de BOURDONNEMENT) � la gauche sup�rieure de l'image.<br> ";
echo "Utiliser maintenant la roue de souris en haut et en bas pour remettre � la c�te.<br>";
echo "Quand l'image est plus grande remis � la c�te que la page vue, utiliser le bouton gauche de souris (maintenu) � pour tra�ner � l'image autour. <br>";
echo "<b> Neutraliser le bourdonnement : </b><br>";
echo "Cliquer sur l'�tiquette [NORMAL] (Normale) � la gauche sup�rieure encore pour sortir du mode de bourdonnement, et revoir le titre (au fond), et l'�tiquette [CLOSE X] (droite sup�rieure).";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">Pour cl�turer une image (trois mani�res)</font></b><br><ol>";
echo "<li /> Quand le curseur est dans l'image, cliquer sur l'�tiquette [CLOSE X] la droite en haut.<br>";
echo "<li /> Cliquer n'importe o� ailleurs dans l'image<br>";
echo " (notez si vous faites ceci quand en mode de bourdonnement, vous devrez cliquer l'�tiquette [ZOOM] deux fois encore quand regardant la prochaine image pour voir le titre et l'�tiquette [CLOSE X] une fois de plus.";
echo "<li /> Cliquer en dehors de l'image <br>";
echo " (notez si vous faites ceci quand en mode de bourdonnement, vous devrez cliquer l'�tiquette [ZOOM] deux fois encore quand regardant la prochaine image pour voir le titre et l'�tiquette [CLOSE X] une fois de plus.";
echo "</li></ol><br>";

echo "<li>";
echo "<b><font color=\"blue\"> Pour regarder la prochaine image dans la section</font></b><br>";
echo "Cliquer sur l'�tiquette [PREV] (pr�c�dent), ou [NEXT] (prochain) qui apparaissent des c�t�s de l'image quand vous � exc�dent de souris � l'image.";
echo "</li><br><br>";

echo "<li>";
echo "<b><font color=\"blue\">Navigation ...</b></font><br>";
echo "Employer la table  � Afficher 'Album' �  � la droite de la table d'ic�ne d'image de choisir directement la vue de l'album d'une autre personne.<br>";

echo "</ol>";

echo "<br><b>Notes:</b><br>";
echo "Des ic�nes d'image qui apparaissent dans la rang�e � Autres � , ne seront pas montr�es avec � Lightbox � <br> ";
echo "par exemple: les fichiers � PDF �, et les � TYPES � de media, acoustique, livre, et visuel.<br>";
echo "Ceci est fait intentionnellement car � Lightbox � ne peut pas manipuler ces dossiers actuellement.<br><br>";
echo "<b> Note pour Administrator :</b><br>";
echo "Si des fichiers d' � image � (JPEG etc.) de � TYPE � (photo, certificat, document etc.) apparaissent dans la rang�e � Autres � ,   il signifie qu'aucun � TYPE � n'a �t� plac�.<br>";
echo " Vous pouvez souhaiter reclassifier le � TYPE � de m�dia pour ces articles. ";

echo " </font> ";
 ?>