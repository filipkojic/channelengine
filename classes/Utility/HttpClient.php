<?php

namespace ChannelEngine\PrestaShop\Classes\Utility;

class HttpClient extends Singleton
{
    /**
     * Send a GET request to a given URL.
     *
     * @param string $url
     * @param array $headers
     * @return mixed
     */
    public function get($url, array $headers = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Dodaj timeout

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch) || $httpCode >= 400) {
            error_log('cURL error: ' . curl_error($ch)); // Loguj grešku
            throw new \Exception('HTTP error: ' . $httpCode . ' Response: ' . $response);
        }

        curl_close($ch);

        return json_decode($response, true); // Ili vrati raw odgovor
    }

    /**
     * Send a POST request to a given URL.
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function post($url, array $data = [], array $headers = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Dodaj timeout

        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch) || $httpCode >= 400) {
            error_log('cURL error: ' . curl_error($ch)); // Loguj grešku
            throw new \Exception('HTTP error: ' . $httpCode . ' Response: ' . $response);
        }

        curl_close($ch);

        return json_decode($response, true); // Ili vrati raw odgovor
    }

}

