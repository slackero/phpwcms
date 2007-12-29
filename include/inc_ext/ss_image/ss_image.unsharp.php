<?php

/* 

WARNING! Due to a known bug in PHP 4.3.2 this script is not working well in this version. The sharpened images get too dark. The bug is fixed in version 4.3.3.


Unsharp masking is a traditional darkroom technique that has proven very suitable for 
digital imaging. The principle of unsharp masking is to create a blurred copy of the image
and compare it to the underlying original. The difference in colour values
between the two images is greatest for the pixels near sharp edges. When this 
difference is subtracted from the original image, the edges will be
accentuated. 

The Amount parameter simply says how much of the effect you want. 100 is 'normal'.
Radius is the radius of the blurring circle of the mask. 'Threshold' is the least
difference in colour values that is allowed between the original and the mask. In practice
this means that low-contrast areas of the picture are left unrendered whereas edges
are treated normally. This is good for pictures of e.g. skin or blue skies.

Any suggenstions for improvement of the algorithm, expecially regarding the speed
and the roundoff errors in the Gaussian blur process, are welcome.

f.e. amount = 100, radius = 1, treshold = 3

*/

function UnsharpMask($img, $amount, $radius, $threshold)	{
////////////////////////////////////////////////////////////////////////////////////////////////
////
////                  p h p U n s h a r p M a s k
////
////	Unsharp mask algorithm by Torstein Hønsi 2003.
////	         thoensi_at_netcom_dot_no.
////	           Please leave this notice.
////
///////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////
////    The original script was changed a little bit by Yuriy Horobey
////	to be included into ImageProcessor script.
////	keep in mind that this script is freeware and you were not charged for it.
////	I have included it into the package just for your convenience
////	all credit goes to the author as it is noticed above.
///////////////////////////////////////////////////////////////////////////////////////////////



	// Attempt to calibrate the parameters to Photoshop:
	if ($amount > 500)	$amount = 500;
	$amount = $amount * 0.016;
	if ($radius > 50)	$radius = 50;
	$radius = $radius * 2;
	if ($threshold > 255)	$threshold = 255;
	
	$w = imagesx($img); $h = imagesy($img);
	$img_result = imagecreatetruecolor($w, $h);
	imagecopy ($img_result, $img, 0, 0, 0, 0, $w, $h);

	$radius = abs(round($radius)); 	// Only integers make sense.
	if ($radius == 0) {
		return $img_result;
	}
	$imgCanvas = imagecreatetruecolor($w, $h);
	$imgCanvas2 = imagecreatetruecolor($w, $h);
	$imgBlur = imagecreatetruecolor($w, $h);
	$imgBlur2 = imagecreatetruecolor($w, $h);
	imagecopy ($imgCanvas, $img, 0, 0, 0, 0, $w, $h);
	imagecopy ($imgCanvas2, $img, 0, 0, 0, 0, $w, $h);

	

	// Gaussian blur matrix:
	//						
	//	1	2	1		
	//	2	4	2		
	//	1	2	1		
	//						
	//////////////////////////////////////////////////

	// Move copies of the image around one pixel at the time and merge them with weight
	// according to the matrix. The same matrix is simply repeated for higher radii.
	for ($i = 0; $i < $radius; $i++)	{
		imagecopy ($imgBlur, $imgCanvas, 0, 0, 1, 1, $w - 1, $h - 1); // up left
		imagecopymerge ($imgBlur, $imgCanvas, 1, 1, 0, 0, $w, $h, 50); // down right
		imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 1, 0, $w - 1, $h, 33.33333); // down left
		imagecopymerge ($imgBlur, $imgCanvas, 1, 0, 0, 1, $w, $h - 1, 25); // up right
		imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 1, 0, $w - 1, $h, 33.33333); // left
		imagecopymerge ($imgBlur, $imgCanvas, 1, 0, 0, 0, $w, $h, 25); // right
		imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 20 ); // up
		imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 16.666667); // down
		imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 0, $w, $h, 50); // center
		imagecopy ($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h);

		// During the loop above the blurred copy darkens, possibly due to a roundoff
		// error. Therefore the sharp picture has to go through the same loop to 
		// produce a similar image for comparison. This is not a good thing, as processing
		// time increases heavily.
		imagecopy ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 50);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 33.33333);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 25);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 33.33333);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 25);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 20 );
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 16.666667);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 50);
		imagecopy ($imgCanvas2, $imgBlur2, 0, 0, 0, 0, $w, $h);
		
		}

	// Calculate the difference between the blurred pixels and the original
	// and set the pixels
	for ($x = 0; $x < $w; $x++)	{ // each row
		for ($y = 0; $y < $h; $y++)	{ // each pixel
				
			$rgbOrig = ImageColorAt($imgCanvas2, $x, $y);
			$rOrig = (($rgbOrig >> 16) & 0xFF);
			$gOrig = (($rgbOrig >> 8) & 0xFF);
			$bOrig = ($rgbOrig & 0xFF);
			
			$rgbBlur = ImageColorAt($imgCanvas, $x, $y);
			
			$rBlur = (($rgbBlur >> 16) & 0xFF);
			$gBlur = (($rgbBlur >> 8) & 0xFF);
			$bBlur = ($rgbBlur & 0xFF);
			
			// When the masked pixels differ less from the original
			// than the threshold specifies, they are set to their original value.
			$rNew = (abs($rOrig - $rBlur) >= $threshold) 
				? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig)) 
				: $rOrig;
			$gNew = (abs($gOrig - $gBlur) >= $threshold) 
				? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig)) 
				: $gOrig;
			$bNew = (abs($bOrig - $bBlur) >= $threshold) 
				? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig)) 
				: $bOrig;
			
			
						
			if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) {
    				$pixCol = ImageColorAllocate($img_result, $rNew, $gNew, $bNew);
    				ImageSetPixel($img_result, $x, $y, $pixCol);
				}
}
		}

	imagedestroy($imgCanvas);
	imagedestroy($imgCanvas2);
	imagedestroy($imgBlur);
	imagedestroy($imgBlur2);
	
	return $img_result;

	}

?>