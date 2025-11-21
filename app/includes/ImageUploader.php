<?php

namespace App\Includes;

class ImageUploader
{
    private $uploadDir;
    private $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function upload($file)
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => "Aucun fichier valide envoyé."];
        }

        $filename = basename($file['name']);
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $this->allowedExtensions)) {
            return ['success' => false, 'message' => "Type de fichier non autorisé. Seules les images sont acceptées."];
        }

        $newFileName = uniqid('img_', false) . '.' . $fileExtension;
        $destPath = $this->uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return [
                'success' => true,
                'message' => "Image uploadée avec succès.",
                'file_name' => $newFileName,
                'absolute_path' => $destPath,
            ];
        }

        return ['success' => false, 'message' => "Erreur lors du déplacement du fichier téléchargé."];
    }
}
