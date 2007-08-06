<?php
/**
 * HTML Report Generator
 *
 * used by the SAX parser to generate HTML reports from the XML report file.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2005  John Finlay and Others
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
 */

if (stristr($_SERVER["SCRIPT_NAME"], basename(__FILE__))!==false) {
	print "You cannot access an include file directly.";
	exit;
}

require_once("includes/reportbase.php");

/**
 * main PGV Report Class
 * @package PhpGedView
 * @subpackage Reports
 */
class PGVReport extends PGVReportBase {
	var $headerElements;
	var $pageHeaderElements;
	var $footerElements;
	var $bodyElements;
	var $X=0;
	var $Y=0;
	var $currentStyle='';
	var $pageN = 1;
	var $printedfootnotes = array();
	
	function setup($pw, $ph, $pageSize, $o, $m, $showGenText=true) {
		global $pgv_lang, $VERSION;
		parent::setup($pw, $ph, $pageSize, $o, $m, $showGenText);
		
		$this->headerElements = array();
		$this->pageHeaderElements = array();
		$this->footerElements = array();
		$this->bodyElements = array();
		
		if ($showGenText) {
			$element = new PGVRCellHTML(0,10, "C", "");
			$element->addText("$pgv_lang[generated_by] PhpGedView $VERSION");
			$element->setUrl("http://www.phpgedview.net/");
			$this->footerElements[] = $element;
		}
	}
	
	function addPageHeader(&$element) {
		$this->pageHeaderElements[] = $element;
		return count($this->headerElements)-1;
	}

	function addElement(&$element) {
		if ($this->processing=="H") return $this->headerElements[] = $element;
		if ($this->processing=="PH") return $this->pageHeaderElements[] = $element;
		if ($this->processing=="F") return $this->footerElements[] = $element;
		if ($this->processing=="B") return $this->bodyElements[] = $element;
	}
	
	function runPageHeader() {
		foreach($this->pageHeaderElements as $indexval => $element) {
			if (is_string($element) && $element=="footnotetexts") $this->Footnotes();
			else if (is_string($element) && $element=="addpage") $this->AddPage();
			else if (is_object($element)) $element->render($this);
		}
	}
	
	function Footnotes() {
		$this->currentStyle = "";
		foreach($this->printedfootnotes as $indexval => $element) {
			$element->renderFootnote($this);
		}
	}

	function run() {
		global $download, $embed_fonts, $TEXT_DIRECTION;

		print "<html>\n<head>";
		print "<style type=\"text/css\">\n";
		$this->PGVRStyles['footer'] = array('name'=>'footer', 'font'=>'Arial', 'size'=>'10', 'style'=>'');
		foreach($this->PGVRStyles as $class=>$style) {
			$styleAdd = "";
			if (strstr($style['style'], 'B')!==false) $styleAdd .= " font-weight: bold;";  
			if (strstr($style['style'], 'U')!==false) $styleAdd .= " text-decoration: underline;";
			if ($style['font']=='') $style['font'] = 'Arial';
			else if ($style['font']=='LucidaSansUnicode') $style['font'] = 'Arial';
			print ".".$class." {\n";
			print "font-size: ".($style['size'])."pt;\n";
			print "font-family: ".$style['font'].";\n";
			print $styleAdd."\n";
			print "}\n";
		}
		print "</style>\n";
		print "</head>\n<body>\n";
		if (!isset($this->currentStyle)) $this->currentStyle = "";
		$temp = $this->currentStyle;
		//-- header
		print "<div id=\"headerdiv\" style=\"position: relative; top: auto; width: 100%;\">\n";
		foreach($this->headerElements as $indexval => $element) {
			if (is_string($element) && $element=="footnotetexts") $this->Footnotes();
			else if (is_string($element) && $element=="addpage") $this->AddPage();
			else $element->render($this);
		}
		print "</div>\n";
		//-- body
		
		$oldy = $this->Y;
		$this->Y=0;
		$this->runPageHeader();
		print "<div id=\"bodydiv\" style=\"position: relative; top: auto; width: 100%; height: 100%;\">\n";
		$this->currentStyle = "";
		foreach($this->bodyElements as $indexval => $element) {
			if (is_string($element) && $element=="footnotetexts") $this->Footnotes();
			else if (is_string($element) && $element=="addpage") $this->AddPage();
			else if (is_object($element)) $element->render($this);
		}
		print "</div>\n";
		print "<script type=\"text/javascript\">\ndocument.getElementById('bodydiv').style.height='".($this->Y-$oldy)."pt';\n</script>\n";
		//-- footer
//		$this->SetY(-36);
		$oldy = $this->Y;
		print "<div id=\"footerdiv\" style=\"position: relative; top: auto; width: 100%; height: auto;\">\n";
		$this->currentStyle = "footer";
		foreach($this->footerElements as $indexval => $element) {
			if (is_string($element) && $element=="footnotetexts") $this->Footnotes();
			else if (is_string($element) && $element=="addpage") $this->AddPage();
			else if (is_object($element)) $element->render($this);
		}
		$this->currentStyle = $temp;
		print "</div>\n";
		
		print "<script type=\"text/javascript\">\ndocument.getElementById('footerdiv').style.height='".($this->Y-$oldy)."pt';\n</script>\n";
		print "</body>\n</html>\n";
	}
	
