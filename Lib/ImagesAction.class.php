<?php

class ImagesAction extends GlobalAction {
	public function index() {
		$size = trim($_GET['var']);
		$file = './Runtime/Temp/'.$size.'.jpg';

		header('Content-type: image/jpeg');
        header("Cache-Control: public");
        header("Pragma: cache");

        $offset = 30*60*60*24; // cache 1 month
        $timestamp = strtotime('2012-8-4 12:40:00');
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $timestamp) . " GMT");
        $ExpStr = "Expires: ".gmdate("D, d M Y H:i:s", time() + $offset)." GMT";
        header($ExpStr);

		if(!file_exists($file)) {
			list($width, $height) = explode('x', $size);
			$im = imagecreatetruecolor($width, $height);
			$color = imagecolorallocate($im, 195, 195, 195);
			$font_color = imagecolorallocate($im, 100, 100, 100);

			imagefill($im, 0, 0, $color);

			$font_size = 5;

			list($x, $y) = $this->text_center($im, $size, $font_size);

			imagestring($im, $font_size, $x, $y, $size, $font_color);

			imagejpeg($im, $file);
			imagedestroy($im);
		}
        $image_content = file_get_contents($file);
        echo $image_content;
        unset($image_content);
	}

	protected function text_center($image, $text, $font_size) {
		$width = array(1 => 5, 6, 7, 8, 9);
		$height = array(1 => 6, 8, 13, 15, 15);

		$xi = imagesx($image);
		$yi = imagesy($image);

		$xr = $width[$font_size] * strlen($text);
		$yr = $height[$font_size];

		$x = intval(($xi - $xr) / 2);
		$y = intval(($yi - $yr) / 2);

		return array($x, $y);
	}
}

?>