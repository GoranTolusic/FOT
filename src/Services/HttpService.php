<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;

class HttpService
{
    protected HttpClientInterface $client;

    public function __construct()
    {
        $this->client = HttpClient::create([
            'base_uri' => $_ENV['API_URL'] ?? getenv('API_URL'),
            'timeout' => 10.0,
        ]);
    }

    protected function request(string $method, string $url, array $options = [])
    {
        $response = $this->client->request($method, $url, $options);
        try {
            $content = json_decode($response->getContent(), true);
            $status = $response->getStatusCode();
        } catch (\Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface $e) {
            $status = $e->getResponse()->getStatusCode();
            $content = json_decode($e->getResponse()->getContent(false), true);
        }

        return [
            'status' => $status,
            'body' => $content
        ];
    }

    public function getJson(string $url, array $options = []): array
    {
        return $this->request('GET', $url, $options);
    }

    public function postJson(string $url, array $options = []): array
    {
        return $this->request('POST', $url, $options);
    }

    public function putJson(string $url, array $options = []): array
    {
        return $this->request('PUT', $url, $options);
    }
}
