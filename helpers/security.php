<?php
class Security
{
    public static function clean($data)
    {
        if (is_null($data)) return '';
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    public static function cleanInt($data)
    {
        return (int)$data;
    }

    public static function cleanMoney($data)
    {
        return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public static function validateFile($file)
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, Config::ALLOWED_MIME_TYPES)) {
            return false;
        }

        return true;
    }
}