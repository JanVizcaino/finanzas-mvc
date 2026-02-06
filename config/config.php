<?php
class Config
{
    public static function getN8nUrl() {
        return getenv('N8N_WEBHOOK_URL') ?: 'http://host.docker.internal:5678/webhook/odin_mvc';
    }

    public static function getN8nSecret() {
        return getenv('N8N_API_SECRET') ?: 'odin_secure_token_v1_xyz987';
    }

    public static function getAppUrl() {
        return getenv('APP_BASE_URL') ?: 'http://localhost:8081';
    }

    const UPLOAD_DIR = '../uploads/';
    const LOG_FILE = '../logs/odin_errors.log';
    
    const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];
}