	function getStyle($s) {
		if (!isset($this->PGVRStyles[$s]) || $s=='') {
			$s = $this->currentStyle;
			if (empty($s)) {
				$s = 'footer';
			}
			$this->PGVRStyles[$s] = $s;
		}
		return $this->PGVRStyles[$s];
	}
	
	function getCurrentStyle() {
		return $this->currentStyle;
	}

	function setCurrentStyle($s) {
		$this->currentStyle = $s;
		//$style = $this->getStyle($s);
		//print_r($style);
		//$this->SetFont($style["font"], $style["style"], $style["size"]);
	}
	
	function GetX() {
		return $this->X;
	}
	
	function GetY() {
		return $this->Y;
	}
	
	function SetXY($x, $y) {
		$this->X = $x;
		$this->Y = $y;
	}
	
	function SetY($y) {
		$this->Y = $y;
	}
	
	function SetX($x) {
		$this->X = $x;
	}
	
	function AddPage() {
		$this->pageN++;
	}
	
	function PageNo() {
		return $this->pageN;
	}

	function getMaxWidth() {
		$w = (($this->pagew * 72) - ($this->margin)) - $this->X;
		return $w;
	}

	function getPageHeight() {
		return ($this->pageh*72)-$this->margin;
	}

	function clearPageHeader() {
		$this->pageHeaderElements = array();
	}
	
	function createCell($width, $height, $align, $style, $top=".", $left=".") {
		return new PGVRCellHTML($width, $height, $align, $style, $top, $left);
	}
	
	function createTextBox($width, $height, $border, $fill, $newline, $left=".", $top=".", $pagecheck="true") {
		return new PGVRTextBoxHTML($width, $height, $border, $fill, $newline, $left, $top, $pagecheck);
	}
	
	function createText($style, $color) {
		return new PGVRTextHTML($style, $color);
	}
	
	function createFootnote($style="") {
		return new PGVRFootnoteHTML($style);
	}
	
	function createPageHeader() {
		return new PGVRPageHeaderHTML();
	}
	
	function createImage($file, $x, $y, $w, $h) {
		return new PGVRImageHTML($file, $x, $y, $w, $h);
	}
	
	function createLine($x1, $y1, $x2, $y2) {
		return new PGVRLineHTML($x1, $y1, $x2, $y2);
	}
	
	function checkFootnote(&$footnote) {
		for($i=0; $i<count($this->printedfootnotes); $i++) {
			if ($this->printedfootnotes[$i]->getValue() == $footnote->getValue()) {
				return $this->printedfootnotes[$i];
			}
		}
		$footnote->setNum(count($this->printedfootnotes)+1);
		//$link = $this->AddLink();
		//$footnote->setAddlink($link);
		$this->printedfootnotes[] = $footnote;
		return false;
	}
	
	function getFootnotesHeight() {
		$h=0;
		foreach($this->printedfootnotes as $indexval => $element) {
			$h+=$element->getHeight($this);
		}
		return $h;
	}
	
	function write($text, $color='') {
		$style = $this->getStyle($this->getCurrentStyle());
		$styleAdd = "";
		if (!empty($color)) $styleAdd .= " color: ".$color.";";
		if ($style['font']=='') $style['font'] = 'Arial';
		print "<span class=\"".$style['name']."\" style=\"".$styleAdd."\">";
		print nl2br($text);
		print "</span>\n";
	}
	
