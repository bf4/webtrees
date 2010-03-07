<?php
/**
 * Show help text in a popup window.
 *
 * This file also serves as a database of fact and label descriptions,
 * allowing them to be discovered by xgettext, so we may use them dynamically
 * in the rest of the code.
 *
 * Copyright (C) 2010 Greg Roach
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
 */

define('PGV_SCRIPT_NAME', 'help_text.php');
require './config.php';

// TODO: incorporate help_text_vars.php

/* TODO: This block of code belongs somewhere else.  In print_help_link() perhaps?
require PGV_ROOT.'includes/help_text_vars.php';
if ($help=='help_useradmin.php' && $action=='edituser') {
	$help='edit_useradmin_help';
}
if ($help=='help_login_register.php' && $action=='pwlost') {
	$help='help_login_lost_pw.php';
}
if ($help=='help_contents_help') {
	if (PGV_USER_IS_ADMIN) {
		$help='admin_help_contents_help';
		echo $pgv_lang['admin_help_contents_head_help'];
	} else {
		echo $pgv_lang['help_contents_head_help'];
	}
	print_help_index($help);
} else {
	print_text($help);
}
*/

$help=safe_GET('help');
switch ($help) {
	//////////////////////////////////////////////////////////////////////////////
	// This is a list of all known gedcom tags.  We list them all here so that
	// xgettext() may find them.
	//
	// Tags such as BIRT:PLAC are only used as labels, and do not require help
	// text.  These are only used for translating labels.
	//
	// Tags such as _BIRT_CHIL are pseudo-tags, used to create family events.
	//////////////////////////////////////////////////////////////////////////////
case 'ABBR':
	// I18N: This is the GEDCOM label for "Abbreviation"
	$title=i18n::translate('ABBR');
	$text=i18n::translate('Use this field for storing an abbreviated version of a title.  This field is used in conjunction with the title field on sources.  By default PGV will first use the title and then the abbreviated title.<br /><br />According to the GEDCOM 5.5 specification, "this entry is to provide a short title used for sorting, filing, and retrieving source records (pg 62)."<br /><br />In PhpGedView the abbreviated title is optional, but in other genealogical programs it is required.');
	break;

case 'ADOP':
	// I18N: This is the GEDCOM label for "Adoption"
	$title=i18n::translate('ADOP');
	$text='';
	break;

case 'ADDR':
	// I18N: This is the GEDCOM label for "Address"
	$title=i18n::translate('ADDR');
	$text=i18n::translate('Enter the address into the field just as you would write it on an envelope.<br /><br />Leave this field blank if you do not want to include an address.');
	break;

case 'ADR1':	
		// I18N: This is the GEDCOM label for "Address 1"
	$title=i18n::translate('ADR1');
	$text='';
	break;

case 'ADR2':	
	// I18N: This is the GEDCOM label for "Address 2"
	$title=i18n::translate('ADR2');
	$text='';
	break;

case 'AFN':	
	// I18N: This is the GEDCOM label for "Ancestral File Number (AFN)"
	$title=i18n::translate('AFN');
	$text='';
	break;

case 'AGE':	
	// I18N: This is the GEDCOM label for "Age"
	$title=i18n::translate('AGE');
	$text='';
	break;

case 'AGNC':
	// I18N: This is the GEDCOM label for "Agency"
	$title=i18n::translate('AGNC');
	$text=i18n::translate('The organization, institution, corporation, person, or other entity that has authority.<br /><br />For example, an employer of a person, or a church that administered rites or events, or an organization responsible for creating and/or archiving records.');
	break;

case 'ALIA':	
	// I18N: This is the GEDCOM label for "Alias"
	$title=i18n::translate('ALIA');
	$text='';
	break;

case 'ANCE':	
	// I18N: This is the GEDCOM label for "Ancestors"
	$title=i18n::translate('ANCE');
	$text='';
	break;

case 'ANCI':	
	// I18N: This is the GEDCOM label for "Ancestors Interest"
	$title=i18n::translate('ANCI');
	$text='';
	break;

case 'ANUL':	
	// I18N: This is the GEDCOM label for "Annulment"
	$title=i18n::translate('ANUL');
	$text='';
	break;

case 'ASSO':
	// I18N: This is the GEDCOM label for "Associate"
	$title=i18n::translate('ASSO');
	$text=i18n::translate('Enter associate GEDCOM ID.');
	break;

case 'AUTH':	
	// I18N: This is the GEDCOM label for "Author"
	$title=i18n::translate('AUTH');
	$text='';
	break;

case 'BAPL':	
	// I18N: This is the GEDCOM label for "LDS Baptism"
	$title=i18n::translate('BAPL');
	$text='';
	break;

case 'BAPM':	
	// I18N: This is the GEDCOM label for "Baptism"
	$title=i18n::translate('BAPM');
	// I18N: This is a very short abbreviation for the label "Baptism", to be used on genealogy charts
	$abbrev=i18n::translate('ABBREV_BAPM');
	$text='';
	break;

case 'BAPM:DATE':	
	// I18N: This is the GEDCOM label for "Baptism Date"
	$title=i18n::translate('BAPM:DATE');
	$text='';
	break;

case 'BAPM:PLAC':	
	// I18N: This is the GEDCOM label for "Baptism Place"
	$title=i18n::translate('BAPM:PLAC');
	$text='';
	break;

case 'BAPM:SOUR':	
	// I18N: This is the GEDCOM label for "Baptism Source"
	$title=i18n::translate('BAPM:SOUR');
	$text='';
	break;

case 'BARM':	
	// I18N: This is the GEDCOM label for "Bar Mitzvah"
	$title=i18n::translate('BARM');
	$text='';
	break;

case 'BARM:DATE':	
	// I18N: This is the GEDCOM label for "Bar Mitzvah Date"
	$title=i18n::translate('BARM:DATE');
	$text='';
	break;

case 'BARM:PLAC':	
	// I18N: This is the GEDCOM label for "Bar Mitzvah Place"
	$title=i18n::translate('BARM:PLAC');
	$text='';
	break;

case 'BARM:SOUR':	
	// I18N: This is the GEDCOM label for "Bar Mitzvah Source"
	$title=i18n::translate('BARM:SOUR');
	$text='';
	break;

case 'BASM':	
	// I18N: This is the GEDCOM label for "Bas Mitzvah"
	$title=i18n::translate('BASM');
	$text='';
	break;

case 'BASM:DATE':	
	// I18N: This is the GEDCOM label for "Bas Mitzvah Date"
	$title=i18n::translate('BASM:DATE');
	$text='';
	break;

case 'BASM:PLAC':	
	// I18N: This is the GEDCOM label for "Bas Mitzvah Place"
	$title=i18n::translate('BASM:PLAC');
	$text='';
	break;

case 'BASM:SOUR':	
	// I18N: This is the GEDCOM label for "Bas Mitzvah Source"
	$title=i18n::translate('BASM:SOUR');
	$text='';
	break;

case 'BIRT':	
	// I18N: This is the GEDCOM label for "Birth"
	$title=i18n::translate('BIRT');
	// I18N: This is a very short abbreviation for the label "Birth", to be used on genealogy charts
	$abbr=i18n::translate('ABBREV_BIRT');
	$text='';
	break;

case 'BIRT:DATE':	
	// I18N: This is the GEDCOM label for "Birth Date"
	$title=i18n::translate('BIRT:DATE');
	$text='';
	break;

case 'BIRT:PLAC':	
	// I18N: This is the GEDCOM label for "Birth Place"
	$title=i18n::translate('BIRT:PLAC');
	$text='';
	break;

case 'BIRT:SOUR':	
	// I18N: This is the GEDCOM label for "Birth Source"
	$title=i18n::translate('BIRT:SOUR');
	$text='';
	break;

case 'BLES':	
	// I18N: This is the GEDCOM label for "Blessing"
	$title=i18n::translate('BLES');
	$text='';
	break;

case 'BLOB':	
	// I18N: This is the GEDCOM label for "Binary Data Object"
	$title=i18n::translate('BLOB');
	$text='';
	break;

case 'BURI':	
	// I18N: This is the GEDCOM label for "Burial"
	$title=i18n::translate('BURI');
	// I18N: This is a very short abbreviation for the label "Burial", to be used on genealogy charts
	$abbr=i18n::translate('ABBREV_BURI');
	$text='';
	break;

case 'BURI:DATE':	
	// I18N: This is the GEDCOM label for "Burial Date"
	$title=i18n::translate('BURI:DATE');
	$text='';
	break;

case 'BURI:PLAC':	
	// I18N: This is the GEDCOM label for "Burial Place"
	$title=i18n::translate('BURI:PLAC');
	$text='';
	break;

case 'BURI:SOUR':	
	// I18N: This is the GEDCOM label for "Burial Source"
	$title=i18n::translate('BURI:SOUR');
	$text='';
	break;

case 'CALN':	
	// I18N: This is the GEDCOM label for "Call Number"
	$title=i18n::translate('CALN');
	$text='';
	break;

case 'CAST':	
	// I18N: This is the GEDCOM label for "Caste / Social Status"
	$title=i18n::translate('CAST');
	$text='';
	break;

case 'CAUS':	
	// I18N: This is the GEDCOM label for "Cause of an event"
	$title=i18n::translate('CAUS');
	$text='';
	break;

case 'CEME':
	// I18N: This is the GEDCOM label for "Cemetery"
	$title=i18n::translate('CEME');
	$text=i18n::translate('Enter the name of the cemetery or other resting place where individual is buried.');
	break;

case 'CENS':	
	// I18N: This is the GEDCOM label for "Census"
	$title=i18n::translate('CENS');
	$text='';
	break;

case 'CHAN':	
	// I18N: This is the GEDCOM label for "Last Change"
	$title=i18n::translate('CHAN');
	$text='';
	break;

case 'CHAR':	
	// I18N: This is the GEDCOM label for "Character Set"
	$title=i18n::translate('CHAR');
	$text='';
	break;

case 'CHIL':	
	// I18N: This is the GEDCOM label for "Child"
	$title=i18n::translate('CHIL');
	$text='';
	break;

case 'CHR':	
	// I18N: This is the GEDCOM label for "Christening"
	$title=i18n::translate('CHR');
	// I18N: This is a very short abbreviation for the label "Christening", to be used on genealogy charts
	$abbr=i18n::translate('ABBREV_CHR');
	$text='';
	break;

case 'CHR:DATE':	
	// I18N: This is the GEDCOM label for "Christening Date"
	$title=i18n::translate('CHR:DATE');
	$text='';
	break;

case 'CHR:PLAC':	
	// I18N: This is the GEDCOM label for "Christening Place"
	$title=i18n::translate('CHR:PLAC');
	$text='';
	break;

case 'CHR:SOUR':	
	// I18N: This is the GEDCOM label for "Christening Source"
	$title=i18n::translate('CHR:SOUR');
	$text='';
	break;

case 'CHRA':	
	// I18N: This is the GEDCOM label for "Adult Christening"
	$title=i18n::translate('CHRA');
	$text='';
	break;

case 'CITN':	
	// I18N: This is the GEDCOM label for "Citizenship"
	$title=i18n::translate('CITN');
	$text='';
	break;

case 'CITY':	
	// I18N: This is the GEDCOM label for "City"
	$title=i18n::translate('CITY');
	$text='';
	break;

case 'COMM':	
	// I18N: This is the GEDCOM label for "Comment"
	$title=i18n::translate('COMM');
	$text='';
	break;

case 'CONF':	
	// I18N: This is the GEDCOM label for "Confirmation"
	$title=i18n::translate('CONF');
	$text='';
	break;

case 'CONF:DATE':	
	// I18N: This is the GEDCOM label for "Confirmation Date"
	$title=i18n::translate('CONF:DATE');
	$text='';
	break;

case 'CONF:PLAC':	
	// I18N: This is the GEDCOM label for "Confirmation Place"
	$title=i18n::translate('CONF:PLAC');
	$text='';
	break;

case 'CONF:SOUR':	
	// I18N: This is the GEDCOM label for "Confirmation Source"
	$title=i18n::translate('CONF:SOUR');
	$text='';
	break;

case 'CONL':	
	// I18N: This is the GEDCOM label for "LDS Confirmation"
	$title=i18n::translate('CONL');
	$text='';
	break;

case 'COPR':	
	// I18N: This is the GEDCOM label for "Copyright"
	$title=i18n::translate('COPR');
	$text='';
	break;

case 'CORP':	
	// I18N: This is the GEDCOM label for "Corporation / Company"
	$title=i18n::translate('CORP');
	$text='';
	break;

case 'CREM':	
	// I18N: This is the GEDCOM label for "Cremation"
	$title=i18n::translate('CREM');
	$text='';
	break;

case 'CTRY':	
	// I18N: This is the GEDCOM label for "Country"
	$title=i18n::translate('CTRY');
	$text='';
	break;

case 'DATA':	
	// I18N: This is the GEDCOM label for "Data"
	$title=i18n::translate('DATA');
	$text='';
	break;

case 'DATA:DATE':	
	// I18N: This is the GEDCOM label for "Date of entry in original source"
	$title=i18n::translate('DATA:DATE');
	$text='';
	break;

case 'DATE':	
	// I18N: This is the GEDCOM label for "Date"
	$title=i18n::translate('DATE');
	$text='';
	break;

case 'DEAT':	
	// I18N: This is the GEDCOM label for "Death"
	$title=i18n::translate('DEAT');
	// I18N: This is a very short abbreviation for the label "Death", to be used on genealogy charts
	$abbr=i18n::translate('ABBREV_DEAT');
	$text='';
	break;

case 'DEAT:DATE':	
	// I18N: This is the GEDCOM label for "Death Date"
	$title=i18n::translate('DEAT:DATE');
	$text='';
	break;

case 'DEAT:PLAC':	
	// I18N: This is the GEDCOM label for "Death Place"
	$title=i18n::translate('DEAT:PLAC');
	$text='';
	break;

case 'DEAT:SOUR':	
	// I18N: This is the GEDCOM label for "Death Source"
	$title=i18n::translate('DEAT:SOUR');
	$text='';
	break;

case 'DESC':	
	// I18N: This is the GEDCOM label for "Descendants"
	$title=i18n::translate('DESC');
	$text='';
	break;

case 'DESI':	
	// I18N: This is the GEDCOM label for "Descendants Interest"
	$title=i18n::translate('DESI');
	$text='';
	break;

case 'DEST':	
	// I18N: This is the GEDCOM label for "Destination"
	$title=i18n::translate('DEST');
	$text='';
	break;

case 'DIV':	
	// I18N: This is the GEDCOM label for "Divorce"
	$title=i18n::translate('DIV');
	$text='';
	break;

case 'DIVF':	
	// I18N: This is the GEDCOM label for "Divorce filed"
	$title=i18n::translate('DIVF');
	$text='';
	break;

case 'DSCR':	
	// I18N: This is the GEDCOM label for "Description"
	$title=i18n::translate('DSCR');
	$text='';
	break;

case 'EDUC':	
	// I18N: This is the GEDCOM label for "Education"
	$title=i18n::translate('EDUC');
	$text='';
	break;

case 'EMAIL':
	// I18N: This is the GEDCOM label for "Email Address"
	$title=i18n::translate('EMAIL');
	$text=i18n::translate('Enter the email address.<br /><br />An example email address looks like this: <b>name@hotmail.com</b>  Leave this field blank if you do not want to include an email address.');
	break;

case 'EMAL':	
	// I18N: This is the GEDCOM label for "Email Address"
	$title=i18n::translate('EMAL');
	$text='';
	break;

case 'EMIG':	
	// I18N: This is the GEDCOM label for "Emigration"
	$title=i18n::translate('EMIG');
	$text='';
	break;

case 'ENDL':	
	// I18N: This is the GEDCOM label for "LDS Endowment"
	$title=i18n::translate('ENDL');
	$text='';
	break;

case 'ENGA':	
	// I18N: This is the GEDCOM label for "Engagement"
	$title=i18n::translate('ENGA');
	$text='';
	break;

case 'ENGA:DATE':	
	// I18N: This is the GEDCOM label for "Engagement Date"
	$title=i18n::translate('ENGA:DATE');
	$text='';
	break;

case 'ENGA:PLAC':	
	// I18N: This is the GEDCOM label for "Engagement Place"
	$title=i18n::translate('ENGA:PLAC');
	$text='';
	break;

case 'ENGA:SOUR':	
	// I18N: This is the GEDCOM label for "Engagement Source"
	$title=i18n::translate('ENGA:SOUR');
	$text='';
	break;

case 'EVEN':	
	// I18N: This is the GEDCOM label for "Event"
	$title=i18n::translate('EVEN');
	$text='';
	break;

case 'FACT':	
	// I18N: This is the GEDCOM label for "Fact"
	$title=i18n::translate('FACT');
	$text='';
	break;

case 'FAM':	
	// I18N: This is the GEDCOM label for "Family"
	$title=i18n::translate('FAM');
	$text='';
	break;

case 'FAMC':	
	// I18N: This is the GEDCOM label for "Family as a Child"
	$title=i18n::translate('FAMC');
	$text='';
	break;

case 'FAMC:HUSB:BIRT:PLAC':	
	// I18N: This is the GEDCOM label for "Father's Birthplace"
	$title=i18n::translate('FAMC:HUSB:BIRT:PLAC');
	$text='';
	break;

case 'FAMC:HUSB:FAMC:HUSB:GIVN':	
	// I18N: This is the GEDCOM label for "Paternal Grandfather's Given Name"
	$title=i18n::translate('FAMC:HUSB:FAMC:HUSB:GIVN');
	$text='';
	break;

case 'FAMC:HUSB:FAMC:WIFE:GIVN':	
	// I18N: This is the GEDCOM label for "Paternal Grandmother's Given Name"
	$title=i18n::translate('FAMC:HUSB:FAMC:WIFE:GIVN');
	$text='';
	break;

case 'FAMC:HUSB:GIVN':	
	// I18N: This is the GEDCOM label for "Father's Given Name"
	$title=i18n::translate('FAMC:HUSB:GIVN');
	$text='';
	break;

case 'FAMC:HUSB:OCCU':	
	// I18N: This is the GEDCOM label for "Father's Occupation"
	$title=i18n::translate('FAMC:HUSB:OCCU');
	$text='';
	break;

case 'FAMC:HUSB:OCCU':	
	// I18N: This is the GEDCOM label for "Father's Surname"
	$title=i18n::translate('FAMC:HUSB:OCCU');
	$text='';
	break;

case 'FAMC:MARR:PLAC':	
	// I18N: This is the GEDCOM label for "Parents' Marriage Place"
	$title=i18n::translate('FAMC:MARR:PLAC');
	$text='';
	break;

case 'FAMC:MARR:PLAC':	
	// I18N: This is the GEDCOM label for "Mother's Birthplace"
	$title=i18n::translate('FAMC:MARR:PLAC');
	$text='';
	break;

case 'FAMC:WIFE:FAMC:HUSB:GIVN':	
	// I18N: This is the GEDCOM label for "Maternal Grandfather's Given Name"
	$title=i18n::translate('FAMC:WIFE:FAMC:HUSB:GIVN');
	$text='';
	break;

case 'FAMC:WIFE:FAMC:WIFE:GIVN':	
	// I18N: This is the GEDCOM label for "Maternal Grandmother's Given Name"
	$title=i18n::translate('FAMC:WIFE:FAMC:WIFE:GIVN');
	$text='';
	break;

case 'FAMC:WIFE:GIVN':	
	// I18N: This is the GEDCOM label for "Mother's Given Name"
	$title=i18n::translate('FAMC:WIFE:GIVN');
	$text='';
	break;

case 'FAMC:WIFE:SURN':	
	// I18N: This is the GEDCOM label for "Mother's Surname"
	$title=i18n::translate('FAMC:WIFE:SURN');
	$text='';
	break;

case 'FAMF':	
	// I18N: This is the GEDCOM label for "Family File"
	$title=i18n::translate('FAMF');
	$text='';
	break;

case 'FAMS':	
	// I18N: This is the GEDCOM label for "Family as a Spouse"
	$title=i18n::translate('FAMS');
	$text='';
	break;

case 'FAMS:CENS:DATE':	
	// I18N: This is the GEDCOM label for "Spouse Census Date"
	$title=i18n::translate('FAMS:CENS:DATE');
	$text='';
	break;

case 'FAMS:CENS:PLAC':	
	// I18N: This is the GEDCOM label for "Spouse Census Place"
	$title=i18n::translate('FAMS:CENS:PLAC');
	$text='';
	break;

case 'FAMS:CHIL:BIRT:PLAC':	
	// I18N: This is the GEDCOM label for "Child's Birth Place"
	$title=i18n::translate('FAMS:CHIL:BIRT:PLAC');
	$text='';
	break;

case 'FAMS:DIV:DATE':	
	// I18N: This is the GEDCOM label for "Spouse Divorce Date"
	$title=i18n::translate('FAMS:DIV:DATE');
	$text='';
	break;

case 'FAMS:DIV:PLAC':	
	// I18N: This is the GEDCOM label for "Spouse Divorce Place"
	$title=i18n::translate('FAMS:DIV:PLAC');
	$text='';
	break;

case 'FAMS:MARR:DAT':	
	// I18N: This is the GEDCOM label for "Marriage Date"
	$title=i18n::translate('FAMS:MARR:DATE');
	$text='';
	break;

case 'FAMS:MARR:PLAC':	
	// I18N: This is the GEDCOM label for "Marriage Place"
	$title=i18n::translate('FAMS:MARR:PLAC');
	$text='';
	break;

case 'FAMS:NOTE':	
	// I18N: This is the GEDCOM label for "Spouse Note"
	$title=i18n::translate('FAMS:NOTE');
	$text='';
	break;

case 'FAMS:SLGS:DATE':	
	// I18N: This is the GEDCOM label for "LDS Spouse Sealing Date"
	$title=i18n::translate('FAMS:SLGS:DATE');
	$text='';
	break;

case 'FAMS:SLGS:PLAC':	
	// I18N: This is the GEDCOM label for "LDS Spouse Sealing Place"
	$title=i18n::translate('FAMS:SLGS:PLAC');
	$text='';
	break;

case 'FAMS:SLGS:TEMP':	
	// I18N: This is the GEDCOM label for "LDS Spouse Sealing Temple"
	$title=i18n::translate('FAMS:SLGS:TEMP');
	$text='';
	break;

case 'FAMS:SPOUSE:BIRT:PLAC':	
	// I18N: This is the GEDCOM label for "Spouse's Birth Place"
	$title=i18n::translate('FAMS:SPOUSE:BIRT:PLAC');
	$text='';
	break;

case 'FAMS:SPOUSE:DEAT:PLAC':	
	// I18N: This is the GEDCOM label for "Spouse's Death Place"
	$title=i18n::translate('FAMS:SPOUSE:DEAT:PLAC');
	$text='';
	break;

case 'FAX':
	// I18N: This is the GEDCOM label for "FAX"
	$title=i18n::translate('FAX');
	$text=i18n::translate('Enter the FAX number including the country and area code.<br /><br />Leave this field blank if you do not want to include a FAX number.  For example, a number in Germany might be +49 25859 56 76 89 and a number in USA or Canada might be +1 888 555-1212.');
	break;

case 'FCOM':	
	// I18N: This is the GEDCOM label for "First Communion"
	$title=i18n::translate('FCOM');
	$text='';
	break;

case 'FCOM:DATE':	
	// I18N: This is the GEDCOM label for "First Communion Date"
	$title=i18n::translate('FCOM:DATE');
	$text='';
	break;

case 'FCOM:PLAC':	
	// I18N: This is the GEDCOM label for "First Communion Place"
	$title=i18n::translate('FCOM:PLAC');
	$text='';
	break;

case 'FCOM:SOUR':	
	// I18N: This is the GEDCOM label for "First Communion Source"
	$title=i18n::translate('FCOM:SOUR');
	$text='';
	break;

case 'FILE':
	// I18N: This is the GEDCOM label for "External File"
	$title=i18n::translate('FILE');
	$text=i18n::translate('This is the most important field in the multimedia object record.  It indicates which file to use. At the very minimum, you need to enter the file\'s name.  Depending on your settings, more information about the file\'s location may be helpful.<br /><br />You can use the <b>Find Media</b> link to help you locate media items that have already been uploaded to the site.<br /><br />See <a href="readme.txt" target="_blank"><b>Readme.txt</b></a> for more information.');
	break;

case 'FONE':	
	// I18N: This is the GEDCOM label for "Phonetic"
	$title=i18n::translate('FONE');
	$text='';
	break;

case 'FORM':
	// I18N: This is the GEDCOM label for "Format"
	$title=i18n::translate('FORM');
	$text=i18n::translate('This is an optional field that can be used to enter the file format of the multimedia object.  Some genealogy programs may look at this field to determine how to handle the item.  However, since media do not transfer across computer systems very well, this field is not very important.');
	break;

case 'GIVN':
	// I18N: This is the GEDCOM label for "Given Names"
	$title=i18n::translate('GIVN');
	$text=i18n::translate('In this field you should enter the given names for the person.  As an example, in the name "John Robert Finlay", the given names that should be entered here are "John Robert"');
	break;

case 'GRAD':	
	// I18N: This is the GEDCOM label for "Graduation"
	$title=i18n::translate('GRAD');
	$text='';
	break;

case 'HUSB':	
	// I18N: This is the GEDCOM label for "Husband"
	$title=i18n::translate('HUSB');
	$text='';
	break;

case 'IDNO':	
	// I18N: This is the GEDCOM label for "Identification Number"
	$title=i18n::translate('IDNO');
	$text='';
	break;

case 'IMMI':	
	// I18N: This is the GEDCOM label for "Immigration"
	$title=i18n::translate('IMMI');
	$text='';
	break;

case 'LATI':	
	// I18N: This is the GEDCOM label for "Latitude"
	$title=i18n::translate('LATI');
	$text='';
	break;

case 'LEGA':	
	// I18N: This is the GEDCOM label for "Legatee"
	$title=i18n::translate('LEGA');
	$text='';
	break;

case 'LONG':	
	// I18N: This is the GEDCOM label for "Longitude"
	$title=i18n::translate('LONG');
	$text='';
	break;

case 'MAP':	
	// I18N: This is the GEDCOM label for "Map"
	$title=i18n::translate('MAP');
	$text='';
	break;

case 'MARB':	
	// I18N: This is the GEDCOM label for "Marriage Banns"
	$title=i18n::translate('MARB');
	$text='';
	break;

case 'MARB:DATE':	
	// I18N: This is the GEDCOM label for "Marriage Banns Date"
	$title=i18n::translate('MARB:DATE');
	$text='';
	break;

case 'MARB:PLAC':	
	// I18N: This is the GEDCOM label for "Marriage Banns Place"
	$title=i18n::translate('MARB:PLAC');
	$text='';
	break;

case 'MARB:SOUR':	
	// I18N: This is the GEDCOM label for "Marriage Banns Source"
	$title=i18n::translate('MARB:SOUR');
	$text='';
	break;

case 'MARC':	
	// I18N: This is the GEDCOM label for "Marriage Contract"
	$title=i18n::translate('MARC');
	$text='';
	break;

case 'MARL':	
	// I18N: This is the GEDCOM label for "Marriage Licence"
	$title=i18n::translate('MARL');
	$text='';
	break;

case 'MARR':	
	// I18N: This is the GEDCOM label for "Marriage"
	$title=i18n::translate('MARR');
	// I18N: This is a very short abbreviation for the label "Marriage", to be used on genealogy charts
	$abbr=i18n::translate('ABBREV_MARR');
	$text='';
	break;

case 'MARR:':	
	// I18N: This is the GEDCOM label for "Marriage Date"
	$title=i18n::translate('MARR:DATE');
	$text='';
	break;

case 'MARR:PLAC':	
	// I18N: This is the GEDCOM label for "Marriage Place"
	$title=i18n::translate('MARR:PLAC');
	$text='';
	break;

case 'MARR:SOUR':	
	// I18N: This is the GEDCOM label for "Marriage Source"
	$title=i18n::translate('MARR:SOUR');
	$text='';
	break;

case 'MARR_CIVIL':	
	// I18N: This is the GEDCOM label for "Civil Marriage"
	$title=i18n::translate('MARR_CIVIL');
	$text='';
	break;

case 'MARR_PARTNERS':	
	// I18N: This is the GEDCOM label for "Registered Partnership"
	$title=i18n::translate('MARR_PARTNERS');
	$text='';
	break;

case 'MARR_RELIGIOUS':	
	// I18N: This is the GEDCOM label for "Religious Marriage"
	$title=i18n::translate('MARR_RELIGIOUS');
	$text='';
	break;

case 'MARR_UNKNOWN':	
	// I18N: This is the GEDCOM label for "Marriage Type unknown"
	$title=i18n::translate('MARR_UNKNOWN');
	$text='';
	break;

case 'MARS':	
	// I18N: This is the GEDCOM label for "Marriage Settlement"
	$title=i18n::translate('MARS');
	$text='';
	break;

case 'MEDI':	
	// I18N: This is the GEDCOM label for "Media Type"
	$title=i18n::translate('MEDI');
	$text='';
	break;

case 'NAME':
	// I18N: This is the GEDCOM label for "Name"
	$title=i18n::translate('NAME');
	$text=i18n::translate('This is the most important field in a person\'s Name record.<br /><br />This field should be filled automatically as the other fields are filled in, but it is provided so that you can edit the information according to your personal preference.<br /><br />The name in this field should be entered according to the GEDCOM 5.5.1 standards with the surname surrounded by forward slashes "/".  As an example, the name "John Robert Finlay Jr." should be entered like this: "John Robert /Finlay/ Jr.".');
	break;

case 'NAME:FONE':	
	// I18N: This is the GEDCOM label for "Phonetic Name"
	$title=i18n::translate('NAME:FONE');
	$text='';
	break;

case 'NAME:FONE':	
	// I18N: This is the GEDCOM label for "Romanized Name"
	$title=i18n::translate('NAME:FONE');
	$text='';
	break;

case 'NAME:_HEB':	
	// I18N: This is the GEDCOM label for "Name in Hebrew"
	$title=i18n::translate('NAME:_HEB');
	$text='';
	break;

case 'NATI':	
	// I18N: This is the GEDCOM label for "Nationality"
	$title=i18n::translate('NATI');
	$text='';
	break;

case 'NATU':	
	// I18N: This is the GEDCOM label for "Naturalization"
	$title=i18n::translate('NATU');
	$text='';
	break;

case 'NCHI':
	// I18N: This is the GEDCOM label for "Number of Children"
	$title=i18n::translate('NCHI');
	$text=i18n::translate('Enter the number of children for this individual or family. This is an optional field.');
	break;

case 'NICK':
	// I18N: This is the GEDCOM label for "Nickname"
	$title=i18n::translate('NICK');
	$text=i18n::translate('In this field you should enter any nicknames for the person.<br />This is an optional field.<br /><br />Ways to add a nickname:<ul><li>Select <b>modify name</b> then enter nickname and save</li><li>Select <b>add new name</b> then enter nickname AND name and save</li><li>Select <b>edit GEDCOM record</b> to add multiple [2&nbsp;NICK] records subordinate to the main [1&nbsp;NAME] record.</li></ul>');
	break;

case 'NMR':	
	// I18N: This is the GEDCOM label for "Number of Marriages"
	$title=i18n::translate('NMR');
	$text='';
	break;

case 'NOTE':
	// I18N: This is the GEDCOM label for "Note"
	$title=i18n::translate('NOTE');
	$text=i18n::translate('Notes are free-form text and will appear in the Fact Details section of the page.');
	break;

case 'NPFX':
	// I18N: This is the GEDCOM label for "Prefix"
	$title=i18n::translate('NPFX');
	$text=i18n::translate('This optional field allows you to enter a name prefix such as "Dr." or "Adm."');
	break;

case 'NSFX':
	// I18N: This is the GEDCOM label for "Suffix"
	$title=i18n::translate('NSFX');
	$text=i18n::translate('In this optional field you should enter the name suffix for the person.  Examples of name suffixes are "Sr.", "Jr.", and "III".');
	break;

case 'OBJE':	
	// I18N: This is the GEDCOM label for "Multimedia Object"
	$title=i18n::translate('OBJE');
	$text='';
	break;

case 'OCCU':	
	// I18N: This is the GEDCOM label for "Occupation"
	$title=i18n::translate('OCCU');
	$text='';
	break;

case 'ORDI':	
	// I18N: This is the GEDCOM label for "Ordinance"
	$title=i18n::translate('ORDI');
	$text='';
	break;

case 'ORDN':	
	// I18N: This is the GEDCOM label for "Ordination"
	$title=i18n::translate('ORDN');
	$text='';
	break;

case 'PAGE':
	// I18N: This is the GEDCOM label for "Citation Details"
	$title=i18n::translate('PAGE');
	$text=i18n::translate('In the Citation Details field you would enter the page number or other information that might help someone find the information in the source.');
	break;

case 'PEDI':
	// I18N: This is the GEDCOM label for "Pedigree"
	$title=i18n::translate('PEDI');
	$text=i18n::translate('This field describes the relationship of the child to its family.  The possibilities are:<ul><li><b>unknown</b>&nbsp;&nbsp;&nbsp;The child\'s relationship to its family cannot be determined.  When this option is selected, the Pedigree field will not be copied into the database.<br /><br /></li><li><b>Birth</b>&nbsp;&nbsp;&nbsp;This option indicates that the child is related to its family by birth.<br /><br /></li><li><b>Adopted</b>&nbsp;&nbsp;&nbsp;This option indicates that the child was adopted by its family.  This does <i>not</i> indicate that there is no blood relationship between the child and its family; it shows that the child was adopted by the family in question sometime after the child\'s birth.<br /><br /></li><li><b>Foster</b>&nbsp;&nbsp;&nbsp;This option indicates that the child is a foster child of the family.  Usually, there is no blood relationship between the child and its family.<br /><br /></li><li><b>Sealing</b>&nbsp;&nbsp;&nbsp;The child was sealed to its family in an LDS <i>sealing</i> ceremony.  A child sealing is performed when the parents were sealed to each other after the birth of the child.  Children born after the parents\' sealing are automatically sealed to the family.<br /><br /></li></ul>');
	break;

case 'PHON':
	// I18N: This is the GEDCOM label for "Phone"
	$title=i18n::translate('PHON');
	$text=i18n::translate('Enter the phone number including the country and area code.<br /><br />Leave this field blank if you do not want to include a phone number.  For example, a number in Germany might be +49 25859 56 76 89 and a number in USA or Canada might be +1 888 555-1212.');
	break;

case 'PLAC':
	// I18N: This is the GEDCOM label for "Place"
	$title=i18n::translate('PLAC');
	$text=i18n::translate('Places should be entered according to the standards for genealogy.  In genealogy, places are recorded with the most specific information about the place first and then working up to the least specific place last, using commas to separate the different place levels.  The level at which you record the place information should represent the levels of government or church where vital records for that place are kept.<br /><br />For example, a place like Salt Lake City would be entered as "Salt Lake City, Salt Lake, Utah, USA".<br /><br />Let\'s examine each part of this place.  The first part, "Salt Lake City," is the city or township where the event occurred.  In some countries, there may be municipalities or districts inside a city which are important to note.  In that case, they should come before the city.  The next part, "Salt Lake," is the county.  "Utah" is the state, and "USA" is the country.  It is important to note each place because genealogical records are kept by the governments of each level.<br /><br />If a level of the place is unknown, you should leave a space between the commas.  Suppose, in the example above, you didn\'t know the county for Salt Lake City.  You should then record it like this: "Salt Lake City, , Utah, USA".  Suppose you only know that a person was born in Utah.  You would enter the information like this: ", , Utah, USA".  <br /><br />You can use the <b>Find Place</b> link to help you find places that already exist in the database.');
	break;

case 'PLAC:FONE':	
	// I18N: This is the GEDCOM label for "Phonetic Place"
	$title=i18n::translate('PLAC:FONE');
	$text='';
	break;

case 'PLAC:ROMN':	
	// I18N: This is the GEDCOM label for "Romanized Place"
	$title=i18n::translate('PLAC:ROMN');
	$text='';
	break;

case 'PLAC:_HEB':	
	// I18N: This is the GEDCOM label for "Place in Hebrew"
	$title=i18n::translate('PLAC:_HEB');
	$text='';
	break;

case 'POST':	
	// I18N: This is the GEDCOM label for "Postal Code"
	$title=i18n::translate('POST');
	$text='';
	break;

case 'PROB':	
	// I18N: This is the GEDCOM label for "Probate"
	$title=i18n::translate('PROB');
	$text='';
	break;

case 'PROP':	
	// I18N: This is the GEDCOM label for "Property"
	$title=i18n::translate('PROP');
	$text='';
	break;

case 'PUBL':	
	// I18N: This is the GEDCOM label for "Publication"
	$title=i18n::translate('PUBL');
	$text='';
	break;

case 'QUAY':
	// I18N: This is the GEDCOM label for "Quality of Data"
	$title=i18n::translate('QUAY');
	$text=i18n::translate('You would use this field to record the quality or reliability of the data found in this source.  Many genealogy applications use a number in the field. <b>3</b> might mean that the data is a primary source, <b>2</b> might mean that it was a secondary source, <b>1</b> might mean the information is questionable, and <b>0</b> might mean that the source is unreliable.');
	break;

case 'REFN':	
	// I18N: This is the GEDCOM label for "Reference Number"
	$title=i18n::translate('REFN');
	$text='';
	break;

case 'RELA':
	// I18N: This is the GEDCOM label for "Relationship"
	$title=i18n::translate('RELA');
	$text=i18n::translate('Select a relationship name from the list. Selecting <b>Godfather</b> means: <i>This associate is the Godfather of the current individual</i>.');
	break;

case 'RELI':	
	// I18N: This is the GEDCOM label for "Religion"
	$title=i18n::translate('RELI');
	$text='';
	break;

case 'REPO':	
	// I18N: This is the GEDCOM label for "Repository"
	$title=i18n::translate('REPO');
	$text='';
	break;

case 'RESI':	
	// I18N: This is the GEDCOM label for "Residence"
	$title=i18n::translate('RESI');
	$text='';
	break;

case 'RESN':
	// I18N: This is the GEDCOM label for "Restriction"
	$title=i18n::translate('RESN');
	$text=i18n::translate('Apart from general privacy settings, PhpGedView has the ability to set restrictions on viewing and editing fact information for individuals and families. The restrictions can be set by anyone who is allowed to edit the information, unless privacy or formerly set restrictions prohibit this.<br /><br />The following values can be used:<br /><ul><li><b>None</b><br />Site administrators, GEDCOM administrators, and users who have rights to edit can change the information. Fact information can be viewed according to privacy settings as applied by the administrator.</li><li><b>Do not change</b><br />This setting has no influence on the visibility of the fact data. It restricts editing rights to site administrators and GEDCOM administrators. If the information applies to the user himself, he can also view and, assuming he has editing rights, edit it.</li><li><b>Privacy</b><br />Site administrators and GEDCOM administrators can view and edit the information. If the information applies to the user himself, he can also view and, assuming he has editing rights, edit it. It will be hidden from all other users regardless of their login status.</li><li><b>Confidential</b><br />Only site administrators and GEDCOM administrators can view and edit the information. It will be hidden from all other users regardless of their login status.</li></ul><br /><table><tr><th></th><th colspan="2">Admin</th><th colspan="2">Owner</th><th colspan="2">Others</th></tr><tr><th></th><th>R</th><th>W</th><th>R</th><th>W</th><th>R</th><th>W</th></tr><tr><td><img src="images/RESN_none.gif" alt="" /> None</td><th><img src="images/checked.gif" alt="" /></th><th><img src="images/checked.gif" alt="" /></th><th><img src="images/checked_qm.gif" alt="" /></th><th><img src="images/checked_qm.gif" alt="" /></th><th><img src="images/checked_qm.gif" alt="" /></th><th><img src="images/checked_qm.gif" alt="" /></th></tr><tr><td><img src="images/RESN_locked.gif" alt="" /> Do not change</td><th><img src="images/checked.gif" alt="" /></th><th><img src="images/checked.gif" alt="" /></th><th><img src="images/checked_qm.gif" alt="" /></th><th><img src="images/checked_qm.gif" alt="" /></th><th><img src="images/checked_qm.gif" alt="" /></th><th><img src="images/forbidden.gif" alt="" /></th></tr><tr><td><img src="images/RESN_privacy.gif" alt="" /> Privacy</td><th><img src="images/checked.gif" alt="" /></th><th><img src="images/checked.gif" alt="" /></th><th><img src="images/checked_qm.gif" alt="" /></th><th><img src="images/checked_qm.gif" alt="" /></th><th><img src="images/forbidden.gif" alt="" /></th><th><img src="images/forbidden.gif" alt="" /></th></tr><tr><td><img src="images/RESN_confidential.gif" alt="" /> Confidential</td><th><img src="images/checked.gif" alt="" /></th><th><img src="images/checked.gif" alt="" /></th><th><img src="images/forbidden.gif" alt="" /></th><th><img src="images/forbidden.gif" alt="" /></th><th><img src="images/forbidden.gif" alt="" /></th><th><img src="images/forbidden.gif" alt="" /></th></tr></table><ul><li>R : can read</li><li>W : can edit</li><li><img src="images/checked_qm.gif" alt="" /> : depends on global privacy settings</li></ul>');
	break;

case 'RETI':	
	// I18N: This is the GEDCOM label for "Retirement"
	$title=i18n::translate('RETI');
	$text='';
	break;

case 'RFN':	
	// I18N: This is the GEDCOM label for "Record File Number"
	$title=i18n::translate('RFN');
	$text='';
	break;

case 'RIN':	
	// I18N: This is the GEDCOM label for "Record ID Number"
	$title=i18n::translate('RIN');
	$text='';
	break;

case 'ROLE':	
	// I18N: This is the GEDCOM label for "Role"
	$title=i18n::translate('ROLE');
	$text='';
	break;

case 'ROMN':
	// I18N: This is the GEDCOM label for "Romanized"
	$title=i18n::translate('ROMN');
	$text=i18n::translate('In many cultures it is customary to have a traditional name spelled in the traditional characters and also a romanized version of the name as it would be spelled or pronounced in languages based on the Latin alphabet, such as English.<br /><br />If you prefer to use a non-Latin alphabet such as Hebrew, Greek, Russian, Chinese, or Arabic to enter the name in the standard name fields, then you can use this field to enter the same name using the Latin alphabet.  Both versions of the name will appear in lists and charts.<br /><br />Although this field is labelled "Romanized", it is not restricted to containing only characters based on the Latin alphabet.  This might be of use with Japanese names, where three different alphabets may occur.');
	break;

case 'SERV':	
	// I18N: This is the GEDCOM label for "Remote Server"
	$title=i18n::translate('SERV');
	$text='';
	break;

case 'SEX':
	// I18N: This is the GEDCOM label for "Gender"
	$title=i18n::translate('SEX');
	$text=i18n::translate('Choose the appropriate gender from the drop-down list.  The <b>unknown</b> option indicates that the gender is unknown.');
	break;

case 'SHARED_NOTE':
	// I18N: This is the GEDCOM label for "Shared Note"
	$title=i18n::translate('SHARED_NOTE');
	$text=i18n::translate('Shared Notes are free-form text and will appear in the Fact Details section of the page.<br /><br />Each shared note can be linked to more than one person, family, source, or event.');
	break;

case 'SLGC':	
	// I18N: This is the GEDCOM label for "LDS Child Sealing"
	$title=i18n::translate('SLGC');
	$text='';
	break;

case 'SLGS':	
	// I18N: This is the GEDCOM label for "LDS Spouse Sealing"
	$title=i18n::translate('SLGS');
	$text='';
	break;

case 'SOUR':
	// I18N: This is the GEDCOM label for "Source"
	$title=i18n::translate('SOUR');
	$text=i18n::translate('This field allows you to change the source record that this fact\'s source citation links to.  This field takes a Source ID.  Beside the field will be listed the title of the current source ID.  Use the <b>Find ID</b> link to look up the source\'s ID number.  To remove the entire citation, make this field blank.');
	break;

case 'SPFX':
	// I18N: This is the GEDCOM label for "Surname Prefix"
	$title=i18n::translate('SPFX');
	$text=i18n::translate('Enter or select from the list words that precede the main part of the Surname.  Examples of such words are <b>von</b> Braun, <b>van der</b> Kloot, <b>de</b> Graaf, etc.');
	break;

case 'SSN':	
	// I18N: This is the GEDCOM label for "Social Security Number"
	$title=i18n::translate('SSN');
	$text='';
	break;

case 'STAE':	
	// I18N: This is the GEDCOM label for "State"
	$title=i18n::translate('STAE');
	$text='';
	break;

case 'STAT':
	// I18N: This is the GEDCOM label for "Status"
	$title=i18n::translate('STAT');
	$text=i18n::translate('This is an optional status field and is used mostly for LDS ordinances as they are run through the TempleReady program.');
	break;

case 'STAT:DATE':	
	// I18N: This is the GEDCOM label for "Status Change Date"
	$title=i18n::translate('STAT:DATE');
	$text='';
	break;

case 'SURN':
	// I18N: This is the GEDCOM label for "Surname"
	$title=i18n::translate('SURN');
	$text=i18n::translate('In this field you should enter the surname for the person.  As an example, in the name "John Robert Finlay", the surname that should be entered here is "Finlay"<br /><br />Individuals with multiple surnames, common in Spain and Portugal, should separate the surnames with a comma.  This indicates that the person is to be listed under each of the names.  For example, the surname "Cortes,Vega" will be listed under both <b>C</b> and <b>V</b>, whereas the surname "Cortes Vega" will only be listed under <b>C</b>.');
	break;

case 'TEMP':
	// I18N: This is the GEDCOM label for "Temple"
	$title=i18n::translate('TEMP');
	$text=i18n::translate('For LDS ordinances, this field records the Temple where it was performed.');
	break;

case 'TEXT':
	// I18N: This is the GEDCOM label for "Text"
	$title=i18n::translate('TEXT');
	$text=i18n::translate('In this field you would enter the citation text for this source.  Examples of data may be a transcription of the text from the source, or a description of what was in the citation.');
	break;

case 'TIME':
	// I18N: This is the GEDCOM label for "Time"
	$title=i18n::translate('TIME');
	$text=i18n::translate('Enter the time for this event in 24-hour format with leading zeroes. Midnight is 00:00. Examples: 04:50 13:00 20:30.');
	break;

case 'TITL':
	// I18N: This is the GEDCOM label for "Title"
	$title=i18n::translate('TITL');
	$text=i18n::translate('Enter a title for the item you are editing.  If this is a title for a multimedia item, enter a descriptive title that will identify that item to the user.');
	break;

case 'TITL:FONE':	
	// I18N: This is the GEDCOM label for "Phonetic Title"
	$title=i18n::translate('TITL:FONE');
	$text='';
	break;

case 'TITL:ROMN':	
	// I18N: This is the GEDCOM label for "Romanized Title"
	$title=i18n::translate('TITL:ROMN');
	$text='';
	break;

case 'TITL:_HEB':	
	// I18N: This is the GEDCOM label for "Title in Hebrew"
	$title=i18n::translate('TITL:_HEB');
	$text='';
	break;

case 'TYPE':
	// I18N: This is the GEDCOM label for "Type"
	$title=i18n::translate('TYPE');
	$text=i18n::translate('The Type field is used to enter additional information about the item.  In most cases, the field is completely free-form, and you can enter anything you want.');
	break;

case 'URL':
	// I18N: This is the GEDCOM label for "Web URL"
	$title=i18n::translate('URL');
	$text=i18n::translate('Enter the URL address including the http://.<br /><br />An example URL looks like this: <b>http://www.phpgedview.net/</b> Leave this field blank if you do not want to include a URL.');
	break;

case 'WIFE':	
	// I18N: This is the GEDCOM label for "Wife"
	$title=i18n::translate('WIFE');
	$text='';
	break;

case 'WILL':	
	// I18N: This is the GEDCOM label for "Will"
	$title=i18n::translate('WILL');
	$text='';
	break;

case 'WWW':	
	// I18N: This is the GEDCOM label for "Web Home Page"
	$title=i18n::translate('WWW');
	$text='';
	break;

case '_ADOP_CHIL':	
	// I18N: This is the GEDCOM label for "Adoption of a child"
	$title=i18n::translate('_ADOP_CHIL');
	$text='';
	break;

case '_ADOP_COUS':	
	// I18N: This is the GEDCOM label for "Adoption of a first cousin"
	$title=i18n::translate('_ADOP_COUS');
	$text='';
	break;

case '_ADOP_FSIB':	
	// I18N: This is the GEDCOM label for "Adoption of father's sibling"
	$title=i18n::translate('_ADOP_FSIB');
	$text='';
	break;

case '_ADOP_GCHI':	
	// I18N: This is the GEDCOM label for "Adoption of a grandchild"
	$title=i18n::translate('_ADOP_GCHI');
	$text='';
	break;

case '_ADOP_GGCH':	
	// I18N: This is the GEDCOM label for "Adoption of a great-grandchild"
	$title=i18n::translate('_ADOP_GGCH');
	$text='';
	break;

case '_ADOP_HSIB':	
	// I18N: This is the GEDCOM label for "Adoption of half-sibling"
	$title=i18n::translate('_ADOP_HSIB');
	$text='';
	break;

case '_ADOP_MSIB':	
	// I18N: This is the GEDCOM label for "Adoption of mother's sibling"
	$title=i18n::translate('_ADOP_MSIB');
	$text='';
	break;

case '_ADOP_NEPH':	
	// I18N: This is the GEDCOM label for "Adoption of a nephew or niece"
	$title=i18n::translate('_ADOP_NEPH');
	$text='';
	break;

case '_ADOP_SIBL':	
	// I18N: This is the GEDCOM label for "Adoption of sibling"
	$title=i18n::translate('_ADOP_SIBL');
	$text='';
	break;

case '_ADPF':	
	// I18N: This is the GEDCOM label for "Adopted by Father"
	$title=i18n::translate('_ADPF');
	$text='';
	break;

case '_ADPM':	
	// I18N: This is the GEDCOM label for "Adopted by Mother"
	$title=i18n::translate('_ADPM');
	$text='';
	break;

case '_AKA':	
	// I18N: This is the GEDCOM label for "Also known as"
	$title=i18n::translate('_AKA');
	$text='';
	break;

case '_AKAN':	
	// I18N: This is the GEDCOM label for "Also known as"
	$title=i18n::translate('_AKAN');
	$text='';
	break;

case '_BAPM_CHIL':	
	// I18N: This is the GEDCOM label for "Baptism of a child"
	$title=i18n::translate('_BAPM_CHIL');
	$text='';
	break;

case '_BAPM_COUS':	
	// I18N: This is the GEDCOM label for "Baptism of a first cousin"
	$title=i18n::translate('_BAPM_COUS');
	$text='';
	break;

case '_BAPM_FSIB':	
	// I18N: This is the GEDCOM label for "Baptism of father's sibling"
	$title=i18n::translate('_BAPM_FSIB');
	$text='';
	break;

case '_BAPM_GCHI':	
	// I18N: This is the GEDCOM label for "Baptism of a grandchild"
	$title=i18n::translate('_BAPM_GCHI');
	$text='';
	break;

case '_BAPM_GGCH':	
	// I18N: This is the GEDCOM label for "Baptism of a great-grandchild"
	$title=i18n::translate('_BAPM_GGCH');
	$text='';
	break;

case '_BAPM_HSIB':	
	// I18N: This is the GEDCOM label for "Baptism of half-sibling"
	$title=i18n::translate('_BAPM_HSIB');
	$text='';
	break;

case '_BAPM_MSIB':	
	// I18N: This is the GEDCOM label for "Baptism of mother's sibling"
	$title=i18n::translate('_BAPM_MSIB');
	$text='';
	break;

case '_BAPM_NEPH':	
	// I18N: This is the GEDCOM label for "Baptism of a nephew or niece"
	$title=i18n::translate('_BAPM_NEPH');
	$text='';
	break;

case '_BAPM_SIBL':	
	// I18N: This is the GEDCOM label for "Baptism of sibling"
	$title=i18n::translate('_BAPM_SIBL');
	$text='';
	break;

case '_BIBL':	
	// I18N: This is the GEDCOM label for "Bibliography"
	$title=i18n::translate('_BIBL');
	$text='';
	break;

case '_BIRT_CHIL':	
	// I18N: This is the GEDCOM label for "Birth of a child"
	$title=i18n::translate('_BIRT_CHIL');
	$text='';
	break;

case '_BIRT_COUS':	
	// I18N: This is the GEDCOM label for "Birth of a first cousin"
	$title=i18n::translate('_BIRT_COUS');
	$text='';
	break;

case '_BIRT_FSIB':	
	// I18N: This is the GEDCOM label for "Birth of father's sibling"
	$title=i18n::translate('_BIRT_FSIB');
	$text='';
	break;

case '_BIRT_GCHI':	
	// I18N: This is the GEDCOM label for "Birth of a grandchild"
	$title=i18n::translate('_BIRT_GCHI');
	$text='';
	break;

case '_BIRT_GGCH':	
	// I18N: This is the GEDCOM label for "Birth of a great-grandchild"
	$title=i18n::translate('_BIRT_GGCH');
	$text='';
	break;

case '_BIRT_HSIB':	
	// I18N: This is the GEDCOM label for "Birth of half-sibling"
	$title=i18n::translate('_BIRT_HSIB');
	$text='';
	break;

case '_BIRT_MSIB':	
	// I18N: This is the GEDCOM label for "Birth of mother's sibling"
	$title=i18n::translate('_BIRT_MSIB');
	$text='';
	break;

case '_BIRT_NEPH':	
	// I18N: This is the GEDCOM label for "Birth of a nephew or niece"
	$title=i18n::translate('_BIRT_NEPH');
	$text='';
	break;

case '_BIRT_SIBL':	
	// I18N: This is the GEDCOM label for "Birth of sibling"
	$title=i18n::translate('_BIRT_SIBL');
	$text='';
	break;

case '_BRTM':	
	// I18N: This is the GEDCOM label for "Brit Mila"
	$title=i18n::translate('_BRTM');
	$text='';
	break;

case '_BRTM:DATE':	
	// I18N: This is the GEDCOM label for "Brit Mila Date"
	$title=i18n::translate('_BRTM:DATE');
	$text='';
	break;

case '_BRTM:PLAC':	
	// I18N: This is the GEDCOM label for "Brit Mila Place"
	$title=i18n::translate('_BRTM:PLAC');
	$text='';
	break;

case '_BRTM:SOUR':	
	// I18N: This is the GEDCOM label for "Brit Mila Source"
	$title=i18n::translate('_BRTM:SOUR');
	$text='';
	break;

case '_BURI_CHIL':	
	// I18N: This is the GEDCOM label for "Burial of a child"
	$title=i18n::translate('_BURI_CHIL');
	$text='';
	break;

case '_BURI_COUS':	
	// I18N: This is the GEDCOM label for "Burial of a first cousin"
	$title=i18n::translate('_BURI_COUS');
	$text='';
	break;

case '_BURI_FATH':	
	// I18N: This is the GEDCOM label for "Burial of father"
	$title=i18n::translate('_BURI_FATH');
	$text='';
	break;

case '_BURI_FSIB':	
	// I18N: This is the GEDCOM label for "Burial of father's sibling"
	$title=i18n::translate('_BURI_FSIB');
	$text='';
	break;

case '_BURI_GCHI':	
	// I18N: This is the GEDCOM label for "Burial of a grandchild"
	$title=i18n::translate('_BURI_GCHI');
	$text='';
	break;

case '_BURI_GGCH':	
	// I18N: This is the GEDCOM label for "Burial of a great-grandchild"
	$title=i18n::translate('_BURI_GGCH');
	$text='';
	break;

case '_BURI_GGPA':	
	// I18N: This is the GEDCOM label for "Burial of a great-grand-parent"
	$title=i18n::translate('_BURI_GGPA');
	$text='';
	break;

case '_BURI_GPAR':	
	// I18N: This is the GEDCOM label for "Burial of a grand-parent"
	$title=i18n::translate('_BURI_GPAR');
	$text='';
	break;

case '_BURI_HSIB':	
	// I18N: This is the GEDCOM label for "Burial of half-sibling"
	$title=i18n::translate('_BURI_HSIB');
	$text='';
	break;

case '_BURI_MOTH':	
	// I18N: This is the GEDCOM label for "Burial of mother"
	$title=i18n::translate('_BURI_MOTH');
	$text='';
	break;

case '_BURI_MSIB':	
	// I18N: This is the GEDCOM label for "Burial of mother's sibling"
	$title=i18n::translate('_BURI_MSIB');
	$text='';
	break;

case '_BURI_NEPH':	
	// I18N: This is the GEDCOM label for "Burial of a nephew or niece"
	$title=i18n::translate('_BURI_NEPH');
	$text='';
	break;

case '_BURI_SIBL':	
	// I18N: This is the GEDCOM label for "Burial of sibling"
	$title=i18n::translate('_BURI_SIBL');
	$text='';
	break;

case '_BURI_SPOU':	
	// I18N: This is the GEDCOM label for "Burial of spouse"
	$title=i18n::translate('_BURI_SPOU');
	$text='';
	break;

case '_CHR_CHIL':	
	// I18N: This is the GEDCOM label for "Christening of a child"
	$title=i18n::translate('_CHR_CHIL');
	$text='';
	break;

case '_CHR_COUS':	
	// I18N: This is the GEDCOM label for "Christening of a first cousin"
	$title=i18n::translate('_CHR_COUS');
	$text='';
	break;

case '_CHR_FSIB':	
	// I18N: This is the GEDCOM label for "Christening of father's sibling"
	$title=i18n::translate('_CHR_FSIB');
	$text='';
	break;

case '_CHR_GCHI':	
	// I18N: This is the GEDCOM label for "Christening of a grandchild"
	$title=i18n::translate('_CHR_GCHI');
	$text='';
	break;

case '_CHR_GGCH':	
	// I18N: This is the GEDCOM label for "Christening of a great-grandchild"
	$title=i18n::translate('_CHR_GGCH');
	$text='';
	break;

case '_CHR_HSIB':	
	// I18N: This is the GEDCOM label for "Christening of half-sibling"
	$title=i18n::translate('_CHR_HSIB');
	$text='';
	break;

case '_CHR_MSIB':	
	// I18N: This is the GEDCOM label for "Christening of mother's sibling"
	$title=i18n::translate('_CHR_MSIB');
	$text='';
	break;

case '_CHR_NEPH':	
	// I18N: This is the GEDCOM label for "Christening of a nephew or niece"
	$title=i18n::translate('_CHR_NEPH');
	$text='';
	break;

case '_CHR_SIBL':	
	// I18N: This is the GEDCOM label for "Christening of sibling"
	$title=i18n::translate('_CHR_SIBL');
	$text='';
	break;

case '_COML':	
	// I18N: This is the GEDCOM label for "Common Law Marriage"
	$title=i18n::translate('_COML');
	$text='';
	break;

case '_CREM_CHIL':	
	// I18N: This is the GEDCOM label for "Cremation of a child"
	$title=i18n::translate('_CREM_CHIL');
	$text='';
	break;

case '_CREM_COUS':	
	// I18N: This is the GEDCOM label for "Cremation of a first cousin"
	$title=i18n::translate('_CREM_COUS');
	$text='';
	break;

case '_CREM_FATH':	
	// I18N: This is the GEDCOM label for "Cremation of father"
	$title=i18n::translate('_CREM_FATH');
	$text='';
	break;

case '_CREM_FSIB':	
	// I18N: This is the GEDCOM label for "Cremation of father's sibling"
	$title=i18n::translate('_CREM_FSIB');
	$text='';
	break;

case '_CREM_GCHI':	
	// I18N: This is the GEDCOM label for "Cremation of a grandchild"
	$title=i18n::translate('_CREM_GCHI');
	$text='';
	break;

case '_CREM_GGCH':	
	// I18N: This is the GEDCOM label for "Cremation of a great-grandchild"
	$title=i18n::translate('_CREM_GGCH');
	$text='';
	break;

case '_CREM_GGPA':	
	// I18N: This is the GEDCOM label for "Cremation of a great-grand-parent"
	$title=i18n::translate('_CREM_GGPA');
	$text='';
	break;

case '_CREM_GPAR':	
	// I18N: This is the GEDCOM label for "Cremation of a grand-parent"
	$title=i18n::translate('_CREM_GPAR');
	$text='';
	break;

case '_CREM_HSIB':	
	// I18N: This is the GEDCOM label for "Cremation of half-sibling"
	$title=i18n::translate('_CREM_HSIB');
	$text='';
	break;

case '_CREM_MOTH':	
	// I18N: This is the GEDCOM label for "Cremation of mother"
	$title=i18n::translate('_CREM_MOTH');
	$text='';
	break;

case '_CREM_MSIB':	
	// I18N: This is the GEDCOM label for "Cremation of mother's sibling"
	$title=i18n::translate('_CREM_MSIB');
	$text='';
	break;

case '_CREM_NEPH':	
	// I18N: This is the GEDCOM label for "Cremation of a nephew or niece"
	$title=i18n::translate('_CREM_NEPH');
	$text='';
	break;

case '_CREM_SIBL':	
	// I18N: This is the GEDCOM label for "Cremation of sibling"
	$title=i18n::translate('_CREM_SIBL');
	$text='';
	break;

case '_CREM_SPOU':	
	// I18N: This is the GEDCOM label for "Cremation of spouse"
	$title=i18n::translate('_CREM_SPOU');
	$text='';
	break;

case '_DBID':	
	// I18N: This is the GEDCOM label for "Linked database ID"
	$title=i18n::translate('_DBID');
	$text='';
	break;

case '_DEAT_CHIL':	
	// I18N: This is the GEDCOM label for "Death of a child"
	$title=i18n::translate('_DEAT_CHIL');
	$text='';
	break;

case '_DEAT_COUS':	
	// I18N: This is the GEDCOM label for "Death of a first cousin"
	$title=i18n::translate('_DEAT_COUS');
	$text='';
	break;

case '_DEAT_FATH':	
	// I18N: This is the GEDCOM label for "Death of father"
	$title=i18n::translate('_DEAT_FATH');
	$text='';
	break;

case '_DEAT_FSIB':	
	// I18N: This is the GEDCOM label for "Death of father's sibling"
	$title=i18n::translate('_DEAT_FSIB');
	$text='';
	break;

case '_DEAT_GCHI':	
	// I18N: This is the GEDCOM label for "Death of a grandchild"
	$title=i18n::translate('_DEAT_GCHI');
	$text='';
	break;

case '_DEAT_GGCH':	
	// I18N: This is the GEDCOM label for "Death of a great-grandchild"
	$title=i18n::translate('_DEAT_GGCH');
	$text='';
	break;

case '_DEAT_GGPA':	
	// I18N: This is the GEDCOM label for "Death of a great-grand-parent"
	$title=i18n::translate('_DEAT_GGPA');
	$text='';
	break;

case '_DEAT_GPAR':	
	// I18N: This is the GEDCOM label for "Death of a grand-parent"
	$title=i18n::translate('_DEAT_GPAR');
	$text='';
	break;

case '_DEAT_HSIB':	
	// I18N: This is the GEDCOM label for "Death of half-sibling"
	$title=i18n::translate('_DEAT_HSIB');
	$text='';
	break;

case '_DEAT_MOTHhelp':	
	// I18N: This is the GEDCOM label for "Death of mother"
	$title=i18n::translate('_DEAT_MOTH');
	$text='';
	break;

case '_DEAT_MSIB':	
	// I18N: This is the GEDCOM label for "Death of mother's sibling"
	$title=i18n::translate('_DEAT_MSIB');
	$text='';
	break;

case '_DEAT_NEPH':	
	// I18N: This is the GEDCOM label for "Death of a nephew or niece"
	$title=i18n::translate('_DEAT_NEPH');
	$text='';
	break;

case '_':	
	// I18N: This is the GEDCOM label for "Death of sibling"
	$title=i18n::translate('_DEAT_SIBL');
	$text='';
	break;

case '_DEAT_SPOU':	
	// I18N: This is the GEDCOM label for "Death of spouse"
	$title=i18n::translate('_DEAT_SPOU');
	$text='';
	break;

case '_DEG':	
	// I18N: This is the GEDCOM label for "Degree"
	$title=i18n::translate('_DEG');
	$text='';
	break;

case '_DETS':	
	// I18N: This is the GEDCOM label for "Death of One Spouse"
	$title=i18n::translate('_DETS');
	$text='';
	break;

case '_EMAIL':	
	// I18N: This is the GEDCOM label for "Email Address"
	$title=i18n::translate('_EMAIL');
	$text='';
	break;

case '_EYEC':	
	// I18N: This is the GEDCOM label for "Eye Color"
	$title=i18n::translate('_EYEC');
	$text='';
	break;

case '_FA1':	
	// I18N: This is the GEDCOM label for "Fact 1"
	$title=i18n::translate('_FA1');
	$text='';
	break;

case '_FA2':	
	// I18N: This is the GEDCOM label for "Fact 2"
	$title=i18n::translate('_FA2');
	$text='';
	break;

case '_FA3':	
	// I18N: This is the GEDCOM label for "Fact 3"
	$title=i18n::translate('_FA3');
	$text='';
	break;

case '_FA4':	
	// I18N: This is the GEDCOM label for "Fact 4"
	$title=i18n::translate('_FA4');
	$text='';
	break;

case '_FA5':	
	// I18N: This is the GEDCOM label for "Fact 5"
	$title=i18n::translate('_FA5');
	$text='';
	break;

case '_FA6':	
	// I18N: This is the GEDCOM label for "Fact 6"
	$title=i18n::translate('_FA6');
	$text='';
	break;

case '_FA7':	
	// I18N: This is the GEDCOM label for "Fact 7"
	$title=i18n::translate('_FA7');
	$text='';
	break;

case '_FA8':	
	// I18N: This is the GEDCOM label for "Fact 8"
	$title=i18n::translate('_FA8');
	$text='';
	break;

case '_FA9':	
	// I18N: This is the GEDCOM label for "Fact 9"
	$title=i18n::translate('_FA9');
	$text='';
	break;

case '_FA10':	
	// I18N: This is the GEDCOM label for "Fact 10"
	$title=i18n::translate('_FA10');
	$text='';
	break;

case '_FA11':	
	// I18N: This is the GEDCOM label for "Fact 11"
	$title=i18n::translate('_FA11');
	$text='';
	break;

case '_FA12':	
	// I18N: This is the GEDCOM label for "Fact 12"
	$title=i18n::translate('_FA12');
	$text='';
	break;

case '_FA13':	
	// I18N: This is the GEDCOM label for "Fact 13"
	$title=i18n::translate('_FA12');
	$text='';
	break;

case '_FAMC_EMIG':	
	// I18N: This is the GEDCOM label for "Emigration of parents"
	$title=i18n::translate('_FAMC_EMIG');
	$text='';
	break;

case '_FAMC_RESI':	
	// I18N: This is the GEDCOM label for "Residence of parents"
	$title=i18n::translate('_FAMC_RESI');
	$text='';
	break;

case '_FNRL':	
	// I18N: This is the GEDCOM label for "Funeral"
	$title=i18n::translate('_FNRL');
	$text='';
	break;

case '_FREL':	
	// I18N: This is the GEDCOM label for "Relationship to Father"
	$title=i18n::translate('_FREL');
	$text='';
	break;

case '_GEDF':	
	// I18N: This is the GEDCOM label for "GEDCOM File"
	$title=i18n::translate('_GEDF');
	$text='';
	break;

case '_HAIR':	
	// I18N: This is the GEDCOM label for "Hair Color"
	$title=i18n::translate('_HAIR');
	$text='';
	break;

case '_HEB':
	// I18N: This is the GEDCOM label for "Hebrew"
	$title=i18n::translate('_HEB');
	$text=i18n::translate('In many cultures it is customary to have a traditional name spelled in the traditional characters and also a romanized version of the name as it would be spelled or pronounced in languages based on the Latin alphabet, such as English.<br /><br />If you prefer to use the Latin alphabet to enter the name in the standard name fields, then you can use this field to enter the same name in the non-Latin alphabet such as Greek, Hebrew, Russian, Arabic, or Chinese.  Both versions of the name will appear in lists and charts.<br /><br />Although this field is labelled "Hebrew", it is not restricted to containing only Hebrew characters.');
	break;

case '_HEIG':	
	// I18N: This is the GEDCOM label for "Height"
	$title=i18n::translate('_HEIG');
	$text='';
	break;

case '_HNM':	
	// I18N: This is the GEDCOM label for "Hebrew Name"
	$title=i18n::translate('_HNM');
	$text='';
	break;

case '_HOL':	
	// I18N: This is the GEDCOM label for "Holocaust"
	$title=i18n::translate('_HOL');
	$text='';
	break;

case '_INTE':	
	// I18N: This is the GEDCOM label for "Interred"
	$title=i18n::translate('_INTE');
	$text='';
	break;

case '_MARB_CHIL':	
	// I18N: This is the GEDCOM label for "Marriage Bann of a child"
	$title=i18n::translate('_MARB_CHIL');
	$text='';
	break;

case '_MARB_COUS':	
	// I18N: This is the GEDCOM label for "Marriage Bann of a first cousin"
	$title=i18n::translate('_MARB_COUS');
	$text='';
	break;

case '_MARB_FAMC':	
	// I18N: This is the GEDCOM label for "Marriage Bann of parents"
	$title=i18n::translate('_MARB_FAMC');
	$text='';
	break;

case '_MARB_FATH':	
	// I18N: This is the GEDCOM label for "Marriage Bann of father"
	$title=i18n::translate('_MARB_FATH');
	$text='';
	break;

case '_MARB_FSIB':	
	// I18N: This is the GEDCOM label for "Marriage Bann of father's sibling"
	$title=i18n::translate('_MARB_FSIB');
	$text='';
	break;

case '_MARB_GCHI':	
	// I18N: This is the GEDCOM label for "Marriage Bann of a grandchild"
	$title=i18n::translate('_MARB_GCHI');
	$text='';
	break;

case '_MARB_GGCH':	
	// I18N: This is the GEDCOM label for "Marriage Bann of a great-grandchild"
	$title=i18n::translate('_MARB_GGCH');
	$text='';
	break;

case '_MARB_HSIB':	
	// I18N: This is the GEDCOM label for "Marriage Bann of half-sibling"
	$title=i18n::translate('_MARB_HSIB');
	$text='';
	break;

case '_MARB_MOTH':	
	// I18N: This is the GEDCOM label for "Marriage Bann of mother"
	$title=i18n::translate('_MARB_MOTH');
	$text='';
	break;

case '_MARB_MSIB':	
	// I18N: This is the GEDCOM label for "Marriage Bann of mother's sibling"
	$title=i18n::translate('_MARB_MSIB');
	$text='';
	break;

case '_MARB_NEPH':	
	// I18N: This is the GEDCOM label for "Marriage Bann of a nephew or niece"
	$title=i18n::translate('_MARB_NEPH');
	$text='';
	break;

case '_MARB_SIBL':	
	// I18N: This is the GEDCOM label for "Marriage Bann of sibling"
	$title=i18n::translate('_MARB_SIBL');
	$text='';
	break;

case '_MARI':	
	// I18N: This is the GEDCOM label for "Marriage Intention"
	$title=i18n::translate('_MARI');
	$text='';
	break;

case '_MARNM':
	// I18N: This is the GEDCOM label for "Married Name"
	$title=i18n::translate('_MARNM');
	$text=i18n::translate('Enter the married name for this person, using the same formatting rules that apply to the Name field.  This field is optional.<br /><br />For example, if Mary Jane Brown married John White, you might enter (without the quotation marks, of course)<ul><li>American usage:&nbsp;&nbsp;"Mary Jane Brown /White/"</li><li>European usage:&nbsp;&nbsp;"Mary Jane /White/"</li><li>Alternate European usage:&nbsp;&nbsp;"Mary Jane /White-Brown/" or "Mary Jane /Brown-White/"</li></ul>You should do this only if Mary Brown began calling herself by the new name after marrying John White.  In some places, Quebec (Canada) for example, it\'s illegal for names to be changed in this way.<br /><br />Men sometimes change their name after marriage, most often using the hyphenated form but occasionally taking the wife\'s surname.');
	break;

case '_PRIM':
	// I18N: This is the GEDCOM label for "Highlighted Image"
	$title=i18n::translate('_PRIM');
	$text=i18n::translate('Use this field to signal that this media item is the highlighted or primary item for the person it is attached to.  The highlighted image is the one that will be used on charts and on the Individual page.');
	break;

case '_MARNM_SURN':	
	// I18N: This is the GEDCOM label for "Married Surname"
	$title=i18n::translate('_MARNM_SURN');
	$text='';
	break;

case '_MARR_CHIL':	
	// I18N: This is the GEDCOM label for "Marriage of a child"
	$title=i18n::translate('_MARR_CHIL');
	$text='';
	break;

case '_MARR_COUS':	
	// I18N: This is the GEDCOM label for "Marriage of a first cousin"
	$title=i18n::translate('_MARR_COUS');
	$text='';
	break;

case '_MARR_FAMC':	
	// I18N: This is the GEDCOM label for "Marriage of parents"
	$title=i18n::translate('_MARR_FAMC');
	$text='';
	break;

case '_MARR_FATH':	
	// I18N: This is the GEDCOM label for "Marriage of father"
	$title=i18n::translate('_MARR_FATH');
	$text='';
	break;

case '_MARR_FSIB':	
	// I18N: This is the GEDCOM label for "Marriage of father's sibling"
	$title=i18n::translate('_MARR_FSIB');
	$text='';
	break;

case '_MARR_GCHI':	
	// I18N: This is the GEDCOM label for "Marriage of a grandchild"
	$title=i18n::translate('_MARR_GCHI');
	$text='';
	break;

case '_MARR_GGCH':	
	// I18N: This is the GEDCOM label for "Marriage of a great-grandchild"
	$title=i18n::translate('_MARR_GGCH');
	$text='';
	break;

case '_MARR_HSIB':	
	// I18N: This is the GEDCOM label for "Marriage of half-sibling"
	$title=i18n::translate('_MARR_HSIB');
	$text='';
	break;

case '_MARR_MOTH':	
	// I18N: This is the GEDCOM label for "Marriage of mother"
	$title=i18n::translate('_MARR_MOTH');
	$text='';
	break;

case '_MARR_MSIB':	
	// I18N: This is the GEDCOM label for "Marriage of mother's sibling"
	$title=i18n::translate('_MARR_MSIB');
	$text='';
	break;

case '_MARR_NEPH':	
	// I18N: This is the GEDCOM label for "Marriage of a nephew or niece"
	$title=i18n::translate('_MARR_NEPH');
	$text='';
	break;

case '_MARR_SIBL':	
	// I18N: This is the GEDCOM label for "Marriage of sibling"
	$title=i18n::translate('_MARR_SIBL');
	$text='';
	break;

case '_MBON':	
	// I18N: This is the GEDCOM label for "Marriage Bond"
	$title=i18n::translate('_MBON');
	$text='';
	break;

case '_MDCL':	
	// I18N: This is the GEDCOM label for "Medical"
	$title=i18n::translate('_MDCL');
	$text='';
	break;

case '_MEDC':	
	// I18N: This is the GEDCOM label for "Medical Condition"
	$title=i18n::translate('_MEDC');
	$text='';
	break;

case '_MEND':	
	// I18N: This is the GEDCOM label for "Marriage Ending Status"
	$title=i18n::translate('_MEND');
	$text='';
	break;

case '_MILI':	
	// I18N: This is the GEDCOM label for "Military"
	$title=i18n::translate('_MILI');
	$text='';
	break;

case '_MILT':	
	// I18N: This is the GEDCOM label for "Military Service"
	$title=i18n::translate('_MILT');
	$text='';
	break;

case '_MREL':	
	// I18N: This is the GEDCOM label for "Relationship to Mother"
	$title=i18n::translate('_MREL');
	$text='';
	break;

case '_MSTAT':	
	// I18N: This is the GEDCOM label for "Marriage Beginning Status"
	$title=i18n::translate('_MSTAT');
	$text='';
	break;

case '_NAME':	
	// I18N: This is the GEDCOM label for "Mailing Name"
	$title=i18n::translate('_NAME');
	$text='';
	break;

case '_NAMS':	
	// I18N: This is the GEDCOM label for "Namesake"
	$title=i18n::translate('_NAMS');
	$text='';
	break;

case '_NLIV':	
	// I18N: This is the GEDCOM label for "Not living"
	$title=i18n::translate('_NLIV');
	$text='';
	break;

case '_NMAR':	
	// I18N: This is the GEDCOM label for "Never married"
	$title=i18n::translate('_NMAR');
	$text='';
	break;

case '_NMR':	
	// I18N: This is the GEDCOM label for "Not married"
	$title=i18n::translate('_NMR');
	$text='';
	break;

case '_PGVU':	
	// I18N: This is the GEDCOM label for "by"
	$title=i18n::translate('_PGVU');
	$text='';
	break;

case '_PRMN':	
	// I18N: This is the GEDCOM label for "Permanent Number"
	$title=i18n::translate('_PRMN');
	$text='';
	break;

case '_SCBK':	
	// I18N: This is the GEDCOM label for "Scrapbook"
	$title=i18n::translate('_SCBK');
	$text='';
	break;

case '_SEPR':	
	// I18N: This is the GEDCOM label for "Separated"
	$title=i18n::translate('_SEPR');
	$text='';
	break;

case '_SSHOW':	
	// I18N: This is the GEDCOM label for "Slide Show"
	$title=i18n::translate('_SSHOW');
	$text='';
	break;

case '_STAT':	
	// I18N: This is the GEDCOM label for "Marriage Status"
	$title=i18n::translate('_STAT');
	$text='';
	break;

case '_SUBQ':	
	// I18N: This is the GEDCOM label for "Short Version"
	$title=i18n::translate('_SUBQ');
	$text='';
	break;

case '_THUM':
	// I18N: This is the GEDCOM label for "Always use main image?"
	$title=i18n::translate('_THUM');
	$text=i18n::translate('This option lets you override the usual selection for a thumbnail image.<br /><br />The GEDCOM has a configuration option that specifies whether PhpGedView should send the large or the small image to the browser whenever the current page requires a thumbnail.  The &laquo;Always use main image?&raquo; option, when set to <b>Yes</b>, temporarily overrides the setting of the GEDCOM configuration option, so that PhpGedView will always send the large image.  You cannot force PhpGedView to send the small image when the GEDCOM configuration specifies that large images should always be used.<br /><br />PhpGedView does not re-size the image being sent; the browser does this according to the page specifications it has also received.  This can have undesirable consequences when the image being sent is not truly a thumbnail where PhpGedView is expecting to send a small image.  This is not an error:  There are occasions where it may be desirable to display a large image in places where one would normally expect to see a thumbnail-sized picture.<br /><br />You should avoid setting the &laquo;Always use main image?&raquo; option to <b>Yes</b>.  This choice will cause excessive amounts of image-related data to be sent to the browser, only to have the browser discard the excess.  Page loads, particularly of charts with many images, can be seriously slowed.');
	break;

case '_TODO':	
	// I18N: This is the GEDCOM label for "To Do Item"
	$title=i18n::translate('_TODO');
	$text='';
	break;

case '_TYPE':	
	// I18N: This is the GEDCOM label for "Media Type"
	$title=i18n::translate('_TYPE');
	$text='';
	break;

case '_UID':	
	// I18N: This is the GEDCOM label for "Globally unique Identifier"
	$title=i18n::translate('_UID');
	$text='';
	break;

case '_URL':	
	// I18N: This is the GEDCOM label for "Web URL"
	$title=i18n::translate('_URL');
	$text='';
	break;

case '_WEIG':	
	// I18N: This is the GEDCOM label for "Weight"
	$title=i18n::translate('_WEIG');
	$text='';
	break;

case '_YART':	
	// I18N: This is the GEDCOM label for "Yahrzeit"
	$title=i18n::translate('_YART');
	$text='';
	break;

case '__BRTM_CHIL':	
	// I18N: This is the GEDCOM label for "Brit Mila of a child"
	$title=i18n::translate('__BRTM_CHIL');
	$text='';
	break;

case '__BRTM_COUS':	
	// I18N: This is the GEDCOM label for "Brit Mila of a first cousin"
	$title=i18n::translate('__BRTM_COUS');
	$text='';
	break;

case '__BRTM_FSIB':	
	// I18N: This is the GEDCOM label for "Brit Mila of father's sibling"
	$title=i18n::translate('__BRTM_FSIB');
	$text='';
	break;

case '__BRTM_GCHI':	
	// I18N: This is the GEDCOM label for "Brit Mila of a grandchild"
	$title=i18n::translate('__BRTM_GCHI');
	$text='';
	break;

case '__BRTM_GGCH':	
	// I18N: This is the GEDCOM label for "Brit Mila of a great-grandchild"
	$title=i18n::translate('__BRTM_GGCH');
	$text='';
	break;

case '__BRTM_HSIB':	
	// I18N: This is the GEDCOM label for "Brit Mila of half-sibling"
	$title=i18n::translate('__BRTM_HSIB');
	$text='';
	break;

case '__BRTM_MSIB':	
	// I18N: This is the GEDCOM label for "Brit Mila of mother's sibling"
	$title=i18n::translate('__BRTM_MSIB');
	$text='';
	break;

case '__BRTM_NEPH':	
	// I18N: This is the GEDCOM label for "Brit Mila of a nephew"
	$title=i18n::translate('__BRTM_NEPH');
	$text='';
	break;

case '__BRTM_SIBL':	
	// I18N: This is the GEDCOM label for "Brit Mila of sibling"
	$title=i18n::translate('__BRTM_SIBL');
	$text='';
	break;

	//////////////////////////////////////////////////////////////////////////////
	// This section contains an entry for every page.  It is used to provide a
	// page title and "help about this page".
	//////////////////////////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////
	// This section contains an entry for every configuration item
	//////////////////////////////////////////////////////////////////////////////
case 'ABBREVIATE_CHART_LABELS':
	$title=i18n::translate('Abbreviate chart labels');
	$text=i18n::translate('This option controls whether or not to abbreviate labels like <b>Birth</b> on charts with just the first letter like <b>B</b>.<br /><br />You can customize the abbreviations by supplying overriding values in the <i>languages/extra.xx.php</i> file for each language.  For example, if you want to use <b>*</b> instead of <b>N</b> to abbreviate the BIRT fact in the French language, you should put the following entry into the <i>languages/extra.fr.php</i> file:<br /><br /><code>$factAbbrev["BIRT"]&nbsp;=&nbsp;"*";</code><br /><br />The lengths of abbreviations specified this way are not limited to 1 character.');
	break;

case 'ADVANCED_NAME_FACTS':
	$title=i18n::translate('Advanced name facts');
	$text=i18n::translate('This is a comma separated list of GEDCOM fact tags that will be shown on the add/edit name form.  If you use non-Latin alphabets such as Hebrew, Greek, Cyrillic or Arabic, you may want to add tags such as _HEB, ROMN, FONE, etc. to allow you to store names in several different alphabets.');
	break;

case 'ADVANCED_PLAC_FACTS':
	$title=i18n::translate('Advanced place name facts');
	$text=i18n::translate('This is a comma separated list of GEDCOM fact tags that will be shown when you add or edit place names.  If you use non-Latin alphabets such as Hebrew, Greek, Cyrillic or Arabic, you may want to add tags such as _HEB, ROMN, FONE, etc. to allow you to store place names in several different alphabets.');
	break;

case 'ALLOW_CHANGE_GEDCOM':
	$title=i18n::translate('Allow GEDCOM switching');
	$text=i18n::translate('If you have an environment with multiple GEDCOMs, setting this value to <b>Yes</b> allows your site visitors <u>and</u> users to have the option of changing GEDCOMs.  Setting it to <b>No</b> disables GEDCOM switching for both visitors <u>and</u> logged in users.');
	break;

case 'ALLOW_EDIT_GEDCOM':
	$title=i18n::translate('Enable online editing');
	$text=i18n::translate('This option enables online editing features for this database so that users with Edit privileges may update data online.');
	break;

case 'ALLOW_THEME_DROPDOWN':
	$title=i18n::translate('Display theme dropdown selector for theme changes');
	$text=i18n::translate('Gives users the option of selecting their own theme from a menu.<br /><br />Even with this option set, the theme currently in effect may not provide for such a menu.  To be effective, this option requires the <b>Allow users to select their own theme</b> option to be set as well.');
	break;

case 'ALLOW_USER_THEMES':
	$title=i18n::translate('Allow users to select their own theme');
	$text=i18n::translate('Gives users the option of selecting their own theme.');
	break;

case 'AUTO_GENERATE_THUMBS':
	$title=i18n::translate('Automatically generated thumbnails');
	$text=i18n::translate('Should the system automatically generate thumbnails for images that do not have them.  Your PHP installation might not support this functionality.');
	break;

case 'BOM_detected':
	$title=i18n::translate('A Byte Order Mark (BOM) was detected at the beginning of the file. On cleanup, this special code will be removed.');
	$text=i18n::translate('The GEDCOM file you are importing has a special 3-byte code at the beginning.  This special code is used by some programs to indicate that the file has been recorded in the UTF-8 character set.<br /><br />Although this special code is not really an error, PhpGedView will not work properly when the input file contains the code.  You should let PhpGedView remove the code.');
	break;

case 'CALENDAR_FORMAT':
	$title=i18n::translate('Calendar format');
	$text=i18n::translate('Dates can be recorded in various calendars such as Gregorian, Julian, or the Jewish Calendar.  This option allows you to convert dates to a preferred calendar.  For example, you could select Gregorian to convert Julian and Hebrew dates to Gregorian.  The converted date is shown in parentheses after the regular date.<br /><br />Dates are only converted if they are valid for the calendar.  For example, only dates between 22&nbsp;SEP&nbsp;1792 and 31&nbsp;DEC&nbsp;1805 will be converted to the French Republican calendar and only dates after 15&nbsp;OCT&nbsp;1582 will be converted to the Gregorian calendar.<br /><br />Hebrew is the same as Jewish, but using Hebrew characters.  Arabic is the same as Hijri, but using Arabic characters.<br /><br />Note: Since the Jewish and Hijri calendar day starts at dusk, any event taking place from dusk till midnight will display as one day prior to the correct date.  The display of Hebrew and Arabic can be problematic in old browsers, which may display text backwards (left to right) or not at all.');
	break;

case 'CHANGELOG_CREATE':
	$title=i18n::translate('Archive ChangeLog files');
	$text=i18n::translate('How often should the program archive Changelog files.');
	break;

case 'CHARACTER_SET':
	$title=i18n::translate('Character Set encoding');
	$text=i18n::translate('This is the character set of your GEDCOM file.  UTF-8 is the default and should work for almost all sites.  If you export your GEDCOM using IBM Windows encoding, you should put WINDOWS here.<br /><br />NOTE: PhpGedView can\'t support UNICODE (UTF-16) because the support is missing in PHP.');
	break;

case 'CHART_BOX_TAGS':
	$title=i18n::translate('Other facts to show in charts');
	$text=i18n::translate('This should be a comma or space separated list of facts, in addition to Birth and Death, that you want to appear in chart boxes such as the Pedigree chart.  This list requires you to use fact tags as defined in the GEDCOM 5.5.1 Standard.  For example, if you wanted the occupation to show up in the box, you would add "OCCU" to this field.');
	break;

case 'CHECK_CHILD_DATES':
	$title=i18n::translate('Check child dates');
	$text=i18n::translate('Check children\'s dates when determining whether a person is dead.  On older systems and large GEDCOMs this can slow down the response time of your site.');
	break;

case 'CHECK_MARRIAGE_RELATIONS':
	$title=i18n::translate('Check marriage relations');
	$text=i18n::translate('Check relationships that are related by marriage.');
	break;

case 'COMMIT_COMMAND':
	$title=i18n::translate('Version Control Commit Command');
	$text=i18n::translate('If you wish to use a version control system such as CVS to archive changes to your GEDCOM file and your configuration or privacy settings, enter the command here.  Leave the box blank if you do not wish to use a version control system.  Valid options are <b>cvs</b> and <b>svn</b>.');
	break;

case 'COMMON_NAMES_ADD':
	$title=i18n::translate('Names to add to Common Surnames (comma separated)');
	$text=i18n::translate('If the number of times that a certain surname occurs is lower than the threshold, it will not appear in the list.  It can be added here manually.  If more than one surname is entered, they must be separated by a comma.  <b>Surnames are case-sensitive.</b>');
	break;

case 'COMMON_NAMES_REMOVE':
	$title=i18n::translate('Names to remove from Common Surnames (comma separated)');
	$text=i18n::translate('If you want to remove a surname from the Common Surname list without increasing the threshold value, you can do that by entering the surname here.  If more than one surname is entered, they must be separated by a comma. <b>Surnames are case-sensitive.</b>  Surnames entered here will also be removed from the Top-10 list on the Welcome page.');
	break;

case 'COMMON_NAMES_THRESHOLD':
	$title=i18n::translate('Min. no. of occurrences to be a "Common Surname"');
	$text=i18n::translate('This is the number of times that a surname must occur before it shows up in the Common Surname list on the Welcome page.');
	break;

case 'CONTACT_EMAIL':
	$title=i18n::translate('Genealogy contact');
	$text=i18n::translate('The person to contact about the genealogical data on this site.');
	break;

case 'CONTACT_METHOD':
	$title=i18n::translate('Contact method');
	$text=i18n::translate('The method to be used to contact the Genealogy contact about genealogy questions.<ul><li>The <b>Mailto link</b> option will create a "mailto" link that can be clicked to send an email using the mail client on the user\'s PC.</li><li>The <b>PhpGedView internal messaging</b> option will use a messaging system internal to PhpGedView, and no emails will be sent.</li><li>The <b>Internal messaging with emails</b> option is the default.  It will use the PhpGedView messaging system and will also send copies of the messages via email.</li><li>The <b>PhpGedView sends emails with no storage</b> option allows PhpGedView to handle the messaging and will send the messages as emails, but will not store the messages internally.  This option is similar to the <b>Mailto link</b> option, except that the message will be sent by PhpGedView instead of the user\'s workstation.</li><li>The <b>No contact method</b> option results in your users having no way of contacting you.</li></ul>');
	break;

case 'DAYS_TO_SHOW_LIMIT':
	$title=i18n::translate('Upcoming Events block day limit');
	$text=i18n::translate('Enter the maximum number of days to show in Upcoming Events blocks.  This number cannot be greater than 30. If you enter a larger value, 30 will be used.<br /><br />The value you enter here determines how far ahead PhpGedView looks when searching for upcoming events.  The results of this search, done once daily, are copied into a temporary file.<br /><br />No Upcoming Events blocks on Index or Portal pages can request more days than this value.  The larger you make this, the longer it will take to build the daily database extract, and the longer it will take to display the block, even when you request to display a number of days less than this setting.');
	break;

case 'DBHOST':
	$title=i18n::translate('Database Host');
	$text=i18n::translate('The DNS or IP address of the computer hosting your database server.  This setting is ignored if you are using an SQLite database.');
	break;

case 'DBNAME':
	$title=i18n::translate('Database Name');
	$text=i18n::translate('The database in the server you want PhpGedView to use.<br /><br />The username you enter in the Database Username field must have Create, Insert, Update, Delete, and Select privileges on this database.  In SQLite you need to set this to a file name in a directory that is writable by PHP such as your index directory (index/phpgedview.db).');
	break;

case 'DBPASS':
	$title=i18n::translate('Database Password');
	$text=i18n::translate('The database password for the user you entered in the Database Username field.  This setting is ignored if you are using an SQLite database.');
	break;

case 'DBPORT':
	$title=i18n::translate('Database Port');
	$text=i18n::translate('The TCP Port that should be used when connecting to your database server.  Leave this setting blank to use the default port for your particular type of database.  This setting is ignored if you are using an SQLite database.');
	break;

case 'DBTYPE':
	$title=i18n::translate('Database Type');
	$text=i18n::translate('The type of database to connect to.<br /><br />PhpGedView can use any database that has a PDO driver available.  You need to ensure that your php.ini file loads both the <b>php_pdo</b> library and the appropriate driver for your database, e.g. <b>php_pdo_mysql</b>.<br /><br />Most databases require a username and password.  You also need to create the database before installing PhpGedView. However, SQLite does not need Database Host, Database Username, or Database Password, but you will need to set a file path for your database in the Database Name field.  PhpGedView will create the SQLite database file for you.');
	break;

case 'DBUSER':
	$title=i18n::translate('Database Username');
	$text=i18n::translate('The database username required to login to your database.  This setting is ignored if you are using an SQLite database.');
	break;

case 'DB_UTF8_COLLATION':
	$title=i18n::translate('Use the database to provide UTF-8 collation');
	$text=i18n::translate('Controls whether PhpGedView should use the database\'s built-in sorting and collation facilities.  It is generally quicker to use the database to sort and filter data rather than PHP, although not all databases/versions provide this feature.  The collation sequence used for each language is set in that language\'s settings page.<br /><br /><span class="warning">IMPORTANT: You should only set this value to YES if you do so BEFORE the database tables are created for the first time. Selecting it on an existing database could cause your data to become corrupted.</span><br /><br />MySQL and PostgreSQL both offer good support for UTF-8, although not all collation sequences are available in earlier versions of MySQL.  Other databases offer little or no support for UTF-8. If you are unsure of your database\'s support of UTF-8, you should set this value to <b>No</b>.<br /><br />This <a href=\'http://en.wikipedia.org/wiki/UTF-8\' target=\'_blank\' title=\'Wikipedia article\'><b>Wikipedia article</b></a> contains comprehensive information and links about UTF-8.');
	break;

case 'DEFAULT_PEDIGREE_GENERATIONS':
	$title=i18n::translate('Pedigree generations');
	$text=i18n::translate('Set the default number of generations to display on Descendancy and Pedigree charts.');
	break;

case 'DISPLAY_JEWISH_GERESHAYIM':
	$title=i18n::translate('Display Hebrew Gershayim');
	$text=i18n::translate('Show single and double quotes when displaying Hebrew dates.<br /><br />Setting this to <b>Yes</b> will display February 8 1969 as <span lang=\'he-IL\' dir=\'rtl\'>&#1499;\'&#160;&#1513;&#1489;&#1496;&#160;&#1514;&#1513;&#1499;&quot;&#1496;</span>&lrm; while setting it to <b>No</b> will display it as <span lang=\'he-IL\' dir=\'rtl\'>&#1499;&#160;&#1513;&#1489;&#1496;&#160;&#1514;&#1513;&#1499;&#1496;</span>&lrm;.  This has no impact on the Jewish year setting since quotes are not used in Jewish dates displayed with Latin characters.<br /><br />Note: This setting is similar to the PHP 5.0 Calendar constants CAL_JEWISH_ADD_ALAFIM_GERESH and CAL_JEWISH_ADD_GERESHAYIM.  This single setting affects both.');
	break;

case 'DISPLAY_JEWISH_THOUSANDS':
	$title=i18n::translate('Display Hebrew Thousands');
	$text=i18n::translate('Show Alafim in Hebrew calendars.<br /><br />Setting this to <b>Yes</b> will display the year 1969 as <span lang="he-IL" dir=\'rtl\'>&#1492;\'&#160;&#1514;&#1513;&#1499;&quot;&#1496;</span>&lrm; while setting it to <b>No</b> will display the year as <span lang="he-IL" dir=\'rtl\'>&#1514;&#1513;&#1499;&quot;&#1496;</span>&lrm;.  This has no impact on the Jewish year setting.  The year will display as 5729 regardless of this setting.<br /><br />Note: This setting is similar to the PHP 5.0 Calendar constant CAL_JEWISH_ADD_ALAFIM.');
	break;

case 'EDIT_AUTOCLOSE':
	$title=i18n::translate('Autoclose edit window');
	$text=i18n::translate('This option controls whether or not to automatically close the Edit window after a successful update.');
	break;

case 'ENABLE_AUTOCOMPLETE':
	$title=i18n::translate('Enable Autocomplete');
	$text=i18n::translate('This option determines whether Autocomplete should be active while information is being entered into certain fields on input forms.  When this option is set to <b>Yes</b>, text input fields for which Autocomplete is possible are indicated by a differently colored background.<br /><br />When Autocomplete is active, PhpGedView will search its database for possible matches according to what you have already entered.  As you enter more information, the list of possible matches is refined.  When you see the desired input in the list of matches, you can move the mouse cursor to that line of the list and then click the left mouse button to complete the input.<br /><br />The disadvantages of Autocomplete are that it slows the program, entails significant database activity, and also results in more data being sent to the browser.');
	break;

case 'ENABLE_CLIPPINGS_CART':
	$title=i18n::translate('Enable Clippings Cart');
	$text=i18n::translate('The clippings cart allows users to add people to a temporary file that they can download in GEDCOM format for subsequent import into their genealogy software.');
	break;

case 'ENABLE_MULTI_LANGUAGE':
	$title=i18n::translate('Allow user to change language');
	$text=i18n::translate('Set to <b>Yes</b> to allow users to override the site\'s default language.  They can do this through their browser\'s preferred language configuration, configuration options on their Account page, or through links or buttons on most PhpGedView pages.');
	break;

case 'ENABLE_RSS':
	$title=i18n::translate('Enable RSS');
	$text=i18n::translate('This option lets you disable the RSS feature.<br /><br />RSS lets users monitor your site for changes to the Index page without actually visiting your site periodically.  If too many users make use of this feature or if the refresh frequency set by these users is too high, RSS can use up too much bandwidth or server capacity.<br /><br />This <a href=\'http://en.wikipedia.org/wiki/RSS\' target=\'_blank\' title=\'Wikipedia article\'><b>Wikipedia article</b></a> contains comprehensive information and links about RSS and the various RSS formats.');
	break;

case 'EXPAND_NOTES':
	$title=i18n::translate('Automatically expand notes');
	$text=i18n::translate('This option controls whether or not to automatically display content of a <i>Note</i> record on the Individual page.');
	break;

case 'EXPAND_RELATIVES_EVENTS':
	$title=i18n::translate('Automatically expand list of events of close relatives');
	$text=i18n::translate('This option controls whether or not to automatically expand the <i>Events of close relatives</i> list.');
	break;

case 'EXPAND_SOURCES':
	$title=i18n::translate('Automatically expand sources');
	$text=i18n::translate('This option controls whether or not to automatically display content of a <i>Source</i> record on the Individual page.');
	break;

case 'FAM_FACTS_ADD':
	$title=i18n::translate('Family Add Facts');
	$text=i18n::translate('This is the list of GEDCOM facts that your users can add to families.  You can modify this list by removing or adding fact names, even custom ones, as necessary.  Fact names that appear in this list must not also appear in the <i>Unique Family Facts</i> list.');
	break;

case 'FAM_FACTS_QUICK':
	$title=i18n::translate('Quick Family Facts');
	$text=i18n::translate('This is the short list of GEDCOM family facts that appears next to the full list and can be added with a single click.');
	break;

case 'FAM_FACTS_UNIQUE':
	$title=i18n::translate('Unique Family Facts');
	$text=i18n::translate('This is the list of GEDCOM facts that your users can only add <u>once</u> to families.  For example, if MARR is in this list, users will not be able to add more than one MARR record to a family.  Fact names that appear in this list must not also appear in the <i>Family Add Facts</i> list.');
	break;

case 'FAM_ID_PREFIX':
	$title=i18n::translate('Family ID prefix');
	$text=i18n::translate('When a new family record is added online in PhpGedView, a new ID for that family will be generated automatically. The family ID will have this prefix.');
	break;

case 'FAVICON':
	$title=i18n::translate('Favorites icon');
	$text=i18n::translate('Change this to point to the icon you want to display in peoples\' favorites menu when they bookmark your site.');
	break;

case 'FULL_SOURCES':
	$title=i18n::translate('Use full source citations');
	$text=i18n::translate('Source citations can include fields to record the quality of the data (primary, secondary, etc.) and the date the event was recorded in the source.  If you don\'t use these fields, you can disable them when creating new source citations.');
	break;

case 'GEDCOM_DEFAULT_TAB':
	$title=i18n::translate('Default tab to show on Individual page');
	$text=i18n::translate('This option allows you to choose which tab opens automatically on the Individual page when that page is accessed.');
	break;

case 'GEDCOM_ID_PREFIX':
	$title=i18n::translate('Individual ID prefix');
	$text=i18n::translate('When a new individual record is added online in PhpGedView, a new ID for that individual will be generated automatically. The individual ID will have this prefix.');
	break;

case 'GENERATE_GUID':
	$title=i18n::translate('Automatically create globally unique IDs');
	$text=i18n::translate('<b>GUID</b> in this context is an acronym for «Globally Unique ID».<br /><br />GUIDs are intended to help identify each individual in a manner that is repeatable, so that central organizations such as the Family History Center of the LDS Church in Salt Lake City, or even compatible programs running on your own server, can determine whether they are dealing with the same person no matter where the GEDCOM originates.  The goal of the Family History Center is to have a central repository of genealogical data and expose it through web services. This will enable any program to access the data and update their data within it.<br /><br />If you do not intend to share this GEDCOM with anyone else, you do not need to let PhpGedView create these GUIDs; however, doing so will do no harm other than increasing the size of your GEDCOM.');
	break;

case 'HIDE_GEDCOM_ERRORS':
	$title=i18n::translate('Hide GEDCOM errors');
	$text=i18n::translate('Setting this to <b>Yes</b> will hide error messages produced by PhpGedView when it doesn\'t understand a tag in your GEDCOM file.  PhpGedView makes every effort to conform to the GEDCOM 5.5.1 standard, but many genealogy software programs include their own custom tags.  See the <a href="readme.txt">readme.txt</a> file for more information.');
	break;

case 'HIDE_LIVE_PEOPLE':
	$title=i18n::translate('Enable Privacy');
	$text=i18n::translate('This option will enable all privacy settings and hide the details of living people.<br /><br />Living people are defined to be those who do not have an event more recent than the number of years specified in variable $MAX_ALIVE_AGE.  For this purpose, births of children are considered to be such events as well.');
	break;

case 'HOME_SITE_TEXT':
	$title=i18n::translate('Main WebSite text');
	$text=i18n::translate('The legend used to identify the link to your main Home page.');
	break;

case 'HOME_SITE_URL':
	$title=i18n::translate('Main WebSite URL');
	$text=i18n::translate('Each PhpGedView page includes a link to your main Home page.  The appearance of this link is controlled by the theme being used.  You enter the actual URL to your Home site here.');
	break;

case 'INDEX_DIRECTORY':
	$title=i18n::translate('Index file directory');
	$text=i18n::translate('The path to a readable and writable directory where PhpGedView should store index files (include the trailing "/").  PhpGedView does not require this directory\'s name to be "index".  You can choose any name you like.<br /><br />For security, this directory should be placed somewhere in the server\'s file space that is not accessible from the Internet. An example of such a structure follows:<br /><b>PhpGedView:</b> dir1/dir2/dir3/PhpGedView<br /><b>Index:</b> dir1/dir4/dir5/dir6/index<br /><br />For the example shown, you would enter <b>../../dir4/dir5/dir6/index/</b> in this field.');
	break;

case 'INDI_FACTS_ADD':
	$title=i18n::translate('Individual Add Facts');
	$text=i18n::translate('This is the list of GEDCOM facts that your users can add to individuals.  You can modify this list by removing or adding fact names, even custom ones, as necessary.  Fact names that appear in this list must not also appear in the <i>Unique Individual Facts</i> list.');
	break;

case 'INDI_FACTS_QUICK':
	$title=i18n::translate('Quick Individual Facts');
	$text=i18n::translate('This is the short list of GEDCOM individual facts that appears next to the full list and can be added with a single click.');
	break;

case 'INDI_FACTS_UNIQUE':
	$title=i18n::translate('Unique Individual Facts');
	$text=i18n::translate('This is the list of GEDCOM facts that your users can only add <u>once</u> to individuals.  For example, if BIRT is in this list, users will not be able to add more than one BIRT record to an individual.  Fact names that appear in this list must not also appear in the <i>Individual Add Facts</i> list.');
	break;

case 'JEWISH_ASHKENAZ_PRONUNCIATION':
	$title=i18n::translate('Jewish Ashkenaz pronunciation');
	$text=i18n::translate('Use Jewish Ashkenazi pronunciations.<br /><br />When set to <b>Yes</b> the months Cheshvan and Teves will be spelled with Ashkenazi pronunciation.  Setting it to <b>No</b> will change the months to Hesvan and Tevet.  This only affects the Jewish setting.  The Hebrew setting will always use the Hebrew alphabet.');
	break;

case 'LANGUAGE':
	$title=i18n::translate('Language');
	$text=i18n::translate('Assign the default language for the site.<br /><br />When the <b>Allow user to change language</b> option is set, users can override this setting through their browser\'s preferred language configuration, configuration options on their Account page, or through links or buttons on most PhpGedView pages.');
	break;

case 'LANG_SELECTION':
	$title=i18n::translate('Supported languages');
	$text=i18n::translate('You can change the list of languages supported by your PhpGedView site by adding or removing checkmarks as appropriate.  This changes the language choices available to your users.<br /><br />You can achieve the same thing through the <b>Configure supported languages</b> link on the Admin menu, where you can also change things such as the language\'s flag icon, the date format, or whether the surname should always be printed first.');
	break;

case 'LINK_ICONS':
	$title=i18n::translate('PopUp links on charts');
	$text=i18n::translate('Allows the user to select links to other charts and close relatives of the person.<br /><br />Set to <b>Disabled</b> to disable this feature.  Set to <b>On Mouse Over</b> to popup the links when the user mouses over the icon in the box.  Set to <b>On Mouse Click</b> to popup the links when the user clicks on the icon in the box.');
	break;

case 'LOGFILE_CREATE':
	$title=i18n::translate('Archive log files');
	$text=i18n::translate('How often should the program archive log files.');
	break;

case 'LOGIN_URL':
	$title=i18n::translate('Login URL');
	$text=i18n::translate('You only need to enter a Login URL if you want to redirect to a different site or location when your users login.  This is very useful if you need to switch from http to https when your users login.  Include the full URL to <i>login.php</i>.  For example, https://www.yourserver.com/phpgedview/login.php .');
	break;

case 'MAX_ALIVE_AGE':
	$title=i18n::translate('Age at which to assume a person is dead');
	$text=i18n::translate('If this person has any events other than Death, Burial, or Cremation more recent than this number of years, he is considered to be "alive".  Children\'s birth dates are considered to be such events for this purpose.');
	break;

case 'MAX_DESCENDANCY_GENERATIONS':
	$title=i18n::translate('Maximum Descendancy generations');
	$text=i18n::translate('Set the maximum number of generations to display on Descendancy charts.');
	break;

case 'MAX_PEDIGREE_GENERATIONS':
	$title=i18n::translate('Maximum Pedigree generations');
	$text=i18n::translate('Set the maximum number of generations to display on Pedigree charts.');
	break;

case 'MAX_RELATION_PATH_LENGTH':
	$title=i18n::translate('Max. relation path length');
	$text=i18n::translate('If the <i>Use relationship privacy</i> option is enabled, logged-in users will only be able to see or edit individuals within this number of relationship steps.<br /><br />This option sets the default for all users who have access to this genealogical database.  The Administrator can override this option for individual users by editing the user\'s account details.');
	break;

case 'MAX_VIEW_RATE':
	$title=i18n::translate('Maximum page view rate');
	$text=i18n::translate('This option limits the rate at which a user can view pages.<br /><br />If that rate is exceeded, PhpGedView treats the session as a hacking attempt;  the session will be terminated with a suitable message.  These two values should place a reasonable limit on the amount of bandwith and downloaded bytes from the server.  This feature can be switched off by setting the time interval to 0.');
	break;

case 'MEDIA_DIRECTORY_LEVELS':
	$title=i18n::translate('Multi-Media directory levels to keep');
	$text=i18n::translate('A value of 0 will ignore all directories in the file path for the media object.  A value of 1 will retain the first directory containing this image.  Increasing the numbers increases number of parent directories to retain in the path.<br /><br />For example, if you link an image in your GEDCOM with a path like <b>C:\Documents&nbsp;and&nbsp;Settings\User\My&nbsp;Documents\My&nbsp;Pictures\Genealogy\Surname&nbsp;Line\grandpa.jpg</b>, a value of 0 will translate this path to <b>./media/grandpa.jpg</b>.  A value of 1 will translate this to <b>./media/Surname&nbsp;Line/grandpa.jpg</b>, etc.  Most people will only need to use a 0.  However, it is possible that some media objects kept in different directories have identical names and would overwrite each other when this option is set to 0.  Non-zero settings allow you to keep some organization in your media thereby preventing name collisions.');
	break;

case 'MEDIA_DIRECTORY':
	$title=i18n::translate('MultiMedia directory');
	$text=i18n::translate('The path to a readable and writable directory where PhpGedView should store media files (include the trailing "/").  PhpGedView does not require this directory\'s name to be "media".  You can choose any name you like.<br /><br />Even though the Media Firewall feature lets you store media files in an area of the server\'s file space that is not accessible from the Internet, the directory named here must still exist and must be readable from the Internet and writable by PhpGedView.  For more information, please refer to the Media Firewall configuration options in the Multimedia section of the GEDCOM configuration page.');
	break;

case 'MEDIA_EXTERNAL':
	$title=i18n::translate('Keep links');
	$text=i18n::translate('When a multimedia link is found starting with for example http://, ftp://, mms:// it will not be altered when set to <b>Yes</b>. For example, http://www.myfamily.com/photo/dad.jpg will stay http://www.myfamily.com/photo/dad.jpg.  When set to <b>No</b>, the link will be handled as a standard reference and the media depth will be used.  For example: http://www.myfamily.com/photo/dad.jpg will be changed to ./media/dad.jpg');
	break;

case 'MEDIA_FIREWALL_ROOTDIR':
	$title=i18n::translate('Media Firewall Root Directory');
	$text=i18n::translate('Directory in which the protected Media directory can be created.  When this field is empty, the <b>#GLOBALS[INDEX_DIRECTORY]#</b> directory will be used.');
	break;

case 'MEDIA_FIREWALL_THUMBS':
	$title=i18n::translate('Protect Thumbnails of Protected Images');
	$text=i18n::translate('When an image is in the protected Media directory, should its thumbnail be protected as well?');
	break;

case 'MEDIA_ID_PREFIX':
	$title=i18n::translate('Media ID prefix');
	$text=i18n::translate('When a new media record is added online in PhpGedView, a new ID for that media will be generated automatically. The media ID will have this prefix.');
	break;

case 'META_AUDIENCE':
	$title=i18n::translate('Audience META tag');
	$text=i18n::translate('The value to place in the Audience meta tag in the HTML page header.');
	break;

case 'META_AUTHOR':
	$title=i18n::translate('Author META tag');
	$text=i18n::translate('The value to place in the Author meta tag in the HTML page header.  Leave this field empty to use the full name of the Genealogy contact.');
	break;

case 'META_COPYRIGHT':
	$title=i18n::translate('Copyright META tag');
	$text=i18n::translate('The value to place in the Copyright meta tag in the HTML page header.  Leave this field empty to use the full name of the Genealogy contact.');
	break;

case 'META_DESCRIPTION':
	$title=i18n::translate('Description META tag');
	$text=i18n::translate('The value to place in the Description meta tag in the HTML page header.  Leave this field empty to use the title of the currently active database.');
	break;

case 'META_KEYWORDS':
	$title=i18n::translate('Keywords META tag');
	$text=i18n::translate('The value to place in the Keywords meta tag in the HTML page header.  Some search engines will use the Keywords meta tag to help index your page.<br /><br />The Most Common Surnames list that appears in the GEDCOM Statistics block on your Welcome page can also be added to anything you enter here.');
	break;

case 'META_PAGE_TOPIC':
	$title=i18n::translate('Page-topic META tag');
	$text=i18n::translate('The value to place in the Page-topic meta tag in the HTML page header.  Leave this field empty to use the title of the currently active database.');
	break;

case 'META_PAGE_TYPE':
	$title=i18n::translate('Page-type META tag');
	$text=i18n::translate('The value to place in the Page-type meta tag in the HTML page header.');
	break;

case 'META_PUBLISHER':
	$title=i18n::translate('Publisher META tag');
	$text=i18n::translate('The value to place in the Publisher meta tag in the HTML page header.  Leave this field empty to use the full name of the Genealogy contact.');
	break;

case 'META_REVISIT':
	$title=i18n::translate('How often should crawlers revisit META tag');
	$text=i18n::translate('The value to place in the Revisit meta tag in the HTML page header.  Some web crawlers ignore this value.');
	break;

case 'META_ROBOTS':
	$title=i18n::translate('Robots META tag');
	$text=i18n::translate('The value to place in the Robots meta tag in the HTML page header.  Some robots or web crawlers ignore this value.');
	break;

case 'META_TITLE':
	$title=i18n::translate('Add to TITLE header tag');
	$text=i18n::translate('Anything on this line will be added to the TITLE tag in the HTML page header after the regular page title and before the PhpGedView credit.');
	break;

case 'MULTI_MEDIA':
	$title=i18n::translate('Enable multimedia features');
	$text=i18n::translate('GEDCOM 5.5.1 allows you to link pictures, videos, and other multimedia objects into your GEDCOM.  If you do not include multimedia objects in your GEDCOM, you can disable the multimedia features by setting this value to <b>No</b>.<br /><br />See the Multimedia section in the <a href="readme.txt">readme.txt</a> file for more information about including media in your site.');
	break;

case 'PAGE_AFTER_LOGIN':
	$title=i18n::translate('Page to show after Login');
	$text=i18n::translate('Which page should users see after they have logged in?<br /><br />The choice made here determines whether a successful Login causes the Welcome or the MyGedView page to appear when the login is done from the Welcome page.<br /><br />A Login done from the link at the top of every other page will return the user to that page.');
	break;

case 'PEDIGREE_FULL_DETAILS':
	$title=i18n::translate('Show Birth and Death details on charts');
	$text=i18n::translate('This option controls whether or not to show the Birth and Death details of an individual on charts.');
	break;

case 'PEDIGREE_GENERATIONS':
	$title=i18n::translate('PEDIGREE_GENERATIONS');
	$text=i18n::translate('Here you can set the number of generations to display on this page.<br /><br />The right number for you depends of the size of your screen and whether you show details or not.  Processing time will increase as you increase the number of generations.');
	break;

case 'PEDIGREE_LAYOUT':
	$title=i18n::translate('Default Pedigree chart layout');
	$text=i18n::translate('This option indicates whether the Pedigree chart should be generated in landscape or portrait mode.');
	break;

case 'PEDIGREE_ROOT_ID':
	$title=i18n::translate('Default person for Pedigree and Descendancy charts');
	$text=i18n::translate('Set the ID of the default person to display on Pedigree and Descendancy charts.');
	break;

case 'PEDIGREE_SHOW_GENDER':
	$title=i18n::translate('Show gender icon on charts');
	$text=i18n::translate('This option controls whether or not to show the individual\'s gender icon on charts.<br /><br />Since the gender is also indicated by the color of the box, this option doesn\'t conceal the gender. The option simply removes some duplicate information from the box.');
	break;

case 'PGV_MEMORY_LIMIT':
	$title=i18n::translate('Memory limit');
	$text=i18n::translate('The maximum amount of memory that can be consumed by PhpGedView scripts.  The default is 32 Mb.  Many hosts disable this option in their PHP configuration; changing this value may not actually affect the current maximum memory setting.');
	break;

case 'PGV_SESSION_SAVE_PATH':
	$title=i18n::translate('Session save path');
	$text=i18n::translate('The path to store PhpGedView session files.<br /><br />Some hosts do not have PHP configured properly and sessions are not maintained between page requests.  This option lets site administrators overcome that problem by saving files in one of their local directories.  The ./index/ directory is a good choice if you need to change this.  The default is to leave the field empty, which will use the Save path as configured in <i>php.ini</i>.');
	break;

case 'PGV_SESSION_TIME':
	$title=i18n::translate('Session timeout');
	$text=i18n::translate('The time in seconds that a PhpGedView session remains active before requiring a login.  The default is 7200, which is 2 hours.');
	break;

case 'PGV_SIMPLE_MAIL':
	$title=i18n::translate('Use simple mail headers in external mails');
	$text=i18n::translate('In normal mail headers for external mails, the email address as well as the name are used. Some mail systems will not accept this. When set to <b>Yes</b>, only the email address will be used.');
	break;

case 'PGV_SMTP_ACTIVE':
	$title=i18n::translate('Use SMTP to send external mails');
	$text=i18n::translate('Use SMTP to send e-mails from PhpGedView.<br /><br />This option requires access to an SMTP mail server.  When set to <b>No</b> PhpGedView will use the e-mail system built into PHP on this server.');
	break;

case 'PGV_SMTP_AUTH_PASS':
	$title=i18n::translate('Password');
	$text=i18n::translate('The password required for authentication with the SMTP server.');
	break;

case 'PGV_SMTP_AUTH_USER':
	$title=i18n::translate('User name');
	$text=i18n::translate('The user name required for authentication with the SMTP server.');
	break;

case 'PGV_SMTP_AUTH':
	$title=i18n::translate('Use name and password');
	$text=i18n::translate('Use name and password authentication to connect to the SMTP server.<br /><br />Some SMTP servers require all connections to be authenticated before they will accept outbound e-mails.');
	break;

case 'PGV_SMTP_FROM_NAME':
	$title=i18n::translate('Sender name');
	$text=i18n::translate('Enter the name to be used in the &laquo;From:&raquo; field of e-mails originating at this site.<br /><br />For example, if your name is <b>John Smith</b> and you are the site administrator for a site that is  known as <b>Jones Genealogy</b>, you could enter something like <b>John Smith</b> or <b>Jones Genealogy</b> or even <b>John Smith, Administrator: Jones Genealogy</b>.  You may enter whatever you wish, but HTML is not permitted.');
	break;

case 'PGV_SMTP_HELO':
	$title=i18n::translate('Sending domain name');
	$text=i18n::translate('This is the domain part of a valid e-mail address on the SMTP server.<br /><br />For example, if you have an e-mail account such as <b>yourname@abc.xyz.com</b>, you would enter <b>abc.xyz.com</b> here.');
	break;

case 'PGV_SMTP_HOST':
	$title=i18n::translate('Outgoing server (SMTP) name');
	$text=i18n::translate('This is the name of the SMTP mail server.  Example: <b>smtp.foo.bar.com</b>.<br /><br />Configuration values for some e-mail providers:<br /><br /><b>Gmail<br /></b><br /><b>Outgoing server (SMTP) name:</b> smtp.gmail.com<br /><b>SMTP Port:</b> 465 or 587<br /><b>Secure connection:</b> SSL<br /><br /><b>Hotmail<br /></b><br /><b>Outgoing server (SMTP) name:</b> smtp.live.com<br /><b>SMTP Port:</b> 25 or 587<br /><b>Secure connection:</b> TLS<br /><br /><b>Yahoo Mail Plus (currently a paid service)<br /></b><br /><b>Outgoing server (SMTP) name:</b> smtp.mail.yahoo.com<br /><b>SMTP Port:</b> 25');
	break;

case 'PGV_SMTP_PORT':
	$title=i18n::translate('SMTP Port');
	$text=i18n::translate('The port number to be used for connections to the SMTP server.  Generally, this is port <b>25</b>.');
	break;

case 'PGV_SMTP_SSL':
	$title=i18n::translate('Secure connection');
	$text=i18n::translate('Transport Layer Security (TLS) and Secure Sockets Layer (SSL) are Internet data encryption protocols.<br /><br />TLS 1.0, 1.1 and 1.2 are standardized developments of SSL 3.0. TLS 1.0 and SSL 3.1 are equivalent. Further work on SSL is now done under the new name, TLS.<br /><br />If your SMTP Server requires the SSL protocol during login, you should select the <b>SSL</b> option. If your SMTP Server requires the TLS protocol during login, you should select the <b>TLS</b> option.');
	break;

case 'PGV_STORE_MESSAGES':
	$title=i18n::translate('Allow messages to be stored online');
	$text=i18n::translate('Specifies whether messages sent through PhpGedView can be stored in the database.  If set to <b>Yes</b> users will be able to retrieve their messages when they login to PhpGedView.  If set to <b>No</b> messages will only be emailed.');
	break;

case 'PHPGEDVIEW_EMAIL':
	$title=i18n::translate('PhpGedView reply address');
	$text=i18n::translate('E-mail address to be used in the &laquo;From:&raquo; field of e-mails that PhpGedView creates automatically.<br /><br />PhpGedView can automatically create e-mails to notify administrators of changes that need to be reviewed.  PhpGedView also sends notification e-mails to users who have requested an account.<br /><br />Usually, the &laquo;From:&raquo; field of these automatically created e-mails is something like <i>From: phpgedview-noreply@yoursite</i> to show that no response to the e-mail is required.  To guard against spam or other e-mail abuse, some e-mail systems require each message\'s &laquo;From:&raquo; field to reflect a valid e-mail account and will not accept messages that are apparently from account <i>phpgedview-noreply</i>.');
	break;

case 'POSTAL_CODE':
	$title=i18n::translate('Postal Code position');
	$text=i18n::translate('Different countries use different ways to write the address. This option will enable you to place the postal code either before or after the city name.');
	break;

case 'PREFER_LEVEL2_SOURCES':
	$title=i18n::translate('Source type');
	$text=i18n::translate('When adding new close relatives, you can add source citations to the records (e.g. INDI, FAM) or the facts (BIRT, MARR, DEAT).  This option controls which checkboxes are ticked by default.');
	break;

case 'PRIVACY_BY_RESN':
	$title=i18n::translate('Use GEDCOM (RESN) Privacy restriction');
	$text=i18n::translate('The GEDCOM 5.5.1 specification includes the option of using RESN tags to set Privacy options for people and facts in the GEDCOM file.  Enabling this option will tell the program to look for level 1 RESN tags in GEDCOM records.  Level 2+ RESN tags are automatically applied and will not be affected by this setting.  Note that this might slow down some of the functions of PhpGedView such as the Individual list.');
	break;

case 'PRIVACY_BY_YEAR':
	$title=i18n::translate('Limit Privacy by age of event');
	$text=i18n::translate('The <b>Limit Privacy by age of event</b> setting will hide the details of people based on how old they were at specific events regardless of whether they are dead or alive.<br /><br />Use this setting along with the <b>Age at which to assume a person is dead</b> setting.  For example, if you made the Age setting 100 and set this option to <b>Yes</b>, all persons, alive or dead, born less than 100 years ago would be set to private.  People who were married less than 85 years ago and people who died less than 75 years ago would also be marked as private.  Please note that using this option will slow down your performance somewhat.');
	break;

case 'QUICK_REQUIRED_FACTS':
	$title=i18n::translate('Facts to always show on Quick Update');
	$text=i18n::translate('This is a comma separated list of GEDCOM fact tags that will always be shown on the Quick Update form whether or not they already exist in the individual\'s record.  For example, if BIRT is in the list, fields for birth date and birth place will always be shown on the form.');
	break;

case 'QUICK_REQUIRED_FAMFACTS':
	$title=i18n::translate('Facts for families to always show on Quick Update');
	$text=i18n::translate('This is a comma separated list of GEDCOM fact tags that will always be shown on the Family tabs of the Quick Update form whether or not they already exist in the family\'s record.  For example, if MARR is in the list, then fields for marriage date and marriage place will always be shown on the form.');
	break;

case 'REPO_FACTS_ADD':
	$title=i18n::translate('Repository Add Facts');
	$text=i18n::translate('This is the list of GEDCOM facts that your users can add to repositories.  You can modify this list by removing or adding fact names, even custom ones, as necessary.  Fact names that appear in this list must not also appear in the <i>Unique Repository Facts</i> list.');
	break;

case 'REPO_FACTS_QUICK':
	$title=i18n::translate('Quick Repository Facts');
	$text=i18n::translate('This is the short list of GEDCOM repository facts that appears next to the full list and can be added with a single click.');
	break;

case 'REPO_FACTS_UNIQUE':
	$title=i18n::translate('Unique Repository Facts');
	$text=i18n::translate('This is the list of GEDCOM facts that your users can only add <u>once</u> to repositories.  For example, if NAME is in this list, users will not be able to add more than one NAME record to a repository.  Fact names that appear in this list must not also appear in the <i>Repository Add Facts</i> list.');
	break;

case 'REPO_ID_PREFIX':
	$title=i18n::translate('Repository ID prefix');
	$text=i18n::translate('When a new repository record is added online in PhpGedView, a new ID for that repository will be generated automatically. The repository ID will have this prefix.');
	break;

case 'REQUIRE_ADMIN_AUTH_REGISTRATION':
	$title=i18n::translate('Require an administrator to approve new user registrations');
	$text=i18n::translate('If the option <b>Allow visitors to request account registration</b> is enabled this setting controls whether the admin must approve the registration.<br /><br />Setting this to <b>Yes</b> will require that all new users first verify themselves and then be approved by an admin before they can login.  With this setting on <b>No</b>, the <b>User approved by Admin</b> checkbox will be checked automatically when users verify their account, thus allowing an immediate login afterwards without admin intervention.');
	break;

case 'REQUIRE_AUTHENTICATION':
	$title=i18n::translate('Require visitor authentication');
	$text=i18n::translate('Enabling this option will force all visitors to login before they can view any data on the site.');
	break;

case 'RSS_FORMAT':
	$title=i18n::translate('RSS Format');
	$text=i18n::translate('The format to be used as the default feed format for the site. The numeric suffixes <u>do not</u> indicate version: they identify formats.  For example, RSS 2.0 is not newer than RSS 1.0, but a different format. Feed readers should be able to read any format.<br /><br />This <a href=\'http://en.wikipedia.org/wiki/RSS\' target=\'_blank\'title=\'Wikipedia article\'><b>Wikipedia article</b></a> contains comprehensive information and links about RSS and the various RSS formats.');
	break;

case 'SAVE_WATERMARK_IMAGE':
	$title=i18n::translate('Store watermarked full size images on server?');
	$text=i18n::translate('If the Media Firewall is enabled, should copies of watermarked full size images be stored on the server in addition to the same images without watermarks?<br /><br />When set to <b>Yes</b>, full-sized watermarked images will be produced more quickly at the expense of higher server disk space requirements.');
	break;

case 'SAVE_WATERMARK_THUMB':
	$title=i18n::translate('Store watermarked thumbnails on server?');
	$text=i18n::translate('If the Media Firewall is enabled, should copies of watermarked thumbnails be stored on the server in addition to the same thumbnails without watermarks?<br /><br />When set to <b>Yes</b>, media lists containing watermarked thumbnails will be produced more quickly at the expense of higher server disk space requirements.');
	break;

case 'SEARCHLOG_CREATE':
	$title=i18n::translate('Archive SearchLog files');
	$text=i18n::translate('How often should the program archive Searchlog files.');
	break;

case 'SECURITY_CHECK_GEDCOM_DOWNLOADABLE':
	$title=i18n::translate('Check if GEDCOM files are downloadable');
	$text=i18n::translate('For security reasons, GEDCOM files should not be in a location where they can be directly downloaded, thus bypassing privacy checks. Clicking this link will check if your GEDCOM files can be downloaded over the network.<br /><br />On some systems this check has been known to take a really long time or not even complete.  If that is the case for you, then you should try to point your browser directly at your GEDCOM to see if it can be downloaded.');
	break;

case 'SERVER_URL':
	$title=i18n::translate('PhpGedView URL');
	$text=i18n::translate('If you use https or a port other than the default, you will need to enter the URL to access your server here.');
	break;

case 'SHOW_AGE_DIFF':
	$title=i18n::translate('Show Date Differences');
	$text=i18n::translate('This option controls whether or not the Close Relatives tab should show differences between birth dates of spouses, between marriage date and birth date of first child, and between birth dates of children.');
	break;

case 'SHOW_CONTEXT_HELP':
	$title=i18n::translate('Show contextual <b>?</b> Help links');
	$text=i18n::translate('This option will enable links, identified by question marks, next to items on many pages.  These links allow users to get information or help about those items.');
	break;

case 'SHOW_COUNTER':
	$title=i18n::translate('Show hit counters');
	$text=i18n::translate('Show hit counters on Portal and Individual pages.');
	break;

case 'SHOW_DEAD_PEOPLE':
	$title=i18n::translate('Show dead people');
	$text=i18n::translate('Set the privacy access level for all dead people.');
	break;

case 'SHOW_EMPTY_BOXES':
	$title=i18n::translate('Show empty boxes on Pedigree charts');
	$text=i18n::translate('This option controls whether or not to show empty boxes on Pedigree charts.');
	break;

case 'SHOW_EST_LIST_DATES':
	$title=i18n::translate('Show estimated dates for birth and death');
	$text=i18n::translate('This option controls whether or not to show estimated dates for birth and death instead of leaving blanks on individual lists and charts for individuals whose dates are not known.');
	break;

case 'SHOW_FACT_ICONS':
	$title=i18n::translate('Show Fact icons');
	$text=i18n::translate('Set this to <b>Yes</b> to display icons near Fact names on the Personal Facts and Details page.  Fact icons will be displayed only if they exist in the <i>images/facts</i> directory of the current theme.');
	break;

case 'SHOW_GEDCOM_RECORD':
	$title=i18n::translate('Allow users to see raw GEDCOM records');
	$text=i18n::translate('Setting this to <b>Yes</b> will place links on individuals, sources, and families to let users bring up another window containing the raw data taken right out of the GEDCOM file.');
	break;

case 'SHOW_HIGHLIGHT_IMAGES':
	$title=i18n::translate('Show highlight images in people boxes');
	$text=i18n::translate('If you have enabled multimedia in your site, you can have PhpGedView display a thumbnail image next to the person\'s name in charts and boxes.<br /><br />Currently, PhpGedView uses the first multimedia object listed in the GEDCOM record as the highlight image.  For people with multiple images, you should arrange the multimedia objects such that the one you wish to be highlighted appears first, before any others.<br /><br />See the Multimedia section in the <a href="readme.txt">readme.txt</a> file for more information about including media in your site.');
	break;

case 'SHOW_ID_NUMBERS':
	$title=i18n::translate('Show ID numbers next to names');
	$text=i18n::translate('This option controls whether or not to show ID numbers in parentheses after names on charts and lists.');
	break;

case 'SHOW_LAST_CHANGE':
	$title=i18n::translate('Show GEDCOM record last change date on lists');
	$text=i18n::translate('This option controls whether or not to show GEDCOM record last change date on lists.');
	break;

case 'SHOW_LDS_AT_GLANCE':
	$title=i18n::translate('Show LDS ordinance codes in chart boxes');
	$text=i18n::translate('Setting this option to <b>Yes</b> will show status codes for LDS ordinances in chart boxes.<ul><li><b>B</b> - Baptism</li><li><b>E</b> - Endowed</li><li><b>S</b> - Sealed to spouse</li><li><b>P</b> - Sealed to parents</li></ul>A person who has all of the ordinances done will have <b>BESP</b> printed after their name.  Missing ordinances are indicated by <b>_</b> in place of the corresponding letter code.  For example, <b>BE__</b> indicates missing <b>S</b> and <b>P</b> ordinances.');
	break;

case 'SHOW_LEVEL2_NOTES':
	$title=i18n::translate('Show all Notes and Source references on Notes and Sources tabs');
	$text=i18n::translate('This option controls whether Notes and Source references that are attached to Facts should be shown on the Notes and Sources tabs of the Individual page.<br /><br />Ordinarily, the Notes and Sources tabs show only Notes and Source references that are attached directly to the individual\'s database record.  These are <i>level 1</i> Notes and Source references.<br /><br />The <b>Yes</b> option causes these tabs to also show Notes and Source references that are part of the various Facts in the individual\'s database record.  These are <i>level 2</i> Notes and Source references because the various Facts are at level 1.');
	break;

case 'SHOW_LIST_PLACES':
	$title=i18n::translate('Place levels to show on lists');
	$text=i18n::translate('This determines how much of the Place information is shown in the Place fields on lists.<br /><br />Setting the value to <b>9</b> will ensure that all Place information will be shown.  Setting the value to <b>0</b> (zero) will hide places completely.  Setting the value to <b>1</b> will show the topmost level, which is normally the country.  Setting it to <b>2</b> will show the topmost two levels.  The second topmost level, below the country, is often the state, province, or territory. Etc.');
	break;

case 'SHOW_LIVING_NAMES':
	$title=i18n::translate('Show living names');
	$text=i18n::translate('Should the names of living people be shown to the public?');
	break;

case 'SHOW_MARRIED_NAMES':
	$title=i18n::translate('Show married names on Individual list');
	$text=i18n::translate('This option will show the married names of females on the Individual list.  This option requires that you calculate the married names when you import the GEDCOM file.');
	break;

case 'SHOW_MEDIA_DOWNLOAD':
	$title=i18n::translate('Show download link in Media Viewer');
	$text=i18n::translate('The Media Viewer can show a link which, when clicked, will download the Media file to the local PC.<br /><br />You may want to hide the download link for security reasons.');
	break;

case 'SHOW_MEDIA_FILENAME':
	$title=i18n::translate('Show file name in Media Viewer');
	$text=i18n::translate('The Media Viewer can show the name of the Media file being viewed.  This option determines whether that file name is shown to users or not.<br /><br />You may want to hide the file name for security reasons.');
	break;

case 'SHOW_MULTISITE_SEARCH':
	$title=i18n::translate('Show Multi-Site Search');
	$text=i18n::translate('Multi-site search allows users to search across multiple PhpGedView websites which you have setup in the Manage Sites administration area or remotely linked to.  This option controls whether the Multi-site Search feature is available to everyone or only to authenticated users.');
	break;

case 'SHOW_NO_WATERMARK':
	$title=i18n::translate('Who can view non-watermarked images?');
	$text=i18n::translate('If the Media Firewall is enabled, users will see watermarks if they do not have the privilege level specified here.');
	break;

case 'SHOW_PARENTS_AGE':
	$title=i18n::translate('Show age of parents next to child\'s birthdate');
	$text=i18n::translate('This option controls whether or not to show age of father and mother next to child\'s birthdate on charts.');
	break;

case 'SHOW_PEDIGREE_PLACES':
	$title=i18n::translate('Place levels to show in person boxes');
	$text=i18n::translate('This sets how much of the place information is shown in the person boxes on charts.<br /><br />Setting the value to 9 will guarantee to show all place levels.  Setting the value to 0 will hide places completely.  Setting the value to 1 will show the first level, setting it to 2 will show the first two levels, etc.');
	break;

case 'SHOW_PRIVATE_RELATIONSHIPS':
	$title=i18n::translate('Show private relationships');
	$text=i18n::translate('This option will retain family links in privatized records.  This means that you will see empty "private" boxes on the pedigree chart and on other charts with private people.<br /><br />This is similar to the behavior of PhpGedView versions prior to v4.0.<br /><br />This setting is off by default.  It is recommended instead of turning this on, to point your pedigree root person in your GEDCOM configuration to a person who is not private.');
	break;

case 'SHOW_REGISTER_CAUTION':
	$title=i18n::translate('Show Acceptable Use agreement on «Request new user account» page');
	$text=i18n::translate('When set to <b>Yes</b>, the following message will appear above the input fields on the «Request new user account» page:<div class="list_value_wrap"><div class="largeError">Notice:</div><div class="error">By completing and submitting this form, you agree:<ul><li>to protect the privacy of living people listed on our site;</li><li>and in the text box below, to explain to whom you are related, or to provide us with information on someone who should be listed on our site.</li></ul></div></div>');
	break;

case 'SHOW_RELATIVES_EVENTS':
	$title=i18n::translate('Show events of close relatives on Individual page');
	$text=i18n::translate('Births, marriages, and deaths of relatives are important events in one\'s life. This option controls whether or not to show these events on the <i>Personal facts and details</i> tab on the Individual page.<br /><br />The events affected by this option are:<ul><li>Death of spouse</li><li>Birth and death of children</li><li>Death of parents</li><li>Birth and death of siblings</li><li>Death of grand-parents</li><li>Birth and death of parents\' siblings</li></ul>');
	break;

case 'SHOW_RESEARCH_ASSISTANT':
	$title=i18n::translate('Show Research Assistant');
	$text=i18n::translate('What type of user can view the Research Assistant module if it is installed?');
	break;

case 'SHOW_SOURCES':
	$title=i18n::translate('Show sources');
	$text=i18n::translate('Set the privacy access level for all Sources.  If the user does not have access to Sources, the Source list will be removed from the Lists menu and the Sources tab will not be shown on the Individual Details page.');
	break;

case 'SHOW_SPIDER_TAGLINE':
	$title=i18n::translate('Show spider tagline');
	$text=i18n::translate('On pages generated for search engines, display as the last line the particular search engine the page detected.  If this option is on, it can bias Google&reg; AdSense towards search engine optimization tools.');
	break;

case 'SHOW_STATS':
	$title=i18n::translate('Show execution statistics');
	$text=i18n::translate('Show runtime statistics and database queries at the bottom of every page.');
	break;

case 'SOURCE_ID_PREFIX':
	$title=i18n::translate('Source ID prefix');
	$text=i18n::translate('When a new source record is added online in PhpGedView, a new ID for that source will be generated automatically.  The source ID will have this prefix.');
	break;

case 'SOUR_FACTS_ADD':
	$title=i18n::translate('Source Add Facts');
	$text=i18n::translate('This is the list of GEDCOM facts that your users can add to sources.  You can modify this list by removing or adding fact names, even custom ones, as necessary.  Fact names that appear in this list must not also appear in the <i>Unique Source Facts</i> list.');
	break;

case 'SOUR_FACTS_QUICK':
	$title=i18n::translate('Quick Source Facts');
	$text=i18n::translate('This is the short list of GEDCOM source facts that appears next to the full list and can be added with a single click.');
	break;

case 'SOUR_FACTS_UNIQUE':
	$title=i18n::translate('Unique Source Facts');
	$text=i18n::translate('This is the list of GEDCOM facts that your users can only add <u>once</u> to sources.  For example, if TITL is in this list, users will not be able to add more than one TITL record to a source.  Fact names that appear in this list must not also appear in the <i>Source Add Facts</i> list.');
	break;

case 'SPLIT_PLACES':
	$title=i18n::translate('Split places in Edit mode');
	$text=i18n::translate('Set this to <b>Yes</b> to split each place name by commas into subfields for easier editing.  Example :<br /><ol><li>Default mode<br /><u>Place</u>: Half Moon Bay, San Mateo, California, USA<br /><br /></li><li>Split mode<br /><u>Country</u>: USA<br /><u>State</u>: California<br/><u>County</u>: San Mateo<br/><u>City</u>: Half Moon Bay</li></ol>');
	break;

case 'SUBLIST_TRIGGER_F':
	$title=i18n::translate('Maximum number of family names');
	$text=i18n::translate('Long lists of families with the same name can be broken into smaller sub-lists according to the first letter of the given name.<br /><br />This option determines when sub-listing of family names will occur.  To disable sub-listing completely, set this option to zero.');
	break;

case 'SUBLIST_TRIGGER_I':
	$title=i18n::translate('Maximum number of surnames');
	$text=i18n::translate('Long lists of persons with the same surname can be broken into smaller sub-lists according to the first letter of the individual\'s given name.<br /><br />This option determines when sub-listing of surnames will occur.  To disable sub-listing completely, set this option to zero.');
	break;

case 'SUBM':	
	// I18N: This is the GEDCOM label for "Submitter"
	$title=i18n::translate('SUBM');
	$text='';
	break;

case 'SUBN':	
	// I18N: This is the GEDCOM label for "Submission"
	$title=i18n::translate('SUBN');
	$text='';
	break;

case 'SUPPORT_METHOD':
	$title=i18n::translate('Support method');
	$text=i18n::translate('The method to be used to contact the Support contact about technical questions.<ul><li>The <b>Mailto link</b> option will create a "mailto" link that can be clicked to send an email using the mail client on the user\'s PC.</li><li>The <b>PhpGedView internal messaging</b> option will use a messaging system internal to PhpGedView, and no emails will be sent.</li><li>The <b>Internal messaging with emails</b> option is the default.  It will use the PhpGedView messaging system and will also send copies of the messages via email.</li><li>The <b>PhpGedView sends emails with no storage</b> option allows PhpGedView to handle the messaging and will send the messages as emails, but will not store the messages internally.  This option is similar to the <b>Mailto link</b> option, except that the message will be sent by PhpGedView instead of the user\'s workstation.</li><li>The <b>No contact method</b> option results in your users having no way of contacting you.</li></ul>');
	break;

case 'SURNAME_LIST_STYLE':
	$title=i18n::translate('Surname list style');
	$text=i18n::translate('Lists of surnames, as they appear in the Top 10 Surnames block, the Individuals, and the Families, can be shown in different styles.<ul><li><b>Table</b>&nbsp;&nbsp;&nbsp;In this style, the surnames are shown in a table that can be sorted either by surname or by count.</li><li><b>Tagcloud</b>&nbsp;&nbsp;&nbsp;In this style, the surnames are shown in a list, and the font size used for each name depends on the number of occurrences of that name in the database.  The list is not sortable.</li></ul>');
	break;

case 'SURNAME_TRADITION':
	$title=i18n::translate('Surname tradition');
	$text=i18n::translate('When you add new members to a family, PhpGedView can supply default values for surnames according to regional custom.<br /><br /><ul><li>In the <b>Paternal</b> tradition, all family members share the father\'s surname.</li><li>In the <b>Spanish</b> and <b>Portuguese</b> tradition, children receive a surname from each parent.</li><li>In the <b>Icelandic</b> tradition, children receive their male parent\'s given name as a surname, with a suffix that denotes gender.</li><li>In the <b>Polish</b> tradition, all family members share the father\'s surname. For some surnames, the suffix indicates gender.  The suffixes <i>ski</i>, <i>cki</i>, and <i>dzki</i> indicate male, while the corresponding suffixes <i>ska</i>, <i>cka</i>, and <i>dzka</i> indicate female.</li></ul>');
	break;

case 'SYNC_GEDCOM_FILE':
	$title=i18n::translate('Synchronize edits into GEDCOM file');
	$text=i18n::translate('In past versions of PGV the pending edits were stored in the GEDCOM file and the changed records were then "accepted" into the database.  Starting with v4.1 pending changes are no longer stored in the GEDCOM file but in the changes file.  <br /><br />Setting this value to true will update the GEDCOM file when changes are accepted into the database.  This will keep the GEDCOM file synchronized with the database.  For greater compatibility with previous versions the default value of this field is on.<br /><br />You may want to turn it off to conserve memory when accepting changes.');
	break;

case 'TBLPREFIX':
	$title=i18n::translate('Database Table Prefix');
	$text=i18n::translate('A prefix to prepend to the tables created by PhpGedView.  By changing this value you can set up multiple PhpGedView sites to use the same physical database but different tables.  For example, the same site could host a "test" as well as a "production" installation of PhpGedView with completely independent data tables.');
	break;

case 'THEME_DIR':
	$title=i18n::translate('Theme directory');
	$text=i18n::translate('The directory where your PhpGedView theme files are kept.<br /><br />You may customize any of the standard themes that come with PhpGedView to give your site a unique look and feel.  See the Theme Customization section of the <a href="readme.txt">readme.txt</a> file for more information.');
	break;

case 'THUMBNAIL_WIDTH':
	$title=i18n::translate('Width of generated thumbnails');
	$text=i18n::translate('This is the width (in pixels) that the program will use when automatically generating thumbnails.  The default setting is 100.');
	break;

case 'TIME_LIMIT':
	$title=i18n::translate('PHP time limit');
	$text=i18n::translate('The maximum time in seconds that PhpGedView should be allowed to run.<br /><br />The default is 1 minute.  Depending on the size of your GEDCOM file, you may need to increase this time limit when you need to build the indexes.  Set this value to 0 to allow PHP to run forever.<br /><br />CAUTION: Setting this to 0 or setting it too high could cause your site to hang on certain operating systems until the script finishes.  Setting it to 0 means it may never finish until a server administrator kills the process or restarts the server.  A large Pedigree chart can take a very long time to run; leaving this value as low as possible ensures that someone cannot crash your server by requesting an excessively large chart.');
	break;

case 'UNDERLINE_NAME_QUOTES':
	$title=i18n::translate('Underline names in quotes');
	$text=i18n::translate('Many programs will place the preferred given name in "quotes" in the GEDCOM.  The usual convention for this is to underline the preferred given name.  Enabling this option will convert any names surrounded by quotes to &lt;span&gt; with a CSS class of "starredname".<br /><br />For example, if the name in the GEDCOM were 1&nbsp;NAME&nbsp;Gustave&nbsp;"Jean&nbsp;Paul"&nbsp;Charles&nbsp;/Wilson/ enabling this option would change the part of the name enclosed in quotes to &lt;span&nbsp;class="starredname"&gt;Jean&nbsp;Paul&lt;/span&gt; for printing purposes.  Depending on other settings, the browser would then display that name as <b>Gustave&nbsp;<u>Jean&nbsp;Paul</u>&nbsp;Charles&nbsp;Wilson</b> or <b>Wilson,&nbsp;Gustave&nbsp;<u>Jean&nbsp;Paul</u> Charles</b>');
	break;

case 'USE_GEONAMES':
	$title=i18n::translate('Use GeoNames database');
	$text=i18n::translate('Should the GeoNames database be used to provide more suggestions for place names?<br /><br />When this option is set to <b>Yes</b>, the GeoNames database will be queried to supply suggestions for the place name being entered.  When set to <b>No</b>, only the current genealogical database will be searched.  As you enter more of the place name, the suggestion will become more precise.  This option can slow down data entry, particularly if your Internet connection is slow.<br /><br />The GeoNames geographical database is accessible free of charge. It currently contains over 8,000,000 geographical names.');
	break;

case 'USE_MEDIA_FIREWALL':
	$title=i18n::translate('Use Media Firewall');
	$text=i18n::translate('See the Wiki for a description of how to use the Media Firewall. <a href="#PGV_PHPGEDVIEW_WIKI#/en/index.php?title=Media_Firewall" target="_blank">#PGV_PHPGEDVIEW_WIKI#</a>');
	break;

case 'USE_MEDIA_VIEWER':
	$title=i18n::translate('Use Media Viewer');
	$text=i18n::translate('When this option is <b>Yes</b>, clicking on images will produce the Media Viewer page.  This page shows the details of the image.  If you have sufficient rights, you can also edit these details.<br /><br />When this option is <b>No</b>, clicking on images will produce a full-size image in a new window.');
	break;

case 'USE_REGISTRATION_MODULE':
	$title=i18n::translate('Allow visitors to request account registration');
	$text=i18n::translate('Gives visitors the option of registering themselves for an account on the site.<br /><br />The visitor will receive an email message with a code to verify his application for an account.  After verification, the Administrator will have to approve the registration before it becomes active.');
	break;

case 'USE_RELATIONSHIP_PRIVACY':
	$title=i18n::translate('Use relationship privacy');
	$text=i18n::translate('<b>No</b> means that authenticated users can see the details of all living people.  <b>Yes</b> means that users can only see the private information of living people they are related to.<br /><br />This option sets the default for all users who have access to this genealogical database.  The Administrator can override this option for individual users by editing the user\'s account details.');
	break;

case 'USE_RIN':
	$title=i18n::translate('Use RIN number instead of GEDCOM ID');
	$text=i18n::translate('Set to <b>Yes</b> to use the RIN number instead of the GEDCOM ID when asked for Individual IDs in configuration files, user settings, and charts.  This is useful for genealogy programs that do not consistently export GEDCOMs with the same ID assigned to each individual but always use the same RIN.');
	break;

case 'USE_SILHOUETTE':
	$title=i18n::translate('Use silhouettes');
	$text=i18n::translate('Use silhouette images when no highlighted image for that person has been specified.  The images used are specific to the gender of the person in question.<br /><br /><table><tr><td wrap valign="middle">This image might be used when the gender of the person is unknown: </td><td><img src="images/silhouette_unknown.gif" width="40" alt="Silhouette image" title="Silhouette image" /></td></tr></table>');
	break;

case 'USE_THUMBS_MAIN':
	$title=i18n::translate('Use thumbnail');
	$text=i18n::translate('This option determines whether PhpGedView should send the large or the small image to the browser whenever a chart or the Personal Details page requires a thumbnail.<br /><br />The <b>No</b> choice will cause PhpGedView to send the large image, while the <b>Yes</b> choice will cause the small image to be sent.  Each individual image also has the &laquo;Always use main image?&raquo; option which, when set to <b>Yes</b>, will cause the large image to be sent regardless of the setting of the &laquo;Use thumbnail&raquo; option in the GEDCOM configuration.  You cannot force PhpGedView to send small images when the GEDCOM configuration specifies that large images should always be used.<br /><br />PhpGedView does not re-size the image being sent; the browser does this according to the page specifications it has also received.  This can have undesirable consequences when the image being sent is not truly a thumbnail where PhpGedView is expecting to send a small image.  This is not an error:  There are occasions where it may be desirable to display a large image in places where one would normally expect to see a thumbnail-sized picture.<br /><br />You should avoid setting the &laquo;Use thumbnail&raquo; option to <b>No</b>.  This choice will cause excessive amounts of image-related data to be sent to the browser, only to have the browser discard the excess.  Page loads, particularly of charts with many images, can be seriously slowed.');
	break;

case 'WATERMARK_THUMB':
	$title=i18n::translate('Add watermarks to thumbnails?');
	$text=i18n::translate('If the Media Firewall is enabled, should thumbnails be watermarked? Your media lists will load faster if you don\'t watermark the thumbnails.');
	break;

case 'WEBMASTER_EMAIL':
	$title=i18n::translate('Support contact');
	$text=i18n::translate('The person to be contacted about technical questions or errors encountered on your site.');
	break;

case 'WELCOME_TEXT_AUTH_MODE_CUST_HEAD':
	$title=i18n::translate('Standard header for custom Welcome text');
	$text=i18n::translate('Choose to display a standard header for your custom Welcome text.  When your users change language, this header will appear in the new language.<br /><br />If set to <b>Yes</b>, the header will look look this:<div class="list_value_wrap"><center><b>Welcome to this Genealogy website</b></center><br />Access is permitted to users who have an account and a password for this website.<br /></div>');
	break;

case 'WELCOME_TEXT_AUTH_MODE_CUST':
	$title=i18n::translate('Custom Welcome text');
	$text=i18n::translate('If you have opted for custom Welcome text, you can type that text here.  The text will NOT be translated into the language of the visitor, but will be shown exactly as you typed it.  However, if your custom text contains references to language variables that you can define in the various <i>languages/extra.xx.php</i> files, your site can show translated text.<br /><br />You can insert HTML tags into your custom Welcome text.<br /><br />The following description, taken from the Help text for the FAQ list, is equally applicable to the custom Welcome text.<br /><br />HTML entities are a very easy way to add special characters to your FAQ titles and text.  You can use symbolic names, decimal numbers, or hexadecimal numbers.  A complete list of HTML entities, their coding, and their representation by your browser can be found here:  <a href="http://htmlhelp.com/reference/html40/entities/" target="_blank">HTML entity lists</a><br /><br />On occasion, you may need to show a Tilde character&nbsp;&nbsp;<b>&#x7E;</b>&nbsp;&nbsp;or a Number Sign&nbsp;&nbsp;<b>&#x23;</b>&nbsp;&nbsp;in your URLs or text.  These characters have a special meaning to the PhpGedView Help system and can only be entered in their hexadecimal or decimal form.  Similarly, the&nbsp;&nbsp;<b>&lt;</b>&nbsp;&nbsp;and&nbsp;&nbsp;<b>&gt;</b>&nbsp;&nbsp;characters that usually enclose HTML tags must be entered in their hexadecimal or decimal forms if they are to be treated as normal text instead of signalling an HTML tag.<ul><li><b>&amp;&#x23;35;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x23;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x23;</b></li><li><b>&amp;&#x23;60;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x3C;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x3C;</b></li><li><b>&amp;&#x23;62;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x3E;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x3E;</b></li><li><b>&amp;&#x23;126;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x7E;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x7E;</b></li></ul>There is a&nbsp;&nbsp;<b>&amp;tilde;</b>&nbsp;&nbsp;HTML entity, but this symbol is not interpreted as a Tilde when coded in URLs.<br /><br />You can insert references to entries in the language files or to values of global variables.  Examples: <ul><li><b>&#x23;pgv_lang[add_to_cart]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the language variable $pgv_lang["add_to_cart"], and if it were to appear in this field, would show as <b>Add to Clippings Cart</b> when the FAQ list is viewed in the current language.</li><li><b>&#x23;factarray[AFN]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the Fact name $factarray["AFN"], and if it were to appear in this field, would show as <b>Ancestral File Number (AFN)</b> when the FAQ list is viewed in the current language. </li><li><b>&#x23;PGV_VERSION&#x23;&nbsp;&#x23;PGV_VERSION_RELEASE&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the constant PGV_VERSION, a space, and a reference to the constant PGV_VERSION_RELEASE, and if they were to appear in this field, would show as <b>#PGV_VERSION#&nbsp;#PGV_VERSION_RELEASE#</b> when the FAQ list is viewed in the current language.</li><li><b>&#x23;GLOBALS[GEDCOM]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the global variable $GEDCOM, which is the name of the current GEDCOM file.  If it were to appear in this field, it would show as <b>#GLOBALS[GEDCOM]#</b>.</li><li><b>&#x23;GLOBALS[GEDCOM_TITLE]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the global variable $GEDCOM_TITLE, which is the title of the current GEDCOM file.  If it were to appear in this field, it would show as <b>#GLOBALS[GEDCOM_TITLE]#</b>.</li></ul><br />This feature is useful when you wish to create FAQ lists that are different for each language your site supports.  You should put your customized FAQ list titles and entries into the <i>languages/extra.xx.php</i> files (<i>xx</i> is the code for each language), using the following format:<br />$pgv_lang["faq_title1"] = "This is a sample FAQ title";<br />$pgv_lang["faq_body1"] = "This is a sample FAQ body.";');
	break;

case 'WELCOME_TEXT_AUTH_MODE':
	$title=i18n::translate('Welcome text on Login page');
	$text=i18n::translate('Here you can choose text to appear on the login screen. You must determine which predefined text is most appropriate.<br /><br />You can also choose to enter your own custom Welcome text, but the text you enter will not be translated when your users change language.  However, if your custom text contains references to language variables that you can define in the various <i>languages/extra.xx.php</i> files, your site can show translated text.  Please refer to the Help text associated with the <b>Custom Welcome text</b> field for more information.<br /><br />The predefined texts are:<ul><li><b>Predefined text that states all users can request a user account:</b><div class="list_value_wrap"><center><b>Welcome to this Genealogy website</b></center><br />Access to this site is permitted to every visitor who has a user account.<br /><br />If you have a user account, you can login on this page.  If you don\'t have a user account, you can apply for one by clicking on the appropriate link below.<br /><br />After verifying your application, the site administrator will activate your account.  You will receive an email when your application has been approved.</div><br/></li><li><b>Predefined text that states admin will decide on each request for a user account:</b><div class="list_value_wrap"><center><b>Welcome to this Genealogy website</b></center><br />Access to this site is permitted to <u>authorized</u> users only.<br /><br />If you have a user account you can login on this page.  If you don\'t have a user account, you can apply for one by clicking on the appropriate link below.<br /><br />After verifying your information, the administrator will either approve or decline your account application.  You will receive an email message when your application has been approved.</div><br/></li><li><b>Predefined text that states only family members can request a user account:</b><div class="list_value_wrap"><center><b>Welcome to this Genealogy website</b></center><br />Access to this site is permitted to <u>family members only</u>.<br /><br />If you have a user account you can login on this page.  If you don\'t have a user account, you can apply for one by clicking on the appropriate link below.<br /><br />After verifying the information you provide, the administrator will either approve or decline your request for an account.  You will receive an email when your request is approved.</div></li></ul>');
	break;

case 'WORD_WRAPPED_NOTES':
	$title=i18n::translate('Add spaces where notes were wrapped');
	$text=i18n::translate('Some genealogy programs wrap notes at word boundaries while others wrap notes anywhere.  This can cause PhpGedView to run words together.  Setting this to <b>Yes</b> will add a space between words where they are wrapped in the original GEDCOM.');
	break;

case 'ZOOM_BOXES':
	$title=i18n::translate('Zoom boxes on charts');
	$text=i18n::translate('Allows a user to zoom boxes on charts to get more information.<br /><br />Set to <b>Disabled</b> to disable this feature.  Set to <b>On Mouse Over</b> to zoom boxes when the user mouses over the icon in the box.  Set to <b>On Mouse Click</b> to zoom boxes when the user clicks on the icon in the box.');
	break;

case 'accesskey_viewing_advice':
	$title=i18n::translate('0');
	$text=i18n::translate('Keyboard shortcuts are intended to help users who have difficulty with pointing devices such as a mouse.  Shortcut use differs according to your browser:<ul><li>Internet Explorer 5+: Hold down the ALT key while you strike the desired character, and then press ENTER.</li><li>Firefox 2+: Hold down the SHIFT and ALT key while you strike the desired character.  Do not press ENTER afterwards.</li><li>Firefox 1, 1.5 and Netscape 6+: Hold down the ALT key while you strike the desired character.  Do not press ENTER afterwards.</li><li>Opera: Hold down the SHIFT and ESC keys while you strike the desired character.  Do not press ENTER afterwards.</li><li>Internet Explorer 5+ for Mac / Safari 1.2+: Hold down the CTRL key while you strike the desired character.  Do not press ENTER afterwards.</li></ul><br />The following keyboard shortcuts are available: <ul><li>All Pages <ul><li><b>zero</b> - Information about keyboard shortcuts</li><li><b>one</b> - Welcome page</li><li><b>2</b> - Help Contents</li><li><b>3</b> - Help with this page</li><li><b>4</b> - Contact </li><li><b>C</b> - Skip to Content</li><li><b>S</b> - Search </li></ul></li><li>Individual Page<ul><li><b>I</b> - Show Personal Facts and Details tab</li><li><b>N</b> - Show Notes tab</li><li><b>O</b> - Show Sources tab</li><li><b>A</b> - Show Media tab</li><li><b>R</b> - Show Close Relatives tab</li><li><b>L</b> - Show Research Assistant tab</li><li><b>P</b> - Show individual\'s Pedigree Chart</li><li><b>D</b> - Show individual\'s Descendancy Chart</li><li><b>T</b> - Show individual\'s Timeline Chart</li><li><b>M</b> - Show individual\'s Relationship to me chart</li><li><b>G</b> - View GEDCOM Record</li></ul></li><li>Family Pages <ul><li><b>P</b> - Show couple on timeline chart tab</li><li><b>D</b> - Show children on timeline chart tab</li><li><b>T</b> - Show family on timeline chart tab</li><li><b>G</b> - View GEDCOM Record</li></ul></li></ul>');
	break;

case 'active':
	$title=i18n::translate('Active');
	$text=i18n::translate('Allow users to select this language if the option <b>Allow user to change language</b> is enabled.');
	break;

case 'add_by_id':
	$title=i18n::translate('add_by_id');
	$text=i18n::translate('This input box lets you enter an individual\'s ID number so he can be added to the Clippings Cart.  Once added you\'ll be offered options to link that individual\'s relations to your Clippings Cart.<br /><br />If you do not know an individual\'s ID number, you can perform a search by name by pressing the Person icon next to the Add button.');
	break;

case 'add_child':
	$title=i18n::translate('Add child');
	$text=i18n::translate('You can add a child to this family by clicking this link.<br /><br />Adding a child is simple: Just click the link, fill out the boxes in the pop up screen, and that\'s all.');
	break;

case 'add_custom_facts':
	$title=i18n::translate('add_custom_facts');
	$text=i18n::translate('If you can\'t find the fact that you want to add in the list of GEDCOM facts, you can enter a <b>custom fact</b> as well.<br /><br />Entering a custom fact is just as simple as entering one of the pre-defined ones.  The only difference is that you have to name the fact instead of picking its name from a list. You have to do this in the top field: <b>Type</b>');
	break;

case 'add_facts_general':
	$title=i18n::translate('add_facts_general');
	$text=i18n::translate('When you have added a fact, note, source, or multimedia file to a record in the database, the addition still has to be approved by a user who has Accept rights.<br /><br />Until the changes have been Accepted, they are identified as "pending" by a differently colored border.  All users with Edit rights can see these changes as well as the original information.  Users who do not have Edit rights will only see the original information. When the addition has been Accepted, the borders will disappear and the new data will display normally, replacing the old.  At that time, users without Edit rights will see the new data too.');
	break;

case 'add_facts':
	$title=i18n::translate('add_facts');
	$text=i18n::translate('Here you can add a fact to the record being edited.<br /><br />First choose a fact from the drop-down list, then click the <b>Add</b> button.  All possible facts that you can add to the database are in that drop-down list.');
	break;

case 'add_fam_clip':
	$title=i18n::translate('add_fam_clip');
	$text=i18n::translate('You can add all or some of this family\'s information to your Clippings Cart. On the next page you can choose precisely how much information you wish to add:<ol><li>Add just this family record.</li><li>Add parents\' records together with this family record.</li><li>Add parents\' and children\'s records together with this family record.</li><li>Add parents\' and all descendants\' records together with this family record.</li></ol>');
	break;

case 'add_faq_body':
	$title=i18n::translate('FAQ Body');
	$text=i18n::translate('The text of the FAQ item is entered here.<br /><br />The text can be formatted. HTML tags such as &lt;b&gt; and &lt;br /&gt; are allowed, as are HTML entities such as &amp;amp; and &amp;nbsp;.<br /><br />HTML entities are a very easy way to add special characters to your FAQ titles and text.  You can use symbolic names, decimal numbers, or hexadecimal numbers.  A complete list of HTML entities, their coding, and their representation by your browser can be found here:  <a href="http://htmlhelp.com/reference/html40/entities/" target="_blank">HTML entity lists</a><br /><br />On occasion, you may need to show a Tilde character&nbsp;&nbsp;<b>&#x7E;</b>&nbsp;&nbsp;or a Number Sign&nbsp;&nbsp;<b>&#x23;</b>&nbsp;&nbsp;in your URLs or text.  These characters have a special meaning to the PhpGedView Help system and can only be entered in their hexadecimal or decimal form.  Similarly, the&nbsp;&nbsp;<b>&lt;</b>&nbsp;&nbsp;and&nbsp;&nbsp;<b>&gt;</b>&nbsp;&nbsp;characters that usually enclose HTML tags must be entered in their hexadecimal or decimal forms if they are to be treated as normal text instead of signalling an HTML tag.<ul><li><b>&amp;&#x23;35;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x23;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x23;</b></li><li><b>&amp;&#x23;60;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x3C;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x3C;</b></li><li><b>&amp;&#x23;62;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x3E;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x3E;</b></li><li><b>&amp;&#x23;126;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x7E;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x7E;</b></li></ul>There is a&nbsp;&nbsp;<b>&amp;tilde;</b>&nbsp;&nbsp;HTML entity, but this symbol is not interpreted as a Tilde when coded in URLs.<br /><br />You can insert references to entries in the language files or to values of global variables.  Examples: <ul><li><b>&#x23;pgv_lang[add_to_cart]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the language variable $pgv_lang["add_to_cart"], and if it were to appear in this field, would show as <b>Add to Clippings Cart</b> when the FAQ list is viewed in the current language.</li><li><b>&#x23;factarray[AFN]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the Fact name $factarray["AFN"], and if it were to appear in this field, would show as <b>Ancestral File Number (AFN)</b> when the FAQ list is viewed in the current language. </li><li><b>&#x23;PGV_VERSION&#x23;&nbsp;&#x23;PGV_VERSION_RELEASE&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the constant PGV_VERSION, a space, and a reference to the constant PGV_VERSION_RELEASE, and if they were to appear in this field, would show as <b>#PGV_VERSION#&nbsp;#PGV_VERSION_RELEASE#</b> when the FAQ list is viewed in the current language.</li><li><b>&#x23;GLOBALS[GEDCOM]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the global variable $GEDCOM, which is the name of the current GEDCOM file.  If it were to appear in this field, it would show as <b>#GLOBALS[GEDCOM]#</b>.</li><li><b>&#x23;GLOBALS[GEDCOM_TITLE]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the global variable $GEDCOM_TITLE, which is the title of the current GEDCOM file.  If it were to appear in this field, it would show as <b>#GLOBALS[GEDCOM_TITLE]#</b>.</li></ul><br />This feature is useful when you wish to create FAQ lists that are different for each language your site supports.  You should put your customized FAQ list titles and entries into the <i>languages/extra.xx.php</i> files (<i>xx</i> is the code for each language), using the following format:<br />$pgv_lang["faq_title1"] = "This is a sample FAQ title";<br />$pgv_lang["faq_body1"] = "This is a sample FAQ body.";');
	break;

case 'add_faq_header':
	$title=i18n::translate('FAQ Header');
	$text=i18n::translate('This is the title or subject of the FAQ item.<br /><br />What you enter here can be formatted. HTML tags such as &lt;b&gt; and &lt;br /&gt; are allowed, as are HTML entities such as &amp;amp; and &amp;nbsp;.  HTML tags other than &lt;br /&gt; are probably not very useful in the FAQ title and should be avoided.<br /><br />HTML entities are a very easy way to add special characters to your FAQ titles and text.  You can use symbolic names, decimal numbers, or hexadecimal numbers.  A complete list of HTML entities, their coding, and their representation by your browser can be found here:  <a href="http://htmlhelp.com/reference/html40/entities/" target="_blank">HTML entity lists</a><br /><br />On occasion, you may need to show a Tilde character&nbsp;&nbsp;<b>&#x7E;</b>&nbsp;&nbsp;or a Number Sign&nbsp;&nbsp;<b>&#x23;</b>&nbsp;&nbsp;in your URLs or text.  These characters have a special meaning to the PhpGedView Help system and can only be entered in their hexadecimal or decimal form.  Similarly, the&nbsp;&nbsp;<b>&lt;</b>&nbsp;&nbsp;and&nbsp;&nbsp;<b>&gt;</b>&nbsp;&nbsp;characters that usually enclose HTML tags must be entered in their hexadecimal or decimal forms if they are to be treated as normal text instead of signalling an HTML tag.<ul><li><b>&amp;&#x23;35;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x23;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x23;</b></li><li><b>&amp;&#x23;60;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x3C;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x3C;</b></li><li><b>&amp;&#x23;62;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x3E;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x3E;</b></li><li><b>&amp;&#x23;126;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x7E;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x7E;</b></li></ul>There is a&nbsp;&nbsp;<b>&amp;tilde;</b>&nbsp;&nbsp;HTML entity, but this symbol is not interpreted as a Tilde when coded in URLs.<br /><br />You can insert references to entries in the language files or to values of global variables.  Examples: <ul><li><b>&#x23;pgv_lang[add_to_cart]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the language variable $pgv_lang["add_to_cart"], and if it were to appear in this field, would show as <b>Add to Clippings Cart</b> when the FAQ list is viewed in the current language.</li><li><b>&#x23;factarray[AFN]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the Fact name $factarray["AFN"], and if it were to appear in this field, would show as <b>Ancestral File Number (AFN)</b> when the FAQ list is viewed in the current language. </li><li><b>&#x23;PGV_VERSION&#x23;&nbsp;&#x23;PGV_VERSION_RELEASE&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the constant PGV_VERSION, a space, and a reference to the constant PGV_VERSION_RELEASE, and if they were to appear in this field, would show as <b>#PGV_VERSION#&nbsp;#PGV_VERSION_RELEASE#</b> when the FAQ list is viewed in the current language.</li><li><b>&#x23;GLOBALS[GEDCOM]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the global variable $GEDCOM, which is the name of the current GEDCOM file.  If it were to appear in this field, it would show as <b>#GLOBALS[GEDCOM]#</b>.</li><li><b>&#x23;GLOBALS[GEDCOM_TITLE]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the global variable $GEDCOM_TITLE, which is the title of the current GEDCOM file.  If it were to appear in this field, it would show as <b>#GLOBALS[GEDCOM_TITLE]#</b>.</li></ul><br />This feature is useful when you wish to create FAQ lists that are different for each language your site supports.  You should put your customized FAQ list titles and entries into the <i>languages/extra.xx.php</i> files (<i>xx</i> is the code for each language), using the following format:<br />$pgv_lang["faq_title1"] = "This is a sample FAQ title";<br />$pgv_lang["faq_body1"] = "This is a sample FAQ body.";');
	break;

case 'add_faq_item':
	$title=i18n::translate('Add FAQ item');
	$text=i18n::translate('This option will let you add an item to the FAQ page.');
	break;

case 'add_faq_order':
	$title=i18n::translate('FAQ Position');
	$text=i18n::translate('This field controls the order in which the FAQ items are displayed.<br /><br />You do not have to enter the numbers sequentially.  If you leave holes in the numbering scheme, you can insert other items later.  For example, if you use the numbers 1, 6, 11, 16, you can later insert items with the missing sequence numbers.  Negative numbers and zero are allowed, and can be used to insert items in front of the first one.<br /><br />When more than one FAQ item has the same position number, only one of these items will be visible.');
	break;

case 'add_faq_visibility':
	$title=i18n::translate('FAQ Visibility');
	$text=i18n::translate('You can determine whether this FAQ will be visible regardless of GEDCOM, or whether it will be visible only to the current GEDCOM.<br /><ul><li><b>ALL</b>&nbsp;&nbsp;&nbsp;The FAQ will appear in all FAQ lists, regardless of GEDCOM.</li><li><b>#GLOBALS[GEDCOM]#</b>&nbsp;&nbsp;&nbsp;The FAQ will appear only in the currently active GEDCOM\'s FAQ list.</li></ul>');
	break;

case 'add_from_clipboard':
	$title=i18n::translate('Add from clipboard');
	$text=i18n::translate('PhpGedView allows you to copy up to 10 facts, with all their details, to a clipboard.  This clipboard is different from the Clippings Cart that you can use to export portions of your database.<br /><br />You can select any of the facts from the clipboard and copy the selected fact to the Individual, Family, Media, Source, or Repository record currently being edited.  However, you cannot copy facts of dissimilar record types.  For example, you cannot copy a Marriage fact to a Source or an Individual record since the Marriage fact is associated only with Family records.<br /><br />This is very helpful when entering similar facts, such as census facts, for many individuals or families.');
	break;

case 'add_gedcom':
	$title=i18n::translate('Add GEDCOM');
	$text=i18n::translate('When you use the <b>Add GEDCOM</b> function, it is assumed that you have already uploaded the GEDCOM file to your server using a program or method <u>external</u> to PhpGedView, for example, <i>ftp</i> or <i>network connection</i>.  The file you wish to add could also have been left over from a previous <b>Upload GEDCOM</b> procedure.<br /><br />If the input GEDCOM file is not yet on your server, you <u>have to</u> get it there first, before you can start with Adding.<br /><br />Instead of uploading a GEDCOM file, you can also upload a ZIP file containing the GEDCOM file, either with PhpGedView, or using an external program. PhpGedView will recognize the ZIP file automatically and will extract the GEDCOM file and filename from the ZIP file.<br /><br />If a GEDCOM file with the same name already exists in PhpGedView, it will be overwritten. However, all GEDCOM settings made previously will be preserved.<br /><br />You are guided step by step through the procedure.');
	break;

case 'add_husband':
	$title=i18n::translate('add_husband');
	$text=i18n::translate('By clicking this link, you can add a <u>new</u> male person and link this person to the principal individual as a new husband.<br /><br />Just click the link, and you will get a pop up window to add the new person.  Fill out as many boxes as you can and click the <b>Save</b> button.<br /><br />That\'s all.');
	break;

case 'add_media':
	$title=i18n::translate('Add a new Media item');
	$text=i18n::translate('Adding multimedia files (MM) to the GEDCOM is a very nice feature.  Although this program already has a great look without media, if you add pictures or other MM to your relatives, it will only get better.<br /><br /><b>What you should understand about MM.</b><br />There are many formats of MM. Although PhpGedView can handle most of them, there some things to consider.<br /><ul><li><b>Formats</b><br />Pictures can be edited and saved in many formats.  For example, .jpg, .png, .bmp, .gif, etc.  If the same original picture was used to create each of the formats, the viewed image will appear to be the same size no matter which format is used.  However, the image files stored in the database will vary considerably in size.  Generally, .jpg images are considered to the most efficient in terms of storage space.</li><li><b>Image size</b><br />The larger the original image, the larger will be the resultant file\'s size. The picture should fit on the screen without scrolling; the maximum width or height should not be more than the width or height of the screen. PhpGedView is designed for screens of 1024x768 pixels but not all of this space is available for viewing pictures; the picture\'s size should be set accordingly.  To reduce file sizes, smaller pictures are more desirable.</li><li><b>Resolution</b><br />The resolution of a picture is usually measured in "dpi" (dots/inch), but this is valid only for printed pictures.  When considering pictures shown on screen, the only correct way is to use total dots or pixels. When printed, the picture could have a resolution of 150 - 300 dpi or more depending on the printer. Screen resolutions are rarely better than 50 pixels per inch.  If your picture will never be printed, you can safely lower its resolution (and consequently its file size) without affecting picture quality.  If a low-resolution picture is printed with too great a magnification, its quality will suffer; it will have a grainy appearance.</li><li><b>Color depth</b><br />Another way to keep a file small is to decrease the number of colors that you use.  The number of colors can differ from pure black and white (two colors) to true colors (millions of colors) and anything in between.  You can see that the more colors are used, the bigger the size of the files.</li></ul><b>Why is it important to keep the file size small?</b><br /><ul><li>First of all: Our webspace is limited.  The more large files there are, the more web space we need on the server. The more space we need, the higher our costs.</li><li>Bandwidth.  The more data our server has to send to the remote location (your location), the more we have to pay.  This is because the carrying capacity of the server\'s connection to the Internet is limited, and the link has to be shared (and paid for) by all of the applications running on the server.  PhpGedView is one of many applications that share the server.  The cost is normally apportioned according to the amount of data each application sends and receives.</li><li>Download time. If you have large files, the user (also you) will have to wait long for the page to download from the server.  Not everybody is blessed with a cable connection, broadband or DSL.</li></ul><b>How to upload your MM</b><br />There are two ways to upload media to the site.  If you have a lot of media items to upload you should contact the site administrator to discuss the best ways.  If it has been enabled by your site administrator, you can use the Upload Media form under your MyGedView menu.  You can also use the Upload option on the Multimedia form to upload media items.');
	break;

case 'add_name':
	$title=i18n::translate('Add new Name');
	$text=i18n::translate('This link will allow you to add another name to this individual.  Sometimes people are known by other names or aliases.  This link allows you to add new names to a person without changing the old name.');
	break;

case 'add_new_gedcom':
	$title=i18n::translate('Create a new GEDCOM');
	$text=i18n::translate('You can start a new genealogical database from scratch.<br /><br />This procedure requires only a few simple steps. Step 1 is different from what you know already about uploading and adding. The other steps will be familiar.<ol><li><b>Naming the new GEDCOM</b><br />Type the name of the new GEDCOM <u>without</u> the extension <b>.ged</b>. The new file will be created in the directory named above the box where you enter the name.  Click <b>Add</b>.</li><li><b>Configuration page</b><br />You already know this page;  you configure the settings for your new GEDCOM file.</li><li><b>Validate</b><br />You already know this page;  the new GEDCOM is checked.  Since there is nothing in it, it will be ok.</li><li><b>Importing Records</b><br />Since there will be only one record to import, this will be finished very fast.</li></ol>That\'s it.  Now you can go to the Pedigree chart to see your first person in the new GEDCOM. Click the name of the person and start editing. After that, you can link new individuals to the first person.');
	break;

case 'add_new_language':
	$title=i18n::translate('Add files and settings for a new language');
	$text=i18n::translate('This routine lets you add a new, previously unavailable and completely unsupported language to PhpGedView.<br /><br />You can set up the standard settings of the new language.  These include language detection code, language direction, start of the week for this language, time settings, alphabet, etc.<br /><br />You will get more help on the settings of languages supported by PhpGedView after selecting the language and clicking the <b>Add new language</b> button.<br /><br />More help is available on the Configuration page for the language.');
	break;

case 'add_new_parent':
	$title=i18n::translate('add_new_parent');
	$text=i18n::translate('<center>--- This is a general help text for multiple pages ---</center><br />~ADD NEW FATHER or MOTHER~<br /><br />There are certainly many individuals in the GEDCOM without a record of a father or mother.<br /><br />In that case, on the <b>Individual Information</b> page, tab sheet <b>Close Relatives</b>, table <b>Family with Parents</b>, you will find links to add a <u>new</u> father or mother to the individual.<br /><br />Please keep in mind that these links are for adding a <u>new</u> father or mother.  If the father or mother already has a record in this database, you have to use the link <b>Link this person to an existing family as a child</b>, which you will find on that <b>Individual Information</b> page below the last table.');
	break;

case 'add_note':
	$title=i18n::translate('Add a new Note');
	$text=i18n::translate('<center>--- This is a general help text for multiple pages ---</center><br />~ADD NEW NOTE~<br />If you have a note to add to this record, this is the place to do so.<br /><br />Just click the link, a window will open, and you can type your note.  When you are finished typing, just click the button below the box, close the window, and that\'s all.<br /><br />~General info about adding~<br />When you have added a fact, note, source, or multimedia file to a record in the database, the addition still has to be approved by a user who has Accept rights.<br /><br />Until the changes have been Accepted, they are identified as "pending" by a differently colored border.  All users with Edit rights can see these changes as well as the original information.  Users who do not have Edit rights will only see the original information. When the addition has been Accepted, the borders will disappear and the new data will display normally, replacing the old.  At that time, users without Edit rights will see the new data too.');
	break;

case 'add_opf_child':
	$title=i18n::translate('Add a child to create a one-parent family');
	$text=i18n::translate('By clicking this link, you can add a <u>new</u> child to this person, creating a one-parent family.<br /><br />Just click the link, and you will get a pop up window to add the new person.  Fill out as many boxes as you can and click the <b>Save</b> button.<br /><br />That\'s all.');
	break;

case 'add_person':
	$title=i18n::translate('add_person');
	$text=i18n::translate('You can have several persons on the timeline.<br /><br />Use this box to supply each person\'s ID.  If you don\'t know the ID of the person, you can click the <b>Find ID</b> link next to the box.');
	break;

case 'add_repository_clip':
	$title=i18n::translate('add_repository_clip');
	$text=i18n::translate('When you click this link you can add the repository, as it is stored in the GEDCOM, to your Clippings Cart.');
	break;

case 'add_shared_note':
	$title=i18n::translate('Add a new Shared Note');
	$text=i18n::translate('<center>--- This is a general help text for multiple pages ---</center><br />~Add Shared Note~<br />When you click the <b>Add a new Shared Note</b> link, a new window will open.  You can choose to link to an existing shared note, or you can create a new shared note and at the same time create a link to it.<br /><br />~General info about adding~<br />When you have added a fact, note, source, or multimedia file to a record in the database, the addition still has to be approved by a user who has Accept rights.<br /><br />Until the changes have been Accepted, they are identified as "pending" by a differently colored border.  All users with Edit rights can see these changes as well as the original information.  Users who do not have Edit rights will only see the original information. When the addition has been Accepted, the borders will disappear and the new data will display normally, replacing the old.  At that time, users without Edit rights will see the new data too.');
	break;

case 'add_sibling':
	$title=i18n::translate('Add a brother or sister');
	$text=i18n::translate('You can add a child to this family when you click this link.  "This Family", in this case, is the father and mother of the principal person of this screen.<br /><br />Keep in mind that you are going to add a sibling of that person.  Adding a brother or sister is simple: Just click the link, fill out the boxes in the pop up screen and that\'s all.<br /><br />If you have to add a son or daughter of the principal person, scroll down a little and click the link in "Family with Spouse".');
	break;

case 'add_son_daughter':
	$title=i18n::translate('Add a son or daughter');
	$text=i18n::translate('You can add a child to this family when you click this link.  "This Family", in this case, is the principal person of this screen and his or her spouse.<br /><br />Keep in mind that you are going to add a son or daughter of that person.  Adding a son or daughter is simple: Just click the link, fill out the boxes in the popup screen and that\'s all.<br /><br />If you have to add a brother or sister of the principal person, scroll up a little and click the link in "Family with Parents".');
	break;

case 'add_source_clip':
	$title=i18n::translate('add_source_clip');
	$text=i18n::translate('When you click this link, you can add the source\'s information to your Clippings Cart for later downloading and importing into your own genealogy program.');
	break;

case 'add_source':
	$title=i18n::translate('Add a new Source Citation');
	$text=i18n::translate('<center>--- This is a general help text for multiple pages ---</center><br />~ADD NEW SOURCE CITATION~<br />Here you can add a source <b>Citation</b> to this record.<br /><br />Just click the link, a window will open, and you can choose the source from the list (Find ID) or create a new source and then add the Citation.<br /><br />Adding sources is an important part of genealogy because it allows other researchers to verify where you obtained your information.<br /><br />~General info about adding~<br />When you have added a fact, note, source, or multimedia file to a record in the database, the addition still has to be approved by a user who has Accept rights.<br /><br />Until the changes have been Accepted, they are identified as "pending" by a differently colored border.  All users with Edit rights can see these changes as well as the original information.  Users who do not have Edit rights will only see the original information. When the addition has been Accepted, the borders will disappear and the new data will display normally, replacing the old.  At that time, users without Edit rights will see the new data too.');
	break;

case 'add_upload_gedcom':
	$title=i18n::translate('add_upload_gedcom');
	$text=i18n::translate('<dl><dt><b>Uploading GEDCOM Files</b></dt><dd>Uploading files can be done on line.  You can upload from anywhere without needing an ftp program.</dd><dt><b>Adding GEDCOM Files</b></dt><dd>If a previously uploaded file is still present in your GEDCOM directory, you can use it again without uploading.  Sometimes, because of file or upload size limitations, you need to use Add.</dd></dl>The Add and the Upload procedure can be finished in four simple steps.  In either procedure, only Step 1 is different.');
	break;

case 'add_wife':
	$title=i18n::translate('Add wife');
	$text=i18n::translate('By clicking this link, you can add a <u>new</u> female person and link this person to the principal individual as a new wife.<br /><br />Just click the link, and you will get a pop up window to add the new person.  Fill out as many boxes as you can and click the <b>Save</b> button.<br /><br />That\'s all.');
	break;

case 'admin':
	$title=i18n::translate('Admin');
	$text=i18n::translate('On this page you will find links to the configuration pages, administration pages, documentation, and log files.<br /><br /><b>Current Server Time:</b>, just below the page title, shows the time of the server on which your site is hosted. This means that if the server is located in New York while you\'re in France, the time shown will be six hours less than your local time, unless, of course, the server is running on Greenwich Mean Time (GMT).  The time shown is the server time when you opened or refreshed this page.<br /><br /><b>WARNING</b><br />When you see a red warning message under the system time, it means that your <i>config.php</i> is still writeable.  After configuring your site, you should, for <b>security</b>, set the permissions of this file back to read-only.  You have to do this <u>manually</u>, since PhpGedView cannot do this for you.');
	break;

case 'admin_help_contents_head':
	$title=i18n::translate('admin_help_contents_head');
	$text=i18n::translate('<b>HELP CONTENTS<br /><br />ADMINISTRATOR HELP ITEMS</b> added to the beginning of the list.');
	break;

case 'age_differences':
	$title=i18n::translate('Show Date Differences');
	$text=i18n::translate('When this option box is checked, the «Close Relatives» tab will show date differences as follows:<br /><ul><li>birth dates of partners.<br />A negative value indicates that the second partner is older than the first.<br /><br /></li><li>marriage date and birth date of the first child.<br />A negative value here indicates that the child was born before the marriage date or that either the birth date or the marriage date is wrong.<br /><br /></li><li>birth dates of siblings.<br />A negative value here indicates that either the order of the children is wrong or that one of the birth dates is wrong.</li></ul>');
	break;

case 'ah10':
	$title=i18n::translate('ah10');
	$text=i18n::translate('_GEDCOM: Administration page');
	break;

case 'ah11':
	$title=i18n::translate('ah11');
	$text=i18n::translate('_GEDCOM: Configure');
	break;

case 'ah12':
	$title=i18n::translate('ah12');
	$text=i18n::translate('_GEDCOM: Import');
	break;

case 'ah13':
	$title=i18n::translate('ah13');
	$text=i18n::translate('_GEDCOM: Upload');
	break;

case 'ah14':
	$title=i18n::translate('ah14');
	$text=i18n::translate('_GEDCOM: Validate');
	break;

case 'ah15':
	$title=i18n::translate('ah15');
	$text=i18n::translate('_GEDCOM: Convert ANSI to UTF-8');
	break;

case 'ah16':
	$title=i18n::translate('ah16');
	$text=i18n::translate('_GEDCOM: Privacy settings');
	break;

case 'ah17':
	$title=i18n::translate('ah17');
	$text=i18n::translate('_User Administration');
	break;

case 'ah18':
	$title=i18n::translate('ah18');
	$text=i18n::translate('_Administration');
	break;

case 'ah19':
	$title=i18n::translate('ah19');
	$text=i18n::translate('_GEDCOM: Media tool');
	break;

case 'ah20':
	$title=i18n::translate('ah20');
	$text=i18n::translate('_GEDCOM: Change Individual ID to ...');
	break;

case 'ah21':
	$title=i18n::translate('ah21');
	$text=i18n::translate('_Translator tools');
	break;

case 'ah23':
	$title=i18n::translate('ah23');
	$text=i18n::translate('_Configure supported languages');
	break;

case 'ah24':
	$title=i18n::translate('ah24');
	$text=i18n::translate('_User Information migrate (Index --&gt;&gt; SQL)');
	break;

case 'ah25':
	$title=i18n::translate('ah25');
	$text=i18n::translate('_PhpGedView backup');
	break;

case 'ah26':
	$title=i18n::translate('ah26');
	$text=i18n::translate('_FAQ List: Edit');
	break;

case 'ah2':
	$title=i18n::translate('ah2');
	$text=i18n::translate('_Configure PhpGedView');
	break;

case 'ah3':
	$title=i18n::translate('ah3');
	$text=i18n::translate('_GEDCOM: Add vs Upload');
	break;

case 'ah4':
	$title=i18n::translate('ah4');
	$text=i18n::translate('_GEDCOM: Configuration file');
	break;

case 'ah5':
	$title=i18n::translate('ah5');
	$text=i18n::translate('_GEDCOM: Default');
	break;

case 'ah6':
	$title=i18n::translate('ah6');
	$text=i18n::translate('_GEDCOM: Delete');
	break;

case 'ah7':
	$title=i18n::translate('ah7');
	$text=i18n::translate('_GEDCOM: Add');
	break;

case 'ah8':
	$title=i18n::translate('ah8');
	$text=i18n::translate('_GEDCOM: Create new');
	break;

case 'ah9':
	$title=i18n::translate('ah9');
	$text=i18n::translate('_GEDCOM: Download');
	break;

case 'alpha':
	$title=i18n::translate('alpha');
	$text=i18n::translate('Clicking a letter in the Alphabetical index will display a list of the names that start with the letter you clicked.<br /><br />The second to last item in the Alphabetical index can be <b>(unknown)</b>.  This entry will be present when there are people in the database whose surname has not been recorded or does not contain any recognizable letters.  Unknown surnames are often recorded as <b>?</b>, and these will be recognized as <b>(unknown)</b>.  This will also happen if the person is unknown.<br /><br /><b>Note:</b><br />Surnames entered as, for example, <b>Nn</b>, <b>NN</b>, <b>Unknown</b>, or even <b>N.N.</b> will <u>not</u> be found in the <b>(unknown)</b> list. Instead, you will find these persons by clicking <b>N</b> or <b>U</b> because these are the initial letters of those names.  PhpGedView cannot possibly account for all possible ways of entering unknown surnames;  there is no recognized convention for this.<br /><br />At the end of the Alphabetical index you see <b>ALL</b>. When you click on this item, you will see a list of all surnames in the database.<br /><br /><b>Missing letters?</b><br />If your Alphabetical index appears to be incomplete, with missing letters, your database doesn\'t contain any surnames that start with that missing letter.');
	break;

case 'alphabet_lower':
	$title=i18n::translate('Alphabet lower case');
	$text=i18n::translate('Lower case alphabet letters in this language.  This alphabet is used while sorting lists of names.');
	break;

case 'alphabet_upper':
	$title=i18n::translate('Alphabet upper case');
	$text=i18n::translate('Upper case alphabet letters in this language.  This alphabet is used while sorting lists of names.');
	break;

case 'annivers_date_select':
	$title=i18n::translate('annivers_date_select');
	$text=i18n::translate('The top row of the Selector table is the <b>Day</b> selector.  Its meaning is obvious: You select a <u>day</u>.<br /><br />The result of clicking on a certain day depends of whether you are in <b>Day</b> or in <b>Month</b> mode.<br /><dl><dt><b>Day mode</b></dt><dd>In this mode, you click a day, the screen will refresh, and the list for that day will be displayed.</dd><dt><b>Month mode</b></dt><dd>You have the calendar of a certain month on the screen.  You click a day and the screen will refresh, but you will still see the month that you had on the screen before.  The reason for this is that you can still decide to select another month, year, or event before you either click the <b>View Day</b> or <b>View Month</b> button.<br /><br />At the end of the Day row you will see a <b>Quick Link</b> with today\'s date.  Clicking that <b>Quick Link</b> will display the list for today in <b>Day</b> mode, no matter whether you are in <b>Month</b> or in <b>Day</b> mode.</dd></dl>');
	break;

case 'annivers_event':
	$title=i18n::translate('annivers_event');
	$text=i18n::translate('Here you choose whether you want all events for individuals and families displayed or just a selected event.  You cannot select more than one event category.<br /><br />When you click on an option, the events of your choice will be displayed.<br /><br />The settings of day, month, and year, as well as <b>Day</b> or <b>Month</b> mode, remain as they were.');
	break;

case 'annivers_month_select':
	$title=i18n::translate('annivers_month_select');
	$text=i18n::translate('The middle row of the Selector table is the <b>Month</b> selector.  Its meaning is obvious: You select a <u>month</u>.<br /><br />The result of clicking on a certain month depends of whether you are in <b>Day</b> or in <b>Month</b> mode.<br /><dl><dt><b>Day mode</b></dt><dd>In this mode, you click a month, the screen will refresh, and the list for that month will be displayed.  All other selections like day, year, and events will be unchanged.</dd><dt><b>Month mode</b></dt><dd>When you have the calendar on the screen and click a month in the <b>Month</b> row, the calendar for that new month will be displayed.<br /><br />At the end of the Month row you will see a <b>Quick Link</b> with today\'s month and year.  Clicking that <b>Quick Link</b> will display the list for that month in <b>Month</b> mode, no matter whether you are in <b>Month</b> or in <b>Day</b> mode.</dd></dl>');
	break;

case 'annivers_sex':
	$title=i18n::translate('annivers_sex');
	$text=i18n::translate('When you are logged in or when the admin has not enabled the Privacy option, you can select one of these options:<ol><li><b>All</b> icon<br />This is the default option. The events of all individuals and families are displayed.</li><li><b>Male</b> icon<br />Only events of male individuals are displayed. Only male members of families will be displayed with Family events.</li><li><b>Female</b> icon<br />Only events of female individuals are displayed. Only female members of families will be displayed with Family events.</li></ol>When you click on an option, the events of your choice will be displayed.<br /><br />The settings of day, month, and year, as well as <b>Day</b> or <b>Month</b> mode, remain as they were.');
	break;

case 'annivers_show':
	$title=i18n::translate('annivers_show');
	$text=i18n::translate('The following options are available:<br /><ol><li><b>All People</b><br />With this option, all individuals and families are displayed.</li><li><b>Recent Years (&lt;100 yrs)</b><br />With this option you will see all events for the chosen day or month, but no events older than 100 years will be shown.</li><li><b>Living People</b><br />Unless the administrator has configured PhpGedView so that living people are visible to anyone, this option will only be available to you after you have logged in.<br /><br />With this option, only the events of living persons will be displayed.</li></ol>When you click on an option, the events of your choice will be displayed.<br /><br />The settings of day, month, and year, as well as <b>Day</b> or <b>Month</b> mode, remain as they were.');
	break;

case 'annivers_tip':
	$title=i18n::translate('annivers_tip');
	$text=i18n::translate('Adjust the Date selector to any date in the past.<br /><br />When you click on one of the View buttons you will see a list or calendar for that date.  All the ages, anniversaries, etc. have been recalculated and now count from the date you set in the Date selector.  You are now seeing the calendar or list that your ancestor would have seen on that date, years ago.');
	break;

case 'annivers_year_select':
	$title=i18n::translate('annivers_year_select');
	$text=i18n::translate('This input box lets you change that year of the calendar.  Type a year into the box and press <b>Enter</b> to change the calendar to that year.<br /><br /><b>Advanced features</b> for <b>View Year</b><dl><dt><b>More than one year</b></dt><dd>You can search for dates in a range of years.<br /><br />Year ranges are <u>inclusive</u>.  This means that the date range extends from 1 January of the first year of the range to 31 December of the last year mentioned.  Here are a few examples of year ranges:<br /><br /><b>1992-5</b> for all events from 1992 to 1995.<br /><b>1972-89</b> for all events from 1972 to 1989.<br /><b>1610-759</b> for all events from 1610 to 1759.<br /><b>1880-1905</b> for all events from 1880 to 1905.<br /><b>880-1105</b> for all events from 880 to 1105.<br /><br />To see all the events in a given decade or century, you can use <b>?</b> in place of the final digits. For example, <b>197?</b> for all events from 1970 to 1979 or <b>16??</b> for all events from 1600 to 1699.<br /><br/>Selecting a range of years will change the calendar to the year view.</dd></dl>');
	break;

case 'apply_privacy':
	$title=i18n::translate('Apply privacy settings?');
	$text=i18n::translate('When this option is checked, the output file will pass through privacy checks according to the selected option.  This can result in the removal of certain information.  The output file will contain only the information that is normally visible to a user with the indicated privilege level.<br /><br />If you only have GEDCOM administrator rights, you cannot specify that the output file should be privatized according to the Site administrator privilege level.');
	break;

case 'autoContinue':
	$title=i18n::translate('Automatically press «Continue» button');
	$text=i18n::translate('When PhpGedView detects that the GEDCOM Import requires more time than is permitted by the time limit, it will display a <b>Continue</b> button that you must press to continue the Import.<br /><br />When this option is set to <b>Yes</b>, PhpGedView will automatically press the <b>Continue</b> button for you.  This should relieve the tedium of having to press the button repeatedly for lengthy Imports.');
	break;

case 'basic_or_all':
	$title=i18n::translate('Show only Births, Deaths, and Marriages?');
	$text=i18n::translate('This option lets you eliminate some dated events.  For example, Divorce, Cremation, Graduation, Bar Mitzvah, First Communion, etc. should all be dated.<br /><br />When you select <b>Yes</b>, only Births, Deaths, and Marriages will be shown. When you select <b>No</b>, all dated events will be shown.');
	break;

case 'best_display':
	$title=i18n::translate('best_display');
	$text=i18n::translate('PhpGedView is designed for a screen size of 1024x768 pixels.  This should be the minimum size to have everything displayed properly.<br /><br />If you set the size to a lower value (for example 800x600), you may need to do horizontal scrolling on some pages.');
	break;

case 'block_move_right':
	$title=i18n::translate('block_move_right');
	$text=i18n::translate('Use these buttons to move an entry from one list to another.<br /><br />Highlight the entry to be moved, and then click a button to move or copy that entry in the direction of the arrow.  Use the <b>&raquo;</b> and <b>&laquo;</b> buttons to move the highlighted entry from the leftmost to the rightmost list or vice-versa.  Use the <b>&gt;</b> and <b>&lt;</b> buttons to move the highlighted entry between the Available Blocks list and the list to its right or left.<br /><br />The entries in the Available Blocks list do not change, regardless of what you do with the Move Right and Move Left buttons.  This is so because the same block can appear several times on the same page.  The HTML block is a good example of why you might want to do this.');
	break;

case 'block_move_up':
	$title=i18n::translate('block_move_up');
	$text=i18n::translate('Use these buttons to re-arrange the order of the entries within the list.  The blocks will be printed in the order in which they are listed.<br /><br />Highlight the entry to be moved, and then click a button to move that entry up or down.');
	break;

case 'bom_check':
	$title=i18n::translate('Byte Order Mark (BOM) check');
	$text=i18n::translate('This check will analyze all the language files for the BOM (Byte Order Mark). If found, it will remove the BOM from the affected file. These special codes can cause malfunctions in some parts of PhpGedView.');
	break;

case 'box_width':
	$title=i18n::translate('Box width');
	$text=i18n::translate('Here you can change the box width from 50% to 300%.  At 100% each box is about 270 pixels wide.');
	break;

case 'cache_life':
	$title=i18n::translate('Cache file life');
	$text=i18n::translate('To improve performance, this PhpGedCom Welcome Page block is saved as a cache file.  You can control how often this block\'s cache file is refreshed.<br /><br /><ul><li><b>-1</b> means that the cache file is never refreshed automatically.  To get a fresh copy, you need to delete all cache files.  You can do this on the Customize Welcome Page page.</li><li><b>0</b> (Zero) means that this block is never cached, and every time the block is displayed on the PhpGedView Welcome page, you see a fresh copy.  This setting is used automatically for blocks that change frequently, such as the Logged In Users and the Random Media blocks.</li><li><b>1</b> (One) means that a fresh copy of this block\'s cache file is created daily, <b>2</b> means that a fresh copy is created every two days, <b>7</b> means that a fresh copy is created weekly, etc.</li></ul>');
	break;

case 'cal_dowload':
	$title=i18n::translate('cal_dowload');
	$text=i18n::translate('This option controls whether the button for downloading calendar events is shown to logged-in users. The downloaded calendar file can be imported into compatible programs such as Microsoft Outlook to, for example, generate automatic e-mail reminders of anniversaries.<br /><br />When set to <b>No</b>, the logged-in user will not be able to download the calendar file.  When set to <b>Yes</b>, the Download button will be shown.  This button is never shown when the user is not logged in.');
	break;

case 'change_indi2id':
	$title=i18n::translate('change_indi2id');
	$text=i18n::translate('This tool was designed for users whose Genealogy programs use a different GEDCOM ID for the individuals every time the GEDCOM is exported. For example, the first time the GEDCOM is exported some person\'s ID might be I100 but the next time the GEDCOM is exported that same person\'s ID is changed to I234. These changing IDs make it difficult to administer PhpGedView because the ID is how people are referenced.<br /><br />Most genealogy programs also use the RIN or REFN tag to give each person a unique identifier that can be used to reference the individual. This tool will replace all of the individual IDs in the GEDCOM file with the whatever field (RIN or REFN) you specify.');
	break;

case 'chart_area':
	$title=i18n::translate('chart_area');
	$text=i18n::translate('Select the geographical area that you want to see on the map. You can choose:<p style="padding-left: 25px"><b>World</b>&nbsp;&nbsp;shows all continents.<br /><b>Europe</b>&nbsp;&nbsp;shows Europe.<br /><b>South America</b>&nbsp;&nbsp;shows South America.<br /><b>Asia</b>&nbsp;&nbsp;shows Asia.<br /><b>Middle East</b>&nbsp;&nbsp;shows the Middle East.<br /><b>Africa</b>&nbsp;&nbsp;shows Africa.</p>');
	break;

case 'chart_style':
	$title=i18n::translate('Chart style');
	$text=i18n::translate('Two chart styles are available:<ul><li><b>List</b><br />Vertical tree, with collapsible/expandable families.</li><li><b>Booklet</b><br />One family per page, with parents, grandparents, and all recorded children.<br /><br />This format is easy to print to give to your relatives.</li></ul>');
	break;

case 'chart_type':
	$title=i18n::translate('Chart Type');
	$text=i18n::translate('Select what you want to see on the map chart. You can choose:<p style="padding-left: 25px"><b>Individual distribution chart</b>&nbsp;&nbsp;shows the countries in which persons from this database occur.<br /><b>Surname distribution chart</b>&nbsp;&nbsp;shows the countries in which the specified surname occurs.</p>');
	break;

case 'cleanup_places':
	$title=i18n::translate('Cleanup Places');
	$text=i18n::translate('PhpGedView detected that your GEDCOM file uses places on GEDCOM tags that should not have places.<br /><br />Many genealogy programs, such as Family Tree Maker, will create this type of GEDCOM file. PhpGedView will work with these GEDCOM files, but some invalid places will show up in your place hierarchy. <br /><br />For example, your GEDCOM might have the following encoding<br />1 SSN<br />2 PLAC 123-45-6789<br />1 OCCU<br />2 PLAC Computer Programmer<br /><br />According to the GEDCOM 5.5.1 Standard this should really be shown as<br />1 SSN 123-45-6789<br />1 OCCU Computer Programmer<br /><br />If you select <b>Yes</b>, PhpGedView will automatically correct these encoding errors.');
	break;

case 'clear_cache':
	$title=i18n::translate('Clear cache files');
	$text=i18n::translate('In order to improve performance, several of the blocks on the PhpGedView Welcome page are saved as cache files in the index directory.  The cache files for most blocks are refreshed once each day, but there may be times when you want to refresh them manually.<br /><br />This button allows you to refresh the cache files when necessary.');
	break;

case 'click_here':
	$title=i18n::translate('Click here to continue');
	$text=i18n::translate('Click this button to save your changes.<br /><br />You will be returned to the Welcome or MyGedView Portal page, but your changes may not be shown.  You may need to use the Page Reload function of your browser to view your changes properly.');
	break;

case 'clip_cart':
	$title=i18n::translate('Clippings Cart');
	$text=i18n::translate('This box shows the contents of your Clippings Cart.  The <i>Types</i> column indicates the type of each entry, which can be Individual (INDI), Family (FAM), Source (SOUR), Repository (REPO), Note (NOTE), and Media (OBJE);  each is represented by its own icon.  The <i>ID</i> column shows the ID number for each item of that particular type.  The <i>Name / Description</i> column gives either the name of the family or individual, or a description of the item.  The Remove button will remove that record from the Clippings Cart.  <b>Confirmation to remove is NOT asked for.</b>');
	break;

case 'clip_download':
	$title=i18n::translate('clip_download');
	$text=i18n::translate('When you click this link you will be taken to the next page.  If any of the clippings in your cart refer to multimedia items, these items will also be displayed on that page.<br /><br />Simply follow the instructions.');
	break;

case 'collation':
	$title=i18n::translate('Database collation sequence');
	$text=i18n::translate('If you are using the database\'s built-in collation rules, this option specifies the collation sequence to use for this language.  You should ensure that your database supports all the collation sequences you intend to use.<br /><br />The use of database collation is controlled in the site configuration settings.');
	break;

case 'commit_google':
	$title=i18n::translate('Commit Google&reg; translated changes');
	$text=i18n::translate('When you click this link, all accumulated translations performed through the Google&reg; Translation system that have not already been saved will be saved.<br /><br />The indicator shows the progress of the Save operation.');
	break;

case 'compare_lang_utility':
	$title=i18n::translate('Language File Comparison Utility');
	$text=i18n::translate('This utility will compare two sets of language files to produce a list of the additions and subtractions between them.<br /><br />You should also check the various CHANGELOG files for changes to the English versions.  The English files should be used as the reference from which all others are produced.');
	break;

case 'config':
	$title=i18n::translate('config');
	$text=i18n::translate('Configuration help');
	break;

case 'config_help':
	$title=i18n::translate('Configuration help');
	$text=i18n::translate('This page collects all of the major topics of Configuration Help into one place.  You can view the information on your screen, or you can print it for later use.');
	break;

case 'config_lang_utility':
	$title=i18n::translate('Configuration of supported languages');
	$text=i18n::translate('This page is used to control what language choices are available to your users.  For example, you can set things up so that only German and French are available.  This might be useful if, for example, you are not able to communicate with your users in Hungarian.<br /><br />You also use this page to alter certain aspects of PhpGedView that depend on the selected language.  For example, here is where you tell PhpGedView how to format date and time fields.<br /><br />The languages that are active and greyed out cannot be disabled because they are in use. Look at the bottom table to see where the language is used. When a language is no longer used by the GEDCOM or user you will be able to disable it.<br /><br />All of your changes will be recorded in a new file called <b>lang_settings.php</b> created in the <b>#INDEX_DIRECTORY#</b> directory.  All of your further changes will be made to this new file and PhpGedView will use only <u>this</u> file.  You can revert to the original default language settings by deleting this file.<br /><br />If you must report problems with your language settings, please tell the PhpGedView support team whether this new file is present or not.');
	break;

case 'context':
	$title=i18n::translate('context');
	$text=i18n::translate('More help is available by clicking the <b>?</b> next to items on the page.');
	break;

case 'convertPath':
	$title=i18n::translate('Convert media path to');
	$text=i18n::translate('This option defines a constant path to be prefixed to all media paths in the output file.<br /><br />For example, if the media directory has been configured to be "/media" and if the media file being exported has a path "/media/pictures/xyz.jpg" and you have entered "c:\my pictures\my family" into this field, the resultant media path will be "c:\my pictures\my family/pictures/xyz.jpg".<br /><br />You will notice in this example:<ul><li>the current media directory name is stripped from the path</li><li>and the resultant path will not have correct folder name separators.</li></ul><br />If you wish to retain the media directory in media file paths of the output file, you will need to include that name in the <b>Convert media path to</b> field.<br /><br />You should also use the <b>Convert media folder separators to</b> option to ensure that the folder name separators are consistent and agree with the requirements of the receiving operating system.<br /><br />Media paths that are actually URLs will not be changed.');
	break;

case 'convertSlashes':
	$title=i18n::translate('Convert media folder separators to');
	$text=i18n::translate('This option determines whether folder names in the FILE specification of media objects should be separated by forward slashes or by backslashes.  Your choice depends on the requirements of the receiving operating system.<br /><br />The choice <b>Forward slashes : /</b> is appropriate for most operating systems other than Microsoft Windows.  The choice <b>Backslashes : \</b> should be used when the destination program is running on a Microsoft Windows system.<br /><br />Media paths that are actually URLs will not be changed.');
	break;

case 'convert_ansi2utf':
	$title=i18n::translate('convert_ansi2utf');
	$text=i18n::translate('To ensure that the information in your input GEDCOM files is processed and displayed correctly, these files should be encoded in UTF-8.<br /><br />Some of the more modern genealogy programs can export their data to a GEDCOM file in UTF-8 encoding.  Older programs often don\'t have this capability.  If your program does not offer you this option, PhpGedView can convert the file for you.<br /><br />When PhpGedView validates the input file, it will detect the file\'s encoding and advise you accordingly.');
	break;

case 'cookie':
	$title=i18n::translate('cookie');
	$text=i18n::translate('This site uses cookies to keep track of your login status.<br /><br />Cookies do not appear to be enabled in your browser. You must enable cookies for this site before you can login.  You can consult your browser\'s help documentation for information on enabling cookies.');
	break;

case 'cookie_login':
	$title=i18n::translate('cookie_login');
	$text=i18n::translate('This site remembered you from a previous login.  This allows you to access private information and other user-based features, but in order to edit or administer the site, you must login again for increased security.');
	break;

case 'date_format':
	$title=i18n::translate('Date format');
	$text=i18n::translate('This field defines the date format to be used by PhpGedView when displaying dates from the database. You can use either the <b>Standard</b> or the <b>Advanced</b> format.<ul><li><b>Standard</b><br />The following codes are used to represent different elements of the date.  Note that <b>D</b> and <b>M</b> have different meanings in the <b>Advanced</b> format.<p style="padding-left: 25px"><b>D</b>&nbsp;&nbsp;day of the month, without leading zeros; i.e. 1 to 31<br /><b>M</b>&nbsp;&nbsp;month name; i.e. January, February, etc.<br /><b>Y</b>&nbsp;&nbsp;full year, for example, 1999 or 44B.C.<br /><b>R</b>&nbsp;&nbsp;Calendar conversion.  Please see the topic <b>Calendar Conversion</b> below for a description of this code.</p>These codes can be combined in any order, along with spaces and punctuation.  For example, suppose your GEDCOM contains the date 20&nbsp;AUG&nbsp;1965.<p style="padding-left: 25px">An <b>English</b> user could set the format to <b>D&nbsp;M&nbsp;Y</b> to get <b>20&nbsp;August&nbsp;1965</b>.<br />An <b>American</b> user could set the format to <b>M&nbsp;D,&nbsp;Y</b> to get <b>August&nbsp;20,&nbsp;1965</b><br />A <b>German</b> user could set the format to <b>D.&nbsp;M&nbsp;Y</b> to get <b>20.&nbsp;August&nbsp;1965</b><br />A <b>Hungarian</b> user could set the format to <b>Y.&nbsp;M&nbsp;D.</b> to get <b>1965.&nbsp;augusztus&nbsp;20.</b></p><br /></li><li><b>Advanced</b><br />More complex formatting options are available.  If your date format includes a letter other than <b>D</b>, <b>M</b>, <b>Y</b>, or <b>R</b>, it will be assumed to use the <b>Advanced</b> format.<br /><br />The following codes are used to represent different elements of the date.  Note that <b>D</b> and <b>M</b> have different meanings in the <b>Standard</b> format.<p style="padding-left: 25px"><b>d</b>&nbsp;&nbsp;day of the month, with leading zeros; i.e. 01 to 31<br /><b>j</b>&nbsp;&nbsp;day of the month, without leading zeros; i.e. 1 to 31<br /><b>S</b>&nbsp;&nbsp;ordinal suffix for the day number; for example, the letters <b>th</b> in <b>4th&nbsp;July</b>.<br /><b>l</b> (lower-case "L")&nbsp;&nbsp;day of the week; i.e. Monday, Tuesday, etc.<br /><b>D</b>&nbsp;&nbsp;abbreviated day of the week; i.e. M, Tu, W, Th, F, Sa, Su<br /><b>N</b>&nbsp;&nbsp;ISO day number; i.e. 0 to 6<br /><b>w</b>&nbsp;&nbsp;numeric day number; i.e. 1 to 7<br /><b>z</b>&nbsp;&nbsp;day of the year; i.e 1 to 365<br /><b>F</b>&nbsp;&nbsp;month name; i.e. January, February, etc.<br /><b>M</b>&nbsp;&nbsp;abbreviated month, i.e. Jan, Feb, etc.<br /><b>m</b>&nbsp;&nbsp;month number, with leading zeros; i.e. 01 to 12<br /><b>n</b>&nbsp;&nbsp;month number, without leading zeros; i.e. 1 to 12<br /><b>Y</b>&nbsp;&nbsp;full year, for example, 1999 or 44B.C.<br /><b>y</b>&nbsp;&nbsp;abbreviated year.  Some calendars allow years to be abbreviated.  For the Jewish calendar this is the year without the thousands.  For the Gregorian calendar this is the same as the full year.<br /><b>R</b>&nbsp;&nbsp;Calendar conversion.  Please see the topic <b>Calendar Conversion</b> below for a description of this code.<br /><b>@&nbsp;A&nbsp;O&nbsp;E</b>&nbsp;&nbsp;internal GEDCOM format</p><p style="padding-left: 50px"><b>@</b>&nbsp;&nbsp;calendar code, e.g. @#DGREGORIAN@, @#DHEBREW@, etc.<br /><b>A</b>&nbsp;&nbsp;day, i.e. 01 to 31<br /><b>O</b>&nbsp;&nbsp;month, e.g. JAN, FEB, MAR, etc.<br /><b>E</b>&nbsp;&nbsp;year, e.g. 1776, 1867, 2007, etc.<br /><br />To see the unconverted dates, use <b>A&nbsp;O&nbsp;E</b> or <b>@&nbsp;A&nbsp;O&nbsp;E</b></p><b>Some examples</b>, using the date 08&nbsp;SEP&nbsp;1965:<p style="padding-left: 25px"><b>d&nbsp;M&nbsp;Y</b> = 08&nbsp;Sep&nbsp;1965<br /><b>D&nbsp;j&nbsp;F&nbsp;Y</b> = W&nbsp;8&nbsp;September&nbsp;1965<br /><b>j&nbsp;M&nbsp;y</b> = 8&nbsp;Sep&nbsp;65<br /><b>l&nbsp;-&nbsp;d&nbsp;-&nbsp;F&nbsp;-&nbsp;Y</b> = Wednesday&nbsp;-&nbsp;08&nbsp;-&nbsp;September&nbsp;-&nbsp;1965<br /><b>y/m/d</b> = 1965/09/08</p><br /></li><li><b>Calendar Conversion</b><br />The <b>R</b> code is somewhat unusual in its behavior.  When this code appears in the date format, the normal date will be shown a second time, but in a different calendar system.  The elements of this alternate calendar date will obey the same rules as those of the main part of the format specification, except that the output of the <b>R</b> formatting code is enclosed in parentheses to distinguish it from the regular date.  The examples given under <b>French Revolutionary</b> calendar will make this a little clearer.<br /><br />The alternate calendar to be used for the <b>R</b> code is determined according to the current page language.  When the page language is Hebrew, the <b>Jewish</b> calendar will be used.  When the page language is Arabic, the <b>Hijri</b> calendar will be used.  For all other page languages, the <b>French Revolutionary</b> calendar is used.<br /><br />The action of this code is similar to the choice of calendar format that you can make on the GEDCOM Configuration page.  The difference between the two methods is that the <b>R</b> code, being defined at the Language Configuration level, works only on selected languages but across all GEDCOMs on the site.  The calendar option set at the GEDCOM Configuration level works on all languages but only on the GEDCOMs for which that option has been set.<br /><ul><li><b>French Revolutionary</b> calendar&nbsp;&nbsp;&nbsp;This calendar is also called <b>French Republican</b> calendar.<br /><br />Except for a very brief period in May 1871, this calendar system was only in use in France and only between 22&nbsp;SEP&nbsp;1792 and 31&nbsp;DEC&nbsp;1805.  Accordingly, the French Revolutionary calendar format will only be applicable when the date is within that range.<br /><br />Two example date formats, assuming the page language to be English, will illustrate the action of the <b>R</b> code:<br /><b>Y.m.d&nbsp;R</b> could produce <b>1805.04.26&nbsp;(XIII.08.06)</b> or <b>1806.01.01</b>;&nbsp; <b>j&nbsp;F&nbsp;Y&nbsp;R</b> could produce <b>13&nbsp;October&nbsp;1797&nbsp;(22&nbsp;Vendemiaire&nbsp;VI)</b> or <b>1&nbsp;January&nbsp;1806</b>.</li></ul><br /></li><li><b>Language alternatives</b>&nbsp;&nbsp;&nbsp;The abbreviated texts for the Advanced codes <b>D</b> and <b>M</b> are set in the file <i>languages/lang.xx.php</i>, using  statements like <code>$pgv_lang["sunday_1st"]&nbsp;=&nbsp;"Su";</code>&nbsp;&nbsp; and&nbsp;&nbsp; <code>$pgv_lang["apr_1st"]&nbsp;=&nbsp;"April";</code><br /><br />These can be changed by using a <i>languages/extra.xx.php</i> file as described in Wiki and ReadMe text for language adjustments, for example:<br /><code>$pgv_lang["sunday_1st"]&nbsp;=&nbsp;"Sun";<br />$pgv_lang["apr_1st"]&nbsp;=&nbsp;"Apr";</code><br /><br /><br /></li><li><b>Localization</b>&nbsp;&nbsp;&nbsp;Special formatting rules exist for a number of languages, particularly declension of month endings according to how the date is phrased.  These special rules are implemented in the various <i>includes/extras/functions.xx.php</i> files.</li></ul>');
	break;

case 'day_month':
	$title=i18n::translate('day_month');
	$text=i18n::translate('<ul><li>The <b>View Day</b> button will display the events of the chosen date in a list. All years are scanned, so only the day and month can be set here. Changing the year will have no effect.  You can reduce the list by choosing the option <b>Recent years</b> or <b>Living people</b>.<br /><br />Ages in the list will be calculated from the current year.</li><li>The <b>View Month</b> button will display a calendar diagram of the chosen month and year. Here too you can reduce the lists by choosing the option <b>Recent years</b> or <b>Living people</b>.<br /><br />You will get a realistic impression of what a calendar on the wall of your ancestors looked like by choosing a year in the past in combination with <b>Recent years</b>. All ages on the calendar are shown relative to the year in the Year box.</li><li>The <b>View Year</b> button will show you a list of events of the chosen year.  Here too you can reduce the list by choosing the option <b>Recent years</b> or <b>Living people</b>.<br /><br />You can show events for a range of years.  Just type the beginning and ending years of the range, with a dash <b>-</b> between them.  Examples:<br /><b>1992-4</b> for all events from 1992 to 1994<br /><b>1976-1984</b> for all events from 1976 to 1984<br /><br />To see all the events in a given decade or century, you can use <b>?</b> in place of the final digits. For example, <b>197?</b> for all events from 1970 to 1979 or <b>16??</b> for all events from 1600 to 1699.</li></ul>When you want to <b>change the year</b> you <b>have</b> to press one of these three buttons.  All other settings remain as they were.');
	break;

case 'days_to_show':
	$title=i18n::translate('Number of days to show');
	$text=i18n::translate('Enter the number of days to show.  This number cannot be greater than <b>#DAYS_TO_SHOW_LIMIT#</b>.  If you enter a larger value, that limit will be used.<br /><br />The limit shown is set by the administrator in the GEDCOM configuration, Display and Layout section, Hide &amp; Show sub-section.');
	break;

case 'def_gedcom_date':
	$title=i18n::translate('def_gedcom_date');
	$text=i18n::translate('Although the date field allows for free-form entry (meaning you can type in whatever you want), there are some rules about how dates should be entered according to the GEDCOM 5.5.1 standard.<ol><li>A full date is entered in the form DD MMM YYYY.  For example, <b>01&nbsp;MAR&nbsp;1801</b> or <b>14&nbsp;DEC&nbsp;1950</b>.</li><li>If you are missing a part of the date, you can omit that part.  E.g. <b>MAR&nbsp;1801</b> or <b>14&nbsp;DEC</b>.</li><li>If you are not sure or the date is not confirmed, you could enter <b>ABT&nbsp;MAR&nbsp;1801</b> (abt = about), <b>BEF&nbsp;20&nbsp;DEC&nbsp;1950</b> (bef = before), <b>AFT&nbsp;1949</b> (aft = after)</li><li>Date ranges are entered as <b>FROM&nbsp;MAR&nbsp;1801&nbsp;TO&nbsp;20&nbsp;DEC&nbsp;1810</b> or as <b>BET&nbsp;MAR&nbsp;1801&nbsp;AND&nbsp;20&nbsp;DEC&nbsp;1810</b> (bet = between)<br /><br />The <b>FROM</b> form indicates that the event being described happened continuously between the stated dates and is used with events such as employment. The <b>BET</b> form indicates a single occurrence of the event, sometime between the stated dates and is used with events such as birth.<br /><br />Imprecise dates, where the day of the month or the month is missing, are always interpreted as the first or last possible date, depending on whether that imprecise date occurs before or after the separating keyword.  For example, <b>FEB&nbsp;1804</b> is interpreted as <b>01&nbsp;FEB&nbsp;1804</b> when it occurs before the TO or AND, and as <b>29&nbsp;FEB&nbsp;1804</b> when it occurs after the TO or AND.</li></ol><b>Be sure to enter dates and abbreviations in <u>English</u>,</b> because then the GEDCOM file is exchangeable and PhpGedView can translate all dates and abbreviations properly into the currently active language.  Furthermore, PhpGedView does calculations using these dates. If improper dates are entered into date fields, PhpGedView will not be able to calculate properly.<br /><br />You can click on the Calendar icon for help selecting a date.');
	break;

case 'def_gedcom':
	$title=i18n::translate('def_gedcom');
	$text=i18n::translate('A quote from the Introduction to the GEDCOM 5.5.1 Standard:<div class="list_value_wrap">GEDCOM was developed by the Family History Department of The Church of Jesus Christ of Latter-day Saints (LDS Church) to provide a flexible, uniform format for exchanging computerized genealogical data.&nbsp; GEDCOM is an acronym for <i><b>GE</b></i>nealogical <i><b>D</b></i>ata <i><b>Com</b></i>munication.&nbsp; Its purpose is to foster the sharing of genealogical information and the development of a wide range of inter-operable software products to assist genealogists, historians, and other researchers.</div><br />A copy of the GEDCOM 5.5.1 <u>draft</u> Standard, to which PhpGedView adheres, can be downloaded in PDF format here:&nbsp; <a href="http://www.phpgedview.net/ged551-5.pdf" target="_blank">GEDCOM 5.5.1 Standard</a>  This Standard is only available in English.<br /><br />The GEDCOM file contains all the information about the family. All facts, dates, events, etc. are stored here. GEDCOM files have to follow strict rules because they must be exchangeable between many programs, independent of platforms or operating systems.');
	break;

case 'def_gramps':
	$title=i18n::translate('def_gramps');
	$text=i18n::translate('A quote from GRAMPS Project: <div class="list_value_wrap">GRAMPS helps you track your family tree. It allows you to store, edit, and research genealogical data. GRAMPS attempts to provide all of the common capabilities of other genealogical programs, but, more importantly, to provide an additional capability of integration not common to these programs. This is the ability to input any bits and pieces of information directly into GRAMPS and rearrange/manipulate any/all data events in the entire data base (in any order or sequence) to assist the user in doing research, analysis and correlation with the potential of filling relationship gaps.</div><br />A copy of the GRAMPS XML format v1.1.0 <a href="http://www.gramps-project.org/xml/1.1.0/" target="_blank">can be found here</a> in both RELAX NG Schema format and DTD format.<br /><br />For more information about the GRAMPS Project visit <a href="http://gramps-project.org/" target="_blank">http://gramps-project.org/</a>');
	break;

case 'def':
	$title=i18n::translate('def');
	$text=i18n::translate('<div class="name_head"><center><b>Definitions</b></center></div><br />Here are some explanations of terms used in this Help text:<ul><li><a href="#def_gedcom"><b>GEDCOM</b></a><br /></li><li><a href="#def_gedcom_date"><b>Dates</b></a></li><li><a href="#def_pdf_format"><b>PDF file format</b></a></li><li><a href="#def_pgv"><b>PhpGedView</b></a></li><li><a href="#def_portal"><b>Portal</b></a></li><li><a href="#def_theme"><b>Theme</b></a></li></ul>');
	break;

case 'def_pdf_format':
	$title=i18n::translate('def_pdf_format');
	$text=i18n::translate('The PhpGedView Reporting Engine produces downloadable reports in Adobe&reg; PDF format.  The GEDCOM 5.5.1 Standard specification, mentioned elsewhere in this Help file, is also downloadable as a PDF file.  PDF is an acronym for <b>P</b>ortable <b>D</b>ocument <b>F</b>ormat.<br /><br />PDF files are not viewable or printable by the standard software on your PC.  If you already have Acrobat Reader installed (it\'s often packaged with other softwares), you do not need to replace or upgrade it to deal with report files produced by PhpGedView.<br /><br />Acrobat Reader, the viewing and printing program for these files, is available free of charge from Adobe Systems Inc.  The free Adobe&reg; Acrobat Reader can be downloaded from the <a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank"><b>Adobe Systems Inc.</b></a> web site.  You may find copies of "Acrobat Reader" available for download from other Internet sites, but we strongly advise you to trust <u>only</u> the Adobe Systems Inc. site.<br /><br />Acrobat Reader is available for many different systems, including Microsoft&reg; Windows and Apple&reg; Macintosh, in many languages other than English.  If you have a Windows 95 system, be sure to download Acrobat Reader version 5.0.5.  Versions more recent than this will not install correctly on Windows 95 systems.<br /><br /><a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank"><b>Download Adobe Reader here</b></a>');
	break;

case 'def_pgv':
	$title=i18n::translate('def_pgv');
	$text=i18n::translate('PhpGedView (or PGV) does not just put static pages on the Web; it is dynamic and can be customized in many ways.<br /><br />PhpGedView was created by John Finlay to view GEDCOM files online.  John started developing the program on his own.  An international team of developers and translators has since joined him and is working to improve the program.  Among the more significant features that have been added or improved in the program are its extensive support of languages other than English, and the ability to add and edit events online.');
	break;

case 'def_portal':
	$title=i18n::translate('def_portal');
	$text=i18n::translate('This site\'s Portal is like the lobby of a restaurant or a public library. It is the place where you enter, but you can also find important information like explanations, menus etc.');
	break;

case 'def_theme':
	$title=i18n::translate('def_theme');
	$text=i18n::translate('This site can have different "appearances", called Themes.<br /><br />The site administrator chooses a default Theme, which everybody who enters this site will initially see. When the administrator has enabled this feature, all users can select their own Themes.  PhpGedView remembers the last selected Theme for each logged-in user, so that that user will automatically see that Theme the next time he logs in.  Themes can be used as a way to distinguish between different databases on the same site.  Each database can have a different default Theme.');
	break;

case 'default_gedcom':
	$title=i18n::translate('default_gedcom');
	$text=i18n::translate('If you have more than one genealogical database, you can set here which of them will be the default.<br /><br />This default will be shown to all visitors and users who have not yet logged in.<br /><br />Users who can edit their account settings can override this default.  In that case, the user\'s preferred database will be shown after login.');
	break;

case 'delete_faq_item':
	$title=i18n::translate('Delete FAQ item');
	$text=i18n::translate('This option will let you delete an item from the FAQ page');
	break;

case 'delete_gedcom':
	$title=i18n::translate('delete_gedcom');
	$text=i18n::translate('PhpGedView creates its database from a GEDCOM file that was previously uploaded. When you select <b>Delete</b>, that section of the database will be erased.  You have to confirm your Delete request.<br /><br />Unless you have deliberately removed it outside PhpGedView, the original GEDCOM file will remain in the directory into which it was uploaded.  If you later want to work with that GEDCOM file again, you don\'t have to upload it again. You can choose the <b>Add GEDCOM</b> function.');
	break;

case 'delete_name':
	$title=i18n::translate('Delete Name');
	$text=i18n::translate('<b>EDIT NAME</b><br />When you click this link, another window will open.  There you can edit the name of the person.  Just type the changes into the boxes and click the button, close the window, and that\'s it.<br /><br /><b>DELETE NAME</b><br />By clicking this option you will mark this Name to be deleted from the database.  Note that deleting the name is completely different from deleting the individual.  Deleting the name just removes the name from the person. The person will <u>not</u> be deleted.  If it is an AKA that you want to delete, the person still has his other names.  If it is the <u>only</u> name that you want to remove, the person will still not be deleted, but will now be recorded as <b>(unknown)</b>.  The person will also not be disconnected from any other to relatives, sources, notes, etc.<br /><br />How does it work?<br />You will be asked to confirm your deletion request.  If you decide to continue, it can take a little time before you see a message that the name is deleted.<br /><br />When you continue with your visit, you will notice that the name is still visible and can be used as if the deletion had not occurred.<br /><br /><b>This is <u>not</u> an error.</b>  The site admin will get a message that a change has been made to the database, and that you removed the name.<br />The administrator can accept or reject your change. Only after the administrator has accepted your change will the deletion actually occur <u>irreversibly</u>.  If there is any doubt about your change, the administrator will contact you.');
	break;

case 'delete_person':
	$title=i18n::translate('Delete this individual');
	$text=i18n::translate('When you click this option, you will mark this individual to be deleted from the database.<br /><br />What does that mean?<br />Let\'s suppose you have a good reason to remove this person from the database. You click the link.  You will be asked to confirm your deletion request.  If you decide to continue, it can take a little time before you see a message that the individual is deleted.<br /><br />When you continue with your visit, you will notice that the person is still visible and can be used as if the deletion had not occurred.<br /><br /><b>This is <u>not</u> an error.</b>  The site admin will get a message that a change has been made to the database, and that you removed the individual.<br />The administrator can accept or reject your change. Only after the administrator has accepted your change will the deletion actually occur <u>irreversibly</u>.  If there is any doubt about your change, the administrator will contact you.');
	break;

case 'delete_repo':
	$title=i18n::translate('Delete Repository');
	$text=i18n::translate('When you click this option you mark this Repository to be deleted from the database.<br /><br />What does that mean?<br />Let\'s suppose you have a good reason to remove this Repository from the database. You click the link.  You will be asked to confirm your deletion request.  If you decide to continue, it can take a little time before you see a message that the Repository is deleted.<br /><br />When you continue with your visit, you will notice that the Repository is still visible and can be used as if the deletion had not occurred.<br /><br /><b>This is <u>not</u> an error.</b>  The site admin will get a message that a change has been made to the database, and that you removed the Repository.<br />The administrator can accept or reject your change. Only after the administrator has accepted your change will the deletion actually occur <u>irreversibly</u>.  If there is any doubt about your change, the administrator will contact you.');
	break;

case 'delete_source':
	$title=i18n::translate('Delete this Source');
	$text=i18n::translate('When you click this option, you will mark this Source to be deleted from the database.<br /><br />What does that mean?<br />Let\'s suppose you have a good reason to remove this source from the database. You click the link.  You will be asked to confirm your deletion request.  If you decide to continue, it can take a little time before you see a message that the source is deleted.<br /><br />When you continue with your visit, you will notice that the source is still visible and can be used as if the deletion had not occurred.<br /><br /><b>This is <u>not</u> an error.</b>  The site admin will get a message that a change has been made to the database, and that you removed the Source.<br />The administrator can accept or reject your change. Only after the administrator has accepted your change will the deletion actually occur <u>irreversibly</u>.  If there is any doubt about your change, the administrator will contact you.');
	break;

case 'desc_generations':
	$title=i18n::translate('desc_generations');
	$text=i18n::translate('Here you can set the number of generations to display on this page.<br /><br />The right number for you depends of the size of your screen and whether you show details or not.  Processing time will increase as you increase the number of generations.');
	break;

case 'desc_rootid':
	$title=i18n::translate('desc_rootid');
	$text=i18n::translate('If you want to display a chart with a new starting (root) person, the ID of that new starting person is typed here.<br /><br />If you don\'t know the ID of that person, use the <b>Find ID</b> link.<br /><br /><b>ID NUMBER</b><br />The ID numbers used inside PhpGedView are <u>not</u> the identification numbers issued by various governments (driving permit or passport numbers, for instance).  The ID number referred to here is simply a number used within the database to uniquely identify each individual; it was assigned by the ancestry program that created the GEDCOM file which was imported into PhpGedView.');
	break;

case 'detected_ansi2utf':
	$title=i18n::translate('detected_ansi2utf');
	$text=i18n::translate('The GEDCOM file being validated now is encoded in the ANSI character set.  You are strongly advised to convert the file\'s encoding to UTF-8.<br /><br /><br />~CONVERT ANSI TO UTF-8~<br /><br />To ensure that the information in your input GEDCOM files is processed and displayed correctly, these files should be encoded in UTF-8.<br /><br />Some of the more modern genealogy programs can export their data to a GEDCOM file in UTF-8 encoding.  Older programs often don\'t have this capability.  If your program does not offer you this option, PhpGedView can convert the file for you.<br /><br />When PhpGedView validates the input file, it will detect the file\'s encoding and advise you accordingly.');
	break;

case 'detected_date':
	$title=i18n::translate('detected_date');
	$text=i18n::translate('<b>DATE FORMAT WILL BE CHANGED</b><br /><br />The date format that is standard for PhpGedView and also according to the GEDCOM 5.5.1 Standard is <b>DD&nbsp;MMM&nbsp;YYYY</b> (e.g. 01&nbsp;JAN&nbsp;2004)<br /><br />If, after your GEDCOM file has been validated, you see a message that a wrong date format has been detected, PhpGedView will convert the incorrectly formatted dates as prescribed by the Standard.<br /><br />You have, however, the option to choose either "<b>day</b> before month" (DD&nbsp;MMM&nbsp;YYYY), or "<b>month</b> before day" (MMM&nbsp;DD&nbsp;YYYY).<br /><br />We recommend that you use the first format (day before month).');
	break;

case 'dictionary_sort':
	$title=i18n::translate('Use dictionary rules while sorting');
	$text=i18n::translate('This option controls how characters with diacritic marks are handled when sorting lists of names and titles.<br /><br />When set to <b>Yes</b>, all characters with diacritic marks are treated as if they did not have any marks.  Diacritic marks are considered only when the two words being considered are otherwise identical.  When set to <b>No</b>, all letters are distinct, regardless of the presence or absence of diacritic marks.');
	break;

case 'download_gedcom':
	$title=i18n::translate('Download GEDCOM');
	$text=i18n::translate('From this page you can download your genealogical database in GEDCOM format.  You may want to import the data into another genealogical program, or you may want to share its information with others.<br /><br />~CONVERT FROM UTF-8 TO ANSI~<br /><br />For optimal display on the Internet, PhpGedView uses the UTF-8 character set.  Some programs, Family Tree Maker for example, do not support importing GEDCOM files encoded in UTF-8.  Checking this box will convert the file from <b>UTF-8</b> to <b>ANSI (ISO-8859-1)</b>.<br /><br />The format you need depends on the program you use to work with your downloaded GEDCOM file.  If you aren\'t sure, consult the documentation of that program.<br /><br />Note that for special characters to remain unchanged, you will need to keep the file in UTF-8 and convert it to your program\'s method for handling these special characters by some other means.  Consult your program\'s manufacturer or author.<br /><br />This <a href=\'http://en.wikipedia.org/wiki/UTF-8\' target=\'_blank\' title=\'Wikipedia article\'><b>Wikipedia article</b></a> contains comprehensive information and links about UTF-8.<br /><br /><br /><br />~REMOVE CUSTOM PGV TAGS~<br /><br />Checking this option will remove any custom tags that may have been added to the records by PhpGedView.<br /><br />Custom tags used by PhpGedView include the <b>_PGVU</b> tag which identifies the user who changed a record online and the <b>_THUM</b> tag which tells PhpGedView that the image should be used as a thumbnail.<br /><br />Custom tags may cause errors when importing the downloaded GEDCOM to another genealogy application.<br /><br /><br /><br />~DOWNLOAD GEDCOM AS ZIP FILE~<br /><br />When you check this option, a copy of the GEDCOM file will be compressed into ZIP format before the download begins. This will reduce its size considerably, but you will need to use a compatible Unzip program (WinZIP, for example) to decompress the transmitted GEDCOM file before you can use it.<br /><br />This is a useful option for downloading large GEDCOM files.  There is a risk that the download time for the uncompressed file may exceed the maximum allowed execution time, resulting in incompletely downloaded files.  The ZIP option should reduce the download time by 75%.');
	break;

case 'download_zipped':
	$title=i18n::translate('download_zipped');
	$text=i18n::translate('When you check this option, a copy of the GEDCOM file will be compressed into ZIP format before the download begins. This will reduce its size considerably, but you will need to use a compatible Unzip program (WinZIP, for example) to decompress the transmitted GEDCOM file before you can use it.<br /><br />This is a useful option for downloading large GEDCOM files.  There is a risk that the download time for the uncompressed file may exceed the maximum allowed execution time, resulting in incompletely downloaded files.  The ZIP option should reduce the download time by 75%.');
	break;
	break;

case 'edit_SOUR_EVEN':
	$title=i18n::translate('edit_SOUR_EVEN');
	$text=i18n::translate('Each source records specific events, generally for a given date range and for a place jurisdiction.  For example a Census records census events and church records record birth, marriage, and death events.<br /><br />Select the events that are recorded by this source from the list of events provided. The date should be specified in a range format such as <i>FROM 1900 TO 1910</i>. The place jurisdiction is the name of the lowest jurisdiction that encompasses all lower-level places named in this source. For example, "Oneida, Idaho, USA" would be used as a source jurisdiction place for events occurring in the various towns within Oneida County. "Idaho, USA" would be the source jurisdiction place if the events recorded took place not only in Oneida County but also in other counties in Idaho.');
	break;

case 'edit_add_ASSO':
	$title=i18n::translate('edit_add_ASSO');
	$text=i18n::translate('Add a new Associate allows you to link a fact with an associated person in the site.  This is one way in which you might record that someone was the Godfather of another person.');
	break;

case 'edit_add_GEDFact_ASSISTED':
	$title=i18n::translate('edit_add_GEDFact_ASSISTED');
	$text=i18n::translate('Clicking the "+" icon will open the GEDFact Shared Note Assistant window.<br />Specific help will be found there.<br /><br />When you click the "Save" button, the ID of the Shared Note will be pasted here.');
	break;

case 'edit_add_NOTE':
	$title=i18n::translate('edit_add_NOTE');
	$text=i18n::translate('This section allows you to add a new Note to the fact that you are currently editing.  Notes are free-form text and will appear in the Fact Details section of the page.');
	break;

case 'edit_add_SHARED_NOTE':
	$title=i18n::translate('edit_add_SHARED_NOTE');
	$text=i18n::translate('Shared notes, like regular notes, are free-form text.  Unlike regular notes, each shared note can be linked to more than one person, family, source, or fact.<br /><br />By clicking the appropriate icon, you can establish a link to an existing shared note or create a new shared note and at the same time link to it.  If a link to an existing shared note has already been established, you can also edit that note\'s contents.<br /><ul><li><b>Link to an existing shared note</b><div style="padding-left:20px;">If you already know the ID number of the desired shared note, you can enter that number directly into the field.<br /><br />When you click the <b>Find Shared Note</b> icon, you will be able to search the text of all existing shared notes and then choose one of them.  The ID number of the chosen note will be entered into the field automatically.<br /><br />You must click the <b>Add</b> button to update the original record.</div><br /></li><li><b>Create a new shared note</b><div style="padding-left:20px;">When you click the <b>Create a new Shared Note</b> icon, a new window will open.  You can enter the text of the new note as you wish.  As with regular notes, you can enter URLs.<br /><br />When you click the <b>Save</b> button, you will see a message with the ID number of the newly created shared note.  You should click on this message to close the editing window and also copy that new ID number directly into the ID number field.  If you just close the window, the newly created ID number will not be copied automatically.<br /><br />You must click the <b>Add</b> button to update the original record.</div><br /></li><li><b>Edit an existing shared note</b><div style="padding-left:20px;">When you click the <b>Edit Shared Note</b> icon, a new window will open.  You can change the text of the existing shared note as you wish.  As with regular notes, you can enter URLs.<br /><br />When you click the <b>Save</b> button, the text of the shared note will be updated.  You can close the window and then click the <b>Save</b> button again.<br /><br />When you change the text of a shared note, your change will be reflected in all places to which that shared note is currently linked.  New links that you establish after having made your change will also use the updated text.</div></li></ul>');
	break;

case 'edit_add_SOUR':
	$title=i18n::translate('edit_add_SOUR');
	$text=i18n::translate('This section allows you to add a new source citation to the fact that you are currently editing.<br /><br />In the Source field you enter the ID for the source.  Click the <b>Create a new source</b> link if you need to enter a new source.  In the Citation Details field you would enter the page number or other information that might help someone find the information in the source.  In the Text field you would enter the text transcription from the source.');
	break;

case 'edit_add_child':
	$title=i18n::translate('edit_add_child');
	$text=i18n::translate('With this page you can add a new child to the selected family.  Fill out the name of the child and the birth and death information if it is known.  If you don\'t know some information leave it blank.<br /><br />To add other facts besides birth and death, first add the new child to the database by saving the changes.  Then click on the child\'s name in the updated Family page or Close Relatives tab to view the child\'s Individual Information page.  From the Individual Information page you can add more detailed information.');
	break;

case 'edit_add_parent':
	$title=i18n::translate('edit_add_parent');
	$text=i18n::translate('With this page you can add a new mother or father to the selected person.  Fill out the new person\'s name and the birth and death information if it is known.  If you don\'t know some information, leave it blank.<br /><br />To add other facts besides birth and death, first add the new person to the database by saving the changes.  Then click on the person\'s name in the updated Family page or Close Relatives tab to view the person\'s Individual Information page.  From the Individual Information page you can add more detailed information.');
	break;

case 'edit_add_spouse':
	$title=i18n::translate('edit_add_spouse');
	$text=i18n::translate('With this page you can add a new husband or wife to the selected person.  Fill out the new person\'s name and the birth and death information if it is known.  If you don\'t know some information leave it blank.<br /><br />To add other facts besides birth and death, first add the new person to the database by saving the changes.  Then click on the person\'s name in the updated Family page or Close Relatives tab to view the person\'s Individual Information page.  From the Individual Information page you can add more detailed information.');
	break;

case 'edit_add_unlinked_note':
	$title=i18n::translate('edit_add_unlinked_note');
	$text=i18n::translate('Use this link to add a new shared note to your database without linking the note to any record.<br /><br />The new note will appear in the Shared Note list, but will not appear on any charts or anywhere else in the program until it is linked to an individual, family or event.');
	break;

case 'edit_add_unlinked_person':
	$title=i18n::translate('edit_add_unlinked_person');
	$text=i18n::translate('Use this form to add an unlinked person.<br /><br />When you add an unlinked person to your family tree, the person will not be linked to any other people until you link them.  Later, you can link people together from the Close Relatives tab on the Individual Information page.');
	break;

case 'edit_add_unlinked_source':
	$title=i18n::translate('edit_add_unlinked_source');
	$text=i18n::translate('Use this link to add a new source to your database without linking the source to a source citation in another record.  The new source will appear in the source list, but will not appear on any charts or anywhere else in the program until it is linked up to a source citation.');
	break;

case 'edit_birth':
	$title=i18n::translate('edit_birth');
	$text=i18n::translate('This area allows you to enter the birth information.  First enter the date when the person was born in the standard date format for genealogy (1 JAN 2004).  You can click on the Calendar icon for help selecting a date.  Then enter the place where the person was born.  You can use the <b>Find Place</b> link to select a place that already exists in the database.');
	break;

case 'edit_config_gedcom':
	$title=i18n::translate('edit_config_gedcom');
	$text=i18n::translate('Every genealogical database used with PhpGedView has its own <b>Configuration file</b>.<br /><br />On this form you configure many options such as database title, language, calendar format, email options, logging of database searches, HTML META headers, removal of surnames from the database\'s Frequent Surnames list, etc.');
	break;

case 'edit_death':
	$title=i18n::translate('edit_death');
	$text=i18n::translate('This area allows you to enter Death information.  First enter the date when the person died in the standard date format for genealogy (1 JAN 2004).  You can click on the Calendar icon for help selecting a date.  Then enter the place where the person died.  You can use the <b>Find Place</b> link to select a place that already exists in the database.');
	break;

case 'edit_edit_raw':
	$title=i18n::translate('edit_edit_raw');
	$text=i18n::translate('This page allows you to edit the raw GEDCOM record.  You should use this page with caution; it requires a good understanding of the GEDCOM 5.5.1 Standard.  For more information on the GEDCOM 5.5.1 Standard, refer to Help topic <b>GEDCOM file</b>.<br /><br />PhpGedView provides many ways to add and edit information, but there could be occasions when you may want to edit the raw GEDCOM structure.  When possible, you should use the provided forms for adding information, but when that is impossible, you can use this form.  Upon submitting the form, your information will be checked for basic conformance to the Standard and the CHAN record will be updated.');
	break;

case 'edit_faq_item':
	$title=i18n::translate('Edit FAQ item');
	$text=i18n::translate('This option will let you edit an item on the FAQ page.');
	break;

case 'edit_gedcoms':
	$title=i18n::translate('edit_gedcoms');
	$text=i18n::translate('The GEDCOM Administration page is the control center for administering all of your genealogical databases.<br /><br /><b>Current GEDCOMs</b><br />At the head of the <b>Current GEDCOMs</b> table, you see an action bar with four links.<ul><li>Add GEDCOM</li><li>Upload GEDCOM</li><li>Create a new GEDCOM</li><li>Return to the Admin menu</li></ul>In the <b>Current GEDCOMs</b> table each genealogical database is listed separately, and you have the following options for each of them:<ul><li>Import</li><li>Delete</li><li>Download</li><li>Edit configuration</li><li>Edit privacy</li><li>SearchLog files</li></ul>Edit privacy appears here because every GEDCOM has its own privacy file.<br /><br />Each line in this table should be self-explanatory.  PhpGedView can be configured to log all database searches.  The SearchLog files can be inspected through links found on this page.<br /><br />Contextual help is available on every screen; make sure that the <b>Show Contextual Help</b> option in the Help menu is on, and click on a <b>?</b> next to the subject.');
	break;

case 'edit_given_name':
	$title=i18n::translate('edit_given_name');
	$text=i18n::translate('In this field you should enter the given names for the person.  As an example, in the name "John Robert Finlay", the given names that should be entered here are "John Robert"');
	break;

case 'edit_lang_utility':
	$title=i18n::translate('Language File Edit Utility');
	$text=i18n::translate('You can use this utility to edit the contents of a language file by using the contents of the English one.<br /><br />You will see the contents of the original English language file and the contents of the same file type (there are nine of these) in your chosen language.  You click on the message text shown below the English version.  This opens a new window where you can modify the text.  You can save the changes or abandon them.');
	break;

case 'edit_name':
	$title=i18n::translate('Edit Name');
	$text=i18n::translate('This is the most important field in a person\'s Name record.<br /><br />This field should be filled automatically as the other fields are filled in, but it is provided so that you can edit the information according to your personal preference.<br /><br />The name in this field should be entered according to the GEDCOM 5.5.1 standards with the surname surrounded by forward slashes "/".  As an example, the name "John Robert Finlay Jr." should be entered like this: "John Robert /Finlay/ Jr.".');
	break;

case 'edit_privacy':
	$title=i18n::translate('Edit Privacy');
	$text=i18n::translate('On this page you can make all the Privacy settings for the selected GEDCOM.<br /><br />You can check under the page title to see that you are editing the correct privacy file.  It is displayed like this: (path/nameofyourgedcom_priv.php)<br /><br />If you need more settings, you can make changes to the privacy file manually. You can read more about this on the PhpGedView web site.');
	break;

case 'edit_raw_gedcom':
	$title=i18n::translate('edit_raw_gedcom');
	$text=i18n::translate('When you click this link, a new window will open containing the raw GEDCOM data of the details on this page.<br /><br />Here you can edit the GEDCOM data directly. Be sure to enter valid GEDCOM 5.5.1 data, as no further validity checks will be done.  The changed or added data will be displayed in PhpGedView as "changes", and have to be accepted by a user with Accept rights.');
	break;

case 'edit_sex':
	$title=i18n::translate('Edit Gender');
	$text=i18n::translate('Choose the appropriate gender from the drop-down list.  The <b>unknown</b> option indicates that the gender is unknown.');
	break;

case 'edit_suffix':
	$title=i18n::translate('edit_suffix');
	$text=i18n::translate('In this optional field you should enter the name suffix for the person.  Examples of name suffixes are "Sr.", "Jr.", and "III".');
	break;

case 'edit_surname':
	$title=i18n::translate('edit_surname');
	$text=i18n::translate('In this field you should enter the surname for the person.  As an example, in the name "John Robert Finlay", the surname that should be entered here is "Finlay"<br /><br />Individuals with multiple surnames, common in Spain and Portugal, should separate the surnames with a comma.  This indicates that the person is to be listed under each of the names.  For example, the surname "Cortes,Vega" will be listed under both <b>C</b> and <b>V</b>, whereas the surname "Cortes Vega" will only be listed under <b>C</b>.');
	break;

case 'editlang':
	$title=i18n::translate('Edit');
	$text=i18n::translate('Edit message from language file.');
	break;

case 'edituser_change_lang':
	$title=i18n::translate('edituser_change_lang');
	$text=i18n::translate('Here you can change the language in which PhpGedview will display all its pages and messages after you have logged in.<br /><br />When you first access the site, PhpGedView assumes that you want to see everything in the language configured as the Preferred Language in your browser.  If that assumption is incorrect, you would override it here.  For example, your browser might be set to English because that is the most prevalent language on the Internet.  However, for genealogical purposes, you would prefer to see everything in Finnish or Hebrew.  Here\'s where you do that.<br /><br />The administrator controls what language choices are available to you.  If your preference isn\'t listed, you need to contact the administrator.<br /><br />Please remember that PhpGedView is very much a project staffed by an international team of unpaid volunteers.  Experts come and go.  Consequently, support for languages other than English is sometimes not as good as it should be.<br /><br />If you see something that has not been translated, has been translated incorrectly, or could be phrased better, let your administrator know.  The administrator will know how to get in touch with the PhpGedView developer team to have your concerns addressed.  Better still, volunteer some of your time.  We can use the help.');
	break;

case 'edituser_conf_password':
	$title=i18n::translate('edituser_conf_password');
	$text=i18n::translate('If you have changed your password, you need to confirm it as well.  This is just to make sure that you did not make a typing error in the password field.<br /><br />If the password and its confirmation are not identical, you will get a suitable error message.  You will have to re-type both the original password and its confirmation.');
	break;

case 'edituser_contact_meth':
	$title=i18n::translate('edituser_contact_meth');
	$text=i18n::translate('PhpGedView has several different contact methods.  The administrator determines which method will be used to contact him.  You have control over the method to be used to contact <u>you</u>.  Depending on site configuration, some of the listed methods may not be available to you.');
	break;

case 'edituser_email':
	$title=i18n::translate('edituser_email');
	$text=i18n::translate('Your correct email address is, important to us to keep in touch with you.<br /><br />If you get a new email address, as usually happens when you change your Internet provider, please do not forget to change the address here as well.  You won\'t get a confirmation message from this site when you change this address, but any future messages directed to you will go this new address.');
	break;

case 'edituser_firstname':
	$title=i18n::translate('edituser_firstname');
	$text=i18n::translate('In this box you can change your first name.  This is the name that other users see when you are logged in.<br /><br />Although the choice of what to put into this field is yours, you should inform the administrator when you change it.  When others see an unknown person on-line, they might wonder and ask questions.  The admin can find out without having received your notice, but you should save him that unnecessary work.');
	break;

case 'edituser_gedcomid':
	$title=i18n::translate('edituser_gedcomid');
	$text=i18n::translate('This is an identification number that links you to your own data in the database.<br /><br />You cannot change this ID; it\'s set by the administrator.  If you think that this ID is not correct, you should contact the administrator to have it changed.');
	break;

case 'edituser_lastname':
	$title=i18n::translate('edituser_lastname');
	$text=i18n::translate('In this box you can change your last name.  This is the name that other users see when you are logged in.<br /><br />Although the choice of what to put into this field is yours, you should inform the administrator when you change it.  When others see an unknown person on-line, they might wonder and ask questions.  The admin can find out without having received your notice, but you should save him that unnecessary work.');
	break;

case 'edituser_my_account':
	$title=i18n::translate('edituser_my_account');
	$text=i18n::translate('Here you can change your settings and preferences.<br /><br />You can change your user name, full name, password, language, email address, theme of the site, and preferred contact method.<br /><br />You cannot change the GEDCOM INDI record ID; that has to be done by an administrator.');
	break;

case 'edituser_password':
	$title=i18n::translate('edituser_password');
	$text=i18n::translate('It is a good practice to change your password regularly.  You have to keep in mind that anyone who knows your user name and your password will have access to your data.<br /><br />Make the password at least 6 characters long, the longer the better. You may use uppercase and lower case letters with or without diacritical marks, numbers, dash (-), and underscore (_). Do <u>not</u> use punctuation marks or spaces.  Use a combination of upper and lower case, numbers, and other characters. For example: <b>5Z_q$P4=r9</b>.<br /><br />Like the user name, the password is <u>case sensitive</u>.  That means that <b>Secret.Password!#13</b> is not the same as <b>secret.password!#13</b> or <b>SECRET.PASSWORD!#13</b>.');
	break;

case 'edituser_rootid':
	$title=i18n::translate('edituser_rootid');
	$text=i18n::translate('This is the starting (Root) person of all your charts.<br /><br />If, for example, you were to click the link to the Pedigree, you would see this root person in the leftmost box.  This root person does not have to be you; you can start with any person (your grandfather or your mother\'s aunt, for instance), as long you have the rights to see that person.<br /><br />The changes the default Root person for most charts.  You can change the Root person on many charts, but that is just for that page at that particular invocation.');
	break;

case 'edituser_user_default_tab':
	$title=i18n::translate('edituser_user_default_tab');
	$text=i18n::translate('This setting allows you to specify which tab is opened automatically when you access the Individual Information page.');
	break;

case 'edituser_user_theme':
	$title=i18n::translate('edituser_user_theme');
	$text=i18n::translate('This site can have several different looks or appearances.  Other programs may call them "skins", but here they\'re "themes".<br /><br />Every theme will display the same data, but its presentation or even its location on the screen may vary.  This is like putting a picture into a different frame and hanging it in a different room as well. The picture does not change, but the way you look at it is completely different.<br /><br />Just give it a try. Set it to another theme. Look at it, try another. Change back to the one that suits you the best. Whenever you log in, you will see the theme you last used; you don\'t even have to get to this configuration page to change your preferred theme.');
	break;

case 'edituser_username':
	$title=i18n::translate('edituser_username');
	$text=i18n::translate('In this box you can change your user name.  If you no longer like your user name or if have other reasons to change it, you can do so using this form.<br /><br />The username is <u>case sensitive</u>. That means that <b>John</b> is not the same as <b>john</b> or <b>JOHN</b>.<br /><br />You should <u>only</u> use characters from the alphabets that PhpGedView supports.  You may use uppercase and lower case letters with or without diacritical marks, numbers, dash (-), and underscore (_). Do <u>not</u> use punctuation marks or spaces.');
	break;

case 'empty_cart':
	$title=i18n::translate('Empty Cart');
	$text=i18n::translate('When you click this link your Clippings Cart will be totally emptied.<br /><br />If you don\'t want to remove all persons, families, etc. from the Clippings Cart, you can remove items individually by clicking the <b>Remove</b> link in the Name boxes.  There is <u>no</u> confirmation dialog when you click either of these links;  the requested deletion takes place immediately.');
	break;

case 'empty_lines_detected':
	$title=i18n::translate('Empty lines were detected in your GEDCOM file.    On cleanup, these empty lines will be removed.');
	$text=i18n::translate('PhpGedView has detected that there are empty lines in your input file. These lines may cause errors and will be removed from the file before it is imported.');
	break;

case 'end_admin':
	$title=i18n::translate('end_admin');
	$text=i18n::translate('--- End extra Admin Info ---');
	break;

case 'export_lang_utility':
	$title=i18n::translate('Language File Export Utility');
	$text=i18n::translate('You can create documentation for administrators.<br /><br />This utility will produce an HTML version of the Configuration Help file in the selected language.  You can use your browser to look at this file, which is formatted for printing.');
	break;

case 'fambook_descent':
	$title=i18n::translate('fambook_descent');
	$text=i18n::translate('This value determines the number of descendant generations of the root person that will be printed in Hourglass format.');
	break;

case 'fan_style':
	$title=i18n::translate('fan_style');
	$text=i18n::translate('This option controls the appearance of the diagram.<ul><li><b>1/2</b><br />Half circle 180&deg; diagram</li><li><b>3/4</b><br />Three-quarter 270&deg; diagram, sometimes called <i>Angel wing</i></li><li><b>4/4</b><br />Full circle 360&deg; diagram</li></ul>');
	break;

case 'fan_width':
	$title=i18n::translate('Width');
	$text=i18n::translate('Here you can change the diagram width from 50% to 300%.  At 100% the output image is about 640 pixels wide.');
	break;

case 'file_to_edit':
	$title=i18n::translate('Language file type to edit');
	$text=i18n::translate('PhpGedView has implemented support for many different languages.  This has been achieved by keeping all text that is visible to users in files completely separate from the main program.  There is a set of eight files for each supported language, and the various texts have been separated into one of these files according to function.  <b>Not all language files need to be present.</b>  When a given text is not yet available in translated form, PhpGedView will always use the English version.<br /><br />The files in each language set are:<br /><ul><li><b><i>admin.xx.php</i></b>&nbsp;&nbsp;This file contains terms and common expressions for use during the administration of PhpGedView and the genealogical databases.<br /><br /></li><li><b><i>configure_help.xx.php</i></b>&nbsp;&nbsp;This file contains Help text for use during configuration of PhpGedView.  The Help text is not intended to be viewed by ordinary users.<br /><br /></li><li><b><i>countries.xx.php</i></b>&nbsp;&nbsp;This is a list of country names, taken from the Web site of the Statistics Division, United Nations Department of Economic and Social Affairs.  This is the relevant <a href="http://unstats.un.org/unsd/methods/m49/m49alpha.htm" target="_blank"><b>link</b></a> to the English list.  The list is available in either English or French.<br /><br /></li><li><b><i>editor.xx.php</i></b>&nbsp;&nbsp;This file contains terms and common expressions for use during the editing of entries in the genealogical databases.<br /><br /></li><li><b><i>facts.xx.php</i></b>&nbsp;&nbsp;This file contains the textual equivalents of the GEDCOM Fact codes found in the GEDCOM 5.5.1 Standard.  It also contains additional Fact codes not found in the Standard but used by various genealogy programs.<br /><br />An English copy of the <a href="http://www.phpgedview.net/ged551-5.pdf" target="_blank"><b>GEDCOM 5.5.1 Standard</b></a> can be downloaded in PDF (Portable Document Format).<br /><br /></li><li><b><i>faqlist.xx.php</i></b>&nbsp;&nbsp;This file is a set of <b>f</b>requently <b>a</b>sked <b>q</b>uestions that have been collected by the PhpGedView development team.  Each FAQ has two entries in this file.  One entry is the FAQ heading (usually the question), and the other is the FAQ body (usually the answer).  Replacements for the <b><i>faqlist.xx.php</i></b> files, which are updated frequently, may be downloaded from the PhpGedView home site.<br /><br />The administrator can use the FAQs in this file to build an FAQ list that is specific to his site.<br /><br /></li><li><b><i>help_text.xx.php</i></b>&nbsp;&nbsp;This file contains Help text for ordinary users.  Some Help topics in this file address the needs of administrators, and are hidden from users who do not have Admin rights.<br /><br /></li><li><b><i>lang.xx.php</i></b>&nbsp;&nbsp;Many terms and common expressions are found in this file.</li></ul><br />PhpGedView also supports an optional ninth language file, <b><i>extra.xx.php</i></b>.  This file is always loaded after all the others and provides a means whereby a site administrator can override or alter any standard text in the selected language.  It can also be used to provide a title for the genealogical databases that varies according to the currently active language.<br /><br />The contents of this additional file are completely up to the site administrator;  this file will <b>never</b> be distributed with any version of PhpGedView.  The administrator should never make changes to the standard language files;  all local changes should be concentrated in this optional file.');
	break;

case 'file_type':
	$title=i18n::translate('file_type');
	$text=i18n::translate('Choose the format in which the database export is to be created.  Your choice depends on the requirements and capabilities of the program into which you intend to import the newly downloaded file.  You can choose:<ul><li>~GEDCOM file~<br />A quote from the Introduction to the GEDCOM 5.5.1 Standard:<div class="list_value_wrap">GEDCOM was developed by the Family History Department of The Church of Jesus Christ of Latter-day Saints (LDS Church) to provide a flexible, uniform format for exchanging computerized genealogical data.&nbsp; GEDCOM is an acronym for <i><b>GE</b></i>nealogical <i><b>D</b></i>ata <i><b>Com</b></i>munication.&nbsp; Its purpose is to foster the sharing of genealogical information and the development of a wide range of inter-operable software products to assist genealogists, historians, and other researchers.</div><br />A copy of the GEDCOM 5.5.1 <u>draft</u> Standard, to which PhpGedView adheres, can be downloaded in PDF format here:&nbsp; <a href="http://www.phpgedview.net/ged551-5.pdf" target="_blank">GEDCOM 5.5.1 Standard</a>  This Standard is only available in English.<br /><br />The GEDCOM file contains all the information about the family. All facts, dates, events, etc. are stored here. GEDCOM files have to follow strict rules because they must be exchangeable between many programs, independent of platforms or operating systems.<br /><br /></li><li>~GRAMPS XML Database file~<br />A quote from GRAMPS Project: <div class="list_value_wrap">GRAMPS helps you track your family tree. It allows you to store, edit, and research genealogical data. GRAMPS attempts to provide all of the common capabilities of other genealogical programs, but, more importantly, to provide an additional capability of integration not common to these programs. This is the ability to input any bits and pieces of information directly into GRAMPS and rearrange/manipulate any/all data events in the entire data base (in any order or sequence) to assist the user in doing research, analysis and correlation with the potential of filling relationship gaps.</div><br />A copy of the GRAMPS XML format v1.1.0 <a href="http://www.gramps-project.org/xml/1.1.0/" target="_blank">can be found here</a> in both RELAX NG Schema format and DTD format.<br /><br />For more information about the GRAMPS Project visit <a href="http://gramps-project.org/" target="_blank">http://gramps-project.org/</a></li></ul>');
	break;

case 'find_media':
	$title=i18n::translate('Find Media');
	$text=i18n::translate('This allows you to search the file structure to find the media item you wish to link to.');
	break;

case 'firstname_f':
	$title=i18n::translate('firstname_f');
	$text=i18n::translate('The family name you have chosen has more than #GLOBALS[SUBLIST_TRIGGER_F]# individuals.<br /><br />To help you find the family you want, the list has been broken into smaller lists according to the first letter of the person\'s given name.  This alphabetical sub-index works the same as the alphabetical index for names.<br /><ul><li>Click a letter to see all of the first names which start with that letter.</li><li>Choose <b>(unknown)</b> to list all of the people with unknown first names.</li><li>Choosing <b>ALL</b> will display a list of all families with the previously chosen surname.</li></ul>Because there are many names, it may take a long time for this list to appear on your screen.');
	break;

case 'firstname_i':
	$title=i18n::translate('firstname_i');
	$text=i18n::translate('The surname you have chosen has more than #GLOBALS[SUBLIST_TRIGGER_I]# individuals.<br /><br />To help you find the person you want, the list has been broken into smaller lists according to the first letter of the person\'s given name.  This alphabetical sub-index works the same as the alphabetical index for surnames.<br /><ul><li>Click a letter to see all of the first names which start with that letter.</li><li>Choose <b>(unknown)</b> to list all of the persons with unknown first names.</li><li>Choosing <b>ALL</b> will display a list of all persons with the previously chosen surname.</li></ul>Because there are many names, it may take a long time for this list to appear on your screen.');
	break;

case 'flagsfile':
	$title=i18n::translate('Flag file');
	$text=i18n::translate('Name of the national flag image file for the selected language.<br /><br />Images for many countries are available from <a href="http://w3f.com/gifs/index.html"><b>The WWWeb Factory</b></a> site.<br /><br />When you find a flag image you like, right-click on it and save the image in a temporary folder. Next, open the saved image with a suitable image editor (IrfanView is recommended for Windows systems), reduce its size 50x32 pixels, which is the normal size used in PhpGedView, and then save it as a GIF file to the <i><b>images/flags</b></i> folder.<br /><br />Although you can use any name you wish, you should select a name consistent with the two-letter language shortcut.  For example, Croatian is represented by the two-letter code <i><b>hr</b></i>, so the Croatian flag would normally be named <i><b>hr.gif</b></i>.');
	break;

case 'follow_spouse':
	$title=i18n::translate('Check relationships by marriage');
	$text=i18n::translate('With this check box <b>un</b>checked, the relationships are only checked between blood relatives.  With this check box checked, relationships by marriage are also checked.  You will probably find more relationships by leaving this box checked.');
	break;

case 'ged_filter_description':
	$title=i18n::translate('Search option text');
	$text=i18n::translate('This option lets you search the text associated with configuration options.<br /><br />As you type letters, the search will find all configuration options that contain that letter sequence.  The search becomes more precise as you type more letters.');
	break;

case 'gedcom_configfile':
	$title=i18n::translate('gedcom_configfile');
	$text=i18n::translate('This is the file where all the basic settings related to the genealogical database are stored.  There is a separate file for each such database.<br /><br />You will find the path and name of each configuration file in the <b>Current GEDCOMs</b> table on the <b>GEDCOM Administration</b> page.');
	break;

case 'gedcom_news_archive':
	$title=i18n::translate('View archive');
	$text=i18n::translate('To reduce the height of the News block, the administrator has hidden some articles.  You can reveal these hidden articles by clicking the <b>View archive</b> link.');
	break;

case 'gedcom_news_flag':
	$title=i18n::translate('Limit:');
	$text=i18n::translate('Enter the limiting value here.<br /><br />If you have opted to limit the News article display according to age, any article older than the number of days entered here will be hidden from view.  If you have opted to limit the News article display by number, only the specified number of recent articles, ordered by age, will be shown.  The remaining articles will be hidden from view.<br /><br />Zeros entered here will disable the limit, causing all News articles to be shown.');
	break;

case 'gedcom_news_limit':
	$title=i18n::translate('Limit display by:');
	$text=i18n::translate('You can limit the number of News articles displayed, thereby reducing the height of the GEDCOM News block.<br /><br />This option determines whether any limits should be applied or whether the limit should be according to the age of the article or according to the number of articles.');
	break;

case 'gedcom_path':
	$title=i18n::translate('Path and name of GEDCOM on server');
	$text=i18n::translate('There are two ways of importing your GEDCOM file into PhpGedView. They are:<ol><li>FTP the file to the server</li><li>Upload within PhpGedView</li></ol>When your file already exists on the server, you engage the <i>Add GEDCOM</i> procedure and fill in the path and name of your GEDCOM file as they exist on the server. The name can be with or without extension. If no extension is given, .ged will be assumed. The path is optional. If no path is given, the value of the <i>Index file directory</i> option, as set in your PhpGedView site configuration, will be used.  Please note that on most servers, file and path names are case sensitive.<br /><br />When you engage the <i>Upload GEDCOM</i> procedure built into PhpGedView, you can use the <b>Browse</b> button to locate the desired file on your local computer. This can be a regular GEDCOM file or a ZIP file containing the GEDCOM file. PhpGedView will automatically extract and then use the GEDCOM file contained in that ZIP file.<br /><br />When uploading a file it is possible to specify an alternative path and/or filename to save it under on the server.<br /><br />See the <a href="readme.txt">Readme.txt</a> file for more information.');
	break;

case 'gedcom_title':
	$title=i18n::translate('GEDCOM title');
	$text=i18n::translate('Enter a descriptive title to be displayed when users are choosing among GEDCOM datasets at your site.');
	break;

case 'gen_missing_thumbs':
	$title=i18n::translate('Create missing thumbnails');
	$text=i18n::translate('This option will generate thumbnails for all files in the current directory which don\'t already have a thumbnail.  This is much more convenient than clicking the <b>Create thumbnail</b> link for each such file.<br /><br />If you wish to retain control over which files should have corresponding thumbnails, you should not use this option.  Instead, click the appropriate <b>Create thumbnail</b> links.');
	break;

case 'general_privacy':
	$title=i18n::translate('General Privacy settings');
	$text=i18n::translate('You can have different Privacy settings for each GEDCOM on your PhpGedView web site.  Check under the page title whether you are editing the correct GEDCOM.<br /><br />You can override these general settings by using the other Privacy forms on the Edit GEDCOM privacy settings page.<br /><br /><b>More help</b><br />More help is available by clicking the <b>?</b> next to items on the page.');
	break;

case 'generate_thumb':
	$title=i18n::translate('generate_thumb');
	$text=i18n::translate('Your system can generate thumbnails for certain types of images automatically.  There may be support for BMP, GIF, JPG, and PNG files.  The types that your system supports are listed beside the checkbox.<br /><br />By clicking this checkbox, you signal the system that you are uploading images of this type and that you want it to try to generate thumbnails for them.  Leave the box unchecked if you want to provide your own thumbnails.');
	break;

case 'global_facts':
	$title=i18n::translate('Global Fact Privacy settings');
	$text=i18n::translate('These settings define facts on a global level that should be hidden for all individuals in the GEDCOM.  This only applies to level 1 fact records such as BIRT or DEAT that will appear with their own headings on the personal facts and details tab of the individual page.<br /><ul><li>The <b>Name of fact</b> element determines which fact should be hidden.</li><li>The <b>Choice</b> element specifies the fact itself or related details.</li><li>The <b>Show to?</b> element determines at what access level the fact is shown.</li></ul><br />This feature is meant to hide certain facts, identified by GEDCOM tags, for all individuals alive or dead. By default the SSN tag is hidden to public users. This is to prevent people from stealing social security numbers and committing identity theft of dead persons.  This is probably mostly relevant for the USA.<br /><br />If you wanted to hide all marriages from public users in your GEDCOM you could set:<br /><br /><b>Name of fact</b> (MARR) - Marriage<br /><b>Choice</b> "Show fact"<br /><b>Show to?</b> "Show only to authenticated users"<br /><br /><b>Name of fact</b> (MARR) - Marriage<br /><b>Choice</b> "Show fact details"<br /><b>Show to?</b> "Show only to authenticated users"<br /><br />These settings would hide marriages and related details to everyone who wasn\'t an admin.<br /><br />Unlike all other settings, in <b>Edit existing settings for Global Fact Privacy</b> you can hide facts even from admin users. Unwanted facts are completely suppressed.');
	break;

case 'google_chart_surname':
	$title=i18n::translate('google_chart_surname');
	$text=i18n::translate('The number of occurrences of the specified name will be shown on the map. If you leave this field empty, the most common surname will be used.');
	break;

case 'google_translate':
	$title=i18n::translate('Google&reg; Translate');
	$text=i18n::translate('This tool uses Google&reg; Translate to provide hints to the translator.  It is <b>not</b> intended to replace translations done by a person who fully understands the source and the target languages.    Please be aware also that the Google&reg; translate tool is neither reliable nor always able to perform a translation.<br /><br />When a language text has already been translated, there will be no link to Google&reg; Translate.<br /><br />For each text where you want to use the Google&reg; Translate tool, you click the <b>Google&reg; Translate</b> link.  After the page is reloaded, you should correct the mistranslated entries by hand. <b>There is no guarantee that the Google&reg; translation will be correct.</b> You will need to correct special characters such as #, <, >, / and HTML tags as well as references to other language or global variables in the translated text so that the result matches the original untranslated text.  Note that Google&reg; Translate will add a space after each HTML tag.  You will definitely need to remove these extra spaces.<br /><br />When you are satisfied with the result, click <b>Commit</b> to allow that result to be saved.  If you do not click <b>Commit</b>, the changes will be accumulated until you click the <b>Commit Google&reg; translated changes</b> link at the bottom of the page to commit all saved texts at once.');
	break;

case 'header_favorites':
	$title=i18n::translate('header_favorites');
	$text=i18n::translate('The Favorites drop-down list shows the favorites that you have selected on your personalized Portal page.  It also shows the favorites that the site administrator has selected for the currently active GEDCOM.  Clicking on one of the favorites entries will take you directly to the Individual Information page of that person.<br /><br />More help about adding Favorites is available in your personalized Portal page.');
	break;

case 'header_general':
	$title=i18n::translate('header_general');
	$text=i18n::translate('<div class="name_head"><center><b>GENERAL INFORMATION</b></center></div>');
	break;

case 'header':
	$title=i18n::translate('Header');
	$text=i18n::translate('<div class="name_head"><center><b>HEADER AREA</b></center></div><br />The header is shown at the top of every page.  The header contains some useful links that you can use throughout the site.<br /><br />Since this site can have a different look depending on the selected <a href="#def_theme">theme</a>, headers can be affected and links may vary.<br /><br />The links that you might find are:<ul><li><a href="#header_search"><b>Search Box</b></a></li><li><a href="#header_lang_select"><b>Language Selector</b></a></li><li><a href="#header_user_links"><b>User Links</b></a></li><li><a href="#header_favorites"><b>Favorites</b></a></li><li><a href="#header_theme"><b>Change Theme</b></a></li></ul>');
	break;

case 'header_lang_select':
	$title=i18n::translate('header_lang_select');
	$text=i18n::translate('One of the most important features of PhpGedView is that multiple languages are supported.<br /><br />The language in which PhpGedView displays all pages is determined automatically according to the Preferred Language setting of the browser.  However, the site administrator may have limited the availability of certain languages.<br /><br />Depending on site configuration, you may be able to change the language of PhpGedView by selecting a more suitable language from a drop-down list or by clicking on a flag icon.  If you are a registered user, you can configure PhpGedView to switch to your preferred language after you login, regardless of what your browser is set to.');
	break;

case 'header_search':
	$title=i18n::translate('header_search');
	$text=i18n::translate('This Search box is small but powerful.  You can have PhpGedView search almost anything for you. When you click the <b>></b> or <b>Search</b> button, you will be linked to the Search page to see the results of your search.  You will find extensive help about searching options on the Search page.');
	break;

case 'header_theme':
	$title=i18n::translate('header_theme');
	$text=i18n::translate('When enabled by the site administrator, the Change Theme drop-down list shows you a list of the themes that you can use to view the site.<br /><br />You can change the appearance of the site by selecting a theme from the drop-down list.  If you are logged in, it will also change your user theme to the one that you have chosen so that your next login will automatically select that same theme.');
	break;

case 'header_user_links':
	$title=i18n::translate('header_user_links');
	$text=i18n::translate('The User Links is a small block with useful links that can be found in the same place on every page.  The location of these links varies according to the theme currently in effect.<br /><br />When not logged in, you will only see the <b>Login</b> link.  After you have logged in, you will see:<ul><li><b>Logged in as (your user name)</b>. Clicking that link will take you to your Account page.</li><li>Click <b>Log out</b> to Log out.</li><li>If you have admin rights, you will also see <b>Admin</b>. Clicking this link will take you directly to the main Administration page.</li></ul>');
	break;

case 'help_HS':
	$title=i18n::translate('help_HS');
	$text=i18n::translate('<dl><dt><b>Search Help Text</b></dt><dd>You can search PhpGedView\'s Help system.  The Search Help Text feature gives you a high degree of control over the way the search functions; you should be able to find what you are looking for easily.</dd></dl>');
	break;

case 'help_content':
	$title=i18n::translate('help_content');
	$text=i18n::translate('<dl><dt><b>Help Contents</b></dt><dd>When you click this menu item, you will get a Help page that displays an index of the major Help topics.  The amount of Help information available will be increased as time permits.</dd></dl>');
	break;

case 'help_contents_head':
	$title=i18n::translate('help_contents_head');
	$text=i18n::translate('<b>HELP CONTENTS</b>');
	break;

case 'help_faq':
	$title=i18n::translate('help_faq');
	$text=i18n::translate('<dl><dt><b>FAQ List</b></dt><dd>The FAQ (Frequently Asked Questions) page can contain an overview or a list of questions and answers on the use of this genealogy site.<br /><br />The use to which the FAQ page is put is entirely up to the site administrator. The site administrator controls the content of each item and also the order in which the items are shown on the page.</dd></dl>');
	break;

case 'help':
	$title=i18n::translate('help');
	$text=i18n::translate('Of course, it would be ideal to create a program so simple and easy to use that it doesn\'t need any explanation at all; it should be as simple as reading a book.<br /><br />Although PhpGedView is very complicated, you should not notice that as you use it; almost everything can be used without explanation.  But, since we may have a lot of visitors and users who are not very experienced with the use of a computer or with the Internet, we offer you some help at certain places.<br /><br />You will find the following items in the Help menu:');
	break;

case 'help_page':
	$title=i18n::translate('help_page');
	$text=i18n::translate('<dl><dt><b>Help with this Page</b></dt><dd>For all pages there is a general "Page Help" available.  You can click this item in the menu to get "Page Help", where you will be informed about the items on that very page.<br /><br />Page Help is often brief.  If you need more help or information about a certain item on the page than Page Help provides you can use the "Contextual Help" feature.</dd></dl>');
	break;

case 'help_qm':
	$title=i18n::translate('help_qm');
	$text=i18n::translate('<dl><dt><b>Hide / Show Contextual Help</b></dt><dd>This last menu item could be the most useful for you. Clicking this link will either switch on or off the "Contextual Help".<br /><br />With Contextual Help switched on, you may find a Question Mark or similar icon beside some links, drop-down boxes, or buttons. When you click this icon, a Help screen will pop up.  This Help screen contains information about that object.<br /><br />Of course, when you click "Hide Contextual Help", all the Question Marks or icons will disappear until you click "Show...." again.</dd></dl>');
	break;

case 'hide_context':
	$title=i18n::translate('hide_context');
	$text=i18n::translate('Hide Contextual Help');
	break;

case 'hide_translated':
	$title=i18n::translate('Hide translated');
	$text=i18n::translate('If set to <b>Yes</b> you will only see those messages of your selected language file which have not been translated.  This means the new message does <u>not</u> appear in the target language file.<br /><br />Some language files contain English text for various untranslated messages.  This routine cannot identify these untranslated items, since they already are present in the target file.  The assumption is, "If the message is present in the target file, it must have been translated."');
	break;

case 'hs_inallhelp':
	$title=i18n::translate('hs_inallhelp');
	$text=i18n::translate('All text');
	break;

case 'hs_intruehelp':
	$title=i18n::translate('hs_intruehelp');
	$text=i18n::translate('Help text only');
	break;

case 'hs_title':
	$title=i18n::translate('Search Help Text');
	$text=i18n::translate('<center>~Search Help Text~</center><br />You can search PhpGedView\'s Help system.  The Search Help Text feature gives you a high degree of control over the way the search functions; you should be able to find what you are looking for easily.<br /><br />~Search for~<br />You enter the words or the phrase you wish to find.<br /><br />The search does not pay attention to the case (upper or lower) of the search terms or the text being examined.  This means that if you search for <b>Individual</b>, you will find text containing <b>Individual</b>, <b>individual</b>, or <b>INDIVIDUAL</b>.  You will also find text containing <b>individuals</b>, etc. since the search is looking for sequences of characters rather than words.<br /><br />You can have the search look for several words at once.  Enter all of the words, separating each of them by a space, like this: <b>individual&nbsp;family&nbsp;child</b>.  When more than one word is entered, the meaning of what you have typed is clarified in the Search type field.<br /><br /><br />~Search type~<br />You clarify the meaning of what you have entered into the Search for field by selecting among the possibilities presented here.<br /><dl><dt><b>Any word</b></dt><dd>If you have entered <b>individual&nbsp;family&nbsp;child</b>, this option will find Help text that contains one of the words listed.  The order of the words doesn\'t matter.  The meaning of the search is: "Find Help text containing <b>individual</b> <u>or</u> <b>family</b> <u>or</u> <b>child</b>".</dd><dt><b>All words</b></dt><dd>If you have entered <b>individual&nbsp;family&nbsp;child</b>, this option will find Help text that contains all of the words listed.  The order of the words doesn\'t matter.  The meaning of the search is: "Find Help text containing <b>individual</b> <u>and</u> <b>family</b> <u>and</u> <b>child</b>".</dd><dt><b>Exact phrase</b></dt><dd>If you have entered <b>individual&nbsp;family&nbsp;child</b>, this option will find Help text that contains all of the words listed in the order given.  The meaning of the search is: "Find Help text containing the words <b>individual&nbsp;family&nbsp;child</b> in exactly that order with no other words or characters between".  You probably won\'t find this particular phrase in any Help text.<br /><br />There are a few limitations on this type of search.  Certain special characters such as <b>&quot; &lt; &gt;</b> etc. are contained within the Help text in symbolic form and won\'t be found if they form part of the text you enter.  Some Help text contains a special kind of Space character represented by <b>&amp;nbsp;</b> and you won\'t find phrases containing this character.</dd></dl><br /><br />~Search in~<br />You determine the scope of the search here.<br /><br />Administrators have the choice of searching User Help or Configuration Help or both.  Users do not have this choice; because they do not have access to any configuration features, they can only search the User Help file.<br /><br />The Help files contain not only Help text but also certain text strings used to build input forms and other material.  This option lets you control whether the entire Help file should be examined or whether only the Help text should be looked at.');
	break;

case 'import_gedcom':
	$title=i18n::translate('import_gedcom');
	$text=i18n::translate('In most cases importing of an externally created GEDCOM file is one step in procedures that result in bulk changes to the genealogical database.<br /><br />These steps are in a logical sequence and need to be completed in the prescribed order so that the genealogical database is usable.<br /><br />If, for some reason, you did not complete these steps in the correct order, you will see a <u>warning</u> message that the GEDCOM is not yet imported. To correct the problem, click the <b>Import GEDCOM</b> link to import the file.<br /><br />Existing GEDCOM configuration settings will not change when you re-import a GEDCOM.  Existing data will, however, be overwritten.');
	break;

case 'import_options':
	$title=i18n::translate('Import Options');
	$text=i18n::translate('You can choose additional options to be used when importing the GEDCOM.');
	break;

case 'include_media':
	$title=i18n::translate('Include Media (automatically zips files)');
	$text=i18n::translate('Select this option to include the media files associated with the records in your clippings cart.  Choosing this option will automatically zip the files during download.');
	break;

case 'index_add_favorites':
	$title=i18n::translate('index_add_favorites');
	$text=i18n::translate('This form allows you to add a new favorite item to your list of favorites.<br /><br />You must enter either an ID for the person, family, or source you want to store as a favorite, or you must enter a URL and a title.  The Note field is optional and can be used to describe the favorite.  Anything entered in the Note field will be displayed in the Favorites block after the item.');
	break;

case 'index_charts':
	$title=i18n::translate('index_charts');
	$text=i18n::translate('This block allows a pedigree, descendancy, or hourglass chart to appear on the Welcome or the MyGedView page.  Because of space limitations, the charts should be placed only on the left side of the page.<br /><br />When this block appears on the Welcome page, the root person and the type of chart to be displayed are determined by the administrator.  When this block appears on the user\'s personalized MyGedView page, these options are determined by the user.<br /><br />The behavior of these charts is identical to their behavior when they are called up from the menus.  Click on the box of a person to see more details about them.');
	break;

case 'index_common_given_names':
	$title=i18n::translate('index_common_given_names');
	$text=i18n::translate('This block displays a list of frequently occurring given names from this database. You can configure how many given names should appear in the list.');
	break;

case 'index_common_names':
	$title=i18n::translate('index_common_names');
	$text=i18n::translate('This block displays a list of frequently occurring surnames from this database. A surname must occur at least #COMMON_NAMES_THRESHOLD# times before it will appear in this list.  The administrator has control over this threshold.<br /><br />When you click on a surname in this list, you will be taken to the Individuals, where you will get more details about that name.');
	break;

case 'index_events':
	$title=i18n::translate('index_events');
	$text=i18n::translate('This block shows you anniversaries of events that are coming up in the near future.<br /><br />The administrator determines how far ahead the block will look.  You can further refine the block\'s display of upcoming events through several configuration options.');
	break;

case 'index_favorites':
	$title=i18n::translate('index_favorites');
	$text=i18n::translate('The GEDCOM Favorites block is much the same as the "My Favorites" block of the MyGedView Portal page. Unlike the Portal page configuration, only the administrator or a user with Admin rights can change the list of favorites in this block.<br /><br />The purpose of the GEDCOM Favorites block is to draw the visitor\'s attention to persons of special interest.  This GEDCOM\'s favorites are available for selection from a drop-down list in the header on every page.<br /><br />When you click on one of the listed site favorites, you will be taken to the Individual Information page of that person.');
	break;

case 'index_gedcom_news_adm':
	$title=i18n::translate('index_gedcom_news_adm');
	$text=i18n::translate('The GEDCOM News text allows the use of <b>HTML tags</b> and <b>HTML entities</b>.  HTML should not be used in News titles.<br /><br />Be sure to always use both start and end tags.  It may help to have an understanding of HTML appropriate for a web site administrator. This program uses <b>Cascading Style Sheets (CSS)</b> as well. A different CSS is implemented for each theme.  You can use classes from these style sheets to control the appearance of your messages.<br /><br />If you need more help with this, the PhpGedView web site has some examples of how to use these tags in your GEDCOM News block.<br /><br />As with the FAQ list, GEDCOM News titles and News text allow embedded references to $pgv_lang, $factarray, and $GLOBALS variables to provide complete flexibility in the creation of News items that are sensitive to the currently active language.<br /><br />The following description, taken from the Help text for the FAQ list, is equally applicable to GEDCOM News items.<br /><br />HTML entities are a very easy way to add special characters to your FAQ titles and text.  You can use symbolic names, decimal numbers, or hexadecimal numbers.  A complete list of HTML entities, their coding, and their representation by your browser can be found here:  <a href="http://htmlhelp.com/reference/html40/entities/" target="_blank">HTML entity lists</a><br /><br />On occasion, you may need to show a Tilde character&nbsp;&nbsp;<b>&#x7E;</b>&nbsp;&nbsp;or a Number Sign&nbsp;&nbsp;<b>&#x23;</b>&nbsp;&nbsp;in your URLs or text.  These characters have a special meaning to the PhpGedView Help system and can only be entered in their hexadecimal or decimal form.  Similarly, the&nbsp;&nbsp;<b>&lt;</b>&nbsp;&nbsp;and&nbsp;&nbsp;<b>&gt;</b>&nbsp;&nbsp;characters that usually enclose HTML tags must be entered in their hexadecimal or decimal forms if they are to be treated as normal text instead of signalling an HTML tag.<ul><li><b>&amp;&#x23;35;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x23;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x23;</b></li><li><b>&amp;&#x23;60;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x3C;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x3C;</b></li><li><b>&amp;&#x23;62;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x3E;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x3E;</b></li><li><b>&amp;&#x23;126;</b>&nbsp;&nbsp;or&nbsp;&nbsp;<b>&amp;&#x23;x7E;</b>&nbsp;&nbsp;will result in&nbsp;&nbsp;<b>&#x7E;</b></li></ul>There is a&nbsp;&nbsp;<b>&amp;tilde;</b>&nbsp;&nbsp;HTML entity, but this symbol is not interpreted as a Tilde when coded in URLs.<br /><br />You can insert references to entries in the language files or to values of global variables.  Examples: <ul><li><b>&#x23;pgv_lang[add_to_cart]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the language variable $pgv_lang["add_to_cart"], and if it were to appear in this field, would show as <b>Add to Clippings Cart</b> when the FAQ list is viewed in the current language.</li><li><b>&#x23;factarray[AFN]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the Fact name $factarray["AFN"], and if it were to appear in this field, would show as <b>Ancestral File Number (AFN)</b> when the FAQ list is viewed in the current language. </li><li><b>&#x23;PGV_VERSION&#x23;&nbsp;&#x23;PGV_VERSION_RELEASE&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the constant PGV_VERSION, a space, and a reference to the constant PGV_VERSION_RELEASE, and if they were to appear in this field, would show as <b>#PGV_VERSION#&nbsp;#PGV_VERSION_RELEASE#</b> when the FAQ list is viewed in the current language.</li><li><b>&#x23;GLOBALS[GEDCOM]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the global variable $GEDCOM, which is the name of the current GEDCOM file.  If it were to appear in this field, it would show as <b>#GLOBALS[GEDCOM]#</b>.</li><li><b>&#x23;GLOBALS[GEDCOM_TITLE]&#x23;</b>&nbsp;&nbsp;&nbsp;is a reference to the global variable $GEDCOM_TITLE, which is the title of the current GEDCOM file.  If it were to appear in this field, it would show as <b>#GLOBALS[GEDCOM_TITLE]#</b>.</li></ul><br />This feature is useful when you wish to create FAQ lists that are different for each language your site supports.  You should put your customized FAQ list titles and entries into the <i>languages/extra.xx.php</i> files (<i>xx</i> is the code for each language), using the following format:<br />$pgv_lang["faq_title1"] = "This is a sample FAQ title";<br />$pgv_lang["faq_body1"] = "This is a sample FAQ body.";');
	break;

case 'index_gedcom_news':
	$title=i18n::translate('index_gedcom_news');
	$text=i18n::translate('The News block is like a bulletin board for this GEDCOM.  The site administrator can place important announcements or interesting news messages here.<br /><br />If you have something interesting to display, please contact the site administrator;  he can put your message on this bulletin board.');
	break;

case 'index':
	$title=i18n::translate('index');
	$text=i18n::translate('This page is the Welcome page. It welcomes you to the selected <a href="#def_gedcom">GEDCOM</a> file. You can return to this page by selecting Welcome Page from the top menu. If there are multiple GEDCOMs on this site, you can select a GEDCOM from the drop-down menu.<br /><br />This Help page contains information about:<ul><li><a href="#index_portal"><b>Welcome Page</b></a></li><li><a href="#header"><b>Header Area</b></a></li><li><a href="#menu"><b>Menus</b></a></li><li><a href="#header_general"><b>General Information</b></a></li><li><a href="#def"><b>Definitions</b></a></li></ul>');
	break;

case 'index_htmlplus_compat':
	$title=i18n::translate('index_htmlplus_compat');
	$text=i18n::translate('Enable compatibility with older versions of this block.  When checked, both old and new keywords will be recognized and acted upon.<br /><br />For example, the text <b>&#35;TOTAL_FAM&#35;</b> will be recognized as being equivalent to <b>&#35;totalFamilies&#35;</b>, <b>&#35;FIRST_DEATH_PLACE&#35;</b> to <b>&#35;firstDeathPlace&#35;</b>, <b>&#35;TOP10_BIGFAM&#35;</b> to <b>&#35;topTenLargestFamily&#35;</b>, etc.<br /><br />Unless absolutely necessary, you should not use Compatibility mode.');
	break;

case 'index_htmlplus_content':
	$title=i18n::translate('index_htmlplus_content');
	$text=i18n::translate('Unlike the HTML, GEDCOM News, and GEDCOM Statistics blocks, you have full control over the appearance of your block.  You can use HTML tags, and the block uses the CSS style sheets from the currently active theme.  References to information from the currently active genealogical database can be included in the text.<br /><br />Database references are signalled in the text by enclosing keywords within paired <b>&#35;</b> symbols.  For example, <b>&#35;totalFamilies&#35;</b> represents the number of families in the database.  On occasion, you may wish to use a database reference as text instead of its true meaning.  To do so, you need to replace the <b>&#35;</b> symbols enclosing the keyword by their symbolic equivalent.  For example, if your text contains <b>&amp;&#35;35;totalFamilies&amp;&#35;35;</b> it will print as <b>&#35;totalFamilies&#35;</b> instead of becoming a database reference.<br /><br />For a full example of the use of this block, please examine the &quot;GEDCOM Statistics&quot; template found in the blocks/ directory, it uses most of the styles of tags, including language and help text links.<br /><br />The <b>Keyword Examples (English only)</b> template contains a full list of all supported keywords.');
	break;

case 'index_htmlplus_gedcom':
	$title=i18n::translate('index_htmlplus_gedcom');
	$text=i18n::translate('Select the database to which the keywords apply.<br /><br />Your site supports several databases.  Keywords such as <b>&#35;totalFamilies&#35;</b> can only refer to one database.  You can identify the database that is to be consulted for all such keywords.  Each Advanced HTML block can only access one database.');
	break;

case 'index_htmlplus':
	$title=i18n::translate('index_htmlplus');
	$text=i18n::translate('This block lets the administrator add information to the Index or Portal page.  Its purpose is similar to the HTML, GEDCOM News, and GEDCOM Statistics blocks, but the administrator has more control over its appearance.');
	break;

case 'index_htmlplus_template':
	$title=i18n::translate('index_htmlplus_template');
	$text=i18n::translate('To assist you in getting started with this block, we have created several standard templates.  When you select one of these templates, the text area will contain a copy that you can then alter to suit your site\'s requirements.');
	break;

case 'index_htmlplus_title':
	$title=i18n::translate('index_htmlplus_title');
	$text=i18n::translate('This text should be blank or very brief.  When blank, the Advanced HTML block will show on the Index or Portal page as a plain block, just like the HTML block does.  When there is text, the Advanced HTML block will show like all the other blocks, complete with a block title bar containing the text you enter here.');
	break;

case 'index_loggedin':
	$title=i18n::translate('index_loggedin');
	$text=i18n::translate('This block will show you the users currently logged in.<br /><br />If you are not an administrator, your view of logged-in users is restricted to those who have elected to be visible while on-line.  For this to work, you must also elect to be visible while on-line.  On-line users who are invisible to you are counted as being anonymous.');
	break;

case 'index_login':
	$title=i18n::translate('index_login');
	$text=i18n::translate('You can login on almost every page of this program. You will usually do so on the first page, since you can only access privileged information when you are logged in.<br /><br />You can login by typing your <b>username</b> and <b>password</b> and then clicking the Login button.');
	break;

case 'index_media':
	$title=i18n::translate('index_media');
	$text=i18n::translate('In this block PhpGedView randomly chooses a media file to show you on each visit to this page.<br /><br />When you click on the picture, you will see its full-size version.  Below the picture you have a link to the person associated with the picture.  When you click on the picture caption, you will see the picture on the MultiMedia page. When you click on the person\'s name, you will be taken to the Individual Information page of that person.');
	break;

case 'index_onthisday':
	$title=i18n::translate('index_onthisday');
	$text=i18n::translate('This block is similar to the "Upcoming Events" block, except that it displays today\'s events.');
	break;

case 'index_portal_head':
	$title=i18n::translate('index_portal_head');
	$text=i18n::translate('<div class="name_head"><center><b>THE WELCOME PAGE</b></center></div>');
	break;

case 'index_portal':
	$title=i18n::translate('index_portal');
	$text=i18n::translate('The Welcome page consists of several separate blocks, and can be customized. On sites that have more than one genealogical database, you may see a different Welcome page for each.  Depending on how the administrator customized the site, you may see any of the following blocks on the Welcome page:<ul><li><a href="#index_welcome"><b>Welcome</b></a></li><li><a href="#index_login"><b>Login</b></a></li><li><a href="#index_events"><b>Upcoming events</b></a></li><li><a href="#index_onthisday"><b>On this Day in Your History</b></a></li><li><a href="#index_charts"><b>Charts</b></a></li><li><a href="#index_favorites"><b>GEDCOM Favorites</b></a></li><li><a href="#index_stats"><b>GEDCOM Statistics</b></a></li><li><a href="#index_common_surnames"><b>Most Common Surnames</b></a></li><li><a href="#index_media"><b>Random Media</b></a></li><li><a href="#index_loggedin"><b>Logged in Users</b></a></li><li><a href="#gedcom_news"><b>GEDCOM News</b></a></li><li><a href="#recent_changes"><b>Recent Changes</b></a></li></ul>');
	break;

case 'index_stats':
	$title=i18n::translate('index_stats');
	$text=i18n::translate('In this block you will see some statistics about the current GEDCOM file.  If you need more information than is listed, send a message to the contact at the bottom of the page.');
	break;

case 'index_top10_pageviews':
	$title=i18n::translate('index_top10_pageviews');
	$text=i18n::translate('This block will list the top 10 individuals, families, or sources that have been viewed by visitors to this site.  In order for this block to appear the site administrator must have enabled the Item Hit counters.');
	break;

case 'index_welcome':
	$title=i18n::translate('index_welcome');
	$text=i18n::translate('The Welcome block shows you the current database title, the date and time, and, if enabled by the admin, the Hit Counter.<br /><br />The Hit Counter is only available in the Welcome block and on the Individual Information page.  The counter counts the "Hits" of these pages. That means it counts how many times these pages are visited.  The counter does not check the Internet address of a visitor; every visit to a page from <u>any</u> remote location counts as another Hit.');
	break;

case 'invalid_header':
	$title=i18n::translate('Detected lines before the GEDCOM header <b>0&nbsp;HEAD</b>.  On cleanup, these lines will be removed.');
	$text=i18n::translate('A GEDCOM file must begin with <b>0&nbsp;HEAD</b>. PhpGedView detected that the GEDCOM file you are importing does not have <b>0&nbsp;HEAD</b> as the first line. When you click the Cleanup button, any lines before the first <b>0&nbsp;HEAD</b> line will be removed.<br /><br />This error usually means that the program you used to create your GEDCOM did not create it properly or it is not a GEDCOM file. You should check to make sure that you uploaded the correct file, and that it starts with the line <b>0&nbsp;HEAD</b> and ends with the line <b>0&nbsp;TRLR</b>.');
	break;

case 'is_user':
	$title=i18n::translate('is_user');
	$text=i18n::translate('--- This help text is the same text that site visitors will read. --- <br />--- To save space, we did not make a special admin text for this item. ---');
	break;

case 'keep_media':
	$title=i18n::translate('Keep media links');
	$text=i18n::translate('Should existing media links be retained in the database when a replacement GEDCOM is being uploaded. The <b>No</b> option removes existing media links from the database, while the <b>Yes</b> option keeps them.<br /><br />This option is useful when you export your GEDCOM from PhpGedView to an off-line GEDCOM maintenance program that does not handle embedded media pointers properly, and then subsequently re-import that changed GEDCOM into PhpGedView.  Under such circumstances, the media pointers within the GEDCOM you exported to your off-line editing program are destroyed, and you would have to re-link all of your media files to the proper Person, Family, and Source records after you re-import the GEDCOM into PhpGedView.<br /><br />The <b>Yes</b> option tells PhpGedView to keep the existing media links so that you do not have to re-create them after you import the changed GEDCOM, but this requires the off-line editing program to always produce the same Person, Family, and Source identification numbers.<br /><br /><i>Family Tree Maker</i> is one of several off-line editing programs that does <u>not</u> properly handle media object pointers within the GEDCOM.  <i>Legacy</i>, among many others, <u>does</u> handle these properly.');
	break;

case 'lang_debug':
	$title=i18n::translate('Help Text Debug Option');
	$text=i18n::translate('When you enable this option, the names of the language variables used in help text will print in the help text popup window.  This will help translators determine the variable name when text needs to be adjusted.<br /><br />This setting will only be valid during your current PhpGedView session.');
	break;

case 'lang_edit':
	$title=i18n::translate('Edit');
	$text=i18n::translate('This page is intended to be used by translators.  You can translate, compare, and export language files.  There is also an option to help translators determine the origin of text that is output by PhpGedView.<br /><br />You can use the following options and utilities:');
	break;

case 'lang_filenames':
	$title=i18n::translate('Language files');
	$text=i18n::translate('PhpGedView has implemented support for many different languages.  This has been achieved by keeping all text that is visible to users in files completely separate from the main program.  There is a set of eight files for each supported language, and the various texts have been separated into one of these files according to function.  <b>Not all language files need to be present.</b>  When a given text is not yet available in translated form, PhpGedView will always use the English version.<br /><br />The files in each language set are:<br /><ul><li><b><i>admin.xx.php</i></b>&nbsp;&nbsp;This file contains terms and common expressions for use during the administration of PhpGedView and the genealogical databases.<br /><br /></li><li><b><i>configure_help.xx.php</i></b>&nbsp;&nbsp;This file contains Help text for use during configuration of PhpGedView.  The Help text is not intended to be viewed by ordinary users.<br /><br /></li><li><b><i>countries.xx.php</i></b>&nbsp;&nbsp;This is a list of country names, taken from the Web site of the Statistics Division, United Nations Department of Economic and Social Affairs.  This is the relevant <a href="http://unstats.un.org/unsd/methods/m49/m49alpha.htm" target="_blank"><b>link</b></a> to the English list.  The list is available in either English or French.<br /><br /></li><li><b><i>editor.xx.php</i></b>&nbsp;&nbsp;This file contains terms and common expressions for use during the editing of entries in the genealogical databases.<br /><br /></li><li><b><i>facts.xx.php</i></b>&nbsp;&nbsp;This file contains the textual equivalents of the GEDCOM Fact codes found in the GEDCOM 5.5.1 Standard.  It also contains additional Fact codes not found in the Standard but used by various genealogy programs.<br /><br />An English copy of the <a href="http://www.phpgedview.net/ged551-5.pdf" target="_blank"><b>GEDCOM 5.5.1 Standard</b></a> can be downloaded in PDF (Portable Document Format).<br /><br /></li><li><b><i>faqlist.xx.php</i></b>&nbsp;&nbsp;This file is a set of <b>f</b>requently <b>a</b>sked <b>q</b>uestions that have been collected by the PhpGedView development team.  Each FAQ has two entries in this file.  One entry is the FAQ heading (usually the question), and the other is the FAQ body (usually the answer).  Replacements for the <b><i>faqlist.xx.php</i></b> files, which are updated frequently, may be downloaded from the PhpGedView home site.<br /><br />The administrator can use the FAQs in this file to build an FAQ list that is specific to his site.<br /><br /></li><li><b><i>help_text.xx.php</i></b>&nbsp;&nbsp;This file contains Help text for ordinary users.  Some Help topics in this file address the needs of administrators, and are hidden from users who do not have Admin rights.<br /><br /></li><li><b><i>lang.xx.php</i></b>&nbsp;&nbsp;Many terms and common expressions are found in this file.</li></ul><br />PhpGedView also supports an optional ninth language file, <b><i>extra.xx.php</i></b>.  This file is always loaded after all the others and provides a means whereby a site administrator can override or alter any standard text in the selected language.  It can also be used to provide a title for the genealogical databases that varies according to the currently active language.<br /><br />The contents of this additional file are completely up to the site administrator;  this file will <b>never</b> be distributed with any version of PhpGedView.  The administrator should never make changes to the standard language files;  all local changes should be concentrated in this optional file.');
	break;

case 'lang_langcode':
	$title=i18n::translate('Language detection codes');
	$text=i18n::translate('These codes allow PhpGedView to detect the Preferred Language setting of the browser being used. PhpGedView determines the language actually being requested by the browser by matching the browser\'s language code against this list.  Individual list entries must be separated by a semicolon.');
	break;

case 'lang_shortcut':
	$title=i18n::translate('Abbreviation for language files');
	$text=i18n::translate('This code defines an abbreviation for the language name.  This abbreviation forms part of the name of each of the language files used by PhpGedView.  For example, the abbreviation used for French is <b>fr</b>, and consequently the file names for French are <i>configure_help.<b>fr</b>.php</i>, <i>countries.<b>fr</b>.php</i>, <i>facts.<b>fr</b>.php</i>, <i>help_text.<b>fr</b>.php</i>, and <i>lang.<b>fr</b>.php</i>');
	break;

case 'language_to_edit':
	$title=i18n::translate('Language to edit');
	$text=i18n::translate('In this list box you select the language whose messages you want to edit.');
	break;

case 'language_to_export':
	$title=i18n::translate('Language to export');
	$text=i18n::translate('From this list box you can select the language whose messages you want to export.<br /><br />The routine currently only exports the contents of the <i>configure_help.xx.php</i>, <i>help_text.xx.php</i>, and <i>lang.xx.php</i> files.  The output is an HTML file that you can print from your browser.');
	break;

case 'lifespan_add_person':
	$title=i18n::translate('lifespan_add_person');
	$text=i18n::translate('You can have several persons on the timeline.<br /><br />Use this box to supply each person\'s ID.  If you don\'t know the ID of the person, you can click the <b>Find ID</b> link next to the box.<br /><br />~Include Immediate Family CheckBox~<br/>Include Immediate Family is checked by default.  Leave checked to view the father, mother, spouse, siblings, and children of the individual being added to the timeline.  Uncheck if you wish to omit the immediate family.');
	break;

case 'line_up_generations':
	$title=i18n::translate('Line up the same generations');
	$text=i18n::translate('When this check box is checked, the chart will be printed with the same generations lining up horizontally on the page.  When it is unchecked, each generation will appear going down the page regardless of the type of relationship.');
	break;

case 'link_child':
	$title=i18n::translate('link_child');
	$text=i18n::translate('You can link this person as a child to an existing family when you click this link.<br /><br />Suppose that at one time the parents of the person were unknown, and you discovered later that the parents have a record in this database.<br /><br />Just click the link, enter the ID of the family, and you have competed the task.  If you don\'t know the family\'s ID, you can search for it.');
	break;

case 'link_gedcom_id':
	$title=i18n::translate('link_gedcom_id');
	$text=i18n::translate('Use this section to select the alternate database identifier that contains the the person you are linking to.');
	break;

case 'link_husband':
	$title=i18n::translate('link_husband');
	$text=i18n::translate('This item will allow you to link the current individual as a husband to a family that is already in the database. By clicking this link you can add this person to an existing family, of which the husband was unknown until now. This person will take the place of the previously unknown husband. All events, marriage information, and children will keep their existing links to the family.<br /><br />Just click the link, enter the ID of the family, and you have competed the task. This is an advanced editing option that should only be used if the family you want to link to already exists.  If you want to add a <u>new</u> family to this individual, use the <b>Add a new wife</b> link.');
	break;

case 'link_new_husb':
	$title=i18n::translate('Add a husband using an existing person');
	$text=i18n::translate('This will allow you to link another individual, who already exists, as a new husband to this person.  This will create a new family with the husband you select.  You will also have the option of specifying a marriage for this new family.');
	break;

case 'link_new_wife':
	$title=i18n::translate('Add a wife using an existing person');
	$text=i18n::translate('This will allow you to link another individual, who already exists, as a new wife to this person.  This will create a new family with the wife you select.  You will also have the option of specifying a marriage for this new family.');
	break;

case 'link_person_id':
	$title=i18n::translate('link_person_id');
	$text=i18n::translate('In this field you enter the ID of the person you are linking to (e.g. I100).');
	break;

case 'link_remote':
	$title=i18n::translate('Link remote person');
	$text=i18n::translate('Use this form to link people to other people either from another site or another genealogical database accessible to your copy of PhpGedView.<br /><br />To add such a link, you must first select the relationship type, then choose a site already known to PhpGedView or define a new site, and then enter that site\'s ID of the person you want to link to.  PhpGedView will then automatically download information from the remote site as necessary.  The downloaded information does <u>not</u> become part of your genealogical database; it remains on the original site but is incorporated into the various pages where this remotely linked person is displayed.<br /><br />Refer to the Help link next to each element on the page for more information about that element.  You can also check the online English tutorial for more information: <a href="#PGV_PHPGEDVIEW_WIKI#/en/index.php?title=How_To:Remote_Link_Individuals_Across_Websites_And_Databases" target="_blank">#PGV_PHPGEDVIEW_WIKI#</a>.');
	break;

case 'link_remote_location':
	$title=i18n::translate('link_remote_location');
	$text=i18n::translate('This option allows you to choose whether data for the person you are linking to is on the same site but in a different genealogical database set, or whether the data is on a different site accessible through the Internet.<br /><br />If the person is on the same site, you will be asked to select the dataset identifier and enter the person\'s ID.<br /><br />For a remote site, you will be asked to enter its URL, a database identifier, and the person\'s remote ID.');
	break;

case 'link_remote_rel':
	$title=i18n::translate('link_remote_rel');
	$text=i18n::translate('Use this option to select the relationship the remote person has to the person you are linking them with on your site.  For example, selecting <i>Father</i> would mean that the person on the remote site is the father of the person you are linking them to locally.');
	break;

case 'link_remote_site':
	$title=i18n::translate('link_remote_site');
	$text=i18n::translate('In this section you specify the parameters that are required to connect to the remote site hosting the data you are linking to. You have the option of choosing from a list of known sites that you have used before, or entering the Site URL and Database ID for a new one.<br /><br />In the <b>Site URL</b> field, you enter the URL to access the web services description file (WDSL) which tells PhpGedView how to access the data on the remote site.  For a remote PhpGedView website, the URL to the WSDL file will look like this: <u>http://www.remotesite.com/phpGedView/genservice.php?wsdl</u><br /><br />The <b>Database ID</b> field is used to enter an optional database identifier for remote sites that require one.  For PhpGedView sites, this is the name of the GEDCOM file. <br /><br />The <b>Username</b> and the <b>Password</b> fields are necessary if the database requires it.<br /><br /><i>Note: Remote PhpGedView sites must be running version 4.0 or later; earlier versions do not have this capability.</i>');
	break;

case 'link_wife':
	$title=i18n::translate('link_wife');
	$text=i18n::translate('This item will allow you to link the current individual as a wife to a family that is already in the database.<br /><br />This is an advanced editing option that should only be used if the family you want to link to already exists.  If you want to add a <u>new</u> family to this individual, use the <b>Add a new husband</b> link.');
	break;

case 'login_buttons_aut':
	$title=i18n::translate('login_buttons_aut');
	$text=i18n::translate('Here you see two buttons to login to the system.<br /><br />The page you will be taken to depends on which button you click after typing your user name and password.<br /><ul><li>The <b>Login</b> button<br />If you click this button, you will be logged in and go directly to your MyGedView Portal page, where you can edit your settings, add or edit favorites, send and read messages, etc.</li><li>The <b>Admin</b> button<br />If you have Admin rights, you can click this button to go directly to the main Administration page.</li></ul>');
	break;

case 'login_buttons':
	$title=i18n::translate('login_buttons');
	$text=i18n::translate('Here you see two buttons to login to the system.<br /><br />The page you will be taken to or sent back to depends on which button you click after typing your user name and password.<br /><ul><li>The <b>Login</b> button<br />If you click this button, you will return to the page you were just on, but with logged-in access rights.<br /><br />For example, if you click <b>Login</b> when you were at the Pedigree page, you will return to that same page.  If you click this button when you were on the main Welcome page, you will be taken to the MyGedView Portal page.</li><li>The <b>Admin</b> button<br />If you have Admin rights, you can click this button to go directly to the main Administration page.</li></ul>');
	break;

case 'login_page':
	$title=i18n::translate('login_page');
	$text=i18n::translate('On this page you can login, request a new password, or request a new user account.');
	break;

case 'macfile_detected':
	$title=i18n::translate('Macintosh file detected.  On cleanup your file will be converted to a DOS file.');
	$text=i18n::translate('PhpGedView detected that your GEDCOM file was created on a Macintosh computer.<br /><br />Macintosh files end each line with a CR control code.  CR is Ctrl+M.<br />Unix files end each line with an LF control code.  LF is Ctrl+J.<br />Windows and DOS use a two-code sequence, CR followed by LF.<br /><br />PhpGedView requires that all files use Unix or DOS line endings. When you click the Cleanup button, your line endings will be converted accordingly.');
	break;

case 'mail_option1':
	$title=i18n::translate('mail_option1');
	$text=i18n::translate('With this option, the PhpGedView internal messaging system will be used and no emails will be sent.<br /><br />You will receive only <u>internal</u> messages from the other users.  When another site user sends you a message, that message will appear in the Message block on your personal MyGedView Portal page.  If you have removed this block from your MyGedView Portal page, you will not see any messages.  They will, however, show up as soon as you configure the MyGedView Portal page to again have the Message block.');
	break;

case 'mail_option2':
	$title=i18n::translate('mail_option2');
	$text=i18n::translate('This option is like PhpGedView internal messaging, with one addition.  As an extra, a copy of the message will also be sent to the email address you configured on your Account page.<br /><br />This is the default contact method.');
	break;

case 'mail_option3':
	$title=i18n::translate('mail_option3');
	$text=i18n::translate('With this option, you will only receive email messages at the address you configured on your Account page.  The messaging system internal to PhpGedView will not be used at all, and there will never be any messages in the Message block on your personal MyGedView Portal page.');
	break;

case 'mail_option4':
	$title=i18n::translate('mail_option4');
	$text=i18n::translate('With this option, you will not receive any messages.  Even the administrator will not be able to reach you.');
	break;

case 'manage_media':
	$title=i18n::translate('Manage MultiMedia');
	$text=i18n::translate('On this page you can easily manage your Media files and directories.<br /><br />When you create new Media subdirectories, PhpGedView will ensure that the identical directory structure is maintained within the <b>#GLOBALS[MEDIA_DIRECTORY]#thumbs</b> directory.  When you upload new Media files, PhpGedView can automatically create the thumbnails for you.<br /><br />Beside each image in the Media list you\'ll find the following options.  The options actually shown depend on the current status of the Media file.<ul><li><b>Edit</b>&nbsp;&nbsp;When you click on this option, you\'ll see a page where you can change the title of the Media object.  If the Media object is not yet linked to a person, family, or source in the currently active database, you can establish this link here.  You can rename the file or even change its location within the <b>#GLOBALS[MEDIA_DIRECTORY]#</b> directory structure.  When necessary, PhpGedView will automatically create the required subdirectories or any missing thumbnails.</li><li><b>Edit raw GEDCOM record</b>&nbsp;&nbsp;This option is only available when the administrator has enabled it.  You can view or edit the raw GEDCOM data associated with this Media object.  You should be very careful when you use this option.</li><li><b>Delete file</b>&nbsp;&nbsp;This option lets you erase all knowledge of the Media file from the current database.  Other databases will not be affected.  If this Media file is not mentioned in any other database, it, and its associated thumbnail, will be deleted.</li><li><b>Remove object</b>&nbsp;&nbsp;This option lets you erase all knowledge of the Media file from the current database.  Other databases will not be affected.  The Media file, and its associated thumbnail, will not be deleted.</li><li><b>Remove links</b>&nbsp;&nbsp;This option lets you remove all links to the media object from the current database.  The file will not be deleted, and the Media object by which this file is known to the current database will be retained.  Other databases will not be affected.</li><li><b>Set link</b>&nbsp;&nbsp;This option lets you establish links between the media file and persons, families, or sources of the current database.  When necessary, PhpGedView will also create the Media object by which the Media file is known to the database.</li><li><b>Create thumbnail</b>&nbsp;&nbsp;When you select this option, PhpGedView will create the missing thumbnail.</li></ul>');
	break;

case 'medialist_recursive':
	$title=i18n::translate('List files in subdirectories');
	$text=i18n::translate('When this option is selected, the MultiMedia Objects will search not only the directory selected from the Filter list but all its subdirectories as well. When this option is not selected, only the selected directory is searched.<br /><br />The titles of all media objects found are then examined to determine whether they contain the text entered in the Filter.  The result of these two actions determines the multimedia objects to be listed.');
	break;

case 'menu_annical':
	$title=i18n::translate('menu_annical');
	$text=i18n::translate('The Anniversary Calendar displays the events in a GEDCOM for a given date, month, or year.<ol><li><a href="?help=day_month_help"><b>View Day</b></a></li><li><a href="?help=day_month_help"><b>View Month</b></a></li><li><a href="?help=day_month_help"><b>View Year</b></a><br />These menu items will take you to the Anniversary Calendar to display a list of all the events for the current day, month, or year.</li></ol>');
	break;

case 'menu_charts':
	$title=i18n::translate('menu_charts');
	$text=i18n::translate('The available charts are:<ol><li><a href="?help=help_pedigree.php"><b>Pedigree Tree</b></a><br />This will link you to the Pedigree chart of this GEDCOM file. The pedigree will start with the person configured by the administrator. When you are logged in the starting person can be whoever you have configured in your Account preferences.</li><li><a href="?help=help_descendancy.php"><b>Descendancy Chart</b></a><br />The Descendancy chart is essentially a <a href="?help=help_pedigree.php"><b>Pedigree Tree</b></a> in reverse order.  This comparison is not quite correct, but while the Pedigree chart shows you all the ancestors of a starting person, the Descendancy chart shows you all the descendants of a starting person.</li><li><a href="?help=help_timeline.php"><b>Timeline Chart</b></a><br />Here you view the events of a person along a time line.  It\'s interesting to compare the events of two or more persons along the same time line.</li><li><a href="?help=help_relationship.php"><b>Relationship Chart</b></a><br />Here you can check the relation of a person to yourself or to another person.</li><li><a href="?help=help_ancestry.php"><b>Ancestry Chart</b></a><br />This chart is very similar to the <a href="?help=help_pedigree.php"><b>Pedigree Tree</b></a>, but with more details and alternate <a href="?help=chart_style_help"><b>Chart style</b></a> displays.</li><li><a href="?help=help_fanchart.php"><b>Circle Diagram</b></a><br />This chart is very similar to the <a href="?help=help_pedigree.php"><b>Pedigree Tree</b></a>, but in a more graphical way.</li></ol>');
	break;

case 'menu_clip':
	$title=i18n::translate('menu_clip');
	$text=i18n::translate('You will see this item in the menu bar only when the administrator has enabled this feature.<br /><br />The Clippings Cart allows you to store information about individuals, families, and sources in a temporary file that you can later download in GEDCOM 5.5.1 format.');
	break;

case 'menu_famtree':
	$title=i18n::translate('menu_famtree');
	$text=i18n::translate('All of this site\'s available genealogical databases are listed in this menu. Each database has its own customized Welcome page, like this one.  If there is only one database at this site, there is no sub-menu under the Welcome Page icon.');
	break;

case 'menu':
	$title=i18n::translate('Menu');
	$text=i18n::translate('<div class="name_head"><center><b>MENUS</b></center></div><br />The page headers have drop-down menus associated with each menu icon.<br /><br />When you move your mouse pointer over an icon a sub-menu will appear, if one exists.  When you click on an icon you will be taken to the first item in the sub-menu.<br /><br />The following menu icons are usually available:<ul><li><a href="#menu_fam">Welcome page</a><br /></li><li><a href="#menu_myged">MyGedView Portal</a><br /></li><li><a href="#menu_charts">Charts</a><br /></li><li><a href="#menu_lists">Lists</a><br /></li><li><a href="#menu_annical">Anniversary Calendar</a><br /></li><li><a href="#menu_clip">Family Tree Clippings Cart</a><br /></li><li><a href="#menu_search">Search</a><br /></li><li><a href="#menu_help">Help</a></li></ul>');
	break;

case 'menu_help':
	$title=i18n::translate('<div class="name_head"><center><b>MENUS</b></center></div><br />The page headers have drop-down menus associated with each menu icon.<br /><br />When you move your mouse pointer over an icon a sub-menu will appear, if one exists.  When you click on an icon you will be taken to the first item in the sub-menu.<br /><br />The following menu icons are usually available:<ul><li><a href="#menu_fam">Welcome page</a><br /></li><li><a href="#menu_myged">MyGedView Portal</a><br /></li><li><a href="#menu_charts">Charts</a><br /></li><li><a href="#menu_lists">Lists</a><br /></li><li><a href="#menu_annical">Anniversary Calendar</a><br /></li><li><a href="#menu_clip">Family Tree Clippings Cart</a><br /></li><li><a href="#menu_search">Search</a><br /></li><li><a href="#menu_help">Help</a></li></ul>');
	$text=i18n::translate('#pgv_lang[help_help_items]#');
	break;

case 'menu_lists':
	$title=i18n::translate('menu_lists');
	$text=i18n::translate('The following lists are available:<ol><li><a href="?help=help_indilist.php"><b>Individuals</b></a></li><li><a href="?help=help_famlist.php"><b>Families</b></a><br />In these two lists you can browse alphabetical lists of individuals or families in this GEDCOM.</li><li><a href="?help=help_sourcelist.php"><b>Sources</b></a><br />This item returns a list of all the sources used in the GEDCOM.</li><li><a href="?help=help_placelist.php"><b>Place Hierarchy</b></a><br />Here you can look for people by Place. A two-column list will be returned. Individuals are listed on the left, families on the right.</li><li><a href="?help=help_medialist.php"><b>MultiMedia</b></a><br />You see this menu item only if enabled by the site admin.  This will display links to all multimedia files in this GEDCOM.</li></ol>');
	break;

case 'menu_myged':
	$title=i18n::translate('menu_myged');
	$text=i18n::translate('If you are logged in, this menu can include the following items:<ol><li>MyGedView Portal<br />This takes you to your own customizable Starting page.</li><li>My Account<br />You can edit your personal data here.</li><li>My Pedigree<br />If you have selected a Root person for this GEDCOM, this will take you to the Pedigree chart for that person.</li><li>My Individual Record<br />This link will take you to your Individual Information page, where all genealogical data about yourself and your family is displayed.</li></ol>');
	break;

case 'menu_search':
	$title=i18n::translate('menu_search');
	$text=i18n::translate('The Search page is a more powerful version of the Search box you may find in each page header.');
	break;

case 'messaging2':
	$title=i18n::translate('Internal messaging with emails');
	$text=i18n::translate('When you send this message you will receive a copy sent via email to the address you provided.');
	break;

case 'more_config':
	$title=i18n::translate('more_config');
	$text=i18n::translate('<br /><b>More help</b><br />More help is available by clicking the <b>?</b> next to items on the page.');
	break;

case 'more':
	$title=i18n::translate('more');
	$text=i18n::translate('<br />Contextual help is available on every screen; make sure that the <b>Show Contextual Help</b> option in the Help menu is on, and click on a <b>?</b> next to the subject.');
	break;

case 'move_mediadirs':
	$title=i18n::translate('Move Media directories');
	$text=i18n::translate('When the Media Firewall is enabled, Multi-Media files can be stored in a server directory that is not accessible from the Internet.<br /><br />These buttons allow you to easily move an entire Media directory structure between the protected (not web-addressable) <b>#GLOBALS[MEDIA_FIREWALL_ROOTDIR]##GLOBALS[MEDIA_DIRECTORY]#</b> and the normal <b>#GLOBALS[MEDIA_DIRECTORY]#</b> directories.');
	break;

case 'movedown_faq_item':
	$title=i18n::translate('Move FAQ item down');
	$text=i18n::translate('This option will let you move an item downwards on the FAQ page.<br /><br />Each time you use this option, the FAQ Position number of this item is increased by one.  You can achieve the same effect by editing the item in question and changing the FAQ Position field.  When more than one FAQ item has the same position number, only one of these items will be visible.');
	break;

case 'moveup_faq_item':
	$title=i18n::translate('Move FAQ item up');
	$text=i18n::translate('This option will let you move an item upwards on the FAQ page.<br /><br />Each time you use this option, the FAQ Position number of this item is reduced by one.  You can achieve the same effect by editing the item in question and changing the FAQ Position field.  When more than one FAQ item has the same position number, only one of these items will be visible.');
	break;

case 'multi_letter_alphabet':
	$title=i18n::translate('Multi-letter alphabet');
	$text=i18n::translate('Multi-letter combinations that are to be treated as a single distinct letter when sorting lists of names and titles in this language.<br /><br />Some languages, Hungarian and Slovak for example, consider certain combinations of letters to be distinct letters in their own right.  The order in which you specify these letter combinations determines the order in which they are inserted into the normal alphabet during sorting.  This is important when several multi-letter combinations have the same first letter.  Except for <b>ch</b>, these letter combinations are inserted into the normal alphabet according to their first letter.  <b>ch</b> is always inserted after <b>h</b>.');
	break;

case 'multi_letter_equiv':
	$title=i18n::translate('Multi-letter equivalents');
	$text=i18n::translate('In some languages, multiple letters are often treated as equivalent to a single letter when generating lists of names.<br /><br />For example, in Dutch, names beginning with IJ are listed together with names beginning with Y. In Norwegian, names beginning with AA are listed with &Aring;. In some languages, there are letters that can be written as one character or two. For example in Slovakian, the two characters D and \xC5\xBE can be written as the single character \xC7\x85. By specifying equivalents here, you can allow names beginning with these letters to be grouped together on the individual list pages.<br /><br />You should specify a comma-separated list of equivalents. To support databases that don\'t recognize UTF-8 encoding, you should specify both upper and lower case equivalents. This example demonstrates the format to use.<br /><br />Aa=&Aring;,aa=&aring;');
	break;

case 'multiple':
	$title=i18n::translate('multiple');
	$text=i18n::translate('<center>--- This is a general help text for multiple pages ---</center>');
	break;

case 'mygedview_customize':
	$title=i18n::translate('mygedview_customize');
	$text=i18n::translate('When you entered here for the first time, you already had some blocks on this page.  If you like, you can customize this Portal page.<br /><br />When you click this link you will be taken to a form where you can add, move, or delete blocks.  More explanation is available on that form.');
	break;

case 'mygedview_favorites':
	$title=i18n::translate('mygedview_favorites');
	$text=i18n::translate('Favorites are similar to bookmarks.<br /><br />Suppose you have somebody in the family tree whose record you want to check regularly.  Just go to the person\'s Individual Information page and select the <b>Add to My Favorites</b> option from the Favorites drop-down list. This person is now book marked and added to your list of favorites.<br /><br />Wherever you are on this site, you can click on a name in the "My Favorites" drop-down list in the header.  This will take you to the Individual Information page of that person.');
	break;

case 'mygedview_login':
	$title=i18n::translate('mygedview_login');
	$text=i18n::translate('In order to access the MyGedView Portal page, you must be a registered user on the system.  On the MyGedView Portal page you can bookmark your favorite people, keep a user journal, manage messages, see other logged in users, and customize various aspects of PhpGedView pages.<br /><br />Enter your User name and Password in the appropriate fields to login to MyGedView.');
	break;

case 'mygedview_message':
	$title=i18n::translate('mygedview_message');
	$text=i18n::translate('In this block you will find the messages sent to you by other users or the admin.  You too can send messages to other users or to the admin.<br /><br />The PhpGedView mail system is designed to help protect your privacy.  You don\'t have to leave your email address here and others will not be able to see your email address.<br /><br />To expand a message, click on the message subject or the "<b>+</b>" symbol beside it.  You can delete multiple messages by checking the boxes next to the messages you want to delete and clicking on the <b>Delete Selected Messages</b> button.');
	break;

case 'mygedview_myjournal':
	$title=i18n::translate('mygedview_myjournal');
	$text=i18n::translate('You can use this journal to write notes or reminders for your own use.  When you make such a note, it will still be there the next time you visit the site.<br /><br />These notes are private and will not be visible to others.');
	break;

case 'mygedview_portal':
	$title=i18n::translate('mygedview_portal');
	$text=i18n::translate('This is your Personal MyGedView page.<br /><br />Here you will find easy links to access your personal data such as <b>My Account</b>, <b>My Indi</b> (this is your Individual Information page), and <b>My Pedigree</b>.  You can have blocks with <b>Messages</b>, a <b>Journal</b> (like a Notepad) and many more.<br /><br />The layout of this page is similar to the Welcome page that you see when you first access this site.  While the parts of the Welcome page are selected by the site administrator, you can select what parts to include on this personalized page.  You will find the link to customize this page in the Welcome block or separately when the Welcome block is not present.<br /><br />You can choose from the following blocks:<ul><li><a href="#mygedview_welcome"><b>Welcome</b></a></li><li><a href="#mygedview_customize"><b>Customize MyGedView</b></a></li><li><a href="#mygedview_message"><b>Messages</b></a></li><li><a href="#mygedview_events"><b>Upcoming events</b></a></li><li><a href="#mygedview_onthisday"><b>On this Day in Your History</b></a></li><li><a href="#mygedview_charts"><b>Charts</b></a></li><li><a href="#mygedview_favorites"><b>My Favorites</b></a></li><li><a href="#mygedview_stats"><b>GEDCOM Statistics</b></a></li><li><a href="#mygedview_myjournal"><b>My Journal</b></a></li><li><a href="#mygedview_media"><b>Random Media</b></a></li><li><a href="#mygedview_loggedin"><b>Logged In Users</b></a></li><li><a href="#mygedview_recent_changes"><b>Recent Changes</b></a></li></ul>');
	break;

case 'mygedview_welcome':
	$title=i18n::translate('mygedview_welcome');
	$text=i18n::translate('The Welcome block shows you:<ul><li>The current GEDCOM file</li><li>The date and time</li><li>Links to:<ul><li>My Account</li><li>My Pedigree</li><li>My Individual Record</li><li>Customize MyGedView Portal</li></ul></li></ul><br /><b>Note:</b><br />You will see the links to <b>My Indi</b> and <b>My Pedigree</b> only if you are known to the current GEDCOM file.  You might have a record in one GEDCOM file and therefore see the <b>My Indi</b> and <b>My Pedigree</b> links, while in another GEDCOM file you do not have a record and consequently these links are not displayed.');
	break;

case 'name_list':
	$title=i18n::translate('Name List');
	$text=i18n::translate('This box will display either a surname list or a complete name list.  In both cases all surnames will start with the initial letter that you clicked in the Alphabetical index, unless you clicked <b>ALL</b>.<br /><br />Whether you will see a surname list or the complete name list depends on the status of the <b>Skip/Show Surname Lists</b> link.');
	break;

case 'name_reverse':
	$title=i18n::translate('Surname first');
	$text=i18n::translate('In some languages the surname should be displayed first instead of the default to display it last.  Enabling this option will display the surname first.  Hungarian, Chinese, and Japanese are examples where the surname (the family name) is usually given first.');
	break;

case 'new_dir':
	$title=i18n::translate('new_dir');
	$text=i18n::translate('As an admin user you can create the directory structure you require to keep your media files organized. Creating directories from this page ensures that the thumbnail directories are created as well as creating a suitable index.php in each directory.<br /><br />Click on this link to enter the name of the directory you wish to create.');
	break;

case 'new_language':
	$title=i18n::translate('Source language');
	$text=i18n::translate('From this list box you can select the language whose messages you want to compare with those of another language.<br /><br />All changes and additions to language files are first made to the <b>English</b> language files, so you would normally select English as one of the languages to compare.');
	break;

case 'new_password':
	$title=i18n::translate('new_password');
	$text=i18n::translate('If you have forgotten your password, you can click this link to request a new password.<br /><br />You will be taken to the "Lost Password Request" page.');
	break;

case 'new_user_firstname':
	$title=i18n::translate('new_user_firstname');
	$text=i18n::translate('In this box you have to type your first name.<br /><br />We need your first and last names to determine whether you qualify for an account at this site, and what your rights should be.  These names will be visible to other logged-in family members and users.');
	break;

case 'new_user':
	$title=i18n::translate('new_user');
	$text=i18n::translate('If you are a visitor to this site and wish to request a user account, you can click this link.<br /><br />You will be taken to the "Register" page.');
	break;

case 'new_user_lastname':
	$title=i18n::translate('new_user_lastname');
	$text=i18n::translate('In this box you have to type your last name.<br /><br />We need your first and last names to determine whether you qualify for an account at this site, and what your rights should be.  These names will be visible to other logged-in family members and users.');
	break;

case 'next_path':
	$title=i18n::translate('Find next path');
	$text=i18n::translate('You can click this button to see whether there is another relationship path between the two people.  Previously found paths can be displayed again by clicking the link with the path number.');
	break;

case 'no_update_CHAN':
	$title=i18n::translate('Do not update the CHAN (Last Change) record');
	$text=i18n::translate('Administrators sometimes need to clean up and correct the data submitted by users.  For example, they might need to correct the PLAC location to include the country.  When Administrators make such corrections, information about the original change is normally replaced.  This may not be desirable.<br /><br />When this option is selected, PhpGedView will retain the original Change information instead of replacing it with that of the current session.  With this option selected, Administrators also have the ability to modify or delete the information associated with the original CHAN tag.');
	break;

case 'old_language':
	$title=i18n::translate('Secondary language');
	$text=i18n::translate('From this list box you can select the language which you want to compare with the language selected in the <b>Source</b> list box.<br /><br />After you have made your choice, click the <b>Compare</b> button to get a list of all additions and subtractions in all four files of both languages.<br /><br />To refresh your memory:<br /><b>Addition</b> means: It is <u>in</u> the Source file, but <u>not</u> in the Secondary file.<br /><br /><b>Subtraction</b> means: It is <u>not</u> in the Source file, but it <u>is</u> in the Secondary file.  This might happen when a given message is no longer used and therefore was removed from the English files.  You can safely remove the corresponding message from the secondary language files.');
	break;

case 'oldest_top':
	$title=i18n::translate('Show oldest top');
	$text=i18n::translate('When this check box is checked, the chart will be printed with oldest people at the top.  When it is unchecked, youngest people will appear at the top.<br /><br />Note: This option works only if <b>Line up the same generations</b> is also checked.');
	break;

case 'original_lang_name':
	$title=i18n::translate('Original name of language in #D_LANGNAME#');
	$text=i18n::translate('How is this language named in this language?<br /><br />English calls itself <b>English</b>;  German calls itself <b>Deutsch</b>;  Dutch calls itself <b>Nederlands</b>;  Czech calls itself <b>Cestina</b>, etc.');
	break;

case 'page':
	$title=i18n::translate('Page');
	$text=i18n::translate('Help');
	break;

case 'password':
	$title=i18n::translate('Password');
	$text=i18n::translate('In this box you type your password.<br /><br /><b>The password is case sensitive.</b>  This means that <b>MyPassword</b> is <u>not</u> the same as <b>mypassword</b> or <b>MYPASSWORD</b>.');
	break;

case 'person_facts':
	$title=i18n::translate('Facts Privacy settings by ID');
	$text=i18n::translate('These settings define facts that are hidden for a specific person, family, or source and the level at which they are hidden.  This only applies to level 1 fact records such as BIRT or DEAT that will appear with their own headings on the relevant details page  of the person, family, or source.<br /><br />The first element is the ID of the person, family, or source. The second element is the fact.  The <b>Choice</b> element specifies the fact itself or related details.  The <b>Show to?</b> element determines at what access level the fact is shown.  Not all facts shown in the list are applicable to all types of IDs.  For example, Birth and Death facts are not relevant to Source records.<br /><br />The $person_facts array works the same as the $global_facts array except that you also specify the GEDCOM ID of the person you want to hide facts for. You could, for example, hide the marriage record for a specific person.');
	break;

case 'person_privacy':
	$title=i18n::translate('Privacy settings by ID');
	$text=i18n::translate('These settings allow administrators to override default privacy settings for a particular person, family, source, or media object.<br /><br />Suppose for example you have a child who died in infancy. Normally because the child is dead, its details would be shown to public users. However, you and everyone else in your family are still private. You don\'t want to remove the death record for the child but you want to hide the details and make them private. If this child had the ID of I100 you should enter the following privacy settings:<br />ID: I100<br />Show to: Show only to authenticated users<br /><br />This works the other way as well. If you wanted to make public the details of someone (ID I101) who you know to be dead but don\'t have a death date for, you could add the following:<br />ID: I101<br />Show to: Show to public');
	break;

case 'phpinfo':
	$title=i18n::translate('PHP information');
	$text=i18n::translate('This page provides extensive information about the server on which PhpGedView is being hosted.  Many configuration details about the server\'s software, as it relates to PHP and PhpGedView, can be viewed.');
	break;

case 'ppp_default_form':
	$title=i18n::translate('ppp_default_form');
	$text=i18n::translate('<b>DEFAULT ORDER</b><br />This means that there is no place encoding format declared in this GEDCOM file and the default format is assumed.<br /><br />If another format had been found, it would have been shown between the <b>(</b> and <b>)</b> at the end of the line.');
	break;

case 'ppp_levels':
	$title=i18n::translate('ppp_levels');
	$text=i18n::translate('This shows the levels that are displayed now.  The list box showing places is actually a sublist of the leftmost level.<br /><br />EXAMPLE:<br />The default order is City, County, State/Province, Country.<br />If the current level is "Top Level", the box will list all the countries in the database.<br />If the current level is "U.S.A., Top Level", the box will list all the states in the U.S.A.<br />etc.<br /><br />You can click a level to go back one or more steps.');
	break;

case 'ppp_match_one':
	$title=i18n::translate('ppp_match_one');
	$text=i18n::translate('GEDCOM ORDER<br />The locations are assumed to be encoded in the place format explicitly declared in the GEDCOM file.  This overrules the default order.');
	break;

case 'ppp_name_list':
	$title=i18n::translate('ppp_name_list');
	$text=i18n::translate('This box will display a complete list of individuals and families that are connected to that location.<br /><br />The names in the list are clickable.  When you click a name, you will be taken to the relevant Detail page.  When you click on a location at the top of this list, you change your Location selection.');
	break;

case 'ppp_numfound':
	$title=i18n::translate('ppp_numfound');
	$text=i18n::translate('The number of matches that are found is displayed here.  If you still see a list box with locations you have not yet reached the lowest level.<br /><br />You can choose to view all records connected to this place or you can refine your search by clicking another location.');
	break;

case 'ppp_placelist':
	$title=i18n::translate('ppp_placelist');
	$text=i18n::translate('In this list you can see the locations that are found subordinate to the current location you have chosen.  If you have not yet selected a place, you will see a list of all of the top level locations (e.g. countries or states).<br /><br />The names of the locations in the list are clickable; clicking on a location works like a filter, you will be taken to the next level down.');
	break;

case 'ppp_view_records':
	$title=i18n::translate('ppp_view_records');
	$text=i18n::translate('Clicking on this link will show you a list of all of the individuals and families that have events occurring in this place.  When you get to the end of a place hierarchy, which is normally a town or city, the name list will be shown automatically.');
	break;

case 'preview_faq_item':
	$title=i18n::translate('Preview all FAQ items');
	$text=i18n::translate('This option lets an admin user view the FAQ page without all the editing options and links.<br /><br />Except for a single <b>Edit</b> link above the first FAQ item, the appearance of the FAQ page will be identical to what an ordinary user would see. This special <b>Edit</b> link will restore full Edit functionality to the FAQ page.');
	break;

case 'preview':
	$title=i18n::translate('Preview');
	$text=i18n::translate('Clicking the Printer-friendly Version link will remove the items that don\'t look good on a printed page (menus, input boxes, extra links, the question marks for the contextual help, etc.)<br /><br />On the Printer-friendly version of the page, you will get a <b>Print</b> link at the bottom of the page. Just click it and your printer dialog will pop up. After printing, just click the <b>Back</b> link and the screen will be rebuilt normally.<br /><br />Note: Although the "Printer-friendly version" removes many links from the displayed page, the remaining links are still clickable.');
	break;

case 'privacy_error':
	$title=i18n::translate('This information is private and cannot be shown.');
	$text=i18n::translate('There are several possible reasons for this message:<br /><br /><ul><li><b>Information on living people is set to "Private"</b><br />Visitors and registered users who are not logged in can see full information only for deceased individuals. If allowed by the system administrator, visitors may register for an account by clicking the Login button, then the Request new password link.<br /></li><li><b>You are a user with user name and password...</b><br />But you have not logged in successfully or you have been inactive for a while and your session timed out.<br /></li><li><b>Due to privacy</b><br />The person does not want to be shown at all (Hidden) and may have asked the admin to set him or her to "Private".  Privacy can be set to:<br /><ol><li>Show only to authenticated users</li><li>Show only to admin users</li><li>Hide even from admin users</li></ol></li><li><b>Out of "Relation Path"</b><br />Even if you are a regular user <u>and</u> logged in, it can still happen that you see this message if the person you are trying to view is not related to you within the number of relationship steps (Relation Path length) set by the site administrator for this GEDCOM.<br /><br />Examples:<br />When the Relation Path length is set to <b>1</b>, you can only see the details of your own family, father, mother, brother, sister (but not the spouses and children of your brother or sister)<br /><br />When the Relation Path is set to <b>2</b>, you can also see the details of your brother\'s wife and their children (but not the spouses of their children).<br /><br />The higher the Relation Path length setting, the more remote relatives you can see.<br /></li></ul><br />If you think that you qualify to see certain hidden details, please contact the site administrator.  Use the contact link on any page.');
	break;

case 'random_media_ajax_controls':
	$title=i18n::translate('Show slideshow controls?');
	$text=i18n::translate('You can use this setting to show or hide the slideshow controls of the Random Media block.<br /><br />These controls allow the user to jump to another random object or to play through randomly selected media like a slideshow. The slideshow changes the contents of the block without preloading information from the server and without reloading the entire page.');
	break;

case 'random_media_filter':
	$title=i18n::translate('random_media_filter');
	$text=i18n::translate('You can restrict what the Random Media block is permitted to show according to the format and type of media item.  When a given checkbox is checked, the Random Media block is allowed to display media items of that format or type.<br /><br />Format or Type codes that exist in your database but are not in these checkbox lists are assumed to have the corresponding checkbox checked.  For example, if your database contains Media objects of format <b><i>pdf</i></b>, the Random Media block is always permitted to display them.  Similarly, if your database contains Media objects of type <b><i>special</i></b>, the Random Media block is always permitted to display them.');
	break;

case 'random_media_persons_or_all':
	$title=i18n::translate('Show only persons, events, or all?');
	$text=i18n::translate('This option lets you determine the type of media to show.<br /><br />When you select <b>Persons</b>, only media associated with persons will be shown.  Usually, this would be a person\'s photograph.  When you select <b>Events</b>, only media associated with facts or events will be shown.  This might be an image of a certificate.  When you select <b>ALL</b>, this block will show all types of media.');
	break;

case 'random_media_start_slide':
	$title=i18n::translate('Start slideshow on page load?');
	$text=i18n::translate('Should the slideshow start automatically when the page is loaded.<br /><br />The slideshow changes the contents of the block without preloading information from the server and without reloading the entire page.');
	break;

case 'readme':
	$title=i18n::translate('readme');
	$text=i18n::translate('See <a href="readme.txt" target="_blank"><b>Readme.txt</b></a> for more information.');
	break;

case 'recent_changes':
	$title=i18n::translate('Recent Changes');
	$text=i18n::translate('This block shows you the most recent changes to the GEDCOM as recorded by the CHAN GEDCOM tag.');
	break;

case 'register_comments':
	$title=i18n::translate('register_comments');
	$text=i18n::translate('Use this field to tell the site administrator why you are requesting an account and how you are related to the genealogy displayed on this site.  You can also use this to enter any other comments you may have for the site administrator.');
	break;

case 'register_gedcomid':
	$title=i18n::translate('register_gedcomid');
	$text=i18n::translate('Every person in the database has a unique ID number on this site.  If you know the ID number for your own record, please enter it here.  If you don\'t know your ID number or could not find it because of privacy settings, please provide enough information in the Comments field to help the site administrator identify who you are on this site so that he can set the ID for you.');
	break;

case 'relationship_id':
	$title=i18n::translate('relationship_id');
	$text=i18n::translate('If you have jumped from another page to this one by having clicked the <b>Relation to me</b> link, you will see here the relationship between yourself and that other individual.<br /><br />If you arrived at this page through the <b>Relationship Chart</b> menu entry on any page header, you have to type the identifier numbers of the two people whose relationship you wish to see.  If you don\'t know the identifier of the desired person, you can click the <b>Find ID</b> link.');
	break;

case 'remember_me':
	$title=i18n::translate('Remember me from this computer?');
	$text=i18n::translate('Checking this box when you login will allow PhpGedView to remember you the next time you visit so that you don\'t have to login again.  This feature will set a cookie on your computer which your browser will send back to the site the next time you visit.<br /><br />When you return to the site you will be able to access private information and user pages, but in order to edit or administer, you will be required to enter your username and password again.<br /><br /><b>You should not check this box if you are logging in from a public computer or from a computer that you share with others; anyone can return to PhpGedView as if they were you.</b>');
	break;

case 'remove_person':
	$title=i18n::translate('Remove Person');
	$text=i18n::translate('Click this link to remove the person from the timeline.');
	break;

case 'remove_tags':
	$title=i18n::translate('remove_tags');
	$text=i18n::translate('Checking this option will remove any custom tags that may have been added to the records by PhpGedView.<br /><br />Custom tags used by PhpGedView include the <b>_PGVU</b> tag which identifies the user who changed a record online and the <b>_THUM</b> tag which tells PhpGedView that the image should be used as a thumbnail.<br /><br />Custom tags may cause errors when importing the downloaded GEDCOM to another genealogy application.');
	break;

case 'reorder_children':
	$title=i18n::translate('Re-order children');
	$text=i18n::translate('Children are displayed in the order in which they appear in the family record.  Children are not automatically sorted by birth date because often the birth dates of some of the children are uncertain but the order of their birth <u>is</u> known.<br /><br />This option will allow you to change the order of the children within the family\'s record.  Since you might want to sort the children by their birth dates, there is a button you can press that will do this for you.<br /><br />You can also drag-and-drop any information box to change the order of the children.  As you move the mouse cursor over an information box, its shape will change to a pair of double-headed crossed arrows. If you push and hold the left mouse button before moving the mouse cursor, the information box will follow the mouse cursor up or down in the list.  As the information box is moved, the other boxes will make room.  When you release the left mouse button, the information box will take its new place in the list.');
	break;

case 'reorder_families':
	$title=i18n::translate('Reorder Families');
	$text=i18n::translate('Families on the Close Relatives tab are displayed in the order in which they appear in the individual\'s GEDCOM record.  Families are not sorted by the marriage date because often the marriage dates are unknown but the order of the marriages <u>is</u> known.<br /><br />This option will allow you to change the order of the families in which they are listed on the Close Relatives tab.  If you want to sort the families by their marriage dates, there is a button you can press that will automatically do this for you.');
	break;

case 'repolist_listbox':
	$title=i18n::translate('repolist_listbox');
	$text=i18n::translate('In this box you see the names of the repositories as they are stored in the database.<br /><br />The names are displayed in alphabetical order.<br /><br />When you click on a name in the list, you will go to the Repository Information page where you will see a list of the sources that are linked to that repository.');
	break;

case 'repos_listbox':
	$title=i18n::translate('repos_listbox');
	$text=i18n::translate('In this box you see the names of sources that are linked to the repository.<br /><br />The names are displayed in alphabetical order.<br /><br />When you click on a name in the list, you will go to the Detail page of that source.');
	break;

case 'restore_faq_edits':
	$title=i18n::translate('Restore FAQ edit functionality');
	$text=i18n::translate('This option restores the FAQ page to what an admin user normally sees, so that individual FAQ items may be edited.');
	break;

case 'review_changes':
	$title=i18n::translate('Review GEDCOM Changes');
	$text=i18n::translate('This block will list all of the records that have been changed online and that still need to be reviewed and accepted into the database.');
	break;

case 'rootid':
	$title=i18n::translate('Pedigree Chart Root Person');
	$text=i18n::translate('If you want to display a chart with a new starting (root) person, the ID of that new starting person is typed here.<br /><br />If you don\'t know the ID of that person, use the <b>Find ID</b> link.<br /><br /><b>ID NUMBER</b><br />The ID numbers used inside PhpGedView are <u>not</u> the identification numbers issued by various governments (driving permit or passport numbers, for instance).  The ID number referred to here is simply a number used within the database to uniquely identify each individual; it was assigned by the ancestry program that created the GEDCOM file which was imported into PhpGedView.');
	break;

case 'rss_feed':
	$title=i18n::translate('rss_feed');
	$text=i18n::translate('The ATOM/RSS feed available in PhpGedView allows anyone to view, using a suitable feed aggregator, the contents of your site\'s Welcome page without visiting the site. Most aggregators will pop up a notice letting the user know when something has changed on a page being monitored. This essentially allows anyone to monitor your PhpGedView site without needing to visit it regularly.<br /><br />The Feed block is used to customize the link to the feed, allowing specific feed types (most readers can deal with most types so this can usually be left at the default), and the specific module you would like in your feed. The language of the feed and the GEDCOM used will be based on the language and GEDCOM active in PhpGedView when you select the feed.<br /><br />The types of feed that can be generated include ATOM, RSS 2.0, RSS 1.0, RSS 0.92, HTML and JavaScript. The first four types are for feed aggregators, while JavaScript and HTML are meant to enable inclusion of the feeds in other web pages.  Note that the numbers of the RSS feed indicate different styles, not a different version.<br /><br />There is an option to select authentication that will log the user in, and allow the user to view, using a suitable RSS aggregator, any information that he could normally view if logged in. Basic Authentication uses <i>Basic HTTP Authentication</i> to log the user in. Future enhancements might allow <i>Digest Authentication</i>.<br /><br />This <a href=\'http://en.wikipedia.org/wiki/RSS_(file_format)\' target=\'_blank\' alt=\'Wikipedia article\' title=\'Wikipedia article\'><b>Wikipedia article</b></a> contains comprehensive information and links about RSS and the various RSS formats. <i>Basic HTTP Authentication</i> is discussed in this <a href=\'http://en.wikipedia.org/wiki/Basic_authentication_scheme\' target=\'_blank\' alt=\'Wikipedia article\' title=\'Wikipedia article\'><b>Wikipedia article</b></a>, while <i>Digest Authentication</i> is discussed in this <a http://en.wikipedia.org/wiki/Digest_access_authentication\' target=\'_blank\' alt=\'Wikipedia article\' title=\'Wikipedia article\'><b>Wikipedia article</b></a>.');
	break;

case 'savelang':
	$title=i18n::translate('Save');
	$text=i18n::translate('Save edited message to language file.');
	break;

case 'search_enter_terms':
	$title=i18n::translate('search_enter_terms');
	$text=i18n::translate('In this Search box you can enter criteria such as dates, given names, surnames, places, multimedia, etc.<br /><br /><b>Wildcards</b><br />Wildcards, as you probably know them (like * or ?), are not allowed, but the program will automatically assume wildcards.<br /><br />Suppose you type in the Search box the following: <b>Pete</b>.  The result could be, assuming the names are in the database:<div style="padding-left:30px;"><b>Pete</b> Smith<br /><b>Pete</b>r Johnes<br />Will <b>Pete</b>rson<br />somebody --Born 01 January 1901 <b>Pete</b>rsburg<br />etc.</div><br /><b>Dates</b><br />Typing a year in the Search box will result in a list of individuals who are somehow connected to that year.<br /><br />If you type <b>1950</b>, the result will be all individuals with an event that occurred in 1950.  These events could be births, deaths, marriages, Bar Mitzvahs, LDS Sealings, etc.<br /><br />If you type <b>4 Dec</b>, all persons connected to an event that occurred on 4 December of whatever year will be listed.  Persons connected to an event on 14 or 24 December will be listed as well.  As you see, wildcards are always assumed, so you do not have to type them.  Sometimes, the results can be surprising.<br /><br /><b>Proper dates</b><br />PhpGedView searches for data, as they are stored in the GEDCOM file.  If, for example, you want to search for an event on December 14, you should type <b>14&nbsp;dec</b> because this is how the date is stored in the database.<br /><br />If you were to type <b>dec&nbsp;14</b>, the result could be a person connected to an event on 08&nbsp;<b>dec</b>ember&nbsp;18<b>14</b>.  Again, the results can be surprising.<br /><br />You can use regular expressions in your search if you are familiar with them.  For example, if you wanted to find all of the people who have dates in the 20th century, you could enter the search <b>19[0-9][0-9]</b> and you would get all of the people with dates from 1900-1999.<br /><br />If you need more help with this searching system, please let us know, so that we can extend this Help file as well.<br /><br />~Search the way you think the name is written (Soundex)~<br /><br />Soundex is a method of coding words according to their pronunciation.  This allows you to search the database for names and places when you don\'t know precisely how they are written.  PhpGedView supports two different Soundex algorithms that produce vastly different results.<ul><li><b>Basic</b><br />This method, patented in 1918 by Russell, is very simple and can be done by hand.<br /><br />Because the Basic method retains the first letter of the name as part of the resultant code, it is not very helpful when you are unsure of that first letter.  The Basic algorithm is not well suited to names that were originally in languages other than English, and even with English names the results are very surprising.  For example, a Basic Soundex search for <b>Smith</b> will return not only <b>Smith, Smid, Smit, Schmidt, Smyth, Smithe, Smithee, Schmitt</b>, all of which are clearly variations of <b>Smith</b>, but also <b>Smead, Sneed, Smoote, Sammett, Shand,</b> and <b>Snoddy</b>.  <br /><br /></li><li><b>Daitch-Mokotoff</b><br />This method, developed in 1985, is much more complex than the Basic method and is not easily done by hand.<br /><br />A Soundex search using this method produces much more accurate results.</li></ul>For details on both Soundex algorithms, visit this <a href="http://www.jewishgen.org/infofiles/soundex.html" target=_blank><b>Jewish Genealogical Society</b></a> web page.<br /><br /> ~Search and Replace~<br /><br />Here, you can search for a misspelling or other inaccurate information and replace it with correct information.<br /><br /><b>Searching</b><br />This feature performs searching just like a <a href="help_text.php?help=search_enter_terms_help">normal search</a>.<br /><br /><b>Replacing</b><br />All instances of the search term that are found are replaced by the replacement term in the database.<br /><br /><b>For Example...</b><br />Suppose you accidentally misspell your great-grandpa Michael\'s name.  You accidentally entered \'Micheal.\' <br /><br />You would type <b>Micheal</b> in the Search box, and <b>Michael</b> in the Replace box.<br />Every instance of "Micheal" would then be replaced by "Michael"<br /><br /><b>Search for...</b><br />Select the scope of the search.  You can limit the search to names or places, or apply no limit (search everything).  The <i>Whole words only</i> option will only search for your term in the place field as a whole word.  This means that searching for <i>UT</i> would only match <b>UT</b> and not <i>UT</i> in the other words such as Connectic<b>ut</b>.<br /><br />Don\'t worry if you accidentally replace something where you don\'t want to.  Just click the "Accept/Reject Changes" link at the bottom of the page to accept the changes you want, and reject the changes you don\'t want.<br /><br />If you need more help with this searching system, please let us know, so that we can improve this Help file as well.');
	break;

case 'search_exclude_tags':
	$title=i18n::translate('search_exclude_tags');
	$text=i18n::translate('The <b>Exclude some non-genealogical data</b> choice will cause the Search function to ignore the following GEDCOM tags:<div style="padding-left:30px;"><b>_PGVU</b> - Last change by<br /><b>CHAN</b> - Last change date<br /><b>FILE</b> - External File<br /><b>FORM</b> - Format<br /><b>TYPE</b> - Type<br /><b>SUBM</b> - Submitter<br /><b>REFN</b> - Reference Number</div><br />In addition to these optionally excluded tags, the Search function always excludes these tags:<div style="padding-left:30px;"><b>_UID</b> - Globally unique Identifier<br /><b>RESN</b> - Restriction</div>');
	break;

case 'search_include_ASSO':
	$title=i18n::translate('search_include_ASSO');
	$text=i18n::translate('This option causes PhpGedView to show all individuals who are recorded as having an association relationship to the person or family that was found as a direct result of the search.  The inverse, where all persons or families are shown when a person found as a direct result of the search has an association relationship to these other persons or families, is not possible.<br /><br />Example:  Suppose person <b>A</b> is godparent to person <b>B</b>.  This relationship is recorded in the GEDCOM record of person <b>B</b> by means of an ASSO tag.  No corresponding tag exists in the GEDCOM record of person <b>A</b>.<br /><br />When this option is set to <b>Yes</b> and the Search results list includes <b>B</b>, <b>A</b> will be included automatically because of the ASSO tag in the GEDCOM record of <b>B</b>.  However, if the Search results list includes <b>A</b>, <b>B</b> will not be included automatically since there is no matching ASSO tag in the GEDCOM record of person <b>A</b>.');
	break;

case 'search_replace':
	$title=i18n::translate('Search and Replace');
	$text=i18n::translate('Here, you can search for a misspelling or other inaccurate information and replace it with correct information.<br /><br /><b>Searching</b><br />This feature performs searching just like a <a href="help_text.php?help=search_enter_terms_help">normal search</a>.<br /><br /><b>Replacing</b><br />All instances of the search term that are found are replaced by the replacement term in the database.<br /><br /><b>For Example...</b><br />Suppose you accidentally misspell your great-grandpa Michael\'s name.  You accidentally entered \'Micheal.\' <br /><br />You would type <b>Micheal</b> in the Search box, and <b>Michael</b> in the Replace box.<br />Every instance of "Micheal" would then be replaced by "Michael"<br /><br /><b>Search for...</b><br />Select the scope of the search.  You can limit the search to names or places, or apply no limit (search everything).  The <i>Whole words only</i> option will only search for your term in the place field as a whole word.  This means that searching for <i>UT</i> would only match <b>UT</b> and not <i>UT</i> in the other words such as Connectic<b>ut</b>.<br /><br />Don\'t worry if you accidentally replace something where you don\'t want to.  Just click the "Accept/Reject Changes" link at the bottom of the page to accept the changes you want, and reject the changes you don\'t want.<br /><br />If you need more help with this searching system, please let us know, so that we can improve this Help file as well.');
	break;

case 'setperms':
	$title=i18n::translate('Set Media Permissions');
	$text=i18n::translate('Recursively set the permissions on the protected (not web-addressable) <b>#GLOBALS[MEDIA_FIREWALL_ROOTDIR]##GLOBALS[MEDIA_DIRECTORY]#</b> and the normal <b>#GLOBALS[MEDIA_DIRECTORY]#</b> directories to either world-writable or read-only.');
	break;

case 'showUnknown':
	$title=i18n::translate('Show unknown gender');
	$text=i18n::translate('Hide or show the list of given names of persons of unknown gender.<br /><br />The Top 10 Given Names block always hides the list of given names when no persons of that gender exist in your database.  This option lets you hide the list of persons of unknown gender even when there are such persons in your database.');
	break;

case 'show_age_marker':
	$title=i18n::translate('show_age_marker');
	$text=i18n::translate('If you check this box, you will see an Age marker.<br /><br />You can slide this Age marker up or down along the time line.  The sliding Age marker is a nice tool to check the age of a person at a certain event.  You can enable or disable the Age marker individually for each person in the chart.');
	break;

case 'show_changes':
	$title=i18n::translate('This record has been updated.  Click here to show changes.');
	$text=i18n::translate('When you see this message, it means two things:<ol><li>Somebody has made changes to the GEDCOM<br />Records may have been added, deleted, or changed.</li><li>The changes have not yet been accepted by the administrator.<br />Once the changes have been accepted or rejected, you will no longer see this message.</li></ol>You can see what changes have been made when you click the link.  If you notice that a change is not correct, please notify the admin.');
	break;

case 'show_context':
	$title=i18n::translate('show_context');
	$text=i18n::translate('Show Contextual Help');
	break;

case 'show_fact_sources':
	$title=i18n::translate('Show all sources');
	$text=i18n::translate('When this option is checked, you can see all Source or Note records for this person.  When this option is unchecked, Source or Note records that are associated with other facts for this person will not be shown.');
	break;

case 'show_fam_gedcom':
	$title=i18n::translate('show_fam_gedcom');
	$text=i18n::translate('The information about the family, as it is stored in the database, will be displayed when you click this link.  The display will show raw GEDCOM data.');
	break;

case 'show_fam_timeline':
	$title=i18n::translate('show_fam_timeline');
	$text=i18n::translate('When you click this link you will jump to the Timeline page, where all facts of the couple will be displayed on a timeline scale.');
	break;

case 'show_full':
	$title=i18n::translate('show_full');
	$text=i18n::translate('With this option you can either show or hide all details in the Name boxes.  You can display more boxes on one screen when the details are hidden.<br /><br />When all details are hidden, the Zoom icon described below is not shown.  However, if the administrator has enabled the Zoom function, the entire box will act like a Zoom icon to reveal full details about the person.<br /><br />When the details are not hidden and the Zoom function, identified by a magnifying glass icon, has been enabled by the administrator, you can reveal even more details about that person.  If you normally have to click on the Zoom icon to zoom in, you can reveal additional hidden details by clicking that icon here.  Similarly, if you can zoom in by hovering over the Zoom icon, hidden details will be revealed by hovering over that icon here.<br /><br />If you have clicked on the Zoom icon to reveal more details, you can restore the box to its normal level of detail by clicking on the Zoom icon again.  If you have revealed more details by simply moving the mouse pointer over the Zoom icon, the box will be restored to its normal level of detail when you move the mouse pointer away from the Zoom icon.');
	break;

case 'show_marnms':
	$title=i18n::translate('Include married names');
	$text=i18n::translate('The individual and family list pages can either include or exclude married names.  This option can be helpful when searching for individuals or families where you only know the married name.  Married names can only be included if they already exist in the database.<br /><br />On the family list, this value defaults to exclude.  On the individual list, the default value is set in the GEDCOM Configuration page.<br /><br />When you change this option, your selection will be remembered until you log off or your session ends.');
	break;

case 'show_repo_gedcom':
	$title=i18n::translate('show_repo_gedcom');
	$text=i18n::translate('When you click this link, the information about the repository, as it is stored in the database, will be displayed in a popup window.');
	break;

case 'show_source_gedcom':
	$title=i18n::translate('show_source_gedcom');
	$text=i18n::translate('When you click this link, the source\'s records will be displayed in raw database format.');
	break;

case 'show_spouse':
	$title=i18n::translate('show_spouse');
	$text=i18n::translate('By default this chart does not show spouses for the descendants because it makes the chart harder to read and understand.  Turning this option on will show spouses on the chart.');
	break;

case 'show_thumb':
	$title=i18n::translate('show_thumb');
	$text=i18n::translate('Thumbnails will be shown if you check this box.');
	break;

case 'simple_filter':
	$title=i18n::translate('simple_filter');
	$text=i18n::translate('Simple search filter based on the characters entered, no wildcards are accepted.');
	break;

case 'skip_sublist':
	$title=i18n::translate('skip_sublist');
	$text=i18n::translate('The standard setting is that, after you have clicked a letter of the Alphabetical index, you will get a sub-list with surnames.  If you click this link, all individuals with surnames that have the currently selected initial letter will be displayed immediately. Thereafter, the list of individuals will be displayed directly whenever you click on a new initial letter in the Alphabetical list.<br /><br />To reverse this action, click on the Show Surname lists link.');
	break;

case 'sort_style':
	$title=i18n::translate('Sort Style');
	$text=i18n::translate('This option controls how the information is sorted.<br /><br />When you select <b>Alphabetically</b>, the information is shown in alphabetical order. When you select <b>By Anniversary</b>, the information is ordered by anniversary, with the most recent anniversaries first.');
	break;

case 'sortby':
	$title=i18n::translate('Sequence');
	$text=i18n::translate('Select the order in which you wish to see the list.');
	break;

case 'soundex_search':
	$title=i18n::translate('Search the way you think the name is written (Soundex)');
	$text=i18n::translate('Soundex is a method of coding words according to their pronunciation.  This allows you to search the database for names and places when you don\'t know precisely how they are written.  PhpGedView supports two different Soundex algorithms that produce vastly different results.<ul><li><b>Basic</b><br />This method, patented in 1918 by Russell, is very simple and can be done by hand.<br /><br />Because the Basic method retains the first letter of the name as part of the resultant code, it is not very helpful when you are unsure of that first letter.  The Basic algorithm is not well suited to names that were originally in languages other than English, and even with English names the results are very surprising.  For example, a Basic Soundex search for <b>Smith</b> will return not only <b>Smith, Smid, Smit, Schmidt, Smyth, Smithe, Smithee, Schmitt</b>, all of which are clearly variations of <b>Smith</b>, but also <b>Smead, Sneed, Smoote, Sammett, Shand,</b> and <b>Snoddy</b>.  <br /><br /></li><li><b>Daitch-Mokotoff</b><br />This method, developed in 1985, is much more complex than the Basic method and is not easily done by hand.<br /><br />A Soundex search using this method produces much more accurate results.</li></ul>For details on both Soundex algorithms, visit this <a href="http://www.jewishgen.org/infofiles/soundex.html" target=_blank><b>Jewish Genealogical Society</b></a> web page.');
	break;

case 'sourcelist_listbox':
	$title=i18n::translate('sourcelist_listbox');
	$text=i18n::translate('In this box you see the names of the sources as they are stored in the GEDCOM.<br /><br />The names are displayed in alphabetical order.  When you click on a name in the list, you will go to the Source page where a list is displayed.  This list shows you which individuals or families are linked to that source.');
	break;

case 'sources_listbox':
	$title=i18n::translate('sources_listbox');
	$text=i18n::translate('In this box you see the names of individuals and families that are connected to the source.<br /><br />The names are displayed in alphabetical order.  When you click on a name in the list, you will go to the relevant Detail page.');
	break;

case 'start_admin':
	$title=i18n::translate('start_admin');
	$text=i18n::translate('+++ Begin extra Admin Info +++');
	break;

case 'stat':
	$title=i18n::translate('stat');
	$text=i18n::translate('A number of different plots of statistics from your database can be produced.<br /><br />Select the chart, then adjust the options from the drop-down boxes.<br /><br />The numbers included in each plot depend on the data available. For example, individuals without a month of birth (e.g. just \'1856\') cannot be included in a plot of births by month.');
	break;

case 'style':
	$title=i18n::translate('Presentation Style');
	$text=i18n::translate('This option controls how the information is presented.<br /><br />When you select <b>List</b>, the information is shown in text form, similar to what you see in the various Chart boxes.  This format is well suited to blocks that print on the right side of the page.<br /><br />When you select <b>Table</b>, the information is shown in tabular format, and is more suited to the larger blocks that print on the left side of the page.');
	break;

case 'talloffset':
	$title=i18n::translate('talloffset');
	$text=i18n::translate('With this option you determine the page layout orientation.<br /><br />Changing this setting might be useful if you want to make a screen print or if you have a different type of screen.<ul><li><b>Portrait</b> mode will make the tree taller, such that a 4 generation chart should fit on a single page printed vertically.</li><li><b>Landscape</b> mode will make a wider tree that should print on a single page printed horizontally.</li><li><b>Oldest at top</b> mode rotates the chart, but not its boxes, by 90 degrees counter-clockwise, so that the oldest generation is at the top of the chart.</li><li><b>Oldest at bottom</b> mode rotates the chart, but not its boxes, by 90 degrees clockwise, so that the oldest generation is at the bottom of the chart.</li></ul');
	break;

case 'text_direction':
	$title=i18n::translate('Text direction');
	$text=i18n::translate('Identifies which direction is used to write text in the chosen language.  Most languages are written from left to right.  Arabic and Hebrew are examples of languages that are written in the opposite direction (right to left).');
	break;

case 'text_faq':
	$title=i18n::translate('text_faq');
	$text=i18n::translate('The FAQ (Frequently Asked Questions) page can contain an overview or a list of questions and answers on the use of this genealogy site.<br /><br />The use to which the FAQ page is put is entirely up to the site administrator. The site administrator controls the content of each item and also the order in which the items are shown on the page.');
	break;

case 'time_format':
	$title=i18n::translate('Time format');
	$text=i18n::translate('This field defines the time format to be used by PhpGedView.<br /><br />Symbols you can use are:<br /><b>a</b> - lowercase Ante meridiem and Post meridiem; i.e. <b>am</b> or <b>pm</b><br /><b>A</b> - uppercase Ante meridiem and Post meridiem; i.e. <b>AM</b> or <b>PM</b><br /><b>B</b> - Swatch Internet time; i.e. <b>000</b> through <b>999</b><br /><b>c</b> - ISO 8601 date (added in PHP 5); e.g. <b>2004-02-12T15:19:21+00:00</b><br /><b>d</b> - day of the month, 2 digits, leading zero; i.e. <b>01</b> to <b>31</b><br /><b>D</b> - day, textual, three letters; e.g. <b>Mon</b><br /><b>F</b> - month, textual, long; e.g. <b>January</b><br /><b>g</b> - hour, 12-hour format, no leading zero; i.e. <b>1</b> through <b>12</b><br /><b>G</b> - hour, 24-hour format, no leading zero; i.e. <b>0</b> through <b>23</b><br /><b>h</b> - hour, 12-hour format, leading zero; i.e. <b>01</b> through <b>12</b><br /><b>H</b> - hour, 24-hour format, leading zero; i.e. <b>00</b> through <b>23</b><br /><b>i</b> - minutes, leading zero; i.e. <b>00</b> to <b>59</b><br /><b>I</b> (capital "i") - daylight saving time indicator; <b>1</b> if Daylight Saving Time, <b>0</b> otherwise.<br /><b>j</b> - day of the month, no leading zero; i.e. <b>1</b> to <b>31</b><br /><b>l</b> (lowercase "L") - day of the week, textual, long; e.g. <b>Friday</b><br /><b>L</b> - leap year indicator; <b>1</b> if it is a leap year, <b>0</b> otherwise.<br /><b>m</b> - month, numeric, leading zero; i.e. <b>01</b> through <b>12</b><br /><b>M</b> - month, textual, 3 letters; e.g. <b>Jan</b><br /><b>n</b> - month, numeric, no leading zero; i.e. <b>1</b> through <b>12</b><br /><b>O</b> (capital "o") - difference between local and Greenwich time (GMT) in hours; e.g. <b>+0200</b><br /><b>r</b> - RFC 2822 formatted date; e.g. <b>Thu,&nbsp;21&nbsp;Dec&nbsp;2000nbsp;16:01:07&nbsp;+0200</b><br /><b>s</b> - seconds, leading zero; i.e. <b>00</b> through <b>59</b><br /><b>S</b> - day of the month suffix, English only, 2 characters; i.e. <b>st</b>, <b>nd</b>, <b>rd</b> or <b>th</b>. Works well with <b>j</b><br /><b>t</b> - number of days in the given month; i.e. <b>28</b> through <b>31</b><br /><b>T</b> - time zone setting of this machine; e.g. <b>CEST</b>, <b>EST</b>, <b>MDT</b><br /><b>U</b> - seconds since the start of the Unix Epoch (January&nbsp;1&nbsp;1970&nbsp;00:00:00 GMT)<br /><b>w</b> - day of the week, numeric; i.e. <b>0</b> (for Sunday) through <b>6</b> (for Saturday)<br /><b>W</b> - ISO-8601 week number of year, weeks starting on Monday; e.g. <b>42</b> (the 42nd week in the year)<br /><b>Y</b> - year, 4 digits; e.g. <b>1999</b><br /><b>y</b> - year, 2 digits; e.g. <b>99</b><br /><b>z</b> - day of year, numeric, no leading zeros; i.e. <b>0</b> (1 Jan) through <b>364</b> (31 Dec when not a leap year) or <b>365</b> (31 Dec in leap years)<br /><b>Z</b> - time zone offset in seconds.  The offset for time zones west of Greenwich is always negative, and for those east of Greenwich the offset is always positive. i.e. <b>-43200</b> through <b>43200</b><br /><br />You can change the order of these symbols, and you can add delimiters to the format.<br /><br />For example, in German this field normally contains <b>H:i:s</b> to produce a display like <b>09:01:25</b>. English usually defines the time format as <b>g:i:sa</b> to produce a display like <b>9:01:25am</b>.<br /><br />In the 24-hour system, midnight is 00:00.  In the 12-hour system, midnight is 12:00am and noon is 12:00pm.');
	break;

case 'time_limit':
	$title=i18n::translate('Time limit:');
	$text=i18n::translate('The maximum time the import is allowed to run for processing the GEDCOM file.');
	break;

case 'timeline_control':
	$title=i18n::translate('timeline_control');
	$text=i18n::translate('Click the drop down menu to change the speed at which the timeline scrolls.<br/><br/>~Begin Year~<br/>Enter the starting year of the range.<br/><br/>~End Year~<br/>Enter the ending year of the range.<br/><br/>~Search~<br/>Click the Search button to begin searching for events that occurred within the range identified by the Begin Year and End Year fields.');
	break;

case 'todo':
	$title=i18n::translate('todo');
	$text=i18n::translate('This block helps you keep track of <b>_TODO</b> tasks in the database.<br /><br />To add &quot;To Do&quot; tasks to your records, you may first need amend the GEDCOM configuration so that the <b>_TODO</b> fact is in the list of facts that can be added to the records of individuals, families, sources, and repositories.  Each of these lists, which you will find in the Edit Options section of the GEDCOM configuration, is independent.  The order of the list entries is not important; you can add the new entries at the beginning of each list.');
	break;

case 'todo_show_future':
	$title=i18n::translate('Show future tasks');
	$text=i18n::translate('Show &quot;To Do&quot; tasks that have a date in the future.  Otherwise only items with a date in the past are shown.');
	break;

case 'todo_show_other':
	$title=i18n::translate('Show other users\' tasks');
	$text=i18n::translate('Show &quot;To Do&quot; tasks assigned to other users');
	break;

case 'todo_show_unassigned':
	$title=i18n::translate('Show unassigned tasks');
	$text=i18n::translate('Show &quot;To Do&quot; tasks that are not assigned to any user');
	break;

case 'translation_forum':
	$title=i18n::translate('PhpGedView Translations forum on SourceForge');
	$text=i18n::translate('This <a href="http://sourceforge.net/forum/forum.php?forum_id=294245" target="_blank"><b>link</b></a> opens a new browser window.  You will be redirected to the Translations forum of PhpGedView, where you can discuss translation topics.');
	break;

case 'um_bu':
	$title=i18n::translate('um_bu');
	$text=i18n::translate('This tool can make a backup of several kinds of data in PhpGedView.<br /><br />The data you choose to back up will be gathered into a ZIP file, which you can download by clicking the link at the bottom of the page, after the backup has been successfully made.<br /><br />The ZIP file will remain in your Index directory until you remove it manually.');
	break;

case 'um_index_sql':
	$title=i18n::translate('This tool will import <i>authenticate.php</i> and other <i>.dat</i> files from your index directory into your database.');
	$text=i18n::translate('This tool will import <i>authenticate.php</i> and other <i>.dat</i> files from your index directory into your database.');
	break;

case 'um_sql_index':
	$title=i18n::translate('This tool will create <i>authenticate.php</i> and several <i>.dat</i> files in your index directory.<br /><br />After successful creation, you can switch to Index mode with all current users and their messages, favorites, news, and MyGedview layout available.<br /><br />Note: After switching to Index mode, you will need to import your GEDCOM files again.');
	$text=i18n::translate('This tool will create <i>authenticate.php</i> and several <i>.dat</i> files in your index directory.<br /><br />After successful creation, you can switch to Index mode with all current users and their messages, favorites, news, and MyGedview layout available.<br /><br />Note: After switching to Index mode, you will need to import your GEDCOM files again.');
	break;

case 'um_tool':
	$title=i18n::translate('um_tool');
	$text=i18n::translate('This tool will either export user data from SQL to Index mode, or import user data from Index files into SQL tables.<br /><br />User data, favorites, block definitions, messages, and news will be available again after migration.<br /><br /><b>CAUTION</b><br />You cannot use this tool to migrate user data between different versions of PhpGedView. Be sure that the data originates from, or is imported into the same PhpGedView version.<br /><br /><b>IMPORT</b><br />If you choose to import the user data files from Index mode, all user data present in the database tables will be <u>overwritten</u>. This tool does <u>not</u> merge the information. Once you have run the Import, there is no way to retrieve the old information using PhpGedView.<br /><br /><b>EXPORT</b><br />If you export the user information from your SQL database to Index Mode files, this tool will create <i>authenticate.php</i> and several <i>.dat</i> files in your index directory. If identically named files are already present, you will be prompted if they must be overwritten. After switching to Index mode, all information will be available directly.<br /><br /><b>Note:</b> After switching to Index mode, you will need to import your GEDCOM files again.');
	break;

case 'upload_gedcom':
	$title=i18n::translate('Upload GEDCOM');
	$text=i18n::translate('Unlike the <b>Add GEDCOM</b> function, the GEDCOM file you wish to add to your database does not have to be on your server.<br /><br />In Step 1 you select a GEDCOM file from your local computer. Type the complete path and file name in the text box or use the <b>Browse</b> button on the page.<br /><br />You can also use this function to upload a ZIP file containing the GEDCOM file. PhpGedView will recognize the ZIP file and extract the file and the filename automatically.<br /><br />If a GEDCOM file with the same name already exists in PhpGedView, it will, after your confirmation, be overwritten. However, all GEDCOM settings made previously will be preserved.<br /><br />You will find more help on other pages of the procedure.');
	break;

case 'upload_media_file':
	$title=i18n::translate('upload_media_file');
	$text=i18n::translate('In this field you specify the location and name, on your local computer, of the media file you wish to upload to the server.  You can use the <b>Browse</b> button to search your local computer for the desired file.<br /><br />The uploaded file will have the same name on the server, and it will be uploaded to the directory specified in the <b>Folder on server</b> field.<br /><br />If you do not see the <b>Folder on server</b> field or cannot change it, you do not have sufficient permissions or your GEDCOM configuration has been set to allow no directory levels beyond the default <b>#GLOBALS[MEDIA_DIRECTORY]#</b>.  In this case, the media file will be uploaded to the directory <b>#GLOBALS[MEDIA_DIRECTORY]#</b>.');
	break;

case 'upload_media_folder':
	$title=i18n::translate('upload_media_folder');
	$text=i18n::translate('Your GEDCOM configuration allows up to #GLOBALS[MEDIA_DIRECTORY_LEVELS]# directory levels beyond the default <b>#GLOBALS[MEDIA_DIRECTORY]#</b> where uploaded media files are normally stored. This lets you organize your media files, and you don\'t need to be as concerned about maintaining unique names for each media file.<br /><br />In this field you specify the destination directory on your server where the uploaded media file is to be stored.  Be sure to pay attention to the case (upper or lower case) of what you enter or select here, since file and directory names are case sensitive.<br /><br />If the directory name you enter here does not exist, it will be created automatically. If you enter more than the additional #GLOBALS[MEDIA_DIRECTORY_LEVELS]# directory levels permitted by your GEDCOM configuration, your input will be truncated accordingly.<br /><br />Thumbnails will be uploaded or created in an identical directory structure, starting with <b>#GLOBALS[MEDIA_DIRECTORY]#thumbs/</b>.');
	break;

case 'upload_media':
	$title=i18n::translate('Upload Media files');
	$text=i18n::translate('Select files from your local computer to upload to your server.  All files will be uploaded to the directory <b>#MEDIA_DIRECTORY#</b> or to one of its sub-directories.<br /><br />Folder names you specify will be appended to #MEDIA_DIRECTORY#. For example, #MEDIA_DIRECTORY#myfamily. If the thumbnail directory does not exist, it is created automatically.');
	break;

case 'upload_path':
	$title=i18n::translate('Upload path');
	$text=i18n::translate('This is the path where the GEDCOM file you wish to upload can be found. To select the path, click on <b>Browse</b> and navigate to your GEDCOM file and then click <b>Open</b>.');
	break;

case 'upload_server_file':
	$title=i18n::translate('upload_server_file');
	$text=i18n::translate('The media file you are uploading can be, and probably should be, named differently on the server than it is on your local computer.  This is so because often the local file name has meaning to you but is much less meaningful to others visiting this site.  Consider also the possibility that you and someone else both try to upload different files called "granny.jpg".<br /><br />In this field, you specify the new name of the file you are uploading.  The name you enter here will also be used to name the thumbnail, which can be uploaded separately or generated automatically.  You do not need to enter the file name extension (jpg, gif, pdf, doc, etc.)<br /><br />Leave this field blank to keep the original name of the file you have uploaded from your local computer.');
	break;

case 'upload_server_folder':
	$title=i18n::translate('upload_server_folder');
	$text=i18n::translate('The administrator has enabled up to #GLOBALS[MEDIA_DIRECTORY_LEVELS]# folder levels below the default <b>#GLOBALS[MEDIA_DIRECTORY]#</b>.  This helps to organize the media files and reduces the possibility of name collisions.<br /><br />In this field, you specify the destination folder where the uploaded media file should be stored.  The matching thumbnail file, either uploaded separately or generated automatically, will be stored in a similar folder structure starting at <b>#GLOBALS[MEDIA_DIRECTORY]#thumbs/</b> instead of <b>#GLOBALS[MEDIA_DIRECTORY]#</b>.  You do not need to enter the <b>#GLOBALS[MEDIA_DIRECTORY]#</b> part of the destination folder name.<br /><br />If you are not sure about what to enter here, you should contact your site administrator for advice.');
	break;

case 'upload_thumbnail_file':
	$title=i18n::translate('upload_thumbnail_file');
	$text=i18n::translate('In this field you specify the location and name, on your local computer, of the thumbnail file you wish to upload to the server.  You can use the <b>Browse</b> button to search your local computer for the desired file.  When this field is filled in, the <b>Automatic thumbnail</b> checkbox is ignored.<br /><br />If the <b>Media file to upload</b> field has been filled in, your uploaded thumbnail file will be named according to the contents of that field, regardless of what it is called on your local computer.  If that field is empty, the uploaded thumbnail file will be copied to two places on the server, once into the server directory mentioned in the <b>Folder on server</b> field, and then again into an identical directory structure starting with <b>#GLOBALS[MEDIA_DIRECTORY]#thumbs/</b>.<br /><br />If you do not see the <b>Folder on server</b> field or cannot change it, you do not have sufficient permissions or your GEDCOM configuration has been set to allow no directory levels beyond the default <b>#GLOBALS[MEDIA_DIRECTORY]#</b> where uploaded media files are normally stored.');
	break;

case 'user_privacy':
	$title=i18n::translate('User Privacy settings');
	$text=i18n::translate('These settings give administrators the ability to override default privacy settings for individuals in the GEDCOM based on Username.  Suppose you don\'t want the Username <b>John</b> to be able to see any details of ID I100 in the GEDCOM, you could configure it like this:<br />Username: John<br />ID: I100<br />Show?: "Hide"<br /><br />and details for the specified individual would be hidden for the Username "John" only.<br /><br />To show the details of I101 (which usually would be hidden because I101 is still alive) to Username "John" set:<br /><br />Username: John<br />ID: I101<br />Show?: "Show"');
	break;

case 'useradmin_auto_accept':
	$title=i18n::translate('useradmin_auto_accept');
	$text=i18n::translate('By checking this box you are allowing the system to automatically accept any edit changes made by this user.  The user must also have accept privileges on the GEDCOM in order for this setting to take effect.');
	break;

case 'useradmin_can_admin':
	$title=i18n::translate('useradmin_can_admin');
	$text=i18n::translate('If this box is checked, the user will have the same rights that you have.<dl><dt>These rights include:</dt><dd>Add / Remove / Edit Users</dd><dd>Broadcast messages to all users</dd><dd>Edit Welcome messages</dd><dd>Edit and configure language files</dd><dt></dt><dd>Upgrade PhpGedView</dd><dd>Change program and GEDCOM configurations</dd><dd>Administer the GEDCOMs</dd><dd>Change Privacy settings</dd><dd>And anything else that is not mentioned here.</dd></dl><br />The user <u>cannot</u> change anything on your server outside PhpGedView.');
	break;

case 'useradmin_can_edit':
	$title=i18n::translate('useradmin_can_edit');
	$text=i18n::translate('The user can have different access and editing privileges for each genealogical database in the system.<ul><li><b>None:</b> The user cannot access the private data in this GEDCOM.</li><li><b>Access:</b> The user cannot edit or accept data into the database but can see the private data.</li><li><b>Edit:</b> The user can edit values but another user with <b>Accept</b> privileges must approve the changes before they are added to the database and made public.</li><li><b>Accept:</b> The user can edit.  He can also edit and approve changes made by other users.</li><li><b>Admin GEDCOM:</b> The user edit and approve changes made by other users.  The user can also edit configuration and privacy settings for <u>this</u> GEDCOM.</li></ul>System administrators, identified through the <b>User can administer</b> check box, are automatically given <b>Admin GEDCOM</b> privileges.');
	break;

case 'useradmin_edit_user':
	$title=i18n::translate('useradmin_edit_user');
	$text=i18n::translate('This form is used by the administrator to change a user\'s account<br /><br />The form is very similar to the <b>Add a new user</b> and <b>Update MyAccount</b> forms.');
	break;

case 'useradmin_editaccount':
	$title=i18n::translate('useradmin_editaccount');
	$text=i18n::translate('If this box is checked, this user will be able to edit his account information.  Although this is not generally recommended, you can create a single user name and password for multiple users.  When this box is unchecked for all users with the shared account, they are prevented from editing the account information and only an administrator can alter that account.');
	break;

case 'useradmin_gedcomid':
	$title=i18n::translate('useradmin_gedcomid');
	$text=i18n::translate('The GEDCOM INDI record ID identifies the user.  It has to be set by the administrator.<br /><br />This ID is used as the ID on several pages such as <b>My Individual Record</b> and <b>My Pedigree</b>.<br /><br />You can set the user\'s GEDCOM ID separately for each GEDCOM.  If a user does not have a record in a GEDCOM, you leave that box empty.');
	break;

case 'useradmin':
	$title=i18n::translate('useradmin');
	$text=i18n::translate('On this page you can administer the current users and add new users.<br /><br /><b>User List</b><br />In this table the current users, their status, and their rights are displayed.  You can <b>delete</b> or <b>edit</b> users.<br /><br /><b>Add a new user</b><br />This form is almost the same as the one users see on the  <b>My Account</b> page.<br /><br />For several subjects we did not make special Help text for the administrator. In those cases you will see the following message:');
	break;

case 'useradmin_path_length':
	$title=i18n::translate('useradmin_path_length');
	$text=i18n::translate('If <i>Limit access to related people</i> is enabled, this user will only be able to see or edit living individuals within this number of relationship steps.');
	break;

case 'useradmin_relation_priv':
	$title=i18n::translate('useradmin_relation_priv');
	$text=i18n::translate('If this box is checked, the user will only be allowed access to living people that they are related to.  They will be able to see anyone who is within the relationship path length set by their <i>Max relationship privacy path length</i> setting.  You can require relationship privacy for all of your users by turning on the global option in the GEDCOM privacy settings.<br /><br />This setting requires that the user be associated with a GEDCOM ID before they will be able to see any living people.');
	break;

case 'useradmin_rootid':
	$title=i18n::translate('useradmin_rootid');
	$text=i18n::translate('For each genealogical database, you can designate a <b>Root Person</b> for the user.<br /><br />This Root Person does not need to be the user himself; it can be anybody.  The user will probably want to start the Pedigree chart with himself.  You control that, as well as the default Root person on other charts, here.<br /><br />If the user has Edit rights to his own account information, he can change this setting himself.');
	break;

case 'useradmin_user_default_tab':
	$title=i18n::translate('useradmin_user_default_tab');
	$text=i18n::translate('This setting allows you to specify which tab is opened automatically when this user accesses the Individual Information page.  If allowed to edit their account, the user can change this setting later.');
	break;

case 'useradmin_verbyadmin':
	$title=i18n::translate('useradmin_verbyadmin');
	$text=i18n::translate('If a user has used the Self Registration module and has verified himself, the last step, before his account will become active, is your approval.<br /><br />After you have approved the user\'s application for a new account, the user will receive an email message.  The message will tell the user that his account is now active.  He can login with the user name and password that he supplied when he applied for the account.');
	break;

case 'useradmin_verified':
	$title=i18n::translate('useradmin_verified');
	$text=i18n::translate('<b>Self Registration</b><br />A user can apply for a new account by means of the <b>self registration</b> module.<br /><br />When he does so, he will receive an email message with a link to verify his application.  After the applicant has acted on the instructions in that email, you will see this box checked, and you can proceed with the next step, <b>User approved by Admin</b>.  You should wait with your approval as long as this box is not checked.<br /><br /><b>Add user manually</b><br />If you use this form to add a user manually, you will find this box checked already.');
	break;

case 'useradmin_visibleonline':
	$title=i18n::translate('useradmin_visibleonline');
	$text=i18n::translate('This checkbox controls your visibility to other users while you\'re online.  It also controls your ability to see other online users who are configured to be visible.<br /><br />When this box is unchecked, you will be completely invisible to others, and you will also not be able to see other online users.  When this box is checked, exactly the opposite is true.  You will be visible to others, and you will also be able to see others who are configured to be visible.');
	break;

case 'username':
	$title=i18n::translate('User name');
	$text=i18n::translate('In this box you type your user name.<br /><br /><b>The user name is case sensitive.</b>  This means that <b>MyName</b> is <u>not</u> the same as <b>myname</b> or <b>MYNAME</b>.');
	break;

case 'utf8_ansi':
	$title=i18n::translate('utf8_ansi');
	$text=i18n::translate('For optimal display on the Internet, PhpGedView uses the UTF-8 character set.  Some programs, Family Tree Maker for example, do not support importing GEDCOM files encoded in UTF-8.  Checking this box will convert the file from <b>UTF-8</b> to <b>ANSI (ISO-8859-1)</b>.<br /><br />The format you need depends on the program you use to work with your downloaded GEDCOM file.  If you aren\'t sure, consult the documentation of that program.<br /><br />Note that for special characters to remain unchanged, you will need to keep the file in UTF-8 and convert it to your program\'s method for handling these special characters by some other means.  Consult your program\'s manufacturer or author.<br /><br />This <a href=\'http://en.wikipedia.org/wiki/UTF-8\' target=\'_blank\' title=\'Wikipedia article\'><b>Wikipedia article</b></a> contains comprehensive information and links about UTF-8.');
	break;

case 'validate_gedcom':
	$title=i18n::translate('Validate GEDCOM');
	$text=i18n::translate('This is the third step in the procedure to add externally created GEDCOM data to your genealogical database.<br /><br />PhpGedView will check the input file for the correct use of Date format, Place format, Character Set, etc.  Some deviations from the GEDCOM 5.5.1 Standard, to which PhpGedView adheres, can be corrected automatically. Examples are Macintosh line endings and incorrect use of Place format.  When this happens, you will see a message that the data has been changed.  For other abnormalities you will get a warning message with a recommended solution.<br /><br /><b>Optional Tools</b><br />At this moment there is only one additional tool:<br /><b>Change Individual ID to...</b>.<br /><br /><b>More help</b><br />More help is available by clicking the <b>?</b> next to items on the page.');
	break;

case 'verify_gedcom':
	$title=i18n::translate('Verify GEDCOM');
	$text=i18n::translate('Here you can choose to either continue with the upload and import of this GEDCOM file or to abort the upload and import.');
	break;

case 'view_server_folder':
	$title=i18n::translate('view_server_folder');
	$text=i18n::translate('The administrator has enabled up to #GLOBALS[MEDIA_DIRECTORY_LEVELS]# folder levels below the default <b>#GLOBALS[MEDIA_DIRECTORY]#</b>.  This helps to organize the media files and reduces the possibility of name collisions.<br /><br />In this field, you select the media folder whose contents you wish to view.  When you select <b>ALL</b>, all media files will be shown without regard to the folder in which they are stored.  This can produce a very long list of media items.');
	break;

case 'week_start':
	$title=i18n::translate('Week starting day');
	$text=i18n::translate('The day of the week that starts a new week in this language.  Most languages start the week on Sunday, but some start on Monday or other days.');
	break;

case 'welcome_new':
	$title=i18n::translate('Welcome to your new PhpGedView website.');
	$text=i18n::translate('Since you are seeing this page, you have successfully installed PhpGedView on your server and are ready to begin configuring it to your requirements.<br />This Help page will guide you through the configuration process.  As you enter different fields, this window will provide you with help information about the field you are in.  You may close this window; to open it again click on one of the "?" question marks next to the field label.');
	break;

case 'yahrzeit':
	$title=i18n::translate('yahrzeit');
	$text=i18n::translate('This block shows you Yahrzeiten that are coming up in the near future.<br /><br />Yahrzeiten (singular: Yahrzeit) are anniversaries of a person\'s death.  These anniversaries are observed in the Jewish tradition; they are no longer in common use in other traditions.  «Yahrzeit» can also be spelled «Jahrzeit» or «Yartzeit».<br /><br />The Administrator determines how far ahead the block will look.  You can further refine the block\'s display of upcoming Yahrzeiten through configuration options.');
	break;

case 'zip':
	$title=i18n::translate('zip');
	$text=i18n::translate('Select this option as to save your clippings in a ZIP file.  For more information about ZIP files, please visit <a href="http://www.winzip.com" target="_blank">http://www.winzip.com</a>.');
default:
	$title=i18n::translate('Help');
	$text=i18n::translate('No help is available for this subject.');
	break;
}

if (!$text) {
	$text=i18n::translate('The help text has been written for this item.');
}

print_simple_header(i18n::translate('Help for «%s»', htmlspecialchars($title)));
echo '<h1>', htmlspecialchars($title), '</h1>';
echo '<p>', nl2br($text), '</p>';
echo '<br /><br /><br />';
echo '<a href="help_text.php?help=help_contents_help"><b>', $pgv_lang['help_contents'], '</b></a><br />';
echo '<a href="javascript:;" onclick="window.close();"><b>', $pgv_lang['close_window'], '</b></a>';
print_simple_footer();
?>
