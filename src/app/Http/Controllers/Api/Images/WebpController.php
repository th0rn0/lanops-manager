<?php

namespace App\Http\Controllers\Api\Images;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use Image;

class WebpController extends Controller
{
    public function convert($image)
    {
        if (!file_exists($image))
        {
            $image_path_parts = pathinfo($image);

            $orgImageName = $image_path_parts['dirname'] . "/" . $image_path_parts['filename'];
            if(!file_exists($orgImageName))
            {
                // TODO
                return "Error: Org File not Found: " . $orgImageName . " (from: " . $image . ")";
            }

            $imgData = $this->imagecreatefromfile($orgImageName);

            if(!imagepalettetotruecolor($imgData))
            {
                imagedestroy($imgData);
                return redirect($orgImageName);
            }

            if(!imagewebp($imgData, $image, 90))
            {
                imagedestroy($imgData);
                return redirect($orgImageName);
            }

            imagedestroy($imgData);
        }

        return redirect($image);
    }

    private function imagecreatefromfile( $filename ) {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException('File "'.$filename.'" not found.');
        }
        switch ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ))) {
            case 'jpeg':
            case 'jpg':
                return imagecreatefromjpeg($filename);
            break;

            case 'png':
                return imagecreatefrompng($filename);
            break;

            case 'gif':
                return imagecreatefromgif($filename);
            break;

            default:
                throw new InvalidArgumentException('File "'.$filename.'" is not valid jpg, png or gif image.');
            break;
        }
    }
}