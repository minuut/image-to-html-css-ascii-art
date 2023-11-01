<!DOCTYPE html>
<html>

<head>
    <title>Yonnie's Image to HTML/CSS ASCII art converter</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body style="background-color: black;">
    <?php

    require_once 'ImageToAscii.php';

    $output = "";
    if (isset($_FILES['image']) && isset($_POST['conversion_type'])) {
        $validTypes = ["image/jpeg", "image/png"];
        if (!in_array($_FILES['image']['type'], $validTypes)) {
            throw new Exception("Error: Please upload a valid image file.");
        }
        $imageToAscii = new ImageToAscii($_FILES['image']['tmp_name']);

        switch ($_POST['conversion_type']) {
            case "4px_single":
                $output = $imageToAscii->convert4pxSingleCharacter();
                break;
            case "8px_single":
                $output = $imageToAscii->convert8pxSingleCharacter();
                break;
            case "4px_multiple":
                $output = $imageToAscii->convert4pxWithMultipleCharacters();
                break;
            case "8px_multiple":
                $output = $imageToAscii->convert8pxWithMultipleCharacters();
                break;
        }
    }

    ?>
    <button onclick="location.href='../index.html'">Return</button>
</body>

</html>
