<?php
/**
 * PDF Report Generator
 *
 * used by the SAX parser to generate PDF reports from the XML report file.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @subpackage Reports
 * @version $Id$
 * @link http://www.adobe.com/devnet/pdf/
 * @link http://www.tcpdf.org
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_CLASS_REPORTPDF_PHP', '');

require_once 'includes/classes/class_reportbase.php';
require_once 'tcpdf/tcpdf.php';

/**
 * Main PGV Report Class for PDF
 *
 * @package PhpGedView
 * @subpackage Reports
 */
class PGVReportBasePDF extends PGVReportBase {
	/**
	* PDF compression - Zlib extension is required
	* @var boolean const
	*/
	const compression = true;
	/**
	* If TRUE reduce the RAM memory usage by caching temporary data on filesystem (slower).
	* @var boolean const
	*/
	const diskcache = false;
	/**
	* TRUE means that the input text is unicode (PDF)
	* @var boolean const
	*/
	const unicode = true;
	/**
	* A new object of the PGVRPDF class
	* @var PGVRPDF
	*/
	public $pdf;

	/**
	* PDF Setup - PGVReportBasePDF
	*/
	function setup() {
		parent::setup();

		// Setup the PDF class with custom size pages because PGV supports more page sizes. If PGV sends an unknown size name then the default would be A4
		$this->pdf = new PGVRPDF($this->orientation, parent::unit, array($this->pagew, $this->pageh), self::unicode, $this->charset, self::diskcache);

		// Setup the PDF margins
		$this->pdf->setMargins($this->leftmargin, $this->topmargin, $this->rightmargin);
		$this->pdf->SetHeaderMargin($this->headermargin);
		$this->pdf->SetFooterMargin($this->footermargin);
		//Set auto page breaks
		$this->pdf->SetAutoPageBreak(true, $this->bottommargin);
		// Setup PDF compression
		$this->pdf->SetCompression(self::compression);
		// Setup RTL support
		$this->pdf->setRTL($this->rtl);
		// Set the document information
		$this->pdf->SetCreator($this->generatedby.' ('.parent::pgv_url.')');
		// Not implemented yet - PGVReportBase::setup()
//		$this->pdf->SetAuthor($this->rauthor);
		$this->pdf->SetTitle($this->title);
		$this->pdf->SetSubject($this->rsubject);
		$this->pdf->SetKeywords($this->rkeywords);

		$this->pdf->setReport($this);

		if ($this->showGenText) {
			// The default style name for Generated by.... is 'genby'
			$element = new PGVRCellPDF(0, 10, 0, 'C', '', 'genby', 1, '.', '.', 0, 0, '', '', true);
			$element->addText($this->generatedby);
			$element->setUrl(parent::pgv_url);
			$this->pdf->addFooter($element);
		}
	}

	/**
	* Add an element - PGVReportBasePDF
	* @param object|string &$element Object or string
	*/
	function addElement(&$element) {
		if ($this->processing == "B") {
			return $this->pdf->addBody($element);
		}
		else if ($this->processing == "H") {
			return $this->pdf->addHeader($element);
		}
		else if ($this->processing == "F") {
			return $this->pdf->addFooter($element);
		}
		return 0;
	}

	function run() {
		global $download;

		$this->pdf->Body();
		header("Expires:");
		header("Pragma:");
		header("Cache-control:");
		if ($download == "") {
			$this->pdf->Output();
		}
		else $this->pdf->Output("pgv_report_".basename($_REQUEST["report"], ".xml").".pdf", "D");
		return;
	}

	/**
	* Clear the Header - PGVReportBasePDF
	*/
	function clearHeader() {
		$this->pdf->clearHeader();
	}

	/**
	* Clear the Page Header - PGVReportBasePDF
	*/
	function clearPageHeader() {
		$this->pdf->clearPageHeader();
	}

	/**
	* Create a new Cell object - PGVReportBasePDF
	*
	* @param int $width cell width (expressed in points)
	* @param int $height cell height (expressed in points)
	* @param mixed $border Border style
	* @param string $align Text alignement
	* @param string $bgcolor Background color code
	* @param string $style The name of the text style
	* @param int $ln Indicates where the current position should go after the call
	* @param mixed $top Y-position
	* @param mixed $left X-position
	* @param int $fill Indicates if the cell background must be painted (1) or transparent (0). Default value: 1
	* @param int $stretch Stretch carachter mode
	* @param string $bocolor Border color
	* @param string $tcolor Text color
	* @param bolean $reseth
	* @return PGVRCellPDF
	*/
	function createCell($width, $height, $border, $align, $bgcolor, $style, $ln, $top, $left, $fill, $stretch, $bocolor, $tcolor, $reseth) {
		return new PGVRCellPDF($width, $height, $border, $align, $bgcolor, $style, $ln, $top, $left, $fill, $stretch, $bocolor, $tcolor, $reseth);
	}

