<?php

namespace ChannelEngine\PrestaShop\Classes\Utility;

/**
 * HttpClient class responsible for handling HTTP requests.
 * This class extends the Singleton pattern to ensure only one instance of HttpClient is used across the application.
 */
class HttpClient extends Singleton
{
    /**
     * Sends a GET request and returns the response as an associative array.
     *
     * This method makes a GET request to the specified URL with optional headers.
     * It handles the response, checks for errors, and returns the JSON-decoded data.
     *
     * @param string $url The URL to send the GET request to.
     * @param array $headers Optional headers to include in the GET request.
     * @return array The decoded JSON response as an associative array.
     * @throws \Exception If there is a cURL error or a non-2xx HTTP status code.
     */
    public function get(string $url, array $headers = []): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch) || $httpCode >= 400) {
            error_log('cURL error: ' . curl_error($ch));
            throw new \Exception('HTTP error: ' . $httpCode . ' Response: ' . $response);
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Sends a POST request and returns the response as an associative array.
     *
     * This method makes a POST request to the specified URL with the given data and headers.
     * It handles the response, checks for errors, and returns the JSON-decoded data.
     *
     * @param string $url The URL to send the POST request to.
     * @param array $data The data to be sent in the POST request.
     * @param array $headers Optional headers to include in the POST request.
     * @return array The decoded JSON response as an associative array.
     * @throws \Exception If there is a cURL error or a non-2xx HTTP status code.
     */
    public function post(string $url, array $data = [], array $headers = []): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch) || $httpCode >= 400) {
            error_log('cURL error: ' . curl_error($ch));
            throw new \Exception('HTTP error: ' . $httpCode . ' Response: ' . $response);
        }

        curl_close($ch);

        return json_decode($response, true);
    }

}

