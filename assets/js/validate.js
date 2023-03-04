function validateForm() {
    let file = document.getElementsByName("image")[0].files[0];
    let fileType = file["type"];
    let validTypes = ["image/jpeg", "image/png"];
    if (validTypes.indexOf(fileType) < 0) {
        alert("Error: Please upload a valid image file.");
        return false;
    }
    if (file.size > 500000) {
        alert("Error: File size is too large. Please upload a file under 500KB.");
        return false;
    }
    return true;
}
