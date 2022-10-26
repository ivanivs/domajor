<?php
class Fupimage {
	public  $image;
	public $type;
	public $width;
	public $height;
	public $marked_image;
	public $sizes;
	public $position = "TR";
	public $offset_x;
	public $offset_y;
	public $orientation;
	public $imageCreated = false;
	public $gd_version;
	public $fixedColor = ''; 

function __construct($res) {
		list($this->type, $this->image) = $this->_getImage($res);
		if (!$this->image) {
			$this->_die_error("Your current PHP setup does not support ". $this->type ." images");
		}	
		$this->width = imagesx($this->image);
		$this->height = imagesy($this->image);		
	}

public function setType($type) {
		$this->type = $type;
	}

public function addWatermark($mark) {
	$this->orientation = ($this->width > $this->height) ? "H" : "V";
	$this->sizes = $this->_getTextSizes($mark);
	$this->_getOffsets();
	$chunk = $this->_getChunk();
		if (!$chunk) $this->_die_error("Could not extract chunk from image");
		$img_mark = $this->_createEmptyWatermark();
		$img_mark = $this->_addTextWatermark($mark, $img_mark, $chunk);
			imagedestroy($chunk);
			$this->_createMarkedImage($img_mark, $type, 70);
}

public function getMarkedImage() {
	if ($this->imageCreated == false) {
		$this->addWatermark($this->version);
	}
	return $this->marked_image;
}

public function setPosition($newposition) {
	$valid_positions = array("TL","TR","BL","BR");
	$newposition = strtoupper($newposition);
	if (in_array($newposition, $valid_positions)) {
		$this->position = $newposition;
		return true;
	}
	return false;
}

public function setFixedColor($color) {
	$text_color = array();
	if (is_array($color) and sizeof($color) == 3) {
		$text_color["r"] = $color[0];
		$text_color["g"] = $color[1];
		$text_color["b"] = $color[2];
	} 
	elseif (preg_match('/^#?([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})$/i', $color, $matches)) {
		$text_color["r"] = hexdec($matches[1]);
		$text_color["g"] = hexdec($matches[2]);
		$text_color["b"] = hexdec($matches[3]);
	} else {
		return false;
	}
	foreach (array("r", "g", "b") as $key) {
		if (!array_key_exists($key, $text_color) or $text_color[$key] < 0 or $text_color[$key] > 255) {
			return false;
		}
	}
	$this->fixedColor = $text_color;
	return true;
}

private	function _die_error($errmsg) {
	die($errmsg);
}

private function _getTextSizes($text) {
	$act_scale = 0;
	$act_font = 0;
	$marklength = strlen($text);
	$scale = ($this->orientation == "H") ? $this->width : $this->height; 
	$char_widthmax = intval(($scale / $marklength) - 0.5);
	for ($size = 5; $size >= 1; $size--) {
		$box_w = imagefontwidth($size);
		$box_h = imagefontheight($size);
		$box_spacer_w = 0;
		$box_spacer_h = 0;
		if ($this->orientation == "H") {
			$box_h *= 2;
			$box_w *= 1.75;
			$box_w *= $marklength;
			$box_w += intval($this->width * 0.05);
			$box_spacer_w = intval($this->width * 0.05);
			$box_spacer_h = intval($this->height * 0.01);
		} else {
			$box_w *= 3;
			$box_h *= 1.1;
			$box_h *= $marklength;
			$box_spacer_h = intval($this->height * 0.05);
			$box_spacer_w = intval($this->width * 0.01);
		}
		$box_scale = ($this->orientation == "H") ? $box_w + $box_spacer_w : $box_h + $box_spacer_h;
		if ($box_scale < $scale && $box_scale > $act_scale) { 
			$act_font = 2;//$size; 
			$act_scale = $box_scale; 
		}
	}
	return array(	"fontsize"	=> $act_font,
			"box_w"	=> $box_w,
			"box_h"	=> $box_h,
			"spacer_w"	=> $box_spacer_w,
			"spacer_h"	=> $box_spacer_h
			);
}


private	function _getChunk() {
	$chunk = imagecreatetruecolor($this->sizes["box_w"], $this->sizes["box_h"]);
	imagecopy($chunk,$this->image, 0, 0, $this->offset_x, $this->offset_y,	$this->sizes["box_w"], $this->sizes["box_h"]);
	return $chunk;
}

private	function _createEmptyWatermark() {
	return imagecreatetruecolor($this->sizes["box_w"], $this->sizes["box_h"]);
}

private	function _addTextWatermark($mark, $img_mark, $chunk) {
	imagetruecolortopalette($chunk, true, 65535);
	$text_color = array("r" => 0, "g" => 0, "b" => 0);
	if (is_array($this->fixedColor)) {
		$text_color = $this->fixedColor;
	} else {
		for($x = 0; $x <= $this->sizes["box_w"]; $x++) {
			for ($y = 0; $y <= $this->sizes["box_h"]; $y++) { 
				$colors = imagecolorsforindex($chunk, imagecolorat($chunk, $x, $y));
				$text_color["r"] += $colors["red"];
				$text_color["r"] /= 2;
				$text_color["g"] += $colors["green"];
				$text_color["g"] /= 2;
				$text_color["b"] += $colors["blue"];
				$text_color["b"] /= 2;
			}
		}
		$text_color["r"] = $text_color["r"] < 128 ? $text_color["r"] + 128 : $text_color["r"] - 128;
		$text_color["g"] = $text_color["g"] < 128 ? $text_color["g"] + 128 : $text_color["g"] - 128;
		$text_color["r"] = $text_color["r"] < 128 ? $text_color["r"] + 128 : $text_color["r"] - 128;
	}
	$mark_bg = imagecolorallocate($img_mark,	($text_color["r"] > 128 ? 10 : 240), ($text_color["g"] > 128 ? 10 : 240), ($text_color["b"] > 128 ? 10 : 240));
	$mark_col = imagecolorallocate($img_mark, $text_color["r"], $text_color["g"], $text_color["b"]);
	imagefill($img_mark, 0, 0, $mark_bg);
	imagecolortransparent($img_mark, $mark_bg);
	if ($this->orientation == "H") {
		imagestring($img_mark, $this->sizes["fontsize"], 1, 0, $mark, $mark_col); 
	} else {
		imagestringup($img_mark, $this->sizes["fontsize"], 0, $this->sizes["box_h"] - 5, $mark, $mark_col);
	}
	return $img_mark;
}

private function _createMarkedImage($img_mark, $type, $pct) {
	$this->marked_image = imagecreatetruecolor($this->width, $this->height);
	imagecopy($this->marked_image, $this->image, 0, 0, 0, 0, $this->width, $this->height);
	imagecopymerge($this->marked_image, $img_mark, $this->offset_x, $this->offset_y, 0,0, $this->sizes["box_w"], $this->sizes["box_h"], $pct);
	$this->imageCreated = true;
	}

private function _getOffsets() {
	$width_mark = $this->sizes["box_w"] + $this->sizes["spacer_w"];
	$height_mark = $this->sizes["box_h"] + $this->sizes["spacer_h"];
	$width_left = $this->width - $width_mark;
	$height_left = $this->height - $height_mark; 
	switch ($this->position) {
		case "TL": $this->offset_x = $width_left >= 5 ? 5 : $width_left;	$this->offset_y = $height_left >= 5 ? 5 : $height_left; break;
		case "TR": $this->offset_x = $this->width - $width_mark; $this->offset_y = $height_left >= 5 ? 5 : $height_left; break;
		case "BL": $this->offset_x = $width_left >= 5 ? 5 : $width_left;	$this->offset_y = $this->height - $height_mark; break;
		case "BR": $this->offset_x = $this->width - $width_mark; $this->offset_y = $this->height - $height_mark; break;
	}
}

private function _getImage($res) {
	$img;$type;
	if (intval(@imagesx($res)) > 0) {
		$img = $res;
	} else {
		$imginfo = getimagesize($res);
		switch($imginfo[2]) {
			case 1: $type = "GIF"; if (function_exists("imagecreatefromgif")) {$img = imagecreatefromgif($res);} else {die("Unsupported image type: $type");} break;
			case 2:	$type = "JPG"; if (function_exists("imagecreatefromjpeg")) {$img = imagecreatefromjpeg($res);} else {die("Unsupported image type: $type");}break;
			case 3:	$type = "PNG";if (function_exists("imagecreatefrompng")) {$img = imagecreatefrompng($res);} else {die("Unsupported image type: $type");}break;
		}
	}
	return array($type, $img);
}
}


?>
