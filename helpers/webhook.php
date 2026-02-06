<?php
require_once '../config/Config.php';

class Webhook
{
    public static function send($data)
    {
        $jsonData = json_encode($data);
        
        $url = Config::getN8nUrl(); 
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 400);
        
        $secret = Config::getN8nSecret();

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
            'X-Odin-Token: ' . $secret 
        ]);
        
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}