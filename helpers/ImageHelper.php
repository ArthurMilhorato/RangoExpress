<?php
class ImageHelper {
    private static $uploadDir = 'public/images/';
    private static $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    private static $maxSize = 5242880; // 5MB

    public static function uploadImage($file) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Erro no upload do arquivo'];
        }

        // Verificar tipo do arquivo
        if (!in_array($file['type'], self::$allowedTypes)) {
            return ['success' => false, 'message' => 'Tipo de arquivo não permitido. Use apenas JPG, PNG ou GIF'];
        }

        // Verificar tamanho
        if ($file['size'] > self::$maxSize) {
            return ['success' => false, 'message' => 'Arquivo muito grande. Máximo 5MB'];
        }

        // Criar diretório se não existir
        if (!is_dir(self::$uploadDir)) {
            mkdir(self::$uploadDir, 0755, true);
        }

        // Gerar nome único para o arquivo
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('item_', true) . '.' . strtolower($extension);
        $filepath = self::$uploadDir . $filename;

        // Mover arquivo
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return ['success' => true, 'filename' => $filename, 'message' => 'Imagem enviada com sucesso'];
        } else {
            return ['success' => false, 'message' => 'Erro ao salvar a imagem'];
        }
    }

    public static function validateImageUrl($url) {
        if (empty($url)) {
            return true; // URL vazia é permitida
        }

        // Verificar se é uma URL válida
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Verificar se a URL aponta para uma imagem
        $headers = @get_headers($url, 1);
        if (!$headers) {
            return false;
        }

        $contentType = isset($headers['Content-Type']) ? $headers['Content-Type'] : '';
        if (is_array($contentType)) {
            $contentType = $contentType[0];
        }

        return in_array($contentType, self::$allowedTypes);
    }

    public static function deleteImage($filename) {
        if (empty($filename)) {
            return true;
        }

        $filepath = self::$uploadDir . $filename;
        if (file_exists($filepath)) {
            return unlink($filepath);
        }

        return true; // Arquivo não existe, considerar como sucesso
    }

    public static function getImagePath($filename) {
        if (empty($filename)) {
            return null;
        }

        // Se for URL externa, retornar como está
        if (filter_var($filename, FILTER_VALIDATE_URL)) {
            return $filename;
        }

        // Se for arquivo local, retornar caminho completo
        return '/public/images/' . $filename;
    }
}
?>
