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

        $this->render('home/index', [
            'title' => '',
            'userscore' => $scores,
            'selectedDifficulty' => $selectedDifficulty,
            'cardDeck' => $cardDeck,
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
            set_flash_message('Pas assez de cartes disponibles pour cette difficulté.', 'error');
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

        set_flash_message('Nouvelle grille générée en fonction de la difficulté.', 'success');

        header('Location: /');
        exit;
    }

    public function resetSession(): void
    {
        if (is_post() && post('reset_session') === '1') {
            unset($_SESSION['selected_difficulty'], $_SESSION['card_grid']);
            set_flash_message('Session réinitialisée. Veuillez sélectionner une difficulté.', 'success');
        }
        header('Location: /');
        exit;
    }

}
