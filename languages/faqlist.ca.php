<?php
/**
 * Catalan FAQ texts
  *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team. All rights reserved.
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
 * @subpackage Languages
 * @author Antoni Planas i Vilà
 * @version $Id$
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access a language file directly.";
	exit;
}

$faqlist["FAQ_000_head"] = "\"FAQ\":N'HE SENTIT PARLAR PERÒ, QUÈ ÉS?";
$faqlist["FAQ_000_body"] = "<b>FAQ</b>és l'acrònim anglès de <b>F</b>requently <b>A</b>sked <b>Q</b>uestion. (Preguntes més freqüents) <br /><br />És una llistat de preguntes (juntament amb llurs respostes) que es fan sovint. Han sigut recopilades per l'equip del PhpGedView i es va revisant sovint.";

$faqlist["FAQ_010_head"] = "BENVINGUTS A LES FAQ DE #GLOBALS[GEDCOM_TITLE]# ";
$faqlist["FAQ_010_body"] = "Els membres de la família de #GLOBALS[GEDCOM_TITLE]# volem aprofitar aquesta oportunitat per donar la benvinguda a la genealogia a tots els nostres familiars i animar-vos a que us piqui el cuc d'investigar sobre els vostres avantpassats. Pot esdevenir una tasca d'amor -i odi- dons requereix una dedicació enorme. Però la recompensa és igual d'enorme. Us oferim l'oportunitat de gaudir de la genealogia mitjançant l'ús de <a href=\"http://www.phpgedview.net\" target=\"_blank\">PhpGedView</a>, creat pel talent en programació de John Finlay i el seu equip PGV - Un meravellós programa de genealogia fruit de la tecnologia de Codi Font Obert.";

$faqlist["FAQ_015_head"] = "QUINA ÉS LA DIFERÈNCIA ENTRE AQUEST ARBRE PhpGedView I ALTRES ARBRES TEXTUALS I DINÀMICS?";
$faqlist["FAQ_015_body"] = "Els arbres textuals i els dinàmics es presenten de diverses formes, però cap d'ells us permet configurar-lo, canviar-lo o actualitzar-lo. Solament l'administrador de webs pot fer-hi actualitzacions.<br /><br />El PhpGedView en canvi, és un arbre interactiu. Qualsevol de la família, en tota l'extensió de l'arbre, pot modificar, afegir i fer canvis de les seves branques properes. (Per a poder fer-ho cal registrar-se però prèviament).";

$faqlist["FAQ_017_head"] = "QUINES SON LES CARACTERÍSTIQUES ESPECIALS D'AQUEST ARBRE?";
$faqlist["FAQ_017_body"] = "Amb PhpGedView podeu:<ul><li>Conservar la privadesa de la gent viva; l'administrador del lloc web determina el que podeu veure.</li><li>Gaudiu de més possibilitats per veure l'arbre: com diferents gràfics, informes o llistes.</li><li>És un arbre participatiu, amb el permís de l'administrador cada ú pot col·laborar en actualitzar-lo.</li></ul>";

$faqlist["FAQ_020_head"] = "NECESSITO UN COMPTE PER ACCEDIR-HI? SI ÉS AIXÍ, COM PUC FER-HO PER ACONSEGUIR-LO?";
$faqlist["FAQ_020_body"] = "Podem dir \"Benvingut parent\"?<br /><br /><b>NOTA: a #GLOBALS[GEDCOM_TITLE]#  NO CAL ENREGISTRAR-SE per disposar d'accés a dades de persones difuntes. Tanmateix, per col·laborar i veure esdeveniments de persones presumptament vives, us cal enregistrar-vos i informar-nos sobre el vostre parentiu.</b>";
$faqlist["FAQ_020_body2"] = "Els usuaris enregistrats veuen els noms de totes les persones vives del lloc, dades detallades de les difuntes i llurs parents propers.<br /><br />Els usuaris que no hi tingui cap mena de parentiu veuen solament els noms de les persones vives i dades detallades dels difunts.";
$faqlist["FAQ_020_body3"] = "<ol><li>Els enregistrats cal que siguin parents, llunyans en alguns cassos, o emparentats d'alguna manera amb algú que ja surt o que podria sortir al nostre lloc;</li><li>Cal que estiguin disposats a visitar i contribuir regularment al nostre lloc, primerament fornint-nos informació personal i sobre els seus parents directes. I posteriorment amb modificacions, increment, ampliació i addicions de les nostres dades existents;</li><li>També cal que es comprometin a preservar la privadesa de les dades de totes les persones vives del lloc i, com s'ha dit abans, contribuir amb llur informació personal. Els incompliments poden comportar l'acabament dels privilegis d'accés i causar accions legals. Ens prenem molt seriosament la possibilitat de falsificació de personalitat o d'ús indegut de la informació. Per a més detalls, mireu-vos més avall la nostra secció de Privadesa.</li></ol>Si esteu qualificat per enregistrar-vos i accepteu complir aquestes senzilles normes i procediments, tingueu a be enregistrar-vos omplint aquest <b><a target=\"_blank\" href=\"/phpGedView/login_register.php?action=register\">formulari de registre</a></b> que trobareu dins del lloc. Assegureu-vos d'emplenar el petit qüestionari on detallareu la mena de parentiu amb les persones d'aquest lloc i on també declarareu clarament manifestareu estar d'acord i acceptar les nostres normes i procediments.  Estudiarem i considerarem la vostra sol·licitud.";
$faqlist["FAQ_020_body4"] = "L'autorització de un nou compte d'usuari l'ha de fer manualment l'Administrador del lloc. Generalment pot trigar entre pocs minuts i 24 hores.";

$faqlist["FAQ_022_head"] = "PERQUÈ EM CAL ENREGISTRAR-ME?";
$faqlist["FAQ_022_body"] = "Solament els usuaris enregistrats poden veure noms de persones vives. Si no esteu enregistrat us apareixerà \"Privat\" en lloc dels noms de la gent viva.";

$faqlist["FAQ_025_head"] = "QUAN DE TEMPS TARDA TENIR APROVAT EL MEU ENRGISTRAMENT?";
$faqlist["FAQ_025_body"] = "L'autorització de un nou compte d'usuari l'ha de fer manualment l'Administrador del lloc. Generalment pot trigar entre pocs minuts i 24 hores.";

$faqlist["FAQ_027_head"] = "JO ESTIC ENREGISTRAT I HE SIGUT AUTORITZAT. PUC VEURE NOMS DE GENT VIVA PERÒ NO ALGUNS DELS SEUS DETALLS.";
$faqlist["FAQ_027_body"] = "Per veure detalls (solament de les vostres branques properes), us cal formar part de l'arbre familiar i n'heu d'informar a l'Administrador del lloc/Responsable de qüestions genealògiques per correu electrònic.";

$faqlist["FAQ_030_head"] = "COM PUC ENTRAR DADES? QUINS FORMATS HE DE FER SERVIR?";
$faqlist["FAQ_030_body"] = "Heus aquí algunes indicacions:";
$faqlist["FAQ_030_body2"] = " per usuaris autoritzats a EDITAR en línea.";
$faqlist["FAQ_030_body3"] = "També podeu enviar les actualitzacions per correu electrònic.";
$faqlist["FAQ_030_HELP"] = "<strong>AJUDA</strong>: Al lloc web es dóna ajuda a bastament, a cada capçalera de pàgina i amb el símbol \"?\" al costat de la majoria d'enllaços i ítems. Si malgrat tot encara teniu dubtes, simplement demaneu-nos-ho per correu electrònic.";
$faqlist["FAQ_030_DATES"] = "<strong>DATES</strong>: Fem servir el format estàndard GEDCOM v5.5. DD MMM YYYY or 01 JAN 1822 en lloc de January 1, 1822 o Jan 1, 1822.  El sistema pot fer algunes petites correccions d'erros d'entrada, però no heu de refiar-vos-en.";
$faqlist["FAQ_030_HDATES"] = "<strong>DATES HEBREES</strong> s'omplen amb el format @#DHEBREW@ DD MMM YYYY o @#DHEBREW@ 21 AAV 5705 - Els mesos s'omplen com TSH, CSH, KSL, TVT, SHV, ADR, ADS, NSN, IYR, SVN, TMZ, AAV i ELL tal com l'estàndard GEDCOM v5.5.";
$faqlist["FAQ_030_PLACES"] = "<strong>LLOC</strong>: Sempre que la coneguem inclourem la descripció completa del lloc: ciutat i/o municipi així com Comtat, Estat i normalment afegirem USA (preferible a US, U.S., o U.S.A.) al costat de l'Estat.  Per a països estrangers podem emprar l'abreujament estàndard de 3 lletres aprovat per al GEDCOM en lloc del nom del país: Anglaterra [ENG], Escòcia [SCT], Irlanda [IRE], França [FRA], Itàlia [ITA], Catalunya [CAT] etc. El format que preferim és: <i>Indianapolis, Center Twp, Marion Co, Indiana, USA</i>  No s'han d'abreviar els Estats a dues lletres; no farem servir generalment punts (.) als noms de llocs, així <i>Shelbyville, Addison Twp, Shelby Co, Indiana, USA</i> per comptes de <i>Shelbyville, Addison Twp., Shelby Co., IN</i> or <i>Shelbyville, Addison Township, Shelby County, Indiana, U.S.A.</i>";

?>
