<?php

namespace Svakode\Svaflazz;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Svakode\Svaflazz\Exceptions\SvaflazzException;

class SvaflazzClient
{
    protected string $url = '';
    protected array $body = [];

    public function __construct(protected Client $client)
    {
        $this->body = [
            'username' => config('svaflazz.username'),
        ];
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function setBody(array $body): static
    {
        $this->body = array_merge($this->body, $body);
        return $this;
    }

    protected function url(): string
    {
        return config('svaflazz.base_url') . $this->url;
    }

    protected function options(): array
    {
        return ['json' => $this->body];
    }

    public function run(): mixed
    {
        try {
            $response = $this->client->post($this->url(), $this->options());
        } catch (RequestException $ex) {
            $response = $ex->getResponse();
            $body = json_decode($response->getBody());
            if (isset($body->data)) {
                throw SvaflazzException::requestFailed($body->data->rc, $body->data->message, $ex->getCode());
            } else {
                throw SvaflazzException::requestFailed('-', $ex->getMessage(), $ex->getCode());
            }
        }

        return json_decode($response->getBody());
    }
}
