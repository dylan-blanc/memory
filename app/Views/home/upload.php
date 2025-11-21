<?php

?>
<h1><?= htmlspecialchars($title ?? 'Upload', ENT_QUOTES, 'UTF-8') ?></h1>

<h2>Uploader une image</h2>

<form style="border: 1px solid white; width: 308px;" action="" method="post" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*" required>
    <button type="submit">Uploader</button>
</form>