	/**
	* Create a new TextBox object - PGVReportBasePDF
	*
	* @param float $width Text box width
	* @param float $height Text box height
	* @param boolean $border
	* @param string $bgcolor Background color code in HTML
	* @param boolean $newline
	* @param mixed $left
	* @param mixed $top
	* @param boolean $pagecheck
	* @param string $style
	* @param boolean $fill
	* @return PGVRTextBoxPDF
	*/
	function createTextBox($width, $height, $border, $bgcolor, $newline, $left, $top, $pagecheck, $style, $fill, $padding) {
		return new PGVRTextBoxPDF($width, $height, $border, $bgcolor, $newline, $left, $top, $pagecheck, $style, $fill, $padding);
	}

	/**
	* Create a new Text object- PGVReportBasePDF
	*
	* @param string $style The name of the text style
	* @param string $color HTML color code
	* @return PGVRTextPDF
	*/
	function createText($style, $color) {
		return new PGVRTextPDF($style, $color);
	}

	/**
	* Create a new Footnote object - PGVReportBasePDF
	* @param string $style Style name
	* @return PGVRFootnotePDF
	*/
	function createFootnote($style) {
		return new PGVRFootnotePDF($style);
	}

	/**
	* Create a new Page Header object - PGVReportBasePDF
	* @return PGVRPageHeaderPDF
	*/
	function createPageHeader() {
		return new PGVRPageHeaderPDF();
	}

	/**
	* Create a new image object - PGVReportBasePDF
	* @param string $file File name
	* @param mixed $x
	* @param mixed $y
	* @param int $w Image width
	* @param int $h Image height
	* @param string $align L:left, C:center, R:right or empty to use x/y
	* @param string $ln T:same line, N:next line 
	* @return PGVRImagePDF
	*/
	function createImage($file, $x, $y, $w, $h, $align, $ln) {
		return new PGVRImagePDF($file, $x, $y, $w, $h, $align, $ln);
	}

	/**
	* Create a new line object - PGVReportBasePDF
	* @param mixed $x1
	* @param mixed $y1
	* @param mixed $x2
	* @param mixed $y2
	* @return PGVRLinePDF
	*/
	function createLine($x1, $y1, $x2, $y2) {
		return new PGVRLinePDF($x1, $y1, $x2, $y2);
	}

	/**
	* @return PGVRHtmlPDF
	*/
	function createHTML($tag, $attrs) {
		return new PGVRHtmlPDF($tag, $attrs);
	}
} //-- end PGVReport

/**
 * PGV Report PDF Class
 *
 * This class inherits from the TCPDF class and is used to generate the PDF document
 * @package PhpGedView
 * @subpackage Reports
 */
class PGVRPDF extends TCPDF {
	/**
	 * Array of elements in the header
	 * @var array
	 */
	public $headerElements = array();
	/**
	 * Array of elements in the page header
	* @var array
	 */
	public $pageHeaderElements = array();
	/**
	 * Array of elements in the footer
	 * @var array
	 */
	public $footerElements = array();
	/**
	 * Array of elements in the body
	 * @var array
	 */
	public $bodyElements = array();
	/**
	* Array of elements in the footer notes
	* @var array
	*/
	public $printedfootnotes = array();

	/**
	* Currently used style name
	* @var string
	*/
	public $currentStyle;
	/**
	* The last cell height
	 * @var int
	*/
	public $lastCellHeight = 0;
	/**
	 * The largest font size within a PGVRTextBox
	 * to calculate the height
	 * @var int
	 */
	public $largestFontHeight = 0;
	
	public $pgvreport;

	/**
	* PDF Header -PGVRPDF
	*/
	function Header() {
		foreach($this->headerElements as $element) {
			if (is_string($element) && $element=="footnotetexts") {
				$this->Footnotes();
			}
			else if (is_string($element) && $element=="addpage") {
				$this->AddPage();
			}
			else $element->render($this);
		}
		foreach($this->pageHeaderElements as $element) {
			if (is_string($element) && $element=="footnotetexts") {
				$this->Footnotes();
			}
			else if (is_string($element) && $element=="addpage") {
				$this->AddPage();
			}
			else if (is_object($element)) $element->render($this);
		}
	}

	/**
	* PDF Body -PGVRPDF
	*/
	function Body() {
		$this->AddPage();
		foreach($this->bodyElements as $element) {
			if (is_string($element) && $element=="footnotetexts") {
				$this->Footnotes();
			}
			else if (is_string($element) && $element=="addpage") {
				$this->AddPage();
			}
			else if (is_object($element)) {
				$element->render($this);
			}
		}
	}

	/**
	* PDF Footnotes -PGVRPDF
	*/
	function Footnotes() {
		foreach($this->printedfootnotes as $element) {
			if (($this->GetY() + $element->getFootnoteHeight($this)) > $this->getPageHeight()) {
				$this->AddPage();
			}
			$element->renderFootnote($this);
			if ($this->GetY() > $this->getPageHeight()) {
				$this->AddPage();
			}
		}
	}

	/**
	* PDF Footer -PGVRPDF
	*/
	function Footer() {
		foreach($this->footerElements as $element) {
			if (is_string($element) && $element=="footnotetexts") {
				$this->Footnotes();
			}
			else if (is_string($element) && $element=="addpage") {
				$this->AddPage();
			}
			else if (is_object($element)) {
				$element->render($this);
			}
		}
	}

