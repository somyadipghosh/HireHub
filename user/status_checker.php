<?php
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../vendor');
require_once 'autoload.php';
use WebSocket\Client;

class ServiceChecker
{
    private $services = [];

    public function checkSpeechAPI()
    {
        $apiUrl = "http://localhost:5001";
        try {
            $ch = curl_init($apiUrl . "/status");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return [
                'status' => ($httpCode === 200),
                'details' => json_decode($response, true) ?? []
            ];
        } catch (Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function checkWebSocket()
    {
        $wsUrl = "ws://localhost:8766";
        try {
            $client = new WebSocket\Client($wsUrl);
            $client->disconnect();
            return ['status' => true];
        } catch (Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function checkAll()
    {
        return [
            'speech_api' => $this->checkSpeechAPI(),
            'websocket' => $this->checkWebSocket()
        ];
    }
}

// CLI usage
if (PHP_SAPI === 'cli') {
    $checker = new ServiceChecker();
    $results = $checker->checkAll();

    echo "\n📊 Service Status Check\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Speech API:  " . ($results['speech_api']['status'] ? "✅" : "❌") . "\n";
    echo "WebSocket:   " . ($results['websocket']['status'] ? "✅" : "❌") . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━\n";
}
