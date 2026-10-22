<?php
// includes/upload.php
// One server-side upload validator used by update_profile and smart_feature_add.
// The browser's "accept" attribute is only a hint; everything is re-checked here.

// Returns the stored relative path (e.g. "resume/ab12....pdf"), or null.
// On failure $error holds a message for the user. If no file was chosen,
// returns null with $error === '' so optional uploads are easy to handle.
function save_upload(array $file, array $allowed_ext, int $max_bytes, string $dir, string &$error): ?string
{
    $error = '';

    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        $error = 'The file is too large (max ' . round($max_bytes / 1048576) . 'MB).';
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        $error = 'The file could not be uploaded. Please try again.';
        return null;
    }
    if ($file['size'] > $max_bytes) {
        $error = 'The file is too large (max ' . round($max_bytes / 1048576) . 'MB).';
        return null;
    }

    // Extension check
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext, true)) {
        $error = 'Only ' . strtoupper(implode(', ', $allowed_ext)) . ' files are allowed.';
        return null;
    }

    // Content check: the real file type must match, not just the file name.
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $mime_ok = [
        'pdf'  => ['application/pdf'],
        'jpg'  => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png'  => ['image/png'],
    ];
    if (!isset($mime_ok[$ext]) || !in_array($mime, $mime_ok[$ext], true)) {
        $error = 'The file content does not match its extension.';
        return null;
    }

    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        $error = 'Could not save the file on the server.';
        return null;
    }

    // Random name: no clashes, and the visitor cannot choose the stored name.
    $name = bin2hex(random_bytes(8)) . '.' . $ext;
    $path = rtrim($dir, '/') . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $path)) {
        $error = 'Could not save the file on the server.';
        return null;
    }
    return $path;
}

// True when the whole request was rejected by PHP for being bigger than post_max_size
// (PHP then leaves $_POST and $_FILES empty, which would otherwise look like a blank form).
function post_too_large(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST'
        && empty($_POST) && empty($_FILES)
        && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0;
}

// Deletes a file that save_upload() created earlier (random 16-hex name inside an upload folder).
// Seed files such as img/kits/hg-freedom.jpg never match, so they can't be removed by accident.
function delete_stored_file(?string $path): void
{
    if ($path !== null && preg_match('#^(profile_images|resume|smart_feature_description|img/user_uploads|payment_proofs)/[0-9a-f]{16}\.(pdf|jpg|jpeg|png)$#', $path) && is_file($path)) {
        unlink($path);
    }
}