	/**
	* Add an element to the Header -PGVRPDF
	* @param object|string &$element
	* @return int The number of the Header elements
	*/
	function addHeader($element) {
		$this->headerElements[] = $element;
		return count($this->headerElements)-1;
	}

	/**
	* Add an element to the Page Header -PGVRPDF
	* @param object|string &$element
	* @return int The number of the Page Header elements
	*/
	function addPageHeader($element) {
		$this->pageHeaderElements[] = $element;
		return count($this->pageHeaderElements)-1;
	}

	/**
	* Add an element to the Body -PGVRPDF
	* @param object|string &$element
	* @return int The number of the Body elements
	*/
	function addBody($element) {
		$this->bodyElements[] = $element;
		return count($this->bodyElements)-1;
	}

	/**
	* Add an element to the Footer -PGVRPDF
	* @param object|string &$element
	* @return int The number of the Footer elements
	*/
	function addFooter($element) {
		$this->footerElements[] = $element;
		return count($this->footerElements)-1;
	}

	function removeHeader($index) {
		unset($this->headerElements[$index]);
	}

	function removePageHeader($index) {
		unset($this->pageHeaderElements[$index]);
	}

	function removeBody($index) {
		unset($this->bodyElements[$index]);
	}

	function removeFooter($index) {
		unset($this->footerElements[$index]);
	}

	/**
	* Clear the Header -PGVRPDF
	*/
	function clearHeader() {
		unset($this->headerElements);
		$this->headerElements = array();
	}

	/**
	* Clear the Page Header -PGVRPDF
	*/
	function clearPageHeader() {
		unset($this->pageHeaderElements);
		$this->pageHeaderElements = array();
	}

	function setReport($r) {
		$this->pgvreport = $r;
	}

	/**
	* Get the currently used style name -PGVRPDF
	* @return string
	*/
	function getCurrentStyle() {
		return $this->currentStyle;
	}

	/**
	* Setup a style for usage -PGVRPDF
	* @param string $s Style name
	*/
	function setCurrentStyle($s) {
		$this->currentStyle = $s;
		$style = $this->pgvreport->getStyle($s);
		$this->SetFont($style["font"], $style["style"], $style["size"]);
	}

	/**
	* Get the style -PGVRPDF
	* @param string $s Style name
	* @return array
	*/
	function getStyle($s) {
		if (!isset($this->pgvreport->PGVRStyles[$s])) {
			$s = $this->getCurrentStyle();
			$this->pgvreport->PGVRStyles[$s] = $s;
		}
		return $this->pgvreport->PGVRStyles[$s];
	}

	/**
	* Add margin when static horizontal position is used -PGVRPDF
	* RTL supported
	* @param float $x Static position
	* return float
	*/
	function addMarginX($x) {
		$m = $this->getMargins();
		if ($this->getRTL()) {
			$x += $m['right'];
		}
		else {
			$x += $m['left'];
		}
		$this->SetX($x);
		return $x;
	}
	
	/**
	* Get the maximum line width to draw from the curren position -PGVRPDF
	* RTL supported
	* @return float
	*/
	function getMaxLineWidth() {
		$m = $this->getMargins();
		if ($this->getRTL()){
			return ($this->getRemainingWidth() + $m['right']);
		}
		else {
			return ($this->getRemainingWidth() + $m['left']);
		}
	}
	
	function getFootnotesHeight() {
		$h=0;
		foreach($this->printedfootnotes as $element) {
			$h+=$element->getHeight($this);
		}
		return $h;
	}

	/**
	* Returns the the current font size height -PGVRPDF
	* @return int
	*/
	function getCurrentStyleHeight() {
		if (empty($this->currentStyle)) {
			return $this->pgvreport->defaultFontSize;
		}
		$style = $this->pgvreport->getStyle($this->currentStyle);
		return $style["size"];
	}

	/**
	 * Checks the Footnote and numbers them
	 *
	 * @param object &$footnote
	 * @return boolen false if not numbered befor | object if already numbered
	 */
	function checkFootnote(&$footnote) {
		$ct = count($this->printedfootnotes);
		$val = $footnote->getValue();
		for($i=0; $i < $ct; $i++) {
			if ($this->printedfootnotes[$i]->getValue() == $val) {
				// If this footnote already exist then set up the numbers for this object
				$footnote->setNum($i + 1);
				$footnote->setAddlink($i + 1);
				return $this->printedfootnotes[$i];
			}
		}
		// If this Footnote has not been set up yet
		$footnote->setNum($ct + 1);
		$link = $this->AddLink();
		$footnote->setAddlink($link);
		$this->printedfootnotes[] = $footnote;
		return false;
	}
	

	
	/*******************************************
	* TCPDF protected functions
	*******************************************/
	
	/*
	* Add a page if needed -PGVRPDF
	* @param $height Cell height. Default value: 0
	* @return boolean true in case of page break, false otherwise
	*/
	function checkPageBreakPDF($height) {
		return $this->checkPageBreak($height);
	}

	/**
	* Returns the remaining width between the current position and margins -PGVRPDF
	* @return float Remaining width
	*/
	function getRemainingWidthPDF() {
		return $this->getRemainingWidth();
	}
	
} //-- END PGVRPDF

