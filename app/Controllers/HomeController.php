<?php

namespace App\Controllers;

require_once __DIR__ . '/../includes/helper.php';

use App\Models\HomeModel;
use Core\BaseController;

/**
 * Classe HomeController
 * ----------------------
 * Contrôleur responsable de la gestion de la page d'accueil.
 * Hérite de BaseController afin de bénéficier des méthodes utilitaires
 * comme render() pour afficher les vues.
 */
class HomeController extends BaseController
{
    /**
     * Action principale (point d'entrée de la page d'accueil)
     *
     * @return void
     */
    public function index(): void
    {
        $model = new HomeModel();
        $scores = $model->showuserscore();
        $selectedDifficulty = $_SESSION['selected_difficulty'] ?? null;
        $cardDeck = $_SESSION['card_grid'] ?? [];
        if (!empty($cardDeck)) {
            shuffle($cardDeck); // Mélange le deck à chaque refresh
        }
        $this->render('home/index', [
            'title' => '',
            'userscore' => $scores,
            'selectedDifficulty' => $selectedDifficulty,
            'cardDeck' => $cardDeck,
            'score' => $_SESSION['score'] ?? 0,
            'lastGame' => $_SESSION['last_game'] ?? null,
        ]);
    }


    public function upload(): void
    {
        $this->uploadimage();
        $this->render('home/upload', [
            'title' => ''
        ]);
    }

    public function uploadimage()

    {
        if (is_post()) {
            $uploadDir = __DIR__ . '/../../public/assets/images/';
            $uploader = new \App\Includes\ImageUploader($uploadDir);
            $result = $uploader->upload($_FILES['image'] ?? null);
            if (!$result['success']) {
                set_flash_message($result['message'], 'error');
                return;
            }

            $fileName = $result['file_name'] ?? ($_FILES['image']['name'] ?? '');
            $relativePath = 'assets/images/' . ($result['file_name'] ?? $fileName);
            $cleanName = clean_input(pathinfo($fileName, PATHINFO_FILENAME));
            if ($cleanName === '') {
                $cleanName = $fileName ?: 'Carte';
            }

            $model = new HomeModel();
            $dbResult = $model->saveCard($cleanName, $relativePath);

            $flashType = $dbResult['success'] ? 'success' : 'error';
            $flashMessage = $dbResult['success']
                ? 'Image uploadée et enregistrée en base.'
                : $dbResult['message'];

            set_flash_message($flashMessage, $flashType);
        }
    }

    public function setDifficulty(): void
    {
        if (!is_post()) {
            header('Location: /');
            exit;
        }

        $difficulty = strtolower(trim((string)post('difficulty', 'facile')));
        $config = [
            'facile' => ['pairs' => 3],
            'moyen' => ['pairs' => 6],
            'difficile' => ['pairs' => 12],
        ];

        if (!isset($config[$difficulty])) {
            set_flash_message('Difficulté inconnue.', 'error');
            header('Location: /');
            exit;
        }

        $pairs = $config[$difficulty]['pairs'];
        $model = new HomeModel();
        $cards = $model->fetchRandomCards($pairs);

        if (count($cards) < $pairs) {
            set_flash_message('Pas assez de cartes disponible', 'error');
            header('Location: /');
            exit;
        }

        $deck = [];
        foreach ($cards as $card) {
            $normalizedPath = '/' . ltrim((string)$card['image_path'], '/');
            $cardData = [
                'id' => (int)$card['id'],
                'name' => $card['name'],
                'image_path' => $normalizedPath,
            ];

            $deck[] = $cardData;
            $deck[] = $cardData; // Duplique pour former la paire
        }

        shuffle($deck);

        $_SESSION['selected_difficulty'] = $difficulty;
        $_SESSION['card_grid'] = $deck;
        $_SESSION['score'] = 0;
        unset($_SESSION['last_game']);

        set_flash_message('Nouvelle grille générée en fonction de la difficulté.', 'success');

        header('Location: /');
        exit;
    }

