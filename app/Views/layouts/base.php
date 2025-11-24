<?php

/**
 * Layout principal
 * -----------------
 * Ce fichier définit la structure HTML commune à toutes les pages.
 * Il inclut dynamiquement le contenu spécifique à chaque vue via la variable $content.
 */

require_once __DIR__ . '/../../includes/helper.php';
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">

  <!-- Titre de la page (sécurisé avec htmlspecialchars, valeur par défaut si non défini) -->
  <title><?= isset($title) ? htmlspecialchars($title, ENT_QUOTES, 'UTF-8') : 'Mini MVC' ?></title>

  <!-- Bonne pratique : rendre le site responsive -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/assets/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="icon" type="image/.png" href="/assets/images/favicon.png">
</head>

<body>
  <!-- Menu de navigation global -->
  <nav>
    <p> <a href="/">Accueil</a>
      <a href="/upload">Upload</a>
    </p>
  </nav>


  <!-- Contenu principal injecté depuis BaseController -->
  <main>
    <?= $content ?>
    <?php $successFlash = get_flash('success'); ?>
    <?php $errorFlash = get_flash('error'); ?>
    <?php if ($successFlash): ?>
      <div class="flash flash-success"><?= e($successFlash) ?></div>
    <?php endif; ?>
    <?php if ($errorFlash): ?>
      <div class="flash flash-error"><?= e($errorFlash) ?></div>
    <?php endif; ?>
  </main>

</body>

</html>