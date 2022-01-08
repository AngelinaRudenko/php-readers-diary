<?php

class Image
{
    /**
     * Resizes image and saves to cache folder.
     * @param $relativePath - relative path to original image
     * @return bool|null - is saving successful
     */
    public static function resizeAndCache($relativePath)
    {
        $absolutePath = ROOT."/uploads/".$relativePath;
        $imageInfo = getimagesize($absolutePath);

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        switch (strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                $imageBookPage = imagecreatefromjpeg($absolutePath);
                $imageBooksPage = imagecreatefromjpeg($absolutePath);
                break;
            case 'png':
                $imageBookPage = imagecreatefrompng($absolutePath);
                $imageBooksPage = imagecreatefrompng($absolutePath);
                break;
            default:
                return null;
        }

        $relation = $height / $width;

        $imageBooksPage = self::resizeImageForBookListPage($relation, $imageBooksPage);
        $imageBookPage = self::resizeImageForBookPage($relation, $imageBookPage);

        $originalFileName = basename($absolutePath);

        $result = imagejpeg($imageBooksPage, 'uploads/cachedBookCoverPictures/bookListPage_'.$originalFileName, 75);
        return $result && imagejpeg($imageBookPage, 'uploads/cachedBookCoverPictures/bookPage_'.$originalFileName, 100);
    }

    /**
     * Resizes image for book list page (home page).
     * @param $relation - sides relation
     * @param $image - image
     * @return false|GdImage|resource - image
     */
    private static function resizeImageForBookListPage($relation, $image) {
        if ($relation <= (300/200)) { // vertical with normal width
            // 300px height, original width and height relation
            $image = imagescale($image, -1, 300);
        } else { // square or horizontal or vertical with small width
            // 200px width and height, original width and height relation
            $image = imagescale($image, 200);
        }

        $centerX = round(imagesx($image) / 2);
        $centerY = round(imagesy($image) / 2);

        $xStart = $centerX - 100;
        $yStart = $centerY - 150;

        return imagecrop($image, ['x' => $xStart, 'y' => $yStart, 'width' => 200, 'height' => 300]);
    }

    /**
     * Resizes image for book page.
     * @param $relation - sides relation
     * @param $image - image
     * @return false|GdImage|resource - image
     */
    private static function resizeImageForBookPage($relation, $image) {
        if ($relation == 1) { // square
            // 300px width and height, original width and height relation
            $image = imagescale($image, 300);
        } else if ($relation > 1) { // vertical
            // 500px height, original width and height relation
            $image = imagescale($image, -1, 500);

            // if after resizing width is > 300
            if (imagesx($image) > 300) {
                // 300px width, original width and height relation
                $image = imagescale($image, 300);
            }
        } else { // horizontal
            // 300px width and height, original width and height relation
            $image = imagescale($image, 300);

            // if after resizing height is > 500
            if (imagesY($image) > 500) {
                // 500px height, original width and height relation
                $image = imagescale($image, -1, 500);
            }
        }
        return $image;
    }
}