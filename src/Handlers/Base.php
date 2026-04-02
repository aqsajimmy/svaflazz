<?php

namespace Svakode\Svaflazz\Handlers;

use Svakode\Svaflazz\SvaflazzClient;

class Base
{
    protected SvaflazzClient $client;

    /**
     * Base constructor.
     * @param SvaflazzClient $client
     */
    protected function __construct(SvaflazzClient $client)
    {
        $this->client = $client;
    }

    public function sign(string $keyword): string
    {
        return md5(config('svaflazz.username') . config('svaflazz.key') . $keyword);
    }

    public function perform(): mixed
    {
        return $this->client->run();
    }
}
