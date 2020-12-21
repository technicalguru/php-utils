<?php

namespace TgUtils;

class ImageUtils {

	public static function createThumbnail($imagePath, $maxWidth, $maxHeight, $targetDir = NULL) {
		$pathinfo   = pathinfo($imagePath);
		$targetPath = '.';
		if ($targetDir == NULL) {
			$targetPath = $pathinfo['dirname'].'/'.$pathinfo['filename'].'.thumbnail.';
		} else {
			$targetPath = $targetDir.'/'.$pathinfo['filename'].'.thumbnail.';
		}
		if (class_exists('Imagick')) {
			echo 'Using ImageMagick<br>';
			$image = new \Imagick($imagePath);
			$image->thumbnailImage($maxWidth, $maxHeight, TRUE);
			$targetPath .= 'png';
			if (!$image->writeImage($targetPath)) {
				$targetPath = NULL;
			}
		} else {
			// We use GD
			$imageDetails = getimagesize($imagePath);
			$image = self::readImage($imagePath, $imageDetails);
			if ($image != NULL) {
				// Compute new dimensions
				$thWidth  = $maxWidth;
				$thHeight = $maxHeight;
				if ($imageDetails[0] < $imageDetails[1]) {
					// Portrait
					$thWidth = intval($imageDetails[0] * $thHeight / $imageDetails[1]);
				} else {
					// Landscape or squared
					$thHeight = intval($imageDetails[1] * $thWidth / $imageDetails[0]);
				}

				// Scale it
		        $thumbnail = imagecreatetruecolor($thWidth, $thHeight);
				imagecopyresized($thumbnail, $image, 0, 0, 0, 0, $thWidth, $thHeight, $imageDetails[0], $imageDetails[1]);
				// Writing it
				$gdInfo  = gd_info();
				// Preferential order PNG, JPEG, WEBP, XPM, WBMP, GIF, BMP 
				if ($gdInfo['PNG Support']) {
					$targetPath .= 'png';
					if (!imagepng($thumbnail, $targetPath)) $targetPath = NULL;
				} else if ($gdInfo['JPEG Support']) {
					$targetPath .= 'jpg';
					if (!imagejpeg($thumbnail, $targetPath)) $targetPath = NULL;
				} else if ($gdInfo['WebP Support']) {
					$targetPath .= 'webp';
					if (!imagewebp($thumbnail, $targetPath)) $targetPath = NULL;
				} else if ($gdInfo['XPM Support']) {
					$targetPath .= 'xpm';
					if (!imagexbm($thumbnail, $targetPath)) $targetPath = NULL;
				} else if ($gdInfo['WBMP Support']) {
					$targetPath .= 'wbmp';
					if (!imagewbmp($thumbnail, $targetPath)) $targetPath = NULL;
				} else if ($gdInfo['GIF Create Support']) {
					$targetPath .= 'gif';
					if (!imagegif($thumbnail, $targetPath)) $targetPath = NULL;
				} else if ($gdInfo['BMP Support']) {
					$targetPath .= 'bmp';
					if (!imagebmp($thumbnail, $targetPath)) $targetPath = NULL;
				} else {
					$targetPath = NULL;
				}
				imagedestroy($thumbnail);
				imagedestroy($image);
			} else {
				$targetPath = NULL;
			}
		}
		return $targetPath;
	}

	public static function readImage($imagePath, $imageDetails = NULL) {
		if (file_exists($imagePath)) {
			if (class_exists('Imagick')) {
			} else {
				if ($imageDetails == NULL) $imageDetails = getimagesize($imagePath);
				if ($imageDetails !== FALSE) {
					$gdInfo  = gd_info();
					$image        = NULL;
					switch ($imageDetails[2]) {
					case IMAGETYPE_BMP:
						if ($gdInfo['BMP Support']) {
							$image = imagecreatefrombmp($imagePath);
						}
						break;
					case IMAGETYPE_GIF:
						if ($gdInfo['GIF Read Support']) {
							$image = imagecreatefromgif($imagePath);
						}
						break;
					case IMG_JPEG: 
					case IMG_JPEG: 
						if ($gdInfo['JPEG Support']) {
							$image = imagecreatefromjpeg($imagePath);
						}
						break;
					case IMAGETYPE_PNG:
						if ($gdInfo['PNG Support']) {
							$image = imagecreatefrompng($imagePath);
						}
						break;
					case IMAGETYPE_WBMP:
						if ($gdInfo['WBMP Support']) {
							$image = imagecreatefromwbmp($imagePath);
						}
						break;
					case IMG_XPM:
						if ($gdInfo['XPM Support']) {
							$image = imagecreatefromxpm($imagePath);
						}
						break;
					case IMAGETYPE_WEBP:
						if ($gdInfo['WebP Support']) {
							$image = imagecreatefromwebp($imagePath);
						}
						break;
					}
				}
				return $image;
			}
		}
		return NULL;
	}

	public static function computeRatioSize($image, $maxWidth, $maxHeight) {
	}
}