/**
* Report Base object of PGVReportBase class inherited by PGVReportBasePDF
* @global PGVReportBasePDF $pgvreport
*/
$pgvreport = new PGVReportBasePDF();

$PGVReportRoot = $pgvreport;

/**
 * Cell element - PDF
 *
* @package PhpGedView
* @subpackage Reports
*/
class PGVRCellPDF extends PGVRCell {
	/**
	* Create a class CELL for PDF
	*
	* @param int $width cell width (expressed in points)
	* @param int $height cell height (expressed in points)
	* @param mixed $border Border style
	* @param string $align Text alignement
	* @param string $bgcolor Background color code
	* @param string $style The name of the text style
	* @param int $ln Indicates where the current position should go after the call
	* @param mixed $top Y-position
	* @param mixed $left X-position
	* @param int $fill Indicates if the cell background must be painted (1) or transparent (0). Default value: 1
	* @param int $stretch Stretch carachter mode
	* @param string $bocolor Border color
	* @param string $tcolor Text color
	* @param boolean $reseth
	*/
	function PGVRCellPDF($width, $height, $border, $align, $bgcolor, $style, $ln, $top, $left, $fill, $stretch, $bocolor, $tcolor, $reseth) {
		parent::PGVRCell($width, $height, $border, $align, $bgcolor, $style, $ln, $top, $left, $fill, $stretch, $bocolor, $tcolor, $reseth);
	}

	/**
	* PDF Cell renderer
	* @param PGVRPDF &$pdf
	*/
	function render(&$pdf) {
		/**
		* Use these variables to update/manipulate values
		* Repeted classes would reupdate all their class variables again, Header/Page Header/Footer
		* This is the bugfree version
		*/
		$cX = 0;	// Class Left
		
		// Set up the text style
		if (($pdf->getCurrentStyle()) != ($this->styleName)) {
			$pdf->setCurrentStyle($this->styleName);
		}
		$temptext = preg_replace("/#PAGENUM#/", $pdf->PageNo(), $this->text);

		// Indicates if the cell background must be painted (1) or transparent (0)
		if ($this->fill == 1) {
			if (!empty($this->bgcolor)) {
				// HTML color to RGB
				$ct = preg_match("/#?(..)(..)(..)/", $this->bgcolor, $match);
				if ($ct > 0) {
					$r = hexdec($match[1]);
					$g = hexdec($match[2]);
					$b = hexdec($match[3]);
					$pdf->SetFillColor($r, $g, $b);
				}
			}
			// If no color set then don't fill
			else $this->fill = 0;
		}
		// Paint the Border color if set
		if (!empty($this->bocolor)) {
			// HTML color to RGB
			$ct = preg_match("/#?(..)(..)(..)/", $this->bocolor, $match);
			if ($ct > 0) {
				$r = hexdec($match[1]);
				$g = hexdec($match[2]);
				$b = hexdec($match[3]);
				$pdf->SetDrawColor($r, $g, $b);
			}
		}
		// Paint the text color if set
		if (!empty($this->tcolor)) {
			// HTML color to RGB
			$ct = preg_match("/#?(..)(..)(..)/", $this->tcolor, $match);
			if ($ct > 0) {
				$r = hexdec($match[1]);
				$g = hexdec($match[2]);
				$b = hexdec($match[3]);
				$pdf->SetTextColor($r, $g, $b);
			}
		}

		// If current position (left)
		if ($this->left == ".") {
			$cX = $pdf->GetX();
		}
		// For static position add margin (also updates X)
		else $cX = $pdf->addMarginX($this->left);

		// Check the width if set to page wide OR set by xml to larger then page wide
		if (($this->width == 0) or ($this->width > $pdf->getRemainingWidthPDF())) {
			$this->width = $pdf->getRemainingWidthPDF();
		}
		// For current position
		if ($this->top == ".") {
			$this->top = $pdf->GetY();
		}
		else $pdf->SetY($this->top);

		// Check the last cell height and adjust the current cell height if needed
		if ($pdf->lastCellHeight > $this->height) {
			$this->height = $pdf->lastCellHeight;
		}

		// Returns the number of line writen, if needed...
		$pdf->MultiCell($this->width, $this->height, $temptext, $this->border, $this->align, $this->fill, $this->newline, $cX, $this->top, $this->reseth, $this->stretch, false);
		// Reset the last cell height for the next line
		if ($this->newline >= 1) {
			$pdf->lastCellHeight = 0;
		}
		// OR save the last height if heigher then before
		else if ($pdf->lastCellHeight < $pdf->getLastH()) {
			$pdf->lastCellHeight = $pdf->getLastH();
		}

		// Set up the url link if exists ontop of the cell
		if (!empty($this->url)) {
			$pdf->Link($cX, $this->top, $this->width, $this->height, $this->url);
		}
		// Reset the border and the text color to black or they will be inhereted
		if (!empty($this->bocolor)) {
			$pdf->SetDrawColor(0, 0, 0);
		}
		if (!empty($this->tcolor)) {
			$pdf->SetTextColor(0, 0, 0);
		}
	}
}

/**
 * HTML element - PDF Report
 *
* @package PhpGedView
* @subpackage Reports
* @todo add info
*/
class PGVRHtmlPDF extends PGVRHtml {

