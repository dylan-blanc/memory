<?php

/**
 * Vue : Page d'accueil
 * ---------------------
 * Cette vue reçoit une variable $title optionnelle
 * transmise par le HomeController.
 */
?>
<h1>
  <!-- On sécurise le titre avec htmlspecialchars et on définit une valeur par défaut -->
  <?= htmlspecialchars($title ?? 'Accueil', ENT_QUOTES, 'UTF-8') ?>
</h1>

<?php if (!empty($selectedDifficulty)):

elseif (!empty($userscore) && is_array($userscore)): ?>
  <section class="leaderboard">
    <h2 class="leaderboard-title">Leaderboard</h2>
    <?php
    $firstThree = array_slice($userscore, 0, 3);
    $lastTwenty = array_slice($userscore, -20);

    if (count($firstThree) > 0): ?>
      <div class="leaderboard-top3">
        <table border="0" cellpadding="0" cellspacing="0" class="score-table-Top3">
          <thead>
            <tr>
              <?php foreach ($firstThree as $user): ?>
                <th><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <tr>
              <?php foreach ($firstThree as $user): ?>
                <td><?= (int)$user['score'] ?></td>
              <?php endforeach; ?>
            </tr>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <?php
    $chunks = array_chunk($lastTwenty, 5);
    if (!empty($chunks)): ?>
      <div class="leaderboard-grid">
        <?php foreach ($chunks as $group): ?>
          <table border="0" cellpadding="0" cellspacing="0" class="score-table-Top20">
            <thead>
              <tr>
                <?php foreach ($group as $user): ?>
                  <th><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <tr>
                <?php foreach ($group as $user): ?>
                  <td><?= (int)$user['score'] ?></td>
                <?php endforeach; ?>
              </tr>
            </tbody>
          </table>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
<?php endif; ?>



<!-- Menu déroulant de sélection de difficulté -->
<?php if (empty($selectedDifficulty)): ?>
  <form method="post" action="/set-difficulty" class="difficulty-form">
    <label for="difficulty" class="difficulty-label">Sélectionnez la difficulté</label>
    <select name="difficulty" id="difficulty" class="difficulty-select">
      <option value="facile"> Facile </option>
      <option value="moyen"> Moyen </option>
      <option value="difficile"> Difficile </option>
    </select>
    <div class="difficulty-form-footer">
      <button type="submit" class="difficulty-button">Valider</button>
    </div>
  </form>
<?php endif; ?>

<?php
$difficultyMeta = [
  'facile' => ['label' => 'Facile', 'pairs' => 3, 'cards' => 6],
  'moyen' => ['label' => 'Moyen', 'pairs' => 6, 'cards' => 12],
  'difficile' => ['label' => 'Difficile', 'pairs' => 12, 'cards' => 24],
];
$currentDifficulty = $selectedDifficulty ?? null;
$cardDeck = $cardDeck ?? [];
$backImage = '/assets/images/DosDeCarte1.png';
?>

<section class="memory-section">
  <?php if (!empty($cardDeck)): ?>
    <?php $meta = ($currentDifficulty && isset($difficultyMeta[$currentDifficulty])) ? $difficultyMeta[$currentDifficulty] : null; ?>
    <h2 class="memory-title" style="margin-top: -100px;">
      Grille <?= $meta ? e($meta['label']) : 'Personnalisée' ?>
      <?php if ($meta): ?>
        <small>(<?= (int)$meta['pairs'] ?> paires / <?= (int)$meta['cards'] ?> cartes)</small>
      <?php endif; ?>
    </h2>
    <div class="memory-board <?= $currentDifficulty ? 'memory-board--' . e($currentDifficulty) : '' ?>">
      <?php foreach ($cardDeck as $index => $card): ?>
        <?php $cardId = (int)$card['id']; ?>
        <label class="memory-card"
          data-card-id="<?= $cardId ?>"
          data-position="<?= $index ?>">
          <input type="checkbox"
            class="memory-card-toggle"
            aria-label="Retourner la carte <?= e($card['name']) ?>">
          <span class="memory-card-inner">
            <span class="memory-card-face memory-card-face--back">
              <img src="<?= $backImage ?>" alt="Dos de la carte" width="150" height="250">
            </span>
            <span class="memory-card-face memory-card-face--front">
              <img src="<?= e($card['image_path']) ?>" alt="Face de la carte <?= e($card['name']) ?>" width="150" height="250">
            </span>
          </span>
          <span class="sr-only">Carte <?= e($card['name']) ?></span>
        </label>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
  <?php endif; ?>

  <?php if (!empty($selectedDifficulty)): ?>
  <form method="post" action="/" class="reset-form" style="display: flex; justify-content: center; margin-top: 20px; ">
    <input type="hidden" name="reset_session" value="1">
    <button type="submit" class="reset-button">Reset Session</button>
  </form>
  <?php endif; ?>


</section>

<script src="/assets/js/memory.js" defer></script>