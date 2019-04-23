<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Upload with PHP</title>
</head>
<body>
    <form action="fileUpload.php" method="post" enctype="multipart/form-data">
        <h4>Enter PDF file full path:</h4>
        <input type="text" name="filePath" id="filePath" value="C:/..." size="87">
        <h4>Input key: <a href="https://pdftables.com/pdf-to-excel-api" target="_blank">Get key</a></h4>
    
        <input type="text" name="key" id="key" size="87">
        <hr>
        <input type="submit" name="submit" value="Parse this PDF" style="width:600px;height:90px;">
    </form>
</body>
</html>