	function PGVRHtmlPDF($tag, $attrs) {
		parent::PGVRHtml($tag, $attrs);
	}

	function render(&$pdf, $sub = false) {
		if (!empty($this->attrs['pgvrstyle'])) {
			$pdf->setCurrentStyle($this->attrs['pgvrstyle']);
		}
		if (!empty($this->attrs['width'])) {
			$this->attrs['width'] *= 3.9;
		}

		$this->text = $this->getStart().$this->text;
		foreach($this->elements as $element) {
			if (is_string($element) && $element=="footnotetexts") {
				$pdf->Footnotes();
			}
			else if (is_string($element) && $element=="addpage") {
				$pdf->AddPage();
			}
			else if ($element->get_type()=='PGVRHtml') {
				$this->text .= $element->render($pdf, true);
			}
			else $element->render($pdf);
		}
		$this->text .= $this->getEnd();
		if ($sub) {
			return $this->text;
		}
		$pdf->writeHTML($this->text);
		return 0;
	}
}

/**
 * TextBox element
 *
* @package PhpGedView
* @subpackage Reports
* @todo add info
*/
class PGVRTextBoxPDF extends PGVRTextBox {
	/**
	* Create a class Text Box for PDF
	*
	* @param float $width Text box width
	* @param float $height Text box height
	* @param boolean $border
	* @param string $bgcolor Background color code in HTML
	* @param boolean $newline
	* @param mixed $left
	* @param mixed $top
	* @param boolean $pagecheck
	* @param string $style
	* @param boolean $fill
	* @param boolean $padding
	*/
	function PGVRTextBoxPDF($width, $height, $border, $bgcolor, $newline, $left, $top, $pagecheck, $style, $fill, $padding) {
		parent::PGVRTextBox($width, $height, $border, $bgcolor, $newline, $left, $top, $pagecheck, $style, $fill, $padding);
	}

