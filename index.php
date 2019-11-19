<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Titel der Seite | Name der Website</title>
    <link rel="stylesheet" href="style.css" />
    <script src="uploader.js"></script>
</head>
<body>
<pre><?php var_dump($_GET, $_POST, $_FILES); ?></pre>
<form id="uploadForm" action="index.php" method="post" enctype="multipart/form-data">
    <input id="uploadElement" type="file" name="files[]" multiple="multiple" />
    <input id="uploadSubmit" type="submit">
    <p id="uploadArea">Bilder mit der Maus hier reinziehen oder hier klicken</p>
</form>

<div class="uploadGrid">
    <div class="uploadSlot"><p></p><img src="" /></div>
    <div class="uploadSlot"><p></p><img src="" /></div>
    <div class="uploadSlot"><p></p><img src="" /></div>
    <div class="uploadSlot"><p></p><img src="" /></div>
    <div class="uploadSlot"><p></p><img src="" /></div>
    <div class="uploadSlot"><p></p><img src="" /></div>
    <div class="uploadSlot"><p></p><img src="" /></div>
    <div class="uploadSlot"><p></p><img src="" /></div>
</div>

</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        uploader.init();
    });
</script>
</html>