<?php

namespace TgUtils;

class ImageUtils {

	/**
	 * Creates a thumbnail image from the given image at the path.
	 * @param string $imagepath - path of original image
	 * @param int    $maxWidth  - maximum new width
	 * @param int    $maxHeight - maximum new height
	 * @param string $targetDir - target directory
	 * @return path of the new image file or NULL if it could not be created.
	 */
	public static function createThumbnail($imagePath, $maxWidth, $maxHeight, $targetDir = NULL) {
		$pathinfo   = pathinfo($imagePath);
		$targetPath = '.';
		if ($targetDir == NULL) {
			$targetPath = $pathinfo['dirname'].'/'.$pathinfo['filename'].'.thumbnail.';
		} else {
			$targetPath = $targetDir.'/'.$pathinfo['filename'].'.thumbnail.';
		}
		if (class_exists('Imagick')) {
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
				$size = self::computeNewSize($imageDetails[0], $imageDetails[1], $maxWidth, $maxHeight);

				// Scale it
		        $thumbnail = imagecreatetruecolor($size->width, $size-height);
				imagecopyresized($thumbnail, $image, 0, 0, 0, 0, $size->width, $size->height, $imageDetails[0], $imageDetails[1]);
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

	/**
	 * Reads an image with GD library.
	 * @param string $imagePath - where the image is stored.
	 * @param array  $imageDetails - the result from getimagesize() call when executed before.
	 * @return resource form GD library
	 */
	public static function readImage($imagePath, $imageDetails = NULL) {
		if (file_exists($imagePath)) {
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
		return NULL;
	}

	/**
	 * Returns an object with width and height attributes to resize.
	 * @param int $origWidth  - original width
	 * @param int $origHeight - original height
	 * @param int $maxWidth   - maximum new width
	 * @param int $maxHeight  - maximum new height
	 * @return object with width an height attribute
	 */
	public static function computeNewSize($origWidth, $origHeight, $maxWidth, $maxHeight) {
		// Compute new dimensions
		$rc = new \stdClass;
		$rc->width  = $maxWidth;
		$rc->height = $maxHeight;
		if ($origWidth < $origHeight) {
			// Portrait
			$rc->width = intval($origWidth * $rc->height / $origHeight);
		} else {
			// Landscape or squared
			$rc->height = intval($origHeight * $rc->width / $origWidth);
		}
		return $rc;
	}
}