	/**
	* PDF Text Box renderer
	* @param PGVRPDF &$pdf
	*/
	function render(&$pdf) {

		$newelements = array();
		$lastelement = "";
		// Element counter
		$cE = count($this->elements);
		//-- collapse duplicate elements
		for($i = 0; $i < $cE; $i++) {
			$element = $this->elements[$i];
			if (is_object($element)) {
				if ($element->get_type() == "PGVRText") {
					if (empty($lastelement)) $lastelement = $element;
					else {
						// Checking if the PGVRText has the same style
						if ($element->getStyleName() == $lastelement->getStyleName()) {
							$lastelement->addText(preg_replace("/\n/", "<br />", $element->getValue()));
						}
						else {
							if (!empty($lastelement)) {
								$newelements[] = $lastelement;
								$lastelement = $element;
							}
						}
					}
				}
				//-- do not keep empty footnotes
				else if (($element->get_type() != "PGVRFootnote") or (trim($element->getValue()) != "")) {
					if (!empty($lastelement)) {
						$newelements[] = $lastelement;
						$lastelement = "";
					}
					$newelements[] = $element;
				}
			}
			else {
				if (!empty($lastelement)) {
					$newelements[] = $lastelement;
					$lastelement = "";
				}
				$newelements[] = $element;
			}
		}
		if (!empty($lastelement)) {
			$newelements[] = $lastelement;
		}
		$this->elements = $newelements;

		/**
		* Use these variables to update/manipulate values
		* Repeted classes would reupdate all their class variables again, Header/Page Header/Footer
		* This is the bugfree version
		*/
		$cH = 0;	// Class Height
		$cW = 0;	// Class Width
		$cX = 0;	// Class Left
		$cY = 0;	// Class Top
		$cS	= '';	// Class Style
		// Used with line breaks and cell height calculation within this box
		$pdf->largestFontHeight = 0;

		// If current position (left)
		if ($this->left == ".") {
			$cX = $pdf->GetX();
		}
		// For static position add margin (returns and updates X)
		else $cX = $pdf->addMarginX($this->left);

		// If current position (top)
		if ($this->top == ".") {
			$cY = $pdf->GetY();
		}
		else {
			$cY = $this->top;
			$pdf->SetY($cY);
		}

		// Check the width if set to page wide OR set by xml to larger then page width (margin)
		if (($this->width == 0) or ($this->width > $pdf->getRemainingWidthPDF())) {
			$cW = $pdf->getRemainingWidthPDF();
		}
		else $cW = $this->width;

		// Save the original margins
		$cM = $pdf->getMargins();

//		$h = 0;

		// Use cell padding to wrap the width
		// Temp Width with cell padding
		$cWT = $cW - ($cM['cell'] * 2);
		$w = 0;
		// Temp Height
		$cHT = 0;
		//-- $lw is an array
		// 0 => last line width
		// 1 => 1 if text was wrapped, 0 if text did not wrap
		// 2 => number of LF
		$lw = array();
		// Element counter
		$cE = count($this->elements);
		//-- calculate the text box height + width
		for($i = 0; $i < $cE; $i++) {
			if (is_object($this->elements[$i])) {
				$ew = $this->elements[$i]->setWrapWidth($cWT - $w, $cWT);
				if ($ew == $cWT) {
					$w = 0;
				}
				$lw = $this->elements[$i]->getWidth($pdf);
				$cHT += $lw[2];
				if ($lw[1]==1) {
					$w = $lw[0];
				}
				else if ($lw[1]==2) {
					$w=0;
				}
				else $w += $lw[0];
				if ($w > $cWT) {
					$w = $lw[0];
				}
//	Footnote is at the bottom of the page. No need to calculate it's height or wrap the text!
//	We are changing the margins anyway!
//				$eh = $this->elements[$i]->getHeight($pdf);
//				$h+=$eh;
			}
//			else {
//				$h += $pdf->getFootnotesHeight();
//			}
		}

		// Add up what's the final height
		$cH = $this->height;
		// Convert number of LF to points if any element exist
		if ($cE > 0) {
			// Number of LF but at least one line
			$cHT = ($cHT + 1) * $pdf->getCellHeightRatio();
			// Calculate the cell hight with the largest font size used within this Box
			$cHT = $cHT * $pdf->largestFontHeight;
			// Add cell padding
			if ($this->padding) {
				$cHT += ($cM['cell'] * 2);
			}
			if ($cH < $cHT) {
				$cH = $cHT;
			}
		}
		// Finaly, check the last cells height
		if ($cH < $pdf->lastCellHeight) {
			$cH = $pdf->lastCellHeight;
		}
		
		// Add a new page if needed
		if ($this->pagecheck) {
			if ($pdf->checkPageBreakPDF($cH)) {
				$cY = $pdf->GetY();
			}
		}

		// Setup the border and background color
		if ($this->border) $cS = 'D';		// D or empty string: Draw (default)
		// Fill the background
		if ($this->fill) {
			if (!empty($this->bgcolor)) {
				$ct = preg_match("/#?(..)(..)(..)/", $this->bgcolor, $match);
				if ($ct > 0) {
					$cS .= "F";			// F: Fill the background
					$r = hexdec($match[1]);
					$g = hexdec($match[2]);
					$b = hexdec($match[3]);
					$pdf->SetFillColor($r, $g, $b);
				}
			}
		}
		// Draw the border
		if (!empty($cS)) {
			$pdf->Rect($cX, $cY, $cW, $cH, $cS);
		}

		// Add cell pedding if set and if any text (element) exist
		if ($this->padding) {
			if ($cHT > 0) {
				$pdf->SetY($cY + $cM['cell']);
			}
		}
		// Change the margins X, Width
		if ($pdf->getRTL()) {
			$pdf->SetRightMargin($cX);
			$pdf->SetLeftMargin($pdf->getRemainingWidthPDF() - $cW  + $cM['left']);
		}
		else {
			$pdf->SetLeftMargin($cX);
			$pdf->SetRightMargin($pdf->getRemainingWidthPDF() - $cW + $cM['right']);
		}

		// Render the elements (write text)
		foreach($this->elements as $element) {
			if (is_string($element) and $element == "footnotetexts") {
				$pdf->Footnotes();
			}
			else if (is_string($element) and $element == "addpage") {
				$pdf->AddPage();
			}
			else $element->render($pdf);
		}

		// Restore the margins
		$pdf->SetLeftMargin($cM['left']);
		$pdf->SetRightMargin($cM['right']);

		// New line and some clean up
		if ($this->newline) {
			// addMarginX() also updates X
			$pdf->addMarginX(0);
			$pdf->SetY($cY + $cH);
			$pdf->lastCellHeight = 0;
		}
		else {
			$pdf->SetXY(($cX + $cW), $cY);
			$pdf->lastCellHeight = $cH;
		}
		return 0;
	}
}

/**
 * Text element
 *
* @package PhpGedView
* @subpackage Reports
* @todo add info
*/
class PGVRTextPDF extends PGVRText {
	/**
	* Create a Text class for PDF
	*
	* @param string $style The name of the text style
	* @param string $color HTML color code
	*/
	function PGVRTextPDF($style, $color) {
		parent::PGVRText($style, $color);
	}

	/**
	 * PDF Text renderer
	 * @param PGVRPDF &$pdf
	 */
	function render(&$pdf) {
		// Set up the style
		if ($pdf->getCurrentStyle() != $this->styleName) {
			$pdf->setCurrentStyle($this->styleName);
		}
		$temptext = preg_replace("/#PAGENUM#/", $pdf->PageNo(), $this->text);

		// Paint the text color if set
		if (!empty($this->color)) {
			$ct = preg_match("/#?(..)(..)(..)/", $this->color, $match);
			if ($ct > 0) {
				$r = hexdec($match[1]);
				$g = hexdec($match[2]);
				$b = hexdec($match[3]);
				$pdf->SetTextColor($r, $g, $b);
			}
		}
		$pdf->Write($pdf->getCurrentStyleHeight(), $temptext);
		// Reset the text color to black or it will be inhereted
		if (!empty($this->color)) {
			$pdf->SetTextColor(0, 0, 0);
		}
	}

	/**
	 * Returns the height in points of the text element
	 *
	 * @param PGVRPDF &$pdf
	 * @return float $h
	 */
	function getHeight(&$pdf) {
		$style = $pdf->getStyle($this->styleName);
		$ct = substr_count($this->text, "\n");
		if ($ct > 0) {
			$ct += 1;
		}
		$h = ($style['size'] * $ct);
		return $h;
	}

