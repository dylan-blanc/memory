<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use Core\BaseController;

class ArticleController extends BaseController
{
    public function index(): void
    {
        // Récupération de tous les articles via le modèle ArticleModel
        $articles = new ArticleModel();

        $data = [
            'title' => 'Liste des articles',
            'articles' => $articles->all()
        ];
        $this->render('article/index', $data);
    }
}