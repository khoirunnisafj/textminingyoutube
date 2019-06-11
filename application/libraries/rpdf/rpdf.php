<?php

class RPDF extends FPDF {

	var $widths;
	var $aligns;

	function TextWithDirection($x, $y, $txt, $direction = 'R') {
		if ($direction == 'R')
			$s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 1, 0, 0, 1, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
		elseif ($direction == 'L')
			$s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', -1, 0, 0, -1, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
		elseif ($direction == 'U')
			$s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, 1, -1, 0, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
		elseif ($direction == 'D')
			$s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, -1, 1, 0, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
		else
			$s = sprintf('BT %.2F %.2F Td (%s) Tj ET', $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
		if ($this->ColorFlag)
			$s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
		$this->_out($s);
	}

	function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle = 0) {
		$font_angle+=90 + $txt_angle;
		$txt_angle*=M_PI / 180;
		$font_angle*=M_PI / 180;

		$txt_dx = cos($txt_angle);
		$txt_dy = sin($txt_angle);
		$font_dx = cos($font_angle);
		$font_dy = sin($font_angle);

		$s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', $txt_dx, $txt_dy, $font_dx, $font_dy, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
		if ($this->ColorFlag)
			$s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
		$this->_out($s);
	}

	function WordWrap(&$text, $maxwidth, $maxline) {
		$text = trim($text);
		if ($text === '')
			return 0;
		$space = $this->GetStringWidth(' ');
		$lines = explode("\n", $text);
		$text = '';
		$count = 0;

		foreach ($lines as $line) {
			$words = preg_split('/ +/', $line);
			$width = 0;

			foreach ($words as $word) {
				$wordwidth = $this->GetStringWidth($word);
				if ($wordwidth > $maxwidth) {
					// Word is too long, we cut it
					for ($i = 0; $i < strlen($word); $i++) {
						$wordwidth = $this->GetStringWidth(substr($word, $i, 1));
						if ($width + $wordwidth <= $maxwidth) {
							$width += $wordwidth;
							$text .= substr($word, $i, 1);
						} else {
							$width = $wordwidth;
							$text = rtrim($text) . "\n" . substr($word, $i, 1);
							$count++;
						}
					}
				} elseif ($width + $wordwidth <= $maxwidth) {
					$width += $wordwidth + $space;
					$text .= $word . ' ';
				} else {
					$width = $wordwidth + $space;
					$text = rtrim($text) . "\n" . $word . ' ';
					$count++;
				}
			}
			$text = rtrim($text) . "\n";
			$count++;
			if ($count > $maxline)
				break;
		}
		$text = rtrim($text);
		return $count;
	}

	function SetWidths($w) {
		//Set the array of column widths
		$this->widths = $w;
	}

	function SetAligns($a) {
		//Set the array of column alignments
		$this->aligns = $a;
	}

	function Row($data) {
		//Calculate the height of the row
		$nb = 0;
		for ($i = 0; $i < count($data); $i++)
			$nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
		$h = 5 * $nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for ($i = 0; $i < count($data); $i++) {
			$w = $this->widths[$i];
			$a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x = $this->GetX();
			$y = $this->GetY();
			//Draw the border
			$this->Rect($x, $y, $w, $h);
			//Print the text
			$this->MultiCell($w, 5, $data[$i], 0, $a);
			//Put the position to the right of the cell
			$this->SetXY($x + $w, $y);
		}
		//Go to the next line
		$this->Ln($h);
	}

	function CheckPageBreak($h) {
		//If the height h would cause an overflow, add a new page immediately
		if ($this->GetY() + $h > $this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	function NbLines($w, $txt) {
		//Computes the number of lines a MultiCell of width w will take
		$cw = &$this->CurrentFont['cw'];
		if ($w == 0)
			$w = $this->w - $this->rMargin - $this->x;
		$wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
		$s = str_replace("\r", '', $txt);
		$nb = strlen($s);
		if ($nb > 0 and $s[$nb - 1] == "\n")
			$nb--;
		$sep = -1;
		$i = 0;
		$j = 0;
		$l = 0;
		$nl = 1;
		while ($i < $nb) {
			$c = $s[$i];
			if ($c == "\n") {
				$i++;
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
				continue;
			}
			if ($c == ' ')
				$sep = $i;
			$l+=$cw[$c];
			if ($l > $wmax) {
				if ($sep == -1) {
					if ($i == $j)
						$i++;
				} else
					$i = $sep + 1;
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
			} else
				$i++;
		}
		return $nl;
	}

}

?>