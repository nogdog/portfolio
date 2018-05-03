<?php

/**
* Resize image to specific dimension, cropping as needed
* @return resource Resized image resource, or boolean false on failure
* @param string $imgFile Path to image to be resized
* @param int $width
* @param int $height
* @param string $error Error message
*/
function resize($imgFile, $width, $height, &$error = null)
{
   $attrs = @getimagesize($imgFile);
   if($attrs == false or $attrs[2] != IMG_JPEG)
   {
      $error = "Uploaded image is not JPEG or is not readable by this page.";
      return false;
   }
   if($attrs[0] * $attrs[1] > 10000000)
   {
      $error = "Max pixels allowed is 10,000,000. Your {$attrs[0]} x " .
               "{$attrs[1]} image has " . $attrs[0] * $attrs[1] . " pixels.";
      return false;
   }
   $ratio = (($attrs[0] / $attrs[1]) < ($width / $height)) ?
            $width / $attrs[0] : $height / $attrs[1];
   $x = max(0, round($attrs[0] / 2 - ($width / 2) / $ratio));
   $y = max(0, round($attrs[1] / 2 - ($height / 2) / $ratio));
   $src = imagecreatefromjpeg($imgFile);
   if($src == false)
   {
      $error = "Unknown problem trying to open uploaded image.";
      return false;
   }
   $resized = imagecreatetruecolor($width, $height);
   $result = imagecopyresampled($resized, $src, 0, 0, $x, $y, $width, $height,
             round($width / $ratio, 0), round($height / $ratio));
   if($result == false)
   {
      $error = "Error trying to resize and crop image.";
      return false;
   }
   else
   {
      return $resized;
   }
}