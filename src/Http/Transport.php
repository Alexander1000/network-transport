<?php declare(strict_types = 1);

namespace NetworkTransport\Http;

class Transport implements \NetworkTransport\TransportInterface
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
    }
}