    public function resetSession(): void
    {
        if (is_post() && post('reset_session') === '1') {
            unset($_SESSION['selected_difficulty'], $_SESSION['card_grid']);
            $_SESSION['score'] = 0;
            unset($_SESSION['last_game']);
            set_flash_message('Session réinitialisée. Veuillez sélectionner une difficulté.', 'success');
        }
        header('Location: /');
        exit;
    }

    public function incrementScore(): void
    {
        header('Content-Type: application/json');

        if (!is_post()) {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée']);
            return;
        }

        // Définir les multiplicateurs selon la difficulté
        $difficultyMeta = [
            'facile' => 10,
            'moyen' => 15,
            'difficile' => 20,
        ];

        $difficulty = $_SESSION['selected_difficulty'] ?? 'facile';
        $baseIncrement = $difficultyMeta[$difficulty] ?? 1;

        $timerDuration = max(1, (int)post('timer_duration', 60));
        $timeRemaining = (int)post('time_remaining', 0);
        $timeRemaining = max(0, min($timerDuration, $timeRemaining));

        $baseTimeMultiplier = 1000;
        $speedBonus = 1 + ($timeRemaining / $timerDuration);
        $timeMultiplier = $baseTimeMultiplier * $speedBonus;

        $increment = (int)round($baseIncrement * $timeMultiplier);
        $_SESSION['score'] = ($_SESSION['score'] ?? 0) + $increment;

        echo json_encode([
            'score' => $_SESSION['score'],
            'base_increment' => $baseIncrement,
            'time_remaining' => $timeRemaining,
            'time_multiplier' => $timeMultiplier,
            'difficulty' => $difficulty,
        ]);
    }

    public function completeGame(): void
    {
        header('Content-Type: application/json');

        if (!is_post()) {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée']);
            return;
        }

        $difficulty = $_SESSION['selected_difficulty'] ?? null;
        $cardGrid = $_SESSION['card_grid'] ?? [];

        if ($difficulty === null || empty($cardGrid)) {
            http_response_code(400);
            echo json_encode(['error' => 'Aucune partie en cours']);
            return;
        }

        $timerDuration = max(1, (int)post('timer_duration', 60));
        $timeRemaining = (int)post('time_remaining', 0);
        $timeRemaining = max(0, min($timerDuration, $timeRemaining));
        $timeSpent = $timerDuration - $timeRemaining;
        $pairs = max(1, (int)floor(max(0, count($cardGrid)) / 2));

        $_SESSION['last_game'] = [
            'score' => (int)($_SESSION['score'] ?? 0),
            'difficulty' => $difficulty,
            'time_spent' => $timeSpent,
            'time_remaining' => $timeRemaining,
            'timer_duration' => $timerDuration,
            'pairs' => $pairs,
            'completed_at' => date('d-m-Y H:i:s'),
        ];

        unset($_SESSION['selected_difficulty'], $_SESSION['card_grid']);

        echo json_encode(['success' => true]);
    }

    public function submitScore(): void
    {
        if (!is_post()) {
            header('Location: /');
            exit;
        }

        $lastGame = $_SESSION['last_game'] ?? null;
        if (!$lastGame) {
            set_flash_message('Aucun score à enregistrer pour le moment.', 'error');
            header('Location: /');
            exit;
        }

        $username = clean_input((string)post('username', ''));
        if ($username === '') {
            set_flash_message('Veuillez choisir un pseudo.', 'error');
            header('Location: /');
            exit;
        }

        if (strlen($username) > 12) {
            set_flash_message('Le pseudo doit contenir 12 caractères maximum.', 'error');
            header('Location: /');
            exit;
        }

        $model = new HomeModel();
        $result = $model->saveUserScore($username, (int)($lastGame['score'] ?? 0));

        if ($result['success']) {
            set_flash_message('Score enregistré dans le leaderboard.', 'success');
            unset($_SESSION['last_game']);
        } else {
            set_flash_message($result['message'], 'error');
        }

        header('Location: /');
        exit;
    }
}
