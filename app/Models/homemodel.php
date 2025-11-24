<?php

namespace App\Models;

use Core\Database;
use PDOException;

class HomeModel
{
    public function saveCard(string $name, string $imagePath): array
    {
        $pdo = Database::getPdo();

        $sql = "INSERT INTO cards (name, image_path) VALUES (:name, :image_path)";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':image_path', $imagePath);
            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Image enregistrée en base.',
                'id' => (int)$pdo->lastInsertId(),
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Impossible d\'enregistrer l\'image en base.',
            ];
        }
    }

    public function showuserscore(): array
    {
        $pdo = Database::getPdo();

        $sql = "SELECT username, score FROM users ORDER BY score DESC LIMIT 23";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Récupère aléatoirement un nombre donné de cartes.
     * Utilisé pour construire les paires selon la difficulté choisie.
     */
    public function fetchRandomCards(int $limit): array
    {
        $pdo = Database::getPdo();

        $sql = "SELECT id, name, image_path FROM cards ORDER BY RAND() LIMIT :limit";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}


// class showcards
// {
//     public function fetchTopCards(int $limit = 10): array
//     {
//         $pdo = Database::getPdo();

//         $sql = "SELECT id, name, image_path FROM cards ORDER BY id DESC LIMIT :limit";

//         try {
//             $stmt = $pdo->prepare($sql);
//             $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
//             $stmt->execute();

//             return $stmt->fetchAll(\PDO::FETCH_ASSOC);
//         } catch (PDOException $e) {
//             return [];
//         }
//     }
// }

