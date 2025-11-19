<?php


?>

<h1>
    <!-- On sécurise le titre avec htmlspecialchars et on définit une valeur par défaut -->
    <?= htmlspecialchars($title ?? 'A propos', ENT_QUOTES, 'UTF-8') ?>
</h1>

<p>Bienvenue.</p>

<p>
    <a href="/">← Retour à l'accueil</a>
    <?= "<br>" ?>
    <a href="/articles">Voir la liste des articles →</a>
</p>