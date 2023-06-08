<?php

class ImageToAscii
{
  private $img;
  private $width;
  private $height;

  public function __construct(string $imagePath)
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
        $this->width = imagesx($this->img);
        $this->height = imagesy($this->img);
    }


  protected function convertImage(int $newWidth, string $characters)
  {
    $aspectRatio = $this->height / $this->width;
    $newHeight = $newWidth * $aspectRatio;
    $img = imagecreatetruecolor($newWidth, $newHeight);

    if (!$img) {
      throw new \Exception('Error creating image');
    }

    imagecopyresampled($img, $this->img, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);

    imagefilter($img, IMG_FILTER_CONTRAST, 10);
    $width = imagesx($img);
    $height = imagesy($img);
    for ($h = 0; $h < $height; $h++) {
      for ($w = 0; $w < $width; $w++) {
        // Get color at pixel location.
        $rgb = ImageColorAt($img, $w, $h);
        // Convert color into usable format.
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        // Convert RGB to Hex
        $hex = "#" . str_pad(dechex($r), 2, "0", STR_PAD_LEFT) .  str_pad(dechex($g), 2, "0", STR_PAD_LEFT) . str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
        // Check for white/off-white color.
        if (($r > 240 && $g > 240 && $b > 240)) {
          echo '&nbsp;';
        } else {
          echo '<span style="color:' . $hex . ';">' . $characters[rand(0, strlen($characters) - 1)] . '</span>';
        }
      }
      echo "\n";
    }
  }

  protected function displayImage(int $fontSize, string $className)
  {
    echo '<div class="img-wrapper' . $className . '" style="display: flex; justify-content: center; align-items: center; background-color: #000000;">';
    echo '<pre style="font-size: ' . $fontSize . 'px;font-weight: bold;padding: 0px ' . $fontSize . 'px;';
    
    if ($className === "8px") {
      echo 'line-height: 7px; letter-spacing: 3px;';
    } else {
      echo 'line-height: 5px; letter-spacing: 2.75px;';
    } 
    echo '">';
  }

  protected function closeImage()
  {
    echo '</pre>';
    echo '</div>';
  }

  // You can change the characters in '$characters = "y"' into whatever you'd like
  // I used my first letter and name in these examples

  public function convert4pxSingleCharacter(string $characters = "y", int $fontSize = 4, string $className = "")
  {
    $this->displayImage($fontSize, $className);
    $this->convertImage(150, $characters);
    $this->closeImage();
  }

  public function convert8pxSingleCharacter(string $characters = "y", int $fontSize = 8, string $className = "8px")
  {
    $this->displayImage($fontSize, $className);
    $this->convertImage(100, $characters);
    $this->closeImage();
  }

  public function convert4pxWithMultipleCharacters(string $characters = "yonnie", int $fontSize = 4, string $className = "")
  {
    $this->displayImage($fontSize, $className);
    $this->convertImage(150, $characters);
    $this->closeImage();
  }

  public function convert8pxWithMultipleCharacters(string $characters = "yonnie", int $fontSize = 8, string $className = "8px")
  {
    $this->displayImage($fontSize, $className);
    $this->convertImage(100, $characters);
    $this->closeImage();
  }
}
