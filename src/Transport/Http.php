<?php declare(strict_types = 1);

namespace NetworkTransport\Transport;

class Http implements TransportInterface
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
