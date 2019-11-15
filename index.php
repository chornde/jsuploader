<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Titel der Seite | Name der Website</title>
    <script src="uploader.js"></script>
    <style>
        #uploadArea { display:none; min-width:120px; min-height:120px; padding:20px; border:5px dashed #ccc; border-radius:10px; background: url("uploadarea.png") center center no-repeat; }
        #uploadArea.hover { border:5px dashed #999; }
        .uploadSlot { border:1px solid black; }
        .uploadSlot a { display:inline; }
        .uploadSlot img { display:none; transform: scale(0.5); max-height: 50px; }
        .uploadSlot.reserved { border:1px solid blue; }
        .uploadSlot.reserved a { display:none; }
        .uploadSlot.reserved img { display:inline; }
        .uploadSlot.reserved p { width: 240px; border:1px solid red; background: #eee url("progress.png") 100% 0 repeat-y; transition: background-position 3s ease; }

        #progress p
        {
            display: block;
            width: 240px;
            padding: 2px 5px;
            margin: 2px 0;
            border: 1px inset #446;
            border-radius: 5px;
            background: #eee url("progress.png") 100% 0 repeat-y;
        }

        #progress p.success
        {
            background: #0c0 none 0 0 no-repeat;
        }

        #progress p.failed
        {
            background: #c00 none 0 0 no-repeat;
        }
    </style>
</head>
<body>
<pre><?php var_dump($_GET, $_POST, $_FILES); ?></pre>
<form id="upload" action="index.php" method="post" enctype="multipart/form-data">
    <p>Bilder auswählen</p>
    <input id="uploadElement" type="file" name="files[]" multiple="multiple" />
    <input id="uploadSubmit" type="submit">
    <p>- ODER -</p>
    <p id="uploadArea">Bilder mit der Maus hier reinziehen</p>
    <input type="hidden" name="foo" value="bar">
</form>

<div id="progress"></div>

<div class="uploadSlot"><a>noch ein Bild hochladen</a><p>&nbsp;</p><img src="22.gif" alt="loading" /></div>
<div class="uploadSlot"><a>noch ein Bild hochladen</a><p>&nbsp;</p><img src="22.gif" alt="loading" /></div>
<div class="uploadSlot"><a>noch ein Bild hochladen</a><p>&nbsp;</p><img src="22.gif" alt="loading" /></div>
<div class="uploadSlot"><a>noch ein Bild hochladen</a><p>&nbsp;</p><img src="22.gif" alt="loading" /></div>
<div class="uploadSlot"><a>noch ein Bild hochladen</a><p>&nbsp;</p><img src="22.gif" alt="loading" /></div>
<div class="uploadSlot"><a>noch ein Bild hochladen</a><p>&nbsp;</p><img src="22.gif" alt="loading" /></div>
<div class="uploadSlot"><a>noch ein Bild hochladen</a><p>&nbsp;</p><img src="22.gif" alt="loading" /></div>
<div class="uploadSlot"><a>noch ein Bild hochladen</a><p>&nbsp;</p><img src="22.gif" alt="loading" /></div>

</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        uploader.init();
    });
</script>
</html>