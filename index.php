<?php session_start(); ?>
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

<pre><?php var_dump($_SESSION); ?></pre>

<form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
    <input id="uploadElement" type="file" name="files[]" multiple="multiple" />
    <input id="uploadSubmit" type="submit">
    <p id="uploadArea">Bilder mit der Maus hier reinziehen oder hier klicken</p>
</form>

<div class="uploadGrid">
    <div class="uploadSlot">
        <p class="progress"></p>
        <img src="" />
        <p class="icons"><a href="" title="Startbild">➊</a> <a href="" title="rotieren">➥</a> <a href="" title="löschen">✖</a></p>
    </div>
    <div class="uploadSlot">
        <p class="progress"></p>
        <img src="" />
        <p class="icons"><a href="" title="Startbild">➊</a> <a href="" title="rotieren">➥</a> <a href="" title="löschen">✖</a></p>
    </div>
    <div class="uploadSlot">
        <p class="progress"></p>
        <img src="" />
        <p class="icons"><a href="" title="Startbild">➊</a> <a href="" title="rotieren">➥</a> <a href="" title="löschen">✖</a></p>
    </div>
    <div class="uploadSlot">
        <p class="progress"></p>
        <img src="" />
        <p class="icons"><a href="" title="Startbild">➊</a> <a href="" title="rotieren">➥</a> <a href="" title="löschen">✖</a></p>
    </div>
    <div class="uploadSlot">
        <p class="progress"></p>
        <img src="" />
        <p class="icons"><a href="" title="Startbild">➊</a> <a href="" title="rotieren">➥</a> <a href="" title="löschen">✖</a></p>
    </div>
    <div class="uploadSlot">
        <p class="progress ready"></p>
        <img src="" />
        <p class="icons"><a href="" title="Startbild">➊</a> <a href="" title="rotieren">➥</a> <a href="" title="löschen">✖</a></p>
    </div>
</div>

</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        uploader.init();
    });
</script>
</html>