<?php

/**
 * Class ImageToAscii
 * Converts images to HTML/CSS ASCII art.
 */
class ImageToAscii
{
    private $img;
    private $width;
    private $height;

    /**
     * ImageToAscii constructor.
     * @param string $imagePath
     * @throws Exception
     */
    public function __construct(string $imagePath)
    {
        $this->setImage($imagePath);
        $this->width = imagesx($this->img);
        $this->height = imagesy($this->img);
    }

    /**
     * Sets the image resource.
     * @param string $imagePath
     * @throws Exception
     */
    private function setImage(string $imagePath): void
    {
        $type = exif_imagetype($imagePath);
        switch ($type) {
            case IMAGETYPE_PNG:
                $this->img = imagecreatefrompng($imagePath);
                break;
            case IMAGETYPE_JPEG:
                $this->img = imagecreatefromjpeg($imagePath);
                break;
            default:
                throw new \Exception('Invalid image type');
        }
    }

    /**
     * Resamples the image.
     * @param int $newWidth
     * @return resource
     * @throws Exception
     */
    private function getResampledImage(int $newWidth)
    {
        $aspectRatio = $this->height / $this->width;
        $newHeight = $newWidth * $aspectRatio;
        $img = imagecreatetruecolor($newWidth, $newHeight);
        if (!$img) {
            throw new \Exception('Error creating image');
        }
        imagecopyresampled($img, $this->img, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);
        imagefilter($img, IMG_FILTER_CONTRAST, 10);
        return $img;
    }

    /**
     * Displays the image container.
     * @param int $fontSize
     * @param string $className
     */
    private function displayImage(int $fontSize, string $className = ""): void
    {
        $style = "font-size: {$fontSize}px; font-weight: bold; padding: 0;";
        $style .= $className === "8px" ? 'line-height: 7px; letter-spacing: 3px;' : 'line-height: 5px; letter-spacing: 2.75px;';
        echo '<div class="img-wrapper' . $className . '" style="display: flex; justify-content: center; align-items: center; background-color: #000000;">';
        echo '<pre style="' . $style . '">';
    }

    /**
     * Closes the image container.
     */
    private function closeImage(): void
    {
        echo '</pre>';
        echo '</div>';
    }

    /**
     * Converts the image to ASCII art.
     * @param int $newWidth
     * @param string $characters
     */
    private function convertImage(int $newWidth, string $characters): void
    {
        $img = $this->getResampledImage($newWidth);
        $width = imagesx($img);
        $height = imagesy($img);

        for ($h = 0; $h < $height; $h++) {
            for ($w = 0; $w < $width; $w++) {
                $rgb = ImageColorAt($img, $w, $h);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                if ($r > 240 && $g > 240 && $b > 240) {
                    echo '&nbsp;';
                } else {
                    $hex = sprintf("#%02X%02X%02X", $r, $g, $b);
                    echo '<span style="color:' . $hex . ';">' . $characters[rand(0, strlen($characters) - 1)] . '</span>';
                }
            }
            echo "\n";
        }
    }

    /**
     * Converts the image to ASCII art based on parameters.
     * @param string $characters
     * @param int $fontSize
     * @param int $newWidth
     * @param string $className
     */
    public function convertToAscii(string $characters, int $fontSize, int $newWidth, string $className = ""): void
    {
        $this->displayImage($fontSize, $className);
        $this->convertImage($newWidth, $characters);
        $this->closeImage();
    }
}
