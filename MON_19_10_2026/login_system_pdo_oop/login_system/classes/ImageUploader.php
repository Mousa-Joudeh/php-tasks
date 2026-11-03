<?php
declare(strict_types=1);

class ImageUploader
{
    private string $uploadDirectory;
    private string $relativeDirectory = 'uploads/products';

    public function __construct()
    {
        $this->uploadDirectory = dirname(__DIR__) . DIRECTORY_SEPARATOR . $this->relativeDirectory;
    }

    /**
     * Returns the relative path, null when no file was selected.
     * Throws RuntimeException for invalid uploads.
     */
    public function upload(array $file): ?string
    {
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('The image upload failed.');
        }
        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            throw new RuntimeException('Image must be 5 MB or smaller.');
        }

        $tmp = $file['tmp_name'] ?? '';
        if (!is_uploaded_file($tmp)) {
            throw new RuntimeException('Invalid uploaded file.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmp);
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];
        if (!isset($allowed[$mime]) || @getimagesize($tmp) === false) {
            throw new RuntimeException('Only JPG, PNG, GIF, and WEBP images are allowed.');
        }

        if (!is_dir($this->uploadDirectory) &&
            !mkdir($this->uploadDirectory, 0755, true) &&
            !is_dir($this->uploadDirectory)) {
            throw new RuntimeException('Could not create image upload folder.');
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
        $destination = $this->uploadDirectory . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($tmp, $destination)) {
            throw new RuntimeException('Could not save the uploaded image.');
        }
        return $this->relativeDirectory . '/' . $filename;
    }

    public function delete(?string $relativePath): void
    {
        if (!$relativePath || !str_starts_with($relativePath, $this->relativeDirectory . '/')) {
            return;
        }
        $filename = basename($relativePath);
        $path = $this->uploadDirectory . DIRECTORY_SEPARATOR . $filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
