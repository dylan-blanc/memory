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

        $this->render('home/index', [
            'title' => '',
            'userscore' => $scores,
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
}
