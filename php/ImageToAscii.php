<?php

/**
 * Class ImageToAscii
 *
 * A class for converting images into an ASCII representation.
 * Made by github.com/minuut 
 */
class ImageToAscii
{
  private $image;
  private $width;
  private $height;

  /**
   * ImageToAscii constructor.
   *
   * @param string $imagePath Path to the image file.
   */
  public function __construct(string $imagePath)
  {
    $this->loadImage($imagePath);
    $this->width = imagesx($this->image);
    $this->height = imagesy($this->image);
  }

  /**
   * Loads the image from the given path.
   *
   * @param string $imagePath Path to the image file.
   * @throws \Exception
   */
  private function loadImage(string $imagePath): void
  {
    $type = exif_imagetype($imagePath);
    switch ($type) {
      case IMAGETYPE_PNG:
        $this->image = imagecreatefrompng($imagePath);
        break;
      case IMAGETYPE_JPEG:
        $this->image = imagecreatefromjpeg($imagePath);
        break;
      default:
        throw new \Exception('Invalid image type');
    }
  }

  /**
   * Resizes the loaded image to a specified width while maintaining aspect ratio.
   *
   * @param int $newWidth New width for the resized image.
   * @return \GdImage The resized image resource.
   * @throws \Exception
   */
  private function resizeImage(int $newWidth): \GdImage
  {
    $aspectRatio = $this->height / $this->width;
    $newHeight = $newWidth * $aspectRatio;
    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

    if (!$resizedImage) {
      throw new \Exception('Error creating image');
    }

    imagecopyresampled($resizedImage, $this->image, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);
    imagefilter($resizedImage, IMG_FILTER_CONTRAST, 10);

    return $resizedImage;
  }

  /**
   * Converts the resized image to its ASCII representation.
   *
   * @param \GdImage $resizedImage The resized image resource.
   * @param string $characters Set of characters to use for the ASCII conversion.
   */
  private function convertToAscii(\GdImage $resizedImage, string $characters): void
  {
    $width = imagesx($resizedImage);
    $height = imagesy($resizedImage);

    for ($h = 0; $h < $height; $h++) {
      for ($w = 0; $w < $width; $w++) {
        $this->printPixelAsAscii($resizedImage, $w, $h, $characters);
      }
      echo "\n";
    }
  }

  /**
   * Prints a pixel from the resized image as an ASCII character.
   *
   * @param \GdImage $resizedImage The resized image resource.
   * @param int $w X-coordinate of the pixel.
   * @param int $h Y-coordinate of the pixel.
   * @param string $characters Set of characters to use for the ASCII conversion.
   */
  private function printPixelAsAscii(\GdImage $resizedImage, int $w, int $h, string $characters): void
  {
    $rgb = ImageColorAt($resizedImage, $w, $h);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;

    if ($r > 240 && $g > 240 && $b > 240) {
      echo '&nbsp;';
    } else {
      $hex = sprintf("#%02x%02x%02x", $r, $g, $b);
      echo '<span style="color:' . $hex . ';">' . $characters[rand(0, strlen($characters) - 1)] . '</span>';
    }
  }

  /**
   * Displays the header for the ASCII representation.
   *
   * @param int $fontSize Font size for the ASCII representation.
   * @param string $className CSS class name for the display.
   */
  private function displayImageHeader(int $fontSize, string $className): void
  {
    $styles = 'line-height: 5px; letter-spacing: 2.75px;';
    if ($className === "8px") {
      $styles = 'line-height: 7px; letter-spacing: 3px;';
    }
    echo "<div class=\"img-wrapper{$className}\" style=\"display: flex; justify-content: center; align-items: center; background-color: #000000;\">";
    echo "<pre style=\"font-size: {$fontSize}px;font-weight: bold;padding: 0px 0px;{$styles}\">";
  }

  /**
   * Closes the ASCII representation display.
   */
  private function displayImageFooter(): void
  {
    echo '</pre></div>';
  }

  /**
   * Converts the loaded image to ASCII using specified parameters.
   *
   * @param string $characters Set of characters to use for the ASCII conversion.
   * @param int $fontSize Font size for the ASCII representation.
   * @param string $className CSS class name for the display.
   * @param int $newWidth New width for the resized image.
   */
  private function convertImageToAscii(string $characters, int $fontSize, string $className, int $newWidth): void
  {
    $this->displayImageHeader($fontSize, $className);
    $resizedImage = $this->resizeImage($newWidth);
    $this->convertToAscii($resizedImage, $characters);
    $this->displayImageFooter();
  }


 // Usage:
  
  public function convert4pxSingleCharacter(string $characters = "y", int $fontSize = 4, string $className = ""): void
  {
    $this->convertImageToAscii($characters, $fontSize, $className, 150);
  }

  public function convert8pxSingleCharacter(string $characters = "y", int $fontSize = 8, string $className = "8px"): void
  {
    $this->convertImageToAscii($characters, $fontSize, $className, 100);
  }

  public function convert4pxWithMultipleCharacters(string $characters = "musti", int $fontSize = 4, string $className = ""): void
  {
    $this->convertImageToAscii($characters, $fontSize, $className, 150);
  }

  public function convert8pxWithMultipleCharacters(string $characters = "musti", int $fontSize = 8, string $className = "8px"): void
  {
    $this->convertImageToAscii($characters, $fontSize, $className, 100);
  }
}