	function getStringWidth($text) {
		$style = $this->getStyle($this->currentStyle);
		return strlen($text)*($style['size']/1.8);
	}
	
	function getCurrentStyleHeight() {
		if (empty($this->currentStyle)) return 12;
		$style = $this->getStyle($this->currentStyle);
		return $style["size"];
	}
} //-- end PGVReport

$pgvreport = new PGVReport();
$PGVReportRoot = $pgvreport;

/**
 * Cell element
 */
class PGVRCellHTML extends PGVRCell {
	
	function PGVRCellHTML($width, $height, $align, $style, $top=".", $left=".") {
		parent::PGVRCell($width, $height, $align, $style, $top, $left);
	}
	
	function render(&$pdf) {
		global $TEXT_DIRECTION, $embed_fonts;
		if (strstr($this->text, "{nb}")!==false) return;
		/* -- commenting out because it causes too many problems
		if ($TEXT_DIRECTION=='rtl') {
			if ($this->align=='L') $this->align='R';
			else if ($this->align=='R') $this->align='L';
		}*/
		if ($pdf->getCurrentStyle()!=$this->styleName)
			$pdf->setCurrentStyle($this->styleName);
		$temptext = preg_replace("/#PAGENUM#/", $pdf->PageNo(), $this->text);
		//$temptext = preg_replace("/#PAGENUM#/", 1, $this->text);
		$curx = $pdf->GetX();
		$cury = $pdf->GetY();
		if (($this->top!=".")||($this->left!=".")) {
			if ($this->top==".") $this->top = $cury;
			if ($this->left==".") $this->left = $curx;
			$pdf->SetXY($this->left, $this->top);
		}
		switch($this->align) {
			case 'R':
				$align = "right";
				break;
			case 'C':
				$align = "center";
				break;
			default:
				$align = "left";
				break;
		}
		if ($this->top==".") $this->top = "auto";
		else $this->top .= "pt";
		if ($this->left==".") $this->left = "auto";
		else $this->left .= "pt";
		print "<div style=\"top: ".$this->top."; left: ".$this->left."; ";
		if ($this->width>0) print "width: ".$this->width."pt; ";
		if ($this->height>0) {
			print "height: ".$this->height."pt; ";
			$pdf->SetY($pdf->GetY()+$this->height);
		}
		print "text-align: ".$align.";\">\n";
		
		if (!empty($url)) print "<a href=\"$url\">";
		//print $temptext;
		$pdf->write($temptext);
		if (!empty($url)) print "</a>";
		print "</div>\n";
		
//		$pdf->MultiCell($this->width,$this->height,$temptext,0,$this->align);
//		if (!empty($url)) {
//			$pdf->Link($curx, $cury, $this->width, $this->height, $url);
//		}
	}
}

/**
 * TextBox element
 */
class PGVRTextBoxHTML extends PGVRTextBox {
	
	function PGVRTextBoxHTML($width, $height, $border, $fill, $newline, $left=".", $top=".", $pagecheck="true") {
		parent::PGVRTextBox($width, $height, $border, $fill, $newline, $left, $top, $pagecheck);
	}
	