	/**
	* Splits the text into lines if necessary to fit into a giving cell
	* 
	* @param PGVRPDF &$pdf
	* @return array
	*/
	function getWidth(&$pdf) {
		// Setup the style name, a font must be selected to calculate the width
		if ($pdf->getCurrentStyle() != $this->styleName) {
			$pdf->setCurrentStyle($this->styleName);
		}
		// Check for the largest font size in the box
		$fsize = $pdf->getCurrentStyleHeight();
		if ($fsize > $pdf->largestFontHeight) {
			$pdf->largestFontHeight = $fsize;
		}

		// Get the line width
		$lw = $pdf->GetStringWidth($this->text);
		// Line Feed counter - Number of lines in the text
		$lfct = substr_count($this->text, "\n") + 1;
		// If there is still remaining wrap width...
		if ($this->wrapWidthRemaining > 0) {
			// Check with line counter too!
			// but floor the $wrapWidthRemaining first to keep it bugfree!
			$wrapWidthRemaining = floor($this->wrapWidthRemaining);
			if (($lw >= ($wrapWidthRemaining)) or ($lfct > 1)) {
				$newtext = "";
				$lines = explode("\n", $this->text);
				// Go throught the text line by line
				foreach($lines as $line) {
					// Line width in points + a little margin
					$lw = $pdf->GetStringWidth($line);
					// If the line has to be wraped
					if ($lw >= $wrapWidthRemaining) {
						$words = explode(' ', $line);
						$lw = 0;
						foreach($words as $word) {
							$lw += $pdf->GetStringWidth($word." ");
							if ($lw <= $wrapWidthRemaining) {
								$newtext.=$word." ";
							}
							else {
								$lw = $pdf->GetStringWidth($word." ");
								$newtext .= "\n$word ";
								// Reset the wrap width to the cell width
								$wrapWidthRemaining = $this->wrapWidthCell;
							}
						}
					}
					else $newtext .= $line;
					// Check the Line Feed counter
					if ($lfct > 1) {
						// Add a new line as long as it's not the last line
						$newtext.= "\n";
						// Reset the line width
						$lw = 0;
						// Reset the wrap width to the cell width
						$wrapWidthRemaining = $this->wrapWidthCell;
					}
					$lfct--;
				}
				$this->text = $newtext;
				$lfct = substr_count($this->text, "\n");
				return array($lw, 1, $lfct);
			}
		}
		$l = 0;
		$lfct = substr_count($this->text, "\n");
		if ($lfct > 0) {
			$l = 2;
		}
		return array($lw, $l, $lfct);
	}
}

/**
 * Footnote element
 *
* @package PhpGedView
* @subpackage Reports
* @todo add info
*/
class PGVRFootnotePDF extends PGVRFootnote {

	function PGVRFootnotePDF($style="") {
		parent::PGVRFootnote($style);
	}

	/**
	* PDF Footnotes number renderer
	* @param PGVRPDF &$pdf
	*/
	function render(&$pdf) {
		$pdf->setCurrentStyle("footnotenum");
		$pdf->Write($pdf->getCurrentStyleHeight(), $this->numText, $this->addlink);
	}

	/**
	 * Write the Footnote text
	 * Uses style name "footnote" by default
	 *
	 * @param PGVRPDF &$pdf
	 */
	function renderFootnote(&$pdf) {
		if ($pdf->getCurrentStyle() != $this->styleName) {
			$pdf->setCurrentStyle($this->styleName);
		}
		$temptext = preg_replace("/#PAGENUM#/", $pdf->PageNo(), $this->text);
		$pdf->SetLink($this->addlink, -1);
		$pdf->Write($pdf->getCurrentStyleHeight(), $this->num.". ".$temptext."\n\n");
	}

	/**
	 * Returns the height in points of the Footnote element
	 *
	 * @param PGVRPDF &$pdf
	 * @return float $h
	 */
	function getFootnoteHeight(&$pdf) {
		$style = $pdf->getStyle($this->styleName);
		$ct = substr_count($this->numText, "\n");
		if ($ct > 0) {
			$ct += 1;
		}
		$h = ($style['size'] * $ct);
		return $h;
	}

