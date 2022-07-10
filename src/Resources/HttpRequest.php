<?php

namespace Fedejuret\Andreani\Resources;

final class HttpRequest
{

    private $curl;
    private $baseUri;
    private $options;

    public function __construct(string $baseUri, array $options = [])
    {
        $this->baseUri = $baseUri;
        $this->options = $options;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * Send POST request
     * 
     * @param string $path Path to request
     * @param array $data Data to send
     * @param array $headers Headers to send
     * 
     * @return Response
     */
    public function post(string $path, array $data, array $headers = []): Response
    {

        if (!empty($headers)) {
            $this->options['headers'] = array_merge($this->options['headers'], $headers);
        }

        $this->curl = curl_init($this->baseUri . $path);

        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($data));

        return $this->makeRequest();
    }

    /**
     * Send GET request
     * 
     * @param string $path Path to request
     * @param ?array $query Query to send
     * @param array $headers Headers to send
     * 
     * @return Response
     */
    public function get(string $path, ?array $query = [], array $headers = []): Response
    {

        if (!empty($headers)) {
            $this->options['headers'] = array_merge($this->options['headers'], $headers);
        }

        $url = $this->baseUri . $path;

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $this->curl = curl_init($url);

        return $this->makeRequest();
    }

    /**
     * Get headers to send
     * 
     * @return array
     */
    public function getHeaders(): array
    {
        $headers = [];

        if (isset($this->options['headers'])) {
            foreach ($this->options['headers'] as $key => $value) {
                $headers[] = "{$key}: {$value}";
            }
        }

        return array_values($headers);
    }

    /**
     * Close curl connection
     * 
     * @return void
     */
    private function close(): void
    {
        curl_close($this->curl);
    }

    /**
     * @return Response
     */
    private function makeRequest(): Response
    {
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);

        if (isset($this->options['headers'])) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->getHeaders());
        }

        $response = curl_exec($this->curl);
        $code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        return new Response($code, $response);
    }
}
