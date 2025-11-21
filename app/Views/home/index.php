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

<?php if (!empty($userscore) && is_array($userscore)): ?>
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