	function render(&$pdf) {
		global $lastheight;

		if (!empty($lastheight)) {
			if ($this->height < $lastheight) $this->height = $lastheight;
		}

		$startX = $pdf->GetX();
		$startY = $pdf->GetY();
//		if (!empty($this->fill)) {
//			$ct = preg_match("/#?(..)(..)(..)/", $this->fill, $match);
//			if ($ct>0) {
//				$this->style .= "F";
////				$r = hexdec($match[1]);
////				$g = hexdec($match[2]);
////				$b = hexdec($match[3]);
//				//$pdf->SetFillColor($r, $g, $b);
//			}
//		}
		if ($this->width==0) {
			$this->width = $pdf->getMaxWidth();
		}

		$newelements = array();
		$lastelement = "";
		//-- collapse duplicate elements
		for($i=0; $i<count($this->elements); $i++) {
			$element = $this->elements[$i];
			if (is_object($element)) {
				if ($element->get_type()=="PGVRText") {
					if (empty($lastelement)) $lastelement = $element;
					else {
						if ($element->getStyleName()==$lastelement->getStyleName()) {
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
				else if (($element->get_type()!="PGVRFootnote")||(trim($element->getValue())!="")) {
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
		if (!empty($lastelement)) $newelements[] = $lastelement;
		$this->elements = $newelements;

		//-- calculate the text box height
		$h = 0;
		$w = 0;
		for($i=0; $i<count($this->elements); $i++) {
			if (is_object($this->elements[$i])) {
				$ew = $this->elements[$i]->setWrapWidth($this->width-$w, $this->width);
				if ($ew==$this->width) $w=0;
				//-- $lw is an array 0=>last line width, 1=1 if text was wrapped, 0 if text did not wrap
				$lw = $this->elements[$i]->getWidth($pdf);
				if ($lw[1]==1) $w = $lw[0];
				else if ($lw[1]==2) $w=0;
				else $w += $lw[0];
				if ($w>$this->width) $w = $lw[0];
				$eh = $this->elements[$i]->getHeight($pdf);
				//if ($eh>$h) $h = $eh;
				//else if ($lw[1]) $h+=$eh;
				$h+=$eh;
			}
			else {
				$h += $pdf->getFootnotesHeight();
			}
		}
		if ($h>$this->height) $this->height=$h;
		//if (($this->width>0)&&($this->width<$w)) $this->width=$w;

		$curx = $pdf->GetX();
		$cury = $pdf->GetY();
		$curn = $pdf->PageNo();
		if (($this->top!=".")||($this->left!=".")) {
			if ($this->top==".") $this->top = $cury;
			if ($this->left==".") $this->left = $curx;
			$pdf->SetXY($this->left, $this->top);
			$startY = $this->top;
			$startX = $this->left;
			$cury = $startY;
			$curx = $startX;
		}

		$newpage = false;
		if ($this->pagecheck) {
			$ph = $pdf->getPageHeight();
			if ($pdf->GetY()+$this->height > $ph) {
				if ($this->border==1) {
					//print "HERE2";
					$pdf->AddPage();
					$newpage = true;
					$startX = $pdf->GetX();
					$startY = $pdf->GetY();
				}
				else if ($pdf->GetY()>$ph-36) {
					//print "HERE1";
					$pdf->AddPage();
					$startX = $pdf->GetX();
					$startY = $pdf->GetY();
				}
				else {
					//print "HERE3";
					$th = $this->height;
					$this->height = ($ph - $pdf->GetY())+36;
					$newpage = true;
				}
			}
		}

		print "<div style=\"position: absolute; left: ".$pdf->GetX()."pt; top: ".$pdf->GetY()."pt; width: ".$this->width."pt; height: ".$this->height."pt;";
		if (!empty($this->fill)) {
			print " background-color: ".$this->fill.";";
		}
		if ($this->border>0) {
			print " border: solid black ".$this->border."px;";
		}
		print "\">\n";
		$pdf->SetXY($pdf->GetX(), $pdf->GetY()+1);
		$curx = $pdf->GetX();
		foreach($this->elements as $indexval => $element) {
			if (is_string($element) && $element=="footnotetexts") $pdf->Footnotes();
			else if (is_string($element) && $element=="addpage") $pdf->AddPage();
			else $element->render($pdf, $curx);
		}
		print "</div>\n";
		if ($curn != $pdf->PageNo()) $cury = $pdf->GetY();
		if ($this->newline) {
			$lastheight = 0;
			$ty = $pdf->GetY();
			if ($curn != $pdf->PageNo()) $ny = $cury+$pdf->getCurrentStyleHeight();
			else $ny = $cury+$this->height;
			if ($ty > $ny) $ny = $ty;
			$pdf->SetY($ny+1);
			$pdf->SetX(0);
			print "<br />\n";
			//Here1 ty:71 ny:185 cury:169
			//print "Here1 ty:$ty ny:$ny cury:$cury ";
		}
		else {
			//print "Here2 ";
			$ty = $pdf->GetY()-1;
			if (($ty > $startY) && ($ty < $startY + $this->height)) $ty = $startY;
			$pdf->SetXY($curx+$this->width, $ty);
			$lastheight = $this->height;
		}
	}
}

/**
 * Text element
 */
class PGVRTextHTML extends PGVRText {

	function PGVRTextHTML($style, $color) {
		parent::PGVRText($style, $color);
	}
	
	function render(&$pdf, $curx=0) {
		global $embed_fonts;
		$pdf->setCurrentStyle($this->styleName);
		$temptext = preg_replace("/#PAGENUM#/", $pdf->PageNo(), $this->text);
		//print $this->text;
		$x = $pdf->GetX();
		$cury = $pdf->GetY();

//		if (!empty($this->color)) {
//			$ct = preg_match("/#?(..)(..)(..)/", $this->color, $match);
//			if ($ct>0) {
//				//$this->style .= "F";
//				$r = hexdec($match[1]);
//				$g = hexdec($match[2]);
//				$b = hexdec($match[3]);
//				$pdf->SetTextColor($r, $g, $b);
//			}
//		}

//		$lines = preg_split("/\n/", $temptext);
//		$styleh = $pdf->getCurrentStyleHeight();
//		if (count($lines)>0) {
//			foreach($lines as $indexval => $line) {
//				$pdf->SetXY($x, $cury);
////				print "[$x $cury $line]";
//				$pdf->Write($styleh,$line);
//				$cury+=$styleh+1;
//				if ($cury>$pdf->getPageHeight()) $cury = $pdf->getY()+$styleh+1;
//				$x = $curx;
//			}
//		}
//		else $pdf->Write($pdf->getCurrentStyleHeight(),$temptext);

		$pdf->write($temptext, $this->color);
		
//		$ct = preg_match_all("/".chr(215)."/", $temptext, $match);
//		if ($ct>1) {
//			$x = $pdf->GetX();
//			$x = $x - pow(1.355, $ct);
//			$pdf->SetX($x);
//		}
	}

	function getHeight(&$pdf) {
		$ct = substr_count($this->text, "\n");
		if ($ct>0) $ct+=1;
		$style = $pdf->getStyle($this->styleName);
		$h = (($style["size"]+4)*$ct);
		//print "[".$this->text." $ct $h]";
		return $h;
	}

	function getWidth(&$pdf) {
		$pdf->setCurrentStyle($this->styleName);
		if (!isset($this->text)) $this->text = "";
		$lw = $pdf->GetStringWidth($this->text);
		if ($this->wrapWidth > 0) {
			if ($lw > $this->wrapWidth) {
				$lines = preg_split("/\n/", $this->text);
				$newtext = "";
				$wrapwidth = $this->wrapWidth;
				foreach($lines as $indexval => $line) {
					$w = $pdf->GetStringWidth($line)+10;
					if ($w>$wrapwidth) {
						$words = preg_split("/\s/", $line);
						$lw = 0;
						foreach($words as $indexval => $word) {
							$lw += $pdf->GetStringWidth($word." ");
							if ($lw <= $wrapwidth) $newtext.=$word." ";
							else {
								//print "NEWLNE $word\n";
								$lw = $pdf->GetStringWidth($word." ");
								$newtext .= "\n$word ";
								$wrapwidth = $this->wrapWidth2;
							}
						}
						$newtext .= "\n";
					}
					else $newtext .= $line."\n";
				}
				$this->text = $newtext;
				//$this->text = preg_replace("/\n/", "\n~", $this->text);
				//print $this->wrapWidth." $lw [".$this->text."]1 ";
				return array($lw, 1);
			}
		}
		$l = 0;
		if (preg_match("/\n$/", $this->text)>0) $l=2;
		//print $this->wrapWidth." $lw [".$this->text."]$l ";
		return array($lw, $l);
	}
}

/**
 * Footnote element
 */
class PGVRFootnoteHTML extends PGVRFootnote {
	var $styleName;
	var $addlink;
	var $num;

	function PGVRFootnoteHTML($style="") {
		parent::PGVRFootnote($style);
	}
	
	function render(&$pdf) {
		global $footnote_count, $embed_fonts;

		$fn = $pdf->checkFootnote($this);
		if ($fn===false) {
			$pdf->setCurrentStyle("footnotenum");
//			$pdf->Write($pdf->getCurrentStyleHeight(),$this->num." ", $this->addlink);
			print "<sup><a href=\"#footnote".$this->num."\">";
			$pdf->write($this->num." ");
			print "</a></sup>\n";
		}
		else {
			$fn->rerender($pdf);
		}
	}

	function rerender(&$pdf) {
		global $footnote_count;
		if (empty($this->num)) {
			if (empty($footnote_count)) $footnote_count = 1;
			else $footnote_count++;

			$this->num = $footnote_count;
		}
		$pdf->setCurrentStyle("footnotenum");
		print "<a href=\"#footnote".$this->num."\">";
		$pdf->write($this->num." ");
		print "</a>\n";
	}

	function renderFootnote(&$pdf) {
		global $embed_fonts;
		if ($pdf->getCurrentStyle()!=$this->styleName)
			$pdf->setCurrentStyle($this->styleName);
		$temptext = preg_replace("/#PAGENUM#/", $pdf->PageNo(), $this->text);

		//$pdf->SetLink($this->addlink, -1);
		//$pdf->Write($pdf->getCurrentStyleHeight(),$this->num.". ".$temptext."\n\n");
		print "<a name=\"footnote".$this->num."\">".$this->num.". ";
		$pdf->write($temptext."\n\n");
		print "</a>\n";
		$pdf->SetY($pdf->GetY()+$this->getFootnoteHeight($pdf));
	}
	
	function getFootnoteHeight(&$pdf) {
		$ct = substr_count($this->text, "\n");
		if ($ct>0) $ct+=3;
		$style = $pdf->getStyle($this->styleName);
		$h = round(($style["size"]+4.2)*$ct);
		//print "[".$this->text." $ct $h]";
		return $h;
	}
}

/**
 * PageHeader element
 */
class PGVRPageHeaderHTML extends PGVRPageHeader {
	function PGVRPageHeaderHTML() {
		parent::PGVRPageHeader();
	}
	
	function render(&$pdf) {
		$pdf->clearPageHeader();
		foreach($this->elements as $indexval => $element) {
			$pdf->addPageHeader($element);
		}
	}
}

/**
 * image element
 */
class PGVRImageHTML extends PGVRImage {
	
	function PGVRImageHTML($file, $x, $y, $w, $h) {
		parent::PGVRImage($file, $x, $y, $w, $h);
	}
	
	function render(&$pdf) {
		global $lastpicbottom, $lastpicpage, $lastpicleft, $lastpicright;;
		if ($this->x==0) $this->x=$pdf->GetX();
		if ($this->y==0) {
			//-- first check for a collision with the last picture
			if (isset($lastpicbottom)) {
				if (($pdf->PageNo()==$lastpicpage)&&($lastpicbottom >= $pdf->GetY())&&($this->x>=$lastpicleft)&&($this->x<=$lastpicright))
					$pdf->SetY($lastpicbottom+5);
			}
			$this->y=$pdf->GetY();
		}
		//$pdf->Image($this->file, $this->x, $this->y, $this->width, $this->height);
		print "<img src=\"$this->file\" style=\"position: absolute; left: ".$this->x."pt; top: ".$this->y."pt; width: ".$this->width."pt; height: ".$this->height."pt;\" />\n";
		$lastpicbottom = $this->y + $this->height;
		$lastpicpage = $pdf->PageNo();
		$lastpicleft=$this->x;
		$lastpicright=$this->x+$this->width;
	}


	function getHeight(&$pdf) {
		return $this->height;
	}

	function getWidth(&$pdf) {
		return $this->width;
	}
} //-- END PGVRImage

/**
 * line element
 */
class PGVRLineHTML extends PGVRLine {
	function PGVRLineHTML($x1, $y1, $x2, $y2) {
		parent::PGVRLine($x1, $y1, $x2, $y2);
	}
	function render(&$pdf) {
		if ($this->x1==".") $this->x1=$pdf->GetX();
		if ($this->y1==".") $this->y1=$pdf->GetY();
		if ($this->x2==".") $this->x2=$pdf->GetX();
		if ($this->y2==".") $this->y2=$pdf->GetY();
		//$pdf->Line($this->x1, $this->y1, $this->x2, $this->y2);
		// TODO Non verticle or horizontal lines can use a series of divs absolutely positioned
		if ($this->x1 == $this->x2 || $this->y1==$this->y2) {
			print "<div style=\"position: absolute; overflow: hidden; border: solid black 1px; left: ".($this->x1-1)."pt; top: ".($this->y1+1)."pt; width: ".($this->x2-$this->x1)."pt; height: ".($this->y2-$this->y1)."pt;\">&nbsp;</div>";
		}
	}
} //-- END PGVRLine

?>