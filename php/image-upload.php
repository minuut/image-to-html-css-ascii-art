<!DOCTYPE html>
<html>
<head>
    <title>Yonnie's Image to HTML/CSS ASCII art converter</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body style="background-color: black;">
<?php

require_once 'ImageToAscii.php';

try {
    if (isset($_FILES['image']) && isset($_POST['conversion_type'])) {
        $validTypes = ["image/jpeg", "image/png"];
        if (!in_array($_FILES['image']['type'], $validTypes)) {
            throw new Exception("Error: Please upload a valid image file.");
        }
        if ($_FILES['image']['size'] > 500000) {
            throw new Exception("Error: File size is too large. Please upload a file under 500KB.");
        }

        $imageToAscii = new ImageToAscii($_FILES['image']['tmp_name']);

        switch ($_POST['conversion_type']) {
            case "4px_single":
                $imageToAscii->convertToAscii("@", 4, 50, "4px");
                break;
            case "8px_single":
                $imageToAscii->convertToAscii("@", 8, 100, "8px");
                break;
            case "4px_multiple":
                $imageToAscii->convertToAscii("@%#*+=-:. ", 4, 50, "4px");
                break;
            case "8px_multiple":
                $imageToAscii->convertToAscii("@%#*+=-:. ", 8, 100, "8px");
                break;
        }
    }
} catch (Exception $e) {
    echo '<div class="error">' . $e->getMessage() . '</div>';
}

?>
<button onclick="location.href='../index.html'">Return</button>
</body>

</html>
