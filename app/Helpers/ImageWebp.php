<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ImageWebp
{
    public static function toWebp($path, $toPath, $toWidth = 0, $quality = 90)
    {
        //must to be an image
        if (!self::isImage($path)) return false;

        //base on php_gd lib, only support these kinds of image
        $canConvertExts = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
        if (!in_array(strtolower(self::getExt($path)), $canConvertExts)) return false;

        $currentSize = self::getDimension($path);

        if (
            isset($currentSize->width)
            && isset($currentSize->height)
            && isset($currentSize->type)
        ) {
            if ($toWidth > 0) {
                $toHeight = ($toWidth * $currentSize->height) / $currentSize->width;
            } else {
                $toWidth = $currentSize->width;
                $toHeight = $currentSize->height;
            }

            try {
                $toImage = imagecreatetruecolor($toWidth, $toHeight);
                switch ($currentSize->type) {
                    case IMAGETYPE_GIF:
                        $fromImage = imagecreatefromgif($path);
                        break;
                    case IMAGETYPE_JPEG:
                        $fromImage = imagecreatefromjpeg($path);
                        break;
                    default:
                        $fromImage = imagecreatefrompng($path);
                }

                if (
                    $fromImage !== false
                    && $toImage !== false
                ) {
                    imagecopyresampled($toImage, $fromImage, 0, 0, 0, 0, $toWidth, $toHeight, $currentSize->width, $currentSize->height);
                    $result = imagewebp($toImage, $toPath, $quality);

                    // free memory
                    imagedestroy($fromImage);
                    imagedestroy($toImage);

                    return $result;
                }
            } catch (\Exception $e) {
                Log::error('Image::convertToWebp error. ' . $e->getMessage());
            }
        }

        return false;
    }

    public static function compress($quality = 60)
    {
    }

    /**
     * check a file be an image or not
     * @param string $path
     * @return boolean
     */
    public static function isImage($path)
    {
        if (!self::exist($path)) return false;

        return in_array(strtolower(self::getExt($path)), self::$exts);
    }

    /**
     * get image width and height
     * @param string $path
     * @return boolean|\StdClass
     */
    public static function getDimension($path)
    {
        if (!self::exist($path)) return false;

        list($with, $height, $type) = getimagesize($path);

        return (object)array('width' => $with, 'height' => $height, 'type' => $type);
    }

    /**
     * check file is exists or not
     * @param string $path relative path to file
     * @return boolean
     */
    public static function exist($path)
    {
        //$path = $path;
        return is_file($path);
    }

    /**
     * get extension of file
     * @param string $path
     * @return NULL|string
     */
    public static function getExt($path)
    {
        if (!self::exist($path)) return null;

        $infos = pathinfo($path);

        if (isset($infos['extension'])) return $infos['extension'];

        return null;
    }

    /**
     * image extensions list
     * @var array
     */
    static public $exts = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'svg', 'webp'];
}
