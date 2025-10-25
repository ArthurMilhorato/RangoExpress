<?php
require_once __DIR__ . "/../helpers/ConstantsTokens.php";
class MercadoPago {
    private static $instance = null;
    private $access_token;
    private $base_url;

    private function __construct() {
        // Token de teste do Mercado Pago - SUBSTITUIR pelo token real
        $this->access_token = ConstantsURLs::$API_TOKEN;
        $this->base_url = ConstantsURLs::$URL_API_MERCADO_PAGO;
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new MercadoPago();
        }
        return self::$instance;
    }
    
    public function createPixPayment($amount, $description, $payer_email) {
        $data = [
            'transaction_amount' => floatval($amount),
            'description' => $description,
            'payment_method_id' => 'pix',
            'payer' => [
                'email' => $payer_email
            ]
        ];
        return $this->makeRequest(ConstantsURLs::$URL_API_MERCADO_PAGO_PIX, 'POST', $data);
    }
    
    public function getPaymentStatus($payment_id) {
        return $this->makeRequest("/v1/payments/{$payment_id}", 'GET');
    }
    
    private function makeRequest($endpoint, $method, $data = null) {
        $url = $this->base_url . $endpoint;
        
        $headers = [
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json',
            'X-Idempotency-Key: ' . uniqid()
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        if ($data && $method !== 'GET') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
        // Log para debug
        error_log("MercadoPago Request: $method $url");
        error_log("MercadoPago Response: $http_code - $response");
        if ($curl_error) {
            error_log("MercadoPago cURL Error: $curl_error");
        }
        
        return [
            'status_code' => $http_code,
            'response' => json_decode($response, true),
            'curl_error' => $curl_error
        ];
    }
}
?>