	/**
	* Splits the text into lines to fit into a giving cell
	* and returns the last lines width
	* 
	* @param PGVRPDF &$pdf
	* @return array
	*/
	function getWidth(&$pdf) {
		// Setup the style name, a font must be selected to calculate the width
		$pdf->setCurrentStyle("footnotenum");

		// Check for the largest font size in the box
		$fsize = $pdf->getCurrentStyleHeight();
		if ($fsize > $pdf->largestFontHeight) {
			$pdf->largestFontHeight = $fsize;
		}

		// Returns the Object if already numbered else false
		$pdf->checkFootnote($this);

		// Get the line width
		$lw = ceil($pdf->GetStringWidth($this->numText));
		// Line Feed counter - Number of lines in the text
		$lfct = substr_count($this->numText, "\n") + 1;
		// If there is still remaining wrap width...
		if ($this->wrapWidthRemaining > 0) {
			// Check with line counter too!
			// but floor the $wrapWidthRemaining first to keep it bugfree!
			$wrapWidthRemaining = floor($this->wrapWidthRemaining);
			if (($lw >= $wrapWidthRemaining) or ($lfct > 1)) {
				$newtext = "";
				$lines = explode("\n", $this->numText);
				// Go throught the text line by line
				foreach($lines as $line) {
					// Line width in points
					$lw = ceil($pdf->GetStringWidth($line));
					// If the line has to be wraped
					if ($lw >= $wrapWidthRemaining) {
						$words = explode(' ', $line);
						$lw = 0;
						foreach($words as $word) {
							$lw += ceil($pdf->GetStringWidth($word." "));
							if ($lw < $wrapWidthRemaining) {
								$newtext.=$word." ";
							}
							else {
								$lw = $pdf->GetStringWidth($word." ");
								$newtext .= "\n$word ";
								// Reset the wrap width to the cell width
								$wrapWidthRemaining = $this->wrapWidthCell;
							}
						}
					}
					else $newtext .= $line;
					// Check the Line Feed counter
					if ($lfct > 1) {
						// Add a new line feed as long as it's not the last line
						$newtext.= "\n";
						// Reset the line width
						$lw = 0;
						// Reset the wrap width to the cell width
						$wrapWidthRemaining = $this->wrapWidthCell;
					}
					$lfct--;
				}
				$this->numText = $newtext;
				$lfct = substr_count($this->numText, "\n");
				return array($lw, 1, $lfct);
			}
		}
		$l = 0;
		$lfct = substr_count($this->numText, "\n");
		if ($lfct > 0) {
			$l = 2;
		}
		return array($lw, $l, $lfct);
	}
}

/**
 * PageHeader element
 *
* @package PhpGedView
* @subpackage Reports
*/
class PGVRPageHeaderPDF extends PGVRPageHeader {

	function PGVRPageHeaderPDF() {
		parent::PGVRPageHeader();
	}

	/**
	 * PageHeader element renderer
	 * @param PGVRPDF &$pdf
	*/
	function render(&$pdf) {
		$pdf->clearPageHeader();
		foreach($this->elements as $element) {
			$pdf->addPageHeader($element);
		}
	}
}

/**
 * PGVRImagePDF class element
 *
* @package PhpGedView
* @subpackage Reports
*/
class PGVRImagePDF extends PGVRImage {

	function PGVRImagePDF($file, $x, $y, $w, $h, $align, $ln) {
		parent::PGVRImage($file, $x, $y, $w, $h, $align, $ln);
	}

	/**
	* PDF image renderer
	* @param PGVRPDF &$pdf
	*/
	function render(&$pdf) {
		global $lastpicbottom, $lastpicpage, $lastpicleft, $lastpicright;

		if ($this->y==0) {
			//-- first check for a collision with the last picture
			if (isset($lastpicbottom)) {
				if (($pdf->PageNo()==$lastpicpage) && ($lastpicbottom >= $pdf->GetY()) && ($this->x >= $lastpicleft) && ($this->x <= $lastpicright))
					$pdf->SetY($lastpicbottom+5);
			}
			$this->y=$pdf->GetY();
		}
		$curx = $pdf->GetX();
		// If current position (left)set '.'
		if ($this->x == ".") {
			$this->x = $curx;
		}
		// For static position add margin
		else  {
			$this->x = $pdf->addMarginX($this->x);
			$pdf->SetX($curx);
		}
		if ($this->y == ".") {
			$this->y = $pdf->GetY();
		}

		$pdf->Image($this->file, $this->x, $this->y, $this->width, $this->height, '', '', $this->line, false, 72, $this->align);
		$lastpicpage = $pdf->PageNo();
		$lastpicleft=$this->x;
		$lastpicright=$this->x + $this->width;
		$lastpicbottom = $this->y + $this->height;
		// Setup for the next line
		if ($this->line == 'N') {
			$pdf->SetY($lastpicbottom);
		}
	}

	function getHeight(&$pdf) {
		return $this->height;
	}

	function getWidth(&$pdf) {
		return $this->width;
	}
}

/**
 * Line element
 *
* @package PhpGedView
* @subpackage Reports
* @todo add info
*/
class PGVRLinePDF extends PGVRLine {
	/**
	* Create a line class -PDF
	* @param mixed $x1
	* @param mixed $y1
	* @param mixed $x2
	* @param mixed $y2
	*/
	function PGVRLinePDF($x1, $y1, $x2, $y2) {
		parent::PGVRLine($x1, $y1, $x2, $y2);
	}

	/**
	* PDF line renderer
	* @param PGVRPDF &$pdf
	*/
	function render(&$pdf) {
		if ($this->x1 == ".") $this->x1=$pdf->GetX();
		if ($this->y1 == ".") $this->y1=$pdf->GetY();
		if ($this->x2 == ".") {
			$this->x2 = $pdf->getMaxLineWidth();
		}
		if ($this->y2 == ".") $this->y2=$pdf->GetY();

		$pdf->Line($this->x1, $this->y1, $this->x2, $this->y2);
	}
}

?>
