<?php

namespace App\Models;

use Core\Database;
use PDOException;

class HomeModel
{
    /**
     * Persists a newly uploaded image as a "card" row.
     *
     * @param string $name      Human friendly label (usually original filename)
     * @param string $imagePath Relative path stored in cards.image_path
     *
     * @return array{success:bool,message:string,id?:int}
     */
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
            // Masquer les détails SQL tout en exposant un message utile
            return [
                'success' => false,
                'message' => 'Impossible d\'enregistrer l\'image en base.',
            ];
        }
    }
}
